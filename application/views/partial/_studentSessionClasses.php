<?php 
$current_class=($this->session->userdata('current_class'));

if(!empty($studentclasses)){
foreach ($studentclasses as $student_key => $student_value) {

        if ($role == "parent") {
            $name = $this->customlib->getFullName($student_value->firstname,$student_value->middlename,$student_value->lastname,$sch_setting->middlename,$sch_setting->lastname);
        }
	?>
<div class="row">
        <div class="col-md-12">
            <div class="well well-sm">
                <div class="row">
                    
                    <div class="col-xs-12 col-md-12 section-box">
                      
                        <div class="row rating-desc">
                            <div class="col-md-12">
                            	  <label class="checkbox-inline">
                                    <input type="checkbox" value="<?php echo $student_value->student_session_id; ?>" class="clschg" name="clschg" <?php echo ($student_value->class_id ==$current_class['class_id'] && $student_value->section_id ==$current_class['section_id'] && $student_value->student_session_id ==$current_class['student_session_id']) ? 'checked' :''; ?>><?php echo ($role == 'parent') ? $name . " " . $student_value->class . " (" . $student_value->section . ")" : $student_value->class . " (" . $student_value->section . ")"; ?>

                                </label>
                          
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
        
    </div>
	<?php
}
}else{
?>
<div class="alert alert-info">
	<?php echo $this->lang->line('no_more_classes_found_in_your_current_session'); ?>
</div>
<?php 	
}
?>