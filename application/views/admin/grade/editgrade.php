<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-map-o"></i> <?php echo $this->lang->line('examinations'); ?> <small><?php echo $this->lang->line('student_fee1'); ?></small>  </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
if ($this->rbac->hasPrivilege('marks_grade', 'can_add') || $this->rbac->hasPrivilege('marks_grade', 'can_edit')) {
    ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_marks_grade'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form action="<?php echo site_url('admin/grade/edit/' . $id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) {
        ?>
                                    <?php echo $this->session->flashdata('msg');
        $this->session->unset_userdata('msg'); ?>
                                <?php }?>
                                <?php
if (isset($error_message)) {
        echo "<div class='alert alert-danger'>" . $error_message . "</div>";
    }
    ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <input type="hidden" name="id" value="<?php echo set_value('id', $editgrade['id']); ?>" >
                                   <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('exam_type'); ?></label> <small class="req">*</small>
                                    <select id="name" autofocus="" name="exam_type" placeholder="" type="text" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
foreach ($examType as $examType_key => $examType_value) {
        ?>
                                            <option value="<?php echo $examType_key; ?>" <?php echo set_select('exam_type', $examType_key, (set_value('exam_type', $editgrade['exam_type']) == $examType_key) ? true : false); ?>><?php echo $examType_value; ?></option>
                                            <?php
}
    ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('exam_type'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('grade_name'); ?></label><small class="req"> *</small>
                                    <input id="name" name="name" type="text" class="form-control"  value="<?php echo set_value('name', $editgrade['name']); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('percent_upto'); ?></label><small class="req"> *</small>
                                    <input id="mark_from" name="mark_from"  type="text" class="form-control"  value="<?php echo set_value('mark_from', $editgrade['mark_from']); ?>" />
                                    <span class="text-danger"><?php echo form_error('mark_from'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('percent_from'); ?></label><small class="req"> *</small>
                                    <input id="mark_upto" name="mark_upto"  type="text" class="form-control"  value="<?php echo set_value('mark_upto', $editgrade['mark_upto']); ?>" />
                                    <span class="text-danger"><?php echo form_error('mark_upto'); ?></span>
                                </div>
                                 <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('grade_point'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="grade_point" name="grade_point" placeholder="" type="text" class="form-control"  value="<?php echo set_value('grade_point', $editgrade['point']); ?>" />
                                    <span class="text-danger"><?php echo form_error('grade_point'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" rows="3" ><?php echo set_value('description', $editgrade['description']); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('description'); ?></span>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
            <?php }?>
            <div class="col-md-<?php
if ($this->rbac->hasPrivilege('marks_grade', 'can_add') || $this->rbac->hasPrivilege('marks_grade', 'can_edit')) {
    echo "8";
} else {
    echo "12";
}
?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('grade_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-controls">
                            <div class="pull-right">

                            </div><!-- /.pull-right -->
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('grade_list'); ?></div>
                                 <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('exam_type'); ?></th>
                                        <th>
                                            <table width="100%">
                                                <tr>
                                                    <th width="24%"><?php echo $this->lang->line('grade_name'); ?></th>
                                                    <th width="40%"><?php echo $this->lang->line('percent_from_upto'); ?></th>
                                                    <th width="20%"><?php echo $this->lang->line('grade_point'); ?></th>
                                                    <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                                </tr>
                                            </table>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
if (!empty($listgrade)) {
    foreach ($listgrade as $grade) {
        ?>
                                          <tr>
                                                <td class="mailbox-name">
                                                    <a href="#" data-toggle="popover" class="detail_popover" ><?php echo $grade['exm_type_value']; ?></a>
                                                </td>
                                                <td class="mailbox-name">
                                                     <table width="100%">

                                                    <?php
if (!empty($grade['exam_grade_values'])) {
            foreach ($grade['exam_grade_values'] as $grade_key => $grade_value) {
                ?>
                                                                <tr>
                                                                    <td width="24%"><?php echo $grade_value->name ?></td>
                                                                    <td width="40%"><?php echo $grade_value->mark_upto . " " . $this->lang->line('to') . " " . $grade_value->mark_from; ?> </td>
                                                                    <td width="20%"><?php echo $grade_value->point ?> </td>
                                                                    <td class="text-right">
                                                                        <?php
if ($this->rbac->hasPrivilege('marks_grade', 'can_edit')) {
                    ?>
                                                                            <a href="<?php echo base_url(); ?>admin/grade/edit/<?php echo $grade_value->id; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                                <i class="fa fa-pencil"></i>
                                                                            </a>
                                                                            <?php
}
                if ($this->rbac->hasPrivilege('marks_grade', 'can_delete')) {
                    ?>
                                                                            <a href="<?php echo base_url(); ?>admin/grade/delete/<?php echo $grade_value->id; ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                                <i class="fa fa-remove"></i>
                                                                            </a>
                                                                        <?php }?>
                                                                    </td>
</tr>
                                                                    <?php

            }
        }
        ?>
                                                     </table>
                                                </td>
                                          </tr>
                                            <?php
}
}
?>
                                </tbody>
                            </table>
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
        <div class="row">
            <div class="col-md-12">
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
</script>