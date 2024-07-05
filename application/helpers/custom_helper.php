<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('is_active_2fa')) {

    function is_active_2fa()
    {
        $CI = &get_instance();
        $CI->load->model(array("google_authenticator/gauth_model"));
        return $CI->gauth_model->is_enable();
    }
}

if (!function_exists('is_subAttendence')) {

    function is_subAttendence()
    {

        $CI = &get_instance();
        $CI->db->select(
            'sch_settings.id,sch_settings.lang_id,sch_settings.attendence_type,sch_settings.is_rtl,sch_settings.timezone,
          sch_settings.name,sch_settings.email,sch_settings.biometric,sch_settings.biometric_device,sch_settings.phone,languages.language,
          sch_settings.address,sch_settings.dise_code,sch_settings.date_format,sch_settings.currency,sch_settings.currency_symbol,sch_settings.start_month,sch_settings.session_id,sch_settings.image,sch_settings.theme,sessions.session'
        );
        $CI->db->from('sch_settings');
        $CI->db->join('sessions', 'sessions.id = sch_settings.session_id');
        $CI->db->join('languages', 'languages.id = sch_settings.lang_id');
        $CI->db->order_by('sch_settings.id');
        $query  = $CI->db->get();
        $result = $query->row();

        if ($result->attendence_type) {
            return true;
        }
        return false;
    }
}

function get_currency_list()
{

    $CI = &get_instance();
    $CI->db->select('currencies.*,IFNULL(sch_settings.currency, 0) as `currency_id`')->from('currencies');
    $CI->db->join('sch_settings', 'currencies.id=sch_settings.currency', 'left');
    $CI->db->where('currencies.is_active', 1);
    $CI->db->order_by('id');
    $query = $CI->db->get();
    return $query->result();
}

function calculatePercent($amount, $percent)
{
    $ci = &get_instance();
    $ci->load->helper('custom');
    $percent_amt = 0;
    if ($amount != "") {
        $percent_amt = ($amount * $percent) / 100;
        $percent_amt = amountFormat($percent_amt);
    }
    return $percent_amt;
}

if (!function_exists('check_lock_enabled')) {

    function check_lock_enabled()
    {
        $CI = &get_instance();

        if ($CI->session->has_userdata('current_class')) {

            $CI->db->select('is_student_feature_lock,lock_grace_period');
            $CI->db->from('sch_settings');
            $query  = $CI->db->get();
            $result = $query->row();
            if ($result->is_student_feature_lock) {
                $lock_grace_period = $result->lock_grace_period;
                $date              = date('Y-m-d', strtotime(date("Y-m-d")) - (86400 * $lock_grace_period));

                $student_current_class = $CI->customlib->getStudentCurrentClsSection();

                $student_due_fee = $CI->studentfeemaster_model->getDueFeesByStudent($student_current_class->student_session_id, $date);

                if (!empty($student_due_fee)) {
                    foreach ($student_due_fee as $result_key => $result_value) {

                        if ($result_value->is_system == 0) {
                            $student_due_fee[$result_key]->{'amount'} = $result_value->fee_amount;
                        }
                        $fee_paid     = 0;
                        $fee_discount = 0;
                        $fee_fine     = 0;

                        $feetype_balance = 0;
                        if (isJSON($result_value->amount_detail)) {
                            $fee_deposits = json_decode(($result_value->amount_detail));
                            foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                $fee_paid     = $fee_paid + $fee_deposits_value->amount;
                                $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                                $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
                            }
                        }

                        $feetype_balance = ($result_value->amount + $result_value->fine_amount) - ($fee_paid + $fee_fine + $fee_discount);
                        if ($feetype_balance > 0) {

                            return true;
                        }
                    }
                }

                $student_id = $CI->customlib->getStudentSessionUserID();

                $student = $CI->student_model->getStudentByClassSectionID($student_current_class->class_id, $student_current_class->section_id, $student_id);

                $transport_fees = $CI->studentfeemaster_model->getDueTransportFeeByStudent($student['student_session_id'], $student['route_pickup_point_id'], $date);

                if (!empty($transport_fees)) {
                    foreach ($transport_fees as $tran_fee_key => $tran_fee_value) {
                        $fee_paid         = 0;
                        $fee_discount     = 0;
                        $fee_fine         = 0;
                        $fees_fine_amount = 0;
                        $feetype_balance  = 0;
                        if (isJSON($tran_fee_value->amount_detail)) {
                            $fee_deposits = json_decode(($tran_fee_value->amount_detail));
                            foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                $fee_paid     = $fee_paid + $fee_deposits_value->amount;
                                $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                                $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
                            }
                        }
                        $fees_fine_amount = is_null($tran_fee_value->fine_percentage) ? $tran_fee_value->fine_amount : percentageAmount($tran_fee_value->fees, $tran_fee_value->fine_percentage);

                        $feetype_balance = ($tran_fee_value->fees + $fees_fine_amount) - ($fee_paid + $fee_discount + $fee_fine);

                        if ($feetype_balance > 0) {

                            return true;
                        }
                    }
                }

                return false;
            }
        }

        return false;
    }
}

if (!function_exists('get_subjects')) {

    function get_subjects($class_batch_id)
    {
        $CI = &get_instance();
        $CI->db->select('class_batch_subjects.*,subjects.name as `subject_name`');
        $CI->db->from('class_batch_subjects');
        $CI->db->join('subjects', 'subjects.id = class_batch_subjects.subject_id');
        $CI->db->where('class_batch_id', $class_batch_id);
        $CI->db->order_by('class_batch_subjects.id', 'asc');

        $query         = $CI->db->get();
        $return_string = '<option value="">--Select--</option>';
        $result        = $query->result();
        if (!empty($result)) {
            foreach ($result as $result_key => $result_value) {
                $return_string .= '<option value="' . $result_value->id . '">' . $result_value->subject_name . '</option>';
            }
        }
        return $return_string;
    }
}

if (!function_exists('readmorelink')) {

    function readmorelink($string, $link = false)
    {
        $string = strip_tags($string);
        if (strlen($string) > 150) {

            // truncate string
            $stringCut = substr($string, 0, 150);
            $endPoint  = strrpos($stringCut, ' ');

            //if the string doesn't contain any space then it will cut without word basis.
            $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
            $string .= ($link) ? "<a href='" . $link . "' target='_blank'>Read more...</a>" : "....";
        }

        return $string;
    }
}

if (!function_exists('readmorelinkUser')) {

    function readmorelinkUser($string, $link = false)
    {
        $string = strip_tags($string);
        if (strlen($string) > 150) {

            // truncate string
            $stringCut = substr($string, 0, 150);
            $endPoint  = strrpos($stringCut, ' ');

            //if the string doesn't contain any space then it will cut without word basis.
            $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);

            $string .= ($link) ? "<a href='#" . $link . "' data-toggle='collapse' aria-expanded='false' aria-controls='" . $link . "' >Read more...</a>" : "....";
        }

        return $string;
    }
}

function expensegraphColors($color = null)
{
    $colors = array(
        '1' => "#9966ff",
        '2' => "#36a2eb",
        '3' => "#ff9f40",
        '4' => "#715d20",
        '5' => "#c9cbcf",
        '6' => "#4bc0c0",
        '7' => "#ffcd56",
        '8' => "#66aa18",
    );
    if ($color == null) {
        return $colors;
    } else {
        return $colors[$color];
    }
}

function incomegraphColors($color = null)
{
    $colors = array(
        '1' => "#66aa18",
        '2' => "#ffcd56",
        '3' => "#4bc0c0",
        '4' => "#c9cbcf",
        '5' => "#715d20",
        '6' => "#ff9f40",
        '7' => "#36a2eb",
        '8' => "#9966ff",
    );
    if ($color == null) {
        return $colors;
    } else {
        return $colors[$color];
    }
}

function isJSON($string)
{
    return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}

function currentTime()
{
    return date("d/m/y : H:i:s", time());
}

function markSheetDigit()
{
    $number   = 190908100.25;
    $no       = floor($number);
    $point    = round($number - $no, 2) * 100;
    $hundred  = null;
    $digits_1 = strlen($no);
    $i        = 0;
    $str      = array();
    $words    = array(
        '0' => '', '1'          => 'one', '2'       => 'two',
        '3'                   => 'three', '4'     => 'four', '5'      => 'five', '6' => 'six',
        '7'                   => 'seven', '8'     => 'eight', '9'     => 'nine',
        '10'                  => 'ten', '11'      => 'eleven', '12'   => 'twelve',
        '13'                  => 'thirteen', '14' => 'fourteen',
        '15'                  => 'fifteen', '16'  => 'sixteen', '17'  => 'seventeen',
        '18'                  => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
        '30'                  => 'thirty', '40'   => 'forty', '50'    => 'fifty',
        '60'                  => 'sixty', '70'    => 'seventy',
        '80'                  => 'eighty', '90'   => 'ninety'
    );
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number  = floor($no % $divider);
        $no      = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural  = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str[]   = ($number < 21) ? $words[$number] .
                " " . $digits[$counter] . $plural . " " . $hundred :
                $words[floor($number / 10) * 10]
                . " " . $words[$number % 10] . " "
                . $digits[$counter] . $plural . " " . $hundred;
        } else {
            $str[] = null;
        }
    }
    $str    = array_reverse($str);
    $result = implode('', $str);
    $points = ($point) ?
        "." . $words[$point / 10] . " " .
        $words[$point = $point % 10] : '';
    return $result . $points;
}

function getSecondsFromHMS($time)
{
    $timeArr = array_reverse(explode(":", $time));
    $seconds = 0;
    foreach ($timeArr as $key => $value) {
        if ($key > 2) {
            break;
        }

        $seconds += pow(60, $key) * $value;
    }
    return $seconds;
}

function getHMSFromSeconds($seconds)
{
    $t = round($seconds);
    return sprintf('%02d:%02d:%02d', ($t / 3600), ($t / 60 % 60), $t % 60);
}

function array_insert(&$array, $position, $insert)
{
    if (is_int($position)) {
        array_splice($array, $position, 0, $insert);
    } else {
        $pos   = array_search($position, array_keys($array));
        $array = array_merge(
            array_slice($array, 0, $pos),
            $insert,
            array_slice($array, $pos)
        );
    }
}

function two_digit_float($number)
{

    if ($number != "") {
        $number = number_format($number, 2, ".", "");
    }
    return $number;
}


function sessionMonthDetails($session, $start_month, $month)
{

    list($a, $b)  = explode('-', $session);
    $Current_year = $a;
    if (strlen($b) == 2) {
        $Next_year = substr($a, 0, 2) . $b;
    } else {
        $Next_year = $b;
    }
    $session_start_month_date = $Next_year . "-" . sprintf('%02d', $month) . "-01";
    if ($start_month <= $month) {
        $session_start_month_date = $Current_year . "-" . sprintf('%02d', $month) . "-01";
    }
    return ['month_start' => $session_start_month_date, 'month_end' => date('Y-m-t', strtotime($session_start_month_date)), 'total_days' => date('t', strtotime($session_start_month_date))];
}


function sessionYearDetails($session, $start_month)
{

    list($a, $b)  = explode('-', $session);
    $Current_year = $a;
    if (strlen($b) == 2) {
        $Next_year = substr($a, 0, 2) . $b;
    } else {
        $Next_year = $b;
    }


    if ($start_month == 1) {
        $endmonth = 12;
    } else {
        $endmonth = $start_month - 1;
    }

    $session_start_month_date = $Current_year . "-" . sprintf('%02d', $start_month) . "-01";
    $session_end_month_date = $Next_year . "-" . sprintf('%02d', $endmonth) . "-01";
    return ['month_start' => $session_start_month_date, 'month_end' => date('Y-m-t', strtotime($session_end_month_date))];
}

function amountFormat($amount)
{
    $CI              = &get_instance();
    $currency_format = $CI->customlib->getCurrencyFormat();
    $currency_price = $CI->customlib->getSchoolCurrencyPrice();
    $amount = ($amount * $currency_price);
    if ($currency_format == "#,###.##") {
        $return_amt = number_format($amount, 2, '.', ',');
    } elseif ($currency_format == "#.###,##") {
        $return_amt = number_format($amount, 2, ',', '.');
    } elseif ($currency_format == "# ###.##") {
        $return_amt = number_format($amount, 2, '.', ' ');
    } elseif ($currency_format == "#.###.##") {
        $return_amt = number_format($amount, 2, '.', '.');
    } elseif ($currency_format == "#,###.###") {
        $return_amt = number_format($amount, 3, '.', ',');
    } elseif ($currency_format == "####.##") {
        $return_amt = number_format($amount, 2, '.', '');
    } elseif ($currency_format == "#,##,###.##") {
        $return_amt = indian_money_format($amount);
    }
    return $return_amt;
}


function convertBaseAmountCurrencyFormat($amount)
{
    $CI              = &get_instance();
    $currency_price  = $CI->customlib->getSchoolCurrencyPrice();
    $amount = ($amount * $currency_price);
    return two_digit_float($amount);
}


function convertCurrencyFormatToBaseAmount($amount)
{
    $CI              = &get_instance();
    $currency_price  = $CI->customlib->getSchoolCurrencyPrice();
    $amount = floatval($amount / $currency_price);
    return $amount;
}

function indian_money_format($num)
{
    $explrestunits = "";
    $num           = preg_replace('/,+/', '', $num);
    $words         = explode(".", $num);
    $des           = "00";
    if (count($words) <= 2) {
        $num = $words[0];
        if (count($words) >= 2) {
            $des = $words[1];
        }
        if (strlen($des) < 2) {
            $des = "$des";
        } else {
            $des = substr($des, 0, 2);
        }
    }
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit   = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end
            if ($i == 0) {
                $explrestunits .= (int) $expunit[$i] . ","; // if is first value , convert into integer
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return "$thecash.$des"; // writes the final format where $currency is the currency symbol.

}

function percentageAmount($amount, $percentage)
{
    return number_format((float) ($amount * ($percentage / 100)), 2, '.', '');
}

if (!function_exists('custom_url')) {

    function custom_url()
    {

        $CI           = &get_instance();
        $session_data = $CI->session->userdata('admin');
        return $session_data['base_url'];
    }
}

if (!function_exists('dir_path')) {

    function dir_path()
    {

        $CI           = &get_instance();
        $session_data = $CI->session->userdata('admin');
        return $session_data['folder_path'];
    }
}

if (!function_exists('empty2null')) {

    function empty2null($v)
    {
        return empty($v) ? null : $v;
    }
}

if (!function_exists('multiKeyExists')) {

    function multiKeyExists(array $array, $key, $value)
    {
        if (!empty($array)) {

            foreach ($array as $array_key => $item) {
                if (isset($item[$key]) && $item[$key] == $value) {
                    return $array_key;
                }
            }

            return -1;
        }

        return -1;
    }
}

if (!function_exists('random_string')) {

    function random_string()
    {
        return strtotime('Y-m-d H:i:s');
    }
}

if (!function_exists('img_time')) {

    function img_time()
    {
        return "?" . time();
    }
}

if (!function_exists('format_file_size')) {

    function format_file_size($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}

function IsNullOrEmptyString($str)
{
    return ($str === null || trim($str) === '');
}

function getPercent($total, $obtain)
{
    $ci = &get_instance();

    $percent = 0;


    if ($total != "" && $total > 0) {
        $percent = ($obtain * 100) / $total;
    }
    return number_format((float)$percent, 2, '.', '');
}



function getIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR']    = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }

    return $ip;
}


function getAgentDetail()
{
    $CI           = &get_instance();

    $CI->load->library('user_agent');

    $user_agent = "";
    if ($CI->agent->is_mobile('iphone')) {
        $user_agent .= "Iphone | ";
    } else if ($CI->agent->is_mobile()) {
        $user_agent .= "Mobile | ";
    } else {
        $user_agent .= "Desktop | ";
    }


    if ($CI->agent->is_browser()) {

        $agent = $CI->agent->browser() . ' ' . $CI->agent->version();
    } elseif ($CI->agent->is_robot()) {
        $agent = $CI->agent->robot();
    } elseif ($CI->agent->is_mobile()) {

        $agent = $CI->agent->mobile();
    } else {
        $agent = 'Unidentified User Agent';
    }



     $user_agent .= $CI->agent->platform(). " | ";
     $user_agent .= $agent;
    return  $user_agent;
}
