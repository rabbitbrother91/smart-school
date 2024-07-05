<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.js"></script>

<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat();?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('exam_list'); ?></h3>
                        <div class="impbtntitle">
                            <?php
if ($this->rbac->hasPrivilege('exam', 'can_add')) {
    ?>
                                <a tabindex="-1" class="btn btn-primary btn-sm" href="#" id="examModalButton"> <?php echo $this->lang->line('new_exam'); ?></a>
                                <?php
}
if ($this->rbac->hasPrivilege('link_exam', 'can_view')) {
    ?>
                                <a tabindex="-1" class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#examconnectModal" href="#" id="examconnectModalButton" data-examGroup_id="<?php echo $examgroup->id; ?>"> <?php echo $this->lang->line('link_exams'); ?></a>
                                <?php
}
?>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <input type="hidden" name="current_session" id="current_session" value="<?php echo $current_session; ?>">
                        <div class="row pb10">
                            <div class="col-lg-2 col-md-3 col-sm-12 col-xs-6">
                                <p class="examinfo"><span> <?php echo $this->lang->line('exam_group'); ?></span> <?php echo $examgroup->name; ?></p>
                            </div><!--./col-lg-4-->
                            <div class="col-lg-2 col-md-3 col-sm-12 col-xs-6">
                                <p class="examinfo"><span> <?php echo $this->lang->line('exam_type'); ?></span> <?php echo $examType[$examgroup->exam_type]; ?></p>
                            </div><!--./col-lg-4-->
                            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                                <p class="examinfo"><span> <?php echo $this->lang->line('description'); ?> </span> <?php echo $examgroup->description; ?></p>
                            </div><!--./col-lg-4-->
                        </div><!--./row-->
                        <div class="divider2"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="examgroup_id" name="examgroup_id" value="<?php echo $examgroup->id; ?>">
                            </div>
                        </div>
                        <div class="table-responsive mailbox-messages" id="exam_tbl" >
                            <div class="download_label"><?php echo $this->lang->line('exam_list'); ?></div>
                            <table class="table table-hover table-striped table-bordered loading1" id="exam_table">
                                <thead>
                                    <tr class="white-space-nowrap">
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('session') ?></th>
                                        <th><?php echo $this->lang->line('subjects_included'); ?></th>
                                        <th class="text text-center"><?php echo $this->lang->line('publish_exam'); ?></th>
                                        <th class="text text-center"><?php echo $this->lang->line('publish_result'); ?></th>
                                        <th class=""><?php echo $this->lang->line('description') ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<div class="modal fade" id="examModal">
    <div class="modal-dialog modal-lg">
        <form id="formadd" action="<?php echo site_url('admin/examgroup/ajaxaddexam'); ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('exam'); ?></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="exam_id" value="0">
                    <input type="hidden" name="exam_type" value="<?php echo $examgroup->exam_type; ?>">
                    <div class="row">
                        <div class="form-group col-xs-12 col-sm-9 col-md-9 col-lg-9">
                            <label for="exam"><?php echo $this->lang->line('exam') ?><small class="req"> *</small></label>
                            <input type="text" class="form-control" id="exam" name="exam">
                            <span class="text text-danger" id="exam_error"></span>
                        </div>
                        <div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
                            <label for="exam"><?php echo $this->lang->line('session') ?><small class="req"> *</small></label>
                            <select  id="session_id" name="session_id" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
foreach ($sessionlist as $session) {
    ?>
                                    <option value="<?php echo $session['id']; ?>" <?php echo set_select('session_id', 'session_id', (($current_session == $session['id']) ? true : false)); ?>><?php echo $session['session']; ?></option>
                                    <?php
}
?>
                            </select>
                            <span class="text text-danger" id="session_id_error"></span>
                        </div>
                        <?php
if ($examgroup->exam_type == "average_passing") {
    ?>
    <div class="clearfix"></div>
                        <div class="form-group col-xs-12 col-sm-9 col-md-9 col-lg-9">
                            <label for="passing_percentage"><?php echo $this->lang->line('exam_passing_percentage'); ?><small class="req"> *</small></label>
                            <input type="text" class="form-control" id="passing_percentage" name="passing_percentage" autocomplete="off">
                            <span class="text text-danger" id="passing_percentage_error"></span>
                        </div>
    <?php
}
?>
                        <div class="clearfix"></div>
                        <?php if ($this->rbac->hasPrivilege('exam_publish', 'can_view')){ ?>
                        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <div class="checkbox-inline">
                                <label>
                                    <input type="checkbox" value="1" name="is_active"> <?php echo $this->lang->line('publish'); ?>
                                </label>
                            </div>
                        </div>
                        <?php } ?>
                        
                             <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <div class="checkbox-inline">
                                <label>
                                    <input type="checkbox" value="1" name="is_publish" autocomplete="off"> <?php echo $this->lang->line('publish_result'); ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                            <label class="radio-inline"><input type="radio" value="1" name="use_exam_roll_no" checked="checked"><?php echo $this->lang->line('admit_card_roll_no') ?>  </label>
                            <label class="radio-inline"><input type="radio" value="0" name="use_exam_roll_no"><?php echo $this->lang->line('profile_roll_no') ?>  </label>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 mt10">
                            <label for="description"><?php echo $this->lang->line('description') ?></label>
                            <textarea class="form-control" name="description" id="description"></textarea>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('saving'); ?>"><?php echo $this->lang->line('save') ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="addSubject" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_exam_subject'); ?></h4>
            </div>
            <div class="modal-body subject-body">
            </div>
        </div>
    </div>
</div>

<div id="examconnectModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"> <?php echo $this->lang->line('link_exam'); ?></h4>
            </div>
            <div class="modal-body examconnectModalBody">

            </div>
        </div>
    </div>
</div>

<div class="delmodal modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirmation'); ?></h4>
            </div>
            <div class="modal-body">
                <p><?php echo $this->lang->line('are_you_sure_want_to_delete') ?> <b class="invoice_no"></b> <?php echo $this->lang->line('record_this_action_is_irreversible'); ?></p>
                <p><?php echo $this->lang->line('do_you_want_to_proceed') ?></p>
                <p class="debug-url"></p>
                <input type="hidden" name="del_itemid" class="del_itemid" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <a class="btn btn-danger btn-ok"><?php echo $this->lang->line('delete'); ?></a>
            </div>
        </div>
    </div>
</div>

<div id="subjectmarkModal" class="modal fade modalmark" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <?php echo $this->lang->line('exam_subject'); ?></h4>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<div id="teacherRemarkModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('teacher_remark'); ?></h4>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
<!-- Modal -->

<div id="subjectModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title subjectmodal_header"></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="searchStudentForm" action="<?php echo site_url('admin/examgroup/subjectstudent') ?>" method="post" class="mb10">
                    <input type="hidden" name="subject_id" value="0" class="subject_id">
                    <input type="hidden" name="teachersubject_id" value="0" class="teachersubject_id">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('class'); ?><small class="req"> *</small></label>
                                <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
foreach ($classlist as $class) {
    ?>
                                        <option value="<?php echo $class['id'] ?>" <?php
if (set_value('class_id') == $class['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $class['class'] ?></option>
                                                <?php
}
?>
                                </select>
                                <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                            </div><!--./form-group-->
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?><small class="req"> *</small></label>
                                    <select  id="section_id" name="section_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                </div>
                            </div><!--./form-group-->
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('session'); ?><small class="req"> *</small></label>
                                <select  id="session_id" name="session_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
foreach ($sessionlist as $session) {
    ?>
                                        <option value="<?php echo $session['id'] ?>" <?php echo set_select('session_id', 'session_id', (($current_session == $session['id']) ? true : false)); ?>><?php echo $session['session'] ?></option>
                                        <?php
}
?>
                                </select>
                            </div><!--./form-group-->
                        </div>
                        
                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="examheight100 relative">
                    <div id="examfade"></div>
                    <div id="exammodal">
                        <img id="loader" src="<?php echo base_url() ?>/backend/images/loading_blue.gif" />
                    </div>
                    <div class="marksEntryForm">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="allotStudentModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <?php echo $this->lang->line('exam_students'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="allotStudentForm" action="<?php echo site_url('admin/examgroup/examstudent') ?>" method="post" >
                    <input type="hidden" name="exam_id" value="0" class="exam_group_class_batch_exam_id">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                <select id="class_id" name="class_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
foreach ($classlist as $class) {
    ?>
                                        <option value="<?php echo $class['id'] ?>" <?php
if (set_value('class_id') == $class['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $class['class'] ?></option>
                                                <?php
}
?>
                                </select>
                                <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                <select  id="section_id" name="section_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                        </div>
                    </div>
                </form><br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="scroll-area-fullheight">
                            <div class="studentAllotForm">
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>

<div id="studentRankModal" class="modal fade modalmark" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" autocomplete="off">×</button>
                <h4 class="modal-title"><?php echo $this->lang->line('student_exam_rank'); ?> :  <b class="exam_title"></b></h4>
            </div>

            <div class="modal-body minheight260">  

              <div class="modal_loader_div" style="display: none;"></div>

                <div class="modal-body-inner">
                    
                </div>


            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
       all_records();
    });

</script>
<script type="text/javascript">
    $(document).ready(function () {

$('#studentRankModal').modal({
            backdrop: 'static',
            keyboard: false,
            show:false
        });

        $('.delmodal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        })
        $('#confirm-delete').on('show.bs.modal', function (e) {
            var data = $(e.relatedTarget).data();
            $('.del_itemid', this).val("").val(data.id);
            $('.invoice_no', this).html(data.exam);
        });

        $('#confirm-delete').on('click', '.btn-ok', function (e) {
            var $modalDiv = $(e.delegateTarget);
            var id = $('.del_itemid').val();

            $.ajax({
                type: "post",
                url: '<?php echo site_url("admin/examgroup/deleteExam") ?>',
                dataType: 'JSON',
                data: {'id': id},
                beforeSend: function () {
                    $modalDiv.addClass('modalloading');
                },
                success: function (data) {
                    if (data.status == 1) {
                        successMsg(data.message);
                        all_records();

                    } else {
                        errorMsg(data.message);
                    }
                },
                complete: function () {
                    $('.delmodal').modal('hide');
                    $modalDiv.removeClass('modalloading');

                }
            });
        });
    });
</script>

<script type="text/javascript">

    var batch_subjects = "";
    var x = 1;
    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy', 'M' => 'MM']) ?>';

    var date_format_time = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'DD', 'm' => 'MM', 'Y' => 'YYYY', 'M' => 'MMM']) ?>';
    $(document).ready(function () {
        $('#examconnectModal,#subjectmarkModal,#allotStudentModal,#teacherRemarkModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });

        $('.date').datepicker({
            format: date_format,
            autoclose: true
        });

        $('.datetime').datetimepicker();
        $('#examModalButton').click(function () {
            $('#examModal').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        $('#examModal').on('hidden.bs.modal', function () {
            reset_exm_form();
            $("span[id$='_error']").html("");
        });

        function reset_exm_form() {
            var current_session = $('#current_session').val();
            console.log(current_session);
            $('#formadd')[0].reset();
            $("#class_id").prop("selectedIndex", 0);
            $("#section_id,#batch_id").find('option:not(:first)').remove();
            $("#formadd input[name=exam_id]").val(0);
            $('#session_id').val(current_session);
        }

        $(document).on('click', '.editexamModalButton', function (e) {

            reset_exm_form();
            var exam_id = $(this).data('exam_id');
            $.ajax({
                type: "POST",
                url: base_url + "admin/examgroup/getExamByID",
                data: {'exam_id': exam_id},
                dataType: "json",
                beforeSend: function () {
                    $('#formadd')[0].reset();
                },
                success: function (data) {
                    $("#formadd select[name=session_id] [value=" + data.exam.session_id + "]").attr('selected', 'true');
                    $("#formadd input[name=exam]").val(data.exam.exam);
                    $("#formadd input[name=date_from]").val(data.exam.date_from);
                    $("#formadd input[name=exam_id]").val(data.exam.id);
                    $("#formadd input[name=date_to]").val(data.exam.date_to);
                    $("#formadd select[name=class_id] [value=" + data.exam.class_id + "]").attr('selected', 'true');
                    $("#formadd textarea[name=description]").val(data.exam.description);

                    if(data.exam.exam_group_type =="average_passing"){
                          $("#formadd input[name=passing_percentage]").val(data.exam.passing_percentage);
                    }
                    if (data.exam.is_active == 1) {

                        $("#formadd input[name=is_active]").prop('checked', true);
                    }

                   $("#formadd input[name=use_exam_roll_no][value='"+data.exam.use_exam_roll_no+"']").prop("checked",true);
                    if (data.exam.is_publish == 1) {
                        $("#formadd input[name=is_publish]").prop('checked', true);
                    }

                    $('#examModal').modal('show');
                },
                complete: function () {

                }
            });
        });

        $(document).on('click', '#subjectModalButton', function (e) {
            batch_subjects = "";
            x = 1;
            $('.subject-body').html('');
            var class_batch_id = $(this).data('class_batch_id');
            var exam_id = $(this).data('exam_id');
            var exam_group_id = $('#examgroup_id').val();

            $('#addSubject').modal({
                backdrop: 'static',
                keyboard: false
            });
            $.ajax({
                type: "POST",
                url: base_url + "admin/examgroup/getexamSubjects",
                data: {'exam_group_id': exam_group_id, 'class_batch_id': class_batch_id, 'exam_id': exam_id},
                dataType: "json",
                beforeSend: function () {
                    $('.subject-body').html();
                },
                success: function (data) {
                    var s = data.subject_page;
                    $('.subject-body').html("").html(s);
                    var tmp_row = $('#item_table');
                    $('.datepicker_init', tmp_row).datetimepicker({
                        format: date_format_time,
                        showTodayButton: true,
                        ignoreReadonly: true
                    });

                    $('.datepicker_init_time', tmp_row).datetimepicker({
                        format: 'HH:mm:ss',
                        showTodayButton: true,
                        ignoreReadonly: true
                    });

                    batch_subjects = data.batch_subject_dropdown;
                    if (data.exam_subjects_count > 0) {
                        x = data.exam_subjects_count + 1;
                    }

                },
                complete: function () {

                }
            });
        });

        function getSectionByClass(class_id, section_id) {
            if (class_id != 0 && class_id !== "") {
                $('#section_id').html("");
                var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                $.ajax({
                    type: "POST",
                    url: base_url + "admin/batchsubject/getSectionByClass",
                    data: {'class_id': class_id},
                    dataType: "json",
                    beforeSend: function () {
                        $('#section_id').addClass('dropdownloading');
                    },
                    success: function (data) {
                        $.each(data, function (i, obj)
                        {
                            var sel = "";
                            if (section_id == obj.class_section_id) {
                                sel = "selected";
                            }
                            div_data += "<option value=" + obj.class_section_id + " " + sel + ">" + obj.section + "</option>";
                        });
                        $('#section_id').append(div_data);
                    },
                    complete: function () {
                        $('#section_id').removeClass('dropdownloading');
                    }
                });
            }
        }

        function getBatchByClassSection(section_id, batch_id) {
            if (section_id != "") {
                $('#batch_id').html("");
                var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

                $.ajax({
                    type: "POST",
                    url: base_url + "admin/batchsubject/getBatchByClassSection",
                    data: {'class_section_id': section_id},
                    dataType: "JSON",
                    beforeSend: function () {
                        $('#batch_id').addClass('dropdownloading');
                    },
                    success: function (data) {
                        $.each(data, function (i, obj)
                        {
                            var sel = "";
                            if (batch_id == obj.batch_id) {
                                sel = "selected";
                            }
                            div_data += "<option value=" + obj.id + " " + sel + ">" + obj.batch_name + "</option>";
                        });
                        $('#batch_id').append(div_data);
                    },
                    complete: function () {
                        $('#batch_id').removeClass('dropdownloading');
                    }
                });
            }
        }
    });

    $('#examconnectModal').on('show.bs.modal', function (e) {
        $('.examconnectModalBody').html("");
        var examgroup_id = $(e.relatedTarget).data('examgroup_id');
        $.ajax({
            type: "POST",
            url: base_url + "admin/examgroup/connectexams",
            data: {'examgroup_id': examgroup_id}, // serializes the form's elements.
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function () {

            },
            success: function (data)
            {
                $('.examconnectModalBody').html(data.exam_page);
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            },
            complete: function () {

            }
        });
    });

    $("#formadd").submit(function (e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        $("span[id$='_error']").html("");
        var form = $(this);
        var url = form.attr('action');
        var submit_button = $(this).find(':submit');
        var post_params = $(this).serializeArray();
        post_params.push({name: 'exam_group_id', value: $('#examgroup_id').val()});

        $.ajax({
            type: "POST",
            url: url,
            data: post_params, // serializes the form's elements.
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function () {
                submit_button.button('loading');
            },
            success: function (data)
            {

                if (!data.status) {
                    $.each(data.error, function (index, value) {
                        var errorDiv = '#' + index + '_error';
                        $(errorDiv).empty().append(value);
                    });
                } else if (data.status) {

                    $('#section_id').find('option').not(':first').remove();
                    $('#batch_id').find('option').not(':first').remove();
                    $('#formadd')[0].reset();
                      successMsg(data.message);
                    $('#examModal').modal('hide');
                    all_records();
                }
            },
            error: function (xhr) { // if error occured
                submit_button.button('reset');
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            },
            complete: function () {
                submit_button.button('reset');
            }
        });
    });
</script>

<script type="text/javascript">
    $('.datepicker_init').datetimepicker({
        format: date_format_time,
        reReadonly: true
    });

    function all_records() {
        $.ajax({
            type: "POST",
            url: base_url + "admin/examgroup/getexam",
            data: {examgroup_id: $('#examgroup_id').val()}, // serializes the form's elements.
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function () {

            },
            success: function (data)
            {
                $('#exam_tbl').find('tbody').empty().append(data.exam_page);              

            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            },
            complete: function () {

            }
        });
    }
</script>
<script>
    $(document).on('submit', '.ssaddSubject', function (e) {

        e.preventDefault();
        var form = $(this);
        var subsubmit_button = $(this).find(':submit');
        var formdata = form.serializeArray();
        formdata.push({name: 'examgroup_id', value: $('#examgroup_id').val()});
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: formdata, // serializes the form's elements.
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function () {
                subsubmit_button.button('loading');
            },
            success: function (response)
            {
                if (response.status == 0) {
                    var message = "";
                    $.each(response.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);

                } else {
                    all_records();
                    successMsg(response.message);
                    $('#addSubject').modal('hide');
                }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                subsubmit_button.button('reset');
            },
            complete: function () {
                subsubmit_button.button('reset');
            }
        });
    });
</script>
<script>
    $(document).on('click', '.add', function () {

        var html = '';
        html += '<tr>';
        html += '<td width="150"><select name="subject_' + x + '" class="form-control item_unit tddm200">' + batch_subjects + '</select></td>';
        html += '<td><div class="input-group datepicker_init"><input type="text" name="date_from_' + x + '" class="form-control"/><span class="input-group-addon" id="basic-addon2"><i class="fa fa-calendar"></i></span></div></td>';
        html += '<td><div class="input-group datepicker_init_time"><input type="text" name="time_from' + x + '" class="form-control"/><span class="input-group-addon" id="basic-addon2"><i class="fa fa-clock-o"></i></span></div></td>';
        html += '<td><input type="text" name="duration' + x + '" class="form-control duration" value="0"/></td>';
        html += '<td><input type="number" name="credit_hours' + x + '" class="form-control credit_hours" value="0"/></td>';
        html += '<td class=""><input type="text" name="room_no_' + x + '" class="form-control room_no" /></td>';
        html += '<td class=""><input type="number" name="max_marks_' + x + '" class="form-control max_marks" /></td>';
        html += '<td class=""><input type="hidden" name="rows[]" value="' + x + '"> <input name="prev_row[' + x + ']" type="hidden" value="0"><input type="number" name="min_marks_' + x + '" class="form-control min_marks" /></td>';
        html += '<td class="text-center" style="vertical-align: middle; cursor: pointer;"><span class="text text-danger remove fa fa-times mt5"></span></td></tr>';
        var tmp_row = $('#item_table').append(html);

        $('.datepicker_init', tmp_row).datetimepicker({
            format: date_format_time,
            showTodayButton: true,
            ignoreReadonly: true
        });

        $('.datepicker_init_time', tmp_row).datetimepicker({
            format: 'HH:mm:ss',
            showTodayButton: true,
            ignoreReadonly: true
        });
        x++;
    });

    $(document).on('click', '.remove', function () {
        $(this).closest('tr').remove();
    });

    $('#insert_form').on('submit', function (event) {
        event.preventDefault();
        var error = '';
        $('.item_name').each(function () {
            var count = 1;
            if ($(this).val() == '')
            {
                error += "<p>Enter Item Name at " + count + " Row</p>";
                return false;
            }
            count = count + 1;
        });

        $('.item_quantity').each(function () {
            var count = 1;
            if ($(this).val() == '')
            {
                error += "<p>Enter Item Quantity at " + count + " Row</p>";
                return false;
            }
            count = count + 1;
        });

        $('.item_unit').each(function () {
            var count = 1;
            if ($(this).val() == '')
            {
                error += "<p>Select Unit at " + count + " Row</p>";
                return false;
            }
            count = count + 1;
        });

        var form_data = $(this).serialize();
        if (error == '')
        {
            $.ajax({
                url: "insert.php",
                method: "POST",
                data: form_data,
                success: function (data)
                {
                    if (data == 'ok')
                    {
                        $('#item_table').find("tr:gt(0)").remove();
                        $('#error').html('<div class="alert alert-success"><?php echo $this->lang->line('item_details_saved'); ?></div>');
                    }
                }
            });
        } else
        {
            $('#error').html('<div class="alert alert-danger">' + error + '</div>');
        }
    });
</script>
<script type="text/javascript">
    $(document).on('submit', 'form#connectExamForm', function (e) {

        e.preventDefault();
        var form = $(this);
         var sub_connect_exam = $("button[type=submit]:focus");
        var formdata = form.serializeArray();
            formdata.push({ name: "action", value: sub_connect_exam.attr('name')});
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: formdata, // serializes the form's elements.
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function () {
                sub_connect_exam.button('loading');
                $('.error_connection').html("");
            },
            success: function (response)
            {

                if (response.status == 0) {
                    $('.error_connection').html($('<div>', {class: 'alert alert-info', text: response.message}));
                } else {
                    successMsg(response.message);
                    $('#examconnectModal').modal('hide');
                }
                sub_connect_exam.button('reset');
            },
            error: function (xhr) { // if error occured

                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                sub_connect_exam.button('reset');

            },
            complete: function () {
                sub_connect_exam.button('reset');
            }
        });
    });
    $(document).on('click', '#ckbCheckAll', function (e) {
        $(".checkBoxExam").prop('checked', $(this).prop('checked'));
    });

  $(document).on('click', '.examTeacherReamark', function () {
        var $this = $(this);
        console.log("sdfsfs");
        var recordid = $this.data('recordid');
        $.ajax({
            type: 'POST',
            url: baseurl + "admin/examgroup/getTeacherRemarkByExam",
            data: {'recordid': recordid},
            dataType: 'JSON',
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (data) {
                $('#teacherRemarkModal .modal-body').html(data.subject_page);
                $('#teacherRemarkModal').modal('show');
                $this.button('reset');
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
    });

    $(document).on('click', '.examMarksSubject', function () {
        var $this = $(this);
        var recordid = $this.data('recordid');
        $('input[name=recordid]').val(recordid);
        $.ajax({
            type: 'POST',
            url: baseurl + "admin/examgroup/getSubjectByExam",
            data: {'recordid': recordid},
            dataType: 'JSON',
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (data) {
                $('#subjectmarkModal .modal-body').html(data.subject_page);
                $('#subjectmarkModal').modal('show');
                $this.button('reset');
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#subjectModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        })
    });

    $('#subjectModal').on('shown.bs.modal', function (e) {
        var subject_id = $(e.relatedTarget).data('subject_id');
        var subject_name = $(e.relatedTarget).data('subject_name');
         var teachersubject_id = $(e.relatedTarget).data('teachersubject_id');
        $('.subjectmodal_header').html("").html(subject_name);
        $('.marksEntryForm').html("");
        $('.subject_id').val("").val(subject_id);
        $('.teachersubject_id').val("").val(teachersubject_id);
        $(e.currentTarget).find('input[name="subject_name"]').val(subject_name);
        var current_session = $('#current_session').val();
        $('#session_id option[value="'+current_session+'"]').prop("selected", true);
    })

    $('#subjectModal').on('hidden.bs.modal', function () {
        $('.subjectmodal_header').html("");
        $('.marksEntryForm').html("");
        $('.subject_id').val("");
        $("#searchStudentForm").find('input:text,select,textarea').val('');
        $('#section_id').find('option').not(':first').remove();
        $('#session_id > option[selected="selected"]').removeAttr('selected');
    });

    $(document).on('change', '#class_id', function (e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        var selector = $(this).closest("div.modal-body").find('#section_id');
        getSectionByClass(class_id, section_id, selector);
    });

    function getSectionByClass(class_id, section_id, selector) {
        if (class_id != "") {
            selector.html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    selector.addClass('dropdownloading');
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
                    selector.append(div_data);
                },
                complete: function () {
                    selector.removeClass('dropdownloading');
                }
            });
        }
    }

    $("form#searchStudentForm").on('submit', (function (e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);
        var $this = form.find("button[type=submit]:focus");
        var url = form.attr('action');
        $.ajax({
            url: url,
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $this.button('loading');
                    $('#examfade,#exammodal').css({'display': 'block'});
            },
            success: function (res)
            {

    $('#examfade,#exammodal').css({'display': 'none'});
    if (res.status == "0") {
        $('.marksEntryForm').html('');
                            var message = "";
                            $.each(res.error, function (index, value) {

                                message += value;
                            });
                            errorMsg(message);
                        }else{
                          $('.marksEntryForm').html(res.page);
                $('.marksEntryForm').find('.dropify').dropify();

                        }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
                    $('#examfade,#exammodal').css({'display': 'none'});
            },
            complete: function () {
                $this.button('reset');
                    $('#examfade,#exammodal').css({'display': 'none'});
            }

        });
    }
    ));
</script>
<script type="text/javascript">
    $.validator.addMethod("uniqueUserName", function (value, element, options)
    {
        var max_mark = $('#max_mark').val();
        //we need the validation error to appear on the correct element
        return parseFloat(value) <= parseFloat(max_mark);
    },
            "Invalid Marks"
            );

    $(document).ready(function () {

        $(document).on('submit', 'form#assign_form1111', function (event) {
            event.preventDefault();
            $('form#assign_form1111').validate({
    debug: true,
    errorClass: 'error text text-danger',
    validClass: 'success',
    errorElement: 'span',
    highlight: function(element, errorClass, validClass) {
       $(element).parent().addClass(errorClass);
    },
    unhighlight: function(element, errorClass, validClass) {
      $(element).parent().removeClass(errorClass);
    }
});

            $('.marksssss').each(function () {
                $(this).rules("add",
                        {
                            required: true,
                            uniqueUserName: true,
                            messages: {
                                required: '<?php echo $this->lang->line("required"); ?>',//"Required",
                            }
                        });
            });

            // test if form is valid
            if ($('form#assign_form1111').validate().form()) {
                var $this = $('.allot-fees');
                $.ajax({
                    type: "POST",
                    dataType: 'Json',
                    url: $("#assign_form1111").attr('action'),
                    data: $("#assign_form1111").serialize(), // serializes the form's elements.
                    beforeSend: function () {
                        $this.button('loading');

                    },
                    success: function (data)
                    {
                        $this.button('reset');
                        if (data.status == "fail") {
                            var message = "";
                            $.each(data.error, function (index, value) {

                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                            $('#subjectModal').modal('hide');
                        }
                    },
                    complete: function () {
                        $this.button('reset');
                    }
                });
            } else {
                console.log("does not validate");
            }
        })

        // // initialize the validator

    });
</script>
<script type="text/javascript">
    $(document).on('click', '.assignStudent', function () {
        var examid = $(this).data('examid');
        $('.exam_group_class_batch_exam_id').val(examid);
        $('#allotStudentModal').modal('show');
    });

    $("form#allotStudentForm").on('submit', (function (e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);
        var $this = form.find("button[type=submit]:focus");
        var url = form.attr('action');
        $.ajax({
            url: url,
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

            if (res.status == 1) {
            $('.studentAllotForm').html(res.page);

            } else {
                var message = "";
                $.each(res.error, function (index, value) {

                message += value;
            });

            errorMsg(message);

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
    }
    ));

    $(document).on('submit', 'form#allot_exam_student', function (e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);
        var $this = form.find("button[type=submit]:focus");
        var url = form.attr('action');
        $.ajax({
            url: url,
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
                if (res.status == 1) {
                    successMsg(res.message);
                    $('#allotStudentModal').modal('hide');

                } else {
                    errorMsg(res.message);
                }

                $this.button('reset');
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }

        });
    }
    );

    $(document).on('submit', 'form#remark_form', function (e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);
        var $this = form.find("button[type=submit]:focus");
        var url = form.attr('action');
        $.ajax({
            url: url,
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
                if (res.status == 1) {
                    successMsg(res.message);
                    $('#teacherRemarkModal').modal('hide');

                } else {
                    errorMsg(res.message);
                }

                $this.button('reset');
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }

        });
    }
    );

    $('#allotStudentModal').on('hidden.bs.modal', function () {
        $('form#allotStudentForm').find('select#class_id').prop("selectedIndex", 0);
        $('form#allotStudentForm').find('select#section_id').find('option:not(:first)').remove();
        $('#allotStudentModal').find('div.studentAllotForm').html("");
        $("span[id$='_error']").html("");
    });

    $(document).on('click', '.select_all', function (e) {

        if (this.checked) {
            $(this).closest('div.table-responsive').find('[type=checkbox]').prop('checked', true);
        } else {
            $(this).closest('div.table-responsive').find('[type=checkbox]').prop('checked', false);
        }
    });

    $(document).ready(function () {
        $('body').tooltip({
            selector: "[data-toggle=tooltip]",
            container: "body"
        });
    });

    $(document).on('click', '.attendance_chk', function () {
        if ($(this).prop("checked") == true) {
            console.log("Checkbox is checked.");
            $(this).closest('tr').find('.marksssss').val("0");
            $(this).closest('tr').find('.marksssss').prop("disabled",true);

        } else if ($(this).prop("checked") == false) {
            $(this).closest('tr').find('.marksssss').val("");
            $(this).closest('tr').find('.marksssss').prop("disabled",false);
        }
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', "#btnSubmit", function (event) {

            //stop submit the form, we will post it manually.
            event.preventDefault();
            var file_data = $('#my-file-selector').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);

            $.ajax({
                url: baseurl + "/admin/examgroup/uploadfile",
                type: 'POST',
                dataType: 'JSON',
                data: form_data,
               contentType: false,
cache: false,
processData:false,
 beforeSend: function () {

                    $('#examfade,#exammodal').css({'display': 'block'});
            },
                success: function (data) {
$('#fileUploadForm')[0].reset();
  if (data.status == "0") {
           var message = "";
          $.each(data.error, function (index, value) {
            message += value;
         });
           errorMsg(message);
       } else {
           var arr = [];
                    $.each(data.student_marks, function (index) {
                        var s = JSON.parse(data.student_marks[index]);
                        arr.push({
                            adm_no: s.adm_no,
                            attendence: s.attendence,
                            marks: s.marks,
                            note: s.note
                        });

                    });
//===============
                    $.each(arr, function (index, value) {
                         let adm_no_csv =value.adm_no;
                         var row=$('.marksEntryForm').find('table tbody').find('tr[class="std_adm_'+adm_no_csv+'"]');
                       row.find("td input.marksssss").val(value.marks);
                       row.find("td input.note").val(value.note);
                       if(value.attendence == 0){
                         row.find("td input.attendance_chk").prop( "checked", true );
                     }else{
                         row.find("td input.attendance_chk").prop( "checked", false);
                     }
                    });
                     successMsg("<?php echo $this->lang->line('csv_file_uploaded_successfully') ?>");
                     $(".dropify-clear").trigger('click');
//=================
       }
                },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                    $('#examfade,#exammodal').css({'display': 'none'});
            },
            complete: function () {

                $('#fileUploadForm')[0].reset();
                $('#examfade,#exammodal').css({'display': 'none'});
            }

            });
        });
    });
</script>

<script type="text/javascript">
        // Fill modal with content from link href
    $("#studentRankModal").on("show.bs.modal", function(e) {
        var link = $(e.relatedTarget);
        let exam_name=link.data('examName');
        
         $('#studentRankModal .exam_title').html(exam_name); 
        let exam_id=link.data('exam_id');
        getRankData(exam_id);
    });

const getRankData = (exam_id)=>{


                    $.ajax({
                            url: baseurl+'admin/examresult/examrank',
                            type: "POST",
                            data: {"exam_id" : exam_id},
                            dataType: 'json',                   
                            beforeSend: function () {
                               
                              

                               $('#studentRankModal .modal-body .modal-body-inner').html(""); 
                               $('#studentRankModal .modal-body .modal_loader_div').css("display", "block"); 


                           
                            },
                            success: function (data)
                            {
                          $('#studentRankModal .modal-body .modal-body-inner').html(data.page); 
                          $('#studentRankModal .modal-body .modal_loader_div').fadeOut(400);


                                // $('#studentRankModal .modal-body').html(data.page)  ; 
                            },
                            error: function (xhr) { // if error occured
                            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                         
                            },
                            complete: function () {
                    
                            }
                    });
        }

    $(document).on('submit', '.updaterank', function (e) {
        e.preventDefault();
        let form = $(this);
        let frm_submit_button = $(this).find(':submit');
        let formdata = form.serializeArray();
       let exam_id=(frm_submit_button.data('examId'));
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: formdata, // serializes the form's elements.
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function () {
                frm_submit_button.button('loading');
            },
            success: function (response)
            {
                if (response.status == 0) {
                    var message = "";
                    $.each(response.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);

                } else {
                    
                    successMsg(response.message);
                    getRankData(exam_id);
                  
                }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                frm_submit_button.button('reset');
            },
            complete: function () {
                frm_submit_button.button('reset');
            }
        });
    });
</script>