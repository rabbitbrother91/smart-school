<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('active_link')) {

    function activate_menu($controller, $action)
    {
        $CI     = get_instance();
        $method = $CI->router->fetch_method();
        $class  = $CI->router->fetch_class();
        return ($method == $action && $controller == $class) ? 'active' : '';
    }

    function set_Topmenu($top_menu_name)
    {
        $CI               = get_instance();
        $session_top_menu = $CI->session->userdata('top_menu');
        if ($session_top_menu == $top_menu_name) {
            return 'active';
        }
        return "";
    }

    function set_Submenu($sub_menu_name)
    {
        $CI               = get_instance();
        $session_sub_menu = $CI->session->userdata('sub_menu');
        if ($session_sub_menu == $sub_menu_name) {
            return 'active';
        }
        return "";
    }

    function set_SubSubmenu($sub_menu_name)
    {
        $CI               = get_instance();
        $session_sub_menu = $CI->session->userdata('subsub_menu');
        if ($session_sub_menu == $sub_menu_name) {
            return 'active';
        }
        return "";
    }

}

function access_denied()
{
    redirect('admin/unauthorized');
}

function update_config_installed()
{
    $CI          = &get_instance();
    $config_path = APPPATH . 'config/config.php';
    $CI->load->helper('file');
    @chmod($config_path, FILE_WRITE_MODE);
    $config_file = read_file($config_path);
    $config_file = trim($config_file);
    $config_file = str_replace("\$config['installed'] = false;", "\$config['installed'] = true;", $config_file);
    $config_file = str_replace("\$config['base_url'] = '';", "\$config['base_url'] = '" . site_url() . "';", $config_file);
    if (!$fp = fopen($config_path, FOPEN_WRITE_CREATE_DESTRUCTIVE)) {
        return false;
    }
    flock($fp, LOCK_EX);
    fwrite($fp, $config_file, strlen($config_file));
    flock($fp, LOCK_UN);
    fclose($fp);
    @chmod($config_path, FILE_READ_MODE);
    return true;
}

function update_autoload_installed()
{
    $CI            = &get_instance();
    $autoload_path = APPPATH . 'config/autoload.php';
    $CI->load->helper('file');
    @chmod($autoload_path, FILE_WRITE_MODE);
    $autoload_file = read_file($autoload_path);
    $autoload_file = trim($autoload_file);
    $autoload_file = str_replace("\$autoload['libraries'] = array('database', 'session', 'form_validation')", "\$autoload['libraries'] = array('email','session', 'form_validation', 'upload', 'pagination','Customlib')", $autoload_file);
    if (!$fp = fopen($autoload_path, FOPEN_WRITE_CREATE_DESTRUCTIVE)) {
        return false;
    }
    flock($fp, LOCK_EX);
    fwrite($fp, $autoload_file, strlen($autoload_file));
    flock($fp, LOCK_UN);
    fclose($fp);
    @chmod($config_path, FILE_READ_MODE);
    return true;
}

function delete_dir($dirPath)
{
    if (!is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            delete_dir($file);
        } else {
            unlink($file);
        }
    }
    if (rmdir($dirPath)) {
        return true;
    }
    return false;
}

function admin_url($url = '')
{
    if ($url == '') {
        return site_url() . 'site/login';
    } else {
        return site_url() . 'site/login';
    }
}

if (!function_exists('main_menu_array')) {

    function main_menu_array($find_array)
    {  
        $array = array(

            'front_office' => array(
                'enquiry'         => array('index'),
                'visitors'        => array('index'),
                'generalcall'     => array('index','edit'),
                'dispatch'        => array('index','editdispatch'),
                'receive'         => array('index','editreceive'),
                'complaint'       => array('index','edit'),
                'visitorspurpose' => array('index','edit'),
            ),
            
            'student_information' => array(                
                'student'         => array('search','create','import','disablestudentslist','multiclass','bulkdelete','view','edit'),       
                'onlinestudent'   => array('index','edit'),               
                'category'        => array('index','edit'),               
                'schoolhouse'     => array('index','edit'),               
                'disable_reason'  => array('index','edit'),                              
            ),
            
            'fees_collection' => array(                             
                'studentfee'     => array('index','addfee','searchpayment','feesearch'),                            
                'feemaster'      => array('index','assign','edit'),                               
                'feegroup'       => array('index','edit'),                               
                'feetype'        => array('index','edit'),                               
                'feediscount'    => array('index','edit','assign'),                               
                'feesforward'    => array('index'),                               
                'feereminder'    => array('setting'), 
                'offlinepayment' => array('index'), 				
            ), 
            
            'income' => array(                                 
                'income'        => array('index','edit','incomesearch'),             
                'incomehead'    => array('index','edit'),             
            ),
            
            'expense' => array(                                 
                'expense'       => array('index','edit','expensesearch'),             
                'expensehead'   => array('index','edit'),                             
            ),
            
            'examinations' => array(                                 
                'examgroup'     => array('index','edit','addexam'),                  
                'exam_schedule' => array('index'),                  
                'examresult'    => array('index','admitcard','marksheet'),                  
                'admitcard'     => array('index','edit'),                  
                'marksheet'     => array('index','edit'),                  
                'grade'         => array('index','edit'),                  
                'marksdivision'         => array('index','edit'),                  
            ),
            
            'attendance' => array(                                 
                'approve_leave'    => array('index'),                   
                'stuattendence'    => array('index','edit','attendencereport'),    
                'subjectattendence'    => array('index','reportbydate'),                   
                                  
            ), 
            
            'online_examinations' => array(                                 
                'onlineexam'    => array('index','evalution','assign'),                  
                'question'      => array('index','read'),                  
            ), 
            
            'lesson_plan' => array(                                 
                'syllabus'      => array('index','status'),                
                'lessonplan'    => array('lesson','topic','copylesson','edittopic','editlesson'),                
            ), 
            
            'academics' => array(                                 
                'timetable'     => array('classreport','mytimetable','create'),                 
                'teacher'       => array('assign_class_teacher','update_class_teacher'),                 
                'stdtransfer'   => array('index'),                 
                'subjectgroup'  => array('index','edit'),                 
                'subject'       => array('index','edit'),                 
                'classes'       => array('index','edit'),                 
                'sections'      => array('index','edit'),                 
            ), 
            
            'human_resource' => array(                   
                'staff'             => array('index','profile','edit','leaverequest','rating','disablestafflist','create'),             
                'staffattendance'   => array('index'),                 
                'payroll'           => array('index','edit','create'),                 
                'leaverequest'      => array('leaverequest'),  
                'leavetypes'        => array('index','leaveedit','createleavetype'),  
                'department'        => array('department','departmentedit'),  
                'designation'       => array('designation','designationedit'),            
            ), 
            
            'communicate' => array(          
                'notification'      => array('index','edit','add'),             
                'mailsms'           => array('compose','compose_sms','index','schedule','email_template','sms_template','edit_schedule'),      
                'student'           => array('bulkmail'),             
            ), 
            
            'download_center' => array(          
                'contenttype'       => array('index','edit'),              
                'content'           => array('list','upload'),              
                'video_tutorial'    => array('index'),              
            ), 
            
            'homework' => array(               
                'homework'      => array('index','dailyassignment'),              
            ), 
            
            'library' => array(               
                'book'      => array('getall','edit','index','import'),    
                'member'    => array('index','issue','student','teacher'),    
            ), 
            
            'inventory' => array(               
                'issueitem'      => array('index','create'),    
                'itemstock'      => array('index','edit'),    
                'item'           => array('index','edit'),    
                'itemcategory'   => array('index','edit'),    
                'itemstore'      => array('index','edit','create'),    
                'itemsupplier'   => array('index','edit','create'),    
            ), 
             
            'transport' => array(               
                'transport'      => array('feemaster'),      
                'pickuppoint'    => array('index','assign','student_fees'),      
                'route'    => array('index','edit'),      
                'vehicle'    => array('index'),      
                'vehroute'    => array('index','edit'),        
            ), 
            
            'hostel' => array(               
                'hostelroom'  => array('index','edit'),      
                'roomtype'    => array('index','edit'),      
                'hostel'      => array('index','edit'),      
            ), 
            
            'certificate' => array(               
                'certificate'           => array('index','edit'),      
                'generatecertificate'   => array('index','search'),      
                'studentidcard'         => array('index','edit'),      
                'generateidcard'        => array('search'),      
                'staffidcard'           => array('index','edit'),    
                'generatestaffidcard'   => array('index','search'),    
            ),
            
            'front_cms' => array(               
                'events'        => array('index','edit','create'),      
                'gallery'       => array('index','edit','create'),      
                'notice'        => array('index','edit','create'),      
                'media'         => array('index'),      
                'page'          => array('index','edit','create'),        
                'menus'         => array('index','additem'),        
                'banner'        => array('index'),        
            ),
            
            'alumni' => array(               
                'alumni'        => array('alumnilist','events'),       
            ),            
            
            'reports' => array(  
                'report'            => array('alumnireport','inventory','issueinventory','additem','inventorystock','library','studentbookissuereport','bookduereport','bookinventory','human_resource','staff_report','lesson_plan','teachersyllabusstatus','onlineexamrank','onlineexamattend','onlineexams','attendance','studentinformation','studentreport','online_admission_report','student_teacher_ratio','boys_girls_ratio','student_profile','sibling_report','admission_report','class_subject','classsectionreport','guardianreport','admissionreport','logindetailreport','parentlogindetailreport'),
                
                'attendencereports' => array('attendance','classattendencereport','attendancereport','daily_attendance_report','staffattendancereport','biometric_attlog','reportbymonthstudent','reportbymonth','staffdaywiseattendancereport','daywiseattendancereport'), 
                'payroll'           => array('payrollreport'), 
                'onlineexam'        => array('report'),  
                'examresult'        => array('rankreport','examinations'), 
                'book'              => array('issue_returnreport'), 
                'homework'          => array('homeworkreport','evaluation_report'),                
                'route'             => array('studenttransportdetails'), 
                'hostelroom'        => array('studenthosteldetails'), 
                'userlog'           => array('index'), 
                'audit'             => array('index'),
                'financereports'    => array('finance','reportduefees','reportdailycollection','reportbyname','studentacademicreport','collection_report','onlinefees_report','duefeesremark','income','expense','payroll','incomegroup','expensegroup','onlineadmission'),                
                'homework'          => array('homeworkordailyassignmentreport','homeworkreport','evaluation_report','dailyassignmentreport'),             
            ),            
            
            'system_settings' => array(  
                'schsettings'           => array('index','logo','miscellaneous','backendtheme','mobileapp','studentguardianpanel','fees','idautogeneration','attendancetype','maintenance'),                     
                'sessions'              => array('index','edit'),                     
                'notification'          => array('setting'),                     
                'smsconfig'             => array('index'),                     
                'emailconfig'           => array('index'),                     
                'paymentsettings'       => array('index'),                     
                'print_headerfooter'    => array('index'),                     
                'frontcms'              => array('index'),                     
                'roles'                 => array('index','permission'),                     
                'admin'                 => array('backup','filetype'),                     
                'language'              => array('index','create'),                     
                'currency'              => array('index'),                     
                'users'                 => array('index'),                     
                'module'                => array('index'),                     
                'customfield'           => array('index','edit'),                     
                'captcha'               => array('index'),                     
                'systemfield'           => array('index'),                     
                'student'               => array('profilesetting'),                     
                'onlineadmission'       => array('admissionsetting'),                  
                'updater'               => array('index'),                  
                'sidemenu'              => array('index'),                  
            ),

            'gmeet_live_classes' => array(               
                'gmeet'        => array('timetable','meeting','class_report','meeting_report','index'),               
            ),
                
            'zoom_live_classes' => array(               
                'conference'        => array('timetable','meeting','class_report','meeting_report','index'),               
            ),
            
            'behaviour_records' => array(               
                'studentincidents'  => array('index'),               
                'incidents' => array('index'),               
                'report'    => array('index','studentincidentreport','studentbehaviorsrankreport','classwiserankreport','classsectionwiserank','housewiserank','incidentwisereport'),               
                'setting'   => array('index'),               
            ),
            
            'multi_branch' => array(               
                'branch'    => array('overview','index'),               
                'finance'   => array('dailycollectionreport','payroll','incomelist','expenselist','incomereport','expensereport','userlogreport','index'),               
            ),
            
            'two_factor_authentication' => array(               
                'admin'        => array('setup','index'),               
            ),
            
            'online_course' => array(               
                'course'        => array('index','setting'),               
                'coursecategory'  => array('categoryadd','categoryedit'),               
                'coursereport'   => array('report','coursepurchase','coursesellreport','trendingreport','completereport','courseratingreport','guestlist','quizperformance'),               
                'offlinepayment'   => array('payment'),               
            ),
            
            'cbse_exam' => array(               
                'exam'          => array('index','examtimetable','examwiserank','templatewiserank'),               
                'result'        => array('marksheet'),               
                'grade'         => array('gradelist'),               
                'observation'   => array('index','assign'),               
                'observationparameter' => array('index','edit'),               
                'assessment'    => array('index'),               
                'term'          => array('index'),               
                'template'      => array('index','templatewiserank'),               
                'report'        => array('index','templatewise','examsubject'),               
                'setting'       => array('index'),                              
            ),
            
            'qr_code_attendance' => array(             
                               
                'attendance'    => array('index'),                
                'setting'       => array('index'),                              
            ),
            
        );
        if (array_key_exists($find_array, $array)) {
            return $array[$find_array];
        }
        return false;
    }

}

if (!function_exists('activate_main_menu')) {

    function activate_main_menu($menu, $class_active = "active")
    {
        $CI     = get_instance();
        $class  = $CI->router->fetch_class();
        $method = $CI->router->fetch_method();

        $return_array = main_menu_array($menu);
        if ($return_array) {
            if (array_key_exists($class, $return_array)) {
                $a = $return_array[$class];

                if (!empty($a)) {
                    foreach ($a as $method_key => $method_value) {
                        if ($method_value == $method) {
                            return $class_active;
                            break;
                        }
                    }
                }
            }
        }
    }
}

if (!function_exists("activate_submenu")) {

    function activate_submenu($arg_class = "", $arg_methods = array(), $class_active = "active")
    {
        $CI = get_instance();

        // Getting router class to active.
        $class  = $CI->router->fetch_class();
        $method = $CI->router->fetch_method();
        if (is_array($arg_methods)) {
            foreach ($arg_methods as $arg_methods_key => $arg_methods_value) {
                if ($method == $arg_methods_value && $class == $arg_class) {
                    return $class_active;
                    break;
                }
            }
        }
    }

}

function side_menu_list($list = -1)
{

    $CI = &get_instance();
    $CI->load->model('sidebarmenu_model');
    $result = $CI->sidebarmenu_model->getMenuwithSubmenus($list);
    return $result;

}

function access_permission_sidebar_remove_pipe($access_permissions)
{
    // remove pipe sign ||
    $module_permission = array_map('trim', explode('||', preg_replace('/\(\'|\'|\)/', '', $access_permissions)));

    return $module_permission;
}

function access_permission_remove_comma($m_permission_value)
{
    // remove pipe sign ||
    $module_permission_seprated = array_map('trim', explode(',', preg_replace('/\s+/', '', $m_permission_value)));
    return $module_permission_seprated;
}
