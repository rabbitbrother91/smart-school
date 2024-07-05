<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-map-o"></i> <?php echo $this->lang->line('examinations'); ?> <small><?php echo $this->lang->line('student_fee1'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form role="form" action="<?php echo site_url('admin/examresult') ?>" method="post" >
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-sm-6 col-lg-3 col-md-3 col20">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('exam_group'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="exam_group_id" name="exam_group_id" class="form-control select2" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($examgrouplist as $ex_group_key => $ex_group_value) {
    ?>
                                                <option value="<?php echo $ex_group_value->id ?>" <?php
if (set_value('exam_group_id') == $ex_group_value->id) {
        echo "selected=selected";
    }
    ?>><?php echo $ex_group_value->name; ?></option>
                                                        <?php
}
?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('exam_group_id'); ?></span>
                                    </div>
                                </div>
                                <!--./col-md-3-->
                                <div class="col-sm-6 col-lg-3 col-md-3 col20">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('exam') ?></label><small class="req"> *</small>
                                        <select  id="exam_id" name="exam_id" class="form-control select2" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('exam_id'); ?></span>
                                    </div>
                                </div>
                                <!--./col-md-3-->
                                <div class="col-sm-6 col-lg-3 col-md-3 col20">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('session'); ?></label><small class="req"> *</small>
                                        <select  id="session_id" name="session_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($sessionlist as $session) {
    ?>
                                                <option value="<?php echo $session['id'] ?>" <?php
if (set_value('session_id') == $session['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $session['session'] ?></option>
                                                        <?php
}
?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('session_id'); ?></span>
                                    </div>
                                </div>
                                <!--./col-md-3-->
                                <div class="col-sm-6 col-lg-3 col-md-12 col20">
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
                                <div class="col-sm-6 col-lg-3 col-md-12 col20">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php
if (isset($studentList)) {
    ?>
                        <form method="post" action="<?php echo base_url('admin/examresult/printmarksheet') ?>" id="printMarksheet">
                            <input type="hidden" name="marksheet_template" value="<?php echo $marksheet_template; ?>">
                            <div class="" >
                                <div class="box-header ptbnull"></div>
                                <div class="box-header ptbnull">
                                    <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('exam_result'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <input type="hidden" name="post_exam_id" value="<?php echo $exam_id; ?>">
                                    <input type="hidden" name="post_exam_group_id" value="<?php echo $exam_group_id; ?>">
                                    <div class="tab-pane active table-responsive no-padding" id="tab_1">
                                        <div class="download_label"> <?php echo $this->lang->line('exam_result'); ?></div>
                                        <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                    <th><?php echo $this->lang->line('roll_number'); ?></th>
                                                    <th><?php echo $this->lang->line('student_name'); ?></th>
                                                    <?php
if (!empty($subjectList)) {
        foreach ($subjectList as $subject_key => $subject_value) {
            ?>
                                                            <th>
                                                                <?php
echo $subject_value->subject_name;
            echo "<br/>";
            if ($exam_details->exam_group_type == "average_passing") {
                echo "(" . $subject_value->max_marks . " - " . $subject_value->subject_code . ")";
            } else {
                echo "(" . $subject_value->min_marks . "/" . $subject_value->max_marks . " - " . $subject_value->subject_code . ")";
            }

            if ($exam_details->exam_group_type == "gpa") {
                ?>
                                                                    <br/>
                                                                    (<?php echo $this->lang->line('grade_point'); ?>) * (<?php echo $this->lang->line('credit_hours'); ?>)
                                                                    <?php
}
            ?>
                                                            </th>
                                                            <?php
}

        if ($exam_details->exam_group_type == "school_grade_system" || $exam_details->exam_group_type == "basic_system" || $exam_details->exam_group_type == "average_passing" || $exam_details->exam_group_type == "coll_grade_system") {
            ?>
                                                            <th><?php echo $this->lang->line('grand_total'); ?></th>
                                                            <th><?php echo $this->lang->line('percent') ?> (%)</th>
                                                            <th><?php echo $this->lang->line('rank') ?></th>
                                                            <?php
if ($exam_details->exam_group_type != "gpa") {
                ?>
                                                                <th><?php echo $this->lang->line('result') ?></th>
                                                                <?php
}
            ?>
                                                            <?php
} elseif ($exam_details->exam_group_type == "gpa") {
            ?>
                                                            <th><?php echo $this->lang->line('rank') ?></th>
                                                            <th><?php echo $this->lang->line('result') ?></th>
                                                            <?php
}
    }
    ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
if (empty($studentList)) {
        ?>
                                                    <?php
} else {
        $count = 1;  
        foreach ($studentList as $student_key => $student_value) {           

            $result_status     = 1;
            $no_subject_result = 0;
            ?>
                                                        <tr>
                                                            <td><?php echo $student_value->admission_no; ?></td>
                                                            <td><?php echo ($student_value->roll_no != 0) ? $student_value->roll_no : "-"; ?> </td>
                                                            <td>
                                                                <a href="<?php echo base_url(); ?>student/view/<?php echo $student_value->student_id; ?>"><?php echo $this->customlib->getFullName($student_value->firstname, $student_value->middlename, $student_value->lastname, $sch_setting->middlename, $sch_setting->lastname); ?>
                                                                </a>
                                                            </td>
                                                            <?php
if (!empty($subjectList)) {
                $total_marks         = 0;
                $get_marks           = 0;
                $total_percentage    = 0;
                $total_credit_hour   = 0;
                $total_quality_point = 0;
                foreach ($subjectList as $subject_key => $subject_value) {

                    $subject_status = 1;
                    $total_marks    = $total_marks + $subject_value->max_marks;
                    ?>
                                                                    <td>
                                                                        <?php
$result = getSubjectMarks($student_value->subject_results, $subject_value->subject_id);
                    if ($result) {
                        $no_subject_result = 1;
                        if ($exam_details->exam_group_type == "gpa") {
                            $get_marks           = $get_marks + $result->get_marks;
                            $subject_credit_hour = $subject_value->credit_hours;
                            $total_credit_hour   = $total_credit_hour + $subject_value->credit_hours;

                            $percentage_grade = ($result->get_marks * 100) / $result->max_marks;
                            $point            = findGradePoints($exam_grades, $percentage_grade);                        

                            $total_quality_point = $total_quality_point + ($point * $subject_credit_hour);
                            echo $point . " X " . $subject_credit_hour . " = " . number_format($point * $subject_credit_hour, 2, '.', '');
                            if ($result->attendence == "absent") {
                                ?>
                                                                                    <p class="text">
                                                                                        <?php echo $this->lang->line($result->attendence); ?>
                                                                                    </p>
                                                                                    <?php
}
                            ?>
                                                                                <p class="text"><?php echo $result->note; ?></p>
                                                                                <?php
} else {

                            $get_marks = $get_marks + $result->get_marks;
                            if ($result->get_marks < $subject_value->min_marks) {
                                $result_status  = 0;
                                $subject_status = 0;
                            }

                            echo $result->get_marks;
                            if ($exam_details->exam_group_type == "school_grade_system" || $exam_details->exam_group_type == "coll_grade_system") {
                                $percentage_grade = ($result->get_marks * 100) / $subject_value->max_marks;
                                echo " (" . get_ExamGrade($exam_grades, $percentage_grade) . ")";
                            }
                            if ($exam_details->exam_group_type == "basic_system") {
                                echo ($subject_status == 0) ? " (F)" : "";
                            }

                            if ($result->attendence == "absent") {
                                ?>
                                                                                    <p class="text">
                                                                                        <?php echo $this->lang->line($result->attendence); ?>
                                                                                    </p>
                                                                                    <?php
}
                            ?>
                                                                                <p class="text"><?php echo $result->note; ?></p>
                                                                                <?php
}
                    } else {

                    }
                    ?>
                                                                    </td>
                                                                    <?php
}

                if ($exam_details->exam_group_type == "school_grade_system" || $exam_details->exam_group_type == "basic_system" || $exam_details->exam_group_type == "average_passing" || $exam_details->exam_group_type == "coll_grade_system") {
                    ?>
                                                                    <td><?php echo number_format($get_marks, 2, '.', '') . "/" . number_format($total_marks, 2, '.', ''); ?></td>
                                                                    <td>
                                                                        <?php
$total_percentage = ($get_marks * 100) / $total_marks;
                    echo number_format($total_percentage, 2, '.', '');

                         echo " (" . get_ExamGrade($exam_grades, $total_percentage) . ")";
                    ?>
                                                                    </td>
                                                                    <?php
}
                ?>
                                                                <?php
}
            ?>

<td><?php echo $student_value->rank; ?></td>

                                                            <?php
if ($exam_details->exam_group_type == "gpa") {
                ?>
                                                                <td>
                                                                    <?php
if ($total_credit_hour > 0) {

                    $percentage_grade = ($get_marks * 100) / $total_marks;
                    $exam_qulity_point = number_format($total_quality_point / $total_credit_hour, 2, '.', '');
                    echo $total_quality_point . "/" . $total_credit_hour . "=" . $exam_qulity_point . " [" . get_ExamGrade($exam_grades, $percentage_grade) . "]";
                } else {
                    echo "--";
                }
                ?>
                                                                </td>
                                                                <?php
} elseif ($exam_details->exam_group_type == "school_grade_system" || $exam_details->exam_group_type == "basic_system" || $exam_details->exam_group_type == "average_passing" || $exam_details->exam_group_type == "coll_grade_system") {
                echo "<td>";
                if ($no_subject_result) {
                    if ($exam_details->exam_group_type == "average_passing") {
                        $result_status = ($exam_details->passing_percentage > $total_percentage) ? 0 : 1;
                    }


 if ($exam_details->exam_group_type == "basic_system"  ||  $exam_details->exam_group_type == "average_passing") {
// echo $exam_details->exam_group_type;
                    if ($result_status) {
                        ?>
                                                                <label class="label label-success" ><?php echo $this->lang->line('pass'); ?></label>
                                                                <?php
} else {
                        ?>
                                                                <label class="label label-danger"><?php echo $this->lang->line('fail'); ?></label>
                                                                <?php
}
 }
                }
                echo "</td>";
            }
            ?>
                                                    </tr>
                                                    <?php
$count++;
        }
    }
    ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php
}
?>
            </div>
        </div>
    </section>
</div>
<?php

function getSubjectMarks($subject_results, $subject_id)
{
    if (!empty($subject_results)) {
        foreach ($subject_results as $subject_result_key => $subject_result_value) {
            if ($subject_id == $subject_result_value->subject_id) {
                return $subject_result_value;
            }
        }
    }
    return false;
}

function get_ExamGrade($exam_grades, $percentage)
{
    if (!empty($exam_grades)) {
        foreach ($exam_grades as $exam_grade_key => $exam_grade_value) {

            if ($exam_grade_value->mark_from >= $percentage && $exam_grade_value->mark_upto <= $percentage) {
                return $exam_grade_value->name;
            }
        }
    }

    return "-";
}

function findGradePoints($exam_grades, $percentage)
{
    if (!empty($exam_grades)) {
        foreach ($exam_grades as $exam_grade_key => $exam_grade_value) {

            if ($exam_grade_value->mark_from >= $percentage && $exam_grade_value->mark_upto <= $percentage) {
                return $exam_grade_value->point;
            }
        }
    }
    return 0;
}
?>
<script type="text/javascript">
     $(document).ready(function () {
        $('.select2').select2();

    });
    $(document).ready(function () {
        $.extend($.fn.dataTable.defaults, {
            searching: true,
            ordering: true,
            paging: false,
            retrieve: true,
            destroy: true,
            info: false
        });
    });

    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
    var class_id = '<?php echo set_value('class_id') ?>';
    var section_id = '<?php echo set_value('section_id') ?>';
    var session_id = '<?php echo set_value('session_id') ?>';
    var exam_group_id = '<?php echo set_value('exam_group_id') ?>';
    var exam_id = '<?php echo set_value('exam_id') ?>';
    getSectionByClass(class_id, section_id); 
    getExamByExamgroup(exam_group_id, exam_id);
    $(document).on('change', '#exam_group_id', function (e) {
        $('#exam_id').html("");
        var exam_group_id = $(this).val();
        getExamByExamgroup(exam_group_id, 0);
    });

    $(document).on('change', '#class_id', function (e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });

    function getSectionByClass(class_id, section_id) {

        if (class_id !== "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id === obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }

    function getExamByExamgroup(exam_group_id, exam_id) {

        if (exam_group_id !== "") {
            $('#exam_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: "POST",
                url: base_url + "admin/examgroup/getExamByExamgroup",
                data: {'exam_group_id': exam_group_id},
                dataType: "json",
                beforeSend: function () {
                    $('#exam_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (exam_id === obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.exam + "</option>";
                    });

                    $('#exam_id').append(div_data);
                    $('#exam_id').trigger('change');
                },
                complete: function () {
                    $('#exam_id').removeClass('dropdownloading');
                }
            });
        }
    }
</script>