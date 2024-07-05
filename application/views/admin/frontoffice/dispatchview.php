<div class="content-wrapper" style="min-height: 348px;">  
    <section class="content-header">
        <h1>
            <i class="fa fa-ioxhost"></i> <?php echo $this->lang->line('front_office'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('postal_dispatch', 'can_add')) { ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_postal_dispatch'); ?></h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo site_url('admin/dispatch') ?>"   method="post" accept-charset="utf-8" enctype="multipart/form-data" >
                            <div class="box-body">                        
                                <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
                                <div class="form-group">
                                    <label for="pwd"><?php echo $this->lang->line('to_title'); ?></label>  <small class="req"> *</small>  
                                    <input type="text" class="form-control" value="<?php echo set_value('to_title'); ?>" name="to_title">
                                    <span class="text-danger"><?php echo form_error('to_title'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('reference_no'); ?></label>
                                    <input type="text" class="form-control" value="<?php echo set_value('ref_no'); ?>" name="ref_no">
                                </div>
                                <div class="form-group">
                                    <label for="pwd"><?php echo $this->lang->line('address'); ?></label>
                                    <textarea class="form-control" id="description"  name="address" rows="3"><?php echo set_value('address'); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="email"><?php echo $this->lang->line('note'); ?></label> 
                                    <textarea class="form-control" id="description" name="note" name="note" rows="3"><?php echo set_value('note'); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('from_title'); ?></label>     <input type="text" class="form-control" value="<?php echo set_value('from'); ?>"  name="from">
                                        <span class="text-danger"><?php echo form_error('from'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pwd"><?php echo $this->lang->line('date'); ?></label>
                                    <input id="date" name="date" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly" />
                                    <span class="text-danger"><?php echo form_error('date'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile"><?php echo $this->lang->line('attach_document'); ?></label>
                                    <div><input class="filestyle form-control" type='file' name='file'  />
                                    </div>
                                    <span class="text-danger"><?php echo form_error('file'); ?></span>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right" id="submitbtn"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('postal_dispatch', 'can_add')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('postal_dispatch_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('postal_dispatch'); ?> <?php echo $this->lang->line('list'); ?></div>
                        <div class="mailbox-messages table-responsive overflow-visible-lg">
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>                                    
                                        <th><?php echo $this->lang->line('to_title'); ?></th>
                                        <th><?php echo $this->lang->line('reference_no'); ?></th>
                                        <th><?php echo $this->lang->line('from_title'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($DispatchList)) {
                                        ?>
                                        <?php
                                    } else {
                                        foreach ($DispatchList as $key => $value) {
                                          
                                            ?>
                                            <tr>
                                                <td class="mailbox-name"><?php echo $value->to_title; ?></td>
                                                <td class="mailbox-name"><?php echo $value->reference_no; ?></td>
                                                <td class="mailbox-name"> <?php echo $value->from_title; ?></td>
                                                <td class="mailbox-name"> <?php if($value->date){ echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->date)); } ?></td>
                                                <td class="mailbox-date pull-right white-space-nowrap">
                                                    <a onclick="getRecord('<?php echo $value->id; ?>')" class="btn btn-default btn-xs" data-target="#receviedetails" data-toggle="modal" data-original-title="<?php echo $this->lang->line('view')?>">
                                                        <i class="fa fa-reorder"></i>
                                                    </a>
                                                    <?php if ($value->image != "") { ?>
                                                        <a href="<?php echo base_url(); ?>admin/dispatch/download/<?php echo $value->id; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('download') ?>">
                                                            <i class="fa fa-download"></i>
                                                        </a>  <?php } ?>
                                                    <?php if ($this->rbac->hasPrivilege('postal_dispatch', 'can_edit')) { ?>                                                   
                                                        <a href="<?php echo base_url(); ?>admin/dispatch/editdispatch/<?php echo $value->id; ?>"  class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('edit') ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        <?php
                                                    }
                                                    if ($this->rbac->hasPrivilege('postal_dispatch', 'can_delete')) {
                                                        ?>
                                                        <a href="<?php echo base_url(); ?>admin/dispatch/delete/<?php echo $value->id; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');" data-original-title="<?php echo $this->lang->line('delete') ?>">
                                                            <i class="fa fa-remove"></i>
                                                        </a> 
                                                    <?php } ?>
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
<div id="receviedetails" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('details'); ?></h4>
            </div>
            <div class="modal-body" id="getdetails">
            </div>
        </div>
    </div>
</div>
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    function getRecord(id) {       
        $.ajax({
            url: '<?php echo base_url(); ?>admin/dispatch/details/' + id + '/dispatch',
            success: function (result) {                
                $('#getdetails').html(result);
            }
        });
    }
</script>
<script>
    $(function(){
        $('#form1'). submit( function() {        
            $("#submitbtn").button('loading');
        });
    })
</script>
