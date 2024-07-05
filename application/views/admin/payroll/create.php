<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 393px;">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php //echo $this->lang->line('human_resource'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-4">
                                <h3 class="box-title"><?php echo $this->lang->line('staff_details'); ?></h3>
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
                                </div></div><!--./col-md-8-->
                            <div class="col-md-4 col-sm-12">
                                <div class="sfborder relative overvisible">
                                    <div class="letest">
                                        <div class="rotatetest"><?php echo $this->lang->line("attendance") ?></div>
                                    </div>
                                    <div class="padd-en-rtl33">
                                        <table class="table mb0 font13" >
                                            <tr>
                                                <th  class="bozero"><?php echo $this->lang->line('month'); ?></th>
                                                <?php foreach ($attendanceType as $key => $value) {?>
                                                    <th class="bozero"><span data-toggle="tooltip" title="<?php echo $value["type"]; ?>"><?php echo strip_tags($value["key_value"]); ?></span></th>
                                                <?php }
?>
                                                <th class="bozero"><span data-toggle="tooltip" title="<?php echo $this->lang->line('approved_leave'); ?>">V</span></th>
                                            </tr>
                                            <?php
foreach ($monthAttendance as $attendence_key => $attendence_value) {
    ?><tr>
                                                    <td><?php echo $this->lang->line(strtolower(date("F", strtotime($attendence_key)))); ?></td>
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
                    <form class="form-horizontal" action="<?php echo site_url('admin/payroll/payslip') ?>" method="post"  id="employeeform">
                        <div class="box-header">
                            <div class="row display-flex">
                                <div class="col-md-4 col-sm-4">
                                    <h3 class="box-title"><?php echo $this->lang->line('earning'); ?></h3>
                                    <button type="button" onclick="add_more()" class="plusign"><i class="fa fa-plus"></i></button>
                                    <div class="sameheight">
                                        <div class="feebox">
                                            <table class="table3" id="tableID">
                                                <tr id="row0">
                                                    <td><input type="text" class="form-control" id="allowance_type" name="allowance_type[]" placeholder="<?php echo $this->lang->line('type'); ?>"></td>
                                                    <td><input type="text" id="allowance_amount" name="allowance_amount[]" class="form-control" value="0"></td>

                                                </tr>
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
                                                <tr id="deduction_row0">
                                                    <td><input type="text" id="deduction_type" name="deduction_type[]" class="form-control" placeholder="<?php echo $this->lang->line('type'); ?>"></td>
                                                    <td><input type="text" id="deduction_amount" name="deduction_amount[]" class="form-control" value="0"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div><!--./col-md-4-->
                                <div class="col-md-4 col-sm-4">
                                    <h3 class="box-title"><?php echo $this->lang->line('payroll_summary'); ?> (<?php echo $currency_symbol ?>)</h3>
                                    <button type="button" onclick="add_allowance()" class="plusign"><i class="fa fa-calculator"></i> <?php echo $this->lang->line('calculate'); ?></button>
                                    <div class="sameheight">
                                        <div class="payrollbox feebox">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label"><?php echo $this->lang->line('basic_salary'); ?></label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" name="basic" value="<?php
if (!empty($result["basic_salary"])) {
    echo $result["basic_salary"];
} else {
    echo "0";
}
?>" id="basic"  type="text" />
                                                </div>
                                            </div><!--./form-group-->
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label"><?php echo $this->lang->line('earning'); ?></label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" name="total_allowance" id="total_allowance"  type="text" />
                                                </div>
                                            </div><!--./form-group-->
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label"><?php echo $this->lang->line('deduction'); ?></label>
                                                <div class="col-sm-8 deductiondred">
                                                    <input class="form-control" name="total_deduction" id="total_deduction" type="text" style="color:#f50000" />
                                                </div>
                                            </div><!--./form-group-->
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label"><?php echo $this->lang->line('gross_salary'); ?></label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" name="gross_salary" id="gross_salary" value="0" type="text" />
                                                </div>
                                            </div><!--./form-group-->
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label"><?php echo $this->lang->line('tax'); ?></label>
                                                <div class="col-sm-8 deductiondred">
                                                    <input class="form-control" name="tax" id="tax" value="0" type="text" />
                                                </div>
                                            </div><!--./form-group-->
                                            <hr/>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label"><?php echo $this->lang->line('net_salary'); ?></label>
                                                <div class="col-sm-8 net_green">
                                                    <input class="form-control greentest"  name="net_salary" id="net_salary"  type="text" />
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
                                    <button type="submit" id="contact_submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
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
        
        var basic_pay = $("#basic").val();
        var allowance_type = document.getElementsByName('allowance_type[]');
        var allowance_amount = document.getElementsByName('allowance_amount[]');
        var tax = $("#tax").val();
        if (tax == '') {
            var tax = 0;
        }

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

        var net_salary = parseFloat(basic_pay) + parseFloat(total_allowance) - parseFloat(total_deduction) - parseFloat(tax);

        $("#total_allowance").val(total_allowance.toFixed(2));
        $("#total_deduction").val(total_deduction.toFixed(2));
        $("#total_allow").html(total_allowance.toFixed(2));
        $("#total_deduc").html(total_deduction.toFixed(2));
        $("#gross_salary").val(gross_salary.toFixed(2));
        $("#net_salary").val(net_salary.toFixed(2));
    }

    function add_more() {

        var table = document.getElementById("tableID");
        var table_len = (table.rows.length);
        var id = parseInt(table_len);
        var row = table.insertRow(table_len).outerHTML = "<tr id='row" + id + "'><td><input type='text' class='form-control' id='allowance_type' name='allowance_type[]' placeholder='<?php echo $this->lang->line("type"); ?>'></td><td><input type='text' class='form-control' id='allowance_amount' name='allowance_amount[]'  value='0'></td><td><button type='button' onclick='delete_row(" + id + ")' class='closebtn'><i class='fa fa-remove'></i></button></td></tr>";
    }

    function delete_row(id) {
        var table = document.getElementById("tableID");
        var rowCount = table.rows.length;
        $("#row" + id).html("");
    }

    function add_more_deduction() {
        var table = document.getElementById("tableID2");
        var table_len = (table.rows.length);
        var id = parseInt(table_len);
        var row = table.insertRow(table_len).outerHTML = "<tr id='deduction_row" + id + "'><td><input type='text' class='form-control' id='deduction_type' name='deduction_type[]' placeholder='<?php echo $this->lang->line("type"); ?>'></td><td><input type='text' id='deduction_amount' name='deduction_amount[]' class='form-control' value='0'></td><td><button type='button' onclick='delete_deduction_row(" + id + ")' class='closebtn'><i class='fa fa-remove'></i></button></td></tr>";
    }

    function delete_deduction_row(id) {
        var table = document.getElementById("tableID2");
        var rowCount = table.rows.length;
        $("#deduction_row" + id).html("");
    }

    $("#contact_submit").click(function (event) {
        var net = $("#net_salary").val();
        if (net == "") {
            $("#err").html("<?php echo $this->lang->line('net_salary_should_not_be_empty'); ?>");
            $("#net_salary").focus();
            return false;
            event.preventDefault();
        } else {
            $("#err").html("");
        }
    });
</script>
