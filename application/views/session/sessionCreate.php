<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('add_session'); ?></h3>
                    </div>   
                    <form action="<?php echo site_url('sessions/create') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php 
                                    echo $this->session->flashdata('msg'); 
                                    $this->session->unset_userdata('msg');
                                ?>
                            <?php } ?>     
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('session'); ?></label>
                                <input autofocus="" id="session" name="session" placeholder="" type="text" class="form-control"  value="<?php echo set_value('session'); ?>" />
                                <span class="text-danger"><?php echo form_error('session'); ?></span>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="button" class="btn btn-default"><?php echo $this->lang->line('cancel'); ?></button>
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>  
            </div>
        </div> 
    </section>
</div>