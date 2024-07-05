<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Visitors extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'visitors');
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        $student_session_id    = $student_current_class->student_session_id;
        $data['visitor_list']  = $this->visitors_model->visitorbystudentid($student_session_id);
        $this->load->view('layout/student/header');
        $this->load->view('user/visitor/visitorview', $data);
        $this->load->view('layout/student/footer');
    }

    public function download($id)
    {
        $visitorlist = $this->visitors_model->visitors_list($id);
        $this->media_storage->filedownload($visitorlist['image'], "./uploads/front_office/visitors");

    }
}
