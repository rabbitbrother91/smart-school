<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Category extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('student_categories', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'category/index');
        $data['title']        = $this->lang->line('category_list');
        $category_result      = $this->category_model->get();
        $data['categorylist'] = $category_result;
        $this->load->view('layout/header', $data);
        $this->load->view('category/categoryList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('student_categories', 'can_view')) {
            access_denied();
        }
        $data['title']    = $this->lang->line('category_list');
        $category         = $this->category_model->get($id);
        $data['category'] = $category;
        $this->load->view('layout/header', $data);
        $this->load->view('category/categoryShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('student_categories', 'can_delete')) {
            access_denied();
        }
        $data['title'] = $this->lang->line('category_list');
        $this->category_model->remove($id);
        $this->session->set_flashdata('msgdelete', '<div class="alert alert-success text-left">' . $this->lang->line('delete_message') . '</div>');
        redirect('category/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('student_categories', 'can_add')) {
            access_denied();
        }
        $data['title']        = $this->lang->line('add_category');
        $category_result      = $this->category_model->get();
        $data['categorylist'] = $category_result;
        $this->form_validation->set_rules('category', $this->lang->line('category'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('category/categoryList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'category' => $this->input->post('category'),
            );
            $this->category_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('category/index');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('student_categories', 'can_edit')) {
            access_denied();
        }
        $data['title']        = $this->lang->line('edit_category');
        $category_result      = $this->category_model->get();
        $data['categorylist'] = $category_result;
        $data['id']           = $id;
        $category             = $this->category_model->get($id);
        $data['category']     = $category;
        $this->form_validation->set_rules('category', $this->lang->line('category'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('category/categoryEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'       => $id,
                'category' => $this->input->post('category'),
            );
            $this->category_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('category/index');
        }
    }

}
