<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php //echo $this->lang->line('human_resource'); ?></h1>
    </section>
    <!-- Main content -->

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form id="form1" action="<?php echo site_url('admin/staff/edit/' . $staff["id"]) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="alert alert-info">
                                Staff email is their login username, password is generated automatically and send to staff email. Superadmin can change staff password on their staff profile page.

                            </div>
                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2"><?php echo $this->lang->line('basic_information'); ?> </h4>
                                <div class="around10">
                                    <?php if ($this->session->flashdata('msg')) {
    echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg');
}?>
                                    <?php echo $this->customlib->getCSRF(); ?>

                                    <div class="row">
                                        <?php if (!$staffid_auto_insert) {?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('staff_id'); ?></label><small class="req"> *</small>
                                                    <input autofocus="" id="employee_id" name="employee_id" placeholder="" value="<?php echo $staff["employee_id"] ?>" type="text" class="form-control"  value="" />
                                                    <span class="text-danger"><?php echo form_error('employee_id'); ?></span>
                                                </div>
                                            </div>
                                        <?php }?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('role'); ?></label><small class="req"> *</small>
                                                <input type="hidden" name="editid" value="<?php echo $staff['id']; ?>">
                                                <select  id="role" name="role" class="form-control" >
                                                    <option value=""   ><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
foreach ($getStaffRole as $key => $role) {
    ?>
                                                        <option value="<?php echo $role["id"] ?>" <?php
if ($staff["user_type"] == $role["type"]) {
        echo "selected";
    }
    ?>><?php echo $role["type"] ?></option>
                                                            <?php }
?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('role'); ?></span>
                                            </div>
                                        </div>
                                        <?php if ($sch_setting->staff_designation) {
    ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('designation'); ?></label>

                                                    <select id="designation" name="designation" placeholder="" type="text" class="form-control" >
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($designation as $key => $value) {
        ?>
                                                            <option value="<?php echo $value["id"] ?>" <?php
if ($staff["designation"] == $value["id"]) {
            echo "selected";
        }
        ?>><?php echo $value["designation"] ?></option>
                                                                <?php }
    ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('designation'); ?></span>
                                                </div>
                                            </div>
                                        <?php }if ($sch_setting->staff_department) {
    ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('department'); ?></label>
                                                    <select id="department" name="department" placeholder="" type="text" class="form-control" >
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($department as $key => $value) {
        ?>
                                                            <option value="<?php echo $value["id"] ?>" <?php
if ($staff["department"] == $value["id"]) {
            echo "selected";
        }
        ?>><?php echo $value["department_name"] ?></option>
                                                                <?php }
    ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('department'); ?></span>
                                                </div>
                                            </div>
                                        <?php }?>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('first_name'); ?></label><small class="req"> *</small>
                                                <input id="firstname" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name', $staff["name"]); ?>" />
                                                <span class="text-danger"><?php echo form_error('name'); ?></span>
                                            </div>
                                        </div>
                                        <?php if ($sch_setting->staff_last_name) {?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('last_name'); ?></label>
                                                    <input id="surname" name="surname" placeholder="" type="text" class="form-control"  value="<?php echo set_value('surname', $staff["surname"]); ?>" />
                                                    <span class="text-danger"><?php echo form_error('surname'); ?></span>
                                                </div>
                                            </div>
                                        <?php }if ($sch_setting->staff_father_name) {?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('father_name'); ?></label>
                                                    <input id="father_name" name="father_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_name', $staff["father_name"]); ?>" />
                                                    <span class="text-danger"><?php echo form_error('father_name'); ?></span>
                                                </div>
                                            </div>
                                        <?php }if ($sch_setting->staff_mother_name) {?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_name'); ?></label>
                                                    <input id="mother_name" name="mother_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_name', $staff["mother_name"]); ?>" />
                                                    <span class="text-danger"><?php echo form_error('mother_name'); ?></span>
                                                </div>
                                            </div>
                                        <?php }?>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('email'); ?></label><small class="req"> *</small>
                                                <input id="email" name="email" placeholder="" type="text" class="form-control"  value="<?php echo set_value('email', $staff["email"]); ?>" />
                                                <span class="text-danger"><?php echo form_error('email'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile"> <?php echo $this->lang->line('gender'); ?></label><small class="req"> *</small>
                                                <select class="form-control" name="gender">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
foreach ($genderList as $key => $value) {
    ?>
                                                        <option value="<?php echo $key; ?>" <?php if ($staff['gender'] == $key) {
        echo "selected";
    }
    ?>><?php echo $value; ?></option>
                                                        <?php
}
?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('gender'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('date_of_birth'); ?></label><small class="req"> *</small>
                                                <input id="dob" name="dob" placeholder="" type="text" class="form-control date"  value="<?php
if (!empty($staff["dob"])) {
    echo date($this->customlib->getSchoolDateFormat(), strtotime($staff["dob"]));
}
?>" />
                                                <span class="text-danger"><?php echo form_error('dob'); ?></span>
                                            </div>
                                        </div>
                                        <?php if ($sch_setting->staff_date_of_joining) {
    ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('date_of_joining'); ?></label>
                                                    <input id="date_of_joining" name="date_of_joining" placeholder="" type="text" class="form-control date"  value="<?php
if ($staff["date_of_joining"]) {
        echo date($this->customlib->getSchoolDateFormat(), strtotime($staff["date_of_joining"]));
    }
    ?>"  />
                                                    <span class="text-danger"><?php echo form_error('date_of_joining'); ?></span>
                                                </div>
                                            </div>
                                        <?php }?>
                                    </div>
                                    <div class="row">
                                        <?php if ($sch_setting->staff_phone) {?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('phone'); ?></label>
                                                    <input id="mobileno" name="contactno" placeholder="" type="text" class="form-control"  value="<?php echo set_value('contactno', $staff["contact_no"]); ?>" />
                                                    <input id="editid" name="editid" placeholder="" type="hidden" class="form-control"  value="<?php echo $staff["id"]; ?>" />
                                                    <span class="text-danger"><?php echo form_error('contactno'); ?></span>
                                                </div>
                                            </div>
                                        <?php }if ($sch_setting->staff_emergency_contact) {?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('emergency_contact_number'); ?></label>
                                                    <input id="mobileno" name="emergency_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('emergency_no', $staff["emergency_contact_no"]); ?>" />
                                                    <span class="text-danger"><?php echo form_error('emergency_no'); ?></span>
                                                </div>
                                            </div>
                                        <?php }if ($sch_setting->staff_marital_status) {
    ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('marital_status'); ?></label>
                                                    <select class="form-control" name="marital_status">
                                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                        <?php foreach ($marital_status as $makey => $mavalue) {
        ?>
                                                            <option <?php
if ($staff["marital_status"] == $mavalue) {
            echo "selected";
        }
        ?> value="<?php echo $mavalue; ?>"><?php echo $mavalue; ?></option>
                                                            <?php }?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('marital_status'); ?></span>
                                                </div>
                                            </div>
                                        <?php }if ($sch_setting->staff_photo) {?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo $this->lang->line('photo'); ?></label>
                                                    <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' />
                                                    </div>
                                                    <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                            </div>
                                        <?php }?>
                                    </div>
                                    <div class="row">
                                        <?php if ($sch_setting->staff_current_address) {?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo $this->lang->line('current_address'); ?></label>
                                                    <div><textarea name="address" class="form-control"><?php echo set_value('address', $staff["local_address"]) ?></textarea>
                                                    </div>
                                                    <span class="text-danger"></span></div>
                                            </div>
                                        <?php }if ($sch_setting->staff_permanent_address) {?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo $this->lang->line('permanent_address'); ?></label>
                                                    <div><textarea name="permanent_address" class="form-control"><?php echo set_value('permanent_address', $staff["permanent_address"]); ?></textarea>
                                                    </div>
                                                    <span class="text-danger"></span></div>
                                            </div>
                                        <?php }if ($sch_setting->staff_qualification) {?>
                                            <div class="col-md-3">

                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('qualification'); ?></label>
                                                    <textarea id="qualification" name="qualification" placeholder=""  class="form-control" ><?php echo set_value('qualification', $staff["qualification"]); ?></textarea>
                                                    <span class="text-danger"><?php echo form_error('qualification'); ?></span>
                                                </div>
                                            </div>
                                        <?php }if ($sch_setting->staff_work_experience) {?>
                                            <div class="col-md-3">

                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('work_experience'); ?></label>
                                                    <textarea id="permanent_address" name="work_exp" placeholder="" class="form-control"><?php echo set_value('work_exp', $staff["work_exp"]) ?></textarea>
                                                    <span class="text-danger"><?php echo form_error('work_exp'); ?></span>
                                                </div>
                                            </div>
                                        <?php }if ($sch_setting->staff_note) {?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo $this->lang->line('note'); ?></label>
                                                    <div><textarea name="note" class="form-control"><?php echo set_value('note', $staff["note"]) ?></textarea>
                                                    </div>
                                                    <span class="text-danger"></span></div>
                                            </div>
                                        <?php }?>
                                    </div>
                                    <div class="row">
                                        <?php
echo display_custom_fields('staff', $staff['id']);
?>
                                    </div>
                                </div>
                            </div>
                            <div class="box-group collapsed-box">
                                <div class="panel box box-success collapsed-box">
                                    <div class="box-header with-border">
                                        <a data-widget="collapse" data-original-title="Collapse" aria-expanded="false" class="collapsed btn boxplus">
                                            <i class="fa fa-fw fa-plus"></i><?php echo $this->lang->line('add_more_details'); ?>
                                        </a>
                                    </div>
                                    <div class="box-body">
                                        <div class="tshadow mb25 bozero">
                                            <h4 class="pagetitleh2"><?php echo $this->lang->line('payroll'); ?>
                                            </h4>
                                            <div class="row around10">
                                                <?php if ($sch_setting->staff_epf_no) {?>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('epf_no'); ?></label>
                                                            <input id="epf_no" name="epf_no" placeholder="" type="text" class="form-control"  value="<?php echo $staff["epf_no"] ?>"  />
                                                            <span class="text-danger"><?php echo form_error('epf_no'); ?></span>
                                                        </div>
                                                    </div>
                                                <?php }if ($sch_setting->staff_contract_type) {
    ?>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('contract_type'); ?></label>
                                                            <select class="form-control" name="contract_type">
                                                                <option value=""><?php echo $this->lang->line('select') ?></option>

                                                                <?php foreach ($contract_type as $key => $value) {?>
                                                                    <option value="<?php echo $key ?>" <?php
if ($staff["contract_type"] == $key) {
        echo "selected";
    }
        ?>><?php echo $value ?></option>

                                                                <?php }?>
                                                            </select>
                                                            <span class="text-danger"><?php echo form_error('contract_type'); ?></span>
                                                        </div>
                                                    </div>
                                                <?php }if ($sch_setting->staff_basic_salary) {?>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('basic_salary'); ?></label>
                                                            <input type="text" value="<?php echo $staff["basic_salary"] ?>" class="form-control" name="basic_salary" >
                                                        </div>
                                                    </div>
                                                <?php }if ($sch_setting->staff_work_shift) {?>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('work_shift'); ?></label>
                                                            <input id="shift" name="shift" placeholder="" type="text" class="form-control"  value="<?php echo $staff["shift"] ?>" />
                                                        </div>
                                                    </div>
                                                <?php }if ($sch_setting->staff_work_location) {?>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('work_location'); ?></label>
                                                            <input id="location" name="location" placeholder="" type="text" class="form-control"  value="<?php echo $staff["location"] ?>" />
                                                        </div>
                                                    </div>
                                                <?php }?>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('date_of_leaving'); ?></label>
                                                        <input id="date_of_leaving" name="date_of_leaving" placeholder="" type="text" class="form-control date"  value="<?php
if ($staff["date_of_leaving"]) {
    echo date($this->customlib->getSchoolDateFormat(), strtotime($staff["date_of_leaving"]));
}
?>" />
                                                        <span class="text-danger"><?php echo form_error('date_of_leaving'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($sch_setting->staff_leaves) {
    ?>
                                            <div class="tshadow mb25 bozero">
                                                <h4 class="pagetitleh2"><?php echo $this->lang->line('leaves'); ?>
                                                </h4>

                                                <div class="row around10" >
                                                    <?php
$j = 0;
    foreach ($leavetypeList as $key => $leave) {
        # code...
        ?>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"><?php echo $leave["type"]; ?></label>
                                                                <input id="ifsc_code" name="alloted_leave[]" placeholder="<?php echo $this->lang->line('number_of_leaves'); ?>" type="text" class="form-control"  value="<?php
if (array_key_exists($j, $staffLeaveDetails)) {
            echo $staffLeaveDetails[$j]["alloted_leave"];
        }
        ?>" />

                                                                <input  name="leave_type[]" placeholder="" type="hidden" readonly class="form-control"  value="<?php echo $leave["type"] ?>" />

                                                                <input  name="altid[]" placeholder="" type="hidden" readonly class="form-control"  value="<?php
if (array_key_exists($j, $staffLeaveDetails)) {
            echo $staffLeaveDetails[$j]["altid"];
        }
        ?>" />

                                                                <input  name="leave_type_id[]" placeholder="" type="hidden" class="form-control"  value="<?php echo $leave["id"]; ?>" />
                                                                <span class="text-danger"><?php echo form_error('ifsc_code'); ?></span>
                                                            </div>
                                                        </div>
                                                        <?php
$j++;
    }
    ?>
                                                </div>
                                            </div>
                                        <?php }if ($sch_setting->staff_account_details) {?>
                                            <div class="tshadow mb25 bozero">
                                                <h4 class="pagetitleh2"><?php echo $this->lang->line('bank_account_details'); ?>
                                                </h4>
                                                <div class="row around10">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('account_title'); ?></label>
                                                            <input id="account_title" name="account_title" placeholder="" type="text" class="form-control"  value="<?php echo $staff["account_title"] ?>" />
                                                            <span class="text-danger"><?php echo form_error('bank_account_no'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('bank_account_number'); ?></label>
                                                            <input id="bank_account_no" name="bank_account_no" placeholder="" type="text" class="form-control"  value="<?php echo $staff["bank_account_no"] ?>" />
                                                            <span class="text-danger"><?php echo form_error('bank_account_no'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('bank_name'); ?></label>
                                                            <input id="bank_name" name="bank_name" placeholder="" type="text" class="form-control"  value="<?php echo $staff["bank_name"] ?>" />
                                                            <span class="text-danger"><?php echo form_error('bank_name'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('ifsc_code'); ?></label>
                                                            <input id="ifsc_code" name="ifsc_code" placeholder="" type="text" class="form-control"  value="<?php echo $staff["ifsc_code"] ?>" />
                                                            <span class="text-danger"><?php echo form_error('ifsc_code'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('bank_branch_name'); ?></label>
                                                            <input id="bank_branch" name="bank_branch" placeholder="" type="text" class="form-control"  value="<?php echo $staff["bank_branch"] ?>" />
                                                            <span class="text-danger"><?php echo form_error('bank_branch'); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }if ($sch_setting->staff_social_media) {?>
                                            <div class="tshadow mb25 bozero">
                                                <h4 class="pagetitleh2"><?php echo $this->lang->line('social_media_link'); ?>
                                                </h4>

                                                <div class="row around10">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('facebook_url'); ?></label>
                                                            <input id="bank_account_no" name="facebook" placeholder="" type="text" class="form-control"  value="<?php echo $staff["facebook"] ?>" />

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('twitter_url'); ?></label>
                                                            <input id="bank_account_no" name="twitter" placeholder="" type="text" class="form-control"  value="<?php echo $staff["twitter"] ?>" />

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('linkedin_url'); ?></label>
                                                            <input id="bank_name" name="linkedin" placeholder="" type="text" class="form-control"  value="<?php echo $staff["linkedin"] ?>" />

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('instagram_url'); ?></label>
                                                            <input id="instagram" name="instagram" placeholder="" type="text" class="form-control"  value="<?php echo $staff["instagram"] ?>" />

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }if ($sch_setting->staff_upload_documents) {?>
                                            <div id='upload_documents_hide_show'>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="tshadow bozero">
                                                            <h4 class="pagetitleh2"><?php echo $this->lang->line('upload_documents'); ?></h4>
                                                            <div class="row around10">
                                                                <div class="col-md-6">
                                                                    <table class="table">
                                                                        <tbody><tr>
                                                                                <th style="width: 10px">#</th>
                                                                                <th><?php echo $this->lang->line('title'); ?></th>
                                                                                <th><?php echo $this->lang->line('documents'); ?></th>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>1.</td>
                                                                                <td><?php echo $this->lang->line('resume'); ?></td>
                                                                                <td>
                                                                                    <input class="filestyle form-control" type='file' name='first_doc' id="doc1" >
                                                                                    <input class=" form-control" type='hidden' name='resume' value="<?php echo $staff["resume"] ?>" >
                                                                                    <span class="text-danger"><?php echo form_error('first_doc'); ?></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3.</td>
                                                                                <td><?php echo $this->lang->line('resignation_letter'); ?></td>
                                                                                <td>
                                                                                    <input class="filestyle form-control" type='file' name='third_doc' id="doc3" >
                                                                                    <input class=" form-control" type='hidden' name='resignation_letter' value="<?php echo $staff["resignation_letter"] ?>" >
                                                                                    <span class="text-danger"><?php echo form_error('third_doc'); ?></span>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <table class="table">
                                                                        <tbody><tr>
                                                                                <th style="width: 10px">#</th>
                                                                                <th><?php echo $this->lang->line('title'); ?></th>
                                                                                <th><?php echo $this->lang->line('documents'); ?></th>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2.</td>
                                                                                <td><?php echo $this->lang->line('joining_letter'); ?></td>
                                                                                <td>
                                                                                    <input class="filestyle form-control" type='file' name='second_doc' id="doc2" >
                                                                                    <input class=" form-control" type='hidden' name='joining_letter' value="<?php echo $staff["joining_letter"] ?>" >
                                                                                    <span class="text-danger"><?php echo form_error('second_doc'); ?></span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4.</td>
                                                                                <td><?php echo $this->lang->line('other_documents'); ?><input type="hidden" name='fourth_title' value="<?php echo $staff["other_document_file"] ?>" class="form-control" placeholder="Other Documents">
                                                                                </td>
                                                                                <td>
                                                                                    <input class="filestyle form-control" type='file' name='fourth_doc'  id="doc4" >
                                                                                    <input class=" form-control" type='hidden' name='other_document_file' value="<?php echo $staff["other_document_file"] ?>" >
                                                                                    <span class="text-danger"><?php echo form_error('fourth_doc'); ?></span>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
</div>

<script type="text/javascript">
    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }

    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);

        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });

        $("#btnreset").click(function () {
            $("#form1")[0].reset();
        });

    });

    function auto_fill_guardian_address() {
        if ($("#autofill_current_address").is(':checked'))
        {
            $('#current_address').val($('#guardian_address').val());
        }
    }

    function auto_fill_address() {
        if ($("#autofill_address").is(':checked'))
        {
            $('#permanent_address').val($('#current_address').val());
        }
    }

    $('input:radio[name="guardian_is"]').change(
            function () {
                if ($(this).is(':checked')) {
                    var value = $(this).val();
                    if (value == "father") {
                        $('#guardian_name').val($('#father_name').val());
                        $('#guardian_phone').val($('#father_phone').val());
                        $('#guardian_occupation').val($('#father_occupation').val());
                        $('#guardian_relation').val("Father")
                    } else if (value == "mother") {
                        $('#guardian_name').val($('#mother_name').val());
                        $('#guardian_phone').val($('#mother_phone').val());
                        $('#guardian_occupation').val($('#mother_occupation').val());
                        $('#guardian_relation').val("Mother")
                    } else {
                        $('#guardian_name').val("");
                        $('#guardian_phone').val("");
                        $('#guardian_occupation').val("");
                        $('#guardian_relation').val("")
                    }
                }
            });
</script>

<script type="text/javascript">
    $(".mysiblings").click(function () {
        $('.sibling_msg').html("");
        $('.modal_title').html('<b>' + "<?php echo $this->lang->line('sibling'); ?>" + '</b>');
        $('#mySiblingModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    });
</script>

<script type="text/javascript">
    $(document).on('change', '#sibiling_class_id', function (e) {
        $('#sibiling_section_id').html("");
        var class_id = $(this).val();
        var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "GET",
            url: base_url + "sections/getByClass",
            data: {'class_id': class_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                });
                $('#sibiling_section_id').append(div_data);
            }
        });
    });

    $(document).on('change', '#sibiling_section_id', function (e) {
        getStudentsByClassAndSection();
    });

    function getStudentsByClassAndSection() {
        $('#sibiling_student_id').html("");
        var class_id = $('#sibiling_class_id').val();
        var section_id = $('#sibiling_section_id').val();
        var student_id = '<?php echo set_value('student_id') ?>';
        var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "GET",
            url: base_url + "student/getByClassAndSection",
            data: {'class_id': class_id, 'section_id': section_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    var sel = "";
                    if (section_id == obj.section_id) {
                        sel = "selected=selected";
                    }
                    div_data += "<option value=" + obj.id + ">" + obj.firstname + " " + obj.lastname + "</option>";
                });
                $('#sibiling_student_id').append(div_data);
            }
        });
    }

    $(document).on('click', '.add_sibling', function () {
        var student_id = $('#sibiling_student_id').val();
        var base_url = '<?php echo base_url() ?>';
        if (student_id.length > 0) {
            $.ajax({
                type: "GET",
                url: base_url + "student/getStudentRecordByID",
                data: {'student_id': student_id},
                dataType: "json",
                success: function (data) {
                    $('#sibling_name').text("Sibling: " + data.firstname + " " + data.lastname);
                    $('#sibling_name_next').val(data.firstname + " " + data.lastname);
                    $('#sibling_id').val(student_id);
                    $('#father_name').val(data.father_name);
                    $('#father_phone').val(data.father_phone);
                    $('#father_occupation').val(data.father_occupation);
                    $('#mother_name').val(data.mother_name);
                    $('#mother_phone').val(data.mother_phone);
                    $('#mother_occupation').val(data.mother_occupation);
                    $('#guardian_name').val(data.guardian_name);
                    $('#guardian_relation').val(data.guardian_relation);
                    $('#guardian_address').val(data.guardian_address);
                    $('#guardian_phone').val(data.guardian_phone);
                    $('#state').val(data.state);
                    $('#city').val(data.city);
                    $('#pincode').val(data.pincode);
                    $('#current_address').val(data.current_address);
                    $('#permanent_address').val(data.permanent_address);
                    $('#guardian_occupation').val(data.guardian_occupation);
                    $("input[name=guardian_is][value='" + data.guardian_is + "']").prop("checked", true);
                    $('#mySiblingModal').modal('hide');
                }
            });
        } else {
            $('.sibling_msg').html("<div class='alert alert-danger'><?php echo $this->lang->line('no_student_selected'); ?></div>");
        }

    });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/savemode.js"></script>