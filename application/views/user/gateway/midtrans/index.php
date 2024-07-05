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
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/style-main.css"> 
        <style type="text/css">
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
        <script type="text/javascript"
                src="https://app.midtrans.com/snap/snap.js"
        data-client-key="SB-Mid-client-2uDtZD3V5ZA_pNYW"></script> 
       <script src="<?php echo base_url(); ?>backend/custom/jquery.min.js"></script>
    </head>
    <body style="background: #ededed;">
        <div class="container">
            <form id="payment-form" method="post" action="<?= site_url() ?>/admin/admin/response">
                <input type="hidden" name="result_type" id="result-type" value=""></div>
                <input type="hidden" name="result_data" id="result-data" value=""></div>
            </form>
            <div class="row">
                <div class="paddtop20">
                    <div class="col-md-8 col-md-offset-2 text-center">
                        <img src="<?php echo base_url('uploads/school_content/logo/' . $setting[0]['image']); ?>">
                    </div>
                    <?php echo validation_errors(); ?>
                    <div class="col-md-6 col-md-offset-3 mt20">
                        <div class="paymentbg">
                            <div class="invtext"><?php echo $this->lang->line('fees_payment_details'); ?> </div>
                            <br>
                            <?php if ($api_error) {
                                ?>
                                <div class="alert alert-danger"><?php print_r($api_error); ?> </div>
                            <?php }
                            ?>
                            <div class="padd2 paddtzero">
                                <form action="<?php echo base_url(); ?>user/gateway/midtrans/midtrans_pay" method="post">
                                    <table class="table2" width="100%">
                                        <tr>
                                            <th><?php echo $this->lang->line('description'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('amount') ?></th>
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
                                        <td colspan="2" class="text-right"><?php echo $this->lang->line('total');?>: <?php echo $setting[0]['currency_symbol'] . amountFormat((float)($params['fine_amount_balance']+$params['total']), 2, '.', ''); ?></td>
                                    </tr>
                                        <hr>
                                        <tr class="bordertoplightgray">
                                            <td  bgcolor="#fff"><button type="button" onclick="window.history.go(-1); return false;" name="search"  value="" class="btn btn-info"><i class="fa fa fa-chevron-left"></i> <?php echo $this->lang->line('back'); ?> </button>  </td>
                                            <td  bgcolor="#fff" class="text-right"> <button type="button"  name="search" id="pay-button"  value="" class="btn btn-info"><?php echo $this->lang->line('pay_with_midtrans'); ?>  <i class="fa fa fa-chevron-right"></i></button>  </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>  
    </body>
    
    <script type="text/javascript">
        var resultType = document.getElementById('result-type');
        var resultData = document.getElementById('result-data');

        function changeResult(type, data) {
            $("#result-type").val(type);
            $("#result-data").val(JSON.stringify(data));
        }
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            snap.pay('<?php echo $snap_Token; ?>', {// store your snap token here
                onSuccess: function (result) {
                    changeResult('success', result);
                     document.getElementById("pay-button").disabled = true;
                    $.ajax({
                        url: '<?php echo base_url(); ?>user/gateway/midtrans/success',
                        type: 'POST',
                        data: $('#payment-form').serialize(),
                        dataType: "json",
                        success: function (msg) {
                            window.location.href = "<?php echo base_url(); ?>user/gateway/payment/successinvoice/" + msg.invoice_id + "/" + msg.sub_invoice_id;
                        } 
                    });
                },
                onPending: function (result) {
                    console.log(result);
                },
                onError: function (result) {
                    console.log('error');
                    console.log(result);
                },
                onClose: function () {
                    console.log('customer closed the popup without finishing the payment');
                }
            })
        });
    </script>
</html>