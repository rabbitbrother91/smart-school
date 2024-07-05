<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>     <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-newspaper-o"></i> <?php //echo $this->lang->line('certificate'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('student_id_card', 'can_add') || $this->rbac->hasPrivilege('student_id_card', 'can_edit')) {
                ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_student_id_card'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form id="form1" enctype="multipart/form-data" action="<?php echo site_url('admin/studentidcard/edit/') . $editidcard[0]->id ?>"  id="certificateform" name="certificateform" method="post" accept-charset="utf-8">
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
                                <input type="hidden" name="id" value="<?php echo set_value('id', $editidcard[0]->id); ?>" >
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('background_image'); ?></label>
                                    <input id="documents" placeholder="" type="file" class="filestyle form-control" data-height="40"  name="background_image">
                                    <input type="hidden" name="old_background" value="<?php echo $editidcard[0]->background; ?>">
                                    <span class="text-danger"><?php echo form_error('background_image'); ?></span>
                                    <?php if(!empty($editidcard[0]->background)){
                                        ?>
                                        <div class="background_image">
                                            <div class="fadeheight-sms">
                                             <p class=""> <a class="uploadclosebtn" title="<?php echo $this->lang->line('delete_background_image'); ?>"><i class="fa fa-trash-o" onclick="removebackground_image()"></i></a><?php echo $editidcard[0]->background;?>
                                             </p>
                                            </div>
                                        </div>
                                    <?php }?>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('logo'); ?></label>
                                    <input id="logo_img" placeholder="" type="file" class="filestyle form-control" data-height="40"  name="logo_img">
                                    <input type="hidden" name="old_logo_img" value="<?php echo $editidcard[0]->logo; ?>">
                                    <span class="text-danger"><?php echo form_error('logo_img'); ?></span>
                                     <?php if(!empty($editidcard[0]->logo)){
                                        ?>
                                        <div class="logo_image">
                                        <div class="fadeheight-sms">
                                         <p class=""> <a class="uploadclosebtn" title="<?php echo $this->lang->line('delete_background_image'); ?>"><i class="fa fa-trash-o" onclick="removelogo_image()"></i></a><?php echo $editidcard[0]->logo;?>
                                         </p>
                                        </div>
                                    </div>                                    
                                        <?php }?>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('signature'); ?></label>
                                    <input id="sign_image" placeholder="" type="file" class="filestyle form-control" data-height="40"  name="sign_image">
                                    <input type="hidden" name="old_sign_image" value="<?php echo $editidcard[0]->sign_image; ?>">
                                    <span class="text-danger"><?php echo form_error('sign_image'); ?></span>
                                   <?php if(!empty($editidcard[0]->sign_image)){
                                        ?>
                                        <div class="sign_image">
                                        <div class="fadeheight-sms">
                                         <p class=""> <a class="uploadclosebtn" title="<?php echo $this->lang->line('delete_sign_image'); ?>"><i class="fa fa-trash-o" onclick="removesign_image()"></i></a><?php echo $editidcard[0]->sign_image;?>
                                         </p>
                                        </div>
                                    </div>                                    
                                        <?php }?>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('school_name'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="school_name" name="school_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('school_name', $editidcard[0]->school_name); ?>" />
                                    <span class="text-danger"><?php echo form_error('school_name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('address_phone_email'); ?></label><small class="req"> *</small>
                                    <textarea class="form-control" id="address" name="address" placeholder="" rows="3" placeholder=""><?php echo set_value('address', $editidcard[0]->school_address); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('address'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('id_card_title'); ?></label><small class="req"> *</small>
                                    <input id="title" name="title" placeholder="" type="text" class="form-control" value="<?php echo set_value('title', $editidcard[0]->title); ?>" />
                                    <span class="text-danger"><?php echo form_error('title'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('header_color'); ?></label>
                                    <input id="header_color" name="header_color" placeholder="" type="text" class="form-control my-colorpicker1" value="<?php echo set_value('header_color', $editidcard[0]->header_color); ?>" />
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('admission_no'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_admission_no" name="is_active_admission_no" type="checkbox" class="chk" <?php echo set_checkbox('is_active_admission_no', '1', (set_value('is_active_admission_no', $editidcard[0]->enable_admission_no) == 1) ? TRUE : FALSE); ?> value="1">
                                        <label for="enable_admission_no" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('student_name'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_student_name" name="is_active_student_name" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_student_name', '1', (set_value('is_active_student_name', $editidcard[0]->enable_student_name) == 1) ? TRUE : FALSE); ?>>
                                        <label for="enable_student_name" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('class'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_class" name="is_active_class" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_class', '1', (set_value('is_active_class', $editidcard[0]->enable_class) == 1) ? TRUE : FALSE); ?>>
                                        <label for="enable_class" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('father_name'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_father_name" name="is_active_father_name" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_father_name', '1', (set_value('is_active_father_name', $editidcard[0]->enable_fathers_name) == 1) ? TRUE : FALSE); ?>>
                                        <label for="enable_father_name" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('mother_name'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_mother_name" name="is_active_mother_name" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_mother_name', '1', (set_value('is_active_mother_name', $editidcard[0]->enable_mothers_name) == 1) ? TRUE : FALSE); ?>>
                                        <label for="enable_mother_name" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('student_address'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_address" name="is_active_address" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_address', '1', (set_value('is_active_address', $editidcard[0]->enable_address) == 1) ? TRUE : FALSE); ?>>
                                        <label for="enable_address" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('phone'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_phone" name="is_active_phone" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_phone', '1', (set_value('is_active_phone', $editidcard[0]->enable_phone) == 1) ? TRUE : FALSE); ?>>
                                        <label for="enable_phone" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('date_of_birth'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_dob" name="is_active_dob" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_dob', '1', (set_value('is_active_dob', $editidcard[0]->enable_dob) == 1) ? TRUE : FALSE); ?>>
                                        <label for="enable_dob" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('blood_group'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_blood_group" name="is_active_blood_group" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_blood_group', '1', (set_value('is_active_blood_group', $editidcard[0]->enable_blood_group) == 1) ? TRUE : FALSE); ?>>
                                        <label for="enable_blood_group" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('design_type'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_vertical_card" name="enable_vertical_card" type="checkbox" class="chk" value="1" <?php echo set_checkbox('enable_vertical_card', '1', (set_value('enable_vertical_card', $editidcard[0]->enable_vertical_card) == 1) ? TRUE : FALSE); ?>>
                                        <label for="enable_vertical_card" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('barcode'); ?> / <?php echo $this->lang->line('qrcode'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="enable_student_barcode" name="enable_student_barcode" type="checkbox" class="chk" value="1" <?php echo set_checkbox('enable_student_barcode', '1', (set_value('enable_student_barcode', $editidcard[0]->enable_student_barcode) == 1) ? TRUE : FALSE); ?>>
                                        <label for="enable_student_barcode" class="label-success"></label>
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
            <?php } ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('student_id_card', 'can_add') || $this->rbac->hasPrivilege('student_id_card', 'can_edit')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">
                <!-- general form elements -->
                <div class="box box-primary" id="hroom">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('student_id_card_list'); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('student_id_card_list'); ?></div>
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
                                        <?php if (empty($idcardlist)) {
                                            ?>

                                            <?php
                                        } else {
                                            $count = 1;
                                            foreach ($idcardlist as $idcard) {
                                                ?>
                                                <tr>
                                                    <td class="mailbox-name">                                           
                                                        
                                                        <a data-id="<?php echo $idcard->id ?>" class="btn btn-default btn-xs view_data"  data-toggle="tooltip" ><?php echo $idcard->title; ?></a>                                                 
                                                    </td>
                                                    <td class="mailbox-name">
                                                        <?php if ($idcard->background != '' && !is_null($idcard->background)) { ?>
                                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/background/'.$idcard->background) ?>" width="40">
                                                        <?php } else { ?>
                                                            <i class="fa fa-picture-o fa-3x" aria-hidden="true"></i>
                                                        <?php } ?>
                                                    </td>
                                                       <td class="mailbox-name text text-center">
                                                    <?php echo ($idcard->enable_vertical_card) ? $this->lang->line('vertical') : $this->lang->line('horizontal') ?>
                                                </td>
                                                    <td class="mailbox-date pull-right no-print">
                                                        <a data-id="<?php echo $idcard->id ?>" class="btn btn-default btn-xs view_data" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>">
                                                            <i class="fa fa-reorder"></i>
                                                        </a>
                                                        <?php if ($this->rbac->hasPrivilege('student_id_card', 'can_edit')) { ?>
                                                            <a href="<?php echo base_url(); ?>admin/studentidcard/edit/<?php echo $idcard->id ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                            <?php
                                                        }
                                                        if ($this->rbac->hasPrivilege('student_id_card', 'can_delete')) {
                                                            ?>
                                                            <a href="<?php echo base_url(); ?>admin/studentidcard/delete/<?php echo $idcard->id ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                <i class="fa fa-remove"></i>
                                                            </a>
                                                        <?php } ?>
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
                url: "<?php echo base_url('admin/studentidcard/view') ?>",
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

    function removelogo_image(){
       var result = confirm("<?php echo $this->lang->line('delete_confirm')?>");
        if (result) {
            $('.logo_image').html('<input type="hidden" name="removelogo_image" value="1">');
        } 
    }

    function removesign_image(){
       var result = confirm("<?php echo $this->lang->line('delete_confirm')?>");
        if (result) {
            $('.sign_image').html('<input type="hidden" name="removesign_image" value="1">');
        } 
    }
</script>