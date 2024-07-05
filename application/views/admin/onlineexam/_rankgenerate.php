<?php
if (!empty($student_data)) {
    if ($onlineexam->is_rank_generated) {
?>
        <div class="alert alert-info" role="alert">
            <?php echo $this->lang->line('rank_has_already_generated_you_can_update_rank'); ?>
        </div>
    <?php
    }
    ?>
    <form action="<?php echo site_url('admin/onlineexam/saverank') ?>" id="saverank" method="POST" class="mb10">
        <?php
        $array_rank = array();
        if (!empty($student_question_array)) {
            foreach ($student_question_array as $student_key => $student_value) {
                $correct_ans            = 0;
                $wrong_ans              = 0;
                $not_attempted          = 0;
                $total_question         = 0;
                $exam_total_scored      = 0;
                $exam_total_marks       = 0;
                $exam_total_neg_marks   = 0;
                $exam_total_descriptive = 0;
                if (!empty($student_value)) {
                    foreach ($student_value as $question_key => $question_value) {
                        $total_marks_json  = getMarks($question_value);
                        $total_marks_array = (json_decode($total_marks_json));
                        $exam_total_marks  = $exam_total_marks + $total_marks_array->get_marks;
                        $exam_total_scored = $exam_total_scored + $total_marks_array->scr_marks;

                        //============
                        if ($question_value->select_option != null) {
                            if ($question_value->question_type == "singlechoice" || $question_value->question_type == "true_false") {
                                if ($question_value->select_option == $question_value->correct) {
                                    $correct_ans++;
                                } else {
                                    $exam_total_neg_marks = $exam_total_neg_marks + $question_value->neg_marks;
                                    $wrong_ans++;
                                }
                            } elseif ($question_value->question_type == "multichoice") {

                                if (array_equal(json_decode($question_value->correct), json_decode($question_value->select_option))) {
                                    $correct_ans++;
                                } else {
                                    $exam_total_neg_marks = $exam_total_neg_marks + $question_value->neg_marks;
                                    $wrong_ans++;
                                }
                            }
                        } else {
                            $exam_total_neg_marks = $exam_total_neg_marks + $question_value->neg_marks;
                            $not_attempted++;
                        }

                        //===============

                    }
                }

                if (!$onlineexam->is_neg_marking) {
                    $exam_total_neg_marks = 0;
                }

                $get_marks                = (int) ($exam_total_scored - $exam_total_neg_marks);


                $array_rank[$get_marks][] = $student_key;
            }
        }

        krsort($array_rank);

        $rank_increment = 1;
        foreach ($array_rank as $rank_key => $rank_value) {
            foreach ($rank_value as $student_key => $student_value) {
        ?>
                <input type="hidden" name="row[]" value="<?php echo $student_value ?>">
                <input type="hidden" name="onlineexam_student_id_<?php echo $student_value ?>" value="<?php echo $rank_increment; ?>">
        <?php

            }
            $rank_increment++;
        }

        ?>
        <input type="hidden" name="exam_id" value="<?php echo $examid ?>" id="generate_exam_id">
        <button type="submit" class="btn btn-primary pull-right mb10" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please wait" value=""><?php echo $this->lang->line('generate_rank'); ?></button>

    </form>
<?php
}
?>
<div class="clearboth">
</div>
<div class="table-responsive quizscroll">
    <table class="table table-striped">
        <thead class="tableheadfix">
            <tr>
                <th><?php echo $this->lang->line('admission_no'); ?></th>
                <th><?php echo $this->lang->line('student_name'); ?></th>
                <th><?php echo $this->lang->line('class'); ?></th>
                <?php if ($sch_setting->father_name) { ?>
                    <th>
                        <?php echo $this->lang->line('father_name'); ?></th>
                <?php
                }
                if ($sch_setting->category) {
                ?>
                    <th><?php echo $this->lang->line('category'); ?></th>
                <?php
                }
                ?>
                <th class="pull-right"><?php echo $this->lang->line('gender'); ?></th>
                <th class="pull-right"><?php echo $this->lang->line('rank'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (empty($student_data)) {
            ?>
                <tr>
                    <td colspan="7" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                </tr>
                <?php
            } else {
                $count = 1;
                foreach ($student_data as $student) {
                ?>
                    <tr>
                        <td><?php echo $student['admission_no']; ?></td>
                        <td><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></td>
                        <td><?php echo $student['class'] . " (" . $student['section'] . ")"; ?></td><?php if ($sch_setting->father_name) { ?>
                            <td><?php echo $student['father_name']; ?></td>
                        <?php }
                                                                                                    if ($sch_setting->category) { ?>
                            <td><?php echo $student['category']; ?></td>
                        <?php } ?>
                        <td class="pull-right"><?php echo $this->lang->line(strtolower($student['gender'])); ?></td>
                        <td class="pull-right"><?php echo $student['exam_rank']; ?></td>
                    </tr>
            <?php
                }
                $count++;
            }
            ?>
        </tbody>
    </table>
</div>

<?php
function array_equal($a, $b)
{
    return (
        is_array($a) && is_array($b) && count($a) == count($b) && array_diff($a, $b) === array_diff($b, $a)
    );
}

function getMarks($question)
{
    if ($question->select_option != null) {
        if ($question->question_type == "singlechoice" || $question->question_type == "true_false") {
            if ($question->correct == $question->select_option) {
                return json_encode(array('get_marks' => $question->marks, 'scr_marks' => $question->marks));
            }
        } elseif ($question->question_type == "descriptive") {
            return json_encode(array('get_marks' => $question->marks, 'scr_marks' => $question->score_marks));
        } elseif ($question->question_type == "multichoice") {
            $cr_ans  = json_decode($question->correct);
            $sel_ans = json_decode($question->select_option);
            if (array_equal($cr_ans, $sel_ans)) {
                return json_encode(array('get_marks' => $question->marks, 'scr_marks' => $question->marks));
            }
        }
    }
    return json_encode(array('get_marks' => $question->marks, 'scr_marks' => 0));
}
?>