<div class="content-wrapper" style="min-height: 348px;">     
    <section class="content">
        <div class="row">
        
            <?php $this->load->view('setting/_settingmenu'); ?>
            
            <!-- left column -->
            <div class="col-md-10">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-gear"></i> <?php echo $this->lang->line('backend_theme'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="">
                        <form role="form" id="theme_setting_form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="sch_id" value="<?php echo $result->id; ?>">
                            <div class="box-body">                       
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div id="input-type">
                                            <div class="row">
                                                <div class="col-sm-3 col-xs-6 col20">
                                                    <label class="radio-img">
                                                        <input name="theme" <?php
                                                        if ($result->theme == "white.jpg") {
                                                            echo "checked";
                                                        }
                                                        ?> value="white.jpg" type="radio" />
                                                        <img src="<?php echo $this->media_storage->getImageURL('backend/images/white.jpg'); ?>">
                                                        <span class="radiotext"><?php echo $this->lang->line('white'); ?></span>
                                                    </label>                                                    
                                                </div>
                                                <div class="col-sm-3 col-xs-6 col20">
                                                    <label class="radio-img">
                                                        <input name="theme" <?php
                                                        if ($result->theme == "default.jpg") {
                                                            echo "checked";
                                                        }
                                                        ?>  value="default.jpg" type="radio" />
                                                        <img src="<?php echo $this->media_storage->getImageURL('backend/images/default.jpg'); ?>">
                                                        <span class="radiotext"><?php echo $this->lang->line('default'); ?></span>
                                                    </label>                                                    
                                                </div>
                                                <div class="col-sm-3 col-xs-6 col20">
                                                    <label class="radio-img">
                                                        <input name="theme" <?php
                                                        if ($result->theme == "red.jpg") {
                                                            echo "checked";
                                                        }
                                                        ?> value="red.jpg" type="radio" />
                                                        <img src="<?php echo $this->media_storage->getImageURL('backend/images/red.jpg'); ?>">
                                                        <span class="radiotext"><?php echo $this->lang->line('red'); ?></span>
                                                    </label>                                                    
                                                </div>
                                                <div class="col-sm-3 col-xs-6 col20">
                                                    <label class="radio-img">
                                                        <input name="theme" <?php
                                                        if ($result->theme == "blue.jpg") {
                                                            echo "checked";
                                                        }
                                                        ?> value="blue.jpg" type="radio" />
                                                        <img src="<?php echo $this->media_storage->getImageURL('backend/images/blue.jpg'); ?>">
                                                        <span class="radiotext"><?php echo $this->lang->line('blue'); ?></span>
                                                    </label>                                                    
                                                </div>
                                                <div class="col-sm-3 col-xs-6 col20">
                                                    <label class="radio-img">
                                                        <input name="theme" <?php
                                                        if ($result->theme == "gray.jpg") {
                                                            echo "checked";
                                                        }
                                                        ?> value="gray.jpg" type="radio" />
                                                        <img src="<?php echo $this->media_storage->getImageURL('backend/images/gray.jpg'); ?>">
                                                        <span class="radiotext"><?php echo $this->lang->line('gray'); ?></span>
                                                    </label>                                                   
                                                </div>
                                            </div><!--./row-->
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <?php
                                if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) {
                                    ?>
                                    <button type="button" class="btn btn-primary submit_schsetting pull-right edit_theme_setting" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('save'); ?></button>
                                    <?php
                                }
                                ?>
                            </div>
                        </form>
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- new END -->

</div><!-- /.content-wrapper -->

<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
 
    $(".edit_theme_setting").on('click', function (e) {
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: '<?php echo site_url("schsettings/savebackendtheme") ?>',
            type: 'POST',
            data: $('#theme_setting_form').serialize(),
            dataType: 'json',

            success: function (data) {

                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message); 
                }

                $this.button('reset');
            }
        });
    });


</script>