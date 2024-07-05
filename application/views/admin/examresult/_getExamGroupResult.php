<?php
if (!empty($exam_groups_attempt)) {
    foreach ($exam_groups_attempt as $exam_group_key => $exam_group_value) {
        //Exam Group Details
        echo "<br/>";
        echo $exam_group_value->name;
        echo "<br/>";
        echo $exam_group_value->exam_type;
        echo "<br/>";
        //Exam Details
        if (!empty($exam_group_value->exam_results)) {

            foreach ($exam_group_value->exam_results as $examresult_key => $examresult_value) {
                ?>
                <?php echo $examresult_value->exam; ?>
                <?php echo $examresult_value->total_subjects; ?>
                <?php
                if (!empty($examresult_value->exam_results)) {
                    ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-left"><?php echo $this->lang->line('subject'); ?></th>
                                <?php
                                if ($exam_group_value->exam_type != "gpa") {
                                    ?>
                                    <th class="text-center"><?php echo $this->lang->line('max') . " " . $this->lang->line('marks'); ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('min') . " " . $this->lang->line('marks'); ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('obtain') . " " . $this->lang->line('marks'); ?></th>
                                    <?php
                                    if ($exam_group_value->exam_type == "basic_system") {
                                        ?>
                                        <th class="text-center"><?php echo $this->lang->line('result') ?></th>
                                        <?php
                                    }

                                    if ($exam_group_value->exam_type != "gpa") {
                                        ?>
                                        <th class="text-center"><?php echo $this->lang->line('percentage'); ?></th>
                                        <?php
                                    }
                                }
                                if ($exam_group_value->exam_type != "fail_pass") {
                                    ?>
                                    <th class="text-center"><?php echo $this->lang->line('grade'); ?></th>
                                    <?php
                                }

                                if ($exam_group_value->exam_type == "gpa") {
                                    ?>
                                    <th class="text-center"><?php echo $this->lang->line('grade') . " " . $this->lang->line('point'); ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('credit') . " " . $this->lang->line('hours'); ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('quality') . " " . $this->lang->line('points'); ?></th>
                                    <?php
                                }
                                ?>

                                <th class="text-center"><?php echo $this->lang->line('note'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result_flag = 1;
                            $total_max_marks = 0;
                            $total_hours = 0;
                            $total_points = 0;
                            $total_quality_point = 0;
                            $total_get_marks = 0;
                            $exam_result_flag = 1;
                            $exam_pass_flag = 1;
                            foreach ($examresult_value->exam_results as $subjects_key => $subjects_value) {
                                $result_inner_flag = 1;

                                $total_max_marks = $total_max_marks + $subjects_value->max_marks;
                                $exams_collection[$examresult_value->id] = array(
                                    'exam_id' => $examresult_value->id,
                                    'exam_name' => $examresult_value->exam,
                                    'exam_get_marks' => "N/A",
                                    'exam_max_marks' => "N/A",
                                    'exam_quality_points' => 0,
                                );
                                if ($subjects_value->exam_group_exam_result_id == 0) {
                                    $result_inner_flag = 0;
                                    $exam_result_flag = 0;
                                    $exam_pass_flag = 0; // exam result "N/A"
                                }

                                $total_get_marks = $total_get_marks + $subjects_value->get_marks;
                                ?>
                                <tr>
                                    <td class="text-left"><?php echo $subjects_value->name . "( " . $subjects_value->code . " )"; ?></td>
                                    <?php
                                    if ($exam_group_value->exam_type != "gpa") {
                                        ?>

                                        <td class="text-center"><?php echo $subjects_value->max_marks; ?></td>
                                        <td class="text-center"><?php echo $subjects_value->min_marks; ?></td>
                                        <td class="text-center">
                                            <?php
                                            if ($result_inner_flag) {
                                                if ($subjects_value->attendence != "absent") {
                                                    echo $subjects_value->get_marks;
                                                } else {
                                                    echo $this->lang->line('abs');
                                                }
                                            } else {
                                                echo "N/A";
                                            }
                                            ?>

                                        </td>
                                        <?php
                                        if ($exam_group_value->exam_type == "basic_system") {
                                            ?>
                                            <td class="text-center">
                                                <?php
                                                if ($result_inner_flag) {
                                                    $result = $this->lang->line('pass');
                                                    $subjects_value->attendence = "present";
                                                    if ($subjects_value->attendence != "absent") {
                                                        if ($subjects_value->get_marks < $subjects_value->min_marks) {
                                                            $result = $this->lang->line('fail');
                                                            $exam_pass_flag = 2; // exam result "Fail";
                                                        } else {
                                                            $result = $this->lang->line('pass');
                                                        }
                                                    }
                                                    echo $result;
                                                } else {
                                                    echo "N/A";
                                                }
                                                ?>
                                            </td>
                                            <?php
                                        }
                                        ?>

                                        <td class="text-center">
                                            <?php
                                            if ($result_inner_flag) {

                                                echo number_format((($subjects_value->get_marks * 100) / $subjects_value->max_marks), 2, '.', '');
                                            } else {
                                                echo "N/A";
                                            }
                                            ?>

                                        </td>
                                        <?php
                                    }
                                    ?>
                                    <td class="text-center">
                                        <?php
                                        if ($result_inner_flag) {
                                            $percentage_grade = ($subjects_value->get_marks * 100) / $subjects_value->max_marks;
                                            echo findGrade($exam_grades, $percentage_grade, $exam_group_value->exam_type);
                                        } else {
                                            echo "N/A";
                                        }
                                        ?>

                                    </td>
                                    <?php
                                    if ($exam_group_value->exam_type == "gpa") {
                                        ?>
                                        <td class="text-center">
                                            <?php
                                            if ($result_inner_flag) {
                                                $percentage_grade = ($subjects_value->get_marks * 100) / $subjects_value->max_marks;
                                                $point = findGradePoints($exam_grades, $percentage_grade, $exam_group_value->exam_type);
                                                $total_points = $total_points + $point;
                                                echo $point;
                                            } else {
                                                echo "N/A";
                                            }
                                            ?>

                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if ($result_inner_flag) {
                                                $total_hours = $total_hours + $subjects_value->credit_hours;
                                                echo ($subjects_value->credit_hours);
                                            } else {
                                                echo "N/A";
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if ($result_inner_flag) {
                                                echo ($subjects_value->credit_hours * $point);
                                                $total_quality_point = $total_quality_point + ($subjects_value->credit_hours * $point);
                                            } else {
                                                echo "N/A";
                                            }
                                            ?>

                                        </td>
                                        <?php
                                    }
                                    ?>
                                    <td class="text-center">
                                        <?php
                                        if ($result_inner_flag) {
                                            echo $subjects_value->note;
                                        } else {
                                            echo "N/A";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td></td>
                                <?php
                                $exams_collection[$examresult_value->id]['exam_get_marks'] = $total_get_marks;
                                $exams_collection[$examresult_value->id]['exam_max_marks'] = $total_max_marks;
                                if ($exam_group_value->exam_type != "gpa") {
                                    ?>
                                    <td class="text-center"><?php echo number_format($total_max_marks, 2, '.', ''); ?></td>
                                    <td></td>
                                    <td class="text-center">
                                        <?php
                                        if ($exam_result_flag) {
                                            echo number_format($total_get_marks, 2, '.', '');
                                        }
                                        ?>
                                    </td>
                                    <?php
                                    if ($exam_group_value->exam_type == "basic_system") {
                                        ?>
                                        <td class="text-center">
                                            <?php
                                            if ($exam_pass_flag == 1) {
                                                echo $this->lang->line('pass');
                                            } elseif ($exam_pass_flag == 2) {
                                                echo $this->lang->line('fail');
                                            } else {
                                                echo "N/A";
                                            }
                                            ?>

                                        </td>
                                        <?php
                                    }
                                    ?>
                                    <td class="text-center">
                                        <?php
                                        if ($exam_result_flag == 1) {
                                            echo number_format((($total_get_marks * 100) / $total_max_marks), 2, '.', '');
                                        } else {
                                            echo "N/A";
                                        }
                                        ?>
                                    </td>
                                    <?php
                                }
                                ?>

                                <td></td>
                                <?php
                                if ($exam_group_value->exam_type == "gpa") {
                                    ?>
                                    <td class="text-center">

                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if ($result_flag == 1) {
                                            echo $total_hours;
                                        }
                                        ?>

                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if ($result_flag == 1) {
                                            $exam_qulity_point = number_format($total_quality_point / $total_hours, 2, '.', '');
                                            $exams_collection[$examresult_value->id]['exam_quality_points'] = $exam_qulity_point;
                                            echo $total_quality_point . "/" . $total_hours . "=" . $exam_qulity_point;
                                        }
                                        ?>

                                    </td>
                                    <?php
                                }
                                ?>
                                <td></td>
                            </tr>
                            <?php ?>
                        </tbody>
                    </table>
                    <?php
                }
                ?>
                <?php
            } 
        }
        if (!empty($exam_group_value->exam_group_connection)) {
            echo $this->lang->line("consolidated_results");
            if (!empty($exam_group_value->exam_group_connection['exam_connections'])) {

                $arrays = array();
                $count = 1;
                if ($exam_group_value->exam_type == "gpa") {
                    $total_quality_point = 0;
                    ?>
                    <table class="table table-stripped table-hover">
                        <thead>
                        <th><?php echo $this->lang->line('quality_points'); ?></th>
                        <?php
                        if (!empty($exam_group_value->exam_group_connection['exam_connections'])) {

                            foreach ($exam_group_value->exam_group_connection['exam_connections'] as $exam_loop_key => $exam_loop_value) {                            
                                
                                ?>
                                <th>
                                    <?php echo $exam_loop_value->exam; ?>
                                </th>
                                <?php
                            }
                        }
                        ?>
                        <th>CGPA</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <?php
                            if (!empty($exam_group_value->exam_group_connection['exam_connections'])) {

                                foreach ($exam_group_value->exam_group_connection['exam_connections'] as $exam_loop_key => $exam_loop_value) {
                                    ?>
                                    <td>
                                        <?php
                                        $ex_quality_point = getExamQualityPoints($exam_group_value->exam_results, $exam_loop_value->exam_group_class_batch_exams_id, $exam_grades, $exam_group_value->exam_type);

                                        $total_quality_point = $total_quality_point + $ex_quality_point;
                                        echo $ex_quality_point;
                                        ?>
                                    </td>
                                    <?php
                                }
                            }
                            ?>
                            <td>
                                <?php
                                echo number_format(($total_quality_point / count($exam_group_value->exam_group_connection['exam_connections'])), 2, '.', '');
                                ?>
                            </td>
                        </tr>
                    </tbody>
                    </table>

                    <?php
                } else if ($exam_group_value->exam_type == "coll_grade_system") {
                    $conbine_exam_percentage = 0;
                    ?>
                    <table class="table table-stripped table-hover">
                        <thead>
                            <?php
                            if (!empty($exam_group_value->exam_group_connection['exam_connections'])) {
                                foreach ($exam_group_value->exam_group_connection['exam_connections'] as $exam_loop_key => $exam_loop_value) {                                
                                    
                                    ?>
                                <th>
                                    <?php echo $exam_loop_value->exam; ?>
                                </th>
                                <?php
                            }
                        }
                        ?>
                        <th><?php echo $this->lang->line('combined'); ?></th>
                    </thead>
                    <tbody>
                        <tr>

                            <?php
                            if (!empty($exam_group_value->exam_group_connection['exam_connections'])) {
                                foreach ($exam_group_value->exam_group_connection['exam_connections'] as $exam_loop_key => $exam_loop_value) {
                                    ?>
                                    <td>
                                        <?php
                                        $exam_details = getExamDetails($exam_group_value->exam_results, $exam_loop_value->exam_group_class_batch_exams_id, $exam_grades);
                                        echo $exam_details['total_get_marks'] . "/" . $exam_details['total_max_marks'];

                                        $combine_percentage = findConnectedExamPercentage($exam_group_value->exam_group_connection['exam_connections'], $exam_loop_value->exam_group_class_batch_exam_id);

                                        $conbine_exam_percentage = $conbine_exam_percentage + ((($exam_details['total_get_marks'] * 100) / $exam_details['total_max_marks']) * $combine_percentage) / 100;
                                       ?>
                                    </td>
                                    <?php
                                }
                            }
                            ?>
                            <td>
                                <?php
                                echo number_format($conbine_exam_percentage, 2, '.', '') . "[" . findGrade($exam_grades, $conbine_exam_percentage, $exam_group_value->exam_type) . "]";
                                ?>
                            </td>
                        </tr>
                    </tbody>
                    </table>

                    <?php
                } else if ($exam_group_value->exam_type == "school_grade_system") {
                    ?>

                    <table class="table table-stripped table-hover">
                        <thead>

                            <?php
                            if (!empty($exam_group_value->exam_group_connection['exam_connections'])) {

                                foreach ($exam_group_value->exam_group_connection['exam_connections'] as $exam_loop_key => $exam_loop_value) {

                                    $examwise_percentage[$exam_loop_value->exam_group_class_batch_exams_id] = array('score' => 0, 'max_marks' => 0, 'flag' => 1);
                                    ?>
                                <th>
                                    <?php echo $exam_loop_value->exam; ?>
                                </th>
                                <?php
                            }
                        }
                        ?>
                        <th><?php echo $this->lang->line('combined'); ?></th>
                    </thead>
                    <tbody>

                        <?php
                        if (!empty($exam_group_value->exam_group_connection['connect_subjects'])) {

                            foreach ($exam_group_value->exam_group_connection['connect_subjects'] as $connect_subject_key => $connect_subject_value) {
                                $exam_flag = 1;
                                $total_percentage = 0;
                                $row_total = 0;
                                $conbine_exam_percentage = 0;
                                ?>
                                <tr>
                                    <td><?php echo $connect_subject_value->name; ?></td>
                                    <?php
                                    foreach ($exam_group_value->exam_group_connection['exam_connections'] as $exam_loop_key => $exam_loop_value) {
                                        ?>
                                        <td>
                                            <?php
                                            $ass = findExamGroupMarks($exam_group_value->exam_results, $exam_loop_value->exam_group_class_batch_exam_id, $connect_subject_value->subject_id);

                                            if ($ass->exam_group_exam_result_id != 0) {
                                                $combine_percentage = findConnectedExamPercentage($exam_group_value->exam_group_connection['exam_connections'], $exam_loop_value->exam_group_class_batch_exam_id);
                                                echo $ass->get_marks . "/" . $ass->max_marks;
                                                $conbine_exam_percentage = $conbine_exam_percentage + ((($ass->get_marks * 100) / $ass->max_marks) * $combine_percentage) / 100;
                                            } else {
                                                $exam_flag = 0;
                                                echo "N/A";
                                            }
                                            ?>
                                        </td>
                                        <?php
                                    }
                                    ?>
                                    <td>
                                        <?php
                                        if ($exam_flag == 0) {
                                            echo "N/A";
                                        } else if ($exam_flag == 1) {

                                            echo number_format($conbine_exam_percentage, 2, '.', '') . "[" . findGrade($exam_grades, $conbine_exam_percentage, $exam_group_value->exam_type) . "]";
                                        } else {
                                            
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>

                            <?php
                        } else {
                            ?>
                            <tr>
                                <td colspan="4">
                                    <?php echo $this->lang->line('no_exam_found'); ?>
                                </td>

                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                    </table>

                    <?php
                } else if ($exam_group_value->exam_type == "basic_system") {
                    ?>

                    <table class="table table-stripped table-hover">
                        <thead>
                        <th><?php echo $this->lang->line('subject') ?></th>
                        <?php
                        if (!empty($exam_group_value->exam_group_connection['exam_connections'])) {

                            foreach ($exam_group_value->exam_group_connection['exam_connections'] as $exam_loop_key => $exam_loop_value) {

                                $examwise_percentage[$exam_loop_value->exam_group_class_batch_exams_id] = array('score' => 0, 'max_marks' => 0, 'flag' => 1);
                                ?>
                                <th>
                                    <?php echo $exam_loop_value->exam; ?>
                                </th>
                                <?php
                            }
                        }
                        ?>
                        <th><?php echo $this->lang->line('combined'); ?></th>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($exam_group_value->exam_group_connection['connect_subjects'])) {

                            foreach ($exam_group_value->exam_group_connection['connect_subjects'] as $connect_subject_key => $connect_subject_value) {
                                $exam_flag = 1;
                                $total_percentage = 0;
                                $row_total = 0;
                                $conbine_exam_percentage = 0;
                                ?>
                                <tr>
                                    <td><?php echo $connect_subject_value->name; ?></td>
                                    <?php
                                    foreach ($exam_group_value->exam_group_connection['exam_connections'] as $exam_loop_key => $exam_loop_value) {
                                        ?>
                                        <td>
                                            <?php
                                            $ass = findExamGroupMarks($exam_group_value->exam_results, $exam_loop_value->exam_group_class_batch_exam_id, $connect_subject_value->subject_id);

                                            if ($ass->exam_group_exam_result_id != 0) {
                                                $combine_percentage = findConnectedExamPercentage($exam_group_value->exam_group_connection['exam_connections'], $exam_loop_value->exam_group_class_batch_exam_id);
                                                echo $ass->get_marks . "/" . $ass->max_marks;
                                                $conbine_exam_percentage = $conbine_exam_percentage + ((($ass->get_marks * 100) / $ass->max_marks) * $combine_percentage) / 100;
                                            } else {
                                                $exam_flag = 0;
                                                echo "N/A";
                                            }
                                            ?>
                                        </td>
                                        <?php
                                    }
                                    ?>
                                    <td>
                                        <?php
                                        if ($exam_flag == 0) {
                                            echo "N/A";
                                        } else if ($exam_flag == 1) {

                                            echo number_format($conbine_exam_percentage, 2, '.', '') . "[" . findGrade($exam_grades, $conbine_exam_percentage, $exam_group_value->exam_type) . "]";
                                        } else {
                                            
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>

                            <?php
                        } else {
                            ?>
                            <tr>
                                <td colspan="4">
                                    <?php echo $this->lang->line('no_exam_found'); ?>
                                </td>

                            </tr>
                            <?php
                        }
                        ?>

                    </tbody>
                    </table>

                    <?php
                }
            }
        }
    }
} else {
    ?>
    <div class="alert alert-info">
        <?php echo $this->lang->line('no_exam_found'); ?>
    </div>
    <?php
}
?>

<?php

function getExamDetails($exam_group_exam_results, $exam_id, $exam_grades) {

    if (!empty($exam_group_exam_results)) {
        $total_get_marks = 0;
        $total_max_marks = 0;
        foreach ($exam_group_exam_results as $exam_group_exam_key => $exam_group_exam_val) {

            if ($exam_group_exam_val->id == $exam_id) {
                if (!empty($exam_group_exam_val->exam_results)) {
                    foreach ($exam_group_exam_val->exam_results as $exam_result_key => $exam_result_val) {
                        $total_max_marks = $total_max_marks + $exam_result_val->max_marks;
                        if ($exam_result_val->exam_group_exam_result_id != 0) {
                            $total_get_marks = $total_get_marks + $exam_result_val->get_marks;
                        }
                    }
                }
            }
        }

        return array('total_get_marks' => $total_get_marks, 'total_max_marks' => $total_max_marks);
    }

    return "-";
}

function getExamQualityPoints($exam_group_exam_results, $exam_id, $exam_grades, $exam_type) {

    if (!empty($exam_group_exam_results)) {
        $total_credit_hours = 0;
        $total_point = 0;
        foreach ($exam_group_exam_results as $exam_group_exam_key => $exam_group_exam_val) {

            if ($exam_group_exam_val->id == $exam_id) {
                if (!empty($exam_group_exam_val->exam_results)) {
                    foreach ($exam_group_exam_val->exam_results as $exam_result_key => $exam_result_val) {
                        $percentage_grade = ($exam_result_val->get_marks * 100) / $exam_result_val->max_marks;
                        $point = findGradePoints($exam_grades, $percentage_grade, $exam_type);
                        $total_point = $total_point + ($point * $exam_result_val->credit_hours);
                        $total_credit_hours = $total_credit_hours + $exam_result_val->credit_hours;
                    }
                }
            }
        }
        return number_format($total_point / $total_credit_hours, 2, '.', '');
    }

    return "-";
}

function findConnectedExamPercentage($exam_connections, $exam_group_class_batch_exam_id) {

    if (!empty($exam_connections)) {
        foreach ($exam_connections as $exam_connection_key => $exam_connection_value) {
            if ($exam_connection_value->exam_group_class_batch_exams_id == $exam_group_class_batch_exam_id) {
                return $exam_connection_value->exam_weightage;
            }
        }
    }

    return false;
}

function findExamGroupMarks($exam_group_exam_results, $exam_group_class_batch_exam_id, $subject_id) {

    if (!empty($exam_group_exam_results)) {
        foreach ($exam_group_exam_results as $exam_group_exam_key => $exam_group_exam_val) {

            if ($exam_group_exam_val->id == $exam_group_class_batch_exam_id) {
                if (!empty($exam_group_exam_val->exam_results)) {
                    foreach ($exam_group_exam_val->exam_results as $exam_result_key => $exam_result_val) {
                        if ($exam_result_val->subject_id == $subject_id) {
                            return $exam_result_val;
                        }
                    }
                }
            }
        }
    }

    return "-";
}

function findGrade($exam_grades, $percentage, $exam_type) {

    if (!empty($exam_grades)) {
        foreach ($exam_grades as $exam_grade_key => $exam_grade_value) {

            if ($exam_grade_value['exam_type'] == $exam_type) {

                if ($exam_grade_value['mark_from'] >= $percentage && $exam_grade_value['mark_upto'] <= $percentage) {
                    return $exam_grade_value['name'];
                }
            }
        }
    }

    return "-";
}

function findGradePoints($exam_grades, $percentage, $exam_type) {

    if (!empty($exam_grades)) {
        foreach ($exam_grades as $exam_grade_key => $exam_grade_value) {
            if ($exam_grade_value['exam_type'] == $exam_type) {
                if ($exam_grade_value['mark_from'] >= $percentage && $exam_grade_value['mark_upto'] <= $percentage) {
                    return $exam_grade_value['point'];
                }
            }
        }
    }

    return "-";
}

function arrange_code($exam_group_class_batch_exams_id, $class_batch_subject_id, $exam_result) {
    if (!empty($exam_result)) {
        foreach ($exam_result as $ex_key => $ex_value) {
            if ($ex_value->id == $exam_group_class_batch_exams_id) {

                foreach ($ex_value->exam_results as $ex_result_key => $ex_result_value) {
                    if ($ex_result_value->class_batch_subject_id == $class_batch_subject_id) {
                        return $ex_result_value;
                    }
                }
            }
        }
    }
}

function findExamPercentage($exams, $find_exam_percentage) {
    if (!empty($exams)) {
        foreach ($exams as $exams_key => $exams_value) {
            if ($exams_value->exam_group_class_batch_exams_id == $find_exam_percentage) {
                return $exams_value->exam_weightage;
            }
        }
    }
    return false;
}
?>