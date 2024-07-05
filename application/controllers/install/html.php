<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Smart School - Installation</title>
        <link href="<?php echo base_url(); ?>backend/images/s-favican.png" rel="shortcut icon" type="image/x-icon">
        <link href="<?php echo site_url(); ?>backend/installer_template/assets/css/reset.css" rel="stylesheet">
        <link href="<?php echo site_url(); ?>backend/installer_template/assets/css/bootstrap.min.css" rel="stylesheet">
        <link href='<?php echo site_url(); ?>backend/installer_template/assets/css/open-sans.css' rel='stylesheet' type='text/css'>
        <link href='<?php echo site_url(); ?>backend/installer_template/assets/css/bs-overides.css' rel='stylesheet' type='text/css'>
        <link href='<?php echo site_url(); ?>backend/installer_template/assets/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- danger: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
          <![endif]-->
        <style>
            body {
                font-family: Roboto, Sans-Serif,Arial;
            }
            .install-row {
                border:1px solid #e4e5e7;
                border-radius:2px;
                background:#fff;
                padding:15px;
            }
            .logo {
                /* margin-top: 5px;*/
                background: #444a52;
                padding: 10px 0;
                display: inline-block;
                width: 100%;
                /* border-radius: 5px; */
                margin-bottom: 5px;
                box-shadow: 0 3px 6px rgba(0,0,0,.16),0 3px 6px rgba(0,0,0,.23);
            }
            .logo img {
                display:block; width: 185px;
                /* margin:0 auto;*/
            }
            .control-label {
                font-weight:600;
            }
            .padding-10 {
                padding:10px;
            }
            .mbot15 {
                margin-bottom:15px;
            }
            .bg-default {
                background: #7eac05 !important;
                color:#fff;
                /*border:1px solid #FF8000;*/
            }
            .bg-not-passed {
                border:1px solid #e4e5e7;
                border-radius:2px;
            }
            .bold {
                font-weight:600;
            }
            .header{
                margin:0;
                color: white;
                text-align: left;
                font-weight: bold;
                padding-top:3px;
            }
            .newbox.new-box-primary {
                border-top-color: #faa21c;
                box-shadow: 0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);
            }
            .newbox {
                position: relative;
                padding: 10px 20px 20px;
                border-radius: 3px;
                background: #ffffff;
                border-top: 3px solid #d2d6de;
                margin-bottom: 20px;
                width: 100%;
                box-shadow: 0 1px 1px rgba(0,0,0,0.1);
                min-height:226px;
            }
            .new-info-box {
                display: block;
                position:relative;
                min-height: 100%;
                background: #444a52;
                width: 100%;
                box-shadow: 0 1px 4px rgba(0, 0, 0, 0.25);
                border-radius: 0px;
                margin-bottom: 10px;
                border: 2px solid #fff;
                color: #fff;padding: 5px;
            }
            .m1{ margin-top:20px;}
            .new-info-box i{
                position: absolute;
                left: 0;
                min-width: 50px;
                min-height: 100%;
                font-size: 24px;
                background: #f38000;
                top: 0;
                text-align: center;
                padding-top: 10px;
            }
            .new-ii{padding-top: 13px !important}
            .new-info-box h5{padding-left: 60px;}
            .form-control{ border: 0 !important;
                           border-bottom: 1px solid #ccc !important;
                           border-radius: 0; padding: 6px 0px !important;
                           box-shadow: none !important;}
            @media (min-width:768px) and (max-width:992px){
                .new-info-box h5{padding-left: 45px;}
                .new-info-box i{min-width:45px}
            }
        </style>
    </head>
    <body>
        <div class="logo">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <a href="<?php echo base_url(); ?>"><img src="../backend/images/s_logo.png"></a></div>
                        <div class="col-md-9"><h3 class="header">Smart School : School Management System - Installer</h3></div>
                    </div><!--./col-md-12-->
                </div><!--./row-->
            </div><!--./container-->
        </div><!--./row-->
        <div class="container">
            <div class="row">
                <div class="col-md-12 m1">
                    <div class="col-md-3 col-sm-3">
                        <div class="new-info-box <?php
                        if ($passed_steps[1] == true || $step == 1) {
                            echo 'bg-default';
                        }
                        ?> padding-10">
                            <i class="fa fa-list-alt h5i new-ii"></i> <h5>Requirements</h5>
                        </div>
                        <div class="new-info-box <?php
                        if ($passed_steps[2] || $step == 2) {
                            echo 'bg-default';
                        } else {
                            echo 'bg-not-passed';
                        }
                        ?> padding-10">
                            <i class="fa fa-database h5i"></i> <h5> Database</h5>
                        </div>
                        <div class="new-info-box <?php
                        if ($passed_steps[3] || $step == 3) {
                            echo 'bg-default';
                        } else {
                            echo 'bg-not-passed';
                        }
                        ?> padding-10">
                            <i class="fa fa-download h5i new-ii"></i>  <h5> Install</h5>
                        </div>
                        <div class="new-info-box <?php
                        if ($step == 4) {
                            echo 'bg-default';
                        } else {
                            echo 'bg-not-passed';
                        }
                        ?> padding-10">
                            <i class="fa fa-thumbs-up h5i"></i> <h5> Finish</h5>

                        </div>
                    </div><!--./col-md-3-->
                    <div class="col-md-9 col-sm-9">
                        <div class="newbox new-box-primary">
                            <p><?php echo $debug; ?></p>
                            <?php if (isset($error) && $error != '') { ?>
                                <div class="alert alert-danger text-left">
                                    <?php echo $error; ?>
                                </div>
                            <?php } ?>
                            <?php
                            if ($step == 1) {
                                include_once('requirements.php');
                            }
                            ?>
                            <?php if ($step == 2) { ?>
                                <?php echo form_open($this->uri->uri_string()); ?>
                                <?php echo form_hidden('step', $step); ?>
                                <div class="form-group">
                                    <label for="hostname" class="control-label">MySQL Hostname</label>
                                    <input type="text" class="form-control" name="hostname">
                                </div>
                                <div class="form-group">
                                    <label for="database" class="control-label">Database Name</label>
                                    <input type="text" class="form-control" name="database">
                                </div>
                                <div class="form-group">
                                    <label for="username" class="control-label">Database Username</label>
                                    <input type="text" class="form-control" name="username">
                                </div>
                                <div class="form-group">
                                    <label for="password" class="control-label">Database Password</label>
                                    <input type="password" class="form-control" name="password">
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Check Database</button>
                                </div>
                                <?php echo form_close(); ?>
                            <?php } else if ($step == 3) { ?>
                                <?php echo form_open($this->uri->uri_string()); ?>
                                <?php echo form_hidden('step', $step); ?>
                                <h3>Super Admin User Details</h3>
                                <div class="form-group">
                                    <label for="admin_email" class="control-label">Email (Login Username)</label>
                                    <input type="email" class="form-control" name="admin_email" id="admin_email">
                                </div>
                                <div class="form-group">
                                    <label for="admin_password" class="control-label">Password</label>

                                    <input type="password" class="form-control" name="admin_password" id="admin_password">
                                </div>
                                <div class="form-group">
                                    <label for="admin_passwordr" class="control-label">Confirm Password</label>
                                    <input type="password" class="form-control" name="admin_passwordr" id="admin_passwordr">
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Begin Install</button>
                                </div>
                                <?php echo form_close(); ?>
                            <?php } else if ($step == 4) { ?>
                                <h4 class="bold">Installation Successful!</h4>
                                <p>Due to security reasons you must delete the install directory.</p>
                                <a href="<?php echo site_url('install/start/delete_install_dir'); ?>" class="btn btn-primary">Delete Install Directory and Login to Admin Panel</a>
                                <br><br><br><br><br><br><br>
                                <p><b>Please note that in Smart School there are two login panels -</b></p>
                                <ul style="list-style: disc; margin-left: 15px;">
                                    <li><span style="color: #0084B4; padding-right: 5px;"><?php echo base_url(); ?>site/login</span> Admin Panel : this panel is used for staff like superadmin, admin, teacher, accountant, librarian, receptionist login.</li>
                                    <li><span style="color: #0084B4; padding-right: 5px;"><?php echo base_url(); ?>site/userlogin</span> User Panel : this panel is used for student or parent login.</li>
                                </ul>
                            <?php } ?>
                        </div><!--./newbox.new-box-primary-->
                    </div>
                </div><!--./col-md-12-->
            </div><!--./row-->
        </div><!--./container-->
    </body>
</html>
