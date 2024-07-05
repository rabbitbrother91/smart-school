<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();

?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <section class="content-header">
                <h1>
                    <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?><small><?php echo $this->lang->line('student_fee'); ?></small></h1>
            </section>
        </div>
    </div>
    <!-- /.control-sidebar -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-4">
                                <h3 class="box-title"><?php echo $this->lang->line('student_fees'); ?></h3>
                            </div>
                            <div class="col-md-8 ">
                                <div class="btn-group pull-right">
                                    <a href="<?php echo base_url() ?>user/user/dashboard" type="button" class="btn btn-primary btn-xs">
                                        <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div><!--./box-header-->

                    <div class="box-body" style="padding-top:0;">
                        <div class="row">
                            <?php echo $this->session->flashdata('error');
$this->session->unset_userdata('error'); ?>
                            <div class="col-md-12">
                                <div class="sfborder-top-border">
                                    <div class="col-md-2">
                                        <?php if ($sch_setting->student_photo) {
    ?>
                                            <img class="mt5 mb10 sfborder-img-shadow img-responsive img-rounded" src="<?php echo base_url() . $student['image'] ?>" alt="User profile picture">
                                            <?php
}?>
                                    </div>

                                    <div class="col-md-10">
                                        <div class="row">

                                            <table class="table table-striped mb0 font13">
                                                <tbody>
                                                    <tr>
                                                        <th class="bozero"><?php echo $this->lang->line('name'); ?></th>
                                                        <td class="bozero"><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></td>
                                                        <th class="bozero"><?php echo $this->lang->line('class_section'); ?></th>
                                                        <td class="bozero"><?php echo $student['class'] . " (" . $student['section'] . ")" ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <?php if ($sch_setting->father_name) {?>
                                                            <th><?php echo $this->lang->line('father_name'); ?></th>
                                                            <td><?php echo $student['father_name']; ?></td>
                                                        <?php }
?>

                                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                        <td><?php echo $student['admission_no']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <?php if ($sch_setting->mobile_no) {?>
                                                            <th><?php echo $this->lang->line('mobile_no'); ?></th>
                                                            <td><?php echo $student['mobileno']; ?></td>
                                                        <?php }if ($sch_setting->roll_no) {?>
                                                            <th><?php echo $this->lang->line('roll_no'); ?></th>
                                                            <td> <?php echo $student['roll_no']; ?> </td>
                                                        <?php }?>
                                                    </tr>
                                                    <tr>
                                                        <?php if ($sch_setting->category) {
    ?>
                                                            <th><?php echo $this->lang->line('category'); ?></th>
                                                            <td>
                                                                <?php
foreach ($categorylist as $value) {
        if ($student['category_id'] == $value['id']) {
            echo $value['category'];
        }
    }
    ?>
                                                            </td>
                                                        <?php }if ($sch_setting->rte) {?>
                                                            <th><?php echo $this->lang->line('rte'); ?></th>
                                                            <td><b class="text-danger"> <?php echo $student['rte']; ?> </b>
                                                            </td>
                                                        <?php }?>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="sfborder"></div>
                            </div>
                        </div>
                        <div class="">
                            <div class="download_label"><?php echo $this->lang->line('student_fees') . ": " . $student['firstname'] . " " . $student['lastname'] ?> </div>
                            <?php
if (empty($student_due_fee)) {
    ?>
                                <div class="alert alert-danger">
                                    No fees Found.
                                </div>
                                <?php
} else {
    ?>
                        <div class="row no-print mb10">
                            <div class="col-md-12 mDMb10">
                                <button class="btn btn-sm btn-info printSelected"><i class="fa fa-print"></i> <?php echo $this->lang->line('print_selected'); ?> </button>

                                <span class="pull-right pt5"><?php echo $this->lang->line('date'); ?>: <?php echo date($this->customlib->getSchoolDateFormat()); ?></span>
                            </div>
                        </div>
                        <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover  table-fixed-header">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px"><input type="checkbox" id="select_all"/></th>
                                            <th align="left"><?php echo $this->lang->line('fees_group'); ?></th>
                                            <th align="left"><?php echo $this->lang->line('fees_code'); ?></th>
                                            <th align="left" class="text text-center"><?php echo $this->lang->line('due_date'); ?></th>
                                            <th align="left" class="text text-left"><?php echo $this->lang->line('status'); ?></th>
                                            <th class="text text-right"><?php echo $this->lang->line('amount') ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th class="text text-left"><?php echo $this->lang->line('payment_id'); ?></th>
                                            <th class="text text-left"><?php echo $this->lang->line('mode'); ?></th>
                                            <th class="text text-left"><?php echo $this->lang->line('date'); ?></th>
                                            <th class="text text-right" ><?php echo $this->lang->line('discount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th class="text text-right"><?php echo $this->lang->line('fine'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th class="text text-right"><?php echo $this->lang->line('paid'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th class="text text-right"><?php echo $this->lang->line('balance'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th class="text text-right"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
$total_amount           = "0";
    $total_deposite_amount  = "0";
    $total_fine_amount      = "0";
    $total_discount_amount  = "0";
    $total_balance_amount   = "0";
    $total_fees_fine_amount = 0;
    foreach ($student_due_fee as $key => $fee) {
        foreach ($fee->fees as $fee_key => $fee_value) {
            $fee_paid          = 0;
            $fee_discount      = 0;
            $fee_fine          = 0;
            $alot_fee_discount = 0;

            if (!empty($fee_value->amount_detail)) {
                $fee_deposits = json_decode(($fee_value->amount_detail));
                $fee_fine     = 0;
                foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                    $fee_paid     = $fee_paid + $fee_deposits_value->amount;
                    $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                    $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
                }
            }

            if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {

                $total_fees_fine_amount = $total_fees_fine_amount + $fee_value->fine_amount;
            }

            $total_amount          = $total_amount + $fee_value->amount;
            $total_discount_amount = $total_discount_amount + $fee_discount;
            $total_deposite_amount = $total_deposite_amount + $fee_paid;
            $total_fine_amount     = $total_fine_amount + $fee_fine;
            $feetype_balance       = $fee_value->amount - ($fee_paid + $fee_discount);
            $total_balance_amount  = $total_balance_amount + $feetype_balance;
            ?>
                                                <?php
if ($feetype_balance > 0 && strtotime($fee_value->due_date) < strtotime(date('Y-m-d'))) {
                ?>
                                                    <tr class="danger font12">
                                                        <?php
} else {
                ?>
                                                    <tr class="dark-gray">
                                                        <?php
}
            ?>
                                                          <td>
                                                    <input class="checkbox" type="checkbox" name="fee_checkbox" data-fee_master_id="<?php echo $fee_value->id ?>" data-fee_session_group_id="<?php echo $fee_value->fee_session_group_id ?>" data-fee_groups_feetype_id="<?php echo $fee_value->fee_groups_feetype_id ?>"></td>

                                                    <td align="left"><?php
echo $fee_value->name . " (" . $fee_value->type . ")";
            ?></td>
                                                    <td align="left"><?php echo $fee_value->code; ?></td>
                                                    <td align="left" class="text text-center">

                                                        <?php
if ($fee_value->due_date == "0000-00-00") {

            } else {

                echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_value->due_date));
            }
            ?>
                                                    </td>
                                                    <td align="left" class="text text-left">
                                                        <?php
if ($feetype_balance == 0) {
                ?><span class="label label-success"><?php echo $this->lang->line('paid'); ?></span><?php
} else if (!empty($fee_value->amount_detail)) {
                ?><span class="label label-warning"><?php echo $this->lang->line('partial'); ?></span><?php
} else {
                ?><span class="label label-danger"><?php echo $this->lang->line('unpaid'); ?></span><?php
}
            ?>
                                                    </td>
                                                    <td class="text text-right">

                                                          <?php echo $fee_value->amount;
            if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {
                ?>
<span class="text text-danger"><?php echo " + " . ($fee_value->fine_amount); ?></span>
    <?php

            }
            ?>
                                                    </td>
                                                    <td class="text text-left"></td>
                                                    <td class="text text-left"></td>
                                                    <td class="text text-left"></td>
                                                    <td class="text text-right"><?php
echo (amountFormat($fee_discount, 2, '.', ''));
            ?></td>
                                                    <td class="text text-right"><?php
echo (amountFormat($fee_fine, 2, '.', ''));
            ?></td>
                                                    <td class="text text-right"><?php
echo (amountFormat($fee_paid, 2, '.', ''));
            ?></td>
                                                    <td class="text text-right">
                                                        <?php
$display_none = "ss-none";
            if ($feetype_balance > 0) {
                $display_none = "";
                echo (amountFormat($feetype_balance, 2, '.', ''));
            }
            ?>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group pull-right">
                                                            <?php
if ($payment_method) {

                if ($feetype_balance > 0) {
                    ?>
                                                                    <a href="<?php echo base_url() . 'user/gateway/payment/pay/' . $fee->id . "/" . $fee_value->fee_groups_feetype_id . "/" . $student['id'] ?>" class="btn btn-xs btn-primary pull-right myCollectFeeBtn"><i class="fa fa-money"></i> Pay</a>
                                                                    <?php
}
            }
            ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
if (!empty($fee_value->amount_detail)) {

                $fee_deposits = json_decode(($fee_value->amount_detail));

                foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                    ?>
                                                        <tr class="white-td">
                                                            <td align="left"></td>
                                                            <td align="left"></td>
                                                            <td align="left"></td>
                                                            <td align="left"></td>
                                                            <td align="left"></td>
                                                            <td class="text-right"><img src="<?php echo base_url(); ?>backend/images/table-arrow.png" alt="" /></td>
                                                            <td class="text text-left">
                                                                <a href="#" data-toggle="popover" class="detail_popover" > <?php echo $fee_value->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?></a>
                                                                <div class="fee_detail_popover" style="display: none">
                                                                    <?php
if ($fee_deposits_value->description == "") {
                        ?>
                                                                        <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                        <?php
} else {
                        ?>
                                                                        <p class="text text-info"><?php echo $fee_deposits_value->description; ?></p>
                                                                        <?php
}
                    ?>
                                                                </div>                                                               </td>
                                                            <td class="text text-left"><?php echo $this->lang->line(strtolower($fee_deposits_value->payment_mode)); ?></td>
                                                            <td class="text text-left">
                                                                <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_deposits_value->date)); ?>
                                                            </td>
                                                            <td class="text text-right"><?php echo (amountFormat($fee_deposits_value->amount_discount, 2, '.', '')); ?></td>
                                                            <td class="text text-right"><?php echo (amountFormat($fee_deposits_value->amount_fine, 2, '.', '')); ?></td>
                                                            <td class="text text-right"><?php echo (amountFormat($fee_deposits_value->amount, 2, '.', '')); ?></td>
                                                            <td></td>
                                                            <td class="text text-right">
                                                            </td>
                                                        </tr>
                                                        <?php
}
            }
            ?>
                                                <?php
}
    }
    ?>
                                        <?php
if (!empty($student_discount_fee)) {

        foreach ($student_discount_fee as $discount_key => $discount_value) {
            ?>
                                                <tr class="dark-light">
                                                    <td></td>
                                                    <td align="left"> <?php echo $this->lang->line('discount'); ?> </td>
                                                    <td align="left">
                                                        <?php echo $discount_value['code']; ?>
                                                    </td>
                                                    <td align="left"></td>
                                                    <td align="left" class="text text-left">
                                                        <?php
if ($discount_value['status'] == "applied") {
                ?>
                                                            <a href="#" data-toggle="popover" class="detail_popover" >

                                                                <?php echo $this->lang->line('discount_of') . " " . $currency_symbol . amountFormat($discount_value['amount']) . " " . $this->lang->line($discount_value['status']) . " : " . $discount_value['payment_id']; ?>

                                                            </a>
                                                            <div class="fee_detail_popover" style="display: none">
                                                                <?php
if ($discount_value['student_fees_discount_description'] == "") {
                    ?>
                                                                    <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                    <?php
} else {
                    ?>
                                                                    <p class="text text-danger"><?php echo $discount_value['student_fees_discount_description'] ?></p>
                                                                    <?php
}
                ?>

                                                            </div>
                                                            <?php
} else {
                echo '<p class="text text-danger">' . $this->lang->line('discount_of') . " " . $currency_symbol . amountFormat($discount_value['amount']) . " " . $this->lang->line($discount_value['status']);
            }
            ?>
                                                    </td>
                                                    <td></td>
                                                    <td class="text text-left"></td>
                                                    <td class="text text-left"></td>
                                                    <td class="text text-left"></td>
                                                    <td  class="text text-right">
                                                        <?php
$alot_fee_discount = $alot_fee_discount;
            ?>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <?php
}
    }
    ?>
                                        <tr class="box box-solid total-bg">
                                            <td align="left"></td>
                                            <td align="left"></td>
                                            <td align="left"></td>
                                            <td align="left"></td>
                                            <td align="left" class="text text-left" ><?php echo $this->lang->line('grand_total'); ?></td>
                                            <td class="text text-right">   <?php
echo $currency_symbol . amountFormat($total_amount, 2, '.', '') . "<span class='text text-danger'>+" . amountFormat($total_fees_fine_amount, 2, '.', '') . "</span>";
    ?></td>
                                            <td class="text text-left"></td>
                                            <td class="text text-left"></td>
                                            <td class="text text-left"></td>
                                            <td class="text text-right"><?php
echo ($currency_symbol . amountFormat($total_discount_amount + $alot_fee_discount, 2, '.', ''));
    ?></td>
                                            <td class="text text-right"><?php
echo ($currency_symbol . amountFormat($total_fine_amount, 2, '.', ''));
    ?></td>
                                            <td class="text text-right"><?php
echo ($currency_symbol . amountFormat($total_deposite_amount, 2, '.', ''));
    ?></td>
                                            <td class="text text-right"><?php
echo ($currency_symbol . amountFormat($total_balance_amount - $alot_fee_discount, 2, '.', ''));
    ?></td>
                                            <td class="text text-right"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!--/.col (left) -->
        </div>
    </section>
</div>

<script type="text/javascript">
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
   });

 $(document).on('click', '.printSelected', function () {
            var array_to_print = [];
            $.each($("input[name='fee_checkbox']:checked"), function () {
                var fee_session_group_id = $(this).data('fee_session_group_id');
                var fee_master_id = $(this).data('fee_master_id');
                var fee_groups_feetype_id = $(this).data('fee_groups_feetype_id');
                item = {};
                item ["fee_session_group_id"] = fee_session_group_id;
                item ["fee_master_id"] = fee_master_id;
                item ["fee_groups_feetype_id"] = fee_groups_feetype_id;

                array_to_print.push(item);
            });

            if (array_to_print.length === 0) {
                alert("<?php echo $this->lang->line('no_record_selected'); ?>");
            } else {
                $.ajax({
                    url: '<?php echo site_url("user/user/printFeesByGroupArray") ?>',
                    type: 'POST',
                    data: {'data': JSON.stringify(array_to_print)},
                    success: function (response) {
                        Popup(response);
                    }
                });
            }
        });

      $("#select_all").change(function () {  //"select all" change
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    function Popup(data, winload = false)
    {
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
            if (winload) {
                window.location.reload(true);
            }
        }, 500);

        return true;
    }
</script>