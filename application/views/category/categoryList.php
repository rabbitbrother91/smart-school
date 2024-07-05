<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?> <small><?php echo $this->lang->line('class1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('student_categories', 'can_add')) {
                ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('create_category'); ?></h3>
                        </div> 
                        <form id="form1" action="<?php echo site_url('category/create') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php 
                                    if ($this->session->flashdata('msg')) { 
                                        echo $this->session->flashdata('msg');
                                        $this->session->unset_userdata('msg');
                                    } 
                                ?>    
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('category'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="category" name="category" placeholder="" type="text" class="form-control"  value="<?php echo set_value('category'); ?>" />
                                    <span class="text-danger"><?php echo form_error('category'); ?></span>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>  
                </div> 
            <?php } ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('student_categories', 'can_add')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">             
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('category_list'); ?></h3>           
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('category_list'); ?></div>
                        <div class="table-responsive mailbox-messages overflow-visible">

                            <?php 
                                if ($this->session->flashdata('msgdelete')) { 
                                    echo $this->session->flashdata('msgdelete');
                                    $this->session->unset_userdata('msgdelete');

                                } 
                            ?>

                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('category'); ?></th>
                                        <th><?php echo $this->lang->line('category_id'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($categorylist as $category) {
                                        ?>
                                        <tr>
                                            <td class="mailbox-name"><?php echo $category['category'] ?></td>
                                            <td class="mailbox-name"><?php echo $category['id'] ?></td>
                                            <td  class="mailbox-date pull-right">
                                                <?php
                                                if ($this->rbac->hasPrivilege('student_categories', 'can_edit')) {
                                                    ?>
                                                    <a href="<?php echo base_url(); ?>category/edit/<?php echo $category['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php
                                                if ($this->rbac->hasPrivilege('student_categories', 'can_delete')) {
                                                    ?>
                                                    <a href="<?php echo base_url(); ?>category/delete/<?php echo $category['id'] ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    $count++;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </section>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#btnreset").click(function () {
            $("#form1")[0].reset();
        });
    });
</script>