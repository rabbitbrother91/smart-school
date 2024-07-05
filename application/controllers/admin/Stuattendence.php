<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Stuattendence extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->config->load("mailsms");
        $this->load->library('mailsmsconf');
        $this->config_attendance = $this->config->item('attendence');
        $this->load->model(array("classteacher_model",'class_section_time_model'));
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('student_attendance', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'stuattendence/index');
        $sch_setting         = $this->setting_model->getSchoolDetail();
        $data['sch_setting'] = $this->sch_setting_detail;
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $userdata            = $this->customlib->getUserData();
        $data['class_id']   = "";
        $data['section_id'] = "";
        $data['date']       = "";
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/attendenceList', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $class              = $this->input->post('class_id');
            $section            = $this->input->post('section_id');
            $date               = $this->input->post('date');
            $data['class_id']   = $class;
            $data['section_id'] = $section;
            $data['date']       = $date;
            $search             = $this->input->post('search');
            $holiday            = $this->input->post('holiday');
            if ($search == "saveattendence") {
                $session_ary = $this->input->post('student_session');

                $insert_data=array();
                $update_data=array();
                $absent_student_list = array();
                foreach ($session_ary as $key => $value) {
                    $checkForUpdate = $this->input->post('attendendence_id' . $value);
                    $arr=array();
                    if ($checkForUpdate != 0) {
                        if (isset($holiday)) {
                            $arr = array(
                                'id'                 => $checkForUpdate,
                                'student_session_id' => $value,
                                'attendence_type_id' => 5,
                                'remark'             => $this->input->post("remark" . $value),
                                'date'               => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                            );
                        } else {
                            $arr = array(
                                'id'                 => $checkForUpdate,
                                'student_session_id' => $value,
                                'attendence_type_id' => $this->input->post('attendencetype' . $value),
                                'remark'             => $this->input->post("remark" . $value),
                                'date'               => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                            );
                        }
                        $update_data[]=$arr;
                       
                    } else {
                        if (isset($holiday)) {
                            $arr = array(
                                'student_session_id' => $value,
                                'attendence_type_id' => 5,
                                'remark'             => $this->input->post("remark" . $value),
                                'date'               => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                            );
                        } else {

                            $arr = array(
                                'student_session_id' => $value,
                                'attendence_type_id' => $this->input->post('attendencetype' . $value),
                                'remark'             => $this->input->post("remark" . $value),
                                'date'               => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                            );
                        }

                        $insert_data[]=$arr;
                   
                        $absent_config = $this->config_attendance['absent'];
                        if ($arr['attendence_type_id'] == $absent_config) {
                            $absent_student_list[] = $value;
                        }
                    }
                }
             
                 $insert_id     = $this->stuattendence_model->add($insert_data,$update_data);
                $absent_config = $this->config_attendance['absent'];

                if (!empty($absent_student_list)) {

                    $this->mailsmsconf->mailsms('absent_attendence', $absent_student_list, $date);
                }

                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>'); 
                redirect('admin/stuattendence/index','refresh'); 
            }
            $attendencetypes             = $this->attendencetype_model->get();
            $data['attendencetypeslist'] = $attendencetypes;
            $resultlist                  = $this->stuattendence_model->searchAttendenceClassSection($class, $section, date('Y-m-d', $this->customlib->datetostrtotime($date)));
            $data['resultlist']          = $resultlist;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/attendenceList', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function attendencereport()
    {
        if (!$this->rbac->hasPrivilege('attendance_by_date', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'stuattendence/attendenceReport');
        $data['title']      = 'Add Fees Type';
        $data['title_list'] = 'Fees Type List';
        $class              = $this->class_model->get();
        $userdata           = $this->customlib->getUserData();

        $role_id = $userdata["role_id"];

        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if ($userdata["class_teacher"] == 'yes') {
                $carray = array();
                $class  = array();
                $class  = $this->teacher_model->get_daywiseattendanceclass($userdata["id"]);
            }
        }
        $data['classlist']  = $class;
        $data['class_id']   = "";
        $data['section_id'] = "";
        $data['date']       = "";
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/attendencereport', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class              = $this->input->post('class_id');
            $section            = $this->input->post('section_id');
            $date               = $this->input->post('date');
            $data['class_id']   = $class;
            $data['section_id'] = $section;
            $data['date']       = $date;
            $search             = $this->input->post('search');
            if ($search == "saveattendence") {
                $session_ary = $this->input->post('student_session');
                foreach ($session_ary as $key => $value) {
                    $checkForUpdate = $this->input->post('attendendence_id' . $value);
                    if ($checkForUpdate != 0) {
                        $arr = array(
                            'id'                 => $checkForUpdate,
                            'student_session_id' => $value,
                            'attendence_type_id' => $this->input->post('attendencetype' . $value),
                            'date'               => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                        );
                        $insert_id = $this->stuattendence_model->add($arr);
                    } else {
                        $arr = array(
                            'student_session_id' => $value,
                            'attendence_type_id' => $this->input->post('attendencetype' . $value),
                            'date'               => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                        );
                        $insert_id = $this->stuattendence_model->add($arr);
                    }
                }
            }
            $attendencetypes             = $this->attendencetype_model->get();
            $data['attendencetypeslist'] = $attendencetypes;
            $resultlist                  = $this->stuattendence_model->searchAttendenceClassSectionPrepare($class, $section, date('Y-m-d', $this->customlib->datetostrtotime($date)));

            $data['resultlist']  = $resultlist;
            $data['sch_setting'] = $this->sch_setting_detail;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/stuattendence/attendencereport', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    

    public function monthAttendance($st_month, $no_of_months, $student_id)
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
    
    public function saveclasstime()
    {
        $this->form_validation->set_rules('row[]', $this->lang->line('section'), 'trim|required|xss_clean');
        $class_sections=$this->input->post('class_section_id');
        $time_valid=true;

       if(!empty($class_sections) && isset($class_sections)){
        foreach ($class_sections as $class_sections_key => $class_sections_value) {
        if($class_sections_value == ""){
             $this->form_validation->set_rules('time', $this->lang->line('time'), 'trim|required|xss_clean');
              $time_valid=false;
                break;
        }
        }
       }

        if ($this->form_validation->run() == false) {
            $msg = array(
                'row' => form_error('row')
            );
            if(!$time_valid){
                $msg['time']= form_error('time');
            }

            $array = array('status' => 0, 'error' => $msg, 'message' => '');
        } else {

        $insert_data=array();
        $update_data=array();

         $prev_records=$this->input->post('prev_record_id');
           if(!empty($class_sections) && isset($class_sections)){
            foreach ($class_sections as $class_sections_key => $class_sections_value) {

              if($prev_records[$class_sections_key] > 0){
                 $update_data[]=array(
                        'id'=>$prev_records[$class_sections_key],
                        'class_section_id'=>$class_sections_key,
                        'time'=>$this->customlib->timeFormat($class_sections_value, true),
                    );

              }else{
                 $insert_data[]=array(
                        'class_section_id'=>$class_sections_key,
                        'time'=>$this->customlib->timeFormat($class_sections_value, true),
                    );
              }                  
               
             }
            }

             $this->class_section_time_model->add($insert_data, $update_data);

             $array = array('status' =>1 , 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);

    }
}
