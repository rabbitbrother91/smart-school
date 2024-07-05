<div class="content-wrapper">  
    <section class="content-header">
        <h1>
            <i class="fa fa-comment"></i> Enquiry Type --r
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="box border0">
                    <ul class="tablists">
                        <li><a href="#" class="active">Visitor Book Purpose --r</a></li>
                        <li><a href="#">Complaint Type --r</a></li>
                        <li><a href="#">Source --r</a></li>
                        <li><a href="#">Reference --r</a></li>
                    </ul>
                </div>
            </div><!--./col-md-3--> 
            <div class="col-md-4">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Enquiry Type --r</h3>
                    </div><!-- /.box-header -->
                    <form id="form1" action="<?php echo site_url('admin/enquirytype') ?>"   method="post" accept-charset="utf-8" enctype="multipart/form-data" >
                        <div class="box-body">                        
                            <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
                            <div class="form-group">
                                <label for="pwd">Enquiry Type --r</label>
                                <input class="form-control" id="description" name="enquiry_type"  value="<?php echo set_value('enquiry_type'); ?>"/>
                                <span class="text-danger"><?php echo form_error('enquiry_type'); ?></span>
                            </div>  
                            <div class="form-group">
                                <label for="pwd"><?php echo $this->lang->line('description'); ?></label>
                                <textarea class="form-control" id="description" name="description"rows="3"><?php echo set_value('description'); ?></textarea>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div><!--/.col (right) -->
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> Enquiry Type List --r</h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>                                    
                                        <th>Enquiry Type --r</th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($enquiry_type_list)) {
                                        ?>
                                        <?php
                                    } else {
                                        foreach ($enquiry_type_list as $key => $value) {                                         
                                            ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $value['enquiry_type'] ?></a>
                                                    <div class="fee_detail_popover" style="display: none">
                                                        <?php
                                                        if ($value['description'] == "") {
                                                            ?>
                                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <p class="text text-info"><?php echo $value['description']; ?></p>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div></td>
                                                <td class="mailbox-date pull-right">
                                                    <a href="<?php echo base_url(); ?>admin/enquirytype/edit/<?php echo $value['id']; ?>"  class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="<?php echo base_url(); ?>admin/enquirytype/delete/<?php echo $value['id']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');" data-original-title="Delete">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!-- new END -->
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