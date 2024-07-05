<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form action="<?php echo current_url(); ?>"  method="POST"  id="form_fees">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
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
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php
if (isset($students)) {
    ?>

                    <div class="ptt10">
                      <div class="bordertop">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('student_transport_fees'); ?></h3>
                        </div>
                        <div class="box-body">
                            <?php

    if (!empty($students)) {
        ?>
    <div class="download_label"><?php echo $this->lang->line('student_transport_fees'); ?></div>
    <div class="table-responsive overflow-visible">
        <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('admission_no'); ?></th>
                                            <th><?php echo $this->lang->line('student_name'); ?></th>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <?php if ($sch_setting->father_name) {?>
                                            <th><?php echo $this->lang->line('father_name'); ?></th>
                                            <?php }?>
                                            <th><?php echo $this->lang->line('date_of_birth'); ?></th>
                                            <th><?php echo $this->lang->line('route_title'); ?></th>
                                            <th><?php echo $this->lang->line('vehicle_number'); ?></th>
                                            <th><?php echo $this->lang->line('pickup_point'); ?></th>
                                            <th class="noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
if (empty($students)) {?>

                                        <?php
} else {
            $count = 1;
            foreach ($students as $student) {

                ?>
                                                <tr>
                                                    <td><?php echo $student['admission_no']; ?></td>
                                                    <td>
                                                        <a href="<?php echo base_url(); ?>student/view/<?php echo $student['id']; ?>"><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?>
                                                        </a>
                                                    </td>
                                                    <td><?php echo $student['class'] . "(" . $student['section'] . ")" ?></td>
                                                    <?php if ($sch_setting->father_name) {?>
                                                    <td><?php echo $student['father_name']; ?></td>
                                                    <?php }?>
                                                    <td><?php echo $this->customlib->dateformat($student['dob']); ?></td>
                                                    <td><?php echo $student['route_title']; ?></td>
                                                    <td><?php echo $student['vehicle_no']; ?></td>
                                                    <td><?php echo $student['pickup_point']; ?></td>
                                                    <td>
                                                        <?php
if ($this->rbac->hasPrivilege('student_transport_fees', 'can_view')) {
                    if ($student['pickup_point'] != "") {

                        ?>
   <button type="button" class="btn btn-default btn-xs route_fees" id="load" data-toggle="tooltip" data-recordid="<?php echo $student['student_session_id']; ?>" data-route_pickup_point_id="<?php echo $student['route_pickup_point_id']; ?>" title="" data-loading-text="<i class='fa fa-spinner fa-spin'></i>" data-original-title="<?php echo $this->lang->line('assign_fees'); ?>" autocomplete="off"><i class="fa fa-tag"></i></button>

                                                    <?php
}
                }
                ?>

                                                     </td>
                                                </tr>
                                                <?php
$count++;
            }
        }
        ?>
                                    </tbody>
                                </table>
    </div>
    <?php
} else {?>
     <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
<?php }?>

                        </div>
                    </div>
                  </div>
                  </div>
                    <?php
} else {

}
?>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
    <div class="clearfix"></div>
</div>
<div id="feeMonthModal" class="modal fade modalmark" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="<?php echo site_url('admin/pickuppoint/add_student_fees'); ?>" id="fee_form">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <?php echo $this->lang->line('assign_fees'); ?></h4>
            </div>
            <div class="modal-body">
            </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
 var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy', 'M' => 'MM']) ?>';
$(document).ready(function(){
$('#feeMonthModal').modal({
    backdrop: 'static',
    keyboard: false,
    show:false
})
});

    var class_id = '<?php echo set_value('class_id', 0) ?>';
    var section_id = '<?php echo set_value('section_id', 0) ?>';
    getSectionByClass(class_id, section_id);
    $(document).on('change', '#class_id', function (e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });

    $(document).on('change', '.class_id', function (e) {
        var class_id = $(this).val();
        var target_dropdown = $(this).closest("div.row").find('select.section_id');
        target_dropdown.html("");
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "GET",
            url: baseurl + "sections/getByClass",
            data: {'class_id': class_id},
            dataType: "json",
            beforeSend: function () {
                target_dropdown.html("").addClass('dropdownloading');
            },
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    var sel = "";
                    if (section_id == obj.section_id) {
                        sel = "selected";
                    }
                    div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                });
                target_dropdown.append(div_data);
            },
            complete: function () {
                target_dropdown.removeClass('dropdownloading');
            }
        });
    });

    function getSectionByClass(class_id, section_id) {
        if (class_id != 0 && class_id !== "") {
            $('#section_id').html("");
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: baseurl + "sections/getByClass",
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

    $(document).on('click', '.route_fees', function () {
        var $this = $(this);
        console.log($this.data(route_pickup_point_id));
        var recordid = $this.data('recordid');
        var route_pickup_point_id = $this.data('route_pickup_point_id');
        $('input[name=recordid]').val(recordid);
          $('#feeMonthModal .modal-body').html("");
        $.ajax({
            type: 'POST',
            url: baseurl + "admin/pickuppoint/student_transport_months",
            data: {'student_session_id': recordid,'route_pickup_point_id':route_pickup_point_id},
            dataType: 'JSON',
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (data) {
                $('#feeMonthModal .modal-body').html(data.page);
                $('#feeMonthModal').modal('show');
                $this.button('reset');
            },
            error: function (xhr) { // if error occured
                alert("Error occured.please try again");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
    });

    $("#fee_form").submit(function (event) {
        event.preventDefault();
        var $form = $(this),
        url = $form.attr('action');
        var $button = $form.find("button[type=submit]:focus");
        $.ajax({
            type: "POST",
            url: url,
            data: $form.serialize(),
            dataType: "JSON",
            beforeSend: function () {
                $button.button('loading');

            },
            success: function (data) {
                if (data.status == 0) {
                    var message = "";
                    $.each(data.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    $('#feeMonthModal').modal('hide');
                    successMsg(data.message);
                }
                $button.button('reset');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $button.button('reset');
            },
            complete: function (data) {
                $button.button('reset');
            }
        });
    });

$(document).on('click','.chkall',function(){
    $('input:checkbox.check_month').prop('checked', this.checked);
});
</script>