<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('add_book'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('import_book', 'can_view')) {
                                ?>
                                <a class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>admin/book/import" autocomplete="off"><i class="fa fa-plus"></i> <?php echo $this->lang->line('import_book'); ?></a> 
                            <?php }
                            ?>
                        </div>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?php echo site_url('admin/book/create') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                        <div class="box-body row">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php 
                                    echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg');
                                ?>
                            <?php } ?>
                            <?php
                            if (isset($error_message)) {
                                echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                            }
                            ?>      
                            <?php echo $this->customlib->getCSRF(); ?>                     
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('book_title'); ?></label><small class="req"> *</small>
                                <input autofocus=""  id="book_title" name="book_title" placeholder="" type="text" class="form-control"  value="<?php echo set_value('book_title'); ?>" />
                                <span class="text-danger"><?php echo form_error('book_title'); ?></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('book_number'); ?></label>
                                <input id="book_no" name="book_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('book_no'); ?>" />
                                <span class="text-danger"><?php echo form_error('book_no'); ?></span>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('isbn_number'); ?></label>
                                <input id="isbn_no" name="isbn_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('isbn_no'); ?>" />
                                <span class="text-danger"><?php echo form_error('isbn_no'); ?></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('publisher'); ?></label>
                                <input id="publish" name="publish" placeholder="" type="text" class="form-control"  value="<?php echo set_value('publish'); ?>" />
                                <span class="text-danger"><?php echo form_error('publish'); ?></span>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('author'); ?></label>
                                <input id="author" name="author" placeholder="" type="text" class="form-control"  value="<?php echo set_value('author'); ?>" />
                                <span class="text-danger"><?php echo form_error('author'); ?></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('subject'); ?></label>
                                <input id="subject" name="subject" placeholder="" type="text" class="form-control"  value="<?php echo set_value('subject'); ?>" />
                                <span class="text-danger"><?php echo form_error('subject'); ?></span>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('rack_number'); ?></label>
                                <input id="rack_no" name="rack_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('rack_no'); ?>" />
                                <span class="text-danger"><?php echo form_error('rack_no'); ?></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('qty'); ?></label>
                                <input id="qty" name="qty" placeholder="" type="text" class="form-control"  value="<?php echo set_value('qty'); ?>" />
                                <span class="text-danger"><?php echo form_error('qty'); ?></span>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('book_price'); ?> (<?php echo $currency_symbol; ?>)</label>
                                <input id="perunitcost" name="perunitcost" placeholder="" type="text" class="form-control"  value="<?php echo set_value('perunitcost'); ?>" />
                                <span class="text-danger"><?php echo form_error('perunitcost'); ?></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('post_date'); ?></label>
                                <input id="postdate" name="postdate"  placeholder="" type="text" class="form-control date"  value="<?php echo set_value('postdate', date($this->customlib->getSchoolDateFormat())); ?>" />
                                <span class="text-danger"><?php echo form_error('postdate'); ?></span>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('description'); ?></textarea>
                                <span class="text-danger"><?php echo form_error('description'); ?></span>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div><!--/.col (right) -->
        </div>
        <div class="row">
            <div class="col-md-12">
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    $(document).ready(function () {
        $("#btnreset").click(function () {
            /* Single line Reset function executes on click of Reset Button */
            $("#form1")[0].reset();
        });

    });
</script>
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
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/savemode.js"></script>