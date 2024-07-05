<div id="navbar_top">
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
        
        <?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat();?>
        <div class="container">
            <div class="row">
                <nav class="navbar">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-3">
                            <span class="sr-only"><?php echo $this->lang->line('toggle_navigation'); ?></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand logo" href="<?php echo base_url(); ?>"><img src="<?php echo base_url($front_setting->logo); ?>" alt=""></a>
                    </div>

                    <div class="collapse navbar-collapse navborder" id="navbar-collapse-3">
                        <ul class="nav navbar-nav navbar-right">
                            <?php
foreach ($main_menus as $menu_key => $menu_value) {
    $submenus          = false;
    $cls_menu_dropdown = "";
    $menu_selected     = "";
    if ($menu_value['page_slug'] == $active_menu) {
        $menu_selected = "active";
    }
    if (!empty($menu_value['submenus'])) {
        $submenus          = true;
        $cls_menu_dropdown = "dropdown";
    }
    ?>

                                <li class="<?php echo $menu_selected . " " . $cls_menu_dropdown; ?>" >
                                    <?php
if (!$submenus) {
        $top_new_tab = '';
        $url         = '#';
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
        $url           = '#';
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
}    ?>
                
                            <?php if ($this->module_lib->hasModule('online_course') && $this->module_lib->hasActive('online_course')) { ?>
                            <?php if($active_menu == 'online_course' && (!empty($currencies))){ ?>
                            <li class="menuinlinemobile">
                                <div class="currency-icon-list">
                                    <select class="languageselectpicker currency_list" type="text" id="currencySwitcher" name="currency">
                                    <?php 
                                        foreach ($currencies as $currencie_key => $currencie_value) {  
                                    ?>
                                        <option value="<?php echo $currencie_value->id; ?>" <?php
                                        if ( $currencie_value->id == $this->customlib->getSchoolCurrency()) {
                                            echo "Selected";
                                        }
                                        ?> ><?php echo $currencie_value->short_name." (".$currencie_value->symbol.")"; ?></option>
                                    <?php
                                    }
                                    ?>
                                    </select>
                                </div>
                            </li>
                            <?php } ?>                             
                            
                    <li class="dropdown menuinlinemobile" id="">
                        <?php if($active_menu == 'online_course'){ ?>
                        <div class="shop-chart" data-toggle="dropdown">
                            <a href="#" class="dropdown-toggle"  >
                                <i class="fa fa-shopping-cart"></i>
                                <?php
                                    $cartdata = $this->cart->contents();
                                    if (!empty($cartdata)) {if (sizeof($cartdata) > 0) {?>
                                        <span id="item_count_replace" ><?php echo count($cartdata); ?></span>                                        
                                    <?php }}?>
                                <span id="item_count_replace_show" class="hide" ></span>
                            </a>
                        </div>
                        <?php } ?>
                        <div class="dropdown-menu shop-chart-top13">
                          <div class="cart-wrapper cart-list" id="card_data_list_hide">
                            <ul>
                                <div id="card_data_list"></div>
                            </ul>
                          </div>
                          <div class="cart-wrapper cart-list" id="card_data_list_show">
                            <ul>
                                <?php
$total = 0;
$count = 0;

if (!empty($cartdata)) {
    foreach ($cartdata as $key => $cvalue) {
        $course_data = $this->customlib->getCourseDetail($cvalue['id']);

        if (!empty($course_data)) {
            $total += $cvalue['price'];
            ++$count;
            $course_price = amountFormat($cvalue['price']); 

            if ($course_data["course_thumbnail"] != '') {
                $thumbnail = base_url() . "uploads/course/course_thumbnail/" . $course_data["course_thumbnail"];
            } else {
                $thumbnail = base_url() . 'backend/images/wordicon.png';
            }

            ?>
            <li>
                      <div class="cartitem">
                        <div class="item-image">
                            <a href="<?php echo base_url(); ?>course/coursedetail/<?php echo $course_data["slug"]; ?>"><img src="<?php echo $thumbnail; ?>" alt="" class="img-responsive"></a>
                        </div>
                        <div class="cartdetail">
                            <a href="<?php echo base_url(); ?>course/coursedetail/<?php echo $course_data["slug"]; ?>">
                                <div class="course-name"><?php echo $course_data['title'] ?></div>
                                <div class="courseprice"><?php echo $currency_symbol . $course_price ?> </div>
                            </a>
                            <a class="btn btn-warning btn-xs pull-right" title="<?php echo $this->lang->line('delete'); ?>" onclick="removecartheader('<?php echo $cvalue['rowid']; ?>')"><i class="fa fa-trash-o"></i></a>
                        </div>
                      </div>
                    </li>
    <?php }}}?>
                            </ul>
                          </div>
                        <div class="cart-footer">
                            <div class="focarttotal-price">
                                <b id="card_total_amount">
                                <?php
if ($this->cart->total() == 0) {
    $course_total = 0;
} else {
    $course_total = $this->cart->total();
}
$course_total = amountFormat($course_total);

echo $this->lang->line('total') . " " . $currency_symbol . $course_total;

if (empty($cartdata)) {
echo "<p><small>".$this->lang->line('your_cart_is_empty_please_add_courses')."</small></p>"; 
}
?>
                                </b>
                                <span id="total_course">
                                    <?php
if (!empty($cartdata)) {
    echo $this->cart->total_items() . ' ' . $this->lang->line('item');
}
?>
                                </span>
                            </div> 
                            <a href="<?php echo base_url() . "cart" ?>" class="gotocartbtn btn btn-success"><?php echo $this->lang->line('go_to_cart') ?></a>
                        </div><!--./cart-footer-->
                        </div>
                    </li>
                    <?php } ?>
                     
                            <li>
                            
                            <?php 
                                $guest_login = 0;
                                if (empty($course_setting->guest_login)) { 
                                    $guest_login = 0;
                                }else{ 
                                    $guest_login = $course_setting->guest_login; 
                                } 
                            ?>
                            
                            <?php if ($this->module_lib->hasModule('online_course') && $this->module_lib->hasActive('online_course')) { 
                            if($guest_login == 0){
                                if ($setting_data[0]['student_panel_login']) {?>
                                
                                    <a class="login-btn" href="<?php echo base_url(); ?>site/userlogin"><i class="fa fa-user"></i><?php echo $this->lang->line('login'); ?></a> 
                                 
                                <?php }
                                
                            }else{
                            
                                if (!$this->session->userdata('student')) {  ?>                                
                                    <a class="login-btn" onclick="openmodal()"><i class="fa fa-user"></i><?php echo $this->lang->line('login'); ?></a>
                                <?php } else {?>                                
                                    <a class="login-btn" href="<?php echo base_url(); ?>course/logout"><?php echo $this->lang->line('logout'); ?></a>
                                <?php }
                            }
                                
                            }else{ 
                                if ($setting_data[0]['student_panel_login']) {?>
                                
                                <a class="login-btn" href="<?php echo base_url(); ?>site/userlogin"><i class="fa fa-user"></i><?php echo $this->lang->line('login'); ?></a> 
                                 
                                <?php } } ?>
                            </li>                               
                               
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </nav><!-- /.navbar -->
            </div>
        </div>
    </header>
</div>

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
                    <button type="submit" id="signupformbtn" class="onlineformbtn mdbtn" ><?php echo $this->lang->line('signup'); ?> </button>
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
            <form action="<?php echo site_url('course/guestlogin') ?>" method="post" class="loginform" id="loginform">
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
                    <button type="submit" id="loginformbtn" class="onlineformbtn mdbtn" ><?php echo $this->lang->line('submit'); ?></button>
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

<div id="forgotmodal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-header-small">
                <button type="button" class="close closebtnmodal" data-dismiss="modal">&times;</button>
                <h4 class=><?php echo $this->lang->line('forgot_password'); ?> </h4>
            </div>
            <form action="#" method="post" class="loginform" id="forgotform">
                <div class="modal-body">
                    <div class="form-group mb10">
                        <label><?php echo $this->lang->line('email_id'); ?></label><small class="req"> *</small>
                        <input type="email" class="form-control" name="username" id="email" autocomplete="off">
                        <span class="text-danger" id="error_email"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"  class="modalclosebtn btn  mdbtn" onclick="openmodal()"><?php echo $this->lang->line('login'); ?></button>                    
                    <button type="submit" id="forgotformbtn" class="onlineformbtn mdbtn" ><?php echo $this->lang->line('submit'); ?></button>
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
<script>
    $(document).on('change','.currency_list',function(e){ 
        let currency_id=$(this).val();
        $.ajax({
            type: 'POST',
            url: base_url+'welcome/changeCurrencyFormat',
            data: {'currency_id':currency_id},
            dataType: 'json',
            beforeSend: function() {
                
            },
            success: function(data) {          
                window.location.reload();
            },
            error: function(xhr) { // if error occured
        
            },
            complete: function() {
                
            }
        
        });
    });
</script>