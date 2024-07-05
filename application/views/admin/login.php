<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#424242" />
        <title>Login : <?php echo $name; ?></title>        
        <link href="<?php echo base_url(); ?>uploads/school_content/admin_small_logo/<?php echo $this->setting_model->getAdminsmalllogo(); ?>" rel="shortcut icon" type="image/x-icon">
        <!-- CSS -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/css/form-elements.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/css/style.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/css/jquery.mCustomScrollbar.min.css">
    </head>
    <body>
        <!-- Top content -->
        <div class="top-content">
            <div class="inner-bg">
                <div class="container-fluid">
                    <div class="row">
                        <?php
                        
                        $offset = "";
                        $bgoffsetbg = "bgoffsetbg";
                        $bgoffsetbgno = "";
                        if (empty($notice)) {
                            
                            // $offset = "col-md-offset-4";
                            // $bgoffsetbg = "";
                            // $bgoffsetbgno = "bgoffsetbgno";
                        }
                        ?>  
                        <div class="<?php echo $bgoffsetbg; ?>">  
                            <div class="col-lg-4 col-md-4 col-sm-12 nopadding <?php echo $bgoffsetbgno; ?> <?php echo $offset; ?>">
                                 
                                <div class="loginbg">  
                                   <div class="form-top">
                                        <div class="form-top-left logowidth">
                                            <img src="<?php echo base_url(); ?>uploads/school_content/admin_logo/<?php echo $this->setting_model->getAdminlogo(); ?>" />    
                                        </div>
                                    </div>
                                    <div class="form-bottom">
                                        <h3 class="font-white bolds"><?php echo $this->lang->line('admin_login'); ?></h3>
                                        <?php
                                        if (isset($error_message)) {
                                            echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                        }
                                        ?>
                                        <?php
                                        if ($this->session->flashdata('message')) {
                                            echo "<div class='alert alert-success'>" . $this->session->flashdata('message') . "</div>";
                                            $this->session->unset_userdata('message'); 
                                        };
                                        ?>
                                        <?php
                                        if ($this->session->flashdata('disable_message')) {
                                            echo "<div class='alert alert-danger'>" . $this->session->flashdata('disable_message') . "</div>";
                                            $this->session->unset_userdata('disable_message'); 
                                        };
                                        ?>
                                        <form action="<?php echo site_url('site/login') ?>" method="post">
                                            <?php echo $this->customlib->getCSRF(); ?>
                                            <div class="form-group has-feedback">                                            
                                                <input type="text" name="username" placeholder="<?php echo $this->lang->line('username'); ?>" value="<?php echo set_value('username') ?>" class="form-username form-control" id="form-username">
                                                <span class="fa fa-envelope form-control-feedback"></span>
                                                <span class="text-danger"><?php echo form_error('username'); ?></span>
                                            </div>
                                            <div class="form-group has-feedback">                      
                                                <input type="password" value="<?php echo set_value('password') ?>" name="password" placeholder="<?php echo $this->lang->line('password'); ?>" class="form-password form-control" id="form-password">
                                                <span class="fa fa-lock form-control-feedback"></span>
                                                <span class="text-danger"><?php echo form_error('password'); ?></span>
                                            </div>
                                            <?php if($is_captcha){ ?>
                                            <div class="form-group has-feedback row"> 
                                                <div class='col-lg-7 col-md-12 col-sm-6'>
                                                    <span id="captcha_image"><?php echo $captcha_image; ?></span>
                                                    <span title='Refresh Catpcha' class="fa fa-refresh catpcha" onclick="refreshCaptcha()"></span>
                                                </div>
                                                <div class='col-lg-5 col-md-12 col-sm-6'>
                                                    <input type="text" name="captcha" placeholder="<?php echo $this->lang->line('captcha'); ?>" class=" form-control" autocomplete="off" id="captcha"> 
                                                    <span class="text-danger"><?php echo form_error('captcha'); ?></span>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <button type="submit" class="btn"><?php echo $this->lang->line('sign_in'); ?></button>
                                        </form>
                                        <a href="<?php echo site_url('site/forgotpassword') ?>" class="forgot"><i class="fa fa-key"></i> <?php echo $this->lang->line('forgot_password'); ?>?</a>
                                    </div>
                                </div>
                            </div>
                            
                                <div class="col-lg-8 col-md-8 col-sm-12 nopadding-2">
                                   <div class="d-flex align-items-center text-wrap flex-column justify-content-center bg-position-sm-left bg-position-lg-center" style="background: url('<?php echo base_url(); ?>uploads/school_content/login_image/<?php echo $school['admin_login_page_background']; ?>') no-repeat; background-size:cover">  
                                    <div class="<?php if ($notice){ ?> bg-shadow-remove <?php } ?>">
                                    <?php
                                        if ($notice) {
                                    ?>
                                   
                                    <h3 class="h3"><?php echo $this->lang->line('whats_new_in'); ?> <?php echo $school['name']; ?>   </h3>
                                    <div class="loginright mCustomScrollbar">
                                        <div class="messages"> 

                                            <?php
                                            foreach ($notice as $notice_key => $notice_value) {
                                                ?>
                                                <h4><?php echo $notice_value['title']; ?></h4>

                                                <?php
                                                $string = ($notice_value['description']);
                                                $string = strip_tags($string);
                                                if (strlen($string) > 100) {

                                                    // truncate string
                                                    $stringCut = substr($string, 0, 100);
                                                    $endPoint = strrpos($stringCut, ' ');

                                                    //if the string doesn't contain any space then it will cut without word basis.
                                                    $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                                    $string .= '... <a class=more href="' . site_url('read/' . $notice_value['slug']) . '" target="_blank">' . $this->lang->line('read_more') . '  </a>';
                                                }
                                                echo '<p>' . $string . '</p>';
                                                ?>

                                                <div class="logdivider"></div>
                                                <?php
                                            }
                                            ?>
                                        </div>  
                                    </div>
                                    <?php
                                        }
                                    ?>
                                  </div>  
                                  </div>  
                                </div><!--./col-lg-6-->
                                
                        </div>  
                    </div>
                </div>
            </div>
        </div>
        <!-- Javascript -->
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery-1.11.1.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery.backstretch.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery.mCustomScrollbar.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery.mousewheel.min.js"></script>
    </body>
</html>
<script type="text/javascript">
    $(document).ready(function () {
        $('.login-form input[type="text"], .login-form input[type="password"], .login-form textarea').on('focus', function () {
            $(this).removeClass('input-error');
        });
        $('.login-form').on('submit', function (e) {
            $(this).find('input[type="text"], input[type="password"], textarea').each(function () {
                if ($(this).val() == "") {
                    e.preventDefault();
                    $(this).addClass('input-error');
                } else {
                    $(this).removeClass('input-error');
                }
            });
        });
    });
</script>
<script type="text/javascript">
    function refreshCaptcha(){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('site/refreshCaptcha'); ?>",
            data: {},
            success: function(captcha){
                $("#captcha_image").html(captcha);
            }
        });
    }    
</script>