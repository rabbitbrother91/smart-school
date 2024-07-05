<?php

$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <section class="content-header">
                <h1>
                    <i class="fa fa-money"></i> <?php //echo $this->lang->line('fees_collection'); ?><small><?php //echo $this->lang->line('student_fee'); ?></small></h1>
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
                            </div>
                        </div>
                    </div><!--./box-header-->

                    <div class="box-body" style="padding-top:0;">
                        <div class="row">
                            <?php echo $this->session->flashdata('error');
$this->session->unset_userdata('error'); ?>
                            <div class="col-md-12">
                                <div class="sfborder">
                                    <div class="col-md-2">
                                        <?php if ($sch_setting->student_photo) {
    ?>
                                            <img class="profile-user-img img-responsive img-circle" src="<?php
if (!empty($student['image'])) {
        echo base_url() . $student['image'];
    } else {
        echo base_url() . "uploads/student_images/no_image.png";
    }
    ?>" alt="User profile picture">
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
                                                            <th><?php echo $this->lang->line('mobile_number'); ?></th>
                                                            <td><?php echo $student['mobileno']; ?></td>
                                                        <?php }if ($sch_setting->roll_no) {?>
                                                            <th><?php echo $this->lang->line('roll_number'); ?></th>
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
                                </div></div>
                            <div class="col-md-12">
                                <div style="background: #dadada; height: 1px; width: 100%; clear: both; margin-bottom: 10px;"></div>
                            </div>
                        </div>
                      <div class="row">
                            <div class="col-md-12 mb10">
                                <?php if ($payment_method) {?>
                                <div class="float-rtl-right float-left">    
                                <button type="button" class="btn btn-sm btn-warning collectSelected" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait') ?>"><i class="fa fa-money"></i> <?php echo $this->lang->line('pay_selected') ?></button>
                                </div>
                                <?php }?>
                                <span class="pull-right"><?php echo $this->lang->line('date'); ?>: <?php echo date($this->customlib->getSchoolDateFormat()); ?></span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('student_fees') . ": " . $student['firstname'] . " " . $student['lastname'] ?> </div>
                            <?php
if (empty($student_due_fee) && empty($transport_fees)) {
    ?>
                                <div class="alert alert-danger">
                                    No fees Found.
                                </div>
                                <?php
} else {
    ?>
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
$total_amount           = 0;
    $total_deposite_amount  = 0;
    $total_fine_amount      = 0;
    $total_discount_amount  = 0;
    $total_balance_amount   = 0;
    $total_fees_fine_amount = 0;

    foreach ($student_due_fee as $fee_key => $fee_value) {

        $fee_paid          = 0;
        $fee_discount      = 0;
        $fee_fine          = 0;
        $alot_fee_discount = 0;

        if (!empty($fee_value->amount_detail)) {
            $fee_deposits = json_decode(($fee_value->amount_detail));

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
                                                        <td><input class="checkbox" type="checkbox" name="fee_checkbox" data-fee_master_id="<?php echo $fee_value->id ?>" data-fee_session_group_id="<?php echo $fee_value->fee_session_group_id ?>" data-fee_groups_feetype_id="<?php echo $fee_value->fee_groups_feetype_id ?>" data-fee_category="fees"  data-trans_fee_id="0"> </td>
                                                    <td align="left" class="text-rtl-right"><?php
echo $fee_value->name . " (" . $fee_value->type . ")";
        ?> </td>
                                                    <td align="left" class="text-rtl-right"><?php echo $fee_value->code; ?></td>
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
                                                    <td class="text text-right"><?php echo amountFormat($fee_value->amount);
        if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {
            ?>

<span data-toggle="popover" class="text text-danger detail_popover"><?php echo " + " . amountFormat($fee_value->fine_amount); ?></span>

<div class="fee_detail_popover" style="display: none">
    <?php
if ($fee_value->fine_amount != "") {
                ?>
        <p class="text text-danger"><?php echo $this->lang->line('fine'); ?></p>
        <?php
}
            ?>
</div>
    <?php
}
        ?></td>
                                                    <td class="text text-left"></td>
                                                    <td class="text text-left"></td>
                                                    <td class="text text-left"></td>
                                                    <td class="text text-right"><?php
echo amountFormat($fee_discount);
        ?></td>
                                                    <td class="text text-right"><?php
echo amountFormat($fee_fine);
        ?></td>
                                                    <td class="text text-right"><?php
echo amountFormat($fee_paid);
        ?></td>
                                                    <td class="text text-right">
                                                        <?php
$display_none = "ss-none";
        if ($feetype_balance > 0) {
            $display_none = "";
            echo amountFormat($feetype_balance);
        }
        ?>
                                                    </td>
                                                  <td>
                                                        <div class="btn-group pull-right">
                                                            <?php
if ($feetype_balance > 0) {

                if ($payment_method && $sch_setting->is_offline_fee_payment) {

                    ?>

<form class="form_fees" action="<?php echo site_url('user/gateway/payment/pay'); ?>" method="POST">
    <input type="hidden" name="fee_category" value="fees">
    <input type="hidden" name="student_transport_fee_id" value="0">
    <input type="hidden" name="student_fees_master_id" value="<?php echo $fee_value->id; ?>">
    <input type="hidden" name="fee_groups_feetype_id" value="<?php echo $fee_value->fee_groups_feetype_id; ?>">
    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
    <input type="hidden" name="submit_mode" value="">

    <div class="dropdown">
  <button class="btn btn-xs btn-primary pull-right dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-money"></i>  <?php echo $this->lang->line('pay'); ?>
  <span class="caret"></span></button>
  <ul class="dropdown-menu  dropdown-menu-right">
    <li ><a href="javascript:void(0)" onclick="submitform('online_payment',this)"><?php echo $this->lang->line('online_payment'); ?></a></li>
    <li><a href="javascript:void(0)" onclick="submitform('offline_payment',this)"><?php echo $this->lang->line('offline_payment'); ?></a></li>
  </ul>
</div>
</form>
                                                                    <?php
} elseif ($payment_method) {
                    ?>

    <form class="form_fees" action="<?php echo site_url('user/gateway/payment/pay'); ?>" method="POST">
    <input type="hidden" name="fee_category" value="fees">
    <input type="hidden" name="student_transport_fee_id" value="0">
    <input type="hidden" name="student_fees_master_id" value="<?php echo $fee_value->id; ?>">
    <input type="hidden" name="fee_groups_feetype_id" value="<?php echo $fee_value->fee_groups_feetype_id; ?>">
    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
    <input type="hidden" name="submit_mode" value="online_payment">
    <button type="submit" class="btn btn-xs btn-primary pull-right myCollectFeeBtn"><i class="fa fa-money"></i>  <?php echo $this->lang->line('pay'); ?></button>
</form>
    <?php

                } elseif ($sch_setting->is_offline_fee_payment) {
                    ?>

    <form class="form_fees" action="<?php echo site_url('user/gateway/payment/pay'); ?>" method="POST">
    <input type="hidden" name="fee_category" value="fees">
    <input type="hidden" name="student_transport_fee_id" value="0">
    <input type="hidden" name="student_fees_master_id" value="<?php echo $fee_value->id; ?>">
    <input type="hidden" name="fee_groups_feetype_id" value="<?php echo $fee_value->fee_groups_feetype_id; ?>">
    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
    <input type="hidden" name="submit_mode" value="offline_payment">
    <button type="submit" class="btn btn-xs btn-primary pull-right myCollectFeeBtn"><i class="fa fa-money"></i>  <?php echo $this->lang->line('pay'); ?></button>
</form>
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
                                                            </div>
                                                            </td>
                                                            <td class="text text-left"><?php echo $this->lang->line(strtolower($fee_deposits_value->payment_mode)); ?></td>
                                                            <td class="text text-left">
                                                                <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_deposits_value->date)); ?>
                                                            </td>
                                                            <td class="text text-right"><?php echo amountFormat($fee_deposits_value->amount_discount); ?></td>
                                                            <td class="text text-right"><?php echo amountFormat($fee_deposits_value->amount_fine); ?></td>
                                                            <td class="text text-right"><?php echo amountFormat($fee_deposits_value->amount); ?></td>
                                                            <td></td>
                                                            <td class="text text-right">
 <button  class="btn btn-xs btn-default printDoc" data-main_invoice="<?php echo $fee_value->student_fees_deposite_id ?>" data-sub_invoice="<?php echo $fee_deposits_value->inv_no ?>"  data-fee-category="fees"  title="<?php echo $this->lang->line('print'); ?>"><i class="fa fa-print"></i> </button>
                                                            </td>
                                                        </tr>
                                                        <?php
}
        }
        ?>
                                                <?php

    }
    ?>
<?php

    if (!empty($transport_fees)) {
        foreach ($transport_fees as $transport_fee_key => $transport_fee_value) {

            $fee_paid         = 0;
            $fee_discount     = 0;
            $fee_fine         = 0;
            $fees_fine_amount = 0;
            $feetype_balance  = 0;

            if (!empty($transport_fee_value->amount_detail)) {
                $fee_deposits = json_decode(($transport_fee_value->amount_detail));

                foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                    $fee_paid     = $fee_paid + $fee_deposits_value->amount;
                    $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                    $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
                }
            }

            $feetype_balance = $transport_fee_value->fees - ($fee_paid + $fee_discount);

            if (($transport_fee_value->due_date != "0000-00-00" && $transport_fee_value->due_date != null) && (strtotime($transport_fee_value->due_date) < strtotime(date('Y-m-d')))) {
                $fees_fine_amount       = is_null($transport_fee_value->fine_percentage) ? $transport_fee_value->fine_amount : percentageAmount($transport_fee_value->fees, $transport_fee_value->fine_percentage);
                $total_fees_fine_amount = $total_fees_fine_amount + $fees_fine_amount;
            }

            $total_amount += $transport_fee_value->fees;
            $total_discount_amount += $fee_discount;
            $total_deposite_amount += $fee_paid;
            $total_fine_amount += $fee_fine;
            $total_balance_amount += $feetype_balance;

            if (strtotime($transport_fee_value->due_date) < strtotime(date('Y-m-d'))) {
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
                    <?php //print_r($transport_fee_value); ?>
                      <input class="checkbox" type="checkbox" name="fee_checkbox" data-fee_master_id="0" data-fee_session_group_id="0" data-fee_groups_feetype_id="0"  data-fee_category="transport"  data-trans_fee_id="<?php echo $transport_fee_value->id; ?>">
                 </td>
                                                <td align="left" class="text-rtl-right"><?php echo $this->lang->line('transport_fees'); ?></td>
                                                <td align="left" class="text-rtl-right"><?php echo $transport_fee_value->month; ?></td>
                                                <td align="left" class="text text-left">
<?php echo $this->customlib->dateformat($transport_fee_value->due_date); ?>                                             </td>
                                                     <td align="left" class="text text-left width85">
                                                    <?php
if ($feetype_balance == 0) {
                ?><span class="label label-success"><?php echo $this->lang->line('paid'); ?></span><?php
} else if (!empty($transport_fee_value->amount_detail)) {
                ?><span class="label label-warning"><?php echo $this->lang->line('partial'); ?></span><?php
} else {
                ?><span class="label label-danger"><?php echo $this->lang->line('unpaid'); ?></span><?php
}
            ?>
                                                </td>
                                                <td class="text text-right"><?php echo amountFormat($transport_fee_value->fees);

            if (($transport_fee_value->due_date != "0000-00-00" && $transport_fee_value->due_date != null) && (strtotime($transport_fee_value->due_date) < strtotime(date('Y-m-d')))) {
                $tr_fine_amount = $transport_fee_value->fine_amount;
                if ($transport_fee_value->fine_type != "" && $transport_fee_value->fine_type == "percentage") {

                    $tr_fine_amount = percentageAmount($transport_fee_value->fees, $transport_fee_value->fine_percentage);
                }
                ?>

<span data-toggle="popover" class="text text-danger detail_popover"><?php echo " + " . amountFormat($tr_fine_amount); ?></span>

<div class="fee_detail_popover" style="display: none">
    <?php
if ($tr_fine_amount != "") {
                    ?>
        <p class="text text-danger"><?php echo $this->lang->line('fine'); ?></p>
        <?php
}
                ?>
</div>
    <?php
}

            ?>   </td>
                                                <td class="text text-left"></td>
                                                <td class="text text-left"></td>
                                                <td class="text text-left"></td>
                                                <td class="text text-right">
                                                    <?php echo amountFormat($fee_discount); ?>
                                                </td>
                                                <td class="text text-right">
                                                    <?php echo amountFormat($fee_fine); ?>
                                                </td>
                                                <td class="text text-right">
                                                    <?php echo amountFormat($fee_paid); ?>
                                                </td>
                                                <td class="text text-right">
                                                    <?php
$display_none = "ss-none";
            if ($feetype_balance > 0) {
                $display_none = "";
                echo amountFormat($feetype_balance);
            }
            ?>
                                                </td>
                                            <td >
              <?php

            if ($feetype_balance > 0) {

                if ($payment_method && $sch_setting->is_offline_fee_payment) {

                    ?>

                    <form class="form_fees" action="<?php echo site_url('user/gateway/payment/pay'); ?>" method="POST">
<input type="hidden" name="fee_category" value="transport">
    <input type="hidden" name="student_transport_fee_id" value="<?php echo $transport_fee_value->id; ?>">
    <input type="hidden" name="student_fees_master_id" value="0">
    <input type="hidden" name="fee_groups_feetype_id" value="0">
    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
    <input type="hidden" name="submit_mode" value="">
    <div class="dropdown">
  <button class="btn btn-xs btn-primary pull-right dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-money"></i>  <?php echo $this->lang->line('pay'); ?>
  <span class="caret"></span></button>
  <ul class="dropdown-menu  dropdown-menu-right">

    <li ><a href="javascript:void(0)" onclick="submitform('online_payment',this)"><?php echo $this->lang->line('online_payment'); ?></a></li>
    <li><a href="javascript:void(0)" onclick="submitform('offline_payment',this)"><?php echo $this->lang->line('offline_payment'); ?></a></li>
  </ul>
</div>
</form>

                                                                    <?php
} elseif ($payment_method) {
                    ?>

    <form class="form_fees" action="<?php echo site_url('user/gateway/payment/pay'); ?>" method="POST">
    <input type="hidden" name="fee_category" value="transport">
    <input type="hidden" name="student_transport_fee_id" value="<?php echo $transport_fee_value->id; ?>">
    <input type="hidden" name="student_fees_master_id" value="0">
    <input type="hidden" name="fee_groups_feetype_id" value="0">
    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
    <input type="hidden" name="submit_mode" value="online_payment">
    <button type="submit" class="btn btn-xs btn-primary pull-right myCollectFeeBtn"><i class="fa fa-money"></i>  <?php echo $this->lang->line('pay'); ?></button>
</form>
    <?php
                } elseif ($sch_setting->is_offline_fee_payment) {
                    ?>

    <form class="form_fees" action="<?php echo site_url('user/gateway/payment/pay'); ?>" method="POST">
    <input type="hidden" name="fee_category" value="transport">
    <input type="hidden" name="student_transport_fee_id" value="<?php echo $transport_fee_value->id; ?>">
    <input type="hidden" name="student_fees_master_id" value="0">
    <input type="hidden" name="fee_groups_feetype_id" value="0">
    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
    <input type="hidden" name="submit_mode" value="offline_payment">
    <button type="submit" class="btn btn-xs btn-primary pull-right myCollectFeeBtn"><i class="fa fa-money"></i>  <?php echo $this->lang->line('pay'); ?></button>
</form>
    <?php
                }
            }
            ?>
                                                </td>
                                            </tr>
                                             <?php
if (!empty($transport_fee_value->amount_detail)) {

                $fee_deposits = json_decode(($transport_fee_value->amount_detail));

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
                                                            <a href="#" data-toggle="popover" class="detail_popover" > <?php echo $transport_fee_value->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?></a>
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
                                                            </div>
                                                        </td>
                                                        <td class="text text-left"><?php echo $this->lang->line(strtolower($fee_deposits_value->payment_mode)); ?></td>
                                                        <td class="text text-left">
                                                            <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_deposits_value->date)); ?>
                                                        </td>
                                                        <td class="text text-right"><?php echo amountFormat($fee_deposits_value->amount_discount); ?></td>
                                                        <td class="text text-right"><?php echo amountFormat($fee_deposits_value->amount_fine); ?></td>
                                                        <td class="text text-right"><?php echo amountFormat($fee_deposits_value->amount); ?></td>
                                                        <td></td>
                                                          <td class="text text-right">
 <button  class="btn btn-xs btn-default printDoc" data-main_invoice="<?php echo $transport_fee_value->student_fees_deposite_id ?>" data-sub_invoice="<?php echo $fee_deposits_value->inv_no ?>" data-fee-category="transport" title="<?php echo $this->lang->line('print'); ?>"><i class="fa fa-print"></i> </button>
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
                                                    <td align="left" class="text-rtl-right"> <?php echo $this->lang->line('discount'); ?> </td>
                                                    <td align="left" class="text-rtl-right">
                                                        <?php echo $discount_value['code']; ?>
                                                    </td>
                                                    <td align="left"></td>
                                                    <td align="left" class="text text-left">
                                                        <?php
if ($discount_value['status'] == "applied") {
                ?>
                                                            <a href="#" data-toggle="popover" class="detail_popover" >

                                                                <?php echo $this->lang->line('discount_of') . " " . $currency_symbol . $discount_value['amount'] . " " . $this->lang->line($discount_value['status']) . " : " . $discount_value['payment_id']; ?>
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
                echo '<p class="text text-danger">' . $this->lang->line('discount_of') . " " . $currency_symbol . $discount_value['amount'] . " " . $this->lang->line($discount_value['status']);
            }
            ?>
                                                    </td>
                                                    <td></td>
                                                    <td class="text text-left"></td>
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
                                            <td class="text text-right">
                                                <?php echo $currency_symbol . amountFormat($total_amount); ?>
<span data-toggle="popover" class="text text-danger detail_popover"><?php echo " + " . amountFormat($total_fees_fine_amount); ?></span>
<div class="fee_detail_popover" style="display: none">
    <?php
if ($total_fees_fine_amount != "") {
        ?>
        <p class="text text-danger"><?php echo $this->lang->line('fine'); ?></p>
        <?php
}
    ?>
</div>
                                            </td>
                                            <td class="text text-left"></td>
                                            <td class="text text-left"></td>
                                            <td class="text text-left"></td>
                                            <td class="text text-right"><?php
echo $currency_symbol . amountFormat($total_discount_amount);
    ?></td>
                                            <td class="text text-right"><?php
echo $currency_symbol . amountFormat($total_fine_amount);
    ?></td>
                                            <td class="text text-right"><?php
echo $currency_symbol . amountFormat($total_deposite_amount);
    ?></td>
                                            <td class="text text-right"><?php
echo $currency_symbol . amountFormat($total_balance_amount);
    ?></td>
                                            <td class="text text-right"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php
}?>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!--/.col (left) -->
        </div>
    </section>
</div>

<div id="listCollectionModal" class="modal fade">
    <div class="modal-dialog">
        <form action="<?php echo site_url('user/gateway/payment/grouppay'); ?>" method="POST" id="collect_fee_group">
            <div class="modal-content">
<!-- //================ -->
<input  type="hidden" class="form-control" id="group_std_id" name="student_session_id" value="<?php echo $student["student_session_id"]; ?>" readonly="readonly"/>
<input  type="hidden" class="form-control" id="group_parent_app_key" name="parent_app_key" value="<?php echo $student['parent_app_key'] ?>" readonly="readonly"/>
<input  type="hidden" class="form-control" id="group_guardian_phone" name="guardian_phone" value="<?php echo $student['guardian_phone'] ?>" readonly="readonly"/>
<input  type="hidden" class="form-control" id="group_guardian_email" name="guardian_email" value="<?php echo $student['guardian_email'] ?>" readonly="readonly"/>
<!-- //================ -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('pay_fees'); ?></h4>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </form>
    </div>
</div>

<div id="processing_fess_modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('processing_fess'); ?></h4>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

     $(document).on('click', '.printDoc', function () {
            var main_invoice = $(this).data('main_invoice');
            var sub_invoice = $(this).data('sub_invoice');
                var fee_category = $(this).data('fee-category');
            var student_session_id = '<?php echo $student['student_session_id'] ?>';
            $.ajax({
                url: base_url+'user/user/printFeesByName',
                type: 'post',
                dataType:"JSON",
                data: {'fee_category': fee_category,'student_session_id': student_session_id, 'main_invoice': main_invoice, 'sub_invoice': sub_invoice},
                success: function (response) {
                  Popup(response.page);
                }
            });
        });

       $("#select_all").change(function () {  //"select all" change
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

        $(document).ready(function () {
         $('#listCollectionModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });

        $(document).on('click', '.printSelected', function () {
            var print_btn=$(this);
            var array_to_print = [];
            $.each($("input[name='fee_checkbox']:checked"), function () {
                   var trans_fee_id = $(this).data('trans_fee_id');
                var fee_category = $(this).data('fee_category');
                var fee_session_group_id = $(this).data('fee_session_group_id');
                var fee_master_id = $(this).data('fee_master_id');
                var fee_groups_feetype_id = $(this).data('fee_groups_feetype_id');
                item = {};
                 item ["fee_category"] = fee_category;
                item ["trans_fee_id"] = trans_fee_id;
                item ["fee_session_group_id"] = fee_session_group_id;
                item ["fee_master_id"] = fee_master_id;
                item ["fee_groups_feetype_id"] = fee_groups_feetype_id;

                array_to_print.push(item);
            });
            if (array_to_print.length === 0) {
                errorMsg("<?php echo $this->lang->line('please_select_record'); ?>");
            } else {
                $.ajax({
                    url: '<?php echo site_url("user/user/printFeesByGroupArray") ?>',
                    type: 'post',
                    data: {'data': JSON.stringify(array_to_print)},
                     beforeSend: function () {
                print_btn.button('loading');
            },
                    success: function (response) {
                        Popup(response);
                    },
                    error: function (xhr) { // if error occured
                print_btn.button('reset');
                errorMsg("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

            },
            complete: function () {
                print_btn.button('reset');
            }
                });
            }
        });

  $(document).on('click', '.collectSelected', function () {
            var $this = $(this);
            var array_to_collect_fees = [];
            var select_count=0;
            $.each($("input[name='fee_checkbox']:checked"), function () {
                var trans_fee_id = $(this).data('trans_fee_id');
                var fee_category = $(this).data('fee_category');
                var fee_session_group_id = $(this).data('fee_session_group_id');
                var fee_master_id = $(this).data('fee_master_id');
                var fee_groups_feetype_id = $(this).data('fee_groups_feetype_id');
                item = {};
                item ["fee_category"] = fee_category;
                item ["trans_fee_id"] = trans_fee_id;
                item ["fee_session_group_id"] = fee_session_group_id;
                item ["fee_master_id"] = fee_master_id;
                item ["fee_groups_feetype_id"] = fee_groups_feetype_id;
                array_to_collect_fees.push(item);
                select_count++;
            });

            if(select_count > 0){
                $.ajax({
                type: 'POST',
                url: base_url + "user/user/getcollectfee",
                data: {'data': JSON.stringify(array_to_collect_fees)},
                dataType: "JSON",
                beforeSend: function () {
                    $this.button('loading');
                },
                success: function (data) {
                    $("#listCollectionModal .modal-body").html(data.view);
                    $("#listCollectionModal").modal('show');
                    $this.button('reset');
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

                },
                complete: function () {
                    $this.button('reset');
                }
            });
            }else{
                errorMsg('<?php echo $this->lang->line('please_select_record') ?>');

            }
        });

  $(document).on('click', '.getProcessingfees', function () {
            var $this = $(this);

                $.ajax({
                type: 'POST',
                url: base_url + "user/user/getProcessingfees",

                dataType: "JSON",
                beforeSend: function () {
                    $this.button('loading');
                },
                success: function (data) {
                    $("#processing_fess_modal .modal-body").html(data.view);
                    $("#processing_fess_modal").modal('show');
                    $this.button('reset');
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

                },
                complete: function () {
                    $this.button('reset');
                }
            });
        });
        });

    var base_url = '<?php echo base_url() ?>';

    function Popup(data, winload = false)
    {
        var frame1 = $('<iframe />').attr("id", "printDiv");
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
        document.getElementById('printDiv').contentWindow.focus();
        document.getElementById('printDiv').contentWindow.print();
        $("#printDiv", top.document).remove();
            if (winload) {
                window.location.reload(true);
            }
        }, 500);

        return true;
    }

</script>



<script type="text/javascript">
    function submitform(type,element){
        $(element).closest("form").find("input[name=submit_mode]").val(type);
        $(element).closest("form").submit();
    }
   

</script>