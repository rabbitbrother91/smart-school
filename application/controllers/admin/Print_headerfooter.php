<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Print_headerfooter extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
    }

    public function index()
    {
        if (!($this->rbac->hasPrivilege('print_header_footer', 'can_view'))) {
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'admin/print_headerfooter');
        $data['title']  = 'SMS Config List';
        $data['result'] = $this->setting_model->get_printheader();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/print_headerfooter/print_headerfooter', $data);
        $this->load->view('layout/footer', $data);
    }

    public function edit()
    {
        $message = "";
        if (isset($_POST['type'])) {
            $is_required = $this->setting_model->check_haederimage($_POST['type']);
            $this->form_validation->set_rules('header_image', $this->lang->line('header_image'), 'trim|xss_clean|callback_handle_upload[' . $is_required . ']');

            if ($_POST['type'] == 'staff_payslip') {
                $message = 'message';
            } else if ($_POST['type'] == 'online_admission_receipt') {
                $message = "admission_message";
            } else if ($_POST['type'] == 'online_exam') {
                $message = 'online_exam_message';
            } else {
                $message = 'message1';
            }
        }

        if ($this->form_validation->run() == false) {

        } else {

            if (isset($_FILES["header_image"]) && !empty($_FILES['header_image']['name'])) {

                if ($_POST['type'] == 'student_receipt') {

                    $img_name = $this->media_storage->fileupload("header_image", "./uploads/print_headerfooter/student_receipt/");

                    $row = $this->setting_model->unlink_receiptheader();

                    if (!empty($row['header_image'])) {
                        $this->media_storage->filedelete($row['header_image'], "uploads/print_headerfooter/student_receipt/");
                    }
                } else if ($_POST['type'] == 'online_admission_receipt') {

                    $img_name = $this->media_storage->fileupload("header_image", "./uploads/print_headerfooter/online_admission_receipt/");

                    $row = $this->setting_model->unlink_onlinereceiptheader();
                  
                    if (!empty($row['header_image'])) {
                        $this->media_storage->filedelete($row['header_image'], "uploads/print_headerfooter/online_admission_receipt/");
                    }
                } else if ($_POST['type'] == 'online_exam') {

                    $img_name = $this->media_storage->fileupload("header_image", "./uploads/print_headerfooter/online_exam/");

                    $row = $this->setting_model->get_onlineexamheader();

                    if (!empty($row['header_image'])) {
                        $this->media_storage->filedelete($row['header_image'], "uploads/print_headerfooter/online_exam/");
                    }
                } else {

                    $img_name = $this->media_storage->fileupload("header_image", "./uploads/print_headerfooter/staff_payslip/");

                    $row = $this->setting_model->unlink_payslipheader();

                    if ($row != '') {
                        $this->media_storage->filedelete($row, "uploads/print_headerfooter/staff_payslip/");
                    }
                }

                $data = array('print_type' => $_POST['type'], 'header_image' => $img_name, 'footer_content' => $_POST[$message], 'created_by' => $this->customlib->getStaffID());
 
                $this->setting_model->add_printheader($data);
            }

            $data = array('print_type' => $_POST['type'], 'footer_content' => $_POST[$message], 'created_by' => $this->customlib->getStaffID());
            $this->setting_model->add_printheader($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
        }

        redirect('admin/print_headerfooter');
    }

    public function handle_upload($str, $is_required)
    {
        $image_validate = $this->config->item('image_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["header_image"]) && !empty($_FILES['header_image']['name']) && $_FILES["header_image"]["size"] > 0) {

            $file_type = $_FILES["header_image"]['type'];
            $file_size = $_FILES["header_image"]["size"];
            $file_name = $_FILES["header_image"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->image_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->image_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['header_image']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
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

            return true;
        } else {
            if ($is_required == 0) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('please_choose_a_file_to_upload'));
                return false;
            } else {
                return true;
            }
        }
    }

}
