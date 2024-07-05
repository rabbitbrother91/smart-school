<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php //echo $this->lang->line('human_resource'); ?>
            <?php
if ($this->rbac->hasPrivilege('approve_leave_request', 'can_add')) {
    ?>
                <small class="pull-right"><a href="#addleave" onclick="addLeave()" role="button" class="btn btn-primary btn-sm checkbox-toggle pull-right edit_setting" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $this->lang->line('add_leave_request'); ?></a></small>
            <?php }?></h1>

    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix pt5"><?php echo $this->lang->line('approve_leave_request'); ?></h3> <?php
if ($this->rbac->hasPrivilege('approve_leave_request', 'can_add')) {
    ?>
                            <small class="pull-right"><a href="#addleave" onclick="addLeave()" role="button" class="btn btn-primary btn-sm checkbox-toggle pull-right edit_setting" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $this->lang->line('add_leave_request'); ?></a></small>
                        <?php }?>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="tab-pane active table-responsive no-padding overflow-visible">
                                    <div class="download_label"><?php echo $this->lang->line('approve_leave_request'); ?></div>
                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                        <th><?php echo $this->lang->line('staff'); ?></th>
                                        <th><?php echo $this->lang->line('leave_type'); ?></th>
                                        <th><?php echo $this->lang->line('leave_date'); ?></th>
                                        <th><?php echo $this->lang->line('days'); ?></th>
                                        <th><?php echo $this->lang->line('apply_date'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </thead>
                                        <tbody>
                                            <?php
$i = 0;
foreach ($leave_request as $key => $value) {
    ?>
                                                <tr>

                                                    <td><?php echo $value['name'] . " " . $value['surname'] . ' (' . $value['employee_id'] . ')'; ?></td>
                                                    <td><?php echo $value["type"] ?></td>
                                                    <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($value["leave_from"])) ?> - <?php echo date($this->customlib->getSchoolDateFormat(), strtotime($value["leave_to"])) ?></td>

                                                    <td><?php echo $value["leave_days"]; ?></td>
                                                    <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($value["date"]));  ?></td>
                                                    <?php
if ($value["status"] == "approved") {
        $status1 = 'approve';
        $label = "class='label label-success'";
    } else if ($value["status"] == "pending") {
        $status1 = 'pending';
        $label = "class='label label-warning'";
    } else if ($value["status"] == "disapprove" || $value["status"] == "disapproved") {
        $status1 = 'disapprove';
        $label = "class='label label-danger'";
    }
    ?>
                                                    <td><span data-toggle="popover" class="detail_popover" data-original-title="" title=""><small <?php echo $label ?>><?php echo $status[$status1]; ?></small></span>

                                                        <div class="fee_detail_popover" style="display: none"><?php echo $this->lang->line('submitted_by'); ?>: <?php echo $value['applied_by']; ?></div></td>
                                                    <td class="pull-right no-print white-space-nowrap">
                                                        <a href="#leavedetails" onclick="getRecord('<?php echo $value["id"] ?>')" role="button" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>" ><i class="fa fa-reorder"></i></a>

                                                        <?php if ($value["applied_by"] == $this->customlib->getAdminSessionUserName()) {
        ?>
                                                            <?php
if ($this->rbac->hasPrivilege('approve_leave_request', 'can_edit')) {
            ?>
                                                                <a href="#addleave" onclick="editRecord('<?php echo $value["id"] ?>')" role="button" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>" ><i class="fa fa-pencil"></i></a>
                                                            <?php }?>

                                                        <?php }?>
                                                        <?php if (!empty($value['document_file'])) {?>
                                                            <a href="<?php echo base_url(); ?>admin/leaverequest/downloadleaverequestdoc/<?php echo $value['staff_id'] . "/" . $value['id']; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                                <i class="fa fa-download"></i>
                                                            </a>
                                                        <?php }
    ?>
                                                        <?php if ($value["applied_by"] == $this->customlib->getAdminSessionUserName()) {?>

                                                            <a onclick="getDelete('<?php echo $value["id"] ?>')"  class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" ><i class="fa fa-remove"></i></a>

                                                            <?php
} else {

        if ($this->rbac->hasPrivilege('approve_leave_request', 'can_delete')) {
            ?>
                                                                <a onclick="getDelete('<?php echo $value["id"] ?>','<?php echo $value["staff_id"] ?>')"  class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" ><i class="fa fa-remove"></i></a>
                                                            <?php }?>
                                                        <?php }
    ?>
                                                    </td>
                                                </tr>
                                                <?php
$i++;
}
?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="leavedetails" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('details'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form role="form" id="leavedetails_form" action="">
                        <div class="col-md-12 table-responsive">
                            <table class="table mb0 table-striped table-bordered examples">
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('name'); ?></th>
                                    <td width="35%"><span id='name'></span></td>
                                    <th width="15%"><?php echo $this->lang->line('staff_id'); ?></th>
                                    <td width="35%"><span id="employee_id"></span>
                                        <span class="text-danger"><?php echo form_error('leave_request_id'); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo $this->lang->line('submitted_by'); ?></th>
                                    <td><span id="appliedby"></span></td>
                                    <th><?php echo $this->lang->line('leave_type'); ?></th>
                                    <td><span id="leave_type"></span>
                                        <input id="leave_request_id" name="leave_request_id" placeholder="" type="hidden" class="form-control" />
                                        <span class="text-danger"><?php echo form_error('leave_request_id'); ?></span></td>
                                </tr>
                                <tr>
                                    <th><?php echo $this->lang->line('leave'); ?></th>
                                    <td><span id='leave_from'></span> - <label> </label><span id='leave_to'> </span> (<span id='days'></span>)
                                        <span class="text-danger"><?php echo form_error('leave_from'); ?></span></td>
                                    <th><?php echo $this->lang->line('apply_date'); ?></th>
                                    <td><span id="applied_date"></span></td>
                                </tr>
                                <tr>
                                    <?php if ($this->rbac->hasPrivilege('approve_leave_request', 'can_edit')) {
    ?>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <td>
                                            <label class="radio-inline">
                                                <input type="radio" value="<?php echo "pending"; ?>" name="status" checked ><?php echo $status["pending"]; ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" value="<?php echo "approved"; ?>" name="status"><?php echo $status["approve"]; ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" value="<?php echo "disapproved"; ?>" name="status"><?php echo $status["disapprove"]; ?>
                                            </label>
                                            <span class="text-danger"><?php echo form_error('status'); ?></span>
                                        </td>
                                    <?php }
?>
                                    <th><?php echo $this->lang->line('reason'); ?></th>
                                    <td><span id="remark"> </span></td>
                                </tr>
                                <tr>
                                    <th><?php echo $this->lang->line('note'); ?></th>
                                </tr>
                                <tr>
                                    <td colspan=" 4">
                                        <div id="reason">
                                            <textarea class="form-control" style="resize: none;" rows="2" id="detailremark" name="detailremark" placeholder=""></textarea>
                                            <span class="text-danger"><?php echo form_error('address'); ?></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <?php
if ($this->rbac->hasPrivilege('approve_leave_request', 'can_edit')) {
    ?>
                                        <td colspan="4">
                                            <button type="button" style="width: auto;"  class="btn btn-primary submit_schsetting pull-right" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('save'); ?></button>
                                        </td>
                                    <?php }?>
                                </tr>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="addleave" class="modal fade " role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title leave_title"></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form role="form" id="addleave_form" method="post" enctype="multipart/form-data" action="">
                        <div class="form-group  col-xs-12 col-sm-12 col-md-12 col-lg-6">
                            <label>
                                <?php echo $this->lang->line('role'); ?></label><small class="req"> *</small>
                            <select name="role" id="role"  class="form-control" onchange="getEmployeeName(this.value)">
                                <option value="" ><?php echo $this->lang->line('select') ?></option>
                                <?php foreach ($staffrole as $rolekey => $rolevalue) {
    ?>
                                    <option value="<?php echo $rolevalue["id"] ?>"><?php echo $rolevalue["type"] ?></option>
                                <?php }?>
                            </select>
                            <span class="text-danger"><?php echo form_error('role'); ?></span>
                        </div>
                        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6">
                            <label><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                            <select name="empname" id="empname" value=""onchange="getLeaveTypeDDL(this.value)"  class="form-control">
                                <option value="" selected><?php echo $this->lang->line('select') ?></option>
                            </select>
                            <span class="text-danger"><?php echo form_error('empname'); ?></span>
                        </div>
                        <div class="form-group  col-xs-12 col-sm-12 col-md-12 col-lg-6">
                            <label><?php echo $this->lang->line('apply_date'); ?></label><small class="req"> *</small>
                            <input type="text" id="applieddate" name="applieddate" value="<?php echo date($this->customlib->getSchoolDateFormat()) ?>" class="form-control date">
                        </div>
                        <div class="form-group  col-xs-12 col-sm-12 col-md-12 col-lg-6 ">
                            <label>
                                <?php echo $this->lang->line('leave_type'); ?></label><small class="req"> *</small>
                            <div id="leavetypeddl">
                                <select name="leave_type" id="leave_type" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                    <?php foreach ($leavetype as $leave_key => $leave_value) {
    ?>
                                        <option value="<?php echo $leave_value["id"] ?>"><?php echo $leave_value["type"] ?></option>
                                    <?php }
?>
                                </select>
                            </div>
                            <span class="text-danger"><?php echo form_error('leave_type'); ?></span>
                        </div>
                          <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6">
                            <label><?php echo $this->lang->line('leave_from_date'); ?></label><small class="req"> *</small>
                                <input type="text" readonly id="leave_from_date" name="leave_from_date" class="form-control date" >
                            <!-- /.input group -->
                        </div>
                         <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6">
                            <label><?php echo $this->lang->line('leave_to_date'); ?></label><small class="req"> *</small>
                                <input type="text" readonly id="leave_to_date" name="leave_to_date" class="form-control date" >
                            <!-- /.input group -->
                        </div>
                        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6">
                            <label><?php echo $this->lang->line('reason'); ?></label><br/>
                            <textarea name="reason" id="reason" style="resize: none;" rows="4" class="form-control"></textarea>
                            <input type="hidden" name="leaverequestid" id="leaverequestid">
                        </div>
                        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6" id="reason">
                            <label><?php echo $this->lang->line('note'); ?></label>
                            <textarea class="form-control" style="resize: none;" rows="4" id="remark" name="remark" placeholder=""></textarea>
                            <span class="text-danger"><?php echo form_error('remark'); ?></span>
                        </div>
                        <div class="form-group  col-xs-12 col-sm-12 col-md-12 col-lg-6">
                            <label><?php echo $this->lang->line('attach_document'); ?></label>
                            <input type="file" id="file" name="userfile" class="filestyle form-control">
                            <input type="hidden" id="filename" name="filename" >
                        </div>
                        <div class="form-group  col-xs-12 col-sm-12 col-md-12 col-lg-6">
                            <label><?php echo $this->lang->line('status'); ?> </label>
                            <br/>
                            <label class="radio-inline">
                                <input type="radio" value="<?php echo "pending" ?>" name="addstatus" checked><?php echo $status["pending"] ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" value="<?php echo "approved" ?>"  name="addstatus" ><?php echo $status["approve"] ?></label>
                            <label class="radio-inline">
                                <input type="radio" value="<?php echo "disapproved" ?>"  name="addstatus"><?php echo $status["disapprove"] ?></label>
                            <span class="text-danger"><?php echo form_error('addstatus'); ?></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <button type="submit" id="submitbtn" class="btn btn-primary submit_addLeave pull-right" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('save'); ?></button>
                            <input type="reset"  name="resetbutton" id="resetbutton" style="display:none">
                            <button type="button" style="display: none;" id="clearform" onclick="clearForm(this.form)" class="btn btn-primary submit_addLeave pull-right" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('clear'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    /*--dropify--*/
    $(document).ready(function () {
        // Basic
        $('.filestyle').dropify();
    });
    /*--end dropify--*/
</script>

<script type="text/javascript">
    function getDelete(id,staff_id) {
        var result = confirm("<?php echo $this->lang->line('delete_confirm'); ?>");
        if (result) {
            $.ajax({
                url: "<?php echo base_url(); ?>admin/leaverequest/remove/" + id+'/'+ staff_id,
                type: "POST",

                success: function (res)
                {
                    successMsg('<?php echo $this->lang->line("delete_message"); ?>');
                    window.location.reload(true);
                },
                error: function (xhr) { // if error occured
                    alert('<?php echo $this->lang->line("error_occurred_please_try_again"); ?>');
                },
                complete: function () {

                }
            });
        }
    }

    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            title: '',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });

        $('#reservation').daterangepicker({
            timePickerIncrement: 5, locale: {
                format: calendar_date_time_format
            }});
        });

    function addLeave() {
        $('.leave_title').html('<?php echo $this->lang->line('add_details'); ?>')
        $('input[type=text]').val('');
        $('textarea[name="reason"]').text('');
        $("#resetbutton").click();
        $("#clearform").click();

        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';

        var date = '<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>';
        $('input[type=text][name=applieddate]').val(date);

        $('#addleave').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    }

    function getRecord(id) {

        $('input:radio[name=status]').attr('checked', false);
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/leaverequest/leaveRecord',
            type: 'POST',
            data: {id: id},
            dataType: "json",
            success: function (result) {

                $('input[name="leave_request_id"]').val(result.id);
                $('#employee_id').html(result.employee_id);
                $('#name').html(result.name + ' ' + result.surname);
                $('#leave_from').html(result.leavefrom);
                $('#leave_to').html(result.leaveto);
                $('#leave_type').html(result.type);
                $('#days').html(result.leave_days + ' Days');
                $('#remark').html(result.employee_remark);
                $('#applied_date').html(result.date);
                $('#appliedby').html(result.applied_by);
                $("#detailremark").text(result.admin_remark);

                if (result.status == 'approved') {
                    $('input:radio[name=status]')[1].checked = true;
                } else if (result.status == 'pending') {
                    $('input:radio[name=status]')[0].checked = true;
                } else if (result.status == 'disapproved') {
                    $('input:radio[name=status]')[2].checked = true;
                }
            }
        });

        $('#leavedetails').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    }
    ;

    $(document).on('click', '.submit_schsetting', function (e) {
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: '<?php echo site_url("admin/leaverequest/leaveStatus") ?>',
            type: 'post',
            data: $('#leavedetails_form').serialize(),
            dataType: 'json',
            success: function (data) {

                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);
                    window.location.reload(true);
                }

                $this.button('reset');
            }
        });
    });

    function checkStatus(status) {
        if (status == 'approved') {
            $("#reason").hide();
        } else if (status == 'pending') {
            $("#reason").hide();
        } else if (status == 'disapprove') {
            $("#reason").show();
        }
    }

    $(document).ready(function (e) {
        $("#addleave_form").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo site_url("admin/leaverequest/addLeave") ?>",
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                  beforeSend: function() {
                    $("#submitbtn").button('loading');
                 },
                success: function (data)
                {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                        $("#submitbtn").button('reset');
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                },
                error: function(xhr) { // if error occured
        $("#submitbtn").button('reset');
    },
    complete: function() {
        $("#submitbtn").button('reset');
    }
            });
        }));
    });

    function getEmployeeName(role) {
        var ne = "";
        var base_url = '<?php echo base_url() ?>';
        $("#empname").html('<option value=><?php echo $this->lang->line('select') ?></option>');
        var div_data = "";
        $.ajax({
            type: "POST",
            url: base_url + "admin/staff/getEmployeeByRole",
            data: {'role': role},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data += "<option value='" + obj.id + "' >" + obj.name + " " + obj.surname + " " + "(" + obj.employee_id + ")</option>";
                });

                $('#empname').append(div_data);
            }
        });
    }

    function setEmployeeName(role, id = '') {
        var ne = "";
        var base_url = '<?php echo base_url() ?>';
        $("#empname").html("<option value=><?php echo $this->lang->line('select') ?></option>");
        var div_data = "";
        $.ajax({
            type: "POST",
            url: base_url + "admin/staff/getEmployeeByRole",
            data: {'role': role},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    if (obj.employee_id == id) {
                        ne = 'selected';
                    } else {
                        ne = "";
                    }

                    div_data += "<option value='" + obj.id + "' " + ne + " >" + obj.name + " " + obj.surname + " " + "(" + obj.employee_id + ")</option>";
                });

                $('#empname').append(div_data);
            }
        });
    }

    function getLeaveTypeDDL(id, lid = '') {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/leaverequest/countLeave/' + id,
            type: 'POST',
            data: {lid: lid},
            success: function (result) {
                $("#leavetypeddl").html(result);
            }
        });
    }

    function editRecord(id) {
        $('.leave_title').html('<?php echo $this->lang->line('edit_details'); ?>');
        var leave_from = '05/01/2018';
        var leave_to = '05/10/2018';
        $('textarea[name="reason"]').text('');
        $('textarea[name="remark"]').text('');
        $('input:radio[name=addstatus]').attr('checked', false);

        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/leaverequest/leaveRecord',
            type: 'POST',
            data: {id: id},
            dataType: "json",
            success: function (result) {

                leave_from = result.leavefrom;
                leave_to = result.leaveto;

                setEmployeeName(result.staff_role, result.employee_id);
                getLeaveTypeDDL(result.staff_id, result.lid);
                $('#role').val(result.staff_role);

                $('input[name="applieddate"]').val(result.date);
                $('input[name="leavefrom"]').val(new Date(result.leave_from).toString(calendar_date_time_format));
                $('input[name="filename"]').val(result.document_file);

                $('#leave_from_date').val(result.leavefrom);
                $('#leave_to_date').val(result.leaveto);

                $('input[name="leaverequestid"]').val(id);
                $('textarea[name="reason"]').text(result.employee_remark);
                $('textarea[name="remark"]').text(result.admin_remark);

                if (result.status == 'approved') {
                    $('input:radio[name=addstatus]')[1].checked = true;
                } else if (result.status == 'pending') {
                    $('input:radio[name=addstatus]')[0].checked = true;
                } else if (result.status == 'disapprove') {
                    $('input:radio[name=addstatus]')[2].checked = true;
                }

                $('#reservation').daterangepicker({
                    startDate: leave_from,
                    endDate: leave_to,
                    timePickerIncrement: 5, locale: {
                        format: calendar_date_time_format
                    }
                });
            }
        });

        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['m' => 'mm', 'd' => 'dd', 'Y' => 'yyyy']) ?>';

        $('#addleave').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    }
    ;

    function clearForm(oForm) {
        var elements = oForm.elements;
        for (i = 0; i < elements.length; i++) {
            field_type = elements[i].type.toLowerCase();
            switch (field_type) {

                case "text":
                case "password":
                case "hidden":

                    elements[i].value = "";
                    break;

                case "select-one":
                case "select-multi":
                    elements[i].selectedIndex = "";
                    break;

                default:
                    break;
            }
        }
    }
</script>