<div class="content-wrapper">  
    <section class="content-header">
        <h1>
            <i class="fa fa-ioxhost"></i> <?php echo $this->lang->line('front_office'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('complaint', 'can_add') || $this->rbac->hasPrivilege('complaint', 'can_edit')) { ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_complain'); ?></h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo site_url('admin/complaint/edit/' . $complaint_data['id']) ?>"   method="post" accept-charset="utf-8" enctype="multipart/form-data" >
                            <div class="box-body">                              
                                
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('complaint_type'); ?></label>

                                    <select name="complaint" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>  
                                        <?php
                                        foreach ($complaint_type as $key => $value) {
                                            ?>
                                            <option value="<?php echo $value['complaint_type']; ?>" <?php if (set_value('complaint', $complaint_data['complaint_type']) == $value['complaint_type']) echo "selected"; ?>><?php echo $value['complaint_type']; ?></option>
                                        <?php } ?>                                       
                                    </select>
                                    <span class="text-danger"><?php echo form_error('complaint'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="pwd"><?php echo $this->lang->line('source'); ?></label>  
                                    <select name="source" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>  
                                        <?php foreach ($complaintsource as $key => $value) { ?>
                                            <option value="<?php echo $value['source']; ?>"<?php if (set_value('source', $complaint_data['source']) == $value['source']) echo "selected"; ?>><?php echo $value['source']; ?></option>
                                        <?php }
                                        ?>                 
                                    </select>
                                    <span class="text-danger"><?php echo form_error('source'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="pwd"><?php echo $this->lang->line('complain_by'); ?></label> <small class="req"> *</small> 
                                    <input type="text" class="form-control" value="<?php echo set_value('name', $complaint_data['name']); ?>"  name="name">
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="email"><?php echo $this->lang->line('phone'); ?></label> 
                                    <input type="text" class="form-control" value="<?php echo set_value('contact', $complaint_data['contact']); ?>"  name="contact">
                                </div>
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('date'); ?></label>
                                        <input type="text" class="form-control date" value="<?php if($complaint_data['date'] != '0000-00-00'){ echo set_value('date', date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($complaint_data['date']))); } ?>"  name="date" id="date" readonly>
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pwd"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description"rows="3"><?php echo set_value('description', $complaint_data['description']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="pwd"><?php echo $this->lang->line('action_taken'); ?></label>
                                    <input type="text" class="form-control" value="<?php echo set_value('action_taken', $complaint_data['action_taken']); ?>"  name="action_taken">
                                    <span class="text-danger"><?php echo form_error('action_taken'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="pwd"><?php echo $this->lang->line('assigned'); ?></label>
                                    <input type="text" class="form-control" value="<?php echo set_value('assigned', $complaint_data['assigned']); ?>"  name="assigned">
                                    <span class="text-danger"><?php echo form_error('assigned'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="pwd"><?php echo $this->lang->line('note'); ?></label>
                                    <textarea class="form-control" id="description" name="note" name="note" rows="3"><?php echo set_value('note', $complaint_data['note']); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('note'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile"><?php echo $this->lang->line('attach_document'); ?></label>
                                    <div><input class="filestyle form-control" type='file' name='file'  />
                                    </div>
                                    <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>

                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('complaint', 'can_add') || $this->rbac->hasPrivilege('complaint', 'can_edit')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('complaint_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"></div>
                        <div class="mailbox-messages">
                            <div class="table-responsive overflow-visible">
                              <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('complain'); ?> #</th>
                                        <th><?php echo $this->lang->line('complaint_type'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?> </th>
										<th><?php echo $this->lang->line('phone'); ?> </th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
										<th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($complaint_list)) {
                                        ?>

                                        <?php
                                    } else {
                                        foreach ($complaint_list as $key => $value) {
                                            ?>
                                            <tr>
                                                <td class="mailbox-name"><?php echo $value['id']; ?></td>
                                                <td class="mailbox-name"><?php echo $value['complaint_type']; ?></td>
                                                <td class="mailbox-name"><?php echo $value['name']; ?><?php if(!empty($value['email'])){ ?> ( <?php echo $value['email']; ?> )
												<?php } ?> </td>
                                                <td class="mailbox-name"> <?php echo $value['contact']; ?></td>
                                                <td class="mailbox-name white-space-nowrap"> <?php if($value['date'] != '0000-00-00'){ echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['date'])); } ?></td>
                                                <td class="mailbox-date pull-right white-space-nowrap">
                                                
                                                    <a onclick="getRecord(<?php echo $value['id']; ?>)" class="btn btn-default btn-xs" data-target="#complaintdetails" title="<?php echo $this->lang->line('view') ?>" data-toggle="modal"  data-original-title="<?php echo $this->lang->line('view') ?>"><i class="fa fa-reorder"></i></a>        
                                                    
                                                    <?php if ($value['image'] != "") { ?><a href="<?php echo base_url(); ?>admin/complaint/download/<?php echo $value['id']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('download'); ?>"><i class="fa fa-download"></i></a><?php } ?> 
                                                        
                                                    <?php if ($this->rbac->hasPrivilege('complaint', 'can_edit')) { ?>    
                                                        <a href="<?php echo base_url(); ?>admin/complaint/edit/<?php echo $value['id']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ($this->rbac->hasPrivilege('complaint', 'can_delete')) { ?> 
                                                       
                                                            <a href="<?php echo base_url(); ?>admin/complaint/delete/<?php echo $value['id']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');" data-original-title="<?php echo $this->lang->line('delete'); ?>">
                                                                <i class="fa fa-remove"></i>
                                                            </a>
                                                            <?php
                                                        
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table><!-- /.table -->
                          </div>  
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- new END -->
<div id="complaintdetails" class="modal fade" role="dialog">
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
            url: '<?php echo base_url(); ?>admin/complaint/details/' + id,
            success: function (result) {
                $('#getdetails').html(result);
            }
        });
    }
</script>