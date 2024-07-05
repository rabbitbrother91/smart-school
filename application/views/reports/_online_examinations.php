<div class="row">
    <div class="col-md-12">
        <div class="box box-primary border0 mb0 margesection">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i>  <?php echo $this->lang->line('online_examinations_report'); ?></h3>
            </div>
            <div class="">
                <ul class="reportlists">
                    <?php
                    if ($this->rbac->hasPrivilege('online_exam_wise_report', 'can_view')) {
                        ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/online_examinations/online_exam_report'); ?>"><a href="<?php echo base_url() ?>admin/onlineexam/report"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('result_report'); ?></a></li>
                        <?php
                    }
                    if ($this->rbac->hasPrivilege('online_exams_report', 'can_view')) {
                        ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/online_examinations/onlineexams'); ?>"><a href="<?php echo base_url() ?>report/onlineexams"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('exams_report'); ?></a></li>
                        <?php
                    }
                    if ($this->rbac->hasPrivilege('online_exams_attempt_report', 'can_view')) {
                        ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/online_examinations/onlineexamattend'); ?>"><a href="<?php echo base_url() ?>report/onlineexamattend"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('student_exams_attempt_report'); ?></a></li>
                        <?php
                    }
                    if ($this->rbac->hasPrivilege('online_exams_rank_report', 'can_view')) {
                        ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/online_examinations/onlineexamrank'); ?>"><a href="<?php echo base_url() ?>report/onlineexamrank"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('exams_rank_report'); ?></a></li>
                            <?php
                        }
                        ?>
                </ul>
            </div>
        </div>
    </div>
</div>