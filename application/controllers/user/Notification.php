<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Notification extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'notification');
        $data['title'] = 'Notifications';
        $user_role     = $this->customlib->getUserRole();
        if ($user_role == 'student') {
            $student_id    = $this->customlib->getStudentSessionUserID();
            $notifications = $this->notification_model->getNotificationForStudent($student_id);
        } elseif ($user_role == 'parent') {
            $student_id    = $this->customlib->getUsersID();
            $notifications = $this->notification_model->getNotificationForParent($student_id);
        }
        $notification_bydate = array();
        foreach ($notifications as $key => $value) {

            if (strtotime(date('Y-m-d')) >= strtotime($value['publish_date'])) {
                $notification_bydate[] = $value;
            }
        }

        $data['notificationlist'] = $notification_bydate;
        $this->load->view('layout/student/header', $data);
        $this->load->view('user/notification/notificationList', $data);
        $this->load->view('layout/student/footer', $data);
    }

    public function updatestatus()
    {
        $notification_id = $this->input->post('notification_id');

        $user_role = $this->customlib->getUserRole();
        if ($user_role == 'student') {
            $student_id = $this->customlib->getStudentSessionUserID();
            $data       = $this->notification_model->updateStatus($notification_id, $student_id);
        } elseif ($user_role == 'parent') {
            $parent_id = $this->customlib->getUsersID();
            $data      = $this->notification_model->updateStatusforParent($notification_id, $parent_id);
        }

        $array = array('status' => "success", 'data' => $data);
        echo json_encode($array);
    }

    public function read()
    {
        $array           = array('status' => "fail", 'msg' => $this->lang->line('something_went_wrong'));
        $notification_id = $this->input->post('notice');
        if ($notification_id != "") {
            $student_id = $this->customlib->getStudentSessionUserID();
            $data       = $this->notification_model->updateStatusforStudent($notification_id, $student_id);
            $array      = array('status' => "success", 'data' => $data, 'msg' => $this->lang->line('delete_message'));
        }

        echo json_encode($array);
    }
    
    public function download($id)
    {       
        $notification = $this->notification_model->notification($id);
        $this->media_storage->filedownload($notification['attachment'], "uploads/notice_board_images");       
    }

    public function notification()
    {
        $settingresult           = $this->setting_model->getSetting();
        $superadmin_restriction  = $settingresult->superadmin_restriction;
        //------------------------------------------------        
        
        $message_id               = $this->input->post('message_id');
        $notificationlist         = $this->notification_model->notification($message_id);                 
        
        if ($superadmin_restriction == 'disabled') {
            $staff = $this->staff_model->get($notificationlist['staff_id']); 
            if ($staff['role_id'] != 7) {
                 $notificationlist['created_by'] = ($notificationlist['surname'] != "") ? $notificationlist["name"] . " " . $notificationlist["surname"] . "  (" . $notificationlist["employee_id"] . ")" : $notificationlist["name"] . " (" . $notificationlist['employee_id'] . ")";
            } else {
                 $notificationlist['created_by'] = '';
            }  
        } else { 
            $notificationlist['created_by'] = ($notificationlist['surname'] != "") ? $notificationlist["name"] . " " . $notificationlist["surname"] . "  (" . $notificationlist["employee_id"] . ")" : $notificationlist["name"] . " (" . $notificationlist['employee_id'] . ")";            
        } 
        
        $data['notificationlist'] = $notificationlist;
        
        $page                     = $this->load->view('user/notification/_notification', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }
    
    
}
