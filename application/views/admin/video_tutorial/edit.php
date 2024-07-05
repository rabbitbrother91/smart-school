<style type="text/css">
    .select2-container--open {z-index: 9001;}
</style>
<div class="row">
<input type="hidden" name="id" value="<?php echo set_value('id', $videotutoriallist->id); ?>" >
<div class="col-lg-12 col-md-12 col-sm-12">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label><?php echo $this->lang->line('class'); ?></label> <small class="req"> *</small>
                <select autofocus="" id="edit_class_id" name="class_id" class="form-control" >
                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                    <?php
                        foreach ($classlist as $classlist_value) {
                    ?>
                            <option value="<?php echo $classlist_value['id']; ?>" <?php if ($classlist_value['id'] == $classid['class_id']) {echo "selected";}?> ><?php echo $classlist_value['class']; ?></option>

                    <?php
                        }
                    ?>
                </select>
                <span class="text-danger" id="error_class_id"></span>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group select2-container-3">
                <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>

                <select  id="edit_section_id" name="edit_section_id[]" class="form-control select2" multiple="multiple">
                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                </select>
                <span class="text-danger" id="error_edit_section_id"></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                <input autofocus="" id="title" name="title" placeholder="" type="text" class="form-control"  value="<?php echo set_value('title',$videotutoriallist->title); ?>" />
                <span class="text-danger"><?php echo form_error('title'); ?></span>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label><?php echo $this->lang->line('video_link'); ?></label><small class="req"> *</small>
                <input autofocus="" id="video_link" name="video_link" placeholder="" type="text" class="form-control"  value="<?php echo set_value('video_link',$videotutoriallist->video_link); ?>" />
                <span class="text-danger"><?php echo form_error('video_link'); ?></span>
            </div>
        </div>
    </div>
   
    <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
                <label><?php echo $this->lang->line('description'); ?></label>
                <textarea class="form-control" id="description" name="description" placeholder="" rows="3"><?php echo set_value('description',$videotutoriallist->description); ?></textarea>
                <span class="text-danger"><?php echo form_error('description'); ?></span>
            </div>
        </div>
    </div>
    </div><!--./row-->
</div><!--./col-md-12-->
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()
  })
</script>