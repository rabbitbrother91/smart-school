<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-bullhorn"></i> <?php //echo $this->lang->line('communicate'); ?> <small><?php //echo $this->lang->line('student_fee1'); ?></small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid1 box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-commenting-o"></i> <?php echo $this->lang->line('notice_board'); ?></h3>
                        <?php if ($this->rbac->hasPrivilege('notice_board', 'can_add')) {?>
                            <div class="box-tools pull-right">
                                <a href="<?php echo base_url() ?>admin/notification/add" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo $this->lang->line('post_new_message'); ?></a>
                            </div>
                        <?php }?>
                    </div>
                    <div class="box-body pt0">
                        <?php  
    $this->session->unset_userdata('msg'); ?>
                    <?php

if (empty($notificationlist)) {
    ?>
                        <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                    <?php
} else {
    foreach ($notificationlist as $key => $notification) {

        ?>
                        <div class="email-info d-flex">
                            <a href="#" class="navbar-toggle2 force-visible mail-sidebar w-100" data-id="<?php echo $notification['id']; ?>">
                                <h4 class="h4-title"><i class="fa fa-envelope-o"></i><?php echo $notification['title']; ?></h4>
                                <div class="email-discription"><?php //echo $notification['message']; ?></div>
                            </a>
                            <div class="d-flex ptt10 hover-show">
                            
                                <?php if ($notification["created_id"] == $user_id) {?>
                                    <a href="<?php echo base_url() ?>admin/notification/edit/<?php echo $notification['id'] ?>" class="" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    
                                <?php }elseif($this->rbac->hasPrivilege('notice_board', 'can_edit')){ ?>
                                
                                     <a href="<?php echo base_url() ?>admin/notification/edit/<?php echo $notification['id'] ?>" class="" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                        <i class="fa fa-pencil"></i>
                                    </a>                                
                                
                                <?php } ?>
                                
                                
                                <?php if ($notification["created_id"] == $user_id) {?>
                                
                                    <a href="<?php echo base_url() ?>admin/notification/delete/<?php echo $notification['id'] ?>" class="" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                        <i class="fa fa-remove"></i>
                                    </a>
                                    
                                <?php }elseif($this->rbac->hasPrivilege('notice_board', 'can_delete')){ ?>
                                
                                    <a href="<?php echo base_url() ?>admin/notification/delete/<?php echo $notification['id'] ?>" class="" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                        <i class="fa fa-remove"></i>
                                    </a>                                
                                
                                <?php } ?>
                                
                            </div>
                        </div>
                        <?php }}?>

                    </div>
                    <aside class="sidebar-container" role="dialog">
                       <article class="email-collection">
                          <a href="#" class="mail-sidebar mail-close-btn"><i class="fa fa-times fs-2"></i></a>
                            <div id="notificationdata"></div>
                       </article>
                    </aside>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $('.mail-sidebar').on('click', function(e) {
        $('.sidebar-container, .email-collection').toggleClass("open");
        $('.mail-close-btn').toggleClass("open");
        e.preventDefault();

        var message_id = $(this).attr('data-id');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/notification/notification',
            method: 'post',
            data:{message_id:message_id},
            dataType: 'json',
            success:function(response){
               $('#notificationdata').html(response.page);
            }
        })
    })
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            title: '',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('li').find('.fee_detail_popover').html();
            }
        });
    });

</script>

<script>
    $(function () {
        $("#compose-textarea").wysihtml5();
    });
</script>