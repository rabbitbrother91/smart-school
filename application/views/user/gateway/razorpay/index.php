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
    </head>
    <body style="background: #ededed;">
        <div class="container">
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
                                <form action="<?php echo base_url(); ?>user/gateway/paystack/paystack_pay" method="post">
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
                                            <td  bgcolor="#fff" class="text-right"> <button type="button" onclick="pay()" name="search"  value="" class="btn btn-info"><?php echo $this->lang->line('pay_with_razorpay'); ?> <i class="fa fa fa-chevron-right"></i></button>  </td>
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
</html>
 <?php 

 ?>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
     <script src="<?php echo base_url(); ?>backend/custom/jquery.min.js"></script>
<script>
                                                var SITEURL = "<?php echo base_url() ?>";
                                            
                                                function pay(e) {
                                                    var totalAmount = <?php echo $total; ?>;
                                                    var product_id = <?php echo $merchant_order_id; ?>;
                                                    var options = {
                                                        "key": "<?php echo $key_id; ?>",
                                                        "amount": "<?php echo $amount; ?>", // 2000 paise = INR 20
                                                        "name": "<?php echo $name; ?>",
                                                        "description": "<?php echo $title; ?>",
                                                        "currency": "<?php echo $currency_code; ?>",
                                                        "image": "<?php echo base_url(); ?>uploads/school_content/admin_small_logo/<?php $this->setting_model->getAdminsmalllogo(); ?>",
                                                        "order_id": '<?php echo $order_id; ?>',
                                                                    "handler": function (response) {

                                                                        $.ajax({
                                                                            url: SITEURL + 'user/gateway/razorpay/callback',
                                                                            type: 'post',
                                                                            dataType: 'json',
                                                                            data: {
                                                                                razorpay_payment_id: response.razorpay_payment_id, totalAmount: totalAmount, product_id: product_id,
                                                                            },
                                                                            success: function (msg) {

                                                                                window.location.assign(SITEURL + 'user/gateway/payment/successinvoice')
                                                                            }
                                                                        });

                                                                    },

                                                                    "theme": {
                                                                        "color": "#528FF0"
                                                                    }
                                                                };
                                                                console.log(options);
                                                                var rzp1 = new Razorpay(options);
                                                                rzp1.open();

                                                            }
                                                            ;

</script>