<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('attendance'); ?> <small><?php echo $this->lang->line('by_date1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form id='form1' action="<?php echo site_url('admin/subjectattendence/reportbydate') ?>"  method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php
if ($this->session->flashdata('msg')) {
    echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg');
}
?>
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($classlist as $class) {
    ?>
                                                <option value="<?php echo $class['id'] ?>" <?php
if (set_value('class_id') == $class['id']) {
        echo "selected =selected";
    }
    ?>><?php echo $class['class'] ?></option>
                                                        <?php
$count++;
}
?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">
                                            <?php echo $this->lang->line('date'); ?>
                                        </label><small class="req"> *</small>
                                        <input  name="date" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('date'); ?>" readonly="readonly"/>
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <?php
if (isset($resultlist)) {
    ?>
                        <div class="">
                            <div class="box-header ptbnull"></div>
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('student_list'); ?></h3>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <div class="box-body">
                              <div class="table-responsive">
                                <?php
if (!empty($resultlist)) {

        $student_result = (json_decode($resultlist));
        ?>
                                    <table class="table table-hover table stripped">
                                        <thead>
                                            <tr>
                                                <th>Student</th>
                                                <?php
foreach ($student_result->subjects as $subject_key => $subject_value) {
            ?>
                                                    <th class="text text-center">
                                                        <?php
$sub_code = ($subject_value->code != "") ? " (" . $subject_value->code . ")" : "";
            echo $subject_value->name . $sub_code;
            echo "<br/>";
            echo $subject_value->time_from . " - " . $subject_value->time_to;
            ?>

                                                    </th>
                                                    <?php
}
        ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
foreach ($student_result->student_record as $students_key => $students_value) {
            ?>
                                                <tr>
                                                    <td><?php echo $this->customlib->getFullName($students_value->firstname, $students_value->middlename, $students_value->lastname, $sch_setting->middlename, $sch_setting->lastname) . " (" . $students_value->admission_no . ")"; ?></td>
                                                    <?php
for ($i = 1; $i <= count($student_result->subjects); $i++) {
                ?>
                                                        <td class="text text-center"><?php
if ($students_value->{"attendence_type_id_" . $i} == "") {
                    ?>
                                                                <span class="label label-danger">N/A</span>
                                                                <?php
} else {
                    echo getattendencetype($attendencetypeslist, $students_value->{"attendence_type_id_" . $i});
                }
                ?></td>
                                                        <?php
}
            ?>
                                                </tr>
                                                <?php
}
        ?>
                                        </tbody>
                                    </table>
                                    <?php
} else {
        ?>
                                    <div class="alert alert-info"><?php echo $this->lang->line('admited_alert') ?></div>
                                    <?php
}
    ?>
                            </div>
                         </div>
                        </div>
                    </div>
                    <?php
}
?>
                </section>
            </div>
            <?php

function getattendencetype($attendencetype, $find)
{
    foreach ($attendencetype as $attendencetype_key => $attendencetype_value) {
        if ($attendencetype_value['id'] == $find) {
            return $attendencetype_value['key_value'];
        }
    }
    return false;
}
?>

            <script type="text/javascript">
                $(document).ready(function () {
                    var section_id_post = "<?php echo set_value('section_id'); ?>";
                    var class_id_post = "<?php echo set_value('class_id'); ?>";
                    var date_post = "<?php echo set_value('date'); ?>";
                    var subject_timetable_id = "<?php echo set_value('subject_timetable_id', 0); ?>";
                    populateSection(section_id_post, class_id_post);
                    function populateSection(section_id_post, class_id_post) {
                        if (section_id_post != "" && class_id_post != "") {
                            $('#section_id').html("");
                            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                            $.ajax({
                                type: "GET",
                                url: baseurl + "sections/getByClass",
                                data: {'class_id': class_id_post},
                                dataType: "json",
                                success: function (data) {
                                    $.each(data, function (i, obj)
                                    {
                                        var select = "";
                                        if (section_id_post == obj.section_id) {
                                            var select = "selected=selected";
                                        }
                                        div_data += "<option value=" + obj.section_id + " " + select + ">" + obj.section + "</option>";
                                    });
                                    $('#section_id').html(div_data);
                                }
                            });
                        }
                    }

                    $(document).on('change', '#class_id', function (e) {
                        $('#section_id').html("");
                        var class_id = $(this).val();
                        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                        var url = "";
                        $.ajax({
                            type: "GET",
                            url: baseurl + "sections/getByClass",
                            data: {'class_id': class_id},
                            dataType: "json",
                            success: function (data) {
                                $.each(data, function (i, obj)
                                {
                                    div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                                });
                                $('#section_id').html(div_data);
                            }
                        });
                    });
                });
            </script>