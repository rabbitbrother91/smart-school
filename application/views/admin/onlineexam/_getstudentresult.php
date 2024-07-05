<?php

if ($online_exam_validate->is_attempted == 1 && ($exam->is_quiz)) {
    $exam->publish_result = true;
} else if (($exam->auto_publish_date != "0000-00-00" && $exam->auto_publish_date != "" && $exam->auto_publish_date != null) && $exam->publish_result) {
    $exam->publish_result = true;
} else if (($exam->auto_publish_date != "0000-00-00" && $exam->auto_publish_date != "" && $exam->auto_publish_date != null) && !$exam->publish_result) {

    if (strtotime($exam->auto_publish_date) <= strtotime(date('Y-m-d H:i:s'))) {
        $exam->publish_result = true;
    } else {
        $exam->publish_result = false;
    }
} else {

}

$dispaly_negative_marks = $exam->is_neg_marking;
if (!empty($online_exam_validate)) {
    $correct_ans            = 0;
    $wrong_ans              = 0;
    $not_attempted          = 0;
    $total_question         = 0;
    $exam_total_scored      = 0;
    $exam_total_marks       = 0;
    $exam_total_neg_marks   = 0;
    $exam_total_descriptive = 0;
    if (!empty($question_result)) {
        $total_question = count($question_result);

        foreach ($question_result as $result_key => $question_value) {
            $total_marks_json  = getMarks($question_value);
            $total_marks_array = (json_decode($total_marks_json));
            $exam_total_marks  = $exam_total_marks + $total_marks_array->get_marks;
            $exam_total_scored = $exam_total_scored + $total_marks_array->scr_marks;
            if ($question_value->question_type == "descriptive") {
                $exam_total_descriptive++;
            }
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
        }
        if (!$dispaly_negative_marks) {
            $exam_total_neg_marks = 0;
        }
    }

    ?>
                  <button class="btn btn-primary btn-xs print_div" title="<?php echo $this->lang->line('print'); ?>" data-examid="<?php echo $exam->id; ?>" data-student_session_id="<?php echo $student_session_id; ?>" data-recordid="<?php echo $onlineexam_student_id; ?>"  data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-print"></i></button>

                  <div class="">
                  <h4 class="text-center font-weight-bold"><?php echo $exam->exam; ?></h4>
                  <div class="row" >
                     <div class="col-lg-8 col-md-8 col-sm-12">
                        <dl class="row mb10">
                           <div class="col-lg-6 col-md-6 col-sm-12">
                              <div class="row">
                                 <dt class="col-sm-6 col-xs-6"><?php echo $this->lang->line('student_name'); ?></dt>
                                 <dd class="col-sm-6 col-xs-6"><?php echo $student_name; ?></dd>
                                 <dt class="col-sm-6 col-xs-6"><?php echo $this->lang->line('class'); ?></dt>
                                 <dd class="col-sm-6 col-xs-6"><?php echo $class; ?></dd>
                                 <dt class="col-sm-6 col-xs-6"><?php echo $this->lang->line('section'); ?></dt>
                                 <dd class="col-sm-6 col-xs-6"><?php echo $section; ?></dd>
                                 <dt class="col-sm-6 col-xs-6"><?php echo $this->lang->line('father_name'); ?></dt>
                                 <dd class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><?php if ($father_name != "") {echo $father_name;} else {echo "&nbsp;";}?></dd>
                                 <dt class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><?php echo $this->lang->line('total_attempt'); ?></dt>
                                 <dd class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><?php echo $exam->attempt; ?></dd>
                                 <dt class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><?php echo $this->lang->line('exam_from'); ?></dt>
                                 <dd class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                  <?php echo $this->customlib->dateyyyymmddToDateTimeformat($exam->exam_from, false); ?>
                                  </dd>
                                 <dt class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                  <?php echo $this->lang->line('exam_to') ?></dt>
                                 <dd class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                 <?php echo $this->customlib->dateyyyymmddToDateTimeformat($exam->exam_to, false); ?>
                                  </dd>
                                             <?php
if (($exam->auto_publish_date != "0000-00-00" && $exam->auto_publish_date != "" && $exam->auto_publish_date != null)) {
        ?>
 <dt class="col-sm-6 col-xs-12 col-md-6 col-lg-6">
                                 <?php echo $this->lang->line('result_publish_date') ?></dt>
                                 <dd class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                 <?php echo $this->customlib->dateyyyymmddToDateTimeformat($exam->auto_publish_date, false); ?>
                                  </dd>
  <?php
}
    ?>
                                 <dt class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><?php echo $this->lang->line('duration') ?></dt>
                                 <dd class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><?php echo $exam->duration; ?></dd>
                                 <dt class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><?php echo $this->lang->line('passing') ?>   (%)</dt>
                                 <dd class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><?php echo $exam->passing_percentage; ?></dd>
                              </div>
                           </div>
                           <!--lcol-lg-6-->
                           <div class="col-lg-6 col-md-6 col-sm-12">
                              <div class="row">
                                 <dt class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><?php echo $this->lang->line('total_questions') ?></dt>
                                 <dd class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><?php echo $total_question; ?></dd>
                                 <dt class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><?php echo $this->lang->line('descriptive_questions'); ?></dt>
                                 <dd class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><?php echo $exam_total_descriptive; ?></dd>
                                 <?php
if ($exam->publish_result) {
        ?>
                                 <dt class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><?php echo $this->lang->line('correct_answer') ?></dt>
                                 <dd class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><?php echo $correct_ans; ?></dd>
                                 <dt class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><?php echo $this->lang->line('wrong_answer'); ?></dt>
                                 <dd class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><?php echo $wrong_ans; ?></dd>
                                 <dt class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><?php echo $this->lang->line('not_attempted'); ?></dt>
                                 <dd class="col-sm-6 col-xs-6 col-md-6 col-lg-6"><?php echo $not_attempted; ?></dd>
                              </div>
                           </div>
                           <!--lcol-lg-6-->
                           <?php
}
    ?>
                        </dl>
                     </div>
                     <div class="col-lg-4 col-md-4 col-sm-4">
                        <ul class="qulist_circle">
                        <li><i class="fa fa-check-circle-o text-success"></i> <?php echo $this->lang->line('correct_answer'); ?></li>
                           <li><i class="fa fa-dot-circle-o text-success"></i> <?php echo $this->lang->line('correct_answer_but_not_attempted'); ?></li>
                           <li><i class="fa fa-times-circle-o text-danger"></i> <?php echo $this->lang->line('wrong_answer') ?></li>
                        </ul>
                     </div>
                  </div>
                  </div>
                   <div class="row pb10">
                     <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
                    <span class="font-weight-bold"> <?php echo $this->lang->line('description'); ?>:</span> <?php echo $exam->description; ?>
                     </div>
                     </div>
                  <?php
if ($exam->publish_result) {
        if (!$online_exam_validate->is_attempted) {
            ?>
  <div class="alert alert-info">
  <?php echo $this->lang->line('exam_not_submitted') ?>
  </div>
  <?php
}
        ?>

  <div class="hrexam ptt10"></div>
                  <div class="row">
                     <div class="col-sm-6 col-xs-12 col-md-3 col-lg-3"><span class="font-weight-bold"> <?php echo $this->lang->line('total_exam_marks'); ?>:</span> <?php echo $exam_total_marks; ?></div>

                     <?php
if ($dispaly_negative_marks) {
            ?>
  <div class="col-sm-6 col-xs-12 col-md-3 col-lg-3"><span class="font-weight-bold"> <?php echo $this->lang->line('total_negative_marks'); ?>:</span> <?php echo $exam_total_neg_marks; ?></div>
  <?php
}
        ?>
                       <div class="col-sm-6 col-xs-12 col-md-3 col-lg-3"><span class="font-weight-bold"> <?php echo $this->lang->line('total_scored_marks'); ?>:</span> <?php echo $exam_total_scored - $exam_total_neg_marks; ?></div>

                        <div class="col-sm-6 col-xs-12 col-md-3 col-lg-3"><span class="font-weight-bold"><?php echo $this->lang->line('score'); ?> (%):</span> <?php

        echo ($exam_total_marks === 0) ? 0 : number_format(((($exam_total_scored - $exam_total_neg_marks) * 100) / $exam_total_marks), 2, '.', '');
        ?></div>
                        <div class="col-sm-6 col-xs-12 col-md-3 col-lg-3"><span class="font-weight-bold"><?php echo $this->lang->line('exam_rank') ?>: </span>
                               <?php
if ($exam->is_rank_generated && $exam->publish_result) {
            echo ($online_exam_validate->rank);

        } else {
            ?><?php echo $this->lang->line('awaited') ?><?php
}
        ?>
                        </div>
                  </div>
                  <div class="hrexamtopbottom"></div>
  <?php
}
    ?>
                  <?php
if (!empty($question_result) && ($exam->publish_result)) {

        $question_no = 1;
        foreach ($question_result as $result_key => $question_value) {
            $marks_json  = getMarks($question_value);
            $marks_array = (json_decode($marks_json));

            $not_attempted = true;
            $given_ans     = false;
            $display_ans   = true;
            if ($question_value->select_option != null) {
                $not_attempted = false;
            }
            ?>
                  <div class="row">
                     <div class="col-xs-12 col-md-12 section-box">
                        <div>
                           <p>
                              <span class="font-weight-bold">Q.<?php echo $question_no; ?> </span><?php echo $question_value->question; ?>
                               <span class="text text-danger">
                                Marks :(<?php echo $marks_array->scr_marks . "/" . $marks_array->get_marks ?>)
                                <?php
if ($dispaly_negative_marks && $question_value->question_type != "descriptive") {
                ?>
                                Negative Marks :(<?php echo $question_value->neg_marks; ?>)
                                <?php
}
            ?>
                                                                          </span>
                           </p>
                           <p>
                              <b><?php echo $this->lang->line('subject') ?>:</b>
                              <?php echo $question_value->subject_name; ?> <?php if($question_value->subject_code){ echo ' ('.$question_value->subject_code.')'; } ?>
                           </p>
                           <?php
if ($question_value->question_type != "descriptive") {

                if ($question_value->question_type == "singlechoice") {
                    $question_total_option = 1;
                    $question_display      = true;
                    foreach ($questionOpt as $question_opt_key => $question_opt_value) {
                        if ($question_total_option == 5 && $question_value->{$question_opt_key} == "") {
                            $question_display = false;
                        }
                        if ($question_display) {
                            if (($question_value->correct == $question_opt_key) && $question_value->select_option == null) {
                                $cls     = "text text-success";
                                $fa_icon = "fa fa-dot-circle-o";
                            } elseif (($question_value->correct == $question_opt_key)) {
                                $cls     = "text text-success";
                                $fa_icon = "fa fa-check-circle-o";
                            } elseif (($question_value->select_option == $question_opt_key)) {
                                $cls     = "text text-danger";
                                $fa_icon = "fa fa-times-circle-o";
                            } else {
                                $cls     = "";
                                $fa_icon = "fa fa-dot-circle-o";
                            }
                            ?>
                           <div class="<?php echo $cls; ?>">
                              <i class="<?php echo $fa_icon; ?>"></i> <?php echo $question_value->{$question_opt_key}; ?>
                           </div>
                           <?php
}
                        $question_total_option++;
                    }
                } elseif ($question_value->question_type == "true_false") {
                    foreach ($question_true_false as $question_true_false_key => $question_true_false_value) {

                        if (($question_value->correct == $question_true_false_key) && $question_value->select_option == null) {
                            $cls     = "text text-success";
                            $fa_icon = "fa fa-dot-circle-o";
                        } elseif (($question_value->correct == $question_true_false_key)) {
                            $cls     = "text text-success";
                            $fa_icon = "fa fa-check-circle-o";
                        } elseif (($question_value->select_option == $question_true_false_key)) {
                            $cls     = "text text-danger";
                            $fa_icon = "fa fa-times-circle-o";
                        } else {
                            $cls     = "";
                            $fa_icon = "fa fa-dot-circle-o";
                        }
                        ?>
                           <div class="<?php echo $cls; ?>">
                              <i class="<?php echo $fa_icon; ?>"></i> <?php echo $question_true_false_value; ?>
                           </div>
                           <?php
}
                } elseif ($question_value->question_type == "multichoice") {
                    $question_total_option = 1;
                    $question_display      = true;

                    foreach ($questionOpt as $question_opt_key => $question_opt_value) {
                        if ($question_total_option == 5 && $question_value->{$question_opt_key} == "") {
                            $question_display = false;
                        }
                        if ($question_display) {
                            $correct_answer = json_decode($question_value->correct);

                            $selected_answer = isJSON($question_value->select_option) ? json_decode($question_value->select_option) : array();

                            if (in_array($question_opt_key, $correct_answer) && !in_array($question_opt_key, $selected_answer)) {
                                $cls     = "text text-success";
                                $fa_icon = "fa fa-dot-circle-o";
                            } elseif (in_array($question_opt_key, $correct_answer)) {
                                $cls     = "text text-success";
                                $fa_icon = "fa fa-check-circle-o";
                            } elseif (in_array($question_opt_key, $selected_answer)) {
                                $cls     = "text text-danger";
                                $fa_icon = "fa fa-times-circle-o";
                            } else {
                                $cls     = "";
                                $fa_icon = "fa fa-dot-circle-o";
                            }
                            ?>
                           <div class="<?php echo $cls; ?>">
                              <i class="<?php echo $fa_icon; ?>"></i> <?php echo $question_value->{$question_opt_key}; ?>
                           </div>
                           <?php
}
                        $question_total_option++;
                    }
                }
            }
            ?>
                           <?php
if ($question_value->question_type == "descriptive") {

                ?>
                           <p>
                              <b> <?php echo $this->lang->line('your_answer'); ?>:</b><br>
                              <?php echo $question_value->select_option; ?>
                           </p>
                           <?php
if ($question_value->remark != "") {
                    ?>
                           <p>
                              <b><?php echo $this->lang->line('teacher_remark') ?>:</b>
                              <br>
                              <?php echo $question_value->remark; ?>
                           </p>
                           <?php
}

                ?>
                           <?php }
            ?>

                        </div>
                     </div>
                  </div>
                  <div class="hrexamtopbottom"></div>
                  <?php
$question_no++;
        }
    }
    ?>
                  <?php

    if (!$exam->publish_result) {
        ?>
                  <div class="row no-print">
                     <div class="col-xs-12">

<div class="alert alert-info">
  <?php echo $this->lang->line('result_not_published'); ?>
</div>
                     </div>
                  </div>
                  <?php
}

    ?>
                  <?php
} else {
    ?>
                  <div class="alert alert-info">
                     <?php echo $this->lang->line('exam_meassage_student'); ?>
                  </div>
                  <?php
}
?>
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