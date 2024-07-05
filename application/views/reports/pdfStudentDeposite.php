<?php
if (isset($student)) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
            <title>  <th><?php echo $this->lang->line('example'); ?></th> 2</title>
            <link rel="stylesheet" href="style.css" media="all" />
            <style type="text/css">
                @font-face {
                    font-family: SourceSansPro;
                    src:url(<?php echo base_url('backend/report/SourceSansPro-Regular.ttf'); ?>);
                    /*//src: url(SourceSansPro-Regular.ttf);*/
                }

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
                    font-family: Arial, sans-serif;
                    font-size: 14px;
                    font-family: SourceSansPro;
                }

                header {
                    padding: 10px 0;
                    margin-bottom: 20px;
                    border-bottom: 1px solid #AAAAAA;
                }

                #logo {
                    float: left;
                    margin-top: 8px;
                }

                #logo img {
                    height: 70px;
                }

                #company {
                    float: right;
                    text-align: right;
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
                <div id="details" class="clearfix">                   
                    <table>
                        <tr>
                            <td style="background: #ffffff;padding-left: 6px;
                                border-left: 6px solid #0087C3;
                                float: left;text-align: left;
                                ">
                                <h2 style="font-size: 1.4em;
                                    font-weight: normal;
                                    margin: 0;"><?php echo $student['firstname'] . " " . $student['lastname']; ?></h2>
                                <div class="address">  <th><?php echo $this->lang->line('class'); ?></th>: <?php echo $student['class'] . "(" . $student['section'] . ")" ?> </div>
                                <div class="address"><i class="fa fa-search"></i><?php echo $student['guardian_phone']; ?></div>
                                <div class="address"><?php echo $student['current_address'] . ", " . $student['city'] ?></div>
                                <div class="email"><a href="mailto:john@example.com"><?php echo $student['email']; ?></a></div>
                            </td>
                            <td style=" background: #ffffff;    float: right;
                                text-align: right;" valign="top">
                                <div class="date">  <th><?php echo $this->lang->line('date'); ?></th>: <?php echo date($this->customlib->getSchoolDateFormat()) ?></div>
                            </td>
                        </tr>
                    </table>
                </div>
                <table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th class="no">#</th>
                            <th><?php echo $this->lang->line('invoice_no'); ?></th>
                            <th><?php echo $this->lang->line('date'); ?></th>
                            <th><?php echo $this->lang->line('category'); ?></th>
                            <th><?php echo $this->lang->line('type'); ?></th>
                            <th><?php echo $this->lang->line('status'); ?></th>
                            <th><?php echo $this->lang->line('amount'); ?></th>
                            <th><?php echo $this->lang->line('fine'); ?></th>
                            <th><?php echo $this->lang->line('discount'); ?></th>
                            <th><?php echo $this->lang->line('total'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $target_amount = "0";
                        $deposite_amount = "0";
                        $fine = "0";
                        $discount = "0";
                        $row_count = 1;
                        foreach ($student_fee_history as $key => $fee) {
                            $target_amount = $target_amount + $fee['amount'];
                            $cls = "";
                            $total_row = "xxx";
                            $payment_status = "<span class='label label-success'><?php echo $this->lang->line('paid'); ?></span>";
                            if ($fee['date'] == "xxx") {
                                $cls = "text-red";
                                $payment_status = '<span class="label label-danger">Pending</span>';
                            } else {
                                $deposite_amount = $deposite_amount + $fee['amount'];
                                $total_row = number_format(($fee['amount'] + $fee['amount_discount']) - $fee['amount_fine'], 2, '.', '');
                                $target_amount = $target_amount + $total_row;
                                $fine = $fine + $fee['amount_fine'];
                                $discount = $discount + $fee['amount_discount'];
                            }
                            ?>
                            <tr>
                                <td class="no" ><?php echo $row_count; ?></td>
                                <td class="desc"><h3><?php echo $fee['invoiceno']; ?></h3></td>
                                <td class="qty"><?php echo $fee['date']; ?></td>
                                <td class="qty"><?php echo $fee['category']; ?></td>
                                <td class="qty"><?php echo $fee['type']; ?></td>
                                <td class="qty"><?php echo $payment_status; ?></td>
                                <td class="unit"><?php echo $fee['amount']; ?></td>
                                <td class="qty"><?php echo $fee['amount_discount']; ?></td>
                                <td class="qty"><?php echo $fee['amount_fine']; ?></td>
                                <td class="qty"><?php echo $total_row; ?></td>
                            </tr>
                            <?php
                            $row_count++;
                        }
                        ?>
                        <tr>
                            <td colspan="8" style="background: none;"></td>
                            <td colspan="1" style=" padding: 10px 20px;
                                background: #FFFFFF;
                                border-bottom: none;
                                font-size: 1.2em;
                                white-space: nowrap;
                                border-top: 1px solid #AAAAAA; "><b>  <th><?php echo $this->lang->line('basic_amount'); ?></th>:</b></td>
                            <td  style=" padding: 10px 20px;
                                 background: #FFFFFF;
                                 border-bottom: none;
                                 font-size: 1.2em;
                                 white-space: nowrap;
                                 border-top: 1px solid #AAAAAA; "><b><?php echo number_format($deposite_amount, 2, '.', ','); ?></b></td>
                        </tr>
                        <tr>
                            <td colspan="8" style="background: none;"></td>
                            <td colspan="1"  style=" padding: 10px 20px;
                                background: #FFFFFF;
                                border-bottom: none;
                                font-size: 1.2em;
                                white-space: nowrap;
                                border-top: 1px solid #AAAAAA; "><b>  <th><?php echo $this->lang->line('fine'); ?></th>:</b></td>
                            <td colspan="1"  style=" padding: 10px 20px;
                                background: #FFFFFF;
                                border-bottom: none;
                                font-size: 1.2em;
                                white-space: nowrap;
                                border-top: 1px solid #AAAAAA; "><b><?php echo number_format($fine, 2, '.', ','); ?></b></td>
                        </tr>
                        <tr>
                        <tr>
                            <td colspan="8" style="background: none;"></td>
                            <td colspan="1"  style=" padding: 10px 20px;
                                background: #FFFFFF;
                                border-bottom: none;
                                font-size: 1.2em;
                                white-space: nowrap;
                                border-top: 1px solid #AAAAAA; "><b>  <th><?php echo $this->lang->line('discount'); ?></th>:</b></td>
                            <td colspan="1"  style=" padding: 10px 20px;
                                background: #FFFFFF;
                                border-bottom: none;
                                font-size: 1.2em;
                                white-space: nowrap;
                                border-top: 1px solid #AAAAAA; "><b><?php echo number_format($discount, 2, '.', ','); ?></b></td>
                        </tr>
                        <tr>
                            <td colspan="8" style="background: none;"></td>
                            <td colspan="1"  style=" padding: 10px 20px;
                                background: #FFFFFF;
                                border-bottom: none;
                                font-size: 1.2em;
                                white-space: nowrap;
                                border-top: 1px solid #AAAAAA; "><b>  <th><?php echo $this->lang->line('total'); ?></th>:</b></td>
                            <td colspan="1"  style=" padding: 10px 20px;
                                background: #FFFFFF;
                                border-bottom: none;
                                font-size: 1.2em;
                                white-space: nowrap;
                                border-top: 1px solid #AAAAAA; "><b><?php echo number_format($target_amount, 2, '.', ','); ?></b></td>
                        </tr>
                    </tbody>
                </table>
            </main>
            <footer>               
            </footer>
        </body>
    </html>
    <?php
} else {
    
}
?>