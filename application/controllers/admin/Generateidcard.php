<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Generateidcard extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('Customlib');
            $this->load->library('media_storage');
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function search()
    {
        if (!$this->rbac->hasPrivilege('generate_id_card', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/generateidcard');

        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting']     = $this->sch_setting_detail;
        $idcardlist              = $this->Generateidcard_model->getstudentidcard();
        $data['idcardlist']      = $idcardlist;
        $button                  = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/certificate/generateidcard', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class   = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search  = $this->input->post('search');
            $id_card = $this->input->post('id_card');
            if (isset($search)) {
                $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');

                $this->form_validation->set_rules('id_card', $this->lang->line('id_card_template'), 'trim|required|xss_clean');
                if ($this->form_validation->run() == false) {

                } else {
                    $data['searchby']     = "filter";
                    $data['class_id']     = $this->input->post('class_id');
                    $data['section_id']   = $this->input->post('section_id');
                    $id_card              = $this->input->post('id_card');
                    $idcardResult         = $this->Generateidcard_model->getidcardbyid($id_card);
                    $data['idcardResult'] = $idcardResult;
                    $resultlist           = $this->student_model->searchByClassSection($class, $section);
                    $data['resultlist']   = $resultlist;
                     
                }
            }

            $this->load->view('layout/header', $data);
            $this->load->view('admin/certificate/generateidcard', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function generate($student, $class, $idcard)
    {
        $idcardlist         = $this->Generateidcard_model->getidcardbyid($idcard);
        $data['idcardlist'] = $idcardlist;
        $resultlist         = $this->student_model->searchByClassStudent($class, $student);
        $data['resultlist'] = $resultlist;

        $this->load->view('admin/certificate/studentidcard', $data);
    }

    public function generatemultiple()
    {
        $studentid           = $this->input->post('data');
        $student_array       = json_decode($studentid);
        $idcard              = $this->input->post('id_card');
        $class               = $this->input->post('class_id');
        $data                = array();
        $results             = array();
        $std_arr             = array();
        $data['sch_setting'] = $this->setting_model->get();
        $data['id_card']     = $this->Generateidcard_model->getidcardbyid($idcard);     
        $scan_type= $this->sch_setting_detail->scan_code_type;
        foreach ($student_array as $key => $value) {
            $std_arr[] = $value->student_id;
        }
        $students = $this->student_model->getStudentsByArray($std_arr);
        foreach ($students as $key => $students_value) {
            $students[$key]->barcode = $this->customlib->generatebarcode($students_value->admission_no,$scan_type);
        }
    
        $data['students']        = $students;
        $data['sch_settingdata'] = $this->sch_setting_detail;
        $id_cards = $this->load->view('admin/certificate/generatemultiple', $data, true);
        echo json_encode(array('status' => 1, 'page' => $id_cards));
    }

}
