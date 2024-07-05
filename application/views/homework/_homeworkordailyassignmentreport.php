<div class="row">
    <div class="col-md-12">
        <div class="box box-primary border0 mb0 margesection">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i>  <?php echo $this->lang->line('homework_report'); ?></h3>
            </div>
            <div class="">
                <ul class="reportlists">
                    <?php
                    if ($this->rbac->hasPrivilege('homework', 'can_view')) {
                        ?>                        
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('homework/homeworkreport'); ?>"><a href="<?php echo base_url(); ?>homework/homeworkreport"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('homework_report'); ?></a></li> 
                    <?php } ?>
                    
                    <?php  if ($this->rbac->hasPrivilege('homehork_evaluation_report', 'can_view')) {  ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('homework/evaluation_report'); ?>"><a href="<?php echo base_url(); ?>homework/evaluation_report"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('homework_evaluation_report'); ?></a></li>
                    <?php } ?>
                    
                    <?php
                    if ($this->rbac->hasPrivilege('daily_assignment', 'can_view')) {
                        ?>                        
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('homework/dailyassignmentreport'); ?>"><a href="<?php echo base_url(); ?>homework/dailyassignmentreport"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('daily_assignment_report'); ?></a></li> 
                    <?php } ?>
                </ul>
            </div>
        </div> 
    </div>
</div>