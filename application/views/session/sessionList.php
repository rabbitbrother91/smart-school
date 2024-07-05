<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('session_setting', 'can_add')) { ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_session'); ?></h3>
                        </div>
                        <form id="form1" action="<?php echo site_url('sessions/create') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php 
                                        echo $this->session->flashdata('msg');
                                        $this->session->unset_userdata('msg');
                                    ?>
                                <?php } ?> 
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group mb5">
                                    <label><?php echo $this->lang->line('session'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="session" name="session" placeholder="" type="text" class="form-control"  value="<?php echo set_value('session'); ?>" />
                                    <span class="text-danger"><?php echo form_error('session'); ?></span>
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
            if ($this->rbac->hasPrivilege('session_setting', 'can_add')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">                
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('session_list'); ?></h3>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('list_msg')) { ?>
                            <?php 
                                echo $this->session->flashdata('list_msg');
                                $this->session->unset_userdata('list_msg');
                            ?>
                        <?php } ?>
                        <div class="mailbox-messages">
                            <div class="">
                                <div class="download_label"><?php echo $this->lang->line('session_list'); ?></div>
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('session'); ?></th>
                                            <th><?php echo $this->lang->line('status'); ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $count = 1;
                                        foreach ($sessionlist as $session) {
                                            ?>
                                            <tr>
                                                <td class="mailbox-name"><?php echo $session['session'] ?></td>
                                                <td class="mailbox-name"><?php
                                                    if ($session['active'] != 0) {
                                                        ?>
                                                        <span class="label bg-green font-weight-normal"><?php echo $this->lang->line('active'); ?></span>
                                                        <?php
                                                    } else {
                                                        
                                                    }
                                                    ?></td>
                                                <td class="mailbox-date text-right">
                                                    <?php if ($this->rbac->hasPrivilege('session_setting', 'can_edit')) { ?>
                                                        <a href="<?php echo base_url(); ?>sessions/edit/<?php echo $session['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php } if ($this->rbac->hasPrivilege('session_setting', 'can_delete')) { ?>
                                                        <a href="<?php echo base_url(); ?>sessions/delete/<?php echo $session['id'] ?>"class="btn btn-default btn-xs <?php
                                                        if ($session['active'] != 0) {
                                                            echo'disabled';
                                                        }
                                                        ?>"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $count++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div> 
                    </div>

                </div>
            </div>

        </div> 
    </section>
</div>
<script type="text/javascript">
    $("#btnreset").click(function () {
        $("#form1")[0].reset();
    });
</script>