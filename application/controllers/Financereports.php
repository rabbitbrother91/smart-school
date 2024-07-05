<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Financereports extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->time               = strtotime(date('d-m-Y H:i:s'));
        $this->payment_mode       = $this->customlib->payment_mode();
        $this->search_type        = $this->customlib->get_searchtype();
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->library('media_storage');
        $this->load->model("module_model");
    }

    public function finance()
    {
        $this->session->set_userdata('top_menu', 'Financereports');
        $this->session->set_userdata('sub_menu', 'Financereports/finance');
        $this->session->set_userdata('subsub_menu', '');
        $this->load->view('layout/header');
        $this->load->view('financereports/finance');
        $this->load->view('layout/footer');
    }

    public function reportduefees()
    {
        if (!$this->rbac->hasPrivilege('balance_fees_statement', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/reportduefees');
        $data                = array();
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['sch_setting'] = $this->sch_setting_detail;
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $date               = date('Y-m-d');
            $class_id           = $this->input->post('class_id');
            $section_id         = $this->input->post('section_id');
            $data['class_id']   = $class_id;
            $data['section_id'] = $section_id;
            $fees_dues          = $this->studentfeemaster_model->getStudentDueFeeTypesByDate($date, $class_id, $section_id);
            $students_list      = array();

            if (!empty($fees_dues)) {
                foreach ($fees_dues as $fee_due_key => $fee_due_value) {
                    $amount_paid = 0;

                    if (isJSON($fee_due_value->amount_detail)) {
                        $student_fees_array = json_decode($fee_due_value->amount_detail);
                        foreach ($student_fees_array as $fee_paid_key => $fee_paid_value) {
                            $amount_paid += ($fee_paid_value->amount + $fee_paid_value->amount_discount);
                        }
                    }
                    if ($amount_paid < $fee_due_value->fee_amount || ($amount_paid < $fee_due_value->amount && $fee_due_value->is_system)) {

                        $students_list[$fee_due_value->student_session_id]['admission_no']             = $fee_due_value->admission_no;
                        $students_list[$fee_due_value->student_session_id]['class_id']             = $fee_due_value->class_id;
                        $students_list[$fee_due_value->student_session_id]['section_id']             = $fee_due_value->section_id;
                        $students_list[$fee_due_value->student_session_id]['student_id']             = $fee_due_value->student_id;
                        $students_list[$fee_due_value->student_session_id]['roll_no']                  = $fee_due_value->roll_no;
                        $students_list[$fee_due_value->student_session_id]['admission_date']           = $fee_due_value->admission_date;
                        $students_list[$fee_due_value->student_session_id]['firstname']                = $fee_due_value->firstname;
                        $students_list[$fee_due_value->student_session_id]['middlename']               = $fee_due_value->middlename;
                        $students_list[$fee_due_value->student_session_id]['lastname']                 = $fee_due_value->lastname;
                        $students_list[$fee_due_value->student_session_id]['father_name']              = $fee_due_value->father_name;
                        $students_list[$fee_due_value->student_session_id]['image']                    = $fee_due_value->image;
                        $students_list[$fee_due_value->student_session_id]['mobileno']                 = $fee_due_value->mobileno;
                        $students_list[$fee_due_value->student_session_id]['email']                    = $fee_due_value->email;
                        $students_list[$fee_due_value->student_session_id]['state']                    = $fee_due_value->state;
                        $students_list[$fee_due_value->student_session_id]['city']                     = $fee_due_value->city;
                        $students_list[$fee_due_value->student_session_id]['pincode']                  = $fee_due_value->pincode;
                        $students_list[$fee_due_value->student_session_id]['class']                    = $fee_due_value->class;
                        $students_list[$fee_due_value->student_session_id]['section']                  = $fee_due_value->section;
                        $students_list[$fee_due_value->student_session_id]['fee_groups_feetype_ids'][] = $fee_due_value->fee_groups_feetype_id;
                    }
                }
            }

            if (!empty($students_list)) {
                foreach ($students_list as $student_key => $student_value) {
                    $students_list[$student_key]['fees_list'] = $this->studentfeemaster_model->studentDepositByFeeGroupFeeTypeArray($student_key, $student_value['fee_groups_feetype_ids']);
                    $students_list[$student_key]['transport_fees']       = array();
                    $student               = $this->student_model->getByStudentSession($student_value['student_id']);

                    if(!empty($student)){
                        $route_pickup_point_id = $student['route_pickup_point_id'];                    
                        $student_session_id    = $student['student_session_id'];
                    }else{
                        $route_pickup_point_id = '';                    
                        $student_session_id    = '';   
                    }
                    
                    $transport_fees = [];
                    $module = $this->module_model->getPermissionByModulename('transport');

                    if ($module['is_active']) {
                        $transport_fees        = $this->studentfeemaster_model->getStudentTransportFees($student_session_id, $route_pickup_point_id);
                    }
                    $students_list[$student_key]['transport_fees']       = $transport_fees;
                }
            }

            $data['student_due_fee'] = $students_list;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('financereports/reportduefees', $data);
        $this->load->view('layout/footer', $data);
    }

    public function printreportduefees()
    {
        $data                = array();
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['sch_setting'] = $this->sch_setting_detail;
        $date                = date('Y-m-d');
        $class_id            = $this->input->post('class_id');
        $section_id          = $this->input->post('section_id');
        $data['class_id']    = $class_id;
        $data['section_id']  = $section_id;
        $fees_dues           = $this->studentfeemaster_model->getStudentDueFeeTypesByDate($date, $class_id, $section_id);
        $students_list       = array();

        if (!empty($fees_dues)) {
            foreach ($fees_dues as $fee_due_key => $fee_due_value) {
                $amount_paid = 0;

                if (isJSON($fee_due_value->amount_detail)) {
                    $student_fees_array = json_decode($fee_due_value->amount_detail);
                    foreach ($student_fees_array as $fee_paid_key => $fee_paid_value) {
                        $amount_paid += ($fee_paid_value->amount + $fee_paid_value->amount_discount);
                    }
                }
                // if ($amount_paid < $fee_due_value->fee_amount) {
                if ($amount_paid < $fee_due_value->fee_amount || ($amount_paid < $fee_due_value->amount && $fee_due_value->is_system)) {
                    $students_list[$fee_due_value->student_session_id]['admission_no']             = $fee_due_value->admission_no;
                    $students_list[$fee_due_value->student_session_id]['class_id']             = $fee_due_value->class_id;
                    $students_list[$fee_due_value->student_session_id]['section_id']             = $fee_due_value->section_id;
                    $students_list[$fee_due_value->student_session_id]['student_id']             = $fee_due_value->student_id;
                    $students_list[$fee_due_value->student_session_id]['roll_no']                  = $fee_due_value->roll_no;
                    $students_list[$fee_due_value->student_session_id]['admission_date']           = $fee_due_value->admission_date;
                    $students_list[$fee_due_value->student_session_id]['firstname']                = $fee_due_value->firstname;
                    $students_list[$fee_due_value->student_session_id]['middlename']               = $fee_due_value->middlename;
                    $students_list[$fee_due_value->student_session_id]['lastname']                 = $fee_due_value->lastname;
                    $students_list[$fee_due_value->student_session_id]['father_name']              = $fee_due_value->father_name;
                    $students_list[$fee_due_value->student_session_id]['image']                    = $fee_due_value->image;
                    $students_list[$fee_due_value->student_session_id]['mobileno']                 = $fee_due_value->mobileno;
                    $students_list[$fee_due_value->student_session_id]['email']                    = $fee_due_value->email;
                    $students_list[$fee_due_value->student_session_id]['state']                    = $fee_due_value->state;
                    $students_list[$fee_due_value->student_session_id]['city']                     = $fee_due_value->city;
                    $students_list[$fee_due_value->student_session_id]['pincode']                  = $fee_due_value->pincode;
                    $students_list[$fee_due_value->student_session_id]['class']                    = $fee_due_value->class;
                    $students_list[$fee_due_value->student_session_id]['section']                  = $fee_due_value->section;
                    $students_list[$fee_due_value->student_session_id]['fee_groups_feetype_ids'][] = $fee_due_value->fee_groups_feetype_id;
                }
            }
        }

        if (!empty($students_list)) {
            foreach ($students_list as $student_key => $student_value) {
                $students_list[$student_key]['fees_list'] = $this->studentfeemaster_model->studentDepositByFeeGroupFeeTypeArray($student_key, $student_value['fee_groups_feetype_ids']);
                $students_list[$student_key]['transport_fees']       = array();
                $student               = $this->student_model->getByStudentSession($student_value['student_id']);

                $route_pickup_point_id = $student['route_pickup_point_id'];
                $student_session_id    = $student['student_session_id'];
                $transport_fees = [];
                $module = $this->module_model->getPermissionByModulename('transport');

                if ($module['is_active']) {

                    $transport_fees        = $this->studentfeemaster_model->getStudentTransportFees($student_session_id, $route_pickup_point_id);
                }
                $students_list[$student_key]['transport_fees']       = $transport_fees;
            }
        }
        $data['student_due_fee'] = $students_list;
        $page                    = $this->load->view('financereports/_printreportduefees', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function reportdailycollection()
    {
        if (!$this->rbac->hasPrivilege('daily_collection_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/reportdailycollection');
        $data          = array();
        $data['title'] = 'Daily Collection Report';
        $this->form_validation->set_rules('date_from', $this->lang->line('date_from'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date_to', $this->lang->line('date_to'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == true) {

            $date_from          = $this->input->post('date_from');
            $date_to            = $this->input->post('date_to');
            $formated_date_from = strtotime($this->customlib->dateFormatToYYYYMMDD($date_from));
            $formated_date_to   = strtotime($this->customlib->dateFormatToYYYYMMDD($date_to));
            $st_fees            = $this->studentfeemaster_model->getCurrentSessionStudentFees();
            $fees_data          = array();

            for ($i = $formated_date_from; $i <= $formated_date_to; $i += 86400) {
                $fees_data[$i]['amt']                       = 0;
                $fees_data[$i]['count']                     = 0;
                $fees_data[$i]['student_fees_deposite_ids'] = array();
            }

            if (!empty($st_fees)) {
                foreach ($st_fees as $fee_key => $fee_value) {
                    if (isJSON($fee_value->amount_detail)) {
                        $fees_details = (json_decode($fee_value->amount_detail));
                        if (!empty($fees_details)) {
                            foreach ($fees_details as $fees_detail_key => $fees_detail_value) {
                                $date = strtotime($fees_detail_value->date);
                                if ($date >= $formated_date_from && $date <= $formated_date_to) {
                                    if (array_key_exists($date, $fees_data)) {
                                        $fees_data[$date]['amt'] += $fees_detail_value->amount + $fees_detail_value->amount_fine;
                                        $fees_data[$date]['count'] += 1;
                                        $fees_data[$date]['student_fees_deposite_ids'][] = $fee_value->student_fees_deposite_id;
                                    } else {
                                        $fees_data[$date]['amt']                         = $fees_detail_value->amount + $fees_detail_value->amount_fine;
                                        $fees_data[$date]['count']                       = 1;
                                        $fees_data[$date]['student_fees_deposite_ids'][] = $fee_value->student_fees_deposite_id;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $data['fees_data'] = $fees_data;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('financereports/reportdailycollection', $data);
        $this->load->view('layout/footer', $data);
    }

    public function feeCollectionStudentDeposit()
    {
        $data                 = array();
        $date                 = $this->input->post('date');
        $fees_id              = $this->input->post('fees_id');
        $fees_id_array        = explode(',', $fees_id);
        $fees_list            = $this->studentfeemaster_model->getFeesDepositeByIdArray($fees_id_array);
        $data['student_list'] = $fees_list;
        $data['date']         = $date;
        $data['sch_setting']  = $this->sch_setting_detail;
        $page                 = $this->load->view('financereports/_feeCollectionStudentDeposit', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function reportbyname()
    {
        if (!$this->rbac->hasPrivilege('fees_statement', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/reportbyname');
        $data['title']       = 'student fees';
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['sch_setting'] = $this->sch_setting_detail;

        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('financereports/reportByName', $data);
            $this->load->view('layout/footer', $data);
        } else { {
                $data['student_due_fee'] = array();
                $class_id                = $this->input->post('class_id');
                $section_id              = $this->input->post('section_id');
                $student_id              = $this->input->post('student_id');
                $student_due_fee         = $this->studentfeemaster_model->getStudentFeesByClassSectionStudent($class_id, $section_id, $student_id);
                foreach ($student_due_fee as $key => $value) {
                    $transport_fees = array();
                    $student               = $this->student_model->getByStudentSession($value['student_id']);
                    
                    if($student){
                    $route_pickup_point_id = $student['route_pickup_point_id'];
                    $student_session_id    = $student['student_session_id'];
                    }else{
                        $route_pickup_point_id = '';
                        $student_session_id    = '';
                    }
                    $transport_fees = [];
                    $module = $this->module_model->getPermissionByModulename('transport');

                    if ($module['is_active']) {

                        $transport_fees        = $this->studentfeemaster_model->getStudentTransportFees($student_session_id, $route_pickup_point_id);
                    }
                    $student_due_fee[$key]['transport_fees']         = $transport_fees;
                }

                $data['student_due_fee'] = $student_due_fee;
                $data['class_id']        = $class_id;
                $data['section_id']      = $section_id;
                $data['student_id']      = $student_id;
                $category                = $this->category_model->get();
                $data['categorylist']    = $category;
                $this->load->view('layout/header', $data);
                $this->load->view('financereports/reportByName', $data);
                $this->load->view('layout/footer', $data);
            }
        }
    }

    public function studentacademicreport()
    {
        if (!$this->rbac->hasPrivilege('balance_fees_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/studentacademicreport');
        $data['title']           = 'student fee';
        $data['payment_type']    = $this->customlib->getPaymenttype();
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $this->form_validation->set_rules('search_type', $this->lang->line('search_type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data['student_due_fee'] = array();
            $data['resultarray']     = array();
            $data['feetype']     = "";
            $data['feetype_arr'] = array();
        } else {
            $student_Array = array();
            $search_type   = $this->input->post('search_type');
            $class_id   = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');

            if (isset($class_id)) {
                $studentlist = $this->student_model->searchByClassSectionWithSession($class_id, $section_id);
            } else {
                $studentlist = $this->student_model->getStudents();
            }

            $student_Array = array();
            if (!empty($studentlist)) {
                foreach ($studentlist as $key => $eachstudent) {
                    $obj                = new stdClass();
                    $obj->name          = $this->customlib->getFullName($eachstudent['firstname'], $eachstudent['middlename'], $eachstudent['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname);
                    $obj->class         = $eachstudent['class'];
                    $obj->section       = $eachstudent['section'];
                    $obj->admission_no  = $eachstudent['admission_no'];
                    $obj->roll_no       = $eachstudent['roll_no'];
                    $obj->father_name   = $eachstudent['father_name'];
                    $student_session_id = $eachstudent['student_session_id'];
                    $student_total_fees = $this->studentfeemaster_model->getTransStudentFees($student_session_id);

                    if (!empty($student_total_fees)) {
                        $totalfee = 0;
                        $deposit  = 0;
                        $discount = 0;
                        $balance  = 0;
                        $fine     = 0;
                        
                        foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {

                            if (!empty($student_total_fees_value->fees)) {
                                foreach ($student_total_fees_value->fees as $each_fee_key => $each_fee_value) {
                                    $totalfee = $totalfee + $each_fee_value->amount;
                                    
                                    if(isJSON($each_fee_value->amount_detail)){                                        
                                        $amount_detail = json_decode($each_fee_value->amount_detail);
    
                                        if (is_object($amount_detail) && !empty($amount_detail)) {
                                            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                                $deposit  = $deposit + $amount_detail_value->amount;
                                                $fine     = $fine + $amount_detail_value->amount_fine;
                                                $discount = $discount + $amount_detail_value->amount_discount;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        $obj->totalfee     = $totalfee;
                        $obj->payment_mode = "N/A";
                        $obj->deposit      = $deposit;
                        $obj->fine         = $fine;
                        $obj->discount     = $discount;
                        $obj->balance      = $totalfee - ($deposit + $discount);
                    } else {

                        $obj->totalfee     = 0;
                        $obj->payment_mode = 0;
                        $obj->deposit      = 0;
                        $obj->fine         = 0;
                        $obj->balance      = 0;
                        $obj->discount     = 0;
                    }

                    if ($search_type == 'all') {
                        $student_Array[] = $obj;
                    } elseif ($search_type == 'balance') {
                        if ($obj->balance > 0) {
                            $student_Array[] = $obj;
                        }
                    } elseif ($search_type == 'paid') {
                        if ($obj->balance <= 0) {
                            $student_Array[] = $obj;
                        }
                    }
                }
            }

            $classlistdata[]         = array('result' => $student_Array);
            $data['student_due_fee'] = $student_Array;
            $data['resultarray']     = $classlistdata;
      
        }

        $this->load->view('layout/header', $data);
        $this->load->view('financereports/studentAcademicReport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function collection_report()
    {
        if (!$this->rbac->hasPrivilege('collect_fees', 'can_view')) {
            access_denied();
        }

        $data['collect_by']  = $this->studentfeemaster_model->get_feesreceived_by();
        $data['searchlist']  = $this->customlib->get_searchtype();
        $data['group_by']    = $this->customlib->get_groupby();
        $feetype             = $this->feetype_model->get();
        $tnumber = count($feetype);
        $feetype[$tnumber] = array('id' => 'transport_fees', 'type' => 'Transport Fees');

        $data['feetypeList'] = $feetype;
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/collection_report');
        $subtotal = false;

        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }

        if (isset($_POST['collect_by']) && $_POST['collect_by'] != '') {
            $data['received_by'] = $received_by = $_POST['collect_by'];
        } else {
            $data['received_by'] = $received_by = '';
        }

        if (isset($_POST['feetype_id']) && $_POST['feetype_id'] != '') {
            $feetype_id = $_POST['feetype_id'];
        } else {
            $feetype_id = "";
        }

        if (isset($_POST['group']) && $_POST['group'] != '') {
            $data['group_byid'] = $group = $_POST['group'];
            $subtotal           = true;
        } else {
            $data['group_byid'] = $group = '';
        }

        $collect_by = array();
        $collection = array();
        $start_date = date('Y-m-d', strtotime($dates['from_date']));
        $end_date   = date('Y-m-d', strtotime($dates['to_date']));

        $this->form_validation->set_rules('search_type', $this->lang->line('search_duration'), 'trim|required|xss_clean');

        $data['classlist']        = $this->class_model->get();
        $data['selected_section'] = '';

        if ($this->form_validation->run() == false) {
            $data['results'] = array();
        } else {

            $class_id   = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');

            $data['selected_section'] = $section_id;

            $data['results'] = $this->studentfeemaster_model->getFeeCollectionReport($start_date, $end_date, $feetype_id, $received_by, $group, $class_id, $section_id);

            if ($group != '') {

                if ($group == 'class') {
                    $group_by = 'class_id';
                } elseif ($group == 'collection') {
                    $group_by = 'received_by';
                } elseif ($group == 'mode') {
                    $group_by = 'payment_mode';
                }

                foreach ($data['results'] as $key => $value) {
                    $collection[$value[$group_by]][] = $value;
                }
            } else {

                $s = 0;
                foreach ($data['results'] as $key => $value) {
                    $collection[$s++] = array($value);
                }
            }

            $data['results'] = $collection;
        }
        $data['subtotal']    = $subtotal;

        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/collection_report', $data);
        $this->load->view('layout/footer', $data);
    }

    public function onlinefees_report()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/onlinefees_report');
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['group_by']   = $this->customlib->get_groupby();

        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }

        $collection = array();
        $start_date = date('Y-m-d', strtotime($dates['from_date']));
        $end_date   = date('Y-m-d', strtotime($dates['to_date']));
        $this->form_validation->set_rules('search_type', $this->lang->line('search_type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data['collectlist'] = array();
        } else {
            $data['collectlist'] = $this->studentfeemaster_model->getOnlineFeeCollectionReport($start_date, $end_date);
        }

        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/onlineFeesReport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function duefeesremark()
    {
        if (!$this->rbac->hasPrivilege('balance_fees_report_with_remark', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/duefeesremark');
        $data                = array();
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == true) {
            $date               = date('Y-m-d');
            $class_id           = $this->input->post('class_id');
            $section_id         = $this->input->post('section_id');
            $data['class_id']   = $class_id;
            $data['section_id'] = $section_id;
            $date               = date('Y-m-d');
            $student_due_fee    = $this->studentfee_model->getDueStudentFeesByDateClassSection($class_id, $section_id, $date);
            $students = array();
            if (!empty($student_due_fee)) {
                foreach ($student_due_fee as $student_due_fee_key => $student_due_fee_value) {

                    $amt_due = ($student_due_fee_value['is_system']) ? $student_due_fee_value['previous_balance_amount'] : $student_due_fee_value['amount'];

                    $a = json_decode($student_due_fee_value['amount_detail']);


                    if (!empty($a)) {
                        $amount          = 0;
                        $amount_discount = 0;
                        $amount_fine     = 0;

                        foreach ($a as $a_key => $a_value) {
                            $amount          = $amount + $a_value->amount;
                            $amount_discount = $amount_discount + $a_value->amount_discount;
                            $amount_fine     = $amount_fine + $a_value->amount_fine;
                        }
                        if ($amt_due <= ($amount + $amount_discount)) {
                            unset($student_due_fee[$student_due_fee_key]);
                        } else {

                            if (!array_key_exists($student_due_fee_value['student_session_id'], $students)) {
                                $students[$student_due_fee_value['student_session_id']] = $this->add_new_student($student_due_fee_value);
                            }

                            $students[$student_due_fee_value['student_session_id']]['fees'][] = array(
                                'is_system' => $student_due_fee_value['is_system'],
                                'amount'          => $amt_due,
                                'amount_deposite' => $amount,
                                'amount_discount' => $amount_discount,
                                'amount_fine'     => $amount_fine,
                                'fee_group'       => $student_due_fee_value['fee_group'],
                                'fee_type'        => $student_due_fee_value['fee_type'],
                                'fee_code'        => $student_due_fee_value['fee_code'],

                            );
                        }
                    } else {
                        $amount          = 0;
                        $amount_discount = 0;

                        if ($amt_due <= ($amount + $amount_discount)) {
                            unset($student_due_fee[$student_due_fee_key]);
                        } else {
                            if (!array_key_exists($student_due_fee_value['student_session_id'], $students)) {

                                $students[$student_due_fee_value['student_session_id']] = $this->add_new_student($student_due_fee_value);
                            }
                            $students[$student_due_fee_value['student_session_id']]['fees'][] = array(
                                'is_system' => $student_due_fee_value['is_system'],
                                'amount'          => $amt_due,
                                'amount_deposite' => 0,
                                'amount_discount' => 0,
                                'amount_fine'     => 0,
                                'fee_group'       => $student_due_fee_value['fee_group'],
                                'fee_type'        => $student_due_fee_value['fee_type'],
                                'fee_code'        => $student_due_fee_value['fee_code'],
                            );
                        }
                    }
                }
            }

            $data['student_remain_fees'] = $students;
        }
        $data['start_month'] = $this->sch_setting_detail->start_month;
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/duefeesremark', $data);
        $this->load->view('layout/footer', $data);
    }

    public function add_new_student($student)
    {
        $new_student = array(
            'id'                 => $student['id'],
            'student_session_id' => $student['student_session_id'],
            'class'              => $student['class'],
            'section_id'         => $student['section_id'],
            'section'            => $student['section'],
            'admission_no'       => $student['admission_no'],
            'roll_no'            => $student['roll_no'],
            'admission_date'     => $student['admission_date'],
            'firstname'          => $student['firstname'],
            'middlename'         => $student['middlename'],
            'lastname'           => $student['lastname'],
            'image'              => $student['image'],
            'mobileno'           => $student['mobileno'],
            'email'              => $student['email'],
            'state'              => $student['state'],
            'city'               => $student['city'],
            'pincode'            => $student['pincode'],
            'religion'           => $student['religion'],
            'dob'                => $student['dob'],
            'current_address'    => $student['current_address'],
            'permanent_address'  => $student['permanent_address'],
            'category_id'        => $student['category_id'],
            'category'           => $student['category'],
            'adhar_no'           => $student['adhar_no'],
            'samagra_id'         => $student['samagra_id'],
            'bank_account_no'    => $student['bank_account_no'],
            'bank_name'          => $student['bank_name'],
            'ifsc_code'          => $student['ifsc_code'],
            'guardian_name'      => $student['guardian_name'],
            'guardian_relation'  => $student['guardian_relation'],
            'guardian_phone'     => $student['guardian_phone'],
            'guardian_address'   => $student['guardian_address'],
            'is_active'          => $student['is_active'],
            'father_name'        => $student['father_name'],
            'rte'                => $student['rte'],
            'gender'             => $student['gender'],

        );
        return $new_student;
    }

    public function printduefeesremark()
    {
        if (!$this->rbac->hasPrivilege('fees_statement', 'can_view')) {
            access_denied();
        }

        $date                = date('Y-m-d');
        $class_id            = $this->input->post('class_id');
        $section_id          = $this->input->post('section_id');
        $data['class_id']    = $class_id;
        $data['section_id']  = $section_id;
        $data['class']       = $this->class_model->get($class_id);
        $data['section']     = $this->section_model->get($section_id);
        $date                = date('Y-m-d');
        $data['sch_setting'] = $this->sch_setting_detail;
        $student_due_fee     = $this->studentfee_model->getDueStudentFeesByDateClassSection($class_id, $section_id, $date);

        $students = array();

        if (!empty($student_due_fee)) {
            foreach ($student_due_fee as $student_due_fee_key => $student_due_fee_value) {
                
                $amt_due = ($student_due_fee_value['is_system']) ? $student_due_fee_value['previous_balance_amount'] : $student_due_fee_value['amount'];

                $a = json_decode($student_due_fee_value['amount_detail']);
                if (!empty($a)) {
                    $amount          = 0;
                    $amount_discount = 0;
                    $amount_fine     = 0;

                    foreach ($a as $a_key => $a_value) {
                        $amount          = $amount + $a_value->amount;
                        $amount_discount = $amount_discount + $a_value->amount_discount;
                        $amount_fine     = $amount_fine + $a_value->amount_fine;
                    }
                    if ($amt_due <= ($amount + $amount_discount)) {
                        unset($student_due_fee[$student_due_fee_key]);
                    } else {

                        if (!array_key_exists($student_due_fee_value['student_session_id'], $students)) {
                            $students[$student_due_fee_value['student_session_id']] = $this->add_new_student($student_due_fee_value);
                        }

                        $students[$student_due_fee_value['student_session_id']]['fees'][] = array(
                            'is_system' => $student_due_fee_value['is_system'],
                            'amount'          => $amt_due,
                            'amount_deposite' => $amount,
                            'amount_discount' => $amount_discount,
                            'amount_fine'     => $amount_fine,
                            'fee_group'       => $student_due_fee_value['fee_group'],
                            'fee_type'        => $student_due_fee_value['fee_type'],
                            'fee_code'        => $student_due_fee_value['fee_code'],
                        );
                    }
                } else {
                    $amount          = 0;
                    $amount_discount = 0;

                    if ($amt_due <= ($amount + $amount_discount)) {
                        unset($student_due_fee[$student_due_fee_key]);
                    } else {
                        if (!array_key_exists($student_due_fee_value['student_session_id'], $students)) {
                            $students[$student_due_fee_value['student_session_id']] = $this->add_new_student($student_due_fee_value);
                        }
                        $students[$student_due_fee_value['student_session_id']]['fees'][] = array(
                            'is_system' => $student_due_fee_value['is_system'],
                            'amount'          => $amt_due,
                            'amount_deposite' => 0,
                            'amount_discount' => 0,
                            'amount_fine'     => 0,
                            'fee_group'       => $student_due_fee_value['fee_group'],
                            'fee_type'        => $student_due_fee_value['fee_type'],
                            'fee_code'        => $student_due_fee_value['fee_code'],
                        );
                    }
                }
            }
        }

        $data['student_remain_fees'] = $students;
        $page = $this->load->view('financereports/_printduefeesremark', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function income()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/income');
        $data['searchlist'] = $this->customlib->get_searchtype();
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/income', $data);
        $this->load->view('layout/footer', $data);
    }

    public function searchreportvalidation()
    {
        $this->form_validation->set_rules('search_type', $this->lang->line('search_type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $error = array();

            $error['search_type'] = form_error('search_type');

            $array = array('status' => 0, 'error' => $error);
            echo json_encode($array);
        } else {
            $search_type = $this->input->post('search_type');
            $date_from   = "";
            $date_to     = "";
            if ($search_type == 'period') {

                $date_from = $this->input->post('date_from');
                $date_to   = $this->input->post('date_to');
            }

            $params = array('search_type' => $search_type, 'date_from' => $date_from, 'date_to' => $date_to);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    public function getincomelistbydt()
    {
        $search_type = $this->input->post('search_type');
        $date_from   = $this->input->post('date_from');
        $date_to     = $this->input->post('date_to');

        if ($search_type == "") {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        } else {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        }

        $start_date = date('Y-m-d', strtotime($dates['from_date']));
        $end_date   = date('Y-m-d', strtotime($dates['to_date']));

        $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));

        $incomeList = $this->income_model->search("", $start_date, $end_date);

        $incomeList      = json_decode($incomeList);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();
        $grand_total     = 0;
        if (!empty($incomeList->data)) {
            foreach ($incomeList->data as $key => $value) {
                $grand_total += $value->amount;

                $row   = array();
                $row[] = $value->name;
                $row[] = $value->invoice_no;
                $row[] = $value->income_category;
                $row[] = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->date));
                $row[] = $currency_symbol . amountFormat($value->amount);
                $dt_data[] = $row;
            }
            $footer_row   = array();
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "<b>" . $this->lang->line('grand_total') . "</b>";
            $footer_row[] = $currency_symbol . amountFormat($grand_total);
            $dt_data[]    = $footer_row;
        }

        $json_data = array(
            "draw"            => intval($incomeList->draw),
            "recordsTotal"    => intval($incomeList->recordsTotal),
            "recordsFiltered" => intval($incomeList->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function expense()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/expense');
        $data['searchlist']  = $this->customlib->get_searchtype();
        $data['date_type']   = $this->customlib->date_type();
        $data['date_typeid'] = '';

        $this->form_validation->set_rules('search_type', $this->lang->line('search_type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        } else {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        }

        $start_date = date('Y-m-d', strtotime($dates['from_date']));
        $end_date   = date('Y-m-d', strtotime($dates['to_date']));

        $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/expense', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getexpenselistbydt()
    {
        $search_type = $this->input->post('search_type');
        $date_from   = $this->input->post('date_from');
        $date_to     = $this->input->post('date_to');

        if ($search_type == "") {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        } else {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        }

        $start_date = date('Y-m-d', strtotime($dates['from_date']));
        $end_date   = date('Y-m-d', strtotime($dates['to_date']));

        $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));
        $expenseList   = $this->expense_model->search('', $start_date, $end_date);

        $m               = json_decode($expenseList);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();
        $grand_total     = 0;
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $grand_total += $value->amount;

                $row       = array();
                $row[]     = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->date));
                $row[]     = $value->exp_category;
                $row[]     = $value->name;
                $row[]     = $value->invoice_no;
                $row[]     = $currency_symbol . amountFormat($value->amount);
                $dt_data[] = $row;
            }
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "<b>" . $this->lang->line('grand_total') . "</b>";
            $footer_row[] = "<b>" . $currency_symbol . amountFormat($grand_total) . "</b>";
            $dt_data[]    = $footer_row;
        }

        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function payroll()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/payroll');
        $data['searchlist']  = $this->customlib->get_searchtype();
        $data['date_type']   = $this->customlib->date_type();
        $data['date_typeid'] = '';

        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {

            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }

        $start_date = date('Y-m-d', strtotime($dates['from_date']));
        $end_date   = date('Y-m-d', strtotime($dates['to_date']));

        $data['label']        = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));
        $data['payment_mode'] = $this->payment_mode;

        $result              = $this->payroll_model->getbetweenpayrollReport($start_date, $end_date);
        $data['payrollList'] = $result;
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/payroll', $data);
        $this->load->view('layout/footer', $data);
    }

    public function incomegroup()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/incomegroup');
        $data['searchlist']  = $this->customlib->get_searchtype();
        $data['date_type']   = $this->customlib->date_type();
        $data['date_typeid'] = '';
        $data['headlist']    = $this->incomehead_model->get();
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/incomegroup', $data);
        $this->load->view('layout/footer', $data);
    }

    public function dtincomegroupreport()
    {
        $search_type = $this->input->post('search_type');
        $date_from   = $this->input->post('date_from');
        $date_to     = $this->input->post('date_to');
        $head        = $this->input->post('head');

        if (isset($search_type) && $search_type != '') {

            $dates               = $this->customlib->get_betweendate($search_type);
            $data['search_type'] = $_POST['search_type'];
        } else {

            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }
        $data['head_id'] = $head_id = "";
        if (isset($_POST['head']) && $_POST['head'] != '') {
            $data['head_id'] = $head_id = $_POST['head'];
        }

        $start_date = date('Y-m-d', strtotime($dates['from_date']));
        $end_date   = date('Y-m-d', strtotime($dates['to_date']));

        $data['label']   = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));
        $incomeList      = $this->income_model->searchincomegroup($start_date, $end_date, $head_id);
        $m               = json_decode($incomeList);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();
        $grand_total     = 0;

        if (!empty($m->data)) {
            $grd_total  = 0;
            $inchead_id = 0;
            $count      = 0;
            foreach ($m->data as $key => $value) {
                $income_head[$value->head_id][] = $value;
            }

            foreach ($m->data as $key => $value) {
                $inc_head_id  = $value->head_id;
                $total_amount = "<b>" . $value->amount . "</b>";
                $grd_total += $value->amount;
                $row = array();
                if ($inchead_id == $inc_head_id) {
                    $row[] = "";
                    $count++;
                } else {
                    $row[] = $value->income_category;
                    $count = 0;
                }
                $row[]      = $value->id;
                $row[]      = $value->name;
                $row[]      = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->date));
                $row[]      = $value->invoice_no;
                $row[]      = amountFormat($value->amount);
                $dt_data[]  = $row;
                $inchead_id = $value->head_id;
                $sub_total  = 0;
                if ($count == (count($income_head[$value->head_id]) - 1)) {
                    foreach ($income_head[$value->head_id] as $inc_headkey => $inc_headvalue) {
                        $sub_total += $inc_headvalue->amount;
                    }
                    $amount_row   = array();
                    $amount_row[] = "";
                    $amount_row[] = "";
                    $amount_row[] = "";
                    $amount_row[] = "";
                    $amount_row[] = "<b>" . $this->lang->line('sub_total') . "</b>";
                    $amount_row[] = "<b>" . $currency_symbol . amountFormat($sub_total) . "</b>";
                    $dt_data[]    = $amount_row;
                }
            }

            $grand_total  = "<b>" . $currency_symbol . amountFormat($grd_total) . "</b>";
            $footer_row   = array();
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "<b>" . $this->lang->line('total') . "</b>";
            $footer_row[] = $grand_total;
            $dt_data[]    = $footer_row;
        }

        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function getgroupreportparam()
    {
        $search_type = $this->input->post('search_type');
        $head        = $this->input->post('head');
        $date_from = "";
        $date_to   = "";
        if ($search_type == 'period') {

            $date_from = $this->input->post('date_from');
            $date_to   = $this->input->post('date_to');
        }

        $params = array('search_type' => $search_type, 'head' => $head, 'date_from' => $date_from, 'date_to' => $date_to);
        $array  = array('status' => 1, 'error' => '', 'params' => $params);
        echo json_encode($array);
    }

    public function expensegroup()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/expensegroup');
        $data['searchlist']  = $this->customlib->get_searchtype();
        $data['date_type']   = $this->customlib->date_type();
        $data['date_typeid'] = '';
        $data['headlist']    = $this->expensehead_model->get();
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/expensegroup', $data);
        $this->load->view('layout/footer', $data);
    }

    public function dtexpensegroupreport()
    {
        $search_type = $this->input->post('search_type');
        $date_from   = $this->input->post('date_from');
        $date_to     = $this->input->post('date_to');
        $head        = $this->input->post('head');

        $data['date_type']   = $this->customlib->date_type();
        $data['date_typeid'] = '';

        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {

            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }

        $data['head_id'] = $head_id = "";
        if (isset($_POST['head']) && $_POST['head'] != '') {
            $data['head_id'] = $head_id = $_POST['head'];
        }

        $start_date = date('Y-m-d', strtotime($dates['from_date']));
        $end_date   = date('Y-m-d', strtotime($dates['to_date']));

        $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));
        $result        = $this->expensehead_model->searchexpensegroup($start_date, $end_date, $head_id);

        $m               = json_decode($result);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();
        $grand_total     = 0;
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $expense_head[$value->exp_head_id][] = $value;
            }

            $grd_total  = 0;
            $exphead_id = 0;
            $count      = 0;
            foreach ($m->data as $key => $value) {

                $exp_head_id  = $value->exp_head_id;
                $total_amount = "<b>" . $value->total_amount . "</b>";
                $grd_total += $value->total_amount;
                $row = array();

                if ($exphead_id == $exp_head_id) {
                    $row[] = "";
                    $count++;
                } else {
                    $row[] = $value->exp_category;
                    $count = 0;
                }

                $row[]      = $value->id;
                $row[]      = $value->name;
                $row[]      = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->date));
                $row[]      = $value->invoice_no;
                $row[]      = amountFormat($value->amount);
                $dt_data[]  = $row;
                $exphead_id = $value->exp_head_id;
                $sub_total  = 0;
                if ($count == (count($expense_head[$value->exp_head_id]) - 1)) {
                    foreach ($expense_head[$value->exp_head_id] as $exp_headkey => $exp_headvalue) {
                        $sub_total += $exp_headvalue->amount;
                    }
                    $amount_row   = array();
                    $amount_row[] = "";
                    $amount_row[] = "";
                    $amount_row[] = "";
                    $amount_row[] = "";
                    $amount_row[] = "<b>" . $this->lang->line('sub_total') . "</b>";
                    $amount_row[] = "<b>" . $currency_symbol . amountFormat($sub_total) . "</b>";
                    $dt_data[]    = $amount_row;
                }
            }

            $grand_total  = "<b>" . $currency_symbol . amountFormat($grd_total) . "</b>";
            $footer_row   = array();
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "<b>" . $this->lang->line('total') . "</b>";
            $footer_row[] = $grand_total;
            $dt_data[]    = $footer_row;
        }

        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function onlineadmission()
    {
        if (!$this->rbac->hasPrivilege('online_admission', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/onlineadmission');
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['group_by']   = $this->customlib->get_groupby();

        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {

            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }

        $collection = array();
        $start_date = date('Y-m-d', strtotime($dates['from_date']));
        $end_date   = date('Y-m-d', strtotime($dates['to_date']));
        $this->form_validation->set_rules('search_type', $this->lang->line('search_type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $data['collectlist'] = array();
        } else {

            $data['collectlist'] = $this->onlinestudent_model->getOnlineAdmissionFeeCollectionReport($start_date, $end_date);
        }
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/onlineadmission', $data);
        $this->load->view('layout/footer', $data);
    }
}
