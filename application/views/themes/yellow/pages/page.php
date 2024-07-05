<?php
if ($page_form) {
    if (!empty($form)) {
        //build start form

        if (validation_errors() != false) {
            ?>
            <div class="col-md-12 alert alert-danger">
                <?php echo validation_errors(); ?>
            </div>
            <?php
}
        if ($this->session->flashdata('msg')) {
            ?>
            <div class='alert alert-success'>
                <?php
echo $this->session->flashdata('msg');
            $this->session->unset_userdata('msg');
            ?>

            </div>
            <?php
}
        $form_content                     = $this->form_builder->open_form(array('action' => '', 'id' => 'open'));
        $defaults_object_or_array_from_db = null;
        $form_content .= "<input type='hidden' value='$form_name' name='form_name'/>";
        $form_content .= $this->form_builder->build_form_horizontal($form, $defaults_object_or_array_from_db);
        $form_content .= $this->form_builder->close_form();
        //build end form
        $replace_frm      = '[form-builder:' . $form_name . ']';
        $replace_to       = $form_content;
        echo $description = str_replace($replace_frm, $replace_to, $page['description']);
    }
} else {
    echo $page['description'];
}
?>
<!-- <h2 class="courses-head text-center"><?php echo ucfirst($page_content_type); ?></h2> -->
<input type="hidden" name="page_content_type" id="page_content_type" value="<?php echo $page_content_type; ?>">
<div class="post-list spaceb50" id="postList">
    <?php
if (!empty($page['category_content'])) {
    ?>
        <?php
foreach ($page['category_content'] as $page_content_key => $page_content_value) {
        ?>

            <?php
if ($page_content_key == "events") {

            $numOfCols         = 3;
            $rowCount          = 0;
            $bootstrapColWidth = 12 / $numOfCols;
            ?>
                <div class="row">
                    <?php
if (!empty($page['category_content'][$page_content_key])) {

                foreach ($page['category_content'][$page_content_key] as $key => $value) {
                    ?>
                            <div class="col-md-<?php echo $bootstrapColWidth; ?> col-sm-<?php echo $bootstrapColWidth; ?>">
                                <div class="cuadro_intro_hover" style="background-color:#cccccc;">
                                    <a href="<?php echo site_url($value['url']); ?>">
                                        <?php
if ($value['feature_image'] == "") {
                        $feature_image = base_url('uploads/gallery/gallery_default.png');
                    } else {
                        $feature_image = $value['feature_image'];
                    }
                    ?>
                                        <div align="center"><img src="<?php echo $feature_image; ?>" alt="" title=""></div>
                                        <div class="eventcaption">
                                            <div class="blur"></div>
                                            <div class="event20">
                                                <h3 style="margin-top: 10px;"><?php echo $value['title']; ?></h3>
                                                <p><?php echo substr($value['description'], 0, 85) . ".."; ?></p>
                                            </div><!--./around20-->
                                        </div>
                                    </a>
                                </div><!--./eventbox-->
                            </div>
                            <?php
$rowCount++;
                    if ($rowCount % $numOfCols == 0) {
                        echo '</div><div class="row">';
                    }

                }
            }
            ?>
                </div>

                <?php
} elseif ($page_content_key == "notice") {

            foreach ($page['category_content'][$page_content_key] as $key => $value) {
                ?>
                    <div class="alert-message alert-message-default">
                        <h4><a href="<?php echo site_url($value['url']); ?>"><?php echo $value['title']; ?></a></h4>
                        <p><?php echo substr($value['description'], 0, 85) . ".."; ?></p>
                    </div>

                    <?php
}
        } elseif ($page_content_key == "gallery") {
            $numOfCols         = 3;
            $rowCount          = 0;
            $bootstrapColWidth = 12 / $numOfCols;
            ?>
                <div class="row">
                    <?php
foreach ($page['category_content'][$page_content_key] as $key => $value) {
                ?>
                        <div class="col-md-<?php echo $bootstrapColWidth; ?> col-sm-<?php echo $bootstrapColWidth; ?>">
                            <div class="eventbox">
                                <a href="<?php echo site_url($value['url']); ?>">
                                    <?php
if ($value['feature_image'] == "") {
                    $feature_image = base_url('uploads/gallery/gallery_default.png');
                } else {
                    $feature_image = $value['feature_image'];
                }
                ?>
                                    <img src="<?php echo $feature_image; ?>" alt="" title="">
                                    <div class="around20">
                                        <h3><?php echo $value['title']; ?></h3>
                                        <?php
echo substr(strip_tags(html_entity_decode($value['description'])), 0, 50);
                ?>
                                    </div><!--./around20-->
                                </a>
                            </div><!--./eventbox-->
                        </div>
                        <?php
$rowCount++;
                if ($rowCount % $numOfCols == 0) {
                    echo '</div><div class="row">';
                }

            }
            ?>
                </div>

                <?php
} else {

        }
    }
    echo $this->ajax_pagination->create_links(); //pagination link
}
?>
</div>

<script>
    function searchFilter(page_num) {
        page_num = page_num ? page_num : 0;
        var page_content_type = $('#page_content_type').val();

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>welcome/ajaxPaginationData/' + page_num,
            data: 'page=' + page_num + '&page_content_type=' + page_content_type,
            beforeSend: function () {
                $('.loading').show();
            },
            success: function (html) {
                $('#postList').html(html);
                $('.loading').fadeOut("slow");
            }
        });
    }
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