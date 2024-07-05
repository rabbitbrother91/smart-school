<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo $this->customlib->getAppName() . " : School Management System" ?></title>
        <!--favican-->
        <link href="<?php echo base_url(); ?>backend/images/s-favican.png" rel="shortcut icon" type="image/x-icon">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
        <style type="text/css">

            ::selection { background-color: #E13300; color: white; }
            ::-moz-selection { background-color: #E13300; color: white; }

            body {
                background-color: #ececec;
                margin:0px; padding:0;
                font: 13px/20px normal Helvetica, Arial, sans-serif;
                color: #4F5155;
            }
            a {
                color: #003399;
                background-color: transparent;
                font-weight: normal;
            }
            h1 {
                color: #444;
                background-color: transparent;
                border-bottom: 1px solid #D0D0D0;
                font-size: 19px;
                font-weight: normal;
                margin: 0 0 14px 0;
                padding: 14px 15px 10px 15px;
            }
            code {
                font-family: Consolas, Monaco, Courier New, Courier, monospace;
                font-size: 12px;
                background-color: #f9f9f9;
                border: 1px solid #D0D0D0;
                color: #002166;
                display: block;
                margin: 14px 0 14px 0;
                padding: 12px 10px 12px 10px;
            }
            #body {
                margin: 0 15px 0 15px;
            }
            p.footer {
                text-align: right;
                font-size: 11px;
                border-top: 1px solid #D0D0D0;
                line-height: 32px;
                padding: 0 10px 0 10px;
                margin: 20px 0 0 0;
            }
            /*#container {
                    margin: 10px;
                    border: 1px solid #D0D0D0;
                    box-shadow: 0 0 8px #D0D0D0;
            }*/
            .margin-auto{margin:0 auto; display:block; text-align:center}
            .main-header { padding: 10px 0 5px;
                           background-color: #424242; min-height:50px;
                           box-shadow: 0 3px 6px rgba(0,0,0,.16),0 3px 6px rgba(0,0,0,.23);}
            .logo-lg img {width: 85%;}
            .imgresponsive{ width:100%; display:block; height:auto;}
            .space30{ padding:40px 0}
            .font-size2{font-size:20px;}
            .btn2{margin-top:20px; background:#727272; border:1px solid #525252;}
            .btn2:hover{background:#070707;}
        </style>
    </head>
    <body>
        <header class="main-header">
            <div class="container">
                <div class="row">
                    <div class=""><img src="<?php echo base_url(); ?>/uploads/school_content/admin_logo/<?php $this->setting_model->getAdminlogo(); ?>" class="margin-auto" width="162" height="29"  /></div>
                </div>
            </div>
        </header>
        <div class="container text-center">
            <div class="row">
                <div class="space30">
                    <img src="<?php echo base_url(); ?>/backend/images/errorimg.png" class="img-responsive margin-auto" /></div>
                <h2 style="color:#C00">The page you were looking for doesn't exist.</h2>
                <p class="font-size2">You may have mistyped the address or the page may have moved.</p>
                <button onclick="history.go(-1);"  name="search"  class="btn btn2 btn-primary btn-sm checkbox-toggle">Back to Previous Page</button>
            </div>
        </div>
        <div id="container">
        </div>
    </body>
</html>


