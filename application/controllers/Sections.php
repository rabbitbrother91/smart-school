<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sections extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('section', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'sections/index');
        $data['title'] = 'Section List';

        $section_result      = $this->section_model->get(); 
        $data['sectionlist'] = $section_result;
        $this->form_validation->set_rules('section', $this->lang->line('section'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('section/sectionList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'section' => $this->input->post('section'),
            );
            $this->section_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('sections/index');
        }
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('section', 'can_view')) {
            access_denied();
        }
        $data['title']   = 'Section List';
        $section         = $this->section_model->get($id);
        $data['section'] = $section;
        $this->load->view('layout/header', $data);
        $this->load->view('section/sectionShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('section', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Section List';
        $this->section_model->remove($id);

        $student_delete=$this->student_model->getUndefinedStudent();
        if(!empty($student_delete)){
            $delte_student_array=array();
            foreach ($student_delete as $student_key => $student_value) {

                $delte_student_array[]=$student_value->id;
            }
            $this->student_model->bulkdelete($delte_student_array);
        }        
        redirect('sections/index');
    }

    public function getByClass()
    {
        $class_id = $this->input->get('class_id');
        $data     = $this->section_model->getClassBySection($class_id);
        echo json_encode($data);
    }

    public function getClassTeacherSection()
    {
        $class_id = $this->input->get('class_id');
        $data     = array();
        $userdata = $this->customlib->getUserData();
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            $id    = $userdata["id"];
            $query = $this->db->where("staff_id", $id)->where("class_id", $class_id)->get("class_teacher");

            if ($query->num_rows() > 0) {

                $data = $this->section_model->getClassTeacherSection($class_id);
            } else {

                $data = $this->section_model->getSubjectTeacherSection($class_id, $id);
            }
        } else {
            $data = $this->section_model->getClassBySection($class_id);
        }
        echo json_encode($data);
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('section', 'can_edit')) {
            access_denied();
        }
        $data['title']       = 'Section List';
        $section_result      = $this->section_model->get();
        $data['sectionlist'] = $section_result;
        $data['title']       = 'Edit Section';
        $data['id']          = $id;
        $section             = $this->section_model->get($id);
        $data['section']     = $section;
        $this->form_validation->set_rules('section', $this->lang->line('section'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('section/sectionEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'      => $id,
                'section' => $this->input->post('section'),
            );
            $this->section_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('sections/index');
        }
    }

}
