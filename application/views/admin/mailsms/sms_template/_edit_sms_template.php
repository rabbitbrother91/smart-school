<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
<div class="row">
    <input name="id" type="hidden" class="form-control"  value="<?php echo $sms_template_list->id; ?>" />
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                    <input name="title" type="text" class="form-control"  value="<?php echo $sms_template_list->title; ?>" />
                    <span class="text-danger"><?php echo form_error('title'); ?></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                    <label><?php echo $this->lang->line('message'); ?></label><small class="req"> *</small>
                    <textarea name="message" class="form-control" rows="10"><?php echo $sms_template_list->message; ?></textarea>
                    <span class="text-danger"><?php echo form_error('message'); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>