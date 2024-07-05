<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-building-o"></i> <?php //echo $this->lang->line('hostel'); ?> <small><?php //echo $this->lang->line('student_fees1'); ?></small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('room_type', 'can_add') || $this->rbac->hasPrivilege('room_type', 'can_edit')) {
    ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_room_type'); ?></h3>
                        </div>
                        <form action="<?php echo site_url('admin/roomtype/edit/' . $id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) {?>
                                    <?php echo $this->session->flashdata('msg');
        $this->session->unset_userdata('msg'); ?>
                                <?php }?>
                                <?php
if (isset($error_message)) {
        echo "<div class='alert alert-danger'>" . $error_message . "</div>";
    }
    ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <input type="hidden" name="id" value="<?php echo set_value('id', $roomtype['id']); ?>" >
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('room_type'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="amount" name="room_type" placeholder="" type="text" class="form-control"  value="<?php echo set_value('room_type', $roomtype['room_type']); ?>" />
                                    <span class="text-danger"><?php echo form_error('room_type'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('description', $roomtype['description']); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('description'); ?></span>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php }?>
            <div class="col-md-<?php
if ($this->rbac->hasPrivilege('room_type', 'can_add') || $this->rbac->hasPrivilege('room_type', 'can_edit')) {
    echo "8";
} else {
    echo "12";
}
?>">
                <div class="box box-primary" id="rtype">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('room_type_list'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                            <div class="pull-right">
                            </div><!-- /.pull-right -->
                        </div>
                        <div class="mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('room_type_list'); ?></div>
                            <div class="table-responsive overflow-visible">
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('room_type'); ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($roomtypelist)) {
    ?>

                                            <?php
} else {
    $count = 1;
    foreach ($roomtypelist as $roomtype) {
        ?>
                                                <tr>
                                                    <td class="mailbox-name">
                                                        <a href="#" data-toggle="popover" class="detail_popover"><?php echo $roomtype['room_type'] ?></a>
                                                        <div class="fee_detail_popover" style="display: none">
                                                            <?php
if ($roomtype['description'] == "") {
            ?>
                                                                <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                <?php
} else {
            ?>
                                                                <p class="text text-info"><?php echo $roomtype['description']; ?></p>
                                                                <?php
}
        ?>
                                                        </div>
                                                    </td>
                                                    <td class="mailbox-date pull-right no-print">
                                                        <?php if ($this->rbac->hasPrivilege('room_type', 'can_edit')) {?>
                                                            <a href="<?php echo base_url(); ?>admin/roomtype/edit/<?php echo $roomtype['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        <?php }if ($this->rbac->hasPrivilege('room_type', 'can_delete')) {?>
                                                            <a href="<?php echo base_url(); ?>admin/roomtype/delete/<?php echo $roomtype['id'] ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                <i class="fa fa-remove"></i>
                                                            </a>
                                                        <?php }?>
                                                    </td>
                                                </tr>
                                                <?php
}
    $count++;
}
?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
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