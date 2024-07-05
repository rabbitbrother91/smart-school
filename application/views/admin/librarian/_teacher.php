<div class="box box-primary">
    <div class="box-body box-profile">
        <?php
$image = $memberList->image;
if (!empty($image)) {
    $file = $memberList->image;

} else {
    $file = "no_image.png";
}
?>
        <img class="profile-user-img img-responsive img-circle" src="<?php echo $this->media_storage->getImageURL("uploads/staff_images/" . $file)?>" alt="User profile picture">
        <h3 class="profile-username text-center"><?php echo $memberList->name ?> <?php echo $memberList->surname ?></h3>
        <ul class="list-group list-group-unbordered">
        
            <li class="list-group-item">
                <b><?php echo $this->lang->line('staff_id'); ?></b> <a class="pull-right text-aqua"><?php echo $memberList->employee_id ?></a>
            </li>
            <li class="list-group-item">
                <b><?php echo $this->lang->line('member_id'); ?></b> <a class="pull-right text-aqua"><?php echo $memberList->lib_member_id ?></a>
            </li>
            <li class="list-group-item">
                <b><?php echo $this->lang->line('library_card_no'); ?></b> <a class="pull-right text-aqua"><?php echo $memberList->library_card_no ?></a>
            </li>
            <li class="list-group-item">
                <b><?php echo $this->lang->line('email'); ?></b> <a class="pull-right text-aqua"><?php echo $memberList->email ?></a>
            </li>
            <li class="list-group-item">
                <b><?php echo $this->lang->line('member_type'); ?></b> <a class="pull-right text-aqua"><?php echo $this->lang->line($memberList->member_type); ?></a>
            </li>
            <li class="list-group-item">
                <b><?php echo $this->lang->line('gender'); ?></b> <a class="pull-right text-aqua"><?php echo $this->lang->line(strtolower($memberList->gender)) ?></a>
            </li>
            <li class="list-group-item">
                <b><?php echo $this->lang->line('phone'); ?></b> <a class="pull-right text-aqua"><?php echo $memberList->contact_no ?></a>
            </li>

            <?php
           
               if ($sch_setting->staff_barcode) { ?>
                <li class="list-group-item listnoback">
                    <b><?php echo $this->lang->line('barcode'); ?></b>
                    <?php if (file_exists("./uploads/staff_id_card/barcodes/" . $memberList->employee_id . ".png")) { ?>
                        <a class="pull-right text-aqua">
                            <img src="<?php echo $this->media_storage->getImageURL('uploads/staff_id_card/barcodes/' . $memberList->employee_id . '.png'); ?>" width="auto" height="auto" /></a>
                    <?php } ?>
                </li>
            <?php }
              if ($sch_setting->staff_barcode) { ?>
                <li class="list-group-item listnoback">
                    <b><?php echo $this->lang->line('qrcode'); ?></b>
                    <?php if (file_exists("./uploads/staff_id_card/qrcode/" . $memberList->employee_id . ".png")) { ?>
                        <a class="pull-right text-aqua" href="<?php echo $this->media_storage->getImageURL('uploads/staff_id_card/qrcode/' . $memberList->employee_id . '.png'); ?>" target="_blank">
                            <img src="<?php echo $this->media_storage->getImageURL('uploads/staff_id_card/qrcode/' . $memberList->employee_id . '.png'); ?>" width="auto" height="auto" class="h-50" /></a>
                    <?php } ?>
                </li>
            <?php }
            
            ?>
        </ul>
    </div>
</div>