<div class="content-wrapper">  
    <section class="content-header">
        <h1><i class="fa fa-ioxhost"></i> <?php echo $this->lang->line('front_office'); ?></h1>
    </section>
    <?php $call_type = $this->customlib->getCalltype(); ?>
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('phone_call_log', 'can_add')) { ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_phone_call_log'); ?></h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo site_url('admin/generalcall') ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">
                                <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label>
                                    <input type="text" class="form-control" value="<?php echo set_value('name'); ?>" name="name">
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="pwd"><?php echo $this->lang->line('phone'); ?></label><small class="req"> *</small>  
                                    <input type="text" class="form-control" value="<?php echo set_value('contact'); ?>" name="contact">
                                    <span class="text-danger"><?php echo form_error('contact'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="pwd"><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small> 
                                    <input id="date" name="date" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly" />
                                    <span class="text-danger"><?php echo form_error('date'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="email"><?php echo $this->lang->line('description'); ?></label> 
                                    <textarea class="form-control" id="description" name="description"  rows="3"><?php echo set_value('description'); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('next_follow_up_date'); ?></label>     <input id="follow_up_date" name="follow_up_date" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('follow_up_date'); ?>" readonly="readonly" />
                                        <span class="text-danger"><?php echo form_error('follow_up_date'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pwd"><?php echo $this->lang->line('call_duration'); ?></label>
                                    <input type="text" class="form-control" value="<?php echo set_value('call_duration'); ?>" name="call_duration">
                                    <span class="text-danger"><?php echo form_error('call_duration'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="pwd"><?php echo $this->lang->line('note'); ?></label>
                                    <textarea class="form-control" id="description" name="note" name="note" rows="3"><?php echo set_value('note'); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('note'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="pwd"><?php echo $this->lang->line('call_type'); ?></label>
                                    <small class="req"> *</small>  
                                    <?php foreach ($call_type as $key => $value) { ?>
                                        <label class="radio-inline"><input type="radio" name="call_type" value="<?php echo $key; ?>" <?php if (set_value('call_type') == $key) { ?> checked=""<?php } ?>> <?php echo $value; ?></label>
                                    <?php } ?>
                                    <span class="text-danger"><?php echo form_error('call_type'); ?></span>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                <?php } ?> 
            </div><!--/.col (right) -->
            <!-- left column -->
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('phone_call_log', 'can_add')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('phone_call_log_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-messages table-responsive overflow-visible-lg">
                            <table class="table table-striped table-bordered table-hover call-list" data-export-title="<?php echo $this->lang->line('phone_call_log_list'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th class="white-space-nowrap"><?php echo $this->lang->line('date'); ?></th>
                                        <th class="white-space-nowrap"><?php echo $this->lang->line('next_follow_up_date'); ?></th>
                                        <th><?php echo $this->lang->line('call_type'); ?></th>
                                        <th class="text-right noExport "><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- new END -->
<div id="calldetails" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('details') ?></h4>
            </div>
            <div class="modal-body" id="getdetails">

            </div>
        </div>
    </div>
</div>
</div><!-- /.content-wrapper -->

<script type="text/javascript">

    function getRecord(id) {       
        $.ajax({
            url: '<?php echo base_url(); ?>admin/generalcall/details/' + id,
            success: function (result) {               
                $('#getdetails').html(result);
            }
        });
    }
</script>

<script>
    ( function ( $ ) {
    'use strict';
    $(document).ready(function () {
      initDatatable('call-list','admin/generalcall/getcalllist',[],[],100);
    });
} ( jQuery ) )
</script>