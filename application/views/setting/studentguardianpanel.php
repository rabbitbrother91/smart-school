<div class="content-wrapper">      
    <section class="content">
        <div class="row">
        
            <?php $this->load->view('setting/_settingmenu'); ?>
            
            <!-- left column -->
            <div class="col-md-10">
                <!-- general form elements -->

                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-gear"></i> <?php echo  $this->lang->line('student_guardian_panel'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="">
                        <form role="form" id="student_guardian_form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="sch_id" value="<?php echo $result->id; ?>">
                            <div class="box-body">                       
                                <div class="row">
                                    <div class="row">
                                    <div class="col-md-12">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-4"> <?php echo $this->lang->line('user_login_option'); ?></label>
                                            <div class="col-sm-8">
                                                <label class="checkbox-inline">
                                                    <input id="student_panel_login" type="checkbox" name="student_panel_login"  <?php if($result->student_panel_login=='1'){ echo 'checked' ; } ?> ><?php echo $this->lang->line('student_login'); ?>
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input id="parent_panel_login" type="checkbox" name="parent_panel_login"  <?php if($result->parent_panel_login=='1'){ echo 'checked' ; } ?>><?php echo $this->lang->line('parent_login'); ?>
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
                                            <label class="col-sm-4"><?php echo $this->lang->line('additional_username_option_for_student_login'); ?></label>
                                            <div class="col-sm-8">
                                            <?php $student_login    = json_decode($result->student_login); ?>                
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="student_login[]" value="admission_no" <?php
                                                    if (!empty($student_login) && in_array("admission_no", $student_login)){
                                                        echo "checked";
                                                    }
                                                    ?>  ><?php echo $this->lang->line('admission_no'); ?>
                                                </label>                                                
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="student_login[]" value="mobile_number" <?php
                                                    if (!empty($student_login) && in_array("mobile_number", $student_login)){
                                                        echo "checked";
                                                    }
                                                    ?> ><?php echo $this->lang->line('mobile_number'); ?>
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="student_login[]" value="email" <?php
                                                    if (!empty($student_login) && in_array("email", $student_login)){
                                                        echo "checked";
                                                    }
                                                    ?> ><?php echo $this->lang->line('email'); ?>
                                                </label>
                                            </div>
                                        </div>
                                        </div>                                        
                                    <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-sm-4"><?php echo $this->lang->line('additional_username_option_for_parent_login'); ?></label>
                                                <div class="col-sm-8">
                                                <?php $parent_login    = json_decode($result->parent_login); ?>                                  
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" name="parent_login[]" value="mobile_number" <?php
                                                        if (!empty($parent_login) && in_array("mobile_number", $parent_login)){
                                                            echo "checked";
                                                        }
                                                        ?> ><?php echo $this->lang->line('mobile_number'); ?>
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" name="parent_login[]" value="email" <?php
                                                        if (!empty($parent_login) && in_array("email", $parent_login)){
                                                            echo "checked";
                                                        }
                                                        ?> ><?php echo $this->lang->line('email'); ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-4"> <?php echo $this->lang->line('allow_student_to_add_timeline'); ?></label>
                                                    <div class="col-sm-8">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="student_timeline" value="disabled" <?php if($result->student_timeline=='disabled'){ echo 'checked' ; } ?> ><?php echo $this->lang->line('disabled'); ?>
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="student_timeline" value="enabled" <?php if($result->student_timeline=='enabled'){ echo 'checked' ; } ?>><?php echo $this->lang->line('enabled'); ?>
                                                        </label>
                                                    </div>
                                                </div>
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
                                    <button type="button" class="btn btn-primary submit_schsetting pull-right edit_student_guardian" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('save'); ?></button>
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
 
    $(".edit_student_guardian").on('click', function (e) {
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: '<?php echo site_url("schsettings/studentguardian") ?>',
            type: 'POST',
            data: $('#student_guardian_form').serialize(),
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