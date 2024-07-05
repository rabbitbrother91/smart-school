<?php
if (!empty($messagelist)) {
    foreach ($messagelist as $messagelist_value) {
        $staff_profile_pic = '';
        if ($messagelist_value['staff_image'] != '') {
            $staff_profile_pic = 'uploads/staff_images/' . $messagelist_value['staff_image'];
        } else {
            if ($messagelist_value['gender'] == 'Male') {
                $staff_profile_pic = 'uploads/staff_images/default_male.jpg';
            } else {
                $staff_profile_pic = 'uploads/staff_images/default_female.jpg';
            }
        }

        $employee_id = '';
        if ($messagelist_value['staff_employee_id'] != '') {
            $employee_id = ' (' . $messagelist_value['staff_employee_id'] . ')';
        }

        $admission_no = '';
        if ($messagelist_value['admission_no'] != '') {
            $admission_no = ' (' . $messagelist_value['admission_no'] . ')';
        }

        $student_profile_pic = '';
        if ($messagelist_value['student_image'] != '') {
            $student_profile_pic = $messagelist_value['student_image'];
        } else {
            if ($messagelist_value['students_gender'] == 'Male') {
                $student_profile_pic = 'uploads/student_images/default_male.jpg';
            } else {
                $student_profile_pic = 'uploads/student_images/default_female.jpg';
            }
        }
        ?>
        <?php

        if ($messagelist_value['type'] == 'staff') {
            ?>

            <li class="forum-list ">
                <div class="forum-set-flex">
                    <img src="<?php echo $this->media_storage->getImageURL($staff_profile_pic); ?>" alt="" class="img-circle msr-3">
                    <div class="d-flex justify-content-between">
                        <div class="media-title bolds"><?php echo $messagelist_value['staff_name'] . ' ' . $messagelist_value['staff_surname'] . $employee_id; ?></div>
                        <div class="text-muted mb0"><?php echo $this->customlib->dateyyyymmddToDateTimeformat($messagelist_value['created_date'], false); ?>&nbsp

                        <?php
if ($this->rbac->hasPrivilege('lesson_plan_comments', 'can_delete')) {
                if ($login_staff_id == $messagelist_value['staff_id']) {?>
                        <a onclick='deletemessage("<?php echo $messagelist_value['fourm_id']; ?>")' class='btn btn-default btn-xs pull-right text-tooltip-sm' data-placement="bottom" title="<?php echo $this->lang->line('delete'); ?>" data-toggle='tooltip'> <i class='fa fa-trash'></i></a>
                        <?php }}?>

                        </div>
                    </div>
                </div>
                <div class="forum-ms-auto">
                    <div class=""><?php echo $messagelist_value['message']; ?></div>
                </div>
            </li>

        <?php } else {?>

            <li class="forum-list">
                <div class="forum-set-flex">
                    <img src="<?php echo $this->media_storage->getImageURL($student_profile_pic); ?>" alt="" class="img-circle msr-3">
                    <div class="d-flex justify-content-between">
                        <div class="media-title bolds"><?php echo $this->customlib->getFullname($messagelist_value['firstname'], $messagelist_value['middlename'], $messagelist_value['lastname'], $sch_setting->middlename, $sch_setting->lastname) . $admission_no; ?> - <?php echo $messagelist_value['gender']; ?>
                        </div>
                        <div class="text-muted mb0"><?php echo $this->customlib->dateyyyymmddToDateTimeformat($messagelist_value['created_date'], false); ?></div>
                    </div>
                </div>
                <div class="forum-ms-auto">
                    <div class=""><?php echo $messagelist_value['message']; ?></div>
                </div>
            </li>
        <?php }?>

<?php }}?>