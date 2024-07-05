<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sessions extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('session_setting', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'sessions/index');
        $data['title']       = 'Session List';
        $session_result      = $this->session_model->getAllSession();
        $data['sessionlist'] = $session_result;
        $this->load->view('layout/header', $data);
        $this->load->view('session/sessionList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('session_setting', 'can_view')) {
            access_denied();
        }
        $data['title']   = 'Session List';
        $session         = $this->session_model->get($id);
        $data['session'] = $session;
        $this->load->view('layout/header', $data);
        $this->load->view('session/sessionShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('session_setting', 'can_delete')) {
            access_denied();
        }
        $this->session->set_flashdata('list_msg', '<div class="alert alert-success text-left">' . $this->lang->line('delete_message') . '</div>');
        $this->session_model->remove($id);
        redirect('sessions/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('session_setting', 'can_add')) {
            access_denied();
        }
        $session_result      = $this->session_model->getAllSession();
        $data['sessionlist'] = $session_result;
        $data['title']       = 'Add Session';
        $this->form_validation->set_rules('session', $this->lang->line('session'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('session/sessionList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'session' => $this->input->post('session'),
            );
            $this->session_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('sessions/index');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('session_setting', 'can_edit')) {
            access_denied();
        }
        $session_result      = $this->session_model->getAllSession();
        $data['sessionlist'] = $session_result;
        $data['title']       = 'Edit Session';
        $data['id']          = $id;
        $session             = $this->session_model->get($id);
        $data['session']     = $session;
        $this->form_validation->set_rules('session', $this->lang->line('session'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('session/sessionEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'      => $id,
                'session' => $this->input->post('session'),
            );
            $this->session_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('sessions/index');
        }
    }

}