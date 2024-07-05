<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> <?php //echo $this->lang->line('system_settings'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('restore', 'can_view')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-database"></i> <?php echo $this->lang->line('backup_history'); ?></h3>
                        <div class="box-tools pull-right">
                            <form id="form1" action="<?php echo site_url('admin/admin/backup') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" role="form">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <?php if ($this->rbac->hasPrivilege('backup', 'can_add')) { ?>
                                    <button class="btn btn-primary btn-sm btn-info" type="submit" name="backup" value="backup"><i class="fa fa-plus-square-o"></i>   <?php echo $this->lang->line('create_backup'); ?></button>
<?php } ?>
                            </form>
                        </div>
                    </div>
                    <div class="box-body">

                        <?php 
                            if ($this->session->flashdata('msg')) {  
                                echo $this->session->flashdata('msg');
                                $this->session->unset_userdata('msg');
                            } 
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive mailbox-messages">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('backup_files'); ?></th>
                                                <th class="text-right" colspan="4">
                                                    <?php echo $this->lang->line('action'); ?>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $count = 1;
                                            foreach ($dbfileList as $data) {
                                                ?>
                                                <tr>
                                                    <td width="80%" class="mailbox-name"><a href="#"> <?php echo $data; ?></a></td>
                                                    <td class="mailbox-name">
                                                        <a href="<?php echo site_url('admin/admin/downloadbackup/' . $data) ?>" class="btn btn-success btn-xs" ><i class="fa fa-download"></i> <?php echo $this->lang->line('download'); ?></a>
                                                    </td>
    <?php if ($this->rbac->hasPrivilege('restore', 'can_view')) { ?>
                                                        <td class="mailbox-name">

                                                            <form class="formrestore" action="<?php echo site_url('admin/admin/backup') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" role="form">
        <?php echo $this->customlib->getCSRF(); ?>
                                                                <input type="hidden" name="filename" value="<?php echo $data; ?>">
                                                                <button class="btn btn-primary btn-xs btn-warning" type="submit" name="backup" value="restore"><i class="fa fa-plus-square-o"></i>  <?php echo $this->lang->line('restore'); ?> </button>
                                                            </form>
                                                        </td>
                                                            <?php } ?>
                                                    <td class="mailbox-name">
                                                        <form class="formdelete" method="post" role="form" name="employeeform" id="employeeform" accept-charset="utf-8"  action="<?php echo site_url('admin/admin/dropbackup/' . $data); ?>" >
                                                            <?php echo $this->customlib->getCSRF(); ?>
                                                            <?php if ($this->rbac->hasPrivilege('backup', 'can_delete')) { ?>
                                                                <button class="btn btn-primary btn-xs btn-danger" type="submit" name="backup" value="restore"><i class="fa fa-trash"></i>  <?php echo $this->lang->line('delete'); ?></button>
    <?php } ?>
                                                        </form></td>

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
                    </div></div></div>
<?php if ($this->rbac->hasPrivilege('restore', 'can_view')) { ?>
                <div class="col-md-4 col-sm-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('upload_from_local_directory'); ?></h3>
                        </div>
                        <form role="form" action="<?php echo site_url('admin/admin/backup') ?>" method="post" enctype="multipart/form-data" id="local_form">
    <?php echo $this->customlib->getCSRF(); ?>
                            <div class="box-body">
                                <input class="filestyle form-control" data-height="30"  type="file" name="file" id="exampleInputFile" >
                                <span class="text-danger"><?php echo form_error('file'); ?></span>
                            </div> 
                            <div class="box-footer">

                                <button class="btn btn-primary btn-sm pull-right" type="submit" name="backup" value="upload"  data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('please_wait') ?>"><i class="fa fa-upload"></i> <?php echo $this->lang->line('upload'); ?></button>
                            </div>
                        </form>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('cron_secret_key') ?></h3>
                            <div class="box-tools pull-right">   
                                <a class="btn btn-primary btn-sm btn-info" data-toggle="tooltip" href="<?php echo base_url() . "admin/admin/addCronsecretkey/" . $settinglist[0]['id'] ?>"><?php
                                    if (!empty($settinglist[0]['cron_secret_key'])) {
                                        echo $this->lang->line('regenerate');
                                    } else {
                                        echo $this->lang->line('generate');
                                    }
                                    ?></a>
                            </div> 
                        </div>
                        <div class="box-body cronkeyheight">
                            <div style="display:none" id="cronkey">
                                <p class="hideeyep"><?php print_r($settinglist[0]['cron_secret_key']); ?></p>
                            </div>
                            <a class="hideeye" data-toggle="tooltip" title="<?php echo $this->lang->line('cron_secret_key') ?>" id="showbtn" onclick="showkey()" href="#"><i class="fa fa-eye"></i></a>

                        </div>

                    </div><!--./box box-warning-->
                </div><!--./col-md-4-->             
<?php } ?>

        </div>
</div>
</div>
</div>
</div>
</div>

<script type="text/javascript">
    $('#form1').submit(function () {
        var c = confirm("<?php echo $this->lang->line('are_you_sure_want_to_make_current_backup')?>");
        return c;
    });
    $('.formdelete').submit(function () {
        var c = confirm("<?php echo $this->lang->line('are_you_sure_want_to_delete_backup')?>");
        return c;
    });
    $('.formrestore').submit(function () {
        var c = confirm("<?php echo $this->lang->line('are_you_sure_want_to_restore_backup?')?>");
        return c;
    });

    function showkey() {

        $("#cronkey").show();
        $("#showbtn").html("<i class='fa fa-eye-slash'></i>");
        $("#showbtn").attr("onclick", "hidekey()");

    }

    function hidekey() {

        $("#cronkey").hide();
        $("#showbtn").html("<i class='fa fa-eye'></i>");
        $("#showbtn").attr("onclick", "showkey()");

    }
$(document).on('submit','#local_form',function(){
   
    var btn_submit = $("button[type=submit]",this);
   btn_submit.button('loading');

});

</script>