<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<style type="text/css">
    .page-break { display: block; page-break-before: always; }
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
         
            <h3 class=" "><center><?php echo $this->lang->line('balance_fees_statement'); ?></center></h3>
         
        <div class="container"> 
            
        <?php
                foreach ($student_due_fee as $student_key => $student_value) {
        ?>
            <div class="row">
                <div id="content" class="col-lg-12 col-sm-12 ">
                    <div class="invoice">                    
                        <div class="row">                           
                            <div class="col-xs-6 text-left">
                                <br/>
                                <address>
                                    <strong><?php                                   
                                    echo $this->customlib->getFullName($student_value['firstname'],$student_value['middlename'],$student_value['lastname'],$sch_setting->middlename,$sch_setting->lastname);
                                      ?></strong><?php echo " (".$student_value['admission_no'].")"; ?><br>

                                    <?php echo $this->lang->line('father_name'); ?>: <?php echo $student_value['father_name']; ?><br>
                                    <?php echo $this->lang->line('class'); ?>: <?php echo $student_value['class'] . " (" . $student_value['section'] . ")"; ?>
                                </address>
                            </div>
                            <div class="col-xs-6 text-right">
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
                            if (!empty($student_value)) {
                                ?>
                                <table class="table table-striped table-responsive" style="font-size: 8pt;">
                                                    <thead class="header">
                                                        <tr>                 
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
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $total_amount = 0;
                                                        $total_deposite_amount = 0;
                                                        $total_discount_amount = 0;
                                                        $total_fine_amount = 0;
                                                        $total_fees_fine_amount = 0;
                                                        $total_balance_amount = 0;

                                                        foreach ($student_value['fees_list'] as $fee_key => $fee_value) {
                                                            if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != NULL) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {

                                                                $total_fees_fine_amount+=$fee_value->fine_amount;
                                                            }
                                                            //======================
                                                            $fee_paid = 0;
                                                            $fee_discount = 0;
                                                            $fee_fine = 0;
                                                            $fees_fine_amount = 0;
                                                            if (!empty($fee_value->amount_detail)) {
                                                                $fee_deposits = json_decode(($fee_value->amount_detail));

                                                                foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                                    $fee_paid += $fee_deposits_value->amount;
                                                                    $fee_discount += $fee_deposits_value->amount_discount;
                                                                    $fee_fine += $fee_deposits_value->amount_fine;
                                                                }
                                                            }
                                                            $feetype_balance = $fee_value->amount - ($fee_paid + $fee_discount);
                                                            $total_amount+=$fee_value->amount;
                                                            $total_discount_amount +=$fee_discount;
                                                            $total_fine_amount +=$fee_fine;
                                                            $total_deposite_amount+=$fee_paid;
                                                            $total_balance_amount+=$feetype_balance;

                                                            //===============================
                                                            ?>
                                                            <tr class="dark-gray">
                                                                <td align="left">
                                                                    <?php
                                                               

 echo ($fee_value->is_system) ? $this->lang->line($fee_value->fee_group_name) . ' (' . $this->lang->line($fee_value->type) . ')' :$fee_value->fee_group_name . ' (' . $fee_value->type. ')';


                                                                    ?>                                                                      
                                                                    </td>
                                                                <td align="left">
                                                                    <?php 


 echo ($fee_value->is_system) ? $this->lang->line($fee_value->code)  : $fee_value->code;

                                                             

                                                                 ?></td>
                                                                <td align="left" class="text text-left">

                                                                    <?php
                                                                    if ($fee_value->due_date == "0000-00-00") {
                                                                        
                                                                    } else {

                                                                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_value->due_date));
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td align="left" class="text text-left width85">
                                                                    <?php
                                                                    if ($feetype_balance == 0) {
                                                                        ?><?php echo $this->lang->line('paid'); ?><?php
                                                                    } else if (!empty($fee_value->amount_detail)) {
                                                                        ?><?php echo $this->lang->line('partial'); ?><?php
                                                                    } else {
                                                                        ?><?php echo $this->lang->line('unpaid'); ?><?php
                                                                        }
                                                                        ?>
                                                                </td>
                                                                <td class="text text-right">
                                                                    <?php
                                                                    echo amountFormat($fee_value->amount);
                                                                    if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != NULL) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {
                                                                        ?>
                                                                        <span class="text text-danger"><?php echo " + " . (amountFormat($fee_value->fine_amount)); ?></span>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td class="text text-left"></td>
                                                                <td class="text text-left"></td>
                                                                <td class="text text-left"></td>
                                                                <td class="text text-right"><?php
                                                                    echo (amountFormat($fee_discount));
                                                                    ?></td>
                                                                <td class="text text-right"><?php
                                                                    echo (amountFormat($fee_fine));
                                                                    ?></td>
                                                                <td class="text text-right"><?php
                                                                    echo (amountFormat($fee_paid));
                                                                    ?></td>
                                                                <td class="text text-right"><?php
                                                                    $display_none = "ss-none";
                                                                    if ($feetype_balance > 0) {
                                                                        $display_none = "";

                                                                        echo (amountFormat($feetype_balance));
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr> 
                                                            
                                                            <?php
                                                        }
//=================================
                                                        ?>
                                                         <?php

if (!empty($student_value['transport_fees'])) {
    foreach ($student_value['transport_fees'] as $transport_fee_key => $transport_fee_value) {

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
                
                                                <td align="left" class="text-rtl-right"><?php echo $this->lang->line('transport_fees'); ?></td>
                                                <td align="left" class="text-rtl-right"><?php echo $transport_fee_value->month; ?></td>
                                                <td align="left" class="text text-left">
<?php echo $this->customlib->dateformat($transport_fee_value->due_date); ?>                                             </td>
                                                     <td align="left" class="text text-left width85">
                                                    <?php
if ($feetype_balance == 0) {
            ?><span class=""><?php echo $this->lang->line('paid'); ?></span><?php
} else if (!empty($transport_fee_value->amount_detail)) {
            ?><span class=""><?php echo $this->lang->line('partial'); ?></span><?php
} else {
            ?><span class=""><?php echo $this->lang->line('unpaid'); ?></span><?php
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
                                                       
                                                    </tr>
                                                    <?php
}
        }
        ?>
        <?php
}
}

?>
                                                        <tr class="box box-solid total-bg">

                                                            <td align="left" ></td>
                                                            <td align="left" ></td>
                                                            <td align="left" ></td>
                                                            <td align="left" class="text text-left" ><?php echo $this->lang->line('grand_total'); ?></td>
                                                            <td class="text text-right">
                                                                <?php
                                                                echo $currency_symbol . amountFormat($total_amount) . "<span class='text text-danger'>+" . amountFormat($total_fees_fine_amount) . "</span>";
                                                                ?>

                                                            </td>
                                                            <td class="text text-left"></td>
                                                            <td class="text text-left"></td>
                                                            <td class="text text-left"></td>
                                                            <td class="text text-right"><?php
                                                                echo ($currency_symbol . amountFormat($total_discount_amount));
                                                                ?></td>
                                                            <td class="text text-right"><?php
                                                                echo ($currency_symbol . amountFormat($total_fine_amount));
                                                                ?></td>
                                                            <td class="text text-right"><?php
                                                                echo ($currency_symbol . amountFormat($total_deposite_amount));
                                                                ?></td>
                                                            <td class="text text-right"><?php
                                                                echo ($currency_symbol . amountFormat($total_balance_amount));
                                                                ?></td>
                                                        </tr>
                                                        <?php
//=================================
                                                        ?>

                                                    </tbody>
                                                </table>
                               
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>             
            </div>
											<?php                                            
										}
											?>           
            
        </div>
        <div class="clearfix"></div>     
    </body>
</html>