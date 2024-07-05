<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <?php echo $this->lang->line('general_form_element'); ?>
            <small><?php echo $this->lang->line('preview'); ?></small>
        </h1>
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
                    <div class="box-body no-padding">

                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <a href="<?php echo base_url(); ?>category/create" class="btn btn-primary btn-sm"  data-toggle="tooltip" title="<?php echo $this->lang->line('add_category'); ?>">
                                <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_category'); ?>
                            </a>
                            <div class="pull-right">
                            </div><!-- /.pull-right -->
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped">
                                <tbody>
                                    <tr>
                                        <td><?php echo $this->lang->line('category'); ?></td>
                                        <td class="mailbox-name"><a href="<?php echo base_url(); ?>category/view/<?php echo $category['id'] ?>"> <?php echo $category['category'] ?></a></td>
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
            <!-- right column -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div>