<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <form id="form1" action="<?php echo base_url() ?>admin/notification/edit/<?php echo $id ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <?php
$prev_roles = explode(',', $notification['roles']);
foreach ($prev_roles as $prev_roles_key => $prev_roles_value) {
    ?>
                        <input type="hidden" name="prev_roles[]" value="<?php echo $prev_roles_value; ?>">
                        <?php
}
?>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-commenting-o"></i> <?php echo $this->lang->line('edit_message'); ?></h3>
                        </div>
                        <div class="box-body row pb0">
                            <?php if ($this->session->flashdata('msg')) {
    ?>
                                <?php echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg'); ?>
                            <?php }?>
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-md-9">
                              <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('title'); ?><small class="req"> *</small></label>
                                        <input autofocus="" id="title" name="title" placeholder="" type="text" class="form-control"  value="<?php echo set_value('title', $notification['title']); ?>" />
                                        <span class="text-danger"><?php echo form_error('title'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('notice_date'); ?><small class="req"> *</small></label>
                                        <input id="date" name="date"  placeholder="" type="text" class="form-control date"  value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($notification['date']))); ?>" />
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('publish_on'); ?><small class="req"> *</small></label>
                                        <input id="publish_date" name="publish_date"  placeholder="" type="text" class="form-control date"  value="<?php echo set_value('publish_date', date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($notification['publish_date']))); ?>" />
                                        <span class="text-danger"><?php echo form_error('publish_date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('attachment'); ?></label>
                                        <input type="file" id="file" name="file" class="form-control filestyle" autocomplete="off" />
                                        <span class="text-danger"><?php echo form_error('file'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('message'); ?><small class="req"> *</small></label>
                                        <textarea id="compose-textarea" name="message" class="form-control" style="height: 300px">
                                            <?php echo set_value('message', $notification['message']); ?>
                                        </textarea>
                                        <span class="text-danger"><?php echo form_error('message'); ?></span>
                                    </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-3">
                                <div class="box-body">
                                    <?php
if (isset($error_message)) {
    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
}
?>
                                    <div class="form-horizontal">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('message_to'); ?><small class="req"> *</small></label>
                                        <div class="checkbox">
                                            <label><input type="checkbox" name="visible[]" value="student" <?php echo set_checkbox('visible[]', 'student', (set_value('visible[]', $notification['visible_student']) == 'Yes') ? true : false); ?> /> <b><?php echo $this->lang->line('student'); ?></b> </label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" name="visible[]"  value="parent"  <?php echo set_checkbox('visible[]', 'student', (set_value('visible[]', $notification['visible_parent']) == 'Yes') ? true : false); ?>  /> <b><?php echo $this->lang->line('parent'); ?></b></label>
                                        </div>
                                        <?php
foreach ($roles as $role_key => $role_value) {
    ?>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="visible[]" value="<?php echo $role_value['id']; ?>"
                                                    <?php echo set_checkbox('visible[]', $role_value['id'], (set_value('visible[]', in_array($role_value['id'], $prev_roles)) == 1) ? true : false); ?>
                                                           /> <b><?php echo $role_value['name']; ?></b>
                                                </label>
                                            </div>
                                            <?php
}
?>
                                    </div>
                                    <span class="text-danger"><?php echo form_error('visible[]'); ?></span>
                                </div>
                            </div>
                            <div class="box-footer" style="clear: both;">
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-envelope-o"></i> <?php echo $this->lang->line('send'); ?> </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            </div>
        </div>
    </section>
</div>

<script>
    $(function () {
        $("#compose-textarea").wysihtml5();
    });
</script>