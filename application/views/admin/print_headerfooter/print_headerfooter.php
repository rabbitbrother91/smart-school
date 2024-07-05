<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom box box-primary theme-shadow">
                    <ul class="nav nav-tabs pull-right flex-sm-wrap d-xs-flex">
                        <li><a href="#tab_2" data-toggle="tab"><?php echo $this->lang->line('online_exam'); ?></a></li>
                        <li><a href="#tab_1" data-toggle="tab"><?php echo $this->lang->line('online_admission_receipt'); ?></a></li>
                        <li ><a href="#tab_4" data-toggle="tab"><?php echo $this->lang->line('payslip') ?></a></li>
                        <li class="active"><a href="#tab_3" data-toggle="tab"><?php echo $this->lang->line('fees_receipt'); ?></a></li>
                        <li class="pull-left header"> <?php echo $this->lang->line('print_headerfooter'); ?></li>
                    </ul>
                    <div class="tab-content">
                        <?php
if ($this->session->flashdata('msg') != '') {
    $msg = $this->session->flashdata('msg');

    ?>

                            <?php echo $msg;
    $this->session->unset_userdata('msg'); ?>
                        <?php }?>
                        <?php echo $this->customlib->getCSRF(); ?>
                        <!-- /.tab-pane -->
                        <div class="tab-pane active" id="tab_3">
                            <form role="form" id="form1"  enctype="multipart/form-data" action="<?php echo site_url('admin/print_headerfooter/edit') ?>" class="" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('header_image') . " (2230px X 300px)"; ?><small class="req"> *</small></label>
                                            <input id="documents" data-default-file="<?php echo $this->customlib->getBaseUrl() ?>./uploads/print_headerfooter/student_receipt/<?php echo $result[1]['header_image'] ?>" placeholder="" type="file" class="filestyle form-control" data-height="180"  name="header_image">
                                            <input  placeholder="" type="hidden" class="form-control" value="student_receipt" name="type">
                                            <span class="text-danger"><?php echo form_error('header_image'); ?></span>
                                        </div>
                                        <div class="form-group"><label><?php echo $this->lang->line('footer_content'); ?> </label>
                                            <textarea id="student_textarea" name="message1" class="form-control" style="height: 250px">
                                                <?php echo set_value('message1', $result[1]['footer_content']); ?>
                                            </textarea>
                                            <span class="text-danger"><?php echo form_error('message1'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="pull-right">
                                            <button type="submit" id="submitbtn1" class="btn btn-primary " data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_4">
                            <form role="form" id="form2" action="<?php echo site_url('admin/print_headerfooter/edit') ?>" class="" enctype="multipart/form-data" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('header_image') . " (2230px X 300px)"; ?><small class="req"> *</small></label>
                                            <input id="documents" data-default-file="<?php echo $this->customlib->getBaseUrl() ?>./uploads/print_headerfooter/staff_payslip/<?php echo $result[0]['header_image'] ?>" placeholder="" type="file" class="filestyle form-control" data-height="180"  name="header_image">
                                            <input  placeholder="" type="hidden" class="form-control" value="staff_payslip" name="type">
                                            <span class="text-danger"><?php echo form_error('header_image'); ?></span>
                                        </div>
                                        <div class="form-group"><label><?php echo $this->lang->line('footer_content'); ?> </label>
                                            <textarea id="staff_textarea" name="message" class="form-control" style="height: 250px">
                                                <?php echo set_value('message', $result[0]['footer_content']); ?>
                                            </textarea>
                                            <span class="text-danger"><?php echo form_error('message'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="pull-right">
                                            <button type="submit" id="submitbtn2" class="btn btn-primary" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_1">
                            <form role="form" id="form3" enctype="multipart/form-data" action="<?php echo site_url('admin/print_headerfooter/edit') ?>" class="" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('header_image') . " (2230px X 300px)"; ?><small class="req"> *</small></label>
                                            <input id="admission_documents" data-default-file="<?php echo $this->customlib->getBaseUrl() ?>./uploads/print_headerfooter/online_admission_receipt/<?php echo $result[2]['header_image'] ?>" placeholder="" type="file" class="filestyle form-control" data-height="180"  name="header_image">
                                            <input  placeholder="" type="hidden" class="form-control" value="online_admission_receipt" name="type">
                                            <span class="text-danger"><?php echo form_error('header_image'); ?></span>
                                        </div>
                                        <div class="form-group"><label><?php echo $this->lang->line('footer_content'); ?> </label>
                                            <textarea id="online_admission_textarea" name="admission_message" class="form-control" style="height: 250px">
                                                <?php echo set_value('admission_message', $result[2]['footer_content']); ?>
                                            </textarea>
                                            <span class="text-danger"><?php echo form_error('admission_message'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="pull-right">
                                            <button type="submit" id="submitbtn3" class="btn btn-primary " data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <form role="form" id="form4" enctype="multipart/form-data" action="<?php echo site_url('admin/print_headerfooter/edit') ?>" class="" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('header_image') . " (2230px X 300px)"; ?><small class="req"> *</small></label>
                                            <input id="admission_documents" data-default-file="<?php echo $this->customlib->getBaseUrl() ?>./uploads/print_headerfooter/online_exam/<?php echo $result[3]['header_image'] ?>" placeholder="" type="file" class="filestyle form-control" data-height="180"  name="header_image">
                                            <input  placeholder="" type="hidden" class="form-control" value="online_exam" name="type">
                                            <span class="text-danger"><?php echo form_error('header_image'); ?></span>
                                        </div>
                                        <div class="form-group"><label><?php echo $this->lang->line('footer_content'); ?></label>
                                            <textarea id="online_exam_textarea" name="online_exam_message" class="form-control" style="height: 250px">
                                                <?php echo set_value('online_exam_message', $result[3]['footer_content']); ?>
                                            </textarea>
                                            <span class="text-danger"><?php echo form_error('online_exam_message'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="pull-right">
                                            <button type="submit" id="submitbtn4" class="btn btn-primary " data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
            </div>
        </div>
    </section>
</div>

<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<script>
    $(function () {
        $("#staff_textarea").wysihtml5();
        $("#student_textarea").wysihtml5();
        $("#online_exam_textarea").wysihtml5();
        $("#online_admission_textarea").wysihtml5();
     

    });
</script>

<script>
    $(function(){
        $('#form1'). submit( function() {
            $("#submitbtn1").button('loading');
        });
        $('#form2'). submit( function() {
            $("#submitbtn2").button('loading');
        });
        $('#form3'). submit( function() {
            $("#submitbtn3").button('loading');
        });
        $('#form4'). submit( function() {
            $("#submitbtn4").button('loading');
        });
    })
</script>
