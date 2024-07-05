<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Audit extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function unauthorized()
    {
        $data = array();
        $this->load->view('layout/header', $data);
        $this->load->view('unauthorized', $data);
        $this->load->view('layout/footer', $data);
    }

    public function index($offset = 0)
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'audit/index');
        $data['title']      = 'Audit Trail Report';
        $data['title_list'] = 'Audit Trail List';
        $this->load->view('layout/header');
        $this->load->view('admin/audit/index');
        $this->load->view('layout/footer');
    }

    public function getDatatable()
    {
        $audit = $this->audit_model->getAllRecord();
        $audit = json_decode($audit);

        $dt_data = array();
        if (!empty($audit->data)) {
            foreach ($audit->data as $key => $value) {

                $date = date($this->customlib->getSchoolDateFormat(), strtotime($value->time));
                $time = date('H:i:s', strtotime($value->time));

                $row   = array();
                $row[] = $value->message;
                $row[] = $value->name;
                $row[] = $value->ip_address;
                $row[] = $value->action;
                $row[] = $value->platform;
                $row[] = $value->agent;
                $row[] = $date . " " . $time;

                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw"            => intval($audit->draw),
            "recordsTotal"    => intval($audit->recordsTotal),
            "recordsFiltered" => intval($audit->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function delete()
    {
        $this->audit_model->audittrail_delete();
        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('delete_message'));
        echo json_encode($array);
    }

}
