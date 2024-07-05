<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-th"></i> <?php echo $this->lang->line('manage'); ?><small><?php echo $this->lang->line('section'); ?></small>        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $title; ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <a href="<?php echo base_url(); ?>sections/create" class="btn btn-primary btn-sm"  data-toggle="tooltip" title="Add Section">
                                <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_section'); ?>
                            </a>
                            <div class="pull-right">
                            </div><!-- /.pull-right -->
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped">
                                <tbody>
                                    <tr>
                                        <td><?php echo $this->lang->line('section'); ?></td>
                                        <td class="mailbox-name"><a href="<?php echo base_url(); ?>sections/view/<?php echo $section['id'] ?>"> <?php echo $section['section'] ?></a></td>
                                    </tr>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <div class="pull-right">
                            </div><!-- /.pull-right -->
                        </div>
                    </div>
                </div>
            </div>
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div>