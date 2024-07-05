 
<div class="d-flex pr-2">
    <a href="#" class="mail-sidebar"><i class="fa fa-arrow-left valign-top pr-1 fs-2"></i></a>
    <h4 class="box-title mt0 mb0"><?php echo $notificationlist['title']; ?></h4>
</div>
<div class="dividerhr"></div>
<p><?php echo $notificationlist['message']; ?></p>

<?php if ($notificationlist['attachment'] != '') {?>
     <a href="<?php echo base_url(); ?>user/notification/download/<?php echo $notificationlist['id']; ?>"><i class="fa fa-download pr-1"></i><?php echo $this->lang->line('download_attachment'); ?></a>
<?php }?>

 
<ul class="email-list-group ptt10">
   <li><i class="fa fa-calendar-check-o pr-1"></i><?php echo $this->lang->line('publish_date'); ?>: <?php echo $this->customlib->dateformat($notificationlist['publish_date']); ?></li>
   <li><i class="fa fa-calendar pr-1"></i><?php echo $this->lang->line('notice_date'); ?>: <?php echo $this->customlib->dateformat($notificationlist['date']); ?></li>
   
   <?php if($notificationlist["created_by"]){ ?>
   <li><i class="fa fa-user pr-1"></i><?php echo $this->lang->line('created_by'); ?>: <?php echo $notificationlist["created_by"]  ;?>
   <?php } ?> 
   
   </li>
</ul>
<div class="dividerhr"></div>