<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-building-o"></i> <?php echo $this->lang->line('staff_certificate') ; ?>
        </h1> 
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('edit_staff_certificates') ; ?></h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form action="<?php echo site_url('admin/Staffcertificate/edit/' . $editcertificate[0]->id) ?>"  id="certificateform" enctype="multipart/form-data" name="certificateform" method="post" accept-charset="utf-8">
                        <div class="box-body">
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
                            <input type="hidden" name="id" value="<?php echo set_value('id', $editcertificate[0]->id); ?>" >
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('certificate_name') ; ?> *</label>
                                <input autofocus="" id="certificate_name" name="certificate_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('certificate_name', $editcertificate[0]->certificate_name); ?>" />
                                <span class="text-danger"><?php echo form_error('certificate_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('header_left_text') ; ?></label>
                                <input autofocus="" id="left_header" name="left_header" placeholder="" type="text" class="form-control"  value="<?php echo set_value('left_header', $editcertificate[0]->left_header); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('header_center_text') ; ?></label>
                                <input autofocus="" id="center_header" name="center_header" placeholder="" type="text" class="form-control"  value="<?php echo set_value('center_header', $editcertificate[0]->center_header); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('header_right_text') ; ?></label>
                                <input autofocus="" id="right_header" name="right_header" placeholder="" type="text" class="form-control"  value="<?php echo set_value('right_header', $editcertificate[0]->right_header); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('certificate_text') ; ?> * </label>
                                <textarea class="form-control" id="certificate_text" name="certificate_text" placeholder="" rows="3" placeholder=""><?php echo set_value('certificate_name', $editcertificate[0]->certificate_text); ?></textarea>
                                <span class="text-danger"><?php echo form_error('certificate_text'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('footer_left_text') ; ?></label>
                                <input autofocus="" id="left_footer" name="left_footer" placeholder="" type="text" class="form-control"  value="<?php echo set_value('left_footer', $editcertificate[0]->left_footer); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('footer_center_text') ; ?></label>
                                <input autofocus="" id="center_footer" name="center_footer" placeholder="" type="text" class="form-control"  value="<?php echo set_value('center_footer', $editcertificate[0]->center_footer); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('footer_right_text') ; ?></label>
                                <input autofocus="" id="right_footer" name="right_footer" placeholder="" type="text" class="form-control"  value="<?php echo set_value('right_footer', $editcertificate[0]->right_footer); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('change_background') ; ?></label>
                                <input id="documents" placeholder="" type="file" class="filestyle form-control" data-height="40"  name="background_image">
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div><!--/.col (right) -->
            <!-- left column -->
            <div class="col-md-8">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('staff_certificate_list') ; ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-controls">
                            <div class="pull-right">
                            </div><!-- /.pull-right -->
                        </div>
                        <div class="table-responsive mailbox-messages overflow-visible">
                            <div class="download_label"><?php echo $this->lang->line('staff_certificate_list') ; ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('certificate_name') ; ?></th>
                                        <th><?php echo $this->lang->line('background') ; ?></th>
                                        <th><?php echo $this->lang->line('action') ; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($certificateList)) {
                                        ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($certificateList as $certificate) {
                                            ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <a href="#" data-toggle="popover" class="detail_popover" ><?php echo $certificate->certificate_name; ?></a>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php if ($certificate->background_image != '' && !is_null($certificate->background_image)) { ?>
                                                        <img src="<?php echo $this->media_storage->getImageURL('uploads/certificate/'.$certificate->background_imag); ?>" width="40">
                                                    <?php } else { ?>
                                                        <i class="fa fa-picture-o fa-3x" aria-hidden="true"></i>
                                                    <?php } ?>
                                                </td>
                                                <td class="mailbox-date pull-right no-print white-space-nowrap">
                                                    <a href="<?php echo base_url(); ?>admin/Staffcertificate/edit/<?php echo $certificate->id ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="<?php echo base_url(); ?>admin/certificate/delete/<?php echo $certificate->id ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        $count++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
        <div class="row">
            <div class="col-md-12">
            </div><!--/.col (right) -->
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