<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Updater extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('string');
    }

    public function index($chk = null)
    {
        if (!$this->rbac->hasPrivilege('superadmin', 'can_view')) {
            access_denied();
        }
        $data = array();
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'System Settings/updater');
        if ($chk == "") {
            $fn_response     = $this->checkup();
            $res_json        = json_decode($fn_response);
            $data['version'] = $res_json->version;
        } else {
            if (!$this->session->flashdata('message') && !$this->session->flashdata('error')) {

                $fn_response     = $this->checkup();
                $res_json        = json_decode($fn_response);
                $data['version'] = $res_json->version;

            } else {

                if ($this->session->has_userdata('version')) {
                    $fn_response     = $this->checkup();
                    $res_json        = json_decode($fn_response);
                    $data['version'] = $res_json->version;
                } else {

                }

            }
        }
        if ($this->input->server('REQUEST_METHOD') == "POST") {

            $this->auth->clear_messages();
            $this->auth->clear_error();
            $this->auth->autoupdate();
            $this->session->set_flashdata('message', $this->auth->messages());
            $this->session->set_flashdata('error', $this->auth->error());
            redirect('admin/updater/index/' . random_string('alpha', 16), 'refresh');
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/updater/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function checkup()
    {
        $version  = "";
        $response = $this->auth->checkupdate();
        if ($response) {
            $result = json_decode($response);
            if ($this->session->has_userdata('version')) {
                $version = $this->session->userdata('version');
                $version = $version['version'];
            }
            $this->session->set_flashdata('message', $this->auth->messages());
        } else {
            $this->session->set_flashdata('error', $this->auth->error());
        }
        return json_encode(array('version' => $version));
    }

}
