<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Feetype extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'Expenses');
        $this->session->set_userdata('sub_menu', 'expense/index');
        $data['title']      = 'Add Feetype';
        $data['title_list'] = 'Recent FeeType';

        $this->form_validation->set_rules(
            'code', 'Code', array(
                'required',
                array('check_exists', array($this->feetype_model, 'check_exists')),
            )
        );
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('ss', $this->lang->line('name'), 'required');
        if ($this->form_validation->run() == false) {

        } else {
            $data = array(
                'name'        => $this->input->post('name'),
                'description' => $this->input->post('description'),
            );
            $this->feetype_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');

            redirect('admin/feetype/index');
        }
        $feegroup_result     = $this->feetype_model->get();
        $data['feetypeList'] = $feegroup_result;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/feetype/feetypeList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        $data['title'] = 'Fees Master List';
        $this->feetype_model->remove($id);
        redirect('admin/feetype/index');
    }

    public function edit($id)
    {
        $data['id']          = $id;
        $feegroup            = $this->feetype_model->get($id);
        $data['feegroup']    = $feegroup;
        $feegroup_result     = $this->feetype_model->get();
        $data['feetypeList'] = $feegroup_result;
        $this->form_validation->set_rules(
            'name', 'Name', array(
                'required',
                array('check_exists', array($this->feetype_model, 'check_exists')),
            )
        );

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/feetype/feegroupEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'          => $id,
                'name'        => $this->input->post('name'),
                'description' => $this->input->post('description'),
            );
            $this->feetype_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/feetype/index');
        }
    }

}
