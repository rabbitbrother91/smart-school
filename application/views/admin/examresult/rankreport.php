<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-map-o"></i>  <small></small>  </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('reports/_examinations');?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form role="form" action="<?php echo site_url('admin/examresult/rankreport') ?>" method="post">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-sm-6 col-lg-3 col-md-3 col20">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('exam_group'); ?></label><small class="req"> *</small>
                                        <select  id="exam_group_id" name="exam_group_id" class="form-control select2" >
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
                                </div><!--./col-md-3-->
                                <div class="col-sm-6 col-lg-3 col-md-3 col20">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('exam') ?></label><small class="req"> *</small>
                                        <select  id="exam_id" name="exam_id" class="form-control select2" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('exam_id'); ?></span>
                                    </div>
                                </div><!--./col-md-3-->
                                <div class="col-sm-6 col-lg-3 col-md-3 col20">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('session'); ?></label><small class="req"> *</small>
                                        <select  id="session_id" name="session_id" class="form-control select2" >
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
                                </div><!--./col-md-3-->
                                <div class="col-sm-6 col-lg-3 col-md-12 col20">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
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
                        <div class="box-header ptbnull"></div>
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('student_list'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive no-padding">
                                <div class="download_label"><?php ?> <?php echo $this->lang->line('student_list');
    $this->customlib->get_postmessage();
    ?></div>

                                <?php
if (empty($studentList)) {
        ?>

                                    <?php
} else {
        $count              = 1;
        $student_list_array = array();
        foreach ($studentList as $student_key => $student_value) {

            $result_status                    = 1;
            $no_subject_result                = 0;
            $student_array                    = array();
            $student_array['rank']    = $student_value->rank;
            $student_array['admission_no']    = $student_value->admission_no;
            $student_array['profile_roll_no'] = ($student_value->roll_no != 0) ? $student_value->roll_no : "-";
            $student_array['exam_roll_no']    = ($student_value->exam_roll_no != 0) ? $student_value->exam_roll_no : "-";
            $student_array['student_id']      = $student_value->student_id;
            $student_array['name']            = $this->customlib->getFullName($student_value->firstname, $student_value->middlename, $student_value->lastname, $sch_setting->middlename, $sch_setting->lastname);
            $total_subject                    = count($subjectList);
            $result_total_subject             = 0;

            if (!empty($subjectList)) {
                $student_array['subject_added'] = true;
                $total_marks                    = 0;
                $get_marks                      = 0;
                $get_percentage                 = 0;
                $total_credit_hour              = 0;
                $total_quality_point            = 0;
                $subject_result_list            = array();
                $subject_status                 = true;

                foreach ($subjectList as $subject_key => $subject_value) {
                    $total_marks                     = $total_marks + $subject_value->max_marks;
                    $result                          = getSubjectMarks($student_value->subject_results, $subject_value->subject_id);
                    $subject_result                  = array();
                    $subject_result['result_status'] = false;

                    if ($result) {

                        $result_total_subject++;
                        $subject_status                        = false;
                        $subject_result['result_status']       = true;
                        $no_subject_result                     = 1;
                        $subject_credit_hour                   = $subject_value->credit_hours;
                        $total_credit_hour                     = $total_credit_hour + $subject_value->credit_hours;
                        $percentage_grade                      = ($result->get_marks * 100) / $result->max_marks;
                        $point                                 = findGradePoints($exam_grades, $percentage_grade);
                        $subject_result['point']               = $point;
                        $subject_result['subject_credit_hour'] = $subject_credit_hour;

                        $total_quality_point              = $total_quality_point + ($point * $subject_credit_hour);
                        $get_marks                        = $get_marks + $result->get_marks;
                        $subject_result['get_marks']      = $result->get_marks;
                        $percentage_grade                 = ($result->get_marks * 100) / $subject_value->max_marks;
                        $subject_result['get_exam_grade'] = get_ExamGrade($exam_grades, $percentage_grade);
                        $subject_result['attendence']     = $result->attendence;
                        $subject_result['note']           = $result->note;

                        if (($result->get_marks < $subject_value->min_marks) || $result->attendence == "absent") {
                            $result_status = 0;
                        }
                    }
                    $subject_result_list[] = $subject_result;
                }

                $student_array['total_subject']        = $total_subject;
                $student_array['result_total_subject'] = $result_total_subject;
                $student_array['subjet_results']       = $subject_result_list;
                $student_array['get_marks']            = $get_marks;
                $student_array['total_marks']          = $total_marks;
                $student_array['grand_total']          = number_format($get_marks, 2, '.', '') . "/" . number_format($total_marks, 2, '.', '');
                $total_percentage                      = ($get_marks * 100) / $total_marks;
                $student_array['percentage']           = number_format($total_percentage, 2, '.', '');

                if ($total_quality_point > 0 && $total_credit_hour > 0) {
                    $exam_qulity_point = number_format($total_quality_point / $total_credit_hour, 2, '.', '');
                } else {
                    $exam_qulity_point = number_format(0, 2, '.', '');
                }

                $student_array['quality_points']    = $total_quality_point . "/" . $total_credit_hour . "=" . $exam_qulity_point;
                $student_array['no_subject_result'] = $no_subject_result;
                $student_array['exam_qulity_point'] = $exam_qulity_point;

                if ($exam_details->exam_group_type == "average_passing") {
                    $result_status = ($exam_details->passing_percentage > $total_percentage) ? 0 : 1;
                }

                $student_array['result_status'] = $result_status;
            } else {
                $student_array['subject_added'] = false;
            }

            $student_list_array[] = $student_array;
        }

        if ($student_array['subject_added']) {
            if ($exam_details->exam_group_type != "gpa") {
                aasort($student_list_array);
            } else {
                aasort_gpa($student_list_array);
            }
        }
    }
    ?>

                                <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('rank'); ?></th>
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
                                                    <th><?php echo $this->lang->line('result') ?></th>
                                                    <?php
}
    }
    ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
if (!empty($student_list_array)) {
        $rank_count = 1;
        foreach ($student_list_array as $student_list_value) {
            ?>
                                                <tr>
                                                    <td><?php echo $student_list_value['rank']; ?></td>
                                                    <td><?php echo $student_list_value['admission_no']; ?></td>
                                                    <td>
                                                        <?php
echo ($exam_details->use_exam_roll_no) ? $student_list_value['exam_roll_no'] : $student_list_value['profile_roll_no']; ?> </td>
                                                    <td>
                                                        <a href="<?php echo base_url(); ?>student/view/<?php echo $student_list_value['student_id']; ?>"><?php echo $student_list_value['name'];

            ?>
                                                        </a>
                                                    </td>
                                                    <?php
if ($student_list_value['subject_added']) {

                if (!empty($student_list_value['subjet_results'])) {
                    foreach ($student_list_value['subjet_results'] as $result_key => $result_value) {
                        ?>
                                                                <td>
                                                                    <?php
if ($result_value['result_status']) {
                            if ($exam_details->exam_group_type == "gpa") {
                                echo $result_value['point'] . " X " . $result_value['subject_credit_hour'] . " = " . number_format($result_value['point'] * $result_value['subject_credit_hour'], 2, '.', '');
                            } else {

                                echo $result_value['get_marks'] . ($result_value['get_exam_grade'] == "-" ? "" : " [" . $result_value['get_exam_grade'] . "]");
                            }

                            if ($result_value['attendence'] == "absent") {
                                ?>
                                                                            <p class="text">
                                                                            <?php echo $this->lang->line($result_value['attendence']); ?>
                                                                            </p>
                                                                            <?php
}
                            ?>
                                                                        <p class="text"><?php echo $result_value['note']; ?></p>
                                                                        <?php
}
                        ?>
                                                                </td>
                                                                <?php
}
                }

                if ($exam_details->exam_group_type != "gpa") {
                    ?>
                                                            <td>
                    <?php echo $student_list_value['grand_total']; ?>
                                                            </td>
                                                            <td>
                                                            <?php echo $student_list_value['percentage']; ?>
                                                                <?php 

  if ($exam_details->exam_group_type == "school_grade_system" || $exam_details->exam_group_type == "coll_grade_system") {

 echo  " [" .get_ExamGrade($exam_grades,$student_list_value['percentage'])."]";
  }
                                                                 ?>
                                                            </td>
                                                            <?php
}
                ?>
                                                        <td>
                                                            <?php

                if ($student_list_value['total_subject'] > 0 && $student_list_value['result_total_subject'] >= 1) {
                    if ($exam_details->exam_group_type == "gpa") {
                        
                         $percentage_grade = ($get_marks * 100) / $total_marks;
                         
                        echo $student_list_value['quality_points']. " [" . get_ExamGrade($exam_grades, $percentage_grade) . "]";
                    } else {


  if ($exam_details->exam_group_type == "basic_system") {

                        if ($student_list_value['result_status']) {
                            ?>
                         <label class="label label-success"><?php echo $this->lang->line('pass'); ?><label>
                                                                                <?php
} else {
                            ?>
                          <label class="label label-danger"><?php echo $this->lang->line('fail'); ?><label>
                                                                                        <?php
}
  }


                    }
                }

                ?>
                                                                            </td>
                                                                            <?php
}
            ?>
                                                                        </tr>
                                                                        <?php
$rank_count++;
        }
    }
    ?>
                                                                </tbody>
                                                                </table>
                                                                </div>
                                                                </div>
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

function aasort(&$arr)
{
    array_multisort(
        array_column($arr, 'result_status'), SORT_DESC, array_column($arr, 'percentage'), SORT_DESC, $arr);
}

function aasort_gpa(&$arr)
{
    array_multisort(
        array_column($arr, 'exam_qulity_point'), SORT_DESC, $arr);
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
                                                                            async: false,
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
                                                                            },
                                                                            complete: function () {
                                                                                $('#exam_id').removeClass('dropdownloading');
                                                                            }
                                                                        });
                                                                    }
                                                                }

                                                            </script>