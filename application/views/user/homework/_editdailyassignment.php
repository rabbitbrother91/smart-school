<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="row">
            <input type="hidden" name="assigment_id" value="<?php echo $dailyassignmentlist['id']; ?>">

            <div class="col-sm-12">
                <div class="form-group">  
                    <label><?php echo $this->lang->line('subject'); ?></label><small class="req"> *</small>
                    <select name="subject" id="subject" class="form-control">
                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                        <?php foreach ($subjectlist as $key => $subjectlist_value) {
    $selected = '';
    if ($subjectlist_value->subject_group_subject_id == $dailyassignmentlist['subject_group_subject_id']) {
        $selected = 'selected';
    }
    ?>
                            <option value="<?php echo $subjectlist_value->subject_group_subject_id; ?>" <?php echo $selected; ?>><?php echo $subjectlist_value->subject_name; ?> <?php if($subjectlist_value->subject_code){ echo '('.$subjectlist_value->subject_code .')'; }?></option>
                        <?php }?>
                    </select>
                </div>
                <span id="subject_add_error" class="text-danger"><?php echo form_error('subject'); ?></span>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                    <input type="text" id="title" name="title" class="form-control" value="<?php if (!empty($dailyassignmentlist['title'])) {echo $dailyassignmentlist['title'];}?>">
                </div>
                <span id="name_add_error" class="text-danger"><?php echo form_error('title'); ?></span>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label><?php echo $this->lang->line('description'); ?></label>
                     <textarea class="form-control" id="description" name="description" rows="4" cols="50"><?php if (!empty($dailyassignmentlist['title'])) {echo $dailyassignmentlist['description'];}?></textarea>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label><?php echo $this->lang->line('attach_document'); ?></label>
                    <input type="file" name="file" class="form-control filestyle" value="<?php if (!empty($dailyassignmentlist['attachment'])) {echo $dailyassignmentlist['attachment'];}?>">
                </div>
                <span id="name_add_error" class="text-danger"><?php echo form_error('attachment'); ?></span>
            </div>
        </div><!--./row-->
    </div><!--./col-md-12-->
</div><!--./row-->

<script type="text/javascript">
       $('.filestyle').dropify();
</script>