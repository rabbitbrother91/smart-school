<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>backend/js/ckeditor_config.js"></script>
<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/adapters/jquery.js"></script>
<script src="<?php echo base_url() ?>backend/plugins/ckeditor/plugins/ckeditor_wiris/integration/WIRISplugins.js?viewer=image"></script>

<div class="content-wrapper">
   <!-- Main content -->
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-primary">
               <div class="box-header ptbnull">
                  <h3 class="box-title titlefix"> <?php echo $this->lang->line('online_exam'); ?></h3>
                  <div class="box-tools pull-right"></div>
               </div>
               <div class="box-body">
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
            <button class="btn btn-primary print_div btn-xs" title="<?php echo $this->lang->line("print") ?>" data-record-id="<?php echo $exam->id; ?>" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-print"></i></button>
                  <div class="aa">
                  <h4 class="text-center font-weight-bold"><?php echo $exam->exam . ($exam->is_quiz ? " (" . $this->lang->line('quiz') . ")" : " (" . $this->lang->line('exam') . ")"); ?></h4>
                      <div class="row">
                           <div class="col-lg-4 col-md-4 col-sm-12">
                              <div class="row">
                                 <dt class="col-sm-6 col-xs-12 col-md-6 col-lg-6"> <?php echo $this->lang->line('name'); ?> </dt>
                                 <dd class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $this->customlib->getFullname($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname) . " (" . $student['admission_no'] . ")" ?> </dd>
                              </div>
                           </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                              <div class="row">
                                 <dt class="col-sm-6 col-xs-12 col-md-6 col-lg-6"> <?php echo $this->lang->line('class'); ?></dt>
                                 <dd class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $student['class'] . " (" . $student['section'] . ")" ?></dd>
                              </div>
                           </div>
                           <!--lcol-lg-6-->
                           <div class="col-lg-4 col-md-4 col-sm-12">
                              <div class="row">
                                 <dt class="col-sm-6 col-xs-12 col-md-6 col-lg-6"> <?php echo $this->lang->line('father_name'); ?> </dt>
                                 <dd class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $student['father_name']; ?></dd>
                              </div>
                           </div>
                           <!--col-lg-6-->
                    </div><!--./row-->
                  <div class="row">
                     <div class="col-lg-8 col-md-8 col-sm-12">
                        <dl class="row mb10">
                           <div class="col-lg-6 col-md-6 col-sm-12">
                              <div class="row">
                                 <dt class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $this->lang->line('total_attempt'); ?></dt>
                                 <dd class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $exam->attempt; ?></dd>
                                 <dt class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $this->lang->line('exam_from'); ?></dt>
                                 <dd class="col-sm-6 col-xs-12 col-md-6 col-lg-6">
                                  <?php echo $this->customlib->dateyyyymmddToDateTimeformat($exam->exam_from, false); ?>
                                  </dd>
                                 <dt class="col-sm-6 col-xs-12 col-md-6 col-lg-6">
                                  <?php echo $this->lang->line('exam_to'); ?></dt>
                                 <dd class="col-sm-6 col-xs-12 col-md-6 col-lg-6">
                                 <?php echo $this->customlib->dateyyyymmddToDateTimeformat($exam->exam_to, false); ?>
                                  </dd>
                                  <?php
if (($exam->auto_publish_date != "0000-00-00" && $exam->auto_publish_date != "" && $exam->auto_publish_date != null)) {
        ?>
 <dt class="col-sm-6 col-xs-12 col-md-6 col-lg-6">
                                  <?php echo $this->lang->line('auto_result_publish_date'); ?> </dt>
                                 <dd class="col-sm-6 col-xs-12 col-md-6 col-lg-6">
                                 <?php echo $this->customlib->dateyyyymmddToDateTimeformat($exam->auto_publish_date, false); ?>
                                  </dd>
  <?php
}
    ?>
                                 <dt class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $this->lang->line('duration') ?></dt>
                                 <dd class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $exam->duration; ?></dd>
                                   <dt class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $this->lang->line('answer_word_limit') ?></dt>
                                 <dd class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo ($exam->answer_word_count == "-1") ? $this->lang->line('no_limit') : $exam->answer_word_count; ?></dd>
                                 <dt class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $this->lang->line('passing') ?>   (%)</dt>
                                 <dd class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $exam->passing_percentage; ?></dd>
                              </div>
                           </div>
                           <!--lcol-lg-6-->
                           <div class="col-lg-6 col-md-6 col-sm-12">
                              <div class="row">
                                 <dt class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $this->lang->line('total_questions') ?></dt>
                                 <dd class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $total_question; ?></dd>
                                 <dt class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $this->lang->line('descriptive_questions') ?></dt>
                                 <dd class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $exam_total_descriptive; ?></dd>
                                 <?php
if ($exam->publish_result) {
        ?>
                                 <dt class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $this->lang->line('correct_answer') ?></dt>
                                 <dd class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $correct_ans; ?></dd>
                                 <dt class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $this->lang->line('wrong_answer'); ?></dt>
                                 <dd class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $wrong_ans; ?></dd>
                                 <dt class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $this->lang->line('not_attempted'); ?></dt>
                                 <dd class="col-sm-6 col-xs-12 col-md-6 col-lg-6"><?php echo $not_attempted; ?></dd>
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
                           <li><i class="fa fa-check-circle-o text-success"></i><?php echo $this->lang->line('correct_answer') ?></li>
                           <li><i class="fa fa-dot-circle-o text-success"></i><?php echo $this->lang->line('correct_answer_but_not_attempted') ?></li>
                           <li><i class="fa fa-times-circle-o text-danger"></i><?php echo $this->lang->line('wrong_answer') ?></li>
                        </ul>
                     </div>
                  </div>
                  </div>
                   <div class="row pb10">
                     <div class="col-lg-12 col-md-12">
                    <span class="font-weight-bold"><?php echo $this->lang->line('description') ?>: </span> <?php echo $exam->description; ?>
                     </div>
                     </div>
                  <?php
if ($exam->publish_result) {
        if (!$online_exam_validate->is_attempted) {
            ?>
  <div class="alert alert-info">
    <?php echo $this->lang->line('exam_not_submitted'); ?>
  </div>
  <?php
}
        ?>
  <div class="hrexamfirstrow"></div>
                  <div class="row">

                           <div class="col-sm-6 col-xs-12 col-md-3 col-lg-3"><span class="font-weight-bold"><?php echo $this->lang->line('total_exam_marks') ?>: </span> <?php echo $exam_total_marks; ?></div>

                     <?php
if ($dispaly_negative_marks) {
            ?>
  <div class="col-sm-6 col-xs-12 col-md-3 col-lg-3"><span class="font-weight-bold"><?php echo $this->lang->line('total_negative_marks') ?>: </span> <?php echo $exam_total_neg_marks; ?></div>
  <?php
}
        ?>
                       <div class="col-sm-6 col-xs-12 col-md-3 col-lg-3"><span class="font-weight-bold"><?php echo $this->lang->line('total_scored_marks') ?>: </span> <?php echo $exam_total_scored - $exam_total_neg_marks; ?></div>

                     <div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">
                      <span class="font-weight-bold"><?php echo $this->lang->line('score'); ?> (%):</span> <?php echo ($exam_total_marks === 0) ? 0 : number_format(((($exam_total_scored - $exam_total_neg_marks) * 100) / $exam_total_marks), 2, '.', '');
        ?>
                      </div>
                           <div class="col-sm-6 col-xs-12 col-md-3 col-lg-3">
<?php
if (!$exam->is_quiz) {
            ?>
               <span class="font-weight-bold"><?php echo $this->lang->line('exam_rank') ?>: </span>
                               <?php
if ($exam->is_rank_generated && $exam->publish_result) {
                echo ($online_exam_validate->rank);

            } else {
                ?><?php echo $this->lang->line('awaited') ?><?php
}
            ?>
  <?php
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
            $marks_json    = getMarks($question_value);
            $marks_array   = (json_decode($marks_json));
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
                              <span class="font-weight-bold"><?php echo $this->lang->line('q') ?><?php echo $question_no; ?> </span><?php echo $question_value->question; ?>
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
                              <?php echo $question_value->subject_name ; ?> <?php if($question_value->subject_code){ echo ' ('.$question_value->subject_code.')' ; } ?>
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
                        if ($question_value->{$question_opt_key} == "") {
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
                              <b><?php echo $this->lang->line('your_answer'); ?>: </b><br>
                              <?php echo $question_value->select_option; ?>
                           </p>

<?php if ($question_value->attachment_name != "") {
                    ?>

<div class="font-weight-bold"> <?php echo $this->lang->line('attachment') ?>: <a href="<?php echo site_url('user/onlineexam/downloadattachment/' . $question_value->attachment_upload_name); ?>"><?php echo $question_value->attachment_name; ?> <i class="fa fa-download"></i></a></div>
  <?php
}
                ?>
                           <?php
if ($question_value->remark != "") {
                    ?>
                           <p>
                              <b><?php echo $this->lang->line('teacher_remark'); ?>: </b>
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
if (!$online_exam_validate->is_attempted && ($exam->is_active) && !($exam->publish_result) && $role == 'student' && strtotime(date('Y-m-d H:i:s')) >= strtotime(date($exam->exam_from)) && strtotime(date('Y-m-d H:i:s')) <= strtotime(date($exam->exam_to))) {
        ?>
                  <div class="row no-print">
                     <div class="col-xs-12">
                        <button type="button" class="btn btn-info questions" data-recordid="<?php echo $exam->id; ?>"  data-loading-text="<i class='fa fa-spinner fa-spin'></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-bullhorn"></i> <?php echo $this->lang->line('start_exam') ?></button>
                     </div>
                  </div>
                  <?php
}

    if ($online_exam_validate->is_attempted && !$exam->publish_result) {
        ?>
                  <div class="row no-print">
                     <div class="col-xs-12">
                     <div class="alert alert-info">
                       <?php echo $this->lang->line('you_have_submitted_the_exam'); ?>.
                     </div>
                     </div>
                  </div>
                  <?php
}

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
   </section>
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
<!-- Modal -->
<div class="questionmodal">
   <form id="regiration_form"  action="<?php echo site_url('user/onlineexam/save') ?>"  enctype="multipart/form-data" method="post">
      <div id="onlineexample" class="modal fade" role="dialog">
         <div class="modal-dialog modal-dialogfullwidth">
            <!-- Modal content-->
            <div class="modal-content modal-contentfull">
               <div class="modal-header">
                  <button type="button" class="close questionclose" data-dismiss="modal">&times;</button>
                  <div class="questionlogo"><img src="<?php echo $this->customlib->getBaseUrl(); ?>uploads/school_content/admin_logo/<?php echo $this->setting_model->getAdminlogo();?>" alt="<?php echo $this->customlib->getAppName() ?>" /></div>
               </div>
               <div class="exambgtop">
                  <h3><?php echo $exam->exam; ?></h3>
                  <div class="exambgright">
                    <div class="timeclock">
                     <i class="fa fa-clock-o"></i><div id="box_header" class="inlineblock valign-middle"></div>
                    </div>
                     <button type="button" class="btn btn-info btn-sm save_exam_btn"><?php echo $this->lang->line('submit') ?> </button>
                  </div>
               </div>
               <!-- ./exambgtop -->
               <div class="modal-body">
                <span id='spanFileName'></span>
                  <div class="row question_container">
                  </div>
                  <!--./row-->
               </div>
               <!--./modal-body-->
            </div>
            <!--./modal-content-->
         </div>
         <!--./modal-dialog-->
         <div class="quizfooter">
           <input type="button" name="next" class="next qbtn-previous" value="<?php echo $this->lang->line('previous'); ?>" style="display: none;">
            <input type="button" name="next" class="next qbtn-next" value="<?php echo $this->lang->line('next'); ?>">
         </div>
         <!-- ./quizfooter -->
      </div>
      <!--./-->
   </form>
</div>
<!-- questionmodal -->
<div id="saveModal" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?php echo $this->lang->line('confirm_save'); ?></h4>
         </div>
         <div class="modal-body">
            <p><?php echo $this->lang->line('are_you_sure_you_want_to_submit_this_exam') ?></p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-success btn-ok" data-loading-text="<i class='fa fa-spinner fa-spin'></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save'); ?></button>
         </div>
      </div>
   </div>
</div>

<script type="text/javascript">
  var word_count_limit="<?php echo $exam->answer_word_count ?>";
  var allowed_mime_type=<?php echo json_encode($allowed_mime_type) ?>;
  var allowed_extension=<?php echo json_encode($allowed_extension) ?>;
  var allowed_upload_size='<?php echo $allowed_upload_size; ?>';

   $('#saveModal').on('click', '.btn-ok', function (e) {
       $("#regiration_form").submit();
       var $this = $(this);
       $this.button('loading');
       setTimeout(function () {
           $this.button('reset');
       }, 800000);


   });

   $("#regiration_form").submit(function () {
       // submit more than once return false
       $(this).submit(function () {
           return false;
       });
       // submit once return true
       return true;
   });


   $(document).ready(function () {
       var current = 1, current_step, next_step, steps, elapsed_seconds;
       steps = 0;
       elapsed_seconds = 0;
       var timer2 = "00:00:00";
       $(document).on('click', '.qbtn-next', function () {

           if ($("div.question_list").find("fieldset:visible").next().is(":last-child"))
           {
               $('.qbtn-next').toggle();
           }

           current_step = $("div.question_list").find("fieldset:visible");
           next_step = $("div.question_list").find("fieldset:visible").next();

           next_step.show();
           current_step.hide();

           if ($("div.question_list").find("fieldset:visible").prev().length) {
               $('.qbtn-previous').show();
           }

           if ($("div.question_list").find("fieldset:visible").next().length) {
               $('.qbtn-next').show();
           }

           activeQuestionButton();

       });

       $(document).on('click', '.qbtn-previous', function () {

           if ($("div.question_list").find("fieldset:visible").prev().is(":first-child"))
           {
               $('.qbtn-previous').hide();
           }
           current_step = $("div.question_list").find("fieldset:visible");
           next_step = $("div.question_list").find("fieldset:visible").prev();
           next_step.show();
           current_step.hide();

           if ($("div.question_list").find("fieldset:visible").prev().length) {
               $('.qbtn-previous').show();
           }
           if ($("div.question_list").find("fieldset:visible").next().length) {
               $('.qbtn-next').show();
           }
           activeQuestionButton();
       });
   });

   function activeQuestionButton() {
       var qu = $("div.question_list").find("fieldset:visible").attr('id');
       var qustion_n = qu.split("question_");
       var sss = $("button[data-qustion_no='" + qustion_n[1] + "']");
       sss.addClass("activeqbtn");
       $("button.question_switcher").not(sss).removeClass('activeqbtn');
   }
</script>
<script type="text/javascript">
   $(document).on('click', '.question_switcher', function () {
       var question_no = $(this).data('qustion_no');
       var btn = $(this).addClass("activeqbtn");

       $("button.question_switcher").not(btn).removeClass('activeqbtn');

       var $this = $("div.question_list").find("fieldset#question_" + question_no);
       $("div.question_list").find("fieldset").not($this).hide();
       $this.show();

       if ($("div.question_list").find("fieldset:visible").is(":first-child"))
       {
           $('.qbtn-previous').hide();
       }

       if ($("div.question_list").find("fieldset:visible").is(":last-child"))
       {
           $('.qbtn-next').hide();
       }

       if ($("div.question_list").find("fieldset:visible").prev().length) {
           $('.qbtn-previous').show();
       }

       if ($("div.question_list").find("fieldset:visible").next().length) {
           $('.qbtn-next').show();
       }

   });

   $(document).on('click', '.questions', function () {
       elapsed_seconds = 0;
       var $this = $(this);
       var recordid = $this.data('recordid');
       $('input[name=recordid]').val(recordid);

       $.ajax({
           type: 'POST',
           url: baseurl + "user/onlineexam/getExamForm",
           data: {'recordid': recordid},
           dataType: 'JSON',
           beforeSend: function () {
               $this.button('loading');
               clearInterval(interval);
           },
           success: function (data) {
               if (data.question_status == 0) {
                   if(data.total_question <= 1){
                    $('.qbtn-next').css('display','none');
                   }

                   $('#box_header').html(data.duration);

                   timer2 = data.duration;
                   timer();
                $('.question_container').html(data.page);
                $('.question_container').find('.filestyle').dropify();
                  CKEDITOR.env.isCompatible = true;
                 $('[class*="ckeditor"]').ckeditor({
                     toolbar: 'Evalution',
                     allowedContent : true,
                     extraPlugins: 'ckeditor_wiris,wordcount,notification',
                     enterMode : CKEDITOR.ENTER_BR,
                     shiftEnterMode: CKEDITOR.ENTER_P,
                     customConfig: baseurl+'/backend/js/ckeditor_config.js',
                    wordcount : {

    // Whether or not you Show Remaining Count (if Maximum Word/Char/Paragraphs Count is set)
    showRemaining: false,

    // Whether or not you want to show the Paragraphs Count
    showParagraphs: false,

    // Whether or not you want to show the Word Count
    showWordCount: true,

    // Whether or not you want to show the Char Count
    showCharCount: false,

    // Whether or not you want to Count Bytes as Characters (needed for Multibyte languages such as Korean and Chinese)
    countBytesAsChars: false,

    // Whether or not you want to count Spaces as Chars
    countSpacesAsChars: false,

    // Whether or not to include Html chars in the Char Count
    countHTML: false,

    // Whether or not to include Line Breaks in the Char Count
    countLineBreaks: false,

    // Whether or not to prevent entering new Content when limit is reached.
    hardLimit: true,

    // Whether or not to to Warn only When limit is reached. Otherwise content above the limit will be deleted on paste or entering
    warnOnLimitOnly: false,

    // Maximum allowed Word Count, -1 is default for unlimited
    maxWordCount: word_count_limit,

    // Maximum allowed Char Count, -1 is default for unlimited
    maxCharCount: -1,

    // Maximum allowed Paragraphs Count, -1 is default for unlimited
    maxParagraphs: -1,

    // How long to show the 'paste' warning, 0 is default for not auto-closing the notification
    pasteWarningDuration: 0,

    // Add filter to add or remove element before counting (see CKEDITOR.htmlParser.filter), Default value : null (no filter)
    filter: new CKEDITOR.htmlParser.filter({
        elements: {
            div: function( element ) {
                if(element.attributes.class == 'mediaembed') {
                    return false;
                }
            }
        }
    }),

}
                });

                   $('#onlineexample').modal({
                       show: true,
                       backdrop: 'static',
                       keyboard: false
                   });
               } else {
                   errorMsg(data.page);
               }
               $this.button('reset');
           },
           error: function (xhr) { // if error occured
               alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
               $this.button('reset');
           },
           complete: function () {
               $this.button('reset');
           }
       });
   });
</script>
<script type="text/javascript">
   var interval;
   var timer = function () {
       interval = setInterval(function () {
           $('#box_header').text(get_elapsed_time_string());
       }, 1000);
   };

   function get_elapsed_time_string() {
       function pretty_time_string(num) {
           return (num < 10 ? "0" : "") + num;
       }

       timer111 = timer2.split(':');
       var hours = parseInt(timer111[0], 10);
       var minutes = parseInt(timer111[1], 10);
       var seconds = parseInt(timer111[2], 10);
       --seconds;
       minutes = (seconds < 0) ? --minutes : minutes;
       seconds = (seconds < 0) ? 59 : seconds;
       hours = (minutes < 0) ? --hours : hours;
       minutes = (minutes < 0) ? 59 : minutes;

       hours = pretty_time_string(hours);
       minutes = pretty_time_string(minutes);
       seconds = pretty_time_string(seconds);

       if (hours < 0)
           clearInterval(interval);

       if ((seconds <= 0) && (minutes <= 0) && (hours <= 0)) {
           clearInterval(interval);
           $("#regiration_form").submit();
       }

       timer2 = hours + ":" + minutes + ":" + seconds;
       var currentTimeString = hours + ":" + minutes + ":" + seconds;
       return currentTimeString;
   }

   $('.save_exam_btn').click(function () {
       $('#saveModal').modal({
           show: true,
           backdrop: 'static',
           keyboard: false
       })
   });
</script>
<script type="text/javascript">
   var base_url = '<?php echo base_url() ?>';
   function Popup(data)
   {
       var frame1 = $('<iframe />');
       frame1[0].name = "frame1";
       frame1.css({"position": "absolute", "top": "-1000000px"});
       $("body").append(frame1);
       var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
       frameDoc.document.open();
       //Create a new HTML document.
       frameDoc.document.write('<html>');
       frameDoc.document.write('<head>');
       frameDoc.document.write('<title></title>');
       frameDoc.document.write('<link rel=\"stylesheet\" href=\"' + base_url + 'backend/dist/css/font-awesome.min.css\" type=\"text/css\" media=\"all\" > ' );
       frameDoc.document.write('</head>');
       frameDoc.document.write('<body>');
       frameDoc.document.write(data);
       frameDoc.document.write('</body>');
       frameDoc.document.write('</html>');
       frameDoc.document.close();
       setTimeout(function () {
           window.frames["frame1"].focus();
           window.frames["frame1"].print();
           frame1.remove();
       }, 500);

       return true;
   }

</script>
<script type="text/javascript">

    $(document).on('click', '.print_div', function () {
        var $this = $(this);
        $this.button('loading');
        var id = $this.data('recordId');
        $.ajax(
                {
                    url: "<?php echo site_url('user/onlineexam/print') ?>",
                    type: "POST",
                    data: {'exam_id': id},
                    dataType: 'Json',
                    success: function (data, textStatus, jqXHR)
                    {
                        console.log(data.page);
                         Popup(data.page);
                        $this.button('reset');
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        $this.button('reset');
                    }
                });
    });

    $(document).on('change','.exam_attachment',function(){
      var files = $(this).get(0).files;
      var file = files[0];
        if (($.inArray(file.name.split('.').pop().toLowerCase(), allowed_extension) == -1) || ($.inArray(file.type,allowed_mime_type) == -1) || (allowed_upload_size <= file.size) ) {
         errorMsg("<?php echo $this->lang->line('invalid_file_format_or_size'); ?>");
         $(this).parent().find(".dropify-clear").trigger('click');
        }
        else {

        }
    });
</script>