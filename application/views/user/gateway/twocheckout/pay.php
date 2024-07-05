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
                    
                    <div class="col-md-6 col-md-offset-3 mt20">
                        <div class="paymentbg">
                            <div class="invtext"><?php echo $this->lang->line('fees_payment_details'); ?> </div>
                            <br>
                          

                            <div class="padd2 paddtzero">
                                <form action="#" method="post">
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
                                                <span class="title"><?php echo $fees_value['fee_group_name'] ?></span>
                                                <span class="product-description">
                                                    <?php echo $fees_value['fee_type_code']; ?></span>
                                            </td>
                                            <td class="text-right"><?php echo $setting[0]['currency_symbol'] . number_format((float) $fees_value['amount_balance'], 2, '.', ''); ?></td>
                                        </tr>

                                        <tr class="border_bottom">
                                            <td> 
                                                <span class="text-fine"><?php echo $this->lang->line('fine'); ?></span></td>
                                            <td class="text-right"><?php echo $setting[0]['currency_symbol'] . number_format((float) $fees_value['fine_balance'], 2, '.', ''); ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <tr class="bordertoplightgray">
                                        <td colspan="2" class="text-right"><?php echo $this->lang->line('total'); ?>: <?php echo $setting[0]['currency_symbol'] . number_format((float) ($params['fine_amount_balance'] + $params['total']), 2, '.', ''); ?></td>
                                    </tr>
                                        <tr class="bordertoplightgray">
                                            <td  bgcolor="#fff"><?php echo $this->lang->line('phone') ?>:</td>
                                            <td  bgcolor="#fff" class="text-right"> <input type="text" readonly="" class="form-control"  name="phone" value="<?php echo set_value('phone', $params['guardian_phone']); ?>" /> <span class="text-danger"> <?php echo form_error('phone'); ?></span></td>
                                        </tr>
                                        <tr class="bordertoplightgray">
                                            <td  bgcolor="#fff"><?php echo $this->lang->line('email'); ?>:</td>
                                            <td  bgcolor="#fff" class="text-right"> <input type="text" readonly="" class="form-control" name="email" value="<?php echo set_value('email', $student_data['guardian_email']); ?>"> <span class="text-danger"> <?php echo form_error('email'); ?></span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">  <?php if ($api_error) {
                                ?> 
                                <div class="alert alert-danger"><?php
                                    echo "<pre>"; print_r($api_error);
                                    ?> </div>
                            <?php }
                            ?> </td>
                                        </tr>
                                        <hr>
                                        <tr class="bordertoplightgray">
                                            <td  bgcolor="#fff"><button type="button" onclick="window.history.go(-1); return false;" name="search"  value="" class="btn btn-info"><i class="fa fa fa-chevron-left"></i> <?php echo $this->lang->line('back'); ?> </button>  </td>
                                            <td  bgcolor="#fff" class="text-right"> <button type="button"  id="buy-button" name="search"  value="" class="btn btn-info"><?php echo $this->lang->line('pay_with_twocheckout'); ?>  <i class="fa fa fa-chevron-right"></i></button>  </td>
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
<script src="<?php echo base_url();?>backend/custom/jquery.min.js"></script>
         
         <script>
            (function(document, src, libName, config) {
                var script = document.createElement('script');
                script.src = src;
                script.async = true;
                var firstScriptElement = document.getElementsByTagName('script')[0];
                script.onload = function() {
                    for (var namespace in config) {
                        if (config.hasOwnProperty(namespace)) {
                            window[libName].setup.setConfig(namespace, config[namespace]);
                        }
                    }
                    window[libName].register();
                };

                firstScriptElement.parentNode.insertBefore(script, firstScriptElement);
            })(document, 'https://secure.2checkout.com/checkout/client/twoCoInlineCart.js', 'TwoCoInlineCart', {
                "app": {
                    "merchant": "<?php echo $api_config->api_publishable_key; ?>"
                },
                "cart": {
                    "host": "https:\/\/secure.2checkout.com"
                }
            }); 
        </script>
          <script type="text/javascript">
          	//$('#buy-button').trigger("click");
                window.document.getElementById('buy-button').addEventListener('click', function() {

                    TwoCoInlineCart.events.subscribe('cart:closed', function(e) {
                        alert();
                        //window.location.replace("");
                    });

                    TwoCoInlineCart.setup.setMerchant("<?php echo $api_config->api_publishable_key; ?>");
                    TwoCoInlineCart.setup.setMode('DYNAMIC'); // product type
                    TwoCoInlineCart.register();

                    TwoCoInlineCart.products.add({
                        name: "Student Fees",
                        quantity: 1,
                        price: "<?php echo $amount;?>",
                    });

                    TwoCoInlineCart.cart.setOrderExternalRef("<?php echo md5(time()); ?>");
                    TwoCoInlineCart.cart.setExternalCustomerReference("<?php echo md5("1".time()); ?>"); // external customer reference 
                    TwoCoInlineCart.cart.setCurrency("<?php echo $currency; ?>");
                    TwoCoInlineCart.cart.setTest(false);
                    TwoCoInlineCart.cart.setReturnMethod({
                        type: 'redirect',
                        url: "<?php echo base_url() ?>user/gateway/twocheckout/success",
                    });

                    TwoCoInlineCart.cart.checkout(); // start checkout process
                });

                setTimeout(function() {
                    $('#buy-button').removeClass('disabled');
                }, 3000);
            </script>