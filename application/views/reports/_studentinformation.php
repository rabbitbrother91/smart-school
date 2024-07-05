<div class="row">
    <div class="col-md-12">
        <div class="box box-primary border0 mb0 margesection">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i>  <?php echo $this->lang->line('student_information_report'); ?></h3>
            </div>
            <div class="">
                <ul class="reportlists">
                    <?php
                    if ($this->rbac->hasPrivilege('student_report', 'can_view')) {
                        ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/student_report'); ?> "><a href="<?php echo base_url(); ?>report/studentreport"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('student_report'); ?></a></li>
						
                          <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/classsectionreport'); ?>"><a href="<?php echo site_url('report/classsectionreport'); ?>"><i class="fa fa-file-text-o"></i>  <?php echo $this->lang->line('class_section_report'); ?></a></li>						  
						  
                        <?php
                    }
                    if ($this->rbac->hasPrivilege('guardian_report', 'can_view')) {
                        ?>

                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/guardian_report'); ?>"><a href="<?php echo base_url(); ?>report/guardianreport"><i class="fa fa-file-text-o"></i><?php echo $this->lang->line('guardian_report'); ?></a></li>
                        <?php
                    }
                    if ($this->rbac->hasPrivilege('student_history', 'can_view')) {
                        ?>

                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/student_history'); ?>"><a href="<?php echo base_url() ?>report/admissionreport"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('student_history'); ?></a></li>
                    <?php } ?>

                    <?php if ($this->rbac->hasPrivilege('student_login_credential_report', 'can_view')) { ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/student_login_credential'); ?>"><a href="<?php echo base_url(); ?>report/logindetailreport"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('student_login_credential'); ?></a></li>
                    <?php } ?>

                    <?php if ($this->rbac->hasPrivilege('student_login_credential_report', 'can_view')) { ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/parent_login_credential'); ?>"><a href="<?php echo base_url(); ?>report/parentlogindetailreport"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('parent_login_credential'); ?></a></li>
                    <?php } ?>

                    <?php if ($this->rbac->hasPrivilege('class_subject_report', 'can_view')) {
                        ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/class_subject_report'); ?>"><a href="<?php echo base_url(); ?>report/class_subject"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('class_subject_report'); ?></a></li>
                    <?php } if ($this->rbac->hasPrivilege('admission_report', 'can_view')) { ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/admission_report'); ?>"><a href="<?php echo base_url(); ?>report/admission_report"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('admission_report'); ?></a></li>
                    <?php } if ($this->rbac->hasPrivilege('sibling_report', 'can_view')) { ?>

                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/sibling_report'); ?>"><a href="<?php echo base_url(); ?>report/sibling_report"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('sibling_report'); ?></a></li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('student_profile', 'can_view')) {
                        ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/student_profile'); ?>"><a href="<?php echo base_url(); ?>report/student_profile"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('student_profile'); ?></a></li>
                    <?php } ?>                  
                    
                    <?php if ($this->rbac->hasPrivilege('student_gender_ratio_report', 'can_view')) {   ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/boys_girls_ratio'); ?>"><a href="<?php echo base_url(); ?>report/boys_girls_ratio"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('student_gender_ratio_report'); ?></a></li>
                        <?php }
                        if ($this->rbac->hasPrivilege('student_teacher_ratio_report', 'can_view')) { ?>
                       
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/student_information/student_teacher_ratio'); ?>"><a href="<?php echo base_url(); ?>report/student_teacher_ratio"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('student_teacher_ratio_report'); ?></a></li>
<?php } ?>

                       <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/online_admission'); ?>"><a href="<?php echo base_url(); ?>report/online_admission_report"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('online_admission_report'); ?></a></li>

                </ul>
            </div>
        </div> 
    </div>
</div>