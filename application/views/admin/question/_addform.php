  <input type="hidden" name="recordid" value="<?php echo $recordid; ?>">
 <div class="row">
                    <div class="form-group col-md-6">
                        <label for="subject_id"><?php echo $this->lang->line('subject') ?></label><small class="req"> *</small>

                        <select class="form-control" name="subject_id">
                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                            <?php
foreach ($subjectlist as $subject_key => $subject_value) {
    ?>
                                <option value="<?php echo $subject_value['id']; ?>"><?php echo $subject_value['name']; ?> <?php if($subject_value['code']){ echo '('.$subject_value['code'].')'; } ?>  </option>
                                <?php
}
?>
                        </select>
                        <span class="text text-danger subject_id_error"></span>
                    </div>

                      <div class="form-group col-md-3">
                        <label for="question_type"><?php echo $this->lang->line('question_type'); ?></label><small class="req"> *</small>

                        <select class="form-control" name="question_type" id="question_type">
                      <option value=""><?php echo $this->lang->line('select'); ?></option>
                            <?php
foreach ($question_type as $question_type_key => $question_type_value) {
    ?>
    <option value="<?php echo $question_type_key; ?>"><?php echo $question_type_value; ?></option>
                                <?php
}
?>
                        </select>
                        <span class="text text-danger question_type_error"></span>
                    </div>
                      <div class="form-group col-md-3">
                        <label for="question_level"><?php echo $this->lang->line('question_level'); ?></label><small class="req"> *</small>

                        <select class="form-control" name="question_level">
                       <option value=""><?php echo $this->lang->line('select'); ?></option>
                            <?php
foreach ($question_level as $question_level_key => $question_level_value) {
    ?>
    <option value="<?php echo $question_level_key; ?>"><?php echo $question_level_value; ?></option>
                                <?php
}
?>
                        </select>
                        <span class="text text-danger question_level_error"></span>
                    </div>
                    </div>
                   <div class="row">
                          <div class="form-group col-md-6">
                        <label for="class_id"><?php echo $this->lang->line('class') ?></label><small class="req"> *</small>

                        <select class="form-control" name="class_id" id="class_id">
                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                            <?php
foreach ($classList as $class_key => $class_value) {
    ?>
    <option value="<?php echo $class_value['id']; ?>"><?php echo $class_value['class']; ?></option>
                                <?php
}
?>
                        </select>
                        <span class="text text-danger class_id_error"></span>
                    </div>

                 <div class="form-group col-md-6">
                     <label for="section_id"><?php echo $this->lang->line('section'); ?></label>
                     <select  id="section_id" name="section_id" class="form-control" >
                         <option value=""><?php echo $this->lang->line('select'); ?></option>
                    </select>
                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                </div>
                   </div>

                    <div class="form-group">
                        <label><?php echo $this->lang->line('question') ?></label><small class="req"> *</small>
                        <button class="btn btn-primary pull-right btn-xs" type="button" id="question" data-toggle="modal" data-location="question" data-target="#myimgModal"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_image'); ?></button>
                            <textarea class="form-control ckeditor" id="question_textbox" name="question"></textarea>
                            <span class="text text-danger question_error"></span>
                    </div>

                    <div class="option_list">

                    <?php
foreach ($questionOpt as $question_opt_key => $question_opt_value) {
    ?>
                        <div class="form-group">
                            <label for="<?php echo $question_opt_key; ?>"><?php echo $this->lang->line('option_' . $question_opt_value); ?><?php if ($question_opt_value != 'E' && $question_opt_value != 'D' && $question_opt_value != 'C') {?><small class="req"> *</small><?php }?></label>

   <button class="btn btn-primary pull-right btn-xs" type="button" data-location="<?php echo $question_opt_key; ?>" id="<?php echo $question_opt_key; ?>" data-toggle="modal" data-target="#myimgModal"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_image'); ?></button>

                            <textarea class="form-control ckeditor" name="<?php echo $question_opt_key; ?>" id="<?php echo $question_opt_key . "_textbox"; ?>" onkeypress="maxLength()"></textarea>
                            <span class="text text-danger <?php echo $question_opt_key; ?>_error"></span>
                        </div>
                        <?php
}
?>
   </div>
                    <div class="form-group ans">
                        <label for="subject_id"><?php echo $this->lang->line('answer') ?></label><small class="req"> *</small>
                        <select class="form-control" name="correct">
                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                            <?php
foreach ($questionOpt as $question_opt_key => $question_opt_value) {
    ?>
                                <option value="<?php echo $question_opt_key; ?>"><?php echo $question_opt_value; ?></option>
                                <?php
}
?>
                        </select>
                        <span class="text text-danger correct_error"></span>
                    </div>
                      <div class="form-group ans_true_false">
                        <label for="subject_id"><?php echo $this->lang->line('answer') ?></label><small class="req"> *</small>
                        <select class="form-control" name="correct_true_false">
                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                            <?php
foreach ($question_true_false as $question_true_false_key => $question_true_false_value) {
    ?>
                                <option value="<?php echo $question_true_false_key; ?>"><?php echo $question_true_false_value; ?></option>
                                <?php
}
?>
                        </select>
                        <span class="text text-danger correct_true_false_error"></span>
                    </div>
  <div class="form-group ans_checkbox">
                        <label for="subject_id"><?php echo $this->lang->line('answer') ?></label><small class="req"> *</small>
                        <div>
                                                   <?php
foreach ($questionOpt as $question_opt_key => $question_opt_value) {
    ?>
     <label class="checkbox-inline">
      <input type="checkbox" name="ans[]" value="<?php echo $question_opt_key; ?>"><?php echo $question_opt_value; ?>
    </label>
                                <?php
}
?>
                        </div>
                        <span class="text text-danger ans_error"></span>
                    </div>