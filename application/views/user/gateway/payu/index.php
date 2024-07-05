<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#424242" />
        <title><?php echo $this->customlib->getAppName(); ?></title>
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css"> 
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css"> 
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/style-main.css"> <style type="text/css">
            .table2 tr.border_bottom td {
                box-shadow: none;
                border-radius: 0;
                border-bottom: 1px solid #e6e6e6;
            }
            .table2 td {
                padding-bottom: 3px;
                padding-top: 6px;
            }
            .title{
                color: #0084B4;
                font-weight: 600 !important;
                font-size: 15px !important;;
                display: inline;

            }
            .product-description {
                display: block;
                color: #999;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;
            }
            .text-fine{
                color: #bf4f4d;
            }
        </style>
    </head>
    <body style="background: #ededed;">
        <div class="container">
            <div class="row">
                <div class="paddtop20">
                    <div class="col-md-8 col-md-offset-2 text-center">
                        <img src="<?php echo base_url('uploads/school_content/logo/' . $setting[0]['image']); ?>">
                    </div>
                    <div class="col-md-6 col-md-offset-3 mt20">
                        <div class="paymentbg">
                            <div class="invtext"><?php echo $this->lang->line('fees_payment_details'); ?></div>
                            <div class="padd2 paddtzero">
                                <table class="table2" width="100%">
                                    <tr>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('amount'); ?> </th>
                                    </tr>
                                    <?php
                                    foreach ($student_fees_master_array as $fees_key => $fees_value) {
                                        ?>
                                        <tr>
                                             <td>
                                                <span class="title"><?php if ($fees_value['is_system']) {
                echo $this->lang->line($fees_value['fee_group_name']);
            } else {
                echo $fees_value['fee_group_name'] ;
            }?> </span>
                                                <span class="product-description">
                                                    <?php  if ($fees_value['is_system']) {
                echo $this->lang->line($fees_value['fee_type_code']);
            } else {
                echo $fees_value['fee_type_code'];
            } ?></span>
                                            </td>
                                            <td class="text-right"><?php echo $setting[0]['currency_symbol'] . amountFormat((float) $fees_value['amount_balance'], 2, '.', ''); ?></td>
                                        </tr>
                                        <tr class="border_bottom">
                                            <td> 
                                                <span class="text-fine"><?php echo $this->lang->line('fine'); ?></span></td>
                                            <td class="text-right"><?php echo $setting[0]['currency_symbol'] . amountFormat((float) $fees_value['fine_balance'], 2, '.', ''); ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <tr class="bordertoplightgray">
                                        <td colspan="2" class="text-right"><?php echo $this->lang->line('total'); ?>: <?php echo $setting[0]['currency_symbol'] . amountFormat((float) ($session_data['fine_amount_balance'] + $session_data['total']), 2, '.', ''); ?></td>
                                    </tr>
                                </table>
                                <?php
                                defined('BASEPATH') OR exit('No direct script access allowed');
                                ?>
                                <script src="<?php echo base_url(); ?>backend/custom/jquery.min.js"></script>
                                <div class="divider"></div>
                                <form class="paddtlrb" action="<?php echo $action; ?>/_payment" method="post" id="payuForm" name="payuForm">
                                    <input type="hidden" name="key" value="<?php echo $mkey ?>" />
                                    <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
                                    <input type="hidden" name="txnid" value="<?php echo $tid ?>" />
                                    <input class="form-control" type="hidden" name="amount" value="<?php echo  round(number_format((float) (convertBaseAmountCurrencyFormat($session_data['fine_amount_balance'] + $session_data['total'])), 2, '.', '')); ?>"  readonly/>
                                    <input class="form-control" type="hidden" name="firstname" id="firstname" value="<?php echo $session_data['name']; ?>" readonly/>
                                    <input class="form-control" type="hidden" name="email" id="email" value="<?php echo $session_data['email']; ?>" readonly/>
                                    <input class="form-control"  type="hidden" name="phone" value="<?php echo $session_data['guardian_phone']; ?>" readonly />
                                    <textarea class="form-control" style="display:none;"  name="productinfo" readonly><?php echo $productinfo ?></textarea>
                                    <input class="form-control" type="hidden" name="address1" value="<?php echo $address ?>" readonly/>
                                    <input name="surl" value="<?php echo $sucess ?>" size="64" type="hidden" />
                                    <input name="furl" value="<?php echo $failure ?>" size="64" type="hidden" />                           
                                    <input type="hidden" name="service_provider" value="payu_paisa" size="64" />
                                    <input name="curl" value="<?php echo $cancel ?> " type="hidden" />
                                    <button type="button" onclick="window.history.go(-1);
                                            return false;" name="search"  value="" class="btn btn-info"><i class="fa fa fa-chevron-left"></i> <?php echo $this->lang->line('back') ?></button>    
                                    <button type="button" class="btn cfees pull-right submit_button" ><i class="fa fa fa-money"></i> <?php echo $this->lang->line('pay_with_payu') ?></button></td>
                                </form>        
                            </div>
                        </div>
                    </div>                      
                </div>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function () {
                $(".submit_button").click(function (e) {
                    var url = "<?php echo site_url('user/gateway/payu/checkout') ?>";

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: $("#payuForm").serialize(),
                        dataType: "Json",
                        success: function (response)
                        {

                            if (response.status == "success") {
                                $('form#payuForm').submit();
                            } else if (response.status == "fail") {
                                $.each(response.error, function (index, value) {
                                    var errorDiv = '.' + index + '_error';
                                    $(errorDiv).empty().append(value);
                                });
                            }
                        }
                    });

                    e.preventDefault();
                });
            });
        </script>
    </body>
</html>          