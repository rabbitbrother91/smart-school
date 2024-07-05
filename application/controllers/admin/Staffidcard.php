<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Staffidcard extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('staff_id_card', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/staffidcard');
        $this->data['staffidcardlist'] = $this->Staffidcard_model->staffidcardlist();
        $this->load->view('layout/header');
        $this->load->view('admin/staffidcard/staffidcardView', $this->data);
        $this->load->view('layout/footer');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('staff_id_card', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_rules('school_name', $this->lang->line('school_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', $this->lang->line('address_phone_email'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('title', $this->lang->line('id_card_title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('background_image', $this->lang->line('background_image'), 'callback_background_image_handle_upload');
        $this->form_validation->set_rules('logo_img', $this->lang->line('logo_img'), 'callback_logo_img_handle_upload');
        $this->form_validation->set_rules('sign_image', $this->lang->line('sign_image'), 'callback_sign_image_handle_upload');
        if ($this->form_validation->run() == true) {

            $staff_id          = 0;
            $department        = 0;
            $designation       = 0;
            $name              = 0;
            $fathername        = 0;
            $mothername        = 0;
            $date_of_joining   = 0;
            $permanent_address = 0;
            $phone             = 0;
            $dob               = 0;
            $vertical_card     = 0;
            $staff_barcode     = 0;

            if ($this->input->post('is_active_staff_id') == 1) {
                $staff_id = $this->input->post('is_active_staff_id');
            }
            if ($this->input->post('is_active_department') == 1) {
                $department = $this->input->post('is_active_department');
            }
            if ($this->input->post('is_active_designation') == 1) {
                $designation = $this->input->post('is_active_designation');
            }
            if ($this->input->post('is_active_staff_name') == 1) {
                $name = $this->input->post('is_active_staff_name');
            }
            if ($this->input->post('is_active_staff_father_name') == 1) {
                $fathername = $this->input->post('is_active_staff_father_name');
            }
            if ($this->input->post('is_active_staff_mother_name') == 1) {
                $mothername = $this->input->post('is_active_staff_mother_name');
            }
            if ($this->input->post('is_active_date_of_joining') == 1) {
                $date_of_joining = $this->input->post('is_active_date_of_joining');
            }
            if ($this->input->post('is_active_staff_permanent_address') == 1) {
                $permanent_address = $this->input->post('is_active_staff_permanent_address');
            }
            if ($this->input->post('is_active_staff_phone') == 1) {
                $phone = $this->input->post('is_active_staff_phone');
            }
            if ($this->input->post('is_active_staff_dob') == 1) {
                $dob = $this->input->post('is_active_staff_dob');
            }
            if ($this->input->post('enable_staff_barcode') == 1) {
                $staff_barcode = $this->input->post('enable_staff_barcode');
            }
            $enable_vertical_card = $this->input->post('enable_vertical_card');
            if (isset($enable_vertical_card)) {
                $vertical_card = 1;
            }
            $data = array(
                'title'                    => $this->input->post('title'),
                'school_name'              => $this->input->post('school_name'),
                'school_address'           => $this->input->post('address'),
                'header_color'             => $this->input->post('header_color'),
                'enable_staff_id'          => $staff_id,
                'enable_staff_department'  => $department,
                'enable_designation'       => $designation,
                'enable_name'              => $name,
                'enable_fathers_name'      => $fathername,
                'enable_mothers_name'      => $mothername,
                'enable_date_of_joining'   => $date_of_joining,
                'enable_permanent_address' => $permanent_address,
                'enable_staff_dob'         => $dob,
                'enable_staff_phone'       => $phone,
                'enable_vertical_card'     => $vertical_card,
                'enable_staff_barcode'     => $staff_barcode,
                'status'                   => 1,
            );

            if (!empty($_FILES['background_image']['name'])) {
                $background_img_name = $this->media_storage->fileupload("background_image", "./uploads/staff_id_card/background/");
            } else {
                $background_img_name = '';
            }
            $data['background'] = $background_img_name;

            if (!empty($_FILES['logo_img']['name'])) {
                $logo_img_name = $this->media_storage->fileupload("logo_img", "./uploads/staff_id_card/logo/");
            } else {
                $logo_img_name = '';
            }
            $data['logo'] = $logo_img_name;

            if (!empty($_FILES['sign_image']['name'])) {
                $sign_img_name = $this->media_storage->fileupload("sign_image", "./uploads/staff_id_card/signature/");
            } else {
                $sign_img_name = '';
            }
            $data['sign_image'] = $sign_img_name;

            $insert_id = $this->Staffidcard_model->addstaffidcard($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
         
            redirect('admin/staffidcard/index');
        }
        $this->data['staffidcardlist'] = $this->Staffidcard_model->staffidcardlist();
        $this->load->view('layout/header');
        $this->load->view('admin/staffidcard/staffidcardView', $this->data);
        $this->load->view('layout/footer');
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('staff_id_card', 'can_edit')) {
            access_denied();
        }
        $data['id']                    = $id;
        $editstaffidcard               = $this->Staffidcard_model->get($id);
        $this->data['editstaffidcard'] = $editstaffidcard;
        $this->form_validation->set_rules('school_name', $this->lang->line('school_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', $this->lang->line('address_phone_email'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('title', $this->lang->line('id_card_title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('background_image', $this->lang->line('background_image'), 'callback_background_image_handle_upload');
        $this->form_validation->set_rules('logo_img', $this->lang->line('logo_img'), 'callback_logo_img_handle_upload');
        $this->form_validation->set_rules('sign_image', $this->lang->line('sign_image'), 'callback_sign_image_handle_upload');
        if ($this->form_validation->run() == false) {
            $this->data['staffidcardlist'] = $this->Staffidcard_model->staffidcardlist();
            $this->load->view('layout/header');
            $this->load->view('admin/staffidcard/staffidcardedit', $this->data);
            $this->load->view('layout/footer');
        } else {
            $staff_id          = 0;
            $department        = 0;
            $designation       = 0;
            $name              = 0;
            $fathername        = 0;
            $mothername        = 0;
            $date_of_joining   = 0;
            $permanent_address = 0;
            $phone             = 0;
            $dob               = 0;
            $vertical_card     = 0;
            $staff_barcode     = 0;

            if ($this->input->post('is_active_staff_id') == 1) {
                $staff_id = $this->input->post('is_active_staff_id');
            }
            if ($this->input->post('is_active_department') == 1) {
                $department = $this->input->post('is_active_department');
            }
            if ($this->input->post('is_active_designation') == 1) {
                $designation = $this->input->post('is_active_designation');
            }
            if ($this->input->post('is_active_staff_name') == 1) {
                $name = $this->input->post('is_active_staff_name');
            }
            if ($this->input->post('is_active_staff_father_name') == 1) {
                $fathername = $this->input->post('is_active_staff_father_name');
            }
            if ($this->input->post('is_active_staff_mother_name') == 1) {
                $mothername = $this->input->post('is_active_staff_mother_name');
            }
            if ($this->input->post('is_active_date_of_joining') == 1) {
                $date_of_joining = $this->input->post('is_active_date_of_joining');
            }
            if ($this->input->post('is_active_staff_permanent_address') == 1) {
                $permanent_address = $this->input->post('is_active_staff_permanent_address');
            }
            if ($this->input->post('is_active_staff_phone') == 1) {
                $phone = $this->input->post('is_active_staff_phone');
            }
            if ($this->input->post('is_active_staff_dob') == 1) {
                $dob = $this->input->post('is_active_staff_dob');
            }
            if ($this->input->post('enable_staff_barcode') == 1) {
                $staff_barcode = $this->input->post('enable_staff_barcode');
            }

            $enable_vertical_card = $this->input->post('enable_vertical_card');
            if (isset($enable_vertical_card)) {
                $vertical_card = 1;
            }

            $data = array(
                'id'                       => $this->input->post('id'),
                'title'                    => $this->input->post('title'),
                'school_name'              => $this->input->post('school_name'),
                'school_address'           => $this->input->post('address'),
                'header_color'             => $this->input->post('header_color'),
                'enable_staff_id'          => $staff_id,
                'enable_staff_department'  => $department,
                'enable_designation'       => $designation,
                'enable_name'              => $name,
                'enable_fathers_name'      => $fathername,
                'enable_mothers_name'      => $mothername,
                'enable_date_of_joining'   => $date_of_joining,
                'enable_permanent_address' => $permanent_address,
                'enable_staff_dob'         => $dob,
                'enable_staff_phone'       => $phone,
                'enable_vertical_card'     => $vertical_card,
                'enable_staff_barcode'     => $staff_barcode,
                'status'                   => 1,
            );

            $removebackground_image = $this->input->post('removebackground_image');
            $removelogo_image       = $this->input->post('removelogo_image');
            $removesign_image       = $this->input->post('removesign_image');

            if ($removebackground_image != '') {
                $data['background'] = '';
            }

            if ($removelogo_image != '') {
                $data['logo'] = '';
            }

            if ($removesign_image != '') {
                $data['sign_image'] = '';
            }

            if (isset($_FILES["background_image"]) && $_FILES['background_image']['name'] != '' && (!empty($_FILES['background_image']['name']))) {
                $background         = $this->media_storage->fileupload("background_image", "./uploads/staff_id_card/background/");
                $data['background'] = $background;
            }

            if (isset($_FILES["background_image"]) && $_FILES['background_image']['name'] != '' && (!empty($_FILES['background_image']['name']))) {
                $this->media_storage->filedelete($editstaffidcard[0]->background, "uploads/staff_id_card/background");
            }

            if (isset($_FILES["logo_img"]) && $_FILES['logo_img']['name'] != '' && (!empty($_FILES['logo_img']['name']))) {
                $logo_img     = $this->media_storage->fileupload("logo_img", "./uploads/staff_id_card/logo/");
                $data['logo'] = $logo_img;
            }

            if (isset($_FILES["logo_img"]) && $_FILES['logo_img']['name'] != '' && (!empty($_FILES['logo_img']['name']))) {
                $this->media_storage->filedelete($editstaffidcard[0]->logo, "uploads/staff_id_card/logo");
            }

            if (isset($_FILES["sign_image"]) && $_FILES['sign_image']['name'] != '' && (!empty($_FILES['sign_image']['name']))) {
                $sign_image         = $this->media_storage->fileupload("sign_image", "./uploads/staff_id_card/signature/");
                $data['sign_image'] = $sign_image;
            }

            if (isset($_FILES["sign_image"]) && $_FILES['sign_image']['name'] != '' && (!empty($_FILES['sign_image']['name']))) {
                $this->media_storage->filedelete($editstaffidcard[0]->sign_image, "uploads/staff_id_card/signature");
            }

            $this->Staffidcard_model->addstaffidcard($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/staffidcard');
        }
    }

    public function delete($id)
    {
        $data['title'] = 'Certificate List';
        $row           = $this->Staffidcard_model->get($id);
        if ($row[0]->background != '') {
            $this->media_storage->filedelete($row[0]->background, "uploads/staff_id_card/background/");
        }

        if ($row[0]->logo != '') {
            $this->media_storage->filedelete($row[0]->logo, "uploads/staff_id_card/logo/");
        }

        if ($row[0]->sign_image != '') {
            $this->media_storage->filedelete($row[0]->sign_image, "uploads/staff_id_card/signature/");
        }

        $this->Staffidcard_model->remove($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/staffidcard/index');
    }

    public function view()
    {
        $id             = $this->input->post('certificateid');
        $setting=$this->setting_model->getSetting();
        $data['scan_code_type'] =$setting->scan_code_type;
        $data['idcard'] = $this->Staffidcard_model->idcardbyid($id);
        $this->load->view('admin/staffidcard/staffidcardpreview', $data);
    }

    public function background_image_handle_upload()
    {
        if (isset($_FILES["background_image"]) && !empty($_FILES["background_image"]['name'])) {
            $allowedExts = array('jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG', 'Jpg', 'Jpeg', 'Png');
            $temp        = explode(".", $_FILES["background_image"]["name"]);
            $extension   = end($temp);
            if ($_FILES["background_image"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if ($_FILES["background_image"]["type"] != 'image/gif' &&
                $_FILES["background_image"]["type"] != 'image/jpeg' &&
                $_FILES["background_image"]["type"] != 'image/png') {
                $this->form_validation->set_message('background_image_handle_upload', $this->lang->line('invalid_file_type'));
                return false;
            }
            if (!in_array($extension, $allowedExts)) {
                $this->form_validation->set_message('background_image_handle_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            return true;
        } else {
            return true;
        }
    }

    public function logo_img_handle_upload()
    {
        if (isset($_FILES["logo_img"]) && !empty($_FILES["logo_img"]['name'])) {
            $allowedExts = array('jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG', 'Jpg', 'Jpeg', 'Png');
            $temp        = explode(".", $_FILES["logo_img"]["name"]);
            $extension   = end($temp);
            if ($_FILES["logo_img"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if ($_FILES["logo_img"]["type"] != 'image/gif' &&
                $_FILES["logo_img"]["type"] != 'image/jpeg' &&
                $_FILES["logo_img"]["type"] != 'image/png') {
                $this->form_validation->set_message('logo_img_handle_upload', $this->lang->line('invalid_file_type'));
                return false;
            }
            if (!in_array($extension, $allowedExts)) {
                $this->form_validation->set_message('logo_img_handle_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            return true;
        } else {
            return true;
        }
    }

    public function sign_image_handle_upload()
    {
        if (isset($_FILES["sign_image"]) && !empty($_FILES["sign_image"]['name'])) {
            $allowedExts = array('jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG', 'Jpg', 'Jpeg', 'Png');
            $temp        = explode(".", $_FILES["sign_image"]["name"]);
            $extension   = end($temp);
            $error ='';
            if ($_FILES["background_image"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if ($_FILES["sign_image"]["type"] != 'image/gif' &&
                $_FILES["sign_image"]["type"] != 'image/jpeg' &&
                $_FILES["sign_image"]["type"] != 'image/png') {
                $this->form_validation->set_message('sign_image_handle_upload', $this->lang->line('invalid_file_type'));
                return false;
            }
            if (!in_array($extension, $allowedExts)) {
                $this->form_validation->set_message('sign_image_handle_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            return true;
        } else {
            return true;
        }
    }

}
