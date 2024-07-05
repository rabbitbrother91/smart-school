<div class="error_connection">
</div>
<form method="POST" action="<?php echo site_url('admin/examgroup/ajaxConnectForm'); ?>" id="connectExamForm">
    <input type="hidden" name="examgroup_id" value="<?php echo $examgroup_id; ?>">
    <table class="table table-strippedn table-hover mb10">
        <thead>
            <tr class="active">
                <th width="20">
                    <input type="checkbox" class="select-all checkbox" id="ckbCheckAll"/>
                </th>
                <th><?php echo $this->lang->line('exam'); ?></th>
                <th><?php echo $this->lang->line('weightage') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($examList)) {
                foreach ($examList as $exam_key => $exam_value) {
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" class="checkbox checkBoxExam" name="<?php echo "exam[]" ?>" <?php echo ($exam_value->exam_group_exam_connection_id > 0) ? "checked='checked'" : ""; ?> value="<?php echo $exam_value->id ?>" />
                        </td>
                        <td>
                            <?php echo $exam_value->exam; ?>
                        </td>
                        <td>
                            <input type="number" class="form-control" value="<?php echo $exam_value->exam_weightage; ?>" name="exam_<?php echo $exam_value->id ?>">
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <?php echo $this->lang->line('no_exam_found'); ?>
                <?php
            }
            ?>
        </tbody>
    </table>
    <div class="row">
        <button type="submit" class="btn btn-danger" id="load" name="reset" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('saving'); ?>"><?php echo $this->lang->line('reset_link_exam'); ?></button>
        <div class="pull-right">
            <?php
            if ($this->rbac->hasPrivilege('link_exam', 'can_edit')) {
                ?>
                <button type="submit" class="btn btn-primary" id="load" name="save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('saving'); ?>"><?php echo $this->lang->line('save'); ?></button>
                <?php
            }
            ?>
        </div>
    </div>
</form>