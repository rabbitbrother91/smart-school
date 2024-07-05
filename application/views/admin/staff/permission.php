<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" >
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('assign_permission'); ?> (<?php echo $staff['name'] ?>) </h3>
                    </div>
                    <form id="form1" action="<?php echo site_url('admin/staff/permission/' . $staff['id']) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
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
                            <input type="hidden" name="staff_id" value="<?php echo $staff['id'] ?>"/>
                            <div class="form-group">
                                <label class="col-lg-2 control-label"><?php echo $this->lang->line('permisssion'); ?></label>
                                <div class="col-lg-10">
                                    <?php
foreach ($userpermission as $userpermission_key => $userpermission_value) {

    if ($userpermission_value->user_permissions_id == 1) {
        ?>
                                            <input type="hidden" name="prev_array[]" value="<?php echo $userpermission_value->id ?>">
                                            <?php
}
    ?>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="module_perm[]" value="<?php echo $userpermission_value->id ?>" <?php echo set_checkbox('module_perm[]', $userpermission_value->id, ($userpermission_value->user_permissions_id == 1) ? true : false) ?>> <?php echo $userpermission_value->name; ?>
                                        </label>
                                        <?php
}
?>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>