<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Feemaster extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'feemaster/index');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('feecategory_id', $this->lang->line('fees_category'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('feetype_id', $this->lang->line('fees_type'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

        } else {
            $data = array(
                'feetype_id'  => $this->input->post('feetype_id'),
                'class_id'    => $this->input->post('class_id'),
                'amount'      => $this->input->post('amount'),
                'description' => $this->input->post('description'),
            );
            $result = $this->feemaster_model->check_Exits_group($data);
            if ($result) {
                $this->feemaster_model->add($data);
                $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');

                redirect('feemaster/index');
            } else {
                $data['error_message'] = $this->lang->line('fee_master_combination_already_exists');
            }
        }

        $data['title']           = $this->lang->line('add_fees_master');
        $data['title_list']      = $this->lang->line('fees_master_list');
        $feemaster_result        = $this->feemaster_model->get();
        $data['feemasterlist']   = $feemaster_result;
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $feecategory             = $this->feecategory_model->get(null, 'asc');
        $data['feecategorylist'] = $feecategory;

        $this->load->view('layout/header', $data);
        $this->load->view('feemaster/feemasterList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function view($id)
    {
        $data['title']     = 'Fees Master List';
        $feemaster         = $this->feemaster_model->get($id);
        $data['feemaster'] = $feemaster;
        $this->load->view('layout/header', $data);
        $this->load->view('feemaster/feemasterShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getByFeecategory()
    {
        $feecategory_id = $this->input->get('feecategory_id');
        $data           = $this->feetype_model->getTypeByFeecategory($feecategory_id);
        echo json_encode($data);
    }

    public function getStudentCategoryFee()
    {
        $type     = $this->input->post('type');
        $class_id = $this->input->post('class_id');
        $data     = $this->feemaster_model->getTypeByFeecategory($type, $class_id);
        if (empty($data)) {
            $status = 'fail';
        } else {
            $status = 'success';
        }
        $array = array('status' => $status, 'data' => $data);
        echo json_encode($array);
    }

    public function delete($id)
    {
        $data['title'] = 'Fees Master List';
        $this->feemaster_model->remove($id);
        redirect('feemaster/index');
    }

    public function create()
    {
        $data['title'] = 'Add Fees Master';
        $this->form_validation->set_rules('feemaster', $this->lang->line('fees_master'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('feemaster/feemasterCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'feemaster' => $this->input->post('feemaster'),
            );
            $this->feemaster_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');

            redirect('feemaster/index');
        }
    }

    public function edit($id)
    {
        $data['title']           = 'Edit Fees Master';
        $data['id']              = $id;
        $feemaster               = $this->feemaster_model->get($id);
        $data['feemaster']       = $feemaster;
        $data['title_list']      = 'Fees Master List';
        $feecategory             = $this->feecategory_model->get(null, 'asc');
        $data['feecategorylist'] = $feecategory;
        $feemaster_result        = $this->feemaster_model->get();
        $data['feemasterlist']   = $feemaster_result;
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('monthly_amount'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('feecategory_id', $this->lang->line('fees_category'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('feetype_id', $this->lang->line('fees_type'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('feemaster/feemasterEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'          => $id,
                'feetype_id'  => $this->input->post('feetype_id'),
                'class_id'    => $this->input->post('class_id'),
                'amount'      => $this->input->post('amount'),
                'description' => $this->input->post('description'),
            );
            $this->feemaster_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');

            redirect('feemaster/index');
        }
    }

}
