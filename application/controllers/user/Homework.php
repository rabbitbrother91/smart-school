<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Homework extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->load->library("customlib");
        $this->load->model("homework_model");
        $this->load->model("staff_model");
        $this->load->model("student_model");
        $this->load->model("filetype_model");
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'Homework');
        $student_id            = $this->customlib->getStudentSessionUserID();
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        $data["created_by"]    = "";
        $data["evaluated_by"]  = "";
        $userdata              = $this->customlib->getLoggedInUserData();
        $result                = $this->student_model->getRecentRecord($student_id);
        $class_id              = $student_current_class->class_id;
        $section_id            = $student_current_class->section_id;
        $homeworklist          = $this->homework_model->getStudentHomeworkWithStatus($class_id, $section_id, $student_current_class->student_session_id);

        foreach ($homeworklist as $key => $homeworklist_value) {
            $homeworklist[$key]['status'] = '';
            $checkstatus                  = $this->homework_model->checkstatus($homeworklist_value['id'], $student_id);
            if ($checkstatus['record_count'] != 0) {
                $homeworklist[$key]['status'] = 'submitted';
            }
        }

        $data["homeworklist"] = $homeworklist;
        $closedhomeworklist   = $this->homework_model->getstudentclosedhomeworkwithstatus($class_id, $section_id, $student_current_class->student_session_id);
        foreach ($closedhomeworklist as $key => $closedhomeworklist_value) {
            $closedhomeworklist[$key]['status'] = '';
            $checkstatus                        = $this->homework_model->checkstatus($closedhomeworklist_value['id'], $student_id);

            if ($checkstatus['record_count'] != 0) {
                $closedhomeworklist[$key]['status'] = 'submitted';
            }
        } 

        $data["closedhomeworklist"] = $closedhomeworklist;
        $this->load->view("layout/student/header");
        $this->load->view("user/homework/homeworklist", $data);
        $this->load->view("layout/student/footer");
    }

    public function upload_docs()
    {
        $homework_id         = $_REQUEST['homework_id'];
        $userdata            = $this->customlib->getLoggedInUserData();
        $student_id          = $userdata["student_id"];
        $data['homework_id'] = $homework_id;
        $data['student_id']  = $student_id;
        $is_required         = $this->homework_model->check_assignment($homework_id, $student_id);
        $this->form_validation->set_rules('message', $this->lang->line('message'), 'trim|required|xss_clean');
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $this->form_validation->set_rules('file', $this->lang->line('attach_document'), 'trim|xss_clean|callback_handle_upload[' . $is_required . ']');
        }

        if ($this->form_validation->run() == false) {
            $msg = array(
                'message' => form_error('message'),
                'file'    => form_error('file'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $data['message'] = $this->input->post('message');

            $img_name = $this->media_storage->fileupload("file", "./uploads/homework/assignment/");
            if ($img_name != '') {
                $data['docs'] = $img_name;
            }

            $this->homework_model->upload_docs($data);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }

        echo json_encode($array);
    }

    public function homework_detail($id, $status)
    {
        $settingresult           = $this->setting_model->getSetting();
        $superadmin_restriction  = $settingresult->superadmin_restriction;
        $data['homework_status'] = $status;
        $data["title"]           = "Homework Evaluation";
        $userdata                = $this->customlib->getLoggedInUserData();
        $student_id              = $userdata["student_id"];
        $data['homework_id']     = $id;
        $result                  = $this->homework_model->getRecord($id);

        $class_id             = $result["class_id"];
        $section_id           = $result["section_id"];
        $studentlist          = $this->homework_model->getStudents($class_id, $section_id);
        $data["studentlist"]  = $studentlist;
        $data["result"]       = $result;
        $report               = $this->homework_model->getEvaluationReportForStudent($id, $student_id);
        $data["report"]       = $report;
        $created_by  = "";
        $evaluated_by = "";
        $data["homeworkdocs"] = $this->homework_model->get_homeworkDocByIdStdid($id, $student_id);

        $create_data = $this->staff_model->get($result["created_by"]);       

        if ($superadmin_restriction == 'disabled') {
            if ($create_data['role_id'] != 7) {
                $created_by = ($create_data['surname'] != "") ? $create_data["name"] . " " . $create_data["surname"] . "  (" . $create_data["employee_id"] . ")" : $create_data["name"] . " (" . $create_data['employee_id'] . ")";
            } else {
                $created_by = '';
            }  
        } else { 
            $created_by = ($create_data['surname'] != "") ? $create_data["name"] . " " . $create_data["surname"] . "  (" . $create_data["employee_id"] . ")" : $create_data["name"] . " (" . $create_data['employee_id'] . ")";            
        }        
        
        if($result["evaluated_by"]){
            $eval_data   = $this->staff_model->get($result["evaluated_by"]);

            if ($superadmin_restriction == 'disabled') {                

                if ($eval_data['role_id'] != 7) {
                    $eval_employeeid = '';
                    if ($eval_data["employee_id"] != '') {
                        $eval_employeeid = ' (' . $eval_data["employee_id"] . ')';
                    }
                    $evaluated_by = ($eval_data['surname'] != "") ? $eval_data["name"] . " " . $eval_data["surname"] . $eval_employeeid : $eval_data["name"] . $eval_employeeid . ")";
                } else {
                    $evaluated_by = '';
                }
            } else {
                $eval_employeeid = '';
                if ($eval_data["employee_id"] != '') {
                    $eval_employeeid = ' (' . $eval_data["employee_id"] . ')';
                } 
                $evaluated_by = ($eval_data['surname'] != "") ? $eval_data["name"] . " " . $eval_data["surname"] . $eval_employeeid : $eval_data["name"] . $eval_employeeid;
            }
        }        
        
        $data["created_by"]   = $created_by;
        $data["evaluated_by"] = $evaluated_by;

        $checkstatus    = $this->homework_model->checkstatus($data['homework_id'], $student_id);
        $data['status'] = '';
        if ($checkstatus['record_count'] != 0) {
            $data['status'] = 'submitted';
        }
        $this->load->view("user/homework/homework_detail", $data);
    }

    public function download($id)
    {
        $homework = $this->homework_model->getRecord($id);
        $this->media_storage->filedownload($homework['document'], "./uploads/homework");
    }

    public function assigmnetDownload($id)
    {
        $userdata     = $this->customlib->getLoggedInUserData();
        $student_id   = $userdata["student_id"];
        $homeworkdocs = $this->homework_model->get_homeworkDocByIdStdid($id, $student_id);
        $this->media_storage->filedownload($homeworkdocs[0]['docs'], "./uploads/homework/assignment");
    }

    public function handle_upload($str, $is_required)
    {
        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0) {

            $file_type = $_FILES["file"]['type'];
            $file_size = $_FILES["file"]["size"];
            $file_name = $_FILES["file"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION)); 
            
            if (!in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload', 'File Type Not Allowed');
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload', 'Extension Not Allowed');
                return false;
            }

            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . " MB");
                return false;
            }

            return true;
        } else {
            if ($is_required == 0) {
                $this->form_validation->set_message('handle_upload', 'Please choose a file to upload.');
                return false;
            } else {
                return true;
            }
        }
    }

    public function assignment_handle_upload($str, $is_required)
    {
        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();

        if (isset($_FILES["file"]) && !empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0) {

            $file_type = $_FILES["file"]['type'];
            $file_size = $_FILES["file"]["size"];
            $file_name = $_FILES["file"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['file']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('assignment_handle_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('assignment_handle_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }

            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('assignment_handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . " MB");
                return false;
            }

            return true;
        } else {
            return true;
        }

    }

    public function dailyassignment()
    {
        $this->session->set_userdata('top_menu', 'Homework');
        $student_id                  = $this->customlib->getStudentSessionUserID();
        $student_current_session     = $this->customlib->getStudentCurrentClsSection();        
        $dailyassignmentlist         = $this->homework_model->getdailyassignment($student_id, $student_current_session->student_session_id);        
        $data["dailyassignmentlist"] = $dailyassignmentlist;        
        $class_id                    = $student_current_session->class_id;
        $section_id                  = $student_current_session->section_id;

        $data['subjectlist'] = $this->subjectgroup_model->getAllsubjectByClassSection($class_id, $section_id);       
        $this->load->view("layout/student/header");
        $this->load->view("user/homework/dailyassignmentlist", $data);
        $this->load->view("layout/student/footer");
    }

    public function createdailyassignment()
    {
        $student_current_session = $this->customlib->getStudentCurrentClsSection();
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject', $this->lang->line('subject'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_assignment_handle_upload');
        if ($this->form_validation->run() == false) {

            $msg = array(
                'title'   => form_error('title'),
                'subject' => form_error('subject'),
                'file'    => form_error('file'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');

        } else {

            $img_name = $this->media_storage->fileupload("file", "./uploads/homework/daily_assignment/");

            $data = array(
                'title'                    => $this->input->post('title'),
                'student_session_id'       => $student_current_session->student_session_id,
                'description'              => $this->input->post('description'),
                'subject_group_subject_id' => $this->input->post('subject'),
                'date'                     => date('Y-m-d'),
                'attachment'               => $img_name,
                'evaluated_by'             => NULL,
            );

            $id = $this->homework_model->adddailyassignment($data);

            $msg   = $this->lang->line('success_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }

        echo json_encode($array);
    }

    public function editdailyassignment()
    {
        $assignment_id               = $this->input->post('assignment_id');
        $singledailyassignmentlist   = $this->homework_model->getsingledailyassignment($assignment_id);
        $data["dailyassignmentlist"] = $singledailyassignmentlist;
        $student_current_class       = $this->customlib->getStudentCurrentClsSection();
        $class_id                    = $student_current_class->class_id;
        $section_id                  = $student_current_class->section_id;
        $data['subjectlist']         = $this->subjectgroup_model->getAllsubjectByClassSection($class_id, $section_id);  
        $page                        = $this->load->view("user/homework/_editdailyassignment", $data, true);
        echo json_encode(array('page' => $page));
    }

    public function updatedailyassignment()
    {
        $student_current_session = $this->customlib->getStudentCurrentClsSection();
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject', $this->lang->line('subject'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_assignment_handle_upload');
        if ($this->form_validation->run() == false) {

            $msg = array(
                'title'   => form_error('title'),
                'subject' => form_error('subject'),
                'file'    => form_error('file'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');

        } else {

            $data = array(
                'id'                       => $this->input->post('assigment_id'),
                'title'                    => $this->input->post('title'),
                'student_session_id'       => $student_current_session->student_session_id,
                'subject_group_subject_id' => $this->input->post('subject'),
                'description'              => $this->input->post('description'),
                'date'                     => date('Y-m-d'),
            );

            $assignment_list = $this->homework_model->getsingledailyassignment($this->input->post('assigment_id'));
            if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {

                $img_name = $this->media_storage->fileupload("file", "./uploads/homework/daily_assignment/");
            } else {
                $img_name = $assignment_list['attachment'];
            }

            $data['attachment'] = $img_name;

            if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {
                if ($assignment_list['attachment'] != '') {
                    $this->media_storage->filedelete($assignment_list['attachment'], "uploads/homework/daily_assignment");
                }
            }

            $id = $this->homework_model->adddailyassignment($data);

            $msg   = $this->lang->line('success_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }

        echo json_encode($array);
    }

    public function deletedailyassignment($id)
    {
        $row = $this->homework_model->getsingledailyassignment($id);
        if ($row['attachment'] != '') {
            $this->media_storage->filedelete($row['attachment'], "uploads/homework/daily_assignment/");
        }

        $this->homework_model->deletedailyassignment($id);
        redirect('user/homework/dailyassignment');
    }

    public function dailyassigmnetdownload($id)
    {
        $dailyassigmnetlist = $this->homework_model->getsingledailyassignment($id);
        $this->media_storage->filedownload($dailyassigmnetlist['attachment'], "./uploads/homework/daily_assignment");
    }
}
