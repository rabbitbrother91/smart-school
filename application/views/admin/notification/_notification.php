<div class="d-flex pr-2">
    <a href="#" class="mail-sidebar"><i class="fa fa-arrow-left valign-top pr-1 fs-2"></i></a>
    <h4 class="box-title mt0 mb0"><?php echo $notificationlist['title']; ?></h4>
</div>
<div class="dividerhr"></div>
<p><?php echo $notificationlist['message']; ?></p>

<?php if ($notificationlist['attachment'] != '') {?>    
   <div class="ptt10">  
      <a href="<?php echo base_url(); ?>admin/notification/download/<?php echo $notificationlist['id']; ?>">      
      <i class="fa fa-download pr-1"></i><?php echo $this->lang->line('download_attachment'); ?></a>  
   </div>     
<?php }?>

<ul class="email-list-group">
   <li><i class="fa fa-calendar-check-o pr-1"></i><?php echo $this->lang->line('publish_date'); ?>: <?php echo $this->customlib->dateformat($notificationlist['publish_date']); ?></li>
   <li><i class="fa fa-calendar pr-1"></i><?php echo $this->lang->line('notice_date'); ?>: <?php echo $this->customlib->dateformat($notificationlist['date']); ?></li>
   <?php if (!empty($notificationlist['createdby_name'])) {?>
   <?php echo $notificationlist['createdby_name']; ?>
   <?php }?>
</ul>
<div class="dividerhr"></div>
<h4 class="box-title"><?php echo $this->lang->line('message_to'); ?></h4>
<ul class="email-list-group">
   <?php foreach ($notificationlist['role_name'] as $key => $role_value) {?>
      <li><i class="fa fa-user-secret"></i> <?php echo $role_value['name']; ?></li>
   <?php }?>
   <?php if ($notificationlist['visible_student'] == "Yes") {?>
      <li><i class="fa fa-user"></i> <?php echo $this->lang->line('student'); ?></li>
   <?php }?>
   <?php if ($notificationlist['visible_parent'] == "Yes") {?>
      <li><i class="fa fa-user"></i> <?php echo $this->lang->line('parent'); ?></li>
   <?php }?>
</ul>