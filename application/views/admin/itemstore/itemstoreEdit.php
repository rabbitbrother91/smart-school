<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-object-group"></i> <?php //echo $this->lang->line('item_store'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('store', 'can_add') || $this->rbac->hasPrivilege('store', 'can_edit')) {?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_item_store'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form action="<?php echo site_url("admin/itemstore/edit/" . $id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php echo validation_errors(); ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('item_store_name'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="name" name="name" placeholder="name" type="text" class="form-control"  value="<?php echo set_value('itemstore', $itemstore['item_store']); ?>" />
                                    <span class="text-danger"><?php echo form_error('itemstore'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('item_store_code'); ?></label>
                                    <input id="code" name="code" placeholder="code" type="text" class="form-control"  value="<?php echo set_value('itemstore', $itemstore['code']); ?>" />
                                    <span class="text-danger"><?php echo form_error('itemstore'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('description', $itemstore['description']); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('description'); ?></span>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div><!--/.col (right) -->
            <?php }?>
            <div class="col-md-<?php
if ($this->rbac->hasPrivilege('store', 'can_add') || $this->rbac->hasPrivilege('store', 'can_edit')) {
    echo "8";
} else {
    echo "12";
}
?>">
                <!-- general form elements -->
                <div class="box box-primary" id="exphead">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('item_store_list'); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body  ">
                        <div class="mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('item_store_list'); ?></div>
                            <div class="table-responsive overflow-visible">
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('item_store_name'); ?></th>
                                            <th><?php echo $this->lang->line('item_store_code'); ?></th>
                                            <th width="40%"><?php echo $this->lang->line('description'); ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($itemstorelist)) {
    ?>

                                            <?php
} else {
    $count = 1;
    foreach ($itemstorelist as $store) {
        ?>
                                                <tr>
                                                     
                                                    <td class="mailbox-name"><?php echo $store['item_store']; ?></td>
                                                    <td class="mailbox-name">
                                                        <?php echo $store['code'] ?>
                                                    </td>
                                                    <td class="mailbox-name"><?php echo $store['description']; ?></td>

                                                    <td class="mailbox-date pull-right no-print">
                                                        <?php if ($this->rbac->hasPrivilege('store', 'can_edit')) {?>
                                                            <a href="<?php echo base_url(); ?>admin/itemstore/edit/<?php echo $store['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        <?php }if ($this->rbac->hasPrivilege('store', 'can_delete')) {?>
                                                            <a href="<?php echo base_url(); ?>admin/itemstore/delete/<?php echo $store['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
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
                                </table><!-- /.table -->
                            </div>
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div>
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