<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Generatecertificate extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('Customlib');
        $this->sch_setting_detail = $this->setting_model->getSetting();
            $this->load->library('media_storage');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('generate_certificate', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/generatecertificate');

        $certificateList         = $this->Certificate_model->getstudentcertificate();
        $data['certificateList'] = $certificateList;
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/certificate/generatecertificate', $data);
        $this->load->view('layout/footer', $data);
    }

    public function search()
    {
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/generatecertificate');

        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $certificateList         = $this->Certificate_model->getstudentcertificate();
        $data['certificateList'] = $certificateList;
        $button                  = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/certificate/generatecertificate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class       = $this->input->post('class_id');
            $section     = $this->input->post('section_id');
            $search      = $this->input->post('search');
            $certificate = $this->input->post('certificate_id');
            if (isset($search)) {
                $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
                $this->form_validation->set_rules('certificate_id', $this->lang->line('certificate'), 'trim|required|xss_clean');
                if ($this->form_validation->run() == false) {

                } else {
                    $data['searchby']          = "filter";
                    $data['class_id']          = $this->input->post('class_id');
                    $data['section_id']        = $this->input->post('section_id');
                    $certificate               = $this->input->post('certificate_id');
                    $certificateResult         = $this->Generatecertificate_model->getcertificatebyid($certificate);
                    $data['certificateResult'] = $certificateResult;
                    $resultlist                = $this->student_model->searchByClassSection($class, $section);
                    $data['resultlist']        = $resultlist;
                    $title                     = $this->classsection_model->getDetailbyClassSection($data['class_id'], $data['section_id']);
                    // $data['title']             = $this->lang->line('std_dtl_for') . ' ' . $title['class'] . "(" . $title['section'] . ")";
                }
            }
            $data['sch_setting'] = $this->sch_setting_detail;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/certificate/generatecertificate', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function generate($student, $class, $certificate)
    {
        $certificateResult         = $this->Generatecertificate_model->getcertificatebyid($certificate);
        $data['certificateResult'] = $certificateResult;
        $resultlist                = $this->student_model->searchByClassStudent($class, $student);
        $data['resultlist']        = $resultlist;

        $this->load->view('admin/certificate/transfercertificate', $data);
    }

    public function generatemultiple()
    {

        $studentid           = $this->input->post('data');
        $student_array       = json_decode($studentid);
        $certificate_id      = $this->input->post('certificate_id');
        $class               = $this->input->post('class_id');
        $data                = array();
        $results             = array();
        $std_arr             = array();
        $data['sch_setting'] = $this->setting_model->get();
        $data['certificate'] = $this->Generatecertificate_model->getcertificatebyid($certificate_id);

        foreach ($student_array as $key => $value) {
            $std_arr[] = $value->student_id;
        }
        $data['students'] = $this->student_model->getStudentsByArray($std_arr);
        foreach ($data['students'] as $key => $value) {
            $data['students'][$key]->name = $this->customlib->getFullName($value->firstname, $value->middlename, $value->lastname, $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname);
        }

        $data['sch_setting'] = $this->sch_setting_detail;
        $certificates        = $this->load->view('admin/certificate/printcertificate', $data, true);
        echo $certificates;
    }

}
