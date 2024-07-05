<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class studentidcard extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('media_storage');
        $this->load->library('Customlib');
    }

    public function index()
    {

        if (!$this->rbac->hasPrivilege('student_id_card', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/studentidcard');
        $this->data['idcardlist'] = $this->Student_id_card_model->idcardlist();
        $this->load->view('layout/header');
        $this->load->view('admin/certificate/createidcard', $this->data);
        $this->load->view('layout/footer');
    }

    public function create()
    {

        if (!$this->rbac->hasPrivilege('student_id_card', 'can_add')) {
            access_denied();
        }

        $data['title'] = 'Student ID Card';
        $this->form_validation->set_rules('school_name', $this->lang->line('school_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', $this->lang->line('address_phone_email'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('title', $this->lang->line('id_card_title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('background_image', $this->lang->line('background_image'), 'callback_handle_upload[background_image]');
        $this->form_validation->set_rules('logo_img', $this->lang->line('logo'), 'callback_handle_upload[logo_img]');
        $this->form_validation->set_rules('sign_image', $this->lang->line('signature'), 'callback_handle_upload[sign_image]');

        if ($this->form_validation->run() == false) {
            $this->data['idcardlist'] = $this->Student_id_card_model->idcardlist();
            $this->load->view('layout/header');
            $this->load->view('admin/certificate/createidcard', $this->data);
            $this->load->view('layout/footer');
        } else {
            $admission_no    = 0;
            $studentname     = 0;
            $class           = 0;
            $fathername      = 0;
            $mothername      = 0;
            $address         = 0;
            $phone           = 0;
            $dob             = 0;
            $bloodgroup      = 0;
            $vertical_card   = 0;
            $student_barcode = 0;

            if ($this->input->post('is_active_admission_no') == 1) {
                $admission_no = $this->input->post('is_active_admission_no');
            }
            if ($this->input->post('is_active_student_name') == 1) {
                $studentname = $this->input->post('is_active_student_name');
            }
            if ($this->input->post('is_active_class') == 1) {
                $class = $this->input->post('is_active_class');
            }
            if ($this->input->post('is_active_father_name') == 1) {
                $fathername = $this->input->post('is_active_father_name');
            }
            if ($this->input->post('is_active_mother_name') == 1) {
                $mothername = $this->input->post('is_active_mother_name');
            }
            if ($this->input->post('is_active_address') == 1) {
                $address = $this->input->post('is_active_address');
            }
            if ($this->input->post('is_active_phone') == 1) {
                $phone = $this->input->post('is_active_phone');
            }
            if ($this->input->post('is_active_dob') == 1) {
                $dob = $this->input->post('is_active_dob');
            }
            if ($this->input->post('is_active_blood_group') == 1) {
                $bloodgroup = $this->input->post('is_active_blood_group');
            }
            if ($this->input->post('enable_student_barcode') == 1) {
                $student_barcode = $this->input->post('enable_student_barcode');
            }

            $enable_vertical_card = $this->input->post('enable_vertical_card');
            if (isset($enable_vertical_card)) {
                $vertical_card = 1;
            }

            $data = array(
                'title'                  => $this->input->post('title'),
                'school_name'            => $this->input->post('school_name'),
                'school_address'         => $this->input->post('address'),
                'header_color'           => $this->input->post('header_color'),
                'enable_admission_no'    => $admission_no,
                'enable_student_name'    => $studentname,
                'enable_class'           => $class,
                'enable_fathers_name'    => $fathername,
                'enable_mothers_name'    => $mothername,
                'enable_address'         => $address,
                'enable_phone'           => $phone,
                'enable_dob'             => $dob,
                'enable_blood_group'     => $bloodgroup,
                'enable_vertical_card'   => $vertical_card,
                'enable_student_barcode' => $student_barcode,
                'status'                 => 1,
            );

            if (!empty($_FILES['background_image']['name'])) {
                $background_img_name = $this->media_storage->fileupload("background_image", "./uploads/student_id_card/background/");
            } else {
                $background_img_name = '';
            }
            $data['background'] = $background_img_name;

            if (!empty($_FILES['logo_img']['name'])) {
                $logo_img_name = $this->media_storage->fileupload("logo_img", "./uploads/student_id_card/logo/");
            } else {
                $logo_img_name = '';
            }
            $data['logo'] = $logo_img_name;

            if (!empty($_FILES['sign_image']['name'])) {
                $sign_img_name = $this->media_storage->fileupload("sign_image", "./uploads/student_id_card/signature/");
            } else {
                $sign_img_name = '';
            }
            $data['sign_image'] = $sign_img_name;

            $insert_id = $this->Student_id_card_model->addidcard($data);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/studentidcard/index');
        }
    }

    public function handle_upload($str, $var)
    {
        $image_validate = $this->config->item('image_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES[$var]) && !empty($_FILES[$var]['name'])) {

            $file_type = $_FILES[$var]['type'];
            $file_size = $_FILES[$var]["size"];
            $file_name = $_FILES[$var]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->image_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->image_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = @getimagesize($_FILES[$var]['tmp_name'])) {

                if (!in_array($files['mime'], $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
                    return false;
                }

                if ($file_size > $result->image_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->image_size / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed_or_extension_not_allowed'));
                return false;
            }

            return true;
        }
        return true;
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('student_id_card', 'can_edit')) {
            access_denied();
        }

        $data['title']            = 'Edit ID Card';
        $data['id']               = $id;
        $editidcard               = $this->Student_id_card_model->get($id);
        $this->data['editidcard'] = $editidcard;
        $this->form_validation->set_rules('school_name', $this->lang->line('school_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', $this->lang->line('address_phone_email'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('title', $this->lang->line('id_card_title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('background_image', $this->lang->line('background_image'), 'callback_handle_upload[background_image]');
        $this->form_validation->set_rules('logo_img', $this->lang->line('logo'), 'callback_handle_upload[logo_img]');
        $this->form_validation->set_rules('sign_image', $this->lang->line('signature'), 'callback_handle_upload[sign_image]');
        if ($this->form_validation->run() == false) {
            $this->data['idcardlist'] = $this->Student_id_card_model->idcardlist();
            $this->load->view('layout/header');
            $this->load->view('admin/certificate/studentidcardedit', $this->data);
            $this->load->view('layout/footer');
        } else {
            $admission_no    = 0;
            $studentname     = 0;
            $class           = 0;
            $fathername      = 0;
            $mothername      = 0;
            $address         = 0;
            $phone           = 0;
            $dob             = 0;
            $bloodgroup      = 0;
            $vertical_card   = 0;
            $student_barcode = 0;

            if ($this->input->post('is_active_admission_no') == 1) {
                $admission_no = $this->input->post('is_active_admission_no');
            }
            if ($this->input->post('is_active_student_name') == 1) {
                $studentname = $this->input->post('is_active_student_name');
            }
            if ($this->input->post('is_active_class') == 1) {
                $class = $this->input->post('is_active_class');
            }
            if ($this->input->post('is_active_father_name') == 1) {
                $fathername = $this->input->post('is_active_father_name');
            }
            if ($this->input->post('is_active_mother_name') == 1) {
                $mothername = $this->input->post('is_active_mother_name');
            }
            if ($this->input->post('is_active_address') == 1) {
                $address = $this->input->post('is_active_address');
            }
            if ($this->input->post('is_active_phone') == 1) {
                $phone = $this->input->post('is_active_phone');
            }
            if ($this->input->post('is_active_dob') == 1) {
                $dob = $this->input->post('is_active_dob');
            }
            if ($this->input->post('is_active_blood_group') == 1) {
                $bloodgroup = $this->input->post('is_active_blood_group');
            }

            if ($this->input->post('enable_student_barcode') == 1) {
                $student_barcode = $this->input->post('enable_student_barcode');
            }

            $enable_vertical_card = $this->input->post('enable_vertical_card');
            if (isset($enable_vertical_card)) {
                $vertical_card = 1;
            }

            $data = array(
                'id'                     => $this->input->post('id'),
                'title'                  => $this->input->post('title'),
                'school_name'            => $this->input->post('school_name'),
                'school_address'         => $this->input->post('address'),
                'header_color'           => $this->input->post('header_color'),
                'enable_admission_no'    => $admission_no,
                'enable_student_name'    => $studentname,
                'enable_class'           => $class,
                'enable_fathers_name'    => $fathername,
                'enable_mothers_name'    => $mothername,
                'enable_address'         => $address,
                'enable_phone'           => $phone,
                'enable_dob'             => $dob,
                'enable_blood_group'     => $bloodgroup,
                'enable_vertical_card'   => $vertical_card,
                'enable_student_barcode' => $student_barcode,
                'status'                 => 1,
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
                $background         = $this->media_storage->fileupload("background_image", "./uploads/student_id_card/background/");
                $data['background'] = $background;
            }

            if (isset($_FILES["background_image"]) && $_FILES['background_image']['name'] != '' && (!empty($_FILES['background_image']['name']))) {
                $this->media_storage->filedelete($editidcard[0]->background, "uploads/student_id_card/background");
            }

            if (isset($_FILES["logo_img"]) && $_FILES['logo_img']['name'] != '' && (!empty($_FILES['logo_img']['name']))) {
                $logo_img     = $this->media_storage->fileupload("logo_img", "./uploads/student_id_card/logo/");
                $data['logo'] = $logo_img;
            }

            if (isset($_FILES["logo_img"]) && $_FILES['logo_img']['name'] != '' && (!empty($_FILES['logo_img']['name']))) {
                $this->media_storage->filedelete($editidcard[0]->logo, "uploads/student_id_card/logo");
            }

            if (isset($_FILES["sign_image"]) && $_FILES['sign_image']['name'] != '' && (!empty($_FILES['sign_image']['name']))) {
                $sign_image         = $this->media_storage->fileupload("sign_image", "./uploads/student_id_card/signature/");
                $data['sign_image'] = $sign_image;
            }

            if (isset($_FILES["sign_image"]) && $_FILES['sign_image']['name'] != '' && (!empty($_FILES['sign_image']['name']))) {
                $this->media_storage->filedelete($editidcard[0]->sign_image, "uploads/student_id_card/signature");
            }

            $this->Student_id_card_model->addidcard($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/studentidcard');
        }
    }

    public function delete($id)
    {
        $data['title'] = 'Certificate List';
        $row           = $this->Student_id_card_model->get($id);
        if ($row[0]->background != '') {
            $this->media_storage->filedelete($row[0]->background, "uploads/student_id_card/background/");
        }

        if ($row[0]->logo != '') {
            $this->media_storage->filedelete($row[0]->logo, "uploads/student_id_card/logo/");
        }

        if ($row[0]->sign_image != '') {
            $this->media_storage->filedelete($row[0]->sign_image, "uploads/student_id_card/signature/");
        }

        $this->Student_id_card_model->remove($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/studentidcard/index');
    }
    
    public function view()
    {
        $id             = $this->input->post('certificateid');
        $output         = '';
        $setting=$this->setting_model->getSetting();
        $data['scan_code_type'] =$setting->scan_code_type;
        $data['idcard'] = $this->Student_id_card_model->idcardbyid($id);
        $this->load->view('admin/certificate/studentidcardpreview', $data);
    }
    
}
