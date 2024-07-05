                            <div class="row pb10">
                              <div class="col-lg-2 col-md-3 col-sm-12">   
                                <p class="examinfo"><span><?php echo $this->lang->line('exam')?></span><?php echo $examgroupDetail->exam; ?></p>
                              </div> 

                              <div class="col-lg-10 col-md-9 col-sm-12">   
                                <p class="examinfo"><span><?php echo $this->lang->line('exam_group'); ?></span><?php echo $examgroupDetail->exam_group_name; ?></p>
                              </div> 
                            </div><!--./row-->
                            <div class="divider2"></div>
                                  <?php
                                    if (!empty($examgroupStudents)) {
                                 

                                       ?>
<form action="<?php echo site_url('admin/examgroup/saveexamremark') ?>" method="get" accept-charset="utf-8" method="POST" id="remark_form">
    
      <table class="table table-striped mb10 ">
      <thead>
        <tr>
          <th><?php echo $this->lang->line('name') ; ?></th>
          <th><?php echo $this->lang->line('admission_no') ; ?></th>
          <th><?php echo $this->lang->line('class') ; ?></th>
          <th><?php echo $this->lang->line('section') ; ?></th>
          <th><?php echo $this->lang->line('roll_number') ; ?></th>
          <th><?php echo $this->lang->line('remark') ; ?></th>
        </tr>
      </thead>
        <tbody>
        <?php
          foreach ($examgroupStudents as $exam_student_key => $student) { ?>
            <input type="hidden" name="exam_group_class_batch_exam_student[]" value="<?php echo $student->exam_group_class_batch_exam_student_id; ?>">
            <tr>
              <td><?php echo $this->customlib->getFullName($student->firstname,$student->middlename,$student->lastname,$sch_setting->middlename,$sch_setting->lastname);?></td>
              <td><?php echo $student->admission_no; ?></td>            
              <td><?php echo $student->class; ?></td>            
              <td><?php echo $student->student_section; ?></td>            
              <td><?php echo ($examgroupDetail->use_exam_roll_no)?$student->exam_roll_no:$student->roll_no;; ?> </td>
              <td>
                <div class="form-group">  
                  <textarea id="remark_<?php echo $student->exam_group_class_batch_exam_student_id; ?>" name="remark_<?php echo $student->exam_group_class_batch_exam_student_id; ?>" class="form-control"><?php echo $student->teacher_remark; ?></textarea>  
                </div>
              </td>
            </tr>                                        
        <?php } ?>      
        </tbody>
    </table>             
    <div class="clearfix"></div>  
    <div class="row">
      <div class="col-md-12"> 
        <button type="submit" class="btn btn-primary btn-sm pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait') ; ?>" autocomplete="off"><?php echo $this->lang->line('save') ; ?> </button>
      </div>              
    </div>                         
</form>
                                       <?php
                                    }
                                    ?>