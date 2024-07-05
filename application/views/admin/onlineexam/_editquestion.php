<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<div class="col-sm-12">
    <div class="form-group">
        <label for="email"><?php echo $this->lang->line('description'); ?></label><small class="req"> *</small>
        <textarea name="description" id="compose-textarea" class="form-control" ><?php echo $result['question'] ?></textarea>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
        <label for="email"><?php echo $this->lang->line('subject'); ?></label><small class="req"> *</small>
        <select class="form-control" id="subject" name="subject" >
            <option value=""><?php echo $this->lang->line('select') ?></option>
            <?php
foreach ($subject as $value) {
    ?>
                <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                <?php
}
?>
        </select>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group" id="extraoption">
        <label for="email"><?php echo $this->lang->line('option'); ?></label><small class="req"> *</small></br>
        <input type="radio" name="answer" id="answer1" >
        <input type="text" name="option[]" onkeyup="set_answer(this.value, 'opt1', 'answer1')" id="opt1" ></br>
        <input type="radio" name="answer" id="answer2"  >
        <input type="text" name="option[]" onkeyup="set_answer(this.value, 'opt2', 'answer2')" id="opt2">
        <lable onclick="add_more()"><?php echo $this->lang->line('add') ?></lable>
    </div>
</div>