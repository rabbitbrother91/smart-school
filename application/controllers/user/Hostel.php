<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Hostel extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'Hostel');
        $this->session->set_userdata('sub_menu', 'hostel/index');
        $listhostel         = $this->hostel_model->listhostel();
        $data['listhostel'] = $listhostel;
        $ght                = $this->customlib->getHostaltype();
        $data['ght']        = $ght;
        $this->load->view('layout/student/header');
        $this->load->view('user/hostel/createhostel', $data);
        $this->load->view('layout/student/footer');
    }

    public function create()
    {
        $data['title'] = 'Add Library';
        $this->form_validation->set_rules('hostel_name', 'Hostel Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('type', 'Type', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $listhostel         = $this->hostel_model->listhostel();
            $data['listhostel'] = $listhostel;
            $ght                = $this->customlib->getHostaltype();
            $data['ght']        = $ght;
            $this->load->view('layout/header');
            $this->load->view('admin/hostel/createhostel', $data);
            $this->load->view('layout/footer');
        } else {
            $data = array(
                'hostel_name' => $this->input->post('hostel_name'),
                'type'        => $this->input->post('type'),
                'address'     => $this->input->post('address'),
                'intake'      => $this->input->post('intake'),
                'description' => $this->input->post('description'),
            );
            $this->hostel_model->addhostel($data);
            redirect('admin/hostel/index');
        }
    }

    public function edit($id)
    {
        $data['title']      = 'Add Hostel';
        $data['id']         = $id;
        $edithostel         = $this->hostel_model->get($id);
        $data['edithostel'] = $edithostel;
        $ght                = $this->customlib->getHostaltype();
        $data['ght']        = $ght;
        $this->form_validation->set_rules('hostel_name', 'Hostel Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('type', 'Type', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $listhostel         = $this->hostel_model->listhostel();
            $data['listhostel'] = $listhostel;
            $this->load->view('layout/header');
            $this->load->view('admin/hostel/edithostel', $data);
            $this->load->view('layout/footer');
        } else {
            $data = array(
                'id'          => $this->input->post('id'),
                'hostel_name' => $this->input->post('hostel_name'),
                'type'        => $this->input->post('type'),
                'address'     => $this->input->post('address'),
                'intake'      => $this->input->post('intake'),
                'description' => $this->input->post('description'),
            );
            $this->hostel_model->addhostel($data);
            redirect('admin/hostel/index');
        }
    }

    public function delete($id)
    {
        $data['title'] = 'Fees Master List';
        $this->hostel_model->remove($id);
        redirect('admin/hostel/index');
    }

}
