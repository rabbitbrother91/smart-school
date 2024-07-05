<div class="content-wrapper"> 
    <section class="content-header">
        <h1><i class="fa fa-mortar-board"></i> <small></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">           
            <div class="col-md-4">       
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('edit_lesson'); ?></h3>
                    </div>  
                    <form   id="lesson_form" name="lesson_form" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
                            <?php } ?> 
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="form-group">
                                <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                <select autofocus="" id="searchclassid" name="class_id" onchange="getSectionByClass(this.value, 0, 'secid')"  class="form-control" >
                                    <option value="" ><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    foreach ($classlist as $class) {
                                        ?>
                                        <option <?php
                                        if ($class_id == $class["id"]) {
                                            echo "selected";
                                        }
                                        ?> value="<?php echo $class['id'] ?>" ><?php echo $class['class'] ?></option>
                                            <?php
                                        }
                                        ?>
                                </select>
                                <input type="hidden" id="lesson_subjectid" name="lesson_subjectid" value="<?php echo $lesson_subject_group_subjectid; ?>" >
                                <span class="class_id_error text-danger"><?php echo form_error('class_id'); ?></span>
                            </div>
                            <div class="form-group">
                                <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                <select  id="secid" name="section_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="section_id_error text-danger"></span>
                            </div>

                            <div class="form-group">
                                <label><?php echo $this->lang->line('subject_group') ?></label><small class="req"> *</small>
                                <select  id="subject_group_id" name="subject_group_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="section_id_error text-danger"></span>
                            </div>

                            <div class="">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('subject'); ?></label><small class="req"> *</small>
                                    <select  id="subid" name="subject_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="section_id_error text-danger"></span>
                                </div>
                            </div></br></br>
                            <div class="form-group">
                                <?php if ($this->rbac->hasPrivilege('lesson', 'can_add')) {
                                    ?>
                                    <lebel class="btn btn-xs btn-info pull-right" onclick="add_lesson()"><?php echo $this->lang->line('add_more'); ?></lebel>
                                <?php } ?>
                            </div>
                            <?php foreach ($editlessonname as $editlessonname_value) { ?>
                                <div class="form-group" id="<?php echo $editlessonname_value['id']; ?>"><label><?php echo $this->lang->line('lesson_name'); ?></label><small class="req"> *</small><input type="text" name="lessons_<?php echo $editlessonname_value['id']; ?>" value="<?php echo $editlessonname_value['name']; ?>"  style="width:95%" />  <?php if ($this->rbac->hasPrivilege('lesson', 'can_delete')) {
                                    ?><span  onclick="remove_lesson('<?php echo $editlessonname_value['id']; ?>')" class="section_id_error text-danger">&nbsp;<i class="fa fa-remove"></i></span><?php } ?></div>
                                <?php } ?>
                            <div id="lesson_result"></div>
                            <div id="lesson_delete"></div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-8">              
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('lesson_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                            <div class="pull-right">
                            </div>
                        </div>
                        <div class="table-responsive mailbox-messages overflow-visible-lg" id="transfee">
                              <table class="table table-striped table-bordered table-hover topic-list " id="headerTable" data-export-title="<?php echo $this->lang->line('lesson_list'); ?>" id="headerTable" >
                                <thead>
                                    <tr class="hide" id="visible">
                                        <td colspan="6"><center><b><?php echo $this->lang->line("lesson_list"); ?></b></center></td>
                                    </tr>
                                <tr>
                                    <th><?php echo $this->lang->line('class'); ?></th>
                                    <th><?php echo $this->lang->line('section'); ?></th>
                                    <th><?php echo $this->lang->line('subject_group'); ?></th>
                                    <th><?php echo $this->lang->line('subject'); ?></th>
                                    <th><?php echo $this->lang->line('lesson'); ?></th>
                                    <th class="mailbox-date text-right noExport "><?php echo $this->lang->line('action'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="mailbox-controls">  
                            <div class="pull-right">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">          
            <div class="col-md-12">
            </div>
        </div> 
    </section>
</div>

<script>
    function deletelessonbulk(subject_group_class_sections_id, subject_group_subject_id) {
        if (confirm('<?PHP echo $this->lang->line('delete_confirm') ?>')) {
            $.ajax({
                url: base_url + 'admin/lessonplan/deletelessonbulk/' + subject_group_class_sections_id + '/' + subject_group_subject_id,
                success: function (res) {
                    successMsg(res.message);
                    window.location.replace("<?php echo base_url("admin/lessonplan/lesson") ?>");
                }

            })
        }
    }
</script>

<script>
    $(document).ready(function (e) {
        getSectionByClass("<?php echo $class_id ?>", "<?php echo $section_id ?>", 'secid');
        getSubjectGroup("<?php echo $class_id ?>", "<?php echo $section_id ?>", "<?php echo $subject_group_id ?>", 'subject_group_id');
        getsubjectBySubjectGroup("<?php echo $class_id ?>", "<?php echo $section_id ?>", "<?php echo $subject_group_id ?>", "<?php echo $lesson_subject_group_subjectid ?>", 'subid');
        $('#searchclassid').css('pointer-events', 'none');
        $('#secid').css('pointer-events', 'none');
        $('#subject_group_id').css('pointer-events', 'none');
        $('#subid').css('pointer-events', 'none');

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
                        
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.name + code +"</option>";
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

    function add_lesson() {
        var id = makeid(8);
        $('#lesson_result').append('<div class="form-group" id="' + id + '"><label><?php echo $this->lang->line("lesson_name"); ?></label><small class="req"> *</small><br><input type="text" name="lessons[]" class="lessinput" /> <span  onclick="remove_lesson(' + id + ')" class="section_id_error text-danger">&nbsp;<i class="fa fa-remove"></i></span></div>');
    }

    function remove_lesson(id) {
        $('#' + id).html("");
        $('#lesson_delete').append('<input type="hidden" name="lesson_delete[]" value="' + id + '">');
    }

    function makeid(length) {
        var result = '';
        var characters = '0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }

    $("#lesson_form").on('submit', (function (e) {
        e.preventDefault();
        var $this = $(this).find("button[type=submit]:focus");
        var inps = document.getElementsByName('lessons[]');
        for (var i = 0; i < inps.length; i++) {
            var inp = inps[i];
            if (inp.value == '') {
                
            } else {

            }
        }

        $.ajax({
            url: "<?php echo site_url("admin/lessonplan/updatelesson") ?>",
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
                    window.location.replace("<?php echo base_url("admin/lessonplan/lesson") ?>");
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
    ( function ( $ ) {
    'use strict';
    $(document).ready(function () {
        initDatatable('topic-list','admin/lessonplan/getlessonlist',[],[],100);
    });
} ( jQuery ) )
</script>