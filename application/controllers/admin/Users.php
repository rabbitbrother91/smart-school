<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Users extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("classteacher_model");
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('user_status', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'users/index');
        $studentList         = $this->student_model->getStudents();
        $staffList           = $this->staff_model->getAll_users();
        $parentList          = $this->student_model->getParentList();
        $data['sch_setting'] = $this->setting_model->getSetting();
        $data['studentList'] = $studentList;
        $data['parentList']  = $parentList;
        $data['staffList']   = $staffList;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/users/userList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function changeStatus()
    {
        if (!$this->rbac->hasPrivilege('user_status', 'can_view')) {
            access_denied();
        }
        $id     = $this->input->post('id');
        $status = $this->input->post('status');
        $role   = $this->input->post('role');
        $data   = array('id' => $id, 'is_active' => $status);
        if ($role != "staff") {
            $result = $this->user_model->changeStatus($data);
        } else {
            if ($status == "yes") {
                $data['is_active'] = 1;
            } else {
                $data['is_active'] = 0;
            }

            $result = $this->staff_model->update($data);
        }

        if ($result) {
            $response = array('status' => 1, 'msg' => $this->lang->line('status_change_successfully'));
            echo json_encode($response);
        }
    }     

    
}
