<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
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
                    <th align="left"><?php echo $this->lang->line('fees_group'); ?></th>
                    <th align="left"><?php echo $this->lang->line('fees_code'); ?></th>
                    <th align="left" class="text text-center"><?php echo $this->lang->line('due_date'); ?></th>
                    <th align="left" class="text text-left"><?php echo $this->lang->line('status'); ?></th>
                    <th class="text text-right"><?php echo $this->lang->line('amount') ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                    <th class="text text-left"><?php echo $this->lang->line('payment_id'); ?></th>
                    <th class="text text-left"><?php echo $this->lang->line('mode'); ?></th>
                    <th class="text text-left"><?php echo $this->lang->line('date'); ?></th>
                    <th class="text text-right"><?php echo $this->lang->line('discount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                    <th class="text text-right"><?php echo $this->lang->line('fine'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                    <th class="text text-right" width="8%"><?php echo $this->lang->line('paid'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                    <th class="text text-right"><?php echo $this->lang->line('balance'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
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

                foreach ($student_due_fee as $key => $fee) {

                    foreach ($fee->fees as $fee_key => $fee_value) {

                        $fee_paid          = 0;
                        $fee_discount      = 0;
                        $fee_fine          = 0;
                        $alot_fee_discount = 0;

                        if (!empty($fee_value->amount_detail)) {
                            $fee_deposits_value = json_decode(($fee_value->amount_detail), true);

                            $fee_paid     = $fee_paid + $fee_deposits_value['amount'];
                            $fee_discount = $fee_discount + $fee_deposits_value['amount_discount'];
                            $fee_fine     = $fee_fine + $fee_deposits_value['amount_fine'];
                        }

                        $total_fees_fine_amount = $total_fees_fine_amount;
                        $total_amount           = $total_amount + $fee_value->amount;
                        $total_discount_amount  = $total_discount_amount + $fee_discount;
                        $total_deposite_amount  = $total_deposite_amount + $fee_paid;
                        $total_fine_amount      = $total_fine_amount + $fee_fine;
                        $feetype_balance        = $fee_value->amount - ($fee_paid + $fee_discount);
                        $total_balance_amount   = $total_balance_amount + $feetype_balance;
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

                            <td align="left"><?php
                                                if ($fee_value->is_system) {
                                                    echo $this->lang->line($fee_value->name) . " (" . $this->lang->line($fee_value->type) . ")";
                                                } else {
                                                    echo $fee_value->name . " (" . $fee_value->type . ")";
                                                }
                                                ?></td>
                            <td align="left"><?php
                                                if ($fee_value->is_system) {
                                                    echo $this->lang->line($fee_value->code);
                                                } else {
                                                    echo $fee_value->code;
                                                }

                                                ?></td>
                            <td align="left" class="text text-center">

                                <?php
                                if ($fee_value->due_date) {
                                    echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_value->due_date));
                                } else {
                                }
                                ?>
                            </td>
                            <td align="left" class="text text-left">
                                <span class="label label-danger"><?php echo $this->lang->line('processing'); ?></span>
                            </td>
                            <td class="text text-right"><?php echo amountFormat($fee_value->amount);
                                                        if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {
                                                        ?>
                                    <span data-toggle="popover" class="text text-danger detail_popover"><?php echo " + " . (amountFormat($fee_value->fine_amount)); ?></span>

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
                            </tr>
                            <?php
                            if (!empty($fee_value->amount_detail)) {

                                $fee_deposits = json_decode(($fee_value->amount_detail));

                            ?>
                                <tr class="white-td">
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td class="text-right"><img src="<?php echo base_url(); ?>backend/images/table-arrow.png" alt="" /></td>
                                    <td class="text text-left">

                                        <a href="#" data-toggle="popover" class="detail_popover"> <?php echo $fee_value->unique_id; ?></a>
                                        <div class="fee_detail_popover" style="display: block">
                                            <?php
                                            if ($fee_deposits->description == "") {
                                            ?>
                                                <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                            <?php
                                            } else {
                                            ?>
                                                <p class="text text-info"><?php echo $fee_deposits->description; ?> </p>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td class="text text-left"><?php echo $this->lang->line(strtolower($fee_deposits->payment_mode)); ?></td>
                                    <td class="text text-left">
                                        <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_deposits->date)); ?>
                                    </td>
                                    <td class="text text-right"><?php echo (amountFormat($fee_deposits->amount_discount, 2, '.', '')); ?></td>
                                    <td class="text text-right"><?php echo (amountFormat($fee_deposits->amount_fine, 2, '.', '')); ?></td>
                                    <td class="text text-right"><?php echo (amountFormat($fee_deposits->amount, 2, '.', '')); ?></td>
                                    <td></td>
                                </tr>
                            <?php
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
                                $trans_fee_deposits = json_decode(($transport_fee_value->amount_detail));
                                $fee_paid     = $fee_paid + $trans_fee_deposits->amount;
                                $fee_discount = $fee_discount + $trans_fee_deposits->amount_discount;
                                $fee_fine     = $fee_fine + $trans_fee_deposits->amount_fine;
                           
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
                <?php echo $this->customlib->dateformat($transport_fee_value->due_date); ?>                                       
                  </td>
                                                                   
                                                                     <td align="left" class="text text-left">
                                <span class="label label-danger"><?php echo $this->lang->line('processing'); ?></span>
                            </td>
                                                                </td>
                            <td class="text text-right">
                                <?php
                
                        echo amountFormat($transport_fee_value->fees);
                
                        if (($transport_fee_value->due_date != "0000-00-00" && $transport_fee_value->due_date != null) && (strtotime($transport_fee_value->due_date) < strtotime(date('Y-m-d')))) {
                            $tr_fine_amount = $transport_fee_value->fine_amount;
                            if ($transport_fee_value->fine_type != "" && $transport_fee_value->fine_type == "percentage") {
                
                                $tr_fine_amount = percentageAmount($transport_fee_value->fees, $transport_fee_value->fine_percentage);
                            }
                         
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
                
                            $trans_fee_deposits = json_decode(($transport_fee_value->amount_detail));
                
                           
                                ?>
                                                                         <tr class="white-td">
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td align="left"></td>
                                    <td class="text-right"><img src="<?php echo base_url(); ?>backend/images/table-arrow.png" alt="" /></td>
                                    <td class="text text-left">

                                    <a href="#" data-toggle="popover" class="detail_popover"> <?php echo $fee_value->unique_id; ?></a>
                                        <div class="fee_detail_popover" style="display: block">
                                            <?php
                                            if ($trans_fee_deposits->description == "") {
                                            ?>
                                                <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                            <?php
                                            } else {
                                            ?>
                                                <p class="text text-info"><?php echo $trans_fee_deposits->description; ?> </p>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td class="text text-left"><?php echo $this->lang->line(strtolower($trans_fee_deposits->payment_mode)); ?></td>
                                    <td class="text text-left">
                                        <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($trans_fee_deposits->date)); ?>
                                    </td>
                                    <td class="text text-right"><?php echo (amountFormat($trans_fee_deposits->amount_discount, 2, '.', '')); ?></td>
                                    <td class="text text-right"><?php echo (amountFormat($trans_fee_deposits->amount_fine, 2, '.', '')); ?></td>
                                    <td class="text text-right"><?php echo (amountFormat($trans_fee_deposits->amount, 2, '.', '')); ?></td>
                                    <td></td>
                                </tr>
                                                                    <?php
            
                        }
                        ?>
                
                <?php
                }
                }
                    ?>

                    <tr class="box box-solid total-bg">
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left"></td>
                        <td align="left" class="text text-left"><?php echo $this->lang->line('grand_total'); ?></td>
                        <td class="text text-right"><?php
                                                    echo $currency_symbol . amountFormat($total_amount, 2, '.', '');
                                                    ?></td>
                        <td class="text text-left"></td>
                        <td class="text text-left"></td>
                        <td class="text text-left"></td>
                        <td class="text text-right"><?php
                                                    echo ($currency_symbol . amountFormat($total_discount_amount, 2, '.', ''));
                                                    ?></td>
                        <td class="text text-right"><?php
                                                    echo ($currency_symbol . amountFormat($total_fine_amount, 2, '.', ''));
                                                    ?></td>
                        <td class="text text-right"><?php
                                                    echo ($currency_symbol . amountFormat($total_deposite_amount, 2, '.', ''));
                                                    ?></td>
                        <td class="text text-right"><?php
                                                    echo ($currency_symbol . amountFormat($total_balance_amount, 2, '.', ''));
                                                    ?></td>
                    </tr>
            </tbody>
        </table>
    <?php } ?>
</div>
<!-- /.box-body -->
<script>
    $('.detail_popover').popover({
        placement: 'right',
        title: '',
        trigger: 'hover',
        container: 'body',
        html: true,
        content: function() {
            return $(this).closest('td').find('.fee_detail_popover').html();
        }
    });
</script>