<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <i class="fa fa-key"></i> <?php //echo $this->lang->line('change_password'); ?><small><?php //echo $this->lang->line('setting1'); ?></small>        </h1>
    </section>    
    <section class="content">
        <div class="row">


            <div class="col-md-12">
                <!-- Custom Tabs (Pulled to the right) -->
                <div class="nav-tabs-custom theme-shadow">
                    <ul class="nav nav-tabs">

                        <li ><a href="<?php echo site_url('user/user/changepass') ?>"><?php echo $this->lang->line('change_password'); ?></a></li>
                        <li class="active"><a href="#"><?php echo $this->lang->line('change_username'); ?></a></li>


                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1-1">
                            <form action="<?php echo site_url('user/user/changeusername') ?>"  id="passwordform" name="passwordform" method="post" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php 
                                        echo $this->session->flashdata('msg');
                                        $this->session->unset_userdata('msg');
                                    ?>
                                <?php } ?>                       
                                <?php
                                if (isset($error_message)) {
                                    echo $error_message;
                                }
                                ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group  <?php
                                if (form_error('current_username')) {
                                    echo 'has-error';
                                }
                                ?>">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"><?php echo $this->lang->line('current_username'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input  name="current_username" required="required" class="form-control col-md-7 col-xs-12" type="text"  value="<?php echo set_value('currentr_password'); ?>">

                                    </div>
                                </div>
                                <div class="form-group  <?php
                                if (form_error('new_username')) {
                                    echo 'has-error';
                                }
                                ?>">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"><?php echo $this->lang->line('new_username'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input   required="required" class="form-control col-md-7 col-xs-12" name="new_username" placeholder="" type="text"  value="<?php echo set_value('new_username'); ?>">

                                    </div>
                                </div>
                                <div class="form-group  <?php
                                if (form_error('confirm_username')) {
                                    echo 'has-error';
                                }
                                ?>">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"><?php echo $this->lang->line('confirm_username'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input id="confirm_username" name="confirm_username" placeholder="" type="text"  value="<?php echo set_value('confirm_username'); ?>" class="form-control col-md-7 col-xs-12" >

                                    </div>
                                </div>
                                <div class="box-footer">
                                    <div class="form-group">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                            <button type="submit" class="btn btn-info"><?php echo $this->lang->line('save'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->

                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>

    </section>
</div>