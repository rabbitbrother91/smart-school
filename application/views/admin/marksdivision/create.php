<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <?php
if ($this->rbac->hasPrivilege('marks_division', 'can_add')) {
    ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_marks_division'); ?></h3>
                        </div>
                        <form id="form1" action="<?php echo site_url('admin/marksdivision') ?>" name="employeeform" method="post" accept-charset="utf-8">
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

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('division_name'); ?><small class="req"> *</small></label>
                                    <input  id="name" name="name" placeholder="" type="text" class="form-control" value="<?php echo set_value('name'); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('percent_from'); ?><small class="req"> *</small></label>
                                    <input id="percentage_from" name="percentage_from" placeholder="" type="text" class="form-control"  value="<?php echo set_value('percentage_from'); ?>" />
                                    <span class="text-danger"><?php echo form_error('percentage_from'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('percent_upto'); ?><small class="req"> *</small></label>
                                    <input id="percentage_to" name="percentage_to" placeholder="" type="text" class="form-control"  value="<?php echo set_value('percentage_to'); ?>" />
                                    <span class="text-danger"><?php echo form_error('percentage_to'); ?></span>
                                </div>
                            </div><!--./box-body-->

                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>

            <?php }?>
            <div class="col-md-<?php
if ($this->rbac->hasPrivilege('marks_division', 'can_add')) {
    echo "8";
} else {
    echo "12";
}
?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('division_list'); ?></h3>
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

                             <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('division_name'); ?></th>
                                        <th><?php echo $this->lang->line('percentage_from'); ?></th>
                                        <th><?php echo $this->lang->line('percentage_upto'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
$count = 1;
foreach ($division_list as $division) {
    ?>
                                        <tr>
                                            <td class="mailbox-name"><a href="#"> <?php echo $division->name ?></a></td>
                                            <td class="mailbox-name"> <?php echo $division->percentage_from ?></td>
                                            <td class="mailbox-name"> <?php echo $division->percentage_to ?></td>
                                            <td class="mailbox-date pull-right">
                                               <?php if ($this->rbac->hasPrivilege('marks_division', 'can_edit')) {?>
                                                <a href="<?php echo site_url('admin/marksdivision/edit/' . $division->id); ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            <?php }if ($this->rbac->hasPrivilege('marks_division', 'can_delete')) {?>
                                                <a href="<?php echo site_url('admin/marksdivision/delete/' . $division->id); ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            <?php }?>
                                            </td>
                                        </tr>
                                        <?php
$count++;
}
?>
                                </tbody>
                            </table>
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>
</div>

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