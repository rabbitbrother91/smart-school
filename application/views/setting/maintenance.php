<div class="content-wrapper" style="min-height: 348px;">  
    <section class="content">
        <div class="row">
            <?php $this->load->view('setting/_settingmenu'); ?>
            
            <!-- left column -->
            <div class="col-md-10">
                <!-- general form elements -->

                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-gear"></i> <?php echo $this->lang->line('maintenance'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="">
                        <form role="form" id="maintenance_form" action="<?php echo site_url('schsettings/save_maintenance'); ?>" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="sch_id" value="<?php echo $result->id; ?>">
                            <div class="box-body">                       
                                <div class="row">
                                    <div class="form-group">
                                            <label class="col-sm-3 col-lg-2 col-md-3"> <?php echo $this->lang->line('maintenance_mode'); ?></label>
                                            <div class="col-sm-9 col-lg-10 col-md-9">
                                                <label class="radio-inline">
                                                    <input type="radio" name="maintenance_mode" value="0" <?php
                                                    if (!$result->maintenance_mode) {
                                                        echo "checked";
                                                    }
                                                    ?> ><?php echo $this->lang->line('disabled'); ?>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="maintenance_mode" value="1" <?php
                                                    if ($result->maintenance_mode) {
                                                        echo "checked";
                                                    }
                                                    ?>><?php echo $this->lang->line('enabled'); ?>
                                                </label>
                                            </div>
                                    </div>                       
                                
                                </div><!--./row--> 
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <?php
                                if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) {
                                    ?>
                                    <button type="submit" class="btn btn-primary submit_schsetting pull-right edit_attendancetype" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('save'); ?></button>
                                    <?php
                                }
                                ?>
                            </div>
                        </form>
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- new END -->

</div><!-- /.content-wrapper -->
 
<script type="text/javascript">
 
 $(document).on('submit','#maintenance_form',function(e){

    e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var submit_btn = $("button[type=submit]",this); 
        var url = form.attr('action');
        var data = form.serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            beforeSend: function() {
                // setting a timeout
               submit_btn.button('loading');
            },
            success: function(data) {
               if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);
                    // window.location.reload(true);
                }
            },
            error: function(xhr) { // if error occured
                alert("Error occured.please try again");
                submit_btn.button('reset');
            },
            complete: function() {
             submit_btn.button('reset');
            }
        });
    });

</script>