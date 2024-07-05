<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mailsms extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->model("classteacher_model");
        $this->config->load("payroll");
        $this->send_through = $this->config->item('send_through');
        $this->mailer;
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('email_sms_log', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Communicate');
        $this->session->set_userdata('sub_menu', 'mailsms/index');
        $data['title']       = 'Add Mailsms';
        $listMessage         = $this->messages_model->get();
        $data['listMessage'] = $listMessage;
        $this->load->view('layout/header');
        $this->load->view('admin/mailsms/index', $data);
        $this->load->view('layout/footer');
    }

    public function schedule()
    {
        if (!$this->rbac->hasPrivilege('schedule_email_sms_log', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Communicate');
        $this->session->set_userdata('sub_menu', 'mailsms/schedule');
        $data['title']       = 'Add Mailsms';
        $listMessage         = $this->messages_model->schedule();
        $data['listMessage'] = $listMessage;
        $this->load->view('layout/header');
        $this->load->view('admin/mailsms/schedule', $data);
        $this->load->view('layout/footer');
    }

    public function search()
    {
        $keyword     = $this->input->post('keyword');
        $category    = $this->input->post('category');
        $result      = array();
        $sch_setting = $this->setting_model->getSetting();
        if ($keyword != "" and $category != "") {
            if ($category == "student") {
                $result = $this->student_model->searchNameLike($keyword);

                foreach ($result as $key => $value) {
                    $result[$key]['fullname'] = $this->customlib->getFullName($value['firstname'], $value['middlename'], $value['lastname'], $sch_setting->middlename, $sch_setting->lastname);
                }
            } elseif ($category == "student_guardian") {
                $result = $this->student_model->searchNameLike($keyword);
                foreach ($result as $key => $value) {
                    $result[$key]['fullname'] = $this->customlib->getFullName($value['firstname'], $value['middlename'], $value['lastname'], $sch_setting->middlename, $sch_setting->lastname) . ' (' . $value['admission_no'] . ') (' . $value['guardian_name'] . ')';
                }
            } elseif ($category == "parent") {

                $result = $this->student_model->searchGuardianNameLike($keyword);
            } elseif ($category == "staff") {
                $result = $this->staff_model->searchNameLike($keyword);
            } else {

            }
        }

        echo json_encode($result);
    }

    public function compose()
    {
        if (!$this->rbac->hasPrivilege('email', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Communicate');
        $this->session->set_userdata('sub_menu', 'Communicate/mailsms/compose');
        $data['title']               = 'Add Mailsms';
        $class                       = $this->class_model->get();
        $data['classlist']           = $class;
        $userdata                    = $this->customlib->getUserData();
        $data['email_template_list'] = $this->messages_model->get_email_template();

        $carray = array();

        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }
        $date          = date('Y-m-d');
        $birthDaysList = array();
        $birthStudents = $this->student_model->getBirthDayStudents($date, true);
        $birthStaff    = $this->staff_model->getBirthDayStaff($date, 1, true);

        if (!empty($birthStudents)) {
            $array = array();
            foreach ($birthStudents as $student_key => $student_value) {

                $array[] = array('name' => $this->customlib->getFullName($student_value['firstname'], $student_value['middlename'], $student_value['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname), 'email' => $student_value['email'], 'admission_no' => $student_value['admission_no']);
            }
            $birthDaysList['students'] = $array;
        }
        if (!empty($birthStaff)) {
            $array = array();
            foreach ($birthStaff as $staff_key => $staff_value) {

                $array[] = array('name' => $staff_value['name'], 'email' => $staff_value['email'], 'employee_id' => $staff_value['employee_id']);
            }
            $birthDaysList['staff'] = $array;
        }

        $data['roles']                  = $this->role_model->get();
        $data['birthDaysList']          = $birthDaysList;
        $data['sch_setting']            = $this->sch_setting_detail;
        $data['superadmin_restriction'] = $this->customlib->superadmin_visible();
        $this->load->view('layout/header');
        $this->load->view('admin/mailsms/compose', $data);
        $this->load->view('layout/footer');
    }

    public function compose_sms()
    {
        if (!$this->rbac->hasPrivilege('sms', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Communicate');
        $this->session->set_userdata('sub_menu', 'mailsms/compose_sms');
        $data['title']             = 'Add Mailsms';
        $class                     = $this->class_model->get();
        $data['classlist']         = $class;
        $data['sms_template_list'] = $this->messages_model->get_sms_template();
        $userdata                  = $this->customlib->getUserData();
        $carray                    = array();
        $date                      = date('Y-m-d');
        $birthDaysList             = array();
        $birthStudents             = $this->student_model->getBirthDayStudents($date, false, false);
        $birthStaff                = $this->staff_model->getBirthDayStaff($date, 1, false, false);

        if (!empty($birthStudents)) {
            $array = array();
            foreach ($birthStudents as $student_key => $student_value) {

                $array[] = array('name' => $this->customlib->getFullName($student_value['firstname'], $student_value['middlename'], $student_value['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname),
                    'contact_no'    => $student_value['mobileno'],
                    'app_key'       => $student_value['app_key'],                    
                    'admission_no'  => $student_value['admission_no']
                );
            }
            $birthDaysList['students'] = $array;
        }
        if (!empty($birthStaff)) {
            $array = array();
            foreach ($birthStaff as $staff_key => $staff_value) {
                $array[] = array('name' => $staff_value['name'], 'contact_no' => $staff_value['contact_no'], 'employee_id' => $staff_value['employee_id']);
            }
            $birthDaysList['staff'] = $array;
        }

        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {
                $carray[] = $cvalue["id"];
            }
        }

        $data['roles']                  = $this->role_model->get();
        $data['birthDaysList']          = $birthDaysList;
        $data['sch_setting']            = $this->sch_setting_detail;
        $data['send_through_list']      = $this->send_through;
        $data['superadmin_restriction'] = $this->customlib->superadmin_visible();
        $this->load->view('layout/header');
        $this->load->view('admin/mailsms/compose_sms', $data);
        $this->load->view('layout/footer');
    }

    public function edit($id)
    {
        $data['title']       = 'Add Vehicle';
        $data['id']          = $id;
        $editvehicle         = $this->vehicle_model->get($id);
        $data['editvehicle'] = $editvehicle;
        $listVehicle         = $this->vehicle_model->get();
        $data['listVehicle'] = $listVehicle;
        $this->form_validation->set_rules('vehicle_no', $this->lang->line('vehicle_number'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

            $this->load->view('layout/header');
            $this->load->view('admin/mailsms/edit', $data);
            $this->load->view('layout/footer');
        } else {
            $manufacture_year = $this->input->post('manufacture_year');
            $data             = array(
                'id'             => $this->input->post('id'),
                'vehicle_no'     => $this->input->post('vehicle_no'),
                'vehicle_model'  => $this->input->post('vehicle_model'),
                'driver_name'    => $this->input->post('driver_name'),
                'driver_licence' => $this->input->post('driver_licence'),
                'driver_contact' => $this->input->post('driver_contact'),
                'note'           => $this->input->post('note'),
            );
            ($manufacture_year != "") ? $data['manufacture_year'] = $manufacture_year : '';
            $this->vehicle_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/mailsms/index');
        }
    }

    public function delete($id)
    {
        $data['title'] = 'Fees Master List';
        $this->vehicle_model->remove($id);
        redirect('admin/mailsms/index');
    }

    public function send_individual()
    {
        $send_type = $this->input->post('individual_send_type');
        $this->form_validation->set_error_delimiters('');
        $this->form_validation->set_rules('individual_title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('individual_message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('user_list', $this->lang->line('recipient'), 'required');
        $this->form_validation->set_rules('individual_send_by', $this->lang->line('send_through'), 'required');
        $this->form_validation->set_rules('files', $this->lang->line('file'), 'callback_multihandle_upload[files]');
        if ($send_type == 'individual_schedule') {
            $this->form_validation->set_rules('schedule_date_time', $this->lang->line('schedule_date_time'), 'required');
        }
        if ($this->form_validation->run()) {

            $userlisting = json_decode($this->input->post('user_list'));
            $user_array  = array();
            foreach ($userlisting as $userlisting_key => $userlisting_value) {
                $array = array(
                    'category'      => $userlisting_value[0]->category,
                    'user_id'       => $userlisting_value[0]->record_id,
                    'email'         => $userlisting_value[0]->email,
                    'guardianEmail' => $userlisting_value[0]->guardianEmail,
                    'mobileno'      => $userlisting_value[0]->mobileno,
                    'role'=>$userlisting_value[0]->category,
                );
                $user_array[] = $array;
            }

            $sms_mail = $this->input->post('individual_send_by');
            if ($sms_mail == "sms") {
                $send_sms  = 1;
                $send_mail = 0;
            } else {
                $send_sms  = 0;
                $send_mail = 1;
            }
            $message       = $this->input->post('individual_message');
            $message_title = $this->input->post('individual_title');
            $data          = array(
                'is_individual'     => 1,
                'title'             => $message_title,
                'message'           => $message,
                'send_mail'         => $send_mail,
                'send_sms'          => $send_sms,
                'email_template_id' => $this->input->post('template_id'),
                'user_list'         => json_encode($user_array),
                'created_at'        => date('Y-m-d H:i:s'),
            );

            $send_attachments     = array();
            $send_new_attachments = array();
            $send_old_attachments = array();
            $myattachment_data    = array();
            $attachments          = array();
            if (isset($_FILES['files']['name']) && !empty($_FILES['files']['name'])) {
                foreach ($_FILES['files']['name'] as $key => $files_value) {
                    $file_type = $_FILES['files']['type'][$key];
                    $file_size = $_FILES['files']["size"][$key];
                    $file_name = $_FILES['files']['name'][$key];
                    $fileInfo  = pathinfo($_FILES["files"]["name"][$key]);
                    $img_name  = time() . rand(99, 999) . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["files"]["tmp_name"][$key], "./uploads/communicate/email_attachments/" . $img_name);
                    $send_new_attachments[] = array('directory' => 'uploads/communicate/email_attachments/', 'attachment' => $img_name, 'attachment_name' => $file_name);
                    $myattachment_data[]    = array('attachment' => $img_name, 'attachment_name' => $file_name, 'directory' => 'uploads/communicate/email_attachments/');
                }
            }

            if (!empty($_POST['template_attachment'])) {
                foreach ($_POST['template_attachment'] as $key => $value) {
                    $email_template_attachment_data = $this->messages_model->get_single_email_template_attachment($key);

                    $send_old_attachments[] = array('directory' => 'uploads/communicate/email_template_images/', 'attachment' => $email_template_attachment_data['attachment'], 'attachment_name' => $email_template_attachment_data['attachment_name']);

                    $attachments[] = array('attachment' => $email_template_attachment_data['attachment'], 'attachment_name' => $email_template_attachment_data['attachment_name'], 'directory' => 'uploads/communicate/email_template_images/');
                }
            }

            $send_attachments = array_merge($send_new_attachments, $send_old_attachments);
            $attachment_data  = array_merge($myattachment_data, $attachments);

            if ($send_type == 'send_now') {
                if (!empty($user_array)) {
                    if ($send_mail) {
                        if (!empty($this->mail_config)) {
                            foreach ($user_array as $user_mail_key => $user_mail_value) {

                                if ($user_mail_value['email'] != "") {

                                    $this->mailer->compose_mail($user_mail_value['email'], $message_title, $message, $send_attachments, $user_mail_value['guardianEmail']);
                                }
                            }
                        }
                    }
                }
            } else {
                $data['is_schedule']        = 1;
                $data['sent']               = 0;
                $data['schedule_date_time'] = date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($this->input->post('schedule_date_time'), false));
            }

            if (!empty($user_array)) {
                if ($send_sms) {
                    foreach ($user_array as $user_mail_key => $user_mail_value) {
                        if ($user_mail_value['mobileno'] != "") {
                            $this->smsgateway->sendSMS($user_mail_value['mobileno'], "", ($message));
                        }
                    }
                }
            }

            $data['user_list'] = json_encode($user_array);
            $last_inserted_id  = $this->messages_model->add($data);

            if (!empty($attachment_data)) {
                foreach ($attachment_data as $attachment_key => $attachment_data_value) {

                    $email_attachments_data['message_id']      = $last_inserted_id;
                    $email_attachments_data['attachment']      = $attachment_data_value['attachment'];
                    $email_attachments_data['attachment_name'] = $attachment_data_value['attachment_name'];
                    $email_attachments_data['directory']       = $attachment_data_value['directory'];

                    $this->messages_model->add_email_attachment($email_attachments_data);
                }
            }

            if ($send_type == 'send_now') {
                echo json_encode(array('status' => 0, 'msg' => $this->lang->line('message_sent_successfully')));
            } else {
                echo json_encode(array('status' => 0, 'msg' => $this->lang->line('schedule_message_successfully')));
            }
        } else {

            $data = array(
                'individual_title'   => form_error('individual_title'),
                'individual_message' => form_error('individual_message'),
                'individual_send_by' => form_error('individual_send_by'),
                'user_list'          => form_error('user_list'),
                'files'              => form_error('files'),
            );
            if ($send_type == 'schedule') {
                $data['schedule_date_time'] = form_error('schedule_date_time');
            }
            echo json_encode(array('status' => 1, 'msg' => $data));
        }
    }

    public function send_birthday()
    {
        $this->form_validation->set_error_delimiters('');
        $this->form_validation->set_rules('user[]', $this->lang->line('recipient'), 'required');
        $this->form_validation->set_rules('birthday_title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('birthday_message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('birthday_send_by', $this->lang->line('send_through'), 'required');
        $this->form_validation->set_rules('files', $this->lang->line('file'), 'callback_multihandle_upload[files]');

        if ($this->form_validation->run()) {
            $user_array = array();

            $sms_mail = $this->input->post('birthday_send_by');
            if ($sms_mail == "sms") {
                $send_sms  = 1;
                $send_mail = 0;
            } else {
                $send_sms  = 0;
                $send_mail = 1;
            }
            $message       = $this->input->post('birthday_message');
            $message_title = $this->input->post('birthday_title');
            $data          = array(
                'is_group'    => 1,
                'title'       => $message_title,
                'message'     => $message,
                'send_mail'   => $send_mail,
                'send_sms'    => $send_sms,
                'template_id' => $this->input->post('template_id'),
                'group_list'  => json_encode(array()),
            );

            $userlisting = $this->input->post('user[]');

            $send_attachments     = array();
            $send_new_attachments = array();
            $send_old_attachments = array();
            $myattachment_data    = array();
            $attachments          = array();
            if (isset($_FILES['files']['name']) && !empty($_FILES['files']['name'])) {
                foreach ($_FILES['files']['name'] as $key => $files_value) {
                    $file_type = $_FILES['files']['type'][$key];
                    $file_size = $_FILES['files']["size"][$key];
                    $file_name = $_FILES['files']['name'][$key];
                    $fileInfo  = pathinfo($_FILES["files"]["name"][$key]);
                    $img_name  = time() . rand(99, 999) . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["files"]["tmp_name"][$key], "./uploads/communicate/email_attachments/" . $img_name);
                    $send_new_attachments[] = array('directory' => 'uploads/communicate/email_attachments/', 'attachment' => $img_name, 'attachment_name' => $file_name);
                    $myattachment_data[]    = array('attachment' => $img_name, 'attachment_name' => $file_name, 'directory' => 'uploads/communicate/email_attachments/');
                }
            }

            if (!empty($_POST['template_attachment'])) {
                foreach ($_POST['template_attachment'] as $key => $value) {
                    $email_template_attachment_data = $this->messages_model->get_single_email_template_attachment($key);

                    $send_old_attachments[] = array('directory' => 'uploads/communicate/email_template_images/', 'attachment' => $email_template_attachment_data['attachment'], 'attachment_name' => $email_template_attachment_data['attachment_name']);

                    $attachments[] = array('attachment' => $email_template_attachment_data['attachment'], 'attachment_name' => $email_template_attachment_data['attachment_name'], 'directory' => 'uploads/communicate/email_template_images/');
                }
            }

            $send_attachments = array_merge($send_new_attachments, $send_old_attachments);
            $attachment_data  = array_merge($myattachment_data, $attachments);

            foreach ($userlisting as $users_key => $users_value) {
                $array = array(
                    'email'    => $users_value,
                    'mobileno' => $users_value,

                );
                $user_array[] = $array;
            }

            if (!empty($user_array)) {
                if ($send_mail) {
                    if (!empty($this->mail_config)) {
                        foreach ($user_array as $user_mail_key => $user_mail_value) {
                            if ($user_mail_value['email'] != "") {
                                $this->mailer->compose_mail($user_mail_value['email'], $message_title, $message, $send_attachments);
                            }
                        }
                    }
                }
            }

            if ($send_sms) {
                foreach ($user_array as $user_mail_key => $user_mail_value) {
                    if ($user_mail_value['mobileno'] != "") {
                        $this->smsgateway->sendSMS($user_mail_value['mobileno'], "", ($message));
                    }
                }
            }

            $data['user_list'] = json_encode($user_array);
            $last_inserted_id  = $this->messages_model->add($data);

            if (!empty($attachment_data)) {
                foreach ($attachment_data as $attachment_key => $attachment_data_value) {

                    $email_attachments_data['message_id']      = $last_inserted_id;
                    $email_attachments_data['attachment']      = $attachment_data_value['attachment'];
                    $email_attachments_data['attachment_name'] = $attachment_data_value['attachment_name'];
                    $email_attachments_data['directory']       = $attachment_data_value['directory'];

                    $this->messages_model->add_email_attachment($email_attachments_data);
                }
            }

            echo json_encode(array('status' => 0, 'msg' => $this->lang->line('message_sent_successfully')));
        } else {

            $data = array(
                'birthday_title'   => form_error('birthday_title'),
                'birthday_message' => form_error('birthday_message'),
                'birthday_send_by' => form_error('birthday_send_by'),
                'user[]'           => form_error('user[]'),
                'files'            => form_error('files'),
            );

            echo json_encode(array('status' => 1, 'msg' => $data));
        }
    }

    public function send_group()
    {
        $send_type = $this->input->post('send_type');
        $this->form_validation->set_error_delimiters('');
        $this->form_validation->set_rules('group_title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('group_message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('user[]', $this->lang->line('message_to'), 'required');
        $this->form_validation->set_rules('group_send_by', $this->lang->line('send_through'), 'required');
        $this->form_validation->set_rules('files', $this->lang->line('file'), 'callback_multihandle_upload[files]');
        if ($send_type == 'schedule') {
            $this->form_validation->set_rules('schedule_date_time', $this->lang->line('schedule_date_time'), 'required');
        }
        if ($this->form_validation->run()) {

            $user_array = array();
            $sms_mail = $this->input->post('group_send_by');

            if ($sms_mail == "sms") {
                $send_sms  = 1;
                $send_mail = 0;
            } else {
                $send_sms  = 0;
                $send_mail = 1;
            }

            $send_attachments     = array();
            $send_new_attachments = array();
            $send_old_attachments = array();
            $attachments          = array();
            $myattachment_data    = array();
            $message              = $this->input->post('group_message');
            $message_title        = $this->input->post('group_title');

            $data = array(
                'is_group'          => 1,
                'title'             => $message_title,
                'message'           => $message,
                'send_mail'         => $send_mail,
                'send_sms'          => $send_sms,
                'email_template_id' => $this->input->post('template_id'),
                'group_list'        => json_encode($this->input->post('user[]')),
                'created_at'        => date('Y-m-d H:i:s'),
            );

            if (isset($_FILES['files']['name']) && !empty($_FILES['files']['name'])) {
                foreach ($_FILES['files']['name'] as $key => $files_value) {
                    $file_type = $_FILES['files']['type'][$key];
                    $file_size = $_FILES['files']["size"][$key];
                    $file_name = $_FILES['files']['name'][$key];
                    $fileInfo  = pathinfo($_FILES["files"]["name"][$key]);
                    $img_name  = time() . rand(99, 999) . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["files"]["tmp_name"][$key], "./uploads/communicate/email_attachments/" . $img_name);
                    $send_new_attachments[] = array('directory' => 'uploads/communicate/email_attachments/', 'attachment' => $img_name, 'attachment_name' => $file_name);
                    $myattachment_data[]    = array('attachment' => $img_name, 'attachment_name' => $file_name, 'directory' => 'uploads/communicate/email_attachments/');
                }
            }

            if (!empty($_POST['template_attachment'])) {
                foreach ($_POST['template_attachment'] as $key => $value) {
                    $email_template_attachment_data = $this->messages_model->get_single_email_template_attachment($key);

                    $send_old_attachments[] = array('directory' => 'uploads/communicate/email_template_images/', 'attachment' => $email_template_attachment_data['attachment'], 'attachment_name' => $email_template_attachment_data['attachment_name']);

                    $attachments[] = array('attachment' => $email_template_attachment_data['attachment'], 'attachment_name' => $email_template_attachment_data['attachment_name'], 'directory' => 'uploads/communicate/email_template_images/');
                }
            }

            $send_attachments = array_merge($send_new_attachments, $send_old_attachments);
            $attachment_data  = array_merge($myattachment_data, $attachments);

            $userlisting = $this->input->post('user[]');

            foreach ($userlisting as $users_key => $users_value) {

                if ($users_value == "student" || $users_value == "parent") {
                    $userdata_array = $this->student_model->getStudents();
                }

                if ($users_value == "student") {

                    if (!empty($userdata_array)) {
                        foreach ($userdata_array as $student_key => $student_value) {

                            $array = array(
                                'user_id'  => $student_value['id'],
                                'email'    => $student_value['email'],
                                'mobileno' => $student_value['mobileno'],
                               'role' => 'student'
                            );
                            $user_array[] = $array;
                        }
                    }
                } else if ($users_value == "parent") {

                    if (!empty($userdata_array)) {
                        foreach ($userdata_array as $parent_key => $parent_value) {
                            $array = array(
                                'user_id'  => $parent_value['id'],
                                'email'    => $parent_value['guardian_email'],
                                'mobileno' => $parent_value['guardian_phone'],
                                'role' => 'parent'
                            );
                            $user_array[] = $array;
                        }
                    }
                } else if (is_numeric($users_value)) {

                    $staff = $this->staff_model->getEmployeeByRoleID($users_value);
                    if (!empty($staff)) {
                        foreach ($staff as $staff_key => $staff_value) {
                            $array = array(
                                'user_id'  => $staff_value['id'],
                                'email'    => $staff_value['email'],
                                'mobileno' => $staff_value['contact_no'],
                                'role' => 'staff'
                            );
                            $user_array[] = $array;
                        }
                    }
                }
            }
            if ($send_type == 'send_now') {

                if (!empty($user_array)) {
                    if ($send_mail) {
                        if (!empty($this->mail_config)) {
                            foreach ($user_array as $user_mail_key => $user_mail_value) {
                                if ($user_mail_value['email'] != "") {
                                    $this->mailer->compose_mail($user_mail_value['email'], $message_title, $message, $send_attachments);
                                }
                            }
                        }
                    }
                }
            } else {
                $data['is_schedule']        = 1;
                $data['sent']               = 0;
                $data['schedule_date_time'] = date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($this->input->post('schedule_date_time'), false));
            }

            if (!empty($user_array)) {
                if ($send_sms) {
                    foreach ($user_array as $user_mail_key => $user_mail_value) {
                        if ($user_mail_value['mobileno'] != "") {
                            $this->smsgateway->sendSMS($user_mail_value['mobileno'], "", ($message));
                        }
                    }
                }
            }
            $data['user_list'] = json_encode($user_array);
            $last_inserted_id  = $this->messages_model->add($data);

            if (!empty($attachment_data)) {
                foreach ($attachment_data as $attachment_key => $attachment_data_value) {

                    $email_attachments_data['message_id']      = $last_inserted_id;
                    $email_attachments_data['attachment']      = $attachment_data_value['attachment'];
                    $email_attachments_data['attachment_name'] = $attachment_data_value['attachment_name'];
                    $email_attachments_data['directory']       = $attachment_data_value['directory'];

                    $this->messages_model->add_email_attachment($email_attachments_data);
                }
            }

            if ($send_type == 'send_now') {
                echo json_encode(array('status' => 0, 'msg' => $this->lang->line('message_sent_successfully')));
            } else {
                echo json_encode(array('status' => 0, 'msg' => $this->lang->line('schedule_message_successfully')));
            }

        } else {

            $data = array(
                'group_title'   => form_error('group_title'),
                'group_message' => form_error('group_message'),
                'group_send_by' => form_error('group_send_by'),
                'user[]'        => form_error('user[]'),
                'files'         => form_error('files'),
            );

            if ($send_type == 'schedule') {
                $data['schedule_date_time'] = form_error('schedule_date_time');
            }

            echo json_encode(array('status' => 1, 'msg' => $data));
        }
    }

    public function send_group_sms()
    {
        $send_type = $this->input->post('send_type');
        $this->form_validation->set_error_delimiters('');
        $this->form_validation->set_rules('group_title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('group_message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('user[]', $this->lang->line('message_to'), 'required');
        $this->form_validation->set_rules('group_send_by[]', $this->lang->line('send_through'), 'required');
        if ($send_type == 'schedule') {
            $this->form_validation->set_rules('schedule_date_time', $this->lang->line('schedule_date_time'), 'required');
        }
        $template_id = $this->input->post('group_template_id');

        if ($this->form_validation->run()) {
            $user_array    = array();
            $sms_mail      = $this->input->post('group_send_by');
            $message       = $this->input->post('group_message');
            $message_title = $this->input->post('group_title');
            $data          = array(
                'is_group'        => 1,
                'title'           => $message_title,
                'message'         => $message,
                'send_mail'       => 0,
                'send_sms'        => 1,
                'send_through'    => json_encode($sms_mail),
                'sms_template_id' => $this->input->post('template_id'),
                'group_list'      => json_encode($this->input->post('user[]')),
                'created_at'      => date('Y-m-d H:i:s'),
                'template_id'     => $template_id,
            );

            $userlisting = $this->input->post('user[]');
            foreach ($userlisting as $users_key => $users_value) {
                if ($users_value == "student") {
                    $student_array = $this->student_model->get();

                    if (!empty($student_array)) {
                        foreach ($student_array as $student_key => $student_value) {

                            $array = array(
                                'user_id'  => $student_value['id'],
                                'email'    => $student_value['email'],
                                'mobileno' => $student_value['mobileno'],
                                'app_key'  => $student_value['app_key'],
                                'role'=>'student'
                            );
                            $user_array[] = $array;
                        }
                    }
                } else if ($users_value == "parent") {
                    $parent_array = $this->student_model->get();
                    if (!empty($parent_array)) {
                        foreach ($parent_array as $parent_key => $parent_value) {
                            $array = array(
                                'user_id'  => $parent_value['id'],
                                'email'    => $parent_value['guardian_email'],
                                'mobileno' => $parent_value['guardian_phone'],
                                'app_key'  => $parent_value['parent_app_key'],
                                'role'=>'parent'
                            );
                            $user_array[] = $array;
                        }
                    }
                } else if (is_numeric($users_value)) {

                    $staff = $this->staff_model->getEmployeeByRoleID($users_value);
                    if (!empty($staff)) {
                        foreach ($staff as $staff_key => $staff_value) {
                            $array = array(
                                'user_id'  => $staff_value['id'],
                                'email'    => $staff_value['email'],
                                'mobileno' => $staff_value['contact_no'],
                                'app_key'  => '',
                                'role'=>'staff'
                            );
                            $user_array[] = $array;
                        }
                    }
                }
            }
            if ($send_type == 'send_now') {
                if (!empty($user_array)) {

                    foreach ($user_array as $user_mail_key => $user_mail_value) {
                        if (in_array("sms", $sms_mail)) {
                            if ($user_mail_value['mobileno'] != "") {
                                $this->smsgateway->sendSMS($user_mail_value['mobileno'], $message, $template_id, "");
                            }
                        }
                        if (in_array("push", $sms_mail)) {
                            $push_array = array(
                                'title' => $message_title,
                                'body'  => $message,
                            );
                            if ($user_mail_value['app_key'] != "") {
                                $this->pushnotification->send($user_mail_value['app_key'], $push_array, "mail_sms");
                            }
                        }
                    }
                }
            } else {
                $data['is_schedule']        = 1;
                $data['sent']               = 0;
                $data['schedule_date_time'] = date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($this->input->post('schedule_date_time'), false));
            }

            $data['user_list'] = json_encode($user_array);
            $this->messages_model->add($data);
            if ($send_type == 'send_now') {
                echo json_encode(array('status' => 0, 'msg' => $this->lang->line('message_sent_successfully')));
            } else {
                echo json_encode(array('status' => 0, 'msg' => $this->lang->line('schedule_message_successfully')));
            }
        } else {

            $data = array(
                'group_title'     => form_error('group_title'),
                'group_send_by[]' => form_error('group_send_by[]'),
                'group_message'   => form_error('group_message'),
                'user[]'          => form_error('user[]'),
            );
            if ($send_type == 'schedule') {
                $data['schedule_date_time'] = form_error('schedule_date_time');
            }

            echo json_encode(array('status' => 1, 'msg' => $data));
        }
    }

    public function send_birthday_sms()
    {
        $this->form_validation->set_error_delimiters('');
        $this->form_validation->set_rules('user[]', $this->lang->line('recipient'), 'required');
        $this->form_validation->set_rules('birthday_title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('birthday_message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('birthday_send_by[]', $this->lang->line('send_through'), 'required');
        $template_id = $this->input->post('birthday_template_id');

        if ($this->form_validation->run()) {
            $user_array      = array();
            $user_push_array = array();

            $sms_mail      = $this->input->post('birthday_send_by');
            $message       = $this->input->post('birthday_message');
            $message_title = $this->input->post('birthday_title');
            $data          = array(
                'is_group'   => 1,
                'title'      => $message_title,
                'message'    => $message,
                'send_mail'  => 0,
                'send_sms'   => 1,
                'group_list' => json_encode(array()),
            );

            $userlisting = $this->input->post('user[]');

            $userpushlisting = $this->input->post('app-key');

            foreach ($userlisting as $users_key => $users_value) {
                $array = array(

                    'mobileno' => $users_value,
                );
                $user_array[] = $array;
            }
            foreach ($userpushlisting as $user_push_key => $user_push_value) {
                $array = array(
                    'app-key' => $user_push_value,
                );
                $user_push_array[] = $array;
            }

            if (!empty($user_array)) {
                foreach ($user_array as $user_mail_key => $user_mail_value) {
                    if (in_array("sms", $sms_mail)) {
                        if ($user_mail_value['mobileno'] != "" && $user_mail_value['mobileno'] != 0) {
                            $this->smsgateway->sendSMS($user_mail_value['mobileno'], ($message), $template_id, "");
                        }
                    }
                }
            }

            if (!empty($user_push_array)) {
                foreach ($user_push_array as $user_push_sms_key => $user_push_sms_value) {
                    if (in_array("push", $sms_mail)) {
                        $push_array = array(
                            'title' => $message_title,
                            'body'  => $message,
                        );
                        if ($user_push_sms_value['app-key'] != "") {
                            $this->pushnotification->send($user_push_sms_value['app-key'], $push_array, "mail_sms");
                        }
                    }
                }
            }

            echo json_encode(array('status' => 0, 'msg' => $this->lang->line('message_sent_successfully')));
        } else {

            $data = array(
                'birthday_title'     => form_error('birthday_title'),
                'birthday_send_by[]' => form_error('birthday_send_by[]'),
                'birthday_message'   => form_error('birthday_message'),
                'user[]'             => form_error('user[]'),
            );
            echo json_encode(array('status' => 1, 'msg' => $data));
        }
    }

    public function send_individual_sms()
    {
        $send_type = $this->input->post('individual_send_type');
        $this->form_validation->set_error_delimiters('');
        $this->form_validation->set_rules('individual_title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('individual_message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('user_list', $this->lang->line('recipient'), 'required');
        $this->form_validation->set_rules('individual_send_by[]', $this->lang->line('send_through'), 'required');
        if ($send_type == 'schedule') {
            $this->form_validation->set_rules('schedule_date_time', $this->lang->line('schedule_date_time'), 'required');
        }
        $template_id = $this->input->post('individual_template_id');

        if ($this->form_validation->run()) {

            $userlisting = json_decode($this->input->post('user_list'));
            $user_array  = array();
            foreach ($userlisting as $userlisting_key => $userlisting_value) {
                $array = array(
                    'category'      => $userlisting_value[0]->category,
                    'user_id'       => $userlisting_value[0]->record_id,
                    'email'         => $userlisting_value[0]->email,
                    'guardianEmail' => $userlisting_value[0]->guardianEmail,
                    'mobileno'      => $userlisting_value[0]->mobileno,
                    'app_key'       => $userlisting_value[0]->app_key,
                    'role'=>$userlisting_value[0]->category,
                );
                $user_array[] = $array;
            }

            $sms_mail = $this->input->post('individual_send_by');

            $message       = $this->input->post('individual_message');
            $message_title = $this->input->post('individual_title');
            $data          = array(
                'is_individual'   => 1,
                'title'           => $message_title,
                'message'         => $message,
                'send_through'    => json_encode($sms_mail),
                'sms_template_id' => $this->input->post('template_id'),
                'group_list'      => json_encode($this->input->post('user[]')),
                'template_id'     => $template_id,
                'send_mail'       => 0,
                'send_sms'        => 1,
                'created_at'      => date('Y-m-d H:i:s'),
            );

            if ($send_type == 'send_now') {
                if (!empty($user_array)) {

                    foreach ($user_array as $user_mail_key => $user_mail_value) {
                        if (in_array("sms", $sms_mail)) {

                            if ($user_mail_value['mobileno'] != "") {

                                $this->smsgateway->sendSMS($user_mail_value['mobileno'], $message, $template_id, "");
                            }
                        }
                        if (in_array("push", $sms_mail)) {
                            $push_array = array(
                                'title' => $message_title,
                                'body'  => $message,
                            );
                            if ($user_mail_value['app_key'] != "") {
                                $this->pushnotification->send($user_mail_value['app_key'], $push_array, "mail_sms");
                            }
                        }
                    }
                }} else {
                $data['is_schedule']        = 1;
                $data['sent']               = 0;
                $data['schedule_date_time'] = date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($this->input->post('schedule_date_time'), false));
            }
            $data['user_list'] = json_encode($user_array);
            $this->messages_model->add($data);
            if ($send_type == 'send_now') {
                echo json_encode(array('status' => 0, 'msg' => $this->lang->line('message_sent_successfully')));
            } else {
                echo json_encode(array('status' => 0, 'msg' => $this->lang->line('schedule_message_successfully')));
            }
        } else {

            $data = array(
                'individual_title'     => form_error('individual_title'),
                'individual_send_by[]' => form_error('individual_send_by[]'),
                'individual_message'   => form_error('individual_message'),
                'user_list'            => form_error('user_list'),
            );
            if ($send_type == 'schedule') {
                $data['schedule_date_time'] = form_error('schedule_date_time');
            }
            echo json_encode(array('status' => 1, 'msg' => $data));
        }
    }

    public function send_class_sms()
    {
        $send_type = $this->input->post('class_send_type');
        $this->form_validation->set_error_delimiters('');
        $this->form_validation->set_rules('class_title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('class_message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'required');
        $this->form_validation->set_rules('user[]', $this->lang->line('recipient'), 'required');
        $this->form_validation->set_rules('class_send_by[]', $this->lang->line('send_through'), 'required');
        if ($send_type == 'schedule') {
            $this->form_validation->set_rules('schedule_date_time', $this->lang->line('schedule_date_time'), 'required');
        }
        $template_id = $this->input->post('class_template_id');
        if ($this->form_validation->run()) {

            $sms_mail      = $this->input->post('class_send_by');
            $message       = $this->input->post('class_message');
            $message_title = $this->input->post('class_title');
            $section       = $this->input->post('user[]');
            $class_id      = $this->input->post('class_id');

            $user_array = array();
            foreach ($section as $section_key => $section_value) {
                $userlisting = $this->student_model->searchByClassSection($class_id, $section_value);
                if (!empty($userlisting)) {
                    foreach ($userlisting as $userlisting_key => $userlisting_value) {
                        $array = array(
                            'user_id'  => $userlisting_value['id'],
                            'email'    => $userlisting_value['email'],
                            'mobileno' => $userlisting_value['mobileno'],
                            'app_key'  => $userlisting_value['app_key'],
                            'role'  => 'student',
                        );
                        $user_array[] = $array;
                    }
                }
            }

            $data = array(
                'is_class'         => 1,
                'title'            => $message_title,
                'message'          => $message,
                'send_through'     => json_encode($sms_mail),
                'sms_template_id'  => $this->input->post('template_id'),
                'group_list'       => json_encode($this->input->post('user[]')),
                'template_id'      => $template_id,
                'schedule_class'   => $class_id,
                'schedule_section' => json_encode($section),
                'send_mail'        => 0,
                'send_sms'         => 1,
                'user_list'        => json_encode($user_array),
                'created_at'       => date('Y-m-d H:i:s'),
            );

            if ($send_type == 'send_now') {
                if (!empty($user_array)) {

                    foreach ($user_array as $user_mail_key => $user_mail_value) {
                        if (in_array("sms", $sms_mail)) {
                            if ($user_mail_value['mobileno'] != "") {

                                $this->smsgateway->sendSMS($user_mail_value['mobileno'], $message, $template_id, "");
                            }
                        }
                        if (in_array("push", $sms_mail)) {
                            $push_array = array(
                                'title' => $message_title,
                                'body'  => $message,
                            );
                            if ($user_mail_value['app_key'] != "") {
                                $this->pushnotification->send($user_mail_value['app_key'], $push_array, "mail_sms");
                            }
                        }
                    }
                }
            } else {
                $data['is_schedule']        = 1;
                $data['sent']               = 0;
                $data['schedule_date_time'] = date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($this->input->post('schedule_date_time'), false));
            }
            $data['user_list'] = json_encode($user_array);
            $this->messages_model->add($data);
            if ($send_type == 'send_now') {
                echo json_encode(array('status' => 0, 'msg' => $this->lang->line('message_sent_successfully')));
            } else {
                echo json_encode(array('status' => 0, 'msg' => $this->lang->line('schedule_message_successfully')));
            }
        } else {

            $data = array(
                'class_title'     => form_error('class_title'),
                'class_send_by[]' => form_error('class_send_by[]'),
                'class_message'   => form_error('class_message'),
                'class_id'        => form_error('class_id'),
                'user[]'          => form_error('user[]'),
            );
            if ($send_type == 'schedule') {
                $data['schedule_date_time'] = form_error('schedule_date_time');
            }
            echo json_encode(array('status' => 1, 'msg' => $data));
        }
    }

    public function send_class()
    {
        $send_type = $this->input->post('class_send_type');
        $this->form_validation->set_error_delimiters('');
        $this->form_validation->set_rules('class_title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('class_message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'required');
        $this->form_validation->set_rules('user[]', $this->lang->line('section'), 'required');
        $this->form_validation->set_rules('class_send_by', $this->lang->line('send_through'), 'required');
        $this->form_validation->set_rules('files', $this->lang->line('file'), 'callback_multihandle_upload[files]');
        if ($send_type == 'class_schedule') {
            $this->form_validation->set_rules('schedule_date_time', $this->lang->line('schedule_date_time'), 'required');
        }
        if ($this->form_validation->run()) {

            $sms_mail = $this->input->post('class_send_by');
            if ($sms_mail == "sms") {
                $send_sms  = 1;
                $send_mail = 0;
            } else {
                $send_sms  = 0;
                $send_mail = 1;
            }
            $message       = $this->input->post('class_message');
            $message_title = $this->input->post('class_title');
            $section       = $this->input->post('user[]');
            $class_id      = $this->input->post('class_id');

            $user_array = array();
            foreach ($section as $section_key => $section_value) {
                $userlisting = $this->student_model->searchByClassSection($class_id, $section_value);
                if (!empty($userlisting)) {
                    foreach ($userlisting as $userlisting_key => $userlisting_value) {
                        $array = array(
                            'user_id'  => $userlisting_value['id'],
                            'email'    => $userlisting_value['email'],
                            'mobileno' => $userlisting_value['mobileno'],
                            'mobileno' => 'student',
                        );
                        $user_array[] = $array;
                    }
                }
            }

            $data = array(
                'is_class'          => 1,
                'title'             => $message_title,
                'message'           => $message,
                'send_mail'         => $send_mail,
                'send_sms'          => $send_sms,
                'email_template_id' => $this->input->post('template_id'),
                'schedule_class'    => $class_id,
                'schedule_section'  => json_encode($section),
                'user_list'         => json_encode($user_array),
                'created_at'        => date('Y-m-d H:i:s'),
            );

            $send_attachments     = array();
            $send_new_attachments = array();
            $send_old_attachments = array();
            $myattachment_data    = array();
            $attachments          = array();
            if (isset($_FILES['files']['name']) && !empty($_FILES['files']['name'])) {
                foreach ($_FILES['files']['name'] as $key => $files_value) {
                    $file_type = $_FILES['files']['type'][$key];
                    $file_size = $_FILES['files']["size"][$key];
                    $file_name = $_FILES['files']['name'][$key];
                    $fileInfo  = pathinfo($_FILES["files"]["name"][$key]);
                    $img_name  = time() . rand(99, 999) . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["files"]["tmp_name"][$key], "./uploads/communicate/email_attachments/" . $img_name);
                    $send_new_attachments[] = array('directory' => 'uploads/communicate/email_attachments/', 'attachment' => $img_name, 'attachment_name' => $file_name);
                    $myattachment_data[]    = array('attachment' => $img_name, 'attachment_name' => $file_name, 'directory' => 'uploads/communicate/email_attachments/');
                }
            }

            if (!empty($_POST['template_attachment'])) {
                foreach ($_POST['template_attachment'] as $key => $value) {
                    $email_template_attachment_data = $this->messages_model->get_single_email_template_attachment($key);

                    $send_old_attachments[] = array('directory' => 'uploads/communicate/email_template_images/', 'attachment' => $email_template_attachment_data['attachment'], 'attachment_name' => $email_template_attachment_data['attachment_name']);
                    $attachments[]          = array('attachment' => $email_template_attachment_data['attachment'], 'attachment_name' => $email_template_attachment_data['attachment_name'], 'directory' => 'uploads/communicate/email_template_images/');
                }
            }

            $send_attachments = array_merge($send_new_attachments, $send_old_attachments);
            $attachment_data  = array_merge($myattachment_data, $attachments);

            if ($send_type == 'send_now') {
                if (!empty($user_array)) {
                    if ($send_mail) {
                        if (!empty($this->mail_config)) {
                            foreach ($user_array as $user_mail_key => $user_mail_value) {
                                if ($user_mail_value['email'] != "") {
                                    $this->mailer->compose_mail($user_mail_value['email'], $message_title, $message, $send_attachments);
                                }
                            }
                        }
                    }
                }
            } else {
                $data['is_schedule']        = 1;
                $data['sent']               = 0;
                $data['schedule_date_time'] = date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($this->input->post('schedule_date_time'), false));
            }
            if (!empty($user_array)) {

                if ($send_sms) {
                    foreach ($user_array as $user_mail_key => $user_mail_value) {
                        if ($user_mail_value['mobileno'] != "") {

                            $this->smsgateway->sendSMS($user_mail_value['mobileno'], "", ($message));
                        }
                    }
                }
            }
            $data['user_list'] = json_encode($user_array);

            $last_inserted_id = $this->messages_model->add($data);

            if (!empty($attachment_data)) {
                foreach ($attachment_data as $attachment_key => $attachment_data_value) {

                    $email_attachments_data['message_id']      = $last_inserted_id;
                    $email_attachments_data['attachment']      = $attachment_data_value['attachment'];
                    $email_attachments_data['attachment_name'] = $attachment_data_value['attachment_name'];
                    $email_attachments_data['directory']       = $attachment_data_value['directory'];

                    $this->messages_model->add_email_attachment($email_attachments_data);
                }
            }

            if ($send_type == 'send_now') {
                echo json_encode(array('status' => 0, 'msg' => $this->lang->line('message_sent_successfully')));
            } else {
                echo json_encode(array('status' => 0, 'msg' => $this->lang->line('schedule_message_successfully')));
            }
        } else {

            $data = array(
                'class_title'   => form_error('class_title'),
                'class_message' => form_error('class_message'),
                'class_id'      => form_error('class_id'),
                'class_send_by' => form_error('class_send_by'),
                'user[]'        => form_error('user[]'),
                'files'         => form_error('files'),
            );
            if ($send_type == 'schedule') {
                $data['schedule_date_time'] = form_error('schedule_date_time');
            }
            echo json_encode(array('status' => 1, 'msg' => $data));
        }
    }

    public function test_sms()
    {
        $this->form_validation->set_rules('mobile', $this->lang->line('mobile_number'), 'required');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'mobile' => form_error('mobile'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $this->smsgateway->sendSMS($this->input->post('mobile'), ('Smart School SMS Test Successful.'));

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('test_sms_sent_successfully_please_check_your_mobile_if_you_have_receiveds'));
        }
        echo json_encode($array);
    }

    public function email_template()
    {
        if (!$this->rbac->hasPrivilege('email_template', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Communicate');
        $this->session->set_userdata('sub_menu', 'email_template');

        $email_template_list         = $this->messages_model->get_email_template();
        $data['email_template_list'] = $email_template_list;
        $this->load->view('layout/header');
        $this->load->view('admin/mailsms/email_template/email_template', $data);
        $this->load->view('layout/footer');
    }

    public function add_email_template()
    {
        if (!$this->rbac->hasPrivilege('email_template', 'can_add')) {
            access_denied();
        }

        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('message', $this->lang->line('message'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('files', $this->lang->line('file'), 'callback_multihandle_upload[files]');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'title'   => form_error('title'),
                'message' => form_error('message'),
                'files'   => form_error('files'),
            );
            $array = array('status' => '0', 'error' => $msg, 'message' => '');

        } else {

            $data = array(
                'title'      => $this->input->post('title'),
                'message'    => $this->input->post('message'),
                'created_at' => date('Y-m-d'),
            );

            $this->messages_model->add_email_template($data, $_FILES);

            $array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));

        }
        echo json_encode($array);

    }

    public function edit_email_template()
    {
        if (!$this->rbac->hasPrivilege('email_template', 'can_edit')) {
            access_denied();
        }

        $id                  = $this->input->post('id');
        $email_template_list = $this->messages_model->get_email_template($id);
        $attachment_list     = $this->messages_model->get_email_template_attachment($id);

        $attachment_output = '';
        foreach ($attachment_list as $key => $value) {
            $attachment_data        = $value['attachment_name'];
            $template_attachment_id = $value['id'];

            $attachment_output .= $this->genrateDiv($attachment_data, $value['attachment'], $template_attachment_id);
        }

        echo json_encode(array('data' => $email_template_list, 'attachment_list' => $attachment_output));
    }

    public function update_email_template()
    {
        if (!$this->rbac->hasPrivilege('email_template', 'can_edit')) {
            access_denied();
        }

        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('message', $this->lang->line('message'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('files', $this->lang->line('attachment'), 'callback_handle_upload[files]');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'title'   => form_error('title'),
                'message' => form_error('message'),
                'files'   => form_error('files'),
            );
            $array = array('status' => '0', 'error' => $msg, 'message' => '');

        } else {

            $data = array(
                'id'         => $this->input->post('id'),
                'title'      => $this->input->post('title'),
                'message'    => $this->input->post('message'),
                'created_at' => date('Y-m-d'),
            );

            $attachments    = array();
            $new_attachment = array();

            if (isset($_FILES['attachment']['name']) && !empty($_FILES['attachment']['name'])) {
                foreach ($_FILES['attachment']['name'] as $key => $files_value) {
                    $file_type = $_FILES['attachment']['type'][$key];
                    $file_size = $_FILES['attachment']["size"][$key];
                    $file_name = $_FILES['attachment']['name'][$key];
                    $fileInfo  = pathinfo($_FILES["attachment"]["name"][$key]);
                    $img_name  = time() . rand(99, 999) . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["attachment"]["tmp_name"][$key], "./uploads/communicate/email_template_images/" . $img_name);

                    $new_attachment[] = array('attachment' => $img_name, 'attachment_name' => $file_name);
                }
            }

            if (!empty($_POST['template_attachment'])) {
                foreach ($_POST['template_attachment'] as $key => $value) {
                    $checkstatus = $this->messages_model->check_template_attachment($this->input->post('id'), $value);
                    if (!empty($checkstatus)) {

                        if ($checkstatus->attachment == $value) {
                            $attachments[] = array('attachment' => $checkstatus->attachment, 'attachment_name' => $checkstatus->attachment_name);
                        }

                    } else {
                        $email_template_attachment_data = $this->messages_model->get_single_email_template_attachment($key);
                        $attachments[]                  = array('attachment' => $email_template_attachment_data['attachment'], 'attachment_name' => $email_template_attachment_data['attachment_name']);
                    }
                }
            }

            $attachment_data = array_merge($new_attachment, $attachments);
            $this->messages_model->delete_template_attachment($this->input->post('id'));
            $this->messages_model->add_email_template($data, $attachment_data);

            $array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);

    }

    public function handle_upload($str, $var)
    {
        if (isset($_FILES['attachment']['name']) && !empty($_FILES['attachment']['name'])) {
            $image_validate               = $this->config->item('file_validate');
            $result                       = $this->filetype_model->get();
            $file_size_shoud_be_less_than = array();
            $file_type_not_allowed        = array();
            foreach ($_FILES['attachment']['name'] as $key => $files_value) {
                $file_type = $_FILES['attachment']['type'][$key];
                $file_size = $_FILES['attachment']["size"][$key];
                $file_name = $_FILES['attachment']['name'][$key];

                $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
                $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
                $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $message           = "";
                if ($files = filesize($_FILES['attachment']['tmp_name'][$key])) {

                    if (!in_array($file_type, $allowed_mime_type)) {

                        $file_type_not_allowed[] = 0;

                    }
                    if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {

                        $file_type_not_allowed[] = 0;

                    }
                    if ($file_size > $result->file_size) {

                        $file_size_shoud_be_less_than[] = 0;

                    }
                } else {

                }

            }

            if (in_array(0, $file_type_not_allowed)) {

                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                $file_type_not_allowed[] = 0;
                return false;
            }

            if (in_array(0, $file_size_shoud_be_less_than)) {

                $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

        }

        return true;
    }

    public function delete_email_template($id)
    {
        if (!$this->rbac->hasPrivilege('email_template', 'can_delete')) {
            access_denied();
        }

        $this->messages_model->delete_email_template($id);
        $this->session->set_flashdata('message', $this->lang->line('delete_message'));
        redirect('admin/mailsms/email_template');
    }

    public function email_template_download($doc)
    {
        $this->load->helper('download');
        $name     = $this->uri->segment(5);
        $ext      = explode(".", $name);
        $filepath = "./uploads/communicate/email_template_images/" . $doc;
        $data     = file_get_contents($filepath);
        force_download($name, $data);
    }

    public function sms_template()
    {
        if (!$this->rbac->hasPrivilege('sms_template', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Communicate');
        $this->session->set_userdata('sub_menu', 'sms_template');

        $sms_template_list         = $this->messages_model->get_sms_template();
        $data['sms_template_list'] = $sms_template_list;
        $this->load->view('layout/header');
        $this->load->view('admin/mailsms/sms_template/sms_template', $data);
        $this->load->view('layout/footer');
    }

    public function add_sms_template()
    {
        if (!$this->rbac->hasPrivilege('sms_template', 'can_add')) {
            access_denied();
        }

        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('message', $this->lang->line('message'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'title'   => form_error('title'),
                'message' => form_error('message'),
                'files'   => form_error('files'),
            );
            $array = array('status' => '0', 'error' => $msg, 'message' => '');

        } else {

            $data = array(
                'title'      => $this->input->post('title'),
                'message'    => $this->input->post('message'),
                'created_at' => date('Y-m-d'),
            );

            $this->messages_model->add_sms_template($data);

            $array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));

        }
        echo json_encode($array);

    }

    public function edit_sms_template()
    {

        if (!$this->rbac->hasPrivilege('sms_template', 'can_edit')) {
            access_denied();
        }

        $id                        = $this->input->post('id');
        $data['sms_template_list'] = $this->messages_model->get_sms_template($id);
        $page                      = $this->load->view('admin/mailsms/sms_template/_edit_sms_template', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function update_sms_template()
    {
        if (!$this->rbac->hasPrivilege('sms_template', 'can_edit')) {
            access_denied();
        }

        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('message', $this->lang->line('message'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'title'   => form_error('title'),
                'message' => form_error('message'),
            );
            $array = array('status' => '0', 'error' => $msg, 'message' => '');

        } else {

            $data = array(
                'id'         => $this->input->post('id'),
                'title'      => $this->input->post('title'),
                'message'    => $this->input->post('message'),
                'created_at' => date('Y-m-d'),
            );

            $this->messages_model->add_sms_template($data);

            $array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));

        }
        echo json_encode($array);

    }

    public function delete_sms_template($id)
    {
        if (!$this->rbac->hasPrivilege('sms_template', 'can_delete')) {
            access_denied();
        }

        $this->messages_model->delete_sms_template($id);
        $this->session->set_flashdata('message', $this->lang->line('delete_message'));
        redirect('admin/mailsms/sms_template/sms_template');
    }

    public function templatedata()
    {
        $template_id         = $this->input->post('template_id');
        $email_template_list = $this->messages_model->get_email_template($template_id);
        $attachment_list     = $this->messages_model->get_email_template_attachment($template_id);
        $attachment_output   = '';
        foreach ($attachment_list as $key => $value) {
            $attachment_data        = $value['attachment_name'];
            $template_attachment_id = $value['id'];

            $attachment_output .= $this->genrateDiv($attachment_data, $value['attachment'], $template_attachment_id);
        }

        echo json_encode(array('data' => $email_template_list, 'attachment_list' => $attachment_output));
    }

    public function smstemplatedata()
    {
        $template_id       = $this->input->post('template_id');
        $sms_template_list = $this->messages_model->get_sms_template($template_id);
        echo json_encode(array('data' => $sms_template_list));
    }

    public function multihandle_upload($str, $var)
    {
        if (isset($_FILES['files']['name']) && !empty($_FILES['files']['name'])) {
            $image_validate               = $this->config->item('file_validate');
            $result                       = $this->filetype_model->get();
            $file_size_shoud_be_less_than = array();
            $file_type_not_allowed        = array();
            foreach ($_FILES['files']['name'] as $key => $files_value) {
                $file_type         = $_FILES['files']['type'][$key];
                $file_size         = $_FILES['files']["size"][$key];
                $file_name         = $_FILES['files']['name'][$key];
                $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
                $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
                $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $message           = "";
                if ($files = filesize($_FILES['files']['tmp_name'][$key])) {

                    if (!in_array($file_type, $allowed_mime_type)) {

                        $file_type_not_allowed[] = 0;

                    }
                    if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {

                        $file_type_not_allowed[] = 0;

                    }
                    if ($file_size > $result->file_size) {

                        $file_size_shoud_be_less_than[] = 0;

                    }
                } else {

                }
            }

            if (in_array(0, $file_type_not_allowed)) {

                $this->form_validation->set_message('multihandle_upload', $this->lang->line('file_type_not_allowed'));
                $file_type_not_allowed[] = 0;
                return false;
            }

            if (in_array(0, $file_size_shoud_be_less_than)) {

                $this->form_validation->set_message('multihandle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

        }

        return true;
    }

    public function get_birthday_preview()
    {
        $is_image  = "0";
        $is_video  = "0";
        $delete_id = $_POST['delete_id'];
        $img_name  = $_POST['img_name'];
        $dir_path  = $_POST['dir_path'];
        $file_type = $_POST['file_type'];
        if ($file_type == 'image/png' || $file_type == 'image/jpeg' || $file_type == 'image/jpeg' || $file_type == 'image/jpeg' || $file_type == 'image/gif') {
            $file     = $dir_path;
            $file_src = $dir_path;
            $is_image = 1;
        } elseif ($file_type == 'video') {
            $file     = $dir_path;
            $file_src = $dir_path;
            $is_video = 1;
        } elseif ($file_type == 'text/plain') {
            $file     = base_url('backend/images/txticon.png');
            $file_src = $dir_path;
        } elseif ($file_type == 'application/zip' || $file_type == 'application/x-rar' || $file_type == 'application/x-zip-compressed') {
            $file     = base_url('backend/images/zipicon.png');
            $file_src = $dir_path;
        } elseif ($file_type == 'application/pdf') {
            $file     = base_url('backend/images/pdficon.png');
            $file_src = $dir_path;
        } elseif ($file_type == 'application/msword') {
            $file     = base_url('backend/images/wordicon.png');
            $file_src = $dir_path;
        } elseif ($file_type == 'application/vnd.ms-excel') {
            $file     = base_url('backend/images/excelicon.png');
            $file_src = $dir_path;
        } else {
            $file     = base_url('backend/images/docicon.png');
            $file_src = $dir_path;
        }
        $output = '';
        $output .= "<div class='col-sm-3 col-md-2 col-xs-6 img_div_modal image_div div_record_' id='birthday_myattach_" . $delete_id . "'>";
        $output .= "<div class='fadeoverlay_'>";
        $output .= "<div class='fadeheight-sms'><p class=''><a class='uploadclosebtn delete_gallery_img' data-record_id='2' data-toggle='modal' data-target='#confirm-delete' title=''><i class='fa fa-trash-o' onclick='birthday_removemyAttachment(" . $delete_id . ")'></i></a>" . $img_name . " </p>";
        $output .= "</div>";
//==============

        if ($is_video == 1) {
            //$output .= "<i class='fa fa-youtube-play videoicon'></i>";
        }
        if ($is_image == 1) {
            //$output .= "<i class='fa fa-picture-o videoicon'></i>";
        }

        if ($is_video == 1) {
            //$output .= "<p class=''>" "</p>";
        } else {
            $output .= "";
        }
        $output .= "</div>";
        $output .= "</div>";
        echo json_encode($output);
    }

    public function get_class_preview()
    {
        $is_image  = "0";
        $is_video  = "0";
        $delete_id = $_POST['delete_id'];
        $img_name  = $_POST['img_name'];
        $dir_path  = $_POST['dir_path'];
        $file_type = $_POST['file_type'];
        if ($file_type == 'image/png' || $file_type == 'image/jpeg' || $file_type == 'image/jpeg' || $file_type == 'image/jpeg' || $file_type == 'image/gif') {

            $file     = $dir_path;
            $file_src = $dir_path;
            $is_image = 1;
        } elseif ($file_type == 'video') {
            $file     = $dir_path;
            $file_src = $dir_path;

            $is_video = 1;
        } elseif ($file_type == 'text/plain') {
            $file     = base_url('backend/images/txticon.png');
            $file_src = $dir_path;
        } elseif ($file_type == 'application/zip' || $file_type == 'application/x-rar' || $file_type == 'application/x-zip-compressed') {
            $file     = base_url('backend/images/zipicon.png');
            $file_src = $dir_path;
        } elseif ($file_type == 'application/pdf') {
            $file     = base_url('backend/images/pdficon.png');
            $file_src = $dir_path;
        } elseif ($file_type == 'application/msword') {
            $file     = base_url('backend/images/wordicon.png');
            $file_src = $dir_path;
        } elseif ($file_type == 'application/vnd.ms-excel') {
            $file     = base_url('backend/images/excelicon.png');
            $file_src = $dir_path;
        } else {
            $file     = base_url('backend/images/docicon.png');
            $file_src = $dir_path;
        }
        $output = '';
        $output .= "<div class='col-sm-3 col-md-2 col-xs-6 img_div_modal image_div div_record_' id='class_myattach_" . $delete_id . "'>";
        $output .= "<div class='fadeoverlay_'>";
        $output .= "<div class='fadeheight-sms'><p class=''><a class='uploadclosebtn delete_gallery_img' data-record_id='2' data-toggle='modal' data-target='#confirm-delete' title=''><i class='fa fa-trash-o' onclick='class_removemyAttachment(" . $delete_id . ")'></i></a>" . $img_name . " </p>";

        $output .= "</div>";
//==============

        if ($is_video == 1) {
            //$output .= "<i class='fa fa-youtube-play videoicon'></i>";
        }
        if ($is_image == 1) {
            //$output .= "<i class='fa fa-picture-o videoicon'></i>";
        }

        if ($is_video == 1) {
            //$output .= "<p class=''>" "</p>";
        } else {
            $output .= "";
        }
        $output .= "</div>";
        $output .= "</div>";
        echo json_encode($output);
    }

    public function get_individual_preview()
    {
        $is_image  = "0";
        $is_video  = "0";
        $delete_id = $_POST['delete_id'];
        $img_name  = $_POST['img_name'];
        $dir_path  = $_POST['dir_path'];
        $file_type = $_POST['file_type'];
        if ($file_type == 'image/png' || $file_type == 'image/jpeg' || $file_type == 'image/jpeg' || $file_type == 'image/jpeg' || $file_type == 'image/gif') {

            $file     = $dir_path;
            $file_src = $dir_path;
            $is_image = 1;
        } elseif ($file_type == 'video') {
            $file     = $dir_path;
            $file_src = $dir_path;

            $is_video = 1;
        } elseif ($file_type == 'text/plain') {
            $file     = base_url('backend/images/txticon.png');
            $file_src = $dir_path;
        } elseif ($file_type == 'application/zip' || $file_type == 'application/x-rar' || $file_type == 'application/x-zip-compressed') {
            $file     = base_url('backend/images/zipicon.png');
            $file_src = $dir_path;
        } elseif ($file_type == 'application/pdf') {
            $file     = base_url('backend/images/pdficon.png');
            $file_src = $dir_path;
        } elseif ($file_type == 'application/msword') {
            $file     = base_url('backend/images/wordicon.png');
            $file_src = $dir_path;
        } elseif ($file_type == 'application/vnd.ms-excel') {
            $file     = base_url('backend/images/excelicon.png');
            $file_src = $dir_path;
        } else {
            $file     = base_url('backend/images/docicon.png');
            $file_src = $dir_path;
        }

        $output = '';
        $output .= "<div class='col-sm-3 col-md-2 col-xs-6 img_div_modal image_div div_record_' id='individual_myattach_" . $delete_id . "'>";
        $output .= "<div class='fadeoverlay_'>";
        $output .= "<div class='fadeheight-sms'><p class=''><a class='uploadclosebtn delete_gallery_img' data-record_id='2' data-toggle='modal' data-target='#confirm-delete' title=''><i class='fa fa-trash-o' onclick='individual_removemyAttachment(" . $delete_id . ")'></i></a>" . $img_name . " </p>";
        $output .= "</div>";
        $output .= "</div>";
        $output .= "</div>";
        echo json_encode($output);
    }

    public function get_preview()
    {
        $is_image  = "0";
        $is_video  = "0";
        $delete_id = $_POST['delete_id'];
        $img_name  = $_POST['img_name'];
        $dir_path  = $_POST['dir_path'];
        $file_type = $_POST['file_type'];
        if ($file_type == 'image/png' || $file_type == 'image/jpeg' || $file_type == 'image/jpeg' || $file_type == 'image/jpeg' || $file_type == 'image/gif') {
            $file     = $dir_path;
            $file_src = $dir_path;
            $is_image = 1;
        } elseif ($file_type == 'video') {
            $file     = $dir_path;
            $file_src = $dir_path;

            $is_video = 1;
        } elseif ($file_type == 'text/plain') {
            $file     = base_url('backend/images/txticon.png');
            $file_src = $dir_path;
        } elseif ($file_type == 'application/zip' || $file_type == 'application/x-rar' || $file_type == 'application/x-zip-compressed') {
            $file     = base_url('backend/images/zipicon.png');
            $file_src = $dir_path;
        } elseif ($file_type == 'application/pdf') {
            $file     = base_url('backend/images/pdficon.png');
            $file_src = $dir_path;
        } elseif ($file_type == 'application/msword') {
            $file     = base_url('backend/images/wordicon.png');
            $file_src = $dir_path;
        } elseif ($file_type == 'application/vnd.ms-excel') {
            $file     = base_url('backend/images/excelicon.png');
            $file_src = $dir_path;
        } else {
            $file     = base_url('backend/images/docicon.png');
            $file_src = $dir_path;
        }
//==============
        $output = '';
        $output .= "<div class='col-sm-3 col-md-2 col-xs-6 img_div_modal image_div div_record_' id='myattach_" . $delete_id . "'>";
        $output .= "<div class='fadeoverlay_'>";
        $output .= "<div class='fadeheight-sms'><p class=''><a class='uploadclosebtn delete_gallery_img' data-record_id='2' data-toggle='modal' data-target='#confirm-delete' title=''><i class='fa fa-trash-o' onclick='removemyAttachment(" . $delete_id . ")'></i></a>" . $img_name . " </p>";

        $output .= "</div>";

        if ($is_video == 1) {
            //$output .= "<p class=''>" "</p>";
        } else {
            $output .= "";
        }
        $output .= "</div>";
        $output .= "</div>";
        echo json_encode($output);
    }

    public function genrateDiv($image_name, $attachments, $template_attachment_id)
    {
        $delete_id = time() . rand(99, 999);

        $output = '';
        $output .= "<div class='col-sm-3 col-md-2 col-xs-6 img_div_modal  div_record_' id='image_div_" . $delete_id . "'>";
        $output .= "<div class='fadeoverlay_'>";
        $output .= "<div class='fadeheight-sms'> <p class=''> <a  class='uploadclosebtn delete_gallery_img' data-record_id='2' data-toggle='modal' data-target='#confirm-delete'  title=''><i class='fa fa-trash-o' onclick='removeAttachment(" . $delete_id . ")'></i></a>" . $image_name . "</p> <input type='hidden' name='template_attachment[$template_attachment_id]' value='" . $attachments . "' />";
        $output .= "</div>";
        $output .= "</div>";
        $output .= "</div>";
        return $output;
    }

    public function edit_schedule($id)
    {
        if (!$this->rbac->hasPrivilege('schedule_email_sms_log', 'can_edit')) {
            access_denied();
        }

        $messagelist                    = $this->messages_model->schedule($id);
        $data['sch_setting']            = $this->sch_setting_detail;
        $data['roles']                  = $this->role_model->get();
        $data['superadmin_restriction'] = $this->customlib->superadmin_visible();
        $data['email_template_list']    = $this->messages_model->get_email_template();
        $data['sms_template_list']      = $this->messages_model->get_sms_template();
        $sch_setting                    = $this->setting_model->getSetting();

        if (!empty($messagelist)) {
            $data['messagelist'] = $messagelist;

            if ($messagelist['send_mail']) {
                if ($messagelist['is_group'] == '1') {
                    $data['group_list'] = json_decode($messagelist['group_list']);
                    $this->load->view('layout/header');
                    $this->load->view('admin/mailsms/schedule/email/edit_email_group', $data);
                    $this->load->view('layout/footer');
                } elseif ($messagelist['is_individual'] == '1') {
                    $user_list = json_decode($messagelist['user_list']);

                    foreach ($user_list as $key => $user_list_value) {

                        if ($user_list_value->category == 'staff') {
                            $staff_data                 = $this->staff_model->get($user_list_value->user_id);
                            $user_list_value->user_name = $staff_data['name'] . " " . $staff_data['surname'] . '(' . $staff_data['employee_id'] . ')';
                        } elseif ($user_list_value->category == 'parent') {
                            $student_data               = $this->student_model->getstudentdetailbyid($user_list_value->user_id);
                            $user_list_value->user_name = $student_data['guardian_name'];
                        } elseif ($user_list_value->category == "student_guardian") {
                            $student_guardian_data      = $this->student_model->getstudentdetailbyid($user_list_value->user_id);
                            $user_list_value->user_name = $this->customlib->getFullName($student_guardian_data['firstname'], $student_guardian_data['middlename'], $student_guardian_data['lastname'], $sch_setting->middlename, $sch_setting->lastname) . ' (' . $student_guardian_data['admission_no'] . ') (' . $student_guardian_data['guardian_name'] . ')';
                        } else {
                            $student_data               = $this->student_model->getstudentdetailbyid($user_list_value->user_id);
                            $user_list_value->user_name = $this->customlib->getFullName($student_data['firstname'], $student_data['middlename'], $student_data['lastname'], $sch_setting->middlename, $sch_setting->lastname) . '(' . $student_data['admission_no'] . ')';
                        }
                    }

                    $data['user_list'] = json_encode($user_list);

                    $this->load->view('layout/header');
                    $this->load->view('admin/mailsms/schedule/email/edit_email_individual', $data);
                    $this->load->view('layout/footer');
                } elseif ($messagelist['is_class'] == '1') {
                    $data['classlist']        = $this->class_model->get();
                    $data['selected_section'] = $messagelist['schedule_section'];

                    $this->load->view('layout/header');
                    $this->load->view('admin/mailsms/schedule/email/edit_email_class', $data);
                    $this->load->view('layout/footer');
                }
            } else {
                if ($messagelist['is_group'] == '1') {
                    $data['group_list']            = json_decode($messagelist['group_list']);
                    $data['selected_send_through'] = json_decode($messagelist['send_through']);
                    $data['send_through_list']     = $this->send_through;
                    $this->load->view('layout/header');
                    $this->load->view('admin/mailsms/schedule/sms/edit_sms_group', $data);
                    $this->load->view('layout/footer');
                } elseif ($messagelist['is_individual'] == '1') {
                    $user_list = json_decode($messagelist['user_list']);

                    foreach ($user_list as $key => $user_list_value) {

                        if ($user_list_value->category == 'staff') {
                            $staff_data                 = $this->staff_model->get($user_list_value->user_id);
                            $user_list_value->user_name = $staff_data['name'] . " " . $staff_data['surname'] . '(' . $staff_data['employee_id'] . ')';
                        } elseif ($user_list_value->category == 'parent') {
                            $student_data               = $this->student_model->getstudentdetailbyid($user_list_value->user_id);
                            $user_list_value->user_name = $student_data['guardian_name'];
                        } else {
                            $student_data               = $this->student_model->getstudentdetailbyid($user_list_value->user_id);
                            $user_list_value->user_name = $this->customlib->getFullName($student_data['firstname'], $student_data['middlename'], $student_data['lastname'], $sch_setting->middlename, $sch_setting->lastname) . '(' . $student_data['admission_no'] . ')';
                        }
                    }

                    $data['user_list'] = json_encode($user_list);

                    $data['selected_send_through'] = json_decode($messagelist['send_through']);
                    $data['send_through_list']     = $this->send_through;
                    $this->load->view('layout/header');
                    $this->load->view('admin/mailsms/schedule/sms/edit_sms_individual', $data);
                    $this->load->view('layout/footer');
                } elseif ($messagelist['is_class'] == '1') {
                    $data['classlist']             = $this->class_model->get();
                    $data['selected_section']      = $messagelist['schedule_section'];
                    $data['selected_send_through'] = json_decode($messagelist['send_through']);
                    $data['send_through_list']     = $this->send_through;

                    $this->load->view('layout/header');
                    $this->load->view('admin/mailsms/schedule/sms/edit_sms_class', $data);
                    $this->load->view('layout/footer');
                }
            }
        }
    }

    public function update_group_schedule()
    {
        $this->form_validation->set_rules('group_title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('group_message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('user[]', $this->lang->line('message_to'), 'required');
        $this->form_validation->set_rules('files', $this->lang->line('file'), 'callback_multihandle_upload[files]');
        $this->form_validation->set_rules('schedule_date_time', $this->lang->line('schedule_date_time'), 'required');

        if ($this->form_validation->run()) {

            $user_array = array();
            $attachments       = array();
            $myattachment_data = array();
            $new_attachments   = array();
            $message           = $this->input->post('group_message');
            $message_title     = $this->input->post('group_title');

            $data = array(
                'id'                => $this->input->post('message_id'),
                'title'             => $message_title,
                'message'           => $message,
                'email_template_id' => $this->input->post('template_id'),
                'group_list'        => json_encode($this->input->post('user[]')),
                'updated_at'        => date('Y-m-d H:i:s'),
                'sent'              => 0,
            );

            if (isset($_FILES['files']['name']) && !empty($_FILES['files']['name'])) {
                foreach ($_FILES['files']['name'] as $key => $files_value) {
                    $file_type = $_FILES['files']['type'][$key];
                    $file_size = $_FILES['files']["size"][$key];
                    $file_name = $_FILES['files']['name'][$key];
                    $fileInfo  = pathinfo($_FILES["files"]["name"][$key]);
                    $img_name  = time() . rand(99, 999) . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["files"]["tmp_name"][$key], "./uploads/communicate/email_attachments/" . $img_name);
                    $myattachment_data[] = array('attachment' => $img_name, 'attachment_name' => $file_name, 'directory' => 'uploads/communicate/email_attachments/');
                }
            }

            if (!empty($_POST['template_attachment'])) {
                foreach (array_unique($_POST['template_attachment']) as $key => $value) {
                    $message_list = $this->messages_model->schedule($this->input->post('message_id'));

                    if ($message_list['email_template_id'] == $this->input->post('template_id')) {
                        $checkstatus = $this->messages_model->check_email_attachment($this->input->post('message_id'), $value);

                        if (!empty($checkstatus)) {

                            if ($checkstatus->attachment == $value) {
                                $attachments[] = array('attachment' => $checkstatus->attachment, 'attachment_name' => $checkstatus->attachment_name, 'directory' => $checkstatus->directory);
                            }

                        } else {
                            $email_template_attachment_data = $this->messages_model->get_single_email_template_attachment($key);
                            $new_attachments[]              = array('attachment' => $email_template_attachment_data['attachment'], 'attachment_name' => $email_template_attachment_data['attachment_name'], 'directory' => 'uploads/communicate/email_template_images/');
                        }

                    } else {
                        $email_template_attachment_data = $this->messages_model->get_single_email_template_attachment($key);
                        $new_attachments[]              = array('attachment' => $email_template_attachment_data['attachment'], 'attachment_name' => $email_template_attachment_data['attachment_name'], 'directory' => 'uploads/communicate/email_template_images/');
                    }
                }
            }

            $attachment_data = array_merge($myattachment_data, $attachments, $new_attachments);

            $this->messages_model->delete_email_attachment($this->input->post('message_id'));

            foreach ($attachment_data as $attachment_key => $attachment_data_value) {

                $email_attachments_data['message_id']      = $this->input->post('message_id');
                $email_attachments_data['attachment']      = $attachment_data_value['attachment'];
                $email_attachments_data['attachment_name'] = $attachment_data_value['attachment_name'];
                $email_attachments_data['directory']       = $attachment_data_value['directory'];

                $this->messages_model->add_email_attachment($email_attachments_data);
            }

            $userlisting = $this->input->post('user[]');

            foreach ($userlisting as $users_key => $users_value) {
                if ($users_value == "student") {
                    $student_array = $this->student_model->get();
                    if (!empty($student_array)) {
                        foreach ($student_array as $student_key => $student_value) {

                            $array = array(
                                'user_id'  => $student_value['id'],
                                'email'    => $student_value['email'],
                                'mobileno' => $student_value['mobileno'],
                                'role'=>'student'
                            );
                            $user_array[] = $array;
                        }
                    }
                } else if ($users_value == "parent") {
                    $parent_array = $this->student_model->get();
                    if (!empty($parent_array)) {
                        foreach ($parent_array as $parent_key => $parent_value) {
                            $array = array(
                                'user_id'  => $parent_value['id'],
                                'email'    => $parent_value['guardian_email'],
                                'mobileno' => $parent_value['guardian_phone'],
                                'role'=>'parent'
                            );
                            $user_array[] = $array;
                        }
                    }
                } else if (is_numeric($users_value)) {

                    $staff = $this->staff_model->getEmployeeByRoleID($users_value);
                    if (!empty($staff)) {
                        foreach ($staff as $staff_key => $staff_value) {
                            $array = array(
                                'user_id'  => $staff_value['id'],
                                'email'    => $staff_value['email'],
                                'mobileno' => $staff_value['contact_no'],
                                'role'=>'staff'
                            );
                            $user_array[] = $array;
                        }
                    }
                }
            }

            $data['schedule_date_time'] = date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($this->input->post('schedule_date_time'), false));

            $data['user_list'] = json_encode($user_array);
            $this->messages_model->add($data);
            echo json_encode(array('status' => 0, 'msg' => $this->lang->line('success_message')));
        } else {

            $data = array(
                'group_title'   => form_error('group_title'),
                'group_message' => form_error('group_message'),
                'user[]'        => form_error('user[]'),
                'files'         => form_error('files'),
            );

            $data['schedule_date_time'] = form_error('schedule_date_time');

            echo json_encode(array('status' => 1, 'msg' => $data));
        }
    }

    public function update_individual_schedule()
    {
        $this->form_validation->set_rules('individual_title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('individual_message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('user_list', $this->lang->line('recipient'), 'required');
        $this->form_validation->set_rules('files', $this->lang->line('file'), 'callback_multihandle_upload[files]');
        $this->form_validation->set_rules('schedule_date_time', $this->lang->line('schedule_date_time'), 'required');

        if ($this->form_validation->run()) {

            $userlisting = json_decode($this->input->post('user_list'));
            $user_array  = array();
            foreach ($userlisting as $userlisting_key => $userlisting_value) {
                $array = array(
                    'category'      => $userlisting_value[0]->category,
                    'user_id'       => $userlisting_value[0]->record_id,
                    'email'         => $userlisting_value[0]->email,
                    'guardianEmail' => $userlisting_value[0]->guardianEmail,
                    'mobileno'      => $userlisting_value[0]->mobileno,
                    'role'=>$userlisting_value[0]->category,
                );
                $user_array[] = $array;
            }

            $message       = trim($this->input->post('individual_message'));
            $message_title = $this->input->post('individual_title');
            $data          = array(
                'id'                => $this->input->post('message_id'),
                'title'             => $message_title,
                'message'           => $message,
                'email_template_id' => $this->input->post('template_id'),
                'user_list'         => json_encode($user_array),
                'created_at'        => date('Y-m-d H:i:s'),
                'sent'              => 0,
            );
            $myattachment_data = array();
            $attachments       = array();
            $new_attachments   = array();
            if (isset($_FILES['files']['name']) && !empty($_FILES['files']['name'])) {
                foreach ($_FILES['files']['name'] as $key => $files_value) {
                    $file_type = $_FILES['files']['type'][$key];
                    $file_size = $_FILES['files']["size"][$key];
                    $file_name = $_FILES['files']['name'][$key];
                    $fileInfo  = pathinfo($_FILES["files"]["name"][$key]);
                    $img_name  = time() . rand(99, 999) . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["files"]["tmp_name"][$key], "./uploads/communicate/email_attachments/" . $img_name);
                    $myattachment_data[] = array('attachment' => $img_name, 'attachment_name' => $file_name, 'directory' => 'uploads/communicate/email_attachments/');
                }
            }

            if (!empty($_POST['template_attachment'])) {

                foreach (array_unique($_POST['template_attachment']) as $key => $value) {
                    $message_list = $this->messages_model->schedule($this->input->post('message_id'));

                    if ($message_list['email_template_id'] == $this->input->post('template_id')) {
                        $checkstatus = $this->messages_model->check_email_attachment($this->input->post('message_id'), $value);
                        if (!empty($checkstatus)) {

                            if ($checkstatus->attachment == $value) {
                                $attachments[] = array('attachment' => $checkstatus->attachment, 'attachment_name' => $checkstatus->attachment_name, 'directory' => $checkstatus->directory);
                            }

                        } else {
                            $email_template_attachment_data = $this->messages_model->get_single_email_template_attachment($key);
                            $new_attachments[]              = array('attachment' => $email_template_attachment_data['attachment'], 'attachment_name' => $email_template_attachment_data['attachment_name'], 'directory' => 'uploads/communicate/email_template_images/');
                        }

                    } else {
                        $email_template_attachment_data = $this->messages_model->get_single_email_template_attachment($key);
                        $new_attachments[]              = array('attachment' => $email_template_attachment_data['attachment'], 'attachment_name' => $email_template_attachment_data['attachment_name'], 'directory' => 'uploads/communicate/email_template_images/');
                    }
                }
            }

            $attachment_data = array_merge($myattachment_data, $attachments, $new_attachments);

            $this->messages_model->delete_email_attachment($this->input->post('message_id'));

            foreach ($attachment_data as $attachment_key => $attachment_data_value) {

                $email_attachments_data['message_id']      = $this->input->post('message_id');
                $email_attachments_data['attachment']      = $attachment_data_value['attachment'];
                $email_attachments_data['attachment_name'] = $attachment_data_value['attachment_name'];
                $email_attachments_data['directory']       = $attachment_data_value['directory'];

                $this->messages_model->add_email_attachment($email_attachments_data);
            }

            $data['schedule_date_time'] = date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($this->input->post('schedule_date_time'), false));

            $data['user_list'] = json_encode($user_array);

            $this->messages_model->add($data);
            echo json_encode(array('status' => 0, 'msg' => $this->lang->line('success_message')));
        } else {

            $data = array(
                'individual_title'   => form_error('individual_title'),
                'individual_message' => form_error('individual_message'),
                'user_list'          => form_error('user_list'),
                'files'              => form_error('files'),
            );

            $data['schedule_date_time'] = form_error('schedule_date_time');

            echo json_encode(array('status' => 1, 'msg' => $data));
        }
    }

    public function update_class_schedule()
    {
        $this->form_validation->set_error_delimiters('');
        $this->form_validation->set_rules('class_title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('class_message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'required');
        $this->form_validation->set_rules('user[]', $this->lang->line('section'), 'required');
        $this->form_validation->set_rules('files', $this->lang->line('file'), 'callback_multihandle_upload[files]');

        $this->form_validation->set_rules('schedule_date_time', $this->lang->line('schedule_date_time'), 'required');

        if ($this->form_validation->run()) {

            $message       = trim($this->input->post('class_message'));
            $message_title = $this->input->post('class_title');
            $section       = $this->input->post('user[]');
            $class_id      = $this->input->post('class_id');

            $user_array = array();
            foreach ($section as $section_key => $section_value) {
                $userlisting = $this->student_model->searchByClassSection($class_id, $section_value);
                if (!empty($userlisting)) {
                    foreach ($userlisting as $userlisting_key => $userlisting_value) {
                        $array = array(
                            'user_id'  => $userlisting_value['id'],
                            'email'    => $userlisting_value['email'],
                            'mobileno' => $userlisting_value['mobileno'],
                            'role'=>'student'
                        );
                        $user_array[] = $array;
                    }
                }
            }

            $data = array(
                'id'                => $this->input->post('message_id'),
                'title'             => $message_title,
                'message'           => $message,
                'email_template_id' => $this->input->post('template_id'),
                'schedule_class'    => $class_id,
                'schedule_section'  => json_encode($section),
                'user_list'         => json_encode($user_array),
                'created_at'        => date('Y-m-d H:i:s'),
                'sent'              => 0,
            );
            $myattachment_data = array();
            $attachments       = array();
            $new_attachments   = array();
            if (isset($_FILES['files']['name']) && !empty($_FILES['files']['name'])) {
                foreach ($_FILES['files']['name'] as $key => $files_value) {
                    $file_type = $_FILES['files']['type'][$key];
                    $file_size = $_FILES['files']["size"][$key];
                    $file_name = $_FILES['files']['name'][$key];
                    $fileInfo  = pathinfo($_FILES["files"]["name"][$key]);
                    $img_name  = time() . rand(99, 999) . '.' . $fileInfo['extension'];
                    move_uploaded_file($_FILES["files"]["tmp_name"][$key], "./uploads/communicate/email_attachments/" . $img_name);

                    $myattachment_data[] = array('attachment' => $img_name, 'attachment_name' => $file_name, 'directory' => 'uploads/communicate/email_attachments/');
                }
            }

            if (!empty($_POST['template_attachment'])) {

                foreach (array_unique($_POST['template_attachment']) as $key => $value) {
                    $message_list = $this->messages_model->schedule($this->input->post('message_id'));

                    if ($message_list['email_template_id'] == $this->input->post('template_id')) {
                        $checkstatus = $this->messages_model->check_email_attachment($this->input->post('message_id'), $value);
                        if (!empty($checkstatus)) {

                            if ($checkstatus->attachment == $value) {
                                $attachments[] = array('attachment' => $checkstatus->attachment, 'attachment_name' => $checkstatus->attachment_name, 'directory' => $checkstatus->directory);
                            }

                        } else {
                            $email_template_attachment_data = $this->messages_model->get_single_email_template_attachment($key);
                            $new_attachments[]              = array('attachment' => $email_template_attachment_data['attachment'], 'attachment_name' => $email_template_attachment_data['attachment_name'], 'directory' => 'uploads/communicate/email_template_images/');
                        }

                    } else {
                        $email_template_attachment_data = $this->messages_model->get_single_email_template_attachment($key);
                        $new_attachments[]              = array('attachment' => $email_template_attachment_data['attachment'], 'attachment_name' => $email_template_attachment_data['attachment_name'], 'directory' => 'uploads/communicate/email_template_images/');
                    }
                }
            }

            $attachment_data = array_merge($myattachment_data, $attachments, $new_attachments);
            $this->messages_model->delete_email_attachment($this->input->post('message_id'));
            foreach ($attachment_data as $attachment_key => $attachment_data_value) {

                $email_attachments_data['message_id']      = $this->input->post('message_id');
                $email_attachments_data['attachment']      = $attachment_data_value['attachment'];
                $email_attachments_data['attachment_name'] = $attachment_data_value['attachment_name'];
                $email_attachments_data['directory']       = $attachment_data_value['directory'];

                $this->messages_model->add_email_attachment($email_attachments_data);
            }

            $data['schedule_date_time'] = date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($this->input->post('schedule_date_time'), false));

            $data['user_list'] = json_encode($user_array);
            $this->messages_model->add($data);
            echo json_encode(array('status' => 0, 'msg' => $this->lang->line('success_message')));
        } else {

            $data = array(
                'class_title'   => form_error('class_title'),
                'class_message' => form_error('class_message'),
                'class_id'      => form_error('class_id'),
                'user[]'        => form_error('user[]'),
                'files'         => form_error('files'),
            );

            $data['schedule_date_time'] = form_error('schedule_date_time');
            echo json_encode(array('status' => 1, 'msg' => $data));
        }
    }

    public function update_group_sms_schedule()
    {
        $this->form_validation->set_rules('group_title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('group_message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('user[]', $this->lang->line('message_to'), 'required');
        $this->form_validation->set_rules('group_send_by[]', $this->lang->line('send_through'), 'required');
        $this->form_validation->set_rules('schedule_date_time', $this->lang->line('schedule_date_time'), 'required');
        $template_id = $this->input->post('group_template_id');

        if ($this->form_validation->run()) {
            $user_array = array();
            $sms_mail   = $this->input->post('group_send_by');
            $message    = trim($this->input->post('group_message'));

            $message_title = $this->input->post('group_title');
            $data          = array(
                'id'              => $this->input->post('message_id'),
                'title'           => $message_title,
                'message'         => $message,
                'send_through'    => json_encode($sms_mail),
                'sms_template_id' => $this->input->post('template_id'),
                'group_list'      => json_encode($this->input->post('user[]')),
                'created_at'      => date('Y-m-d H:i:s'),
                'template_id'     => $template_id,
                'sent'            => 0,
            );

            $userlisting = $this->input->post('user[]');
            foreach ($userlisting as $users_key => $users_value) {
                if ($users_value == "student") {
                    $student_array = $this->student_model->get();

                    if (!empty($student_array)) {
                        foreach ($student_array as $student_key => $student_value) {

                            $array = array(
                                'user_id'  => $student_value['id'],
                                'email'    => $student_value['email'],
                                'mobileno' => $student_value['mobileno'],
                                'app_key'  => $student_value['app_key'],
                                'role'=>'student'
                            );
                            $user_array[] = $array;
                        }
                    }
                } else if ($users_value == "parent") {
                    $parent_array = $this->student_model->get();
                    if (!empty($parent_array)) {
                        foreach ($parent_array as $parent_key => $parent_value) {
                            $array = array(
                                'user_id'  => $parent_value['id'],
                                'email'    => $parent_value['guardian_email'],
                                'mobileno' => $parent_value['guardian_phone'],
                                'app_key'  => $parent_value['parent_app_key'],
                                'role'=>'parent'
                            );
                            $user_array[] = $array;
                        }
                    }
                } else if (is_numeric($users_value)) {

                    $staff = $this->staff_model->getEmployeeByRoleID($users_value);
                    if (!empty($staff)) {
                        foreach ($staff as $staff_key => $staff_value) {
                            $array = array(
                                'user_id'  => $staff_value['id'],
                                'email'    => $staff_value['email'],
                                'mobileno' => $staff_value['contact_no'],
                                'role'=>'staff'
                            );
                            $user_array[] = $array;
                        }
                    }
                }
            }

            $data['schedule_date_time'] = date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($this->input->post('schedule_date_time'), false));

            $data['user_list'] = json_encode($user_array);
            $this->messages_model->add($data);
            echo json_encode(array('status' => 0, 'msg' => $this->lang->line('success_message')));
        } else {

            $data = array(
                'group_title'     => form_error('group_title'),
                'group_send_by[]' => form_error('group_send_by[]'),
                'group_message'   => form_error('group_message'),
                'user[]'          => form_error('user[]'),
            );

            $data['schedule_date_time'] = form_error('schedule_date_time');
            echo json_encode(array('status' => 1, 'msg' => $data));
        }
    }

    public function update_individual_sms_schedule()
    {
        $this->form_validation->set_rules('individual_title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('individual_message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('user_list', $this->lang->line('recipient'), 'required');
        $this->form_validation->set_rules('individual_send_by[]', $this->lang->line('send_through'), 'required');
        $this->form_validation->set_rules('schedule_date_time', $this->lang->line('schedule_date_time'), 'required');
        $template_id = $this->input->post('individual_template_id');

        if ($this->form_validation->run()) {

            $userlisting = json_decode($this->input->post('user_list'));
            $user_array  = array();
            foreach ($userlisting as $userlisting_key => $userlisting_value) {
                $array = array(
                    'category'      => $userlisting_value[0]->category,
                    'user_id'       => $userlisting_value[0]->record_id,
                    'email'         => $userlisting_value[0]->email,
                    'guardianEmail' => $userlisting_value[0]->guardianEmail,
                    'mobileno'      => $userlisting_value[0]->mobileno,
                    'app_key'       => $userlisting_value[0]->app_key,
                    'role'=>$userlisting_value[0]->category,
                );
                $user_array[] = $array;
            }

            $sms_mail = $this->input->post('individual_send_by');

            $message       = $this->input->post('individual_message');
            $message_title = $this->input->post('individual_title');
            $data          = array(
                'id'              => $this->input->post('message_id'),
                'title'           => $message_title,
                'message'         => $message,
                'send_through'    => json_encode($sms_mail),
                'sms_template_id' => $this->input->post('template_id'),
                'group_list'      => json_encode($this->input->post('user[]')),
                'template_id'     => $template_id,
                'created_at'      => date('Y-m-d H:i:s'),
                'sent'            => 0,
            );

            $data['schedule_date_time'] = date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($this->input->post('schedule_date_time'), false));

            $data['user_list'] = json_encode($user_array);
            $this->messages_model->add($data);
            echo json_encode(array('status' => 0, 'msg' => $this->lang->line('success_message')));
        } else {

            $data = array(
                'individual_title'     => form_error('individual_title'),
                'individual_send_by[]' => form_error('individual_send_by[]'),
                'individual_message'   => form_error('individual_message'),
                'user_list'            => form_error('user_list'),
            );

            $data['schedule_date_time'] = form_error('schedule_date_time');

            echo json_encode(array('status' => 1, 'msg' => $data));
        }
    }

    public function update_class_sms_schedule()
    {
        $this->form_validation->set_rules('class_title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('class_message', $this->lang->line('message'), 'required');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'required');
        $this->form_validation->set_rules('user[]', $this->lang->line('recipient'), 'required');
        $this->form_validation->set_rules('class_send_by[]', $this->lang->line('send_through'), 'required');
        $this->form_validation->set_rules('schedule_date_time', $this->lang->line('schedule_date_time'), 'required');

        $template_id = $this->input->post('class_template_id');
        if ($this->form_validation->run()) {

            $sms_mail      = $this->input->post('class_send_by');
            $message       = $this->input->post('class_message');
            $message_title = $this->input->post('class_title');
            $section       = $this->input->post('user[]');
            $class_id      = $this->input->post('class_id');

            $user_array = array();
            foreach ($section as $section_key => $section_value) {
                $userlisting = $this->student_model->searchByClassSection($class_id, $section_value);
                if (!empty($userlisting)) {
                    foreach ($userlisting as $userlisting_key => $userlisting_value) {
                        $array = array(
                            'user_id'  => $userlisting_value['id'],
                            'email'    => $userlisting_value['email'],
                            'mobileno' => $userlisting_value['mobileno'],
                            'app_key'  => $userlisting_value['app_key'],
                            'role'=>'student'
                        );
                        $user_array[] = $array;
                    }
                }
            }

            $data = array(
                'id'               => $this->input->post('message_id'),
                'title'            => $message_title,
                'message'          => $message,
                'send_through'     => json_encode($sms_mail),
                'sms_template_id'  => $this->input->post('template_id'),
                'group_list'       => json_encode($this->input->post('user[]')),
                'template_id'      => $template_id,
                'schedule_class'   => $class_id,
                'schedule_section' => json_encode($section),
                'user_list'        => json_encode($user_array),
                'created_at'       => date('Y-m-d H:i:s'),
                'sent'             => 0,
            );

            $data['schedule_date_time'] = date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($this->input->post('schedule_date_time'), false));

            $data['user_list'] = json_encode($user_array);
            $this->messages_model->add($data);
            echo json_encode(array('status' => 0, 'msg' => $this->lang->line('success_message')));
        } else {

            $data = array(
                'class_title'     => form_error('class_title'),
                'class_send_by[]' => form_error('class_send_by[]'),
                'class_message'   => form_error('class_message'),
                'class_id'        => form_error('class_id'),
                'user[]'          => form_error('user[]'),
            );

            $data['schedule_date_time'] = form_error('schedule_date_time');

            echo json_encode(array('status' => 1, 'msg' => $data));
        }
    }

    public function schedule_templatedata()
    {
        $message_id      = $this->input->post('message_id');
        $attachment_list = $this->messages_model->get_email_attachment($message_id);

        $attachment_output = '';
        if (!empty($attachment_list)) {
            foreach ($attachment_list as $key => $value) {
                $attachment_output .= $this->genrateDiv($value->attachment_name, $value->attachment, '');
            }
        }

        echo json_encode(array('attachment_list' => $attachment_output));
    }

    public function delete_schedule()
    {
        if (!$this->rbac->hasPrivilege('schedule_email_sms_log', 'can_delete')) {
            access_denied();
        }
        $message_id = $this->input->post('message_id');
        $this->messages_model->remove($message_id);
        echo json_encode(array('status' => '1', 'message' => $this->lang->line('delete_message')));
    }
}
