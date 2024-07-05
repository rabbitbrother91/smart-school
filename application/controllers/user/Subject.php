<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Subject extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'Subjects');
        $this->session->set_userdata('sub_menu', 'subject/index');
        $data['title']       = 'Add Subject';
        $stuid               = $this->session->userdata('student');
        $stu_record          = $this->student_model->getRecentRecord($stuid['student_id']);
        $subject_result      = $this->teachersubject_model->getSubjectByClsandSection($stu_record['class_id'], $stu_record['section_id']);
        $data['subjectlist'] = $subject_result;
        $this->load->view('layout/student/header', $data);
        $this->load->view('user/subject/subjectList', $data);
        $this->load->view('layout/student/footer', $data);
    }

    public function view($id)
    {
        $data['title']   = 'Subject List';
        $subject         = $this->subject_model->get($id);
        $data['subject'] = $subject;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/subject/subjectShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        $data['title'] = 'Subject List';
        $this->subject_model->remove($id);
        redirect('admin/subject/index');
    }

    public function create()
    {
        $data['title']       = 'Add subject';
        $subject_result      = $this->subject_model->get();
        $data['subjectlist'] = $subject_result;
        $this->form_validation->set_rules('name', 'First Name', 'trim|required|callback__check_name_exists');
        if ($this->input->post('code')) {
            $this->form_validation->set_rules('code', 'Code', 'trim|required|xss_clean|is_unique[subjects.code]');
            $this->form_validation->set_message('is_unique', '%s is already exists');
        }
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/subject/subjectCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'type' => $this->input->post('type'),
            );
            $this->subject_model->add($data);
            $this->session->set_flashdata('msg', '<div subject="alert alert-success text-center">Employee details added to Database!!!</div>');
            redirect('admin/subject/index');
        }
    }

    public function _check_name_exists()
    {
        $data['name'] = $this->security->xss_clean($this->input->post('name'));
        $data['type'] = $this->security->xss_clean($this->input->post('type'));
        if ($this->subject_model->check_data_exists($data)) {
            $this->form_validation->set_message('_check_name_exists', 'Record already exists');
            return false;
        } else {
            return true;
        }
    }

    public function edit($id)
    {
        $subject_result      = $this->subject_model->get();
        $data['subjectlist'] = $subject_result;
        $data['title']       = 'Edit Subject';
        $data['id']          = $id;
        $subject             = $this->subject_model->get($id);
        $data['subject']     = $subject;
        $this->form_validation->set_rules('name', 'Subject', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/subject/subjectEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'   => $id,
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
            );
            $this->subject_model->add($data);
            $this->session->set_flashdata('msg', '<div subject="alert alert-success text-center">Employee details added to Database!!!</div>');
            redirect('admin/subject/index');
        }
    }

    public function getSubjctByClassandSection()
    {
        $class_id   = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $date       = $this->teachersubject_model->getSubjectByClsandSection($class_id, $section_id);
        echo json_encode($data);
    }

}
