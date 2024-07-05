<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('add_language'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?php echo site_url('admin/language/create') ?>"  id="employeeform" name="employeeform" class="form-horizontal form-label-left" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <div class="row">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1"><?php echo $this->lang->line('language'); ?> <small class="req">  *</small></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input autofocus="" id="language" name="language"  type="text" placeholder="" class="form-control"  value="<?php echo set_value('language'); ?>" />
                                        <span class="text-danger"><?php echo form_error('language'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1"><?php echo $this->lang->line('language_short_code'); ?> <small class="req"> *</small></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input autofocus="" id="short_code" name="short_code"  type="text" placeholder="" class="form-control"  value="<?php echo set_value('short_code'); ?>" />
                                        <span class="text-danger"><?php echo form_error('short_code'); ?></span>
                                    </div> </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1"><?php echo $this->lang->line('country_code'); ?> <small class="req"> *</small></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input autofocus="" id="country_code" name="country_code" type="text" placeholder="" class="form-control" value="<?php echo set_value('country_code'); ?>" />
                                        <span class="text-danger"><?php echo form_error('country_code'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-md-offset-3">
                                    <button type="submit" class="btn btn-info float-sm-right ml-n1"><?php echo $this->lang->line('save'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    $(document).ready(function () {
        $("#btnreset").click(function () {
            $("#form1")[0].reset();
        });
    });
</script>