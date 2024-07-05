<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Itemsupplier extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('supplier', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Inventory');
        $this->session->set_userdata('sub_menu', 'itemsupplier/index');
        $data['title']            = 'Item Supplier List';
        $itemsupplier_result      = $this->itemsupplier_model->get();
        $data['itemsupplierlist'] = $itemsupplier_result;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/itemsupplier/itemsupplierList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('supplier', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Item Supplier List';
        $this->itemsupplier_model->remove($id);
        redirect('admin/itemsupplier/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('supplier', 'can_add')) {
            access_denied();
        }
        $data['title']            = 'Add Item supplier';
        $itemsupplier_result      = $this->itemsupplier_model->get();
        $data['itemsupplierlist'] = $itemsupplier_result;

        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|xss_clean|valid_email');
        $this->form_validation->set_rules('contact_person_phone', $this->lang->line('phone'), 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('contact_person_email', $this->lang->line('email'), 'trim|xss_clean|valid_email');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/itemsupplier/itemsupplierList', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $data = array(
                'phone'                => $this->input->post('phone'),
                'contact_person_phone' => $this->input->post('contact_person_phone'),
                'item_supplier'        => $this->input->post('name'),
                'email'                => $this->input->post('email'),
                'address'              => $this->input->post('address'),
                'contact_person_name'  => $this->input->post('contact_person_name'),
                'contact_person_email' => $this->input->post('contact_person_email'),
                'description'          => $this->input->post('description'),
            );
            $this->itemsupplier_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/itemsupplier/index');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('supplier', 'can_edit')) {
            access_denied();
        }
        $data['title']            = 'Edit Item Supplier';
        $itemsupplier_result      = $this->itemsupplier_model->get();
        $data['itemsupplierlist'] = $itemsupplier_result;
        $data['id']               = $id;
        $store                    = $this->itemsupplier_model->get($id);
        $data['itemsupplier']     = $store;

        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|xss_clean|valid_email');
        $this->form_validation->set_rules('contact_person_phone', $this->lang->line('phone'), 'trim|numeric|xss_clean');
        $this->form_validation->set_rules('contact_person_email', $this->lang->line('email'), 'trim|xss_clean|valid_email');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/itemsupplier/itemsupplierEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $data = array(
                'id'                   => $id,
                'item_supplier'        => $this->input->post('name'),
                'phone'                => $this->input->post('phone'),
                'contact_person_phone' => $this->input->post('contact_person_phone'),
                'email'                => $this->input->post('email'),
                'address'              => $this->input->post('address'),
                'contact_person_name'  => $this->input->post('contact_person_name'),
                'contact_person_email' => $this->input->post('contact_person_email'),
                'description'          => $this->input->post('description'),
            );
            $this->itemsupplier_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/itemsupplier/index');
        }
    }

}
