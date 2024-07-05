<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Reference extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model("reference_model");
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('setup_font_office', 'can_view')) {
            access_denied();
        }
        $this->form_validation->set_rules('reference', $this->lang->line('reference'), 'required');

        if ($this->form_validation->run() == false) {
            $data['reference_list'] = $this->reference_model->reference_list();
            $this->load->view('layout/header');
            $this->load->view('admin/frontoffice/referenceview', $data);
            $this->load->view('layout/footer');
        } else {
            $reference = array(
                'reference'   => $this->input->post('reference'),
                'description' => $this->input->post('description'),
            );
            $this->reference_model->add($reference);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/reference');
        }
    }

    public function edit($reference_id)
    {
        if (!$this->rbac->hasPrivilege('setup_font_office', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('reference', $this->lang->line('reference'), 'required');

        if ($this->form_validation->run() == false) {
            $data['reference_list'] = $this->reference_model->reference_list();
            $data['reference_data'] = $this->reference_model->reference_list($reference_id);
            $this->load->view('layout/header');
            $this->load->view('admin/frontoffice/referenceeditview', $data);
            $this->load->view('layout/footer');
        } else {

            $reference = array(
                'reference'   => $this->input->post('reference'),
                'description' => $this->input->post('description'),
            );
            $this->reference_model->update($reference_id, $reference);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/reference');
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('setup_font_office', 'can_delete')) {
            access_denied();
        }
        $this->reference_model->delete($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/reference');
    }

}
