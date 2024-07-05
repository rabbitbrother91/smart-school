<?php //$currency_symbol = $this->customlib->getSchoolCurrencyFormat();?>
<header>
    <link href="<?php echo base_url(); ?>backend/toast-alert/toastr.css" rel="stylesheet"/>
    <script src="<?php echo base_url(); ?>backend/toast-alert/toastr.js"></script>
    
<style type="text/css">

form .form-bottom button.btn {
    min-width: 105px;
}

form .form-bottom .input-error {
    border-color: #d03e3e;
    color: #d03e3e;
}

form.gauthenticate-form {
    display: none;
}
</style>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-3">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand logo" href="<?php echo base_url(); ?>"><img src="<?php echo base_url($front_setting->logo); ?>" alt=""/></a>
                    </div>

                    <div class="collapse navbar-collapse" id="navbar-collapse-3">
                        <ul class="nav navbar-nav navbar-right">
                            <?php  
                            foreach ($main_menus as $menu_key => $menu_value) {
                                $submenus = false;
                                $cls_menu_dropdown = "";
                                $menu_selected = "";                               
                                
                                
                                
                                if ($menu_value['page_slug'] == $active_menu) {
                                    $menu_selected = "active";
                                }
                                if (!empty($menu_value['submenus'])) {
                                    $submenus = true;
                                    $cls_menu_dropdown = "dropdown";
                                }
                                ?>

                                <li class="<?php echo $menu_selected . " " . $cls_menu_dropdown; ?>" >
                                    <?php
                                    if (!$submenus) {
                                        $top_new_tab = '';
                                        $url = '#';

                                        if ($menu_value['open_new_tab']) {
                                            $top_new_tab = "target='_blank'";
                                        }
                                        if ($menu_value['ext_url']) {
                                            $url = $menu_value['ext_url_link'];
                                        } else {
                                            $url = site_url($menu_value['page_url']);
                                        }
                                        ?>

                                        <a href="<?php echo $url; ?>" <?php echo $top_new_tab; ?>><?php echo $menu_value['menu']; ?></a>

                                        <?php
                                    } else {
                                        $child_new_tab = '';
                                        $url = '#';
                                        ?>
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $menu_value['menu']; ?> <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <?php
                                            foreach ($menu_value['submenus'] as $submenu_key => $submenu_value) {
                                                if ($submenu_value['open_new_tab']) {
                                                    $child_new_tab = "target='_blank'";
                                                }
                                                if ($submenu_value['ext_url']) {
                                                    $url = $submenu_value['ext_url_link'];
                                                } else {
                                                    $url = site_url($submenu_value['page_url']);
                                                }
                                                ?>
                                                <li><a href="<?php echo $url; ?>" <?php echo $child_new_tab; ?> ><?php echo $submenu_value['menu'] ?></a></li>
                                                <?php
                                            }
                                            ?>

                                        </ul>

                                        <?php
                                    }
                                    ?>

                                </li>
                                <?php
                            }
                            ?>

                        </ul>
                    </div><!-- /.navbar-collapse -->
                </nav><!-- /.navbar -->
            </div>
        </div>
    </div>   
</header> 

<!---   Guest Signup  --->
<div id="myModal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-small">
                <button type="button" class="close closebtnmodal" data-dismiss="modal">&times;</button>
                <h4 ><?php echo $this->lang->line('guest_registration') ?></h4>
            </div>
            <form action="<?php echo base_url() . 'course/guestsignup' ?>" method="post" class="signupform" id="signupform">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                        <input type="text" class="form-control reg_name" name="name" id="name" autocomplete="off">
                        <span class="text-danger" id="error_refno"></span>
                    </div>
                    <div class="form-group mb10">
                        <label><?php echo $this->lang->line('email_id'); ?></label><small class="req"> *</small>
                        <input type="text"  class="form-control reg_email"  name="email" id="email" autocomplete="off" >
                        <span class="text-danger" id="error_dob"></span>
                    </div>
                    <div class="form-group mb10">
                        <label><?php echo $this->lang->line('password'); ?></label><small class="req"> *</small>
                        <input type="password"  class="form-control reg_password"  name="password" id="password" autocomplete="off" >
                        <span class="text-danger" id="error_dob"></span>
                    </div>
                    <div id="load_signup_captcha"></div>                    
                </div>
                <div class="modal-footer">
                    <button type="button"  class="modalclosebtn btn  mdbtn" onclick="openmodal()"><?php echo $this->lang->line('login'); ?></button>
                    <button type="submit" id="signupformbtn" class="onlineformbtn mdbtn" data-loading-text="<i class='fa fa-spinner fa-spin '></i> wait..."><?php echo $this->lang->line('signup'); ?> </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!---   Guest Login  --->
<div id="loginmodal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-header-small">
                <button type="button" class="close closebtnmodal" data-dismiss="modal">&times;</button>
                <h4 ><?php echo $this->lang->line('guest').' '.$this->lang->line('login') ?> </h4>
            </div>
            <div>               
         
            <form action="<?php echo site_url('course/guestlogin') ?>" method="post" class="loginform" id="loginform" autocomplete="off">
                <div class="modal-body">
                    <div class="form-group mb10">
                        <label><?php echo $this->lang->line('email_id'); ?></label><small class="req"> *</small>
                        <input type="text"  class="form-control login_email"  name="username" id="username" autocomplete="off">
                        <span class="text-danger" id="error_dob"></span>
                    </div>
                    <div class="form-group mb10">
                        <label><?php echo $this->lang->line('password'); ?></label><small class="req"> *</small>
                        <input type="password"  class="form-control login_password"  name="password" id="password" autocomplete="off" >
                        <input type="hidden"  class="form-control "  name="checkout_status" id="checkout_status"  autocomplete="off" >
                        <span class="text-danger" id="error_dob"></span>
                    </div>             

                    <div id="load_login_captcha"></div>
                </div>
                <div class="modal-footer">

                    <a href="#" class="pull-left forgotbtn" data-toggle="modal" data-target="#forgotmodal"><i class="fa fa-key"></i> <?php echo $this->lang->line('forgot_password'); ?></a>

                    <button type="button" class="signup modalclosebtn btn mdbtn" data-dismiss="modal"><?php echo $this->lang->line('signup'); ?> </button>
                    <button type="submit" id="loginformbtn" class="onlineformbtn mdbtn" data-loading-text="<i class='fa fa-spinner fa-spin '></i> wait..."><?php echo $this->lang->line('submit'); ?></button>

                </div>
            </form>

            <form action="<?php echo site_url('course/user_submit_login') ?>" method="post" class="gauthenticate-form" id="gauthenticate-form">
                <div class="modal-body">
                   
                  <div class="form-group mb10">
                        <label><?php echo $this->lang->line('verification_code'); ?></label><small class="req"> *</small>
                        <input type="text"  class="form-control gauth_code"  name="gauth_code" id="gauth_code" autocomplete="off" >                       
                        <span class="text-danger" id="error_gauth_code"></span>
                    </div>                    
                </div>
                <div class="modal-footer">
                    <a href="#" class="pull-left forgotbtn" data-toggle="modal" data-target="#forgotmodal"><i class="fa fa-key"></i> <?php echo $this->lang->line('forgot_password'); ?></a>
                    <button type="button" class="signup modalclosebtn btn mdbtn" data-dismiss="modal"><?php echo $this->lang->line('signup'); ?> </button>
                    <button type="submit" id="loginformbtn" class="onlineformbtn mdbtn" data-loading-text="<i class='fa fa-spinner fa-spin '></i> wait..."><?php echo $this->lang->line('submit'); ?></button>
                </div>
            </form>
   </div>

        </div>
    </div>
</div>

<div id="forgotmodal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-header-small">
                <button type="button" class="close closebtnmodal" data-dismiss="modal">&times;</button>
                <h4 class=><?php echo $this->lang->line('forgot_password'); ?> </h4>
            </div>
            <form action="#" method="post" class="forgotform" id="forgotform">
                <div class="modal-body">
                    <div class="form-group mb10">
                        <label><?php echo $this->lang->line('email_id'); ?></label><small class="req"> *</small>
                        <input type="email" class="form-control" name="username" id="email" autocomplete="off">
                        <span class="text-danger" id="error_email"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"  class="modalclosebtn btn  mdbtn" onclick="openmodal()"><?php echo $this->lang->line('login'); ?></button>                    
                    <button type="submit" id="forgotformbtn" class="onlineformbtn mdbtn" data-loading-text="<i class='fa fa-spinner fa-spin '></i> wait..." ><?php echo $this->lang->line('submit'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div> 
<script>
$(document).ready(function () { 
    $('#myModal,#forgotmodal,#loginmodal').modal({
        backdrop: 'static',
        keyboard: false,
        show: false
    });
});
</script>