<style type="text/css">

    @media print {
      .page-break { display: block; page-break-before: always; }
    }
    *{ margin:0; padding: 0;}

    table{ font-family: 'arial'; margin:0; padding: 0;font-size: 12px; color: #000;}
    .tc-container{width: 100%;position: relative; text-align: center;margin-bottom:60px;padding-bottom: 10px;}
    .tcmybg {
        background: top center;
        background-size: contain;
        position: absolute;
        left: 0;
        bottom: 10px;
        width: 160px;
        height: 160px;
        margin-left: auto;
        margin-right: auto;
        right: 0;
        opacity: 0.10;
    }
    /*begin students id card*/
    .studentmain{background: #efefef;width: 100%; margin-bottom: 30px;}
    .studenttop img{width:30px;vertical-align: top;}
    .studenttop{background: #453278;padding:2px;color: #fff;overflow: hidden;position: relative;z-index: 1;}
    .sttext1{font-size: 24px;font-weight: bold;line-height: 30px;}
    .stgray{background: #efefef;padding-top: 5px; padding-bottom: 10px;}
    .staddress{margin-bottom: 0; padding-top: 2px;}
    .stdivider{border-bottom: 2px solid #000;margin-top: 5px; margin-bottom: 5px;}
    .stlist{padding: 0; margin:0; list-style: none;}
    .stlist li{text-align: left;display: inline-block;width: 100%;padding: 0px 5px;}
    .stlist li span{width:65%;float: right;}
    .stimg{width: 80px;height: 80px; margin-bottom: 5px;}
    .stimg img{width: 100%;height: 80px;border-radius: 2px;display: block;}
    .img-circle {border-radius:16px;}
    .center-block {display: block;margin-right: auto;margin-left: auto;}
    .staround{padding:3px 10px 3px 0;position: relative;overflow: hidden;}
    .staround2{position: relative; z-index: 9;}
    .stbottom{background: #453278;height: 20px;width: 100%;clear: both;margin-bottom: 5px;}
    .principal{margin-top: -40px;margin-right:10px; float:right;}
    .stred{color: #000;}
    .spanlr{padding-left: 5px; padding-right: 5px;}
    .cardleft{width: 20%;float: left;}
    .cardright{width: 77%;float: right; }
    .width32{width: 32.55%; padding: 3px; float: left; height: 100%;}
    .signature{border:1px solid #ddd;display:block; text-align: center; padding: 5px 20px; margin-top: 2px;}
    .vertlist{padding: 0; margin:0; list-style: none;height: 129px;}
    .vertlist li{text-align: left;display: inline-block;width: 100%; padding-bottom: 0px;color: #000;}
    .vertlist li span{width:55%;float: right;}
    .barcodeimg{display: block;margin-top: 2px;text-align: center;}
</style>

<?php
$school = $sch_setting[0];
$i = 0;

?>
<?php 
if($id_card[0]->enable_vertical_card)
{
?>

<table cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <?php 
        foreach ($students as $student) {
            $i++;
            ?>
            <td valign="top" class="width32">
             <table cellpadding="0" cellspacing="0" width="100%" style="background: <?php echo $id_card[0]->header_color; ?>;">
    <tr>
        <td valign="top" style="text-align: center;color: #fff;padding: 5px 5px;min-height: 94px;display: block; text-align: center">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                   <td valign="top">
                        <div style="color: #fff;position: relative; z-index: 1; text-align: center;vertical-align: top">
                            <div class="sttext1" style="font-size: 16px;line-height: 8px;"><img style="vertical-align: middle; width: 30px;" src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/logo/'.$id_card[0]->logo); ?>" width="30" height="24">  <?php echo $id_card[0]->school_name; ?> 
                           </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td valign="top" style="color: #fff;text-align: center;"><?php echo $id_card[0]->school_address; ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top" style="background: #fff">
           <table cellpadding="0" cellspacing="0" width="100%" style="margin-top: -45px; position: relative;z-index: 1;">
               <tr>
                    <td valign="top">
                        <div class="stimg center-block">
                            <img src="<?php
											if (!empty($student->image)) {
												   echo $this->media_storage->getImageURL($student->image);
											} else {

												if ($student->gender == 'Female') {
													echo $this->media_storage->getImageURL("uploads/student_images/default_female.jpg");
												} elseif ($student->gender == 'Male') {
													echo $this->media_storage->getImageURL("uploads/student_images/default_male.jpg");
												}

											}
											?>" class="img-responsive img-circle block-center" style="border-radius: 8px; border:3px solid <?php echo $id_card[0]->header_color; ?>">
                        </div>
                    </td>
                </tr>                
                <tr>
                    <td valign="top" style="text-align: center;">
                        <h4 style="margin:0; text-transform: uppercase;font-weight: bold; margin-top: 6.5px;"> <?php echo $this->customlib->getFullName($student->firstname,$student->middlename,$student->lastname,$sch_settingdata->middlename,$sch_settingdata->lastname); ?></h4> 
                    </td>
                </tr>  
           </table> 
        </td>
    </tr>
    <tr>
        <td valign="top">
           <table cellpadding="0" cellpadding="0" width="90%" align="center" style="background: #fff;padding: 5px 5px;display: block;width: 90%;margin:0 auto">
                <tr>
                    <td valign="top">
                        <ul class="vertlist">                        
                                        
                                        <?php if ($id_card[0]->enable_admission_no == 1) { ?><li><?php echo $this->lang->line('admission_no'); ?><span> <?php echo $student->admission_no; ?></span></li><?php } ?>
                                        <?php if ($id_card[0]->enable_class == 1) { ?><li><?php echo $this->lang->line('class'); ?><span><?php echo $student->class . ' - ' . $student->section . " (" . $school['current_session']['session'] . ")"; ?></span></li><?php } ?>
                                        <?php if ($id_card[0]->enable_fathers_name == 1) { ?><li><?php echo $this->lang->line('father_name'); ?><span><?php echo $student->father_name; ?></span></li><?php } ?>
                                        <?php if ($id_card[0]->enable_mothers_name == 1) { ?><li><?php echo $this->lang->line('mother_name')?><span><?php echo $student->mother_name; ?></span></li><?php } ?>
                                        <?php if ($id_card[0]->enable_address == 1) { ?><li><?php echo $this->lang->line('address')?><span><?php echo $student->current_address; ?></span></li><?php } ?>
                                        <?php if ($id_card[0]->enable_phone == 1) { ?><li>Phone<span><?php echo $student->mobileno; ?></span></li><?php } ?>
                                        <?php
                                        if ($id_card[0]->enable_dob == 1) {
                                            ?>
                                         <li><?php echo $this->lang->line('d_o_b');?>
                                        <span>
                                                    <?php
                                                    echo $dob = "";
                                                    if ((!empty($student->dob)) && ($student->dob != "0000-00-00")) {
                                                        $dob = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateYYYYMMDDtoStrtotime($student->dob));
                                                    }
                                                    echo $dob;
                                                    ?>
                                                </span></li>
                                            <?php
                                        }
                                        ?>

                                        <?php if ($id_card[0]->enable_blood_group == 1) { ?><li class="stred"><?php echo $this->lang->line('blood_group'); ?><span><?php echo $student->blood_group; ?></span></li><?php } ?>
                        </ul>
                        <div class="signature" style="margin-bottom: 8px;"><img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/signature/'.$id_card[0]->sign_image); ?>" width="150" height="24" style="width: 150px;" /></div>  

                        <?php if ($id_card[0]->enable_student_barcode == 1) { ?>
                        <div class="signature"><img src="<?php echo $this->media_storage->getImageURL($student->barcode); ?>" style="max-width: 65px; margin: 0 auto; height:auto" /></div>
                        <?php } ?>
                        
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
            </td>

            <?php
            if ($i == 3) {
                // three items in a row. Edit this to get more or less items on a row
                ?></tr><tr><?php
                $i = 0;
            }
        }
        ?>
    </tr>

</table>
    <?php
}else{
    ?>

<table cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <?php
        foreach ($students as $student) {
            $i++;
            ?>
            <td valign="top" class="width32">
                <table cellpadding="0" cellspacing="0" width="100%" class="tc-container" style="background: #efefef;">
                    <tr>
                        <td valign="top">
                            <img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/background/' . $id_card[0]->background); ?>" class="tcmybg" /></td>
                    </tr>                   
                    <tr>
                        <td valign="top">
                            <div class="studenttop" style="background: <?php echo $id_card[0]->header_color ?>">
                                <div class="sttext1"><img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/logo/' . $id_card[0]->logo); ?>" width="30" height="30" />
                                    <?php echo $id_card[0]->school_name ?></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="center" style="padding: 1px 0; position: relative; z-index: 1">
                            <p>  <?php echo $id_card[0]->school_address ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" style="color: #fff;font-size: 16px; padding: 2px 0 0; position: relative; z-index: 1;background: <?php echo $id_card[0]->header_color ?>;text-transform: uppercase;"><?php echo $id_card[0]->title ?></td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <div class="staround">
                                <div class="cardleft">
                                    <div class="stimg">
                                        <img src="<?php
                                            if (!empty($student->image)) {
                                                   echo $this->media_storage->getImageURL($student->image);
                                            } else {

                                                if ($student->gender == 'Female') {
                                                    echo $this->media_storage->getImageURL("uploads/student_images/default_female.jpg");
                                                } elseif ($student->gender == 'Male') {
                                                    echo $this->media_storage->getImageURL("uploads/student_images/default_male.jpg");
                                                }

                                            }
                                            ?>" class="img-responsive" />
                                    </div>
                                    
                                    <?php if ($id_card[0]->enable_student_barcode == 1) { ?>
                                    <div class="barcodeimg center-block" style="width: 90%;margin:0 auto"><img src="<?php echo $this->media_storage->getImageURL($student->barcode); ?>" style="max-width: 65px; margin: 0 auto; height:auto" /></div>
                                    <?php } ?>
                                    
                                </div><!--./cardleft-->                                
                    
                                <div class="cardright">
                                    <ul class="stlist">
                                      
                                        <?php if ($id_card[0]->enable_student_name == 1) { ?><li><?php echo $this->lang->line('student_name'); ?><span> <?php echo $this->customlib->getFullName($student->firstname,$student->middlename,$student->lastname,$sch_settingdata->middlename,$sch_settingdata->lastname); ?></span></li><?php } ?>
                                        <?php if ($id_card[0]->enable_admission_no == 1) { ?><li><?php echo $this->lang->line('admission_no'); ?><span> <?php echo $student->admission_no; ?></span></li><?php } ?>
                                        <?php if ($id_card[0]->enable_class == 1) { ?><li><?php echo $this->lang->line('class'); ?><span><?php echo $student->class . ' - ' . $student->section . " (" . $school['current_session']['session'] . ")"; ?></span></li><?php } ?>
                                        <?php if ($id_card[0]->enable_fathers_name == 1) { ?><li><?php echo $this->lang->line('father_name'); ?><span><?php echo $student->father_name; ?></span></li><?php } ?>
                                        <?php if ($id_card[0]->enable_mothers_name == 1) { ?><li><?php echo $this->lang->line('mother_name')?><span><?php echo $student->mother_name; ?></span></li><?php } ?>
                                        <?php if ($id_card[0]->enable_address == 1) { ?><li><?php echo $this->lang->line('address')?><span><?php echo $student->current_address; ?></span></li><?php } ?>
                                        <?php if ($id_card[0]->enable_phone == 1) { ?><li>Phone<span><?php echo $student->mobileno; ?></span></li><?php } ?>
                                        <?php
                                        if ($id_card[0]->enable_dob == 1) {
                                            ?>
                                         <li><?php echo $this->lang->line('d_o_b');?>
                                        <span>
                                                    <?php
                                                    echo $dob = "";
                                                    if ($student->dob != "0000-00-00") {
                                                        $dob = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateYYYYMMDDtoStrtotime($student->dob));
                                                    }
                                                    echo $dob;
                                                    ?>
                                                </span></li>
                                            <?php
                                        }
                                        ?>

                                        <?php if ($id_card[0]->enable_blood_group == 1) { ?><li class="stred"><?php echo $this->lang->line('blood_group'); ?><span><?php echo $student->blood_group; ?></span></li><?php } ?>
                                    </ul>
                                </div><!--./cardright-->
                            </div><!--./staround-->
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="right" class="principal"><img src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/signature/' . $id_card[0]->sign_image); ?>" width="66" height="40" /></td>
                    </tr>
                </table>
            </td>

            <?php
            if ($i == 3) {
                // three items in a row. Edit this to get more or less items on a row
                ?></tr><tr><?php
                $i = 0;
            }
        }
        ?>
    </tr>
</table>
    <?php
}

 ?>