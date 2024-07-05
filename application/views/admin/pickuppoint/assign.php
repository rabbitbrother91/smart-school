<style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
      .liststyle1 {
        margin: 0;
        list-style: none;
        line-height: 28px;
    }
    .bootstrap-detetimepicker-widget{z-index: 99999; position: absolute; overflow: visible !important;}
</style>

<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-bus"></i> <?php //echo $this->lang->line('transport'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" id="route">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('route_pickup_point'); ?></h3>
                        <div class="box-tools pull-right">
                        <?php if ($this->rbac->hasPrivilege('route_pickup_point', 'can_add')) {?>
                        <button type="button" onclick="add()" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add') ?></button>
                        <?php }?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                            <div class="pull-right">
                            </div>
                        </div>
                        <div class="mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('route_pickup_point'); ?></div>
                            <div class="table-responsive overflow-visible">
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('route'); ?></th>
                                            <th><?php echo $this->lang->line('pickup_point'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('monthly_fees'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?> </span></th>
                                            <th><?php echo $this->lang->line('distance_km'); ?></th>
                                            <th><?php echo $this->lang->line('pickup_time'); ?></th>
                                            <th><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($assign_pickup_point_list)) {
    ?>

                                            <?php
} else {
    $count         = 1;
    $pointroute_id = 0;
    foreach ($assign_pickup_point_list as $data) {

        ?>
                                                <tr>

                                                    <td class="mailbox-name"> <?php echo $data['route_title'] ?></td>
                                    <td class="mailbox-name"> <?php $sn = 1;foreach ($data['point_list'] as $key => $value) {
            ?>
                                                    <ul class="liststyle1"><li><?php echo $sn . " " . $value['pickup_point']; ?></li></ul>
                                                    <?php $sn++;
        }?></td>
                                                    <td class="mailbox-name"> <?php foreach ($data['point_list'] as $key => $value) {
            ?>
                                                    <ul class="liststyle1 text-right"><li><?php echo amountFormat($value['fees']); ?> &nbsp &nbsp   </li>
                                                    </ul>
                                                    <?php
}?></td>
                                                    <td  > <?php foreach ($data['point_list'] as $key => $value) {
            ?>
                                                    <ul class="liststyle1 "><li><?php echo $value['destination_distance']; ?></li>
                                                    </ul>
                                                    <?php
}?> </td>
                                                    <td  > <?php foreach ($data['point_list'] as $key => $value) {
            ?>
                                                    <ul class="liststyle1"><li><?php echo $this->customlib->timeFormat($value['pickup_time'], $this->customlib->getSchoolTimeFormat()); ?></li>
                                                    </ul>
                                                    <?php
}?> </td>

                                                        <td class="mailbox-date pull-right no-print">
                                                        <?php if ($this->rbac->hasPrivilege('route_pickup_point', 'can_view')) {?>
                                                             <a onclick="reorder('<?php echo $data["transport_route_id"]; ?>')" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo ('Reorder'); ?>">
                                                                <i class="fa fa-reorder"></i>
                                                            </a>

                                                        <?php }if ($this->rbac->hasPrivilege('route_pickup_point', 'can_edit')) {?>
                                                            <a onclick="edit('<?php echo $data["transport_route_id"]; ?>')" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        <?php }if ($this->rbac->hasPrivilege('route_pickup_point', 'can_delete')) {?>
                                                            <a href="<?php echo base_url(); ?>admin/pickuppoint/delete/<?php echo $data['transport_route_id'] ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                <i class="fa fa-remove"></i>
                                                            </a>
                                                        <?php }?>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            </div>
        </div>
    </section>
</div>
<div id="reorder" class="modal fade " role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" onclick="refreshpage()" class="close" data-dismiss="modal" >&times;</button>
                <h4 class="modal-title" id="" > <?php echo $this->lang->line('order_from_school_location'); ?></h4>
            </div>
           <div class="scroll-area">
                <div class="modal-body" id="">
                    <div class="table-responsive mailbox-messages" id="myTable">
                        <table id="headerTable" class="table table-hover table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line("s_no"); ?></th>
                                        <th><?php echo $this->lang->line("pickup_point"); ?></th>
                                        <th><?php echo $this->lang->line('distance_km'); ?></th>
                                        <th><?php echo $this->lang->line('pickup_time'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('monthly_fees'); ?> <?php echo "(" . $currency_symbol . ")"; ?></th>
                                    </tr>
                                </thead>
                                <tbody class="row_position" id="reorder_result">

                                </tbody>
                        </table>
                    </div>
                </div>
           </div>
       </div>
    </div>
</div>

<div id="add" class="modal fade " role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" >&times;</button>
                <h4 class="modal-title" id="modal-title" ></h4>
            </div>
             <form id="form1" name="employeeform" method="post" accept-charset="utf-8">
                <div class="">
                    <div class="">
                        <div class="modal-body">

                                <?php if ($this->session->flashdata('msg')) {
    ?>
                                    <?php echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg'); ?>
                                <?php }?>
                                <?php
if (isset($error_message)) {
    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
}
?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div id="delete_ides"></div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('route_list'); ?></label><small class="req"> *</small>
                                    <input type="hidden" name="action_type" id="action_type">
                                    <select class="form-control" onchange="get_pickup_point(this.value,'')" name="route_id" id="route_id">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($vehroutelist as $vehroute) {
    ?>
                                                    <option value="<?php echo $vehroute['routes_id'] ?>" data-fee="">
                                                        <?php echo $vehroute['route_title'] ?>
                                                    </option>
                                            <?php
}
?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('route_id'); ?></span>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 pb10">
                                <button type="button" class="btn btn-sm btn-info pull-right" onclick="add_pickuppoint()"><?php echo $this->lang->line('add_more') ?></button>
                                    </div>
                                </div>
                                <div id="pickuppoint_result"></div>
       </div>
    </div>
    </div>
        <div class="modal-footer bg-white relative z-index-1 bordertoplightgray">
             <button type="submit" data-loading-text="<?php echo $this->lang->line('saving') ?>" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait') ?>"><?php echo $this->lang->line('save') ?></button>
       </div>
    </form>
           </div>
       </div>
    </div>
</div>

<script type="text/javascript">
    function refreshpage(){
        window.location.reload(true);
    }

    function reorder(id){
        $('#reorder').modal('show');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/pickuppoint/reorder',
            type: "POST",
            data:{route_id:id},
            dataType: 'json',
            beforeSend: function() {

            },
            success: function(res) {
                $('#reorder_result').html(res);
            },
            error: function(xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

            },
            complete: function() {

            }
        });
    }

    $(" .row_position" ).sortable({
        delay: 150,
        stop: function() {
            var selectedData = new Array();
            $('.row_position>tr').each(function() {
                selectedData.push($(this).attr("id"));
            });
           reorder_pointid(selectedData);
        }
    });

    function reorder_pointid(selectedData){

            $.ajax({
                url: '<?php echo base_url(); ?>admin/pickuppoint/reorder_pointid',
                type: "POST",
                data:{position:selectedData},
                dataType: 'json',
                 beforeSend: function() {

                },
                success: function(res) {
                    successMsg('<?php echo $this->lang->line('update_message'); ?>');
                  reorder(res);
                },
                   error: function(xhr) { // if error occured
                   alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

            },
            complete: function() {

            }
            });
    }

 function remove_editpickpoint(id){
         var result = confirm("<?php echo $this->lang->line('delete_confirm') ?>");
if (result) {
      $('#'+id).html('');
         $('#delete_ides').append('<input type="hidden" name="delete_ides[]" value="'+id+'"/>');
}

    }

    function add(){

        $('#action_type').val('add');
        $('#delete_ides').html('');
        $('#add').modal('show');
        $('#route_id').val('');
        $('#modal-title').html('<?php echo $this->lang->line('add') ?>');
        $('#pickuppoint_result').html('');
        add_pickuppoint();
    }

    function edit(route_id){
        $('#action_type').val('edit');
        $('#route_id').val(route_id);
        $('#delete_ides').html('');
         $('#pickuppoint_result').html('');
        $('#modal-title').html('<?php echo $this->lang->line('edit') ?>');
         $.ajax({
                url: '<?php echo base_url(); ?>admin/pickuppoint/get_assigndetails',
                type: "POST",
                data:{id:route_id},
                dataType: 'json',
                 beforeSend: function() {

                },
                success: function(res) {

                $.each(res, function (index, value) {

                $.ajax({
                url: '<?php echo base_url(); ?>admin/pickuppoint/addmore_point',
                type: "POST",
                data:{id:value.id,delete_string:value.id},
                dataType: 'json',
                 beforeSend: function() {

                },
                success: function(res) {
                   $('#pickuppoint_result').append(res);
                },
                   error: function(xhr) { // if error occured
                   alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

            },
            complete: function() {

            }
            });
                    });

                    $('#add').modal('show');
                    $('#modal-title').html('<?php echo $this->lang->line('edit') ?>');
                },
                   error: function(xhr) { // if error occured
                   alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

            },
            complete: function() {

            }
            });
    }

    function add_pickuppoint(){
         $.ajax({
                url: '<?php echo base_url(); ?>admin/pickuppoint/addmore_point',
                type: "POST",
                data:{delete_string:makeid(8)},
                dataType: 'json',
                 beforeSend: function() {

                },
                success: function(res) {
                   $('#pickuppoint_result').append(res);
                },
                   error: function(xhr) { // if error occured
                   alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            },
            complete: function() {

            }
            });
    }

    function makeid(length) {
        var result = '';
        var characters = '0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }

    function remove_pickpoint(string){
        var result = confirm("<?php echo $this->lang->line('delete_confirm') ?>");
if (result) {
     $('#'+string).html('');
}

    }

    $("#form1").on('submit', (function (e) {
        e.preventDefault();

        var $this = $(this).find("button[type=submit]:focus");
        var inps = document.getElementsByName('lessons[]');
        $.ajax({
            url: base_url+"admin/pickuppoint/create",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (res)
            {
                if (res.status == "fail") {
                    var message = "";
                    $.each(res.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(res.message);
                    window.location.reload(true);
                }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
    }));

    function get_pickup_point(route_id,point_id){

        $('#pickup_point').html('');
        var sel="";
         var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
                url: '<?php echo base_url(); ?>admin/pickuppoint/get_pickupdropdownlist/'+route_id,
                type: "POST",
                data:{id:route_id},
                dataType: 'json',
                 beforeSend: function() {

                },
                success: function(res) {

                    $.each(res, function (index, value) {
                        if(value.id==point_id){
                            sel="selected";
                        }else{
                            sel="";
                        }
                        div_data += "<option value=" + value.id + " "+sel+">" + value.pickup_point + "</option>";
                    });

                    $('#pickup_point').append(div_data);
                },
                   error: function(xhr) { // if error occured
                   alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

            },
            complete: function() {

            }
            });
    }

    $(document).on('focus', '.time', function () {
                    var $this = $(this);
                    $this.datetimepicker({
                        format: 'LT'
                    });
                });
</script>