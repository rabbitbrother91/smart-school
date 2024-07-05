<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
        <title>Example 2</title>
        <link rel="stylesheet" href="style.css" media="all" />
        <style type="text/css">
            .clearfix:after {
                content: "";
                display: table;
                clear: both;
            }

            a {
                color: #0087C3;
                text-decoration: none;
            }

            body {
                position: relative;
                width: 90%;
                height: 29.7cm;
                margin: 0 auto;
                color: #555555;
                background: #FFFFFF;

                font-size: 14px;
                font-family: SourceSansPro;
            }

            header {
                padding: 10px 0;
                margin-bottom: 20px;
                border-bottom: 1px solid #AAAAAA;
            }

            #details {
                margin-bottom: 50px;
            }

            #client {
                padding-left: 6px;
                border-left: 6px solid #0087C3;
                float: left;
            }

            #client .to {
                color: #777777;
            }

            h2.name {
                font-size: 1.4em;
                font-weight: normal;
                margin: 0;
            }

            #invoice {
                float: right;
                text-align: right;
            }

            #invoice h1 {
                color: #0087C3;
                font-size: 2.4em;
                line-height: 1em;
                font-weight: normal;
                margin: 0  0 10px 0;
            }

            #invoice .date {
                font-size: 1.1em;
                color: #777777;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0;
                margin-bottom: 20px;
            }

            table th{
                padding: 20px;
                background: #EEEEEE;
                text-align: center;
                border-bottom: 1px solid #FFFFFF;
            }

            table td {
                padding: 10px;
                background: #EEEEEE;
                text-align: center;
                border-bottom: 1px solid #FFFFFF;
            }

            table th {
                white-space: nowrap;
                font-weight: normal;
            }

            table td {
                text-align: right;
            }

            table td h3{
                color: #0096D3;
                font-size: 1.2em;
                font-weight: normal;
                margin: 0 0 0.2em 0;
            }

            table .no {
                color: #555555;
                font-size: 1.2em;
            }

            table .desc {
                text-align: center;
            }

            table .unit {
                background: #DDDDDD;
            }
            table .unit {
                background: #DDDDDD;
            }

            table .qty {
            }

            table .total {
                color: #555555;
            }

            table td.unit,
            table td.qty,
            table td.total {
                font-size: 1.2em;
            }

            table tbody tr:last-child td {
                border: none;
            }

            table tfoot td {
                padding: 10px 20px;
                background: #FFFFFF;
                border-bottom: none;
                font-size: 1.2em;
                white-space: nowrap;
                border-top: 1px solid #AAAAAA;
            }

            table tfoot tr:first-child td {
                border-top: none;
            }

            table tfoot tr:last-child td {
                color: #57B223;
                font-size: 1.4em;
                border-top: 1px solid #57B223;
            }

            table tfoot tr td:first-child {
                border: none;
            }

            #thanks{
                font-size: 2em;
                margin-bottom: 50px;
            }

            #notices{
                padding-left: 6px;
                border-left: 6px solid #0087C3;
            }

            #notices .notice {
                font-size: 1.2em;
            }

            footer {
                color: #777777;
                width: 100%;
                height: 30px;
                position: absolute;
                bottom: 0;
                border-top: 1px solid #AAAAAA;
                padding: 8px 0;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <header class="clearfix">
            <table style="background: #ffffff !important;">
                <tr>
                    <td style="background: #ffffff !important;text-align: left; margin-top: 8px;">
                        <img style="height:70px; " src="<?php echo base_url(); ?>/images/<?php echo $settinglist[0]['image']; ?>">
                    </td>
                    <td style="background: #ffffff !important;float: right;
                        text-align: right;">
                        <h2 class="name"><?php echo $settinglist[0]['name']; ?></h2>
                        <div><?php echo $settinglist[0]['address']; ?></div>
                        <div><?php echo $settinglist[0]['phone']; ?></div>
                        <div><a href="mailto:company@example.com"><?php echo $settinglist[0]['email']; ?></a></div>
                    </td>
                </tr>
            </table>
        </header>
        <main>
            <div  style="text-align: right;">  <th><?php echo $this->lang->line('date'); ?></th>: <?php echo date($this->customlib->getSchoolDateFormat()); ?></div>
            <div id="details" class="clearfix">
                <h6 class="text text-center" style="text-align: center"><b><?php echo $exp_title; ?></b></h6>
                <h6 class="text text-left"><b>Fees Collection Details</b></h6><hr/>
                <table class="">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th><?php echo $this->lang->line('student_name'); ?></th>
                            <th><?php echo $this->lang->line('class'); ?></th>
                            <th><?php echo $this->lang->line('fee_type'); ?></th>
                            <th class="text text-right"><?php echo $this->lang->line('fees'); ?></th>
                            <th class="text text-right"><?php echo $this->lang->line('discount'); ?></th>
                            <th class="text text-right"><?php echo $this->lang->line('fine'); ?></th>
                            <th class="text text-right"><?php echo $this->lang->line('amount'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$amount    = 0;
$discount  = 0;
$fine      = 0;
$total     = 0;
$grd_total = 0;
if (empty($feeList)) {
    ?>
                            <tr>
                                <td colspan="8" class="text-danger text-center"><?php echo $this->lang->line('no_transaction_found'); ?></td>
                            </tr>
                            <?php
} else {
    $count = 1;
    foreach ($feeList as $key => $value) {
        $amount   = $amount + $value['amount'];
        $discount = $discount + $value['amount_discount'];
        $fine     = $fine + $value['amount_fine'];
        $total    = ($amount + $fine) - $discount;
        ?>
                                <tr>
                                    <td>
                                        <?php echo $value['id']; ?>
                                    </td>
                                    <td>
                                        <?php echo $value['firstname'] . " " . $value['lastname']; ?>
                                    </td>
                                    <td>
                                        <?php echo $value['class'] . " (" . $value['section'] . ")"; ?>
                                    </td>
                                    <td>
                                        <?php echo $value['type']; ?>
                                    </td>
                                    <td class="text text-right">
                                        <?php echo $currency_symbol . $value['amount']; ?>
                                    </td>
                                    <td class="text text-right">
                                        <?php echo $currency_symbol . $value['amount_discount']; ?>
                                    </td>
                                    <td class="text text-right">
                                        <?php echo $currency_symbol . $value['amount_fine']; ?>
                                    </td>
                                    <td class="text text-right">
                                        <?php
$t = ($value['amount'] + $value['amount_fine']) - $value['amount_discount'];
        echo $currency_symbol . number_format($t, 2, '.', '')
        ?>
                                    </td>
                                </tr>
                                <?php
$count++;
    }
}
?>
                        <tr ><th colspan="4" class="text-right"><?php echo $this->lang->line('grand_total'); ?> </th>
                            <th class="text text-right"><?php echo $currency_symbol . number_format($amount, 2, '.', ''); ?></th>
                            <th class="text text-right"><?php echo $currency_symbol . number_format($discount, 2, '.', ''); ?></th>
                            <th class="text text-right"><?php echo $currency_symbol . number_format($fine, 2, '.', ''); ?></th>
                            <th class="text text-right"><?php echo $currency_symbol . number_format($total, 2, '.', ''); ?></th>
                        </tr>
                    </tbody>
                </table>
                <h6 class="text text-left"><b><?php echo $this->lang->line('expense_detail'); ?></b></h6><hr/>
                <table class="">
                    <thead>
                        <tr>
                            <th><?php echo $this->lang->line('expense_id'); ?></th>
                            <th><?php echo $this->lang->line('date'); ?></th>
                            <th><?php echo $this->lang->line('expense_head'); ?></th>
                            <th><?php echo $this->lang->line('name'); ?></th>
                            <th class="text text-right"><?php echo $this->lang->line('amount'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$count       = 1;
$grand_total = 0;
if (empty($expenseList)) {
    ?>
                            <tr>
                                <td colspan="5" class="text-danger text-center"><?php echo $this->lang->line('no_transaction_found'); ?></td>
                            </tr>
                            <?php
} else {
    foreach ($expenseList as $key => $value) {
        $grand_total = $grand_total + $value['amount'];
        ?>
                                <tr>
                                    <td>
                                        <?php echo $value['id']; ?>
                                    </td>
                                    <td>
                                        <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['date'])); ?>
                                    </td>
                                    <td>
                                        <?php echo $value['exp_category']; ?>
                                    </td>
                                    <td>
                                        <?php echo $value['name']; ?>
                                    </td>
                                    <td class="text text-right">
                                        <?php echo $currency_symbol . $value['amount']; ?>
                                    </td>
                                </tr>
                                <?php
$count++;
    }
}
?>
                        <tr>
                            <th colspan="4" class="text-right"><?php echo $this->lang->line('grand_total'); ?></th>
                            <th class="text text-right">
                                <?php echo $currency_symbol . number_format($grand_total, 2, '.', ''); ?>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </body>
</html>