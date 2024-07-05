<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<div class="content-wrapper" style="min-height: 348px;">     
    <section class="content">
        <div class="row">
        
            <?php $this->load->view('setting/_settingmenu'); ?>
            
            <!-- left column -->
            <div class="col-md-10">
                <!-- general form elements -->

                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-gear"></i> <?php echo $this->lang->line('fees'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="">
                        <form role="form" id="fees_form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="sch_id" value="<?php echo $result->id; ?>">
                            <div class="box-body">                       
                                <div class="row">
                                    <div class="row">
                                    <div class="col-md-12">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-4"><?php echo $this->lang->line("offline_bank_payment_in_student_panel"); ?></label>
                                            <div class="col-sm-8" id="radioBtnDiv">
                                                <label class="radio-inline">
                                                    <input type="radio" class="is_offline_fee_payment" name="is_offline_fee_payment" value="0" <?php
                                                    if ($result->is_offline_fee_payment == 0) {
                                                        echo "checked";
                                                    }
                                                    ?> ><?php echo $this->lang->line('disabled'); ?>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" class="is_offline_fee_payment" name="is_offline_fee_payment" value="1"  <?php
                                                    if ($result->is_offline_fee_payment == 1) {
                                                        echo "checked";
                                                    }
                                                    ?>><?php echo $this->lang->line('enabled'); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>                                    
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-4"><?php echo $this->lang->line("offline_bank_payment_instruction"); ?></label>
                                            <div class="col-sm-8">                           
                                                
                                                <textarea id="offline_bank_payment_instruction" name="offline_bank_payment_instruction" class="form-control" style="height: 150px">
                                                <?php echo $result->offline_bank_payment_instruction ; ?>
                                                </textarea>
                                                <span class="text-danger"><?php echo form_error('offline_bank_payment_instruction'); ?></span>                                           
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-4"> <?php echo $this->lang->line("lock_student_panel_if_fees_remaining"); ?></label>
                                            <div class="col-sm-8" id="radioBtnDiv">
                                                <label class="radio-inline">
                                                    <input type="radio" class="is_student_feature_lock" name="is_student_feature_lock" value="0" <?php
                                                    if ($result->is_student_feature_lock == 0) {
                                                        echo "checked";
                                                    }
                                                    ?> ><?php echo $this->lang->line('disabled'); ?>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" class="is_student_feature_lock" name="is_student_feature_lock" value="1"  <?php
                                                    if ($result->is_student_feature_lock == 1) {
                                                        echo "checked";
                                                    }
                                                    ?>><?php echo $this->lang->line('enabled'); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="col-md-12 hide" id="fees_payment_grace_period">
                                            <div class="form-group row">
                                                <label class="col-sm-4"><?php echo $this->lang->line('fees_payment_grace_period'); ?><small class="req"> *</small></label>
                                                <div class="col-sm-8">
                                                    <input type="number" name="lock_grace_period" id="lock_grace_period" class="form-control" value="<?php echo $result->lock_grace_period; ?>">
                                                    <span class="text-danger"><?php echo form_error('lock_grace_period'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-4"> <?php echo $this->lang->line("print_fees_receipt_for"); ?></label>
                                             
                                                <div class="col-sm-8">
                                                    <label class="checkbox-inline">
                                                    <input type="checkbox" name="is_duplicate_fees_invoice[]" value="0"  <?php echo set_checkbox("is_duplicate_fees_invoice[]", "0" ,(set_value('is_duplicate_fees_invoice[]', in_array(0, $duplicate_fees_invoice)) == 1) ? TRUE : FALSE) ?> ><?php echo $this->lang->line('office_copy'); ?>
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" name="is_duplicate_fees_invoice[]" value="1"  <?php echo set_checkbox("is_duplicate_fees_invoice[]", "1" ,(set_value('is_duplicate_fees_invoice[]', in_array(1, $duplicate_fees_invoice)) == 1) ? TRUE : FALSE) ?> ><?php echo $this->lang->line('student_copy'); ?>
                                                    </label>
                                                      <label class="checkbox-inline">
                                                        <input type="checkbox" name="is_duplicate_fees_invoice[]" value="2"  <?php echo set_checkbox("is_duplicate_fees_invoice[]", "2" ,(set_value('is_duplicate_fees_invoice[]', in_array(2, $duplicate_fees_invoice)) == 1) ? TRUE : FALSE) ?> ><?php echo $this->lang->line('bank_copy'); ?>
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
                                                <label class="col-sm-4"><?php echo $this->lang->line('carry_forward_fees_due_days'); ?><small class="req"> *</small></label>
                                                <div class="col-sm-8">
                                                    <input type="number" name="fee_due_days" id="fee_due_days" class="form-control" value="<?php echo $result->fee_due_days; ?>">
                                                    <span class="text-danger"><?php echo form_error('fee_due_days'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-4"><?php echo $this->lang->line('single_page_fees_print'); ?> </label>
                                                <div class="col-sm-8">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="single_page_print" value="0"  <?php
                                                        if ($result->single_page_print == 0) {
                                                            echo "checked";
                                                        }
                                                        ?> ><?php echo $this->lang->line('disabled'); ?>
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="single_page_print" <?php
                                                        if ($result->single_page_print == 1) {
                                                            echo "checked";
                                                        }
                                                        ?> value="1"><?php echo $this->lang->line('enabled'); ?>
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
                                                <label class="col-sm-4"> <?php echo $this->lang->line("collect_fees_in_back_date"); ?></label>
                                                <div class="col-sm-8">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="collect_back_date_fees" value="0" <?php
                                                        if (!$result->collect_back_date_fees) {
                                                            echo "checked";
                                                        }
                                                        ?> ><?php echo $this->lang->line('disabled'); ?>
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="collect_back_date_fees" value="1" <?php
                                                        if ($result->collect_back_date_fees) {
                                                            echo "checked";
                                                        }
                                                        ?>><?php echo $this->lang->line('enabled'); ?>
                                                    </label>
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
                                    <button type="button" class="btn btn-primary submit_schsetting pull-right edit_fees" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('save'); ?></button>
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
 
    $(".edit_fees").on('click', function (e) {
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: '<?php echo site_url("schsettings/savefees") ?>',
            type: 'POST',
            data: $('#fees_form').serialize(),
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


<script type="text/javascript">
     $('input[type=radio][name=is_student_feature_lock]').change(function() {
        if (this.value == '1') {
            $('#fees_payment_grace_period').removeClass('hide'); 
        }
        else if (this.value == '0') {
             $('#fees_payment_grace_period').addClass('hide');   
        }
    }); 
     
    window.onload = function(){  
        var is_student_feature_lock = '<?php echo $result->is_student_feature_lock; ?>';  
        if(is_student_feature_lock == '1'){
            $('#fees_payment_grace_period').removeClass('hide'); 
        }else if(is_student_feature_lock == '0'){
            $('#fees_payment_grace_period').addClass('hide');   
        }
    }  
</script>  
<script>
    $(function () {
        $("#offline_bank_payment_instruction").wysihtml5();
    });
</script>