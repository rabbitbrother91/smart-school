<style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
/*    .switch-inline label {width: 100px;}*/
</style>
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
if ($this->rbac->hasPrivilege('design_admit_card', 'can_add')) {
    ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('add_admit_card'); ?></h3>
                        </div><!-- /.box-header -->
                        <form id="form1" enctype="multipart/form-data" action="<?php echo site_url('admin/admitcard') ?>"  id="certificateform" name="certificateform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) {?>
                                    <?php 
                                        echo $this->session->flashdata('msg');
                                        $this->session->unset_userdata('msg');
                                    ?>
                                <?php }?>
                                <?php
if (isset($error_message)) {
        echo "<div class='alert alert-danger'>" . $error_message . "</div>";
    }
    ?>
                                <div class="form-group">
                                    <label> <?php echo $this->lang->line('template') ?></label><small class="req"> *</small>
                                    <input autofocus="" id="template" value="<?php echo set_value('template'); ?>" name="template" placeholder="" type="text" class="form-control" />
                                    <span class="text-danger"><?php echo form_error('template'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label> <?php echo $this->lang->line('heading'); ?></label>
                                    <input autofocus="" id="heading" name="heading" value="<?php echo set_value('heading'); ?>" placeholder="" type="text" class="form-control" />
                                    <span class="text-danger"><?php echo form_error('heading'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label> <?php echo $this->lang->line('title') ?></label>
                                    <input autofocus="" id="title" value="<?php echo set_value('title'); ?>" name="title" placeholder="" type="text" class="form-control" />
                                    <span class="text-danger"><?php echo form_error('title'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label> <?php echo $this->lang->line('exam_name'); ?></label>
                                    <input autofocus="" id="exam_name" value="<?php echo set_value('exam_name'); ?>" name="exam_name" placeholder="" type="text" class="form-control" />
                                    <span class="text-danger"><?php echo form_error('exam_name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label> <?php echo $this->lang->line('school_name'); ?></label>
                                    <input autofocus="" id="school_name" value="<?php echo set_value('school_name'); ?>" name="school_name" placeholder="" type="text" class="form-control" />
                                    <span class="text-danger"><?php echo form_error('school_name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label> <?php echo $this->lang->line('exam_center'); ?></label>
                                    <input autofocus="" id="exam_center" value="<?php echo set_value('exam_center'); ?>" name="exam_center" placeholder="" type="text" class="form-control" />
                                    <span class="text-danger"><?php echo form_error('exam_center'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('footer_text'); ?></label>
                                    <textarea name="content_footer" type="text" class="form-control"><?php echo set_value('content_footer'); ?></textarea>

                                    <span class="text-danger"><?php echo form_error('content_footer'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label> <?php echo $this->lang->line('left_logo'); ?></label>
                                    <input id="documents" name="left_logo" placeholder="" type="file" class="filestyle form-control" data-height="28" >
                                    <span class="text-danger"><?php echo form_error('left_logo'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('right_logo'); ?></label>
                                    <input id="documents" name="right_logo" placeholder="" type="file" class="filestyle form-control" data-height="28">
                                    <span class="text-danger"><?php echo form_error('right_logo'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('sign'); ?></label>
                                    <input id="documents" name="sign" placeholder="" type="file" class="filestyle form-control" data-height="28"  name="sign">
                                    <span class="text-danger"><?php echo form_error('sign'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('background_image'); ?></label>
                                    <input id="documents" name="background_img" placeholder="" type="file" class="filestyle form-control" data-height="28"  name="background_image">
                                    <span class="text-danger"><?php echo form_error('background_img'); ?></span>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('name'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_name" name="is_name" type="checkbox" class="chk" value="1">
                                        <label for="is_name" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('father_name'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_father_name" name="is_father_name" type="checkbox" class="chk" value="1">
                                        <label for="is_father_name" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('mother_name'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_mother_name" name="is_mother_name" type="checkbox" class="chk" value="1">
                                        <label for="is_mother_name" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('date_of_birth'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_dob" name="is_dob" type="checkbox" class="chk" value="1">
                                        <label for="is_dob" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('admission_no'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_admission_no" name="is_admission_no" type="checkbox" class="chk" value="1">
                                        <label for="is_admission_no" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('roll_number'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_roll_no" name="is_roll_no" type="checkbox" class="chk" value="1">
                                        <label for="is_roll_no" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('address') ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_address" name="is_address" type="checkbox" class="chk" value="1">
                                        <label for="is_address" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('gender'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_gender" name="is_gender" type="checkbox" class="chk" value="1">
                                        <label for="is_gender" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('photo'); ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_photo" name="is_photo" type="checkbox" class="chk" value="1">
                                        <label for="is_photo" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('class') ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_class" name="is_class" type="checkbox" class="chk" value="1">
                                        <label for="is_class" class="label-success"></label>
                                    </div>
                                </div>
                                <div class="form-group switch-inline">
                                    <label><?php echo $this->lang->line('section') ?></label>
                                    <div class="material-switch switchcheck">
                                        <input id="is_section" name="is_section" type="checkbox" class="chk" value="1">
                                        <label for="is_section" class="label-success"></label>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" id="submitbtn" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
            <?php }?>
            <div class="col-md-<?php
if ($this->rbac->hasPrivilege('design_admit_card', 'can_add')) {
    echo "8";
} else {
    echo "12";
}
?>">
                <!-- general form elements -->
                <div class="box box-primary" id="hroom">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('admit_card_list'); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible">
                            <div class="download_label"> <?php echo $this->lang->line('admit_card_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('certificate_name'); ?></th>
                                        <th><?php echo $this->lang->line('background_image'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($admitcardList)) {
    ?>
                                        <?php
} else {
    $count = 1;
    foreach ($admitcardList as $certificate) {
        ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <a style="cursor: pointer;" class="view_data" id="<?php echo $certificate->id ?>" data-toggle="popover" class="detail_popover" ><?php echo $certificate->template; ?></a>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php if ($certificate->background_img != '' && !is_null($certificate->background_img)) {?>
                                                        <img src="<?php echo $this->media_storage->getImageURL('uploads/admit_card/'. $certificate->background_img) ?>" width="40">
                                                    <?php } else {?>
                                                        <i class="fa fa-picture-o fa-3x" aria-hidden="true"></i>
                                                    <?php }?>
                                                </td>
                                                <td class="mailbox-date text-right no-print white-space-nowrap">
                                                    <a data-toggle="tooltip"  id="<?php echo $certificate->id ?>" class="btn btn-default btn-xs view_data" title="<?php echo $this->lang->line('view'); ?>">
                                                        <i class="fa fa-reorder"></i>
                                                    </a>
                                                    <?php
if ($this->rbac->hasPrivilege('design_admit_card', 'can_edit')) {
            ?>
                                                        <a href="<?php echo site_url('admin/admitcard/edit/' . $certificate->id); ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        <?php
}
        if ($this->rbac->hasPrivilege('design_admit_card', 'can_delete')) {
            ?>
                                                        <a href="<?php echo base_url(); ?>admin/admitcard/delete/<?php echo $certificate->id ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
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
                <h4 class="modal-title"><?php echo $this->lang->line('view_admit_card'); ?></h4>
            </div>
            <div class="modal-body" id="certificate_detail">

            </div>
        </div>
    </div>
</div>

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
                url: "<?php echo base_url('admin/admitcard/view') ?>",
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
</script>
<script>
    $(function(){
        $('#form1'). submit( function() {
          
            $("#submitbtn").button('loading');
        });
    })
</script>