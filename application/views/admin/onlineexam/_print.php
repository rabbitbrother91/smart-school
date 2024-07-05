<html lang="en">
    <head>
        <title><?php echo $this->lang->line('fees_receipt'); ?></title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <style type="text/css">

@media print {

  body{line-height: 24px;font-family:'Roboto', arial;}
  .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
    float: left;
  }
  .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
    position: relative;
    min-height: 1px;
    padding-right: 5px;
    padding-left: 5px;
}
  .col-md-12 {
    width: 100%;
  }
  .col-md-11 {
    width: 91.66666667%;
  }
  .col-md-10 {
    width: 83.33333333%;
  }
  .col-md-9 {
    width: 75%;
  }
  .col-md-8 {
    width: 66.66666667%;
  }
  .col-md-7 {
    width: 58.33333333%;
  }
  .col-md-6 {
    width: 50%;
  }
  .col-md-5 {
    width: 41.66666667%;
  }
  .col-md-4 {
    width: 33.33333333%;
  }
  .col-md-3 {
    width: 25%;
  }
  .col-md-2 {
    width: 16.66666667%;
  }
  .col-md-1 {
    width: 8.33333333%;
  }

  .clear{clear: both;}
  .row {
    margin-right: -5px;
    margin-left: -5px;
}
  .pb10{padding-bottom: 10px;}
  .mb10{margin-bottom: 10px !important;}
  .hrexam {margin-top: 5px;margin-bottom: 5px;border: 0;border-top: 1px solid #eee; width: 100%; clear: both;}
  .qulist_circle{margin: 0; padding: 0; list-style: none;}
  .qulist_circle li{display: block;}
  .qulist_circle li i{padding-right: 5px;}
  .font-weight-bold{font-weight: bold;}
  .text-center{text-align: center;}
  .section-box i {
    font-size: 18px;
    vertical-align: middle; padding-left: 2px;
}
}

</style>
    </head>
    <body>
     <div class="row">
         <div class="col-md-12">
            <div class="box box-primary">
               <div class="box-header ptbnull">
                  <h3 class="titlefix"> <?php echo $this->lang->line('online_exam'); ?></h3>
               </div>
               <div class="">

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
                 <h4 class="text-center font-weight-bold"><?php echo $exam->exam; ?></h4>
                  <div class="row ">
                     <div class="col-md-4">
                                <div><span class="font-weight-bold"><?php echo $this->lang->line('student_name'); ?> : </span>
                                 <?php echo $student_name; ?>
                                </div>

                                <div><span class="font-weight-bold"><?php echo $this->lang->line('class'); ?> : </span>
                                 <?php echo $class; ?>
                                </div>

                                <div><span class="font-weight-bold"><?php echo $this->lang->line('section'); ?> : </span>
                                 <?php echo $section; ?>
                                </div>

                                <div><span class="font-weight-bold"><?php echo $this->lang->line('father_name'); ?> : </span>
                                 <?php echo $father_name; ?>
                                </div>

                                 <div><span class="font-weight-bold"><?php echo $this->lang->line('total_attempt'); ?> : </span>
                                 <?php echo $exam->attempt; ?></div>
                                 <div><span class="font-weight-bold"><?php echo $this->lang->line('exam_from'); ?> : </span>

                                  <?php echo $this->customlib->dateyyyymmddToDateTimeformat($exam->exam_from, false); ?>
                                  </div>
                                 <div>
                                  <span class="font-weight-bold"><?php echo $this->lang->line('exam_to'); ?> : </span>

                                 <?php echo $this->customlib->dateyyyymmddToDateTimeformat($exam->exam_to, false); ?>

                                  </div>
                                 <div><span class="font-weight-bold"><?php echo $this->lang->line('duration') ?> : </span>
                                 <?php echo $exam->duration; ?></div>
                                 <div><span class="font-weight-bold"><?php echo $this->lang->line('passing') ?>   (%) : </span>
                                 <?php echo $exam->passing_percentage; ?></div>
                              </div>
   <div class="col-md-4">
                              <div><span class="font-weight-bold"><?php echo $this->lang->line('total_questions'); ?> : </span> <?php echo $total_question; ?></div>
                              <div><span class="font-weight-bold"> <?php echo $this->lang->line('descriptive_questions'); ?>: </span> <?php echo $exam_total_descriptive; ?></div>
                                 <?php
if ($exam->publish_result) {
        ?>
                              <div><span class="font-weight-bold"><?php echo $this->lang->line('correct_answer'); ?> : </span> <?php echo $correct_ans; ?></div>
                              <div><span class="font-weight-bold"><?php echo $this->lang->line('wrong_answer'); ?> : </span> <?php echo $wrong_ans; ?></div>
                              <div><span class="font-weight-bold"><?php echo $this->lang->line('not_attempted'); ?> : </span> <?php echo $not_attempted; ?></div>

                               <?php
}
    ?>
                              </div>
                       <div class="col-md-4">
                           <ul class="qulist_circle">
                           <li><i class="fa fa-check-circle-o text-success"></i> <?php echo $this->lang->line('correct_answer'); ?></li>
                           <li><i class="fa fa-dot-circle-o text-success"></i> <?php echo $this->lang->line('correct_answer_but_not_attempted'); ?></li>
                           <li><i class="fa fa-times-circle-o text-danger"></i> <?php echo $this->lang->line('wrong_answer'); ?></li>
                        </ul>
                     </div>
                  </div>

                  <?php
if ($exam->publish_result) {
        if (!$online_exam_validate->is_attempted) {
            ?>
  <div class="row clear">
    <?php echo $this->lang->line('exam_not_submitted') ?>
  </div>
  <?php
}
        ?>
                  <div class="row clear">
                      <div class="col-md-12"><div class="hrexam"></div></div>
                     <div class="col-md-4"><span class="font-weight-bold"> <?php echo $this->lang->line('total_exam_marks'); ?>:</span> <?php echo $exam_total_marks; ?></div>

                     <?php
if ($dispaly_negative_marks) {
            ?>
  <div class="col-md-4"><span class="font-weight-bold"> <?php echo $this->lang->line('total_negative_marks') ?>:</span> <?php echo $exam_total_neg_marks; ?></div>
  <?php
}
        ?>
                       <div class="col-md-4"><span class="font-weight-bold"> <?php echo $this->lang->line('total_scored_marks') ?>:</span> <?php echo $exam_total_scored - $exam_total_neg_marks; ?></div>

                     <div class="col-md-4"><span class="font-weight-bold"><?php echo $this->lang->line('score'); ?> (%):</span> <?php
echo ($exam_total_marks === 0) ? 0 : number_format(((($exam_total_scored - $exam_total_neg_marks) * 100) / $exam_total_marks), 2, '.', '');
        ?></div>
                        <div class="col-md-4"><span class="font-weight-bold"><?php echo $this->lang->line('exam_rank'); ?></span>
 <?php
if ($exam->is_rank_generated && $exam->publish_result) {
            echo ($online_exam_validate->rank);
        } else {
            ?><?php echo $this->lang->line('awaited') ?><?php
}
        ?>
                         </div>
                   <div class="col-md-12"><div class="hrexam"></div></div>
                  </div>
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
                  <div class="row clear">
                     <div class="col-xs-12 col-md-12 section-box">
                        <div>
                           <p>
                              <span class="font-weight-bold">Q.<?php echo $question_no; ?> </span><?php echo $question_value->question; ?>
                               <span class="text text-danger">
                                 <?php echo $this->lang->line('marks') ?>:(<?php echo $marks_array->scr_marks . "/" . $marks_array->get_marks ?>)
                                <?php
if ($dispaly_negative_marks && $question_value->question_type != "descriptive") {

                ?>
                                <?php echo $this->lang->line('negative_marks') ?>:(<?php echo $question_value->neg_marks; ?>)
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
                        if ($question_value->{$question_opt_key} == "") {
                            $question_display = false;
                        }
                        if ($question_display) {
                            if (($question_value->correct == $question_opt_key) && $question_value->select_option == null) {
                                $cls     = "text text-success";
                                $fa_icon = "fa fa-check-circle-o";
                            } elseif (($question_value->correct == $question_opt_key)) {
                                $cls     = "text text-success";
                                $fa_icon = "fa fa-check-circle";
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
                            $fa_icon = "fa fa-check-circle-o";
                        } elseif (($question_value->correct == $question_true_false_key)) {
                            $cls     = "text text-success";
                            $fa_icon = "fa fa-check-circle";
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
                        if ($question_value->{$question_opt_key} == "") {
                            $question_display = false;
                        }
                        if ($question_display) {
                            $correct_answer = json_decode($question_value->correct);

                            $selected_answer = isJSON($question_value->select_option) ? json_decode($question_value->select_option) : array();

                            if (in_array($question_opt_key, $correct_answer) && !in_array($question_opt_key, $selected_answer)) {
                                $cls     = "text text-success";
                                $fa_icon = "fa fa-check-circle-o";
                            } elseif (in_array($question_opt_key, $correct_answer)) {
                                $cls     = "text text-success";
                                $fa_icon = "fa fa-check-circle";
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
                              <b> <?php echo $this->lang->line('your_answer') ?>: </b><br>
                              <?php echo $question_value->select_option; ?>
                           </p>
                           <?php
if ($question_value->remark != "") {
                    ?>
                           <p>
                              <b><?php echo $this->lang->line('your_answer') ?>: </b>
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
                  <?php
$question_no++;
        }
    }
    ?>
                  <?php
if (empty($result_prepare) && ($exam->is_active) && !($exam->publish_result) && strtotime(date('Y-m-d H:i:s')) >= strtotime(date($exam->exam_from)) && strtotime(date('Y-m-d H:i:s')) < strtotime(date($exam->exam_to))) {
        ?>
                  <div class="row no-print clear">
                     <div class="col-xs-12">
                        <button type="button" class="btn btn-info questions" data-recordid="<?php echo $exam->id; ?>"  data-loading-text="<i class='fa fa-spinner fa-spin'></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-bullhorn"></i> <?php echo $this->lang->line('start_exam'); ?></button>
                     </div>
                  </div>
                  <?php
}

    if (!$exam->publish_result) {
        ?>
                  <div class="row no-print clear">
                     <div class="col-xs-12">

<div class="alert alert-info">
<?php echo $this->lang->line('result_not_published') ?>.
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
               </div>
            </div>
         </div>
      </div>
            <div class="clearfix"></div>
    </body>
</html>

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