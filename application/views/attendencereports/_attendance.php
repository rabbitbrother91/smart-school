<div class="row">
    <div class="col-md-12">
        <div class="box box-primary border0 mb0 margesection">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('attendance_report'); ?></h3>

            </div>
            <div class="">
                <ul class="reportlists">
                    <?php
                    if (!is_subAttendence()) {


                        if ($this->rbac->hasPrivilege('attendance_report', 'can_view')) {
                    ?>
                            <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/attendance/attendance_report'); ?>"><a href="<?php echo base_url(); ?>attendencereports/classattendencereport"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('attendance_report'); ?></a></li>
                        <?php
                        }
                        if ($this->rbac->hasPrivilege('student_attendance_type_report', 'can_view')) {
                        ?>
                            <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/attendence/attendancereport'); ?>"><a href="<?php echo base_url() ?>attendencereports/attendancereport"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('student_attendance_type_report'); ?></a></li>
                        <?php
                        }
                        if ($this->rbac->hasPrivilege('daily_attendance_report', 'can_view')) {
                        ?>

                            <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/attendance/daily_attendance_report'); ?>"><a href="<?php echo site_url('attendencereports/daily_attendance_report'); ?>"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('daily_attendance_report'); ?></a></li>
                            
                            <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/attendance/daywiseattendancereport'); ?>"><a href="<?php echo site_url('attendencereports/daywiseattendancereport'); ?>"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('student_day_wise_attendance_report'); ?> </a></li>

                        <?php }                       
                           
                    }
                    if ($this->rbac->hasPrivilege('staff_attendance_report', 'can_view')) {
                        ?>

                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/attendance/staffdaywiseattendancereport'); ?>"><a href="<?php echo site_url('attendencereports/staffdaywiseattendancereport') ?>"><i class="fa fa-file-text-o"></i>  <?php echo $this->lang->line('staff_day_wise_attendance_report'); ?></a></li>


                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/attendance/staff_attendance_report'); ?>"><a href="<?php echo base_url() ?>attendencereports/staffattendancereport"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('staff_attendance_report'); ?></a></li>
                    <?php } ?>
                    <?php
                    if (is_subAttendence()) {
                        if (($this->rbac->hasPrivilege('student_period_attendance_report', 'can_view'))) {
                    ?>

                            <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_subSubmenu('Reports/attendence/reportbymonth'); ?>"><a href="<?php echo site_url('attendencereports/reportbymonth'); ?>"><i class="fa fa-file-text-o"></i> <?php ?> <?php echo $this->lang->line('period_attendance_report'); ?></a></li>

                            <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_subSubmenu('Reports/attendence/reportbymonthstudent'); ?>"><a href="<?php echo site_url('attendencereports/reportbymonthstudent'); ?>"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('student_period_attendance'); ?></a></li>
                        <?php
                        }
                    }
                    if ($this->customlib->is_biometricAttendence()) {
                        if ($this->rbac->hasPrivilege('biometric_attendance_log', 'can_view')) {
                        ?>
                            <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/attendence/biometric_attlog'); ?>"><a href="<?php echo site_url('attendencereports/biometric_attlog'); ?>"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('biometric_attendance_log'); ?></a></li>
                    <?php
                        }
                    } ?>

                </ul>
            </div>
        </div>
    </div>
</div>