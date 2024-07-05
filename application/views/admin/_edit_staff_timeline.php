<input type="hidden" name="id" value="<?php echo $singletimelinelist["id"] ?>" id="student_timeline_id">
<input type="hidden" name="edit_staff_id" value="<?php echo $singletimelinelist["staff_id"] ?>" id="staff_timeline_id">
<div class="">
    <div class="form-group">
        <label for=""><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
        <input id="timeline_title" name="timeline_title" placeholder="" type="text" class="form-control" value="<?php echo $singletimelinelist["title"] ?>" />
        <span class="text-danger"><?php echo form_error('timeline_title'); ?></span>
    </div>
    <div class="form-group">
        <label for=""><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
        <input id="timeline_date" name="timeline_date" value="<?php
                                                if (!empty($singletimelinelist["timeline_date"])) {
                                                    echo date($this->customlib->getSchoolDateFormat(), strtotime($singletimelinelist["timeline_date"]));
                                                }
                                                ?>" placeholder="" type="text" class="form-control date"  />
        <span class="text-danger"><?php echo form_error('timeline_date'); ?></span>
    </div>
    <div class="form-group">
        <label for=""><?php echo $this->lang->line('description'); ?></label>
        <textarea id="timeline_desc" name="timeline_desc" placeholder=""  class="form-control"><?php echo $singletimelinelist["description"] ?></textarea>
        <span class="text-danger"><?php echo form_error('description'); ?></span>
    </div>
    <div class="form-group">
        <label for=""><?php echo $this->lang->line('attach_document'); ?></label>
        <div class=""><input id="timeline_doc_id" name="timeline_doc" placeholder="" type="file"  class="filestyle form-control" data-height="30"  value="<?php echo set_value('document'); ?>" />
            <span class="text-danger"><?php echo form_error('timeline_doc'); ?></span></div>
    </div>
    <div class="form-group">  
        <label for="" class="col-align--top"><?php echo $this->lang->line('visible_to_this_person'); ?></label>
        <input  name="visible_check" id="visible_check"  type="checkbox" <?php if($singletimelinelist["status"] == 'yes'){echo "checked";} ?>  />
    </div>
</div>

<script type="text/javascript">
    $('.filestyle').dropify();
</script>