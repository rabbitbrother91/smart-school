<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Attendencereports extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('file');
        $this->config->load("mailsms");
        $this->config->load("payroll");
        $this->load->library('mailsmsconf');
        $this->config_attendance = $this->config->item('attendence');
        $this->staff_attendance  = $this->config->item('staffattendance');
        $this->load->model("staffattendancemodel");
        $this->load->model("staff_model");
        $this->load->model("payroll_model");
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->search_type        = $this->customlib->get_searchtype();
    }

    public function attendance()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/attendance');
        $this->session->set_userdata('subsub_menu', '');
        $this->load->view('layout/header');
        $this->load->view('attendencereports/attendance');
        $this->load->view('layout/footer');
    }


    public function staffdaywiseattendancereport()
    {

        if (!$this->rbac->hasPrivilege('attendance_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/attendance');
        $this->session->set_userdata('subsub_menu', 'Reports/attendance/staffdaywiseattendancereport');
        $data['sch_setting'] = $this->sch_setting_detail;

        
        $staffRole                   = $this->staff_model->getStaffRole();
        $data["role"]                = $staffRole;
        $data["role_selected"]       = "";
        $attendencetypes             = $this->attendencetype_model->getStaffAttendanceType();
        $data['attendencetypeslist'] = $attendencetypes;      
        $data['date']           = "";
        $this->form_validation->set_rules('role', $this->lang->line('role'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == true) {

            $resultlist             = array();
            $role                  = $this->input->post('role');
            $date                  = $this->input->post('date');
            $attendance_mode                  = $this->input->post('attendance_mode');
            $data['role_selected']       = $role;
            $data['date_selected'] = $date;
            $resultlist                  = $this->staffattendancemodel->searchAttendenceUserTypeWithMode($role, date('Y-m-d', $this->customlib->datetostrtotime($date)),$attendance_mode);
            $data['resultlist']          = $resultlist;
        }
        $this->load->view('layout/header', $data);
        $this->load->view('attendencereports/staffdaywiseattendancereport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function daywiseattendancereport()
    {

        if (!$this->rbac->hasPrivilege('attendance_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/attendance');
        $this->session->set_userdata('subsub_menu', 'Reports/attendance/daywiseattendancereport');
        $data['sch_setting'] = $this->sch_setting_detail;
        $attendencetypes             = $this->attendencetype_model->getAttType();
        $data['attendencetypeslist'] = $attendencetypes;
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $data['class_id']       = "";
        $data['section_id']     = "";
        $data['date']           = "";
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == true) {

            $resultlist             = array();
            $class                  = $this->input->post('class_id');
            $section                = $this->input->post('section_id');
            $date                  = $this->input->post('date');
            $attendance_mode                  = $this->input->post('attendance_mode');
            $data['class_id']       = $class;
            $data['section_id']     = $section;
            $data['date_selected'] = $date;
            $attendencetypes             = $this->attendencetype_model->get();
            $data['attendencetypeslist'] = $attendencetypes;
            $resultlist                  = $this->stuattendence_model->searchAttendenceClassSectionWithMode($class, $section, date('Y-m-d', $this->customlib->datetostrtotime($date)),$attendance_mode);
            $data['resultlist']          = $resultlist;
        }



        $this->load->view('layout/header', $data);
        $this->load->view('attendencereports/daywiseattendancereport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function classattendencereport()
    {
        if (!$this->rbac->hasPrivilege('attendance_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/attendance');
        $this->session->set_userdata('subsub_menu', 'Reports/attendance/attendance_report');
        $attendencetypes             = $this->attendencetype_model->getAttType();
        $data['attendencetypeslist'] = $attendencetypes;

        $setting_data                 = $this->setting_model->get();
        $data['low_attendance_limit']     = $setting_data[0]['low_attendance_limit'];

        $data['title']               = 'Add Fees Type';
        $data['title_list']          = 'Fees Type List';
        $class                       = $this->class_model->get();
        $userdata                    = $this->customlib->getUserData();

        $role_id = $userdata["role_id"];

        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if ($userdata["class_teacher"] == 'yes') {
                $carray = array();
                $class  = array();
                $class  = $this->teacher_model->get_daywiseattendanceclass($userdata["id"]);
            }
        }
        $data['classlist'] = $class;
        $userdata          = $this->customlib->getUserData();

        $data['monthlist']      = $this->customlib->getMonthDropdown();
        $data['yearlist']       = $this->stuattendence_model->attendanceYearCount();
        $data['class_id']       = "";
        $data['section_id']     = "";
        $data['date']           = "";
        $data['month_selected'] = "";
        $data['year_selected']  = "";
        $data['sch_setting']    = $this->sch_setting_detail;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('month', $this->lang->line('month'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('attendencereports/classattendencereport', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $resultlist             = array();
            $class                  = $this->input->post('class_id');
            $section                = $this->input->post('section_id');
            $month                  = $this->input->post('month');
            $data['class_id']       = $class;
            $data['section_id']     = $section;
            $data['month_selected'] = $month;
            $studentlist            = $this->student_model->searchByClassSection($class, $section);
            $session_current        = $this->setting_model->getCurrentSessionName();
            $startMonth             = $this->setting_model->getStartMonth();
            $centenary              = substr($session_current, 0, 2); //2017-18 to 2017
            $year_first_substring   = substr($session_current, 2, 2); //2017-18 to 2017
            $year_second_substring  = substr($session_current, 5, 2); //2017-18 to 18
            $month_number           = date("m", strtotime($month));
            $year                   = $this->input->post('year');
            $data['year_selected']  = $year;
            if (!empty($year)) {

                $year = $this->input->post("year");
            } else {

                if ($month_number >= $startMonth && $month_number <= 12) {
                    $year = $centenary . $year_first_substring;
                } else {
                    $year = $centenary . $year_second_substring;
                }
            }

            $num_of_days        = cal_days_in_month(CAL_GREGORIAN, $month_number, $year);
            $attr_result        = array();
            $attendence_array   = array();
            $student_result     = array();
            $data['no_of_days'] = $num_of_days;
            $date_result        = array();
            for ($i = 1; $i <= $num_of_days; $i++) {
                $att_date           = $year . "-" . $month_number . "-" . sprintf("%02d", $i);
                $attendence_array[] = $att_date;

                $res            = $this->stuattendence_model->searchAttendenceReport($class, $section, $att_date);
                $student_result = $res;
                $s              = array();
                foreach ($res as $result_k => $result_v) {
                    $s[$result_v['student_session_id']] = $result_v;
                }
                $date_result[$att_date] = $s;
            }

            $monthAttendance = array();
            foreach ($res as $result_k => $result_v) {

                $date              = $year . "-" . $month;
                $newdate           = date('Y-m-d', strtotime($date));
                $monthAttendance[] = $this->stuMonthAttendance($newdate, 1, $result_v['student_session_id']);
            }

            $data['monthAttendance'] = $monthAttendance;
            $data['resultlist']       = $date_result;
            $data['attendence_array'] = $attendence_array;
            $data['student_array']    = $student_result;

            $this->load->view('layout/header', $data);
            $this->load->view('attendencereports/classattendencereport', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function stuMonthAttendance($st_month, $no_of_months, $student_id)
    {
        $record = array();
        $r     = array();
        $month = date('m', strtotime($st_month));
        $year  = date('Y', strtotime($st_month));
        foreach ($this->config_attendance as $att_key => $att_value) {
            $s = $this->stuattendence_model->count_attendance_obj($month, $year, $student_id, $att_value);

            $attendance_key = $att_key;
            $r[$attendance_key] = $s;
        }

        $record[$student_id] = $r;
        return $record;
    }

    public function attendancereport()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/attendance');
        $this->session->set_userdata('subsub_menu', 'Reports/attendence/attendancereport');
        $data['searchlist']      = $this->search_type;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $class                   = $this->input->post('class_id');
        $section                 = $this->input->post('section_id');
        $data['class_id']        = $class;
        $data['section_id']      = $section;
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $searchterm              = '';
        $condition               = "";
        $date_condition          = "";

        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $between_date        = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $search_type = $_POST['search_type'];
        } else {
            $between_date        = $this->customlib->get_betweendate('this_week');
            $data['search_type'] = '';
        }

        $from_date = date('Y-m-d', strtotime($between_date['from_date']));
        $to_date   = date('Y-m-d', strtotime($between_date['to_date']));
        $dates     = array();
        $off_date  = array();
        $current   = strtotime($from_date);
        $last      = strtotime($to_date);

        while ($current <= $last) {

            $date    = date('Y-m-d', $current);
            $day     = date("D", strtotime($date));
            $holiday = $this->stuattendence_model->checkholidatbydate($date);

            if ($day == 'Sun' || $holiday > 0) {
                $off_date[] = $date;
            } else {
                $dates[] = $date;
            }

            $current = strtotime('+1 day', $current);
        }

        $data['filter']          = date($this->customlib->getSchoolDateFormat(), strtotime($from_date)) . " To " . date($this->customlib->getSchoolDateFormat(), strtotime($to_date));
        $data['attendance_type'] = $this->attendencetype_model->getstdAttType('2');
        $this->form_validation->set_rules('attendance_type', $this->lang->line('attendance_type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

            $this->load->view('layout/header', $data);
            $this->load->view('attendencereports/stuattendance', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $data['attendance_type_id'] = $attendance_type_id = $this->input->post('attendance_type');
            $condition .= " and `student_attendences`.`attendence_type_id`=" . $this->input->post('attendance_type');
            foreach ($dates as $key => $value) {
            }

            if ($data['class_id'] != '') {
                $condition .= ' and class_id=' . $data['class_id'];
            }
            $condition .= " and date_format(student_attendences.date,'%Y-%m-%d') between '" . $from_date . "' and '" . $to_date . "'";
            if ($data['section_id'] != '') {
                $condition .= ' and section_id=' . $data['section_id'];
            }

            $data['student_attendences'] = $this->stuattendence_model->student_attendences($condition, $date_condition);

            $attd = array();

            foreach ($data['student_attendences'] as $value) {
                $std_id          = $value['id'];
                $attd[$std_id][] = $value;
            }

            foreach ($attd as $key => $att_value) {
                $all_week = 1;
                foreach ($att_value as $value) {

                    if (in_array($value['date'], $off_date)) {
                    } else {
                        if (in_array($value['date'], $dates)) {
                            //echo "Match found";
                        } else {
                            $all_week = 0;
                        }
                    }
                }
                if ($all_week == 1) {
                    $fdata[] = $att_value[0];
                }
            }

            $dates = " '" . $from_date . "' and '" . $to_date . "'";

            $this->load->view('layout/header', $data);
            $this->load->view('attendencereports/stuattendance', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function daily_attendance_report()
    {
        $data = array();
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/attendance');
        $this->session->set_userdata('subsub_menu', 'Reports/attendance/daily_attendance_report');
        $date         = "";
        $data['date'] = "";
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $date         = " and student_attendences.date='" . date('Y-m-d') . "'";
            $data['date'] = date($this->customlib->getSchoolDateFormat());
        } else {
            $date         = " and student_attendences.date='" . date('Y-m-d', $this->customlib->datetostrtotime($_POST['date'])) . "'";
            $data['date'] = date($this->customlib->getSchoolDateFormat(), $this->customlib->datetostrtotime($_POST['date']));
        }

        $resultlist     = array();
        $data['result'] = $this->stuattendence_model->get_attendancebydate($date);
        if (!empty($data['result'])) {
            $all_student = $all_present = $all_absent = 0;
            foreach ($data['result'] as $key => $value) {
                $total_present = $value->present + $value->excuse + $value->late + $value->half_day;
                $total_student = $total_present + $value->absent;
                if ($total_present > 0) {
                    $presnt_percent = round(($total_present / $total_student) * 100);
                } else {
                    $presnt_percent = 0;
                }
                if ($value->absent > 0) {
                    $presnt_absent = round(($value->absent / $total_student) * 100);
                } else {
                    $presnt_absent = 0;
                }
                $all_student += $total_student;
                $all_present += $total_present;
                $all_absent += $value->absent;

                $data['resultlist'][] = array('class_section' => $value->class_name . " (" . $value->section_name . ")", 'total_present' => $total_present, 'total_absent' => $value->absent, 'present_percent' => $presnt_percent . "%", 'absent_persent' => $presnt_absent . "%");
                # code...
            }
            $data['all_student'] = $all_student;
            $data['all_present'] = $all_present;
            $data['all_absent']  = $all_absent;
            if ($all_student > 0) {
                $data['all_present_percent'] = round(($data['all_present'] / $data['all_student']) * 100) . "%";
                $data['all_absent_percent']  = round(($data['all_absent'] / $data['all_student']) * 100) . "%";
            } else {
                $data['all_present_percent'] = "0%";
                $data['all_absent_percent']  = "0%";
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('attendencereports/daily_attendance_report', $data);
        $this->load->view('layout/footer', $data);
    }

    public function staffattendancereport()
    {
        if (!$this->rbac->hasPrivilege('staff_attendance_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/attendance');
        $this->session->set_userdata('subsub_menu', 'Reports/attendance/staff_attendance_report');
        $attendencetypes             = $this->staffattendancemodel->getStaffAttendanceType();
        $data['attendencetypeslist'] = $attendencetypes;
        $staffRole                   = $this->staff_model->getStaffRole();
        $data["role"]                = $staffRole;
        $data['title']               = 'Attendance Report';
        $data['title_list']          = 'Attendance';
        $data['monthlist']           = $this->customlib->getMonthDropdown();
        $data['yearlist']            = $this->staffattendancemodel->attendanceYearCount();
        $data['date']                = "";
        $data['month_selected']      = "";
        $data["role_selected"]       = "";
        $role                        = $this->input->post("role");
        $this->form_validation->set_rules('month', $this->lang->line('month'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('attendencereports/staffattendancereport', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $resultlist             = array();
            $month                  = $this->input->post('month');
            $searchyear             = $this->input->post('year');
            $data['month_selected'] = $month;
            $data["role_selected"]  = $role;
            $stafflist              = $this->staff_model->getEmployee($role);
            $session_current        = $this->setting_model->getCurrentSessionName();
            $startMonth             = $this->setting_model->getStartMonth();
            $centenary              = substr($session_current, 0, 2); //2017-18 to 2017
            $year_first_substring   = substr($session_current, 2, 2); //2017-18 to 2017
            $year_second_substring  = substr($session_current, 5, 2); //2017-18 to 18
            $month_number           = date("m", strtotime($month));

            if ($month_number >= $startMonth && $month_number <= 12) {
                $year = $centenary . $year_first_substring;
            } else {
                $year = $centenary . $year_second_substring;
            }

            $num_of_days        = cal_days_in_month(CAL_GREGORIAN, $month_number, $searchyear);
            $attr_result        = array();
            $attendence_array   = array();
            $student_result     = array();
            $data['no_of_days'] = $num_of_days;
            $date_result        = array();
            $monthAttendance    = array();

            for ($i = 1; $i <= $num_of_days; $i++) {
                $att_date           = $searchyear . "-" . $month_number . "-" . sprintf("%02d", $i);
                $attendence_array[] = $att_date;

                $res = $this->staffattendancemodel->searchAttendanceReport($role, $att_date);

                $student_result = $res;
                $s              = array();

                foreach ($res as $result_k => $result_v) {
                    $date    = $searchyear . "-" . $month;
                    $newdate = date('Y-m-d', strtotime($date));
                    $s[$result_v['id']] = $result_v;
                }

                $date_result[$att_date] = $s;
            }

            foreach ($res as $result_k => $result_v) {
                $date              = $searchyear . "-" . $month;
                $newdate           = date('Y-m-d', strtotime($date));
                $monthAttendance[] = $this->monthAttendance($newdate, 1, $result_v['id']);
            }

            $data['monthAttendance'] = $monthAttendance;
            $data['resultlist']      = $date_result;
            if (!empty($searchyear)) {
                $data['attendence_array'] = $attendence_array;
                $data['student_array']    = $student_result;
            } else {
                $data['attendence_array'] = array();
                $data['student_array']    = array();
            }

            $this->load->view('layout/header', $data);
            $this->load->view('attendencereports/staffattendancereport', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function monthAttendance($st_month, $no_of_months, $emp)
    {
        $this->load->model("payroll_model");
        $record = array();
        $r     = array();
        $month = date('m', strtotime($st_month));
        $year  = date('Y', strtotime($st_month));
        foreach ($this->staff_attendance as $att_key => $att_value) {
            $s = $this->payroll_model->count_attendance_obj($month, $year, $emp, $att_value);
            $r[$att_key] = $s;
        }

        $record[$emp] = $r;
        return $record;
    }

    public function biometric_attlog($offset = 0)
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/attendance');
        $this->session->set_userdata('subsub_menu', 'Reports/attendence/biometric_attlog');
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;

        $config['total_rows'] = $this->stuattendence_model->biometric_attlogcount();

        $config['base_url']    = base_url() . "report/biometric_attlog";
        $config['per_page']    = 100;
        $config['uri_segment'] = '3';

        $config['full_tag_open']  = '<div class="pagination"><ul>';
        $config['full_tag_close'] = '</ul></div>';

        $config['first_link']      = '« First';
        $config['first_tag_open']  = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';

        $config['last_link']      = 'Last »';
        $config['last_tag_open']  = '<li class="next page">';
        $config['last_tag_close'] = '</li>';

        $config['next_link']      = 'Next →';
        $config['next_tag_open']  = '<li class="next page">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link']      = '← Previous';
        $config['prev_tag_open']  = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open']  = '<li ><a href="" class="active">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open']  = '<li class="page">';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $query = $this->stuattendence_model->biometric_attlog(100, $this->uri->segment(3));

        $data['resultlist'] = $query;
        $this->load->view('layout/header', $data);
        $this->load->view('attendencereports/biometric_attlog', $data);
        $this->load->view('layout/footer', $data);
    }

    public function reportbymonthstudent()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/attendance');
        $this->session->set_userdata('subsub_menu', 'Reports/attendence/reportbymonthstudent');

        $data                = array();
        $class               = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']   = $class;
        $sch_setting         = $this->setting_model->getSetting();
        $data['sch_setting'] = $sch_setting;
        $data['monthlist']   = $this->customlib->getMonthNoDropdown($sch_setting->start_month);

        $data['student_id'] = "";
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('student_id', $this->lang->line('student'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('month', $this->lang->line('month'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == true) {
            $attendencetypes             = $this->attendencetype_model->get();
            $data['attendencetypeslist'] = $attendencetypes;
            $student_id                  = $data['student_id']                  = $this->input->post('student_id');
            $class_id                    = $this->input->post('class_id');
            $section_id                  = $this->input->post('section_id');
            $month                       = $this->input->post('month');
            $subject_id                  = $this->input->post('subject_id');
            $month_data                  = sessionMonthDetails($sch_setting->session, $sch_setting->start_month, $month);

            $attr_result        = array();
            $attendence_array   = array();
            $student_result     = array();
            $data['no_of_days'] = $month_data['total_days'];
            $date_result        = array();
            $from_date          = 1;

            $resultlist = $this->studentsubjectattendence_model->getStudentMontlyAttendence($class_id, $section_id, $month_data['month_start'], $month_data['month_end'], $student_id, $subject_id);

            $data['resultlist'] = $resultlist;
        }
        $this->load->view('layout/header', $data);
        $this->load->view('attendencereports/reportbymonthstudent', $data);
        $this->load->view('layout/footer', $data);
    }

    public function reportbymonth()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/attendence');
        $this->session->set_userdata('subsub_menu', 'Reports/attendence/reportbymonth');

        $data              = array();
        $class             = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist'] = $class;

        $sch_setting         = $this->setting_model->getSetting();
        $data['sch_setting'] = $sch_setting;

        $data['monthlist'] = $this->customlib->getMonthNoDropdown($sch_setting->start_month);

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('month', $this->lang->line('month'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == true) {
            $attendencetypes             = $this->attendencetype_model->get();
            $data['attendencetypeslist'] = $attendencetypes;
            $subject_id                  = $this->input->post('subject_id');
            $class_id                    = $this->input->post('class_id');
            $section_id                  = $this->input->post('section_id');
            $month                       = $this->input->post('month');
            $year                        = $this->input->post('year');
            $month_data                  = sessionMonthDetails($sch_setting->session, $sch_setting->start_month, $month);

            $attr_result        = array();
            $attendence_array   = array();
            $student_result     = array();
            $data['no_of_days'] = $month_data['total_days'];
            $date_result        = array();

            $resultlist = $this->studentsubjectattendence_model->getStudentsMontlyAttendence($class_id, $section_id, $month_data['month_start'], $month_data['month_end'], $subject_id);

            $data['resultlist'] = $resultlist;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('attendencereports/reportbymonth', $data);
        $this->load->view('layout/footer', $data);
    }
}
