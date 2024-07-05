<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12">
    <div class="row">
        <input type="hidden" name="visitor_id" value="<?php echo $visitor_data['id'];?>">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('purpose'); ?></label><small class="req"> *</small>
                <select name="purpose" class="form-control">
                    <option value=""><?php echo $this->lang->line('select'); ?> </option>
                    <?php foreach ($Purpose as $key => $value) { 
                        $selected = '';
                        if($value['visitors_purpose'] == $visitor_data['purpose']){
                            $selected = 'selected';
                        }
                        ?>
                        <option value="<?php echo $value['visitors_purpose'];?>" <?php echo $selected; ?>><?php echo $value['visitors_purpose'];?></option>
                    <?php }?>
                </select>
                <span class="text-danger"><?php echo form_error('purpose'); ?></span>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('meeting_with'); ?></label><small class="req"> *</small>
                <select name="edit_meeting_with" id="edit_meeting_with" class="form-control">
                    <option value=""><?php echo $this->lang->line('select'); ?> </option>
                    <?php foreach ($meeting_with as $key => $meeting_with_value) {
                            $selected = '';
                            if($key == $visitor_data['meeting_with']){
                                $selected = 'selected';
                            }
                        ?>
                        <option value="<?php echo $key;?>" <?php echo $selected; ?>><?php echo $meeting_with_value;?></option>
                    <?php }?>
                </select>
                <span class="text-danger"><?php echo form_error('meeting_with'); ?></span>
            </div>
        </div>

        <div id="edit_visible_staff">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo $this->lang->line('staff'); ?></label><small class="req"> *</small>
                    <select name="edit_staff_id" id="edit_staff_id" class="form-control">
                        <option value=""><?php echo $this->lang->line('select'); ?> </option>
                        <?php foreach($stafflist as $key => $stafflist_value){ 
                               $selected = '';
                               if($stafflist_value['id'] == $visitor_data['staff_id']){
                                 $selected = 'selected';
                               }
                            ?>
                            <option value="<?php echo $stafflist_value['id']; ?>" <?php echo $selected; ?>><?php echo $stafflist_value['name'].' '.$stafflist_value['surname'].' ('.$stafflist_value['employee_id'].')'; ?></option>
                        <?php } ?>
                    </select>
                    <span class="text-danger"><?php echo form_error('edit_staff_id'); ?></span>
                </div>
            </div>
        </div>
        <div id="edit_visible_student">
            <div class="col-sm-4">
                <div class="form-group">
                    <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                    <select autofocus="" id="edit_class_id" name="edit_class_id" class="form-control" >
                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                        <?php
                        foreach ($classlist as $class) {
                            $selected = '';
                            if($class['id'] == $visitor_data['class_id']){
                                $selected = 'selected';
                            }
                            ?>
                            <option value="<?php echo $class['id'] ?>" <?php echo $selected; ?>><?php echo $class['class'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                     <span class="text-danger" id="error_class_id"></span>
                </div>
            </div> 
            <div class="col-md-4">
                <div class="form-group">
                    <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                    <select  id="edit_section_id" name="edit_class_section_id" class="form-control" >
                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                    </select>
                    <span class="text-danger"><?php echo form_error('class_section_id'); ?></span>
                </div>  
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label><?php echo $this->lang->line('student'); ?></label><small class="req"> *</small>
                    <select  id="edit_student_session_id" name="edit_student_session_id" class="form-control">
                    </select>
                    <span class="text-danger"><?php echo form_error('student_session_id'); ?></span>
                </div>  
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="pwd"><?php echo $this->lang->line('visitor_name'); ?></label>  <small class="req"> *</small>
                <input type="text" class="form-control" value="<?php echo set_value('name', $visitor_data['name']); ?>" name="name">
                <span class="text-danger"><?php echo form_error('name'); ?></span>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="pwd"><?php echo $this->lang->line('phone'); ?></label>
                <input type="text" class="form-control" value="<?php echo set_value('contact', $visitor_data['contact']); ?>" name="contact">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="pwd"><?php echo $this->lang->line('id_card'); ?></label>
                <input type="text" class="form-control" value="<?php echo set_value('id_proof', $visitor_data['id_proof']); ?>" name="id_proof">
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="email"><?php echo $this->lang->line('number_of_person'); ?></label>
                <input type="text" class="form-control" value="<?php echo set_value('no_of_people', $visitor_data['no_of_people']); ?>" name="pepples">
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="pwd"><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
                <input type="text" id="date" class="form-control date" value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($visitor_data['date']))); ?>"  name="date" readonly="">
                <span class="text-danger"><?php echo form_error('date'); ?></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="pwd"><?php echo $this->lang->line('in_time'); ?></label>
                <div class="bootstrap-timepicker">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" name="time" class="form-control timepicker" id="stime_" value="<?php echo set_value('time', $visitor_data['in_time']); ?>">
                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <span class="text-danger"><?php echo form_error('time'); ?></span>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="pwd"><?php echo $this->lang->line('out_time'); ?></label>
                <div class="bootstrap-timepicker">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" name="out_time" class="form-control timepicker" id="stime_" value="<?php echo set_value('out_time', $visitor_data['out_time']); ?>">
                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <span class="text-danger"><?php echo form_error('out_time'); ?></span>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="exampleInputFile"><?php echo $this->lang->line('attach_document'); ?></label>
                <div>
                    <input class="filestyle form-control" type='file' name='file'/>
                </div>
                <span class="text-danger"><?php echo form_error('file'); ?></span>
            </div>            
        </div>
    </div>   
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="pwd"><?php echo $this->lang->line('note'); ?></label>
                <textarea class="form-control" id="description" name="note" name="note" rows="3"><?php echo set_value('note', $visitor_data['note']); ?></textarea>
                <span class="text-danger"><?php echo form_error('note'); ?></span>
            </div>
        </div>
    </div>    
    </div>
</div>

<script>
    $('.filestyle').dropify();
    $(document).ready(function(){
        var class_id = $('#edit_class_id').val();
        var section_id = '<?php echo $visitor_data['section_id']; ?>';
        getsectionbyclass(class_id,section_id);
        var students_id = '<?php echo $visitor_data['student_session_id']; ?>';   
        studentbysection(class_id,section_id,students_id);     
        var meeting_with = '<?php echo $visitor_data['meeting_with']; ?>';

        if(meeting_with == 'staff'){
            $('#edit_visible_staff').show();
            $('#edit_visible_student').hide();
        }else{
            $('#edit_visible_student').show();
            $('#edit_visible_staff').hide();
        }
    });

    $('#edit_meeting_with').change(function(){
        var meeting_with = $('#edit_meeting_with').val();
        if(meeting_with == 'staff'){
            $('#edit_visible_staff').show();
            $('#edit_visible_student').hide();
        }else if(meeting_with == 'student'){
            $('#edit_visible_student').show();
            $('#edit_visible_staff').hide();
        }else{
            $('#edit_visible_student').hide();
            $('#edit_visible_staff').hide();
        } 
    }); 

    $('#edit_class_id').change(function(){    
        $('#edit_section_id').html('');
        var class_id = $('#edit_class_id').val();
        getsectionbyclass(class_id,'');
    });

    $('#edit_section_id').change(function(){   
        $('#student_id').html('');
        $('#edit_student_session_id').html('');
        var class_id = $('#edit_class_id').val();
        var section_id = $('#edit_section_id').val();
        studentbysection(class_id,section_id,'');
    });
</script>
<script type="text/javascript">
    $(function () {
        $(".timepicker").timepicker({

        });
    });
    </script>