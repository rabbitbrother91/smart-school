<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat();?>
<style type="text/css">
    @media print {
        .page-break { display: block; page-break-before: always; }
    }
    @media print {
        .page-break { display: block; page-break-before: always; }
        .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
            float: left;
        }
        .col-sm-12 {
            width: 100%;
        }
        .col-sm-11 {
            width: 91.66666667%;
        }
        .col-sm-10 {
            width: 83.33333333%;
        }
        .col-sm-9 {
            width: 75%;
        }
        .col-sm-8 {
            width: 66.66666667%;
        }
        .col-sm-7 {
            width: 58.33333333%;
        }
        .col-sm-6 {
            width: 50%;
        }
        .col-sm-5 {
            width: 41.66666667%;
        }
        .col-sm-4 {
            width: 33.33333333%;
        }
        .col-sm-3 {
            width: 25%;
        }
        .col-sm-2 {
            width: 16.66666667%;
        }
        .col-sm-1 {
            width: 8.33333333%;
        }
        .col-sm-pull-12 {
            right: 100%;
        }
        .col-sm-pull-11 {
            right: 91.66666667%;
        }
        .col-sm-pull-10 {
            right: 83.33333333%;
        }
        .col-sm-pull-9 {
            right: 75%;
        }
        .col-sm-pull-8 {
            right: 66.66666667%;
        }
        .col-sm-pull-7 {
            right: 58.33333333%;
        }
        .col-sm-pull-6 {
            right: 50%;
        }
        .col-sm-pull-5 {
            right: 41.66666667%;
        }
        .col-sm-pull-4 {
            right: 33.33333333%;
        }
        .col-sm-pull-3 {
            right: 25%;
        }
        .col-sm-pull-2 {
            right: 16.66666667%;
        }
        .col-sm-pull-1 {
            right: 8.33333333%;
        }
        .col-sm-pull-0 {
            right: auto;
        }
        .col-sm-push-12 {
            left: 100%;
        }
        .col-sm-push-11 {
            left: 91.66666667%;
        }
        .col-sm-push-10 {
            left: 83.33333333%;
        }
        .col-sm-push-9 {
            left: 75%;
        }
        .col-sm-push-8 {
            left: 66.66666667%;
        }
        .col-sm-push-7 {
            left: 58.33333333%;
        }
        .col-sm-push-6 {
            left: 50%;
        }
        .col-sm-push-5 {
            left: 41.66666667%;
        }
        .col-sm-push-4 {
            left: 33.33333333%;
        }
        .col-sm-push-3 {
            left: 25%;
        }
        .col-sm-push-2 {
            left: 16.66666667%;
        }
        .col-sm-push-1 {
            left: 8.33333333%;
        }
        .col-sm-push-0 {
            left: auto;
        }
        .col-sm-offset-12 {
            margin-left: 100%;
        }
        .col-sm-offset-11 {
            margin-left: 91.66666667%;
        }
        .col-sm-offset-10 {
            margin-left: 83.33333333%;
        }
        .col-sm-offset-9 {
            margin-left: 75%;
        }
        .col-sm-offset-8 {
            margin-left: 66.66666667%;
        }
        .col-sm-offset-7 {
            margin-left: 58.33333333%;
        }
        .col-sm-offset-6 {
            margin-left: 50%;
        }
        .col-sm-offset-5 {
            margin-left: 41.66666667%;
        }
        .col-sm-offset-4 {
            margin-left: 33.33333333%;
        }
        .col-sm-offset-3 {
            margin-left: 25%;
        }
        .col-sm-offset-2 {
            margin-left: 16.66666667%;
        }
        .col-sm-offset-1 {
            margin-left: 8.33333333%;
        }
        .col-sm-offset-0 {
            margin-left: 0%;
        }
        .visible-xs {
            display: none !important;
        }
        .hidden-xs {
            display: block !important;
        }
        table.hidden-xs {
            display: table;
        }
        tr.hidden-xs {
            display: table-row !important;
        }
        th.hidden-xs,
        td.hidden-xs {
            display: table-cell !important;
        }
        .hidden-xs.hidden-print {
            display: none !important;
        }
        .hidden-sm {
            display: none !important;
        }
        .visible-sm {
            display: block !important;
        }
        table.visible-sm {
            display: table;
        }
        tr.visible-sm {
            display: table-row !important;
        }
        th.visible-sm,
        td.visible-sm {
            display: table-cell !important;
        }
    }
</style>

<html lang="en">
    <head>
        <title><?php echo $this->lang->line('fees_receipt'); ?></title>
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/AdminLTE.min.css">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div id="content" class="col-lg-12 col-sm-12 ">
                    <div class="invoice">
                        <div class="row header text-center">
                            <div class="col-sm-12">
                                <?php
?>

                                <img  src="<?php echo base_url(); ?>/uploads/print_headerfooter/student_receipt/<?php $this->setting_model->get_receiptheader();?>" style="height: 100px;width: 100%;">
                                <?php
?>
                            </div>
                            <?php
if ($sch_setting->is_duplicate_fees_invoice) {
    ?>
                                <div class="row">
                                    <div class="col-md-12 text text-center">
                                        <?php echo $this->lang->line('office_copy'); ?>
                                    </div>
                                </div>
                                <?php
}
?>

                            <div class="row">
                                <div class="col-xs-9 text-left">
                                    <br/>
                                    <address>
                                        <strong><?php

echo $this->customlib->getFullName($feearray[0]->firstname, $feearray[0]->middlename, $feearray[0]->lastname, $sch_setting->middlename, $sch_setting->lastname);

?></strong><br>

                                        <?php echo $this->lang->line('father_name'); ?>: <?php echo $feearray[0]->father_name; ?><br>
                                        <?php echo $this->lang->line('class'); ?>: <?php echo $feearray[0]->class . " (" . $feearray[0]->section . ")"; ?>
                                    </address>
                                </div>
                                <div class="col-xs-3 text-right">
                                    <br/>
                                    <address>
                                        <strong>Date: <?php
$date = date('d-m-Y');

echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($date));
?></strong><br/>

                                    </address>
                                </div>
                            </div>
                            <hr style="margin-top: 0px;margin-bottom: 0px;" />
                            <div class="row">

                                <?php
if (!empty($feearray)) {
    ?>

                                    <table class="table table-striped table-responsive" style="font-size: 8pt;">
                                        <thead>
                                        <th><?php echo $this->lang->line('fees_group'); ?></th>
                                        <th><?php echo $this->lang->line('fees_code'); ?></th>
                                        <th  class=""><?php echo $this->lang->line('due_date'); ?></th>
                                        <th class=""><?php echo $this->lang->line('status'); ?></th>
                                        <th  class="text text-right"><?php echo $this->lang->line('amount'); ?></th>
                                        <th  class="text text-center"><?php echo $this->lang->line('payment_id'); ?></th>
                                        <th  class="text text-center"><?php echo $this->lang->line('mode'); ?></th>
                                        <th  class=""><?php echo $this->lang->line('date'); ?></th>
                                        <th  class="text text-right"><?php echo $this->lang->line('paid'); ?></th>
                                        <th  class="text text-right"><?php echo $this->lang->line('fine'); ?></th>
                                        <th class="text text-right" ><?php echo $this->lang->line('discount'); ?></th>
                                        <th  class="text text-right"><?php echo $this->lang->line('balance'); ?></th>
                                        <th></th>
                                        </thead>
                                        <tbody>
                                            <?php
$total_amount          = 0;
    $total_deposite_amount = 0;
    $total_fine_amount     = 0;
    $total_discount_amount = 0;
    $total_balance_amount  = 0;
    $alot_fee_discount     = 0;
    if (empty($feearray)) {
        ?>
                                                <tr>
                                                    <td colspan="11" class="text-danger text-center">
                                                        <?php echo $this->lang->line('no_transaction_found'); ?>
                                                    </td>
                                                </tr>
                                                <?php
} else {

        foreach ($feearray as $fee_key => $feeList) {
            if ($feeList->is_system) {
                $feeList->amount = $feeList->student_fees_master_amount;
            }

            $fee_discount = 0;
            $fee_paid     = 0;
            $fee_fine     = 0;
            if (!empty($feeList->amount_detail)) {
                $fee_deposits = json_decode(($feeList->amount_detail));

                foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                    $fee_paid     = $fee_paid + $fee_deposits_value->amount;
                    $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                    $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
                }
            }
            $feetype_balance       = $feeList->amount - ($fee_paid + $fee_discount);
            $total_amount          = $total_amount + $feeList->amount;
            $total_discount_amount = $total_discount_amount + $fee_discount;
            $total_fine_amount     = $total_fine_amount + $fee_fine;
            $total_deposite_amount = $total_deposite_amount + $fee_paid;
            $total_balance_amount  = $total_balance_amount + $feetype_balance;
            ?>
                                                    <tr  class="dark-gray">
                                                        <td><?php
echo $feeList->name;
            ?></td>
                                                        <td><?php echo $feeList->code; ?></td>
                                                        <td class="">

                                                            <?php
if ($feeList->due_date == "0000-00-00") {

            } else {
                echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($feeList->due_date));
            }
            ?>
                                                        </td>
                                                        <td class="">
                                                            <?php
if ($feetype_balance == 0) {
                echo $this->lang->line('paid');
            } else if (!empty($feeList->amount_detail)) {
                ?><?php echo $this->lang->line('partial'); ?><?php
} else {
                echo $this->lang->line('unpaid');
            }
            ?>

                                                        </td>
                                                        <td class="text text-right"><?php echo $currency_symbol . $feeList->amount; ?></td>

                                                        <td colspan="3"></td>
                                                        <td class="text text-right"><?php
echo ($currency_symbol . number_format($fee_paid, 2, '.', ''));
            ?></td>
                                                        <td class="text text-right"><?php
echo ($currency_symbol . number_format($fee_fine, 2, '.', ''));
            ?></td>
                                                        <td class="text text-right"><?php
echo ($currency_symbol . number_format($fee_discount, 2, '.', ''));
            ?></td>
                                                        <td class="text text-right"><?php
$display_none = "ss-none";
            if ($feetype_balance > 0) {
                $display_none = "";

                echo ($currency_symbol . number_format($feetype_balance, 2, '.', ''));
            }
            ?>
                                                        </td>
                                                    </tr>

                                                    <?php
$fee_deposits = json_decode(($feeList->amount_detail));
            if (is_object($fee_deposits)) {
                foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                    ?>
                                                            <tr class="white-td">
                                                                <td colspan="5" class="text-right"><img src="<?php echo base_url(); ?>backend/images/table-arrow.png" alt="" /></td>
                                                                <td class="text text-center">
                                                                    <?php echo $feeList->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?>
                                                                </td>
                                                                <td class="text text-center"><?php echo $fee_deposits_value->payment_mode; ?></td>
                                                                <td class="text text-center">
                                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_deposits_value->date)); ?>
                                                                </td>
                                                                <td class="text text-right"><?php echo ($currency_symbol . number_format($fee_deposits_value->amount, 2, '.', '')); ?></td>
                                                                <td class="text text-right"><?php echo ($currency_symbol . number_format($fee_deposits_value->amount_fine, 2, '.', '')); ?></td>
                                                                <td class="text text-right"><?php echo ($currency_symbol . number_format($fee_deposits_value->amount_discount, 2, '.', '')); ?></td>
                                                                <td></td>

                                                            </tr>
                                                            <?php
}
            }
        }
    }
    ?>
                                            <tr class="success">
                                                <td align="left" ></td>
                                                <td align="left" ></td>
                                                <td align="left" ></td>
                                                <td align="left" class="text text-left" >
                                                    <b>    <?php echo $this->lang->line('grand_total'); ?></b>
                                                </td>
                                                <td class="text text-right">
                                                    <b>    <?php
echo ($currency_symbol . number_format($total_amount, 2, '.', ''));
    ?></b>
                                                </td>
                                                <td class="text text-left"></td>
                                                <td class="text text-left"></td>
                                                <td class="text text-left"></td>
                                                <td class="text text-right"> <b>  <?php
echo ($currency_symbol . number_format($total_deposite_amount, 2, '.', ''));
    ?></b></td>
                                                <td class="text text-right"> <b>  <?php
echo ($currency_symbol . number_format($total_fine_amount, 2, '.', ''));
    ?></b></td>
                                                <td class="text text-right"> <b>  <?php
echo ($currency_symbol . number_format($total_discount_amount, 2, '.', ''));
    ?></b></td>
                                                <td class="text text-right"> <b>  <?php
echo ($currency_symbol . number_format($total_balance_amount, 2, '.', ''));
    ?></b></td>  <td class="text text-right"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <?php
}
?>

                            </div>
                        </div>
                    </div>
                    <div class="row header ">
                        <div class="col-sm-12">
                            <?php $this->setting_model->get_receiptfooter();?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
