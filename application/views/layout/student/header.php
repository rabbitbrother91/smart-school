<!DOCTYPE html>
<html <?php echo $this->customlib->getRTL(); ?>>

<?php
$is_lock_panel   = check_lock_enabled();
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$role            = $this->customlib->getUserRole();

?>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $this->customlib->getAppName(); ?></title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="theme-color" content="#424242" />
        <link href="<?php echo base_url(); ?>uploads/school_content/admin_small_logo/<?php echo $this->setting_model->getAdminsmalllogo();?>" rel="shortcut icon" type="image/x-icon">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">

        <?php
$sch_setting = $this->setting_model->getSetting();
$this->load->view('layout/theme');
?>

        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/ss-print.css">

        <?php
if ($this->customlib->getRTL() != "") {
    ?>
            <!-- Bootstrap 3.3.5 RTL -->
            <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/bootstrap-rtl/css/bootstrap-rtl.min.css"/>
            <!-- Theme RTL style -->
            <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/admin-rtl.min.css" />
            <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/ss-rtlmain.css">
            <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/skins/_all-skins-rtl.min.css" />

            <?php
} else {

}
?>
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/ionicons.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/iCheck/flat/blue.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/morris/morris.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/datepicker/datepicker3.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/daterangepicker/daterangepicker-bs3.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/sweet-alert/sweetalert2.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/custom_style.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/datepicker/css/bootstrap-datetimepicker.css">
        <!--file dropify-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/dropify.min.css">
        <!--file nprogress-->
        <link href="<?php echo base_url(); ?>backend/dist/css/nprogress.css" rel="stylesheet">
        <!--print table-->
        <link href="<?php echo base_url(); ?>backend/dist/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend/dist/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend/dist/datatables/css/dataTables.bootstrap.min.css" rel="stylesheet">
        <!--language css-->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/0.8.2/css/flag-icon.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>backend/dist/css/bootstrap-select.min.css">
        <!--print table mobile support-->
        <link href="<?php echo base_url(); ?>backend/dist/datatables/css/responsive.dataTables.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>backend/dist/datatables/css/rowReorder.dataTables.min.css" rel="stylesheet">
        <script src="<?php echo base_url(); ?>backend/custom/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/dist/js/moment.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/datepicker/js/bootstrap-datetimepicker.js"></script>
        <script src="<?php echo base_url(); ?>backend/datepicker/date.js"></script>
        <script src="<?php echo base_url(); ?>backend/dist/js/jquery-ui.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/js/school-custom.js"></script>
        <script src="<?php echo base_url(); ?>backend/js/sstoast.js"></script>

        <!-- fullCalendar -->
        <link rel="stylesheet" href="<?php echo base_url() ?>backend/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>backend/fullcalendar/dist/fullcalendar.print.min.css" media="print">

        <script type="text/javascript">
            var baseurl = "<?php echo base_url(); ?>";
            var start_week="<?php echo $this->customlib->getStartWeek(); ?>";
        </script>

        <?php if ($this->module_lib->hasModule('online_course') && $this->module_lib->hasModule('online_course')) {?>
        <script src="<?php echo base_url(); ?>backend/js/studentcourse.js"></script>
        <?php }?>

    </head>
    <script>
         var baseurl = "<?php echo base_url(); ?>";
    </script>

    <body class="hold-transition skin-blue fixed sidebar-mini">
        <script>

    function collapseSidebar() {

        if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed1'))) {
        sessionStorage.setItem('sidebar-toggle-collapsed1', '');
        } else {
        sessionStorage.setItem('sidebar-toggle-collapsed1', '1');
        }

        }

    function checksidebar() {
        if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed1'))) {
        var body = document.getElementsByTagName('body')[0];

        body.className = body.className + ' sidebar-collapse';
        }
    }
    checksidebar();

</script>
        <div class="wrapper">
            <header class="main-header" id="alert">
<?php
if ($role == 'guest') {
    $function = 'user/studentcourse/profile';
} elseif ($role == 'student') {
    $function = 'user/user/dashboard';
} elseif ($role == 'parent') {
    $function = 'user/user/dashboard';
}?>
                <a href="<?php echo base_url(); ?><?php echo $function; ?>" class="logo">
                
                    <span class="logo-mini"><img src=" <?php echo base_url('uploads/school_content/admin_small_logo/'. $this->setting_model->getAdminsmalllogo());?>" alt="<?php echo $this->customlib->getAppName() ?>" /></span>
                    
                    <span class="logo-lg"><img src="<?php echo base_url('uploads/school_content/admin_logo/'.$this->setting_model->getAdminlogo());?>" alt="<?php echo $this->customlib->getAppName() ?>" /></span> 
                    
                </a>
                <nav class="navbar navbar-static-top" role="navigation">
                    <a onclick="collapseSidebar()" href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only"><?php echo $this->lang->line('toggle_navigation'); ?></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <div class="col-lg-5 col-md-3 col-sm-2 col-xs-4">
                        <span href="#" class="sidebar-session">
                            <?php echo $this->setting_model->getCurrentSchoolName(); ?>
                        </span>
                    </div>
                    <div class="col-lg-7 col-md-9 col-sm-10 col-xs-8">
                        <div class="pull-right">

                            <div class="navbar-custom-menu">

                                    <div class="langdiv">
                                        <select class="languageselectpicker" onchange="set_languages(this.value)"  type="text" id="languageSwitcher" class="form-control search-form search-form3 langselect" title="<?php echo $this->lang->line('language') ?>"  >

                                           <?php $this->load->view('student/languageSwitcher')?>

                                        </select>
                                    </div>

                                    <div class="currency-icon-list" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('currency') ?>">
                                        <select class="languageselectpicker" type="text" id="currencySwitcher" >

                                           <?php $this->load->view('student/currencySwitcher')?>

                                        </select>
                                    </div>

                                    <ul class="nav navbar-nav headertopmenu">
                                   <?php

if ($this->studentmodule_lib->hasActive('multi_class')) {
    ?>
       <li class="cal15"><a href="#" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('switch_class') ?>"><span data-toggle="modal" data-target="#classSwitchModal"><i class="fa fa-exchange" aria-hidden="true"></i></span></a></li>
   <?php

}
?>
                                     <?php if ($this->studentmodule_lib->hasActive('calendar_to_do_list')) {
    ?>
                                    <li class="cal15 d-sm-none <?php echo ($is_lock_panel) ? "disable-link" : "" ?>"><a data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('calendar') ?>" href="<?php echo base_url() ?>user/calendar/"><i class="fa fa fa-calendar"></i></a></li>

                                    <li data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('task') ?>" class="dropdown <?php echo ($is_lock_panel) ? "disable-link" : "" ?>">
                                        <a href="#" class="dropdown-toggle todoicon" data-toggle="dropdown">
                                            <i class="fa fa-check-square-o"></i>
                                            <?php
$userdata = $this->customlib->getLoggedInUserData();
    $count    = $this->customlib->countincompleteTask($userdata["id"]);
    if ($count > 0) {
        ?>

                                                <span class="todo-indicator"><?php echo $count ?></span>
                                            <?php }?>
                                        </a>
                                        <ul class="dropdown-menu menuboxshadow">

                                            <li class="todoview plr10 ssnoti"><?php echo $this->lang->line('today_you_have') . " " . $count . " " . $this->lang->line('pending_task') ?><a href="<?php echo base_url() ?>user/calendar/" class="pull-right pt0"> <?php echo $this->lang->line('view_all'); ?></a></li>
                                            <li>
                                                <ul class="todolist">
                                                    <?php
$tasklist = $this->customlib->getincompleteTask($userdata["id"]);
    foreach ($tasklist as $key => $value) {
        ?>
                                                        <li><div class="checkbox">
                                                                <label><input type="checkbox" id="newcheck<?php echo $value["id"] ?>" onclick="markc('<?php echo $value["id"] ?>')" name="eventcheck"  value="<?php echo $value["id"]; ?>"><?php echo $value["event_title"] ?></label>
                                                            </div></li>
                                                    <?php }?>

                                                </ul>
                                            </li>
                                        </ul>
                                    </li>

                                <li class="dropdown d-lg-none d-sm-block ellipsis-px-3">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu min-w-full sm-drop-down">
                                        <li><a href="<?php echo base_url() ?>user/calendar/"><i class="fa fa-calendar"></i></a></li>
                                        <li><a href="<?php echo base_url() ?>user/chat"><i class="fa fa-whatsapp"></i></a></li>
                                    </ul>
                                </li>    

                                <?php }if ($this->module_lib->hasActive('chat') && $this->studentmodule_lib->hasActive('chat')) {
    ?>
                                 <li class="cal15 d-sm-none <?php echo ($is_lock_panel) ? "disable-link" : "" ?>"><a data-placement="bottom" data-toggle="tooltip" title="" href="<?php echo base_url() ?>user/chat" data-original-title="<?php echo $this->lang->line('chat'); ?>" class="todoicon"><i class="fa fa-whatsapp"></i></a></li>
                                <?php }

$student_data = $this->customlib->getLoggedInUserData(); 

if (!empty($student_data["image"])) {
    if($student_data['role'] == 'guest'){                    
        $file = base_url() . "uploads/guest_images/" . $student_data["image"] . img_time();                                    
    }else{                   
        $file = base_url() . $student_data["image"] . img_time();               
    }                
}else{
    if ($student_data['gender'] == 'Female') {
        $file = base_url() . "uploads/student_images/default_female.jpg" . img_time();
    } elseif ($student_data['gender'] == 'Male') {
        $file = base_url() . "uploads/student_images/default_male.jpg" . img_time();
    }else{
        $file = base_url() . "uploads/student_images/no_image.png";  
    }                
}
?>

                                    <li class="dropdown user-menu">
                                        <a class="dropdown-toggle" style="padding: 15px 13px;" data-toggle="dropdown" href="#" aria-expanded="false">
                                            <?php if ($sch_setting->student_photo) {
    ?>
                                                <img src="<?php echo $file . img_time(); ?>" class="topuser-image" alt="User Image">
                                                <?php
}?>

                                        </a>
                                        <ul class="dropdown-menu dropdown-user menuboxshadow">

                                            <li>
                                                <div class="sstopuser">
                                                    <div class="ssuserleft">
                                                   <a href="<?php echo base_url() ?><?php echo $function; ?>">
                                                    <?php if ($sch_setting->student_photo) {
    ?>
                                                        <img src="<?php echo $file . img_time(); ?>" alt="User Image"></a>
                                                    <?php }?>

                                                    </div>

                                                    <div class="sstopuser-test">
                                                        <h4 style="text-transform: capitalize;"><?php echo $this->customlib->getStudentSessionUserName(); ?></h4>
                                                        <h5><?php
if ($this->customlib->getUserRole() == 'student') {
    echo $this->lang->line("student");
} elseif ($this->customlib->getUserRole() == 'parent') {
    echo $this->lang->line("parent");
} elseif ($this->customlib->getUserRole() == 'guest') {
    echo $this->lang->line("guest");
}
?></h5>

                                                        <?php
if ($this->module_lib->hasModule('google_authenticator') && $this->module_lib->hasActive('google_authenticator')) {

    // if (is_active_2fa() && $this->auth->addonchk('ssglc', false)) {
        ?>

                                                            <p class="sspass"><a href="<?php echo site_url('user/gauthenticate/setup') ?>"><i class="fa fa-cog"></i><?php echo $this->lang->line('setting'); ?></a></p>

                                                        <?php
// }
}
?>

                                                    </div>
                                                    <div class="divider"></div>
                                                    <div class="sspass">
                                                        <a class="" href="<?php echo base_url(); ?>user/user/changepass"><i class="fa fa-key"></i> <?php echo $this->lang->line('change_password'); ?></a> <a class="pull-right" href="<?php echo base_url(); ?>site/logout"><i class="fa fa-sign-out fa-fw"></i> <?php echo $this->lang->line('logout'); ?></a>
                                                    </div>
                                                </div><!--./sstopuser--></li>

                                        </ul>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </nav>
            </header>
            <aside class="main-sidebar" id="alert2">

                <?php
if ($role == 'student' || $role == 'parent') {
    ?>
                <section class="sidebar <?php echo ($is_lock_panel) ? "disable-sidebar" : "" ?>">

                    <ul class="sessionul fixedmenu">
                        <li class="removehover accurrent">
                            <a data-toggle="modal" data-target="#user_sessionModal"><span><?php echo $this->lang->line('current_session') . ": " . $this->setting_model->getCurrentSessionName(); ?></span><i class="fa fa-pencil pull-right"></i></a>
                        </li>
                    </ul>
                    <ul class="sidebar-menu verttop38" id="sibe-box">
                        <li class="treeview <?php echo set_Topmenu('dashboard'); ?>">
                            <a href="<?php echo base_url(); ?>user/user/dashboard">
                                <i class="fa fa-television"></i> <span> <?php echo $this->lang->line('dashboard'); ?></span>
                            </a>
                        </li>

                        <li class="<?php echo set_Topmenu('my_profile'); ?>"><a href="<?php echo base_url(); ?>user/user/profile"><i class="fa fa-user-plus ftlayer"></i> <span><?php echo $this->lang->line('my_profile'); ?></span></a></li>                        
                        

                        <?php if ($this->module_lib->hasActive('fees_collection') && $this->studentmodule_lib->hasActive('fees')) {?>
                            <li class="<?php echo set_Topmenu('fees'); ?>"><a href="<?php echo base_url(); ?>user/user/getfees"><i class="fa fa-money ftlayer"></i> <span><?php echo $this->lang->line('fees'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->module_lib->hasModule('online_course') && $this->module_lib->hasActive('online_course') && $this->studentmodule_lib->hasActive('online_course') && $this->auth->addonchk('ssoclc', false)) {?>
                            <li class="<?php echo set_topmenu('user/studentcourse'); ?>"><a href="<?php echo base_url(); ?>user/studentcourse"><i class="fa fa-file-video-o ftlayer"></i> <span><?php echo $this->lang->line('online_course'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->module_lib->hasModule('zoom_live_classes') && $this->module_lib->hasActive('zoom_live_classes') && $this->studentmodule_lib->hasActive('live_classes') && $this->auth->addonchk('sszlc', false)) {?>
                            <li class="<?php echo set_topmenu('Conference'); ?>"><a href="<?php echo base_url('user/conference'); ?>"><i class="fa fa-video-camera ftlayer"></i> <?php echo $this->lang->line('zoom_live_classes'); ?></a></li>
                        <?php }?>

                        <?php if ($this->module_lib->hasModule('gmeet_live_classes') && $this->module_lib->hasActive('gmeet_live_classes') && $this->studentmodule_lib->hasActive('gmeet_live_classes') && $this->auth->addonchk('ssglc', false)) {?>
                            <li class="<?php echo set_topmenu('Gmeet'); ?>"><a href="<?php echo base_url('user/gmeet'); ?>"><i class="fa fa-video-camera ftlayer"></i> <?php echo $this->lang->line('gmeet') . " " . $this->lang->line('live_class'); ?></a></li>
                        <?php }?>

                        <?php if ($this->studentmodule_lib->hasActive('class_timetable')) {?>
                            <li class="<?php echo set_Topmenu('Time_table'); ?>"><a href="<?php echo base_url(); ?>user/timetable"><i class="fa fa-calendar-plus-o ftlayer"></i> <span><?php echo $this->lang->line('class_timetable'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->module_lib->hasActive('lesson_plan') && $this->studentmodule_lib->hasActive('lesson_plan')) {?>
                            <li class="<?php echo set_Topmenu('syllabus'); ?>"><a href="<?php echo base_url(); ?>user/syllabus"><i class="fa fa fa-list-alt ftlayer"></i> <span><?php echo $this->lang->line('lesson_plan'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->studentmodule_lib->hasActive('syllabus_status')) {?>
                             <li class="<?php echo set_Topmenu('syllabus/status'); ?>"><a href="<?php echo base_url(); ?>user/syllabus/status"><i class="fa fa-list-ol ftlayer"></i> <span><?php echo $this->lang->line('syllabus_status'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->module_lib->hasActive('homework') && $this->studentmodule_lib->hasActive('homework')) {?>
                            <li class="<?php echo set_Topmenu('Homework'); ?>"><a href="<?php echo base_url(); ?>user/homework"><i class="fa fa-flask ftlayer"></i> <span><?php echo $this->lang->line('homework'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->module_lib->hasActive('online_examination') && $this->studentmodule_lib->hasActive('online_examination')) {?>
                            <li class="treeview <?php echo set_Topmenu('Onlineexam'); ?>"><a href="<?php echo site_url('user/onlineexam'); ?>"><i class="fa fa-rss ftlayer"></i> <span><?php echo $this->lang->line('online_exam'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->studentmodule_lib->hasActive('apply_leave')) {?>
                              <li class="<?php echo set_Topmenu('apply_leave'); ?>"><a href="<?php echo base_url(); ?>user/apply_leave"><i class="fa fa-check-square ftlayer"></i> <span><?php echo $this->lang->line('apply_leave'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->studentmodule_lib->hasActive('visitor_book')) {?>
                              <li class="<?php echo set_Topmenu('visitors'); ?>"><a href="<?php echo base_url(); ?>user/visitors"><i class="fa fa-check-square ftlayer"></i> <span><?php echo $this->lang->line('visitor_book'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->module_lib->hasActive('download_center') && $this->studentmodule_lib->hasActive('download_center')) {?>
                            <li class="treeview <?php echo set_Topmenu('Downloads'); ?>">
                                <a href="#">
                                    <i class="fa fa-download ftlayer"></i> <span><?php echo $this->lang->line('download_center'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="<?php echo set_Submenu('content/index'); ?>">
                                        <a href="<?php echo base_url(); ?>user/content/list"><i class="fa fa-angle-double-right"></i><?php echo $this->lang->line('contents'); ?></a>
                                    </li>

                                    <li class="<?php echo set_Submenu('video_tutorial/index'); ?>"><a href="<?php echo base_url(); ?>user/video_tutorial"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('video_tutorial'); ?></a></li>
                                </ul>
                            </li>
                        <?php }?>

                        <?php if ($this->module_lib->hasActive('student_attendance') && $this->studentmodule_lib->hasActive('attendance')) {?>
                            <li class="treeview <?php echo set_Topmenu('Attendence'); ?>"><a href="<?php echo base_url(); ?>user/attendence"><i class="fa fa-calendar-check-o ftlayer"></i> <span><?php echo $this->lang->line('attendance'); ?></span></a></li>
                        <?php }?>
                        
                        <?php  
                        if ($this->module_lib->hasModule('cbseexam') && $this->module_lib->hasActive('cbseexam') && $this->studentmodule_lib->hasActive('cbseexam')   ) { ?>					
							
							
							<li class="treeview <?php echo set_Topmenu('cbse_exam'); ?>">
                                <a href="#">
                                    <i class="fa fa-wpforms"></i> <span><?php echo $this->lang->line('cbse_exam'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                                </a> 
                                <ul class="treeview-menu">
                                    <li class="<?php echo set_Submenu('user/cbse/cbse_exam_result'); ?>"><a href="<?php echo site_url('user/cbse/exam/result'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('exam_result'); ?></a></li>
									
                                    <li class="<?php echo set_Submenu('user/cbse/cbse_exam_timetable'); ?>"><a href="<?php echo site_url('user/cbse/exam/timetable'); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('exam_schedule'); ?></a></li>
                                     
                                </ul>
                            </li>		
							

                        <?php  }  ?>
                        
                        <?php if ($this->module_lib->hasActive('examination') && $this->studentmodule_lib->hasActive('examinations')) {?>
                        <li class="treeview <?php echo set_Topmenu('Examinations'); ?>">
                                <a href="#">
                                    <i class="fa fa-map-o ftlayer"></i> <span><?php echo $this->lang->line('examinations'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="<?php echo set_Submenu('examSchedule/index'); ?>"><a href="<?php echo base_url(); ?>user/examschedule"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('exam_schedule'); ?></a></li>
                                    <li class="<?php echo set_Submenu('examresult/index'); ?>"><a href="<?php echo base_url(); ?>user/exam/examresult"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('exam_result'); ?></a></li>
                                </ul>
                            </li>
                        <?php }?>

                        <?php if ($this->studentmodule_lib->hasActive('notice_board')) {
        ?>

                            <li class="treeview <?php echo set_Topmenu('notification'); ?>">
                                <a href="<?php echo base_url(); ?>user/notification">
                                    <i class="fa fa-envelope ftlayer"></i> <span><?php echo $this->lang->line('notice_board'); ?></span>
                                    <?php
$ntf = $this->customlib->getUserunreadNotification();
        if ($ntf) {
            ?>
                                        <small class="label pull-right bg-red" style="margin-top: -7px;">
                                            <?php echo $ntf; ?>
                                        </small>
                                        <?php
}
        ?>
                                </a>
                            </li>
                        <?php }?>

                        <?php if ($this->studentmodule_lib->hasActive('teachers_rating')) {?>
                            <li class="<?php echo set_Topmenu('Teachers'); ?>"><a href="<?php echo base_url(); ?>user/teacher"><i class="fa fa-user-secret ftlayer"></i> <span><?php echo $this->lang->line('teachers_reviews'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->module_lib->hasActive('library') && $this->studentmodule_lib->hasActive('library')) {?>
                            <li class="treeview <?php echo set_Topmenu('Library'); ?>">
                                <a href="#">
                                    <i class="fa fa-book ftlayer"></i> <span><?php echo $this->lang->line('library'); ?></span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="<?php echo set_Submenu('book/index'); ?>">
                                        <a href="<?php echo base_url(); ?>user/book">
                                            <i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('books'); ?></a>
                                    </li>
                                    <li class="<?php echo set_Submenu('book/issue'); ?>">
                                        <a href="<?php echo base_url(); ?>user/book/issue">
                                            <i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('book_issued'); ?></a>
                                    </li>
                                </ul>
                            </li>
                        <?php }?>

                        <?php if ($this->module_lib->hasActive('transport') && $this->studentmodule_lib->hasActive('transport_routes')) {?>
                            <li class="<?php echo set_Submenu('route/index'); ?>"><a href="<?php echo base_url(); ?>user/route"><i class="fa fa-bus ftlayer"></i> <span><?php echo $this->lang->line('transport_routes'); ?></span></a></li>
                        <?php }?>

                        <?php if ($this->module_lib->hasActive('hostel') && $this->studentmodule_lib->hasActive('hostel_rooms')) {?>
                            <li class="<?php echo set_Submenu('hostelroom/index'); ?>"><a href="<?php echo base_url(); ?>user/hostelroom"><i class="fa fa-building-o ftlayer"></i> <span><?php echo $this->lang->line('hostel_rooms'); ?></span></a></li>
                        <?php }?>
                    </ul>

                </section>
                <?php } elseif ($role == 'guest') {?>
                <section class="sidebar">
                    <ul class="sidebar-menu " id="sibe-box">
                        <li class="<?php echo set_Topmenu('user/guestprofile'); ?>"><a href="<?php echo base_url(); ?>user/studentcourse/profile"><i class="fa fa-user-plus ftlayer"></i> <span><?php echo $this->lang->line('my_profile'); ?></span></a></li>

                        <li class="<?php echo set_topmenu('user/studentcourse'); ?>"><a href="<?php echo base_url(); ?>user/studentcourse"><i class="fa fa-file-video-o ftlayer"></i> <span><?php echo $this->lang->line('online_course'); ?></span></a></li>

                        <li class="<?php echo set_topmenu('user/purchasehistory'); ?>"><a href="<?php echo base_url(); ?>user/studentcourse/purchasehistory"><i class="fa fa-file-video-o ftlayer"></i> <span><?php echo $this->lang->line('purchase_history'); ?></span></a></li>

                    </ul>
                 </section>
                <?php }?>
            </aside>
            <script>
                 function set_languages(lang_id){

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>user/user/user_language/"+lang_id,
                    data: {},
                    //dataType: "json",
                    success: function (data) {
                        successMsg("<?php echo $this->lang->line('status_change_successfully'); ?>");
                       window.location.reload('true');

                    }
                });

                     }
            </script>