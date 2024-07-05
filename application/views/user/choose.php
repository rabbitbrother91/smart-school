<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#424242" />
        <title><?php echo $this->customlib->getAppName(); ?></title> 
        <!--favican-->
        <!-- <link href="<?php echo base_url(); ?>backend/images/s-favican.png" rel="shortcut icon" type="image/x-icon"> -->
         <link href="<?php echo base_url(); ?>uploads/school_content/admin_small_logo/<?php $this->setting_model->getAdminsmalllogo();?>" rel="shortcut icon" type="image/x-icon">
        <!-- CSS -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/css/style.css">
        <style type="text/css">
         body {
    /*background: linear-gradient(to right,#0acffe 0,#495aff 100%);*/
    background:url('../../backend/images/white-dashboard.png') no-repeat top center;
    z-index: 0;
    background-size: cover;
    position: relative;
    height: 100vh;
    width: 100%;}   
 body:after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.90);
    width: 100%;
    height: 100%;
    z-index: -1;
}   
.top-content {position: relative;}
.top-content {
    width: 100%;
    min-height: 95vh;
    padding-bottom: 50px;
    display: -webkit-box;
    display: -webkit-flex;
    display: -moz-box;
    display: -ms-flexbox;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    padding: 15px;
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
}
.bgoffsetbgno {
    background: transparent;
    border-right: 0 !important;
    box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.29);
    border-radius: 4px;
}
.nopadding {
    padding-left: 0;
    padding-right: 0;
    border-right: 1px solid #ddd;
}
.loginradius {border-radius: 4px; overflow: hidden;}
.loginbg {background: #fff;}
.login390 {min-height: 100%;}
.btn2:hover {
    color: #fff;
    background: #35aa47;
}
.btn2 {
    margin: 0;
    padding: 9px 15px;
    vertical-align: middle;
    background: #424242;
    border: 0;
    font-family: 'Roboto', sans-serif;
    font-size: 14px;
    font-weight: 400;
    color: #fff;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
    text-shadow: none;
    -moz-box-shadow: none;
    -webkit-box-shadow: none;
    box-shadow: none;
    -o-transition: all .3s;
    -moz-transition: all .3s;
    -webkit-transition: all .3s;
    -ms-transition: all .3s;
    transition: all .3s;
}


.form-bottom {
       margin-top: 30px;
       border-top:1px solid #f4f4f4;
    overflow: hidden;
    padding: 15px 15px 15px;
    background: #fff;
    -moz-border-radius: 3px 3px 0 0;
    -webkit-border-radius: 3px 3px 0 0;
    border-radius: 3px 3px 0 0;
    text-align: left;
}
.selectform {text-align: left;
    
    padding-top: 10px;
}
.selectform .radio{  
    margin-left: 15px;
    margin-right: 15px;
    border-radius: 2px;
    min-height: 20px;
    padding: 9px;
    margin-bottom: 25px;
    background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    border-radius: 2px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
}
.selectform .radio label{color: #000;}
input[type=radio] {
    margin: 6px 0 0;
}
.select-title{ border-bottom: 1px solid #e5e5e5;
    color: #333;
    padding: 6px 16px 10px;
    margin-top: 0;
    margin-bottom: 20px;}
  @media(max-width:767px){
    .container{width: 100%;}
  }  
        </style>
    </head>
 <body>   
<section class="top-content">    
<div class="container">
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12 col-md-offset-3">
            <div class="loginbg loginradius login390 bgoffsetbgno"> 
                
<?php

if (!empty($student_lists)) {
    ?>
<form action="<?php echo site_url('user/user/choose'); ?>" method="POST" >

<div class="selectform">
   <h4 class="select-title"><?php echo $this->lang->line('select_class'); ?></h4>

    <?php
foreach ($student_lists as $student_key => $student_value) {
        if ($role == "parent") {
            $name = $this->customlib->getFullName($student_value->firstname,$student_value->middlename,$student_value->lastname,$sch_setting->middlename,$sch_setting->lastname);
        }
        ?>

<div class="radio">
  <label>
    <input type="radio" value="<?php echo $student_value->student_session_id; ?>" class="clschg" name="clschg"><?php echo ($role == 'parent') ? $name . " " . $student_value->class . " (" . $student_value->section . ")" : $student_value->class . " (" . $student_value->section . ")"; ?>
  </label>
</div>



    <?php
}
    ?>
 </div>   
    <span class="text-danger"><?php echo form_error('clschg'); ?></span>
    <div class="form-bottom"><input type="submit" class="btn2 pull-right" name="submit" value="<?php echo $this->lang->line('select_and_proceed'); ?>"></div>
</form>

    <?php
} else {
    ?>
<div class="alert alert-info">    
	<?php echo $this->lang->line('no_more_classes_found_in_your_current_session'); ?>
	
</div>
<?php
}
?>
</div>
</div>
</div>
</div>
</section>
</body>
</html>