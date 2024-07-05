<input type="hidden" name="id" value="<?php echo $singletimelinelist["id"] ?>" id="student_timeline_id">
<input type="hidden" name="student_id" value="<?php echo $singletimelinelist["student_id"] ?>" id="student_timeline_id">
<div class="col-md-12">
    <div class="form-group">
        <label><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
        <input id="timeline_title" name="timeline_title" placeholder="" type="text" class="form-control" value="<?php echo $singletimelinelist["title"] ?>" />
        <span class="text-danger"><?php echo form_error('timeline_title'); ?></span>
    </div>
    <div class="form-group">
        <label><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
        <input id="timeline_date" name="timeline_date" value="<?php echo set_value('timeline_date', date($this->customlib->getSchoolDateFormat())); ?>" placeholder="" type="text" class="form-control date"  />
        <span class="text-danger"><?php echo form_error('timeline_date'); ?></span>
    </div>
    <div class="form-group">
        <label><?php echo $this->lang->line('description'); ?></label>
        <textarea id="timeline_desc" name="timeline_desc" placeholder=""  class="form-control"><?php echo $singletimelinelist["description"] ?></textarea>
        <span class="text-danger"><?php echo form_error('description'); ?></span>
    </div>
    <div class="form-group">
        <label><?php echo $this->lang->line('attach_document'); ?></label>
        <div class=""><input id="timeline_doc_id" name="timeline_doc" placeholder="" type="file"  class="filestyle form-control" data-height="40"  value="<?php echo set_value('document'); ?>" />
            <span class="text-danger"><?php echo form_error('timeline_doc'); ?></span></div>
    </div>
</div>

<script type="text/javascript">
    $('.filestyle').dropify();
</script>