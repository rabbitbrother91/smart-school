<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <section class="content-header">
                <h1><i class="fa fa-sitemap"></i> <?php //echo $this->lang->line('human_resource'); 
                                                    ?></h1>
            </section>
        </div>
        <div>
            <?php if ($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) { ?>
                <a id="sidebarCollapse" class="studentsideopen"><i class="fa fa-navicon"></i></a>
            <?php } ?>

            <?php
            $employee_id = '';
            if ($staff["employee_id"] != '') {
                $employee_id = ' (' . $staff["employee_id"] . ')';
            }
            ?>
            <aside class="studentsidebar">
                <div class="stutop" id="">
                    <!-- Create the tabs -->
                    <div class="studentsidetopfixed">
                        <p class="classtap"><?php echo $this->lang->line('staff'); ?> <a href="#" data-toggle="control-sidebar" class="studentsideclose"><i class="fa fa-times"></i>
                            </a>
                        </p>
                        <ul class="nav nav-justified studenttaps">
                            <?php foreach ($roles as $role_key => $role_value) {
                            ?>
                                <li <?php
                                    if ($staff["role_id"] == $role_value["id"]) {
                                        echo "class='active'";
                                    }
                                    ?>><a href="#role<?php echo $role_value["id"] ?>" data-toggle="tab"><?php echo $role_value["name"] ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <?php foreach ($roles as $rolet_key => $rolet_value) {
                        ?>
                            <div class="tab-pane <?php
                                                    if ($staff["role_id"] == $rolet_value["id"]) {
                                                        echo "active";
                                                    }
                                                    ?>" id="role<?php echo $rolet_value['id'] ?>">

                                <?php
                                foreach ($stafflist as $skey => $svalue) {

                                    if ($rolet_value['id'] == $svalue["role_id"]) {

                                        if (!empty($svalue["image"])) {
                                            $image = $svalue['image'];
                                        } else {
                                            if ($svalue['gender'] == 'Male') {
                                                $image = "default_male.jpg";
                                            } else {
                                                $image = "default_female.jpg";
                                            }
                                        }
                                ?>
                                        <div class="studentname">
                                            <a href="<?php echo base_url() . "admin/staff/profile/" . $svalue["id"] ?>">
                                                <div class="icon"><img src="<?php echo $this->media_storage->getImageURL("uploads/staff_images/" . $image); ?>" alt="<?php echo $this->lang->line('user_image'); ?>"></div>
                                                <div class="student-tittle"><?php echo $svalue['name'] . " " . $svalue['surname']; ?></div>
                                            </a>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </aside>
        </div>
    </div>
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary" <?php
                                                if ($staff["is_active"] == 0) {
                                                    echo "style='background-color:#f0dddd;'";
                                                }
                                                ?>>
                    <div class="box-body box-profile">
                        <?php

                        $image = $staff['image'];
                        if (!empty($image)) {

                            $file = $staff['image'];
                        } else {
                            if ($staff['gender'] == 'Male') {
                                $file = "default_male.jpg";
                            } else {
                                $file = "default_female.jpg";
                            }
                        }
                        ?>
                        <img class="profile-user-img img-responsive img-circle" src="<?php echo $this->media_storage->getImageURL("uploads/staff_images/" . $file); ?>" alt="User profile picture">
                        <h3 class="profile-username text-center"><?php echo $staff['name'] . " " . $staff['surname']; ?></h3>
                        <?php if ($staff['user_type'] == 'Teacher') {
                        ?>

                            <?php if ($rate_canview == 1) { ?><center>
                                    <h3><?php
                                        $stage     = (int) ($rate);
                                        $stagehalf = "";
                                        $half      = fmod($rate, 1);
                                        if ($half != 0) {
                                            $stagehalf = $stage + 1;
                                        }

                                        for ($i = 1; $i <= 5; $i++) {
                                        ?>
                                            <span class="fa fa-star<?php
                                                                    if ($i == $stagehalf && ($half > 0 && $half < 1)) {
                                                                        echo '-half-o checked';
                                                                    }
                                                                    ?> " <?php if ($stage >= $i) { ?> style="color:orange;" <?php } ?>></span>
                                        <?php
                                        }
                                        ?>
                                    </h3>
                                </center>
                                <center>
                                    <h5><?php echo substr($rate, 0, 3); ?> average based on <?php echo $reviews; ?> <?php echo $this->lang->line('reviews'); ?>.</h5>
                                </center> <?php }
                                    } ?>
                        <ul class="list-group list-group-unbordered">

                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('staff_id'); ?></b> <a class="pull-right text-aqua"><?php echo $staff['employee_id']; ?></a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('role'); ?></b> <a class="pull-right text-aqua"><?php echo $staff['user_type']; ?></a>
                            </li>
                            <?php if ($sch_setting->staff_designation) { ?>
                                <li class="list-group-item listnoback">
                                    <b><?php echo $this->lang->line('designation'); ?></b> <a class="pull-right text-aqua"><?php echo $staff['designation']; ?></a>
                                </li>
                            <?php }
                            if ($sch_setting->staff_department) { ?>
                                <li class="list-group-item listnoback">
                                    <b><?php echo $this->lang->line('department'); ?></b> <a class="pull-right text-aqua"><?php echo $staff['department']; ?></a>
                                </li>
                            <?php }
                            if ($sch_setting->staff_epf_no) { ?>
                                <li class="list-group-item listnoback">
                                    <b><?php echo $this->lang->line('epf_no'); ?></b> <a class="pull-right text-aqua"><?php echo $staff['epf_no']; ?></a>
                                </li>
                            <?php } ?>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('basic_salary'); ?></b> <a class="pull-right text-aqua"><?php if (!empty($staff['basic_salary'])) {
                                                                                                                            echo amountFormat($staff['basic_salary']);
                                                                                                                        } ?></a>
                            </li>
                            <?php if ($sch_setting->staff_contract_type) {
                            ?>
                                <li class="list-group-item listnoback">
                                    <b><?php echo $this->lang->line('contract_type'); ?></b> <a class="pull-right text-aqua"><?php
                                                                                                                                if (array_key_exists($staff['contract_type'], $contract_type)) {
                                                                                                                                    echo $contract_type[$staff['contract_type']];
                                                                                                                                }
                                                                                                                                ?></a>
                                </li>
                            <?php }
                            if ($sch_setting->staff_work_shift) { ?>
                                <li class="list-group-item listnoback">
                                    <b><?php echo $this->lang->line('work_shift'); ?></b> <a class="pull-right text-aqua"><?php echo $staff['shift']; ?></a>
                                </li>
                            <?php }
                            if ($sch_setting->staff_work_location) { ?>
                                <li class="list-group-item listnoback">
                                    <b><?php echo $this->lang->line('work_location'); ?></b> <a class="pull-right text-aqua"><?php echo $staff['location']; ?></a>
                                </li>
                            <?php }
                            if ($sch_setting->staff_date_of_joining) {
                            ?>
                                <li class="list-group-item listnoback">
                                    <b><?php echo $this->lang->line('date_of_joining'); ?></b> <a class="pull-right text-aqua"><?php
                                                                                                                                if (!empty($staff["date_of_joining"]) && $staff["date_of_joining"] != '0000-00-00') {
                                                                                                                                    echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($staff['date_of_joining']));
                                                                                                                                }
                                                                                                                                ?></a>
                                </li>
                            <?php }

                            if ($sch_setting->staff_barcode) { ?>
                                <li class="list-group-item listnoback">
                                    <b><?php echo $this->lang->line('barcode'); ?></b>
                                    <?php if (file_exists("./uploads/staff_id_card/barcodes/" . $staff['employee_id'] . ".png")) { ?>
                                        <a class="pull-right text-aqua">
                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/staff_id_card/barcodes/' . $staff['employee_id'] . '.png'); ?>" width="auto" height="auto" /></a>
                                    <?php } ?>
                                </li>
                            <?php }
                              if ($sch_setting->staff_barcode) { ?>
                                <li class="list-group-item listnoback">
                                    <b><?php echo $this->lang->line('qrcode'); ?></b>
                                    <?php if (file_exists("./uploads/staff_id_card/qrcode/" . $staff['employee_id'] . ".png")) { ?>
                                        <a class="pull-right text-aqua" href="<?php echo $this->media_storage->getImageURL('uploads/staff_id_card/qrcode/' . $staff['employee_id'] . '.png'); ?>" target="_blank">
                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/staff_id_card/qrcode/' . $staff['employee_id'] . '.png'); ?>" width="auto" height="auto" class="h-50" /></a>
                                    <?php } ?>
                                </li>
                            <?php }

                            if (($staff["is_active"] == 0)) {
                            ?>
                                <li class="list-group-item listnoback">
                                    <b><?php echo $this->lang->line('date_of_leaving'); ?></b> <a class="pull-right text-aqua"><?php

                                                                                                                                echo $this->customlib->dateformat($staff['date_of_leaving']);

                                                                                                                                ?></a>
                                </li>
                            <?php }
                            if (($staff["is_active"] == 0)) {
                            ?>
                                <li class="list-group-item listnoback">
                                    <b><?php echo $this->lang->line('disable_date'); ?></b> <a class="pull-right text-aqua"><?php

                                                                                                                            echo $this->customlib->dateformat($staff['disable_at']);

                                                                                                                            ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="nav-tabs-custom theme-shadow">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('profile'); ?></a></li>
                        <li class=""><a href="#payroll" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('payroll'); ?></a></li>
                        <li class=""><a href="#leaves" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('leaves'); ?></a></li>
                        <li class=""><a href="#attendance" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('attendance'); ?></a></li>
                        <?php if ($sch_setting->staff_upload_documents) { ?>
                            <li class=""><a href="#documents" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('documents'); ?></a></li>
                        <?php } ?>
                        <?php if ($this->rbac->hasPrivilege('staff_timeline', 'can_view')) { ?>
                            <li class=""><a href="#timelineh" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('timeline'); ?></a></li>
                        <?php } ?>
                        <?php if ($staff['user_type'] == 2) {
                        ?>
                            <li class=""><a href="#reviews" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('reviews'); ?></a></li>
                        <?php
                        }
                        $userdata            = $this->customlib->getUserData();
                        $logged_in_User      = $this->customlib->getLoggedInUserData();
                        $logged_in_User_Role = json_decode($this->customlib->getStaffRole());
                        $a                   = false;
                        if ($staff['id'] == $logged_in_User['id']) {
                            $a = true;
                        } elseif ($logged_in_User_Role->id == 7 && $logged_in_User_Role->name == "Super Admin") {
                            if ($staff["role_id"] == 7) {
                                if ($staff["role_id"] == 7 && $staff['id'] != $logged_in_User['id']) {
                                    $a = false;
                                } else {
                                    $a = true;
                                }
                            } else {
                                $a = true;
                            }
                        }

                        ?>
                        <?php

                        if ($enable_disable == 1) {
                            if ($staff["is_active"] == 1) {
                                if ($this->rbac->hasPrivilege('disable_staff', 'can_view')) {
                                    if ($logged_in_User_Role->id == 7) {
                                        if ($a) {
                        ?>
                                            <li class="pull-right"><a class="text-red" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('disable'); ?>" onclick="disable_staff('<?php echo $id; ?>');"></i> <i class="fa fa-thumbs-o-down"></i></a></li>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <li class="pull-right"><a href="<?php echo base_url('admin/staff/disablestaff/' . $id); ?>" class="text-red" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('disable'); ?>" onclick="return confirm('<?php echo $this->lang->line('are_you_sure_you_want_to_disable_this_record'); ?>')"></i> <i class="fa fa-thumbs-o-down"></i></a></li>
                                    <?php
                                    }
                                }
                            } else if ($staff["is_active"] == 0) {
                                if ($logged_in_User_Role->id == 7) {
                                    if ($a) {
                                    ?>

                                        <li class="pull-right"><a href="<?php echo base_url('admin/staff/delete/' . $id); ?>" class="text-red" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('are_you_sure_want_to_delete'); ?>');"></i><i class="fa fa-trash"></i></a></li>
                                        <li class="pull-right"><a href="<?php echo base_url('admin/staff/enablestaff/' . $id); ?>" class="text-green" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('enable'); ?>" onclick="return confirm('<?php echo $this->lang->line('are_you_sure_you_want_to_enable_this_record'); ?>');"><i class="fa fa-thumbs-o-up"></i></a></li>

                                    <?php
                                    }
                                } else {
                                    if ($this->rbac->hasPrivilege('staff', 'can_delete')) {
                                    ?>

                                        <li class="pull-right"><a href="<?php echo base_url('admin/staff/delete/' . $id); ?>" class="text-red" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('are_you_sure_want_to_delete'); ?>');"></i><i class="fa fa-trash"></i></a></li>
                                    <?php }
                                    if ($this->rbac->hasPrivilege('disable_staff', 'can_view')) {
                                    ?>

                                        <li class="pull-right"><a href="<?php echo base_url('admin/staff/enablestaff/' . $id); ?>" class="text-green" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('enable'); ?>" onclick="return confirm('<?php echo $this->lang->line('are_you_sure_you_want_to_enable_this_record'); ?>');"><i class="fa fa-thumbs-o-up"></i></a></li>

                        <?php
                                    }
                                }
                            }
                        }
                        ?>

                        <li class="pull-right">
                            <?php if ($a) {
                            ?>
                                <a href="#" class="change_password text-green" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('change_password'); ?>"></i> <i class="fa fa-key"></i></a>

                            <?php
                            }
                            ?>
                        </li>
                        <?php
                        if ($this->rbac->hasPrivilege('staff', 'can_edit')) {

                            if ($logged_in_User_Role->id == 7) {
                                if ($a) {
                        ?>

                                    <li class="pull-right"><a href="<?php echo base_url('admin/staff/edit/' . $id); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('edit'); ?>" class="text-light"><i class="fa fa-pencil"></i></a></li>
                                <?php
                                }
                            } else {
                                ?>

                                <li class="pull-right"><a href="<?php echo base_url('admin/staff/edit/' . $id); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('edit'); ?>" class="text-light"><i class="fa fa-pencil"></i></a></li>
                        <?php
                            }
                        }
                        ?>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="activity">
                            <div class="tshadow mb25 bozero">
                                <div class="table-responsive around10 pt0">
                                    <table class="table table-hover table-striped tmb0">
                                        <tbody>
                                            <?php if ($sch_setting->staff_phone) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('phone'); ?></td>
                                                    <td><?php echo $staff['contact_no']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->staff_emergency_contact) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('emergency_contact_number'); ?></td>
                                                    <td><?php echo $staff['emergency_contact_no']; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td><?php echo $this->lang->line('email'); ?></td>
                                                <td><?php echo $staff['email']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('gender'); ?></td>
                                                <td><?php echo $this->lang->line(strtolower($staff['gender'])); ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('date_of_birth'); ?></td>
                                                <td><?php
                                                    if (!empty($staff["dob"]) && $staff["dob"] != '0000-00-00') {
                                                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($staff['dob']));
                                                    }
                                                    ?></td>
                                            </tr>
                                            <?php if ($sch_setting->staff_marital_status) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('marital_status'); ?></td>
                                                    <td><?php echo $staff['marital_status']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->staff_father_name) { ?>
                                                <tr>
                                                    <td class="col-md-4"><?php echo $this->lang->line('father_name'); ?></td>
                                                    <td class="col-md-5"><?php echo $staff['father_name']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->staff_mother_name) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('mother_name'); ?></td>
                                                    <td><?php echo $staff['mother_name']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->staff_qualification) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('qualification'); ?></td>
                                                    <td><?php echo $staff['qualification']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->staff_work_experience) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('work_experience'); ?></td>
                                                    <td><?php echo $staff['work_exp']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->staff_note) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('note'); ?></td>
                                                    <td><?php echo $staff['note']; ?></td>
                                                </tr>
                                                <?php }
                                            $cutom_fields_data = get_custom_table_values($staff['id'], 'staff');
                                            if (!empty($cutom_fields_data)) {
                                                foreach ($cutom_fields_data as $field_key => $field_value) {
                                                ?>
                                                    <tr>
                                                        <td><?php echo $field_value->name; ?></td>
                                                        <td>
                                                            <?php
                                                            if (is_string($field_value->field_value) && is_array(json_decode($field_value->field_value, true)) && (json_last_error() == JSON_ERROR_NONE)) {
                                                                $field_array = json_decode($field_value->field_value);
                                                                echo "<ul class='student_custom_field'>";
                                                                foreach ($field_array as $each_key => $each_value) {
                                                                    echo "<li>" . $each_value . "</li>";
                                                                }
                                                                echo "</ul>";
                                                            } else {

                                                                $display_field = $field_value->field_value;
                                                                if ($field_value->type == "link") {
                                                                    $display_field = "<a href=" . $field_value->field_value . " target='_blank'>" . $field_value->field_value . "</a>";
                                                                }
                                                                echo $display_field;
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tshadow mb25 bozero">
                                <h3 class="pagetitleh2"><?php echo $this->lang->line('address_details'); ?></h3>
                                <div class="table-responsive around10 pt0">
                                    <table class="table table-hover table-striped tmb0">
                                        <tbody>
                                            <?php if ($sch_setting->staff_current_address) { ?>
                                                <tr>
                                                    <td class="col-md-4"><?php echo $this->lang->line('current_address'); ?></td>
                                                    <td class="col-md-5"><?php echo $staff['local_address']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->staff_permanent_address) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('permanent_address'); ?></td>
                                                    <td><?php echo $staff['permanent_address']; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php if ($sch_setting->staff_account_details) { ?>
                                <div class="tshadow mb25 bozero">
                                    <h3 class="pagetitleh2"><?php echo $this->lang->line('bank_account_details'); ?></h3>
                                    <div class="table-responsive around10 pt10">
                                        <table class="table table-hover table-striped tmb0">
                                            <tbody>
                                                <tr>
                                                    <td class="col-md-4"><?php echo $this->lang->line('account_title'); ?></td>
                                                    <td class="col-md-5"><?php echo $staff['account_title']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $this->lang->line('bank_name'); ?></td>
                                                    <td><?php echo $staff['bank_name']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $this->lang->line('bank_branch_name'); ?></td>
                                                    <td><?php echo $staff['bank_branch']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $this->lang->line('bank_account_number'); ?></td>
                                                    <td><?php echo $staff['bank_account_no']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $this->lang->line('ifsc_code'); ?></td>
                                                    <td><?php echo $staff['ifsc_code']; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php }
                            if ($sch_setting->staff_social_media) { ?>
                                <div class="tshadow mb25  bozero">
                                    <h3 class="pagetitleh2"><?php echo $this->lang->line('social_media_link'); ?></h3>
                                    <div class="table-responsive around10 pt0">
                                        <table class="table table-hover table-striped tmb0">
                                            <tbody>
                                                <tr>
                                                    <td class="col-md-4"><?php echo $this->lang->line('facebook_url'); ?></td>
                                                    <td class="col-md-5"><a href="<?php echo $staff['facebook']; ?>" target="_blank"><?php echo $staff['facebook']; ?></a></td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $this->lang->line('twitter_url'); ?></td>
                                                    <td><a href="<?php echo $staff['twitter']; ?>" target="_blank"><?php echo $staff['twitter']; ?></a></td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $this->lang->line('linkedin_url'); ?></td>
                                                    <td><a href="<?php echo $staff['linkedin']; ?>" target="_blank"><?php echo $staff['linkedin']; ?></a></td>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $this->lang->line('instagram_url'); ?></td>
                                                    <td><a href="<?php echo $staff['instagram']; ?>" target="_blank"><?php echo $staff['instagram']; ?></a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="tab-pane" id="payroll">
                            <div class="row row-flex">
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="staffprofile">
                                        <h5><?php echo $this->lang->line('total_net_salary_paid'); ?></h5>
                                        <h4><?php
                                            if (!empty($salary["net_salary"])) {
                                                echo $currency_symbol . amountFormat($salary["net_salary"]);
                                            } else {
                                                echo $currency_symbol . "0.00";
                                            }
                                            ?></h4>
                                        <div class="icon mt12font40">
                                            <i class="fa fa-money"></i>
                                        </div>
                                    </div>
                                </div><!--./col-md-3-->
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="staffprofile">
                                        <h5><?php echo $this->lang->line('total_gross_salary'); ?></h5>
                                        <h4><?php
                                            if (!empty($salary["basic_salary"])) {
                                                $basic_salary = $salary["basic_salary"] + $salary["earnings"] - $salary["deduction"];
                                                echo $currency_symbol . amountFormat($basic_salary);
                                            } else {
                                                echo $currency_symbol . "0.00";
                                            }
                                            ?></h4>
                                        <div class="icon mt12font40">
                                            <i class="fa fa-money"></i>
                                        </div>
                                    </div>
                                </div><!--./col-md-3-->
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="staffprofile">
                                        <h5><?php echo $this->lang->line('total_earning'); ?></h5>
                                        <h4><?php
                                            if (!empty($salary["earnings"])) {
                                                echo $currency_symbol . amountFormat($salary["earnings"]);
                                            } else {
                                                echo $currency_symbol . "0.00";
                                            }
                                            ?></h4>
                                        <div class="icon mt12font40">
                                            <i class="fa fa-money"></i>
                                        </div>
                                    </div>
                                </div><!--./col-md-3-->
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="staffprofile">
                                        <h5><?php echo $this->lang->line('total_deduction'); ?></h5>
                                        <h4><?php
                                            $deduction = $salary["deduction"] + $salary["tax"];

                                            if (!empty($deduction)) {
                                                echo $currency_symbol . amountFormat($deduction);
                                            } else {
                                                echo $currency_symbol . "0.00";
                                            } ?> </h4>
                                        <div class="icon mt12font40">
                                            <i class="fa fa-money"></i>
                                        </div>
                                    </div>
                                </div><!--./col-md-3-->
                            </div>
                            <div class="table-responsive">
                                <div class="download_label"><?php echo $this->lang->line('details_for'); ?> <?php echo $staff["name"] . " " . $staff["surname"] . $employee_id; ?></div>
                                <table class="table table-hover table-striped example">
                                    <thead>
                                        <tr>
                                            <th class="text text-left"><?php echo $this->lang->line('payslip'); ?> #</th>
                                            <th class="text text-left"><?php echo $this->lang->line('month_year'); ?><span></span></th>
                                            <th class="text text-left"><?php echo $this->lang->line('date'); ?></th>
                                            <th class="text text-left"><?php echo $this->lang->line('mode'); ?></th>
                                            <th class="text text-left"><?php echo $this->lang->line('status'); ?></th>
                                            <th class="text text-right"><?php echo $this->lang->line('net_salary'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($staff_payroll as $key => $payroll_value) {

                                            if ($payroll_value["status"] == "paid") {
                                                $label = "class='label label-success'";
                                            } else if ($payroll_value["status"] == "generated") {
                                                $label = "class='label label-warning'";
                                            } else {
                                                $label = "class='label label-default'";
                                            }
                                        ?>
                                            <tr>
                                                <td>
                                                    <a data-toggle="popover" href="#" class="detail_popover" data-original-title="" title=""><?php echo $payroll_value['id'] ?></a>
                                                    <div class="fee_detail_popover" style="display: none"><?php echo $payroll_value['remark']; ?></div>
                                                </td>
                                                <td><?php echo $this->lang->line(strtolower($payroll_value['month'])) . " - " . $payroll_value['year']; ?></td>
                                                <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($payroll_value['payment_date'])); ?></td>
                                                <td><?php
                                                    if (!empty($payroll_value['payment_mode'])) {
                                                        echo $payment_mode[$payroll_value['payment_mode']];
                                                    }
                                                    ?></td>
                                                <td><span <?php echo $label ?>><?php echo $payroll_status[$payroll_value['status']]; ?></span></td>
                                                <td class="text text-right"><?php echo amountFormat($payroll_value['net_salary']); ?></td>
                                                <td class="text-right">
                                                    <?php if ($payroll_value["status"] == "paid") {
                                                    ?>
                                                        <?php
                                                        if (
                                                            $this->rbac->hasPrivilege('staff', 'can_view')
                                                        ) {
                                                        ?>
                                                            <a href="#" onclick="getPayslip('<?php echo $payroll_value["id"]; ?>')" role="button" class="btn btn-primary btn-xs checkbox-toggle edit_setting" data-toggle="tooltip"><?php echo $this->lang->line('view_payslip'); ?></a>

                                                        <?php } ?>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <?php if ($sch_setting->staff_upload_documents) {
                        ?>
                            <div class="tab-pane" id="documents">
                                <div class="timeline-header no-border">
                                    <div class="row">
                                        <?php if ((empty($staff["resume"])) && (empty($staff["joining_letter"])) && (empty($staff["resignation_letter"])) && (empty($staff["other_document_file"]))) {
                                        ?>
                                            <div class="col-md-12">
                                                <div class="alert alert-info"><?php echo $this->lang->line("no_record_found"); ?></div>
                                            </div>
                                        <?php } else {
                                        ?>
                                            <?php if (!empty($staff["resume"])) { ?>
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <div class="staffprofile">
                                                        <h5><?php echo $this->lang->line('resume'); ?></h5>
                                                        <a href="<?php echo base_url(); ?>admin/staff/download/<?php echo $staff['id'] . "/" . 'resume'; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                            <i class="fa fa-download"></i></a>
                                                        <?php
                                                        if (
                                                            $this->rbac->hasPrivilege('staff', 'can_edit')
                                                        ) {
                                                        ?>
                                                            <a href="<?php echo base_url(); ?>admin/staff/doc_delete/<?php echo $staff['id'] . "/resume"; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                <i class="fa fa-remove"></i></a>
                                                        <?php } ?>
                                                        <div class="icon">
                                                            <i class="fa fa-file-text-o"></i>
                                                        </div>
                                                    </div>
                                                </div><!--./col-md-3-->
                                            <?php } ?>
                                            <?php if (!empty($staff["joining_letter"])) {
                                            ?>
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <div class="staffprofile">
                                                        <h5><?php echo $this->lang->line('joining_letter'); ?></h5>
                                                        <a href="<?php echo base_url(); ?>admin/staff/download/<?php echo $staff['id'] . "/" . 'joining_letter'; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                            <i class="fa fa-download"></i></a>
                                                        <?php
                                                        if (
                                                            $this->rbac->hasPrivilege('staff', 'can_edit')
                                                        ) {
                                                        ?>
                                                            <a href="<?php echo base_url(); ?>admin/staff/doc_delete/<?php echo $staff['id'] . "/joining_letter"; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                <i class="fa fa-remove"></i>
                                                            </a>
                                                        <?php } ?>
                                                        <div class="icon">
                                                            <i class="fa fa-file-archive-o"></i>
                                                        </div>
                                                    </div>
                                                </div><!--./col-md-3-->
                                            <?php } ?>
                                            <?php if (!empty($staff["resignation_letter"])) {
                                            ?>
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <div class="staffprofile">
                                                        <h5><?php echo $this->lang->line('resignation_letter'); ?></h5>
                                                        <a href="<?php echo base_url(); ?>admin/staff/download/<?php echo $staff['id'] . "/" . 'resignation_letter'; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                            <i class="fa fa-download"></i></a>
                                                        <?php
                                                        if (
                                                            $this->rbac->hasPrivilege('staff', 'can_edit')
                                                        ) {
                                                        ?>
                                                            <a href="<?php echo base_url(); ?>admin/staff/doc_delete/<?php echo $staff['id'] . "/resignation_letter"; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                <i class="fa fa-remove"></i></a>
                                                        <?php } ?>
                                                        <div class="icon">
                                                            <i class="fa fa-file-archive-o"></i>
                                                        </div>
                                                    </div>
                                                </div><!--./col-md-3-->
                                            <?php } ?>
                                            <?php if (!empty($staff["other_document_file"])) {
                                            ?>
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <div class="staffprofile">
                                                        <h5><?php echo $this->lang->line('other_documents'); ?></h5>
                                                        <a href="<?php echo base_url(); ?>admin/staff/download/<?php echo $staff['id'] . "/" . 'other_document_file'; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                            <i class="fa fa-download"></i></a>
                                                        <?php
                                                        if (
                                                            $this->rbac->hasPrivilege('staff', 'can_edit')
                                                        ) {
                                                        ?>
                                                            <a href="<?php echo base_url(); ?>admin/staff/doc_delete/<?php echo $staff['id'] . "/other_document_file" ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                <i class="fa fa-remove"></i></a>
                                                        <?php } ?>
                                                        <div class="icon">
                                                            <i class="fa fa-file-archive-o"></i>
                                                        </div>
                                                    </div>
                                                </div><!--./col-md-3-->
                                            <?php } ?>
                                        <?php } ?>
                                    </div><!--./row-->
                                </div>
                                </table>
                            </div>
                        <?php } ?>

                        <div class="tab-pane" id="timelineh">
                            <div><?php if ($this->rbac->hasPrivilege('staff_timeline', 'can_add')) { ?>
                                    <input type="button" id="myTimelineButton" class="btn btn-sm btn-primary pull-right " value="<?php echo $this->lang->line('add') ?>" />
                                <?php } ?>
                            </div>
                            <br />
                            <div class="timeline-header no-border">
                                <div id="timeline_list">
                                    <?php
                                    if (empty($timeline_list)) {
                                    ?>
                                        <br />
                                        <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                    <?php } else {
                                    ?>
                                        <ul class="timeline timeline-inverse">
                                            <?php
                                            foreach ($timeline_list as $key => $value) {
                                            ?>
                                                <li class="time-label">
                                                    <span class="bg-blue"> <?php
                                                                            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['timeline_date']));
                                                                            ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa fa-list-alt bg-blue"></i>
                                                    <div class="timeline-item">
                                                        <?php if ($this->rbac->hasPrivilege('staff_timeline', 'can_delete')) { ?>
                                                            <span class="time">
                                                                <a class="defaults-c text-right" data-toggle="tooltip" title="" onclick="delete_timeline('<?php echo $value['id']; ?>')" data-original-title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash"></i></a>
                                                            </span>
                                                        <?php } ?>
                                                        <?php if ($this->rbac->hasPrivilege('staff_timeline', 'can_edit')) { ?>
                                                            <span class="time">
                                                                <a data-toggle="tooltip" class="pull-right edit_timeline defaults-c text-right" data-id="<?php echo $value["id"]; ?>" data-original-title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>
                                                            </span>
                                                        <?php } ?>

                                                        <?php if (!empty($value["document"])) { ?>
                                                            <span class="time"><a class="defaults-c text-right" data-toggle="tooltip" title="" href="<?php echo base_url() . "admin/timeline/download_staff_timeline/" . $value["id"] ?>" data-original-title="Download"><i class="fa fa-download"></i></a></span>
                                                        <?php } ?>
                                                        <h3 class="timeline-header text-aqua"> <?php echo $value['title']; ?> </h3>
                                                        <div class="timeline-body">
                                                            <?php echo $value['description']; ?>

                                                        </div>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                            <li><i class="fa fa-clock-o bg-gray"></i></li>
                                        <?php } ?>
                                        </ul>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="attendance">
                            <div class="row">
                                <div class="col-lg-3 col-md-4 col-sm-6 col20per">
                                    <div class="staffprofile">
                                        <h5><?php echo $this->lang->line('total_present'); ?></h5>
                                        <h4 class="total_present"><?php echo count($countAttendance['Present']); ?></h4>
                                        <div class="icon">
                                            <i class="fa  fa-check-square-o"></i>
                                        </div>
                                    </div>
                                </div><!--./col-md-3-->
                                <div class="col-lg-3 col-md-4 col-sm-6 col20per">
                                    <div class="staffprofile">
                                        <h5><?php echo $this->lang->line('total_late'); ?></h5>
                                        <h4 class="total_late"><?php echo count($countAttendance['Late']); ?></h4>
                                        <div class="icon">
                                            <i class="fa  fa-check-square-o"></i>
                                        </div>
                                    </div>
                                </div><!--./col-md-3-->
                                <div class="col-lg-3 col-md-4 col-sm-6 col20per">
                                    <div class="staffprofile">
                                        <h5><?php echo $this->lang->line('total_absent'); ?></h5>
                                        <h4 class="total_absent"><?php echo count($countAttendance['Absent']); ?></h4>
                                        <div class="icon">
                                            <i class="fa  fa-check-square-o"></i>
                                        </div>
                                    </div>
                                </div><!--./col-md-3-->
                                <div class="col-lg-3 col-md-4 col-sm-6 col20per">
                                    <div class="staffprofile">
                                        <h5><?php echo $this->lang->line('total_half_day'); ?></h5>
                                        <h4 class="total_half_day"><?php echo count($countAttendance['Half Day']); ?></h4>
                                        <div class="icon">
                                            <i class="fa fa-check-square-o"></i>
                                        </div>
                                    </div>
                                </div><!--./col-md-3-->
                                <div class="col-lg-3 col-md-4 col-sm-6 col20per">
                                    <div class="staffprofile">
                                        <h5><?php echo $this->lang->line('total_holiday'); ?></h5>
                                        <h4 class="total_holiday"><?php echo count($countAttendance['Holiday']); ?></h4>
                                        <div class="icon">
                                            <i class="fa  fa-check-square-o"></i>
                                        </div>
                                    </div>
                                </div><!--./col-md-3-->
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-3">
                                    <form id="" action="" method="">
                                        <div class="form-group">
                                            <label class="sess18"><?php echo $this->lang->line('year'); ?></label>
                                            <div class="sessyearbox">
                                                <select class="form-control" style="margin-top: -5px;" name="year" onchange="ajax_attendance('<?php echo $staff["id"]; ?>', this.value)">
                                                    <?php foreach ($yearlist as $yearkey => $yearvalue) {
                                                    ?>
                                                        <option <?php
                                                                if ($yearvalue["year"] == date("Y")) {
                                                                    echo "selected";
                                                                }
                                                                ?> value="<?php echo $yearvalue["year"]; ?>"><?php echo $yearvalue["year"]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <span class="text-danger"><?php echo form_error('year'); ?></span>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-9 col-sm-9">
                                    <div class="halfday pull-right">
                                        <?php
                                        foreach ($attendencetypeslist as $key_type => $value_type) {
                                        ?>
                                            <b>
                                                <?php
                                                $att_type = str_replace(" ", "_", strtolower($value_type['type']));
                                                echo $this->lang->line($att_type) . ": " . $value_type['key_value'] . "";
                                                ?>
                                            </b>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div style="position: relative;" class="row">
                                <div class="modal_inner_loader displaynone"></div>
                                <div id="ajaxattendance" class="table-responsive mt10">
                                    <div class="download_label"><?php echo $this->lang->line('details_for'); ?> <?php echo $staff["name"] . " " . $staff["surname"]; ?></div>
                                    <table class="table table-striped table-bordered table-hover" id="attendancetable">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <?php echo $this->lang->line('date_month'); ?>
                                                </th>
                                                <?php foreach ($monthlist as $monthkey => $monthvalue) {
                                                ?>
                                                    <th><?php echo $monthvalue; ?></th>
                                                <?php }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $j = 0;

                                            for ($i = 1; $i <= 31; $i++) {
                                            ?>
                                                <tr>
                                                    <td><?php echo sprintf("%02d", $i) ?></td>
                                                    <?php
                                                    foreach ($monthlist as $key => $value) {
                                                        $datemonth = date("m", strtotime($key));
                                                        $att_dates = date("Y") . "-" . $datemonth . "-" . sprintf("%02d", $i);
                                                    ?>
                                                        <td>
                                                            <span data-toggle="popover" class="detail_popover" data-original-title="" title=""><a href="#" style="color:#333"><?php
                                                                                                                                                                                if (array_key_exists($att_dates, $resultlist)) {
                                                                                                                                                                                    if (!empty($resultlist[$att_dates]["key"])) {
                                                                                                                                                                                        echo $resultlist[$att_dates]["key"];
                                                                                                                                                                                    } else {
                                                                                                                                                                                    }
                                                                                                                                                                                }
                                                                                                                                                                                ?></a>
                                                            </span>
                                                        </td>
                                                    <?php
                                                    } ?>
                                                </tr>
                                            <?php
                                                $j++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php if ($staff['user_type'] == 2) {
                        ?>
                            <div class="tab-pane" id="reviews">
                                <div class="row">
                                </div>
                                <div class="timeline-header no-border">
                                    <div class="table-responsive" style="clear: both;">
                                        <div class="download_label"><?php echo $this->lang->line('details_for'); ?> <?php echo $staff["name"] . " " . $staff["surname"]; ?></div>
                                        <table class="table table-striped table-bordered table-hover example">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $this->lang->line('name'); ?></th>
                                                    <th><?php echo $this->lang->line('role'); ?></th>
                                                    <th><?php echo $this->lang->line('rate'); ?></th>
                                                    <th><?php echo $this->lang->line('comment'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($user_reviewlist as $value) { ?>
                                                    <tr>
                                                        <td><?php
                                                            if ($value['role'] == 'student') {
                                                                echo $value['firstname'] . " " . $value['lastname'];
                                                            } else {
                                                                echo $value['guardian_name'];
                                                            }
                                                            ?></td>
                                                        <td><?php echo $value['role']; ?></td>
                                                        <td><?php
                                                            $j = 5;
                                                            for ($i = 1; $i <= $j; $i++) {
                                                            ?>
                                                                <span class="fa fa-star" <?php if ($i <= $value['rate']) { ?> style="color:orange" <?php } ?>></span>
                                                            <?php }
                                                            ?>
                                                        </td>
                                                        <td><?php echo $value['comment']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="tab-pane" id="leaves">
                            <div class="row row-flex">
                                <?php foreach ($leavedetails as $ldkey => $ldvalue) {
                                ?>
                                    <?php if (!empty($ldvalue["alloted_leave"])) {
                                    ?>
                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                            <div class="staffprofile">
                                                <h5><?php echo $ldvalue["type"] . " (" . $ldvalue["alloted_leave"] . ")"; ?></h5>
                                                <p><?php echo $this->lang->line('used'); ?>: <?php
                                                                                                if (!empty($ldvalue["approve_leave"])) {
                                                                                                    echo $ldvalue["approve_leave"];
                                                                                                } else {
                                                                                                    echo "0";
                                                                                                }
                                                                                                ?></p>
                                                <p><?php echo $this->lang->line('available'); ?>: <?php echo $ldvalue["alloted_leave"] - $ldvalue["approve_leave"] ?></p>
                                                <div class="icon">
                                                    <i class="fa fa-plane"></i>
                                                </div>
                                            </div>
                                        </div><!--./col-md-3-->
                                <?php
                                    }
                                }
                                ?>
                            </div>
                            <div class="timeline-header no-border">
                                <div class="download_label"><?php echo $this->lang->line('details_for'); ?> <?php echo $staff["name"] . " " . $staff["surname"] . $staff['employee_id']; ?></div>
                                <div class="table-responsive overflow-visible">
                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                            <th><?php echo $this->lang->line('leave_type'); ?></th>
                                            <th><?php echo $this->lang->line('leave_date'); ?></th>
                                            <th><?php echo $this->lang->line('days'); ?></th>
                                            <th><?php echo $this->lang->line('apply_date'); ?></th>
                                            <th><?php echo $this->lang->line("status") ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line("action") ?></th>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($staff_leaves as $key => $value) {

                                                if ($value["status"] == "approved" || $value["status"] == "approve") {
                                                    $status1 = "approve";
                                                    $label = "class='label label-success'";
                                                } else if ($value["status"] == "pending") {
                                                    $status1 = "pending";
                                                    $label = "class='label label-warning'";
                                                } else if ($value["status"] == "disapproved" || $value["status"] == "disapprove") {
                                                    $status1 = "disapprove";
                                                    $label = "class='label label-danger'";
                                                }
                                            ?>
                                                <tr>
                                                    <td><?php echo $value["type"]; ?></td>
                                                    <td class="white-space-nowrap"><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['leave_from'])) . " - " . date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['leave_to'])); ?></td>
                                                    <td><?php echo $value["leave_days"]; ?></td>
                                                    <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['date'])); ?></td>
                                                    <td><small style="text-transform: capitalize;" <?php echo $label ?>><?php echo $status[$status1]; ?></small></td>
                                                    <td class="text-right white-space-nowrap"><a href="#leavedetails" onclick="getRecord('<?php echo $value["id"] ?>')" role="button" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>"><i class="fa fa-eye"></i></a>
                                                        <?php if (!empty($value['document_file'])) { ?>
                                                            <a href="<?php echo base_url(); ?>admin/leaverequest/downloadleaverequestdoc/<?php echo $value['staff_id'] . "/" . $value['id']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                                <i class="fa fa-download"></i>
                                                            </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<div id="leavedetails" class="modal fade " role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-dialog modal-dialog2 modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('details'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form role="form" id="leavedetails_form" action="">
                            <div class="col-md-12 table-responsive">
                                <table class="table mb0 table-striped table-bordered examples">
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('name'); ?></th>
                                        <td width="35%"><span id='name'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('staff_id'); ?></th>
                                        <td width="35%"><span id="employee_id"></span>
                                            <span class="text-danger"><?php echo form_error('leave_request_id'); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('leave'); ?></th>
                                        <td><span id='leave_from'></span> - <label for="exampleInputEmail1"> </label><span id='leave_to'> </span> (<span id='days'></span>)
                                            <span class="text-danger"><?php echo form_error('leave_from'); ?></span>
                                        </td>
                                        <th><?php echo $this->lang->line('leave_type'); ?></th>
                                        <td><span id="leave_type"></span>
                                            <input id="leave_request_id" name="leave_request_id" placeholder="" type="hidden" class="form-control" />
                                            <span class="text-danger"><?php echo form_error('leave_request_id'); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <td>
                                            <span id="status"></span>
                                        </td>
                                        <th><?php echo $this->lang->line('apply_date'); ?></th>
                                        <td><span id="applied_date"></span></td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('reason'); ?></th>
                                        <td><span id="reason"> </span></td>
                                        <th><?php echo $this->lang->line('note'); ?></th>
                                        <td>
                                            <span id="remark"> </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myTimelineModal" role="dialog">
    <div class="modal-dialog modal-sm400">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title"><?php echo $this->lang->line('add_timeline'); ?> </h4>
            </div>
            <form id="timelineform" name="timelineform" method="post" action="<?php echo base_url() . "admin/timeline/add_staff_timeline" ?>" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <?php echo $this->customlib->getCSRF(); ?>
                    <div id='timeline_hide_show'>
                        <input type="hidden" name="staff_id" value="<?php echo $staff["id"] ?>" id="staff_id">
                        <h4></h4>
                        <div class="">
                            <div class="form-group">
                                <label for=""><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                <input id="timeline_title" name="timeline_title" placeholder="" type="text" class="form-control" />
                                <span class="text-danger"><?php echo form_error('timeline_title'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for=""><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
                                <input id="timeline_date" name="timeline_date" value="<?php echo set_value('timeline_date', date($this->customlib->getSchoolDateFormat())); ?>" placeholder="" type="text" class="form-control date" />
                                <span class="text-danger"><?php echo form_error('timeline_date'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for=""><?php echo $this->lang->line('description'); ?></label>
                                <textarea id="timeline_desc" name="timeline_desc" placeholder="" class="form-control"></textarea>
                                <span class="text-danger"><?php echo form_error('description'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for=""><?php echo $this->lang->line('attach_document'); ?></label>
                                <div class=""><input id="timeline_doc_id" name="timeline_doc" placeholder="" type="file" class="filestyle form-control" data-height="40" value="<?php echo set_value('timeline_doc'); ?>" />
                                    <span class="text-danger"><?php echo form_error('timeline_doc'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-align--top"><?php echo $this->lang->line('visible_to_this_person'); ?></label>
                                <input id="visible_check" checked="checked" name="visible_check" value="yes" placeholder="" type="checkbox" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="clear:both">
                    <button type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>

                    <button type="reset" id="reset" style="display: none" class="btn btn-info pull-right"><?php echo $this->lang->line('reset'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="scheduleModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title_logindetail"></h4>
            </div>
            <div class="modal-body_logindetail">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<div id="payslipview" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('details'); ?> <span id="print" class=></span></h4>
            </div>
            <div class="modal-body" id="testdata">

            </div>
        </div>
    </div>
</div>

<div id="changepwdmodal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('change_password'); ?></h4>
            </div>
            <form method="post" id="changepassbtn" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email"><?php echo $this->lang->line('new_password'); ?> <small class="req"> *</small></label>
                        <input type="password" class="form-control" name="new_pass" id="pass">
                    </div>
                    <div class="form-group">
                        <label for="pwd"><?php echo $this->lang->line('confirm_password'); ?> <small class="req"> *</small></label>
                        <input type="password" class="form-control" name="confirm_pass" id="pwd">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('saving'); ?>"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="disablemodal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('disable_staff'); ?></h4>
            </div>
            <form method="post" id="disablebtn" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email"><?php echo $this->lang->line('date'); ?> <small class="req"> *</small></label>
                        <input type="text" class="form-control date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" name="date" readonly="readonly">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edittimelineModal" role="dialog">
    <div class="modal-dialog modal-sm400">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_timeline'); ?></h4>
            </div>
            <form id="edittimelineform" name="timelineform" method="post" action="<?php echo base_url() . "admin/timeline/add_staff_timeline" ?>" enctype="multipart/form-data">
                <div class="modal-body pb0">
                    <?php echo $this->customlib->getCSRF(); ?>
                    <div id="edittimelinedata"></div>
                </div>
                <div class="modal-footer" style="clear:both">
                    <button type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>
                    <button type="reset" id="reset" style="display: none" class="btn btn-info pull-right"><?php echo $this->lang->line('reset'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    function disable_staff(id) {
        $('#disablemodal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true

        });
    }

    $(".myTransportFeeBtn").click(function() {
        $("span[id$='_error']").html("");
        $('#transport_amount').val("");
        $('#transport_amount_discount').val("0");
        $('#transport_amount_fine').val("0");
        var student_session_id = $(this).data("student-session-id");
        $('.transport_fees_title').html("<b><?php echo $this->lang->line('upload_documents'); ?></b>");
        $('#transport_student_session_id').val(student_session_id);
        $('#myTransportFeesModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true

        });
    });
</script>

<script type="text/javascript">
    $("#myTimelineButton").click(function() {
        $("#reset").click();
        $('.transport_fees_title').html("<b><?php echo $this->lang->line('add_timeline'); ?></b>");
        $(".dropify-clear").click();
        $('#myTimelineModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true

        });
    });

    $("#timelineform").on('submit', (function(e) {
        e.preventDefault();
        var $this = $(this).find("button[type=submit]:focus");
        $.ajax({
            url: "<?php echo site_url("admin/timeline/add_staff_timeline") ?>",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $this.button('loading');

            },
            success: function(res) {
                if (res.status == "fail") {
                    var message = "";
                    $.each(res.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(res.message);
                    window.location.reload(true);
                }
            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function() {
                $this.button('reset');
            }
        });
    }));

    $(document).ready(function(e) {
        $("#disablebtn").on('submit', (function(e) {
            var staff_id = $("#staff_id").val();
            e.preventDefault();
            $.ajax({
                url: "<?php echo site_url('admin/staff/disablestaff/') ?>" + staff_id,
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function(index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }

                },
                error: function(e) {
                    alert("<?php echo $this->lang->line('fail'); ?>");
                    console.log(e);
                }
            });
        }));
    });

    $(document).ready(function(e) {
        $("form#changepassbtn").on('submit', (function(e) {

            var staff_id = $("#staff_id").val();
            var form = $(this);
            var $this = form.find("button[type=submit]:focus");
            e.preventDefault();

            $.ajax({
                url: "<?php echo site_url('admin/staff/change_password/') ?>" + staff_id,
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $this.button('loading');
                },
                success: function(res) {
                    if (res.status == "fail") {
                        var message = "";
                        $.each(res.error, function(index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(res.message);
                        window.location.reload(true);
                    }
                    $this.button('loading');
                },
                error: function(xhr) { // if error occured
                    alert("Error occured.please try again");
                    $this.button('reset');
                },
                complete: function() {
                    $this.button('reset');
                }



            });
        }));
    });

    function delete_timeline(id) {
        var staff_id = $("#staff_id").val();
        if (confirm('<?php echo $this->lang->line("delete_confirm") ?>')) {

            $.ajax({
                url: '<?php echo base_url(); ?>admin/timeline/delete_staff_timeline/' + id,
                success: function(res) {
                    $.ajax({
                        url: '<?php echo base_url(); ?>admin/timeline/staff_timeline/' + staff_id,
                        success: function(res) {
                            $('#timeline_list').html(res);
                            successMsg('<?php echo $this->lang->line('delete_message'); ?>');
                        },
                        error: function() {
                            alert("<?php echo $this->lang->line('fail'); ?>");
                        }
                    });
                },
                error: function() {
                    alert("<?php echo $this->lang->line('fail'); ?>");
                }
            });
        }
    }

    $(document).ready(function() {
        $(document).on('click', '.change_password', function() {
            $('#changepwdmodal').modal('show');
        });

        $("#attendancetable").DataTable({
            searching: false,
            ordering: false,
            paging: false,
            bSort: false,
            info: false,
            dom: "Bfrtip",
            buttons: [

                {
                    extend: 'copyHtml5',
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: 'Copy',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',

                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: 'CSV',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'

                    }
                },

                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: 'Print',
                    title: $('.download_label').html(),
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '10pt');

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    },
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'colvis',
                    text: '<i class="fa fa-columns"></i>',
                    titleAttr: 'Columns',
                    title: $('.download_label').html(),
                    postfixButtons: ['colvisRestore']
                },
            ]
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.detail_popover').popover({
            placement: 'right',
            title: '',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function() {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });

    function getRecord(id) {
        $('input:radio[name=status]').attr('checked', false);
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/leaverequest/leaveRecord',
            type: 'POST',
            data: {
                id: id
            },
            dataType: "json",
            success: function(result) {

                $('inputs[name="leave_request_id"]').val(result.id);
                $('#name').html(result.name + ' ' + result.surname);
                $('#leave_from').html(result.leavefrom);
                $('#leave_to').html(result.leaveto);
                $('#leave_type').html(result.type);
                $('#reason').html(result.employee_remark);
                $('#applied_date').html(result.date);
                $('#days').html(result.leave_days + ' Days');
                $("#remark").html(result.admin_remark);
                $("#employee_id").html(' ' + result.employee_id);
                $("#status").html(' ' + result.leave_status);

            }
        });

        $('#leavedetails').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    };

    function ajax_attendance(id, year) {

        $.ajax({
            url: baseurl + 'admin/staff/ajax_attendance',
            type: 'POST',
            data: {
                "id": id,
                "year": year
            },
            dataType: "JSON",
            beforeSend: function() {
                $('.modal_inner_loader').css({
                    'display': 'block'
                });
            },
            success: function(result) {
                $("#ajaxattendance").html(result.page);
                console.log(result.countAttendance);
                $('.total_present').text(result.countAttendance.present);
                $('.total_late').text(result.countAttendance.late);
                $('.total_absent').text(result.countAttendance.absent);
                $('.total_half_day').text(result.countAttendance.half_day);
                $('.total_holiday').text(result.countAttendance.holiday);
                $('.modal_inner_loader').fadeOut("slow");
            },
            error: function(xhr) { // if error occured
                alert("Error occured.please try again");
                $('.modal_inner_loader').fadeOut("slow");
            },
            complete: function() {
                $('.modal_inner_loader').fadeOut("slow");
            }


        });
    }

    function getPayslip(id) {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/payroll/payslipView',
            type: 'POST',
            data: {
                payslipid: id
            },

            success: function(result) {
                $("#print").html("<a href='#' class='pull-right modal-title moprintblack ' onclick='printData(" + id + ")'  title='<?php echo $this->lang->line('print'); ?>'><i class='fa fa-print'></i></a>");
                $("#testdata").html(result);

            }
        });

        $('#payslipview').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    };

    function printData(id) {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/payroll/payslipView',
            type: 'POST',
            data: {
                payslipid: id
            },
            success: function(result) {
                $("#testdata").html(result);
                popup(result);
            }
        });
    }

    function popup(data) {
        var base_url = '<?php echo base_url() ?>';
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({
            "position": "absolute",
            "top": "-1000000px"
        });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function() {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);

        return true;
    }
</script>

<script>
    $('.edit_timeline').click(function() {
        $('#edittimelineModal').modal('show');
        var id = $(this).attr('data-id');
        $.ajax({
            url: "<?php echo site_url("admin/timeline/getstaffsingletimeline") ?>",
            type: "POST",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#edittimelinedata').html(response.page);
            }
        });
    })

    $("#edittimelineform").on('submit', (function(e) {
        e.preventDefault();
        var $this = $(this).find("button[type=submit]:focus");
        $.ajax({
            url: "<?php echo site_url("admin/timeline/editstafftimeline") ?>",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $this.button('loading');
            },
            success: function(res) {
                if (res.status == "fail") {
                    var message = "";
                    $.each(res.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(res.message);
                    window.location.reload(true);
                }
            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function() {
                $this.button('reset');
            }
        });
    }));
</script>