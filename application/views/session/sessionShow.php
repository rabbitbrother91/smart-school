<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('session_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body no-padding">
                        <div class="mailbox-controls">                         
                            <a href="<?php echo base_url(); ?>sessions/create" class="btn btn-primary btn-sm"  data-toggle="tooltip" title="<?php echo $this->lang->line('add_session'); ?>">
                                <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_session'); ?>
                            </a>
                            <div class="pull-right">
                            </div>
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped">
                                <tbody>
                                    <tr>
                                        <td><?php echo $this->lang->line('session'); ?></td>
                                        <td class="mailbox-name"><a href="<?php echo base_url(); ?>sessions/view/<?php echo $session['id'] ?>"> <?php echo $session['session'] ?></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="mailbox-controls">                          
                            <div class="pull-right">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </section>
</div>