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
                        <form role="form" action="<?php echo site_url('admin/examgroup/assign/' . $id) ?>" method="post" class="form-horizontal">

                            <?php echo $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="examgroup_id" value="<?php echo $examgroup->id; ?>">
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
                                    <label><?php echo $this->lang->line('session'); ?></label>
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
                <form method="post" action="<?php echo site_url('admin/examgroup/addstudent') ?>" id="assign_form">

                    <?php
                    if (isset($resultlist)) {
                        ?>
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('assign_exam_group'); ?>
                                    </i> </h3>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <div class="box-body no-padding">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <div class="table-responsive">
                                                <h4>
                                                    <input type="hidden" name="exam_group" value="<?php echo $examgroup->id; ?>">
                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $examgroup->name; ?></a>
                                                </h4>
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('exam'); ?></th>
                                                            <th><?php echo $this->lang->line('date_from'); ?></th>
                                                            <th><?php echo $this->lang->line('date_to'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (empty($examgroup->exams)) {
                                                            ?>

                                                        <td colspan="5" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                                        <?php
                                                    } else {

                                                        foreach ($examgroup->exams as $exam_key => $exam_value) {
                                                            ?>
                                                            <tr class="mailbox-name">
                                                                <td><?php echo $exam_value->exam; ?></td>
                                                                <td><?php echo $exam_value->date_from; ?></td>
                                                                <td><?php echo $exam_value->date_to; ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="table-responsive ptt10">
                                                <table class="table table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <th><input style="vertical-align: inherit;" type="checkbox" id="select_all"/> <?php echo $this->lang->line('all'); ?></th>
                                                            <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                            <th><?php echo $this->lang->line('student_name'); ?></th>
                                                            <th><?php echo $this->lang->line('class'); ?></th>
                                                            <th><?php echo $this->lang->line('father_name'); ?></th>
                                                            <th><?php echo $this->lang->line('category'); ?></th>
                                                            <th><?php echo $this->lang->line('gender'); ?></th>
                                                        </tr>
                                                        <?php
                                                        if (empty($resultlist)) {
                                                            ?>
                                                            <tr>
                                                                <td colspan="7" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                                            </tr>
                                                            <?php
                                                        } else {
                                                            $count = 1;
                                                            foreach ($resultlist as $student) {
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php
                                                                        if ($student['exam_group_student_id'] != 0) {
                                                                            $sel = "checked='checked'";
                                                                        } else {
                                                                            $sel = "";
                                                                        }
                                                                        ?>
                                                                        <input type="hidden" name="all_students[]" value="<?php echo $student['student_id']; ?>">

                                                                        <input class="checkbox" type="checkbox" name="students_id[]"  value="<?php echo $student['student_id']; ?>" <?php echo $sel; ?>/>
                                                                    </td>
                                                                    <td><?php echo $student['admission_no']; ?></td>
                                                                    <td><?php echo $student['firstname'] . " " . $student['lastname']; ?></td>
                                                                    <td><?php echo $student['class']; ?></td>
                                                                    <td><?php echo $student['father_name']; ?></td>
                                                                    <td><?php echo $student['category']; ?></td>
                                                                    <td><?php echo $student['gender']; ?></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            $count++;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <button type="submit" class="allot-fees btn btn-primary btn-sm pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('save'); ?>
                                            </button>

                                            <br/>
                                            <br/>
                                        </div>
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

//select all checkboxes
    $("#select_all").change(function () {  //"select all" change
        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

//".checkbox" change
    $('.checkbox').change(function () {
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if (false == $(this).prop("checked")) { //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.checkbox:checked').length == $('.checkbox').length) {
            $("#select_all").prop('checked', true);
        }
    });
    $("#assign_form").submit(function (e) {
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
        e.preventDefault();
    });

</script>