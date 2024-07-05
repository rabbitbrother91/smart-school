<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-bus"></i> <?php //echo $this->lang->line('transport'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form role="form" action="<?php echo site_url('admin/route/studenttransportdetails') ?>" method="post" class="">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('class'); ?></label>
                                    <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
foreach ($classlist as $class) {
    ?>
                                            <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) {
        echo "selected=selected";
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
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('section'); ?></label>
                                    <select  id="section_id" name="section_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('route_list'); ?></label>
                                    <select onchange="get_pickup_point(this.value)" class="form-control" id="transport_route_id" name="transport_route_id">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
foreach ($vehroutelist as $vehroute) {
    ?>
                                                        <option value="<?php echo $vehroute['id'] ?>" <?php echo set_select('transport_route_id', $vehroute['id']); ?>>
                                                        <?php echo $vehroute['route_title']; ?>
                                                        </option>
                                                        <?php
}

?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('transport_fees'); ?></span>
                                </div>
                            </div>
                             <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="pickup_point_id"><?php echo $this->lang->line('pickup_point'); ?></label>
                                    <select class="form-control" id="pickup_point_id" name="pickup_point_id">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                </div>
                            </div>
                               <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label for="vehicle_id"><?php echo $this->lang->line('vehicle'); ?></label>
                                    <select class="form-control" id="vehicle_id" name="vehicle_id">
                                           <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
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
                            <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo form_error('student'); ?> <?php echo $this->lang->line('student_transport_report'); ?></h3>
                        </div>
                        <div class="box-body table-responsive overflow-visible">
                            <div class="download_label"><?php echo $this->lang->line('student_transport_report') . " " .
$this->customlib->get_postmessage();
?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                        <th><?php echo $this->lang->line('student_name'); ?></th>
                                        <th><?php echo $this->lang->line('mobile_number'); ?></th>
                                        <th><?php echo $this->lang->line('father_name'); ?></th>
                                        <th><?php echo $this->lang->line('route_title'); ?></th>
                                        <th><?php echo $this->lang->line('vehicle_number'); ?></th>
                                        <th><?php echo $this->lang->line('pickup_point'); ?></th>
                                        <th><?php echo $this->lang->line('driver_name'); ?></th>
                                        <th><?php echo $this->lang->line('driver_contact'); ?></th>
                                        <th class="text-right" width="8%"><?php echo $this->lang->line('fare') . " (" . $currency_symbol . ")"; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
if (empty($resultlist)) {
    ?>

                                        <?php
} else {
    $count = 1;
    foreach ($resultlist as $student) {
        ?>
                                            <tr>
                                                <td><?php echo $student['class'] . " - " . $student["section"]; ?></td>
                                                <td><?php echo $student['admission_no']; ?></td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>student/view/<?php echo $student['id']; ?>"><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?>
                                                    </a>
                                                </td>
                                                <td><?php echo $student['mobileno']; ?></td>
                                                <td><?php echo $student['father_name']; ?></td>
                                                <td><?php echo $student['route_title']; ?></td>
                                                <td><?php echo $student['vehicle_no']; ?></td>
                                                <td><?php echo $student['pickup_name']; ?></td>
                                                <td><?php echo $student['driver_name']; ?></td>
                                                <td><?php echo $student['driver_contact']; ?></td>
                                                <td class="text-right"><?php echo amountFormat($student['fees']); ?></td>
                                            </tr>
                                            <?php
$count++;
    }
}
?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!--./box box-primary-->
            </div><!--./col-md-12-->
        </div>
    </div>
</section>
</div>

<script type="text/javascript">

    $(document).ready(function () {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id', 0) ?>';
        var pickup_point_id = '<?php echo set_value('pickup_point_id', 0) ?>';
        var vehicle_id = '<?php echo set_value('vehicle_id', 0) ?>';
        getSectionByClass(class_id, section_id);
        get_pickup_point($('#transport_route_id').val(),pickup_point_id,vehicle_id);
    });

        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            getSectionByClass(class_id, 0);
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

    function get_pickup_point(transport_route_id,pickup_point_id,vehicle_id){
           if (transport_route_id != "") {
        $('#pickup_point_id').html('');
        $('#vehicle_id').html('');
         var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
         var vehicle_div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
                url: '<?php echo base_url(); ?>admin/pickuppoint/getpickuppointsbyroute/',
                type: "POST",
                data:{transport_route_id:transport_route_id},
                dataType: 'json',
                 beforeSend: function() {
                       $('#pickup_point_id').html('');
                       $('#vehicle_id').html('');
                },
                success: function(res) {

                    $.each(res.vehicle_route_pickups, function (index, value) {
                         let sel = "";
                        if (pickup_point_id == value.pickup_point_id) {
                            sel = "selected";
                        }

                        div_data += "<option value=" + value.pickup_point_id + " " + sel + ">" + value.pickup_point + "</option>";
                    });

                    $('#pickup_point_id').html(div_data);
                      $.each(res.routes_vehicle, function (index, value) {
                         let sel = "";
                        if (vehicle_id == value.id) {
                            sel = "selected";
                        }
                        vehicle_div_data += "<option value=" + value.id  + " " + sel + ">" +  value.vehicle_no + "</option>";
                    });

                    $('#vehicle_id').html(vehicle_div_data);

                },
                   error: function(xhr) { // if error occured
                   alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            },
            complete: function() {

            }
            });
    }
    }
</script>