<style type="text/css">
    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }
    }

    .option_grade {
        display: none;
    }
</style>

<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content">
        <?php
        foreach ($unread_notifications as $notice_key => $notice_value) {
        ?>
            <div class="dashalert alert alert-success alert-dismissible" role="alert">
                <button type="button" class="alertclose close close_notice stualert" data-dismiss="alert" aria-label="Close" data-noticeid="<?php echo $notice_value->id; ?>"><span aria-hidden="true">&times;</span></button>
                <a href="<?php echo site_url('user/notification') ?>"><?php echo $notice_value->title; ?></a>
            </div>
        <?php
        }
        ?>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12">
                <div class="box box-primary">
                    <div class="box box-widget widget-user-2 mb0">
                        <div class="widget-user-header bg-gray-light overflow-hidden">
                            <div class="widget-user-image">
                                <?php

                                if ($sch_setting->student_photo) {
                                ?>
                                    <img class="user-img-grid center-block img-rounded" src="       
       <?php
                                    if (!empty($student["image"])) {
                                        echo base_url() . $student["image"] . img_time();
                                    } else {

                                        if ($student['gender'] == 'Female') {
                                            echo base_url() . "uploads/student_images/default_female.jpg" . img_time();
                                        } else {
                                            echo base_url() . "uploads/student_images/default_male.jpg" . img_time();
                                        }
                                    }
        ?>" alt="User profile picture">
                                <?php } ?>
                            </div>

                            <h3 class="widget-user-username"><?php echo $this->customlib->getFullname($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></h3>
                            <h5 class="widget-user-desc mb5"><?php echo $this->lang->line('admission_no'); ?> <span class="text-aqua"><?php echo $student['admission_no']; ?></span></h5>
                            <?php if ($sch_setting->roll_no) { ?>
                                <h5 class="widget-user-desc"><?php echo $this->lang->line('roll_number'); ?> <span class="text-aqua"><?php echo $student['roll_no']; ?></span></h5>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="box-body box-profile pt0">
                        <ul class="list-group list-group-unbordered">

                            <li class="list-group-item border0">
                                <b><?php echo $this->lang->line('class'); ?></b> <a class="pull-right text-aqua"><?php echo $student['class'] . ' (' . $student['session'] . ')'; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b><?php echo $this->lang->line('section'); ?></b> <a class="pull-right text-aqua"><?php echo $student['section']; ?></a>
                            </li>
                            <?php if ($sch_setting->rte) { ?>
                                <li class="list-group-item">
                                    <b><?php echo $this->lang->line('rte'); ?></b> <a class="pull-right text-aqua"> <?php echo $this->lang->line(strtolower($student['rte'])); ?></a>
                                </li>
                            <?php } ?>

                            <li class="list-group-item">
                                <b><?php echo $this->lang->line('gender'); ?></b> <a class="pull-right text-aqua"> <?php echo $this->lang->line(strtolower($student['gender'])); ?></a>
                            </li>

                            <?php if ($sch_setting->student_barcode == 1) { ?>
                                <li class="list-group-item listnoback">
                                    <b><?php echo $this->lang->line('barcode'); ?></b>
                                    <?php if (file_exists("./uploads/student_id_card/barcodes/" . $student['admission_no'] . ".png")) { ?>
                                        <a class="pull-right text-aqua">
                                            <img src="<?php echo base_url('uploads/student_id_card/barcodes/' . $student['admission_no'] . '.png'); ?>" width="auto" height="auto" /></a>
                                    <?php } ?>
                                </li>
                            <?php } ?>
                            <?php if ($sch_setting->student_barcode == 1) { ?>
                                <li class="list-group-item listnoback">
                                    <b><?php echo $this->lang->line('qrcode'); ?></b>
                                    <?php if (file_exists("./uploads/student_id_card/qrcode/" . $student['admission_no'] . ".png")) { ?>
                                        <a class="pull-right text-aqua" href="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/qrcode/' . $student['admission_no'] . '.png'); ?>" target="_blank">
                                            <img class="h-50" src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/qrcode/' . $student['admission_no'] . '.png'); ?>" width="auto" height="auto" /></a>
                                    <?php } ?>
                                </li>
                            <?php } ?>

                            <!------- Behaviour Report Start-------->
                            <?php

                            if ($this->module_lib->hasModule('behaviour_records') && $this->module_lib->hasActive('behaviour_records') && $this->studentmodule_lib->hasActive('behaviour_records')) {
                            ?>
                                <li class="list-group-item listnoback">
                                    <b><?php echo $this->lang->line('behaviour_score'); ?></b> <a class="pull-right text-aqua"><?php echo $student['total_points']; ?></a>
                                </li>
                            <?php

                            }
                            ?>
                            <!------- Behaviour Report End--------->

                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-12">
                <div class="nav-tabs-custom theme-shadow">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('profile'); ?></a></li>
                        <?php if ($this->module_lib->hasActive('fees_collection') && $this->studentmodule_lib->hasActive('fees')) { ?>
                            <li class=""><a href="#fee" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('fees'); ?></a></li>
                        <?php } ?>

                        <?php if ($this->module_lib->hasActive('examination') && $this->studentmodule_lib->hasActive('examinations')) { ?>
                            <li><a href="#exam" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('exam'); ?></a></li>
                        <?php } ?>


                        <!------- CBSE Exam Start-------->
                        <?php
                        if ($this->module_lib->hasModule('cbseexam') && $this->module_lib->hasActive('cbseexam') && $this->studentmodule_lib->hasActive('cbseexam')) {  ?>
                            <li class=""><a href="#cbseexam" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('cbse_exam'); ?></a></li>
                        <?php
                        }
                        ?>
                        <!------- CBSE Exam End--------->


                        <?php
                        if ($this->module_lib->hasActive('student_attendance') && $this->studentmodule_lib->hasActive('attendance')) {
                            if (!$sch_setting->attendence_type) {
                        ?>
                                <li class="">
                                    <a href="#attendance" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('attendance'); ?></a>
                                </li>

                        <?php
                            }
                        }
                        ?>
                        <?php if ($sch_setting->upload_documents) { ?>
                            <li class=""><a href="#documents" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('documents'); ?></a></li>
                        <?php } ?>

                        <?php
                        if ($this->studentmodule_lib->hasActive('student_timeline')) {
                            if ($this->studentmodule_lib->hasActive('student_timeline', 'can_view')) {
                        ?>
                                <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('timeline'); ?></a></li>
                        <?php }
                        } ?>

                        <!------- Behaviour Report Start-------->
                        <?php
                        if ($this->module_lib->hasModule('behaviour_records') && $this->module_lib->hasActive('behaviour_records') && $this->studentmodule_lib->hasActive('behaviour_records')) {  ?>
                            <li class=""><a href="#incident" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('student_behaviour'); ?></a></li>
                        <?php
                        }
                        ?>
                        <!------- Behaviour Report End--------->

                        <?php
                        if ($sch_setting->student_profile_edit) {
                        ?>
                            <li class="pull-right">
                                <a href="<?php echo site_url('user/user/edit') ?>" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $this->lang->line('edit') ?>"><i class="fa fa-pencil"></i>
                                </a>
                            </li>
                        <?php
                        }
                        ?>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="activity">
                            <div class="tshadow mb25 bozero">
                                <div class="table-responsive around10 pt0">
                                    <table class="table3 table-striped table-hover">
                                        <tbody>
                                            <?php if ($sch_setting->admission_date) {
                                            ?>
                                                <tr class="bordertop">
                                                    <td width="35%"><?php echo $this->lang->line('admission_date'); ?></td>
                                                    <td class="col-md-5">
                                                        <?php
                                                        if (!empty($student['admission_date']) && $student['admission_date'] != '0000-00-00') {
                                                            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['admission_date']));
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td><?php echo $this->lang->line('date_of_birth'); ?></td>
                                                <td><?php
                                                    if (!empty($student['dob'])) {
                                                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']));
                                                    }
                                                    ?></td>
                                            </tr>
                                            <?php if ($sch_setting->category) {
                                            ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('category'); ?></td>
                                                    <td>
                                                        <?php
                                                        foreach ($category_list as $value) {
                                                            if ($student['category_id'] == $value['id']) {
                                                                echo $value['category'];
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->mobile_no) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('mobile_number'); ?></td>
                                                    <td><?php echo $student['mobileno']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->cast) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('caste'); ?></td>
                                                    <td><?php echo $student['cast']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->religion) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('religion'); ?></td>
                                                    <td><?php echo $student['religion']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->student_email) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('email'); ?></td>
                                                    <td><?php echo $student['email']; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php
                                            $cutom_fields_data = get_custom_table_values($student['id'], 'students');
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
                                            <tr>
                                                <td><?php echo $this->lang->line('note'); ?></td>
                                                <td><?php echo $student['note']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tshadow mb25 bozero">
                                <h3 class="pagetitleh2"><?php echo $this->lang->line('address_details'); ?></h3>
                                <div class="table-responsive around10 pt0">
                                    <table class="table3 table-striped table-hover">
                                        <tbody>
                                            <?php if ($sch_setting->current_address) { ?>
                                                <tr>
                                                    <td width="35%"><?php echo $this->lang->line('current_address'); ?></td>
                                                    <td class="col-md-5"><?php echo $student['current_address']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->permanent_address) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('permanent_address'); ?></td>
                                                    <td><?php echo $student['permanent_address']; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php if (($sch_setting->father_name) || ($sch_setting->father_phone) || ($sch_setting->father_occupation) || ($sch_setting->father_pic) || ($sch_setting->mother_name) || ($sch_setting->mother_phone) || ($sch_setting->mother_occupation) || ($sch_setting->mother_pic) || ($sch_setting->guardian_name) || ($sch_setting->guardian_occupation) || ($sch_setting->guardian_relation) || ($sch_setting->guardian_phone) || ($sch_setting->guardian_email) || ($sch_setting->guardian_pic) || ($sch_setting->guardian_address)) {
                            ?>
                                <div class="tshadow mb25 bozero">
                                    <h3 class="pagetitleh2"><?php echo $this->lang->line('parent_guardian_detail'); ?> </h3>
                                    <div class="table-responsive around10 pt0">
                                        <table class="table3 table-striped table-hover">
                                            <?php if ($sch_setting->father_name) {
                                            ?>
                                                <tr>
                                                    <td width="35%"><?php echo $this->lang->line('father_name'); ?></td>
                                                    <td><?php echo $student['father_name']; ?></td>
                                                    <?php if ($sch_setting->father_pic) {
                                                    ?>
                                                        <td rowspan="3" width="100"><img class="profile-user-img img-responsive img-rounded border0" src="             
            <?php
                                                        if (!empty($student["father_pic"])) {
                                                            echo base_url() . $student["father_pic"] . img_time();
                                                        } else {
                                                            echo base_url() . "uploads/student_images/no_image.png" . img_time();
                                                        }
            ?>
        "></td>
                                                    <?php } ?>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->father_phone) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('father_phone'); ?></td>
                                                    <td><?php echo $student['father_phone']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->father_occupation) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('father_occupation'); ?></td>
                                                    <td><?php echo $student['father_occupation']; ?></td>
                                                </tr>
                                            <?php }
                                            ?>
                                            <tr class="bordertop">
                                                <td><?php if ($sch_setting->mother_name) {
                                                        echo $this->lang->line('mother_name');
                                                    } ?></td>
                                                <td><?php if ($sch_setting->mother_name) {
                                                        echo $student['mother_name'];
                                                    } ?></td>

                                                <td rowspan="3" width="100"> <?php if ($sch_setting->mother_pic) {
                                                                                ?><img class="profile-user-img img-responsive img-rounded border0" src="
        <?php
                                                                                    if (!empty($student["mother_pic"])) {
                                                                                        echo base_url() . $student["mother_pic"] . img_time();
                                                                                    } else {
                                                                                        echo base_url() . "uploads/student_images/no_image.png" . img_time();
                                                                                    }
        ?>
        
        "> <?php } ?></td>

                                            </tr>
                                            <?php if ($sch_setting->mother_phone) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('mother_phone'); ?></td>
                                                    <td><?php echo $student['mother_phone']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->mother_occupation) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('mother_occupation'); ?></td>
                                                    <td><?php echo $student['mother_occupation']; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <tr class="bordertop">
                                                <td><?php if ($sch_setting->guardian_name) {
                                                        echo $this->lang->line('guardian_name');
                                                    } ?></td>
                                                <td><?php if ($sch_setting->guardian_name) {
                                                        echo $student['guardian_name'];
                                                    } ?></td>
                                                <td rowspan="3"><?php if ($sch_setting->guardian_pic) {
                                                                ?><img class="profile-user-img img-responsive img-rounded border0" src="<?php
                                                                                if (!empty($student["guardian_pic"])) {
                                                                                    echo base_url() . $student["guardian_pic"] . img_time();
                                                                                } else {
                                                                                    echo base_url() . "uploads/student_images/no_image.png" . img_time();
                                                                                }
                                                                                ?>
        
        
        "> <?php } ?></td>

                                            </tr>
                                            <?php if ($sch_setting->guardian_email) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('guardian_email'); ?></td>
                                                    <td><?php echo $student['guardian_email']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->guardian_relation) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('guardian_relation'); ?></td>
                                                    <td><?php echo $student['guardian_relation']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->guardian_phone) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('guardian_phone'); ?></td>
                                                    <td><?php echo $student['guardian_phone']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->guardian_occupation) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('guardian_occupation'); ?></td>
                                                    <td><?php echo $student['guardian_occupation']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->guardian_address) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('guardian_address'); ?></td>
                                                    <td><?php echo $student['guardian_address']; ?></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php }
                            if ($sch_setting->route_list) {
                            ?>
                                <?php if ($student['vehroute_id'] != 0) { ?>
                                    <div class="tshadow mb25  bozero">
                                        <h3 class="pagetitleh2"><?php echo $this->lang->line('transport_details'); ?></h3>
                                        <div class="table-responsive around10 pt0">
                                            <table class="table3 table-striped table-hover tmb0">
                                                <tbody>
                                                    <tr>
                                                        <td width="35%"><?php echo $this->lang->line('pick_up_point'); ?></td>
                                                        <td class="col-md-5"><?php echo $student['pickup_point_name']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="35%"><?php echo $this->lang->line('route'); ?></td>
                                                        <td class="col-md-5"><?php echo $student['route_title']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo $this->lang->line('vehicle_number'); ?></td>
                                                        <td><?php echo $student['vehicle_no']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo $this->lang->line('driver_name'); ?></td>
                                                        <td><?php echo $student['driver_name']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo $this->lang->line('driver_contact'); ?></td>
                                                        <td><?php echo $student['driver_contact']; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                            <?php if ($sch_setting->hostel_id) {
                            ?>
                                <?php
                                if ($student['hostel_room_id'] != 0) {
                                ?>
                                    <div class="tshadow mb25  bozero">
                                        <h3 class="pagetitleh2"><?php echo $this->lang->line('hostel_details') ?></h3>
                                        <div class="table-responsive around10 pt0">
                                            <table class="table3 table-striped tmb0 table-hover">
                                                <tbody>
                                                    <tr>
                                                        <td width="35%"><?php echo $this->lang->line('hostel'); ?></td>
                                                        <td class="col-md-5"><?php echo $student['hostel_name']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo $this->lang->line('room_no'); ?></td>
                                                        <td><?php echo $student['room_no']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo $this->lang->line('room_type'); ?></td>
                                                        <td><?php echo $student['room_type']; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                            <div class="tshadow mb25  bozero">
                                <h3 class="pagetitleh2"><?php echo $this->lang->line('miscellaneous_details'); ?></h3>
                                <div class="table-responsive around10 pt0">
                                    <table class="table3 table-striped table-hover">
                                        <tbody>
                                            <?php if ($sch_setting->is_blood_group) { ?>
                                                <tr>
                                                    <td width="35%"><?php echo $this->lang->line('blood_group'); ?></td>
                                                    <td class="col-md-5"><?php echo $student['blood_group']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->is_student_house) { ?>
                                                <tr>
                                                    <td width="35%"><?php echo $this->lang->line('house'); ?></td>
                                                    <td class="col-md-5"><?php echo $student['house_name']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->student_height) { ?>
                                                <tr>
                                                    <td width="35%"><?php echo $this->lang->line('height'); ?></td>
                                                    <td class="col-md-5"><?php echo $student['height']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->student_weight) { ?>
                                                <tr>
                                                    <td width="35%"><?php echo $this->lang->line('weight'); ?></td>
                                                    <td class="col-md-5"><?php echo $student['weight']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->measurement_date) {
                                            ?>
                                                <tr>
                                                    <td width="35%"><?php echo $this->lang->line('measurement_date'); ?></td>
                                                    <td class="col-md-5"><?php
                                                                            if (!empty($student['measurement_date']) && $student['measurement_date'] != '0000-00-00') {
                                                                                echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['measurement_date']));
                                                                            }
                                                                            ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->previous_school_details) { ?>
                                                <tr>
                                                    <td width="35%"><?php echo $this->lang->line('previous_school_details'); ?></td>
                                                    <td class="col-md-5"><?php echo $student['previous_school']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->national_identification_no) { ?>
                                                <tr>
                                                    <td width="35%"><?php echo $this->lang->line('national_identification_number'); ?></td>
                                                    <td class="col-md-5"><?php echo $student['adhar_no']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->local_identification_no) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('local_identification_number'); ?></td>
                                                    <td><?php echo $student['samagra_id']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->bank_account_no) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('bank_account_number'); ?></td>
                                                    <td><?php echo $student['bank_account_no']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->bank_name) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('bank_name'); ?></td>
                                                    <td><?php echo $student['bank_name']; ?></td>
                                                </tr>
                                            <?php }
                                            if ($sch_setting->ifsc_code) { ?>
                                                <tr>
                                                    <td><?php echo $this->lang->line('ifsc_code'); ?></td>
                                                    <td><?php echo $student['ifsc_code']; ?></td>
                                                </tr>

                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <?php if ($this->studentmodule_lib->hasActive('fees')) { ?>
                            <div class="tab-pane" id="fee">
                                <!--<div class="download_label"><?php echo $this->lang->line("fees"); ?></div>-->
                                <?php
                                if (empty($student_due_fee) && empty($student_discount_fee)) {
                                ?>
                                    <div class="alert alert-danger">
                                        <?php echo $this->lang->line('no_record_found'); ?>
                                    </div>
                                <?php
                                } else {
                                ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="feetable">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $this->lang->line('fees_group'); ?></th>
                                                    <th><?php echo $this->lang->line('fees_code'); ?></th>
                                                    <th class="text text-left"><?php echo $this->lang->line('due_date'); ?></th>
                                                    <th class="text text-left"><?php echo $this->lang->line('status'); ?></th>
                                                    <th class="text text-right"><?php echo $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                    <th class="text text-right"><?php echo $this->lang->line('payment_id'); ?></th>
                                                    <th class="text text-left"><?php echo $this->lang->line('mode'); ?></th>
                                                    <th class="text text-left"><?php echo $this->lang->line('date'); ?></th>
                                                    <th class="text text-right"><?php echo $this->lang->line('discount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                    <th class="text text-right"><?php echo $this->lang->line('fine'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                    <th class="text text-right"><?php echo $this->lang->line('paid'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                    <th class="text text-right"><?php echo $this->lang->line('balance'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                </tr>
                                            </thead>
                                            <?php
                                            if (empty($student_due_fee) && empty($student_discount_fee)) {
                                            ?>
                                            <?php
                                            } else {
                                            ?>
                                                <tbody>
                                                    <?php
                                                    $total_amount           = "0";
                                                    $total_deposite_amount  = "0";
                                                    $total_fine_amount      = "0";
                                                    $total_discount_amount  = "0";
                                                    $total_balance_amount   = "0";
                                                    $alot_fee_discount      = 0;
                                                    $total_fees_fine_amount = 0;
                                                    foreach ($student_due_fee as $key => $fee) {

                                                        foreach ($fee->fees as $fee_key => $fee_value) {
                                                            $fee_paid     = 0;
                                                            $fee_discount = 0;
                                                            $fee_fine     = 0;

                                                            if (!empty($fee_value->amount_detail)) {
                                                                $fee_deposits = json_decode(($fee_value->amount_detail));

                                                                foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                                    $fee_paid     = $fee_paid + $fee_deposits_value->amount;
                                                                    $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                                                                    $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
                                                                }
                                                            }
                                                            $total_amount          = $total_amount + $fee_value->amount;
                                                            $total_discount_amount = $total_discount_amount + $fee_discount;
                                                            $total_deposite_amount = $total_deposite_amount + $fee_paid;
                                                            $total_fine_amount     = $total_fine_amount + $fee_fine;
                                                            $feetype_balance       = $fee_value->amount - ($fee_paid + $fee_discount);
                                                            $total_balance_amount  = $total_balance_amount + $feetype_balance;
                                                            if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {

                                                                $total_fees_fine_amount = $total_fees_fine_amount + $fee_value->fine_amount;
                                                            }

                                                    ?>
                                                            <?php
                                                            if ($feetype_balance > 0 && strtotime($fee_value->due_date) < strtotime(date('Y-m-d'))) {
                                                            ?>
                                                                <tr class="danger">
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <tr class="dark-gray">
                                                                <?php
                                                            }
                                                                ?>
                                                                <td>

                                                                    <?php
                                                                    if ($fee_value->is_system) {
                                                                        echo $this->lang->line($fee_value->name) . " (" . $this->lang->line($fee_value->type) . ")";
                                                                    } else {
                                                                        echo $fee_value->name . " (" . $fee_value->type . ")";
                                                                    }

                                                                    ?>

                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if ($fee_value->is_system) {
                                                                        echo  $this->lang->line($fee_value->code);
                                                                    } else {
                                                                        echo $fee_value->code;
                                                                    }


                                                                    ?>

                                                                </td>
                                                                <td class="text text-left">
                                                                    <?php
                                                                    if ($fee_value->due_date) {
                                                                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_value->due_date));
                                                                    } else {
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td class="text text-left">
                                                                    <?php
                                                                    if ($feetype_balance == 0) {
                                                                    ?><span class="label label-success"><?php echo $this->lang->line('paid'); ?></span><?php
                                                                                                        } else if (!empty($fee_value->amount_detail)) {
                                                                                                            ?><span class="label label-warning"><?php echo $this->lang->line('partial'); ?></span><?php
                                                                                                            } else {
                                                                                                                ?><span class="label label-danger"><?php echo $this->lang->line('unpaid'); ?></span><?php
                                                                                                            }
                                                                                                            ?>
                                                                </td>
                                                                <td class="text text-right">

                                                                    <?php echo amountFormat($fee_value->amount);
                                                                    if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {
                                                                    ?>
                                                                        <span class="text text-danger"><?php echo " + " . amountFormat($fee_value->fine_amount); ?></span>
                                                                    <?php

                                                                    }

                                                                    ?>
                                                                </td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td class="text text-right"><?php
                                                                                            echo (amountFormat($fee_discount));
                                                                                            ?></td>
                                                                <td class="text text-right"><?php
                                                                                            echo (amountFormat($fee_fine));
                                                                                            ?></td>
                                                                <td class="text text-right"><?php
                                                                                            echo (amountFormat($fee_paid));
                                                                                            ?></td>
                                                                <td class="text text-right"><?php
                                                                                            $display_none = "ss-none";
                                                                                            if ($feetype_balance > 0) {
                                                                                                $display_none = "";

                                                                                                echo (amountFormat($feetype_balance));
                                                                                            }
                                                                                            ?>
                                                                </td>
                                                                </tr>
                                                                <?php
                                                                if (!empty($fee_value->amount_detail)) {

                                                                    $fee_deposits = json_decode(($fee_value->amount_detail));

                                                                    foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                                ?>
                                                                        <tr class="white-td">
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td class="text-right"><img src="<?php echo base_url(); ?>backend/images/table-arrow.png" alt="" /></td>
                                                                            <td class="text text-center">
                                                                                <a href="#" data-toggle="popover" class="detail_popover"> <?php echo $fee_value->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?></a>
                                                                                <div class="fee_detail_popover" style="display: none">
                                                                                    <?php
                                                                                    if ($fee_deposits_value->description == "") {
                                                                                    ?>
                                                                                        <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <p class="text text-info"><?php echo $fee_deposits_value->description; ?></p>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </td>
                                                                            <td class="text text-center"><?php echo $this->lang->line(strtolower($fee_deposits_value->payment_mode)); ?></td>
                                                                            <td class="text text-center">
                                                                                <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_deposits_value->date)); ?>
                                                                            </td>
                                                                            <td class="text text-right"><?php echo (amountFormat($fee_deposits_value->amount_discount)); ?></td>
                                                                            <td class="text text-right"><?php echo (amountFormat($fee_deposits_value->amount_fine)); ?></td>
                                                                            <td class="text text-right"><?php echo (amountFormat($fee_deposits_value->amount)); ?></td>
                                                                            <td></td>
                                                                        </tr>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                        <?php
                                                        }
                                                    }
                                                        ?>
                                                        <?php

                                                        if (!empty($transport_fees)) {
                                                            foreach ($transport_fees as $transport_fee_key => $transport_fee_value) {

                                                                $fee_paid         = 0;
                                                                $fee_discount     = 0;
                                                                $fee_fine         = 0;
                                                                $fees_fine_amount = 0;
                                                                $feetype_balance  = 0;

                                                                if (!empty($transport_fee_value->amount_detail)) {
                                                                    $fee_deposits = json_decode(($transport_fee_value->amount_detail));

                                                                    foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                                        $fee_paid     = $fee_paid + $fee_deposits_value->amount;
                                                                        $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                                                                        $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
                                                                    }
                                                                }

                                                                $feetype_balance       = $transport_fee_value->fees - ($fee_paid + $fee_discount);


                                                                if (($transport_fee_value->due_date != "0000-00-00" && $transport_fee_value->due_date != null) && (strtotime($transport_fee_value->due_date) < strtotime(date('Y-m-d')))) {
                                                                    $fees_fine_amount  =  is_null($transport_fee_value->fine_percentage) ? $transport_fee_value->fine_amount : percentageAmount($transport_fee_value->fees, $transport_fee_value->fine_percentage);
                                                                    $total_fees_fine_amount = $total_fees_fine_amount + $fees_fine_amount;
                                                                }

                                                                $total_amount          +=  $transport_fee_value->fees;
                                                                $total_discount_amount +=  $fee_discount;
                                                                $total_deposite_amount += $fee_paid;
                                                                $total_fine_amount     += $fee_fine;
                                                                $total_balance_amount  +=  $feetype_balance;

                                                                if (strtotime($transport_fee_value->due_date) < strtotime(date('Y-m-d'))) {
                                                        ?>
                                                                    <tr class="danger font12">
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <tr class="dark-gray">
                                                                    <?php
                                                                }
                                                                    ?>

                                                                    <td align="left"><?php echo $this->lang->line('transport_fees'); ?></td>
                                                                    <td align="left"><?php echo $transport_fee_value->month; ?></td>
                                                                    <td align="left" class="text text-left">
                                                                        <?php echo $this->customlib->dateformat($transport_fee_value->due_date); ?> </td>
                                                                    <td align="left" class="text text-left width85">
                                                                        <?php
                                                                        if ($feetype_balance == 0) {
                                                                        ?><span class="label label-success"><?php echo $this->lang->line('paid'); ?></span><?php
                                                                                            } else if (!empty($transport_fee_value->amount_detail)) {
                                                                                                ?><span class="label label-warning"><?php echo $this->lang->line('partial'); ?></span><?php
                                                                                                } else {
                                                                                                    ?><span class="label label-danger"><?php echo $this->lang->line('unpaid'); ?></span><?php
                                                                                                }
                                                                                                ?>

                                                                    </td>
                                                                    <td class="text text-right"><?php echo amountFormat($transport_fee_value->fees);

                                                                                                if (($transport_fee_value->due_date != "0000-00-00" && $transport_fee_value->due_date != null) && (strtotime($transport_fee_value->due_date) < strtotime(date('Y-m-d')))) {
                                                                                                    $tr_fine_amount = $transport_fee_value->fine_amount;
                                                                                                    if ($transport_fee_value->fine_type != "" && $transport_fee_value->fine_type == "percentage") {

                                                                                                        $tr_fine_amount = percentageAmount($transport_fee_value->fees, $transport_fee_value->fine_percentage);
                                                                                                    }
                                                                                                ?>

                                                                            <span data-toggle="popover" class="text text-danger detail_popover"><?php echo " + " . (amountFormat($tr_fine_amount)); ?></span>

                                                                            <div class="fee_detail_popover" style="display: none">
                                                                                <?php
                                                                                                    if ($tr_fine_amount != "") {
                                                                                ?>
                                                                                    <p class="text text-danger"><?php echo $this->lang->line('fine'); ?></p>
                                                                                <?php
                                                                                                    }
                                                                                ?>

                                                                            <?php
                                                                                                }
                                                                            ?>
                                                                    </td>

                                                                    <td class="text text-left"></td>
                                                                    <td class="text text-left"></td>
                                                                    <td class="text text-left"></td>
                                                                    <td class="text text-right">
                                                                        <?php echo amountFormat($fee_discount); ?>
                                                                    </td>
                                                                    <td class="text text-right">
                                                                        <?php echo amountFormat($fee_fine); ?>
                                                                    </td>
                                                                    <td class="text text-right">
                                                                        <?php echo amountFormat($fee_paid); ?>

                                                                    </td>
                                                                    <td class="text text-right">
                                                                        <?php
                                                                        $display_none = "ss-none";
                                                                        if ($feetype_balance > 0) {
                                                                            $display_none = "";
                                                                            echo amountFormat($feetype_balance);
                                                                        }
                                                                        ?>
                                                                    </td>

                                                                    </tr>
                                                                    <?php
                                                                    if (!empty($transport_fee_value->amount_detail)) {

                                                                        $fee_deposits = json_decode(($transport_fee_value->amount_detail));

                                                                        foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                                    ?>
                                                                            <tr class="white-td">
                                                                                <td align="left"></td>
                                                                                <td align="left"></td>
                                                                                <td align="left"></td>
                                                                                <td align="left"></td>
                                                                                <td align="left"></td>
                                                                                <td class="text-right"><img src="<?php echo base_url(); ?>backend/images/table-arrow.png" alt="" /></td>
                                                                                <td class="text text-left">


                                                                                    <a href="#" data-toggle="popover" class="detail_popover"> <?php echo $transport_fee_value->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?></a>
                                                                                    <div class="fee_detail_popover" style="display: none">
                                                                                        <?php
                                                                                        if ($fee_deposits_value->description == "") {
                                                                                        ?>
                                                                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                                        <?php
                                                                                        } else {
                                                                                        ?>
                                                                                            <p class="text text-info"><?php echo $fee_deposits_value->description; ?></p>
                                                                                        <?php
                                                                                        }
                                                                                        ?>
                                                                                    </div>


                                                                                </td>
                                                                                <td class="text text-left"><?php echo $this->lang->line(strtolower($fee_deposits_value->payment_mode)); ?></td>
                                                                                <td class="text text-left">

                                                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_deposits_value->date)); ?>
                                                                                </td>
                                                                                <td class="text text-right"><?php echo amountFormat($fee_deposits_value->amount_discount); ?></td>
                                                                                <td class="text text-right"><?php echo amountFormat($fee_deposits_value->amount_fine); ?></td>
                                                                                <td class="text text-right"><?php echo amountFormat($fee_deposits_value->amount); ?></td>
                                                                                <td></td>

                                                                            </tr>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>

                                                            <?php
                                                            }
                                                        }

                                                            ?>
                                                            <?php
                                                            if (!empty($student_discount_fee)) {

                                                                foreach ($student_discount_fee as $discount_key => $discount_value) {
                                                            ?>
                                                                    <tr class="dark-light">
                                                                        <td align="left"> <?php echo $this->lang->line('discount'); ?> </td>
                                                                        <td align="left">
                                                                            <?php echo $discount_value['code']; ?>
                                                                        </td>
                                                                        <td align="left"></td>
                                                                        <td align="left" class="text text-left">
                                                                            <?php
                                                                            if ($discount_value['status'] == "applied") {
                                                                            ?>

                                                                                <a href="#" data-toggle="popover" class="detail_popover">

                                                                                    <?php echo $this->lang->line('discount_of') . " " . (($discount_value['type'] == "fix") ?  $currency_symbol . amountFormat($discount_value['amount']) : $discount_value['percentage'] . "%")  . " " . $this->lang->line($discount_value['status']) . " : " . $discount_value['payment_id']; ?>
                                                                                </a>
                                                                                <div class="fee_detail_popover" style="display: none">
                                                                                    <?php
                                                                                    if ($fee_deposits_value->description == "") {
                                                                                    ?>
                                                                                        <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <p class="text text-danger"><?php echo $discount_value['student_fees_discount_description'] ?></p>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            <?php
                                                                            } else {
                                                                                echo '<p class="text text-danger">' . $this->lang->line('discount_of') . " " . (($discount_value['type'] == "fix") ?  $currency_symbol . amountFormat($discount_value['amount']) : $discount_value['percentage'] . "%") . " " . $this->lang->line($discount_value['status']);
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td></td>
                                                                        <td class="text text-left"></td>
                                                                        <td class="text text-left"></td>
                                                                        <td class="text text-left"></td>
                                                                        <td class="text text-right">
                                                                            <?php
                                                                            $alot_fee_discount = $alot_fee_discount;
                                                                            ?>
                                                                        </td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                    </tr>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                            <tr class="box box-solid total-bg">
                                                                <td></td>
                                                                <td></td>
                                                                <td class="text text-right" colspan="2"><?php echo $this->lang->line('grand_total'); ?></td>
                                                                <td class="text text-right">
                                                                    <?php
                                                                    echo $currency_symbol . amountFormat($total_amount) . "<span class='text text-danger'>+" . amountFormat($total_fees_fine_amount) . "</span>";
                                                                    ?></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td class="text text-right"><?php
                                                                                            echo ($currency_symbol . amountFormat($total_discount_amount + $alot_fee_discount));
                                                                                            ?></td>
                                                                <td class="text text-right"><?php
                                                                                            echo ($currency_symbol . amountFormat($total_fine_amount));
                                                                                            ?></td>
                                                                <td class="text text-right"><?php
                                                                                            echo ($currency_symbol . amountFormat($total_deposite_amount));
                                                                                            ?></td>
                                                                <td class="text text-right"><?php
                                                                                            echo ($currency_symbol . amountFormat($total_balance_amount - $alot_fee_discount));
                                                                                            ?></td>
                                                            </tr>
                                                        <?php
                                                    }
                                                        ?>
                                                </tbody>
                                        </table>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        <?php } ?>


                        <div class="tab-pane" id="timeline">
                            <div class="relative z-index-1">
                                <?php if ($student_timeline == 'enabled') { ?>
                                    <button type="button" id="myTimelineButton" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add') ?></button>
                                <?php } ?>
                            </div>

                            <div class="timeline-header no-border">
                                <div id="timeline_list">
                                    <?php if (empty($timeline_list)) { ?>
                                        <br /><br>
                                        <div class="alert alert-info"><?php echo $this->lang->line("no_record_found") ?></div>
                                    <?php } else { ?>
                                        <ul class="timeline timeline-inverse">
                                            <?php foreach ($timeline_list as $key => $value) { ?>
                                                <li class="time-label">
                                                    <span class="bg-blue">
                                                        <?php if ($value['timeline_date'] != '0000-00-00') {
                                                            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['timeline_date']));
                                                        } ?>

                                                    </span>
                                                </li>
                                                <li>
                                                    <i class="fa fa-list-alt bg-blue"></i>
                                                    <div class="timeline-item">

                                                        <?php if ($value["created_student_id"] == $student_id) {
                                                            if ($student_timeline == 'enabled') { ?>
                                                                <span class="time"><a class="defaults-c text-right" data-toggle="tooltip" onclick="delete_timeline('<?php echo $value['id']; ?>')" data-original-title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash"></i></a> </span>
                                                                <span class="time"><a data-toggle="tooltip" class="pull-right edit_timeline defaults-c text-right" data-id="<?php echo $value["id"]; ?>" data-original-title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a></span>
                                                        <?php }
                                                        } ?>

                                                        <?php if (!empty($value["document"])) { ?>
                                                            <span class="time"><a class="defaults-c text-right" style="color:#0084B4" data-toggle="tooltip" href="<?php echo base_url() . "user/timeline/download/" . $value["id"] ?>" data-original-title="<?php echo $this->lang->line('download'); ?>"><i class="fa fa-download"></i></a></span>
                                                        <?php } ?>
                                                        <h3 class="timeline-header text-aqua text-break"> <?php echo $value['title']; ?> </h3>
                                                        <div class="timeline-body text-break">
                                                            <?php echo $value['description']; ?>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                            <li><i class="fa fa-clock-o bg-blue"></i></li>
                                        <?php } ?>
                                        </ul>
                                </div>
                            </div>
                        </div>

                        <?php

                        if (!$sch_setting->attendence_type) {
                        ?>
                            <div class="tab-pane" id="attendance">
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 col-sm-6 col20per">
                                        <div class="staffprofile">
                                            <h5><?php echo $this->lang->line('total_present'); ?></h5>
                                            <h4><?php
                                                if (!empty($countAttendance[1])) {
                                                    echo $countAttendance[1];
                                                } else {
                                                    echo "0";
                                                }
                                                ?></h4>
                                            <div class="icon">
                                                <i class="fa fa-check-square-o"></i>
                                            </div>
                                        </div>
                                    </div><!--./col-md-3-->
                                    <div class="col-lg-3 col-md-4 col-sm-6 col20per">
                                        <div class="staffprofile">

                                            <h5><?php echo $this->lang->line('total_late'); ?></h5>
                                            <h4><?php
                                                if (!empty($countAttendance[3])) {
                                                    echo $countAttendance[3];
                                                } else {
                                                    echo "0";
                                                }
                                                ?></h4>
                                            <div class="icon">
                                                <i class="fa  fa-check-square-o"></i>
                                            </div>
                                        </div>
                                    </div><!--./col-md-3-->
                                    <div class="col-lg-3 col-md-4 col-sm-6 col20per">
                                        <div class="staffprofile">
                                            <h5><?php echo $this->lang->line('total_absent'); ?></h5>
                                            <h4><?php
                                                if (!empty($countAttendance[4])) {
                                                    echo $countAttendance[4];
                                                } else {
                                                    echo "0";
                                                }
                                                ?></h4>
                                            <div class="icon">
                                                <i class="fa  fa-check-square-o"></i>
                                            </div>
                                        </div>
                                    </div><!--./col-md-3-->
                                    <div class="col-lg-3 col-md-4 col-sm-6 col20per">
                                        <div class="staffprofile">
                                            <h5><?php echo $this->lang->line('total_half_day'); ?></h5>
                                            <h4><?php
                                                if (!empty($countAttendance[6])) {
                                                    echo $countAttendance[6];
                                                } else {
                                                    echo "0";
                                                }
                                                ?></h4>
                                            <div class="icon">
                                                <i class="fa  fa-check-square-o"></i>
                                            </div>
                                        </div>
                                    </div><!--./col-md-3-->
                                    <div class="col-lg-3 col-md-4 col-sm-6 col20per">
                                        <div class="staffprofile">
                                            <h5><?php echo $this->lang->line('total_holiday'); ?></h5>
                                            <h4><?php
                                                if (!empty($countAttendance[5])) {
                                                    echo $countAttendance[5];
                                                } else {
                                                    echo "0";
                                                }
                                                ?></h4>
                                            <div class="icon">
                                                <i class="fa  fa-check-square-o"></i>
                                            </div>
                                        </div>
                                    </div><!--./col-md-3-->
                                </div>
                                <div class="row">

                                    <div class="col-md-12 col-sm-12">
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
                                <div>
                                    <div class="download_label"><?php echo $this->lang->line('attendance'); ?> of <?php echo $student["firstname"] . " " . $student["lastname"] . ' (' . $student["admission_no"] . ')'; ?></div>
                                    <div id="ajaxattendance" class="table-responsive">

                                        <table class="table table-bordered table-hover example">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <?php

                                                        ?>
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
                                                $j = 1;
                                                for ($i = 1; $i <= 31; $i++) {


                                                ?>
                                                    <tr>
                                                        <td><?php echo $i; ?></td>
                                                        <?php
                                                        $st = $start_year;
                                                        foreach ($monthlist as $monthkey => $monthvalue) {

                                                            $datemonth       = $monthkey;
                                                            if ($datemonth < $sch_setting->start_month) {
                                                                $st = $Next_year;
                                                            }

                                                        ?>
                                                            <td>
                                                                <?php

                                                                $date_att = $st . "-" . $monthkey . "-" . sprintf("%02d", $i);



                                                                if (array_key_exists($date_att, $resultlist) && !empty($resultlist[$date_att])) {

                                                                    echo ($resultlist[$date_att]['key']);
                                                                }



                                                                ?>


                                                            </td>
                                                        <?php

                                                        }
                                                        ?>
                                                        <td>




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

                        <?php
                        }
                        ?>


                        <div class="tab-pane" id="documents">
                            <!-- <div class="download_label"><?php echo "Uploaded Documents" ?></div> -->
                            <div class="timeline-header no-border">
                                <?php
                                if (!empty($student_doc)) {
                                ?>
                                    <button type="button" data-student-session-id="<?php echo $student['student_session_id'] ?>" class="btn btn-xs btn-primary pull-right myTransportFeeBtn"> <i class="fa fa-upload"></i> <?php echo $this->lang->line('upload_documents'); ?></button>
                                    <!-- <button type="button"  data-student-session-id="<?php echo $student['student_session_id'] ?>" class="btn btn-xs btn-primary pull-right myTransportFeeBtn mb10"> <i class="fa fa-upload"></i>  <?php echo $this->lang->line('upload_documents'); ?></button> -->
                                <?php } ?>
                                <div class="table-responsive" style="clear: both;">
                                    <?php
                                    if (!empty($student_doc)) {
                                    ?>
                                        <table class="table table-striped table-bordered ">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <?php echo $this->lang->line('title'); ?>
                                                    </th>
                                                    <th>
                                                        <?php echo $this->lang->line('file_name'); ?>
                                                    </th>
                                                    <th class="mailbox-date text-right">
                                                        <?php echo $this->lang->line('action'); ?>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($student_doc as $value) {
                                                ?>
                                                    <tr>
                                                        <td><?php echo $value['title']; ?></td>
                                                        <td><?php echo $value['doc']; ?></td>
                                                        <td class="mailbox-date text-right">
                                                            <a href="<?php echo base_url(); ?>user/user/download/<?php echo $value['student_id'] . "/" . $value['id']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                                <i class="fa fa-download"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        <div class="alert alert-danger">
                                            <?php echo $this->lang->line('no_record_found'); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <!------- Behaviour Report Start-------->
                        <?php
                        if ($this->module_lib->hasModule('behaviour_records') && $this->studentmodule_lib->hasActive('behaviour_records')) {  ?>
                            <div class="tab-pane" id="incident">
                                <div class="timeline-header no-border">
                                    <div class="mailbox-messages table-responsive overflow-visible-lg">
                                        <div class="download_label"><?php echo $this->lang->line('student_behaviour'); ?></div>
                                        <table class="table table-striped table-bordered table-hover example">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $this->lang->line('title'); ?>
                                                    </th>
                                                    <th><?php echo $this->lang->line('point'); ?>
                                                    </th>
                                                    <th><?php echo $this->lang->line('date'); ?></th>
                                                    <th><?php echo $this->lang->line('description'); ?></th>
                                                    <th><?php echo $this->lang->line('assign_by'); ?></th>
                                                    <th style="width: 70px;" class="noExport"><?php echo $this->lang->line('action'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($assignstudent)) {
                                                ?>
                                                    <td colspan="6">
                                                        <div class="alert alert-danger"><?php echo $this->lang->line('no_record_found'); ?></div>
                                                    </td>
                                                    <?php
                                                } else {

                                                    foreach ($assignstudent as $assignstudent_value) {
                                                        $staff_id = '';
                                                        if ($assignstudent_value['staff_employee_id'] != "") {
                                                            $staff_id = ' (' . $assignstudent_value['staff_employee_id'] . ')';
                                                        }

                                                        $pointclass = '';
                                                        if ($assignstudent_value['point'] < 0) {
                                                            $pointclass = 'danger';
                                                        }
                                                    ?>
                                                        <tr class="<?php echo $pointclass; ?>">
                                                            <td class="mailbox-name"> <?php echo $assignstudent_value['title'] ?></td>
                                                            <td class="mailbox-name"> <?php echo $assignstudent_value['point'] ?></td>
                                                            <td class="mailbox-name"> <?php echo $this->customlib->dateformat($assignstudent_value['created_at']) ?></td>
                                                            <td class="mailbox-name" style="width: 380px;"> <?php echo $assignstudent_value['description'] ?></td>
                                                            <td class="mailbox-name">
                                                                <?php
                                                                if ($superadmin_restriction == 'disabled' && $assignstudent_value['role_id'] == 7) {
                                                                    echo  '';
                                                                } else {
                                                                    echo $assignstudent_value['staff_name'] . ' ' . $assignstudent_value['staff_surname'] . $staff_id;
                                                                }
                                                                ?>
                                                            </td>
                                                            <td class="mailbox-name">
                                                                <a class="btn btn-default btn-xs comments relative overflow-visible" data-toggle="tooltip" data-placement="left" data-original-title="<?php echo $this->lang->line('comment'); ?>" data-record-id="<?php echo $assignstudent_value['id'] ?>">
                                                                    <?php if ($assignstudent_value['totalcomments']['totalcomments'] != '0') { ?>
                                                                        <span class="comment-badges"><?php echo $assignstudent_value['totalcomments']['totalcomments']; ?></span><?php } ?>
                                                                    <i class="fa fa-comment"></i>

                                                                </a>
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
                            </div>
                        <?php
                        }
                        ?>
                        <!------- Behaviour Report End--------->
                        
                        
                        <!------- CBSE Exam Start-------->
                        <?php
                        if ($this->module_lib->hasModule('cbseexam') && $this->studentmodule_lib->hasActive('cbseexam')) {  ?>
                            <div class="tab-pane" id="cbseexam">
                                <div class="dt-buttons btn-group pull-right">
                                    <a class="btn btn-default btn-xs dt-button no_print" title="<?php echo $this->lang->line('print'); ?>" id="print" onclick="printDiv('cbseexam')"><i class="fa fa-print"></i></a>
                                </div>              
                               
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                
                                                <?php
                                                if (!empty($exams)) {
                                                    foreach ($exams as $exam_key => $exam_value) {
                
                                                        $total_marks = 0;
                                                        $total_max_marks = 0;
                                                ?>
                
                                                        <div class="shadow-sm mb30">
                                                            <h3 class="pagetitleh2 mt10 border-b-none pl-5"><?php echo  $exam_value->name; ?></h3>
                
                                                            <div class="table-responsive">
                
                                                                <?php
                                                                if (!empty($exam_value->subjects)) {
                                                                ?>
                                                                    <table class="table table-bordered table-b mb0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="bolds">
                                                                                    <?php echo $this->lang->line('subject') ;?>
                                                                                </td>
                                                                                <?php
                
                                                                                foreach ($exam_value->exam_assessments as $exam_assessment_key => $exam_assessment_value) {
                                                                                ?>
                                                                                    <td class="text-center bolds">
                                                                                    <?php $assessment_code= ($exam_assessment_value->code == "") ? "": " (" . $exam_assessment_value->code . ")"; ?>
                                                                        <?php echo $exam_assessment_value->name . $assessment_code; ?>
                                                                                        <br />
                                                                                        (<?php echo $this->lang->line('max'); ?> <?php echo $exam_assessment_value->maximum_marks; ?>)
                                                                                    </td>
                                                                                <?php
                                                                                }
                
                                                                                ?>
                                                                                <td class="bolds">
                                                                                    <?php echo $this->lang->line('total') ;?>
                                                                                </td>
                                                                            </tr>
                
                
                                                                            <?php
                                                                            foreach ($exam_value->subjects as $subject_key => $subject_value) {
                                                                                $subject_total = 0;
                                                                            ?>
                                                                                <tr>
                
                                                                                    <td>

                                                                            <?php $subject_code= ($subject_value->subject_code == "") ? "": " (" . $subject_value->subject_code . ")"; ?>


                                                                                 <?php echo $subject_value->subject_name . $subject_code; ?>

                                                                                    </td>
                                                                                    <?php
                                                                                    foreach ($exam_value->exam_assessments as $exam_assessment_key => $exam_assessment_value) {
                                                                                        
                                                                                    ?>
                                                                                        <td class="text-center">
                                                                                            <?php
                                                                                    
                                                                                            
                                                                                        $assessment_exists=  find_subject_assessment_exists($exam_value->exam_subject_assessments, $subject_value->id, $exam_assessment_value->id);
                
                                                                                        if($assessment_exists){
                                                                                            $assessment_array = findAssessmentValue($subject_value->subject_id, $exam_assessment_value->id, $exam_value);
                                                                                            echo ($assessment_array['is_absent']) ? $this->lang->line('abs') : $assessment_array['marks'];
                                                                                            if ($assessment_array['marks'] == "N/A") {
                                                                                                $assessment_array['marks'] = 0;
                                                                                            }
                                                                                            $subject_total += $assessment_array['marks'];
                                                                                            $total_max_marks += $assessment_array['maximum_marks'];
                                                                                            $total_marks += $assessment_array['marks'];
                                                                                        }else{
                                                                                            echo "<b>xx</b>";
                                                                                        }
                
                                                                            
                                                                                            ?>
                                                                                        </td>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                    <td class="bolds">
                                                                                        <?php echo  two_digit_float($subject_total); ?>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php
                                                                            }
                                                                            ?>
                
                
                                                                        </tbody>
                
                
                                                                    </table>
                                                                <?php
                
                                                                }
                                                                if($total_max_marks > 0){
                                                                $exam_percentage = getPercent($total_max_marks, $total_marks);
                                                                }else{
                                                                $exam_percentage =0;   
                                                                }
                                                                ?>            
                                                            
                                                            <table class="table table-bordered table-b mb0 bg-gray-light">
                                                                <tr>
                                                                    <td class="bolds"><?php echo $this->lang->line('total_marks'); ?> : <?php echo $total_marks . "/" . $total_max_marks; ?></td>
                                                                    <td class="bolds"><?php echo $this->lang->line('percentage'); ?> (%) : <?php echo $exam_percentage; ?></td>
                                                                    <td class="bolds"><?php echo $this->lang->line('grade'); ?> : <?php echo getGrade($exam_value->grades, $exam_percentage); ?></td>
                                                                    <td class="bolds"><?php echo $this->lang->line('rank'); ?> : <?php echo $exam_value->rank; ?></td>
                                                                </tr>
                                                            </table>
                                                        </div>  
                
                                                            
                                                        </div>
                
                                                    <?php
                
                
                                                    }
                                                } else {
                                                    ?>
                                                    <div class="alert alert-info">
                                                        <?php echo $this->lang->line('no_exam_assigned'); ?>
                                                    </div>
                                                <?php
                                                }
                
                                                ?>
                
                                            </div>
                
                                        </div>
                                    
                            </div>
                        <?php
                        }
                        ?>
                        <!------- CBSE Exam End--------->
                        

                        <div class="tab-pane" id="exam">

                            <?php
                            if (empty($exam_result)) {
                            ?>
                                <div class="alert alert-danger">
                                    <?php echo $this->lang->line('no_record_found'); ?>
                                </div>
                            <?php
                            }
                            if (!empty($exam_result)) {
                            ?>
                                <div class="dt-buttons btn-group pull-right miusDM40">
                                    <a class="btn btn-default btn-xs dt-button no_print" id="print" title="<?php echo $this->lang->line('print'); ?>" onclick="printDiv('exam')"><i class="fa fa-print"></i></a>
                                </div>
                                <?php
                                foreach ($exam_result as $exam_key => $exam_value) {
                                ?>
                                    <div class="tshadow mb25">
                                        <h4 class="pagetitleh">
                                            <?php
                                            echo $exam_value->exam;
                                            ?>
                                        </h4>
                                        <?php
                                        if (!empty($exam_value->exam_result)) {
                                            if ($exam_value->exam_result['exam_connection'] == 0) {
                                                if (!empty($exam_value->exam_result['result'])) {
                                                    $exam_quality_points = 0;
                                                    $exam_total_points   = 0;
                                                    $exam_credit_hour    = 0;
                                                    $exam_grand_total    = 0;
                                                    $exam_get_total      = 0;
                                                    $exam_pass_status    = 1;
                                                    $exam_absent_status  = 0;
                                                    $total_exams         = 0;
                                        ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-striped ptt10" id="headerTable">
                                                            <thead>
                                                                <th><?php echo $this->lang->line('subject'); ?></th>
                                                                <?php
                                                                if ($exam_value->exam_type == "gpa") {
                                                                ?>
                                                                    <th><?php echo $this->lang->line('grade_point'); ?></th>
                                                                    <th><?php echo $this->lang->line('credit_hours'); ?></th>
                                                                    <th><?php echo $this->lang->line('quality_points'); ?></th>
                                                                <?php
                                                                }
                                                                ?>
                                                                <?php
                                                                if ($exam_value->exam_type != "gpa") {
                                                                ?>
                                                                    <th><?php echo $this->lang->line('max_marks'); ?></th>
                                                                    <?php
                                                                    if ($exam_value->exam_type != "average_passing") {

                                                                    ?>
                                                                        <th><?php echo $this->lang->line('min_marks'); ?></th>
                                                                    <?php
                                                                    }
                                                                    ?>

                                                                    <th><?php echo $this->lang->line('marks_obtained'); ?></th>
                                                                <?php
                                                                }
                                                                ?>
                                                                <?php
                                                                if ($exam_value->exam_type == "coll_grade_system" || $exam_value->exam_type == "school_grade_system") {
                                                                ?>
                                                                    <th><?php echo $this->lang->line('grade'); ?> </th>
                                                                <?php
                                                                }

                                                                if ($exam_value->exam_type == "basic_system") {
                                                                ?>
                                                                    <th>
                                                                        <?php echo $this->lang->line('result'); ?>
                                                                    </th>
                                                                <?php
                                                                }
                                                                ?>
                                                                <th><?php echo $this->lang->line('note'); ?></th>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                if (!empty($exam_value->exam_result['result'])) {
                                                                    $total_exams = 1;
                                                                    foreach ($exam_value->exam_result['result'] as $exam_result_key => $exam_result_value) {
                                                                        $exam_grand_total = $exam_grand_total + $exam_result_value->max_marks;
                                                                        $exam_get_total   = $exam_get_total + $exam_result_value->get_marks;
                                                                        $percentage_grade = ($exam_result_value->get_marks * 100) / $exam_result_value->max_marks;

                                                                        if ($exam_result_value->get_marks < $exam_result_value->min_marks) {
                                                                            $exam_pass_status = 0;
                                                                        }
                                                                ?>
                                                                        <tr>
                                                                            <td><?php echo ($exam_result_value->name); ?> <?php if ($exam_result_value->code) {
                                                                                                                                echo ' (' . $exam_result_value->code . ')';
                                                                                                                            } ?></td>
                                                                            <?php
                                                                            if ($exam_value->exam_type != "gpa") {
                                                                            ?>
                                                                                <td><?php echo ($exam_result_value->max_marks); ?></td>
                                                                                <?php
                                                                                if ($exam_value->exam_type != "average_passing") {
                                                                                ?>

                                                                                    <td><?php echo ($exam_result_value->min_marks); ?></td>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                                <td>
                                                                                    <?php
                                                                                    echo $exam_result_value->get_marks;

                                                                                    if ($exam_result_value->attendence == "absent") {
                                                                                        $exam_absent_status = 1;
                                                                                        echo "&nbsp;" . $this->lang->line('abs');
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                            <?php
                                                                            } elseif ($exam_value->exam_type == "gpa") {
                                                                            ?>
                                                                                <td>
                                                                                    <?php

                                                                                    $percentage_grade  = ($exam_result_value->get_marks * 100) / $exam_result_value->max_marks;
                                                                                    $point             = findGradePoints($exam_grade, $exam_value->exam_type, $percentage_grade);
                                                                                    $exam_total_points = $exam_total_points + $point;
                                                                                    echo two_digit_float($point);
                                                                                    ?>
                                                                                </td>
                                                                                <td> <?php
                                                                                        echo $exam_result_value->credit_hours;
                                                                                        $exam_credit_hour = $exam_credit_hour + $exam_result_value->credit_hours;
                                                                                        ?></td>
                                                                                <td><?php
                                                                                    echo two_digit_float($exam_result_value->credit_hours * $point);
                                                                                    $exam_quality_points = $exam_quality_points + ($exam_result_value->credit_hours * $point);
                                                                                    ?></td>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                            <?php
                                                                            if ($exam_value->exam_type == "coll_grade_system" || $exam_value->exam_type == "school_grade_system") {
                                                                            ?>
                                                                                <td><?php echo findExamGrade($exam_grade, $exam_value->exam_type, $percentage_grade); ?></td>
                                                                            <?php
                                                                            }
                                                                            if ($exam_value->exam_type == "basic_system") {
                                                                            ?>
                                                                                <td>
                                                                                    <?php
                                                                                    if ($exam_result_value->get_marks < $exam_result_value->min_marks) {
                                                                                    ?>
                                                                                        <label class="label label-danger"><?php echo $this->lang->line('fail') ?></label>
                                                                                    <?php
                                                                                    } else {
                                                                                    ?>
                                                                                        <label class="label label-success"><?php echo $this->lang->line('pass') ?></label>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                            <td><?php echo ($exam_result_value->note); ?></td>
                                                                        </tr>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <?php ?>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="bgtgray">
                                                                <?php
                                                                if ($exam_value->exam_type != "gpa") {
                                                                ?>
                                                                    <div class="col-sm-3 col-lg-2 col-md-3">
                                                                        <div class="description-block">
                                                                            <h5 class="description-header"><?php echo $this->lang->line('percentage') ?> : <span class="description-text"><?php

                                                                                                                                                                                            $exam_percentage = ($exam_get_total * 100) / $exam_grand_total;
                                                                                                                                                                                            echo two_digit_float($exam_percentage);
                                                                                                                                                                                            ?></span> </h5>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-3 pull ">
                                                                        <div class="description-block">
                                                                            <h5 class="description-header"><?php echo $this->lang->line('rank') ?> : <span class="description-text">
                                                                                    <?php echo $exam_value->rank; ?>

                                                                                </span>
                                                                            </h5>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-sm-4 col-lg-3 col-md-4 border-right">
                                                                        <div class="description-block">
                                                                            <h5 class="description-header"><?php echo $this->lang->line('result') ?> :<span class="description-text">
                                                                                    <?php
                                                                                    if ($total_exams) {
                                                                                        if ($exam_value->exam_type == "average_passing") {
                                                                                            if ($exam_value->passing_percentage <= $exam_percentage) {

                                                                                    ?>
                                                                                                <span class='label bg-green ml-rtl' style="margin-right: 5px;">
                                                                                                    <?php
                                                                                                    echo $this->lang->line('pass');
                                                                                                    ?>
                                                                                                </span> <?php
                                                                                                    } else {
                                                                                                        ?>
                                                                                                <span class='label label-danger'>
                                                                                                    <?php
                                                                                                        echo $this->lang->line('fail');
                                                                                                    ?>
                                                                                                </span>
                                                                                </span><?php
                                                                                                    }

                                                                                                    echo $this->lang->line('division') . " : " . findExamDivision($marks_division, $exam_percentage);
                                                                                                } else {
                                                                                                    if ($exam_absent_status) {
                                                                                        ?>
                                                                                <span class='label label-danger'>
                                                                                    <?php
                                                                                                        echo $this->lang->line('fail');
                                                                                    ?>
                                                                                </span>
                                                                                <?php
                                                                                                    } else {

                                                                                                        if ($exam_pass_status) {
                                                                                ?>
                                                                                    <span class='label bg-green ml-rtl' style="margin-right: 5px;">
                                                                                        <?php
                                                                                                            echo $this->lang->line('pass');
                                                                                        ?>
                                                                                    </span> <?php
                                                                                                        } else {
                                                                                            ?>
                                                                                    <span class='label label-danger'>
                                                                                        <?php
                                                                                                            echo $this->lang->line('fail');
                                                                                        ?>
                                                                                    </span>
                                                                                    </span>
                                                                    <?php
                                                                                                        }

                                                                                                        if ($exam_pass_status) {

                                                                                                            echo $this->lang->line('division') . " : " . findExamDivision($marks_division, $exam_percentage);
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                    ?>
                                                                            </h5>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-2 col-lg-2 col-md-2 border-right">
                                                                        <div class="description-block">
                                                                            <h5 class="description-header"><?php echo $this->lang->line('grand_total'); ?> : <span class="description-text"><?php echo $exam_grand_total; ?></span></h5>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-3 col-lg-3 col-md-3 border-right">
                                                                        <div class="description-block">
                                                                            <h5 class="description-header"><?php echo $this->lang->line('total_obtain_marks'); ?> : <span class="description-text"><?php echo $exam_get_total; ?></span></h5>
                                                                        </div>
                                                                    </div>
                                                                <?php
                                                                } elseif ($exam_value->exam_type == "gpa") {
                                                                ?>

                                                                    <div class="col-lg-3 col-md-3">
                                                                        <div class="description-block">
                                                                            <h5 class="description-header"><?php echo $this->lang->line('credit_hours'); ?> : <span class="description-text"><?php echo $exam_credit_hour; ?></span></h5>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 pull ">
                                                                        <div class="description-block">
                                                                            <h5 class="description-header"><?php echo $this->lang->line('rank') ?> : <span class="description-text">
                                                                                    <?php echo $exam_value->rank; ?>

                                                                                </span>
                                                                            </h5>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-5 col-md-3">
                                                                        <div class="description-block">
                                                                            <h5 class="description-header"><?php echo $this->lang->line('quality_points'); ?> : <span class="description-text">
                                                                                    <?php
                                                                                    if ($exam_credit_hour <= 0) {
                                                                                        echo "--";
                                                                                    } else {
                                                                                        $exam_grade_percentage = ($exam_get_total * 100) / $exam_grand_total;
                                                                                        echo $exam_quality_points . "/" . $exam_credit_hour . '=' . two_digit_float($exam_quality_points / $exam_credit_hour) . " [" . findExamGrade($exam_grade, $exam_value->exam_type, $exam_grade_percentage) . "]";
                                                                                    }

                                                                                    ?>
                                                                                </span>

                                                                                <?php
                                                                                ?>
                                                                            </h5>
                                                                        </div>
                                                                    </div>
                                                                <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                    </div>
                                <?php
                                                }
                                            } elseif ($exam_value->exam_result['exam_connection'] == 1) {

                                                $exam_connected_exam = ($exam_value->exam_result['exam_result']['exam_result_' . $exam_value->exam_group_class_batch_exam_id]);

                                                if (!empty($exam_connected_exam)) {
                                                    $exam_quality_points = 0;
                                                    $exam_total_points   = 0;
                                                    $exam_credit_hour    = 0;
                                                    $exam_grand_total    = 0;
                                                    $exam_get_total      = 0;
                                                    $exam_pass_status    = 1;
                                                    $exam_absent_status  = 0;
                                                    $total_exams         = 0;
                                ?>
                                    <table class="table table-striped ">
                                        <thead>
                                            <th><?php echo $this->lang->line('subject') ?></th>
                                            <?php
                                                    if ($exam_value->exam_type == "gpa") {
                                            ?>
                                                <th><?php echo $this->lang->line('grade_point'); ?> </th>
                                                <th><?php echo $this->lang->line('credit_hours'); ?></th>
                                                <th><?php echo $this->lang->line('quality_points'); ?></th>
                                            <?php
                                                    }
                                            ?>
                                            <?php
                                                    if ($exam_value->exam_type != "gpa") {
                                            ?>
                                                <th><?php echo $this->lang->line('max_marks'); ?></th>
                                                <?php
                                                        if ($exam_value->exam_type != "average_passing") {
                                                ?>

                                                    <th><?php echo $this->lang->line('min_marks') ?></th>
                                                <?php
                                                        }
                                                ?>
                                                <th><?php echo $this->lang->line('marks_obtained') ?> </th>
                                            <?php
                                                    }
                                            ?>
                                            <?php
                                                    if ($exam_value->exam_type == "coll_grade_system" || $exam_value->exam_type == "school_grade_system") {
                                            ?>
                                                <th><?php echo $this->lang->line('grade'); ?></th>
                                            <?php
                                                    }

                                                    if ($exam_value->exam_type == "basic_system") {
                                            ?>
                                                <th>
                                                    <?php echo $this->lang->line('result'); ?>
                                                </th>
                                            <?php
                                                    }
                                            ?>
                                            <th><?php echo $this->lang->line('note') ?></th>
                                        </thead>
                                        <tbody>
                                            <?php
                                                    if (!empty($exam_connected_exam)) {
                                                        $total_exams = 1;
                                                        foreach ($exam_connected_exam as $exam_result_key => $exam_result_value) {
                                                            $exam_grand_total = $exam_grand_total + $exam_result_value->max_marks;
                                                            $exam_get_total   = $exam_get_total + $exam_result_value->get_marks;
                                                            $percentage_grade = ($exam_result_value->get_marks * 100) / $exam_result_value->max_marks;
                                                            if ($exam_result_value->get_marks < $exam_result_value->min_marks) {
                                                                $exam_pass_status = 0;
                                                            }
                                            ?>
                                                    <tr>
                                                        <td><?php echo ($exam_result_value->name); ?> <?php if ($exam_result_value->code) {
                                                                                                            echo ' (' . $exam_result_value->code . ')';
                                                                                                        } ?></td>
                                                        <?php
                                                            if ($exam_value->exam_type != "gpa") {
                                                        ?>
                                                            <td><?php echo ($exam_result_value->max_marks); ?></td>
                                                            <?php

                                                                if ($exam_value->exam_type != "average_passing") {
                                                            ?>
                                                                <td><?php echo ($exam_result_value->min_marks); ?></td>
                                                            <?php
                                                                }
                                                            ?>

                                                            <td>
                                                                <?php
                                                                echo $exam_result_value->get_marks;

                                                                if ($exam_result_value->attendence == "absent") {
                                                                    $exam_absent_status = 1;
                                                                    echo "&nbsp; " . $this->lang->line('abs');
                                                                }
                                                                ?>
                                                            </td>
                                                        <?php
                                                            } elseif ($exam_value->exam_type == "gpa") {
                                                        ?>
                                                            <td style="">
                                                                <?php
                                                                $percentage_grade  = ($exam_result_value->get_marks * 100) / $exam_result_value->max_marks;
                                                                $point             = findGradePoints($exam_grade, $exam_value->exam_type, $percentage_grade);
                                                                $exam_total_points = $exam_total_points + $point;
                                                                echo two_digit_float($point);
                                                                ?>
                                                            </td>
                                                            <td> <?php
                                                                    echo $exam_result_value->credit_hours;
                                                                    $exam_credit_hour = $exam_credit_hour + $exam_result_value->credit_hours;
                                                                    ?></td>
                                                            <td><?php
                                                                echo two_digit_float($exam_result_value->credit_hours * $point);
                                                                $exam_quality_points = $exam_quality_points + ($exam_result_value->credit_hours * $point);
                                                                ?></td>
                                                        <?php
                                                            }
                                                        ?>
                                                        <?php
                                                            if ($exam_value->exam_type == "coll_grade_system" || $exam_value->exam_type == "school_grade_system") {
                                                        ?>
                                                            <td><?php echo findExamGrade($exam_grade, $exam_value->exam_type, $percentage_grade); ?></td>
                                                        <?php
                                                            }
                                                            if ($exam_value->exam_type == "basic_system") {
                                                        ?>
                                                            <td>
                                                                <?php
                                                                if ($exam_result_value->get_marks < $exam_result_value->min_marks) {
                                                                ?>
                                                                    <label class="label label-danger">
                                                                        <?php echo $this->lang->line('fail') ?>
                                                                        <label>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                            <label class="label label-success"><?php echo $this->lang->line('pass') ?>
                                                                                <label>
                                                                                <?php
                                                                            }
                                                                                ?>
                                                            </td>
                                                        <?php
                                                            }
                                                        ?>
                                                        <td><?php echo ($exam_result_value->note); ?></td>
                                                    </tr>
                                            <?php
                                                        }
                                                    }
                                            ?>
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="bgtgray">
                                                <?php
                                                    if ($exam_value->exam_type != "gpa") {
                                                ?>
                                                    <div class="col-sm-3 col-lg-2 col-md-3 pull no-print">
                                                        <div class="description-block">
                                                            <h5 class="description-header"> <?php echo $this->lang->line('percentage') ?> : <span class="description-text">
                                                                    <?php
                                                                    $exam_percentage = ($exam_get_total * 100) / $exam_grand_total;
                                                                    echo two_digit_float($exam_percentage);
                                                                    ?>
                                                                </span> </h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2 col-md-3 pull ">
                                                        <div class="description-block">
                                                            <h5 class="description-header"><?php echo $this->lang->line('rank') ?> : <span class="description-text">
                                                                    <?php echo $exam_value->rank; ?>

                                                                </span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 col-lg-3 col-md-3 pull border-right no-print">
                                                        <div class="description-block">
                                                            <h5 class="description-header"><?php echo $this->lang->line('result'); ?> :<span class="description-text">
                                                                    <?php
                                                                    if ($total_exams) {

                                                                        if ($exam_value->exam_type == "average_passing") {
                                                                            if ($exam_value->passing_percentage <= $exam_percentage) {

                                                                    ?>
                                                                                <span class='label bg-green ml-rtl' style="margin-right: 5px;">
                                                                                    <?php
                                                                                    echo $this->lang->line('pass');
                                                                                    ?>
                                                                                </span> <?php
                                                                                    } else {
                                                                                        ?>
                                                                                <span class='label label-danger'>
                                                                                    <?php
                                                                                        echo $this->lang->line('fail');
                                                                                    ?>
                                                                                </span>

                                                                            <?php
                                                                                    }
                                                                                } else {
                                                                                    if ($exam_absent_status) {
                                                                            ?>
                                                                                <span class='label label-danger ml-rtl' style="margin-right: 5px;">
                                                                                    <?php
                                                                                        echo $this->lang->line('fail');
                                                                                    ?>
                                                                                </span>
                                                                                <?php
                                                                                    } else {

                                                                                        if ($exam_pass_status) {

                                                                                ?>
                                                                                    <span class='label bg-green ml-rtl' style="margin-right: 5px;">
                                                                                        <?php
                                                                                            echo $this->lang->line('pass');
                                                                                        ?>
                                                                                    </span>
                                                                                <?php
                                                                                        } else {
                                                                                ?>
                                                                                    <span class='label label-danger ml-rtl' style="margin-right: 5px;">
                                                                                        <?php
                                                                                            echo $this->lang->line('fail');
                                                                                        ?>
                                                                                    </span>
                                                                    <?php
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                    ?>
                                                                    <?php
                                                                    if ($total_exams) {

                                                                        if ($exam_pass_status) {
                                                                            echo $this->lang->line('division') . " : " . findExamDivision($marks_division, $exam_percentage);
                                                                        }
                                                                    }
                                                                    ?>
                                                                </span></h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 col-lg-2 col-md-2 border-right no-print">
                                                        <div class="description-block">
                                                            <h5 class="description-header"><?php echo $this->lang->line('grand_total'); ?> : <span class="description-text"><?php echo $exam_grand_total; ?></span></h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 border-right no-print">
                                                        <div class="description-block">
                                                            <h5 class="description-header"><?php echo $this->lang->line('total_obtain_marks'); ?> : <span class="description-text"><?php echo $exam_get_total; ?></span></h5>
                                                        </div>
                                                    </div>
                                                <?php
                                                    } elseif ($exam_value->exam_type == "gpa") {
                                                ?>
                                                    <div class="col-sm-3 col-lg-3 col-md-3 pull no-print">
                                                        <div class="description-block">
                                                            <h5 class="description-header">
                                                                <?php echo $this->lang->line('credit_hours'); ?> :
                                                                <span class="description-text"><?php echo $exam_credit_hour; ?>
                                                                </span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 pull ">
                                                        <div class="description-block">
                                                            <h5 class="description-header"><?php echo $this->lang->line('rank') ?> : <span class="description-text">
                                                                    <?php echo $exam_value->rank; ?>

                                                                </span>
                                                            </h5>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-5 col-md-3 pull no-print">
                                                        <div class="description-block">
                                                            <h5 class="description-header">
                                                                <?php echo $this->lang->line('quality_points'); ?> :<span class="description-text"><?php
                                                                                                                                                    if ($exam_credit_hour <= 0) {
                                                                                                                                                        echo "--";
                                                                                                                                                    } else {
                                                                                                                                                        $exam_grade_percentage = ($exam_get_total * 100) / $exam_grand_total;
                                                                                                                                                        echo $exam_quality_points . "/" . $exam_credit_hour . '=' . two_digit_float($exam_quality_points / $exam_credit_hour) . " [" . findExamGrade($exam_grade, $exam_value->exam_type, $exam_grade_percentage) . "]";
                                                                                                                                                    }
                                                                                                                                                    ?>
                                                                </span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                            <?php
                                                    }
                                                }
                                            ?>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                        <div class="tshadow mb25">
                            <h4 class="pagetitleh">
                                <?php echo $this->lang->line('consolidated_result'); ?>
                            </h4>
                            <?php
                                                $consolidate_exam_result            = false;
                                                $consolidate_exam_result_percentage = false;

                                                if ($exam_value->exam_type == "coll_grade_system" || $exam_value->exam_type == "school_grade_system") {
                            ?>
                                <table class="table table-striped ">
                                    <thead>
                                        <th><?php echo $this->lang->line('exam') ?></th>
                                        <?php
                                                    foreach ($exam_value->exam_result['exams'] as $each_exam_key => $each_exam_value) {
                                        ?>
                                            <th>
                                                <?php echo $each_exam_value->exam; ?>
                                            </th>
                                        <?php
                                                    }
                                        ?>
                                        <th><?php echo $this->lang->line('consolidate') ?></th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $this->lang->line('marks_obtained'); ?></td>
                                            <?php
                                                    $consolidate_get_total = 0;
                                                    $consolidate_max_total = 0;
                                                    $consolidate_get_total_percentage = 0;
                                                    if (!empty($exam_value->exam_result['exams'])) {
                                                        $consolidate_exam_result = "pass";
                                                        foreach ($exam_value->exam_result['exams'] as $each_exam_key => $each_exam_value) {
                                            ?>
                                                    <td>
                                                        <?php
                                                            $consolidate_each = getCalculatedExam($exam_value->exam_result['exam_result'], $each_exam_value->id);

                                                            $exam_get_percentage = ($consolidate_each->get_marks * 100) / $consolidate_each->max_marks;

                                                            $consolidate_get_percentage_mark = getConsolidateRatio($exam_value->exam_result['exam_connection_list'], $each_exam_value->id, $consolidate_each->get_marks, $exam_get_percentage);
                                                            if ($consolidate_each->exam_status == "fail") {
                                                                $consolidate_exam_result = "fail";
                                                            }

                                                            echo $consolidate_get_percentage_mark['exam_consolidate_marks'] . " (" . $consolidate_get_percentage_mark['exam_weightage'] . "%)";

                                                            $consolidate_get_total_percentage += ($consolidate_get_percentage_mark['exam_consolidate_percentage']);

                                                            $consolidate_get_total = $consolidate_get_total + ($consolidate_get_percentage_mark['exam_consolidate_marks']);
                                                            $consolidate_max_total = $consolidate_max_total + ($consolidate_each->max_marks);
                                                        ?>
                                                    </td>
                                            <?php
                                                        }
                                                    }
                                            ?>
                                            <td>
                                                <?php
                                                    if ($consolidate_max_total > 0) {

                                                        $consolidate_percentage_grade = ($consolidate_get_total * 100) / $consolidate_max_total;
                                                    } else {
                                                        $consolidate_percentage_grade = 0;
                                                    }

                                                    echo two_digit_float($consolidate_get_total_percentage)  . " [" . findExamGrade($exam_grade, $exam_value->exam_type, $consolidate_get_total_percentage) . "]";
                                                    $consolidate_exam_result_percentage = $consolidate_get_total_percentage;
                                                ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php
                                                } elseif ($exam_value->exam_type == "basic_system" || $exam_value->exam_type == "average_passing") {
                            ?>
                                <table class="table table-striped ">
                                    <thead>
                                        <th><?php echo $this->lang->line('exam'); ?></th>
                                        <?php
                                                    foreach ($exam_value->exam_result['exams'] as $each_exam_key => $each_exam_value) {
                                        ?>
                                            <th>
                                                <?php echo $each_exam_value->exam; ?>
                                            </th>
                                        <?php
                                                    }
                                        ?>
                                        <th><?php echo $this->lang->line('consolidate'); ?></th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $this->lang->line('marks_obtained') ?></td>
                                            <?php
                                                    $consolidate_get_total = 0;
                                                    $consolidate_get_total_percentage = 0;
                                                    $consolidate_max_total = 0;
                                                    if (!empty($exam_value->exam_result['exams'])) {
                                                        $consolidate_exam_result = "pass";
                                                        foreach ($exam_value->exam_result['exams'] as $each_exam_key => $each_exam_value) {

                                            ?>
                                                    <td>
                                                        <?php
                                                            $consolidate_each                = getCalculatedExam($exam_value->exam_result['exam_result'], $each_exam_value->id);

                                                            if ($consolidate_each->max_marks > 0) {
                                                                $exam_get_percentage = ($consolidate_each->get_marks * 100) / $consolidate_each->max_marks;
                                                            } else {
                                                                $exam_get_percentage = 0;
                                                            }

                                                            $consolidate_get_percentage_mark = getConsolidateRatio($exam_value->exam_result['exam_connection_list'], $each_exam_value->id, $consolidate_each->get_marks, $exam_get_percentage);
                                                            if ($exam_value->exam_type == "average_passing") {
                                                                if ($each_exam_value->passing_percentage > $exam_get_percentage) {
                                                                    $consolidate_exam_result = "fail";
                                                                }
                                                            } elseif ($consolidate_each->exam_status == "fail") {
                                                                $consolidate_exam_result = "fail";
                                                            }
                                                            echo $consolidate_get_percentage_mark['exam_consolidate_marks'] . " (" . $consolidate_get_percentage_mark['exam_weightage'] . "%)";
                                                            $consolidate_get_total += ($consolidate_get_percentage_mark['exam_consolidate_marks']);
                                                            $consolidate_get_total_percentage += ($consolidate_get_percentage_mark['exam_consolidate_percentage']);
                                                            $consolidate_max_total = $consolidate_max_total + ($consolidate_each->max_marks);
                                                        ?>
                                                    </td>
                                            <?php
                                                        }
                                                    }
                                            ?>
                                            <td><?php
                                                    if ($consolidate_max_total > 0) {
                                                        $consolidate_percentage_grade = ($consolidate_get_total * 100) / $consolidate_max_total;
                                                    } else {
                                                        $consolidate_percentage_grade = 0;
                                                    }

                                                    echo two_digit_float($consolidate_get_total_percentage) . " [" . findExamGrade($exam_grade, $exam_value->exam_type, $consolidate_get_total_percentage) . "]";
                                                    $consolidate_exam_result_percentage = $consolidate_get_total_percentage;
                                                ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php
                                                } elseif ($exam_value->exam_type == "gpa") {
                            ?>
                                <table class="table table-striped ">
                                    <thead>
                                        <th><?php echo $this->lang->line('exam') ?></th>
                                        <?php
                                                    foreach ($exam_value->exam_result['exams'] as $each_exam_key => $each_exam_value) {
                                        ?>
                                            <th>
                                                <?php echo $each_exam_value->exam; ?>
                                            </th>
                                        <?php
                                                    }
                                        ?>
                                        <th><?php echo $this->lang->line('consolidate'); ?></th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $this->lang->line('marks_obtained'); ?></td>
                                            <?php
                                                    $consolidate_get_total      = 0;
                                                    $consolidate_subjects_total = 0;

                                                    foreach ($exam_value->exam_result['exams'] as $each_exam_key => $each_exam_value) {

                                            ?>
                                                <td>
                                                    <?php
                                                        $consolidate_each        = getCalculatedExamGradePoints($exam_value->exam_result['exam_result'], $each_exam_value->id, $exam_grade, $exam_value->exam_type);

                                                        $consolidate_exam_result = ($consolidate_each->total_points / $consolidate_each->total_exams);
                                                        $consolidate_each->total_points . "/" . $consolidate_each->total_exams . "=" . two_digit_float($consolidate_exam_result);

                                                        $exam_get_percentage = ($consolidate_each->total_get_marks * 100) / $consolidate_each->total_max_marks;


                                                        $consolidate_get_percentage_mark = getConsolidateRatio($exam_value->exam_result['exam_connection_list'], $each_exam_value->id, $consolidate_exam_result, 100);
                                                        echo two_digit_float($consolidate_get_percentage_mark['exam_consolidate_marks']) . " (" . $consolidate_get_percentage_mark['exam_weightage'] . "%)";
                                                        $consolidate_get_total           = $consolidate_get_total + ($consolidate_get_percentage_mark['exam_consolidate_marks']);
                                                        $consolidate_subjects_total      = $consolidate_subjects_total + $consolidate_each->total_exams;
                                                    ?>
                                                </td>
                                            <?php
                                                    }
                                            ?>
                                            <td>

                                                <?php

                                                    $consolidate_percentage_grade = ($consolidate_get_total * 100) / 10;
                                                    $consolidate_exam_result_percentage = $consolidate_percentage_grade;
                                                    echo (two_digit_float($consolidate_get_total)) . " [" . findExamGrade($exam_grade, $exam_value->exam_type, $consolidate_percentage_grade) . "]";
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php
                                                }

                                                if ($consolidate_exam_result) {
                            ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="bgtgray">

                                            <?php

                                                    if ($exam_value->exam_type != "gpa") {
                                            ?>

                                                <div class="col-sm-3 pull no-print">
                                                    <div class="description-block">
                                                        <h5 class="description-header"><?php echo $this->lang->line('result') ?> :
                                                            <span class="description-text">
                                                                <?php

                                                                if ($consolidate_exam_result == "pass") {
                                                                ?>
                                                                    <span class='label label-success ml-rtl' style="margin-right: 5px;">
                                                                        <?php
                                                                        echo $this->lang->line('pass');
                                                                        ?>
                                                                    </span>
                                                                <?php
                                                                } else {
                                                                ?>
                                                                    <span class='label label-danger ml-rtl' style="margin-right: 5px;">
                                                                        <?php
                                                                        echo $this->lang->line('fail');
                                                                        ?>
                                                                    </span>
                                                                <?php
                                                                }
                                                                ?>
                                                            </span>
                                                        </h5>
                                                    </div>
                                                </div>
                                            <?php

                                                    }
                                            ?>
                                            <?php
                                                    if ($consolidate_exam_result_percentage) {
                                            ?>
                                                <div class="col-sm-3 border-right no-print">
                                                    <div class="description-block">
                                                        <h5 class="description-header"><?php echo $this->lang->line('division'); ?> :<span class="description-text">
                                                                <?php
                                                                echo findExamDivision($marks_division, $consolidate_exam_result_percentage);

                                                                ?>
                                                            </span></h5>
                                                    </div>
                                                </div>
                                        <?php
                                                    }
                                                }
                                        ?>
                                        </div>
                                    </div>
                                </div>
                        </div>
            <?php
                                            }
                                        }
                                    }
                                } else {
            ?>
        <?php
                                }

        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="myTransportFeesModal" role="dialog">
    <div class="modal-dialog modal-sm400">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title text-center transport_fees_title"></h4>
            </div>
            <div class="modal-body pb0">
                <form id="form1" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <input type="hidden" class="form-control" id="transport_student_session_id" value="0" readonly="readonly" />
                    <?php echo $this->customlib->getCSRF(); ?>
                    <div id='upload_documents_hide_show'>
                        <input type="hidden" name="student_id" value="<?php echo $student_doc_id; ?>" id="student_id">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('title'); ?> <small class="req">*</small></label>
                            <input id="first_title" name="first_title" placeholder="" type="text" class="form-control" value="<?php echo set_value('first_title'); ?>" />
                            <span class="text-danger"><?php echo form_error('first_title'); ?></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('documents'); ?> <small class="req">*</small></label>
                            <div class="">
                                <input name="first_doc" placeholder="" type="file" class="form-control filestyle" data-height="40" value="<?php echo set_value('first_doc'); ?>" />
                                <span class="text-danger"><?php echo form_error('first_doc'); ?></span>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer" style="clear:both">
                <button type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>

            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="myTimelineModal" role="dialog">
    <div class="modal-dialog modal-sm400">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title timeline_title"></h4>
            </div>
            <div class="">
                <div class="">
                    <form id="timelineform" name="timelineform" method="post" enctype="multipart/form-data">
                        <div class="modal-body pb0">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div id='timeline_hide_show' class="row">
                                <input type="hidden" name="student_id" value="<?php echo $student["id"] ?>" id="student_id">

                                <div class=" col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                        <input id="timeline_title" name="timeline_title" placeholder="" type="text" class="form-control" />
                                        <span class="text-danger"><?php echo form_error('timeline_title'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
                                        <input id="timeline_date" value="<?php echo set_value('timeline_date', date($this->customlib->getSchoolDateFormat())); ?>" name="timeline_date" placeholder="" type="text" class="form-control date" />
                                        <span class="text-danger"><?php echo form_error('timeline_date'); ?></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                        <textarea id="timeline_desc" name="timeline_desc" placeholder="" class="form-control"></textarea>
                                        <span class="text-danger"><?php echo form_error('description'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                                        <div class=""><input id="timeline_doc_id" name="timeline_doc" placeholder="" type="file" class="filestyle form-control" data-height="40" value="<?php echo set_value('timeline_doc'); ?>" />
                                            <span class="text-danger"><?php echo form_error('timeline_doc'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>

                            <button type="reset" id="reset" style="display: none" class="btn btn-info pull-right"><?php echo $this->lang->line('reset'); ?></button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edittimelineModal" role="dialog">
    <div class="modal-dialog modal-sm400">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title"><?php echo $this->lang->line('edit_timeline'); ?></h4>
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

<!------- Behaviour Report Comments Modal--------->
<div class="modal fade" id="commentmodel" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header d-flex justify-content-between">
                <div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="box-title"><?php echo $this->lang->line('comments'); ?></h4>
                </div>
            </div>

            <div class="">
                <div class="modal-body pt0 pb0 relative bg-e6">
                    <form id="formadd" method="post" class="ptt10 mb10 place-italic" enctype="multipart/form-data">
                        <?php
                        $comment_option    = json_decode($behavioursetting['comment_option']);
                        if (isset($comment_option)) {
                            if (in_array($role, $comment_option)) {   ?>
                                <input type="hidden" name="student_incident_id" id="student_incident_id">
                                <div class="clearfix">
                                    <div class="d-flex justify-content-between gap-1">
                                        <textarea name="comment" cols="10" rows="2" placeholder="<?php echo $this->lang->line('type_your_comment'); ?>" class="form-control resize-auto border-radius-1 max-height-40"></textarea>

                                        <button type="submit" class="btn btn-send overflow-inherit max-height-40" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('send') ?></button>
                                    </div>

                                </div>
                        <?php }
                        } ?>
                    </form>

                    <div class="scroll-area-inside">
                        <ul class="user-progress">
                            <div id="messagedetails"></div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
function findGradePoints($exam_grade, $exam_type, $percentage)
{
    foreach ($exam_grade as $exam_grade_key => $exam_grade_value) {
        if ($exam_grade_value['exam_key'] == $exam_type) {

            if (!empty($exam_grade_value['exam_grade_values'])) {
                foreach ($exam_grade_value['exam_grade_values'] as $grade_key => $grade_value) {
                    if ($grade_value->mark_from >= $percentage && $grade_value->mark_upto <= $percentage) {
                        return $grade_value->point;
                    }
                }
            }
        }
    }
    return 0;
}

function findExamGrade($exam_grade, $exam_type, $percentage)
{
    foreach ($exam_grade as $exam_grade_key => $exam_grade_value) {
        if ($exam_grade_value['exam_key'] == $exam_type) {

            if (!empty($exam_grade_value['exam_grade_values'])) {
                foreach ($exam_grade_value['exam_grade_values'] as $grade_key => $grade_value) {
                    if ($grade_value->mark_from >= $percentage && $grade_value->mark_upto <= $percentage) {
                        return $grade_value->name;
                    }
                }
            }
        }
    }
    return "";
}

function findExamDivision($marks_division, $percentage)
{
    if (!empty($marks_division)) {
        foreach ($marks_division as $division_key => $division_value) {
            if ($division_value->percentage_from >= $percentage && $division_value->percentage_to <= $percentage) {
                return $division_value->name;
            }
        }
    }

    return "";
}


function getConsolidateRatio($exam_connection_list, $examid, $get_marks, $exam_get_percentage)
{
    if (!empty($exam_connection_list)) {
        foreach ($exam_connection_list as $exam_connection_key => $exam_connection_value) {

            if ($exam_connection_value->exam_group_class_batch_exams_id == $examid) {
                return [
                    'exam_weightage' => $exam_connection_value->exam_weightage,
                    'exam_consolidate_marks' => ($get_marks * $exam_connection_value->exam_weightage) / 100,
                    'exam_consolidate_percentage' => ($exam_get_percentage * $exam_connection_value->exam_weightage) / 100
                ];
            }
        }
    }
    return 0;
}

function getCalculatedExamGradePoints($array, $exam_id, $exam_grade, $exam_type)
{
    $object              = new stdClass();
    $return_total_points = 0;
    $return_total_exams  = 0;
    $return_max_marks  = 0;
    $return_quality_point  = 0;
    $return_get_marks  = 0;
    $return_credit_hours  = 0;
    if (!empty($array)) {

        if (!empty($array['exam_result_' . $exam_id])) {

            foreach ($array['exam_result_' . $exam_id] as $exam_key => $exam_value) {
                $return_total_exams++;
                // print_r($exam_value->credit_hours);
                // exit();
                $percentage_grade    = ($exam_value->get_marks * 100) / $exam_value->max_marks;
                $point               = findGradePoints($exam_grade, $exam_type, $percentage_grade);
                $return_total_points = $return_total_points + $point;
                $return_quality_point += ($point * $exam_value->credit_hours);
                $return_credit_hours += $exam_value->credit_hours;
                $return_max_marks += $exam_value->max_marks;
                $return_get_marks += $exam_value->get_marks;
            }
        }
    }

    $object->total_max_marks = $return_max_marks;
    $object->total_get_marks = $return_get_marks;
    $object->total_points = $return_total_points;
    $object->total_exams  = $return_total_exams;
    $object->return_quality_point  = $return_quality_point;
    $object->return_credit_hours  = $return_credit_hours;

    return $object;
}

function getCalculatedExam($array, $exam_id)
{
    $object              = new stdClass();
    $return_max_marks    = 0;
    $return_get_marks    = 0;
    $return_credit_hours = 0;
    $return_exam_status  = false;
    if (!empty($array)) {
        $return_exam_status = 'pass';
        if (!empty($array['exam_result_' . $exam_id])) {
            foreach ($array['exam_result_' . $exam_id] as $exam_key => $exam_value) {

                if ($exam_value->get_marks < $exam_value->min_marks || $exam_value->attendence != "present") {
                    $return_exam_status = "fail";
                }

                $return_max_marks    = $return_max_marks + ($exam_value->max_marks);
                $return_get_marks    = $return_get_marks + ($exam_value->get_marks);
                $return_credit_hours = $return_credit_hours + ($exam_value->credit_hours);
            }
        }
    }
    $object->credit_hours = $return_credit_hours;
    $object->get_marks    = $return_get_marks;
    $object->max_marks    = $return_max_marks;
    $object->exam_status  = $return_exam_status;
    return $object;
}
?>
<script>
    $('.comments').click(function() {
        var student_incident_id = $(this).attr('data-record-id');
        $('#student_incident_id').val(student_incident_id);

        $('#commentmodel').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
        getmessage(student_incident_id);
    })

    $("#formadd").on('submit', (function(e) {
        e.preventDefault();

        var student_incident_id = $('#student_incident_id').val();

        var $this = $(this).find("button[type=submit]:focus");
        $.ajax({
            url: "<?php echo site_url("user/studentincidentcomments/addmessage"); ?>",
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
                    $('#formadd')[0].reset();
                    getmessage(student_incident_id);
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

    function getmessage(student_incident_id) {
        $('#messagedetails').html('');
        $.ajax({
            url: "<?php echo site_url("user/studentincidentcomments/getmessage"); ?>",
            type: "POST",
            data: {
                student_incident_id: student_incident_id
            },
            dataType: 'json',
            success: function(res) {
                if (res.status == "success") {
                    $('#messagedetails').html(res.page);
                } else {
                    $('#messagedetails').html('');
                }
            }
        });
    }

    function delete_comment(id, student_incident_id) {
        if (confirm("<?php echo $this->lang->line('delete_confirm'); ?>") == true) {
            $.ajax({
                url: "<?php echo site_url("user/studentincidentcomments/delete_comment"); ?>",
                type: "POST",
                data: {
                    id: id
                },
                success: function(res) {
                    getmessage(student_incident_id);
                }
            });
        }
    }
</script>

<script type="text/javascript">
    // var base_url = '<?php echo base_url() ?>';

    // function printDiv(elem) {
        // Popup(jQuery(elem).html());
    // }

    // function Popup(data) {
        // var frame1 = $('<iframe />');
        // frame1[0].name = "frame1";
        // frame1.css({
            // "position": "absolute",
            // "top": "-1000000px"
        // });
        // $("body").append(frame1);
        // var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        // frameDoc.document.open();
        // frameDoc.document.write('<html>');
        // frameDoc.document.write('<head>');
        // frameDoc.document.write('<title></title>');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        // frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        // frameDoc.document.write('</head>');
        // frameDoc.document.write('<body>');
        // frameDoc.document.write(data);
        // frameDoc.document.write('</body>');
        // frameDoc.document.write('</html>');
        // frameDoc.document.close();
        // setTimeout(function() {
            // window.frames["frame1"].focus();
            // window.frames["frame1"].print();
            // frame1.remove();
        // }, 500);
        // return true;
    // }
</script>
<script type="text/javascript">
    /*--dropify--*/
    $(document).ready(function() {
        // Basic
        $('.filestyle').dropify();
    });
    /*--end dropify--*/
</script>
<script type="text/javascript">
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

    $(document).ready(function() {
        $('table.display').DataTable();
    });
</script>
<script type="text/javascript">
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

    function printDiv(divid) {

        $('.bg-green').removeClass('label');
        $('.label-danger').removeClass('label');
        $('.label-success').removeClass('label');
        var divElements = document.getElementById(divid).innerHTML;
        var oldPage = document.body.innerHTML;
        document.body.innerHTML =
            "<html><head><title></title></head><body>" +
            divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;

        location.reload(true);
    }

    $(document).ready(function() {
        $(document).on('click', '.close_notice', function() {
            var data = $(this).data();

            $.ajax({
                type: "POST",
                url: "<?php echo site_url("user/notification/read") ?>",   
                data: {
                    'notice': data.noticeid
                },
                dataType: "json",
                success: function(data) {
                    if (data.status == "fail") {

                        errorMsg(data.msg);
                    } else {
                        successMsg(data.msg);
                    }
                }
            });
        });
    });

    $("#myTimelineButton").click(function() {
        $('.timeline_title').html("<b><?php echo $this->lang->line('add_timeline'); ?></b>");
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
            url: "<?php echo site_url("user/timeline/add") ?>",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $this.button('loading');

            },
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
            },
            complete: function() {
                $this.button('reset');
            }
        });
    }));

    $('.edit_timeline').click(function() {
        $('#edittimelineModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });

        var id = $(this).attr('data-id');
        $.ajax({
            url: "<?php echo site_url("user/timeline/getstudentsingletimeline") ?>",
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
            url: "<?php echo site_url("user/timeline/edit") ?>",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $this.button('loading');
            },
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
            },
            complete: function() {
                $this.button('reset');
            }
        });

    }));

    function delete_timeline(id) {

        if (confirm('<?php echo $this->lang->line("delete_confirm") ?>')) {

            $.ajax({
                url: '<?php echo base_url(); ?>user/timeline/delete_timeline/',
                type: 'post',
                data: {
                    id: id
                },
                dataType: 'JSON',
                success: function(res) {
                    if (res.status == 'success') {
                        successMsg(res.message);
                        window.location.reload(true);
                    }

                },
                error: function() {
                    alert("<?php echo $this->lang->line('fail'); ?>");
                }
            });
        }
    }
</script>
<script>
    $("#form1").on('submit', (function(e) {
        e.preventDefault();

        var $this = $(this).find("button[type=submit]:focus");

        document.getElementById('submit').disabled = true;

        $.ajax({
            url: "<?php echo site_url("user/user/create_doc") ?>",
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
                document.getElementById('submit').disabled = false;
            }

        });
    }));
</script>



<?php
//-----------CBSE Exam start----------------------

function find_subject_assessment_exists($subject_assessments, $cbse_exam_timetable_id, $cbse_exam_assessment_type_id)
{

    if (!empty($subject_assessments)) {
        foreach ($subject_assessments as $key => $value) {
            if ($value->id == $cbse_exam_timetable_id) {
                if (!empty($value->subject_assessments)) {
                    foreach ($value->subject_assessments as $askey => $asvalue) {
                        if ($asvalue->cbse_exam_timetable_id == $cbse_exam_timetable_id  && $asvalue->cbse_exam_assessment_type_id == $cbse_exam_assessment_type_id) {
                            return true;
                            break;
                        }
                    }
                }
            }
        }
    }
    return false;
}

function getGrade($grade_array, $Percentage)
{

    if (!empty($grade_array)) {
        foreach ($grade_array as $grade_key => $grade_value) {

            if ($grade_value->minimum_percentage <= $Percentage) {
                return $grade_value->name;
                break;
            } elseif (($grade_value->minimum_percentage >= $Percentage && $grade_value->maximum_percentage <= $Percentage)) {

                return $grade_value->name;
                break;
            }
        }
    }
    return "-";
}

function findAssessmentValue($find_subject_id, $find_cbse_exam_assessment_type_id, $student_value)
{

 
    $return_array = [
        'maximum_marks' => "",
        'marks' => "",
        'note' => "",
        'is_absent' => "",
    ];


    if (property_exists($student_value,'subjects')) {

        if (array_key_exists($find_subject_id, $student_value->exam_data['subjects'])) {

            $result_array = ($student_value->exam_data['subjects'][$find_subject_id]['exam_assessments'][$find_cbse_exam_assessment_type_id]);


            $return_array = [
                'maximum_marks' => $result_array['maximum_marks'],
                'marks' => is_null($result_array['marks']) ? "N/A" : $result_array['marks'],
                'note' => $result_array['note'],
                'is_absent' => $result_array['is_absent'],
            ];
        }
    }


    return $return_array;
}

//-----------CBSE Exam End----------------------


