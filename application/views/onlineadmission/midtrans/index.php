<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#424242" />
        <title><?php echo $setting->name;?></title>
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
            <div class="row">
                <div class="paddtop20">
                    <div class="col-md-8 col-md-offset-2 text-center">

                        <img src="<?php echo base_url('uploads/school_content/logo/' . $setting->image); ?>">

                    </div>
                    <form id="payment-form" method="post" action="#">
                        <input type="hidden" name="result_type" id="result-type" value=""></div>
                        <input type="hidden" name="result_data" id="result-data" value=""></div>
                    </form>
                    <div class="col-md-6 col-md-offset-3 mt20">
                        <div class="paymentbg">
                            <div class="invtext"><?php echo $this->lang->line('payment_details'); ?> </div>
                            <div class="padd2 paddtzero">
                                <form id="checkoutform" action="#" method="post">
                                    <table class="table2" width="100%">
                                        <tr>
                                            <th><?php echo $this->lang->line('description'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('amount') ?></th>
                                        </tr>

                                        <tr class="border_bottom">
                                            <td> 
                                                <span class="title"><?php echo $this->lang->line('online_admission_form_fees'); ?></span></td>
                                            <td class="text-right"><?php echo $this->customlib->getSchoolCurrencyFormat() . amountFormat($amount); ?></td>
                                        </tr>
                                    <tr class="bordertoplightgray">
                                        <td colspan="2" class="text-right"><?php echo $this->lang->line('total');?>: <?php echo $this->customlib->getSchoolCurrencyFormat() . amountFormat($amount); ?></td>
                                    </tr>
                                        <tr class="bordertoplightgray">
                                            <td  bgcolor="#fff"><button type="submit" onclick="window.history.go(-1); return false;" name="search"  value="" class="btn btn-info"><i class="fa fa fa-chevron-left"></i> <?php echo $this->lang->line('back'); ?> </button>  </td>
                                            <td  bgcolor="#fff" class="text-right"> <button type="button"  name="search" id="pay-button" value="" class="btn btn-success"> <?php echo $this->lang->line('pay_with_midtrans'); ?> <i class="fa fa fa-chevron-right"></i></button>  </td>
                                        </tr>
                                    </table>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>

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
                            url: '<?php echo base_url(); ?>onlineadmission/midtrans/complete',
                            type: 'POST',
                            data: $('#payment-form').serialize(),
                            dataType: "json",
                            success: function (msg) {

                                window.location.href = "<?php echo base_url(); ?>onlineadmission/checkout/successinvoice/" + msg;

                            } 
                        });
                    },
                    onPending: function (result) {
                        console.log('pending');
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
    </body>
</html>