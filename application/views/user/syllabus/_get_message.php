<?php
if (!empty($messagelist)) {
    foreach ($messagelist as $messagelist_value) {
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

        ?>
    <li class="forum-list">
        <?php if ($messagelist_value['type'] == 'student') {

            ?>
            <div class="forum-set-flex">
                <img src="<?php echo base_url(); ?><?php echo $student_profile_pic . img_time(); ?>" alt="" class="img-circle msr-3">
                <div class="d-flex justify-content-between">
                    <div class="media-title bolds"><?php echo $this->customlib->getFullname($messagelist_value['firstname'], $messagelist_value['middlename'], $messagelist_value['lastname'], $sch_setting->middlename, $sch_setting->lastname) . $admission_no; ?>
                    </div>
                    <div class="text-muted mb0"><?php echo $this->customlib->dateyyyymmddToDateTimeformat($messagelist_value['created_date'], false); ?>

                    <?php if ($messagelist_value['student_id'] == $login_student_id) {?>
                        &nbsp;
                        <a onclick='deletemessage("<?php echo $messagelist_value['fourm_id']; ?>")'  class='btn btn-default btn-xs pull-right' data-placement="bottom" title="<?php echo $this->lang->line('delete'); ?>" data-toggle='tooltip'> <i class='fa fa-trash'></i></a>
                    <?php }?>
                    </div>

                </div>
            </div>
        <?php } else {?>
            <div class="forum-set-flex">
                <img src="<?php echo base_url(); ?><?php echo $staff_profile_pic . img_time(); ?>" alt="" class="img-circle msr-3">
                <div class="d-flex justify-content-between">
                    <div class="media-title bolds"><?php echo $messagelist_value['staff_name'] . ' ' . $messagelist_value['staff_surname'] . $employee_id; ?></div>
                    <div class="text-muted mb0"><?php echo $this->customlib->dateyyyymmddToDateTimeformat($messagelist_value['created_date'], false); ?></div>
                </div>
            </div>
        <?php }?>
            <div class="forum-ms-auto">
                <div class=""><?php echo $messagelist_value['message']; ?></div>
             </div>
    </li>
<?php }}?>