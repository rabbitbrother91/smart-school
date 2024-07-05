<style type="text/css">
    *{padding: 0; margin:0;}
    body{ font-family: 'arial';}
    .tc-container{width: 100%;position: relative; text-align: center;padding: 2%;}
    .tc-container tr td{vertical-align: bottom;}

    .tcmybg {
        background:top center;
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        z-index: 1;
    }

    .tc-container tr td h1, h2 ,h3{margin-top: 0;font-weight: normal;} 
</style>               

<div class="" style="position: relative; text-align: center; font-family: 'arial';">   
    <?php if (!empty($certificate->background_image)) { ?>
        <img src="<?php echo $this->media_storage->getImageURL('uploads/certificate/' . $certificate->background_image); ?>" style="width: 100%; height: 100vh" />
    <?php } ?>

    <table width="100%" cellspacing="0" cellpadding="0" style="position: absolute;top: 0; margin-left: auto; margin-right: auto;left: 0;right: 0;<?php echo "width:" . $certificate->content_width . "px" ?>">
        <tr>
            <td style="position: absolute;right:0;">
                <?php if ($certificate->enable_student_image == 1) { ?>
                    <img style="position: relative; <?php echo "top:" . $certificate->enable_image_height . "px" ?>;" src="<?php echo $this->media_storage->getImageURL('uploads/student_images/no_image.png'); ?>" width="100" height="auto">
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td valign="top" style="text-align:left; position: relative; <?php echo "top:" . $certificate->header_height . "px" ?>"><?php echo $certificate->left_header ?></td>
            <td valign="top" style="text-align:center; position: relative; <?php echo "top:" . $certificate->header_height . "px" ?>"><?php echo $certificate->center_header ?></td>
            <td valign="top" style="text-align:right; position: relative; <?php echo "top:" . $certificate->header_height . "px" ?>"><?php echo $certificate->right_header ?></td>
        </tr>
        <tr>
            <td colspan="3" valign="top" style="position: relative;<?php echo "top:" . $certificate->content_height . "px" ?>">
                <p style="font-size: 14px; text-align:center;line-height: normal;"><?php echo $certificate->certificate_text ?></p></td>
        </tr>
        <tr style="">
            <td valign="top" style="text-align:left;position: relative; <?php echo "top:" . $certificate->footer_height . "px" ?>"><?php echo $certificate->left_footer ?></td>
            <td valign="top" style="text-align:center;position: relative; <?php echo "top:" . $certificate->footer_height . "px" ?>"><?php echo $certificate->center_footer ?></td>
            <td valign="top" style="text-align:right;position: relative; <?php echo "top:" . $certificate->footer_height . "px" ?>"><?php echo $certificate->right_footer ?></td>
        </tr>
    </table>
</div>