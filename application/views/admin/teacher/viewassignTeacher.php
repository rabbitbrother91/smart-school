<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> <?php echo $this->lang->line('academics'); ?> <small><?php echo $this->lang->line('student_fees1'); ?></small>        </h1>
    </section>
    <!-- Main content -->
    <section class="content">       
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                <div class="box-tools pull-right">
                    <?php if ($this->rbac->hasPrivilege('assign_class_teacher', 'can_add')) { ?>
                        <a href="<?php echo base_url(); ?>admin/teacher/assignteacher" class="btn btn-primary btn-sm"  data-toggle="tooltip" title="<?php echo $this->lang->line('assign_subjects'); ?>" >
                            <i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
            <form  class="assign_teacher_form" action="<?php echo base_url(); ?>admin/teacher/getSubjectTeachers" method="post" enctype="multipart/form-data">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
                            <?php } ?>
                            <?php echo $this->customlib->getCSRF(); ?>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    foreach ($classlist as $class) {
                                        ?>
                                        <option value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                        <?php
                                        $count++;
                                    }
                                    ?>
                                </select>
                                <span class="class_id_error text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                <select  id="section_id" name="section_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="section_id_error text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" id="search_filter" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>                 
                </div>
            </form>
        </div>
        <div class="col-md-12" id="errorinfo">

        </div>
        <div class="box box-info" id="box_display" style="display:none">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-users"> </i> <?php echo $this->lang->line('assign_subject'); ?></h3>

                <div class="box-tools pull-right">
                    <button id="btnAdd"  class="btn btn-primary btn-sm checkbox-toggle pull-right" type="button"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?></button>
                </div>
            </div>
            <form action="<?php echo base_url() ?>admin/teacher/assignteacher" method="POST" id="formSubjectTeacher">
                <?php echo $this->customlib->getCSRF(); ?>
                <br/>
                <input type="hidden" value="0" id="post_class_id" name="class_id">
                <input type="hidden" value="0" id="post_section_id" name="section_id">
                <div class="form-horizontal" id="TextBoxContainer" role="form">
                </div>
                <div class="box-footer">

                    <button type="submit" class="btn btn-primary btn-sm btn pull-right save_button" style="display: none;"><?php echo $this->lang->line('save'); ?></button>

                </div>
            </form>
        </div>       
    </section>
</div>

<script type="text/javascript">
    $(function () {
        $(document).on("click", "#btnAdd", function () {
            var lenght_div = $('#TextBoxContainer .app').length;
            var div = GetDynamicTextBox(lenght_div);
            $("#TextBoxContainer").append(div);
        });
        $(document).on("click", "#btnGet", function () {
            var values = "";
            $("input[name=DynamicTextBox]").each(function () {
                values += $(this).val() + "\n";
            });
        });
        $("body").on("click", ".remove", function () {
            $(this).closest("div").remove();
        });
    });
    function GetDynamicTextBox(value) {
        var row = "";
        row += '<div class="form-group app">';
        row += '<input type="hidden" name="i[]" value="' + value + '"/>';
        row += '<input type="hidden" name="row_id_' + value + '" value="0"/>';
        row += '<div class="col-md-12">';
        row += '<div class="form-group row">';
        row += '<label for="inputValue" class="col-md-1 control-label">Subject</label>';
        row += '<div class="col-md-4">';
        row += '<select disabled id="subject_id_' + value + '" name="subject_id_' + value + '" class="form-control" >';
        row += '<option value=""><?php echo $this->lang->line('select'); ?></option>';
<?php
foreach ($subjectlist as $subject) {
    ?>
            row += '<option value="<?php echo $subject['id'] ?>"><?php echo $subject['name'] . " (" . $subject['type'] . ")" ?></option>';
    <?php
    $count++;
}
?>
        row += '</select>';
        row += '</div>';
        row += '<label for="inputKey" class="col-md-1 control-label">Teacher</label>';
        row += '<div class="col-md-4">';
        row += '<select  id="teacher_id_' + value + '" name="teacher_id_' + value + '" no="' + value + '" class="form-control" >';
        row += '<option value=""><?php echo $this->lang->line('select'); ?></option>';
<?php
foreach ($teacherlist as $teacher) {
    ?>
            row += '<option value="<?php echo $teacher['id'] ?>"><?php echo str_replace("'", "\\'", $teacher['name']) ?></option>';
    <?php
    $count++;
}
?>
        row += '</select>';
        row += '</div>';
        row += '<div class="col-md-2"><button id="btnRemove" style="" class="btn btn-sm btn-danger" type="button"><i class="fa fa-trash"></i></button></div>';
        row += '</div>';
        row += '</div>';
        row += '</div>';
        return row;
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#btnAdd').hide();
        $(".assign_teacher_form").submit(function (e)
        {
            $("#TextBoxContainer").html("");
            $("input[class$='_error']").html("");
            var class_id = $('#class_id').val();
            var section_id = $('#section_id').val();
            var postData = $(this).serializeArray();
            var formURL = $(this).attr("action");
            $.ajax(
                    {
                        url: formURL,
                        type: "POST",
                        data: postData,
                        dataType: 'json',
                        success: function (data, textStatus, jqXHR)
                        {
                            if (data.st === 1) {
                                $.each(data.msg, function (key, value) {
                                    $('.' + key + "_error").html(value);
                                });
                            } else {
                                var response = data.msg;
                                if (response && response.length > 0) {
                                    for (i = 0; i < response.length; ++i) {
                                        var subject_id = response[i].subject_id;
                                        var teacher_id = response[i].teacher_id;
                                        var row_id = response[i].id;
                                        appendRow(subject_id, teacher_id, row_id);
                                    }
                                } else {
                                    $('#box_display').html(" <div class='box-header with-border'><div class='alert alert-info'>No Subject assigned.</div></div>");                                    
                                }
                                $('#post_class_id').val(class_id);
                                $('#post_section_id').val(section_id);
                                $('#box_display').show();
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                        }
                    });

            e.preventDefault();

        });

        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            resetForm();
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });
    });

    function appendRow(subject_id, teacher_id, row_id) {
        var value = $('#TextBoxContainer .app').length;
        var row = "";
        row += '<div class="form-group app">';
        row += '<input type="hidden" name="i[]" value="' + value + '"/>';
        row += '<input type="hidden" name="row_id_' + value + '" value="' + row_id + '"/>';
        row += '<div class="col-md-12">';
        row += '<div class="form-group row">';
        row += '<label for="inputValue" class="col-md-1 control-label">Subject</label>';
        row += '<div class="col-md-4">';
        row += '<select  disabled id="subject_id_' + value + '" name="subject_id_' + value + '" class="form-control" >';
        row += '<option value=""><?php echo $this->lang->line('select'); ?></option>';
<?php
foreach ($subjectlist as $subject) {
    ?>
            var selected = "";
            if (subject_id === '<?php echo $subject['id'] ?>') {
                selected = "selected";
            }
            row += '<option value="<?php echo $subject['id'] ?>" ' + selected + '><?php echo $subject['name'] . " (" . $subject['type'] . ")" ?></option>';

    <?php
    $count++;
}
?>
        row += '</select>';
        row += '</div>';
        row += '<label for="inputKey" class="col-md-1 control-label">Teacher</label>';
        row += '<div class="col-md-4">';
        row += '<select disabled id="teacher_id_' + value + '" name="teacher_id_' + value + '" no="' + value + '" class="form-control" >';
        row += '<option value=""><?php echo $this->lang->line('select'); ?></option>';
<?php
foreach ($teacherlist as $teacher) {
    ?>
            var selected = "";
            if (teacher_id === '<?php echo $teacher['id'] ?>') {
                selected = "selected";
            }

            row += '<option value="<?php echo $teacher['id'] ?>" ' + selected + '><?php echo str_replace("'", "\\'", $teacher['name'] . " " . $teacher['surname'] . "(" . $teacher["employee_id"] . ")") ?></option>';

    <?php
    $count++;
}
?>
        row += '</select>';
        row += '</div>';
        row += '</div>';
        row += '</div>';
        row += '</div>';
        $("#TextBoxContainer").append(row);
    }

    $(document).on('change', '#section_id', function (e) {
        resetForm();
    });

    function resetForm() {
        $('#TextBoxContainer').html("");
        $('#btnAdd').hide();
        $('.save_button').hide();
    }

    $(document).on('click', '#btnRemove', function () {
        $(this).parents('.form-group').remove();
    });
</script> 