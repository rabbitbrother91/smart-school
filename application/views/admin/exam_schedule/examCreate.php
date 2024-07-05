<link rel="stylesheet" href="<?php echo base_url() ?>backend/plugins/timepicker/bootstrap-timepicker.min.css">
<script src="<?php echo base_url() ?>backend/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <form action="<?php echo site_url('admin/examschedule/create') ?>"  method="post" accept-charset="utf-8" id="schedule-form">
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="box-body">
                            <div class="row">
                                <input type="hidden" name="save_exam" value="search" >
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('exam_name'); ?></label>
                                        <select autofocus=""  id="exam_id" name="exam_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($examlist as $exam) {
    ?>
                                                <option value="<?php echo $exam['id'] ?>" <?php
if ($exam_id == $exam['id']) {
        echo "selected =selected";
    }
    ?>><?php echo $exam['name'] ?></option>

                                                <?php
$count++;
}
?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('exam_id'); ?></span>
                                    </div>
                                </div><!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label>

                                        <select  id="class_id" name="class_id" class="form-control" >
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
                                </div><!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.box-body -->
                    </form>
                </div>
                <?php
if (isset($examSchedule)) {
    ?>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-list"></i> <?php echo $this->lang->line('exam_schedule'); ?></h3>
                        </div>
                        <div class="box-body">
                            <?php
if (!empty($examSchedule)) {
        ?>
                                <form role="form" id="" class="addschedule-form" method="post" action="<?php echo site_url('admin/examschedule/create') ?>">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                                    <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
                                    <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <?php echo $this->lang->line('subject'); ?>
                                                    </th>
                                                    <th>
                                                        <?php echo $this->lang->line('date'); ?>
                                                    </th>
                                                    <th>
                                                        <?php echo $this->lang->line('start_time'); ?>
                                                    </th>
                                                    <th>
                                                        <?php echo $this->lang->line('end_time'); ?>
                                                    </th>
                                                    <th>
                                                        <?php echo $this->lang->line('room'); ?>
                                                    </th>
                                                    <th>
                                                        <?php echo $this->lang->line('full_mark'); ?>
                                                    </th>
                                                    <th>
                                                        <?php echo $this->lang->line('passing_marks'); ?>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php foreach ($examSchedule as $key => $value) {
            ?>
                                                <input type="hidden" name="i[]" value="<?php echo $value['id'] ?>">
                                                <tr>
                                                    <td>
                                                        <div class="form-group">
                                                            <?php echo $value['name'] . " (" . substr($value['type'], 0, 2) . ".)" ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php
$exam_date = $value['date_of_exam'];
            if ($exam_date != "") {
                $exam_date = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($exam_date));
            }
            ?>
                                                        <div class="form-group">
                                                            <input type="text" name="date_<?php echo $value['id'] ?>" class="form-control sandbox-container" id="date_<?php echo $value['id'] ?>" placeholder="Enter date" value="<?php echo $exam_date; ?>">
                                                        </div>
                                                    </td>
                                                    <td style="width:200px;">
                                                        <?php
$exam_time = $value['start_to'];

            if ($exam_time != "") {
                $exam_time = $exam_time;
            } else {

                $exam_time = " ";
            }
            ?>
                                                        <div class="bootstrap-timepicker">
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input type="text" name="stime_<?php echo $value['id'] ?>" class="form-control timepicker" id="stime_<?php echo $value['id'] ?>" value="<?php echo $exam_time; ?>">
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </div>
                                                                </div><!-- /.input group -->
                                                            </div><!-- /.form group -->
                                                        </div>
                                                    </td>
                                                    <td style="width:200px;">
                                                        <div class="bootstrap-timepicker">
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input type="text" name="etime_<?php echo $value['id'] ?>" class="form-control timepicker" id="etime_<?php echo $value['id'] ?>" value="<?php echo $value['end_from'] ?>">
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-clock-o"></i>
                                                                    </div>
                                                                </div><!-- /.input group -->
                                                            </div><!-- /.form group -->
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="text" name="room_<?php echo $value['id'] ?>" class="form-control"  id="room_<?php echo $value['id'] ?>" value="<?php echo $value['room_no'] ?>" placeholder="Enter Room">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="text" name="fmark_<?php echo $value['id'] ?>" class="form-control" id="fmark_<?php echo $value['id'] ?>" value="<?php echo $value['full_marks'] ?>" placeholder="Enter Full Marks">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="text" name="pmarks_<?php echo $value['id'] ?>" class="form-control" id="pmarks_<?php echo $value['id'] ?>" value="<?php echo $value['passing_marks'] ?>" placeholder="Enter Passing Marks">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
}
        ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="submit" class="btn btn-primary save_form pull-right" name="save_exam" value="save_exam"><?php echo $this->lang->line('submit'); ?></button>
                                </form>
                                <?php
} else {
        ?>
                                <div class="alert alert-info">No Subject Assigned. Please assign subjects in this class.</div>
                                <?php
}
    ?>
                        </div><!---./end box-body--->
                    </div>
                </div>
                <!-- right column -->
            </div>   <!-- /.row -->
            <?php
} else {

}
?>

    </section><!-- /.content -->
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            var url = "<?php
$userdata = $this->customlib->getUserData();
if (($userdata["role_id"] == 2)) {
    echo "getClassTeacherSection";
} else {
    echo "getByClass";
}
?>";
            $.ajax({
                type: "GET",
                url: base_url + "sections/" + url,
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

        function getSectionByClass(class_id, section_id) {
            if (class_id != "" && section_id != "") {
                $('#section_id').html("");

                var base_url = '<?php echo base_url() ?>';
                var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                var url = "<?php
$userdata = $this->customlib->getUserData();
if (($userdata["role_id"] == 2)) {
    echo "getClassTeacherSection";
} else {
    echo "getByClass";
}
?>";
                $.ajax({
                    type: "GET",
                    url: base_url + "sections/" + url,
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

        $(document).on('change', '#feecategory_id', function (e) {

            $('#feetype_id').html("");
            var feecategory_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "feemaster/getByFeecategory",
                data: {'feecategory_id': feecategory_id},
                dataType: "json",
                success: function (data) {

                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.id + ">" + obj.type + "</option>";
                    });

                    $('#feetype_id').append(div_data);
                }
            });
        });
    });

    $(document).on('change', '#section_id', function (e) {
        $("form#schedule-form").submit();
    });
</script>

<script src="<?php echo base_url(); ?>backend/custom/jquery.validate.min.js"></script>

<div class="row">
    <div id="sandbox-container">
    </div>
</div>
</div>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>backend/custom/bootstrap-datepicker.js"></script>

<script>
    $(function () {

        $(".timepicker").timepicker({
            showInputs: false,

        });
    });
</script>
<script>
    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
    $('.sandbox-container').datepicker({

        autoclose: true,
        format: date_format,
    });

    $(function () {
        $('.addschedule-form').validate({
            submitHandler: function (form) {
                form.submit();
            }
        });

        $('input[id^="date_"]').each(function () {
            $(this).rules('add', {
                required: true,
                messages: {
                    required: "Required"
                }
            });

        });

        $('input[id^="stime_"]').each(function () {
            $(this).rules('add', {
                required: true,
                messages: {
                    required: "Required"
                }
            });
        });

        $('input[id^="etime_"]').each(function () {
            $(this).rules('add', {
                required: true,
                messages: {
                    required: "Required"
                }
            });
        });

        $('input[id^="room_"]').each(function () {
            $(this).rules('add', {
                required: true,
                messages: {
                    required: "Required"
                }
            });
        });

        $('input[id^="fmark_"]').each(function () {
            $(this).rules('add', {
                required: true,
                messages: {
                    required: "Required"
                }
            });
        });
        
        $('input[id^="pmarks_"]').each(function () {
            $(this).rules('add', {
                required: true,
                messages: {
                    required: "Required"
                }
            });
        });

    });
</script>