<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?php echo $this->lang->line('general_form_element'); ?>
            <small><?php echo $this->lang->line('preview'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $title; ?></h3>
                    </div><!-- /.box-header -->
                    <form action="<?php echo site_url("admin/exam/edit/" . $id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <?php echo validation_errors(); ?>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('subject_name'); ?></label>
                                <input autofocus="" id="name" name="name" placeholder="name" type="text" class="form-control"  value="<?php echo set_value('name', $exam['name']); ?>" />
                                <span class="text-danger"><?php echo form_error('name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('note'); ?></label>
                                <textarea class="form-control" rows="3" id="note" name="note" placeholder="note" placeholder="Enter ..."><?php echo set_value('note', $exam['note']); ?></textarea>
                                <span class="text-danger"><?php echo form_error('note'); ?></span>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="button" class="btn btn-default"><?php echo $this->lang->line('cancel'); ?></button>
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->