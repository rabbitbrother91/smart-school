<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Marksdivision extends Admin_Controller
{
    public $exam_type = array();
    public function __construct()
    {
        parent::__construct();
        $this->load->model('marksdivision_model');

    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('marks_division', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/marksdivision');
        $data['title']      = 'Add Arade';
        $data['title_list'] = 'Grade Details';

        $data['division_list'] = $this->marksdivision_model->get();

        $this->form_validation->set_rules('name', $this->lang->line('division_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('percentage_from', $this->lang->line('percentage_from'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('percentage_to', $this->lang->line('percentage_upto'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $this->load->view('layout/header');
            $this->load->view('admin/marksdivision/create', $data);
            $this->load->view('layout/footer');
        } else {
            $data = array(

                'name'            => $this->input->post('name'),
                'percentage_from' => $this->input->post('percentage_from'),
                'percentage_to'   => $this->input->post('percentage_to'),

            );
            $this->marksdivision_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/marksdivision/index');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('marks_division', 'can_edit')) {
            access_denied();
        }
        $data['title']      = 'Edit Grade';
        $data['title_list'] = 'Grade Details';

        $data['id']            = $id;
        $editdivision          = $this->marksdivision_model->get($id);
        $data['editdivision']  = $editdivision;
        $data['division_list'] = $this->marksdivision_model->get();
        $this->form_validation->set_rules('name', $this->lang->line('division_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('percentage_from', $this->lang->line('percentage_from'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('percentage_to', $this->lang->line('percentage_upto'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

            $this->load->view('layout/header');
            $this->load->view('admin/marksdivision/edit', $data);
            $this->load->view('layout/footer');
        } else {
            $data = array(
                'id'              => $this->input->post('id'),
                'name'            => $this->input->post('name'),
                'percentage_from' => $this->input->post('percentage_from'),
                'percentage_to'   => $this->input->post('percentage_to'),
            );
            $this->marksdivision_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/marksdivision/index');
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('marks_division', 'can_delete')) {
            access_denied();
        }

        $this->marksdivision_model->remove($id);
        redirect('admin/marksdivision/index');
    }

}
