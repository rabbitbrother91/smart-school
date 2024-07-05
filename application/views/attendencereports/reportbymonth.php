<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('attendance'); ?> <small><?php echo $this->lang->line('by_date1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('attendencereports/_attendance');?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form id='form1' action="<?php echo site_url('attendencereports/reportbymonth') ?>"  method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php
if ($this->session->flashdata('msg')) {
    echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg');
}
?>
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($classlist as $class) {
    ?>
                                                <option value="<?php echo $class['id'] ?>" <?php
if (set_value('class_id') == $class['id']) {
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">

                                            <?php echo $this->lang->line('month') ?>
                                        </label><small class="req"> *</small>
                                        <select  id="month" name="month" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($monthlist as $m_key => $month) {
    ?>
                                                <option value="<?php echo $m_key ?>" <?php echo set_select('month', $m_key, set_value('month')) ?>><?php echo $month; ?></option>
                                                <?php
}
?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('month'); ?></span>
                                    </div>
                                </div>
                                      <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">

                                            <?php echo $this->lang->line('subject') ?>
                                        </label>
                                        <select  id="subject_id" name="subject_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <?php
if (isset($resultlist)) {
    ?>

                        <div class="">
                            <div class="box-header ptbnull"></div>
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('student_period_attendance'); ?> </h3>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <div class="box-body">
                                <?php
if (!empty($resultlist)) {
        ?>
                                    <div class="download_label"><?php echo $this->lang->line('student_period_attendance'); ?>  </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover table stripped attendance_table example" >
                                            <thead>
                                                <tr>
                                                    <th>Student</th>
                                                    <?php
for ($i = 1; $i <= $no_of_days; $i++) {
            ?>
                                                        <th class="text text-center">
                                                            <?php
 echo sprintf("%02d", $i);
            ?>
                                                        </th>
                                                        <?php
}
        ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
if (!empty($resultlist['class_students'])) {
            foreach ($resultlist['class_students'] as $student_key => $student_value) {
                ?>
                                                        <tr>
                                                            <td>
                    <?php echo $this->customlib->getFullName($student_value['firstname'], $student_value['middlename'], $student_value['lastname'], $sch_setting->middlename, $sch_setting->lastname) . ' (' . $student_value['admission_no'] . ')'; ?></td>
                                                            <?php
for ($i = 1; $i <= $no_of_days; $i++) {
    
     $i =  sprintf("%02d", $i);
     
                    ?>
                                                                <td class="text text-center">
                                                                    <?php
if (!empty($resultlist['students_attendances'][$i]['subjects'])) {
                        $students_attendance_list = getAttendance($resultlist['students_attendances'][$i]['students'], $student_value['id']);

                        $count = 1;
                        foreach ($resultlist['students_attendances'][$i]['subjects'] as $subject_loop_key => $subject_loop_value) {
                            ?>
                                                                            <div class="list-group" style="width: 180px;">
                                                                                <?php
echo $subject_loop_value->name;
                            if ($subject_loop_value->code != '') {
                                echo "  (" . $subject_loop_value->code . ") ";
                            }
                            echo "<br/>";
                            echo $subject_loop_value->time_from . " - " . $subject_loop_value->time_to;
                            echo "<br/>";

                            if ($students_attendance_list->{"attendence_type_id_" . $count} == "") {
                                ?>
                                                                                    <span class="label label-danger">N/A</span>
                                                                                    <?php
} else {
                                echo " ";
                                echo getattendencetype($attendencetypeslist, $students_attendance_list->{"attendence_type_id_" . $count});
                            }
                            ?>
                                                                            </div>
                                                                            <?php
$count++;
                        }
                    } else {
                        ?>
                                                                        <span class="label label-danger">N/A</span>
                                                                        <?php
}
                    ?>
                                                                </td>
                                                                <?php
}
                ?>
                                                        </tr>
                                                        <?php
}
        }
        ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php
} else {
        ?>
                                    <div class="alert alert-info">No student admitted in this Class-Section</div>
                                    <?php
}
    ?>
                            </div>
                        </div>
                    </div>
                    <?php
}
?>
                </section>
            </div>

            <?php

function getAttendance($array, $student_session_id)
{
    if (!empty($array)) {
        return $array[$student_session_id];
    }
}

function getattendencetype($attendencetype, $find)
{
    foreach ($attendencetype as $attendencetype_key => $attendencetype_value) {
        if ($attendencetype_value['id'] == $find) {
            return $attendencetype_value['key_value'];
        }
    }
    return false;
}
?>
            <script type="text/javascript">

                $(document).ready(function () {
                    var section_id_post = "<?php echo set_value('section_id'); ?>";
                    var class_id_post = "<?php echo set_value('class_id'); ?>";
                    var date_post = "<?php echo set_value('date'); ?>";
                    var subject_id = "<?php echo set_value('subject_id'); ?>";
                    var subject_timetable_id = "<?php echo set_value('subject_timetable_id', 0); ?>";
                    populateSection(section_id_post, class_id_post);
                    populateSubject(class_id_post,section_id_post,subject_id);

                    function populateSection(section_id_post, class_id_post) {
                        if (section_id_post != "" && class_id_post != "") {

                            $('#section_id').html("");

                            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                            $.ajax({
                                type: "GET",
                                url: baseurl + "sections/getByClass",
                                data: {'class_id': class_id_post},
                                dataType: "json",
                                success: function (data) {
                                    $.each(data, function (i, obj)
                                    {
                                        var select = "";
                                        if (section_id_post == obj.section_id) {
                                            var select = "selected=selected";
                                        }
                                        div_data += "<option value=" + obj.section_id + " " + select + ">" + obj.section + "</option>";
                                    });
                                    $('#section_id').append(div_data);
                                }
                            });
                        }
                    }

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

   function populateSubject(class_id_post,section_id_post,subject_id_post) {

                        if (section_id_post != "" && class_id_post != "") {
                             $('#subject_id').html("");

                            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                            $.ajax({
                                type: "POST",
                                url: baseurl + "admin/subjectgroup/getAllSubjectByClassandSection",
                            data: {'class_id': class_id_post,'section_id':section_id_post},
                                dataType: "json",
                                success: function (data) {
                                    $.each(data, function (i, obj)
                                    {
                                        var select = "";
                                        if (subject_id_post == obj.subject_id) {
                                            var select = "selected=selected";
                                        }
                                        
                                        var code ='';
                                        if(obj.subject_code){
                                            code = " (" + obj.subject_code + ") ";
                                        }
                        
                                        div_data += "<option value=" + obj.subject_id + " " + select + ">" + obj.subject_name + code +"</option>";
                                    });
                                    $('#subject_id').append(div_data);
                                }
                            });
                        }
                    }

                    $(document).on('change', '#section_id', function (e) {
                        $('#subject_id').html("");
                        let class_id = $('#class_id').val();
                        let section_id = $(this).val();
                        populateSubject(class_id,section_id,0);

                    });
                });
            </script>