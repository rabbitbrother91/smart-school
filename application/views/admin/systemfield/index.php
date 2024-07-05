<style type="text/css">
    .table .pull-right {text-align: initial; width: auto; float: right !important}
</style>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> <?php //echo $this->lang->line('system_settings'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-12">
                <div class="nav-tabs-custom theme-shadow">
                    <ul class="nav nav-tabs pull-right">
                        <li><a href="#tab_parent" data-toggle="tab"><?php echo $this->lang->line('staff') ?></a></li>
                        <li class="active"><a href="#tab_student" data-toggle="tab" ><?php echo $this->lang->line('student') ?></a></li>
                        <li class="pull-left header"><?php echo $this->lang->line('system_fields'); ?></li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active table-responsive" id="tab_student">
                            <div class="download_label"><?php echo $this->lang->line('system_fields'); ?></div>
                            <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('roll_number'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="roll_no" name="roll_no" type="checkbox" data-role="roll_no" class="chk" data-rowid="1" value="checked" <?php if ($result->roll_no == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="roll_no" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                     <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('middle_name'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="middlename" name="middlename" type="checkbox" data-role="middlename" class="chk" data-rowid="1" value="checked" <?php if ($result->middlename == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="middlename" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('last_name'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="lastname" name="lastname" type="checkbox" data-role="lastname" class="chk" data-rowid="1" value="checked" <?php if ($result->lastname == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="lastname" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('category'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="category" name="category" type="checkbox" data-role="category" class="chk" data-rowid="1" value="checked" <?php if ($result->category == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="category" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('religion'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="religion" name="religion" type="checkbox" data-role="religion" class="chk" data-rowid="1" value="checked" <?php if ($result->religion == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="religion" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('caste'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="cast" name="cast" type="checkbox" data-role="cast" class="chk" data-rowid="1" value="checked" <?php if ($result->cast == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="cast" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('mobile_number'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="mobile_no" name="mobile_no" type="checkbox" data-role="mobile_no" class="chk" data-rowid="1" value="checked" <?php if ($result->mobile_no == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="mobile_no" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('email'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="student_email" name="student_email" type="checkbox" data-role="student_email" class="chk" data-rowid="1" value="checked" <?php if ($result->student_email == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="student_email" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('admission_date'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="admission_date" name="admission_date" type="checkbox" data-role="admission_date" class="chk" data-rowid="1" value="checked" <?php if ($result->admission_date == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="admission_date" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('student_photo'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="student_photo" name="student_photo" type="checkbox" data-role="student_photo" class="chk" data-rowid="1" value="checked" <?php if ($result->student_photo == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="student_photo" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('house') ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="is_student_house" name="is_student_house" type="checkbox" data-role="is_student_house" class="chk" data-rowid="1" value="checked" <?php if ($result->is_student_house == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="is_student_house" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('blood_group'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="is_blood_group" name="is_blood_group" type="checkbox" data-role="is_blood_group" class="chk" data-rowid="1" value="checked" <?php if ($result->is_blood_group == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="is_blood_group" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('height'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="student_height" name="student_height" type="checkbox" data-role="student_height" class="chk" data-rowid="1" value="checked" <?php if ($result->student_height == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="student_height" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('weight'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="student_weight" name="student_weight" type="checkbox" data-role="student_weight" class="chk" data-rowid="1" value="checked" <?php if ($result->student_weight == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="student_weight" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('measurement_date'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="measurement_date" name="measurement_date" type="checkbox" data-role="measurement_date" class="chk" data-rowid="1" value="checked" <?php if ($result->measurement_date == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="measurement_date" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('father_name'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="father_name" name="father_name" type="checkbox" data-role="father_name" class="chk" data-rowid="1" value="checked" <?php if ($result->father_name == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="father_name" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('father_phone'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="father_phone" name="father_phone" type="checkbox" data-role="father_phone" class="chk" data-rowid="1" value="checked" <?php if ($result->father_phone == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="father_phone" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('father_occupation'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="father_occupation" name="father_occupation" type="checkbox" data-role="father_occupation" class="chk" data-rowid="1" value="checked" <?php if ($result->father_occupation == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="father_occupation" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('father_photo'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="father_pic" name="father_pic" type="checkbox" data-role="father_pic" class="chk" data-rowid="1" value="checked" <?php if ($result->father_pic == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="father_pic" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('mother_name'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="mother_name" name="mother_name" type="checkbox" data-role="mother_name" class="chk" data-rowid="1" value="checked" <?php if ($result->mother_name == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="mother_name" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('mother_phone'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="mother_phone" name="mother_phone" type="checkbox" data-role="mother_phone" class="chk" data-rowid="1" value="checked" <?php if ($result->mother_phone == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="mother_phone" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('mother_occupation'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="mother_occupation" name="mother_occupation" type="checkbox" data-role="mother_occupation" class="chk" data-rowid="1" value="checked" <?php if ($result->mother_occupation == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="mother_occupation" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('mother_photo'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="mother_pic" name="mother_pic" type="checkbox" data-role="mother_pic" class="chk" data-rowid="1" value="checked" <?php if ($result->mother_pic == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="mother_pic" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('guardian_name'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="guardian_name" name="guardian_name" type="checkbox" data-role="guardian_name" class="chk" data-rowid="1" value="checked" <?php if ($result->guardian_name == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="guardian_name" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('guardian_phone'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="guardian_phone" name="guardian_phone" type="checkbox" data-role="guardian_phone" class="chk" data-rowid="1" value="checked" <?php if ($result->guardian_phone == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="guardian_phone" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                     <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('guardian_relation'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="guardian_relation" name="guardian_relation" type="checkbox" data-role="guardian_relation" class="chk" data-rowid="1" value="checked" <?php if ($result->guardian_relation == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="guardian_relation" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('guardian_email'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="guardian_email" name="guardian_email" type="checkbox" data-role="guardian_email" class="chk" data-rowid="1" value="checked" <?php if ($result->guardian_email == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="guardian_email" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('guardian_occupation'); ?> </td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="guardian_occupation" name="guardian_occupation" type="checkbox" data-role="guardian_occupation" class="chk" data-rowid="1" value="checked" <?php if ($result->guardian_occupation == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="guardian_occupation" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                     <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('guardian_photo'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="guardian_pic" name="guardian_pic" type="checkbox" data-role="guardian_pic" class="chk" data-rowid="1" value="checked" <?php if ($result->guardian_pic == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="guardian_pic" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('guardian_address'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="guardian_address" name="guardian_address" type="checkbox" data-role="guardian_address" class="chk" data-rowid="1" value="checked" <?php if ($result->guardian_address == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="guardian_address" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('if_guardian_address_is_current_address'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="current_address" name="current_address" type="checkbox" data-role="current_address" class="chk" data-rowid="1" value="checked" <?php if ($result->current_address == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="current_address" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('if_permanent_address_is_current_address'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="permanent_address" name="permanent_address" type="checkbox" data-role="permanent_address" class="chk" data-rowid="1" value="checked" <?php if ($result->permanent_address == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="permanent_address" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('route_list'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="route_list" name="route_list" type="checkbox" data-role="route_list" class="chk" data-rowid="1" value="checked" <?php if ($result->route_list == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="route_list" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('hostel_details'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="hostel_id" name="hostel_id" type="checkbox" data-role="hostel_id" class="chk" data-rowid="1" value="checked" <?php if ($result->hostel_id == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="hostel_id" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('bank_account_number'); ?> </td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="bank_account_no" name="bank_account_no" type="checkbox" data-role="bank_account_no" class="chk" data-rowid="1" value="checked" <?php if ($result->bank_account_no == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="bank_account_no" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('bank_name'); ?> </td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="bank_name" name="bank_name" type="checkbox" data-role="bank_name" class="chk" data-rowid="1" value="checked" <?php if ($result->bank_name == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="bank_name" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('ifsc_code'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="ifsc_code" name="ifsc_code" type="checkbox" data-role="ifsc_code" class="chk" data-rowid="1" value="checked" <?php if ($result->ifsc_code == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="ifsc_code" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('national_identification_number'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="national_identification_no" name="national_identification_no" type="checkbox" data-role="national_identification_no" class="chk" data-rowid="1" value="checked" <?php if ($result->national_identification_no == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="national_identification_no" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('local_identification_number'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="local_identification_no" name="local_identification_no" type="checkbox" data-role="local_identification_no" class="chk" data-rowid="1" value="checked" <?php if ($result->local_identification_no == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="local_identification_no" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('rte'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="rte" name="rte" type="checkbox" data-role="rte" class="chk" data-rowid="1" value="checked" <?php if ($result->rte) {
    echo "checked='checked'";
}
?> />
                                                <label for="rte" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('previous_school_details'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="previous_school_details" name="previous_school_details" type="checkbox" data-role="previous_school_details" class="chk" data-rowid="1" value="checked" <?php if ($result->previous_school_details == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="previous_school_details" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('note'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="student_note" name="student_note" type="checkbox" data-role="student_note" class="chk" data-rowid="1" value="checked" <?php if ($result->student_note == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="student_note" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('upload_documents'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="upload_documents" name="upload_documents" type="checkbox" data-role="upload_documents" class="chk" data-rowid="1" value="checked" <?php if ($result->upload_documents == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="upload_documents" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('barcode'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="student_barcode" name="student_barcode" type="checkbox" data-role="student_barcode" class="chk" data-rowid="1" value="checked" <?php if ($result->student_barcode == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="student_barcode" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane table-responsive" id="tab_parent">
                            <div class="download_label"><?php echo $this->lang->line('system_fields'); ?></div>
                            <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('designation'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_designation" name="staff_designation" type="checkbox" data-role="staff_designation" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_designation == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_designation" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('department'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_department" name="staff_department" type="checkbox" data-role="staff_department" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_department == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_department" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('last_name'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_last_name" name="staff_last_name" type="checkbox" data-role="staff_last_name" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_last_name == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_last_name" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('father_name'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_father_name" name="staff_father_name" type="checkbox" data-role="staff_father_name" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_father_name == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_father_name" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('mother_name'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_mother_name" name="staff_mother_name" type="checkbox" data-role="staff_mother_name" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_mother_name == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_mother_name" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('date_of_joining'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_date_of_joining" name="staff_date_of_joining" type="checkbox" data-role="staff_date_of_joining" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_date_of_joining == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_date_of_joining" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('phone'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_phone" name="staff_phone" type="checkbox" data-role="staff_phone" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_phone == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_phone" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('emergency_contact_number'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_emergency_contact" name="staff_emergency_contact" type="checkbox" data-role="staff_emergency_contact" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_emergency_contact == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_emergency_contact" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('marital_status'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_marital_status" name="staff_marital_status" type="checkbox" data-role="staff_marital_status" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_marital_status == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_marital_status" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('photo'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_photo" name="staff_photo" type="checkbox" data-role="staff_photo" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_photo == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_photo" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('current_address'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_current_address" name="staff_current_address" type="checkbox" data-role="staff_current_address" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_current_address == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_current_address" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('permanent_address'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_permanent_address" name="staff_permanent_address" type="checkbox" data-role="staff_permanent_address" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_permanent_address == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_permanent_address" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('qualification'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_qualification" name="staff_qualification" type="checkbox" data-role="staff_qualification" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_qualification == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_qualification" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('work_experience'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_work_experience" name="staff_work_experience" type="checkbox" data-role="staff_work_experience" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_work_experience == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_work_experience" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('note'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_note" name="staff_note" type="checkbox" data-role="staff_note" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_note == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_note" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('epf_no'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_epf_no" name="staff_epf_no" type="checkbox" data-role="staff_epf_no" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_epf_no == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_epf_no" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('basic_salary'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_basic_salary" name="staff_basic_salary" type="checkbox" data-role="staff_basic_salary" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_basic_salary == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_basic_salary" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('contract_type'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_contract_type" name="staff_contract_type" type="checkbox" data-role="staff_contract_type" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_contract_type == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_contract_type" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('work_shift'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_work_shift" name="staff_work_shift" type="checkbox" data-role="staff_work_shift" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_work_shift == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_work_shift" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('work_location'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_work_location" name="staff_work_location" type="checkbox" data-role="staff_work_location" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_work_location == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_work_location" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('leaves'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_leaves" name="staff_leaves" type="checkbox" data-role="staff_leaves" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_leaves == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_leaves" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('bank_account_details'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_account_details" name="staff_account_details" type="checkbox" data-role="staff_account_details" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_account_details == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_account_details" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('social_media_link'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_social_media" name="staff_social_media" type="checkbox" data-role="staff_social_media" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_social_media == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_social_media" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('upload_documents'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_upload_documents" name="staff_upload_documents" type="checkbox" data-role="staff_upload_documents" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_upload_documents == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_upload_documents" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-rtl-right" width="100%"><?php echo $this->lang->line('barcode'); ?></td>
                                        <td>
                                            <div class="material-switch pull-right">
                                                <input id="staff_barcode" name="staff_barcode" type="checkbox" data-role="staff_barcode" class="chk" data-rowid="1" value="checked" <?php if ($result->staff_barcode == "1") {
    echo "checked='checked'";
}
?> />
                                                <label for="staff_barcode" class="label-success"></label>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                    </div>
                    <!-- /.tab-content -->
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $(document).on('click', '.chk', function () {
            var checked = $(this).is(':checked');
            var rowid = $(this).data('rowid');
            var role = $(this).data('role');

            console.log(checked);
            console.log(rowid);
            console.log(role);

            if (checked) {
                if (!confirm('<?php echo $this->lang->line('confirm_status'); ?>')) {
                    $(this).removeAttr('checked');
                } else {
                    var status = "yes";
                    changeStatus(rowid, status, role);

                }
            } else if (!confirm('<?php echo $this->lang->line('confirm_status'); ?>')) {
                $(this).prop("checked", true);
            } else {
                var status = "no";
                changeStatus(rowid, status, role);

            }
        });
    });

    function changeStatus(rowid, status, role) {

        var base_url = '<?php echo base_url() ?>';

        $.ajax({
            type: "POST",
            url: base_url + "admin/systemfield/changeStatus",
            data: {'id': rowid, 'status': status, 'role': role},
            dataType: "json",
            success: function (data) {
                successMsg(data.msg);
            }
        });
    }

</script>