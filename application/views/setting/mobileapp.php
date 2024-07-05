<div class="content-wrapper" style="min-height: 348px;">    
    <section class="content">
        <div class="row">
        
            <?php $this->load->view('setting/_settingmenu'); ?>
            
            <!-- left column -->
            <div class="col-md-10">
                <!-- general form elements -->

                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-gear"></i> <?php echo $this->lang->line('mobile_app'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="">
                        <form role="form" id="mobileapp_form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="sch_id" value="<?php echo $result->id; ?>">
                            <div class="box-body">                       
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="relative">   

                                            <h4 class="session-head"><?php echo $this->lang->line('user_mobile_app'); ?> <?php if ($app_response) { echo "<small class=' alert-success'>(".$this->lang->line('android_app_purchase_code_already_registered').")</small>"; } ?></h4>

                                            <?php if (!$app_response) {
                                                ?>
                                                <button type="button" class="btn btn-info btn-sm impbtntitle3" data-toggle="modal" data-target="#andappModal"><?php echo $this->lang->line('register_your_android_app')?></button>
                                                <?php
                                            }
                                            ?>                      
                                          
                                        </div>
                                    </div><!--./col-md-12-->
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-4"> <?php echo $this->lang->line('user_mobile_app_api_url') ?></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="mobile_api_url" id="mobile_api_url" class="form-control" value="<?php echo $result->mobile_api_url; ?>">
                                                <span class="text-danger"><?php echo form_error('mobile_api_url'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-4"> <?php echo $this->lang->line('user_mobile_app_primary_color_code') ?></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="app_primary_color_code" id="app_primary_color_code" class="form-control" value="<?php echo $result->app_primary_color_code; ?>">
                                                <span class="text-danger"><?php echo form_error('app_primary_color_code'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-4"> <?php echo $this->lang->line('user_mobile_app_secondary_color_code'); ?></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="app_secondary_color_code" id="app_secondary_color_code" class="form-control" value="<?php echo $result->app_secondary_color_code; ?>">
                                                <span class="text-danger"><?php echo form_error('app_secondary_color_code'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--./row-->
                                <div class="row hidden">
                                    <div class="col-md-12">
                                        <div class="settinghr"></div>
                                        <div class="relative">   
                                            <h4 class="session-head"><?php echo $this->lang->line('admin_mobile_app'); ?> </h4>
                                        </div>
                                    </div><!--./col-md-12-->
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-4"> <?php echo $this->lang->line('admin_mobile_app_api_url') ?></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="admin_mobile_api_url" id="admin_mobile_api_url" class="form-control" value="<?php echo $result->admin_mobile_api_url; ?>">
                                                <span class="text-danger"><?php echo form_error('admin_mobile_api_url'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-4"> <?php echo $this->lang->line('admin_mobile_app_primary_color_code') ?></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="admin_app_primary_color_code" id="admin_app_primary_color_code" class="form-control" value="<?php echo $result->admin_app_primary_color_code; ?>">
                                                <span class="text-danger"><?php echo form_error('admin_app_primary_color_code'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-4"> <?php echo $this->lang->line('admin_mobile_app_secondary_color_code'); ?></label>
                                            <div class="col-sm-8">
                                                <input type="text" name="admin_app_secondary_color_code" id="admin_app_secondary_color_code" class="form-control" value="<?php echo $result->admin_app_secondary_color_code; ?>">
                                                <span class="text-danger"><?php echo form_error('admin_app_secondary_color_code'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--./row-->
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <?php
                                if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) {
                                    ?>
                                    <button type="button" class="btn btn-primary submit_schsetting pull-right edit_mobileapp" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('save'); ?></button>
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

<div id="andappModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('register_your_android_app_purchase_code') ?></h4>
            </div>
            <form action="<?php echo site_url('admin/admin/updateandappCode') ?>" method="POST" id="andapp_code">
                <div class="modal-body andapp_modal-body">
                    <div class="error_message">
                    </div>
                    <div class="form-group">
                        <label class="ainline"><span><?php echo $this->lang->line('envato_market_purchase_code_for_smart_school_android_app') ?> ( <a target="_blank" href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-"> <?php echo $this->lang->line('how_to_find_it') ?></a> )</span></label>
                        <input type="text" class="form-control" id="input-app-envato_market_purchase_code" name="app-envato_market_purchase_code">
                        <div id="error" class="input-error text text-danger"></div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo $this->lang->line('your_email_registered_with_envato') ?></label>
                        <input type="text" class="form-control" id="input-app-email" name="app-email">
                        <div id="error" class="input-error text text-danger"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('saving') ?>"><?php echo $this->lang->line('save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
 
    $(".edit_mobileapp").on('click', function (e) {
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: '<?php echo site_url("schsettings/savemobileapp") ?>',
            type: 'POST',
            data: $('#mobileapp_form').serialize(),
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