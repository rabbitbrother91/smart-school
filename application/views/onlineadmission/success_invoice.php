<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $setting->name;?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta http-equiv="Cache-control" content="no-cache">
  <meta name="theme-color" content="#424242" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/style-main.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css">
</head>
 <body class="bg-light-gray">
     <div class="payment-main">
       <div class="container">
        <div class="row">
          <div class="col-lg-6 col-lg-offset-3">
            <div class="successpayment">
            <div class="successpayment-circle">
              <div class="successpayment-icon"><i class="fa fa-check"></i></div>
             </div> 
                <h1><?php echo $this->lang->line('success') ?></h1>
                <p class="mb20"><?php echo $this->lang->line('your_online_admission_fees_is_successfully_submitted') ?></p>
                <p><?php echo $this->lang->line('thank_you_for_payment') ?></p>
                <a href='<?php echo base_url("welcome/online_admission_review/".$reference_no); ?>' class="btn btn-info btn-lg mt30"><?php echo $this->lang->line('payment_status'); ?></a>
            </div>
         </div>  
        </div>  
        </div>  
      </div>     
   </body>
</html>		