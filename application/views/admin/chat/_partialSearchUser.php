<?php
if (!empty($chat_user)) {    
    echo "<ul class='list-group' id='contact-list'>";
    foreach ($chat_user as $user_key => $user_value) {
        ?>
        <li class="list-group-item" data-user-type="<?php echo ($user_value->student_id == "") ? 'Staff' : 'Student'; ?>" data-user-id="<?php echo ($user_value->student_id == "") ? $user_value->staff_id : $user_value->student_id; ?>">
            <div class="col-xs-2 col-sm-1">
                <?php
                if ($user_value->image == "") {
                    $img = $this->media_storage->getImageURL("uploads/staff_images/no_image.png");
                } else {
                    $img = ($user_value->student_id == "") ? $this->media_storage->getImageURL("uploads/staff_images/" . $user_value->image) : $this->media_storage->getImageURL($user_value->image);
                }
                ?>

                <img src="<?php echo $img; ?>" alt="Glenda Patterson" class="img-responsive">
            </div>
            <div class="col-xs-10 col-sm-9">
                <span class="name"> <?php
              
                    if ($user_value->student_id != "") {
                        echo $this->customlib->getFullName($user_value->first_name,$user_value->middle_name,$user_value->last_name,$sch_setting->middlename,$sch_setting->lastname);
                    } else {
                       echo ($user_value->surname == "")? $user_value->name : $user_value->name." ".$user_value->surname; 
                    }
                    ?>
                        
                    </span>
                <br>
                <span>
                    <?php
                    if ($user_value->student_id != "") {
                        echo "(" . $this->lang->line('student') . ")";
                    } else {
                        echo "(" . $this->lang->line('staff') . ")";
                    }
                    ?>
                </span>
            </div>
            <div class="clearfix"></div>
        </li>
        <?php
    }
    echo "</ul>";
}
?>