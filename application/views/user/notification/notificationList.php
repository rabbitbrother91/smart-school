<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('notice_board'); ?></h3>
                    </div>
                    <div class="box-body pt0">
                        <?php if (empty($notificationlist)) {
    ?>
                                <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                <?php
} else {

    foreach ($notificationlist as $key => $notification) {
        ?>
                                    <div class="email-info">
                                        <a href="#" class="navbar-toggle2 force-visible mail-sidebar notification_msg" data-id="<?php echo $notification['id']; ?>">
                                            <h4 class="h4-title"><i class="fa fa-envelope-o"></i><?php echo $notification['title']; ?></h4>
                                            <div class="email-discription"><?php //echo $notification['message']; ?></div>
                                       </a>
                                    </div>
                        <?php }}?>

                    </div>
               </div>
                <aside class="sidebar-container" role="dialog">
                   <article class="email-collection">
                      <a href="#" class="mail-sidebar mail-close-btn"><i class="fa fa-times fs-2"></i></a>
                        <div id="notificationdata"></div>
                   </article>
                </aside>
            </div><!--./col-lg-12-->
        </div>
</div>
</section>
</div>

<script type="text/javascript">
    $('.mail-sidebar').on('click', function(e) {
        $('.sidebar-container, .email-collection, .mail-close-btn').toggleClass("open");

        e.preventDefault();
        $('#notificationdata').html('');
        var message_id = $(this).attr('data-id');
        $.ajax({
            url: '<?php echo base_url(); ?>user/notification/notification',
            method: 'post',
            data:{message_id:message_id},
            dataType: 'json',
            success:function(response){
               $('#notificationdata').html(response.page);
            }
        })
    })
</script>
<script>
    $(document).on('click', '.notification_msg', function () {
        var base_url = '<?php echo base_url() ?>';
        var notification_id = $(this).attr('data-id'); //$(this).data('msgid');
        $.ajax({
            type: "POST",
            url: base_url + "user/notification/updatestatus",
            data: {'notification_id': notification_id},
            dataType: "json",
            success: function (data) {
            }
        });
    });
</script>