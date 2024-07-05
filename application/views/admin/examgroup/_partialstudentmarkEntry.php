<div class="divider"></div>
<?php if ($this->rbac->hasPrivilege('marks_import', 'can_view')) {
    ?>
    <div class="row">   
        <div class="col-md-9"> 
            <form method="POST" enctype="multipart/form-data" id="fileUploadForm">

                <div class="input-group mb10">
                    <input id="my-file-selector" data-height="34" class="dropify" type="file">
                    <div class="input-group-btn">
                        <input type="submit" class="btn btn-primary" value="<?php echo $this->lang->line('submit') ?>" id="btnSubmit"/>
                    </div>
                </div>
        </div>  
    </form>

    <div class="col-md-3"> 
        <a class="btn btn-primary pull-right" href="<?php echo site_url('admin/examgroup/exportformat') ?>" target="_blank"><i class="fa fa-download"></i> <?php echo $this->lang->line('export_sample'); ?></a>
    </div>
    </div>    
    <?php
}
?>

<form method="post" action="<?php echo site_url('admin/examgroup/entrymarks') ?>" id="assign_form1111">
    <input type="hidden" id="max_mark" value="<?php echo $subject_detail->max_marks; ?>">
    <?php
    if (isset($resultlist) && !empty($resultlist)) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="exam_group_class_batch_exam_subject_id" value="<?php echo $exam_group_class_batch_exam_subject_id; ?>">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?php echo $this->lang->line('admission_no'); ?></th>
                                <th><?php echo $this->lang->line('roll_number'); ?></th>
                                <th><?php echo $this->lang->line('student_name'); ?></th> 
                                <th><?php echo $this->lang->line('father_name'); ?></th>
                                <th><?php echo $this->lang->line('category'); ?></th>
                                <th><?php echo $this->lang->line('gender'); ?></th>
                                <th><?php echo $this->lang->line('attendance'); ?></th>
                                <th><?php echo $this->lang->line('marks') ?></th>
                                <th><?php echo $this->lang->line('note') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (empty($resultlist)) {
                                ?>
                                <tr>
                                    <td colspan="7" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                </tr>
                                <?php
                            } else {

                                foreach ($resultlist as $student) {

                                    ?>
                                    <tr class="std_adm_<?php echo $student['admission_no']; ?>">
                                <input type="hidden" name="prev_id[<?php echo $student['exam_group_class_batch_exam_students_id'] ?>]" value="<?php echo $student['exam_group_exam_result_id'] ?>">
                                <input type="hidden" name="exam_group_student_id[]" value="<?php echo $student['exam_group_class_batch_exam_students_id'] ?>">
                                <td><?php echo $student['admission_no']; ?></td>
                                <td><?php  $roll_no=($student['use_exam_roll_no'])?$student['exam_roll_no']:$student['roll_no']; 
                                echo ($roll_no != 0) ? $roll_no : '-'; ?></td>
                                <td><?php echo $this->customlib->getFullName($student['firstname'],$student['middlename'],$student['lastname'],$sch_setting->middlename,$sch_setting->lastname);?></td>
                                <td><?php echo $student['father_name']; ?></td>
                                <td><?php echo $student['category']; ?></td>
                                <td><?php echo $this->lang->line(strtolower($student['gender'])); ?></td>
                                <td>
                                    <div>
                                        <?php
                                      $attendance_status=0;
                                        foreach ($attendence_exam as $attendence_key => $attendence_value) {
                                            $chk = ($student['exam_group_exam_result_attendance'] == $attendence_value) ? "checked='checked'" : "";
                                            $attendance_status = ($student['exam_group_exam_result_attendance'] == $attendence_value) ? 1 : 0;
                                            ?>
                                            <label class="checkbox-inline"><input type="checkbox" class="attendance_chk" name="exam_group_student_attendance_<?php echo $student['exam_group_class_batch_exam_students_id']; ?>" value="<?php echo $attendence_value; ?>" <?php echo $chk; ?>><?php echo $this->lang->line($attendence_value); ?></label>
                                            <?php
                                        }
                                        ?>

                                    </div>
                                </td>
                                <td> 
                                    <input type="number" class="marksssss form-control" name="exam_group_student_mark_<?php echo $student['exam_group_class_batch_exam_students_id']; ?>" value="<?php echo $student['exam_group_exam_result_get_marks']; ?>" step="any" <?php echo ($attendance_status) ? "disabled":"" ?>>
                                </td>
                                <td> <input type="text" class="form-control note" name="exam_group_student_note_<?php echo $student['exam_group_class_batch_exam_students_id']; ?>" value="<?php echo $student['exam_group_exam_result_note']; ?>"></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody></table>
                </div>

                <?php if ($this->rbac->hasPrivilege('exam_marks', 'can_edit')) { ?>
                    <button type="submit" class="allot-fees btn btn-primary btn-sm pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save'); ?>
                    </button>
                <?php } ?>
                <br/>
                <br/>
            </div>
        </div>
        <?php
    } else {
        ?>

        <div class="alert alert-info">
            <?php echo $this->lang->line('no_record_found'); ?>
        </div>
        <?php
    }
    ?>
</form>