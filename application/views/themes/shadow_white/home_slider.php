<?php
if (isset($banner_images) && !empty($banner_images)) {
    ?>

    <div id="bootstrap-touch-slider" class="carousel bs-slider slide  control-round" data-ride="carousel" data-interval="5000">

        <div class="carousel-inner">
            <?php
            $banner_first = TRUE;
            foreach ($banner_images as $banner_img_key => $banner_img_value) {
                ?>
                <div class="item <?php if ($banner_first) echo 'active'; ?>">
                    <img src="<?php echo base_url($banner_img_value->dir_path . $banner_img_value->img_name); ?>" alt="" />
                </div>
                <?php
                $banner_first = FALSE;
            }
            ?>
        </div><!--./carousel-inner-->
        <a class="left carousel-control" href="#bootstrap-touch-slider"  data-slide="prev">
            <span class="fa fa-angle-left"></span>
            <span class="sr-only"><?php echo $this->lang->line('previous'); ?></span>
        </a>

        <a class="right carousel-control" href="#bootstrap-touch-slider" data-slide="next">
            <span class="fa fa-angle-right"></span>
            <span class="sr-only"><?php echo $this->lang->line('next'); ?></span>
        </a>


    </div> <!--./bootstrap-touch-slider-->

    <!-- <div class="col-md-4 col-sm-4">
        <div class="sidebar newsmain">
    <?php
    if (in_array('news', json_decode($front_setting->sidebar_options))) {
        ?>
                        <div class="catetab"><?php echo $this->lang->line('latest_news'); ?></div>
                        <div class="newscontent">
                            <div class="tickercontainer"><div class="mask"><ul id="ticker01" class="newsticker" style="height: 666px; top: 124.54px;">
        <?php
        foreach ($banner_notices as $banner_notice_key => $banner_notice_value) {
            ?>
                                                    <li><a href="<?php echo site_url('read/' . $banner_notice_value['slug']) ?>"><div class="date"><?php echo date('d', strtotime($banner_notice_value['date'])); ?><span><?php echo date('F', strtotime($banner_notice_value['date'])); ?></span></div><?php echo $banner_notice_value['title']; ?>
                                                        </a></li>
            <?php
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
    <!--</div>./col-md-12--> 

    <?php
}
?>