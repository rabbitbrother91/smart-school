<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.js"></script>
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">

                        <form role="form" action="<?php echo site_url('admin/examgroup/addmark/' . $id) ?>" method="post" class="form-horizontal">

                            <?php echo $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="exam_group_class_batch_exam_subject_id" value="<?php echo $id; ?>">
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <label><?php echo $this->lang->line('class'); ?></label>
                                    <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($classlist as $class) {
                                            ?>
                                            <option value="<?php echo $class['id'] ?>" <?php
                                            if (set_value('class_id') == $class['id']) {
                                                echo "selected=selected";
                                            }
                                            ?>><?php echo $class['class'] ?></option>
                                                    <?php
                                                }
                                                ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small> 
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label>Session --r</label>
                                    <select  id="session_id" name="session_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($sessionlist as $session) {
                                            ?>
                                            <option value="<?php echo $session['id'] ?>" <?php
                                            if (set_value('session_id') == $session['id']) {
                                                echo "selected=selected";
                                            }
                                            ?>><?php echo $session['session'] ?></option>
                                                    <?php
                                                }
                                                ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <form method="post" action="<?php echo site_url('admin/examgroup/entrymarks') ?>" id="assign_form">
                    <?php
                    if (isset($resultlist)) {
                        ?>
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-users"></i> Assign Exam Group
                                    </i> </h3>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-3">                                        
                                        <input type="hidden" id="max_mark" value="<?php echo $subject_detail->max_marks; ?>">
                                        <ul class="list-group">
                                            <li class="list-group-item">Subject --r : <?php echo $subject_detail->subject_name . " (" . $subject_detail->code . ")"; ?></li>
                                            <li class="list-group-item">Date From --r : <?php echo $subject_detail->date_from; ?></li>
                                            <li class="list-group-item">Date To --r : <?php echo $subject_detail->date_to; ?></li>
                                            <li class="list-group-item">Room No --r : <?php echo $subject_detail->room_no; ?></li>
                                            <li class="list-group-item">Max Marks --r : <?php echo $subject_detail->max_marks; ?></li>
                                            <li class="list-group-item">Min marks --r : <?php echo $subject_detail->min_marks; ?></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="exam_group_class_batch_exam_subject_id" value="<?php echo $id; ?>">
                                        <div class=" table-responsive">
                                            <table class="table table-striped">
                                                <tbody>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                        <th><?php echo $this->lang->line('student_name'); ?></th> <th><?php echo $this->lang->line('father_name'); ?></th>
                                                        <th><?php echo $this->lang->line('category'); ?></th>
                                                        <th><?php echo $this->lang->line('gender'); ?></th>
                                                        <th><?php echo $this->lang->line('attendance'); ?></th>
                                                        <th><?php echo $this->lang->line('marks'); ?></th>
                                                        <th><?php echo $this->lang->line('note'); ?></th>

                                                    </tr>
                                                    <?php
                                                    if (empty($resultlist)) {
                                                        ?>
                                                        <tr>
                                                            <td colspan="7" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                                        </tr>
                                                        <?php
                                                    } else {

                                                        foreach ($resultlist as $student) {
                                                            ?>
                                                            <tr>
                                                        <input type="hidden" name="prev_id[<?php echo $student['exam_group_student_id'] ?>]" value="<?php echo $student['exam_group_exam_result_id'] ?>">
                                                        <input type="hidden" name="exam_group_student_id[]" value="<?php echo $student['exam_group_student_id'] ?>">
                                                        <td><?php echo $student['admission_no']; ?></td>
                                                        <td><?php echo $student['firstname'] . " " . $student['lastname']; ?></td>
                                                        <td><?php echo $student['father_name']; ?></td>
                                                        <td><?php echo $student['category']; ?></td>
                                                        <td><?php echo $student['gender']; ?></td>
                                                        <td>
                                                            <div>

                                                                <?php
                                                                foreach ($attendence_exam as $attendence_key => $attendence_value) {
                                                                    $chk = ($student['exam_group_exam_result_attendance'] == $attendence_value) ? "checked='checked'" : "";
                                                                    ?>
                                                                    <label class="checkbox-inline"><input type="checkbox" class="attendance_chk" name="exam_group_student_attendance_<?php echo $student['exam_group_student_id']; ?>" value="<?php echo $attendence_value; ?>" <?php echo $chk; ?>><?php echo $attendence_value; ?></label>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </td>
                                                        <td> <input type="number" class="form-control marks" name="exam_group_student_mark_<?php echo $student['exam_group_student_id']; ?>" value="<?php echo $student['exam_group_exam_result_get_marks']; ?>"></td>
                                                        <td> <input type="text" class="form-control" name="exam_group_student_note_<?php echo $student['exam_group_student_id']; ?>" value="<?php echo $student['exam_group_exam_result_note']; ?>"></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php
                                        if (!empty($resultlist)) {
                                            ?>

                                            <button type="submit" class="allot-fees btn btn-primary btn-sm pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('save'); ?>
                                            </button>
                                            <?php
                                        }
                                        ?>

                                        <br/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </form>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">

    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';

    var class_id = '<?php echo set_value('class_id', 0) ?>';
    var section_id = '<?php echo set_value('section_id', 0) ?>';
    getSectionByClass(class_id, section_id);
    $(document).on('change', '#class_id', function (e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, section_id);
    });

    function getSectionByClass(class_id, section_id) {

        if (class_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
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
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }

    var response;
    $.validator.addMethod("uniqueUserName", function (value, element, options)
    {
        var max_mark = $('#max_mark').val();
        //we need the validation error to appear on the correct element
        return parseFloat(value) <= parseFloat(max_mark);
    },
            "Invalid Marks"
            );


    $('form#assign_form').on('submit', function (event) {
        //Add validation rule for dynamically generated name fields
        $('.marks').each(function () {
            $(this).rules("add",
                    {
                        required: true,
                        uniqueUserName: true,
                        messages: {
                            required: "Required",
                        }
                    });
        });

        //Add validation rule for dynamically generated email fields

    });
    
    $("#assign_form").validate({
        submitHandler: function (form) {
            if (confirm('Are you sure?')) {
                var $this = $('.allot-fees');
                $.ajax({
                    type: "POST",
                    dataType: 'Json',
                    url: $("#assign_form").attr('action'),
                    data: $("#assign_form").serialize(), // serializes the form's elements.
                    beforeSend: function () {
                        $this.button('loading');

                    },
                    success: function (data)
                    {
                        if (data.status == "fail") {
                            var message = "";
                            $.each(data.error, function (index, value) {

                                message += value;
                            });
                            errorMsg(message);
                        } else {
                            successMsg(data.message);
                        }

                        $this.button('reset');
                    },
                    complete: function () {
                        $this.button('reset');
                    }
                });

            }
            return false; // required to block normal submit since you used ajax
        }
    });

</script>