<?php

class Timeline extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->load->library('form_validation');
    }

    public function add()
    {
        $this->form_validation->set_rules('timeline_title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('timeline_date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('timeline_doc', $this->lang->line('image'), 'callback_handle_upload[timeline_doc]');
        $title = $this->input->post("timeline_title");

        if ($this->form_validation->run() == false) {

            $msg = array(
                'timeline_title' => form_error('timeline_title'),
                'timeline_date'  => form_error('timeline_date'),
                'timeline_doc'   => form_error('timeline_doc'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $timeline_date = $this->input->post('timeline_date');
            if (empty($visible_check)) {
                $visible = '';
            } else {
                $visible = $visible_check;
            }

            $img_name = $this->media_storage->fileupload("timeline_doc", "./uploads/student_timeline/");

            $timeline = array(
                'title'         => $this->input->post('timeline_title'),
                'description'   => $this->input->post('timeline_desc'),
                'timeline_date' => date('Y-m-d', $this->customlib->datetostrtotime($timeline_date)),
                'status'        => 'yes',
                'date'          => date('Y-m-d'),
                'student_id'    => $this->input->post('student_id'),
                'created_student_id'    => $this->input->post('student_id'),
                'document'      => $img_name,
            );

            $id = $this->timeline_model->add($timeline);

            $msg   = $this->lang->line('success_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }
        echo json_encode($array);
    }

    public function getstudentsingletimeline()
    {
        $id                         = $this->input->post('id');
        $data['singletimelinelist'] = $this->timeline_model->getstudentsingletimeline($id);
        $page                       = $this->load->view("user/_edit_student_timeline", $data, true);
        echo json_encode(array('page' => $page));
    }

    public function edit()
    {
        $this->form_validation->set_rules('timeline_title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('timeline_date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('timeline_doc', $this->lang->line('image'), 'callback_handle_upload[timeline_doc]');

        $title = $this->input->post("timeline_title");

        if ($this->form_validation->run() == false) {

            $msg = array(
                'timeline_title' => form_error('timeline_title'),
                'timeline_date'  => form_error('timeline_date'),
                'timeline_doc'   => form_error('timeline_doc'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $timeline_date = $this->input->post('timeline_date');
            if (empty($visible_check)) {
                $visible = '';
            } else {
                $visible = $visible_check;
            }

            $timeline = array(
                'id'            => $this->input->post('id'),
                'title'         => $this->input->post('timeline_title'),
                'description'   => $this->input->post('timeline_desc'),
                'timeline_date' => date('Y-m-d', $this->customlib->datetostrtotime($timeline_date)),
                'status'        => 'yes',
                'date'          => date('Y-m-d'),
                'student_id'    => $this->input->post('student_id'),
                'created_student_id'    => $this->input->post('student_id'),
            );

            $timeline_list = $this->timeline_model->getstudentsingletimeline($this->input->post('id'));
            if (isset($_FILES["timeline_doc"]) && $_FILES['timeline_doc']['name'] != '' && (!empty($_FILES['timeline_doc']['name']))) {
                $img_name = $this->media_storage->fileupload("timeline_doc", "./uploads/student_timeline/");
            } else {
                $img_name = $timeline_list['document'];
            }

            $timeline['document'] = $img_name;
            if (isset($_FILES["timeline_doc"]) && $_FILES['timeline_doc']['name'] != '' && (!empty($_FILES['timeline_doc']['name']))) {
                if ($timeline_list['document'] != "") {
                    $this->media_storage->filedelete($timeline_list['document'], "uploads/student_timeline");
                }
            }

            $this->timeline_model->add($timeline);

            $msg   = $this->lang->line('success_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }
        echo json_encode($array);
    }

    public function handle_upload()
    {
        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();

        if (isset($_FILES["timeline_doc"]) && !empty($_FILES['timeline_doc']['name'])) {

            $file_type         = $_FILES["timeline_doc"]['type'];
            $file_size         = $_FILES["timeline_doc"]["size"];
            $file_name         = $_FILES["timeline_doc"]["name"];
            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = filesize($_FILES['timeline_doc']['tmp_name'])) {

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
        $timelinelist = $this->timeline_model->getstudentsingletimeline($id);
        $this->media_storage->filedownload($timelinelist['document'], "./uploads/student_timeline");
    }

    public function delete_timeline()
    {
        $id  = $this->input->post('id');
        $row = $this->timeline_model->getstudentsingletimeline($id);
        if ($row['document'] != '') {
            $this->media_storage->filedelete($row['document'], "uploads/student_timeline/");
        }

        $this->timeline_model->delete_timeline($id);
        echo json_encode(array('status' => 'success', 'message' => $this->lang->line('delete_message')));
    }

}
