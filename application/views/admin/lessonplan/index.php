<style type="text/css">
    .table .pull-right {text-align: initial; width: auto; margin-bottom: 3px}
</style>

<?php
$language      = $this->customlib->getLanguage();
$language_name = $language["short_code"];
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-flask"></i> <?php echo $this->lang->line('manage_lesson_plan'); ?>
        </h1>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
            </div>
            <form class="assign_teacher_form" action="<?php echo base_url(); ?>admin/syllabus/status" method="post" enctype="multipart/form-data">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if ($this->session->flashdata('msg')) {
    ?>
                                <?php echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg'); ?>
                            <?php }?>
                            <?php echo $this->customlib->getCSRF(); ?>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                <select autofocus="" id="searchclassid" name="class_id" onchange="getSectionByClass(this.value, 0, 'secid')"  class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
foreach ($classlist as $class) {
    ?>
                                        <option <?php
if ($class_id == $class["id"]) {
        echo "selected";
    }
    ?> value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                            <?php
}
?>
                                </select>
                                <span class="class_id_error text-danger"><?php echo form_error('class_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                <select  id="secid" name="section_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="class_id_error text-danger"><?php echo form_error('section_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('subject_group'); ?></label><small class="req"> *</small>
                                <select  id="subject_group_id" name="subject_group_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="class_id_error text-danger"><?php echo form_error('subject_group_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('subject'); ?></label><small class="req"> *</small>
                                <select  id="subid" name="subject_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="class_id_error text-danger"><?php echo form_error('subject_id'); ?></span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" id="search_filter" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                </div>
            </form>

            <?php if (!empty($lessons)) {
    ?>

                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('syllabus_status_for') . ": " . $subject_name; ?></h3>
                </div>
                <div class="box-body" id="transfee">
                    <div class="table-responsive mailbox-messages overflow-visible-lg">

                    <p class="pull-right">
                    <a class="btn btn-default btn-xs displayinline" title="<?php echo $this->lang->line('export'); ?>" id="btnExport" onclick="fnExcelReport();"> <i class="fa fa-file-excel-o"></i> </a>
                        <a class="btn btn-default btn-xs displayinline" title="<?php echo $this->lang->line('print'); ?>" id="print" onclick="printDiv()" ><i class="fa fa-print"></i></a> </p>

                        <table class="table table-bordered topictable ptt10" id="headerTable">
                            <tr class="hide" id="visible">
                                <td colspan="5"><center><b><?php echo $this->lang->line('syllabus_status_for') . ": " . $subject_name; ?></b></center></td>
                            </tr>
                            <tr>
                                <th width="30">#</th>
                                <th width="60%"><?php echo $this->lang->line('lesson_topic'); ?></th>
                                <th width="20%"><?php echo $this->lang->line('topic_completion_date'); ?></th>
                                <th width=""><?php echo $this->lang->line('status'); ?></th>
                                <?php if ($this->rbac->hasPrivilege('manage_syllabus_status', 'can_edit')) {?>
                                    <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                <?php }?>
                            </tr>
                            <?php
$losson_count = 1;
    foreach ($lessons as $key => $value) {
        ?>
                                <tr>
                                    <td><?php echo $losson_count; ?></td>
                                    <td>
                                        <h4><?php echo $value['name']; ?></h4>
                                        <ul class="stausbtns">
                                            <?php
if (isset($value['topic'])) {
            $topic_count = 1;
            foreach ($value['topic'] as $topic_key => $topic_value) {
                ?>
                                                    <li><span><?php echo $losson_count . "." . $topic_count; ?></span><?php echo $topic_value['name']; ?></li>

                                                    <?php
$topic_count++;
            }
        }
        ?>
                                        </ul>
                                    </td>
                                    <td>
                                        <h4 style="height: 14px;"></h4>
                                        <ul class="stausbtns">
                                            <?php
if (isset($value['topic'])) {

            foreach ($value['topic'] as $topic_key => $topic_value) {
                ?>
                                                    <?php if ($topic_value['status'] == 1) {?> <li><?php if($topic_value['complete_date']!= '0000-00-00' && !empty($topic_value['complete_date'])){ echo date($this->customlib->getSchoolDateFormat(), strtotime($topic_value['complete_date'])); } ?> </li><?php } else {?>
                                                        <li> &nbsp; </li>
                                                    <?php }?>

                                                    <?php
}
        }
        ?>
                                        </ul>
                                    </td>
                                    <td>
                                        <h4 style="height: 14px;"></h4>
                                        <ul class="stausbtns">
                                            <?php
if (isset($value['topic'])) {

            foreach ($value['topic'] as $topic_key => $topic_value) {
                ?>
                                                    <li><?php echo $status[$topic_value['status']]; ?></li>

                                                    <?php
}
        }
        ?>
                                        </ul>
                                    </td>
                                    <?php if ($this->rbac->hasPrivilege('manage_syllabus_status', 'can_edit')) {
            ?>
                                        <td class="pull-right">
                                            <h4 style="height: 14px;"></h4>
                                            <ul class="topiclist">
                                                <?php
if (isset($value['topic'])) {

                foreach ($value['topic'] as $topic_key => $topic_value) {
                    ?>
                                                        <li>

                                                            <div class="material-switch pull-right">
                                                                <input id="topic<?php echo $topic_value['id'] ?>" name="someSwitchOption001" type="checkbox" class="chk" data-rowid="<?php echo $topic_value['id'] ?>" value="checked" <?php if ($topic_value['status'] == "1") {
                        echo "checked='checked'";
                    }
                    ?> />
                                                                <label for="topic<?php echo $topic_value['id'] ?>" class="label-success"></label>
                                                            </div>
                                                        </li>

                                                        <?php
}
            }
            ?>
                                            </ul>
                                        </td>
                                    <?php }?>
                                </tr>
                                <?php
$losson_count++;
    }
    ?>
                        </table>
                    </div><!--./table-responsive-->
                </div>
                <?php
} else {
    if ($no_record != 0) {
        ?>

                    <div class="box-header">
                        <div class="alert alert-danger"> <center><?php echo $this->lang->line('no_record_found'); ?></center></div>
                    </div>
                <?php
}
}
?>
    </section>
</div>
<div id="topic_status" class="modal fade " role="dialog">
    <div class="modal-dialog modal-dialog2 modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="close_modal()" >&times;</button>
                <h4 class="modal-title" id="modal-title" ><?php echo $this->lang->line('topic_completion_date'); ?> <small style="color:red;"> *</small></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form role="form" id="addevent_form11" method="post" enctype="multipart/form-data" action="">
                        <div class="form-group col-md-12">
                            <input type="hidden" id="topic_id" name="id">
                            <input class="form-control date" id="date" name="date" type="text"  >
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <button type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function close_modal() {
        window.location.reload(true);
    }

    $(document).ready(function () {
        $(document).on('click', '.chk', function () {
            var checked = $(this).is(':checked');
            var rowid = $(this).data('rowid');
            if (checked) {
                
                var status = "1";
                $('#topic_status').modal('show');
                $('#topic_id').val(rowid);                

            } else {
                if (!confirm('<?PHP echo $this->lang->line('change_status') ?>')) {
                    $(this).removeAttr('checked');
                    window.location.reload(true);
                } else {
                    var status = "0";
                    changeTopicStatus(rowid, status);
                }
            }
        });
    });

    function change_status(id) {
        $.ajax({
            type: "POST",
            url: base_url + "admin/lessonplan/get_",
            data: {'id': id},
            dataType: "json",
            success: function (data) {
                successMsg(data.msg);
            }
        });
    }

    function changeTopicStatus(rowid, status) {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            type: "POST",
            url: base_url + "admin/lessonplan/changeTopicStatus",
            data: {'id': rowid, 'status': status},
            dataType: "json",
            success: function (data) {
                successMsg(data.msg);
                window.location.reload(true);
            }
        });
    }

    $("#addevent_form11").on('submit', (function (e) {
        e.preventDefault();
        var $this = $(this).find("button[type=submit]:focus");
        $.ajax({
            url: "<?php echo site_url("admin/lessonplan/topic_completedate") ?>",
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
                    $('#date').val();
                    $('#topic_status').modal('hide');
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

<script>
    $(document).ready(function (e) {
        getSectionByClass("<?php echo $class_id ?>", "<?php echo $section_id ?>", 'secid');
        getSubjectGroup("<?php echo $class_id ?>", "<?php echo $section_id ?>", "<?php echo $subject_group_id ?>", 'subject_group_id')
        getsubjectBySubjectGroup("<?php echo $class_id ?>", "<?php echo $section_id ?>", "<?php echo $subject_group_id ?>", "<?php echo $subject_id ?>", 'subid');
    });

    function getSectionByClass(class_id, section_id, select_control) {
        if (class_id != "") {
            $('#' + select_control).html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('#' + select_control).addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#' + select_control).append(div_data);
                },
                complete: function () {
                    $('#' + select_control).removeClass('dropdownloading');
                }
            });
        }
    }

    $(document).on('change', '#secid', function () {
        var class_id = $('#searchclassid').val();
        var section_id = $(this).val();
        getSubjectGroup(class_id, section_id, 0, 'subject_group_id');
    });

    function getSubjectGroup(class_id, section_id, subjectgroup_id, subject_group_target) {
        if (class_id != "" && section_id != "") {
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: 'POST',
                url: base_url + 'admin/subjectgroup/getGroupByClassandSection',
                data: {'class_id': class_id, 'section_id': section_id},
                dataType: 'JSON',
                beforeSend: function () {
                    // setting a timeout
                    $('#' + subject_group_target).html("").addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (subjectgroup_id == obj.subject_group_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.subject_group_id + " " + sel + ">" + obj.name + "</option>";
                    });
                    $('#' + subject_group_target).append(div_data);
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

                },
                complete: function () {
                    $('#' + subject_group_target).removeClass('dropdownloading');
                }
            });
        }
    }

    $(document).on('change', '#subject_group_id', function () {
        var class_id = $('#searchclassid').val();
        var section_id = $('#secid').val();
        var subject_group_id = $(this).val();
        getsubjectBySubjectGroup(class_id, section_id, subject_group_id, 0, 'subid');
    });

    function getsubjectBySubjectGroup(class_id, section_id, subject_group_id, subject_group_subject_id, subject_target) {
        if (class_id != "" && section_id != "" && subject_group_id != "") {
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: 'POST',
                url: base_url + 'admin/subjectgroup/getGroupsubjects',
                data: {'subject_group_id': subject_group_id},
                dataType: 'JSON',
                beforeSend: function () {
                    // setting a timeout
                    $('#' + subject_target).html("").addClass('dropdownloading');
                },
                success: function (data) {
                    console.log(data);
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (subject_group_subject_id == obj.id) {
                            sel = "selected";
                        }
                        
                        var code ='';
                        if(obj.code){
                            code = " (" + obj.code + ") ";
                        }
                        
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.name + code + "</option>";
                    });
                    $('#' + subject_target).append(div_data);
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

                },
                complete: function () {
                    $('#' + subject_target).removeClass('dropdownloading');
                }
            });
        }
    }
</script>

<script>

    document.getElementById("print").style.display = "block";
    document.getElementById("btnExport").style.display = "block";

    function printDiv() {
        $("#visible").removeClass("hide");
        $(".pull-right").addClass("hide");
        $(".text-right").addClass("hide");

        document.getElementById("print").style.display = "none";
        document.getElementById("btnExport").style.display = "none";
        var divElements = document.getElementById('transfee').innerHTML;
        var oldPage = document.body.innerHTML;
        document.body.innerHTML =
                "<html><head><title></title></head><body>" +
                divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;
        location.reload(true);
    }

 
    
    
    $(document).ready(function () {      
        $('#topic_status').modal({          
            backdrop: 'static',
            keyboard: false,
            show: false
        });    
    });    
</script>