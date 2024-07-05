<style type="text/css">
    .radio {
        padding-left: 20px;
    }

    .radio label {
        display: inline-block;
        vertical-align: middle;
        position: relative;
        padding-left: 5px;
    }

    .radio label::before {
        content: "";
        display: inline-block;
        position: absolute;
        width: 17px;
        height: 17px;
        left: 0;
        margin-left: -20px;
        border: 1px solid #cccccc;
        border-radius: 50%;
        background-color: #fff;
        -webkit-transition: border 0.15s ease-in-out;
        -o-transition: border 0.15s ease-in-out;
        transition: border 0.15s ease-in-out;
    }

    .radio label::after {
        display: inline-block;
        position: absolute;
        content: " ";
        width: 11px;
        height: 11px;
        left: 3px;
        top: 3px;
        margin-left: -20px;
        border-radius: 50%;
        background-color: #555555;
        -webkit-transform: scale(0, 0);
        -ms-transform: scale(0, 0);
        -o-transform: scale(0, 0);
        transform: scale(0, 0);
        -webkit-transition: -webkit-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        -moz-transition: -moz-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        -o-transition: -o-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        transition: transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
    }

    .radio input[type="radio"] {
        opacity: 0;
        z-index: 1;
    }

    .radio input[type="radio"]:focus+label::before {
        outline: thin dotted;
        outline: 5px auto -webkit-focus-ring-color;
        outline-offset: -2px;
    }

    .radio input[type="radio"]:checked+label::after {
        -webkit-transform: scale(1, 1);
        -ms-transform: scale(1, 1);
        -o-transform: scale(1, 1);
        transform: scale(1, 1);
    }

    .radio input[type="radio"]:disabled+label {
        opacity: 0.65;
    }

    .radio input[type="radio"]:disabled+label::before {
        cursor: not-allowed;
    }

    .radio.radio-inline {
        margin-top: 0;
    }

    .radio-primary input[type="radio"]+label::after {
        background-color: #337ab7;
    }

    .radio-primary input[type="radio"]:checked+label::before {
        border-color: #337ab7;
    }

    .radio-primary input[type="radio"]:checked+label::after {
        background-color: #337ab7;
    }

    .radio-danger input[type="radio"]+label::after {
        background-color: #d9534f;
    }

    .radio-danger input[type="radio"]:checked+label::before {
        border-color: #d9534f;
    }

    .radio-danger input[type="radio"]:checked+label::after {
        background-color: #d9534f;
    }

    .radio-info input[type="radio"]+label::after {
        background-color: #5bc0de;
    }

    .radio-info input[type="radio"]:checked+label::before {
        border-color: #5bc0de;
    }

    .radio-info input[type="radio"]:checked+label::after {
        background-color: #5bc0de;
    }

    @media (max-width:767px) {
        .radio.radio-inline {
            display: inherit;
        }
    }
</style>

<?php
$language1      = $this->customlib->getLanguage();
$language_name1 = $language1["short_code"];
?>
<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('attendance'); ?> <small><?php echo $this->lang->line('by_date1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form id='form1' action="<?php echo site_url('admin/subjectattendence') ?>" method="post" accept-charset="utf-8">
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
                                        <select autofocus="" id="class_id" name="class_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                            ?>
                                                <option value="<?php echo $class['id'] ?>" <?php echo set_select('class_id', $class['id'], set_value('class_id')); ?>><?php echo $class['class'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">
                                            <?php echo $this->lang->line('date'); ?>
                                        </label><small class="req"> *</small>
                                        <input name="date" placeholder="" type="text" class="form-control date" value="<?php echo set_value('date'); ?>" readonly="readonly" />
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">
                                            <?php echo $this->lang->line('subject'); ?>
                                        </label><small class="req"> *</small>
                                        <select id="subject_timetable_id" name="subject_timetable_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('subject_timetable_id'); ?></span>
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
                                <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('student_list'); ?></h3>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <div class="box-body">
                                <?php
                                if (!empty($resultlist)) {
                                    $checked  = "";
                                    $can_edit = 1;
                                    if (!isset($msg)) {
                                        if ($resultlist[0]['attendence_type_id'] != "") {
                                            if ($resultlist[0]['attendence_type_id'] != 5) {
                                                if ($this->rbac->hasPrivilege('student_attendance', 'can_edit')) {
                                                    $can_edit = 1;
                                                } else {
                                                    $can_edit = 0;
                                                }
                                ?>
                                                <div class="alert alert-success"><?php echo $this->lang->line('attendance_already_submitted_you_can_edit_record'); ?></div>
                                            <?php
                                            } else {
                                                $checked = "checked='checked'";
                                            ?>
                                                <div class="alert alert-warning"><?php echo $this->lang->line('attendance_already_submitted_as_holiday_you_can_edit_record'); ?></div>
                                        <?php
                                            }
                                        }
                                    } else {
                                        ?>
                                        <div class="alert alert-success"><?php echo $this->lang->line('attendance_saved_successfully'); ?></div>
                                    <?php
                                    }
                                    ?>
                                    <form action="<?php echo site_url('admin/subjectattendence/index') ?>" method="post" class="form_attendence">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="mailbox-controls">
                                        <div class="row">
                                                <div class="col-md-6">                            
                                                <?php
                                                if ($this->rbac->hasPrivilege('student_attendance', 'can_add')) { ?>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('set_attendance_for_all_students_as'); ?> &nbsp;</label>
                                                        <?php
                                                        foreach ($attendencetypeslist as $key => $type) {
                                                            $att_type = str_replace(" ", "_", strtolower($type['type']));

                                                        ?>
                                                            <div class="radio radio-info radio-inline">
                                                                <input type="radio" name="attendencetype" class="default_radio" value="radio_<?php echo $type['id'] ?>" id="attendencetype<?php echo $type['id'] ?>">
                                                                <label for="attendencetype<?php echo $type['id'] ?>">
                                                                    <?php echo $this->lang->line($att_type); ?>
                                                                </label>

                                                            </div>
                                                        <?php

                                                        }
                                                        ?>

                                                    </div>
                                                <?php
                                                }
                                                ?>
                                           
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="pull-right">
                                                        <?php

                                                        if ($can_edit == 1) {
                                                            if ($this->rbac->hasPrivilege('student_attendance', 'can_add')) {
                                                        ?>
                                                                <button type="submit" name="search" value="saveattendence" id="saveattendence" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-save"></i> <?php echo $this->lang->line('save_attendance'); ?> </button>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                                        <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
                                        <input type="hidden" name="subject_timetable_id" value="<?php echo $subject_timetable_id; ?>">
                                        <input type="hidden" name="date" value="<?php echo $date; ?>">
                                        <div class="table-responsive ptt10">
                                            <div class="download_label">
                                                <?php echo $this->lang->line('student_list'); ?>
                                            </div>
                                            <table class="table table-hover table-striped example">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                        <th><?php echo $this->lang->line('roll_number'); ?></th>
                                                        <th><?php echo $this->lang->line('name'); ?></th>
                                                        <th class=""><?php echo $this->lang->line('attendance'); ?></th>
                                                        <th><?php echo $this->lang->line('note'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $row_count = 1;
                                                    foreach ($resultlist as $key => $value) {
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <input type="hidden" name="student_session[]" value="<?php echo $value['student_session_id']; ?>">
                                                                <input type="hidden" value="<?php echo $value['student_subject_attendance_id']; ?>" name="attendance_id<?php echo $value['student_session_id']; ?>">
                                                                <?php echo $row_count; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $value['admission_no']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $value['roll_no']; ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                echo $this->customlib->getFullName($value['firstname'], $value['middlename'], $value['lastname'], $sch_setting->middlename, $sch_setting->lastname);
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $c     = 1;
                                                                $count = 0;
                                                                foreach ($attendencetypeslist as $key => $type) {
                                                                  
                                                                        $att_type = str_replace(" ", "_", strtolower($type['type']));
                                                                        if ($value['date'] != "xxx") {
                                                                ?>
                                                                            <div class="radio radio-info radio-inline">
                                                                                <input <?php if ($value['attendence_type_id'] == $type['id']) {
                                                                                            echo "checked";
                                                                                        }
                                                                                        ?> type="radio" id="attendencetype<?php echo $value['student_session_id'] . "-" . $count; ?>" value="<?php echo $type['id'] ?>"  class="radio_<?php echo $type['id'] ?>" name="attendencetype<?php echo $value['student_session_id']; ?>">
                                                                                <label for="attendencetype<?php echo $value['student_session_id'] . "-" . $count; ?>">
                                                                                    <?php echo $this->lang->line($att_type); ?>
                                                                                </label>
                                                                            </div>
                                                                        <?php
                                                                        } else {
                                                                        ?>
                                                                            <div class="radio radio-info radio-inline">
                                                                                <input <?php if ($c == 1) {
                                                                                            echo "checked";
                                                                                        }
                                                                                        ?> type="radio" id="attendencetype<?php echo $value['student_session_id'] . "-" . $count; ?>" value="<?php echo $type['id'] ?>"  class="radio_<?php echo $type['id'] ?>" name="attendencetype<?php echo $value['student_session_id']; ?>">
                                                                                <label for="attendencetype<?php echo $value['student_session_id'] . "-" . $count; ?>">
                                                                                    <?php echo $this->lang->line($att_type); ?>
                                                                                </label>
                                                                            </div>
                                                                <?php
                                                                        }
                                                                        $c++;
                                                                        $count++;
                                                                   
                                                                }
                                                                ?>
                                                            </td>
                                                            <?php if ($date == 'xxx') { ?>
                                                                <td><input type="text" name="remark<?php echo $value["student_session_id"] ?>"></td>
                                                            <?php } else { ?>
                                                                <td><input type="text" name="remark<?php echo $value["student_session_id"] ?>" value="<?php echo $value["remark"]; ?>"></td>
                                                            <?php } ?>
                                                        </tr>
                                                    <?php
                                                        $row_count++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </form>
                                <?php
                                } else {
                                ?>
                                    <div class="alert alert-info"><?php echo $this->lang->line('admited_alert'); ?></div>
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

<script type="text/javascript">
    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';

    $(document).ready(function() {

        var section_id_post = "<?php echo set_value('section_id'); ?>";
        var class_id_post = "<?php echo set_value('class_id'); ?>";
        var date_post = "<?php echo set_value('date'); ?>";
        var subject_timetable_id = "<?php echo set_value('subject_timetable_id', 0); ?>";
        populateSection(section_id_post, class_id_post);
        getSubjects(class_id_post, section_id_post, date_post, subject_timetable_id);

        function populateSection(section_id_post, class_id_post) {
            if (section_id_post != "" && class_id_post != "") {

                $('#section_id').html("");

                var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                $.ajax({
                    type: "GET",
                    url: baseurl + "sections/getByClass",
                    data: {
                        'class_id': class_id_post
                    },
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, obj) {
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

        function getSubjects(class_id, section_id, date, subject_timetable_id) {

            if (section_id != "" && class_id != "" && date != "") {
                $('#subject_timetable_id').html("");
                var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                $.ajax({
                    type: "POST",
                    url: baseurl + "admin/subjectgroup/getSubjectByClassandSectionDate",
                    data: {
                        'class_id': class_id,
                        'section_id': section_id,
                        'date': date
                    },
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, obj) {
                            var staff_name = (obj.surname != "") ? obj.name + " " + obj.surname : obj.name;
                            var select = "";
                            if (subject_timetable_id == obj.id) {
                                var select = "selected=selected";
                            }

                            div_data += "<option value=" + obj.id + " " + select + ">" + obj.subject_name + " (" + obj.time_from + "- " + obj.time_to + ") By " + staff_name + " (" + obj.employee_id + ")" + "</option>";
                        });
                        $('#subject_timetable_id').append(div_data);
                    }
                });
            }
        }

        $(document).on('change', '#class_id', function(e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            var url = "";
            $.ajax({
                type: "GET",
                url: baseurl + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });

        $('.date').datepicker({
            format: date_format,
            weekStart: start_week,
            todayHighlight: true,

            autoclose: true,
            language: '<?php echo $language_name1; ?>'
        }).on('changeDate', function(ev) {

            var class_id = $('#class_id').val();
            var section_id = $('#section_id').val();
            var date = $(this).val();
            getSubjects(class_id, section_id, date, 0);
        });
    });

    $(function() {
        $('.button-checkbox').each(function() {
            var $widget = $(this),
                $button = $widget.find('button'),
                $checkbox = $widget.find('input:checkbox'),
                color = $button.data('color'),
                settings = {
                    on: {
                        icon: 'glyphicon glyphicon-check'
                    },
                    off: {
                        icon: 'glyphicon glyphicon-unchecked'
                    }
                };
            $button.on('click', function() {
                $checkbox.prop('checked', !$checkbox.is(':checked'));
                $checkbox.triggerHandler('change');
                updateDisplay();
            });
            $checkbox.on('change', function() {
                updateDisplay();
            });

            function updateDisplay() {
                var isChecked = $checkbox.is(':checked');
                $button.data('state', (isChecked) ? "on" : "off");
                $button.find('.state-icon')
                    .removeClass()
                    .addClass('state-icon ' + settings[$button.data('state')].icon);
                if (isChecked) {
                    $button
                        .removeClass('btn-success')
                        .addClass('btn-' + color + ' active');
                } else {
                    $button
                        .removeClass('btn-' + color + ' active')
                        .addClass('btn-primary');
                }
            }

            function init() {
                updateDisplay();
                if ($button.find('.state-icon').length == 0) {
                    $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
                }
            }
            init();
        });
    });

    // $('#checkbox1').change(function() {
    //     if (this.checked) {
    //         var returnVal = confirm("<?php //echo $this->lang->line('are_you_sure'); ?>");
    //         $(this).prop("checked", returnVal);
    //         $("input[type=radio]").attr('disabled', true);

    //     } else {
    //         $("input[type=radio]").attr('disabled', false);
    //         $("input[type=radio][value='1']").attr("checked", "checked");

    //     }
    // });


    $(document).ready(function() {
        $('.default_radio').click(function() {
       let radio_default=($(this).val());
            var returnVal = confirm("<?php echo $this->lang->line('are_you_sure'); ?>");
            if(returnVal){
                $("input[type=radio][class='"+radio_default+"']").prop("checked", returnVal);
            }else{
                console.log('sdfsdfdsfs');
                return false;
            }
    });

    });


    $('form.form_attendence').on('submit', function(e) {
        $(this).submit(function() {
            return false;
        });
        return true;
    });
</script>