<?php

define('THEMES_DIR', 'themes');
define('BASE_URI', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));

class MY_Controller extends CI_Controller
{

    protected $langs = array();

    public function __construct()
    {

        parent::__construct();

        $this->load->library('Db_manager');
        $this->config->load('license');
        $this->load->helper(array('language', 'directory', 'customfield', 'custom', 'mime'));
        $this->load->model(array('session_model', 'staff_model', 'section_model', 'setting_model', 'class_model', 'classsection_model', 'category_model', 'student_model', 'feemaster_model', 'feecategory_model', 'feetype_model', 'studentfee_model', 'stuattendence_model', 'attendencetype_model', 'studentsession_model', 'language_model', 'admin_model', 'smsconfig_model', 'langpharses_model', 'subject_model', 'teacher_model', 'teachersubject_model', 'exam_model', 'mark_model', 'examschedule_model', 'examresult_model', 'expense_model', 'expensehead_model', 'studenttransportfee_model', 'book_model', 'grade_model', 'timetable_model', 'hostel_model', 'route_model', 'content_model', 'user_model', 'notification_model', 'paymentsetting_model', 'payroll_model', 'roomtype_model', 'department_model', 'designation_model', 'hostelroom_model', 'vehicle_model', 'vehroute_model', 'librarian_model', 'accountant_model', 'homework_model', 'librarymanagement_model', 'librarymember_model', 'bookissue_model', 'feegroup_model', 'feegrouptype_model', 'feesessiongroup_model', 'studentfeemaster_model', 'feediscount_model', 'emailconfig_model', 'income_model', 'incomehead_model', 'itemcategory_model', 'schoolhouse_model', 'item_model', 'messages_model', 'itemstore_model', 'itemsupplier_model', 'notificationsetting_model', 'itemstock_model', 'itemissue_model', 'userlog_model', 'cms_program_model', 'cms_menu_model', 'cms_media_model', 'cms_page_model', 'cms_menuitems_model', 'cms_page_content_model', 'role_model', 'calendar_model', 'userpermission_model', 'staffroles_model', 'staffattendancemodel', 'rolepermission_model', 'Certificate_model', 'classteacher_model', 'Generatecertificate_model', 'Student_id_card_model', 'timeline_model', 'Generateidcard_model', 'Module_model', 'subjectgroup_model', 'studentsubjectgroup_model', 'subjecttimetable_model', 'studentsubjectattendence_model', 'audit_model', 'Chat_model', 'apply_leave_model', 'disable_reason_model', 'question_model', 'leavetypes_model', 'alumni_model', 'lessonplan_model', 'syllabus_model', 'Staffidcard_model', 'Generatestaffidcard_model', 'visitors_model', 'video_tutorial_model'));
        $this->load->model(array('customfield_model', 'onlinestudent_model', 'houselist_model', 'onlineexam_model', 'onlineexamquestion_model', 'onlineexamresult_model', 'examstudent_model', 'admitcard_model', 'marksheet_model', 'chatuser_model', 'examgroupstudent_model', 'examgroup_model', 'batchsubject_model', 'filetype_model', 'currency_model', 'examsubject_model', 'feereminder_model'));
        $this->load->library(array('Role', 'Smsgateway', 'QDMailer', 'Adler32', 'Aes'));
        $this->load->library(array('auth', 'module_lib', 'pushnotification', 'jsonlib'));
        $this->load->library(array('version_control'));

        if ($this->session->has_userdata('admin')) {

            $admin    = $this->session->userdata('admin');
            $language = ($admin['language']['language']);
        } else if ($this->session->has_userdata('student')) {

            $student = $this->session->userdata('student');

            $language = ($student['language']['language']);
        } else {
            $this->school_details = $this->setting_model->getSchoolDetail();
            $language             = ($this->school_details->language);
        }

        $this->config->set_item('language', $language);
        $lang_array = array('form_validation_lang');
        $map        = directory_map(APPPATH . "./language/" . $language . "/app_files");
        foreach ($map as $lang_key => $lang_value) {
            $lang_array[] = 'app_files/' . str_replace(".php", "", $lang_value);
        }

        $this->load->language($lang_array, $language);
    }

}

class Admin_Controller extends MY_Controller
{

    protected $aaaa = false;

    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged_in();
        $this->check_license();
        $this->load->library('rbac');
        $this->config->load('app-config');
        $this->config->load('ci-blog');
        $this->config->load('custom_filed-config');
    }

    public function check_license()
    {
		return true;
        $license = $this->config->item('SSLK');

        if (!empty($license)) {

            $regex = "/^[A-Z0-9]{6}-[A-Z0-9]{6}-[A-Z0-9]{6}-/";

            if (preg_match($regex, $license)) {
                $valid_string = $this->aes->validchk('encrypt', base_url());

                if (strpos($license, $valid_string) !== false) {

                    true; //valid
                } else {
                    $this->update_ss_routine();
                }
            } else {

                $this->update_ss_routine();
            }
        }
    }

    public function update_ss_routine()
    {

        $license       = $this->config->item('SSLK');
        $fname         = APPPATH . 'config/license.php';
        $update_handle = fopen($fname, "r");
        $content       = fread($update_handle, filesize($fname));
        $file_contents = str_replace('$config[\'SSLK\'] = \'' . $license . '\'', '$config[\'SSLK\'] = \'\'', $content);
        $update_handle = fopen($fname, 'w') or die("can't open file");
        if (fwrite($update_handle, $file_contents)) {

        }
        fclose($update_handle);

        $this->config->set_item('SSLK', '');
    }

}

class Student_Controller extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();
        $this->school_details = $this->setting_model->getSchoolDetail();
        if ($this->school_details->maintenance_mode) {
            echo $this->load->view('maintenance', '', true);
            exit;
        }

        $this->load->library('studentmodule_lib');
        $this->load->library('cart');
        $this->config->load('app-config');
        $this->auth->is_logged_in_user('student');
        $is_lock_panel = check_lock_enabled();

        if ($is_lock_panel) {

            $active_class  = $this->router->fetch_class();
            $active_method = $this->router->fetch_method();
            if (($active_class == "user" && (
                $active_method == "fees"
                || $active_method == "getcollectfee"
                || $active_method == "change_currency"
                || $active_method == "user_language"

            ))

                || ($active_class == "offlinepayment" && (
                    $active_method == "index"

                ))
            ) {

            } else {
                redirect('user/user/fees');
            }

        }

    }

}

class Studentgateway_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('studentmodule_lib');
        $this->config->load('app-config');
        $this->auth->is_logged_in_user('student');

    }

}

class Public_Controller extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();
         $active_class  = $this->router->fetch_class();
         $active_method = $this->router->fetch_method();
       
        if (($active_class == "site" || $active_class =="gauthenticate") && ($active_method == "userlogin")) {

            $this->school_details = $this->setting_model->getSchoolDetail();
            if ($this->school_details->maintenance_mode) {
                echo $this->load->view('maintenance', '', true);
                exit;
            }

        }

    }

}

class OnlineAdmission_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('custom');
    }

}

class Parent_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged_in_user('parent');
        $this->config->load('app-config');
        $this->load->library('parentmodule_lib');
    }

}

class Front_Controller extends CI_Controller
{

    protected $data           = array();
    protected $school_details = array();
    protected $parent_menu    = '';
    protected $page_title     = '';
    protected $theme_path     = '';
    protected $front_setting  = '';

    public function __construct()
    {

        parent::__construct();
        $this->check_installation();
        $this->load->database();
                $this->load->library(array('Smsgateway', 'QDMailer'));
        $this->load->model(array('setting_model', 'language_model', 'Module_model', 'cms_program_model', 'cms_menu_model', 'cms_menuitems_model', 'cms_page_model', 'cms_page_content_model', 'class_model', 'category_model','notificationsetting_model'));

        if ($this->config->item('installed') == true) {

            $this->db->reconnect();
        }
        $this->load->helper('language');
        $this->school_details = $this->setting_model->getSchoolDetail();
        $this->customlib->initFrontSession();

        if ($this->school_details->maintenance_mode) {
            echo $this->load->view('maintenance', '', true);
            exit;
        }

        $this->load->model('frontcms_setting_model');

        $this->front_setting = $this->frontcms_setting_model->get();

        if (!$this->front_setting) {
            redirect('site/userlogin');
        } else {
            $front_cms_class  = $this->router->fetch_class();
            $front_cms_method = $this->router->fetch_method();
            if ($this->front_setting->is_active_front_cms) {
                $this->config->set_item('front_layout', true);
            }
            if (!$this->front_setting->is_active_front_cms) {
                $this->config->set_item('front_layout', false);
            }

            if (!$this->front_setting->is_active_front_cms && !$this->school_details->online_admission) {
                redirect('site/userlogin');
            }

            if ($this->school_details->online_admission) {
                if (!$this->front_setting->is_active_front_cms &&
                    !($front_cms_class == "welcome" && $front_cms_method == "admission") &&
                    !($front_cms_class == "welcome" && $front_cms_method == "editonlineadmission") &&
                    !($front_cms_class == "welcome" && $front_cms_method == "online_admission_review") &&
                    !($front_cms_class == "welcome" && $front_cms_method == "getSections") &&
                    !($front_cms_class == "welcome" && $front_cms_method == "submitadmission") &&
                    !($front_cms_class == "checkout" && $front_cms_method == "index") &&
                    !($front_cms_class == "checkout" && $front_cms_method == "successinvoice") &&
                    !($front_cms_class == "checkout" && $front_cms_method == "paymentfailed") &&
                    !($front_cms_class == "welcome" && $front_cms_method == "checkadmissionstatus")
                ) {
                    redirect('site/userlogin');
                }
            }

        }

        $this->theme_path = $this->front_setting->theme;
//================
        $language = ($this->school_details->language);
        $this->config->set_item('language', $language);
        $this->load->helper(array('directory', 'custom'));
        $lang_array = array('form_validation_lang');
        $map        = directory_map(APPPATH . "./language/" . $language . "/app_files");
        foreach ($map as $lang_key => $lang_value) {
            $lang_array[] = 'app_files/' . str_replace(".php", "", $lang_value);
        }

        $this->load->language($lang_array, $language);
//===============

        $this->load->config('ci-blog');
    }

    protected function load_theme($content = null, $layout = true)
    {

        $this->data['main_menus']     = '';
        $this->data['school_setting'] = $this->school_details;
        $this->data['front_setting']  = $this->front_setting;
        $menu_list                    = $this->cms_menu_model->getBySlug('main-menu');

        $footer_menu_list = $this->cms_menu_model->getBySlug('bottom-menu');
        if (count($menu_list) > 0) {
            $this->data['main_menus'] = $this->cms_menuitems_model->getMenus($menu_list['id']);
        }

        if (count($footer_menu_list) > 0) {
            $this->data['footer_menus'] = $this->cms_menuitems_model->getMenus($footer_menu_list['id']);
        }
        $this->data['layout_type'] = $layout;
        $this->data['header']      = $this->load->view('themes/' . $this->theme_path . '/header', $this->data, true);

        $this->data['slider'] = $this->load->view('themes/' . $this->theme_path . '/home_slider', $this->data, true);

        $this->data['footer'] = $this->load->view('themes/' . $this->theme_path . '/footer', $this->data, true);

        $this->base_assets_url = 'backend/' . THEMES_DIR . '/' . $this->theme_path . '/';

        $this->data['base_assets_url'] = base_url() . $this->base_assets_url; 
        $is_captcha                    = $this->captchalib->is_captcha('admission');
        $this->data["is_captcha"]      = $is_captcha;

        if ($layout == true) {
            $this->data['content'] = (is_null($content)) ? '' : $this->load->view(THEMES_DIR . '/' . $this->theme_path . '/' . $content, $this->data, true);
            $this->load->view(THEMES_DIR . '/' . $this->theme_path . '/layout', $this->data);
        } else {
            $this->data['content'] = (is_null($content)) ? '' : $this->load->view(THEMES_DIR . '/' . $this->theme_path . '/' . $content, $this->data, true);
            $this->load->view(THEMES_DIR . '/' . $this->theme_path . '/base_layout', $this->data);
        }
    }

    protected function load_theme_form($content = null, $layout = true)
    {

        $this->data['main_menus']     = '';
        $this->data['school_setting'] = $this->school_details;
        $this->data['front_setting']  = $this->front_setting;
        $menu_list                    = $this->cms_menu_model->getBySlug('main-menu');
        $footer_menu_list             = $this->cms_menu_model->getBySlug('bottom-menu');
        if (count($menu_list > 0)) {
            $this->data['main_menus'] = $this->cms_menuitems_model->getMenus($menu_list['id']);
        }

        if (count($footer_menu_list > 0)) {
            $this->data['footer_menus'] = $this->cms_menuitems_model->getMenus($footer_menu_list['id']);
        }
        $this->data['header'] = $this->load->view('themes/' . $this->theme_path . '/header', $this->data, true);

        $this->data['slider'] = $this->load->view('themes/' . $this->theme_path . '/home_slider', $this->data, true);

        $this->data['footer'] = $this->load->view('themes/' . $this->theme_path . '/footer', $this->data, true);

        $this->base_assets_url = 'backend/' . THEMES_DIR . '/' . $this->theme_path . '/';

        $this->data['base_assets_url'] = BASE_URI . $this->base_assets_url;

        $this->data['content'] = (is_null($content)) ? '' : $this->load->view(THEMES_DIR . '/' . $this->theme_path . '/' . $content, $this->data, true);
        $this->load->view(THEMES_DIR . '/' . $this->theme_path . '/layout', $this->data);

    }

    private function check_installation()
    {

        if ($this->uri->segment(1) !== 'install') {
            $this->load->config('migration');
            if ($this->config->item('installed') == false && $this->config->item('migration_enabled') == false) {
                redirect(base_url() . 'install/start');
            } else {
                if (is_dir(APPPATH . 'controllers/install')) {
                    echo '<h3>Delete the install folder from application/controllers/install</h3>';
                    die;
                }
            }
        }
    }

}
