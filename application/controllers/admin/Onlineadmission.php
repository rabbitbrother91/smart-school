<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Onlineadmission extends Admin_Controller
{
    public $sch_setting_detail = array();

    public function __construct()
    {
        parent::__construct();

        $this->load->library('media_storage');
        $this->load->model("onlinestudent_model");
        $this->config->load("app-config");
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->role;
    }

    public function admissionsetting()
    {
        if (!$this->rbac->hasPrivilege('online_admission', 'can_view')) {
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'System Settings/onlineadmissionsetting');
        $data                       = array();
        $data['result']             = $this->setting_model->getSetting();
        $data['fields']             = get_onlineadmission_editable_fields();
        $data['inserted_fields']    = $this->onlinestudent_model->getformfields();
        $data['sch_setting_detail'] = $this->sch_setting_detail;
        $data['custom_fields']      = $this->onlinestudent_model->getcustomfields();

        if (!empty($this->input->post('submitbtn'))) {

            $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');

            if ($this->input->post('online_admission_payment') == 'yes') {

                $this->form_validation->set_rules('online_admission_amount', $this->lang->line('amount'), array('required', 'xss_clean', array('check_exists', array($this->onlinestudent_model, 'validate_paymentamount')),
                )
                );

                if ($this->form_validation->run() == true) {
                    $data = array(
                        'online_admission'             => $this->input->post('online_admission'),
                        'online_admission_payment'     => $this->input->post('online_admission_payment'),
                        'online_admission_amount'      => convertCurrencyFormatToBaseAmount($this->input->post('online_admission_amount')),
                        'online_admission_instruction' => $this->input->post('online_admission_instruction'),
                        'online_admission_conditions'  => $this->input->post('online_admission_conditions'),
                        'id'                           => 1,
                    );

                    if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {

                        $img_name = $this->media_storage->fileupload("file", "./uploads/admission_form/");
                    } else {
                        $img_name = $this->sch_setting_detail->online_admission_application_form;
                    }

                    if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {

                        $this->media_storage->filedelete($this->sch_setting_detail->online_admission_application_form, "uploads/admission_form");
                    }
                    $data['online_admission_application_form'] = $img_name;

                    $this->setting_model->add($data);                  
                    
                    $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">'. $this->lang->line('record_updated_successfully') . '</div>');
                    redirect('admin/onlineadmission/admissionsetting');
                } else {

                    $this->load->view("layout/header");
                    $this->load->view("admin/onlineadmission/onlineadmission_setting", $data);
                    $this->load->view("layout/footer");
                }

            } else {

                $data = array(
                    'online_admission'             => $this->input->post('online_admission'),
                    'online_admission_payment'     => 'no',
                    'online_admission_instruction' => $this->input->post('online_admission_instruction'),
                    'online_admission_conditions'  => $this->input->post('online_admission_conditions'),
                    'id'                           => 1,
                );

                if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {

                    $img_name = $this->media_storage->fileupload("file", "./uploads/admission_form/");
                } else {
                    $img_name = $this->sch_setting_detail->online_admission_application_form;
                }

                if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {

                    $this->media_storage->filedelete($this->sch_setting_detail->online_admission_application_form, "uploads/admission_form");
                }
                $data['online_admission_application_form'] = $img_name;

                $this->setting_model->add($data);
                redirect('admin/onlineadmission/admissionsetting');
            }

        } else {
            $this->load->view("layout/header");
            $this->load->view("admin/onlineadmission/onlineadmission_setting", $data);
            $this->load->view("layout/footer");
        }

    }

    public function changeformfieldsetting()
    {
        $this->form_validation->set_rules('name', $this->lang->line('student'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('status', $this->lang->line('status'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

            $msg = array(
                'status' => form_error('status'),
                'name'   => form_error('name'),
            );

            $array = array('status' => '0', 'error' => $msg, 'msg' => $this->lang->line('something_went_wrong'));

        } else {
            $insert = array(
                'name'   => $this->input->post('name'),
                'status' => $this->input->post('status'),
            );

            $this->onlinestudent_model->addformfields($insert);

            if ($this->input->post('name') == 'if_guardian_is') {
                $status = $this->input->post('status');
                $this->onlinestudent_model->editguardianfield($status);
            }

            $array = array('status' => '1', 'error' => '', 'msg' => $this->lang->line('success_message'));
        }

        echo json_encode($array);
    }

    public function handle_upload()
    {
        $result = $this->filetype_model->get();
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

            $file_type = $_FILES["file"]['type'];            
            $file_size = $_FILES["file"]["size"];           
            $file_name = $_FILES["file"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

 
            if ($files = filesize($_FILES['file']['tmp_name'])) {

                if (!in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }
                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }
                if ($file_size > $result->file_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            return true;
        }
        return true;
    }

    public function download($id)
    {
        $settinglist = $this->setting_model->get($id);         
        $this->media_storage->filedownload($settinglist['online_admission_application_form'], "./uploads/admission_form");
    }

}
