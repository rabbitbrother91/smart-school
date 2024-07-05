<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- left column -->
                <form id="form1" action="<?php echo base_url() ?>admin/notification/add" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-commenting-o"></i> <?php echo $this->lang->line('compose_new_message'); ?></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <?php if ($this->session->flashdata('msg')) {
    ?>
                                    <?php echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg'); ?>
                                <?php }?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                  <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                            <input autofocus="" id="title" name="title" placeholder="" type="text" class="form-control"  value="<?php echo set_value('title'); ?>" />
                                            <span class="text-danger"><?php echo form_error('title'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('notice_date'); ?></label><small class="req"> *</small>
                                            <input id="date" name="date"  placeholder="" type="text" class="form-control date"  value="<?php echo set_value('date'); ?>" />
                                            <span class="text-danger"><?php echo form_error('date'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('publish_on'); ?></label><small class="req"> *</small>
                                            <input id="publish_date" name="publish_date"  placeholder="" type="text" class="form-control date"  value="<?php echo set_value('publish_date'); ?>" />
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
                                            <label><?php echo $this->lang->line('message'); ?></label><small class="req"> *</small>
                                            <textarea id="compose-textarea" name="message" class="form-control" style="height: 300px">
                                                <?php echo set_value('message'); ?>
                                            </textarea>
                                            <span class="text-danger"><?php echo form_error('message'); ?></span>
                                        </div>
                                    </div>
                                   </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="">
                                        <?php
if (isset($error_message)) {
    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
}
?>
                                        <div class="form-horizontal">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('message_to'); ?></label>
                                            <div class="checkbox">
                                                <label><input type="checkbox" name="visible[]" value="student" <?php echo set_checkbox('visible[]', 'student', false) ?> /> <b><?php echo $this->lang->line('student'); ?></b> </label>
                                            </div>
                                            <div class="checkbox">
                                                <label><input type="checkbox" name="visible[]"  value="parent" <?php echo set_checkbox('visible[]', 'parent', false) ?> /> <b><?php echo $this->lang->line('parent'); ?></b></label>
                                            </div>
                                            <?php
foreach ($roles as $role_key => $role_value) {
    $userdata = $this->customlib->getUserData();
    $role_id  = $userdata["role_id"];
    ?>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" name="visible[]" value="<?php echo $role_value['id']; ?>" <?php
if ($role_value["id"] == $role_id) {
        echo "checked";
    }
    ?>  <?php echo set_checkbox('visible[]', $role_value['id'], false) ?> /> <b><?php echo $role_value['name']; ?></b> </label>
                                                </div>
                                                <?php
}
?>
                                        </div>
                                        <span class="text-danger"><?php echo form_error('visible[]'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="pull-right">
                                <button type="submit" id="submitbtn" class="btn btn-primary"><i class="fa fa-envelope-o"></i> <?php echo $this->lang->line('send'); ?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div><!--./wrapper-->
        <div class="row">
            <div class="col-md-12">
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#btnreset").click(function () {
            $("#form1")[0].reset();
        });
    });
</script>

<script>
    $(function () {
        $("#compose-textarea").wysihtml5();
    });
</script>

<script>
    $(function(){
        $('#form1'). submit( function() {
            $("#submitbtn").button('loading');
        });
    })
</script>