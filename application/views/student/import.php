<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info" style="padding:5px;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="pull-right box-tools">
                            <a href="<?php echo site_url('student/exportformat') ?>">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-download"></i> <?php echo $this->lang->line('download_sample_import_file'); ?></button>
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {
    ?> <div>  <?php echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg'); ?> </div> <?php }?>
                        <br/>
                        1. <?php echo $this->lang->line('import_student_step1'); ?>
                      <br/>

                        2. <?php echo $this->lang->line('import_student_step2'); ?> <br/>
                        3. <?php echo $this->lang->line('import_student_step3'); ?>
                        <br/>
                        4. <?php echo $this->lang->line('import_student_step4'); ?>
                        <br/>

                        5. <?php echo $this->lang->line('import_student_step5'); ?><br/>

                        6. <?php echo $this->lang->line('import_student_step6'); ?><br/>

                        7. <?php echo $this->lang->line('import_student_step7'); ?><br/>

                        8. <?php echo $this->lang->line('import_student_step8'); ?><br/>

                        9. <?php echo $this->lang->line('import_student_step9'); ?>
                        <hr/></div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="sampledata">
                            <thead>
                                <tr>
                                    <?php
foreach ($fields as $key => $value) {
    echo $value;

    if ($value == 'adhar_no') {
        $value = "national_identification_no";
    }

    if ($value == 'samagra_id') {
        $value = "local_identification_no";
    }

    if ($value == 'firstname') {
        $value = "first_name";
    }

    if ($value == 'middlename') {
        $value = "middle_name";
    }

    if ($value == 'lastname') {
        $value = "last_name";
    }

    if ($value == 'guardian_is') {
        $value = "if_guardian_is";
    }

    if ($value == 'dob') {
        $value = "date_of_birth";
    }

    if ($value == 'category_id') {
        $value = "category";
    }

    if ($value == 'school_house_id') {
        $value = "house";
    }

    if ($value == 'mobileno') {
        $value = "mobile_no";
    }

    if ($value == 'previous_school') {
        $value = "previous_school_details";
    }
    $add = "";

    if (($value == "admission_no") || ($value == "firstname") || ($value == "date_of_birth") || ($value == "if_guardian_is") || ($value == "gender") || ($value == "guardian_name") || ($value == "guardian_phone")) {
        $add = "<span class=text-red>*</span>";
    }
    ?>
                                        <th><?php echo $add . "<span>" . $this->lang->line($value) . "</span>"; ?></th>
                                    <?php }?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php foreach ($fields as $key => $value) {
    ?>
                                        <td><?php echo $this->lang->line('sample_data'); ?></td>
                                    <?php }?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr/>

                    <form action="<?php echo site_url('student/import') ?>"  id="employeeform" name="employeeform" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($classlist as $class) {
    ?>
                                                <option value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                                <?php
$count++;
}
?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('select_csv_file'); ?></label><small class="req"> *</small>
                                        <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' />
                                            <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                    </div></div>
                                <div class="col-md-6 pt20">
                                    <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('import_student'); ?></button>
                                </div>

                            </div>
                        </div>
                    </form>
                    <div>
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
                    $("#sampledata").DataTable({
                        searching: false,
                        ordering: false,
                        paging: false,
                        bSort: false,
                        info: false, });

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
                });
            </script>