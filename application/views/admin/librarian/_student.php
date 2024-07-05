<div class="box box-primary">
    <div class="box-body box-profile">
        <img class="profile-user-img img-responsive img-circle" src="<?php
if (!empty($memberList->image)) {
    echo $this->media_storage->getImageURL($memberList->image);
} else {
    if ($memberList->gender == 'Female') {
        echo $this->media_storage->getImageURL("uploads/student_images/default_female.jpg");
    } elseif ($memberList->gender == 'Male') {
        echo $this->media_storage->getImageURL("uploads/student_images/default_male.jpg");
    }
}
?>" alt="User profile picture">

        <h3 class="profile-username text-center"><?php echo $this->customlib->getFullName($memberList->firstname, $memberList->middlename, $memberList->lastname, $sch_setting->middlename, $sch_setting->lastname); ?></h3>
        <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
                <b><?php echo $this->lang->line('member_id'); ?></b> <a class="pull-right text-aqua"><?php echo $memberList->lib_member_id ?></a>
            </li>
            <li class="list-group-item">
                <b><?php echo $this->lang->line('library_card_no'); ?></b> <a class="pull-right text-aqua"><?php echo $memberList->library_card_no ?></a>
            </li>
            <li class="list-group-item">
                <b><?php echo $this->lang->line('admission_no'); ?></b> <a class="pull-right text-aqua"><?php echo $memberList->admission_no ?></a>
            </li>
            <li class="list-group-item">
                <b><?php echo $this->lang->line('gender'); ?></b> <a class="pull-right text-aqua"><?php echo $this->lang->line(strtolower($memberList->gender)) ?></a>
            </li>
            <li class="list-group-item">
                <b><?php echo $this->lang->line('member_type'); ?></b> <a class="pull-right text-aqua"><?php echo $this->lang->line($memberList->member_type); ?></a>
            </li>
            <li class="list-group-item">
                <b><?php echo $this->lang->line('mobile_number'); ?></b> <a class="pull-right text-aqua"><?php echo $memberList->mobileno ?></a>
            </li>
             <li class="list-group-item ">
                <b><?php echo $this->lang->line('session_year'); ?></b> <a class="pull-right text-aqua"><?php echo $memberList->session_year ?></a>
            </li>
            <?php if ($sch_setting->student_barcode == 1) { ?>          
            
                <li class="list-group-item listnoback">
                    <b><?php echo $this->lang->line('barcode'); ?></b>                     
                    <?php if (file_exists("./uploads/student_id_card/barcodes/" . $memberList->admission_no . ".png")) {?>    
                    <a class="pull-right text-aqua"><img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/barcodes/' . $memberList->admission_no . '.png'); ?>" width="auto" height="auto"/></a>
                    <?php } ?>
                </li>
                
            <?php } ?>
            <?php if ($sch_setting->student_barcode == 1) { ?>          
            
            <li class="list-group-item listnoback">
                <b><?php echo $this->lang->line('qrcode'); ?></b>                     
                <?php if (file_exists("./uploads/student_id_card/qrcode/" . $memberList->admission_no . ".png")) {?>    
                    <a class="pull-right text-aqua" href="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/qrcode/' .  $memberList->admission_no . '.png'); ?>" target="_blank"><img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/qrcode/' . $memberList->admission_no . '.png'); ?>" width="auto" height="auto"  class="h-50"/></a>
                <?php } ?>
            </li>
            
        <?php } ?>
        </ul>
    </div>
</div>