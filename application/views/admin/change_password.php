<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-key"></i> <?php echo $this->lang->line('change_password'); ?><small><?php //echo $this->lang->line('setting1'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <br/>
                    <form action="<?php echo site_url('admin/admin/changepass') ?>"  id="passwordform" name="passwordform" method="post" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
                        <?php 
                            if ($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                                $this->session->unset_userdata('msg'); 
                            } 
                        ?>                      
                        <?php
                        if (isset($error_message)) {
                            echo $error_message;
                        }
                        ?>
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"><?php echo $this->lang->line('current_password'); ?><span class="required"></span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input  name="current_pass" required="required" class="form-control col-md-7 col-xs-12" type="password"  value="<?php echo set_value('currentr_password'); ?>">
                                <span class="text-danger"><?php echo form_error('current_pass'); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"><?php echo $this->lang->line('new_password'); ?><span class="required"></span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input   required="required" class="form-control col-md-7 col-xs-12" name="new_pass" placeholder="" type="password"  value="<?php echo set_value('new_password'); ?>">
                                <span class="text-danger"><?php echo form_error('new_pass'); ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"><?php echo $this->lang->line('confirm_password'); ?><span class="required"></span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="confirm_pass" name="confirm_pass" placeholder="" type="password"  value="<?php echo set_value('confirm_password'); ?>" class="form-control col-md-7 col-xs-12" >
                                <span class="text-danger"><?php echo form_error('confirm_pass'); ?></span>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-info"><?php echo $this->lang->line('change_password'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>