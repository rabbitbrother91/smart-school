<div class="content-wrapper" style="min-height: 348px;">
    <section class="content">
        <div class="row">
            <?php $this->load->view('setting/_settingmenu'); ?>
            
            <!-- left column -->
            <div class="col-md-10">
                <!-- general form elements -->

                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-gear"></i> <?php echo $this->lang->line('id_auto_generation'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="">
                        <form role="form" id="id_auto_generation_form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="sch_id" value="<?php echo $result->id; ?>">
                            <div class="box-body">                       
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="session-head"><?php echo $this->lang->line('student_admission_no_auto_generation'); ?></h4>
                                    </div><!--./col-md-12-->
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-3"><?php echo $this->lang->line('auto_admission_no'); ?></label>
                                            <div class="col-sm-9">
                                                <label class="radio-inline">
                                                    <input type="radio" name="adm_auto_insert" value="0" <?php
                                                    if ($result->adm_auto_insert == 0) {
                                                        echo "checked";
                                                    }
                                                    ?>  ><?php echo $this->lang->line('disabled'); ?>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="adm_auto_insert" value="1" <?php
                                                    if ($result->adm_auto_insert == 1) {
                                                        echo "checked";
                                                    }
                                                    ?> ><?php echo $this->lang->line('enabled'); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-3"><?php echo $this->lang->line('admission_no_prefix'); ?><small class="req"> *</small></label>
                                            <div class="col-sm-9">
                                                <input type="text" name="adm_prefix" id="adm_prefix" class="form-control" value="<?php echo $result->adm_prefix; ?>">
                                                <span class="text-danger"><?php echo form_error('adm_prefix'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-3"><?php echo $this->lang->line('admission_no_digit'); ?><small class="req"> *</small></label>
                                            <div class="col-sm-9">
                                                <select  id="adm_no_digit" name="adm_no_digit" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($digitList as $digit) {
                                                        ?>
                                                        <option value="<?php echo $digit ?>" <?php
                                                        if ($result->adm_no_digit == $digit) {
                                                            echo "selected";
                                                        }
                                                        ?> ><?php echo $digit; ?></option> <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('adm_no_digit'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-3"><?php echo $this->lang->line('admission_start_from'); ?><small class="req"> *</small></label>
                                            <div class="col-sm-9">
                                                <input type="text" name="adm_start_from" id="adm_start_from" class="form-control" value="<?php echo $result->adm_start_from; ?>">
                                                <span class="text-danger"><?php echo form_error('adm_start_from'); ?></span>
                                            </div>
                                        </div>
                                    </div> 
                                </div><!--./row-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="settinghr"></div>
                                        <h4 class="session-head"><?php echo $this->lang->line('staff_id_auto_generation'); ?></h4>
                                    </div><!--./col-md-12-->
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-3"><?php echo $this->lang->line('auto_staff_id'); ?></label>
                                            <div class="col-sm-9">
                                                <label class="radio-inline">
                                                    <input type="radio" name="staffid_auto_insert" value="0" <?php
                                                    if ($result->staffid_auto_insert == 0) {
                                                        echo "checked";
                                                    }
                                                    ?>  ><?php echo $this->lang->line('disabled'); ?>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="staffid_auto_insert" value="1" <?php
                                                    if ($result->staffid_auto_insert == 1) {
                                                        echo "checked";
                                                    }
                                                    ?>><?php echo $this->lang->line('enabled'); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-3"><?php echo $this->lang->line('staff_id_prefix') ?><small class="req"> *</small></label>
                                            <div class="col-sm-9">
                                                <input id="staffid_prefix" value="<?php echo $result->staffid_prefix; ?>" name="staffid_prefix" placeholder="" type="text" class="form-control" />
                                                <span class="text-danger"><?php echo form_error('staffid_prefix'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-3"><?php echo $this->lang->line('staff_no_digit') ?><small class="req"> *</small></label>
                                            <div class="col-sm-9">
                                                <select  id="staffid_no_digit" name="staffid_no_digit" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($digitList as $digit) {
                                                        ?>
                                                        <option value="<?php echo $digit ?>" <?php
                                                        if ($digit == $result->staffid_no_digit) {
                                                            echo "selected";
                                                        }
                                                        ?>><?php echo $digit; ?></option>
                                                            <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('staffid_no_digit'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-3"><?php echo $this->lang->line('staff_id_start_from') ?><small class="req"> *</small></label>
                                            <div class="col-sm-9">

                                                <input id="staffid_start_from" value="<?php echo $result->staffid_start_from; ?>" name="staffid_start_from" placeholder="" type="text" class="form-control" />
                                                <span class="text-danger"><?php echo form_error('staffid_start_from'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--./row-->
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <?php
                                if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) {
                                    ?>
                                    <button type="button" class="btn btn-primary submit_schsetting pull-right id_auto_generation" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('save'); ?></button>
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
 
    $(".id_auto_generation").on('click', function (e) {
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: '<?php echo site_url("schsettings/saveidautogeneration") ?>',
            type: 'POST',
            data: $('#id_auto_generation_form').serialize(),
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
                    //window.location.reload(true);
                }

                $this.button('reset');
            }
        });
    });


</script>