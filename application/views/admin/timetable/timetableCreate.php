<style type="text/css">
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 22px !important; border-radius: 0 !important; padding-left: 0 !important;}
    .input-group-addon .glyphicon{font-size: 12px;}

    .show{
        display : block;
        z-index: 100;
        background-image : url('../../backend/images/timeloader.gif');
        opacity : 0.6;
        background-repeat : no-repeat;
        background-position : center;
    }
    /* .tab-pane{min-height: 200px;}*/
    .commentForm .input-group {position: relative;display: block;border-collapse: separate;}
    .commentForm .input-group-addon{
        position: absolute;
        right: 26px;
        top: 0px;
        z-index: 3;
    }
    .relative{position: relative;}
    .commentForm .input-group-addon i,
    .commentForm .input-group-addon span{padding-left: 13px;}
    .commentForm .relative label.text-danger{position: absolute; bottom: 5px;}
    .addbtnright{ position: absolute;right: 0;top: -46px;}

    @media(max-width:767px){
        .timeresponsive{overflow-x: auto;     overflow-y: hidden;}
        .timeresponsive .dropdown-menu{z-index: 1060;    bottom: 0 !important; height: 250px; padding: 20px;}
        .tablewidthRS{width: 690px;}
    }
</style>
<script src="<?php echo base_url(); ?>backend/custom/jquery.validate.min.js"></script>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <form class="create_time_table" action="<?php echo site_url('admin/timetable/create') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">

                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?><small class="req"> *</small></label>
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
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('section'); ?><small class="req"> *</small></label>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('subject_group'); ?><small class="req"> *</small></label>
                                        <select  id="subject_group_id" name="subject_group_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('subject_group_id'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" id="insertbtn" class="btn btn-primary pull-right btn-sm"><?php echo $this->lang->line('search'); ?></button>
                        </div>
                    </form>

                    <?php
if (isset($getDaysnameList)) {
    ?>
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_parameter_to_generate_time_table_quickly'); ?></h3>
                       
                </div>
                <div class="box-header ptbnull">                       
                <form action="#" method="POST" id="universal_from">           
         
        <div class="row">
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="form_name"><?php echo $this->lang->line('period_start_time'); ?><small class="req"> *</small></label>
                   
                    <div class="input-group">
                                        <input type="text" name="start_time" class="form-control time" id="start_time" value="">
                                        <div class="input-group-addon">
                                            <span class="fa fa-clock-o"></span>
                                        </div>
                                    </div>

                    <div class="text text-danger"></div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="form_email"><?php echo $this->lang->line('duration_minute'); ?><small class="req"> *</small></label>                     
                    <div class="input-group">
                                        <input type="number" name="duration" class="form-control" id="duration" value="">
                                        <div class="input-group-addon">
                                            <span class="fa fa-hourglass-start"></span>
                                        </div>
                                    </div>
                    <div class="text text-danger"></div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="form_phone"><?php echo $this->lang->line('interval_minute'); ?><small class="req"> *</small></label>
                   <div class="input-group">
                                        <input type="number" name="interval" class="form-control" id="interval" value="0">
                                        <div class="input-group-addon">
                                            <span class="fa fa-hourglass-start"></span>
                                        </div>
                                    </div>
                    <div class="text text-danger"></div>
                </div>
            </div>
                 <div class="col-sm-2">
                <div class="form-group">
                    <label for="form_phone"><?php echo $this->lang->line('room_no'); ?></label>
<input type="text" name="rroom_no" class="form-control" id="froom_no">
                  
                    <div class="help-block with-errors"></div>
                </div>
            </div>
              <div class="col-sm-2">
                <div class="form-group">
                    <label for="form_phone" class="displayblock opacity">&nbsp;</label>
                    <input type="submit" class="btn btn-primary btn-sm btn-send" value="<?php echo $this->lang->line('apply'); ?>">
                    <div class="help-block with-errors"></div>
                </div>
            </div>
        </div>
   </form>   

                        </div>
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs" id="myTabs">
                                <?php
$count = 1;

    foreach ($getDaysnameList as $days_key => $days_value) {
        $cls = "";
        if ($count == 1) {
        }
        ?>
                                    <li <?php echo $cls; ?>><a href="#tab_<?php echo $count; ?>" data-c="<?php echo set_value('class_id'); ?>" data-days="<?php echo $days_value; ?>" data-s="<?php echo set_value('section_id'); ?>" data-group="<?php echo set_value('subject_group_id'); ?>" data-day="<?php echo $days_key; ?>" data-toggle="tab" aria-expanded="true"><?php echo $days_value; ?></a></li>

                                    <?php
$count++;
    }
    ?>
                            </ul>
                            <div class="tab-content">
                                <?php
$count = 1;
    foreach ($getDaysnameList as $days_key => $days_value) {
        $cls = "class='tab-pane'";
        if ($count == 1) {

        }
        ?>
                                    <div <?php echo $cls; ?> id="tab_<?php echo $count; ?>">
                                    </div>

                                    <?php
$count++;
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
    $(document).on('submit','.create_time_table',function(e){
        document.getElementById("insertbtn").disabled = true;
    });   
     
    
                $(document).on('focus', '.time', function () {
                    var $this = $(this);
                    $this.datetimepicker({
                        format: 'LT'
                    });
                });
                var tot_count = 0;
                var class_id = $('#class_id').val();
                var section_id = '<?php echo set_value('section_id') ?>';
                var subject_group_id = '<?php echo set_value('subject_group_id') ?>';
                $(document).ready(function () {

                    $('#myTabs a:first').tab('show') // Select first tab
                    getSectionByClass(class_id, section_id);
                    getGroupByClassandSection(class_id, section_id, subject_group_id);

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

                    $(document).on('change', '#section_id', function (e) {
                        $('#subject_group_id').html("");
                        var section_id = $(this).val();
                        var class_id = $('#class_id').val();
                        var base_url = '<?php echo base_url() ?>';
                        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                        $.ajax({
                            type: "POST",
                            url: base_url + "admin/subjectgroup/getGroupByClassandSection",
                            data: {'class_id': class_id, 'section_id': section_id},
                            dataType: "json",
                            success: function (data) {
                                $.each(data, function (i, obj)
                                {
                                    div_data += "<option value=" + obj.subject_group_id + ">" + obj.name + "</option>";
                                });

                                $('#subject_group_id').append(div_data);
                            }
                        });
                    });
                });

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

                function getGroupByClassandSection(class_id, section_id, subject_group_id) {
                    if (class_id != "" && section_id != "" && subject_group_id != "") {
                        $('#subject_group_id').html("");

                        var base_url = '<?php echo base_url() ?>';
                        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                        $.ajax({
                            type: "POST",
                            url: base_url + "admin/subjectgroup/getGroupByClassandSection",
                            data: {'class_id': class_id, 'section_id': section_id},
                            dataType: "json",
                            success: function (data) {
                                console.log(subject_group_id);
                                $.each(data, function (i, obj)
                                {
                                    var sel = "";
                                    if (subject_group_id == obj.subject_group_id) {
                                        sel = "selected";
                                    }
                                    div_data += "<option value=" + obj.subject_group_id + " " + sel + ">" + obj.name + "</option>";
                                });

                                $('#subject_group_id').append(div_data);
                            }
                        });
                    }
                }

                $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                    var target = $(e.target).attr("href"); // activated tab
                    var target_id = $(e.target).attr("id"); // activated tab
                    var ajax_data = $(e.target).data(); // activated tab
                    $(target).html("");
                    getGroupdata(target, target_id, ajax_data);
                })

                function getGroupdata(target, target_id, ajax_data) {

                    $.ajax({
                        type: 'POST',
                        url: base_url + "admin/timetable/getBydategroupclasssection",
                        data: {'day': ajax_data.day, 'class_id': ajax_data.c, 'section_id': ajax_data.s, 'subject_group_id': ajax_data.group},
                        dataType: 'json',
                        beforeSend: function () {
                            $(target).addClass('show');
                        },
                        success: function (data) {
                            $(target).html(data.html);

                            $('.staff', target).select2({
                                dropdownAutoWidth: true,
                                width: '100%'
                            });
                            $('.subject', target).select2({
                                dropdownAutoWidth: true,
                                width: '100%'
                            });
                            tot_count = data.total_count + 1;
                        },
                        error: function (xhr) { // if error occured

                        },
                        complete: function () {
                            $(target).removeClass('show');
                        }
                    });
                }


                $(document).ready(function () {
                    var counter = 0;
                    $(document).on("click", ".addrow", function () {                       
                        var newRow = $("<tr>");
                        var cols = "";
                        cols += '<td class="relative"><input type="hidden" name="total_row[]" value="' + tot_count + '"><input type="hidden" name="prev_id_' + tot_count + '" value="0"><select class="form-control subject" id="subject_id_' + tot_count + '" name="subject_' + tot_count + '">' + $("#subject_dropdown").text() + '</select></td>';
                        cols += '<td class="relative"><select class="form-control staff" id="staff_id_' + tot_count + '" name="staff_' + tot_count + '">' + $("#staff_dropdown").text() + '</select></span></td>';

                        cols += '<td><div class="input-group"><input type="text" name="time_from_' + tot_count + '" class="form-control time_from time" id="time_from_' + tot_count + '"  aria-invalid="false"><div class="input-group-addon"><i class="fa fa-clock-o"></i></div></div></span></td>';

                        cols += '<td><div class="input-group"><input type="text" name="time_to_' + tot_count + '" class="form-control time_to time" id="time_to_' + tot_count + '"  aria-invalid="false"><div class="input-group-addon"><i class="fa fa-clock-o"></i></div></div></span></td>';

                        cols += '<td><input type="text" class="form-control room_no" name="room_no_' + tot_count + '" id="room_no_' + tot_count + '"/> </td>';
                        cols += '<td class="text-right"><button type="button" class="ibtnDel btn btn-danger btn-sm btn-danger"><i class="fa fa-trash"></i></button></td>';
                        newRow.append(cols);

                        $("table.order-list").append(newRow);


                        $('.staff', newRow).select2({
                            dropdownAutoWidth: true,
                            width: '100%'
                        });

                        $('.subject', newRow).select2({
                            dropdownAutoWidth: true,
                            width: '100%'
                        });
                        tot_count++;
                    });

                    $(document).on("click", ".ibtnDel", function (event) {
                          if($(this).closest('tr').prev('input').val()){
                          if (confirm('<?php echo $this->lang->line("are_you_sure_you_want_to_delete"); ?>')) {
                          $(this).closest("tr").remove();
                            counter -= 1
                             }
                    return false;

                }else{
                      $(this).closest("tr").remove();
                            counter -= 1
                }
                    });

                    $(document).on('click', '.submit_subject_group', function () {
                        var form_id = $(this).closest("form").attr('id');
                        var target = $('.nav-tabs .active a').attr("href"); // activated tab
                        var target_id = $('.nav-tabs .active a').attr("id"); // activated tab
                        var ajax_data = $('.nav-tabs .active a').data(); // activated tab

                    });
                });
            </script>
            
            <script type="text/template" id="staff_dropdown">
                <option value=""><?php echo $this->lang->line('select') ?></option>
                <?php
foreach ($staff as $staff_key => $staff_value) {
    ?>
                    <option value="<?php echo $staff_value['id']; ?>"><?php echo $staff_value['name'] . " " . $staff_value['surname'] . " (" . $staff_value['employee_id'] . ")"; ?></option>
                    <?php
}
?>
            </script>

            <script type="text/template" id="subject_dropdown">
                <option value=""><?php echo $this->lang->line('select') ?></option>
                <?php
foreach ($subject as $subject_key => $subject_value) {
    if ($subject_value->code !== '') {
        $sub_name = $subject_value->name . " (" . $subject_value->code . ")";
    } else {
        $sub_name = $subject_value->name;
    }
    ?>
                    <option value="<?php echo $subject_value->id; ?>" ><?php echo $sub_name; ?></option>
                    <?php
}
?>
            </script>

            <script type="text/javascript">


                $(document).ready(function() {
$("#universal_from").validate({    

 rules: {
            start_time: {
                required: true

            },
            duration: {
                required: true

            },
             interval: {
                required: true

            }
        },
    // Specify validation error messages
    messages: {
   
      start_time: "<?php echo $this->lang->line('required');?>",
      duration: "<?php echo $this->lang->line('required');?>",
      interval: "<?php echo $this->lang->line('required');?>",
    },
     errorClass: 'text-danger',
    validClass: 'valid',

    errorPlacement: function(error, element) {
        $("#errorText").empty();

        if(error[0].htmlFor == 'start_time')
        {
            error.appendTo($(element).parents('div.form-group'));
        }
         if(error[0].htmlFor == 'duration')
        {
            error.appendTo($(element).parents('div.form-group'));
        }
         if(error[0].htmlFor == 'interval')
        {
            error.appendTo($(element).parents('div.form-group'));
        }
       
      },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
        
              let start_time= $('#start_time',form).val();
                     let duration= $('#duration',form).val();
                     let interval= $('#interval',form).val();
                     let froom_no= $('#froom_no',form).val();
                     var interest = $('div.tab-pane.active').find('table#tab_logic');
                 $('tbody  > tr',interest).each(function() {
                     var new_time = moment(start_time, "hh:mm A")
                                        .add(duration, 'minutes')
                                        .format('hh:mm A');

                    var t_form = $(this).find(".time_from").val(start_time);    
                    var t_to = $(this).find(".time_to").val(new_time);    
                    var r_no = $(this).find(".room_no").val(froom_no);    

                    start_time=moment(new_time, "hh:mm A")
                                        .add(interval, 'minutes')
                                        .format('hh:mm A');
                 });
    }
});
});
            </script>