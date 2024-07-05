<?php
if (empty($examresult)) {
    ?>
    <div class="alert alter-info">
        <?php echo $this->lang->line('no_record_found') ?>
    </div>
    <?php
} else {

    echo $examresult->name;
    echo $examresult->exam_type;
    echo $examresult->description;
    echo $examresult->is_active;
    echo $examresult->created_at;
    echo $examresult->updated_at;

    if (!empty($examresult->exam_results)) {
        ?>
        <table class="table table-stripped table-hover">
            <thead>
                <tr>
                    <th> <?php echo $this->lang->line('subject'); ?></th>
                    <?php
                    if ($examresult->exam_type != "gpa") {
                        ?>

                        <th class="text-center"><?php echo $this->lang->line('max') . " " . $this->lang->line('marks'); ?></th>
                        <th class="text-center"><?php echo $this->lang->line('min') . " " . $this->lang->line('marks'); ?></th>
                        <th class="text-center"><?php echo $this->lang->line('obtain_marks'); ?></th>
                        <?php
                        if ($examresult->exam_type == "basic_system") {
                            ?>
                            <th class="text-center"><?php echo $this->lang->line('result'); ?></th>
                            <?php
                        }
                        ?>

                        <th class="text-center"><?php echo $this->lang->line('percentage'); ?></th>

                        <?php
                    }
                    ?>

                    <th class="text-center"><?php echo $this->lang->line('grade') ?></th>
                    <?php
                    if ($examresult->exam_type == "gpa") {
                        ?>
                        <th class="text-center"><?php echo $this->lang->line('grade') . " " . $this->lang->line('point'); ?></th>
                        <th class="text-center"><?php echo $this->lang->line('credit') . " " . $this->lang->line('hours'); ?></th>
                        <th class="text-center"><?php echo $this->lang->line('quality') . " " . $this->lang->line('points'); ?></th>
                        <?php
                    }
                    ?>
                    <th class="text-center"><?php echo $this->lang->line('note'); ?></th>
                </tr>
            <tbody>
                <?php
                $exam_max_marks = 0;
                $exam_min_marks = 0;
                $total_obtain_marks = 0;
                $exam_result_flag = 1;
                $exam_pass_flag = 1; //pass
                $total_points = 0;
                $total_hours = 0;
                $total_quality_point = 0;

                foreach ($examresult->exam_results as $exa_subjects_key => $exa_subjects_value) {
                    $exam_max_marks = $exam_max_marks + $exa_subjects_value->max_marks;
                    $exam_min_marks = $exam_max_marks + $exa_subjects_value->min_marks;
                    $total_obtain_marks = $total_obtain_marks + $exa_subjects_value->get_marks;
                    $result_inner_flag = 1;
                    if ($exa_subjects_value->exam_group_exam_result_id == 0) {
                        $result_inner_flag = 0;
                        $exam_result_flag = 0;
                        $exam_pass_flag = 0; // exam result "N/A"
                    }
                    ?>
                    <tr>
                        <td> <?php echo $exa_subjects_value->name . " (" . $exa_subjects_value->code . ")"; ?></td>
                        <?php
                        if ($examresult->exam_type != "gpa") {
                            ?>
                            <td class="text-center">
                                <?php echo $exa_subjects_value->max_marks; ?>
                            </td>
                            <td class="text-center">
                                <?php echo $exa_subjects_value->min_marks; ?>
                            </td>
                            <td class="text-center">
                                <?php
                                if ($result_inner_flag) {
                                    if ($exa_subjects_value->attendence != "absent") {
                                        echo $exa_subjects_value->get_marks;
                                    } else {
                                        echo "Abs";
                                    }
                                } else {
                                    echo "N/A";
                                }
                                ?>
                            </td>
                            <?php
                            if ($examresult->exam_type == "basic_system") {
                                ?>
                                <td class="text-center">
                                    <?php
                                    if ($result_inner_flag) {
                                        $result = $this->lang->line('pass');
                                        $exa_subjects_value->attendence = "present";
                                        if ($exa_subjects_value->attendence != "absent") {
                                            if ($exa_subjects_value->get_marks < $exa_subjects_value->min_marks) {
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
                                    echo number_format((($exa_subjects_value->get_marks * 100) / $exa_subjects_value->max_marks), 2, '.', '');
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
                                $percentage_grade = ($exa_subjects_value->get_marks * 100) / $exa_subjects_value->max_marks;
                                echo findGrade($exam_grades, $percentage_grade);
                            } else {
                                echo "N/A";
                            }
                            ?>
                        </td>
                        <?php
                        if ($examresult->exam_type == "gpa") {
                            ?>
                            <td class="text-center">
                                <?php
                                if ($result_inner_flag) {
                                    $percentage_grade = ($exa_subjects_value->get_marks * 100) / $exa_subjects_value->max_marks;
                                    $point = findGradePoints($exam_grades, $percentage_grade);
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
                                    $total_hours = $total_hours + $exa_subjects_value->credit_hours;
                                    echo ($exa_subjects_value->credit_hours);
                                } else {
                                    echo "N/A";
                                }
                                ?>

                            </td>
                            <td class="text-center">
                                <?php
                                if ($result_inner_flag) {
                                    echo ($exa_subjects_value->credit_hours * $point);
                                    $total_quality_point = $total_quality_point + ($exa_subjects_value->credit_hours * $point);
                                } else {
                                    echo "N/A";
                                }
                                ?>

                            </td>
                            <?php
                        }
                        ?>

                        <td class="text-center">
                            <?php echo $exa_subjects_value->note; ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>

                <tr>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>

                    <?php
                    if ($examresult->exam_type != "gpa") {
                        ?>
                        <td class="text-center">
                            <?php
                            if ($exam_result_flag) {
                                echo number_format($total_obtain_marks, 2, '.', '');
                            }
                            ?>
                        </td>

                        <?php
                        if ($examresult->exam_type == "basic_system") {
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

                        <td class="text-center"><?php
                            if ($exam_result_flag) {
                                echo number_format((($total_obtain_marks * 100) / $exam_max_marks), 2, '.', '');
                            } else {
                                echo "N/A";
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <?php
                    if ($examresult->exam_type == "gpa") {
                        ?>

                        <td class="text-center">
                            <?php
                            if ($exam_result_flag) {
                                echo $total_hours;
                            }
                            ?>

                        </td>
                        <td class="text-center">
                            <?php
                            if ($exam_result_flag) {
                                $exam_qulity_point = number_format($total_quality_point / $total_hours, 2, '.', '');

                                echo $total_quality_point . "/" . $total_hours . "=" . $exam_qulity_point;
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </thead>
    </table>
    <?php
}
?>

<?php

function findGrade($exam_grades, $percentage) {

    if (!empty($exam_grades)) {
        foreach ($exam_grades as $exam_grade_key => $exam_grade_value) {

            if ($exam_grade_value->mark_from >= $percentage && $exam_grade_value->mark_upto <= $percentage) {
                return $exam_grade_value->name;
            }
        }
    }

    return "-";
}

function findGradePoints($exam_grades, $percentage) {

    if (!empty($exam_grades)) {
        foreach ($exam_grades as $exam_grade_key => $exam_grade_value) {

            if ($exam_grade_value->mark_from >= $percentage && $exam_grade_value->mark_upto <= $percentage) {
                return $exam_grade_value->point;
            }
        }
    }

    return "-";
}
?>