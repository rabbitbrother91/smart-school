<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Batchsubject extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('batch', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Batch Subject');
        $this->session->set_userdata('sub_menu', 'batchsubject/index');
        $data['title']       = 'Add Batch Subject';
        $data['title_list']  = 'Recent Batch Subject';
        $class               = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']   = $class;
        $subject             = $this->subject_model->get();
        $data['subjectlist'] = $subject;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('batch_id', $this->lang->line('batch'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        $this->form_validation->set_rules(
            'subject_id', $this->lang->line('subject'), array(
                'required',
                array('check_batchsubjectexists', array($this->batchsubject_model, 'valid_batchsubject')),
            )
        );

        if ($this->form_validation->run() == true) {
            $is_exam = isset($_POST['is_exam']) ? 1 : 0;
            $data    = array(
                'class_section_id' => $this->input->post('section_id'),
                'batch_id'         => $this->input->post('batch_id'),
                'subject_id'       => $this->input->post('subject_id'),
                'is_exam'          => $is_exam,
            );
            $insert_id = $this->batchsubject_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/batchsubject/index');
        }

        $batch_result      = $this->batchsubject_model->get();
        $data['batchlist'] = $batch_result;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/batchsubject/batchsubjectList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('batch', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Delete Batch Subject';
        $this->batchsubject_model->remove($id);
        redirect('admin/batchsubject');
    }

    public function deletegrp($id)
    {
        if (!$this->rbac->hasPrivilege('batch', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Delete Batch Subject';
        $this->batchsubject_model->removeGroup($id);
        redirect('admin/batchsubject');
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('batch', 'can_edit')) {
            access_denied();
        }

        $data['id']          = $id;
        $class               = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']   = $class;
        $batch               = $this->batchsubject_model->getByID($id);
        $data['batch']       = $batch;
        $subject             = $this->subject_model->get();
        $data['subjectlist'] = $subject;
        $batch_result        = $this->batchsubject_model->get();
        $data['batchlist']   = $batch_result;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('batch_id', $this->lang->line('batch'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules(
            'subject_id', $this->lang->line('subject'), array(
                'required',
                array('check_batchsubjectexists', array($this->batchsubject_model, 'valid_batchsubject')),
            )
        );
        
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/batchsubject/batchsubjectEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $is_exam = isset($_POST['is_exam']) ? 1 : 0;
            $data    = array(
                'id'               => $this->input->post('id'),
                'class_section_id' => $this->input->post('section_id'),
                'batch_id'         => $this->input->post('batch_id'),
                'subject_id'       => $this->input->post('subject_id'),
                'is_exam'          => $is_exam,
            );

            $insert_id = $this->batchsubject_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/batchsubject/index');
        }
    }

    public function getSectionByClass()
    {
        $class_id = $this->input->post('class_id');
        $data     = $this->batchsubject_model->getBatchSectionByClass($class_id);
        echo json_encode($data);
    }

    public function getBatchByClassSection()
    {
        $class_section_id = $this->input->post('class_section_id');
        $data             = $this->batchsubject_model->getBatchByClassSection($class_section_id);
        echo json_encode($data);
    }

    public function getBatchByClass()
    {
        $class_id = $this->input->post('class_id');
        $data     = $this->batchsubject_model->getBatchByClass($class_id);
        echo json_encode($data);
    }

}
