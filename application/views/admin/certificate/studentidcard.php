<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="icon" type="image/png" href="assets/img/s-favican.png">
        <meta http-equiv="X-UA-Compatible" content="" />
        <title>Smart School : School Management System by QDOCS</title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <meta name="theme-color" content="#424242" />
        <style type="text/css">
            *{ margin:0; padding: 0;}

            body{ font-family: 'arial'; margin:0; padding: 0;font-size: 12px; color: #000;}
            .tc-container{width: 100%;position: relative; text-align: center;}
            .tcmybg {
                background: top center;
                background-size: contain;
                position: absolute;
                left: 0;
                bottom: 10px;
                width: 200px;
                height: 200px;
                margin-left: auto;
                margin-right: auto;
                right: 0;
            }
            /*begin students id card*/
            .studentmain{background: #efefef;width: 100%; margin-bottom: 30px;}
            .studenttop img{width:30px;vertical-align: top;}
            .studenttop{background: <?php echo $idcardlist[0]->header_color; ?>;padding:2px;color: #fff;overflow: hidden;
                        position: relative;z-index: 1;}
            .sttext1{font-size: 24px;font-weight: bold;line-height: 30px;}
            .stgray{background: #efefef;padding-top: 5px; padding-bottom: 10px;}
            .staddress{margin-bottom: 0; padding-top: 2px;}
            .stdivider{border-bottom: 2px solid #000;margin-top: 5px; margin-bottom: 5px;}
            .stlist{padding: 0; margin:0; list-style: none;}
            .stlist li{text-align: left;display: inline-block;width: 100%;padding: 0px 5px;}
            .stlist li span{width:65%;float: right;}
            .stimg{
                /*margin-top: 5px;*/
                width: 80px;
                height: auto;
                margin-left: 10px;
                /*margin: 0 auto;*/
            }
            .stimg img{width: 100%;height: auto;border-radius: 2px;display: block;}
            .staround{padding:3px 10px 3px 0;position: relative;overflow: hidden;}
            .staround2{position: relative; z-index: 9;}
            .stbottom{background: #453278;height: 20px;width: 100%;clear: both;margin-bottom: 5px;}
            .principal{margin-top: -40px;margin-right:10px; float:right;}
            .stred{color: #000;}
            .spanlr{padding-left: 5px; padding-right: 5px;}
            .cardleft{width: 20%;float: left;}
            .cardright{width: 77%;float: right; }

            /*END students id card*/
        </style>
    </head>
    <body style="margin-top: 50px">
    <center>
        <table cellpadding="0" cellspacing="0" width="32%">
            <tr>
                <td valign="top" width="32%" >
                    <table cellpadding="0" cellspacing="0" width="100%" class="tc-container" style="background: #efefef;">
                        <tr>
                            <td valign="top">
                                <img src="<?php echo base_url('uploads/student_id_card/background/') ?><?php echo $idcardlist[0]->background; ?>" class="tcmybg" /></td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <div class="studenttop">
                                    <div class="sttext1"><img src="<?php echo base_url('uploads/student_id_card/logo/') ?><?php echo $idcardlist[0]->logo; ?>" width="30" height="30" />
                                        <?php echo $idcardlist[0]->school_name; ?></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="center" style="padding: 1px 0;position: relative; z-index: 1">
                                <p><?php echo $idcardlist[0]->school_address; ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" style="color: #fff;font-size: 16px; padding: 2px 0 0; position: relative; z-index: 1;background: <?php echo $idcardlist[0]->header_color; ?>;text-transform: uppercase;"><?php echo $idcardlist[0]->title; ?></td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <div class="staround">
                                    <div class="cardleft">
                                        <div class="stimg">
                                            <img src="<?php echo base_url() ?>/<?php echo $resultlist[0]['image']; ?>" class="img-responsive" />
                                        </div>
                                    </div><!--./cardleft-->
                                    <div class="cardright">
                                        <ul class="stlist">
                                            <?php
if ($idcardlist[0]->enable_admission_no == 1) {
    echo "<li>Admission Number<span>" . $resultlist[0]['admission_no'] . " </span></li>";
}
?>
                                            <?php
if ($idcardlist[0]->enable_student_name == 1) {
    echo "<li>" . $this->lang->line('student') . " " . $this->lang->line('name') . "<span>" . $resultlist[0]['firstname'] . ' ' . $resultlist[0]['lastname'] . "</span></li>";
}
?>
                                            <?php
if ($idcardlist[0]->enable_class == 1) {
    echo "<li>" . $this->lang->line('class') . "<span>" . $resultlist[0]['class'] . " - " . $resultlist[0]['section'] . "</span></li>";
}
?>
                                            <?php
if ($idcardlist[0]->enable_fathers_name == 1) {
    echo "<li>" . $this->lang->line('fathers') . " " . $this->lang->line('name') . "<span>" . $resultlist[0]['father_name'] . " </span></li>";
}
?>
                                            <?php
if ($idcardlist[0]->enable_mothers_name == 1) {
    echo "<li>" . $this->lang->line('mothers') . " " . $this->lang->line('name') . "<span>" . $resultlist[0]['mother_name'] . " </span></li>";
}
?>
                                            <?php
if ($idcardlist[0]->enable_address == 1) {
    echo "<li>" . $this->lang->line('address') . "<span>" . $resultlist[0]['permanent_address'] . " </span></li>";
}
?>
                                            <?php
if ($idcardlist[0]->enable_phone == 1) {
    echo "<li>" . $this->lang->line('phone') . "<span>" . $resultlist[0]['father_phone'] . " </span></li>";
}
?>
                                            <?php
if ($idcardlist[0]->enable_dob == 1) {
    echo "<li>" . $this->lang->line('d_o_b') . "<span>" . date('d-m-Y', strtotime($resultlist[0]['dob'])) . " </span></li>";
}
?>
                                            <?php
if ($idcardlist[0]->enable_blood_group == 1) {
    echo "<li class='stred'>" . $this->lang->line('blood') . " " . $this->lang->line('group') . "<span> A+</span></li>";
}
?>
                                        </ul>
                                    </div><!--./cardright-->
                                </div><!--./staround-->
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="right" class="principal"><img src="<?php echo base_url('uploads/student_id_card/signature.png') ?>" width="66" height="40" /></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>