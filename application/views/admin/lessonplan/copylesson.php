<style type="text/css">
    .table .pull-right {
        text-align: initial;
        width: auto;
        margin-bottom: 3px
    }
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
                <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_old_session_details'); ?></h3>
            </div>
            <form class="assign_teacher_form" action="<?php echo site_url('admin/lessonplan/copylesson'); ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if ($this->session->flashdata('msg')) {
                            ?>
                                <?php echo $this->session->flashdata('msg');
                                $this->session->unset_userdata('msg');
                                ?>
                            <?php
                            }
                            ?>
                            <?php echo $this->customlib->getCSRF(); ?>
                        </div>
                        <div class="col-md-2 col-lg-2 col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('session'); ?></label><small class="req"> *</small>
                                <select id="old_session_id" name="old_session_id" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    foreach ($sessionlist as $session) {
                                    ?>
                                        <option value="<?php echo $session['id'] ?>" <?php echo set_select('old_session_id', $session['id']); ?>>
                                            <?php echo $session['session'] ?>

                                        </option>
                                    <?php
                                    }
                                    ?>

                                </select>
                                <span class="old_session_id_error text-danger"><?php echo form_error('old_session_id'); ?></span>
                            </div>
                        </div>

                        <div class="col-md-2 col-lg-2 col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                <select id="old_class_id" name="old_class_id" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    foreach ($classlist as $class) {
                                    ?>
                                        <option <?php echo set_select('old_class_id', $class['id']); ?> value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <span class="old_class_id_error text-danger"><?php echo form_error('old_class_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-2 col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                <select id="old_section_id" name="old_section_id" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="old_section_id_error text-danger"><?php echo form_error('old_section_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('subject_group'); ?></label><small class="req"> *</small>
                                <select id="old_subject_group_id" name="old_subject_group_id" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="class_id_error text-danger"><?php echo form_error('old_subject_group_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('subject'); ?></label><small class="req"> *</small>
                                <select id="old_subject_id" name="old_subject_id" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="class_id_error text-danger"><?php echo form_error('old_subject_id'); ?></span>
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
                    <form method="POST" action="<?php echo site_url('admin/lessonplan/saveCopyLesson'); ?>" id="save_lesson">
                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="mb10"> <?php echo $this->lang->line('lesson_topics'); ?> <?php echo $this->lang->line('select_subject'); ?></h4>
                                <div class="pupscroll300">
                                    <div class="table-responsive mailbox-messages overflow-visible">
                                        <table class="table table-bordered topictable ptt10" id="headerTable">
                                            <tr>
                                                <th width="20%">#</th>
                                                <th width="80%"><?php echo $this->lang->line('lesson_topic'); ?></th>
                                            </tr>
                                            <?php
                                            $losson_count = 1;
                                            foreach ($lessons as $key => $value) {                                              
                                            ?>
                                                <tr>
                                                    <td><?php echo $losson_count; ?></td>
                                                    <td>
                                                        <h4><?php echo $value['name']; ?><small class="req"> *</small></h4>
                                                        <ul class="stausbtns">
                                                            <?php
                                                            if (isset($value['topic'])) {
                                                                $topic_count = 1;
                                                                foreach ($value['topic'] as $topic_key => $topic_value) {
                                                            ?>
                                                                    <li>
                                                                        <label class="checkbox-inline">
                                                                        </label>
                                                                        <span><?php  echo $losson_count . "." . $topic_count; ?></span>
                                                                        <label class="checkbox-inline">
                                                                            <input type="checkbox" value="<?php echo $topic_value['id']; ?>" name="topic_id[<?php echo $value['id']?>][]"> <?php echo $topic_value['name']; ?>
                                                                        </label>
                                                                    </li>

                                                            <?php
                                                                    $topic_count++;
                                                                }
                                                            }
                                                            ?>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            <?php
                                                $losson_count++;
                                            }
                                            ?>
                                        </table>
                                    </div><!--./table-responsive-->
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h4 class="mb10"><?php echo $this->lang->line('select_subject'); ?></h4>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                    <select id="class_id" name="class_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($classlist as $class) {
                                        ?>
                                            <option value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <span class="class_id_error text-danger"><?php echo form_error('class_id'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                    <select id="section_id" name="section_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="section_id_error text-danger"><?php echo form_error('section_id'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('subject_group'); ?></label><small class="req"> *</small>
                                    <select id="subject_group_id" name="subject_group_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="class_id_error text-danger"><?php echo form_error('subject_group_id'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('subject'); ?></label><small class="req"> *</small>
                                    <select id="subject_id" name="subject_group_subject_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="class_id_error text-danger"><?php echo form_error('subject_id'); ?></span>
                                </div>
                                
                                <button type="submit" class="btn btn-primary pull-right" data-loading-text="<?php echo $this->lang->line('submitting') ?>" value=""><?php echo $this->lang->line('save'); ?></button>
                    </form>
                </div>
        </div>
</div>
<?php
            } else {
                if ($no_record != 0) {
?>

    <div class="box-header">
        <div class="alert alert-danger">
            <center><?php echo $this->lang->line('no_record_found'); ?></center>
        </div>
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
                <button type="button" class="close" onclick="close_modal()">&times;</button>
                <h4 class="modal-title" id="modal-title"><?php echo $this->lang->line('topic_completion_date'); ?> <small style="color:red;"> *</small></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form role="form" id="addevent_form11" method="post" enctype="multipart/form-data" action="">
                        <div class="form-group col-md-12">
                            <input type="hidden" id="topic_id" name="id">
                            <input class="form-control date" id="date" name="date" type="text">
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

<script>
    $(document).ready(function(e) {

        var session_id = $('#old_session_id').val();
        var class_id = $('#old_class_id').val();
        var section_id = '<?php echo set_value('old_section_id', 0) ?>';
        var subject_group_id = '<?php echo set_value('old_subject_group_id', 0) ?>';
        var subject_id = '<?php echo set_value('old_subject_id', 0) ?>';

        getSectionByClass(class_id, section_id, 'old_section_id');
        getSubjectGroup(class_id, section_id, subject_group_id, 'old_subject_group_id', session_id);
        getsubjectBySubjectGroup(class_id, section_id, subject_group_id, subject_id, 'old_subject_id', session_id);
    });

    $(document).on('change', '#class_id', function(e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0, 'section_id');
    });

    $(document).on('change', '#section_id', function() {
        let class_id = $('#class_id').val();
        let section_id = $(this).val();
        getSubjectGroup(class_id, section_id, 0, 'subject_group_id');
    });

    $(document).on('change', '#subject_group_id', function() {
        let class_id = $('#class_id').val();
        let section_id = $('#section_id').val();
        let subject_group_id = $(this).val();
        getsubjectBySubjectGroup(class_id, section_id, subject_group_id, 0, 'subject_id');
    });

    $(document).on('change', '#old_class_id', function(e) {
        $('#old_section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0, 'old_section_id');
    });

    function getSectionByClass(class_id, section_id, select_control) {
        if (class_id != "") {
            $('#' + select_control).html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                beforeSend: function() {
                    $('#' + select_control).addClass('dropdownloading');
                },
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#' + select_control).append(div_data);
                },
                complete: function() {
                    $('#' + select_control).removeClass('dropdownloading');
                }
            });
        }
    }

    $(document).on('change', '#old_section_id', function() {
        let session_id = $('#old_session_id').val();
        let class_id = $('#old_class_id').val();
        let section_id = $(this).val();
        getSubjectGroup(class_id, section_id, 0, 'old_subject_group_id', session_id);
    });

    function getSubjectGroup(class_id, section_id, subjectgroup_id, subject_group_target, session_id = null) {
        console.log(class_id);
        console.log(section_id);
        if (class_id != "" && section_id != "") {
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: 'POST',
                url: base_url + 'admin/subjectgroup/getGroupByClassandSection',
                data: {
                    'class_id': class_id,
                    'section_id': section_id,
                    'session_id': session_id
                },
                dataType: 'JSON',
                beforeSend: function() {
                    // setting a timeout
                    $('#' + subject_group_target).html("").addClass('dropdownloading');
                },
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (subjectgroup_id == obj.subject_group_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.subject_group_id + " " + sel + ">" + obj.name + "</option>";
                    });
                    $('#' + subject_group_target).append(div_data);
                },
                error: function(xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

                },
                complete: function() {
                    $('#' + subject_group_target).removeClass('dropdownloading');
                }
            });
        }
    }

    $(document).on('change', '#old_subject_group_id', function() {
        let session_id = $('#old_session_id').val();
        let class_id = $('#old_class_id').val();
        let section_id = $('#old_section_id').val();
        let subject_group_id = $(this).val();
        getsubjectBySubjectGroup(class_id, section_id, subject_group_id, 0, 'old_subject_id', session_id);
    });

    function getsubjectBySubjectGroup(class_id, section_id, subject_group_id, subject_group_subject_id, subject_target, session_id = null) {
        if (class_id != "" && section_id != "" && subject_group_id != "") {
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: 'POST',
                url: base_url + 'admin/subjectgroup/getGroupsubjects',
                data: {
                    'subject_group_id': subject_group_id,
                    "session_id": session_id
                },
                dataType: 'JSON',
                beforeSend: function() {
                    // setting a timeout
                    $('#' + subject_target).html("").addClass('dropdownloading');
                },
                success: function(data) {
                    console.log(data);
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (subject_group_subject_id == obj.id) {
                            sel = "selected";
                        }

                        code = '';
                        if (obj.code) {
                            code = " (" + obj.code + ") ";
                        }

                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.name + code + "</option>";
                    });
                    $('#' + subject_target).append(div_data);
                },
                error: function(xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

                },
                complete: function() {
                    $('#' + subject_target).removeClass('dropdownloading');
                }
            });
        }
    }
</script>

<script type="text/javascript">
    $("#save_lesson").on('submit', (function(e) {
        e.preventDefault();
        var form = $(this);
        var $this = form.find("button[type=submit]:focus");
        $this.button('loading');
        $.ajax({
            url: form.attr('action'),
            type: "POST",
            data: form.serialize(),
            dataType: 'json',
            beforeSend: function() {
                $this.button('loading');

            },
            success: function(res) {

                if (res.status == 0) {

                    var message = "";
                    $.each(res.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);

                } else {

                    successMsg(res.message);
                    window.location.href = res.redirect_url;

                }
            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function() {
                $this.button('reset');
            }

        });
    }));
</script>