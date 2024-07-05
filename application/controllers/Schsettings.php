<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Schsettings extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->load->library('upload');
        $this->load->model(array('class_section_time_model','sidebarmenu_model'));
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('general_setting', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'schsettings/index');
        $this->session->set_userdata('subsub_menu', 'schsettings/index');

        $timezoneList             = $this->customlib->timezone_list();
        $session_result           = $this->session_model->get();
        $data['sessionlist']      = $session_result;
        $currency_formats         = $this->customlib->currency_format();
        $month_list               = $this->customlib->getMonthList();
        $days_list                = $this->customlib->getDayList();
        $data['currency_formats'] = $currency_formats;
        $data['daysList']         = $days_list;
        $data['timezoneList']     = $timezoneList;
        $data['monthList']        = $month_list;
        $dateFormat               = $this->customlib->getDateFormat();
        $currency                 = $this->customlib->getCurrency();
        $data['dateFormatList']   = $dateFormat;
        $data['currencyList']     = $currency;
        $currencyPlace            = $this->customlib->getCurrencyPlace();
        $data['currencyPlace']    = $currencyPlace;
        $setting                  = $this->setting_model->getSetting();
        $setting->base_url        = ($setting->base_url == "") ? base_url() : $setting->base_url;
        $setting->folder_path     = ($setting->folder_path == "") ? FCPATH : $setting->folder_path;
        $data['result']           = $setting;
        $this->load->view('layout/header', $data);
        $this->load->view('setting/settingList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function ajax_editlogo()
    {
        $this->form_validation->set_rules('id', $this->lang->line('id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');
        if ($this->form_validation->run() == false) {
            $data = array(
                'file' => form_error('file'),
            );
            $array = array('success' => false, 'error' => $data);
            echo json_encode($array);
        } else {
            $id = $this->input->post('id');

            $setting = $this->setting_model->getSetting();

            if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {

                $img_name = $this->media_storage->fileupload("file", "./uploads/school_content/logo/");
            } else {
                $img_name = $setting->image;
            }
            if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {

                $this->media_storage->filedelete($setting->image, "uploads/school_content/logo");
            }

            $data_record = array('id' => $id, 'image' => $img_name);
            $this->setting_model->add($data_record);
            $array = array('success' => true, 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function ajax_editadmin_smalllogo()
    {
        $this->form_validation->set_rules('id', $this->lang->line('id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');
        if ($this->form_validation->run() == false) {
            $data = array(
                'file' => form_error('file'),
            );
            $array = array('success' => false, 'error' => $data);
            echo json_encode($array);
        } else {
            $id = $this->input->post('id');

            $setting = $this->setting_model->getSetting();

            if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {

                $img_name = $this->media_storage->fileupload("file", "./uploads/school_content/admin_small_logo/");
            } else {
                $img_name = $setting->admin_small_logo;
            }
            if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {

                $this->media_storage->filedelete($setting->admin_small_logo, "uploads/school_content/admin_small_logo");
            }
            $data_record = array('id' => $id, 'admin_small_logo' => $img_name);
            $this->setting_model->add($data_record);
            $array = array('success' => true, 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function ajax_editadmin_adminlogo()
    {
        $this->form_validation->set_rules('id', $this->lang->line('id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');
        if ($this->form_validation->run() == false) {
            $data = array(
                'file' => form_error('file'),
            );
            $array = array('success' => false, 'error' => $data);
            echo json_encode($array);
        } else {
            $id = $this->input->post('id');

            $setting = $this->setting_model->getSetting();

            if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {

                $img_name = $this->media_storage->fileupload("file", "./uploads/school_content/admin_logo/");
            } else {
                $img_name = $setting->admin_logo;
            }
            if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {
                if ($setting->admin_logo != '') {
                    $this->media_storage->filedelete($setting->admin_logo, "uploads/school_content/admin_logo");
                }
            }

            $data_record = array('id' => $id, 'admin_logo' => $img_name);
            $this->setting_model->add($data_record);
            $array = array('success' => true, 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function editLogo($id)
    {
        $data['title']       = 'School Logo';
        $setting_result      = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $data['id']          = $id;
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('setting/editLogo', $data);
            $this->load->view('layout/footer', $data);
        } else {
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/school_content/logo/" . $img_name);
            }
            $data_record = array('id' => $id, 'image' => $img_name);
            $this->setting_model->add($data_record);
            $this->session->set_flashdata('msg', '<div class="alert alert-left">' . $this->lang->line('update_message') . '</div>');
            redirect('schsettings/index');
        }
    }

    public function handle_upload()
    {   
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $allowedExts = array('jpg', 'jpeg', 'png');
            $temp        = explode(".", $_FILES["file"]["name"]);
            $extension   = end($temp);            
            
            if ($_FILES["file"]["error"] > 0) {
                
                $error .= "Error opening the file<br />";
                
            }
            if ($_FILES["file"]["type"] != 'image/gif' &&
                $_FILES["file"]["type"] != 'image/jpeg' &&
                $_FILES["file"]["type"] != 'image/png') {
                
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }
            if (!in_array($extension, $allowedExts)) {
               
                $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($_FILES["file"]["size"] > 1024000) {
                
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . " 1MB");
                return false;
            }
            return true;
        } else {
            $this->form_validation->set_message('handle_upload', $this->lang->line('logo_file_is_required'));
            return false;
        }
    }

    public function view($id)
    {
        $data['title']   = 'Setting List';
        $setting         = $this->setting_model->get($id);
        $data['setting'] = $setting;
        $this->load->view('layout/header', $data);
        $this->load->view('setting/settingShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getSchsetting()
    {
        $data = $this->setting_model->getSetting();
        echo json_encode($data);
    }

    public function generalsetting()
    {
        $this->form_validation->set_rules('currency_format', $this->lang->line('currency_format'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('sch_session_id', $this->lang->line('session'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('sch_name', $this->lang->line('school_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('sch_phone', $this->lang->line('phone'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('sch_start_month', $this->lang->line('start_month'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('sch_address', $this->lang->line('address'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('sch_email', $this->lang->line('email'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('sch_timezone', $this->lang->line('timezone'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('currency_place', $this->lang->line('currency_place'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('sch_date_format', $this->lang->line('date_format'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('sch_start_week', $this->lang->line('start_day_of_week'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('base_url', $this->lang->line('url'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('folder_path', $this->lang->line('folder_path'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'sch_session_id'  => form_error('sch_session_id'),
                'sch_name'        => form_error('sch_name'),
                'sch_phone'       => form_error('sch_phone'),
                'sch_start_month' => form_error('sch_start_month'),
                'sch_start_week'  => form_error('sch_start_week'),
                'sch_address'     => form_error('sch_address'),
                'sch_email'       => form_error('sch_email'),
                'sch_timezone'    => form_error('sch_timezone'),
                'currency_place'  => form_error('currency_place'),
                'currency_format' => form_error('currency_format'),
                'sch_date_format' => form_error('sch_date_format'),
                'base_url'        => form_error('base_url'),
                'folder_path'     => form_error('folder_path'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {

            $data = array(
                'id'              => $this->input->post('sch_id'),
                'session_id'      => $this->input->post('sch_session_id'),
                'name'            => $this->input->post('sch_name'),
                'phone'           => $this->input->post('sch_phone'),
                'dise_code'       => $this->input->post('sch_dise_code'),
                'start_month'     => $this->input->post('sch_start_month'),
                'start_week'      => $this->input->post('sch_start_week'),
                'address'         => $this->input->post('sch_address'),
                'email'           => $this->input->post('sch_email'),
                'timezone'        => $this->input->post('sch_timezone'),
                'date_format'     => $this->input->post('sch_date_format'),
                'currency_format' => $this->input->post('currency_format'),
                'currency_place'  => $this->input->post('currency_place'),
                'base_url'        => $this->input->post('base_url'),
                'folder_path'     => $this->input->post('folder_path'),
            );

            $this->setting_model->add($data);

            $this->session->userdata['admin']['base_url']        = $this->input->post('base_url');
            $this->session->userdata['admin']['folder_path']     = $this->input->post('folder_path');
            $this->session->userdata['admin']['currency_format'] = $this->input->post('currency_format');
            $this->session->userdata['admin']['date_format']     = $this->input->post('sch_date_format');
            $this->session->userdata['admin']['start_week']      = date("w", strtotime($this->input->post('sch_start_week')));
            $this->session->userdata['admin']['timezone']        = $this->input->post('sch_timezone');
            $this->session->userdata['admin']['currency_place']  = $this->input->post('currency_place');
            $array                                               = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function ajax_applogo()
    {
        $this->form_validation->set_rules('id', $this->lang->line('id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');

        if ($this->form_validation->run() == false) {
            $data = array(
                'file' => form_error('file'),
            );
            $array = array('success' => false, 'error' => $data);
            echo json_encode($array);
        } else {

            $id      = $this->input->post('id');
            $setting = $this->setting_model->getSetting();

            if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {

                $img_name = $this->media_storage->fileupload("file", "./uploads/school_content/logo/app_logo//");
            } else {
                $img_name = $setting->app_logo;
            }
            if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {
                if ($setting->app_logo != '') {
                    $this->media_storage->filedelete($setting->app_logo, "uploads/school_content/logo/app_logo/");
                }
            }

            $data_record = array('id' => $id, 'app_logo' => $img_name);

            $this->setting_model->add($data_record);
            $array = array('success' => true, 'error' => '', 'message' => $this->lang->line('update_message'));
            echo json_encode($array);
        }
    }

    public function check_admission_digit()
    {
        $adm_start_from = $this->input->post('adm_start_from');
        $adm_no_digit   = $this->input->post('adm_no_digit');
        if ($adm_no_digit != "") {
            if (strlen($adm_start_from) == $adm_no_digit) {
                return true;
            }
            $this->form_validation->set_message('check_admission_digit', $this->lang->line('admission_start_from') . ' ' . $adm_no_digit . ' ' . $this->lang->line('digit_long'));
            return false;
        }
        return true;
    }

    public function check_staff_id_digit()
    {
        $adm_start_from   = $this->input->post('staffid_start_from');
        $staffid_no_digit = $this->input->post('staffid_no_digit');
        if ($staffid_no_digit != "") {
            if (strlen($adm_start_from) == $staffid_no_digit) {
                return true;
            }
            $this->form_validation->set_message('check_staff_id_digit', $this->lang->line('staff_id_start_from_must_be') . ' ' . strlen($adm_start_from) . ' ' . $this->lang->line('digit_long'));
            return false;
        }
        return true;
    }

    public function logo()
    {        
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'schsettings/index');
        $this->session->set_userdata('subsub_menu', 'schsettings/logo');
    
        $setting              = $this->setting_model->getSetting();
        $data['result']       = $setting;
        $this->load->view('layout/header');
        $this->load->view('setting/logo', $data);
        $this->load->view('layout/footer');
    }

    public function miscellaneous()
    {
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'schsettings/index');
        $this->session->set_userdata('subsub_menu', 'schsettings/miscellaneous');
        $setting              = $this->setting_model->getSetting();
        $setting->base_url    = ($setting->base_url == "") ? base_url() : $setting->base_url;
        $setting->folder_path = ($setting->folder_path == "") ? FCPATH : $setting->folder_path;
        $data['result']       = $setting;
        $this->load->view('layout/header');
        $this->load->view('setting/miscellaneous', $data);
        $this->load->view('layout/footer');
    }

    public function savemiscellaneous()
    {
        $event_reminder = $this->input->post('event_reminder');
        if ($event_reminder == 'enabled') {
            $calendar_event_reminder = $this->input->post('calendar_event_reminder');
        } else {
            $calendar_event_reminder = '0';
        }

        $data = array(
            'id'                       => $this->input->post('sch_id'),
            'my_question'              => $this->input->post('my_question'),
            'exam_result'              => $this->input->post('exam_result'),
            'class_teacher'            => $this->input->post('class_teacher'),
            'superadmin_restriction'   => $this->input->post('superadmin_restriction_mode'),
            'calendar_event_reminder'  => $calendar_event_reminder,
            'event_reminder'           => $this->input->post('event_reminder'),
            'staff_notification_email' => $this->input->post('staff_notification_email'),
            'scan_code_type' => $this->input->post('scan_code_type')
        );

        $this->setting_model->add($data);
        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        echo json_encode($array);

    }

    public function backendtheme()
    {
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'schsettings/index');
        $this->session->set_userdata('subsub_menu', 'schsettings/backendtheme');
        $setting              = $this->setting_model->getSetting();
        $setting->base_url    = ($setting->base_url == "") ? base_url() : $setting->base_url;
        $setting->folder_path = ($setting->folder_path == "") ? FCPATH : $setting->folder_path;
        $data['result']       = $setting;
        $this->load->view('layout/header');
        $this->load->view('setting/backendtheme', $data);
        $this->load->view('layout/footer');
    }

    public function savebackendtheme()
    {
        $this->form_validation->set_rules('theme', $this->lang->line('theme'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'theme' => form_error('theme'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {

            $data = array(
                'id'    => $this->input->post('sch_id'),
                'theme' => $this->input->post('theme'),
            );

            $this->setting_model->add($data);
            $this->session->userdata['admin']['theme'] = $this->input->post('theme');
            $array                                     = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function mobileapp()
    {
        $app_ver = $this->config->item('app_ver');
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'schsettings/index');
        $this->session->set_userdata('subsub_menu', 'schsettings/mobileapp');
        $setting              = $this->setting_model->getSetting();
        $setting->base_url    = ($setting->base_url == "") ? base_url() : $setting->base_url;
        $setting->folder_path = ($setting->folder_path == "") ? FCPATH : $setting->folder_path;
        $data['result']       = $setting;
        $data['app_response'] = $this->auth->andapp_validate();
        $this->load->view('layout/header');
        $this->load->view('setting/mobileapp', $data);
        $this->load->view('layout/footer');
    }

    public function savemobileapp()
    {
        $data = array(
            'id'                             => $this->input->post('sch_id'),
            'mobile_api_url'                 => $this->input->post('mobile_api_url'),
            'app_primary_color_code'         => $this->input->post('app_primary_color_code'),
            'app_secondary_color_code'       => $this->input->post('app_secondary_color_code'),
            'admin_app_primary_color_code'   => $this->input->post('admin_app_primary_color_code'),
            'admin_app_secondary_color_code' => $this->input->post('admin_app_secondary_color_code'),
            'admin_mobile_api_url'           => $this->input->post('admin_mobile_api_url'),
        );

        $this->setting_model->add($data);
        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        echo json_encode($array);
    }

    public function studentguardianpanel()
    {
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'schsettings/index');
        $this->session->set_userdata('subsub_menu', 'schsettings/studentguardianpanel');
        $setting              = $this->setting_model->getSetting();
        $setting->base_url    = ($setting->base_url == "") ? base_url() : $setting->base_url;
        $setting->folder_path = ($setting->folder_path == "") ? FCPATH : $setting->folder_path;
        $data['result']       = $setting;
        $this->load->view('layout/header');
        $this->load->view('setting/studentguardianpanel', $data);
        $this->load->view('layout/footer');
    }

    public function studentguardian()
    {
        $parent_panel_login  = 0;
        $student_panel_login = 0;

        if (isset($_POST['student_panel_login'])) {
            $student_panel_login = 1;
            if (isset($_POST['parent_panel_login'])) {
                $parent_panel_login = 1;
            }
        }

        $data = array(
            'id'                  => $this->input->post('sch_id'),
            'student_timeline'    => $this->input->post('student_timeline'),
            'student_login'       => json_encode($this->input->post('student_login')),
            'parent_login'        => json_encode($this->input->post('parent_login')),
            'student_panel_login' => $student_panel_login,
            'parent_panel_login'  => $parent_panel_login,
        );

        $this->setting_model->add($data);

        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        echo json_encode($array);
    }

    public function fees()
    {
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'schsettings/index');
        $this->session->set_userdata('subsub_menu', 'schsettings/fees');

        $setting                        = $this->setting_model->getSetting();
        $setting->base_url              = ($setting->base_url == "") ? base_url() : $setting->base_url;
        $setting->folder_path           = ($setting->folder_path == "") ? FCPATH : $setting->folder_path;
        $data['result']                 = $setting;
        $data['duplicate_fees_invoice'] = explode(",", $setting->is_duplicate_fees_invoice);
        $this->load->view('layout/header');
        $this->load->view('setting/fees', $data);
        $this->load->view('layout/footer');
    }

    public function savefees()
    {
        $this->form_validation->set_rules('is_student_feature_lock', $this->lang->line('is_student_feature_lock'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('is_offline_fee_payment', $this->lang->line('offline_bank_payment_in_student_panel'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('is_duplicate_fees_invoice[]', $this->lang->line('print_fees_receipt_for'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('lock_grace_period', $this->lang->line('fees_payment_grace_period'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('fee_due_days', $this->lang->line('carry_forward_fees_due_days'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('single_page_print', $this->lang->line('single_page_print'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'is_duplicate_fees_invoice' => form_error('is_duplicate_fees_invoice[]'),
                'single_page_print'         => form_error('single_page_print'),
                'fee_due_days'              => form_error('fee_due_days'),
                'lock_grace_period'         => form_error('lock_grace_period'),
                'is_student_feature_lock'   => form_error('is_student_feature_lock'),
                'is_offline_fee_payment'   => form_error('is_offline_fee_payment'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {

            $is_duplicate_fees_invoice = implode(",", $this->input->post('is_duplicate_fees_invoice'));
            $data                      = array(
                'id'                        => $this->input->post('sch_id'),
                'is_duplicate_fees_invoice' => $is_duplicate_fees_invoice,
                'single_page_print'         => $this->input->post('single_page_print'),
                'fee_due_days'              => $this->input->post('fee_due_days'),
                'lock_grace_period'         => $this->input->post('lock_grace_period'),
                'collect_back_date_fees'    => $this->input->post('collect_back_date_fees'),
                'is_student_feature_lock'   => $this->input->post('is_student_feature_lock'),
                'is_offline_fee_payment'    => $this->input->post('is_offline_fee_payment'),
                'offline_bank_payment_instruction'  => $this->input->post('offline_bank_payment_instruction'),
            );

            $this->setting_model->add($data);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function idautogeneration()
    {
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'schsettings/index');
        $this->session->set_userdata('subsub_menu', 'schsettings/idautogeneration');

        $digit                = $this->customlib->getDigits();
        $data['digitList']    = $digit;
        $setting              = $this->setting_model->getSetting();
        $setting->base_url    = ($setting->base_url == "") ? base_url() : $setting->base_url;
        $setting->folder_path = ($setting->folder_path == "") ? FCPATH : $setting->folder_path;
        $data['result']       = $setting;
        $this->load->view('layout/header');
        $this->load->view('setting/idautogeneration', $data);
        $this->load->view('layout/footer');
    }

    public function saveidautogeneration()
    {
        $this->form_validation->set_rules('sch_id', 'Id', 'trim|required|xss_clean');

        if ($this->input->post('adm_auto_insert')) {
            $this->form_validation->set_rules('adm_prefix', $this->lang->line('admission_no_prefix'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('adm_start_from', $this->lang->line('admission_start_from'), 'trim|integer|required|xss_clean');
            $this->form_validation->set_rules('adm_no_digit', $this->lang->line('admission_no_digit'), 'trim|integer|required|xss_clean|callback_check_admission_digit');
        }
        if ($this->input->post('staffid_auto_insert')) {

            $this->form_validation->set_rules('staffid_prefix', $this->lang->line('staff_id_prefix'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('staffid_start_from', $this->lang->line('staff_id_start_from'), 'trim|integer|required|xss_clean');
            $this->form_validation->set_rules('staffid_no_digit', $this->lang->line('staff_id_digit'), 'trim|integer|required|xss_clean|callback_check_staff_id_digit');
        }

        if ($this->form_validation->run() == false) {
            $data = array(
                'adm_start_from'     => form_error('adm_start_from'),
                'adm_prefix'         => form_error('adm_prefix'),
                'adm_no_digit'       => form_error('adm_no_digit'),
                'staffid_start_from' => form_error('staffid_start_from'),
                'staffid_prefix'     => form_error('staffid_prefix'),
                'staffid_no_digit'   => form_error('staffid_no_digit'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $setting_result = $this->setting_model->getSetting();

            $data = array(
                'id'                  => $this->input->post('sch_id'),
                'adm_start_from'      => $this->input->post('adm_start_from'),
                'adm_prefix'          => $this->input->post('adm_prefix'),
                'adm_no_digit'        => $this->input->post('adm_no_digit'),
                'adm_auto_insert'     => $this->input->post('adm_auto_insert'),
                'staffid_start_from'  => $this->input->post('staffid_start_from'),
                'staffid_prefix'      => $this->input->post('staffid_prefix'),
                'staffid_no_digit'    => $this->input->post('staffid_no_digit'),
                'staffid_auto_insert' => $this->input->post('staffid_auto_insert'),
            );

            $data['adm_update_status']     = 1;
            $data['staffid_update_status'] = 1;
            if ($this->input->post('adm_auto_insert')) {
                if ($setting_result->adm_prefix != $this->input->post('adm_prefix') ||
                    $setting_result->adm_start_from != $this->input->post('adm_start_from') ||
                    $setting_result->adm_no_digit != $this->input->post('adm_no_digit')
                ) {
                    $data['adm_update_status'] = 0;
                }
            }

            if ($this->input->post('staffid_auto_insert')) {
                if ($setting_result->staffid_prefix != $this->input->post('staffid_prefix') ||
                    $setting_result->staffid_start_from != $this->input->post('staffid_start_from') ||
                    $setting_result->staffid_no_digit != $this->input->post('staffid_no_digit')
                ) {
                    $data['staffid_update_status'] = 0;
                }
            }

            $data['adm_update_status'];
            $this->setting_model->add($data);

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function attendancetype()
    {
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'schsettings/index');
        $this->session->set_userdata('subsub_menu', 'schsettings/attendancetype');

        $class_list=$this->class_section_time_model->allClassSections();
        $data['class_list']=$class_list;

        $setting              = $this->setting_model->getSetting();
        $setting->base_url    = ($setting->base_url == "") ? base_url() : $setting->base_url;
        $setting->folder_path = ($setting->folder_path == "") ? FCPATH : $setting->folder_path;
        $data['result']       = $setting;
        $this->load->view('layout/header', $data);
        $this->load->view('setting/attendancetype', $data);
        $this->load->view('layout/footer', $data);
    }

    public function maintenance()
    {
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'schsettings/index');
        $this->session->set_userdata('subsub_menu', 'schsettings/maintenance');

        $setting              = $this->setting_model->getSetting();
        $setting->base_url    = ($setting->base_url == "") ? base_url() : $setting->base_url;
        $setting->folder_path = ($setting->folder_path == "") ? FCPATH : $setting->folder_path;
        $data['result']       = $setting;
        $this->load->view('layout/header', $data);
        $this->load->view('setting/maintenance', $data);
        $this->load->view('layout/footer', $data);
    }

    public function saveattendancetype()
    {
        $this->form_validation->set_rules('attendence_type', $this->lang->line('attendance_type'), 'trim|required|xss_clean');        

        if ($this->form_validation->run() == false) {
            $data = array(
                'attendence_type' => form_error('attendence_type'),
                 
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $data = array(
                'id'               => $this->input->post('sch_id'),
                'attendence_type'  => $this->input->post('attendence_type'),
                'biometric_device' => $this->input->post('biometric_device'),
                'biometric'        => $this->input->post('biometric'),
                'low_attendance_limit' => $this->input->post('low_attendance_limit'),
            );
            
            $this->setting_model->add($data);
                    $period_attendance=0;
                    $student_attendance=1;
                     if($this->input->post('attendence_type')){
                          $period_attendance=1;
                          $student_attendance=0;
                     }

              $this->sidebarmenu_model->update_submenu_by_key(
                  [
                      ['key'=>'period_attendance_by_date','is_active'=>$period_attendance],
                      ['key'=>'period_attendance','is_active'=>$period_attendance],
                      ['key'=>'student_attendance','is_active'=>$student_attendance],
                      ['key'=>'attendance_by_date','is_active'=>$student_attendance]
                  ]
                );

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function save_maintenance()
    {
        $this->form_validation->set_rules('maintenance_mode', $this->lang->line('maintenance_mode'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'maintenance_mode' => form_error('maintenance_mode'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $data = array(
                'id'               => $this->input->post('sch_id'),
                'maintenance_mode' => $this->input->post('maintenance_mode'),
            );
            $this->setting_model->add($data);

            $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }
    
    public function login_page_background()
    {        
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'schsettings/index');
        $this->session->set_userdata('subsub_menu', 'schsettings/login_page_background');
    
        $setting              = $this->setting_model->getSetting();
        $data['result']       = $setting;
        $this->load->view('layout/header');
        $this->load->view('setting/login_page_background', $data);
        $this->load->view('layout/footer');
    }
    
    public function add_admin_login_background()
    {
        $this->form_validation->set_rules('id', $this->lang->line('id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');
        if ($this->form_validation->run() == false) {
            $data = array(
                'file' => form_error('file'),
            );
            $array = array('success' => false, 'error' => $data);
            echo json_encode($array);
        } else {
            $id = $this->input->post('id');
            $logo_type = $this->input->post('logo_type');
 
            $setting = $this->setting_model->getSetting();
            if($logo_type != 'admin_logo'){                
                $background =   $setting->user_login_page_background;
            }else {
                $background =   $setting->admin_login_page_background;
            }
            
            if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {
                $img_name = $this->media_storage->fileupload("file", "./uploads/school_content/login_image/");
            } else {
                $img_name = $background;
            }
            
            if (isset($background)) {
                $this->media_storage->filedelete($background, "uploads/school_content/login_image");
            }
            
            if($logo_type != 'admin_logo'){                
                $data_record = array('id' => $id, 'user_login_page_background' => $img_name);
            }else {                 
                $data_record = array('id' => $id, 'admin_login_page_background' => $img_name);
            }          
            
            $this->setting_model->add($data_record);
            $array = array('success' => true, 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }
    
}
