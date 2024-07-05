<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Homework extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('media_storage');
        $this->load->model("homework_model");
        $this->load->model("staff_model");
        $this->load->model("classteacher_model");
        $this->config->load("app-config");
        $this->load->library('mailsmsconf');
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->role;
        $this->search_type        = $this->customlib->get_searchtype();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('homework', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Homework');
        $this->session->set_userdata('sub_menu', 'homework');
        $data["title"] = "Create Homework";

        $data['classlist'] = $this->class_model->get();

        $userdata                 = $this->customlib->getUserData();
        $carray                   = array();
        $data['class_id']         = "";
        $data['section_id']       = "";
        $data['subject_group_id'] = "";
        $data['subject_id']       = "";

        $this->load->view("layout/header", $data);
        $this->load->view("homework/homeworklist", $data);
        $this->load->view("layout/footer", $data);
    }

    public function searchvalidation()
    {
        $class_id         = $this->input->post('class_id');
        $section_id       = $this->input->post('section_id');
        $subject_group_id = $this->input->post('subject_group_id');
        $subject_id       = $this->input->post('subject_id');

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $error = array();

            $error['class_id'] = form_error('class_id');
            $array             = array('status' => 0, 'error' => $error);
            echo json_encode($array);
        } else {
            $class_id   = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');

            $params = array('class_id' => $class_id, 'section_id' => $section_id, 'subject_group_id' => $subject_group_id, 'subject_id' => $subject_id);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    public function dthomeworklist()
    {
        $currency_symbol  = $this->customlib->getSchoolCurrencyFormat();
        $class_id         = $this->input->post('class_id');
        $section_id       = $this->input->post('section_id');
        $subject_group_id = $this->input->post('subject_group_id');
        $subject_id       = $this->input->post('subject_id');

        $carray       = array();
        $homeworklist = $this->homework_model->search_dthomework($class_id, $section_id, $subject_group_id, $subject_id);
        $homework = json_decode($homeworklist);

        $getStaffRole       = $this->customlib->getStaffRole();
        $staffrole          = json_decode($getStaffRole);
        $superadmin_visible = $this->customlib->superadmin_visible();

        $dt_data = array();
        if (!empty($homework->data)) {
            foreach ($homework->data as $homework_key => $homeworklist) {

                $editbtn    = '';
                $deletebtn  = '';
                $viewbtn    = '';
                $collectbtn = '';

                if ($this->rbac->hasPrivilege('homework_evaluation', 'can_view')) {
                    $viewbtn = "<a onclick='evaluation(" . '"' . $homeworklist->id . '"' . ")' title=''  data-toggle='tooltip'  data-original-title=" . $this->lang->line('evaluation') . " class='btn btn-default btn-xs'  title='" . $this->lang->line('evaluation') . "' data-toggle='tooltip'><i class='fa fa-reorder'></i></a>";
                }

                if ($this->rbac->hasPrivilege('homework', 'can_edit')) {
                    $editbtn = "<a  class='btn btn-default btn-xs modal_form'  data-toggle='tooltip'   data-method_call='edit' data-original-title='" . $this->lang->line('edit') . "' data-record_id=" . $homeworklist->id . " ><i class='fa fa-pencil'></i></a>";
                }

                if ($this->rbac->hasPrivilege('homework', 'can_delete')) {
                    $collectbtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . "  )' href='" . base_url() . "homework/delete/" . $homeworklist->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip'  title='" . $this->lang->line('delete') . "' data-original-title='" . $this->lang->line('delete') . "'><i class='fa fa-remove'></i></a>";
                }

                $subject_code = '';
                if ($homeworklist->subject_code) {
                    $subject_code = ' (' . $homeworklist->subject_code . ')';
                }

                $row   = array();
                $row[] = $homeworklist->class;
                $row[] = $homeworklist->section;
                $row[] = $homeworklist->name;
                $row[] = $homeworklist->subject_name . ' ' . $subject_code;
                $row[] = $this->customlib->dateformat($homeworklist->homework_date);
                $row[] = $this->customlib->dateformat($homeworklist->submit_date);

                $evl_date = "";

                $row[] = $this->customlib->dateformat($homeworklist->evaluation_date);

                if ($staffrole->id != 7) {
                    if ($superadmin_visible == 'disabled') {

                        if ($homeworklist->role_id == 7) {
                            $row[] = '';
                        } else {
                            $row[] = $homeworklist->staff_name . ' ' . $homeworklist->staff_surname . ' (' . $homeworklist->staff_employee_id . ')';
                        }
                    } else {
                        $row[] = $homeworklist->staff_name . ' ' . $homeworklist->staff_surname . ' (' . $homeworklist->staff_employee_id . ')';
                    }
                } else {
                    $row[] = $homeworklist->staff_name . ' ' . $homeworklist->staff_surname . ' (' . $homeworklist->staff_employee_id . ')';
                }

                $row[]     = $viewbtn . '' . $editbtn . '' . $collectbtn;
                $dt_data[] = $row;
            }
        }
        $json_data = array(
            "draw"            => intval($homework->draw),
            "recordsTotal"    => intval($homework->recordsTotal),
            "recordsFiltered" => intval($homework->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function closehomeworklist()
    {
        $currency_symbol  = $this->customlib->getSchoolCurrencyFormat();
        $class_id         = $this->input->post('class_id');
        $section_id       = $this->input->post('section_id');
        $subject_group_id = $this->input->post('subject_group_id');
        $subject_id       = $this->input->post('subject_id');

        $userdata     = $this->customlib->getUserData();
        $carray       = array();
        $homeworklist = $this->homework_model->search_closehomework($class_id, $section_id, $subject_group_id, $subject_id);

        $homework           = json_decode($homeworklist);
        $getStaffRole       = $this->customlib->getStaffRole();
        $staffrole          = json_decode($getStaffRole);
        $superadmin_visible = $this->customlib->superadmin_visible();

        $dt_data = array();
        if (!empty($homework->data)) {

            foreach ($homework->data as $homework_key => $homeworklist) {

                $editbtn    = '';
                $deletebtn  = '';
                $viewbtn    = '';
                $collectbtn = '';

                if ($this->rbac->hasPrivilege('homework_evaluation', 'can_view')) {
                    $viewbtn = "<a onclick='evaluation(" . '"' . $homeworklist->id . '"' . "  )' title=''  data-toggle='tooltip'  data-original-title=" . $this->lang->line('evaluation') . " class='btn btn-default btn-xs'  title='" . $this->lang->line('evaluation') . "' data-toggle='tooltip'><i class='fa fa-reorder'></i></a>";
                }

                if ($this->rbac->hasPrivilege('homework', 'can_edit')) {
                    $editbtn = "<a  class='btn btn-default btn-xs modal_form'  data-toggle='tooltip'   data-method_call='edit' data-original-title='" . $this->lang->line('edit') . "' data-record_id=" . $homeworklist->id . " ><i class='fa fa-pencil'></i></a>";
                }

                if ($this->rbac->hasPrivilege('homework', 'can_delete')) {
                    $collectbtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . "  )' href='" . base_url() . "homework/delete/" . $homeworklist->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip'  title='" . $this->lang->line('delete') . "' data-original-title='" . $this->lang->line('delete') . "'><i class='fa fa-remove'></i></a>";
                }

                $subject_code = '';
                if ($homeworklist->subject_code) {
                    $subject_code = ' (' . $homeworklist->subject_code . ')';
                }

                $row   = array();
                $row[] = '<input type="checkbox" id="delete_homework" name="delete_homework[]" value="' . $homeworklist->id . '">';
                $row[] = $homeworklist->class;
                $row[] = $homeworklist->section;
                $row[] = $homeworklist->name;
                $row[] = $homeworklist->subject_name . ' ' . $subject_code;

                if ($homeworklist->homework_date != null && $homeworklist->homework_date != '0000-00-00') {
                    $row[] = $this->customlib->dateformat($homeworklist->homework_date);
                } else {
                    $row[] = "";
                }
                $row[] = $this->customlib->dateformat($homeworklist->submit_date);

                $evl_date = "";
                if ($homeworklist->evaluation_date != "0000-00-00") {

                    $row[] =  $this->customlib->dateformat($homeworklist->evaluation_date);
                } else {
                    $row[] = "";
                }

                if ($staffrole->id != 7) {
                    if ($superadmin_visible == 'disabled') {

                        if ($homeworklist->role_id == 7) {
                            $row[] = '';
                        } else {
                            $row[] = $homeworklist->staff_name . ' ' . $homeworklist->staff_surname . ' (' . $homeworklist->staff_employee_id . ')';
                        }
                    } else {
                        $row[] = $homeworklist->staff_name . ' ' . $homeworklist->staff_surname . ' (' . $homeworklist->staff_employee_id . ')';
                    }
                } else {
                    $row[] = $homeworklist->staff_name . ' ' . $homeworklist->staff_surname . ' (' . $homeworklist->staff_employee_id . ')';
                }

                $row[]     = $viewbtn . '' . $editbtn . '' . $collectbtn;
                $dt_data[] = $row;
            }
        }
        $json_data = array(
            "draw"            => intval($homework->draw),
            "recordsTotal"    => intval($homework->recordsTotal),
            "recordsFiltered" => intval($homework->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function homework_docs($id)
    {
        $docs = $this->homework_model->get_homeworkDocByid($id);
        $docs = json_decode($docs);

        $dt_data = array();
        if (!empty($docs->data)) {

            foreach ($docs->data as $key => $value) {

                if (!empty($value->docs)) {
                    $doc = '<a class="btn btn-default btn-xs" href="' . base_url() . 'homework/assigmnetDownload/' . $value->docs . '"   data-toggle="tooltip" data-original-title=' . $this->lang->line("download") . '>
                <i class="fa fa-download"></i></a>';
                } else {
                    $doc = "";
                }

                if (!empty($value->message)) {
                    $message = $value->message;
                } else {
                    $message = '';
                }

                $row       = array();
                $row[]     = $this->customlib->getFullName($value->firstname, $value->middlename, $value->lastname, $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname) . " (" . $value->admission_no . ")";
                $row[]     = $message;
                $row[]     = $doc;
                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw"            => intval($docs->draw),
            "recordsTotal"    => intval($docs->recordsTotal),
            "recordsFiltered" => intval($docs->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('homework', 'can_add')) {
            access_denied();
        }

        $data["title"]      = "Create Homework";
        $class              = $this->class_model->get();
        $data['classlist']  = $class;
        $data['class_id']   = "";
        $data['section_id'] = "";
        $userdata           = $this->customlib->getUserData();
        $this->form_validation->set_rules('record_id', $this->lang->line('record_id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('modal_class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('modal_section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('modal_subject_group_id', $this->lang->line('subject_group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('modal_subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('homework_date', $this->lang->line('homework_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('submit_date', $this->lang->line('submission_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('userfile', $this->lang->line('image'), 'callback_handle_upload');
        if ($this->form_validation->run() == false) {

            $msg = array(
                'record_id'              => form_error('record_id'),
                'modal_class_id'         => form_error('modal_class_id'),
                'modal_section_id'       => form_error('modal_section_id'),
                'modal_subject_group_id' => form_error('modal_subject_group_id'),
                'modal_subject_id'       => form_error('modal_subject_id'),
                'homework_date'          => form_error('homework_date'),
                'submit_date'            => form_error('submit_date'),
                'description'            => form_error('description'),
                'userfile'               => form_error('userfile'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $session_id = $this->setting_model->getCurrentSession();
            $record_id  = $this->input->post('record_id');
            
            if ($this->input->post("homework_marks")) {
                $marks    =    $this->input->post("homework_marks");
            } else {
                $marks    = NULL;
            }

            $data       = array(
                'id'                       => $record_id,
                'session_id'               => $session_id,
                'class_id'                 => $this->input->post("modal_class_id"),
                'section_id'               => $this->input->post("modal_section_id"),
                'homework_date'            => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('homework_date'))),
                'submit_date'              => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('submit_date'))),
                'staff_id'                 => $userdata["id"],
                'subject_group_subject_id' => $this->input->post("modal_subject_id"),
                'marks'                    => $marks,
                'description'              => $this->input->post("description"),
                'create_date'              => date("Y-m-d"),
                'created_by'               => $userdata["id"],
                'subject_id'               => NULL,

            );

            if ($record_id > 0) {
                $homeworklist = $this->homework_model->get($record_id);

                if (isset($_FILES["userfile"]) && $_FILES['userfile']['name'] != '' && (!empty($_FILES['userfile']['name']))) {
                    $img_name = $this->media_storage->fileupload("userfile", "./uploads/homework/");
                } else {
                    $img_name = $homeworklist['document'];
                }

                $data['document'] = $img_name;

                if (isset($_FILES["userfile"]) && $_FILES['userfile']['name'] != '' && (!empty($_FILES['userfile']['name']))) {
                    $this->media_storage->filedelete($homeworklist['document'], "uploads/homework");
                }
            } else {
                $img_name         = $this->media_storage->fileupload("userfile", "./uploads/homework/");
                $data['document'] = $img_name;
            }

            $id = $this->homework_model->add($data);

            if ($record_id > 0) {
                $id = $record_id;
            } else {
            }

            if ($record_id == 0) {
                $homework_detail = $this->homework_model->get($id);

                $sender_details = array(
                    'class_id'      => $this->input->post("modal_class_id"),
                    'section_id'    => $this->input->post("modal_section_id"),
                    'homework_date' => date($this->customlib->getSchoolDateFormat(), $this->customlib->dateYYYYMMDDtoStrtotime($homework_detail['homework_date'])),
                    'submit_date'   => date($this->customlib->getSchoolDateFormat(), $this->customlib->dateYYYYMMDDtoStrtotime($homework_detail['submit_date'])),
                    'subject'       => $homework_detail['subject_name'],
                );

                $this->mailsmsconf->mailsms('homework', $sender_details);
            }

            $msg   = $this->lang->line('success_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }

        echo json_encode($array);
    }

    public function handle_upload()
    {
        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["userfile"]) && !empty($_FILES['userfile']['name'])) {

            $file_type = $_FILES["userfile"]['type'];
            $file_size = $_FILES["userfile"]["size"];
            $file_name = $_FILES["userfile"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = filesize($_FILES['userfile']['tmp_name'])) {

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

    public function getRecord()
    {
        if (!$this->rbac->hasPrivilege('homework', 'can_edit')) {
            access_denied();
        }
        $id             = $this->input->post('id');
        $result         = $this->homework_model->get($id);
        $data["result"] = $result;

        echo json_encode($result);
    }

    public function edit()
    {
        if (!$this->rbac->hasPrivilege('homework', 'can_edit')) {
            access_denied();
        }
        $id            = $this->input->post("homeworkid");
        $data["title"] = "Edit Homework";

        $class              = $this->class_model->get();
        $data['classlist']  = $class;
        $result             = $this->homework_model->get($id);
        $data["result"]     = $result;
        $data['class_id']   = $result["class_id"];
        $data['section_id'] = $result["section_id"];
        $data['subject_id'] = $result["subject_id"];
        $data["id"]         = $id;
        $userdata           = $this->customlib->getUserData();
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('homework_date', $this->lang->line('homework_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'class_id'      => form_error('class_id'),
                'section_id'    => form_error('section_id'),
                'subject_id'    => form_error('subject_id'),
                'homework_date' => form_error('homework_date'),
                'description'   => form_error('description'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            if (isset($_FILES["userfile"]) && !empty($_FILES['userfile']['name'])) {
                $uploaddir = './uploads/homework/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo = pathinfo($_FILES["userfile"]["name"]);
                $document = basename($_FILES['userfile']['name']);
                $img_name = $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["userfile"]["tmp_name"], $uploaddir . $img_name);
            } else {
                $document = $this->input->post("document");
            }

            $data = array(
                'id'            => $id,
                'class_id'      => $this->input->post("class_id"),
                'section_id'    => $this->input->post("section_id"),
                'subject_id'    => $this->input->post("subject_id"),
                'homework_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('homework_date'))),
                'submit_date'   => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('submit_date'))),
                'staff_id'      => $userdata["id"],
                'subject_id'    => $this->input->post("subject_id"),
                'description'   => $this->input->post("description"),
                'create_date'   => date("Y-m-d"),
                'document'      => $document,
            );

            $this->homework_model->add($data);
            $msg   = $this->lang->line('update_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }

        echo json_encode($array);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('homework', 'can_delete')) {
            access_denied();
        }
        if (!empty($id)) {
            $row = $this->homework_model->get($id);
            if ($row['document'] != '') {
                $this->media_storage->filedelete($row['document'], "uploads/homework/");
            }

            $this->homework_model->delete($id);
            redirect("homework");
        }
    }

    public function download($id)
    {
        $homework = $this->homework_model->get($id);
        $this->media_storage->filedownload($homework['document'], "./uploads/homework");
    }

    public function evaluation($id)
    {
        if (!$this->rbac->hasPrivilege('homework_evaluation', 'can_view')) {
            access_denied();
        }

        $getStaffRole       = $this->customlib->getStaffRole();
        $staffrole          = json_decode($getStaffRole);
        $superadmin_visible = $this->customlib->superadmin_visible();

        $data["title"]        = "Homework Evaluation";
        $data["evaluated_by"] = "";
        $result               = $this->homework_model->getRecord($id);

        $class_id      = $result["class_id"];
        $section_id    = $result["section_id"];
        $data['marks'] = $result["marks"];

        $data["studentlist"] = $this->homework_model->getStudents($id);
        $data["result"]      = $result;
        $data['sch_setting'] = $this->setting_model->getSetting();

        if (!empty($result)) {

            $evaluated_by_staff_id = '';
            $created_by_staff_id   = '';
            $evaluated_by = '';

            if ($result["evaluated_by"]) {
                $eval_data = $this->staff_model->get($result["evaluated_by"]);

                if ($eval_data["employee_id"] != '') {
                    $evaluated_by_staff_id = ' (' . $eval_data["employee_id"] . ')';
                }

                $evaluated_by = $eval_data["name"] . " " . $eval_data["surname"] . $evaluated_by_staff_id;
            }

            $created_by   = '';


            if ($superadmin_visible == 'disabled') {

                if ($result["created_employee_id"] != '') {
                    $created_by_staff_id = ' (' . $result["created_employee_id"] . ')';
                }

                if ($staffrole->id == 7) {

                    $created_by = $result["created_staff_name"] . " " . $result["created_staff_surname"] . $created_by_staff_id;
                    $evaluated_by = $evaluated_by;
                    
                } else {

                    if ($result["created_staff_roleid"] != 7) {
                        $created_by = $result["created_staff_name"] . " " . $result["created_staff_surname"] . $created_by_staff_id;
                    }                   
                    $evaluated_by = $evaluated_by;
                    
                }
            } else {
                $created_by_staff_id = ' (' . $result["created_employee_id"] . ')';
                $created_by   = $result["created_staff_name"] . " " . $result["created_staff_surname"] . $created_by_staff_id;
                $evaluated_by = $evaluated_by;
            }

            $data["created_by"]   = $created_by;
            $data["evaluated_by"] = $evaluated_by;
        }

        $this->load->view("homework/evaluation_modal", $data);
    }

    public function add_evaluation()
    {
        if (!$this->rbac->hasPrivilege('homework_evaluation', 'can_add')) {
            access_denied();
        }

        $userdata = $this->customlib->getUserData();
        $this->form_validation->set_rules('evaluation_date', $this->lang->line('evaluation_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('student_list[]', $this->lang->line('student_name'), 'trim|required|xss_clean');

        $students       = $this->input->post("student_list");
        $marks          = $this->input->post("marks");

        $homeworkresult = $this->homework_model->getRecord($this->input->post("homework_id"));

        if (!empty($students)) {
            foreach ($students as $std_key => $std_value) {

                $marks1 = $marks[$std_key];

                if ($homeworkresult['marks'] < $marks1) {
                    $this->form_validation->set_rules('marks', $this->lang->line('marks'), array('valid_marks', array('check_valid_marks', array($this->homework_model, 'check_valid_marks'))));
                }
            }
        }

        if ($this->form_validation->run() == false) {
            $msg = array(
                'evaluation_date' => form_error('evaluation_date'),
                'student_list[]'  => form_error('student_list[]'),
                'marks'           => form_error('marks'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $insert_prev  = array();
            $insert_array = array();
            $update_array = array();
            $homework_id  = $this->input->post("homework_id");
            $students     = $this->input->post("student_list");
            $marks        = $this->input->post("marks");
            $note         = $this->input->post("note");
            $student_id   = $this->input->post("student_id");

            foreach ($students as $std_key => $std_value) {

                $newmarks = NULL;
                if ($marks[$std_key]) {
                    $newmarks = $marks[$std_key];
                }

                if ($std_value == 0) {
                    $insert_array[] = array(

                        'student_session_id' => $std_key,
                        'note'               => $note[$std_key],
                        'marks'              => $newmarks,
                        'student_id'         => $student_id[$std_key],
                        'status'             => 'completed',
                    );
                } else {
                    $insert_prev[] = $std_value;

                    $update_array[$std_value][] = array(
                        'note'               => $note[$std_key],
                        'marks'              => $newmarks,
                        'student_session_id' => $std_key,
                    );
                }
            }

            $evaluation_date = $this->customlib->dateFormatToYYYYMMDD($this->input->post('evaluation_date'));
            $evaluated_by    = $this->customlib->getStaffID();
            $this->homework_model->addEvaluation($insert_prev, $insert_array, $homework_id, $evaluation_date, $evaluated_by, $update_array);
            $msg   = $this->lang->line('evaluation_completed_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }
        echo json_encode($array);
    }

    public function evaluation_report()
    {
        if (!$this->rbac->hasPrivilege('homehork_evaluation_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'homework/homeworkreport');
        $this->session->set_userdata('subsub_menu', 'homework/evaluation_report');

        $class                    = $this->class_model->get();
        $data['classlist']        = $class;
        $userdata                 = $this->customlib->getUserData();
        $carray                   = array();
        $data['class_id']         = $class_id         = "";
        $data['section_id']       = $section_id       = "";
        $data['subject_id']       = $subject_id       = "";
        $data['subject_group_id'] = $subject_group_id = "";

        $class_id                 = $this->input->post("class_id");
        $section_id               = $this->input->post("section_id");
        $subject_group_id         = $this->input->post("subject_group_id");
        $subject_id               = $this->input->post("subject_id");
        $data['class_id']         = $class_id;
        $data['section_id']       = $section_id;
        $data['subject_group_id'] = $subject_group_id;
        $data['subject_id']       = $subject_id;
        
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_group_id', $this->lang->line('subject_group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data['resultlist'] = array();
            $data["report"]     = array();
        } else {
            $data['resultlist'] = $this->homework_model->search_homework($class_id, $section_id, $subject_group_id, $subject_id);

            foreach ($data['resultlist'] as $key => $value) {
                $report                       = $this->count_percentage($value["id"], $value["class_id"], $value["section_id"]);
                $data["report"][$value['id']] = $report;
            }
        }

        $this->load->view("layout/header");
        $this->load->view("homework/homework_evaluation", $data);
        $this->load->view("layout/footer");
    }

    public function getreport($id = 1)
    {
        $result = $this->homework_model->getEvaluationReport($id);
        if (!empty($result)) {
            $data["result"]       = $result;
            $class_id             = $result[0]["class_id"];
            $section_id           = $result[0]["section_id"];
            $create_data          = $this->staff_model->get($result[0]["created_by"]);
            $eval_data            = $this->staff_model->get($result[0]["evaluated_by"]);
            $created_by           = $create_data["name"] . " " . $create_data["surname"];
            $evaluated_by         = $eval_data["name"] . " " . $eval_data["surname"];
            $data["created_by"]   = $created_by;
            $data["evaluated_by"] = $evaluated_by;
            $studentlist          = $this->homework_model->getStudents($class_id, $section_id);
            $data["studentlist"]  = $studentlist;
            $this->load->view("homework/evaluation_report", $data);
        } else {
            echo "<div class='row'><div class='col-md-12'><br/><div class='alert alert-info'>" . $this->lang->line('no_record_found') . "</div></div></div>";
        }
    }

    public function count_percentage($id, $class_id, $section_id)
    {
        $data               = array();
        $count_students     = $this->homework_model->count_students($class_id, $section_id);
        $count_evalstudents = $this->homework_model->count_evalstudents($id, $class_id, $section_id);
        if ($count_students > 0) {
            $total_students     = $count_students;
            $total_evalstudents = $count_evalstudents['total'];
            $count_percentage   = ($total_evalstudents / $total_students) * 100;
            $data["total"]      = $total_students;
            $data["completed"]  = $total_evalstudents;
            $data["percentage"] = round($count_percentage, 2);
        }

        return $data;
    }

    public function getClass()
    {
        $class = $this->class_model->get();
        echo json_encode($class);
    }

    public function assigmnetDownload($id)
    {
        $assigmnetdata['id'] = $id;
        $assigmnetlist       = $this->homework_model->get_upload_docs($assigmnetdata);
        $this->media_storage->filedownload($assigmnetlist[0]['docs'], "./uploads/homework/assignment");
    }

    public function deletehomework()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('delete_homework[]', $this->lang->line('homework'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'delete_homework[]' => form_error('delete_homework[]'),
            );
            $array = array('status' => 0, 'error' => $msg, 'message' => '');
        } else {
            $delete_homework_list = $this->input->post('delete_homework');

            foreach ($delete_homework_list as $_key => $homework_list_value) {
                $this->homework_model->delete($homework_list_value);
            }

            $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('delete_message'));
        }
        echo json_encode($array);
    }

    public function dailyassignment()
    {
        if (!$this->rbac->hasPrivilege('daily_assignment', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Homework');
        $this->session->set_userdata('sub_menu', 'dailyassignment');
        $class             = $this->class_model->get();
        $data['classlist'] = $class;
        $data['class_id']  = "";
        $this->load->view("layout/header");
        $this->load->view("homework/dailyassignmentlist", $data);
        $this->load->view("layout/footer");
    }

    public function searchdailyassignment()
    {
        $class_id                 = $this->input->post('class_id');
        $section_id               = $this->input->post('section_id');
        $subject_group_id         = $this->input->post('subject_group_id');
        $subject_group_subject_id = $this->input->post('subject_id');
        $date                     = $this->input->post('date');
        $download                 = "";

        if ($date != '') {
            $date = date('Y-m-d', $this->customlib->datetostrtotime($date));
        }

        $superadmin_rest = $this->session->userdata['admin']['superadmin_restriction'];
        $getStaffRole    = $this->customlib->getStaffRole();
        $staffrole       = json_decode($getStaffRole);
        $userdata        = $this->customlib->getUserData();
        $login_staff_id  = $userdata["id"];

        $dailyassignment = $this->homework_model->searchdailyassignment($class_id, $section_id, $subject_group_id, $subject_group_subject_id, $date);
        $dailyassignment = json_decode($dailyassignment);

        $dt_data = array();
        if (!empty($dailyassignment->data)) {

            foreach ($dailyassignment->data as $key => $value) {

                if ($value->attachment != "") {
                    $download = "<a  href='" . base_url() . "homework/dailyassigmnetdownload/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip'  title='" . $this->lang->line('download') . "'><i class='fa fa-download'></i></a>";
                }

                $assignment = '<a onclick="assignmentdetails(' . $value->id . ')" class="btn btn-default btn-xs" data-target="#assignmentdetails" data-backdrop="static" data-keyboard="false" data-toggle="modal"  title="' . $this->lang->line('view') . '"><i class="fa fa-reorder"></i></a>';

                $evaluated_by = '';
                if ($value->evaluated_by != 0) {
                    if ($staffrole->id == 7) {

                        $evaluated_by = $value->name . ' ' . $value->surname . ' (' . $value->employee_id . ')';
                    } elseif ($superadmin_rest == 'enabled') {

                        $evaluated_by = $value->name . ' ' . $value->surname . ' (' . $value->employee_id . ')';
                    } elseif ($value->evaluated_by == $login_staff_id) {

                        $evaluated_by = $value->name . ' ' . $value->surname . ' (' . $value->employee_id . ')';
                    }
                }

                if ($value->evaluation_date) {
                    $evaluation_date = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->evaluation_date));
                } else {
                    $evaluation_date = "";
                }

                $code = '';
                if ($value->subject_code) {
                    $code = '(' . $value->subject_code . ')';
                }

                $row   = array();
                $row[] = $value->firstname . ' ' . $value->middlename . ' ' . $value->lastname . ' (' . $value->student_admission_no . ')';
                $row[] = $value->class;
                $row[] = $value->section;
                $row[] = $value->subject_name . ' ' . $code;
                $row[] = $value->title;
                $row[] = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->date));
                $row[] = $evaluation_date;
                $row[] = $evaluated_by;
                $row[] = $download . ' ' . '<a onclick="assignmentevaluation(' . $value->id . ')" class="btn btn-default btn-xs" data-target="#assignmentevaluation" data-backdrop="static" data-keyboard="false" data-toggle="modal"  title="' . $this->lang->line('evaluate') . '"><i class="fa fa-newspaper-o"></i></a>' . ' ' . $assignment;

                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw"            => intval($dailyassignment->draw),
            "recordsTotal"    => intval($dailyassignment->recordsTotal),
            "recordsFiltered" => intval($dailyassignment->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function assignmentvalidation()
    {
        $class_id         = $this->input->post('class_id');
        $section_id       = $this->input->post('section_id');
        $subject_group_id = $this->input->post('subject_group_id');
        $subject_id       = $this->input->post('subject_id');
        $date             = $this->input->post('date');

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_group_id', $this->lang->line('subject_group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $error = array();

            $error['class_id']         = form_error('class_id');
            $error['section_id']       = form_error('section_id');
            $error['subject_group_id'] = form_error('subject_group_id');
            $error['subject_id']       = form_error('subject_id');
            $error['date']             = form_error('date');

            $array = array('status' => 0, 'error' => $error);
            echo json_encode($array);
        } else {
            $class_id   = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');

            $params = array('class_id' => $class_id, 'section_id' => $section_id, 'subject_group_id' => $subject_group_id, 'subject_id' => $subject_id, 'date' => $date);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    public function getdailyassignmentdetails()
    {
        $id             = $this->input->post('id');
        $assignmentlist = $this->homework_model->getsingledailyassignment($id);
        if ($assignmentlist['evaluation_date'] != "") {
            $assignmentlist['evaluation_date'] = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($assignmentlist['evaluation_date']));
        } else {
            $assignmentlist['evaluation_date'] = "";
        }

        echo json_encode($assignmentlist);
    }

    public function dailyassigmnetdownload($id)
    {
        $dailyassigmnetlist = $this->homework_model->getsingledailyassignment($id);
        $this->media_storage->filedownload($dailyassigmnetlist['attachment'], "./uploads/homework/daily_assignment");
    }

    public function submitassignmentremark()
    {
        $this->form_validation->set_rules('evaluation_date', $this->lang->line('evaluation_date'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'evaluation_date' => form_error('evaluation_date'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $insert_data = array(
                'id'              => $this->input->post('assigment_id'),
                'evaluation_date' => $this->customlib->dateFormatToYYYYMMDD($this->input->post('evaluation_date')),
                'remark'          => $this->input->post('remark'),
                'evaluated_by'    => $this->customlib->getStaffID(),
            );

            $this->homework_model->adddailyassignment($insert_data);
            $msg   = $this->lang->line('evaluation_completed_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }
        echo json_encode($array);
    }

    public function assignmentdetails()
    {
        $assigment_id           = $this->input->post('assigment_id');
        $data['assignmentlist'] = $this->homework_model->assignmentrecord($assigment_id);
        $data['sch_setting']    = $this->setting_model->getSetting();
        $page                   = $this->load->view("homework/_assignmentdetails", $data, true);
        echo json_encode(array('page' => $page));
    }

    public function homeworkreport()
    {
        if (!$this->rbac->hasPrivilege('homework', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'homework/homeworkreport');
        $this->session->set_userdata('subsub_menu', 'homework/homeworkreport');

        $data['classlist'] = $this->class_model->get();

        $userdata                 = $this->customlib->getUserData();
        $carray                   = array();
        $data['class_id']         = $class_id   =   $this->input->post('class_id');
        $data['section_id']       = $section_id =   $this->input->post('section_id');
        $data['subject_group_id'] = $subject_group_id   =   $this->input->post('subject_group_id');
        $data['subject_id']       = $subject_id =   $this->input->post('subject_id');

        if (isset($_POST["search"])) {
            $homeworklist = $this->homework_model->search_dthomeworkreport($class_id, $section_id, $subject_group_id, $subject_id);
            $data["resultlist"] = $homeworklist;
        }

        $this->load->view("layout/header", $data);
        $this->load->view("homework/homeworkreport", $data);
        $this->load->view("layout/footer", $data);
    }

    public function getStudentByClassSection()
    {
        $data               = array();
        $class_id           = $this->input->post('class_id');
        $section_id         = $this->input->post('section_id');
        $homework_id        = $this->input->post('homework_id');
        $type           = $this->input->post('type');

        $class_sections = $this->classsection_model->getDetailbyClassSection($class_id, $section_id);

        if ($type == 'student_count') {
            $student_list         = $this->student_model->getStudentBy_class_section_id($class_sections['id']);
        } elseif ($type == 'homework_submitted') {
            $student_list         = $this->homework_model->get_submitted_homework($homework_id);
        } elseif ($type == 'pending_student') {
            $student_list         = $this->homework_model->get_not_submitted_homework($class_id, $section_id, $homework_id);
        }

        $data['student_list'] = $student_list;
        $data['sch_setting']  = $this->sch_setting_detail;
        $page                 = $this->load->view('homework/_getStudentByClassSection', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function homeworkordailyassignmentreport()
    {
        $this->session->set_userdata('top_menu', 'report');
        $this->session->set_userdata('sub_menu', 'Homework/homeworkordailyassignmentreport');
        $this->session->set_userdata('subsub_menu', '');
        $this->load->view('layout/header');
        $this->load->view('homework/homeworkordailyassignmentreport');
        $this->load->view('layout/footer');
    }

    public function dailyassignmentreport()
    {
        if (!$this->rbac->hasPrivilege('daily_assignment', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'homework/homeworkreport');
        $this->session->set_userdata('subsub_menu', 'homework/dailyassignmentreport');

        $data['searchlist'] = $this->search_type;
        $class              = $this->class_model->get();
        $data['classlist']  = $class;
        $this->load->view("layout/header");
        $this->load->view("homework/dailyassignmentreport", $data);
        $this->load->view("layout/footer");
    }

    public function searchdailyassignmentreport()
    {
        $class_id                 = $this->input->post('class_id');
        $section_id               = $this->input->post('section_id');
        $subject_group_id         = $this->input->post('subject_group_id');
        $subject_group_subject_id = $this->input->post('subject_id');

        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

            $between_date        = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $search_type = $_POST['search_type'];
        } else {

            $between_date        = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = $search_type = '';
        }

        $from_date = date('Y-m-d', strtotime($between_date['from_date']));
        $to_date   = date('Y-m-d', strtotime($between_date['to_date']));
        $condition = " date_format(daily_assignment.date,'%Y-%m-%d') between  '" . $from_date . "' and '" . $to_date . "'";

        $superadmin_rest = $this->session->userdata['admin']['superadmin_restriction'];
        $getStaffRole    = $this->customlib->getStaffRole();
        $staffrole       = json_decode($getStaffRole);
        $userdata        = $this->customlib->getUserData();
        $login_staff_id  = $userdata["id"];
        $dailyassignment = $this->homework_model->dailyassignmentreport($class_id, $section_id, $subject_group_id, $subject_group_subject_id, $condition);
        $dailyassignment = json_decode($dailyassignment);

        $dt_data = array();
        if (!empty($dailyassignment->data)) {

            foreach ($dailyassignment->data as $key => $value) {

                $assignment = '<a onclick="dailyassignmentdetails(' . $value->student_id . ')" class="btn btn-default btn-xs" data-target="#dailyassignmentdetails" data-backdrop="static" data-keyboard="false" data-toggle="modal"  title="' . $this->lang->line('view') . '"><i class="fa fa-reorder"></i></a>';

                $evaluated_by = '';
                if ($value->evaluated_by != 0) {
                    if ($staffrole->id == 7) {

                        $evaluated_by = $value->name . ' ' . $value->surname . ' (' . $value->employee_id . ')';
                    } elseif ($superadmin_rest == 'enabled') {

                        $evaluated_by = $value->name . ' ' . $value->surname . ' (' . $value->employee_id . ')';
                    } elseif ($value->evaluated_by == $login_staff_id) {

                        $evaluated_by = $value->name . ' ' . $value->surname . ' (' . $value->employee_id . ')';
                    }
                }

                if ($value->evaluation_date) {
                    $evaluation_date = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->evaluation_date));
                } else {
                    $evaluation_date = "";
                }

                $row   = array();
                $row[] = $value->firstname . ' ' . $value->middlename . ' ' . $value->lastname . ' (' . $value->student_admission_no . ')';
                $row[] = $value->class;
                $row[] = $value->section;
                $row[] = $value->total_student;
                $row[] = $assignment;
                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw"            => intval($dailyassignment->draw),
            "recordsTotal"    => intval($dailyassignment->recordsTotal),
            "recordsFiltered" => intval($dailyassignment->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function dailyassignmentdetails()
    {
        $student_id  = $this->input->post('student_id');
        $search_type = $this->input->post('search_type');
        $subject_id  = $this->input->post('subject_id');

        $data['superadmin_rest'] = $this->session->userdata['admin']['superadmin_restriction'];
        $getStaffRole            = $this->customlib->getStaffRole();
        $data['staffrole']       = json_decode($getStaffRole);
        $userdata                = $this->customlib->getUserData();
        $data['login_staff_id']  = $userdata["id"];

        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

            $between_date        = $this->customlib->get_betweendate($_POST['search_type']);
            $from_date = date('Y-m-d', strtotime($between_date['from_date']));
            $to_date   = date('Y-m-d', strtotime($between_date['to_date']));
        } else {

            $between_date        = $this->customlib->get_betweendate('this_year');
            $from_date = date('Y-m-d', strtotime($between_date['from_date']));
            $to_date   = date('Y-m-d', strtotime($between_date['to_date']));
        }

        $condition = " date_format(daily_assignment.date,'%Y-%m-%d') between  '" . $from_date . "' and '" . $to_date . "'";

        $data['assignmentlist'] = $this->homework_model->assignmentdetails($student_id, $condition, $subject_id);

        $data['sch_setting'] = $this->setting_model->getSetting();
        $page                = $this->load->view("reports/_dailyassignmentdetails", $data, true);
        echo json_encode(array('page' => $page));
    }

    public function dailyassignmentreportvalidation()
    {
        $this->form_validation->set_rules('search_type', $this->lang->line('search_type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_group_id', $this->lang->line('subject_group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');

        $class_id         = $this->input->post('class_id');
        $section_id       = $this->input->post('section_id');
        $subject_group_id = $this->input->post('subject_group_id');
        $subject_id       = $this->input->post('subject_id');

        if ($this->form_validation->run() == false) {
            $error = array();

            $error['search_type'] = form_error('search_type');

            $error['class_id']         = form_error('class_id');
            $error['section_id']       = form_error('section_id');
            $error['subject_group_id'] = form_error('subject_group_id');
            $error['subject_id']       = form_error('subject_id');

            $array = array('status' => 0, 'error' => $error);
            echo json_encode($array);
        } else {
            $search_type = $this->input->post('search_type');
            $date_from   = "";
            $date_to     = "";
            if ($search_type == 'period') {
                $date_from = $this->input->post('date_from');
                $date_to   = $this->input->post('date_to');
            }

            $class_id   = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');

            $params = array('search_type' => $search_type, 'date_from' => $date_from, 'date_to' => $date_to, 'class_id' => $class_id, 'section_id' => $section_id, 'subject_group_id' => $subject_group_id, 'subject_id' => $subject_id);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }
}
