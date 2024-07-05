<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Apply_leave extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->load->model("filetype_model");
        $this->load->library('mailsmsconf');
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'apply_leave');
        $student_session_id     = $this->session->userdata['current_class']['student_session_id'];
        $student_id             = $this->customlib->getStudentSessionUserID();
        $student                = $this->student_model->get($student_id);
        $data['results']        = $this->apply_leave_model->get_student($student_session_id);
        $data['studentclasses'] = $this->studentsession_model->searchMultiClsSectionByStudent($student_id);
        $this->load->view('layout/student/header', $data);
        $this->load->view('user/apply_leave/apply_leave', $data);
        $this->load->view('layout/student/footer', $data);
    }

    public function get_details($id)
    {
        $data               = $this->apply_leave_model->getstudentleave($id, null, null);        
        $data['from_date']  = date($this->customlib->getSchoolDateFormat(), strtotime($data['from_date']));
        $data['to_date']    = date($this->customlib->getSchoolDateFormat(), strtotime($data['to_date']));
        $data['apply_date'] = date($this->customlib->getSchoolDateFormat(), strtotime($data['apply_date']));
        echo json_encode($data);
    }

    public function add()
    {
        $student_session_id = $this->session->userdata['current_class']['student_session_id'];
        $student_id         = $this->customlib->getStudentSessionUserID();
        $this->form_validation->set_rules('apply_date', $this->lang->line('apply_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('from_date', $this->lang->line('from_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('to_date', $this->lang->line('to_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('files', $this->lang->line('documents'), 'callback_handle_upload');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'apply_date' => form_error('apply_date'),
                'from_date'  => form_error('from_date'),
                'to_date'    => form_error('to_date'),
                'files'      => form_error('files'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $upload_file = array();

            $data = array(
                'apply_date'         => $this->customlib->dateFormatToYYYYMMDD($this->input->post('apply_date')),
                'from_date'          => $this->customlib->dateFormatToYYYYMMDD($this->input->post('from_date')),
                'to_date'            => $this->customlib->dateFormatToYYYYMMDD($this->input->post('to_date')),
                'student_session_id' => $student_session_id,
                'reason'             => $this->input->post('message'),
            );

            if ($this->input->post('leave_id') == '') {
                $leave_id = $this->apply_leave_model->add($data);
            } else {
                $data['id'] = $this->input->post('leave_id');
                $leave_id   = $data['id'];
                $this->apply_leave_model->add($data);
            }

            $student_current_class = $this->customlib->getStudentCurrentClsSection();
            $class_id              = $student_current_class->class_id;
            $section_id            = $student_current_class->section_id;

            $sender_details = array(
                'class_id'           => $student_current_class->class_id,
                'section_id'         => $student_current_class->section_id,
                'message'            => $this->input->post('message'),
                'apply_date'         => $this->input->post('apply_date'),
                'from_date'          => $this->input->post('from_date'),
                'to_date'            => $this->input->post('to_date'),
                'student_session_id' => $student_session_id,
            );

            $this->mailsmsconf->mailsms('student_apply_leave', $sender_details, '', '', $_FILES);

            if (isset($_FILES["files"]["name"][0]) && !empty($_FILES["files"]["name"][0]) && $_FILES['files']['error'][0] == 0) {
                
                foreach ($_FILES['files']['tmp_name'] as $index => $tmpName) {
                    if (!empty($_FILES['files']['tmp_name'][$index])) {
                        
                        
                        $fileInfo = pathinfo($_FILES["files"]["name"][0]);
                        
                        $img_name = time() . "-" . uniqid(rand()) . "!" . $_FILES["files"]["name"][0];
                        
                        
                        move_uploaded_file($_FILES["files"]["tmp_name"][0], "./uploads/student_leavedocuments/" . $img_name);
                        
                        $data = array('id' => $leave_id, 'docs' => $img_name);
                        $this->apply_leave_model->add($data);
                    }
                }
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function remove_leave($id)
    {
        $row = $this->apply_leave_model->get($id, null, null);
        if ($row['docs'] != '') {
            $this->media_storage->filedelete($row['docs'], "uploads/student_leavedocuments/");
        }
        $this->apply_leave_model->remove_leave($id);
        redirect('user/apply_leave');
    }

    public function download($id)
    {
        $leavelist = $this->apply_leave_model->get($id, null, null);
        $this->media_storage->filedownload($leavelist['docs'], "./uploads/student_leavedocuments");
    }

    public function handle_upload($str, $var1)
    {
        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();

        if (isset($_FILES["files"]["name"][0]) && !empty($_FILES["files"]["name"][0])) {

            $file_type         = $_FILES["files"]["type"][0];
            $file_size         = $_FILES["files"]["size"][0];
            $file_name         = $_FILES["files"]["name"][0];
            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = filesize($_FILES["files"]['tmp_name'][0])) {

                if (!in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {

                    $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
                    return false;
                }
                
                if ($file_size > $result->file_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . " MB");
                    return false;
                }

            } else {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_extension_error_uploading'));
                return false;
            }

            return true;
        }

        return true;

    }

}
