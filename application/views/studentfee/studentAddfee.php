<style type="text/css">
    .checkbox-inline+.checkbox-inline, .radio-inline+.radio-inline {
    margin-left: 8px;}
</style>
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$language        = $this->customlib->getLanguage();
$language_name   = $language["short_code"];
?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <section class="content-header">

            </section>
        </div>
        <div>
            <a id="sidebarCollapse" class="studentsideopen"><i class="fa fa-navicon"></i></a>
            <aside class="studentsidebar">
                <div class="stutop" id="">
                    <!-- Create the tabs -->
                    <div class="studentsidetopfixed">
                        <p class="classtap"><?php echo $student["class"]; ?> <a href="#" data-toggle="control-sidebar" class="studentsideclose"><i class="fa fa-times"></i></a></p>
                        <ul class="nav nav-justified studenttaps">
                            <?php foreach ($class_section as $skey => $svalue) {
    ?>
                                <li <?php
if ($student["section_id"] == $svalue["section_id"]) {
        echo "class='active'";
    }
    ?> ><a href="#section<?php echo $svalue["section_id"] ?>" data-toggle="tab"><?php print_r($svalue["section"]);?></a></li>
                                <?php }?>
                        </ul>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <?php foreach ($class_section as $skey => $snvalue) {
    ?>
                            <div class="tab-pane <?php
if ($student["section_id"] == $snvalue["section_id"]) {
        echo "active";
    }
    ?>" id="section<?php echo $snvalue["section_id"]; ?>">
                                 <?php
foreach ($studentlistbysection as $stkey => $stvalue) {
        if ($stvalue['section_id'] == $snvalue["section_id"]) {
            ?>
                                        <div class="studentname">
                                            <a class="" href="<?php echo base_url() . "studentfee/addfee/" . $stvalue["student_session_id"] ?>">
                                                <div class="icon"><img src="<?php echo base_url() . $stvalue["image"] . img_time(); ?>" alt="User Image"></div>
                                                <div class="student-tittle"><?php echo $stvalue["firstname"] . " " . $stvalue["lastname"]; ?></div></a>
                                        </div>
                                        <?php
}
    }
    ?>
                            </div>
                        <?php }?>
                        <div class="tab-pane" id="sectionB">
                            <h3 class="control-sidebar-heading">Recent Activity 2</h3>
                        </div>
                        <div class="tab-pane" id="sectionC">
                            <h3 class="control-sidebar-heading">Recent Activity 3</h3>
                        </div>
                        <div class="tab-pane" id="sectionD">
                            <h3 class="control-sidebar-heading">Recent Activity 3</h3>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                </div>
            </aside>
        </div></div>
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
                            <div class="col-md-8">
                                <div class="btn-group pull-right">
                                    <a href="<?php echo base_url() ?>studentfee" type="button" class="btn btn-primary btn-xs">
                                        <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div><!--./box-header-->
                    <div class="box-body" style="padding-top:0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="sfborder-top-border">
                                    <div class="col-md-2">
                                        <img width="115" height="115" class="mt5 mb10 sfborder-img-shadow img-responsive img-rounded" src="<?php
if (!empty($student["image"])) {
    echo $this->media_storage->getImageURL($student["image"]);
} else {
    if ($student['gender'] == 'Female') {
        echo $this->media_storage->getImageURL("uploads/student_images/default_female.jpg");
    } elseif ($student['gender'] == 'Male') {
        echo $this->media_storage->getImageURL("uploads/student_images/default_male.jpg");
    }
}
?>
                " alt="No Image">
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
                                                        <th><?php echo $this->lang->line('father_name'); ?></th>
                                                        <td><?php echo $student['father_name']; ?></td>
                                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                        <td><?php echo $student['admission_no']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('mobile_number'); ?></th>
                                                        <td><?php echo $student['mobileno']; ?></td>
                                                        <th><?php echo $this->lang->line('roll_number'); ?></th>
                                                        <td> <?php echo $student['roll_no']; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
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
                                                        <?php if ($sch_setting->rte) {?>
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
                        <div class="row no-print mb10">
                            <div class="col-md-12 mDMb10">
                                <div class="float-rtl-right float-left">
                                <button type="button" class="btn btn-sm btn-info printSelected" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait') ?>"><i class="fa fa-print"></i> <?php echo $this->lang->line('print_selected'); ?></button>
                                
                                <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_add')) { ?>
                                <button type="button" class="btn btn-sm btn-warning collectSelected" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait') ?>"><i class="fa fa-money"></i> <?php echo $this->lang->line('collect_selected'); ?></button><?php } ?>
                                
                                <?php
if ($student_processing_fee) {?>
                                    <a  href="javascript:void(0)" class="btn btn-sm btn-info getProcessingfees" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait') ?>"><i class="fa fa-money"></i> <?php echo $this->lang->line('processing_fees') ?></a>
                                <?php
}
?>                              </div>
                                <span class="pull-right pt5"><?php echo $this->lang->line('date'); ?>: <?php echo date($this->customlib->getSchoolDateFormat()); ?></span>
                            </div>
                        </div>
                        <?php
$student_admission_no = '';
if ($student['admission_no'] != '') {
    $student_admission_no = ' (' . $student['admission_no'] . ')';
}
?>
                        <div class="table-responsive">
                            <div class="download_label "><?php echo $this->lang->line('student_fees') . ": " . $student['firstname'] . " " . $student['lastname'] . $student_admission_no; ?> </div>
                            <table class="table table-striped table-bordered table-hover example table-fixed-header">
                                <thead class="header">
                                    <tr>
                                        <th style="width: 10px"><input type="checkbox" id="select_all"/></th>
                                        <th align="left"><?php echo $this->lang->line('fees_group'); ?></th>
                                        <th align="left"><?php echo $this->lang->line('fees_code'); ?></th>
                                        <th align="left" class="text text-left"><?php echo $this->lang->line('due_date'); ?></th>
                                        <th align="left" class="text text-left"><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text text-right"><?php echo $this->lang->line('amount') ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-left"><?php echo $this->lang->line('payment_id'); ?></th>
                                        <th class="text text-left"><?php echo $this->lang->line('mode'); ?></th>
                                        <th  class="text text-left"><?php echo $this->lang->line('date'); ?></th>
                                        <th class="text text-right" ><?php echo $this->lang->line('discount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('fine'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('paid'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('balance'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

$total_amount           = 0;
$total_deposite_amount  = 0;
$total_fine_amount      = 0;
$total_fees_fine_amount = 0;

$total_discount_amount = 0;
$total_balance_amount  = 0;
$alot_fee_discount     = 0;

foreach ($student_due_fee as $key => $fee) {

    foreach ($fee->fees as $fee_key => $fee_value) {
        $fee_paid         = 0;
        $fee_discount     = 0;
        $fee_fine         = 0;
        $fees_fine_amount = 0;
        $feetype_balance  = 0;
        if (!empty($fee_value->amount_detail)) {
            $fee_deposits = json_decode(($fee_value->amount_detail));

            foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                $fee_paid     = $fee_paid + $fee_deposits_value->amount;
                $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
            }
        }
        if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {
            $fees_fine_amount       = $fee_value->fine_amount;
            $total_fees_fine_amount = $total_fees_fine_amount + $fee_value->fine_amount;
        }

        $total_amount += $fee_value->amount;
        $total_discount_amount += $fee_discount;
        $total_deposite_amount += $fee_paid;
        $total_fine_amount += $fee_fine;
        $feetype_balance = $fee_value->amount - ($fee_paid + $fee_discount);
        $total_balance_amount += $feetype_balance;
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
          <input class="checkbox" type="checkbox" name="fee_checkbox" data-fee_master_id="<?php echo $fee_value->id ?>" data-fee_session_group_id="<?php echo $fee_value->fee_session_group_id ?>" data-fee_groups_feetype_id="<?php echo $fee_value->fee_groups_feetype_id ?>" data-fee_category="fees"  data-trans_fee_id="0">
                                                </td>
                                                <td align="left" class="text-rtl-right">
                                                    <?php
if ($fee_value->is_system) {
    echo $fee_value->name;
            echo $this->lang->line($fee_value->name) . " (" . $this->lang->line($fee_value->type) . ")";
        } else {
            echo $fee_value->name . " (" . $fee_value->type . ")";
        }
        ?></td>
                                                <td align="left" class="text-rtl-right">
                                                    <?php
if ($fee_value->is_system) {
            echo $this->lang->line($fee_value->code);
        } else {
            echo $fee_value->code;
        }

        ?></td>
                                                <td align="left" class="text text-left">
                                                    <?php
if ($fee_value->due_date == "0000-00-00") {

        } else {

            if ($fee_value->due_date) {
                echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_value->due_date));
            }
        }
        ?>
                                                </td>
                                                <td align="left" class="text text-left width85">
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
<?php echo amountFormat($fee_value->amount);
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
        ?>
                                                    </td>
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
                                                <td class="text text-right"><?php
$display_none = "ss-none";
        if ($feetype_balance > 0) {
            $display_none = "";

            echo amountFormat($feetype_balance);
        }
        ?>
                                                </td>
                                                <td width="100">
                                                    <div class="btn-group">
                                                      <div class="pull-right">
                                                      
                                                      <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_add')) { ?>
                                                        <button type="button" data-student_session_id="<?php echo $fee->student_session_id; ?>" data-student_fees_master_id="<?php echo $fee->id; ?>" data-fee_groups_feetype_id="<?php echo $fee_value->fee_groups_feetype_id; ?>" data-group="<?php echo ($fee_value->is_system) ? $this->lang->line($fee_value->name). " (" . $this->lang->line($fee_value->type) . ")" : $fee_value->name. " (" . $fee_value->type . ")"; ?>"  data-type="<?php echo ($fee_value->is_system) ? $this->lang->line($fee_value->type) : $fee_value->code; ?>"
                                                                class="btn btn-xs btn-default myCollectFeeBtn <?php echo $display_none; ?>"
                                                                title="<?php echo $this->lang->line('add_fees'); ?>" data-toggle="modal" data-target="#myFeesModal" data-fee-category="fees"  data-trans_fee_id="0"
                                                      ><i class="fa fa-plus"></i></button><?php } ?>

<button  class="btn btn-xs btn-default printInv" data-student_session_id="<?php echo $fee->student_session_id; ?>" data-fee_master_id="<?php echo $fee_value->id ?>" data-fee_session_group_id="<?php echo $fee_value->fee_session_group_id ?>" data-fee_groups_feetype_id="<?php echo $fee_value->fee_groups_feetype_id ?>" data-fee-category="fees"  data-trans_fee_id="0" title="<?php echo $this->lang->line('print'); ?>" data-loading-text="<i class='fa fa-spinner fa-spin '></i>"><i class="fa fa-print"></i> </button>
                                                    </div>
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
                                                        <td class="text-right"><img src="<?php echo $this->media_storage->getImageURL('backend/images/table-arrow.png'); ?>" alt="" /></td>
                                                        <td class="text text-left">                                                            <a href="#" data-toggle="popover" class="detail_popover" > <?php echo $fee_value->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?></a>
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
                                                            <?php if ($fee_deposits_value->date) {echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_deposits_value->date));}?>
                                                        </td>
                                                        <td class="text text-right"><?php echo amountFormat($fee_deposits_value->amount_discount); ?></td>
                                                        <td class="text text-right"><?php echo amountFormat($fee_deposits_value->amount_fine); ?></td>
                                                        <td class="text text-right"><?php echo amountFormat($fee_deposits_value->amount); ?></td>
                                                        <td></td>
                                                        <td class="text text-right">
                                                            <div class="btn-group">
                                                               <div class="pull-right">
                                                                <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_delete')) {?>
                                                                    <button class="btn btn-default btn-xs" data-invoiceno="<?php echo $fee_value->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?>" data-main_invoice="<?php echo $fee_value->student_fees_deposite_id ?>" data-sub_invoice="<?php echo $fee_deposits_value->inv_no ?>" data-toggle="modal" data-target="#confirm-delete" title="<?php echo $this->lang->line('revert'); ?>">
                                                                        <i class="fa fa-undo"> </i>
                                                                    </button>
                                                                <?php }?>
                                                                <button  class="btn btn-xs btn-default printDoc" data-main_invoice="<?php echo $fee_value->student_fees_deposite_id ?>" data-sub_invoice="<?php echo $fee_deposits_value->inv_no ?>"  title="<?php echo $this->lang->line('print'); ?>"><i class="fa fa-print"></i> </button>
                                                            </div>
                                                          </div>
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
                      <input class="checkbox" type="checkbox" name="fee_checkbox" data-fee_master_id="0" data-fee_session_group_id="0" data-fee_groups_feetype_id="0" data-fee_category="transport"  data-trans_fee_id="<?php echo $transport_fee_value->id; ?>">

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
            <td class="text text-right">
                <?php

        echo amountFormat($transport_fee_value->fees);

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
                                                <td class="text text-right"><?php
echo amountFormat($fee_discount);
        ?></td>
                                                <td class="text text-right"><?php
echo amountFormat($fee_fine);
        ?></td>
                                                <td class="text text-right"><?php
echo amountFormat($fee_paid);
        ?></td>
                                                  <td class="text text-right"><?php
$display_none = "ss-none";
        if ($feetype_balance > 0) {
            $display_none = "";

            echo amountFormat($feetype_balance);
        }
        ?>
                                                </td>
                                                <td width="100">
                                                
                                                <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_add')) { ?>
            <button type="button" data-student_session_id="<?php echo $transport_fee_value->student_session_id; ?>" data-student_fees_master_id="0" data-fee_groups_feetype_id="0"
                                                data-group="<?php echo $this->lang->line('transport_fees'); ?>"  data-type="<?php echo $transport_fee_value->month; ?>" class="btn btn-xs btn-default myCollectFeeBtn <?php echo $display_none; ?>"  title="<?php echo $this->lang->line('add_fees'); ?>" data-toggle="modal" data-target="#myFeesModal" data-fee-category="transport" data-trans_fee_id="<?php echo $transport_fee_value->id; ?>"><i class="fa fa-plus"></i></button><?php } ?>

                 <button  class="btn btn-xs btn-default printInv"  data-student_session_id="<?php echo $transport_fee_value->student_session_id; ?>" data-fee_master_id="0" data-fee_session_group_id="0" data-fee_groups_feetype_id="0" data-fee-category="transport"  data-trans_fee_id="<?php echo $transport_fee_value->id; ?>" title="<?php echo $this->lang->line('print'); ?>" data-loading-text="<i class='fa fa-spinner fa-spin '></i>"><i class="fa fa-print"></i> </button>

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
                                                            <div class="btn-group ">
                                                               <div class="pull-right">
                                                                <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_delete')) {?>
                                                                    <button class="btn btn-default btn-xs" data-invoiceno="<?php echo $transport_fee_value->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?>" data-main_invoice="<?php echo $transport_fee_value->student_fees_deposite_id ?>" data-sub_invoice="<?php echo $fee_deposits_value->inv_no ?>" data-toggle="modal" data-target="#confirm-delete" title="<?php echo $this->lang->line('revert'); ?>">
                                                                        <i class="fa fa-undo"> </i>
                                                                    </button>
                                                                <?php }?>
                                                                <button  class="btn btn-xs btn-default printDoc" data-fee-category="transport" data-main_invoice="<?php echo $transport_fee_value->student_fees_deposite_id ?>" data-sub_invoice="<?php echo $fee_deposits_value->inv_no ?>"  title="<?php echo $this->lang->line('print'); ?>"><i class="fa fa-print"></i> </button>
                                                            </div>
                                                          </div>
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

                                                            <?php
if ($discount_value['type'] == "percentage") {
                echo "<p class='text text-success'>" . $this->lang->line('discount_of') . " " . $discount_value['percentage'] . "% " . $this->lang->line($discount_value['status']) . " : " . $discount_value['payment_id'] . "</p>";
            } else {
                echo "<p class='text text-success'>" . $this->lang->line('discount_of') . " " . $currency_symbol . amountFormat($discount_value['amount']) . " " . $this->lang->line($discount_value['status']) . " : " . $discount_value['payment_id'] . "</p>";
            }
            ?>
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
            if ($discount_value['type'] == "" || $discount_value['type'] == "fix") {
                echo '<p class="text text-danger">' . $this->lang->line('discount_of') . " " . $currency_symbol . amountFormat($discount_value['amount']) . " " . $this->lang->line($discount_value['status']);
            } else {
                echo '<p class="text text-danger">' . $this->lang->line('discount_of') . " " . $discount_value['percentage'] . "% " . $this->lang->line($discount_value['status']);
            }

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
                                                <td>
                                                    <div class="btn-group ">
                                                        <div class="pull-right">
                                                        <?php
if ($discount_value['status'] == "applied") {
            ?>
                                                            <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_delete')) {?>
                                                                <button class="btn btn-default btn-xs" data-discounttitle="<?php echo $discount_value['code']; ?>" data-discountid="<?php echo $discount_value['id']; ?>" data-toggle="modal" data-target="#confirm-discountdelete" title="<?php echo $this->lang->line('revert'); ?>">
                                                                    <i class="fa fa-undo"> </i>
                                                                </button>
                                                                <?php
}
        }
        ?>
                                                        <button type="button" data-modal_title="<?php echo $this->lang->line('discount') . " : " . $discount_value['code']; ?>" data-student_fees_discount_id="<?php echo $discount_value['id']; ?>"
                                                                class="btn btn-xs btn-default applydiscount"
                                                                title="<?php echo $this->lang->line('apply_discount'); ?>"
                                                                ><i class="fa fa-check"></i>
                                                        </button>

                                                    </div>
                                                   </div>
                                                </td>
                                            </tr>
                                            <?php
}
}
?>
                                    <tr class="box box-solid total-bg">
                                        <td align="left" ></td>
                                        <td align="left" ></td>
                                        <td align="left" ></td>
                                        <td align="left" ></td>
                                        <td align="left" class="text text-left" ><?php echo $this->lang->line('grand_total'); ?></td>
                                        <td class="text text-right">

<?php
echo $currency_symbol . (amountFormat($total_amount));
?>

<span data-toggle="popover" class="text text-danger detail_popover"><?php echo " + " . (amountFormat($total_fees_fine_amount)); ?></span>

<div class="fee_detail_popover" style="display: none">
    
        <p class="text text-danger"><?php echo $this->lang->line('fine'); ?></p>
 
</div>
                                            </td>
                                        <td class="text text-left"></td>
                                        <td class="text text-left"></td>
                                        <td class="text text-left"></td>
                                        <td class="text text-right"><?php
echo $currency_symbol . amountFormat(($total_discount_amount + $alot_fee_discount));
?></td>
                                        <td class="text text-right"><?php
echo $currency_symbol . amountFormat(($total_fine_amount));
?></td>
                                        <td class="text text-right"><?php
echo $currency_symbol . amountFormat(($total_deposite_amount));
?></td>
                                        <td class="text text-right"><?php
echo $currency_symbol . amountFormat(($total_balance_amount - $alot_fee_discount));
?></td>  <td class="text text-right"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!--/.col (left) -->
        </div>
    </section>
</div>

<div class="modal fade" id="myFeesModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title text-center fees_title"></h4>
            </div>
            <div class="modal-body pb0">
                <div class="form-horizontal balanceformpopup">
                    <div class="box-body">

                        <input  type="hidden" class="form-control" id="std_id" value="<?php echo $student["student_session_id"]; ?>" readonly="readonly"/>
                        <input  type="hidden" class="form-control" id="parent_app_key" value="<?php echo $student['parent_app_key'] ?>" readonly="readonly"/>
                        <input  type="hidden" class="form-control" id="guardian_phone" value="<?php echo $student['guardian_phone'] ?>" readonly="readonly"/>
                        <input  type="hidden" class="form-control" id="guardian_email" value="<?php echo $student['guardian_email'] ?>" readonly="readonly"/>
                        <input  type="hidden" class="form-control" id="student_fees_master_id" value="0" readonly="readonly"/>
                        <input  type="hidden" class="form-control" id="fee_groups_feetype_id" value="0" readonly="readonly"/>
                        <input  type="hidden" class="form-control" id="transport_fees_id" value="0" readonly="readonly"/>
                        <input  type="hidden" class="form-control" id="fee_category" value="" readonly="readonly"/>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label"><?php echo $this->lang->line('date'); ?><small class="req"> *</small></label>
                            <div class="col-sm-9">
                                <input  id="date" name="admission_date" placeholder="" type="text" class="form-control date_fee"  value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly="readonly"/>
                                <span class="text-danger" id="date_error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)<small class="req"> *</small></label>
                            <div class="col-sm-9">
                                <input type="text" autofocus="" class="form-control modal_amount" id="amount" value="0"  >
                                <span class="text-danger" id="amount_error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"> <?php echo $this->lang->line('discount_group'); ?></label>
                            <div class="col-sm-9">
                                <select class="form-control modal_discount_group" id="discount_group">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="text-danger" id="amount_error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('discount'); ?> (<?php echo $currency_symbol; ?>)<small class="req"> *</small></label>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-md-5 col-sm-5">
                                        <div class="">
                                            <input type="text" class="form-control" id="amount_discount"  value="0">

                                            <span class="text-danger" id="amount_discount_error"></span></div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 ltextright">
                                        <label for="inputPassword3" class="control-label"><?php echo $this->lang->line('fine'); ?> (<?php echo $currency_symbol; ?>)<small class="req">*</small></label>
                                    </div>
                                    <div class="col-md-5 col-sm-5">
                                        <div class="">
                                            <input type="text" class="form-control" id="amount_fine" value="0">
                                            <span class="text-danger" id="amount_fine_error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div><!--./col-sm-9-->
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('payment_mode'); ?></label>
                            <div class="col-sm-9">
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="Cash" checked="checked"><?php echo $this->lang->line('cash'); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="Cheque"><?php echo $this->lang->line('cheque'); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="DD"><?php echo $this->lang->line('dd'); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="bank_transfer"><?php echo $this->lang->line('bank_transfer'); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="upi"><?php echo $this->lang->line('upi'); ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payment_mode_fee" value="card"><?php echo $this->lang->line('card'); ?>
                                </label>
                                <span class="text-danger" id="payment_mode_error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('note'); ?></label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" id="description" placeholder=""></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <button type="button" class="btn cfees save_button" id="load" data-action="collect" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $currency_symbol; ?> <?php echo $this->lang->line('collect_fees'); ?> </button>
                <button type="button" class="btn cfees save_button" id="load" data-action="print" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $currency_symbol; ?> <?php echo $this->lang->line('collect_print'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myDisApplyModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title text-center discount_title"></h4>
            </div>
            <div class="modal-body pb0">
                <div class="form-horizontal">
                    <div class="box-body">
                        <input  type="hidden" class="form-control" id="student_fees_discount_id"  value=""/>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('payment_id'); ?> <small class="req">*</small></label>
                            <div class="col-sm-9">

                                <input type="text" class="form-control" id="discount_payment_id" >

                                <span class="text-danger" id="discount_payment_id_error"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo $this->lang->line('description'); ?></label>

                            <div class="col-sm-9">
                                <textarea class="form-control" rows="3" id="dis_description" placeholder=""></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <button type="button" class="btn cfees dis_apply_button" id="load" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('apply_discount'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="delmodal modal fade" id="confirm-discountdelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirmation'); ?></h4>
            </div>
            <div class="modal-body">
                <p><?php echo $this->lang->line('are_you_sure_want_to_revert'); ?> <b class="discount_title"></b> <?php echo $this->lang->line('discount_this_action_is_irreversible'); ?></p>
                <p><?php echo $this->lang->line('do_you_want_to_proceed') ?></p>
                <p class="debug-url"></p>
                <input type="hidden" name="discount_id"  id="discount_id" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <a class="btn btn-danger btn-discountdel"><?php echo $this->lang->line('revert'); ?></a>
            </div>
        </div>
    </div>
</div>

<div class="delmodal modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirmation'); ?></h4>
            </div>
            <div class="modal-body">
                <p><?php echo $this->lang->line('are_you_sure_want_to_delete'); ?> <b class="invoice_no"></b> <?php echo $this->lang->line('invoice_this_action_is_irreversible') ?></p>
                 <p><?php echo $this->lang->line('do_you_want_to_proceed') ?></p>
                <p class="debug-url"></p>
                <input type="hidden" name="main_invoice"  id="main_invoice" value="">
                <input type="hidden" name="sub_invoice" id="sub_invoice"  value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <a class="btn btn-danger btn-ok"><?php echo $this->lang->line('revert'); ?></a>
            </div>
        </div>
    </div>
</div>

<div class="norecord modal fade" id="confirm-norecord" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p><?php echo $this->lang->line('no_record_found'); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<div id="listCollectionModal" class="modal fade">
    <div class="modal-dialog">
        <form action="<?php echo site_url('studentfee/addfeegrp'); ?>" method="POST" id="collect_fee_group">
            <div class="modal-content">
<!-- //================ -->
 <input  type="hidden" class="form-control" id="group_std_id" name="student_session_id" value="<?php echo $student["student_session_id"]; ?>" readonly="readonly"/>
<input  type="hidden" class="form-control" id="group_parent_app_key" name="parent_app_key" value="<?php echo $student['parent_app_key'] ?>" readonly="readonly"/>
<input  type="hidden" class="form-control" id="group_guardian_phone" name="guardian_phone" value="<?php echo $student['guardian_phone'] ?>" readonly="readonly"/>
<input  type="hidden" class="form-control" id="group_guardian_email" name="guardian_email" value="<?php echo $student['guardian_email'] ?>" readonly="readonly"/>
<!-- //================ -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('collect_fees'); ?></h4>
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
                    <h4 class="modal-title"><?php echo $this->lang->line('processing_fees'); ?></h4>
                </div>
                <div class="modal-body">

                </div>
            </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#listCollectionModal,#processing_fess_modal,#confirm-norecord,#myFeesModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });
     
    $(document).ready(function () {
            $(document).on('click', '.printDoc', function () {
            var main_invoice = $(this).data('main_invoice');
            var sub_invoice = $(this).data('sub_invoice');
            var fee_category = $(this).data('fee-category');
            var student_session_id = '<?php echo $student['student_session_id'] ?>';
            $.ajax({
                url: '<?php echo site_url("studentfee/printFeesByName") ?>',
                type: 'post',
                dataType:"JSON",
                data: {'fee_category': fee_category,'student_session_id': student_session_id, 'main_invoice': main_invoice, 'sub_invoice': sub_invoice},
                success: function (response) {
                    Popup(response.page);
                }
            });
        });

        $(document).on('click', '.printInv', function () {
            var $this= $(this);
            var fee_master_id = $(this).data('fee_master_id');
            var student_session_id = $(this).data('student_session_id');
            var fee_session_group_id = $(this).data('fee_session_group_id');
            var fee_groups_feetype_id = $(this).data('fee_groups_feetype_id');
            var trans_fee_id = $(this).data('trans_fee_id');
            var fee_category = $(this).data('feeCategory');
            $.ajax({
                url: '<?php echo site_url("studentfee/printFeesByGroup") ?>',
                type: 'post',
                dataType:"JSON",
                data: {'trans_fee_id':trans_fee_id,'fee_category':fee_category,'fee_groups_feetype_id': fee_groups_feetype_id, 'fee_master_id': fee_master_id, 'fee_session_group_id': fee_session_group_id, 'student_session_id': student_session_id},
                 beforeSend: function() {
        $this.button('loading');
    },

                success: function (response) {

                    Popup(response.page);
                     $this.button('reset');
                },
                  error: function(xhr) { // if error occured
        alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
         $this.button('reset');
    },
    complete: function() {
      $this.button('reset');
    }

            });
        });
    });

      $(document).on('click', '.getProcessingfees', function () {
            var $this = $(this);
                var student_session_id = '<?php echo $student['student_session_id'] ?>';
                $.ajax({
                type: 'POST',
                url: base_url + "studentfee/getProcessingfees/"+student_session_id,

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

</script>

<script type="text/javascript">
    $(document).on('click', '.save_button', function (e) {
        var $this = $(this);
        var action = $this.data('action');
        $this.button('loading');
        var form = $(this).attr('frm');
        var feetype = $('#feetype_').val();
        var date = $('#date').val();
        var student_session_id = $('#std_id').val();
        var amount = $('#amount').val();
        var amount_discount = $('#amount_discount').val();
        var amount_fine = $('#amount_fine').val();
        var description = $('#description').val();
        var parent_app_key = $('#parent_app_key').val();
        var guardian_phone = $('#guardian_phone').val();
        var guardian_email = $('#guardian_email').val();
        var student_fees_master_id = $('#student_fees_master_id').val();
        var fee_groups_feetype_id = $('#fee_groups_feetype_id').val();
        var transport_fees_id = $('#transport_fees_id').val();
        var fee_category = $('#fee_category').val();
        var payment_mode = $('input[name="payment_mode_fee"]:checked').val();
        var student_fees_discount_id = $('#discount_group').val();
        $.ajax({
            url: '<?php echo site_url("studentfee/addstudentfee") ?>',
            type: 'post',
            data: {action: action, student_session_id: student_session_id, date: date, type: feetype, amount: amount, amount_discount: amount_discount, amount_fine: amount_fine, description: description, student_fees_master_id: student_fees_master_id, fee_groups_feetype_id: fee_groups_feetype_id,fee_category:fee_category, transport_fees_id:transport_fees_id, payment_mode: payment_mode, guardian_phone: guardian_phone, guardian_email: guardian_email, student_fees_discount_id: student_fees_discount_id, parent_app_key: parent_app_key},
            dataType: 'json',
            success: function (response) {
                $this.button('reset');
                if (response.status === "success") {
                    if (action === "collect") {
                        location.reload(true);
                    } else if (action === "print") {
                        Popup(response.print, true);
                    }
                } else if (response.status === "fail") {
                    $.each(response.error, function (index, value) {
                        var errorDiv = '#' + index + '_error';
                        $(errorDiv).empty().append(value);
                    });
                }
            }
        });
    });
</script>

<script>
    var base_url = '<?php echo base_url() ?>';

    function Popup(data, winload = false)
    {
        var frameDoc=window.open('', 'Print-Window');
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body onload="window.print()">');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
            frameDoc.close();      
            if (winload) {
                window.location.reload(true);
            }
        }, 5000);

        return true;
    }
    
    $(document).ready(function () {
        $('.delmodal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
        $('#listCollectionModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });

        $('#confirm-delete').on('show.bs.modal', function (e) {
            $('.invoice_no', this).text("");
            $('#main_invoice', this).val("");
            $('#sub_invoice', this).val("");
            $('.invoice_no', this).text($(e.relatedTarget).data('invoiceno'));
            $('#main_invoice', this).val($(e.relatedTarget).data('main_invoice'));
            $('#sub_invoice', this).val($(e.relatedTarget).data('sub_invoice'));
        });

        $('#confirm-discountdelete').on('show.bs.modal', function (e) {
            $('.discount_title', this).text("");
            $('#discount_id', this).val("");
            $('.discount_title', this).text($(e.relatedTarget).data('discounttitle'));
            $('#discount_id', this).val($(e.relatedTarget).data('discountid'));
        });

        $('#confirm-delete').on('click', '.btn-ok', function (e) {
            var $modalDiv = $(e.delegateTarget);
            var main_invoice = $('#main_invoice').val();
            var sub_invoice = $('#sub_invoice').val();

            $modalDiv.addClass('modalloading');
            $.ajax({
                type: "post",
                url: '<?php echo site_url("studentfee/deleteFee") ?>',
                dataType: 'JSON',
                data: {'main_invoice': main_invoice, 'sub_invoice': sub_invoice},
                success: function (data) {
                    $modalDiv.modal('hide').removeClass('modalloading');
                    location.reload(true);
                }
            });
        });

        $('#confirm-discountdelete').on('click', '.btn-discountdel', function (e) {
            var $modalDiv = $(e.delegateTarget);
            var discount_id = $('#discount_id').val();

            $modalDiv.addClass('modalloading');
            $.ajax({
                type: "post",
                url: '<?php echo site_url("studentfee/deleteStudentDiscount") ?>',
                dataType: 'JSON',
                data: {'discount_id': discount_id},
                success: function (data) {
                    $modalDiv.modal('hide').removeClass('modalloading');
                    location.reload(true);
                }
            });
        });

        $(document).on('click', '.btn-ok', function (e) {
            var $modalDiv = $(e.delegateTarget);
            var main_invoice = $('#main_invoice').val();
            var sub_invoice = $('#sub_invoice').val();

            $modalDiv.addClass('modalloading');
            $.ajax({
                type: "post",
                url: '<?php echo site_url("studentfee/deleteFee") ?>',
                dataType: 'JSON',
                data: {'main_invoice': main_invoice, 'sub_invoice': sub_invoice},
                success: function (data) {
                    $modalDiv.modal('hide').removeClass('modalloading');
                    location.reload(true);
                }
            });

        });
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
    var fee_amount = 0;
    var fee_type_amount = 0;
</script>

<script type="text/javascript">
    $("#myFeesModal").on('shown.bs.modal', function (e) {
        e.stopPropagation();
        var discount_group_dropdown = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        var data = $(e.relatedTarget).data();
        console.log(data);

        var modal = $(this);
        var type = data.type;
        var amount = data.amount;
        var group = data.group;
        var fee_groups_feetype_id = data.fee_groups_feetype_id;
        var student_fees_master_id = data.student_fees_master_id;
        var student_session_id = data.student_session_id;
        var fee_category=data.feeCategory;
        var trans_fee_id=data.trans_fee_id;

        $('.fees_title').html("");
        $('.fees_title').html("<b>" + group + ":</b> " + type);
        $('#fee_groups_feetype_id').val(fee_groups_feetype_id);
        $('#student_fees_master_id').val(student_fees_master_id);
        $('#transport_fees_id').val(trans_fee_id);
        $('#fee_category').val(fee_category);

        $.ajax({
            type: "post",
            url: '<?php echo site_url("studentfee/geBalanceFee") ?>',
            dataType: 'JSON',
            data: {'fee_groups_feetype_id': fee_groups_feetype_id,
                'student_fees_master_id': student_fees_master_id,
                'student_session_id': student_session_id,
                'fee_category' : fee_category,
                'trans_fee_id' : trans_fee_id
            },
            beforeSend: function () {
                $('#discount_group').html("");
                $("span[id$='_error']").html("");
                $('#amount').val("");
                $('#amount_discount').val("0");
                $('#amount_fine').val("0"); 
            },
            success: function (data) {

                if (data.status === "success") {
                    fee_amount = data.balance;
                    fee_type_amount = data.student_fees;
                    $('#amount').val(data.balance);
                    $('#amount_fine').val(data.remain_amount_fine);
                    $.each(data.discount_not_applied, function (i, obj)
                    {
                     discount_group_dropdown += "<option value=" + obj.student_fees_discount_id + " data-disamount=" + obj.amount + " data-type=" + obj.type + " data-percentage=" + obj.percentage + ">" + obj.code + "</option>";
                    });
                    $('#discount_group').append(discount_group_dropdown);

                }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

            },
            complete: function () { 
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $.extend($.fn.dataTable.defaults, {
            searching: false,
            ordering: false,
            paging: false,
            bSort: false,
            info: false
        });
    });

    $(document).ready(function () {
        $('.table-fixed-header').fixedHeader();
    });

    (function ($) {

        $.fn.fixedHeader = function (options) {
            var config = {
                topOffset: 50
                        //bgColor: 'white'
            };
            if (options) {
                $.extend(config, options);
            }

            return this.each(function () {
                var o = $(this);

                var $win = $(window);
                var $head = $('thead.header', o);
                var isFixed = 0;
                var headTop = $head.length && $head.offset().top - config.topOffset;

                function processScroll() {
                    if (!o.is(':visible')) {
                        return;
                    }
                    if ($('thead.header-copy').size()) {
                        $('thead.header-copy').width($('thead.header').width());
                    }
                    var i;
                    var scrollTop = $win.scrollTop();
                    var t = $head.length && $head.offset().top - config.topOffset;
                    if (!isFixed && headTop !== t) {
                        headTop = t;
                    }
                    if (scrollTop >= headTop && !isFixed) {
                        isFixed = 1;
                    } else if (scrollTop <= headTop && isFixed) {
                        isFixed = 0;
                    }
                    isFixed ? $('thead.header-copy', o).offset({
                        left: $head.offset().left
                    }).removeClass('hide') : $('thead.header-copy', o).addClass('hide');
                }
                $win.on('scroll', processScroll);

                // hack sad times - holdover until rewrite for 2.1
                $head.on('click', function () {
                    if (!isFixed) {
                        setTimeout(function () {
                            $win.scrollTop($win.scrollTop() - 47);
                        }, 10);
                    }
                });

                $head.clone().removeClass('header').addClass('header-copy header-fixed').appendTo(o);
                var header_width = $head.width();
                o.find('thead.header-copy').width(header_width);
                o.find('thead.header > tr:first > th').each(function (i, h) {
                    var w = $(h).width();
                    o.find('thead.header-copy> tr > th:eq(' + i + ')').width(w);
                });
                $head.css({
                    margin: '0 auto',
                    width: o.width(),
                    'background-color': config.bgColor
                });
                processScroll();
            });
        };

    })(jQuery);

    $(".applydiscount").click(function () {
        $("span[id$='_error']").html("");
        $('.discount_title').html("");
        $('#student_fees_discount_id').val("");
        var student_fees_discount_id = $(this).data("student_fees_discount_id");
        var modal_title = $(this).data("modal_title");
        $('.discount_title').html("<b>" + modal_title + "</b>");
        $('#student_fees_discount_id').val(student_fees_discount_id);
        $('#myDisApplyModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    });

    $(document).on('click', '.dis_apply_button', function (e) {
        var $this = $(this);
        $this.button('loading');
        var discount_payment_id = $('#discount_payment_id').val();
        var student_fees_discount_id = $('#student_fees_discount_id').val();
        var dis_description = $('#dis_description').val();

        $.ajax({
            url: '<?php echo site_url("admin/feediscount/applydiscount") ?>',
            type: 'post',
            data: {
                discount_payment_id: discount_payment_id,
                student_fees_discount_id: student_fees_discount_id,
                dis_description: dis_description
            },
            dataType: 'json',
            success: function (response) {
                $this.button('reset');
                if (response.status === "success") {
                    location.reload(true);
                } else if (response.status === "fail") {
                    $.each(response.error, function (index, value) {
                        var errorDiv = '#' + index + '_error';
                        $(errorDiv).empty().append(value);
                    });
                }
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.printSelected', function () {
            var array_to_print = [];
                var $this = $(this);
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
                alert("<?php echo $this->lang->line('no_record_selected'); ?>");
            } else {
                $.ajax({
                    url: '<?php echo site_url("studentfee/printFeesByGroupArray") ?>',
                    type: 'post',
                    data: {'data': JSON.stringify(array_to_print)},
                     beforeSend: function () {
                    $this.button('loading');
                    },
                    success: function (response) {
                        Popup(response);
                         $this.button('reset');
                    },
                     error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

                },
                complete: function () {
                    $this.button('reset');
                }
                });
            }
        });

        $(document).on('click', '.collectSelected', function () {
            var $this = $(this);
            var array_to_collect_fees = [];
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
            });

            $.ajax({
                type: 'POST',
                url: base_url + "studentfee/getcollectfee",
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
        });
    });

    $(function () {
        $(document).on('change', "#discount_group", function () {
            var amount = $('option:selected', this).data('disamount');
            var type = $('option:selected', this).data('type');
            var percentage = $('option:selected', this).data('percentage');
            let balance_amount=0;
            if (type == null || type == "fix") {

             balance_amount = (parseFloat(fee_amount) - parseFloat(amount)).toFixed(2);
            }else if (type == "percentage") {
             var per_amount=((parseFloat(fee_type_amount) * parseFloat(percentage))/100).toFixed(2);
             balance_amount = (parseFloat(fee_amount)-per_amount).toFixed(2);
            }

            if (typeof amount !== typeof undefined && amount !== false) {
                $('div#myFeesModal').find('input#amount_discount').prop('readonly', true).val((type == "percentage") ? per_amount : amount);
                $('div#myFeesModal').find('input#amount').val(balance_amount);

            } else {
                $('div#myFeesModal').find('input#amount').val(fee_amount);
                $('div#myFeesModal').find('input#amount_discount').prop('readonly', false).val(0);
            }
        });
    });

    $("#collect_fee_group").submit(function (e) {
        var form = $(this);
        var url = form.attr('action');
        var smt_btn = $(this).find("button[type=submit]");
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'JSON',
            data: form.serialize(), // serializes the form's elements.
            beforeSend: function () {
                smt_btn.button('loading');
            },
            success: function (response) {
                if (response.status === 1) {

                    location.reload(true);
                } else if (response.status === 0) {
                    $.each(response.error, function (index, value) {
                        var errorDiv = '#form_collection_' + index + '_error';
                        $(errorDiv).empty().append(value);
                    });
                }
            },
            error: function (xhr) { // if error occured

                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

            },
            complete: function () {
                smt_btn.button('reset');
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });


$(document).on('change','#select_all',function(){
  console.log("sdfsfs");
        $('input:checkbox').not(this).prop('checked', this.checked);
});
</script>