<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Syllabus extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->sch_current_session = $this->setting_model->getCurrentSession();
        $this->staff_id            = $this->customlib->getStaffID();
        $this->sch_setting_detail  = $this->setting_model->getSetting();
        $this->start_weekday       = strtolower($this->sch_setting_detail->start_week);
    }

    public function index()
    {
        if (!($this->rbac->hasPrivilege('manage_lesson_plan', 'can_view'))) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'lessonplan');
        $this->session->set_userdata('sub_menu', 'admin/syllabus');
        $my_role                 = $this->customlib->getStaffRole();
        $role                    = json_decode($my_role);
        $data['role_id']         = $role->id;
        $staff_list              = $this->staff_model->getEmployee('2');
        $data['staff_list']      = $staff_list;
        $monday                  = strtotime("last " . $this->start_weekday);
        $monday                  = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
        $sunday                  = strtotime(date("Y-m-d", $monday) . " +6 days");
        $this_week_start         = date("Y-m-d", $monday);
        $this_week_end           = date("Y-m-d", $sunday);
        $data['this_week_start'] = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($this_week_start));
        $data['this_week_end']   = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($this_week_end));
        $data['staff_id']        = $this->staff_id;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/syllabus/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function get_weekdates()
    {
        $this_week_start         = $this->customlib->dateFormatToYYYYMMDD($_POST['date']);
        $prev_week_start         = date("Y-m-d", strtotime('last ' . $this->start_weekday, strtotime($this_week_start)));
        $next_week_start         = date("Y-m-d", strtotime('next ' . $this->start_weekday, strtotime($this_week_start)));
        $this_week_end           = date("Y-m-d", strtotime($this_week_start . " +6 day"));
        $data['this_week_start'] = $this->customlib->dateformat($this_week_start);
        $data['this_week_end']   = $this->customlib->dateformat($this_week_end);
        $data['prev_week_start'] = $this->customlib->dateformat($prev_week_start);
        $data['next_week_start'] = $this->customlib->dateformat($next_week_start);
        $this->session->set_userdata('top_menu', 'Time_table');
        $staff_id            = $_POST['staff_id'];
        $data['timetable']   = array();
        $days                = $this->customlib->getDaysname();
        $userdata            = $this->customlib->getUserData();
        $role_id             = $userdata["role_id"];
        $class_section_array = array();
        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            $my_class = $this->class_model->get();
            foreach ($my_class as $class_key => $class_value) {
                $section = $this->section_model->getClassBySection($class_value['id']);
                foreach ($section as $key => $value) {
                    $class_section_array[$class_value['id']][] = $value['section_id'];
                }
            }
        }

        foreach ($days as $day_key => $day_value) {
            $data['timetable'][$day_key] = $this->subjecttimetable_model->getSyllabussubject($staff_id, $day_key, $class_section_array);

        }

        $data['staff_id'] = $staff_id;
        $this->load->view('admin/syllabus/_get_weekdates', $data);
    }

    public function get_subject_syllabus()
    {
        $data['id']      = $_POST['id'];
        $staff_id        = $_POST['staff_id'];
        $my_role         = $this->customlib->getStaffRole();
        $role            = json_decode($my_role);
        $data['role_id'] = $role->id;
        $data['result'] = $this->syllabus_model->get_subject_syllabus($data, $staff_id);
        $data['result'] = $data['result'][0];
        $this->load->view('admin/syllabus/_get_subject_syllabus', $data);
    }

    public function delete_subject_syllabus()
    {
        $syllabus_details = $this->syllabus_model->get_subject_syllabusdatabyid($_POST['id']);

        if ($syllabus_details['attachment'] != '') {
            $this->media_storage->filedelete($syllabus_details['attachment'], "uploads/syllabus_attachment/");
        }

        if ($syllabus_details['lacture_video'] != '') {
            $this->media_storage->filedelete($syllabus_details['lacture_video'], "uploads/syllabus_attachment/lacture_video/");
        }

        $this->syllabus_model->delete_subject_syllabus($_POST['id']);

    }

    public function get_subject_syllabusdata()
    {
        $staff_id = $this->customlib->getStaffID();
        $my_role  = $this->customlib->getStaffRole();
        $role     = json_decode($my_role);

        $subject_syllabus = $this->syllabus_model->get_subject_syllabusdata($_POST['subject_group_subject_id'], date('Y-m-d', strtotime($_POST['new_date'])), $role->id, $staff_id);

        if ($subject_syllabus[0]['total'] > 0) {
            echo $subject_syllabus[0]['id'];
        } else {
            echo 0;
        }
    }

    public function getsubject_syllabus($id)
    {
        $data         = $this->syllabus_model->get_subject_syllabusdatabyid($id);
        $data['date'] = date($this->customlib->getSchoolDateFormat(), strtotime($data['date']));
        echo json_encode($data);

    }

    public function add_syllabus()
    {
        $this->form_validation->set_rules('lesson_id', $this->lang->line('lesson'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('topic_id', $this->lang->line('topic'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('time_from', $this->lang->line('time_from'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('time_to', $this->lang->line('time_to'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('attachment'), 'callback_handle_upload');
        $this->form_validation->set_rules('lacture_video', $this->lang->line('lacture_video'), 'callback_handle_uploadlacturevideo');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'lessonid'      => form_error('lesson_id'),
                'topic_id'      => form_error('topic_id'),
                'date'          => form_error('date'),
                'time_from'     => form_error('time_from'),
                'time_to'       => form_error('time_to'),
                'file'          => form_error('file'),
                'lacture_video' => form_error('lacture_video'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $data = array(
                'session_id'              => $this->sch_current_session,
                'topic_id'                => $_POST['topic_id'],
                'date'                    => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'time_from'               => $_POST['time_from'],
                'time_to'                 => $_POST['time_to'],
                'presentation'            => $_POST['presentation'],
                'sub_topic'               => $_POST['sub_topic'],
                'teaching_method'         => $_POST['teaching_method'],
                'general_objectives'      => $_POST['general_objectives'],
                'previous_knowledge'      => $_POST['previous_knowledge'],
                'comprehensive_questions' => $_POST['comprehensive_questions'],
                'lacture_youtube_url'     => $_POST['lacture_youtube_url'],
                'created_by'              => $this->staff_id,
                'created_for'             => $_POST['created_for'],
                'id'                      => $_POST['subject_syllabusid'],
            );

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $attachment_img_name = $this->media_storage->fileupload("file", "./uploads/syllabus_attachment/");
                $data['attachment']  = $attachment_img_name;
            }

            if (isset($_FILES["lacture_video"]) && !empty($_FILES['lacture_video']['name'])) {
                $lacture_video_img_name = $this->media_storage->fileupload("lacture_video", "./uploads/syllabus_attachment/lacture_video/");
                $data['lacture_video']  = $lacture_video_img_name;
            }

            $insert_id = $this->lessonplan_model->add_syllabus($data);       

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function download($id)
    {
      
     $result=$this->lessonplan_model->getSyllabusById($id); 
     $attachment_img_name = $this->media_storage->filedownload($result->attachment,"./uploads/syllabus_attachment/");

    }

    public function lacture_video_download($id)
    {
      
     $result=$this->lessonplan_model->getSyllabusById($id); 
     $attachment_img_name = $this->media_storage->filedownload($result->lacture_video,"./uploads/syllabus_attachment/lacture_video/");

    }

    public function handle_uploadlacturevideo()
    {
        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();

        if (isset($_FILES["lacture_video"]) && !empty($_FILES['lacture_video']['name'])) {

            $file_type = $_FILES["lacture_video"]['type'];
            $file_size = $_FILES["lacture_video"]["size"];
            $file_name = $_FILES["lacture_video"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['lacture_video']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_uploadlacturevideo', $this->lang->line('file_type_not_allowed'));
                return false;
            }
            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_uploadlacturevideo', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_uploadlacturevideo', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . " MB");
                return false;
            }

            return true;
        } else {

        }
        return true;
    }

    public function handle_upload()
    {
        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
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

            return true;
        } else {

        }
        return true;
    }

    public function status()
    {
        if (!($this->rbac->hasPrivilege('manage_syllabus_status', 'can_view'))) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'lessonplan');
        $this->session->set_userdata('sub_menu', 'admin/lessonplan');
        $data                     = array();
        $data['no_record']        = '0';
        $class                    = $this->class_model->get();
        $data['classlist']        = $class;
        $data['class_id']         = "";
        $data['section_id']       = "";
        $data['subject_group_id'] = "";
        $data['subject_id']       = "";
        $data['subject_name']     = "";
        $data['lessons']          = array();
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_group_id', $this->lang->line('subject_group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

        } else {
            $data['class_id']               = $_POST['class_id'];
            $data['section_id']             = $_POST['section_id'];
            $data['subject_group_id']       = $_POST['subject_group_id'];
            $data['subject_id']             = $_POST['subject_id'];
            $subject_details                = $this->lessonplan_model->get_subjectNameBySubjectGroupSubjectId($_POST['subject_id']);
            $subject_group_class_sectionsId = $this->lessonplan_model->getsubject_group_class_sectionsId($_POST['class_id'], $_POST['section_id'], $_POST['subject_group_id']);
            if ($subject_details['code'] == '') {
                $data['subject_name'] = $subject_details['name'];
            } else {
                $data['subject_name'] = $subject_details['name'] . " (" . $subject_details['code'] . ")";
            }
            $lessonlist        = $this->lessonplan_model->getlessonBysubjectid($_POST['subject_id'], $subject_group_class_sectionsId['id']);
            $data['no_record'] = '1';
            foreach ($lessonlist as $key => $value) {
                $data['no_record']             = '2';
                $data['lessons'][$value['id']] = $value;
                $topics                        = $this->lessonplan_model->gettopicBylessonid($value['id'], $this->sch_current_session);
                foreach ($topics as $topic_key => $topic_value) {
                    $data['lessons'][$value['id']]['topic'][] = $topic_value;
                }
            }
        }

        $data['status'] = array('1' => '<span class="label " style="background:#0e0e0e">' . $this->lang->line('completed') . '</span>', '0' => '<span class="label " style="background:#b3b3b3">' . $this->lang->line('incomplete') . '</span>');
        $this->load->view('layout/header');
        $this->load->view('admin/lessonplan/index', $data);
        $this->load->view('layout/footer');
    }

    public function addmessage()
    {
        $userdata = $this->customlib->getUserData();
        $this->form_validation->set_rules('message', $this->lang->line('forum'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'message' => form_error('message'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');

        } else {

            $data = array(
                'subject_syllabus_id' => $this->input->post("subject_syllabus_id"),
                'type'                => 'staff',
                'staff_id'            => $userdata["id"],
                'message'             => $this->input->post("message"),
                'created_date'        => date('Y-m-d H:i:s'),
            );

            $this->syllabus_model->addmessage($data);
            $msg   = $this->lang->line('success_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }

        echo json_encode($array);
    }

    public function getmessage()
    {
        if (!($this->rbac->hasPrivilege('lesson_plan_comments', 'can_view'))) {
            access_denied();
        }
        $subject_syllabus_id = $this->input->post("subject_syllabus_id");
        $data['messagelist'] = $this->syllabus_model->getmessage($subject_syllabus_id);
        $data['sch_setting'] = $this->sch_setting_detail;
        $userdata = $this->customlib->getUserData();
        $data['login_staff_id'] = $userdata["id"];
        $page                = $this->load->view('admin/syllabus/_get_message', $data, true);
        $array               = array('status' => 'success', 'error' => '', 'page' => $page, 'message' => $this->lang->line('success_message'));
        echo json_encode($array);
    }
    
    public function deletemessage()
    {   
        $fourm_id = $_POST['fourm_id'];  
        $this->syllabus_model->deletemessage($fourm_id);
       
    }

}
