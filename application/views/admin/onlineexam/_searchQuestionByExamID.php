<style type="text/css">
.inpwidth40{width: 50px;height: 20px;}
</style>

<?php

if (!empty($questionList)) {

    foreach ($questionList as $question_key => $question_value) {
        $checkbox_status = "";
        if ($question_value->onlineexam_question_id != 0) {
            $checkbox_status = "checked";
        }

        ?>
                 <div class="">
                 <div class="row">
                    <div class="col-xs-12 col-md-12 section-box">
                        <?php if ($this->rbac->hasPrivilege('add_questions_in_exam', 'can_edit')) {?>
                         <div class="checkbox" style="margin-left: 20px"><input type="checkbox" class="question_chk" value="<?php echo $question_value->id; ?>" <?php echo $checkbox_status; ?>></div>
                     <?php }?>
                       <div class="rltpaddleft">
                      <span class="font-weight-bold"> <?php echo $this->lang->line('q_id') ?>: <?php echo $question_value->id; ?></span><br/>
                        <?php echo readmorelink($question_value->question, site_url('admin/question/read/' . $question_value->id)); ?>
                       <div class="pt5">
        <div class="row">
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div>
               <label for="email"><?php echo $this->lang->line('marks') ?>:</label>
               <input type="text" name="question_marks" value="<?php echo $question_value->onlineexam_question_marks; ?>" placeholder="question marks" class="inpwidth40">
            </div>
        </div>
         <div class="col-lg-2 col-md-6 col-sm-12">
            <div>
               <label for="email"><?php echo $this->lang->line('negative_marks') ?>:</label>
               <input type="text" name="question_neg_marks" value="<?php echo $question_value->onlineexam_question_neg_marks; ?>" placeholder="question marks" class="inpwidth40">
            </div>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-12">
        <label for="email"><?php echo $this->lang->line('question_type') ?>:</label>
        <?php echo ($question_value->question_type != "") ? $question_type[$question_value->question_type] : ""; ?>
       </div>
        <div class="col-lg-2 col-md-6 col-sm-12">
        <label for="email"><?php echo $this->lang->line('level') ?>:</label>
        <?php echo ($question_value->level != "") ? $question_level[$question_value->level] : ""; ?>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <label for="email"><?php echo $this->lang->line('subject') ?>:</label>
                <?php echo $question_value->subject_name; ?> <?php if($question_value->subject_code){ echo '('.$question_value->subject_code.')'; } ?>
        </div>
    </div><!--./row-->
</div>

                     </div>
                    </div>
                </div>
                <div class="hrexam"></div>
            </div>
    <?php
}
}
?>
</form>