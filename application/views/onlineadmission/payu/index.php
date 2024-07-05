<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#424242" />
        <title>School Management System</title>
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

                        <img src="<?php echo base_url('uploads/school_content/logo/' . $setting->image); ?>">

                    </div>
                    <div class="col-md-6 col-md-offset-3 mt20">
                        <div class="paymentbg">
                            <div class="invtext"><?php echo $this->lang->line('payment_details'); ?> </div>
                            <div class="padd2 paddtzero">
                            <form id="payuForm" action="<?php echo $action; ?>" method="post">
                                    <table class="table2" width="100%">
                                        <tr>
                                            <th><?php echo $this->lang->line('description'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('amount') ?></th>
                                        </tr>

                                        <tr class="border_bottom">
                                            <td> 
                                                <span class="title"><?php echo $this->lang->line('online_admission_form_fees'); ?></span></td>
                                            <td class="text-right"><?php echo $this->customlib->getSchoolCurrencyFormat() . amountFormat( $amount); ?></td>
                                        </tr>
                                    <tr class="bordertoplightgray">
                                        <td colspan="2" class="text-right"><?php echo $this->lang->line('total');?>: <?php echo $this->customlib->getSchoolCurrencyFormat() . amountFormat($amount); ?></td>
                                    </tr>
                                        <tr class="bordertoplightgray">
                                            <td  bgcolor="#fff"><button type="submit" onclick="window.history.go(-1); return false;" name="search"  value="" class="btn btn-info"><i class="fa fa fa-chevron-left"></i> <?php echo $this->lang->line('back'); ?> </button>  </td>
                                            <td  bgcolor="#fff" class="text-right"> <button type="submit"  name="search"  value="" class="btn btn-success submit_button"> <?php echo $this->lang->line('pay_with_payu'); ?> <i class="fa fa fa-chevron-right"></i></button>  </td>
                                        </tr>
                                    </table>
                                    <input type="hidden" name="key" value="<?php echo $mkey ?>" />
                                    <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
                                    <input type="hidden" name="txnid" value="<?php echo $tid ?>" />
                                    <input type="hidden" name="amount" value="<?php echo set_value('amount', convertBaseAmountCurrencyFormat($amount)) ?>" />
                                    <input type="hidden" name="firstname" id="firstname" value="<?php echo set_value('firstname', $name); ?>" />
                                    <textarea name="productinfo" style="display:none"><?php echo set_value('productinfo', $productinfo); ?></textarea>
                                    <input type="hidden" name="surl" value="<?php echo set_value('surl', $sucess); ?>" size="64" />
                                    <input type="hidden" name="furl" value="<?php echo set_value('furl', $failure); ?>" size="64" />
                                    <input type="hidden" name="service_provider" value="payu_paisa" size="64" />
                                    <input type="hidden" name="phone" value="<?php echo $phoneno;?>" />
                                    <input type="hidden" name="email" value="<?php echo $mailid;?>" />


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
                    var url = "<?php echo site_url('onlineadmission/payu/checkout') ?>";

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