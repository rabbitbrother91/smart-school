<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Alumni extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->library('media_storage');
        $this->config->load('app-config');
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->library('encoding_lib');
    }

    public function alumnilist()
    {
        if (!$this->rbac->hasPrivilege('manage_alumni', 'can_view')) {
            access_denied();
        }
        $data                = array();
        $data['sessionlist'] = $this->session_model->get();
        $this->session->set_userdata('top_menu', 'alumni');
        $this->session->set_userdata('sub_menu', 'alumni/alumnilist');
        $class             = $this->class_model->get();
        $data['classlist'] = $class;

        $data['title']  = $this->lang->line('alumni_student');
        $carray         = array();
        $alumni_studets = array();
        $alumni_student = $this->alumni_model->get();

        foreach ($alumni_student as $key => $value) {
            $alumni_studets[$value['student_id']] = $value;
        }
        $data['alumni_studets'] = $alumni_studets;

        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {
                $carray[] = $cvalue["id"];
            }
        }

        $search = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/alumni/alumnilist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class              = $this->input->post('class_id');
            $section            = $this->input->post('section_id');
            $search             = $this->input->post('search');
            $search_text        = $this->input->post('search_text');
            $data['session_id'] = $session_id = $this->input->post('session_id');
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');
                    $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
                    if ($this->form_validation->run() == false) {

                    } else {
                        $data['searchby']    = "filter";
                        $data['class_id']    = $this->input->post('class_id');
                        $data['section_id']  = $this->input->post('section_id');
                        $data['search_text'] = $this->input->post('search_text');
                        $resultlist          = $this->student_model->search_alumniStudent($class, $section, $session_id);
                        $data['resultlist']  = $resultlist;
                    }
                } else if ($search == 'search_full') {
                    $data['searchby'] = "text";

                    $data['search_text'] = trim($this->input->post('search_text'));
                    $resultlist          = $this->student_model->search_alumniStudentbyAdmissionNo($search_text, $carray);
                    $data['resultlist']  = $resultlist;
                }
            }

            $data['sch_setting'] = $this->sch_setting_detail;
            $this->load->view('layout/header');
            $this->load->view('admin/alumni/alumnilist', $data);
            $this->load->view('layout/footer');
        }
    }

    public function get_alumnidetails()
    {
        $student_id = $_POST['student_id'];
        $data       = $this->alumni_model->get_alumnidetail($student_id);

        if (empty($data)) {

            $data = array(
                'id'            => '',
                'current_email' => '',
                'current_phone' => '',
                'occupation'    => '',
                'address'       => '',
                'student_id'    => '');
        }

        echo json_encode($data);
    }

    public function add()
    {
        $this->form_validation->set_rules('current_phone', $this->lang->line('current_phone'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload[documents]');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'current_phone' => form_error('current_phone'),
                'file'          => form_error('file'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $img_name = $this->media_storage->fileupload("documents", "./uploads/alumni_student_images/");

            $data = array(
                'current_email' => $this->input->post('current_email'),
                'current_phone' => $this->input->post('current_phone'),
                'occupation'    => $this->input->post('occupation'),
                'address'       => $this->input->post('address'),
                'student_id'    => $this->input->post('student_id'),
                'id'            => $this->input->post('id'),
            );

            if ($this->input->post('id') == '') {

                $data['photo'] = $img_name;
                $this->alumni_model->add($data);
            } else {

                $alumni_data = $this->alumni_model->get_alumnidetail($this->input->post('student_id'));

                if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name']))) {

                    $update_img_name = $img_name;

                } else {

                    $update_img_name = $alumni_data['photo'];
                }

                $data['photo'] = $update_img_name;

                $this->alumni_model->add($data);
            }

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }

        echo json_encode($array);
    }

    public function events()
    {
        if (!$this->rbac->hasPrivilege('events', 'can_view')) {
            access_denied();
        }
        $data['title']       = 'Event List';
        $data['sessionlist'] = $this->session_model->get();
        $eventlist           = $this->alumni_model->getevents();

        foreach ($eventlist as $key => $class) {
            $eventclass[$key]   = '';
            $eventsection[$key] = '';
            $eventsession[$key] = '';
            if (!empty($class['class_id'])) {
                $eventclasslist     = $this->class_model->getAll($class['class_id']);
                $eventclass[$key]   = $eventclasslist['class'];
                $eventsection[$key] = $this->class_model->get_section($class['class_id']);
                $sessionlist        = $this->session_model->get($class['session_id']);
                $eventsession[$key] = $sessionlist['session'];
            }
        }

        $data['eventlist'] = $eventlist;
        if (!empty($eventclass)) {
            $data['eventclass'] = $eventclass;
        }
        if (!empty($eventsection)) {
            $data['eventsection'] = $eventsection;
        }
        if (!empty($eventsession)) {
            $data['eventsession'] = $eventsession;
        }

        $data['classlist'] = $this->class_model->get();        
        $language      = $this->customlib->getLanguage();        
        $data['language_name'] = $language["short_code"];
        $this->session->set_userdata('top_menu', 'alumni');
        $this->session->set_userdata('sub_menu', 'alumni/event');
        $this->load->view("layout/header.php");
        $this->load->view("admin/alumni/events", $data);
        $this->load->view("layout/footer.php");
    }

    public function add_event()
    {
        $this->form_validation->set_rules('event_title', $this->lang->line('event_title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('from_date', $this->lang->line("event_from_date"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('to_date', $this->lang->line("event_to_date"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload[file]');

        $studentclass = $this->input->post('event_for');
        if ($studentclass == 'class') {
            $this->form_validation->set_rules('session_id', $this->lang->line("pass_out_session"), 'trim|required|xss_clean');
            $this->form_validation->set_rules('class_id', $this->lang->line("class"), 'trim|required|xss_clean');
            $this->form_validation->set_rules('user[]', $this->lang->line("section"), 'trim|required|xss_clean');
        }

        if ($this->form_validation->run() == false) {
            $msg = array(
                'event_title' => form_error('event_title'),
                'from_date'   => form_error('from_date'),
                'to_date'     => form_error('to_date'),
                'file'        => form_error('file'),
            );
            if ($studentclass == 'class') {
                $msg1 = array(
                    'class_id'   => form_error('class_id'),
                    'user'       => form_error('user[]'),
                    'session_id' => form_error('session_id'),
                );
            }
            if (!empty($msg1)) {
                $error_msg = array_merge($msg, $msg1);
            } else {
                $error_msg = $msg;
            }

            $array = array('status' => 'fail', 'error' => $error_msg, 'message' => '');
        } else {
             

        $event_starting_date = date('Y-m-d H:i:s',$this->customlib->dateTimeformatTwentyfourhour($this->input->post('from_date')." 00:00:00",true));
        $event_end_date = date('Y-m-d H:i:s',$this->customlib->dateTimeformatTwentyfourhour($this->input->post('to_date')." 23:59:00",true));
       
            $event_for  =   $this->input->post('event_for');
            
            if($event_for != 'all'){
                $session_id =   $this->input->post('session_id');
                $class_id =   $this->input->post('class_id');                
            }else{
                $session_id =   NULL;
                $class_id =   NULL;                
            }          
            
            $data = array(                
                'id'                      => $this->input->post('id'),
                'title'                      => $this->input->post('event_title'),
                'event_for'                  => $this->input->post('event_for'),
                'session_id'                 => $session_id,
                'class_id'                   => $class_id,
                'section'                    => json_encode($this->input->post('user')),
                'from_date'                  => $event_starting_date,
                'to_date'                    => $event_end_date,
                'note'                       => $this->input->post('note'),
                'event_notification_message' => $this->input->post('event_notification_message'),
            );

            $img_name="";
            $file_name="";

            if ($this->input->post('id') == '') {
                if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {
                    $img_name = $this->media_storage->fileupload("file", "./uploads/alumni_event_images/");
                    $file_name = $_FILES['file']['name'];
                }
              
            } else {
                $event_list = $this->alumni_model->get_eventbyid($this->input->post('id'));

                if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {
                  
                    $img_name = $this->media_storage->fileupload("file", "./uploads/alumni_event_images/");
                    $file_name = $_FILES['file']['name'];
                } else {
                    $img_name = $event_list['photo'];
                    $file_name = $_FILES['file']['name'];
                    
                }
                if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {
                    if ($event_list['photo'] != '') {
                        $this->media_storage->filedelete($event_list['photo'], "uploads/alumni_event_images");                 
                        
                    }
                }
                
            }

            $data['photo'] = $img_name;
            $data['is_active'] = 0;

            $insert_id = $this->alumni_model->add_event($data);
          
            $email     = $this->input->post('email');
            $sms       = $this->input->post('sms');
            $subject   = $this->input->post('event_title');
            $body      = $this->input->post('event_notification_message');

            $email_value = 'no';
            $sms_value   = 'no';

            if ($email != '') {
                $email_value = 'yes';
            }
            if ($sms != '') {
                $sms_value = 'yes';
            }
            $send_new_attachments=[];
            $studentclass = $this->input->post('event_for');
            
            $template_id = $this->input->post('template_id');
            
            if($file_name != "" ){
                $send_new_attachments[] = array('directory' => 'uploads/alumni_event_images/', 'attachment' => $img_name, 'attachment_name' => $file_name);           
            }         
          
            
            if ($studentclass == 'class') {
                $usersection = $this->input->post('user');
                foreach ($usersection as $section) {
                    $alumniStudent = $this->alumni_model->alumniMail($this->input->post('class_id'), $this->input->post('session_id'), $section);
                    
                    foreach ($alumniStudent as $alumniStudent_value) {               
                          $sender_details = array('student_id' => $insert_id, 'contact_no' => $alumniStudent_value['current_phone'], 'email' => $alumniStudent_value['current_email'], 'email_value' => $email_value, 'sms_value' => $sms_value, 'subject' => $subject, 'body' => $body, 'from_date' => $this->input->post('from_date'), 'to_date' => $this->input->post('to_date'));

                        $this->mailsmsconf->mailsmsalumnistudent($sender_details,$template_id,$send_new_attachments);
                    }
                }
            } else {
                $alumniStudent = $this->alumni_model->get();
                foreach ($alumniStudent as $alumniStudent_value) {
        
                    $sender_details = array('student_id' => $insert_id, 'contact_no' => $alumniStudent_value['current_phone'], 'email' => $alumniStudent_value['current_email'], 'email_value' => $email_value, 'sms_value' => $sms_value, 'subject' => $subject, 'body' => $body, 'from_date' => $this->input->post('from_date'), 'to_date' => $this->input->post('to_date'));

                    $this->mailsmsconf->mailsmsalumnistudent($sender_details,$template_id,$send_new_attachments);
                }
            }

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }

        echo json_encode($array);
    }

    public function get_event($id)
    {
        $data              = $this->alumni_model->get_eventbyid($id);
        
        $data['from_date'] = $this->customlib->dateformat($data['from_date']);
        $data['to_date'] = $this->customlib->dateformat($data['to_date']);      
        
        if ($data['photo']) {
            $data['photo'] =   $this->media_storage->getImageURL('./uploads/alumni_event_images/' . $data['photo']);
        } else {
            $data['photo'] =   $this->media_storage->getImageURL('./uploads/no_image.png');
        }

        echo json_encode($data);
    }

    public function delete_event($id)
    {
        $row = $this->alumni_model->get_eventbyid($id);
        if ($row['photo'] != '') {
            $this->media_storage->filedelete($row['photo'], "uploads/alumni_event_images/");
        }
        $this->alumni_model->delete_event($id);
    }

    public function getevent()
    {
        $start      = $this->input->get('start');
        $end        = $this->input->get('end');
        $event_data = $this->alumni_model->get_eventbydaterange($start, $end);
        foreach ($event_data as $key => $value) {

            $eventdata[] = array('title' => $value['title'],
                'start'                      => $value['from_date'],
                'end'                        => $value['to_date'],
                'description'                => $value['note'],
                'id'                         => $value['id'],
                'backgroundColor'            => '#27ab00',
                'borderColor'                => '#27ab00',
                'event_type'                 => 'Present',
            );
        }

        echo json_encode($eventdata);
    }

    public function deletestudent($id)
    {
        $this->alumni_model->deletestudent($id);
    }

    public function handle_upload($str, $var)
    {
        $image_validate = $this->config->item('image_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES[$var]) && !empty($_FILES[$var]['name'])) {
            $file_type         = $_FILES[$var]['type'];
            $file_size         = $_FILES[$var]["size"];
            $file_name         = $_FILES[$var]["name"];
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

}
