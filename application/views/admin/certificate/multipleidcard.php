<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="icon" type="image/png" href="assets/img/s-favican.png">
        <meta http-equiv="X-UA-Compatible" content="" />
        <title>Smart School : School Management System by QDOCS</title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <meta name="theme-color" content="#424242" />       
    </head>
    <body>
        <table cellpadding="0" cellspacing="0" width="100%">
            <?php
            if (!empty($resultlist)) {
                $i = 0;
                ?>
                <tr> 
                    <?php                    
                    foreach ($resultlist as $list) {
                        $i++;                         
                        ?>
                        <td valign="top" width="32%" style="padding: 3px;">
                            <table cellpadding="0" cellspacing="0" width="100%" class="tc-container" style="background: #efefef;">
                                <tr>
                                    <td valign="top">
                                        <img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/background/'.$idcardlist[0]->background); ?>" class="tcmybg" /></td>
                                </tr>
                                <tr>
                                    <td valign="top">
                                        <div class="studenttop" style="background: <?php echo $idcardlist[0]->header_color; ?>">
                                            <div class="sttext1"><img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/logo/'.$idcardlist[0]->logo); ?>" width="30" height="30" />
                                                <?php echo $idcardlist[0]->school_name; ?></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" align="center" style="padding: 1px 0; position: relative; z-index: 1">
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
                                                    <img src="<?php echo $this->media_storage->getImageURL($list[0]['image']) ?>" class="img-responsive" />
                                                </div>
                                            </div><!--./cardleft--> 
                                            <div class="cardright">
                                                <ul class="stlist">
                                                    <?php
                                                    if ($idcardlist[0]->enable_admission_no == 1) {
                                                        echo "<li>" . $this->lang->line('admission_no') . "<span>" . $list[0]['admission_no'] . " </span></li>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($idcardlist[0]->enable_student_name == 1) {
                                                        echo "<li>" . $this->lang->line('student') . " " . $this->lang->line('name') . "<span>" . $list[0]['firstname'] . ' ' . $list[0]['lastname'] . "</span></li>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($idcardlist[0]->enable_class == 1) {
                                                        echo "<li>" . $this->lang->line('class') . "<span>" . $list[0]['class'] . " - " . $list[0]['section'] . "</span></li>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($idcardlist[0]->enable_fathers_name == 1) {
                                                        echo "<li>" . $this->lang->line('fathers') . " " . $this->lang->line('name') . "<span>" . $list[0]['father_name'] . " </span></li>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($idcardlist[0]->enable_mothers_name == 1) {
                                                        echo "<li>" . $this->lang->line('mothers') . " " . $this->lang->line('name') . "<span>" . $list[0]['mother_name'] . " </span></li>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($idcardlist[0]->enable_address == 1) {
                                                        echo "<li>" . $this->lang->line('address') . "<span>" . $list[0]['permanent_address'] . " </span></li>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($idcardlist[0]->enable_phone == 1) {
                                                        echo "<li>" . $this->lang->line('phone') . "<span>" . $list[0]['father_phone'] . " </span></li>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($idcardlist[0]->enable_dob == 1) {
                                                        echo "<li>" . $this->lang->line('d_o_b') . "<span>" . date('d-m-Y', strtotime($list[0]['dob'])) . " </span></li>";
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($idcardlist[0]->enable_blood_group == 1) {
                                                        echo "<li class='stred'>" . $this->lang->line('blood') . " " . $this->lang->line('group') . "<span> A+ </span></li>";
                                                    }
                                                    ?>
                                                </ul>
                                            </div><!--./cardright-->
                                        </div><!--./staround-->
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" align="right" class="principal"><img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/signature.png') ?>" width="66" height="40" /></td>
                                </tr>
                            </table>
                        </td>
                        <?php if ($i == 3) { ?>
                        </tr>
                        <tr> 
                            <?php
                            $i = 0;
                        }
                        ?> 
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
    </body>  
</html>