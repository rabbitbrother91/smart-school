<script src="<?php echo base_url() ?>backend/plugins/ckeditor/plugins/ckeditor_wiris/integration/WIRISplugins.js?viewer=image"></script>
    <?php
if ($question_status == 0) {
    if (empty($questions)) {
        ?>
        <div class="alert alter-info"><?php echo $this->lang->line('no_question_found_please_contact_to_administrator'); ?></div>
        <?php
} else {
        ?>
        <div class="col-md-9 col-sm-9">
            <input type="hidden" name="onlineexam_student_id" value="<?php echo $onlineexam_student_id->id; ?>">
            <input type="hidden" name="is_quiz" value="<?php echo $exam->is_quiz; ?>">

            <div class="question_list">
                <?php

        $counter = 1;
        foreach ($questions as $question_key => $question_value) {

            ?>
                    <fieldset id="question_<?php echo $counter; ?>">
<input type="hidden" name="total_rows[]" value="<?php echo $counter; ?>">
<input type="hidden" name="question_id_<?php echo $counter; ?>" value="<?php echo $question_value->id; ?>">
<input type="hidden" name="question_type_<?php echo $counter; ?>" value="<?php echo $question_value->question_type; ?>">
            <div class="row">
                <div class="col-md-4 col-sm-12">
                   <h3 class="mt0">Question:<?php echo $counter; ?></h3>
                </div>
                <div class="col-md-8 col-sm-12">
                    <div class="text-right">
                         <?php

            if ($exam->is_marks_display && $exam->is_neg_marking) {
                ?>
                            <span class="ques_marks text text-danger valign-sub">(<?php echo $this->lang->line('marks'); ?>: <?php echo $question_value->marks ?>, <?php echo $this->lang->line('negative_marks') ?>: <?php echo $question_value->neg_marks ?>)</span>
                            <?php
} elseif ($exam->is_marks_display && !$exam->is_neg_marking) {
                ?>
                            <span class="ques_marks text text-danger valign-sub">(<?php echo $this->lang->line('marks'); ?>: <?php echo $question_value->marks ?>)</span>
                            <?php
} elseif (!$exam->is_marks_display && $exam->is_neg_marking) {
                ?>
                            <span class="ques_marks text text-danger valign-sub">(<?php echo $this->lang->line('negative_marks') ?>: <?php echo $question_value->neg_marks ?>)</span>

                            <?php
}
            ?>
                    </div>
                  </div>
                </div>

                        <div class="quizscroll">
                            <?php echo $question_value->question; ?>
                            <div class="radiocontainer">
                                <?php
$question_total_option = 1;
            $question_display      = true;

            if ($question_value->question_type == "singlechoice" || $question_value->question_type == "") {
                foreach ($questionOpt as $question_opt_key => $question_opt_value) {
                    if ($question_value->{$question_opt_key} == "") {
                        $question_display = false;
                    }
                    if ($question_display) {
                        ?>
                                        <label>
                                            <input type="radio" name="radio<?php echo $counter; ?>" value="<?php echo $question_opt_key; ?>" autocomplete="off"><?php echo $question_value->{$question_opt_key}; ?>
                                        </label>

                                        <?php
}
                    $question_total_option++;
                }
            } elseif ($question_value->question_type == "true_false") {
                ?>
                                     <label>
                                            <input type="radio" name="radio<?php echo $counter; ?>" value="true" autocomplete="off"><?php echo $this->lang->line('true'); ?>
                                        </label>
                                          <label>
                                            <input type="radio" name="radio<?php echo $counter; ?>" value="false" autocomplete="off"><?php echo $this->lang->line('false'); ?>
                                        </label>
                         <?php
} elseif ($question_value->question_type == "multichoice") {
                foreach ($questionOpt as $question_opt_key => $question_opt_value) {
                    if ($question_value->{$question_opt_key} == "") {
                        $question_display = false;
                    }
                    if ($question_display) {
                        ?>
                                        <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="checkbox<?php echo $counter; ?>[]" value="<?php echo $question_opt_key; ?>" autocomplete="off"><?php echo $question_value->{$question_opt_key}; ?>
                                        </label>
                                        </div>
                                        <?php
}
                    $question_total_option++;
                }
            } elseif ($question_value->question_type == "descriptive") {
                ?>
                            <div class="form-group">
    <span for="attachment<?php echo $counter; ?>"><?php echo $this->lang->line('attachment'); ?></span>
    <input type="file" class="filestyle form-control exam_attachment" name="attachment<?php echo $counter; ?>" id="attachment<?php echo $counter; ?>">
  </div>
  <!--  -->
                            <div class="form-group">
                              <span for="answer"><?php echo $this->lang->line('answer') ?>: </span>
                              <textarea class="form-control ckeditor" rows="20" id="answer_<?php echo $counter; ?>" name="answer<?php echo $counter; ?>" ></textarea>
                            </div>
                            <?php
}
            ?>
                            </div> <!-- ./radiocontainer -->
                        </div><!--./quizscroll-->
                    </fieldset>
                    <?php
$counter++;
        }
        ?>
            </div>

        </div><!-- ./col-md-12-->
        <div class="col-md-3 col-sm-3">
            <div class="quizscroll">
                <h3 class="mt0"> <?php echo $this->lang->line('question_map'); ?></h3>

                <?php
$question_counter = 1;
        foreach ($questions as $question_key => $question_value) {
            ?>
                    <button type="button" class="btn btn-default question_switcher <?php echo ($question_counter == 1) ? "activeqbtn" : "" ?>" data-qustion_no="<?php echo $question_counter; ?>"><?php echo $question_counter; ?></button>
                    <?php
$question_counter++;
        }
        ?>
            </div><!--./quizscroll-->
        </div><!--./col-md-4-->
        <?php
}
} else {
    ?>
    <?php echo $this->lang->line('you_have_reached_total_attemps_or_exam_date_passed_please_contact_to_administrator'); ?>
    <?php
}
?>