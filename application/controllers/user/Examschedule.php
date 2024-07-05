<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ExamSchedule extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'examSchedule/index');
        $data['title'] = 'Exam Schedule';
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        $student_session_id    = $student_current_class->student_session_id;
        $examSchedule          = $this->examgroupstudent_model->studentExams($student_session_id);
        $data['examSchedule']  = $examSchedule;
        $this->load->view('layout/student/header', $data);
        $this->load->view('user/exam_schedule/examList', $data);
        $this->load->view('layout/student/footer', $data);
    }

    public function getexamscheduledetail()
    {
        $subjects                 = array();
        $exam_id                  = $this->input->post('exam_id');
        $subjects['subject_list'] = $this->batchsubject_model->getExamstudentSubjects($exam_id);
        $result                   = $this->load->view('user/exam_schedule/_getexamscheduledetail', $subjects, true);
        echo json_encode(array('status' => 1, 'result' => $result));
    }

}
