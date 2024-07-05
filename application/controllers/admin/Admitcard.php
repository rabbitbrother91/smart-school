<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Admitcard extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('design_admit_card', 'can_view')) {
            access_denied();
        }

        $data['title'] = 'Add Library';
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/admitcard');
        $this->data['admitcardList'] = $this->admitcard_model->get();

        $this->form_validation->set_rules('template', $this->lang->line('template'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('left_logo', $this->lang->line('left_logo'), 'callback_handle_upload[left_logo]');
        $this->form_validation->set_rules('right_logo', $this->lang->line('right_logo'), 'callback_handle_upload[right_logo]');
        $this->form_validation->set_rules('background_img', $this->lang->line('background_image'), 'callback_handle_upload[background_img]');
        $this->form_validation->set_rules('sign', $this->lang->line('sign'), 'callback_handle_upload[sign]');

        if ($this->form_validation->run() == true) {

            if (isset($_POST['is_name'])) {
                $is_name = 1;
            } else {
                $is_name = 0;
            }

            if (isset($_POST['is_father_name'])) {
                $is_father_name = 1;
            } else {
                $is_father_name = 0;
            }

            if (isset($_POST['is_mother_name'])) {
                $is_mother_name = 1;
            } else {
                $is_mother_name = 0;
            }

            if (isset($_POST['is_dob'])) {
                $is_dob = 1;
            } else {
                $is_dob = 0;
            }

            if (isset($_POST['is_admission_no'])) {
                $is_admission_no = 1;
            } else {
                $is_admission_no = 0;
            }

            if (isset($_POST['is_roll_no'])) {
                $is_roll_no = 1;
            } else {
                $is_roll_no = 0;
            }

            if (isset($_POST['is_address'])) {
                $is_address = 1;
            } else {
                $is_address = 0;
            }

            if (isset($_POST['is_gender'])) {
                $is_gender = 1;
            } else {
                $is_gender = 0;
            }

            if (isset($_POST['is_photo'])) {
                $is_photo = 1;
            } else {
                $is_photo = 0;
            }

            if (isset($_POST['is_class'])) {
                $is_class = 1;
            } else {
                $is_class = 0;
            }

            if (isset($_POST['is_section'])) {
                $is_section = 1;
            } else {
                $is_section = 0;
            }

            $insert_data = array(
                'template'        => $this->input->post('template'),
                'heading'         => $this->input->post('heading'),
                'title'           => $this->input->post('title'),
                'exam_name'       => $this->input->post('exam_name'),
                'school_name'     => $this->input->post('school_name'),
                'exam_center'     => $this->input->post('exam_center'),
                'is_name'         => $is_name,
                'is_father_name'  => $is_father_name,
                'is_mother_name'  => $is_mother_name,
                'is_dob'          => $is_dob,
                'is_admission_no' => $is_admission_no,
                'is_roll_no'      => $is_roll_no,
                'is_address'      => $is_address,
                'is_gender'       => $is_gender,
                'is_photo'        => $is_photo,
                'is_class'        => $is_class,
                'is_section'      => $is_section,
                'content_footer'  => nl2br($this->input->post('content_footer')),
                'left_logo'       => "",
                'right_logo'      => "",
                'sign'            => "",
                'background_img'  => "",
            );

            if (isset($_FILES["left_logo"]) && !empty($_FILES["left_logo"]['name'])) {

                $img_name                 = $this->media_storage->fileupload("left_logo", "./uploads/admit_card/");
                $insert_data['left_logo'] = $img_name;
            }
            if (isset($_FILES["right_logo"]) && !empty($_FILES["right_logo"]['name'])) {

                $img_name                  = $this->media_storage->fileupload("right_logo", "./uploads/admit_card/");
                $insert_data['right_logo'] = $img_name;
            }
            if (isset($_FILES["sign"]) && !empty($_FILES["sign"]['name'])) {

                $img_name            = $this->media_storage->fileupload("sign", "./uploads/admit_card/");
                $insert_data['sign'] = $img_name;
            }
            if (isset($_FILES["background_img"]) && !empty($_FILES["background_img"]['name'])) {

                $img_name                      = $this->media_storage->fileupload("background_img", "./uploads/admit_card/");
                $insert_data['background_img'] = $img_name;
            }

            $this->admitcard_model->add($insert_data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/admitcard/index');
        }

        $this->load->view('layout/header');
        $this->load->view('admin/admitcard/createadmitcard', $this->data);
        $this->load->view('layout/footer');
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
        if (!$this->rbac->hasPrivilege('design_admit_card', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Add Library';
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/admitcard');
        $this->data['admitcardList'] = $this->admitcard_model->get();
        $this->data['admitcard']     = $this->admitcard_model->get($id);

        $this->form_validation->set_rules('template', $this->lang->line('template'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('left_logo', $this->lang->line('left_logo'), 'callback_handle_upload[left_logo]');
        $this->form_validation->set_rules('right_logo', $this->lang->line('right_logo'), 'callback_handle_upload[right_logo]');
        $this->form_validation->set_rules('background_img', $this->lang->line('background_image'), 'callback_handle_upload[background_img]');
        $this->form_validation->set_rules('sign', $this->lang->line('sign'), 'callback_handle_upload[sign]');

        if ($this->form_validation->run() == true) {

            if (isset($_POST['is_name'])) {
                $is_name = 1;
            } else {
                $is_name = 0;
            }

            if (isset($_POST['is_father_name'])) {
                $is_father_name = 1;
            } else {
                $is_father_name = 0;
            }

            if (isset($_POST['is_mother_name'])) {
                $is_mother_name = 1;
            } else {
                $is_mother_name = 0;
            }

            if (isset($_POST['is_dob'])) {
                $is_dob = 1;
            } else {
                $is_dob = 0;
            }

            if (isset($_POST['is_admission_no'])) {
                $is_admission_no = 1;
            } else {
                $is_admission_no = 0;
            }

            if (isset($_POST['is_roll_no'])) {
                $is_roll_no = 1;
            } else {
                $is_roll_no = 0;
            }

            if (isset($_POST['is_address'])) {
                $is_address = 1;
            } else {
                $is_address = 0;
            }

            if (isset($_POST['is_gender'])) {
                $is_gender = 1;
            } else {
                $is_gender = 0;
            }

            if (isset($_POST['is_photo'])) {
                $is_photo = 1;
            } else {
                $is_photo = 0;
            }

            if (isset($_POST['is_class'])) {
                $is_class = 1;
            } else {
                $is_class = 0;
            }

            if (isset($_POST['is_section'])) {
                $is_section = 1;
            } else {
                $is_section = 0;
            }
            $insert_data = array(
                'id'              => $this->input->post('id'),
                'template'        => $this->input->post('template'),
                'heading'         => $this->input->post('heading'),
                'title'           => $this->input->post('title'),
                'exam_name'       => $this->input->post('exam_name'),
                'school_name'     => $this->input->post('school_name'),
                'exam_center'     => $this->input->post('exam_center'),
                'is_name'         => $is_name,
                'is_father_name'  => $is_father_name,
                'is_mother_name'  => $is_mother_name,
                'is_dob'          => $is_dob,
                'is_admission_no' => $is_admission_no,
                'is_roll_no'      => $is_roll_no,
                'is_address'      => $is_address,
                'is_gender'       => $is_gender,
                'is_photo'        => $is_photo,
                'is_class'        => $is_class,
                'is_section'      => $is_section,
                'content_footer'  => htmlentities($this->input->post('content_footer')),
            );

            $removebackground_image = $this->input->post('removebackground_image');
            $removeleft_logo        = $this->input->post('removeleft_logo');
            $removeright_logo       = $this->input->post('removeright_logo');
            $removesign             = $this->input->post('removesign');

            if ($removebackground_image != '') {
                $insert_data['background_img'] = '';
            }

            if ($removeleft_logo != '') {
                $insert_data['left_logo'] = '';
            }

            if ($removeright_logo != '') {
                $insert_data['right_logo'] = '';
            }

            if ($removesign != '') {
                $insert_data['sign'] = '';
            }

            if (isset($_FILES["left_logo"]) && $_FILES['left_logo']['name'] != '' && (!empty($_FILES['left_logo']['name']))) {

                $left_img_name            = $this->media_storage->fileupload("left_logo", "./uploads/admit_card/");
                $insert_data['left_logo'] = $left_img_name;
            }

            if (isset($_FILES["left_logo"]) && $_FILES['left_logo']['name'] != '' && (!empty($_FILES['left_logo']['name']))) {

                $this->media_storage->filedelete($this->data['admitcard']->left_logo, "uploads/admit_card");
            }

            if (isset($_FILES["right_logo"]) && $_FILES['right_logo']['name'] != '' && (!empty($_FILES['right_logo']['name']))) {

                $right_img_name = $this->media_storage->fileupload("right_logo", "./uploads/admit_card/");
                $insert_data['right_logo'] = $right_img_name;
            }

            if (isset($_FILES["right_logo"]) && $_FILES['right_logo']['name'] != '' && (!empty($_FILES['right_logo']['name']))) {

                $this->media_storage->filedelete($this->data['admitcard']->right_logo, "uploads/admit_card");
            }

            if (isset($_FILES["sign"]) && $_FILES['sign']['name'] != '' && (!empty($_FILES['sign']['name']))) {

                $sign_img_name = $this->media_storage->fileupload("sign", "./uploads/admit_card/");
                $insert_data['sign'] = $sign_img_name;
            }

            if (isset($_FILES["sign"]) && $_FILES['sign']['name'] != '' && (!empty($_FILES['sign']['name']))) {

                $this->media_storage->filedelete($this->data['admitcard']->sign, "uploads/admit_card");
            }

            if (isset($_FILES["background_img"]) && $_FILES['background_img']['name'] != '' && (!empty($_FILES['background_img']['name']))) {

                $background_img_name = $this->media_storage->fileupload("background_img", "./uploads/admit_card/");
                $insert_data['background_img'] = $background_img_name;
            }

            if (isset($_FILES["background_img"]) && $_FILES['background_img']['name'] != '' && (!empty($_FILES['background_img']['name']))) {

                $this->media_storage->filedelete($this->data['admitcard']->background_img, "uploads/admit_card");
            }

            $this->admitcard_model->add($insert_data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/admitcard/index');
        }

        $this->load->view('layout/header');
        $this->load->view('admin/admitcard/editadmitcard', $this->data);
        $this->load->view('layout/footer');
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('design_admit_card', 'can_delete')) {
            access_denied();
        }

        $data['title'] = 'Certificate List';
        $row           = $this->admitcard_model->get($id);

        if ($row->left_logo != '') {
            $this->media_storage->filedelete($row->left_logo, "uploads/admit_card/");
        }

        if ($row->right_logo != '') {
            $this->media_storage->filedelete($row->right_logo, "uploads/admit_card/");
        }

        if ($row->sign != '') {
            $this->media_storage->filedelete($row->sign, "uploads/admit_card/");
        }

        if ($row->background_img != '') {
            $this->media_storage->filedelete($row->background_img, "uploads/admit_card/");
        }

        $this->admitcard_model->remove($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/admitcard/index');
    }

    public function view()
    {
        $id                = $this->input->post('certificateid');
        $output            = '';
        $data              = array();
        $data['admitcard'] = $this->admitcard_model->get($id);
        $page              = $this->load->view('admin/admitcard/_view', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

}
