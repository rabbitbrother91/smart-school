<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-newspaper-o"></i> <?php echo $this->lang->line('certificate'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <?php
if ($this->rbac->hasPrivilege('design_marksheet', 'can_edit')) {
    ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_marksheet'); ?></h3>
                        </div><!-- /.box-header -->
                        <form  enctype="multipart/form-data" action="<?php echo site_url('admin/marksheet/edit/' . $marksheet->id) ?>" id="editform" name="editform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php echo validation_errors(); ?>
                                <input type="hidden" name="id" value="<?php echo $marksheet->id; ?>">
                                <?php if ($this->session->flashdata('msg')) {
        ?>
                                    <?php echo $this->session->flashdata('msg');
        $this->session->unset_userdata('msg'); ?>
                                <?php }?>
                                <?php
if (isset($error_message)) {
        echo "<div class='alert alert-danger'>" . $error_message . "</div>";
    }
    ?>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('template'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="template" name="template" placeholder="" type="text" class="form-control" value="<?php echo set_value('template', $marksheet->template); ?>"/>
                                    <span class="text-danger"><?php echo form_error('template'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('exam_name'); ?></label>
                                    <input autofocus="" id="exam_name" name="exam_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('exam_name', $marksheet->exam_name); ?>"/>
                                    <span class="text-danger"><?php echo form_error('exam_name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('school_name'); ?></label>
                                    <input autofocus="" id="school_name" name="school_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('school_name', $marksheet->school_name); ?>"/>
                                    <span class="text-danger"><?php echo form_error('school_name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('exam_center') ?></label>
                                    <input autofocus="" id="exam_center" name="exam_center" placeholder="" type="text" class="form-control" value="<?php echo set_value('exam_center', $marksheet->exam_center); ?>"/>
                                    <span class="text-danger"><?php echo form_error('exam_center'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('body_text'); ?></label>
                                    <textarea name="content" type="text" class="form-control"><?php echo set_value('content', $marksheet->content); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('content'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('footer_text'); ?></label>
                                    <textarea name="content_footer" type="text" class="form-control"><?php echo set_value('content_footer', $marksheet->content_footer); ?></textarea>

                                    <span class="text-danger"><?php echo form_error('content_footer'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('printing_date'); ?></label>
                                    <input autofocus="" id="date" name="date" placeholder="" type="text" class="form-control date" value="<?php echo set_value('date', $marksheet->date); ?>"/>
                                    <span class="text-danger"><?php echo form_error('date'); ?></span>
                                </div>
                                  <div class="form-group">
                                    <label><?php echo $this->lang->line('header_image'); ?></label>
                                    <input id="documents" name="header_image" placeholder="" type="file" class="filestyle form-control" data-height="40" >

                                    <span class="text-danger"><?php echo form_error('header_image'); ?></span>

                                    <?php if (!empty($marksheet->header_image)) {
        ?>
                                        <div class="header_image">
                                            <div class="fadeheight-sms">
                                             <p class=""> <a class="uploadclosebtn" title="<?php echo $this->lang->line('delete_header_image'); ?>"><i class="fa fa-trash-o" onclick="removeheader_image()"></i></a><?php echo $marksheet->header_image; ?>
                                             </p>
                                            </div>
                                        </div>
                                    <?php }?>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('left_logo'); ?></label>
                                    <input id="documents" name="left_logo" placeholder="" type="file" class="filestyle form-control" data-height="40">
                                    <span class="text-danger"><?php echo form_error('left_logo'); ?></span>
                                    <?php if (!empty($marksheet->left_logo)) {
        ?>
                                        <div class="left_logo">
                                            <div class="fadeheight-sms">
                                             <p class=""> <a class="uploadclosebtn" title="<?php echo $this->lang->line('delete_left_logo'); ?>"><i class="fa fa-trash-o" onclick="removeleft_logo()"></i></a><?php echo $marksheet->left_logo; ?>
                                             </p>
                                            </div>
                                        </div>
                                    <?php }?>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('right_logo'); ?></label>
                                    <input id="documents" name="right_logo" placeholder="" type="file" class="filestyle form-control" data-height="40">

                                    <span class="text-danger"><?php echo form_error('right_logo'); ?></span>

                                    <?php if (!empty($marksheet->right_logo)) {
        ?>
                                        <div class="right_logo">
                                            <div class="fadeheight-sms">
                                             <p class=""> <a class="uploadclosebtn" title="<?php echo $this->lang->line('delete_right_logo'); ?>"><i class="fa fa-trash-o" onclick="removeright_logo()"></i></a><?php echo $marksheet->right_logo; ?>
                                             </p>
                                            </div>
                                        </div>
                                    <?php }?>

                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('left_sign') ?></label>
                                    <input id="documents" name="left_sign" placeholder="" type="file" class="filestyle form-control" data-height="40"  name="left_sign">

                                    <span class="text-danger"><?php echo form_error('left_sign'); ?></span>
                                    <?php if (!empty($marksheet->left_sign)) {
        ?>
                                        <div class="left_sign">
                                            <div class="fadeheight-sms">
                                             <p class=""> <a class="uploadclosebtn" title="<?php echo $this->lang->line('delete_left_sign'); ?>"><i class="fa fa-trash-o" onclick="removeleft_sign()"></i></a><?php echo $marksheet->left_sign; ?>
                                             </p>
                                            </div>
                                        </div>
                                    <?php }?>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('middle_sign') ?></label>
                                    <input id="documents" name="middle_sign" placeholder="" type="file" class="filestyle form-control" data-height="40"  name="middle_sign">
                                    <span class="text-danger"><?php echo form_error('middle_sign'); ?></span>
                                    <?php if (!empty($marksheet->middle_sign)) {
        ?>
                                        <div class="middle_sign">
                                            <div class="fadeheight-sms">
                                             <p class=""> <a class="uploadclosebtn" title="<?php echo $this->lang->line('delete_middle_sign'); ?>"><i class="fa fa-trash-o" onclick="removemiddle_sign()"></i></a><?php echo $marksheet->middle_sign; ?>
                                             </p>
                                            </div>
                                        </div>
                                    <?php }?>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('right_sign'); ?></label>
                                    <input id="documents" name="right_sign" placeholder="" type="file" class="filestyle form-control" data-height="40"  name="right_sign">
                                    <span class="text-danger"><?php echo form_error('right_sign'); ?></span>
                                    <?php if (!empty($marksheet->right_sign)) {
        ?>
                                        <div class="right_sign">
                                            <div class="fadeheight-sms">
                                             <p class=""> <a class="uploadclosebtn" title="<?php echo $this->lang->line('delete_right_sign'); ?>"><i class="fa fa-trash-o" onclick="removeright_sign()"></i></a><?php echo $marksheet->right_sign; ?>
                                             </p>
                                            </div>
                                        </div>
                                    <?php }?>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('background_image'); ?></label>
                                    <input id="documents" name="background_img" placeholder="" type="file" class="filestyle form-control" data-height="40"  name="background_image">
                                    <span class="text-danger"><?php echo form_error('background_img'); ?></span>
                                    <?php if (!empty($marksheet->background_img)) {
        ?>
                                        <div class="background_img">
                                            <div class="fadeheight-sms">
                                             <p class=""> <a class="uploadclosebtn" title="<?php echo $this->lang->line('delete_background_img'); ?>"><i class="fa fa-trash-o" onclick="removebackground_img()"></i></a><?php echo $marksheet->background_img; ?>
                                             </p>
                                            </div>
                                        </div>
                                    <?php }?>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('name'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_name" name="is_name" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_name', '1', (set_value('is_name', $marksheet->is_name) == 1) ? true : false); ?>>
                                        <label for="is_name" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('father_name'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_father_name" name="is_father_name" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_father_name', '1', (set_value('is_father_name', $marksheet->is_father_name) == 1) ? true : false); ?>>
                                        <label for="is_father_name" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('mother_name'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_mother_name" name="is_mother_name" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_mother_name', '1', (set_value('is_mother_name', $marksheet->is_mother_name) == 1) ? true : false); ?>>
                                        <label for="is_mother_name" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('exam_session'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="exam_session" name="exam_session" type="checkbox" class="chk" value="1" <?php echo set_checkbox('exam_session', '1', (set_value('exam_session', $marksheet->exam_session) == 1) ? true : false); ?>>
                                        <label for="exam_session" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('admission_no'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_admission_no" name="is_admission_no" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_admission_no', '1', (set_value('is_admission_no', $marksheet->is_admission_no) == 1) ? true : false); ?>>
                                        <label for="is_admission_no" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('division'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_division" name="is_division" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_division', '1', (set_value('is_division', $marksheet->is_division) == 1) ? true : false); ?>>
                                        <label for="is_division" class="label-success"></label>
                                    </div>
                                </div>
                                   <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('rank'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_rank" name="is_rank" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_rank', '1', (set_value('is_rank', $marksheet->is_rank) == 1) ? true : false); ?>>
                                        <label for="is_rank" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('roll_number'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_roll_no" name="is_roll_no" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_roll_no', '1', (set_value('is_roll_no', $marksheet->is_roll_no) == 1) ? true : false); ?>>
                                        <label for="is_roll_no" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('photo'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_photo" name="is_photo" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_photo', '1', (set_value('is_photo', $marksheet->is_photo) == 1) ? true : false); ?>>
                                        <label for="is_photo" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('class'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_class" name="is_class" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_class', '1', (set_value('is_class', $marksheet->is_class) == 1) ? true : false); ?>>
                                        <label for="is_class" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('section'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_section" name="is_section" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_section', '1', (set_value('is_section', $marksheet->is_section) == 1) ? true : false); ?>>
                                        <label for="is_section" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('date_of_birth') ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_dob" name="is_dob" type="checkbox" class="chk" value="1"  <?php echo set_checkbox('is_dob', '1', (set_value('is_dob', $marksheet->is_dob) == 1) ? true : false); ?>>
                                        <label for="is_dob" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('remark'); ?> </label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_teacher_remark" name="is_teacher_remark" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_teacher_remark', '1', (set_value('is_teacher_remark', $marksheet->is_teacher_remark) == 1) ? true : false); ?>>
                                        <label for="is_teacher_remark" class="label-success"></label>
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
if ($this->rbac->hasPrivilege('design_marksheet', 'can_edit')) {
    echo "8";
} else {
    echo "12";
}
?>">
                <!-- general form elements -->
                <div class="box box-primary" id="hroom">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('marksheet_list'); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible">
                            <div class="download_label"><?php echo $this->lang->line('marksheet_list'); ?></div>
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
                                                    <a style="cursor: pointer;" class="view_data" id="<?php echo $certificate->id ?>" data-toggle="popover" class="detail_popover" ><?php echo $certificate->template; ?></a>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php if ($certificate->background_img != '' && !is_null($certificate->background_img)) {?>
                                                        <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/'.$certificate->background_img) ?>" width="40">
                                                    <?php } else {?>
                                                        <i class="fa fa-picture-o fa-3x" aria-hidden="true"></i>
                                                    <?php }?>

                                                </td>
                                                <td class="mailbox-date text-right no-print white-space-nowrap">
                                                    <a data-toggle="tooltip" id="<?php echo $certificate->id ?>" class="btn btn-default btn-xs view_data" title="<?php echo $this->lang->line('view'); ?>">
                                                        <i class="fa fa-reorder"></i>
                                                    </a>
                                                    <?php
if ($this->rbac->hasPrivilege('design_marksheet', 'can_edit')) {
            ?>
                                                        <a href="<?php echo site_url('admin/marksheet/edit/' . $certificate->id); ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        <?php
}
        if ($this->rbac->hasPrivilege('design_marksheet', 'can_delete')) {
            ?>
                                                        <a href="<?php echo base_url(); ?>admin/marksheet/delete/<?php echo $certificate->id ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
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
                <h4 class="modal-title"><?php echo $this->lang->line('view'); ?> <?php echo $this->lang->line('marksheet'); ?></h4>
            </div>
            <div class="modal-body" id="certificate_detail">

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#btnreset").click(function () {
            $("#form1")[0].reset();
        });
    });
</script>

<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';
    function printDiv(elem) {
        Popup(jQuery(elem).html());
    }

    function Popup(data)
    {
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);
        return true;
    }
</script>

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
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.view_data').click(function () {
            var certificateid = $(this).attr("id");
            $.ajax({
                url: "<?php echo base_url('admin/marksheet/view') ?>",
                method: "post",
                data: {certificateid: certificateid},
                dataType: 'JSON',
                success: function (data) {
                    $('#certificate_detail').html(data.page);
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

    function removeheader_image(){
       var result = confirm("<?php echo $this->lang->line('delete_confirm') ?>");
        if (result) {
            $('.header_image').html('<input type="hidden" name="removeheader_image" value="1">');
        }
    }

    function removeleft_logo(){
       var result = confirm("<?php echo $this->lang->line('delete_confirm') ?>");
        if (result) {
            $('.left_logo').html('<input type="hidden" name="removeleft_logo" value="1">');
        }
    }
    function removeright_logo(){
       var result = confirm("<?php echo $this->lang->line('delete_confirm') ?>");
        if (result) {
            $('.right_logo').html('<input type="hidden" name="removeright_logo" value="1">');
        }
    }
    function removeleft_sign(){
       var result = confirm("<?php echo $this->lang->line('delete_confirm') ?>");
        if (result) {
            $('.left_sign').html('<input type="hidden" name="removeleft_sign" value="1">');
        }
    }
    function removemiddle_sign(){
       var result = confirm("<?php echo $this->lang->line('delete_confirm') ?>");
        if (result) {
            $('.middle_sign').html('<input type="hidden" name="removemiddle_sign" value="1">');
        }
    }
    function removeright_sign(){
       var result = confirm("<?php echo $this->lang->line('delete_confirm') ?>");
        if (result) {
            $('.right_sign').html('<input type="hidden" name="removeright_sign" value="1">');
        }
    }
    function removebackground_img(){
       var result = confirm("<?php echo $this->lang->line('delete_confirm') ?>");
        if (result) {
            $('.background_img').html('<input type="hidden" name="removebackground_img" value="1">');
        }
    }
</script>