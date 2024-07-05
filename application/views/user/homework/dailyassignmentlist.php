<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-flask"></i>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info"><div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('daily_assignment_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-sm btn-primary" data-backdrop="static" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> <?php echo $this->lang->line('daily_assignment'); ?></button>
                        </div>
                    </div>
                    <div class="box-body table-responsive overflow-visible">
                        <div class="download_label"><?php echo $this->lang->line('daily_assignment_list'); ?></div>
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('subject') ?></th>
                                        <th><?php echo $this->lang->line('title') ?></th>
                                        <th><?php echo $this->lang->line('description') ?></th>
                                        <th><?php echo $this->lang->line('remark') ?></th>
                                        <th><?php echo $this->lang->line('submission_date') ?></th>
                                        <th><?php echo $this->lang->line('evaluation_date') ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

foreach ($dailyassignmentlist as $key => $dailyassignmentlist_value) {
    ?>
                                        <tr>
                                            <td><?php echo $dailyassignmentlist_value["subject_name"] ?> <?php if($dailyassignmentlist_value["subject_code"]){ echo '('. $dailyassignmentlist_value["subject_code"] .')'; } ?></td>
                                            <td><?php echo $dailyassignmentlist_value["title"] ?></td>
                                            <td><?php echo $dailyassignmentlist_value["description"] ?></td>
                                            <td><?php echo $dailyassignmentlist_value["remark"] ?></td>
                                            <td><?php echo  $this->customlib->dateformat($dailyassignmentlist_value["date"]); ?></td>
                                            <td><?php if ($dailyassignmentlist_value["evaluation_date"] != "" && $dailyassignmentlist_value["evaluation_date"] != '0000-00-00') {
                                            echo  $this->customlib->dateformat($dailyassignmentlist_value["evaluation_date"]);
                                            }?></td>
                                            <td class="white-space-nowrap">
                                            <?php if ($dailyassignmentlist_value["attachment"] != '') {?>
                                                <a class="btn btn-default btn-xs" href="<?php echo base_url(); ?>user/homework/dailyassigmnetdownload/<?php echo $dailyassignmentlist_value['id']; ?>" data-toggle='tooltip' title="<?php echo $this->lang->line("download"); ?>"><i class="fa fa-download"></i></a>
                                            <?php }?>
                                            <?php if ($dailyassignmentlist_value["evaluated_by"] == NULL) {?>
                                                <a class="btn btn-default btn-xs edit_modal_btn" data-toggle="tooltip" data-id ="<?php echo $dailyassignmentlist_value["id"]; ?>" data-original-title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>
                                                
                                                <a href="<?php echo base_url(); ?>user/homework/deletedailyassignment/<?php echo $dailyassignmentlist_value['id']; ?>" class='btn btn-default btn-xs mt-5 pull-right' data-toggle='tooltip' title="<?php echo $this->lang->line('delete'); ?>" onclick='return confirm("<?php echo $this->lang->line('delete_confirm'); ?>")'><i class='fa fa-remove'></i></a>
                                            <?php }?>

                                            </td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('add_daily_assignment'); ?></h4>
            </div>
            <form id="formadd" method="post" class="ptt10" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="row">  
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">  
                                        <label><?php echo $this->lang->line('subject'); ?></label><small class="req"> *</small>
                                        <select name="subject" id="subject" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($subjectlist  as $key => $subjectlist_value) {?>
                                                <option value="<?php echo $subjectlist_value->subject_group_subject_id; ?>"><?php echo $subjectlist_value->subject_name; ?> <?php if($subjectlist_value->subject_code){ echo '('.$subjectlist_value->subject_code .')'; }?> </option>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <span id="subject_add_error" class="text-danger"><?php echo form_error('subject'); ?></span>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                        <input type="text" id="title" name="title" class="form-control">
                                    </div>
                                    <span id="name_add_error" class="text-danger"><?php echo form_error('title'); ?></span>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('description'); ?></label>
                                        <textarea class="form-control" id="description" name="description" rows="4" cols="50"></textarea>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('attach_document'); ?></label>
                                        <input type="file" id="file" name="file" class="form-control filestyle">
                                    </div>
                                </div>
                            </div><!--./row-->
                        </div><!--./col-md-12-->
                    </div><!--./row-->
                </div>
                <div class="box-footer">
                    <div class="paddA10">
                        <button type="submit" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_modal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_daily_assignment'); ?></h4>
            </div>

            <form id="editassignmentform" method="post" class="ptt10" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0 ">
                    <div id="assignment_data"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

    $('.edit_modal_btn').click(function(){
       $('#edit_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
       var assignment_id = $(this).attr('data-id');
       $.ajax({
            url: "<?php echo site_url("user/homework/editdailyassignment") ?>",
            type: "POST",
            data : {assignment_id:assignment_id},
            dataType : 'json',
            success: function (res)
            {
               $('#assignment_data').html(res.page);
            }
        });
    })

    $("#formadd").on('submit', (function (e) {
        e.preventDefault();

        var $this = $(this).find("button[type=submit]:focus");

        $.ajax({
            url: "<?php echo site_url("user/homework/createdailyassignment") ?>",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (res)
            {

                if (res.status == "fail") {
                    var message = "";
                    $.each(res.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(res.message);
                    window.location.reload(true);
                }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
    }));

    $("#editassignmentform").on('submit', (function (e) {
        e.preventDefault();

        var $this = $(this).find("button[type=submit]:focus");

        $.ajax({
            url: "<?php echo site_url("user/homework/updatedailyassignment") ?>",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (res)
            {

                if (res.status == "fail") {

                    var message = "";
                    $.each(res.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);

                } else {

                    successMsg(res.message);

                    window.location.reload(true);
                }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
    }));
</script>