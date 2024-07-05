<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?> <small><?php echo $this->lang->line('class1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $title; ?></h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url(); ?>category" class="btn btn-primary btn-sm"  data-toggle="tooltip" title="<?php echo $this->lang->line('add'); ?>" >
                                <i class="fa fa-list"> <?php echo $this->lang->line('list'); ?></i>
                            </a>
                        </div>
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
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('category'); ?></label>
                                <input autofocus="" id="category" name="category" placeholder="" type="text" class="form-control"  value="<?php echo set_value('category'); ?>" />
                                <span class="text-danger"><?php echo form_error('category'); ?></span>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="button" id="btnreset" class="btn btn-default"><?php echo $this->lang->line('reset'); ?></button>
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
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

