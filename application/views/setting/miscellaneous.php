<div class="content-wrapper" style="min-height: 348px;">   
    <section class="content">
        <div class="row">
        
            <?php $this->load->view('setting/_settingmenu'); ?>
            
            <!-- left column -->
            <div class="col-md-10">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-gear"></i> <?php echo $this->lang->line('miscellaneous'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="">
                        <form role="form" id="miscellaneous_form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="sch_id" value="<?php echo $result->id; ?>">
                            <div class="box-body">                       
                                <div class="row">
                                    <div class="row">
                                    <div class="col-md-12">
                                    <div class="col-md-12">                                        
                                        <h4 class="session-head"><?php echo $this->lang->line('online_examination'); ?></h4>
                                    </div>
                                    </div><!--./col-md-12-->
                                    <div class="col-md-12">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-4"><?php echo $this->lang->line('show_me_only_my_question'); ?></label>
                                            <div class="col-sm-8">
                                                <label class="radio-inline">
                                                    <input type="radio" name="my_question" value="0" <?php
                                                    if ($result->my_question == 0) {
                                                        echo "checked";
                                                    }
                                                    ?>  ><?php echo $this->lang->line('disabled'); ?>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="my_question" value="1" <?php
                                                    if ($result->my_question == 1) {
                                                        echo "checked";
                                                    }
                                                    ?> ><?php echo $this->lang->line('enabled'); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div><!--./row-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="settinghr"></div>
                                            <h4 class="session-head"><?php echo $this->lang->line('id_card_scan_code'); ?></h4>
                                        </div>
                                    </div><!--./col-md-12-->
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-4"><?php echo $this->lang->line('scan_type'); ?></label>
                                                <div class="col-sm-8">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="scan_code_type" value="barcode" <?php
                                                        if ($result->scan_code_type == "barcode") {
                                                            echo "checked";
                                                        }
                                                        ?>  ><?php echo $this->lang->line('barcode'); ?>
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="scan_code_type" value="qrcode" <?php
                                                        if ($result->scan_code_type == "qrcode") {
                                                            echo "checked";
                                                        }
                                                        ?> ><?php echo $this->lang->line('qrcode'); ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--./row-->                                     
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="settinghr"></div>
                                            <h4 class="session-head"><?php echo $this->lang->line('exam_result_in_front_site'); ?></h4>
                                        </div>
                                    </div><!--./col-md-12-->
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-4"><?php echo $this->lang->line('exam_result_page_in_front_site'); ?></label>
                                                <div class="col-sm-8">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="exam_result" value="0" <?php
                                                        if ($result->exam_result == 0) {
                                                            echo "checked";
                                                        }
                                                        ?>  ><?php echo $this->lang->line('disabled'); ?>
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="exam_result" value="1" <?php
                                                        if ($result->exam_result == 1) {
                                                            echo "checked";
                                                        }
                                                        ?> ><?php echo $this->lang->line('enabled'); ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--./row-->                                     
                                <div class="row">
                                    <div class="col-md-12">
                                    <div class="settinghr"></div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-4"><?php echo $this->lang->line('teacher_restricted_mode'); ?></label>
                                            <div class="col-sm-8">
                                                <label class="radio-inline">
                                                    <input type="radio" name="class_teacher" value="no"  <?php
                                                    if ($result->class_teacher == "no") {
                                                        echo "checked";
                                                    }
                                                    ?> ><?php echo $this->lang->line('disabled'); ?>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="class_teacher"  <?php
                                                    if ($result->class_teacher == "yes") {
                                                        echo "checked";
                                                    }
                                                    ?> value="yes"><?php echo $this->lang->line('enabled'); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-4"> <?php echo $this->lang->line('superadmin_visibility'); ?></label>
                                            <div class="col-sm-8">
                                                <label class="radio-inline">
                                                    <input type="radio" name="superadmin_restriction_mode" value="disabled" <?php if($result->superadmin_restriction=='disabled'){ echo 'checked' ; } ?> ><?php echo $this->lang->line('disabled'); ?>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="superadmin_restriction_mode" value="enabled" <?php if($result->superadmin_restriction=='enabled'){ echo 'checked' ; } ?>><?php echo $this->lang->line('enabled'); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>                                
                                    </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-4"><?php echo  $this->lang->line('event_reminder'); ?></label>
                                            <div class="col-sm-8" id="radioBtnDiv">
                                                <label class="radio-inline">
                                                    <input class="event_reminder" type="radio" name="event_reminder" id="event_reminder" value="disabled" <?php if($result->event_reminder=='disabled'){ echo 'checked' ; } ?> ><?php echo $this->lang->line('disabled'); ?>
                                                </label>
                                                <label class="radio-inline">
                                                    <input class="event_reminder" type="radio" name="event_reminder" id="event_reminder" value="enabled" <?php if($result->event_reminder=='enabled'){ echo 'checked' ; } ?>><?php echo $this->lang->line('enabled'); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 hide" id="reminder_before_days">
                                        <div class="form-group row">
                                            <label class="col-sm-8"><?php echo $this->lang->line('calendar_event_reminder_before_days'); ?></label>
                                            <div class="col-sm-4">
                                                <input type="number" name="calendar_event_reminder" id="calendar_event_reminder" class="form-control" value="<?php echo $result->calendar_event_reminder; ?>">
                                                <span class="text-danger"><?php echo form_error('calendar_event_reminder'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    </div> 
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-4"><?php echo $this->lang->line('staff_apply_leave_notification_email'); ?></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="staff_notification_email" id="staff_notification_email" class="form-control" value="<?php echo $result->staff_notification_email; ?>">
                                                    <span class="text-danger"><?php echo form_error('staff_notification_email'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div><!--./row--> 
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <?php
                                if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) {
                                    ?>
                                    <button type="button" class="btn btn-primary submit_schsetting pull-right edit_miscellaneous" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('save'); ?></button>
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
    $("input[name='event_reminder']",$('#radioBtnDiv')).change(
    function(e)
    {
        var event_reminder = $('.event_reminder:checked').val();
        if(event_reminder == 'enabled'){
            $('#reminder_before_days').removeClass('hide'); 
        }else if(event_reminder == 'disabled'){
            $('#reminder_before_days').addClass('hide');   
        }
    });      
</script >

<script type = "text/javascript">  
    window.onload = function(){  
        var event_reminder = $('.event_reminder:checked').val();
        if(event_reminder == 'enabled'){
            $('#reminder_before_days').removeClass('hide'); 
        }else if(event_reminder == 'disabled'){
            $('#reminder_before_days').addClass('hide');   
        }
    }  
</script> 

<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
 
    $(".edit_miscellaneous").on('click', function (e) {
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: '<?php echo site_url("schsettings/savemiscellaneous") ?>',
            type: 'POST',
            data: $('#miscellaneous_form').serialize(),
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