<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Staff extends Admin_Controller
{

    public $sch_setting_detail = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->config->load("payroll");
        $this->config->load("app-config");
        $this->load->library('Enc_lib');
        $this->load->library('mailsmsconf');
        $this->load->model("staff_model");
        $this->load->library('encoding_lib');
        $this->load->model("leaverequest_model");
        $this->contract_type      = $this->config->item('contracttype');
        $this->marital_status     = $this->config->item('marital_status');
        $this->staff_attendance   = $this->config->item('staffattendance');
        $this->payroll_status     = $this->config->item('payroll_status');
        $this->payment_mode       = $this->config->item('payment_mode');
        $this->status             = $this->config->item('status');
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('staff', 'can_view')) {
            access_denied();
        }

        $data['title']  = $this->lang->line('staff_list');
        $data['fields'] = $this->customfield_model->get_custom_fields('staff', 1);
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/staff');
        $search             = $this->input->post("search");
        $resultlist         = $this->staff_model->searchFullText("", 1);
        $data['resultlist'] = $resultlist;
        $staffRole          = $this->staff_model->getStaffRole();
        $data["role"]       = $staffRole;
        $data["role_id"]    = "";
        $search_text        = $this->input->post('search_text');
        if (isset($search)) {
            if ($search == 'search_filter') {
                $this->form_validation->set_rules('role', $this->lang->line('role'), 'trim|required|xss_clean');
                if ($this->form_validation->run() == false) {
                    $data["resultlist"] = array();
                } else {
                    $data['searchby']    = "filter";
                    $role                = $this->input->post('role');
                    $data['employee_id'] = $this->input->post('empid');
                    $data["role_id"]     = $role;
                    $data['search_text'] = $this->input->post('search_text');
                    $resultlist          = $this->staff_model->getEmployee($role, 1);
                    $data['resultlist']  = $resultlist;
                }
            } else if ($search == 'search_full') {
                $data['searchby']    = "text";
                $data['search_text'] = trim($this->input->post('search_text'));
                $resultlist          = $this->staff_model->searchFullText($search_text, 1);
                $data['resultlist']  = $resultlist;
                $data['title']       = $this->lang->line('search_details') . ': ' . $data['search_text'];
            }
        }

        $this->load->view('layout/header');
        $this->load->view('admin/staff/staffsearch', $data);
        $this->load->view('layout/footer');
    }

    public function disablestafflist()
    {
        if (!$this->rbac->hasPrivilege('disable_staff', 'can_view')) {
            access_denied();
        }

        if (isset($_POST['role']) && $_POST['role'] != '') {
            $data['search_role'] = $_POST['role'];
        } else {
            $data['search_role'] = "";
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/staff/disablestafflist');
        $data['title'] = 'Staff Search';
        $staffRole     = $this->staff_model->getStaffRole();

        $data["role"]       = $staffRole;
        $search             = $this->input->post("search");
        $search_text        = $this->input->post('search_text');
        $resultlist         = $this->staff_model->searchFullText('', 0);
        $data['resultlist'] = $resultlist;

        if (isset($search)) {
            if ($search == 'search_filter') {
                $this->form_validation->set_rules('role', $this->lang->line('role'), 'trim|required|xss_clean');
                if ($this->form_validation->run() == false) {
                    $resultlist         = array();
                    $data['resultlist'] = $resultlist;
                } else {
                    $data['searchby']    = "filter";
                    $role                = $this->input->post('role');
                    $data['employee_id'] = $this->input->post('empid');

                    $data['search_text'] = $this->input->post('search_text');
                    $resultlist          = $this->staff_model->getEmployee($role, 0);
                    $data['resultlist']  = $resultlist;
                }
            } else if ($search == 'search_full') {
                $data['searchby']    = "text";
                $data['search_text'] = trim($this->input->post('search_text'));
                $resultlist          = $this->staff_model->searchFullText($search_text, 0);
                $data['resultlist']  = $resultlist;
                $data['title']       = 'Search Details: ' . $data['search_text'];
            }
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/staff/disablestaff', $data);
        $this->load->view('layout/footer', $data);
    }

    public function profile($id)
    {
        $data['enable_disable'] = 1;
        if ($this->customlib->getStaffID() == $id) {
            $data['enable_disable'] = 0;
        } else if (!$this->rbac->hasPrivilege('staff', 'can_view')) {
            access_denied();
        }

        $this->load->model("staffattendancemodel");
        $this->load->model("setting_model");
        $data["id"]      = $id;
        $data['title']   = 'Staff Details';
        $staff_info      = $this->staff_model->getProfile($id);
        $userdata        = $this->customlib->getUserData();
        $userid          = $userdata['id'];
        $timeline_status = '';
        if ($userid == $id) {
            $timeline_status = 'yes';
        }
        $timeline_list         = $this->timeline_model->getStaffTimeline($id, $timeline_status);
        $data["timeline_list"] = $timeline_list;
        $staff_payroll         = $this->staff_model->getStaffPayroll($id);
        $staff_leaves          = $this->leaverequest_model->staff_leave_request($id);

        $alloted_leavetype           = $this->staff_model->allotedLeaveType($id);
        $data['sch_setting']         = $this->sch_setting_detail;
        $data['staffid_auto_insert'] = $this->sch_setting_detail->staffid_auto_insert;
        $this->load->model("payroll_model");
        $salary = $this->payroll_model->getSalaryDetails($id);

        $attendencetypes             = $this->staffattendancemodel->getStaffAttendanceType();
        $data['attendencetypeslist'] = $attendencetypes;
        $i                           = 0;
        $leaveDetail                 = array();
        foreach ($alloted_leavetype as $key => $value) {
            $count_leaves[]                   = $this->leaverequest_model->countLeavesData($id, $value["leave_type_id"]);
            $leaveDetail[$i]['type']          = $value["type"];
            $leaveDetail[$i]['alloted_leave'] = $value["alloted_leave"];
            $leaveDetail[$i]['approve_leave'] = $count_leaves[$i]['approve_leave'];
            $i++;
        }
        $data["leavedetails"]  = $leaveDetail;
        $data["staff_leaves"]  = $staff_leaves;
        $data['staff_doc_id']  = $id;
        $data['staff']         = $staff_info;
        $data['staff_payroll'] = $staff_payroll;
        $data['salary']        = $salary;
        $monthlist             = $this->customlib->getMonthDropdown();
        $startMonth            = $this->setting_model->getStartMonth();
        $data["monthlist"]     = $monthlist;
        $data['yearlist']      = $this->staffattendancemodel->attendanceYearCount();
        $session_current       = $this->setting_model->getCurrentSessionName();
        $startMonth            = $this->setting_model->getStartMonth();
        $centenary             = substr($session_current, 0, 2); //2017-18 to 2017
        $year_first_substring  = substr($session_current, 2, 2); //2017-18 to 2017
        $year_second_substring = substr($session_current, 5, 2); //2017-18 to 18
        $month_number          = date("m", strtotime($startMonth));
        $data['rate_canview']  = 0;

        if ($id != '1') {
            $staff_rating = $this->staff_model->staff_ratingById($id);

            if ($staff_rating['total'] >= 3) {
                $data['rate'] = ($staff_rating['rate'] / $staff_rating['total']);

                $data['rate_canview'] = 1;
            }
            $data['reviews'] = $staff_rating['total'];
        }

        $data['reviews_comment'] = $this->staff_model->staff_ratingById($id);

        $year = date("Y");

        $staff_list              = $this->staff_model->user_reviewlist($id);
        $data['user_reviewlist'] = $staff_list;

        $attendence_count = array();
        $attendencetypes  = $this->attendencetype_model->getStaffAttendanceType();
        foreach ($attendencetypes as $att_key => $att_value) {
            $attendence_count[$att_value['type']] = array();
        }

        foreach ($monthlist as $key => $value) {
            $datemonth       = date("m", strtotime($key));
            $date_each_month = date('Y-' . $datemonth . '-01');

            $date_start = date('01', strtotime($date_each_month));
            $date_end   = date('t', strtotime($date_each_month));
            for ($n = $date_start; $n <= $date_end; $n++) {
                $att_dates        = $year . "-" . $datemonth . "-" . sprintf("%02d", $n);
                $date_array[]     = $att_dates;
                $staff_attendence = $this->staffattendancemodel->searchStaffattendance($att_dates, $id, false);

                if (!empty($staff_attendence)) {
                    if ($staff_attendence['att_type'] != "") {
                        $attendence_count[$staff_attendence['att_type']][] = 1;
                    }
                } else {

                }
                $res[$att_dates] = $staff_attendence;
            }
        }

        $session       = $this->setting_model->getCurrentSessionName();
        $session_start = explode("-", $session);
        $start_year    = $session_start[0];
        $date          = $start_year . "-" . $startMonth;
        $newdate       = date("Y-m-d", strtotime($date . "+1 month"));

        $data["countAttendance"]  = $attendence_count;
        $data["resultlist"]       = $res;
        $data["attendence_array"] = range(01, 31);
        $data["date_array"]       = $date_array;
        $data["payroll_status"]   = $this->payroll_status;
        $data["payment_mode"]     = $this->payment_mode;
        $data["contract_type"]    = $this->contract_type;
        $data["status"]           = $this->status;
        $roles                    = $this->role_model->get();
        $data["roles"]            = $roles;
        $stafflist                = $this->staff_model->get();
        $data['stafflist']        = $stafflist;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/staff/staffprofile', $data);
        $this->load->view('layout/footer', $data);
    }

    public function countAttendance($year, $emp)
    {

        $record = array();

        foreach ($this->staff_attendance as $att_key => $att_value) {

            $s           = $this->staff_model->count_attendance($year, $emp, $att_value);
            $r[$att_key] = $s;
        }

        $record[$year] = $r;

        return $record;
    }

    public function getSession()
    {
        $session             = $this->session_model->getAllSession();
        $data                = array();
        $session_array       = $this->session->has_userdata('session_array');
        $data['sessionData'] = array('session_id' => 0);
        if ($session_array) {
            $data['sessionData'] = $this->session->userdata('session_array');
        } else {
            $setting             = $this->setting_model->get();
            $data['sessionData'] = array('session_id' => $setting[0]['session_id']);
        }
        $data['sessionList'] = $session;

        return $data;
    }

    public function getSessionMonthDropdown()
    {
        $startMonth = $this->setting_model->getStartMonth();
        $array      = array();
        for ($m = $startMonth; $m <= $startMonth + 11; $m++) {
            $month         = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
            $array[$month] = $month;
        }
        return $array;
    }

    public function download($staff_id, $doc)
    {
        $stafflist = $this->staff_model->getProfile($staff_id);
        $this->media_storage->filedownload($stafflist[$doc], "./uploads/staff_documents/$staff_id");
    }

    public function doc_delete($id, $doc)
    {
        $this->staff_model->doc_delete($id, $doc);
        $this->session->set_flashdata('msg', '<i class="fa fa-check-square-o" aria-hidden="true"></i>' . $this->lang->line('delete_message') . '');
        redirect('admin/staff/profile/' . $id);
    }

    public function ajax_attendance()
    {
        $this->load->model("staffattendancemodel");
        $attendencetypes             = $this->staffattendancemodel->getStaffAttendanceType();
        $data['attendencetypeslist'] = $attendencetypes;

        $id           = $this->input->post("id");
        $year         = $this->input->post("year");
        $data["year"] = $year;
        if (!empty($year)) {

            $monthlist         = $this->customlib->getMonthDropdown();
            $startMonth        = $this->setting_model->getStartMonth();
            $data["monthlist"] = $monthlist;
            $data['yearlist']  = $this->staffattendancemodel->attendanceYearCount();
            $session_current   = $this->setting_model->getCurrentSessionName();
            $startMonth        = $this->setting_model->getStartMonth();

            foreach ($monthlist as $key => $value) {
                $datemonth       = date("m", strtotime($key));
                $date_each_month = date('Y-' . $datemonth . '-01');
                $date_end        = date('t', strtotime($date_each_month));
                for ($n = 1; $n <= $date_end; $n++) {
                    $att_date           = sprintf("%02d", $n);
                    $attendence_array[] = $att_date;
                    $datemonth          = date("m", strtotime($key));
                    $att_dates          = $year . "-" . $datemonth . "-" . sprintf("%02d", $n);

                    $date_array[]    = $att_dates;
                    $res[$att_dates] = $this->staffattendancemodel->searchStaffattendance($att_dates, $id);
                }
            }
            $date    = $year . "-" . $startMonth;
            $newdate = date("Y-m-d", strtotime($date . "+1 month"));

            $countAttendance = $this->countAttendance($year, $id);

            $data["countAttendance"] = $countAttendance;

            $data["id"]               = $id;
            $data["resultlist"]       = $res;
            $data["attendence_array"] = $attendence_array;
            $data["date_array"]       = $date_array;

            $page = $this->load->view("admin/staff/ajaxattendance", $data, true);
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'status'          => 1,
                    'countAttendance' => $countAttendance[$year],
                    'page'            => $page,
                )));
        }
    }

    public function create()
    {
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staff');
        $roles                       = $this->role_model->get();
        $data["roles"]               = $roles;
        $genderList                  = $this->customlib->getGender();
        $data['genderList']          = $genderList;
        $payscaleList                = $this->staff_model->getPayroll();
        $leavetypeList               = $this->staff_model->getLeaveType();
        $data["leavetypeList"]       = $leavetypeList;
        $data["payscaleList"]        = $payscaleList;
        $designation                 = $this->staff_model->getStaffDesignation();
        $data["designation"]         = $designation;
        $department                  = $this->staff_model->getDepartment();
        $data["department"]          = $department;
        $marital_status              = $this->marital_status;
        $data["marital_status"]      = $marital_status;
        $data['title']               = 'Add Staff';
        $data["contract_type"]       = $this->contract_type;
        $data['sch_setting']         = $this->sch_setting_detail;
        $data['staffid_auto_insert'] = $this->sch_setting_detail->staffid_auto_insert;
        $custom_fields               = $this->customfield_model->getByBelong('staff');
        foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
            if ($custom_fields_value['validation']) {
                $custom_fields_id   = $custom_fields_value['id'];
                $custom_fields_name = $custom_fields_value['name'];
                $this->form_validation->set_rules("custom_fields[staff][" . $custom_fields_id . "]", $custom_fields_name, 'trim|required');
            }
        }

        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('role', $this->lang->line('role'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', $this->lang->line('date_of_birth'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');
        $this->form_validation->set_rules('first_doc', $this->lang->line('image'), 'callback_handle_first_upload');
        $this->form_validation->set_rules('second_doc', $this->lang->line('image'), 'callback_handle_second_upload');
        $this->form_validation->set_rules('third_doc', $this->lang->line('image'), 'callback_handle_third_upload');
        $this->form_validation->set_rules('fourth_doc', $this->lang->line('image'), 'callback_handle_fourth_upload');
        $this->form_validation->set_rules(
            'email', $this->lang->line('email'), array('required', 'valid_email',
                array('check_exists', array($this->staff_model, 'valid_email_id')),
            )
        );
        if (!$this->sch_setting_detail->staffid_auto_insert) {
            $this->form_validation->set_rules('employee_id', $this->lang->line('staff_id'), 'callback_username_check');
        }

        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');

        if ($this->form_validation->run() == true) {

            $custom_field_post  = $this->input->post("custom_fields[staff]");
            $custom_value_array = array();
            if (!empty($custom_fields)) {
                foreach ($custom_field_post as $key => $value) {
                    $check_field_type = $this->input->post("custom_fields[staff][" . $key . "]");
                    $field_value      = is_array($check_field_type) ? implode(",", $check_field_type) : $check_field_type;
                    $array_custom     = array(
                        'belong_table_id' => 0,
                        'custom_field_id' => $key,
                        'field_value'     => $field_value,
                    );
                    $custom_value_array[] = $array_custom;
                }
            }

            $employee_id       = $this->input->post("employee_id");
            $department        = empty2null($this->input->post("department"));
            $designation       = empty2null($this->input->post("designation"));
            $role              = $this->input->post("role");
            $name              = $this->input->post("name");
            $gender            = $this->input->post("gender");
            $marital_status    = $this->input->post("marital_status");
            $dob               = $this->input->post("dob");
            $contact_no        = $this->input->post("contactno");
            $emergency_no      = $this->input->post("emergency_no");
            $email             = $this->input->post("email");
            $date_of_joining   = $this->input->post("date_of_joining");
            $date_of_leaving   = $this->input->post("date_of_leaving");
            $address           = $this->input->post("address");
            $qualification     = $this->input->post("qualification");
            $work_exp          = $this->input->post("work_exp");
            $basic_salary      = $this->input->post('basic_salary');
            $account_title     = $this->input->post("account_title");
            $bank_account_no   = $this->input->post("bank_account_no");
            $bank_name         = $this->input->post("bank_name");
            $ifsc_code         = $this->input->post("ifsc_code");
            $bank_branch       = $this->input->post("bank_branch");
            $contract_type     = $this->input->post("contract_type");
            $shift             = $this->input->post("shift");
            $location          = $this->input->post("location");
            $leave             = $this->input->post("leave");
            $facebook          = $this->input->post("facebook");
            $twitter           = $this->input->post("twitter");
            $linkedin          = $this->input->post("linkedin");
            $instagram         = $this->input->post("instagram");
            $permanent_address = $this->input->post("permanent_address");
            $father_name       = $this->input->post("father_name");
            $surname           = $this->input->post("surname");
            $mother_name       = $this->input->post("mother_name");
            $note              = $this->input->post("note");
            $epf_no            = $this->input->post("epf_no");

            $password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);

            $data_insert = array(
                'password'        => $this->enc_lib->passHashEnc($password),
                'employee_id'     => $employee_id,
                'name'            => $name,
                'email'           => $email,
                'dob'             => date('Y-m-d', $this->customlib->datetostrtotime($dob)),
                'date_of_leaving' => '',
                'gender'          => $gender,
                'payscale'        => '',
                'is_active'       => 1,
            );

            if (isset($surname)) {
                $data_insert['surname'] = $surname;
            }

            if (isset($department)) {
                $data_insert['department'] = $department;
            }

            if (isset($designation)) {
                $data_insert['designation'] = $designation;
            }

            if (isset($mother_name)) {
                $data_insert['mother_name'] = $mother_name;
            }

            if (isset($father_name)) {
                $data_insert['father_name'] = $father_name;
            }

            if (isset($contact_no)) {
                $data_insert['contact_no'] = $contact_no;
            }

            if (isset($emergency_no)) {
                $data_insert['emergency_contact_no'] = $emergency_no;
            }

            if (isset($marital_status)) {
                $data_insert['marital_status'] = $marital_status;
            }

            if (isset($address)) {
                $data_insert['local_address'] = $address;
            }

            if (isset($permanent_address)) {
                $data_insert['permanent_address'] = $permanent_address;
            }

            if (isset($qualification)) {
                $data_insert['qualification'] = $qualification;
            }

            if (isset($work_exp)) {
                $data_insert['work_exp'] = $work_exp;
            }

            if (isset($note)) {
                $data_insert['note'] = $note;
            }

            if (isset($epf_no)) {
                $data_insert['epf_no'] = $epf_no;
            }

            if (isset($basic_salary)) {
                $data_insert['basic_salary'] = $basic_salary;
            }

            if (isset($contract_type)) {
                $data_insert['contract_type'] = $contract_type;
            }

            if (isset($shift)) {
                $data_insert['shift'] = $shift;
            }

            if (isset($location)) {
                $data_insert['location'] = $location;
            }

            if (isset($bank_account_no)) {
                $data_insert['bank_account_no'] = $bank_account_no;
            }

            if (isset($bank_name)) {
                $data_insert['bank_name'] = $bank_name;
            }

            if (isset($account_title)) {
                $data_insert['account_title'] = $account_title;
            }

            if (isset($ifsc_code)) {
                $data_insert['ifsc_code'] = $ifsc_code;
            }

            if (isset($bank_branch)) {
                $data_insert['bank_branch'] = $bank_branch;
            }

            if (isset($facebook)) {
                $data_insert['facebook'] = $facebook;
            }

            if (isset($twitter)) {
                $data_insert['twitter'] = $twitter;
            }

            if (isset($linkedin)) {
                $data_insert['linkedin'] = $linkedin;
            }

            if (isset($instagram)) {
                $data_insert['instagram'] = $instagram;
            }

            if ($date_of_joining != "") {
                $data_insert['date_of_joining'] = $this->customlib->dateFormatToYYYYMMDD($date_of_joining);
            }

            $data_insert['date_of_leaving'] = null;

            $leave_type  = $this->input->post('leave_type');
            $leave_array = array();
            if (!empty($leave_type)) {
                foreach ($leave_type as $leave_key => $leave_value) {
                    $leave_array[] = array(
                        'staff_id'      => 0,
                        'leave_type_id' => $leave_value,
                        'alloted_leave' => $this->input->post('alloted_leave_' . $leave_value),
                    );
                }
            }
            $role_array = array('role_id' => $this->input->post('role'), 'staff_id' => 0);
//==========================
            $insert                                = true;
            $data_setting                          = array();
            $data_setting['id']                    = $this->sch_setting_detail->id;
            $data_setting['staffid_auto_insert']   = $this->sch_setting_detail->staffid_auto_insert;
            $data_setting['staffid_update_status'] = $this->sch_setting_detail->staffid_update_status;
            $employee_id                           = 0;

            if ($this->sch_setting_detail->staffid_auto_insert) {
                if ($this->sch_setting_detail->staffid_update_status) {

                    $employee_id = $this->sch_setting_detail->staffid_prefix . $this->sch_setting_detail->staffid_start_from;

                    $last_student = $this->staff_model->lastRecord();

                    $last_admission_digit = str_replace($this->sch_setting_detail->staffid_prefix, "", $last_student->employee_id);

                    $employee_id                = $this->sch_setting_detail->staffid_prefix . sprintf("%0" . $this->sch_setting_detail->staffid_no_digit . "d", $last_admission_digit + 1);
                    $data_insert['employee_id'] = $employee_id;
                } else {
                    $employee_id                = $this->sch_setting_detail->staffid_prefix . $this->sch_setting_detail->staffid_start_from;
                    $data_insert['employee_id'] = $employee_id;
                }

                $employee_id_exists = $this->staff_model->check_staffid_exists($employee_id);
                if ($employee_id_exists) {
                    $insert = false;
                }
            } else {

                $data_insert['employee_id'] = $this->input->post('employee_id');
            }
            //==========================
            if ($insert) {

                if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                    $img_name             = $this->media_storage->fileupload("file", "./uploads/staff_images/");
                    $data_insert['image'] = $img_name;
                }

                $insert_id = $this->staff_model->batchInsert($data_insert, $role_array, $leave_array, $data_setting);
                $staff_id  = $insert_id;
                if (!empty($custom_value_array)) {
                    $this->customfield_model->insertRecord($custom_value_array, $insert_id);
                }

                $upload_dir = './uploads/staff_documents/' . $staff_id . '/';
                if (!is_dir($upload_dir) && !mkdir($upload_dir)) {
                    die("Error creating folder $upload_dir");
                }
                    
                if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
                      $resume = $this->media_storage->fileupload("first_doc", $upload_dir);
                } else {
                    $resume = "";
                }

                if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {
                     $joining_letter = $this->media_storage->fileupload("second_doc", $upload_dir);
                } else {
                    $joining_letter = "";
                }

                if (isset($_FILES["third_doc"]) && !empty($_FILES['third_doc']['name'])) {
                    $resignation_letter = $this->media_storage->fileupload("third_doc", $upload_dir);
                } else {
                    $resignation_letter = "";
                }

                if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {
                   $fourth_doc = $this->media_storage->fileupload("fourth_doc", $upload_dir);
                } else {
                    $fourth_title = "";
                    $fourth_doc   = "";
                }

                $data_doc = array('id' => $staff_id, 'resume' => $resume, 'joining_letter' => $joining_letter, 'resignation_letter' => $resignation_letter, 'other_document_name' => $fourth_title, 'other_document_file' => $fourth_doc);
                $this->staff_model->add($data_doc);

                //===================
                if ($staff_id) {
                    $teacher_login_detail = array('id' => $staff_id, 'credential_for' => 'staff', 'first_name' => $this->input->post("name"), 'last_name' => $this->input->post("surname"), 'username' => $email, 'password' => $password, 'contact_no' => $contact_no, 'email' => $email, 'employee_id' => $data_insert['employee_id']);
                    $this->mailsmsconf->mailsms('staff_login_credential', $teacher_login_detail);
                }
                //==========================

                $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');

                redirect('admin/staff');
            } else {
                $data['error_message'] = 'Admission No ' . $admission_no . ' already exists';
                $this->load->view('layout/header', $data);
                $this->load->view('admin/staff/staffcreate', $data);
                $this->load->view('layout/footer', $data);
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/staff/staffcreate', $data);
        $this->load->view('layout/footer', $data);
    }

    public function handle_upload()
    {
        $image_validate = $this->config->item('image_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

            $file_type = $_FILES["file"]['type'];
            $file_size = $_FILES["file"]["size"];
            $file_name = $_FILES["file"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->image_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->image_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = @getimagesize($_FILES['file']['tmp_name'])) {

                if (!in_array($files['mime'], $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }
                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }

                if ($file_size > $result->image_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->image_size / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            return true;
        }
        return true;
    }

    public function handle_first_upload()
    {
        $file_validate = $this->config->item('file_validate');
        $result        = $this->filetype_model->get();
        if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {

            $file_type = $_FILES["first_doc"]['type'];
            $file_size = $_FILES["first_doc"]["size"];
            $file_name = $_FILES["first_doc"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['first_doc']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_first_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_first_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_first_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($file_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

            return true;
        }
        return true;
    }

    public function handle_second_upload()
    {
        $file_validate = $this->config->item('file_validate');
        $result        = $this->filetype_model->get();
        if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {

            $file_type         = $_FILES["second_doc"]['type'];
            $file_size         = $_FILES["second_doc"]["size"];
            $file_name         = $_FILES["second_doc"]["name"];
            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['second_doc']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_second_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_second_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }

            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_second_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($file_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

            return true;
        }
        return true;
    }

    public function handle_third_upload()
    {
        $file_validate = $this->config->item('file_validate');
        $result        = $this->filetype_model->get();
        if (isset($_FILES["third_doc"]) && !empty($_FILES['third_doc']['name'])) {

            $file_type = $_FILES["third_doc"]['type'];
            $file_size = $_FILES["third_doc"]["size"];
            $file_name = $_FILES["third_doc"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['third_doc']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_third_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_third_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_third_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($file_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

            return true;
        }
        return true;
    }

    public function handle_fourth_upload()
    {
        $file_validate = $this->config->item('file_validate');
        $result        = $this->filetype_model->get();
        if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {

            $file_type = $_FILES["fourth_doc"]['type'];
            $file_size = $_FILES["fourth_doc"]["size"];
            $file_name = $_FILES["fourth_doc"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['fourth_doc']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_fourth_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_fourth_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_fourth_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($file_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

            return true;
        }
        return true;
    }

    public function username_check($str)
    {
        if (empty($str)) {
            $this->form_validation->set_message('username_check', $this->lang->line('staff_id_field_is_required'));
            return false;
        } else {
            $result = $this->staff_model->valid_employee_id($str);
            if ($result == false) {
                return false;
            }
            return true;
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('staff', 'can_edit')) {
            access_denied();
        }
        $a           = 0;
        $sessionData = $this->session->userdata('admin');
        $userdata    = $this->customlib->getUserData();

        $data['title']               = 'Edit Staff';
        $data['id']                  = $id;
        $genderList                  = $this->customlib->getGender();
        $data['genderList']          = $genderList;
        $payscaleList                = $this->staff_model->getPayroll();
        $leavetypeList               = $this->staff_model->getLeaveType();
        $data["leavetypeList"]       = $leavetypeList;
        $data["payscaleList"]        = $payscaleList;
        $staffRole                   = $this->staff_model->getStaffRole();
        $data["getStaffRole"]        = $staffRole;
        $designation                 = $this->staff_model->getStaffDesignation();
        $data["designation"]         = $designation;
        $department                  = $this->staff_model->getDepartment();
        $data["department"]          = $department;
        $marital_status              = $this->marital_status;
        $data["marital_status"]      = $marital_status;
        $staff                       = $this->staff_model->get($id);
        $data['staff']               = $staff;
        $data["contract_type"]       = $this->contract_type;
        $data['sch_setting']         = $this->sch_setting_detail;
        $data['staffid_auto_insert'] = $this->sch_setting_detail->staffid_auto_insert;
        if ($staff["role_id"] == 7) {
            $a = 0;
            if ($userdata["email"] == $staff["email"]) {
                $a = 1;
            }
        } else {
            $a = 1;
        }

        if ($a != 1) {
            access_denied();
        }

        $staffLeaveDetails         = $this->staff_model->getLeaveDetails($id);
        $data['staffLeaveDetails'] = $staffLeaveDetails;
        $resume                    = $this->input->post("resume");
        $joining_letter            = $this->input->post("joining_letter");
        $resignation_letter        = $this->input->post("resignation_letter");
        $other_document_name       = $this->input->post("other_document_name");
        $other_document_file       = $this->input->post("other_document_file");
        $custom_fields             = $this->customfield_model->getByBelong('staff');

        foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {

            if ($custom_fields_value['validation']) {
                $custom_fields_id   = $custom_fields_value['id'];
                $custom_fields_name = $custom_fields_value['name'];
                $this->form_validation->set_rules("custom_fields[staff][" . $custom_fields_id . "]", $custom_fields_name, 'trim|required');
            }
        }

        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('role', $this->lang->line('role'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', $this->lang->line('date_of_birth'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');
        $this->form_validation->set_rules('first_doc', $this->lang->line('image'), 'callback_handle_first_upload');
        $this->form_validation->set_rules('second_doc', $this->lang->line('image'), 'callback_handle_second_upload');
        $this->form_validation->set_rules('third_doc', $this->lang->line('image'), 'callback_handle_third_upload');
        $this->form_validation->set_rules('fourth_doc', $this->lang->line('image'), 'callback_handle_fourth_upload');
        if (!$this->sch_setting_detail->staffid_auto_insert) {
            $this->form_validation->set_rules('employee_id', $this->lang->line('staff_id'), 'callback_username_check');
        }

        $this->form_validation->set_rules(
            'email', $this->lang->line('email'), array('required', 'valid_email',
                array('check_exists', array($this->staff_model, 'valid_email_id')),
            )
        );

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staff/staffedit', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $employee_id       = $this->input->post("employee_id");
            $department        = empty2null($this->input->post("department"));
            $designation       = empty2null($this->input->post("designation"));
            $role              = $this->input->post("role");
            $name              = $this->input->post("name");
            $gender            = $this->input->post("gender");
            $marital_status    = $this->input->post("marital_status");
            $dob               = $this->input->post("dob");
            $contact_no        = $this->input->post("contactno");
            $emergency_no      = $this->input->post("emergency_no");
            $email             = $this->input->post("email");
            $date_of_joining   = $this->input->post("date_of_joining");
            $date_of_leaving   = $this->input->post("date_of_leaving");
            $address           = $this->input->post("address");
            $qualification     = $this->input->post("qualification");
            $work_exp          = $this->input->post("work_exp");
            $basic_salary      = $this->input->post('basic_salary');
            $account_title     = $this->input->post("account_title");
            $bank_account_no   = $this->input->post("bank_account_no");
            $bank_name         = $this->input->post("bank_name");
            $ifsc_code         = $this->input->post("ifsc_code");
            $bank_branch       = $this->input->post("bank_branch");
            $contract_type     = $this->input->post("contract_type");
            $shift             = $this->input->post("shift");
            $location          = $this->input->post("location");
            $leave             = $this->input->post("leave");
            $facebook          = $this->input->post("facebook");
            $twitter           = $this->input->post("twitter");
            $linkedin          = $this->input->post("linkedin");
            $instagram         = $this->input->post("instagram");
            $permanent_address = $this->input->post("permanent_address");
            $father_name       = $this->input->post("father_name");
            $surname           = $this->input->post("surname");
            $mother_name       = $this->input->post("mother_name");
            $note              = $this->input->post("note");
            $epf_no            = $this->input->post("epf_no");
            $custom_field_post = $this->input->post("custom_fields[staff]");

            $custom_value_array = array();
            if (!empty($custom_fields)) {
                foreach ($custom_field_post as $key => $value) {
                    $check_field_type = $this->input->post("custom_fields[staff][" . $key . "]");
                    $field_value      = is_array($check_field_type) ? implode(",", $check_field_type) : $check_field_type;
                    $array_custom     = array(
                        'belong_table_id' => $id,
                        'custom_field_id' => $key,
                        'field_value'     => $field_value,
                    );
                    $custom_value_array[] = $array_custom;
                }
                $this->customfield_model->updateRecord($custom_value_array, $id, 'staff');
            }

            $data1 = array(
                'id'                   => $id,
                'department'           => $department,
                'designation'          => $designation,
                'qualification'        => $qualification,
                'work_exp'             => $work_exp,
                'name'                 => $name,
                'contact_no'           => $contact_no,
                'emergency_contact_no' => $emergency_no,
                'email'                => $email,
                'dob'                  => date('Y-m-d', $this->customlib->datetostrtotime($dob)),
                'marital_status'       => $marital_status,
                'local_address'        => $address,
                'permanent_address'    => $permanent_address,
                'note'                 => $note,
                'surname'              => $surname,
                'mother_name'          => $mother_name,
                'father_name'          => $father_name,
                'gender'               => $gender,
                'account_title'        => $account_title,
                'bank_account_no'      => $bank_account_no,
                'bank_name'            => $bank_name,
                'ifsc_code'            => $ifsc_code,
                'bank_branch'          => $bank_branch,
                'payscale'             => '',
                'basic_salary'         => $basic_salary,
                'epf_no'               => $epf_no,
                'contract_type'        => $contract_type,
                'shift'                => $shift,
                'location'             => $location,
                'facebook'             => $facebook,
                'twitter'              => $twitter,
                'linkedin'             => $linkedin,
                'instagram'            => $instagram,
            );

            $data1['date_of_joining'] = $this->customlib->dateFormatToYYYYMMDD($date_of_joining);

            $data1['date_of_leaving'] = $this->customlib->dateFormatToYYYYMMDD($date_of_leaving);

            if (!$this->sch_setting_detail->staffid_auto_insert) {
                $data1['employee_id'] = $employee_id;
            }

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $img_name       = $this->media_storage->fileupload("file", "./uploads/staff_images/");
                $data1['image'] = $img_name;

                if ($staff['image'] != '') {
                    $this->media_storage->filedelete($staff['image'], "uploads/staff_images");
                }
            }

            $insert_id = $this->staff_model->add($data1);
            $role_id   = $this->input->post("role");
            $role_data = array('staff_id' => $id, 'role_id' => $role_id);
            $this->staff_model->update_role($role_data);
            $leave_type    = $this->input->post("leave_type_id");
            $alloted_leave = $this->input->post("alloted_leave");
            $altid         = $this->input->post("altid");

            if (!empty($leave_type)) {
                $i = 0;
                foreach ($leave_type as $key => $value) {

                    if (!empty($altid[$i])) {

                        $data2 = array('staff_id' => $id,
                            'leave_type_id'           => $leave_type[$i],
                            'id'                      => $altid[$i],
                            'alloted_leave'           => $alloted_leave[$i],
                        );
                    } else {
                        $data2 = array('staff_id' => $id,
                            'leave_type_id'           => $leave_type[$i],
                            'alloted_leave'           => $alloted_leave[$i],
                        );
                    }

                    $this->staff_model->add_staff_leave_details($data2);
                    $i++;
                }
            }

            $upload_dir = './uploads/staff_documents/' . $id . '/';
            if (!is_dir($upload_dir) && !mkdir($upload_dir)) {
                die("Error creating folder $upload_dir");
            }
            
            if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
                $resume_doc = $this->media_storage->fileupload("first_doc", $upload_dir);
            } else {
                $resume_doc = $resume;
            }

            if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {
                $joining_letter_doc = $this->media_storage->fileupload("second_doc", $upload_dir);
            } else {
                $joining_letter_doc = $joining_letter;
            }

            if (isset($_FILES["third_doc"]) && !empty($_FILES['third_doc']['name'])) {

                $resignation_letter_doc = $this->media_storage->fileupload("third_doc", $upload_dir);

            } else {
                $resignation_letter_doc = $resignation_letter;
            }

            if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {
                $fourth_doc = $this->media_storage->fileupload("fourth_doc", $upload_dir);
            } else {
                $fourth_title = 'Other Document';
                $fourth_doc   = $other_document_file;
            }

            $data_doc = array('id' => $id, 'resume' => $resume_doc, 'joining_letter' => $joining_letter_doc, 'resignation_letter' => $resignation_letter_doc, 'other_document_name' => $fourth_title, 'other_document_file' => $fourth_doc);

            $this->staff_model->add($data_doc);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/staff');
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('staff', 'can_delete')) {
            access_denied();
        }

        $a           = 0;
        $sessionData = $this->session->userdata('admin');
        $userdata    = $this->customlib->getUserData();
        $staff       = $this->staff_model->get($id);

        if ($staff['id'] == $userdata['id']) {
            $a = 1;
        } else if ($staff["role_id"] == 7) {
            $a = 1;
        }

        if ($a == 1) {
            access_denied();
        }
        $data['title'] = 'Staff List';
        $this->staff_model->remove($id);
        redirect('admin/staff');
    }

    public function disablestaff($id)
    {
        if (!$this->rbac->hasPrivilege('disable_staff', 'can_view')) {
            access_denied();
        }

        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'date' => form_error('date'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');

            echo json_encode($array);

        } else {

            $a           = 0;
            $sessionData = $this->session->userdata('admin');
            $userdata    = $this->customlib->getUserData();
            $staff       = $this->staff_model->get($id);
            if ($staff["role_id"] == 7) {
                $a = 0;
                if ($userdata["email"] == $staff["email"]) {
                    $a = 1;
                }
            } else {
                $a = 1;
            }

            if ($a != 1) {
                access_denied();
            }

            if (isset($_POST['date'])) {
                $data = array('id' => $id, 'disable_at' => date('Y-m-d', $this->customlib->datetostrtotime($_POST['date'])), 'is_active' => 0);
            } else {
                $data = array('id' => $id, 'is_active' => 0);
            }

            $this->staff_model->disablestaff($data);

            if (isset($_POST['date'])) {
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
                echo json_encode($array);
            } else {
                redirect('admin/staff/profile/' . $id);
            }
        }
    }

    public function enablestaff($id)
    {
        $a           = 0;
        $sessionData = $this->session->userdata('admin');
        $userdata    = $this->customlib->getUserData();
        $staff       = $this->staff_model->get($id);
        if ($staff["role_id"] == 7) {
            $a = 0;
            if ($userdata["email"] == $staff["email"]) {
                $a = 1;
            }
        } else {
            $a = 1;
        }

        if ($a != 1) {
            access_denied();
        }
        $this->staff_model->enablestaff($id);
        redirect('admin/staff/profile/' . $id);
    }

    public function staffLeaveSummary()
    {
        $resultdata         = $this->staff_model->getLeaveSummary();
        $data["resultdata"] = $resultdata;
        $this->load->view("layout/header");
        $this->load->view("admin/staff/staff_leave_summary", $data);
        $this->load->view("layout/footer");
    }

    public function getEmployeeByRole()
    {
        $role = $this->input->post("role");
        $data = $this->staff_model->getEmployee($role);
        echo json_encode($data);
    }

    public function dateDifference($date_1, $date_2, $differenceFormat = '%a')
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);
        $interval  = date_diff($datetime1, $datetime2);
        return $interval->format($differenceFormat) + 1;
    }

    public function permission($id)
    {
        $data['title']          = 'Add Role';
        $data['id']             = $id;
        $staff                  = $this->staff_model->get($id);
        $data['staff']          = $staff;
        $userpermission         = $this->userpermission_model->getUserPermission($id);
        $data['userpermission'] = $userpermission;

        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $staff_id   = $this->input->post('staff_id');
            $prev_array = $this->input->post('prev_array');
            if (!isset($prev_array)) {
                $prev_array = array();
            }
            $module_perm  = $this->input->post('module_perm');
            $delete_array = array_diff($prev_array, $module_perm);
            $insert_diff  = array_diff($module_perm, $prev_array);
            $insert_array = array();
            if (!empty($insert_diff)) {

                foreach ($insert_diff as $key => $value) {
                    $insert_array[] = array(
                        'staff_id'      => $staff_id,
                        'permission_id' => $value,
                    );
                }
            }

            $this->userpermission_model->getInsertBatch($insert_array, $staff_id, $delete_array);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/staff');
        }

        $this->load->view('layout/header');
        $this->load->view('admin/staff/permission', $data);
        $this->load->view('layout/footer');
    }

    public function leaverequest()
    {
        if (!$this->rbac->hasPrivilege('apply_leave', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staff/leaverequest');
        $userdata                   = $this->customlib->getUserData();
        $leave_request              = $this->leaverequest_model->user_leave_request($userdata["id"]);
        $data["leave_request"]      = $leave_request;
        $LeaveTypes                 = $this->leaverequest_model->allotedLeaveType($userdata["id"]);
        $data["staff_id"]           = $userdata["id"];
        $data["leavetype"]          = $LeaveTypes;
        $staffRole                  = $this->staff_model->getStaffRole();
        $data["staffrole"]          = $staffRole;
        $data["status"]             = $this->status;
        $data["superadmin_visible"] = $this->customlib->superadmin_visible();
        $data["user_role"]          = $userdata['role_id'];
        $this->load->view("layout/header", $data);
        $this->load->view("admin/staff/leaverequest", $data);
        $this->load->view("layout/footer", $data);
    }

    public function change_password($id)
    {
        $sessionData = $this->session->userdata('admin');
        $userdata    = $this->customlib->getUserData();
        $this->form_validation->set_rules('new_pass', $this->lang->line('new_password'), 'trim|required|xss_clean|matches[confirm_pass]');
        $this->form_validation->set_rules('confirm_pass', $this->lang->line('confirm_password'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

            $msg = array(
                'new_pass'     => form_error('new_pass'),
                'confirm_pass' => form_error('confirm_pass'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            if (!empty($id)) {
                $newdata = array(
                    'id'       => $id,
                    'password' => $this->enc_lib->passHashEnc($this->input->post('new_pass')),
                );

                $query2 = $this->admin_model->saveNewPass($newdata);
                if ($query2) {
                    $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('password_changed_successfully'));
                } else {
                    $array = array('status' => 'fail', 'error' => '', 'message' => $this->lang->line('password_not_changed'));
                }
            } else {
                $array = array('status' => 'fail', 'error' => '', 'message' => $this->lang->line('password_not_changed'));
            }
        }

        echo json_encode($array);
    }

    public function import()
    {
        $data['field'] = array(
            "staff_id"                 => "staff_id",
            "first_name"               => "first_name",
            "last_name"                => "last_name",
            "father_name"              => "father_name",
            "mother_name"              => "mother_name",
            "email_login_username"     => "email",
            "gender"                   => "gender",
            "date_of_birth"            => "date_of_birth",
            "date_of_joining"          => "date_of_joining",
            "phone"                    => "phone",
            "emergency_contact_number" => "emergency_contact_number",
            "marital_status"           => "marital_status",
            "current_address"          => "current_address",
            "permanent_address"        => "permanent_address",
            "qualification"            => "qualification",
            "work_experience"          => "work_experience",
            "note"                     => "note",
        );

        $roles               = $this->role_model->get();
        $data["roles"]       = $roles;
        $designation         = $this->staff_model->getStaffDesignation();
        $data["designation"] = $designation;
        $department          = $this->staff_model->getDepartment();
        $data["department"]  = $department;

        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_csv_upload');
        $this->form_validation->set_rules('role', $this->lang->line('role'), 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view("layout/header", $data);
            $this->load->view("admin/staff/import/import", $data);
            $this->load->view("layout/footer", $data);
        } else {

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if ($ext == 'csv') {

                    $file = $_FILES['file']['tmp_name'];
                    $this->load->library('CSVReader');
                    $result = $this->csvreader->parse_file($file);

                    $rowcount = 0;

                    if (!empty($result)) {

                        foreach ($result as $r_key => $r_value) {

                            $check_exists      = $this->staff_model->import_check_data_exists($result[$r_key]['name'], $result[$r_key]['employee_id']);
                            $check_emailexists = $this->staff_model->import_check_email_exists($result[$r_key]['name'], $result[$r_key]['email']);

                            if ($check_exists == 0 && $check_emailexists == 0) {

                                $result[$r_key]['employee_id']          = $this->encoding_lib->toUTF8($result[$r_key]['employee_id']);
                                $result[$r_key]['qualification']        = $this->encoding_lib->toUTF8($result[$r_key]['qualification']);
                                $result[$r_key]['work_exp']             = $this->encoding_lib->toUTF8($result[$r_key]['work_exp']);
                                $result[$r_key]['name']                 = $this->encoding_lib->toUTF8($result[$r_key]['name']);
                                $result[$r_key]['surname']              = $this->encoding_lib->toUTF8($result[$r_key]['surname']);
                                $result[$r_key]['father_name']          = $this->encoding_lib->toUTF8($result[$r_key]['father_name']);
                                $result[$r_key]['mother_name']          = $this->encoding_lib->toUTF8($result[$r_key]['mother_name']);
                                $result[$r_key]['contact_no']           = $this->encoding_lib->toUTF8($result[$r_key]['contact_no']);
                                $result[$r_key]['emergency_contact_no'] = $this->encoding_lib->toUTF8($result[$r_key]['emergency_contact_no']);
                                $result[$r_key]['email']                = $this->encoding_lib->toUTF8($result[$r_key]['email']);
                                $result[$r_key]['dob']                  = $this->encoding_lib->toUTF8($result[$r_key]['dob']);
                                $result[$r_key]['marital_status']       = $this->encoding_lib->toUTF8($result[$r_key]['marital_status']);
                                $result[$r_key]['date_of_joining']      = $this->encoding_lib->toUTF8($result[$r_key]['date_of_joining']);
                                $result[$r_key]['date_of_leaving']      = $this->encoding_lib->toUTF8($result[$r_key]['date_of_leaving']);
                                $result[$r_key]['local_address']        = $this->encoding_lib->toUTF8($result[$r_key]['local_address']);
                                $result[$r_key]['permanent_address']    = $this->encoding_lib->toUTF8($result[$r_key]['permanent_address']);
                                $result[$r_key]['note']                 = $this->encoding_lib->toUTF8($result[$r_key]['note']);
                                $result[$r_key]['gender']               = $this->encoding_lib->toUTF8($result[$r_key]['gender']);
                                $result[$r_key]['account_title']        = $this->encoding_lib->toUTF8($result[$r_key]['account_title']);
                                $result[$r_key]['bank_account_no']      = $this->encoding_lib->toUTF8($result[$r_key]['bank_account_no']);
                                $result[$r_key]['bank_name']            = $this->encoding_lib->toUTF8($result[$r_key]['bank_name']);
                                $result[$r_key]['ifsc_code']            = $this->encoding_lib->toUTF8($result[$r_key]['ifsc_code']);
                                $result[$r_key]['payscale']             = $this->encoding_lib->toUTF8($result[$r_key]['payscale']);
                                $result[$r_key]['basic_salary']         = $this->encoding_lib->toUTF8($result[$r_key]['basic_salary']);
                                $result[$r_key]['epf_no']               = $this->encoding_lib->toUTF8($result[$r_key]['epf_no']);
                                $result[$r_key]['contract_type']        = $this->encoding_lib->toUTF8($result[$r_key]['contract_type']);
                                $result[$r_key]['shift']                = $this->encoding_lib->toUTF8($result[$r_key]['shift']);
                                $result[$r_key]['location']             = $this->encoding_lib->toUTF8($result[$r_key]['location']);
                                $result[$r_key]['facebook']             = $this->encoding_lib->toUTF8($result[$r_key]['facebook']);
                                $result[$r_key]['twitter']              = $this->encoding_lib->toUTF8($result[$r_key]['twitter']);
                                $result[$r_key]['linkedin']             = $this->encoding_lib->toUTF8($result[$r_key]['linkedin']);
                                $result[$r_key]['instagram']            = $this->encoding_lib->toUTF8($result[$r_key]['instagram']);
                                $result[$r_key]['resume']               = $this->encoding_lib->toUTF8($result[$r_key]['resume']);
                                $result[$r_key]['joining_letter']       = $this->encoding_lib->toUTF8($result[$r_key]['joining_letter']);
                                $result[$r_key]['resignation_letter']   = $this->encoding_lib->toUTF8($result[$r_key]['resignation_letter']);
                                $result[$r_key]['user_id']              = $this->input->post('role');
                                $result[$r_key]['designation']          = $this->input->post('designation');
                                $result[$r_key]['department']           = $this->input->post('department');
                                $result[$r_key]['is_active']            = 1;

                                $password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);

                                $result[$r_key]['password'] = $this->enc_lib->passHashEnc($password);

                                $role_array = array('role_id' => $this->input->post('role'), 'staff_id' => 0);

                                $insert_id = $this->staff_model->batchInsert($result[$r_key], $role_array);
                                $staff_id  = $insert_id;
                                if ($staff_id) {

                                    $teacher_login_detail = array('id' => $staff_id, 'credential_for' => 'staff', 'username' => $result[$r_key]['email'], 'password' => $password, 'contact_no' => $result[$r_key]['contact_no'], 'email' => $result[$r_key]['email']);

                                    $this->mailsmsconf->mailsms('login_credential', $teacher_login_detail);
                                }
                                $rowcount++;
                            }
                        } ///Result loop
                    } //Not emprty l

                    $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('records_found_in_CSV_file_total') . $rowcount . $this->lang->line('records_imported_successfully'));
                }
            } else {
                $msg = array(
                    'e' => $this->lang->line('the_file_field_is_required'),
                );
                $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">' . $this->lang->line('total') . ' ' . count($result) . " " . $this->lang->line('records_found_in_CSV_file_total') . ' ' . $rowcount . ' ' . $this->lang->line('records_imported_successfully') . '</div>');
            redirect('admin/staff/import');
        }
    }

    public function handle_csv_upload()
    {
        $error = "";
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $allowedExts = array('csv');
            $mimes       = array('text/csv',
                'text/plain',
                'application/csv',
                'text/comma-separated-values',
                'application/excel',
                'application/vnd.ms-excel',
                'application/vnd.msexcel',
                'text/anytext',
                'application/octet-stream',
                'application/txt');
            $temp      = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            if ($_FILES["file"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if (!in_array($_FILES['file']['type'], $mimes)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_csv_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }
            if (!in_array($extension, $allowedExts)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_csv_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($error == "") {
                return true;
            }
        } else {
            $this->form_validation->set_message('handle_csv_upload', $this->lang->line('please_select_file'));
            return false;
        }
    }

    public function exportformat()
    {
        $this->load->helper('download');
        $filepath = "./backend/import/staff_csvfile.csv";
        $data     = file_get_contents($filepath);
        $name     = 'staff_csvfile.csv';
        force_download($name, $data);
    }

    public function rating()
    {
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/rating');
        $this->load->view('layout/header');
        $staff_list         = $this->staff_model->getrat();
        $data['resultlist'] = $staff_list;
        $this->load->view('admin/staff/rating', $data);
        $this->load->view('layout/footer');
    }

    public function ratingapr($id)
    {
        $approve['status'] = '1';
        $this->staff_model->ratingapr($id, $approve);
        redirect('admin/staff/rating');
    }

    public function delete_rateing($id)
    {
        $this->staff_model->rating_remove($id);
        redirect('admin/staff/rating');
    }

}
