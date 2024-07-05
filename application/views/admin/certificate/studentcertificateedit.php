<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-newspaper-o"></i> <?php //echo $this->lang->line('certificate'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('student_certificate', 'can_add') || $this->rbac->hasPrivilege('student_certificate', 'can_edit')) {
                ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_student_certificate'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form action="<?php echo site_url('admin/certificate/edit/' . $editcertificate[0]->id) ?>"  id="certificateform" enctype="multipart/form-data" name="certificateform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php 
                                        echo $this->session->flashdata('msg');
                                        $this->session->unset_userdata('msg');
                                    ?>
                                <?php } ?>
                                <?php
                                if (isset($error_message)) {
                                    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                }
                                ?>
                                <?php echo $this->customlib->getCSRF(); ?>

                                <input type="hidden" name="id" value="<?php echo set_value('id', $editcertificate[0]->id); ?>" >
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('certificate_name'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="certificate_name" name="certificate_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('certificate_name', $editcertificate[0]->certificate_name); ?>" />
                                    <span class="text-danger"><?php echo form_error('certificate_name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('header_left_text'); ?></label>
                                    <input autofocus="" id="left_header" name="left_header" placeholder="" type="text" class="form-control"  value="<?php echo set_value('left_header', $editcertificate[0]->left_header); ?>" />
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('header_center_text'); ?></label>
                                    <input autofocus="" id="center_header" name="center_header" placeholder="" type="text" class="form-control"  value="<?php echo set_value('center_header', $editcertificate[0]->center_header); ?>" />
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('header_right_text'); ?></label>
                                    <input autofocus="" id="right_header" name="right_header" placeholder="" type="text" class="form-control"  value="<?php echo set_value('right_header', $editcertificate[0]->right_header); ?>" />
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('body_text'); ?></label><small class="req"> *</small>
                                    <textarea class="form-control" id="certificate_text" name="certificate_text" placeholder="" rows="3" placeholder=""><?php echo set_value('certificate_name', $editcertificate[0]->certificate_text); ?></textarea>
                                    <span class="text-primary">[name] [dob] [present_address] [guardian] [created_at] [admission_no] [roll_no] [class] [section] [gender] [admission_date] [category] [cast] [father_name] [mother_name] [religion] [email] [phone]
                                        <?php
                                        if (!empty($custom_fields)) {
                                            foreach ($custom_fields as $field_key => $field_value) {
                                                echo " [" . $field_value->name . "]";
                                            }
                                        }
                                        ?>
                                    </span>
                                    <span class="text-danger"><?php echo form_error('certificate_text'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('footer_left_text'); ?></label>
                                    <input autofocus="" id="left_footer" name="left_footer" placeholder="" type="text" class="form-control"  value="<?php echo set_value('left_footer', $editcertificate[0]->left_footer); ?>" />
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('footer_center_text'); ?></label>
                                    <input autofocus="" id="center_footer" name="center_footer" placeholder="" type="text" class="form-control"  value="<?php echo set_value('center_footer', $editcertificate[0]->center_footer); ?>" />
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('footer_right_text'); ?></label>
                                    <input autofocus="" id="right_footer" name="right_footer" placeholder="" type="text" class="form-control"  value="<?php echo set_value('right_footer', $editcertificate[0]->right_footer); ?>" />
                                </div>
                                <div class="mediarow">    
                                    <div class="row">
                                        <div class="img_div_modal"><label><?php echo $this->lang->line('certificate_design'); ?></label></div>
                                        <div class="col-md-6 col-sm-6 img_div_modal">
                                            <div class="form-group">
                                                <input id="header_height" name="header_height" placeholder="<?php echo $this->lang->line('header_height'); ?>" type="number" class="form-control" min="0" value="<?php echo set_value('header_height', $editcertificate[0]->header_height); ?>" />
                                            </div>
                                        </div><!--./col-md-6-->   
                                        <div class="col-md-6 col-sm-6 img_div_modal"> 
                                            <div class="form-group">
                                                <input id="footer_height" name="footer_height" placeholder="<?php echo $this->lang->line('footer_height'); ?>" type="number" class="form-control" min="0" value="<?php echo set_value('footer_height', $editcertificate[0]->footer_height); ?>" />
                                            </div>
                                        </div><!--./col-md-6-->
                                        <div class="col-md-6 col-sm-6 img_div_modal">
                                            <div class="form-group">
                                                <input id="content_height" name="content_height" placeholder="<?php echo $this->lang->line('body_height'); ?>" type="number" class="form-control" min="0" value="<?php echo set_value('content_height', $editcertificate[0]->content_height); ?>" />
                                            </div>
                                        </div><!--./col-md-6-->
                                        <div class="col-md-6 col-sm-6 img_div_modal"> 
                                            <div class="form-group">
                                                <input id="content_width" name="content_width" placeholder="<?php echo $this->lang->line('body_width'); ?>" type="number" class="form-control" min="0" value="<?php echo set_value('content_width', $editcertificate[0]->content_width); ?>" />
                                            </div>
                                        </div><!--./col-md-6-->
                                        <div class="col-md-6 col-sm-6 img_div_modal minh45">
                                            <div class="form-group switch-inline">
                                                <label><?php echo $this->lang->line('student_photo'); ?></label>
                                                <div class="material-switch switchcheck">
                                                    <input id="enable_student_img" name="is_active_student_img" type="checkbox" class="chk" value="1" onclick="valueChanged()" <?php echo set_checkbox('is_active_student_img', '1', (set_value('is_active_student_img', $editcertificate[0]->enable_student_image) == 1) ? TRUE : FALSE); ?>>
                                                    <label for="enable_student_img" class="label-success"></label>
                                                </div>
                                            </div>
                                        </div><!--./col-md-6-->
                                        <div class="col-md-6 col-sm-6 img_div_modal">
                                            <div class="form-group" id="enableImageDiv" hidden>
                                                <input id="image_height" name="image_height" placeholder="<?php echo $this->lang->line('photo_height'); ?>" type="text" value="<?php echo set_value('image_height', $editcertificate[0]->enable_image_height); ?>" class="form-control" min="0" />
                                            </div>
                                        </div>
                                    </div><!--./row-->  
                                </div><!--./mediarow-->
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('background_image'); ?></label>
                                    <input id="documents" placeholder="" type="file" class="filestyle form-control" data-height="40"  name="background_image">
                                    <input type="hidden" name="old_background_image" value="<?php echo $editcertificate[0]->background_image; ?>">                              
                                     <span class="text-danger"><?php echo form_error('background_image'); ?></span>
                                     <?php if(!empty($editcertificate[0]->background_image)){
                                        ?>
                                        <div class="background_image">
                                        <div class="fadeheight-sms">
                                         <p class=""> <a class="uploadclosebtn" title="<?php echo $this->lang->line('delete_background_image'); ?>"><i class="fa fa-trash-o" onclick="removebackground_image()"></i></a><?php echo $editcertificate[0]->background_image;?>
                                         </p>
                                        </div>
                                    </div>                                    
                                    <?php }?>
                                </div>
                                <?php
                                if ($editcertificate[0]->enable_student_image == 1) {
                                    $hidden = 'hidden';
                                } else {
                                    $hidden = "";
                                }
                                ?>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('student_certificate', 'can_add') || $this->rbac->hasPrivilege('student_certificate', 'can_edit')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('student_certificate_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-controls">
                            <div class="pull-right">
                            </div><!-- /.pull-right -->
                        </div>
                        <div class="mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('student_certificate_list'); ?></div>
                            <div class="table-responsive overflow-visible">  
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('certificate_name'); ?></th>
                                            <th><?php echo $this->lang->line('background_image'); ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($certificateList)) {
    ?>
                                        <?php
} else {
    $count = 1;
    foreach ($certificateList as $certificate) {
        ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <a style="cursor: pointer;" class="view_data" id="<?php echo $certificate->id ?>" data-toggle="popover" class="detail_popover" ><?php echo $certificate->certificate_name; ?></a>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php if ($certificate->background_image != '' && !is_null($certificate->background_image)) {?>
                                                        <img src="<?php echo $this->media_storage->getImageURL('uploads/certificate/'.$certificate->background_image); ?>" width="40" height="40" class="object-fit-cover fit-image-40">
                                                    <?php } else {?>
                                                        <i class="fa fa-picture-o fa-3x" aria-hidden="true"></i>
                                                    <?php }?>
                                                </td>
                                                <td class="mailbox-date text-right no-print white-space-nowrap">
                                                    <a data-toggle="tooltip" id="<?php echo $certificate->id ?>" class="btn btn-default btn-xs view_data" title="<?php echo $this->lang->line('view'); ?>">
                                                        <i class="fa fa-reorder"></i>
                                                    </a>
                                                    <?php
if ($this->rbac->hasPrivilege('student_certificate', 'can_edit')) {
            ?>
                                                        <a href="<?php echo base_url(); ?>admin/certificate/edit/<?php echo $certificate->id ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        <?php
}
        if ($this->rbac->hasPrivilege('student_certificate', 'can_delete')) {
            ?>
                                                        <a href="<?php echo base_url(); ?>admin/certificate/delete/<?php echo $certificate->id ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
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
                                </table>
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
<div class="modal fade" id="myModal" role="dialog" style="width: 100%;" >
    <div class="modal-dialog modal-lg" style="width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('view_certificate'); ?></h4>
            </div>
            <div class="modal-body" id="certificate_detail">

            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });

        if ($('#enable_student_img').is(":checked")) {
            $("#enableImageDiv").show();
        } else {
            $("#enableImageDiv").hide();
        }
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.view_data').click(function () {
            var certificateid = $(this).attr("id");
            $.ajax({
                url: "<?php echo base_url('admin/certificate/view') ?>",
                method: "post",
                data: {certificateid: certificateid},
                success: function (data) {
                    $('#certificate_detail').html(data);
                    $('#myModal').modal("show");
                }
            });
        });
    });
</script>
<script type="text/javascript">
    function valueChanged()
    {
        if ($('#enable_student_img').is(":checked"))
            $("#enableImageDiv").show();
        else
            $("#enableImageDiv").hide();
    }

    function removebackground_image(){
       var result = confirm("<?php echo $this->lang->line('delete_confirm')?>");
        if (result) {
            $('.background_image').html('<input type="hidden" name="removebackground_image" value="1">');
        } 
    }
</script>