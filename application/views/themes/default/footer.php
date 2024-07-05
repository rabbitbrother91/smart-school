<?php $cookie_consent = $this->customlib->cookie_consent();
if (!empty($cookie_consent)) {?>
<div id="cookieConsent" class="cookieConsent">
    <?php echo $cookie_consent; ?> <a href="<?php echo base_url() . "page/cookie-policy" ?>" target="_blank" ></a> <a onclick="setsitecookies()" class="cookieConsentOK"><?php echo $this->lang->line('accept') ?></a>
</div>
<?php }?>

<footer>
<?php if ($this->module_lib->hasModule('online_course') && $this->module_lib->hasActive('online_course')) { ?>        
    <script src="<?php echo base_url(); ?>backend/js/online_course.js"></script>           
<?php } ?>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-6">
                <h3 class="fo-title"><?php echo $this->lang->line('links'); ?></h3>
                <ul class="f1-list">
                    <?php
foreach ($footer_menus as $footer_menu_key => $footer_menu_value) {

    $cls_menu_dropdown = "";
    if (!empty($footer_menu_value['submenus'])) {

        $cls_menu_dropdown = "dropdown";
    }
    ?>
                        <li class="<?php echo $cls_menu_dropdown; ?>">
                            <?php
$top_new_tab = '';
    $url         = '#';
    if ($footer_menu_value['open_new_tab']) {
        $top_new_tab = "target='_blank'";
    }
    if ($footer_menu_value['ext_url']) {
        $url = $footer_menu_value['ext_url_link'];
    } else {
        $url = site_url($footer_menu_value['page_url']);
    }
    ?>
                            <a href="<?php echo $url; ?>" <?php echo $top_new_tab; ?>><?php echo $footer_menu_value['menu']; ?></a>
                            <?php
?>
                        </li>
                        <?php
}
?>
                </ul>
            </div><!--./col-md-3-->
            <div class="col-md-4 col-sm-6">
                <h3 class="fo-title"><?php echo $this->lang->line('follow_us'); ?></h3>
                <ul class="social">
                    <?php $this->view('/themes/default/social_media');?>
                </ul>
            </div><!--./col-md-3-->
            <div class="col-md-4 col-sm-6">
                <h3 class="fo-title"><?php echo $this->lang->line('contact'); ?></h3>
                <ul class="co-list">
                    <li><i class="fa fa-envelope"></i>
                        <a href="mailto:<?php echo $school_setting->email; ?>"><?php echo $school_setting->email; ?></a></li>
                    <li><i class="fa fa-phone"></i><?php echo $school_setting->phone; ?></li>
                    <li><i class="fa fa-map-marker"></i><?php echo $school_setting->address; ?></li>
                </ul>
            </div><!--./col-md-3-->
            <div class="col-md-3 col-sm-6">
                <a class="twitter-timeline" data-tweet-limit="1" href="#"></a>
            </div><!--./col-md-3-->
        </div><!--./row-->
    </div><!--./container-->

    <div class="copy-right">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 text-center">
                    <p><?php echo $front_setting->footer_text; ?></p>
                </div>
            </div><!--./row-->
        </div><!--./container-->
    </div><!--./copy-right-->
    <a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
</footer>
<script>
    function setsitecookies() {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>welcome/setsitecookies",
            data: {},
            success: function (data) {
                $('.cookieConsent').hide();
            }
        });
    }

    function check_cookie_name(name)
    {
        var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        if (match) {
            console.log(match[2]);
            $('.cookieConsent').hide();
        }
        else{
           $('.cookieConsent').show();
        }
    }
    check_cookie_name('sitecookies');
</script>