<!DOCTYPE html>
<html dir="<?php echo ($front_setting->is_active_rtl) ? "rtl" : "ltr"; ?>" lang="<?php echo ($front_setting->is_active_rtl) ? "ar" : "en"; ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $page['title']; ?></title>        
        <meta name="title" content="<?php if(isset($page['meta_title'])){ echo $page['meta_title']; } ?>">
        <meta name="keywords" content="<?php if(isset($page['meta_keyword'])){ echo $page['meta_keyword']; } ?>">
        <meta name="description" content="<?php if(isset($page['meta_description'])){ echo $page['meta_description']; } ?>">  
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="<?php echo base_url($front_setting->fav_icon); ?>" type="image/x-icon">
        <link href="<?php echo $base_assets_url; ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo $base_assets_url; ?>css/owl.carousel.css" rel="stylesheet">
        <link href="<?php echo $base_assets_url; ?>css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo $base_assets_url; ?>css/style.css" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/ss-print.css">
        <link rel="stylesheet" href="<?php echo $base_assets_url; ?>datepicker/bootstrap-datepicker3.css"/>
        
        <script src="<?php echo base_url(); ?>backend/dist/js/moment.min.js"></script>
        <!--file dropify-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/dropify.min.css">
        <script src="<?php echo base_url(); ?>backend/custom/jquery.min.js"></script>
        <!--file dropify-->
        <script src="<?php echo base_url(); ?>backend/dist/js/dropify.min.js"></script>
        <script type="text/javascript">
            var base_url = "<?php echo base_url() ?>";
        </script>        
       
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>backend/dist/css/bootstrap-select.min.css">
        <script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/bootstrap-select.min.js"></script>
        <script type="text/javascript">
        $(function () {
            $('.languageselectpicker').selectpicker();
        });
        </script>
        
        <?php        

        if ($front_setting->is_active_rtl) {
            ?>
            <link href="<?php echo $base_assets_url; ?>rtl/bootstrap-rtl.min.css" rel="stylesheet">
            <link href="<?php echo $base_assets_url; ?>rtl/style-rtl.css" rel="stylesheet">
            <?php
        }
        ?>
        
        <?php if ($this->module_lib->hasModule('online_course') &&  $this->module_lib->hasActive('online_course') ) { ?>
        <link rel="stylesheet" href="<?php echo $base_assets_url; ?>css/online-course.css"/>        
        <?php } ?>
        
        <?php echo $front_setting->google_analytics; ?>
    </head>
    <body>
    <div id="alert" class="">  
      <div class="topsection">  
        <section class="newsarea">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="newscontent">
                            <?php
                            if (in_array('news', json_decode($front_setting->sidebar_options))) {
                                ?>
                                <div class="newstab"><?php echo $this->lang->line('latest_news'); ?></div>
                                <div class="newscontent">
                                    <marquee class="" behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
                                        <ul id="" class="" >
                                            <?php
                                            if (!empty($banner_notices)) {
                                                foreach ($banner_notices as $banner_notice_key => $banner_notice_value) {
                                                    ?>
                                                    <li><a href="<?php echo site_url('read/' . $banner_notice_value['slug']) ?>">
                                                            <div class="datenews">
                                                                <?php
                                                                echo date('d', strtotime($banner_notice_value['date'])) . " " . $this->lang->line(strtolower(date('F', strtotime($banner_notice_value['date'])))) . " " . date('Y', strtotime($banner_notice_value['date']));
                                                                ?>
                                                                <span>
                                                                </span>
                                                            </div><?php echo $banner_notice_value['title']; ?>
                                                        </a></li>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </marquee>
                                </div><!--./newscontent-->
                                <?php
                            }
                            ?>
                        </div><!--./sidebar-->
                    </div><!--./col-md-12-->
                </div>
            </div>
        </section>
        <div class="toparea">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <ul class="toplist">
                            <li><a href="mailto:<?php echo $school_setting->email; ?>"><i class="fa fa-envelope-o"></i><?php echo $school_setting->email; ?></a>
                        </ul>                        
                    </div><!--./col-md-5-->
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <ul class="topicon">
                            <li><?php echo $this->lang->line('follow_us'); ?></li>
                            <?php $this->view('/themes/darkgray/social_media'); ?>
                        </ul>
                    </div><!--./col-md-6-->
                </div>
            </div>
        </div><!--./toparea-->
     </div><!--./topsection-->   

        <?php echo $header; ?>
        <?php echo $slider; ?>
        <?php if (isset($featured_image) && $featured_image != "") {
            ?>
            <?php
        }
        ?>

        <div class="container spacet60">
            <div class="row">
                <?php
                $page_colomn = "col-md-12 spacet60 pt-0-mobile";
                if ($page_side_bar) {
                    $page_colomn = "col-md-9 col-sm-9";
                }
                ?>
                <div class="<?php echo $page_colomn; ?>">
                    <?php echo $content; ?>
                </div>
                <?php
                if ($page_side_bar) {
                    ?>

                    <div class="col-md-3 col-sm-3">
                        <div class="sidebar">
                            <?php
                            if (in_array('news', json_decode($front_setting->sidebar_options))) {
                                ?>
                                <div class="catetab"><?php echo $this->lang->line('latest_news'); ?></div>
                                <div class="newscontent newsh250">
                                    <div class="tickercontainer">
                                        <div class="mask"><ul id="ticker01" class="newsticker">
                                                <?php
                                                if (!empty($banner_notices)) {

                                                    foreach ($banner_notices as $banner_notice_key => $banner_notice_value) {
                                                        ?>
                                                        <li><a href="<?php echo site_url('read/' . $banner_notice_value['slug']) ?>"><div class="date"><?php echo date('d', strtotime($banner_notice_value['date'])); ?><span><?php echo date('F', strtotime($banner_notice_value['date'])); ?></span></div><?php echo $banner_notice_value['title']; ?>
                                                            </a></li>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div><!--./newscontent-->
                                <?php
                            }
                            ?>
                        </div><!--./sidebar-->
                    </div>
                    <?php
                }
                ?>
            </div><!--./row-->
        </div><!--./container-->
        <?php echo $footer; ?>

        <script src="<?php echo $base_assets_url; ?>js/bootstrap.min.js"></script>
        <script src="<?php echo $base_assets_url; ?>js/owl.carousel.min.js"></script>
        <script type="text/javascript" src="<?php echo $base_assets_url; ?>js/jquery.waypoints.min.js"></script>
        <script type="text/javascript" src="<?php echo $base_assets_url; ?>js/jquery.counterup.min.js"></script>
        <script src="<?php echo $base_assets_url; ?>js/ss-lightbox.js"></script>
        <script src="<?php echo $base_assets_url; ?>js/custom.js"></script>
        <!-- Include Date Range Picker -->
        <script type="text/javascript" src="<?php echo $base_assets_url; ?>datepicker/bootstrap-datepicker.min.js"></script>

        <script type="text/javascript">
                                        $(function () {
                                            jQuery('img.svg').each(function () {
                                                var $img = jQuery(this);
                                                var imgID = $img.attr('id');
                                                var imgClass = $img.attr('class');
                                                var imgURL = $img.attr('src');

                                                jQuery.get(imgURL, function (data) {
                                                    // Get the SVG tag, ignore the rest
                                                    var $svg = jQuery(data).find('svg');

                                                    // Add replaced image's ID to the new SVG
                                                    if (typeof imgID !== 'undefined') {
                                                        $svg = $svg.attr('id', imgID);
                                                    }
                                                    // Add replaced image's classes to the new SVG
                                                    if (typeof imgClass !== 'undefined') {
                                                        $svg = $svg.attr('class', imgClass + ' replaced-svg');
                                                    }

                                                    // Remove any invalid XML tags as per http://validator.w3.org
                                                    $svg = $svg.removeAttr('xmlns:a');

                                                    // Check if the viewport is set, else we gonna set it if we can.
                                                    if (!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                                                        $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
                                                    }

                                                    // Replace image with new SVG
                                                    $img.replaceWith($svg);

                                                }, 'xml');

                                            });
                                        });

        </script>
    </body>
</html>