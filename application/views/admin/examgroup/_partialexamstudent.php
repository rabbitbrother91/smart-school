<form method="post" action="<?php echo site_url('admin/examgroup/entrystudents') ?>" id="allot_exam_student">
    <input type="hidden" name="exam_group_class_batch_exam_id" value="<?php echo $exam_id; ?>">
    <?php
    if (isset($resultlist) && !empty($resultlist)) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class=" table-responsive ptt10">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th width="60"><label class="checkbox-inline bolds"><input type="checkbox" class="select_all"/> <?php echo $this->lang->line('all'); ?></label></th>
                                <th><?php echo $this->lang->line('admission_no'); ?></th>
                                <th><?php echo $this->lang->line('student_name'); ?></th> <th><?php echo $this->lang->line('father_name'); ?></th>
                                <?php if ($sch_setting->category) { ?>
                                    <th><?php echo $this->lang->line('category'); ?></th>
                                <?php } ?>
                                <th><?php echo $this->lang->line('gender'); ?></th>
                            </tr>
                            <?php
                            if (empty($resultlist)) {
                                ?>
                                <tr>
                                    <td colspan="7" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                </tr>
                                <?php
                            } else {
                                $counter = 1; 
                                foreach ($resultlist as $student) {
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="all_students[]" value="<?php echo $student['student_session_id']; ?>">
                                            <input type="hidden" name="student_<?php echo $student['student_session_id']; ?>" value="<?php echo $student['id']; ?>">
                                            <input class="checkbox" type="checkbox" name="student_session_id[]"  value="<?php echo $student['student_session_id']; ?>" <?php echo ($student['onlineexam_student_id'] != 0) ? "checked" : ""; ?> />
                                        </td>
                                        <td><?php echo $student['admission_no']; ?></td>
                                        <td><?php echo $this->customlib->getFullName($student['firstname'],$student['middlename'],$student['lastname'],$sch_setting->middlename,$sch_setting->lastname);?></td>
                                        <td><?php echo $student['father_name']; ?></td>
                                        <?php if ($sch_setting->category) { ?>
                                            <td><?php echo $student['category']; ?></td>
                                        <?php } ?>
                                        <td><?php echo $this->lang->line(strtolower($student['gender'])); ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($this->rbac->hasPrivilege('exam_assign_view_student', 'can_edit')) { ?>
                    <button type="submit" class="btn btn-primary btn-sm pull-right mr-1" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save'); ?>
                    </button>
                <?php } ?>
            </div>
        </div>
        <?php
    } else {
        ?>        
        <div class="alert alert-danger "><?php echo $this->lang->line('no_record_found'); ?></div>
        <?php
    }
    ?>
</form>