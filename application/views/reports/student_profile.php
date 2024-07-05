<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">
    /*REQUIRED*/
    .carousel-row {
        margin-bottom: 10px;
    }
    .slide-row {
        padding: 0;
        background-color: #ffffff;
        min-height: 150px;
        border: 1px solid #e7e7e7;
        overflow: hidden;
        height: auto;
        position: relative;
    }
    .slide-carousel {
        width: 20%;
        float: left;
        display: inline-block;
    }
    .slide-carousel .carousel-indicators {
        margin-bottom: 0;
        bottom: 0;
        background: rgba(0, 0, 0, .5);
    }
    .slide-carousel .carousel-indicators li {
        border-radius: 0;
        width: 20px;
        height: 6px;
    }
    .slide-carousel .carousel-indicators .active {
        margin: 1px;
    }
    .slide-content {
        position: absolute;
        top: 0;
        left: 20%;
        display: block;
        float: left;
        width: 80%;
        max-height: 76%;
        padding: 1.5% 2% 2% 2%;
        overflow-y: auto;
    }
    .slide-content h4 {
        margin-bottom: 3px;
        margin-top: 0;
    }
    .slide-footer {
        position: absolute;
        bottom: 0;
        left: 20%;
        width: 78%;
        height: 20%;
        margin: 1%;
    }
    /* Scrollbars */
    .slide-content::-webkit-scrollbar {
        width: 5px;
    }
    .slide-content::-webkit-scrollbar-thumb:vertical {
        margin: 5px;
        background-color: #999;
        -webkit-border-radius: 5px;
    }
    .slide-content::-webkit-scrollbar-button:start:decrement,
    .slide-content::-webkit-scrollbar-button:end:increment {
        height: 5px;
        display: block;
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-bus"></i> <?php //echo $this->lang->line('transport'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('reports/_studentinformation'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form role="form" action="<?php echo site_url('report/student_profile') ?>" method="post" class="">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-6 col-md-3" >
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search_by_admission_date'); ?></label>
                                    <select class="form-control" name="search_type" onchange="showdate(this.value)">

                                        <?php foreach ($searchlist as $key => $search) {
                                            ?>
                                            <option value="<?php echo $key ?>" <?php
                                            if ((isset($search_type)) && ($search_type == $key)) {

                                                echo "selected";
                                            }
                                            ?>><?php echo $search ?></option>
                                                <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                </div>
                            </div>
                            <div id='date_result'>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('class'); ?><small class="req"> *</small></label>
                                    <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($classlist as $class) {
                                            ?>
                                            <option value="<?php echo $class['id'] ?>" <?php
                                            if ($class_id == $class['id']) {
                                                echo "selected =selected";
                                            }
                                            ?>><?php echo $class['class'] ?></option>
                                                    <?php
                                                    $count++;
                                                }
                                                ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('section'); ?><small class="req"> *</small></label>
                                    <select  id="section_id" name="section_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($section_list as $value) {
                                            ?>
                                            <option  <?php
                                            if ($value['section_id'] == $section_id) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $value['section_id']; ?>"><?php echo $value['section']; ?></option>
                                                <?php
                                            }
                                            ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="">
                        <div class="box-header ptbnull"></div>
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo $this->lang->line('student_profile'); ?></h3>
                        </div>
                        <div class="box-body table-responsive">
                            <div class="download_label"> <?php echo $this->lang->line('student_profile').' '.
                                            $this->customlib->get_postmessage();
                                            ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <?php if (!$adm_auto_insert) {
                                            ?>
                                            <th><?php echo $this->lang->line('admission_no'); ?></th>
                                            <?php
                                        }
                                        if ($sch_setting->roll_no) {
                                            ?>			
                                            <th><?php echo $this->lang->line('roll_number'); ?></th>
                                        <?php } ?>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('section'); ?></th>
                                        <th><?php echo $this->lang->line('first_name'); ?></th>
                                        <?php if ($sch_setting->middlename) { ?>
                                         <th><?php echo $this->lang->line('middle_name'); ?></th>
<?php } if ($sch_setting->lastname) { ?>
                                            <th><?php echo $this->lang->line('last_name'); ?></th>
                                        <?php } ?>
                                        <th><?php echo $this->lang->line('gender'); ?></th>
                                        <th><?php echo $this->lang->line('date_of_birth'); ?></th>
                                        <?php if ($sch_setting->category) { ?>
                                            <th><?php echo $this->lang->line('category'); ?></th>
                                        <?php } if ($sch_setting->religion) { ?>
                                            <th><?php echo $this->lang->line('religion'); ?></th>
                                        <?php } if ($sch_setting->cast) { ?>
                                            <th><?php echo $this->lang->line('caste'); ?></th>
                                        <?php } if ($sch_setting->mobile_no) { ?>
                                            <th><?php echo $this->lang->line('mobile_number'); ?></th>
                                        <?php } if ($sch_setting->student_email) { ?>
                                            <th><?php echo $this->lang->line('email'); ?></th>
                                        <?php } if ($sch_setting->admission_date) { ?>
                                            <th><?php echo $this->lang->line('admission_date'); ?></th>
                                        <?php } if ($sch_setting->is_blood_group) { ?>
                                            <th><?php echo $this->lang->line('blood_group'); ?></th>
                                        <?php } if ($sch_setting->is_student_house) { ?>
                                            <th><?php echo $this->lang->line('house') ?></th>
                                        <?php } if ($sch_setting->student_height) { ?>
                                            <th><?php echo $this->lang->line('height'); ?></th>
                                        <?php } if ($sch_setting->student_weight) { ?>
                                            <th><?php echo $this->lang->line('weight'); ?></th>
                                        <?php } if ($sch_setting->measurement_date) { ?>
                                            <th><?php echo $this->lang->line('measurement_date'); ?></th>
                                        <?php } ?>
                                        <th><?php echo $this->lang->line('fees_discount'); ?></th>
                                        <?php if ($sch_setting->father_name) { ?>
                                            <th><?php echo $this->lang->line('father_name'); ?></th>
                                        <?php } if ($sch_setting->father_phone) { ?>
                                            <th><?php echo $this->lang->line('father_phone'); ?></th>
                                        <?php } if ($sch_setting->father_occupation) { ?>
                                            <th><?php echo $this->lang->line('father_occupation'); ?></th>
                                        <?php } if ($sch_setting->mother_name) { ?>
                                            <th><?php echo $this->lang->line('mother_name'); ?></th>
                                        <?php } if ($sch_setting->mother_phone) { ?>
                                            <th><?php echo $this->lang->line('mother_phone'); ?></th>
                                       <?php } if ($sch_setting->mother_occupation) { ?>
                                            <th><?php echo $this->lang->line('mother_occupation'); ?></th>
                                        <?php } ?>
                                        
                                         <?php if ($sch_setting->guardian_name) { ?>
                                            <th><?php echo $this->lang->line('if_guardian_is'); ?></th>
                                        <th><?php echo $this->lang->line('guardian_name'); ?></th>
                                        <?php } if ($sch_setting->guardian_relation) { ?>
                                            <th><?php echo $this->lang->line('guardian_relation'); ?></th>
                                        <?php } if ($sch_setting->guardian_phone) { ?>
                                        <th><?php echo $this->lang->line('guardian_phone'); ?></th>
                                   <?php } if ($sch_setting->guardian_occupation) { ?>
                                        <th><?php echo $this->lang->line('guardian_occupation'); ?></th><?php } if ($sch_setting->guardian_email) { ?>
                                            <th><?php echo $this->lang->line('guardian_email'); ?></th>
                                        <?php } if ($sch_setting->guardian_address) { ?>
                                            <th><?php echo $this->lang->line('guardian_address'); ?></th>

                                        <?php } if ($sch_setting->current_address) { ?>
                                            <th><?php echo $this->lang->line('current_address'); ?></th>
                                        <?php } if ($sch_setting->permanent_address) { ?>
                                            <th><?php echo $this->lang->line('permanent_address'); ?></th>
                                        <?php } if ($sch_setting->route_list) { ?>
                                            <th><?php echo $this->lang->line('route_list'); ?></th>
                                        <?php } if ($sch_setting->hostel_id) { ?>
                                            <th><?php echo $this->lang->line('hostel_details'); ?></th>
                                        <?php } ?>
                                        <th><?php echo $this->lang->line('room_no'); ?></th>
                                        <?php if ($sch_setting->bank_account_no) { ?>
                                            <th><?php echo $this->lang->line('bank_account_number'); ?></th>
                                        <?php } if ($sch_setting->bank_name) { ?>
                                            <th><?php echo $this->lang->line('bank_name'); ?></th>
                                         <?php } if ($sch_setting->ifsc_code) { ?>
                                        <th><?php echo $this->lang->line('ifsc_code'); ?></th>
                                        <?php } if ($sch_setting->national_identification_no) { ?>
                                            <th><?php echo $this->lang->line('national_identification_number'); ?></th>
                                        <?php } if ($sch_setting->local_identification_no) { ?>
                                            <th><?php echo $this->lang->line('local_identification_number'); ?></th>
                                        <?php } if ($sch_setting->rte) { ?>
                                            <th><?php echo $this->lang->line('rte'); ?></th>
                                        <?php } if ($sch_setting->previous_school_details) { ?>
                                            <th><?php echo $this->lang->line('previous_school_details'); ?></th>
<?php } if ($sch_setting->student_note) { ?>
                                            <th><?php echo $this->lang->line('note'); ?></th>
                                    <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 0;
                                    if (!empty($resultlist)) {
                                        foreach ($resultlist as $value) {
                                            ?>
                                            <tr>
                                                <?php if (!$adm_auto_insert) {
                                                    ?>
                                                    <td><?php echo $value['admission_no']; ?></td>
        <?php } if ($sch_setting->roll_no) { ?>
                                                    <td><?php echo $value['roll_no'] ?></td>
                                                <?php } ?>
                                                <td><?php echo $value['class']; ?></td>
                                                <td><?php echo $value['section'] ?></td>
                                                <td><?php echo $value['firstname'] ?></td>
                                                 <?php if ($sch_setting->middlename) { ?>
                                                    <td><?php echo $value['middlename'] ?></td>
                                                <?php } ?>
        <?php if ($sch_setting->lastname) { ?>
                                                    <td><?php echo $value['lastname'] ?></td>
                                                <?php } ?>
                                                <td><?php echo $this->lang->line(strtolower($value['gender'])) ?></td>
                                                <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($value['dob'])); ?></td>
                                                <?php if ($sch_setting->category) { ?>
                                                    <td><?php echo $value['category']; ?></td>
                                                <?php } if ($sch_setting->religion) { ?>
                                                    <td><?php echo $value['religion']; ?></td>
                                                <?php } if ($sch_setting->cast) { ?>
                                                    <td><?php echo $value['cast']; ?></td>
                                                <?php } if ($sch_setting->mobile_no) { ?>
                                                    <td><?php echo $value['mobileno']; ?></td>
                                                <?php } if ($sch_setting->student_email) { ?>
                                                    <td><?php echo $value['email']; ?></td>
                                                <?php } if ($sch_setting->admission_date) { ?>                                                   
                                                    <td><?php if(!empty($value['admission_date'])){ echo date($this->customlib->getSchoolDateFormat(), strtotime($value['admission_date'])); } ?></td>                                                   
                                                <?php } if ($sch_setting->is_blood_group) { ?>
                                                    <td><?php echo $value['blood_group']; ?></td>
                                                <?php } if ($sch_setting->is_student_house) { ?>
                                                    <td><?php echo $value['house_name']; ?></td>
                                                <?php } if ($sch_setting->student_height) { ?>
                                                    <td><?php echo $value['height']; ?></td>
        <?php } if ($sch_setting->student_weight) { ?>
                                                    <td><?php echo $value['weight']; ?></td>
                                                    
                                                <?php } ?>
                                                
                                                <?php if ($sch_setting->measurement_date) { ?>
                                                
                                                <td><?php if((!empty($value['measurement_date'])) && $value['measurement_date'] != '0000-00-00'){ echo date($this->customlib->getSchoolDateFormat(), strtotime($value['measurement_date'])); } ?></td>
                                                
                                                <?php }  ?>
                                                 
                                                <td><?php echo $value['fees_discount']; ?></td>
                                                <?php if ($sch_setting->father_name) { ?>
                                                    <td><?php echo $value['father_name'] ?></td>
                                                <?php } if ($sch_setting->father_phone) { ?>
                                                    <td><?php echo $value['father_phone']; ?></td>
                                                <?php } if ($sch_setting->mother_occupation) { ?>
                                                    <td><?php echo $value['father_occupation']; ?></td>
                                                <?php } if ($sch_setting->mother_name) { ?>                                         <td><?php echo $value['mother_name']; ?></td>
                                                <?php } if ($sch_setting->mother_phone) { ?>
                                                    <td><?php echo $value['mother_phone']; ?></td>
                                                 <?php } if ($sch_setting->mother_occupation) { ?>
                                                    <td><?php echo $value['mother_occupation']; ?></td>
                                                <?php } if ($sch_setting->guardian_name) { ?>
                                                <td><?php echo $value['guardian_is']; ?></td>
                                                <td><?php echo $value['guardian_name']; ?></td>
                                                <?php } if ($sch_setting->guardian_relation) { ?>
                                                    <td><?php echo $value['guardian_relation']; ?></td>
                                                 <?php } if ($sch_setting->guardian_phone) { ?>
                                                <td><?php echo $value['guardian_phone']; ?></td>
                                            <?php } if ($sch_setting->guardian_occupation) { ?>
                                                <td><?php echo $value['guardian_occupation']; ?></td>
                                                <?php } if ($sch_setting->guardian_email) { ?>
                                                    <td><?php echo $value['guardian_email']; ?></td>
                                                <?php } if ($sch_setting->guardian_address) { ?>
                                                    <td><?php echo $value['guardian_address']; ?></td>
                                                <?php } if ($sch_setting->current_address) { ?>
                                                    <td><?php echo $value['current_address']; ?></td>
                                                <?php } if ($sch_setting->permanent_address) { ?>
                                                    <td><?php echo $value['permanent_address']; ?></td>
                                                <?php } if ($sch_setting->route_list) { ?>
                                                    <td><?php echo $value['route_title']; ?></td>
                                                <?php } if ($sch_setting->hostel_id) { ?>
                                                    <td><?php echo $value['hostel_name']; ?></td>
                                                <?php } ?>
                                                <td><?php echo $value['room_no']; ?></td>
                                                <?php if ($sch_setting->bank_account_no) { ?>
                                                    <td><?php echo $value['bank_account_no']; ?></td>
                                                <?php } ?>
                                                        
                                                    <?php if ($sch_setting->bank_name) {
                                                         ?>
                                                                 <td><?php echo $value['bank_name']; ?></td>
                                                             <?php
                                                     }?>
                                                    <?php if ($sch_setting->ifsc_code) {
                                                         ?>
                                                                 <td><?php echo $value['ifsc_code']; ?></td>
                                                             <?php
                                                     }?>
                                                     
                                                <?php if ($sch_setting->national_identification_no) { ?>
                                                    <td><?php echo $value['adhar_no']; ?></td>
                                                <?php } if ($sch_setting->local_identification_no) { ?>
                                                    <td><?php echo $value['samagra_id']; ?></td>
                                                <?php } if ($sch_setting->rte) { ?>
                                                    <td><?php echo $value['rte']; ?></td>
                                                <?php } if ($sch_setting->previous_school_details) { ?>
                                                    <td><?php echo $value['previous_school']; ?></td>
        <?php } if ($sch_setting->student_note) { ?>
                                                    <td><?php echo $value['note']; ?></td>
                                            <?php } ?>

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
            </div>
        </div>   
    </div>  
</section>
</div>

<script>
<?php
if ($search_type == 'period') {
    ?>

        $(document).ready(function () {
            showdate('period');
        });

    <?php
}
?>
</script>
<script>
    $(document).on('change', '#class_id', function (e) {

        $('#section_id').html("");
        var class_id = $(this).val();

        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        var url = "";
        $.ajax({
            type: "GET",
            url: baseurl + "sections/getByClass",
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
</script>