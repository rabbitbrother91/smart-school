<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Feecategory extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function delete($id)
    {
        $data['title'] = 'feecategory List';
        $this->feecategory_model->remove($id);
        redirect('feecategory/index');
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'feecategory/index');
        $feecategory_result      = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory_result;
        $this->form_validation->set_rules('name', 'Category', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('feecategory/feecategoryList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'category' => $this->input->post('name'),
            );
            $this->feecategory_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect('feecategory/index');
        }
    }

    public function edit($id)
    {
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'feecategory/index');
        $feecategory_result      = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory_result;
        $data['title']           = 'Edit feecategory';
        $data['id']              = $id;

        $feecategory         = $this->feecategory_model->get($id);
        $data['feecategory'] = $feecategory;
        $this->form_validation->set_rules('name', 'category', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('feecategory/feecategoryEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'       => $id,
                'category' => $this->input->post('name'),
            );
            $this->feecategory_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('feecategory/index');
        }
    }

}
