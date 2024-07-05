<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php //echo $this->lang->line('student_information'); ?> <small><?php //echo $this->lang->line('student1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="">
                        <div class="pull-right box-tools"></div>
                    </div>
                
                    <form action="<?php echo site_url("user/user/edit") ?>" id="editform" name="editform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="tshadow mb25 bozero">
                                <h3 class="pagetitleh2"> <?php echo $this->lang->line('edit_student'); ?></h3>
                                <div class="around10">
                                 
                                    <?php if ($this->session->flashdata('msg')) {?>
                                    <?php 
                                        echo $this->session->flashdata('msg');
                                        $this->session->unset_userdata('msg');
                                    ?>
                                <?php }?>
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <input type="hidden" name="student_id" value="<?php echo set_value('id', $student['id']); ?>">

                                    <div class="row">
                                    <?php
if (findSelected($inserted_fields, 'firstname')) {
    ?>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('first_name'); ?></label><small class="req"> *</small>
                                                <input id="firstname" name="firstname" placeholder="" type="text" class="form-control"  value="<?php echo set_value('firstname', $student['firstname']); ?>" />
                                                <input type="hidden" name="studentid" value="<?php echo $student["id"] ?>">
                                                <span class="text-danger"><?php echo form_error('firstname'); ?></span>
                                            </div>
                                        </div>
<?php
}
?> 
 <?php
if (findSelected($inserted_fields, 'middlename') && ($sch_setting_detail->middlename)) {
    ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('middle_name'); ?></label>
                                                <input id="middlename" name="middlename" placeholder="" type="text" class="form-control"  value="<?php echo set_value('middlename', $student['middlename']); ?>" />
                                                <span class="text-danger"><?php echo form_error('middlename'); ?></span>
                                            </div>
                                        </div>

<?php
}
?>
 <?php
if (findSelected($inserted_fields, 'lastname') && ($sch_setting_detail->lastname)) {
    ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('last_name'); ?></label>
                                                <input id="lastname" name="lastname" placeholder="" type="text" class="form-control"  value="<?php echo set_value('lastname', $student['lastname']); ?>" />
                                                <span class="text-danger"><?php echo form_error('lastname'); ?></span>
                                            </div>
                                        </div>

<?php
}
?>

 <?php
if (findSelected($inserted_fields, 'gender')) {
    ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile"> <?php echo $this->lang->line('gender'); ?> </label><small class="req"> *</small>
                                                <select class="form-control" name="gender">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
foreach ($genderList as $key => $value) {
        ?>
                                                        <option  value="<?php echo $key; ?>" <?php if ($student['gender'] == $key) {
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

<?php
}
?>
                                         <?php
if (findSelected($inserted_fields, 'dob')) {
    ?>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('date_of_birth'); ?></label><small class="req"> *</small>
                                                <input id="dob" name="dob" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('dob', date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']))); ?>" />
                                                <span class="text-danger"><?php echo form_error('dob'); ?></span>
                                            </div>
                                        </div>
<?php
}
?>
                                    </div>
                                    <div class="row">

 <?php
if (findSelected($inserted_fields, 'category') && ($sch_setting_detail->category)) {
    ?>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('category'); ?></label>
                                                <select  id="category_id" name="category_id" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
foreach ($categorylist as $category) {
        ?>
                                                        <option value="<?php echo $category['id'] ?>" <?php if ($student['category_id'] == $category['id']) {
            echo "selected =selected";
        }
        ?>><?php echo $category['category']; ?></option>
                                                        <?php
$count++;
    }
    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('category_id'); ?></span>
                                            </div>
                                        </div>
<?php
}
?>

 <?php
if (findSelected($inserted_fields, 'religion')  && ($sch_setting_detail->religion)) {
    ?>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('religion'); ?></label>
                                                <input id="religion" name="religion" placeholder="" type="text" class="form-control"  value="<?php echo set_value('religion', $student['religion']); ?>" />
                                                <span class="text-danger"><?php echo form_error('religion'); ?></span>
                                            </div>
                                        </div>

<?php
}
?>

 <?php
if (findSelected($inserted_fields, 'cast')  && ($sch_setting_detail->cast)) {
    ?>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('caste'); ?></label>
                                                <input id="cast" name="cast" placeholder="" type="text" class="form-control"  value="<?php echo set_value('cast', $student['cast']); ?>" />
                                                <span class="text-danger"><?php echo form_error('cast'); ?></span>
                                            </div>
                                        </div>

<?php
}
?>
 <?php
if (findSelected($inserted_fields, 'mobile_no')  && ($sch_setting_detail->mobile_no)) {
    ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('mobile_number'); ?></label>
                                                <input id="mobileno" name="mobileno" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mobileno', $student['mobileno']); ?>" />
                                                <span class="text-danger"><?php echo form_error('mobileno'); ?></span>
                                            </div>
                                        </div>

<?php
}
?>
                                        <?php
if (findSelected($inserted_fields, 'student_email') && ($sch_setting_detail->student_email)) {
    ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('email'); ?></label>
                                                <input id="email" name="email" placeholder="" type="text" class="form-control"  value="<?php echo set_value('email', $student['email']); ?>" />
                                                <span class="text-danger"><?php echo form_error('email'); ?></span>
                                            </div>
                                        </div>

<?php
}
?>



                                    </div>
                                    <div class="row">

 <?php
if (findSelected($inserted_fields, 'admission_date')  && ($sch_setting_detail->admission_date)) {
    ?>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('admission_date'); ?></label>
                                                <input id="admission_date" name="admission_date" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('admission_date', date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['admission_date']))); ?>" readonly="readonly" />
                                                <span class="text-danger"><?php echo form_error('admission_date'); ?></span>
                                            </div>
                                        </div>
<?php
}
?>

 <?php
if (findSelected($inserted_fields, 'student_photo')  && ($sch_setting_detail->student_photo)) {
    ?>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo $this->lang->line('student_photo'); ?></label>
                                                <input class="filestyle form-control" type='file' name='file' id="file" size='20' />
                                            </div>
                                            <span class="text-danger"><?php echo form_error('file'); ?></span>
                                        </div>
<?php
}
?>

 <?php
if (findSelected($inserted_fields, 'is_blood_group')  && ($sch_setting_detail->is_blood_group)) {
    ?>

                                        <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('blood_group'); ?></label>
                                                           <?php

    ?>
                                                            <select class="form-control" rows="3" placeholder="" name="blood_group">
                                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                                <?php foreach ($bloodgroup as $bgkey => $bgvalue) {
        ?>
                                                         <option value="<?php echo $bgvalue ?>" <?php if ($bgvalue == $student["blood_group"]) {echo "selected";}?>><?php echo $bgvalue ?></option>

                                                               <?php }?>
                                                            </select>

                                                            <span class="text-danger"><?php echo form_error('house'); ?></span>
                                                        </div>
                                                    </div>
<?php
}
?>
                                             <?php
if (findSelected($inserted_fields, 'is_student_house') && ($sch_setting_detail->is_student_house) ) {
    ?>

                                         <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('house') ?></label>
                                                            <select class="form-control" rows="3" placeholder="" name="is_student_house">
                                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                                <?php foreach ($houses as $hkey => $hvalue) {
        ?>
                                                         <option value="<?php echo $hvalue["id"] ?>" <?php if ($hvalue["id"] == $student["school_house_id"]) {echo "selected";}?> ><?php echo $hvalue["house_name"] ?></option>

                                                               <?php }?>
                                                            </select>
                                                            <span class="text-danger"><?php echo form_error('is_student_house'); ?></span>
                                                        </div>
                                                    </div>
<?php
}
?>

 <?php
if (findSelected($inserted_fields, 'student_height')  && ($sch_setting_detail->student_height)) {
    ?>

                                                    <div class="col-md-3 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('height'); ?></label>
                                                           <?php

    ?>
                                                           <input type="text" value="<?php echo $student["height"] ?>" name="height" class="form-control" value="<?php echo set_value('height', $student['height']); ?>">
                                                            <span class="text-danger"><?php echo form_error('height'); ?></span>
                                                        </div>
                                                    </div>
<?php
}
?>


 <?php
if (findSelected($inserted_fields, 'student_weight')  && ($sch_setting_detail->student_weight)) {
    ?>
                                                    <div class="col-md-3 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('weight'); ?></label>
                                                           <?php

    ?>
                                                           <input type="text" value="<?php echo $student["weight"] ?>" name="weight" class="form-control" value="<?php echo set_value('weight', $student['weight']); ?>">
                                                            <span class="text-danger"><?php echo form_error('weight'); ?></span>
                                                        </div>
                                                    </div>

<?php
}
?>
                                                    <?php
if (findSelected($inserted_fields, 'measurement_date')  && ($sch_setting_detail->measurement_date)) {
    ?>

                                                    <div class="col-md-3 col-xs-12">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('measurement_date'); ?></label>

 <input id="measure_date" name="measurement_date" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('measure_date', date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['measurement_date']))); ?>" readonly="readonly"/>
                                                            <span class="text-danger"><?php echo form_error('measurement_date'); ?></span>
                                                        </div>
                                                    </div>
<?php
}
?>


                                    </div>
                                    <div class="row">
                                <?php
echo display_custom_fields_student_penal_edit_profile('students', $student['id']);
?>
                                    </div>
                                </div>
                            </div>
                            <?php 
                            if((findSelected($inserted_fields, 'father_name')  && ($sch_setting_detail->father_name)) || (findSelected($inserted_fields, 'father_phone')  && ($sch_setting_detail->father_phone)) ||
                              (findSelected($inserted_fields, 'father_occupation')  && ($sch_setting_detail->father_occupation)) ||
                              (findSelected($inserted_fields, 'father_pic')  && ($sch_setting_detail->father_pic)) ||
                              (findSelected($inserted_fields, 'mother_name')  && ($sch_setting_detail->mother_name)) ||
                              (findSelected($inserted_fields, 'mother_phone')  && ($sch_setting_detail->mother_phone)) ||
                              (findSelected($inserted_fields, 'mother_occupation')  && ($sch_setting_detail->mother_occupation)) ||
                              (findSelected($inserted_fields, 'mother_pic')  && ($sch_setting_detail->mother_pic)) ||
                              (findSelected($inserted_fields, 'if_guardian_is')) ||
                              (findSelected($inserted_fields, 'guardian_name')    && ($sch_setting_detail->guardian_name)) ||
                              (findSelected($inserted_fields, 'guardian_relation')   && ($sch_setting_detail->guardian_relation)) ||
                              (findSelected($inserted_fields, 'guardian_phone')  && ($sch_setting_detail->guardian_phone)) ||
                              (findSelected($inserted_fields, 'guardian_occupation') && ($sch_setting_detail->guardian_occupation)) ||
                              (findSelected($inserted_fields, 'guardian_email')  && ($sch_setting_detail->guardian_email)) ||
                              (findSelected($inserted_fields, 'guardian_pic')  && ($sch_setting_detail->guardian_pic)) || 
                              (findSelected($inserted_fields, 'guardian_address')  && ($sch_setting_detail->guardian_address))

                               ){
                            ?>
                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2"><?php echo $this->lang->line('parent_guardian_detail'); ?></h4>
                                <div class="around10">
                                    <div class="row">
                                     <?php
if (findSelected($inserted_fields, 'father_name')  && ($sch_setting_detail->father_name)) {
    ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('father_name'); ?></label>
                                                <input id="father_name" name="father_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_name', $student['father_name']); ?>" />
                                                <span class="text-danger"><?php echo form_error('father_name'); ?></span>
                                            </div>
                                        </div>

<?php
}
?>
                                         <?php
if (findSelected($inserted_fields, 'father_phone')  && ($sch_setting_detail->father_phone)) {
    ?>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('phone_number'); ?></label>
                                                <input id="father_phone" name="father_phone" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_phone', $student['father_phone']); ?>" />
                                                <span class="text-danger"><?php echo form_error('father_phone'); ?></span>
                                            </div>
                                        </div>
<?php
}
?>
                                         <?php
if (findSelected($inserted_fields, 'father_occupation')  && ($sch_setting_detail->father_occupation)) {
    ?>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('father_occupation'); ?></label>
                                                <input id="father_occupation" name="father_occupation" placeholder="" type="text" class="form-control"  value="<?php echo set_value('father_occupation', $student['father_occupation']); ?>" />
                                                <span class="text-danger"><?php echo form_error('father_occupation'); ?></span>
                                            </div>
                                        </div>
<?php
}
?>
                                         <?php
if (findSelected($inserted_fields, 'father_pic')  && ($sch_setting_detail->father_pic)) {
    ?>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo $this->lang->line('father_photo'); ?></label>
                                                <div><input class="filestyle form-control" type='file' name='father_pic' id="file" size='20' />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('father_pic'); ?></span></div>
                                        </div>
<?php
}
?>
                                    </div>
                                    <div class="row">
                                      <?php
if (findSelected($inserted_fields, 'mother_name')  && ($sch_setting_detail->mother_name)) {
    ?>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_name'); ?></label>
                                                <input id="mother_name" name="mother_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_name', $student['mother_name']); ?>" />
                                                <span class="text-danger"><?php echo form_error('mother_name'); ?></span>
                                            </div>
                                        </div>
<?php
}
?>
                                         <?php
if (findSelected($inserted_fields, 'mother_phone')  && ($sch_setting_detail->mother_phone)) {
    ?>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_phone'); ?></label>
                                                <input id="mother_phone" name="mother_phone" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_phone', $student['mother_phone']); ?>" />
                                                <span class="text-danger"><?php echo form_error('mother_phone'); ?></span>
                                            </div>
                                        </div>
<?php
}
?>
                                        <?php
if (findSelected($inserted_fields, 'mother_occupation')  && ($sch_setting_detail->mother_occupation)) {
    ?>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_occupation'); ?></label>
                                                <input id="mother_occupation" name="mother_occupation" placeholder="" type="text" class="form-control"  value="<?php echo set_value('mother_occupation', $student['mother_occupation']); ?>" />
                                                <span class="text-danger"><?php echo form_error('mother_occupation'); ?></span>
                                            </div>
                                        </div>
<?php
}
?>
                                         <?php
if (findSelected($inserted_fields, 'mother_pic')  && ($sch_setting_detail->mother_pic)) {
    ?>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo $this->lang->line('mother_photo'); ?></label>
                                                <div><input class="filestyle form-control" type='file' name='mother_pic' id="file" size='20' />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('mother_pic'); ?></span></div>
                                        </div>
<?php
}
?>

                                    </div>
                                    <div class="row">
                                         <?php
if (findSelected($inserted_fields, 'if_guardian_is')) {
    ?>

                                        <div class="form-group col-md-12">
                                            <label><?php echo $this->lang->line('if_guardian_is'); ?></label><small class="req"> *</small>&nbsp;&nbsp;&nbsp;
                                            <label class="radio-inline">
                                                <input type="radio" name="guardian_is"  <?php if ($student['guardian_is'] == "father") {
        echo "checked";
    }
    ?> value="father" > <?php echo $this->lang->line('father'); ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="guardian_is" <?php if ($student['guardian_is'] == "mother") {
        echo "checked";
    }
    ?> value="mother"> <?php echo $this->lang->line('mother'); ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="guardian_is" <?php if ($student['guardian_is'] == "other") {
        echo "checked";
    }
    ?> value="other"> <?php echo $this->lang->line('other'); ?>
                                            </label>
                                              <span class="text-danger"><?php echo form_error('guardian_is'); ?></span>
                                        </div>
<?php
}
?>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                 <?php
                                          
if (findSelected($inserted_fields, 'guardian_name')    && ($sch_setting_detail->guardian_name)) {
    ?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_name'); ?></label><small class="req"> *</small>
                                                        <input id="guardian_name" name="guardian_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('guardian_name', $student['guardian_name']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('guardian_name'); ?></span>
                                                    </div>
                                                </div>

<?php
}
?>
                                             <?php
if (findSelected($inserted_fields, 'guardian_relation')   && ($sch_setting_detail->guardian_relation)) {
    ?>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_relation'); ?></label>
                                                        <input id="guardian_relation" name="guardian_relation" placeholder="" type="text" class="form-control"  value="<?php echo set_value('guardian_relation', $student['guardian_relation']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('guardian_relation'); ?></span>
                                                    </div>
                                                </div>
<?php
}
?>

                                            </div>
                                            <div class="row">
                                                 <?php
if (findSelected($inserted_fields, 'guardian_phone')  && ($sch_setting_detail->guardian_phone)) {
    ?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_phone'); ?></label><small class="req"> *</small>
                                                        <input id="guardian_phone" name="guardian_phone" placeholder="" type="text" class="form-control"  value="<?php echo set_value('guardian_phone', $student['guardian_phone']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('guardian_phone'); ?></span>
                                                    </div>
                                                </div>

<?php
}
?>
                                                 <?php
if (findSelected($inserted_fields, 'guardian_occupation') && ($sch_setting_detail->guardian_occupation)) {
    ?>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_occupation'); ?></label>
                                                        <input id="guardian_occupation" name="guardian_occupation" placeholder="" type="text" class="form-control"  value="<?php echo set_value('guardian_occupation', $student['guardian_occupation']); ?>" />
                                                        <span class="text-danger"><?php echo form_error('guardian_occupation'); ?></span>
                                                    </div>
                                                </div>
<?php
}
?>
                                            </div>
                                        </div>
                                        <?php
if (findSelected($inserted_fields, 'guardian_email')  && ($sch_setting_detail->guardian_email)) {
    ?>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_email'); ?></label>
                                                <input id="guardian_email" name="guardian_email" placeholder="" type="text" class="form-control"  value="<?php echo set_value('guardian_email', $student['guardian_email']); ?>" />
                                                <span class="text-danger"><?php echo form_error('guardian_email'); ?></span>
                                            </div>

                                        </div>
<?php
}
?>
                                       <?php
if (findSelected($inserted_fields, 'guardian_pic')  && ($sch_setting_detail->guardian_pic)) {
    ?>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile"><?php echo $this->lang->line('guardian_photo'); ?></label>
                                                <div><input class="filestyle form-control" type='file' name='guardian_pic' id="file" size='20' />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('guardian_pic'); ?></span>
                                            </div>
                                        </div>
<?php
}
?>
                                        <?php
if (findSelected($inserted_fields, 'guardian_address')  && ($sch_setting_detail->guardian_address)) {
    ?>
                                        <div class="col-md-6">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_address'); ?></label>
                                            <textarea id="guardian_address" name="guardian_address" placeholder="" class="form-control" rows="4"><?php echo set_value('guardian_address', $student['guardian_address']); ?></textarea>
                                            <span class="text-danger"><?php echo form_error('guardian_address'); ?></span>
                                        </div>

<?php
}
?>

                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                            <div class="tshadow mb25 bozero">
                                <h3 class="pagetitleh2"><?php echo $this->lang->line('address_details'); ?></h3>
                                <div class="around10">
                                    <div class="row">
                                     <?php
if (findSelected($inserted_fields, 'current_address')  && ($sch_setting_detail->current_address)) {
    ?>

                                        <div class="col-md-6">
                                            <label>
                                                <input type="checkbox" id="autofill_current_address" onclick="return auto_fill_guardian_address();">
                                                <?php echo $this->lang->line('if_guardian_address_is_current_address'); ?>
                                            </label>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('current_address'); ?></label>
                                                <textarea id="current_address" name="current_address" placeholder=""  class="form-control" ><?php echo set_value('current_address', $student['current_address']); ?></textarea>
                                                <span class="text-danger"><?php echo form_error('current_address'); ?></span>
                                            </div>
                                            <div class="checkbox">
                                            </div>
                                        </div>
<?php
}
?>
                                      <?php
if (findSelected($inserted_fields, 'permanent_address')  && ($sch_setting_detail->permanent_address)) {
    ?>

                                        <div class="col-md-6">
                                            <label>
                                                <input type="checkbox" id="autofill_address"onclick="return auto_fill_address();">
                                                <?php echo $this->lang->line('if_permanent_address_is_current_address'); ?>
                                            </label>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('permanent_address'); ?></label>
                                                <textarea id="permanent_address" name="permanent_address" placeholder="" class="form-control"><?php echo set_value('permanent_address', $student['permanent_address']) ?></textarea>
                                                <span class="text-danger"><?php echo form_error('permanent_address', $student['permanent_address']); ?></span>
                                            </div>
                                        </div>
<?php
}
?>

                                    </div>
                                </div>
                            </div>
                            <div class="tshadow bozero">
                                <h3 class="pagetitleh2"><?php echo $this->lang->line('miscellaneous_details'); ?></h3>
                                <div class="around10">

                                    <div class="row">
                                         <?php
if (findSelected($inserted_fields, 'bank_account_no')  && ($sch_setting_detail->bank_account_no)) {
    ?>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('bank_account_number'); ?></label>
                                                <input id="bank_account_no" name="bank_account_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('bank_account_no', $student['bank_account_no']); ?>" />
                                                <span class="text-danger"><?php echo form_error('bank_account_no'); ?></span>
                                            </div>
                                        </div>
<?php
}
?>
                                         <?php
if (findSelected($inserted_fields, 'bank_name')  && ($sch_setting_detail->bank_name)) {
    ?>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('bank_name'); ?></label>
                                                <input id="bank_name" name="bank_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('bank_name', $student['bank_name']); ?>" />
                                                <span class="text-danger"><?php echo form_error('bank_name'); ?></span>
                                            </div>
                                        </div>
<?php
}
?>
                                         <?php
if (findSelected($inserted_fields, 'ifsc_code')  && ($sch_setting_detail->ifsc_code)) {
    ?>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('ifsc_code'); ?></label>
                                                <input id="ifsc_code" name="ifsc_code" placeholder="" type="text" class="form-control"  value="<?php echo set_value('ifsc_code', $student['ifsc_code']); ?>" />
                                                <span class="text-danger"><?php echo form_error('ifsc_code'); ?></span>
                                            </div>
                                        </div>
<?php
}
?>
                                    </div>

                                    <div class="row">
                                         <?php

if (findSelected($inserted_fields, 'national_identification_no')  && ($sch_setting_detail->national_identification_no)) {
    ?>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">
                                                    <?php echo $this->lang->line('national_identification_number'); ?>
                                                </label>
                                                <input id="national_identification_no" name="adhar_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('adhar_no', $student['adhar_no']); ?>" />
                                                <span class="text-danger"><?php echo form_error('adhar_no'); ?></span>
                                            </div>
                                        </div>
<?php
}
?>
                                        <?php
if (findSelected($inserted_fields, 'local_identification_no')  && ($sch_setting_detail->local_identification_no)) {
    ?>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">
                                                    <?php echo $this->lang->line('local_identification_number'); ?>
                                                </label>
                                                <input id="local_identification_no" name="samagra_id" placeholder="" type="text" class="form-control"  value="<?php echo set_value('samagra_id', $student['samagra_id']); ?>" />
                                                <span class="text-danger"><?php echo form_error('samagra_id'); ?></span>
                                            </div>
                                        </div>
<?php
}
?>
                                         <?php
if (findSelected($inserted_fields, 'rte')  && ($sch_setting_detail->rte)) {
    ?>

                                        <div class="col-md-4">
                                            <label><?php echo $this->lang->line('rte'); ?></label>
                                            <div class="radio" style="margin-top: 2px;">
                                                <label><input class="radio-inline" type="radio" name="rte" value="Yes"  <?php
echo set_value('rte', $student['rte']) == "Yes" ? "checked" : "";
    ?>  ><?php echo $this->lang->line('yes'); ?></label>
                                                <label><input class="radio-inline" type="radio" name="rte" value="No" <?php
echo set_value('rte', $student['rte']) == "No" ? "checked" : "";
    ?>  ><?php echo $this->lang->line('no'); ?></label>
                                            </div>
                                            <span class="text-danger"><?php echo form_error('rte'); ?></span>
                                        </div>
<?php
}
?>

 
                                    </div>
                                    <div class="row">
                                        <?php
if (findSelected($inserted_fields, 'previous_school_details')  && ($sch_setting_detail->previous_school_details)) {
    ?>
                                        <div class="col-md-12">
                                         <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('previous_school_details'); ?></label>
                                                <textarea class="form-control" rows="3" placeholder="" name="previous_school"><?php echo set_value('previous_school', $student['previous_school']); ?></textarea>
                                                <span class="text-danger"><?php echo form_error('previous_school'); ?></span>
                                            </div>
                                        </div>
<?php
}
?> 
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
<?php

function findSelected($inserted_fields, $find)
{
    foreach ($inserted_fields as $inserted_key => $inserted_value) {
        if ($find == $inserted_value->name && $inserted_value->status) {
            return true;
        }

    }
    return false;

}

?>

<script type="text/javascript">
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