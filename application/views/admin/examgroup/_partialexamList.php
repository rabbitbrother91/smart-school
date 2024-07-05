<?php
foreach ($examList as $exam_key => $exam_value) {
    ?>
    <tr>
        <td>
            <?php echo $exam_value->exam; ?>
        </td>
        <td class="white-space-nowrap">
            <?php echo $exam_value->session; ?>
        </td>
        <td>
            <?php echo $exam_value->total_subjects; ?>
        </td>
        <td class="text text-center">
            <?php echo ($exam_value->is_active == 1) ? "<i class='fa fa-check-square-o'></i>" : "<i class='fa fa-exclamation-circle'></i>"; ?>
        </td>
        <td class="text text-center">
            <?php echo ($exam_value->is_publish == 1) ? "<i class='fa fa-check-square-o'></i>" : "<i class='fa fa-exclamation-circle'></i>"; ?>
        </td>
        <td>
            <?php echo $exam_value->description; ?>
        </td>
        <td class="text-right white-space-nowrap">
            <?php
            if ($this->rbac->hasPrivilege('exam_assign_view_student', 'can_view')) {
                ?>
                <button type="button" data-toggle="tooltip"
                        title = "<?php echo $this->lang->line('assign_view_student'); ?>" class="btn btn-default btn-xs assignStudent"  id="load" data-examid="<?php echo $exam_value->id; ?>" ><i class="fa fa-tag"></i>
                    </button>
                        <?php
                    }
                    if ($this->rbac->hasPrivilege('exam_subject', 'can_view')) {
                        ?>
                <button class="btn btn-default btn-xs" id="subjectModalButton" data-toggle="tooltip"   data-exam_id="<?php echo $exam_value->id; ?>"  title="<?php echo $this->lang->line('exam_subject'); ?>"><i class="fa fa-book" aria-hidden="true"></i></button>
                <?php
            }
            if ($this->rbac->hasPrivilege('exam_marks', 'can_view')) {
                ?>
                <button type="button" class="btn btn-default btn-xs examMarksSubject" id="load" data-toggle="tooltip"  data-recordid="<?php echo $exam_value->id; ?>" title="<?php echo $this->lang->line('exam_marks'); ?>" data-loading-text="<i class='fa fa-spinner fa-spin'></i>"><i class="fa fa-newspaper-o"></i></button>
                <?php
            }

             if ($this->rbac->hasPrivilege('exam_marks', 'can_view')) {
                ?>
               <button type="button" class="btn btn-default btn-xs examTeacherReamark" id="load" data-toggle="tooltip"  data-recordid="<?php echo $exam_value->id; ?>" title="<?php echo $this->lang->line('teacher_remark'); ?>" data-loading-text="<i class='fa fa-spinner fa-spin'></i>"><i class="fa fa-comment"></i></button>
                <?php
            }
            
            if ($this->rbac->hasPrivilege('exam', 'can_edit')) {
                ?>
                <button class="btn btn-default btn-xs editexamModalButton" data-toggle="tooltip" data-exam_id="<?php echo $exam_value->id; ?>"  title="<?php echo $this->lang->line('edit') ?>"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                <?php
            }

              if ($this->rbac->hasPrivilege('generate_rank', 'can_view')) {
                ?>
             <button class="btn btn-default btn-xs" data-toggle="modal" data-original-title="<?php echo $this->lang->line('generate_rank'); ?>" data-target="#studentRankModal" data-exam_id="<?php echo $exam_value->id; ?>" data-exam-name="<?php echo $exam_value->exam; ?>" title="<?php echo $this->lang->line('generate_rank'); ?>"><i class="fa fa-list-alt" aria-hidden="true"></i></button>
                <?php
            }

            if ($this->rbac->hasPrivilege('exam', 'can_delete')) {
                ?>
                <span data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>">
                    <a href="#" class="btn btn-default btn-xs"  data-id="<?php echo $exam_value->id; ?>" data-exam="<?php echo $exam_value->exam; ?>" id="deleteItem" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-remove"></i></a>
                </span>
                <?php
            }
            ?>
        </td>
    </tr>
    <?php
}
?>