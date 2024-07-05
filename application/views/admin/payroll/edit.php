<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 393px;">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-4">
                                <h3 class="box-title"><?php echo $this->lang->line("edit_payroll_for"); ?> : <?php echo $this->lang->line(strtolower($month)); ?></h3>
                            </div>
                            <div class="col-md-8 ">
                                <div class="btn-group pull-right">
                                    <a href="<?php echo base_url() ?>admin/payroll" type="button" class="btn btn-primary btn-xs">
                                        <i class="fa fa-arrow-left"></i> </a>
                                </div>
                            </div>
                        </div>
                    </div><!--./box-header-->
                    <div class="box-body" style="padding-top:0;">
                        <div class="row">
                            <div class="col-md-8 col-sm-12">
                                <div class="sfborder">
                                    <div class="col-md-2">
                                        <div class="row">
                                                                 <?php
$image = $result['image'];
if (!empty($image)) {

    $file = $result['image'];
} else {

    $file = "no_image.png";
}
$image=$this->media_storage->getImageURL("uploads/staff_images/" . $file);
?>
    <img width="115" height="115" class="round5" src="<?php echo $image ?>" alt="No Image">
                                        </div>
                                    </div>

                                    <div class="col-md-10">
                                        <div class="row">
                                            <table class="table mb0 font13">
                                                <tbody>
                                                    <tr>
                                                        <th class="bozero"><?php echo $this->lang->line("name"); ?></th>
                                                        <td class="bozero"><?php echo $result["name"] . " " . $result["surname"] ?></td>
                                                        <th class="bozero"><?php echo $this->lang->line('staff_id'); ?></th>
                                                        <td class="bozero"><?php echo $result["employee_id"] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <?php if ($sch_setting->staff_phone) {?>
                                                            <th><?php echo $this->lang->line('phone'); ?></th>
                                                        <?php }?>
                                                        <td><?php echo $result["contact_no"] ?></td>
                                                        <th><?php echo $this->lang->line('email'); ?></th>
                                                        <td><?php echo $result["email"] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <?php if ($sch_setting->staff_epf_no) {?>
                                                            <th><?php echo $this->lang->line('epf_no'); ?></th>
                                                            <td><?php echo $result["epf_no"] ?></td>
                                                        <?php }?>
                                                        <th><?php echo $this->lang->line('role'); ?></th>
                                                        <td><?php echo $result["user_type"] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <?php if ($sch_setting->staff_department) {?>
                                                            <th><?php echo $this->lang->line('department'); ?></th>
                                                            <td><?php echo $result["department"] ?></td>
                                                        <?php }if ($sch_setting->staff_designation) {?>
                                                            <th><?php echo $this->lang->line('designation'); ?></th>
                                                            <td><?php echo $result["designation"] ?>   </td>
                                                        <?php }?>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div><!--./col-md-8-->
                            <div class="col-md-4 col-sm-12">
                                <div class="sfborder relative overvisible">
                                    <div class="letest">
                                        <div class="rotatetest"><?php echo $this->lang->line("attendance") ?></div>
                                    </div>
                                    <div class="padd-en-rtl33">
                                        <table class="table mb0 font13" >
                                            <tr>
                                                <th  class="bozero"><?php echo $this->lang->line('month'); ?></th>
                                                <?php
foreach ($attendanceType as $key => $value) {
    $lang = strtolower($value["type"]);
    ?>
                                                    <th class="bozero"><span data-toggle="tooltip" title="<?php echo $this->lang->line($lang); ?>"><?php echo strip_tags($value["key_value"]); ?></span></th>
                                                <?php }
?>

                                                <th class="bozero"><span data-toggle="tooltip" title="<?php echo $this->lang->line('approved_leave'); ?>">V</span></th>
                                            </tr>
                                            <?php
foreach ($monthAttendance as $attendence_key => $attendence_value) {
    ?><tr>
                                                    <td><?php echo date("F", strtotime($attendence_key)); ?></td>
                                                    <td><?php echo $attendence_value['present'] ?></td>
                                                    <td><?php echo $attendence_value['late']; ?></td>
                                                    <td><?php echo $attendence_value['absent']; ?></td>
                                                    <td><?php echo $attendence_value['half_day']; ?></td>
                                                    <td><?php echo $attendence_value['holiday']; ?></td>
                                                    <td><?php echo $monthLeaves[date("m", strtotime($attendence_key))]; ?></td>
                                                </tr>
                                                <?php
}
?>
                                            <tr>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div><!--./col-md-8-->
                            <div class="col-md-12">
                                <div style="background: #dadada; height: 1px; width: 100%; clear: both; margin-bottom: 10px;"></div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <form class="form-horizontal" action="<?php echo site_url('admin/payroll/editpayroll') ?>" method="post"  id="employeeform">
                        <input type="hidden" name="role" value="<?php echo $result["user_type"] ?>">
                        <input type="hidden" name="id" value="<?php echo $employee_payroll["id"] ?>">

                        <div class="box-header">
                            <div class="row display-flex">
                                <div class="col-md-4 col-sm-4">
                                    <h3 class="box-title"><?php echo $this->lang->line('earning'); ?></h3>
                                    <button type="button" onclick="add_more()" class="plusign"><i class="fa fa-plus"></i></button>
                                    <div class="sameheight">
                                        <div class="feebox">
                                            <table class="table3" id="tableID">
                                                <?php
if (!empty($earnings)) {
    $earning_count = 0;
    foreach ($earnings as $earning_key => $earning_value) {
        ?>
                                                        <input type="hidden" name="allowance_prev_id[]" value="<?php echo $earning_value['id'] ?>" />
  <tr id="row<?php echo $earning_count; ?>">
                                                    <td>
                                                        <input type="text" class="form-control" value="<?php echo $earning_value['allowance_type'] ?>" id="allowance_type" name="allowance_type[]" placeholder="<?php echo $this->lang->line('type'); ?>"></td>
                                                    <td><input type="text" id="allowance_amount" name="allowance_amount[]" class="form-control" value="<?php echo convertBaseAmountCurrencyFormat($earning_value['amount']) ?>"></td>
<td><button type="button" onclick="delete_row(<?php echo $earning_count ?>)" class="closebtn" autocomplete="off"><i class="fa fa-remove"></i></button></td>
                                                </tr>
  <?php
$earning_count++;
    }
} else {
    ?>
  <tr id="row0">
                                                    <td>
                                                         <input type="hidden" name="allowance_prev_id[]" value="0" />
                                                         <input type="text" class="form-control" id="allowance_type" name="allowance_type[]" placeholder="<?php echo $this->lang->line('type'); ?>"></td>
                                                    <td><input type="text" id="allowance_amount" name="allowance_amount[]" class="form-control" value="0"></td>
<td><button type="button" onclick="delete_row(0)" class="closebtn" autocomplete="off"><i class="fa fa-remove"></i></button></td>
                                                </tr>
    <?php
}
?>

                                            </table>
                                        </div>
                                    </div>
                                </div><!--./col-md-4-->
                                <div class="col-md-4 col-sm-4">
                                    <h3 class="box-title"><?php echo $this->lang->line('deduction'); ?></h3>
                                    <button type="button" onclick="add_more_deduction()" class="plusign"><i class="fa fa-plus"></i></button>
                                    <div class="sameheight">
                                        <div class="feebox">
                                            <table class="table3" id="tableID2">
                                                  <?php
if (!empty($deductions)) {
    $deduction_count = 0;
    foreach ($deductions as $deduction_key => $deduction_value) {
        ?>
                                                        <input type="hidden" name="deduction_prev_id[]" value="<?php echo $deduction_value['id'] ?>" />

                                                <tr id="deduction_row<?php echo $deduction_count; ?>">
                                                    <td>
                                                        <input type="text" id="deduction_type" name="deduction_type[]" class="form-control" value="<?php echo $deduction_value['allowance_type'] ?>"  placeholder="<?php echo $this->lang->line('type'); ?>"></td>
                                                    <td>
                                                        <input type="text" id="deduction_amount" name="deduction_amount[]" class="form-control" value="<?php echo convertBaseAmountCurrencyFormat($deduction_value['amount']) ?>">
                                                    </td>

<td><button type="button" onclick="delete_deduction_row(<?php echo $deduction_count ?>)" class="closebtn" autocomplete="off"><i class="fa fa-remove"></i></button></td>
                                                </tr>
  <?php
$deduction_count++;
    }
} else {
    ?>
                                                <tr id="deduction_row0">
                                                    <td>
                                                          <input type="hidden" name="deduction_prev_id[]" value="0" />
                                                        <input type="text" id="deduction_type" name="deduction_type[]" class="form-control" placeholder="<?php echo $this->lang->line('type'); ?>"></td>
                                                    <td><input type="text" id="deduction_amount" name="deduction_amount[]" class="form-control" value="0"></td>
<td><button type="button" onclick="delete_deduction_row(0)" class="closebtn" autocomplete="off"><i class="fa fa-remove"></i></button></td>
                                                </tr>
    <?php
}
?>
                                            </table>
                                        </div>
                                    </div>
                                </div><!--./col-md-4-->
                                <div class="col-md-4 col-sm-4">
                                    <h3 class="box-title"><?php echo $this->lang->line('payroll_summary'); ?>(<?php echo $currency_symbol ?>)</h3>
                                    <button type="button" onclick="add_allowance()" class="plusign"><i class="fa fa-calculator"></i> <?php echo $this->lang->line('calculate'); ?></button>
                                    <div class="sameheight">
                                        <div class="payrollbox feebox">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label"><?php echo $this->lang->line('basic_salary'); ?></label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" name="basic" value="<?php echo convertBaseAmountCurrencyFormat($employee_payroll['basic']); ?>" id="basic"  type="text" />
                                                </div>
                                            </div><!--./form-group-->
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label"><?php echo $this->lang->line('earning'); ?></label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" name="total_allowance" id="total_allowance"  type="text" value="<?php echo convertBaseAmountCurrencyFormat($employee_payroll['total_allowance']); ?>" />
                                                </div>
                                            </div><!--./form-group-->
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label"><?php echo $this->lang->line('deduction'); ?></label>
                                                <div class="col-sm-8 deductiondred">
                                                    <input class="form-control" name="total_deduction" id="total_deduction" type="text" style="color:#f50000" value="<?php echo convertBaseAmountCurrencyFormat($employee_payroll['total_deduction']); ?>"/>
                                                </div>
                                            </div><!--./form-group-->
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label"><?php echo $this->lang->line('gross_salary'); ?></label>
                                                <div class="col-sm-8">
                    <input class="form-control" name="gross_salary" id="gross_salary" type="text" value="<?php echo convertBaseAmountCurrencyFormat(($employee_payroll['basic'] + $employee_payroll['total_allowance']) - $employee_payroll['total_deduction']); ?>"/>
                                                </div>
                                            </div><!--./form-group-->
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label"><?php echo $this->lang->line('tax'); ?></label>
                                                <div class="col-sm-8 deductiondred">
                                                    <input class="form-control" name="tax_percent" id="tax_percent"  type="text" value="<?php echo convertBaseAmountCurrencyFormat($employee_payroll['tax']); ?>"/>
                                                </div>
                                            </div><!--./form-group-->
                                            <hr/>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label"><?php echo $this->lang->line('net_salary'); ?></label>
                                                <div class="col-sm-8 net_green">
                                                    <input class="form-control greentest"  name="net_salary" id="net_salary"  type="text" value="<?php echo convertBaseAmountCurrencyFormat($employee_payroll['net_salary']); ?>"/>
                                                    <span class="text-danger" id="err"><?php echo form_error('net_salary'); ?></span>
                                                    <input class="form-control" name="staff_id" value="<?php echo $result["id"]; ?>"  type="hidden" />
                                                    <input class="form-control" name="month" value="<?php echo $month; ?>"  type="hidden" />
                                                    <input class="form-control" name="year" value="<?php echo $year; ?>"  type="hidden" />
                                                    <input class="form-control" name="status" value="generated"  type="hidden" />
                                                </div>
                                            </div><!--./form-group-->
                                        </div>
                                    </div>
                                </div><!--./col-md-4-->
                                <div class="col-md-12 col-sm-12">
                                    <button type="submit" id="contact_submit" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                                </div><!--./col-md-12-->
                                </form>
                            </div><!--./row-->
                        </div><!--./box-header-->
                </div>
            </div>
            <!--/.col (left) -->
        </div>
    </section>
</div>

<script type="text/javascript">
    function add_allowance() {
        $("#net_salary").val('');
        $("#gross_salary").val('');
        var basic_pay = $("#basic").val();
       if(basic_pay > 0){ 
        var allowance_type = document.getElementsByName('allowance_type[]');
        var allowance_amount = document.getElementsByName('allowance_amount[]');
        var total_allowance = 0;
        var deduction_type = document.getElementsByName('deduction_type[]');
        var deduction_amount = document.getElementsByName('deduction_amount[]');
        var total_deduction = 0;
        for (var i = 0; i < allowance_amount.length; i++) {
            var inp = allowance_amount[i];
            if (inp.value == '') {
                var inpvalue = 0;
            } else {
                var inpvalue = inp.value;
            }

            total_allowance += parseFloat(inpvalue);
        }

        for (var j = 0; j < deduction_amount.length; j++) {
            var inpd = deduction_amount[j];
            if (inpd.value == '') {
                var inpdvalue = 0;
            } else {
                var inpdvalue = inpd.value;
            }
            total_deduction += parseFloat(inpdvalue);
        }

        var gross_salary = parseFloat(basic_pay) + parseFloat(total_allowance) - parseFloat(total_deduction);

        var tax = $("#tax_percent").val();
        if (tax == '') {
            var tax = 0;
        }

        var net_salary = parseFloat(basic_pay) + parseFloat(total_allowance) - parseFloat(total_deduction) - parseFloat(tax);

        $("#total_allowance").val(total_allowance.toFixed(2));
        $("#total_deduction").val(total_deduction.toFixed(2));
        $("#total_allow").html(total_allowance.toFixed(2));
        $("#total_deduc").html(total_deduction.toFixed(2));
        $("#gross_salary").val(gross_salary.toFixed(2));
        $("#net_salary").val(net_salary.toFixed(2));
    }
    }

    function add_more() {
        var table = document.getElementById("tableID");
        var table_len = (table.rows.length);
        var id = parseInt(table_len);
        var row = table.insertRow(table_len).outerHTML = "<tr id='row" + id + "'><td><input type='hidden' name='allowance_prev_id[]' value='0' /><input type='text' class='form-control' id='allowance_type' name='allowance_type[]' placeholder='<?php echo $this->lang->line("type"); ?>'></td><td><input type='text' class='form-control' id='allowance_amount' name='allowance_amount[]'  value='0'></td><td><button type='button' onclick='delete_row(" + id + ")' class='closebtn'><i class='fa fa-remove'></i></button></td></tr>";
    }

    function delete_row(id) {
        var table = document.getElementById("tableID");
        var rowCount = table.rows.length;
        $("#row" + id).remove();
    }

    function add_more_deduction() {
        var table = document.getElementById("tableID2");
        var table_len = (table.rows.length);
        var id = parseInt(table_len);
        var row = table.insertRow(table_len).outerHTML = "<tr id='deduction_row" + id + "'><td><input type='hidden' name='deduction_prev_id[]' value='0' /><input type='text' class='form-control' id='deduction_type' name='deduction_type[]' placeholder='<?php echo $this->lang->line("type"); ?>'></td><td><input type='text' id='deduction_amount' name='deduction_amount[]' class='form-control' value='0'></td><td><button type='button' onclick='delete_deduction_row(" + id + ")' class='closebtn'><i class='fa fa-remove'></i></button></td></tr>";
    }

    function delete_deduction_row(id) {
        var table = document.getElementById("tableID2");
        var rowCount = table.rows.length;
        $("#deduction_row" + id).html("");
    }

    $("#contact_submit").click(function (event) {
        var net = $("#net_salary").val();
        if (net == "") {
            $("#err").html("<?php echo $this->lang->line('net_salary_should_not_be_empty') ?>");
            $("#net_salary").focus();
            return false;
            event.preventDefault();
        } else {
            $("#err").html("");
        }
    });
</script>