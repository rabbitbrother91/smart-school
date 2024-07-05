<div class="row row-eq">
    <div class="col-lg-8 col-md-8 col-sm-8 paddlr">     
       <div class="scroll-area">
          <div class="pl-sm-10 pr-sm-10">
               <label><b><?php echo $this->lang->line('title'); ?></b>: <?php echo $assignmentlist['title']; ?>  </label>
            <div class="form-group">
              <label><b><?php echo $this->lang->line('description'); ?></b></label>
            </div>
            <p><?php echo $assignmentlist['description']; ?></p>
        </div>       
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-eq">
        <div class="scroll-area">
            <div class="taskside pt0">                
                <h4><?php echo $this->lang->line('summary'); ?></h4>
                <div class="box-tools pull-right">
                </div><!-- /.box-tools -->    

                <hr class="taskseparator" />
                <div class="task-info task-single-inline-wrap task-info-start-date">
                    <h5><i class="fa task-info-icon fa-fw fa-lg fa-calendar-plus-o pull-left fa-margin"></i>
                        <span><?php echo $this->lang->line('submission_date'); ?></span>:<?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($assignmentlist['date'])); ?>  
                    </h5>
                </div>           
                <div class="task-info task-single-inline-wrap task-info-start-date">
                    <h5><i class="fa task-info-icon fa-fw fa-lg fa-calendar-plus-o pull-left fa-margin"></i>
                        <span><?php echo $this->lang->line('evaluation_date'); ?></span>:<?php if(!empty($assignmentlist['evaluation_date'])){ echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($assignmentlist['evaluation_date']));} ?>
                    </h5>
                </div>
                <div class="task-info task-single-inline-wrap ptt10">
                    <label><span><?php echo $this->lang->line('student_name'); ?></span>: <?php echo $this->customlib->getFullName($assignmentlist['firstname'],$assignmentlist['middlename'],$assignmentlist['lastname'],$sch_setting->middlename,$sch_setting->lastname) . " (" . $assignmentlist['student_admission_no'] . ")"; ?>  </label>                    
                    <label><span><?php echo $this->lang->line('evaluated_by');   ?></span>: <?php if($assignmentlist['evaluated_by']){ ?><?php echo $assignmentlist['name'].' '.$assignmentlist['surname'].' ('.$assignmentlist['employee_id'].')'; } ?> </label>                     
                    <label><span><?php echo $this->lang->line("class") ?></span>: <?php echo $assignmentlist['class']; ?></label>
                    <label><span><?php echo $this->lang->line("section") ?></span>: <?php echo $assignmentlist['section']; ?></label>                    
                    <label><span><?php echo $this->lang->line("subject") ?></span>: <?php echo $assignmentlist['subject_name']; ?> <?php if($assignmentlist['subject_code']){ echo '('.$assignmentlist['subject_code'].')'; } ?></label>
                    <?php if($assignmentlist['remark'] != ''){ ?>
                    <label><span><?php echo $this->lang->line("remark") ?></span>: <?php echo $assignmentlist['remark']; ?></label>
                    <?php } ?>
                </div>
            </div>
        </div>    
    </div>
</div> 