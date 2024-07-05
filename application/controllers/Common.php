<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Common extends Public_Controller
{

    public function __construct()
    {
        parent::__construct();
		$this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function parents()
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        $sql        = "SELECT * FROM `users` WHERE role='parent'";
        $query      = $this->db->query($sql);
        $par_result = $query->result();
        foreach ($par_result as $res_key => $res_value) {
            $ids = explode(",", $res_value->childs);
            $this->db->where_in('id', $ids);
            $this->db->update('students', array('parent_id' => $res_value->id));
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function getSudentSessions()
    {
        $student_id          = $this->customlib->getStudentSessionUserID();
        $session             = $this->session_model->getStudentAcademicSession($student_id);
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
        $this->load->view('partial/_session', $data);
    }

    public function getStudentSessionClasses()
    {
        $data = array();

        $role         = $this->customlib->getUserRole();
        $data['role'] = $role;
        if ($role == "student") {
            $student_id             = $this->customlib->getStudentSessionUserID();
            $data['studentclasses'] = $this->studentsession_model->searchMultiClsSectionByStudent($student_id);
        } elseif ($role == "parent") {
            $parent_id              = $this->customlib->getUsersID();
            $data['studentclasses'] = $this->student_model->getParentChilds($parent_id);
        }
		$data['sch_setting'] = $this->sch_setting_detail;
        $page = $this->load->view('partial/_studentSessionClasses', $data, true);
        echo json_encode(array('page' => $page));
    }

    public function getStudentClass()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('clschg', 'clschg', 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'clschg' => form_error('clschg'),
            );
            $array = array('status' => 0, 'error' => $data, 'message' => 'Something went wrong');
            echo json_encode($array);
        } else {
           //===================          
            $student_session_id = $this->input->post('clschg');

            $student        = $this->student_model->getByStudentSession($student_session_id);
            $logged_In_User = $this->customlib->getLoggedInUserData();

            $logged_In_User['student_id'] = $student['id'];

            $current_class = $this->session->has_userdata('current_class');
            if ($current_class) {
                $this->session->unset_userdata('current_class');
            }

            $this->session->set_userdata('student', $logged_In_User);
            $student_current_class = array('class_id' => $student['class_id'], 'section_id' => $student['section_id'], 'student_session_id' => $student['student_session_id']);
            $this->session->set_userdata('current_class', $student_current_class);

            //==================

            $array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function getAllSession()
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
        $this->load->view('partial/_session', $data);
    }

    public function updateSession()
    {
        $role = $this->customlib->getUserRole();        
        $redirect_url = site_url('site/userlogin');
        if ($role == "teacher") {
            $redirect_url = site_url('teacher/teacher/dashboard');
        } elseif ($role == 'accountant') {
            $redirect_url = site_url('accountant/accountant/dashboard');
        }

        $session       = $this->input->post('popup_session');
        $session_array = $this->session->has_userdata('session_array');
        if ($session_array) {
            $this->session->unset_userdata('session_array');
        }
        $session = $this->session_model->get($session);

        $session_array = array('session_id' => $session['id'], 'session' => $session['session']);
        $this->session->set_userdata('session_array', $session_array);

        if ($role == "student"  || $role == "parent") {
            $session                 = $this->input->post('popup_session');
            $session_Array           = $this->session->userdata('student');
            $student_id              = $session_Array['student_id'];
            $student_display_session = $this->studentsession_model->searchActiveClassSectionStudent($student_id, $session);
            $student_current_class   = array('student_session_id' => $student_display_session->id, 'class_id' => $student_display_session->class_id, 'section_id' => $student_display_session->section_id);
            $this->session->unset_userdata('current_class');
            $this->session->set_userdata('current_class', $student_current_class);
        }

        echo json_encode(array('status' => 1, 'message' => $this->lang->line('session_changed_successfully'), 'redirect_url' => $redirect_url));
    }
}