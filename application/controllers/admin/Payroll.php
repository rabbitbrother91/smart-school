<?php

class Payroll extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
        $this->config->load("mailsms");
        $this->config->load("payroll");
        $this->load->library('mailsmsconf');
        $this->load->library('media_storage');
        $this->config_attendance = $this->config->item('attendence');
        $this->staff_attendance  = $this->config->item('staffattendance');
        $this->payment_mode      = $this->config->item('payment_mode');
        $this->load->model("payroll_model");
        $this->load->model("staff_model");
        $this->load->model('staffattendancemodel');
        $this->payroll_status     = $this->config->item('payroll_status');
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {

        if (!$this->rbac->hasPrivilege('staff_payroll', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/payroll');
        $data["staff_id"]            = "";
        $data["name"]                = "";
        $data["month"]               = date("F", strtotime("-1 month"));
        $data["year"]                = date("Y");
        $data["present"]             = 0;
        $data["absent"]              = 0;
        $data["late"]                = 0;
        $data["half_day"]            = 0;
        $data["holiday"]             = 0;
        $data["leave_count"]         = 0;
        $data["alloted_leave"]       = 0;
        $data["basic"]               = 0;
        $data["payment_mode"]        = $this->payment_mode;
        $user_type                   = $this->staff_model->getStaffRole();
        $data['classlist']           = $user_type;
        $data['monthlist']           = $this->customlib->getMonthDropdown();
        $data['sch_setting']         = $this->sch_setting_detail;
        $data['staffid_auto_insert'] = $this->sch_setting_detail->staffid_auto_insert;
        $submit                      = $this->input->post("search");
        if (isset($submit) && $submit == "search") {

            $month    = $this->input->post("month");
            $year     = $this->input->post("year");
            $emp_name = $this->input->post("name");
            $role     = $this->input->post("role");

            $searchEmployee = $this->payroll_model->searchEmployee($month, $year, $emp_name, $role);

            $data["resultlist"] = $searchEmployee;
            $data["name"]       = $emp_name;
            $data["month"]      = $month;
            $data["year"]       = $year;
        }

        $data["payroll_status"] = $this->payroll_status;
        $this->load->view("layout/header", $data);
        $this->load->view("admin/payroll/stafflist", $data);
        $this->load->view("layout/footer", $data);
    }

    public function create($month, $year, $id)
    {       
        
        $data["staff_id"]            = "";
        $data["basic"]               = "";
        $data["name"]                = "";
        $data["month"]               = "";
        $data["year"]                = "";
        $data["present"]             = 0;
        $data["absent"]              = 0;
        $data["late"]                = 0;
        $data["half_day"]            = 0;
        $data["holiday"]             = 0;
        $data["leave_count"]         = 0;
        $data["alloted_leave"]       = 0;
        $data['sch_setting']         = $this->sch_setting_detail;
        $data['staffid_auto_insert'] = $this->sch_setting_detail->staffid_auto_insert;
        $user_type                   = $this->staff_model->getStaffRole();
        $data['classlist']           = $user_type;

        $date = $year . "-" . $month;

        $searchEmployee = $this->payroll_model->searchEmployeeById($id);

        $data['result'] = $searchEmployee;
        $data["month"]  = $month;
        $data["year"]   = $year;

        $alloted_leave = $this->staff_model->alloted_leave($id);

        $newdate = date('Y-m-d', strtotime($date . " +1 month"));

        $data['monthAttendance'] = $this->monthAttendance($newdate, 3, $id);
        $data['monthLeaves']     = $this->monthLeaves($newdate, 3, $id);
        $data["attendanceType"]  = $this->staffattendancemodel->getStaffAttendanceType();
        $data["alloted_leave"]   = $alloted_leave[0]["alloted_leave"];

        $this->load->view("layout/header", $data);
        $this->load->view("admin/payroll/create", $data);
        $this->load->view("layout/footer", $data);
    }

    public function edit($id)
    {
        $data["staff_id"]         = "";
        $data["basic"]            = "";
        $data["name"]             = "";
        $data["month"]            = "";
        $data["year"]             = "";
        $data["present"]          = 0;
        $data["absent"]           = 0;
        $data["late"]             = 0;
        $data["half_day"]         = 0;
        $data["holiday"]          = 0;
        $data["leave_count"]      = 0;
        $data["alloted_leave"]    = 0;
        $user_type                = $this->staff_model->getStaffRole();
        $employee_payroll         = $this->payroll_model->getPayslip($id);
        $data['employee_payroll'] = $employee_payroll;
        $data['classlist']        = $user_type;
        $data['sch_setting']      = $this->sch_setting_detail;
        $searchEmployee           = $this->payroll_model->searchEmployeeById($employee_payroll['staff_id']);
        $date                     = $employee_payroll['year'] . "-" . $employee_payroll['month'];
        $data['result']           = $searchEmployee;
        $data["month"]            = $employee_payroll['month'];
        $data["year"]             = $employee_payroll['year'];

        $data["earnings"]   = $this->payroll_model->getAllowance($id, 'positive');
        $data["deductions"] = $this->payroll_model->getAllowance($id, 'negative');

        $alloted_leave           = $this->staff_model->alloted_leave($employee_payroll['staff_id']);
        $newdate                 = date('Y-m-d', strtotime($date . " +1 month"));
        $data['monthAttendance'] = $this->monthAttendance($newdate, 3, $employee_payroll['staff_id']);
        $data['monthLeaves']     = $this->monthLeaves($newdate, 3, $employee_payroll['staff_id']);
        $data["attendanceType"]  = $this->staffattendancemodel->getStaffAttendanceType();
        $data["alloted_leave"]   = $alloted_leave[0]["alloted_leave"];
        $this->load->view("layout/header", $data);
        $this->load->view("admin/payroll/edit", $data);
        $this->load->view("layout/footer", $data);
    }

    public function editpayroll()
    {
        $id              = $this->input->post("id");
        $basic           = $this->input->post("basic");
        $total_allowance = $this->input->post("total_allowance");
        $total_deduction = $this->input->post("total_deduction");
        $net_salary      = $this->input->post("net_salary");
        $status          = $this->input->post("status");
        $staff_id        = $this->input->post("staff_id");
        $month           = $this->input->post("month");
        $name            = $this->input->post("name");
        $year            = $this->input->post("year");
        $tax             = $this->input->post("tax_percent");
        $leave_deduction = $this->input->post("leave_deduction");
        $this->form_validation->set_rules('net_salary', $this->lang->line('net_salary'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->create($month, $year, $staff_id);
        } else {
                
        if($total_allowance){
                $total_allowance = convertCurrencyFormatToBaseAmount($total_allowance);
        }else{
                $total_allowance = 0;  
        }
        
        if($total_deduction){
                $total_deduction = convertCurrencyFormatToBaseAmount($total_deduction);
        }else{
                $total_deduction = 0;  
        }
        
        if($basic){
                $basic = convertCurrencyFormatToBaseAmount($basic);
        }else{
                $basic = 0;  
        }
        
        if($net_salary){
                $net_salary = convertCurrencyFormatToBaseAmount($net_salary);
        }else{
                $net_salary = 0;  
        }
        
        if($tax){
                $tax = convertCurrencyFormatToBaseAmount($tax);
        }else{
                $tax = 0;  
        }
        
        
            $data = array(
                'id'              => $id,
                'staff_id'        => $staff_id,
                'basic'           => $basic,
                'total_allowance' => $total_allowance,
                'total_deduction' => $total_deduction,
                'net_salary'      => $net_salary,
                'payment_date'    => date("Y-m-d"),
                'status'          => $status,
                'month'           => $month,
                'year'            => $year,
                'tax'             => $tax,
                'leave_deduction' => '0',
                'generated_by'    => $this->customlib->getStaffID(),
            );

            $checkForUpdate = $this->payroll_model->checkPayslip($month, $year, $staff_id);
            if (!$checkForUpdate) {
                $insert_id         = $this->payroll_model->createPayslip($data);
                $payslipid         = $insert_id;
                $allowance_type    = $this->input->post("allowance_type");
                $deduction_type    = $this->input->post("deduction_type");
                $allowance_prev_id = $this->input->post("allowance_prev_id");
                $deduction_prev_id = $this->input->post("deduction_prev_id");
                $allowance_amount  = $this->input->post("allowance_amount");
                $deduction_amount  = $this->input->post("deduction_amount");

                if (!empty($allowance_type)) {

                    $i                        = 0;
                    $insert_payslip_allowance = array();
                    $update_payslip_allowance = array();
                    foreach ($allowance_type as $key => $all) {
                        
                        if($allowance_amount[$i]){
                                $allowanceamount = convertCurrencyFormatToBaseAmount($allowance_amount[$i]);
                        }else{
                                $allowanceamount = 0;  
                        }
                                
                        if ($allowance_prev_id[$i] != 0) {
                        

        
                            $update_payslip_allowance[] = array(
                                'id'             => $allowance_prev_id[$i],
                                'payslip_id'     => $payslipid,
                                'allowance_type' => $allowance_type[$i],
                                'amount'         => $allowanceamount,
                                'staff_id'       => $staff_id,
                                'cal_type'       => "positive",
                            );
                        } else {
                            
                            $insert_payslip_allowance[] = array(
                                'payslip_id'     => $payslipid,
                                'allowance_type' => $allowance_type[$i],
                                'amount'         => $allowanceamount,
                                'staff_id'       => $staff_id,
                                'cal_type'       => "positive",
                            );
                        }

                        $i++;
                    }

                    $insert_payslip_allowance = $this->payroll_model->update_allowance($insert_payslip_allowance, $update_payslip_allowance, $allowance_prev_id, $payslipid, 'positive');
                } else {

                    $insert_payslip_allowance = $this->payroll_model->update_allowance([], [], [0], $payslipid, 'positive');
                }

                if (!empty($deduction_type)) {
                    $j                        = 0;
                    $insert_payslip_allowance = array();
                    $update_payslip_allowance = array();

                    foreach ($deduction_type as $key => $type) {
                        
                                if($deduction_amount[$j]){
                                        $deductionamount = convertCurrencyFormatToBaseAmount($deduction_amount[$j]);
                                }else{
                                        $deductionamount = 0;  
                                }
                                
                        if ($deduction_prev_id[$j] != 0) {
                            

                            
                            $update_payslip_allowance[] = array(
                                'id'             => $deduction_prev_id[$j],
                                'payslip_id'     => $payslipid,
                                'allowance_type' => $deduction_type[$j],
                                'amount'         => $deductionamount,
                                'staff_id'       => $staff_id,
                                'cal_type'       => "negative",
                            );
                        } else {
                            
                            
                            $insert_payslip_allowance[] = array(
                                'payslip_id'     => $payslipid,
                                'allowance_type' => $deduction_type[$j],
                                'amount'         => $deductionamount,
                                'staff_id'       => $staff_id,
                                'cal_type'       => "negative",
                            );
                        }
                        $j++;
                    }

                    $insert_payslip_allowance = $this->payroll_model->update_allowance($insert_payslip_allowance, $update_payslip_allowance, $deduction_prev_id, $payslipid, 'negative');
                } else {
                    $insert_payslip_allowance = $this->payroll_model->update_allowance([], [], [0], $payslipid, 'negative');
                }

                redirect('admin/payroll');
            } else {

                $this->session->set_flashdata("msg", "<div class='alert alert-warning'>" . $this->lang->line('payslip_not_generated') . "</div>");

                redirect('admin/payroll');
            }
        }
    }

    public function monthAttendance($st_month, $no_of_months, $emp)
    {
        $record = array();
        for ($i = 1; $i <= $no_of_months; $i++) {

            $r     = array();
            $month = date('m', strtotime($st_month . " -$i month"));
            $year  = date('Y', strtotime($st_month . " -$i month"));

            foreach ($this->staff_attendance as $att_key => $att_value) {

                $s = $this->payroll_model->count_attendance_obj($month, $year, $emp, $att_value);

                $r[$att_key] = $s;
            }

            $record['01-' . $month . '-' . $year] = $r;
        }
        return $record;
    }

    public function monthLeaves($st_month, $no_of_months, $emp)
    {
        $record = array();
        for ($i = 1; $i <= $no_of_months; $i++) {

            $r           = array();
            $month       = date('m', strtotime($st_month . " -$i month"));
            $year        = date('Y', strtotime($st_month . " -$i month"));
            $leave_count = $this->staff_model->count_leave($month, $year, $emp);
            if (!empty($leave_count["tl"])) {
                $l = $leave_count["tl"];
            } else {
                $l = "0";
            }

            $record[$month] = $l;
        }

        return $record;
    }

    public function payslip()
    {
        if (!$this->rbac->hasPrivilege('staff_payroll', 'can_add')) {
            access_denied();
        }        
        
        if($this->input->post("total_allowance")){
                $total_allowance = convertCurrencyFormatToBaseAmount($this->input->post("total_allowance"));
        }else{
                $total_allowance = 0;  
        }
        
        if($this->input->post("total_deduction")){
                $total_deduction = convertCurrencyFormatToBaseAmount($this->input->post("total_deduction"));
        }else{
                $total_deduction = 0;  
        }
        
        if($this->input->post("basic")){
                $basic = convertCurrencyFormatToBaseAmount($this->input->post("basic"));
        }else{
                $basic = 0;  
        }
        
        if($this->input->post("net_salary")){
                $net_salary = convertCurrencyFormatToBaseAmount($this->input->post("net_salary"));
        }else{
                $net_salary = 0;  
        }
        
        if($this->input->post("tax")){
                $tax = convertCurrencyFormatToBaseAmount($this->input->post("tax"));
        }else{
                $tax = 0;  
        }      
 
        $status          = $this->input->post("status");
        $staff_id        = $this->input->post("staff_id");
        $month           = $this->input->post("month");
        $name            = $this->input->post("name");
        $year            = $this->input->post("year");      
        
        $leave_deduction = $this->input->post("leave_deduction");
        
        $this->form_validation->set_rules('net_salary', $this->lang->line('net_salary'), 'trim|required|xss_clean');       
        
        if ($this->form_validation->run() == false) {
            $this->create($month, $year, $staff_id);
        } else {

            $data = array('staff_id' => $staff_id,
                'basic'                  => $basic,
                'total_allowance'        => $total_allowance,
                'total_deduction'        => $total_deduction,
                'net_salary'             => $net_salary,
                'payment_date'           => date("Y-m-d"),
                'status'                 => $status,
                'month'                  => $month,
                'year'                   => $year,
                'tax'                    => $tax,
                'leave_deduction'        => '0',
            );

            $checkForUpdate = $this->payroll_model->checkPayslip($month, $year, $staff_id);
 
            if ($checkForUpdate == true) {

                $insert_id        = $this->payroll_model->createPayslip($data);
                $payslipid        = $insert_id;
                $allowance_type   = $this->input->post("allowance_type");
                $deduction_type   = $this->input->post("deduction_type");
                $allowance_amount = $this->input->post("allowance_amount");
                $deduction_amount = $this->input->post("deduction_amount");
                if (!empty($allowance_type)) {

                    $i = 0;
                    foreach ($allowance_type as $key => $all) {
                        
                        if($allowance_amount[$i]){
                                $allowanceamount = convertCurrencyFormatToBaseAmount($allowance_amount[$i]);
                        }else{
                                $allowanceamount = 0;  
                        } 
                        
                        $all_data = array(
                            'payslip_id'     => $payslipid,
                            'allowance_type' => $allowance_type[$i],
                            'amount'         => $allowanceamount,
                            'staff_id'       => $staff_id,
                            'cal_type'       => "positive",
                        );

                        $insert_payslip_allowance = $this->payroll_model->add_allowance($all_data);

                        $i++;
                    }
                }

                if (!empty($deduction_type)) {
                    $j = 0;
                    foreach ($deduction_type as $key => $type) {
                        
                        if($deduction_amount[$j]){
                                $deductionamount = convertCurrencyFormatToBaseAmount($deduction_amount[$j]);
                        }else{
                                $deductionamount = 0;  
                        }
                        
                        $type_data = array('payslip_id' => $payslipid,
                            'allowance_type'                => $deduction_type[$j],
                            'amount'                        => $deductionamount,
                            'staff_id'                      => $staff_id,
                            'cal_type'                      => "negative",
                        );

                        $insert_payslip_allowance = $this->payroll_model->add_allowance($type_data);

                        $j++;
                    }
                }

                redirect('admin/payroll');
            } else {

                $this->session->set_flashdata("msg", $this->lang->line('payslip_already_generated'));
                redirect('admin/payroll');
            }
        }
    }

    public function search($month, $year, $role = '')
    {
        $user_type              = $this->staff_model->getStaffRole();
        $data['classlist']      = $user_type;
        $data['monthlist']      = $this->customlib->getMonthDropdown();
        $searchEmployee         = $this->payroll_model->searchEmployee($month, $year, $emp_name = '', $role);
        $data["resultlist"]     = $searchEmployee;
        $data["name"]           = $emp_name;
        $data["month"]          = $month;
        $data["year"]           = $year;
        $data['sch_setting']    = $this->sch_setting_detail;
        $data["payroll_status"] = $this->payroll_status;
        $data["resultlist"]     = $searchEmployee;
        $data["payment_mode"]   = $this->payment_mode;
        $this->load->view("layout/header", $data);
        $this->load->view("admin/payroll/stafflist", $data);
        $this->load->view("layout/footer", $data);
    }

    public function paymentRecord()
    {
        $month              = $this->input->get_post("month");
        $year               = $this->input->get_post("year");
        $id                 = $this->input->get_post("staffid");
        $searchEmployee     = $this->payroll_model->searchPayment($id, $month, $year);
        $data['result']     = $searchEmployee;
        $data['net_salary'] = amountFormat($searchEmployee['net_salary']);
        $data['monthlist']  = $this->customlib->getMonthDropdown();
        $data["month"]      = $data['monthlist'][$month];
        $data["year"]       = $year;
        echo json_encode($data);
    }

    public function paymentStatus($status)
    {
        $id          = $this->input->get('id');
        $updateStaus = $this->payroll_model->updatePaymentStatus($status, $id);
        redirect("admin/payroll");
    }

    public function paymentSuccess()
    {
        $payment_mode = $this->input->post("payment_mode");
        $date         = $this->input->post("payment_date");
        $payment_date = date('Y-m-d', strtotime($date));
        $remark       = $this->input->post("remarks");
        $status       = 'paid';
        $payslipid    = $this->input->post("paymentid");
        $this->form_validation->set_rules('payment_mode', $this->lang->line('payment_mode'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('payment_date', $this->lang->line('payment_date'), 'trim|required|xss_clean');
        
        if ($this->form_validation->run() == false) {
            $msg = array(
                'payment_mode' => form_error('payment_mode'),
                'payment_date' => form_error('payment_date'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $data = array('payment_mode' => $payment_mode, 'payment_date' => $this->customlib->dateFormatToYYYYMMDD($date), 'remark' => $remark, 'status' => $status);
            $this->payroll_model->paymentSuccess($data, $payslipid);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function payslipView()
    {
        $data["payment_mode"] = $this->payment_mode;
        $this->load->model("setting_model");
        $setting_result      = $this->setting_model->get();
        $data['settinglist'] = $setting_result[0];
        $id                  = $this->input->post("payslipid");
        $result              = $this->payroll_model->getPayslip($id);
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['staffid_auto_insert'] = $this->sch_setting_detail->staffid_auto_insert;
        if (!empty($result)) {
            $allowance                  = $this->payroll_model->getAllowance($result["id"]);
            $data["allowance"]          = $allowance;
            $positive_allowance         = $this->payroll_model->getAllowance($result["id"], "positive");
            $data["positive_allowance"] = $positive_allowance;
            $negative_allowance         = $this->payroll_model->getAllowance($result["id"], "negative");
            $data["negative_allowance"] = $negative_allowance;
            $data["result"]             = $result;
            $this->load->view("admin/payroll/payslipview", $data);
        } else {
            echo "<div class='alert alert-info'>" . $this->lang->line('no_record_found') . "</div>";
        }
    }

    public function payslippdf()
    {
        $this->load->model("setting_model");
        $setting_result             = $this->setting_model->get();
        $data['settinglist']        = $setting_result[0];
        $id                         = 15;
        $result                     = $this->payroll_model->getPayslip($id);
        $allowance                  = $this->payroll_model->getAllowance($result["id"]);
        $data["allowance"]          = $allowance;
        $positive_allowance         = $this->payroll_model->getAllowance($result["id"], "positive");
        $data["positive_allowance"] = $positive_allowance;
        $negative_allowance         = $this->payroll_model->getAllowance($result["id"], "negative");
        $data["negative_allowance"] = $negative_allowance;
        $data["result"]             = $result;
        $this->load->view("admin/payroll/payslippdf", $data);
    }

    public function payrollreport()
    {
        if (!$this->rbac->hasPrivilege('payroll_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/human_resource');
        $this->session->set_userdata('subsub_menu', 'Reports/attendance/attendance_report');
        $month                = $this->input->post("month");
        $year                 = $this->input->post("year");
        $role                 = $this->input->post("role");
        $data["month"]        = $month;
        $data["year"]         = $year;
        $data["role_select"]  = $role;
        $data['monthlist']    = $this->customlib->getMonthDropdown();
        $data['yearlist']     = $this->payroll_model->payrollYearCount();
        $staffRole            = $this->staff_model->getStaffRole();
        $data["role"]         = $staffRole;
        $data["payment_mode"] = $this->payment_mode;

        $this->form_validation->set_rules('year', $this->lang->line('year'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view("layout/header", $data);
            $this->load->view("admin/payroll/payrollreport", $data);
            $this->load->view("layout/footer", $data);
        } else {
            $result = $this->payroll_model->getpayrollReport($month, $year, $role);
            $data["result"] = $result;
            $this->load->view("layout/header", $data);
            $this->load->view("admin/payroll/payrollreport", $data);
            $this->load->view("layout/footer", $data);
        }
    }

    public function deletepayroll($payslipid, $month, $year, $role = '')
    {
        if (!$this->rbac->hasPrivilege('staff_payroll', 'can_delete')) {
            access_denied();
        }
        if (!empty($payslipid)) {
            $this->payroll_model->deletePayslip($payslipid);
        }

        redirect('admin/payroll/search/' . $month . "/" . $year . "/" . $role);
    }

    public function revertpayroll($payslipid, $month, $year, $role = '')
    {
        if (!$this->rbac->hasPrivilege('staff_payroll', 'can_delete')) {
            access_denied();
        }
        if (!empty($payslipid)) {
            $this->payroll_model->revertPayslipStatus($payslipid);
        }
        redirect('admin/payroll/search/' . $month . "/" . $year . "/" . $role);

    }

}
