<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Timetable extends Student_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->session->set_userdata('top_menu', 'Time_table');
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        $student_id = $this->customlib->getStudentSessionUserID();
        $student = $this->student_model->get($student_id);
        $days = $this->customlib->getDaysname();
        $days_record = array();
        foreach ($days as $day_key => $day_value) {
            $days_record[$day_key] = $this->subjecttimetable_model->getparentSubjectByClassandSectionDay($student_current_class->class_id, $student_current_class->section_id, $day_key);
           
        }
        $data['timetable'] = $days_record;

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/timetable/timetableList', $data);
        $this->load->view('layout/student/footer', $data);
    }

    public function printclasstimetable()
    {
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        $class_id    = $student_current_class->class_id;
        $section_id  = $student_current_class->section_id;
        $days        = $this->customlib->getDaysname();
        $class_section=$this->section_model->getClassAndSectionNameByClassIDSectionID($class_id, $section_id);
        $data['class_section']=$class_section;
        $days_record = array();
        foreach ($days as $day_key => $day_value) {

            $days_record[$day_key] = $this->subjecttimetable_model->getparentSubjectByClassandSectionDay($class_id, $section_id, $day_key);
        }
        $data['timetable']=$days_record;
        $timetable_page = $this->load->view('admin/timetable/_printclasstimetable', $data, true);
        $json_array = array('status' => '1', 'error' => '', 'page' => $timetable_page);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json_array));
    }

 
}