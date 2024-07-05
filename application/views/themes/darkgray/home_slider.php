<?php
if (isset($banner_images) && !empty($banner_images)) {
    ?>
    <section class="sliderbg">
        <div class="overlay-color">
            <div id="bootstrap-touch-slider" class="carousel bs-slider slide  control-round" data-ride="carousel" data-interval="5000">
                <div class="carousel-inner">
                    <?php
$banner_first = true;
    foreach ($banner_images as $banner_img_key => $banner_img_value) {
        ?>
                        <div class="item <?php if ($banner_first) {
            echo 'active';
        }
        ?>">
                            <img src="<?php echo base_url($banner_img_value->dir_path . $banner_img_value->img_name); ?>" alt="" />
                        </div>
                        <?php
$banner_first = false;
    }
    ?>
                </div><!--./carousel-inner-->
                <a class="left carousel-control" href="#bootstrap-touch-slider"  data-slide="prev">
                    <span class="fa fa-angle-left"></span>
                    <span class="sr-only"><?php echo $this->lang->line('previous'); ?></span>
                </a>
                <!-- Right Control-->
                <a class="right carousel-control" href="#bootstrap-touch-slider" data-slide="next">
                    <span class="fa fa-angle-right"></span>
                    <span class="sr-only"><?php echo $this->lang->line('next'); ?></span>
                </a>
            </div> <!--./bootstrap-touch-slider-->
        </div>
    </section>

    <div class="container pt10 pb10">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="sidebar">
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
    <?php
}
?>