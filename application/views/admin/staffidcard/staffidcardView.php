<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
if ($this->rbac->hasPrivilege('staff_id_card', 'can_add')) {
    ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_staff_id_card'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form id="form1" enctype="multipart/form-data" action="<?php echo site_url('admin/staffidcard/create') ?>"  id="certificateform" name="certificateform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) {
        ?>
                                    <?php echo $this->session->flashdata('msg');
        $this->session->unset_userdata('msg'); ?>
                                <?php }?>
                                 
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('background_image'); ?></label>
                                    <input id="documents" placeholder="" type="file" class="filestyle form-control" data-height="40"  name="background_image"><span class="text-danger"><?php echo form_error('background_image'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('logo'); ?></label>
                                    <input id="logo_img" placeholder="" type="file" class="filestyle form-control" data-height="40"  name="logo_img"><span class="text-danger"><?php echo form_error('logo_img'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('signature'); ?></label>
                                    <input id="sign_image" placeholder="" type="file" class="filestyle form-control" data-height="40"  name="sign_image">
                                    <span class="text-danger"><?php echo form_error('sign_image'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('school_name'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="school_name" name="school_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('school_name'); ?>" />
                                    <span class="text-danger"><?php echo form_error('school_name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('address_phone_email'); ?></label><small class="req"> *</small>
                                    <textarea class="form-control" id="address" name="address" placeholder="" rows="3" placeholder=""><?php echo set_value('address'); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('address'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('id_card_title'); ?></label><small class="req"> *</small>
                                    <input id="title" name="title" placeholder="" type="text" class="form-control" value="<?php echo set_value('title'); ?>" />
                                    <span class="text-danger"><?php echo form_error('title'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('header_color'); ?></label>
                                    <input id="header_color" name="header_color" placeholder="" type="text" class="form-control my-colorpicker1"  value="<?php echo set_value('header_color'); ?>" />
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('staff_name'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_name" name="is_active_staff_name" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_staff_name', '1', (set_value('is_active_staff_name') == 1) ? true : false); ?>>
                                        <label for="enable_name" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('staff_id'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_staff_id" name="is_active_staff_id" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_staff_id', '1', (set_value('is_active_staff_id') == 1) ? true : false); ?>>
                                        <label for="enable_staff_id" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('designation'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_designation" name="is_active_designation" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_designation', '1', (set_value('is_active_designation') == 1) ? true : false); ?>>
                                        <label for="enable_designation" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('department'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_department" name="is_active_department" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_department', '1', (set_value('is_active_department') == 1) ? true : false); ?>>
                                        <label for="enable_department" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('father_name'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_fathers_name" name="is_active_staff_father_name" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_staff_father_name', '1', (set_value('is_active_staff_father_name') == 1) ? true : false); ?>>
                                        <label for="enable_fathers_name" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('mother_name'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_staff_mother_name" name="is_active_staff_mother_name" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_staff_mother_name', '1', (set_value('is_active_staff_mother_name') == 1) ? true : false); ?>>
                                        <label for="enable_staff_mother_name" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('date_of_joining'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_date_of_joining" name="is_active_date_of_joining" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_date_of_joining', '1', (set_value('is_active_date_of_joining') == 1) ? true : false); ?>>
                                        <label for="enable_date_of_joining" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('current_address'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_staff_permanent_address" name="is_active_staff_permanent_address" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_staff_permanent_address', '1', (set_value('is_active_staff_permanent_address') == 1) ? true : false); ?>>
                                        <label for="enable_staff_permanent_address" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('phone'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_staff_phone" name="is_active_staff_phone" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_staff_phone', '1', (set_value('is_active_staff_phone') == 1) ? true : false); ?>>
                                        <label for="enable_staff_phone" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('date_of_birth'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_staff_dob" name="is_active_staff_dob" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_staff_dob', '1', (set_value('is_active_staff_dob') == 1) ? true : false); ?>>
                                        <label for="enable_staff_dob" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('design_type'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_vertical_card" name="enable_vertical_card" type="checkbox" class="chk" value="1" <?php echo set_checkbox('enable_vertical_card', '1', (set_value('enable_vertical_card') == 1) ? true : false); ?>>
                                        <label for="enable_vertical_card" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('barcode'); ?> / <?php echo $this->lang->line('qrcode'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_staff_barcode" name="enable_staff_barcode" type="checkbox" class="chk" value="1" <?php echo set_checkbox('enable_staff_barcode', '1', (set_value('enable_staff_barcode') == 1) ? true : false); ?>>
                                        <label for="enable_staff_barcode" class="label-success"></label>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
            <?php }?>
            <div class="col-md-<?php
if ($this->rbac->hasPrivilege('staff_id_card', 'can_add')) {
    echo "8";
} else {
    echo "12";
}
?>">
                <!-- general form elements -->
                <div class="box box-primary" id="hroom">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('staff_id_card_list'); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('staff_id_card_list'); ?></div>
                        <div class="table-responsive overflow-visible">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('id_card_title'); ?></th>
                                        <th><?php echo $this->lang->line('background_image'); ?></th>
                                         <th class="text text-center"><?php echo $this->lang->line('design_type'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($staffidcardlist)) {
    ?>
                                        <?php
} else {
    $count = 1;
    foreach ($staffidcardlist as $staffidcard_value) {
        ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <a style="cursor: pointer;" id="<?php echo $staffidcard_value->id ?>" data-toggle="popover" class="detail_popover" ><?php echo $staffidcard_value->title; ?></a>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php if ($staffidcard_value->background != '' && !is_null($staffidcard_value->background)) {?>
     <img class="object-fit-cover fit-image-40" src="<?php echo $this->media_storage->getImageURL('uploads/staff_id_card/background/'.$staffidcard_value->background); ?>" width="40">
                                                    <?php } else {?>
                                                        <i class="fa fa-picture-o fa-3x" aria-hidden="true"></i>
                                                    <?php }?>
                                                </td>
                                                    <td class="mailbox-name text text-center">
                                                    <?php echo ($staffidcard_value->enable_vertical_card) ? $this->lang->line('vertical') : $this->lang->line('horizontal') ?>
                                                </td>
                                                <td class="mailbox-date pull-right no-print white-space-nowrap">
                                                    <a data-id="<?php echo $staffidcard_value->id ?>" class="btn btn-default btn-xs view_data"  data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>">
                                                        <i class="fa fa-reorder"></i>
                                                    </a>
                                                    <?php
if ($this->rbac->hasPrivilege('staff_id_card', 'can_edit')) {
            ?>
                                                        <a href="<?php echo base_url(); ?>admin/staffidcard/edit/<?php echo $staffidcard_value->id ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        <?php
}
        if ($this->rbac->hasPrivilege('staff_id_card', 'can_delete')) {
            ?>
                                                        <a href="<?php echo base_url(); ?>admin/staffidcard/delete/<?php echo $staffidcard_value->id ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php }?>
                                                </td>
                                            </tr>
                                            <?php
}
    $count++;
}
?>
                                </tbody>
                            </table><!-- /.table -->
                          </div>
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
        <div class="row">
            <div class="col-md-12">
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!-- Modal -->
<div class="modal fade" id="certificateModal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('view_id_card'); ?></h4>
            </div>
            <div class="modal-body" id="certificate_detail">
            <div class="modal-inner-loader"></div>
            <div class="modal-inner-content">

            </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
          $("#header_color").colorpicker();
        $(document).on('click','.view_data',function(){
           $('#certificateModal').modal("show");
          var certificateid = $(this).data('id');
           $.ajax({
                url: "<?php echo base_url('admin/staffidcard/view') ?>",
                method: "post",
                data: {certificateid: certificateid},
                 beforeSend: function() {

                  },
                success: function (data) {
                 $('#certificateModal .modal-inner-content').html(data);
                 $('#certificateModal .modal-inner-loader').addClass('displaynone');

                 },
                error: function(xhr) { // if error occured
                 alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                },
                complete: function() {

                }
            });
        });
    });

    $('#certificateModal').on('hidden.bs.modal', function (e) {
        $('#certificateModal .modal-inner-content').html("");
        $('#certificateModal .modal-inner-loader').removeClass('displaynone');
     });
</script>