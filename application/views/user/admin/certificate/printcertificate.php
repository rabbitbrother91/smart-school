<style type="text/css">
    *{padding: 0; margin:0;}
    body{ font-family: 'arial';}
    .tc-container{width: 100%;position: relative; text-align: center;padding: 2%;}
    .tc-container tr td{vertical-align: bottom;}
    /*.tc-container{
        width: 100%;
        padding: 2%;
        position: relative;
        z-index: 2;
    }*/
    .tcmybg {
        background:top center;
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        z-index: 1;
    }
    .tc-container tr td h1, h2 ,h3{margin-top: 0; font-weight: normal;}
    /*@media (max-width:210mm) and (min-width:297mm){
        .tc-container{
            margin-top: 200px;
            margin-bottom: 100px;}
    }*/
</style>

<?php
$certificate[0]->certificate_text = str_replace('[name]', '[name]', $certificate[0]->certificate_text);
$certificate[0]->certificate_text = str_replace('[present_address]', '[current_address]', $certificate[0]->certificate_text);
$certificate[0]->certificate_text = str_replace('[guardian]', '[guardian_name]', $certificate[0]->certificate_text);
$certificate[0]->certificate_text = str_replace('[phone]', '[mobileno]', $certificate[0]->certificate_text);

foreach ($students as $student) {
    $certificate_body = "";
    $certificate_body = $certificate[0]->certificate_text;

    foreach ($student as $std_key => $std_value) {

        if ($std_key == "dob") {

            if ($std_value != "0000-00-00" && $std_value != "") {
                $std_value = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateYYYYMMDDtoStrtotime($std_value));
            }
        }
        if ($std_key == "admission_date") {

            if ($std_value != "0000-00-00" && $std_value != "") {
                $std_value = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateYYYYMMDDtoStrtotime($std_value));
            }
        }
        if ($std_key == "created_at") {

            if ($std_value != "0000-00-00" && $std_value != "") {
                $std_value = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateYYYYMMDDtoStrtotime($std_value));
            }
        }
        $certificate_body = str_replace('[' . $std_key . ']', $std_value ?? '', $certificate_body);         
        //str_replace('[' . $std_key . ']', $std_value, $certificate_body);    
    }
    ?>

    <div class="" style="position: relative; text-align: center; font-family: 'arial';">
        <?php if (!empty($certificate[0]->background_image)) { ?>
            <img src="<?php echo $this->media_storage->getImageURL('uploads/certificate/' . $certificate[0]->background_image); ?>" style="width: 100%; height: 100vh" />
        <?php } ?>

        <table width="100%" cellspacing="0" cellpadding="0" style="position: absolute;top: 0; margin-left: auto;margin-right: auto;left: 0;right: 0;<?php echo "width:" . $certificate[0]->content_width . "px" ?>">
            <tr>
                <td style="position: absolute;right:0;">
                    <?php if ($certificate[0]->enable_student_image == 1) { ?>
                        <img style="position: relative; <?php echo "top:" . $certificate[0]->enable_image_height . "px" ?>;" src="<?php echo $this->media_storage->getImageURL($student->image); ?>" width="100" height="auto">
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td valign="top" style="text-align:left; position: relative; <?php echo "top:" . $certificate[0]->header_height . "px" ?>"><?php echo $certificate[0]->left_header ?></td>
                <td valign="top" style="text-align:center; position: relative; <?php echo "top:" . $certificate[0]->header_height . "px" ?>"><?php echo $certificate[0]->center_header ?></td>
                <td valign="top" style="text-align:right; position: relative; <?php echo "top:" . $certificate[0]->header_height . "px" ?>"><?php echo $certificate[0]->right_header ?></td>
            </tr>
            <tr>
                <td colspan="3" valign="top" style="position: relative; <?php echo "top:" . $certificate[0]->content_height . "px" ?>">
                    <p style="font-size: 14px; line-height: 24px; text-align:center;"><?php echo $certificate_body;
                    ?></p></td>
            </tr>
            <tr>
                <td valign="top" style="text-align:left;position: relative; <?php echo "top:" . $certificate[0]->footer_height . "px" ?>"><?php echo $certificate[0]->left_footer ?></td>
                <td valign="top" style="text-align:center;position: relative; <?php echo "top:" . $certificate[0]->footer_height . "px" ?>"><?php echo $certificate[0]->center_footer ?></td>
                <td valign="top" style="text-align:right;position: relative; <?php echo "top:" . $certificate[0]->footer_height . "px" ?>"><?php echo $certificate[0]->right_footer ?></td>
            </tr>
        </table>
    </div>

    <?php
}
?>