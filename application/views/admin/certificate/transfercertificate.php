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
            body{ font-family: 'arial';}
            .tc-container{width: 100%;position: relative; text-align: center;}
            .tc-container tr td{vertical-align: bottom;}
        </style>
    </head>
    <?php
if (isset($certificateResult) && isset($resultlist)) {
    $name = $resultlist[0]['firstname'] . ' ' . $resultlist[0]['lastname'];
    $dob  = date('d-m-Y', strtotime($resultlist[0]['dob']));
    if (!is_null($resultlist[0]['permanent_address']) && !empty($resultlist[0]['permanent_address'])) {
        $address = $resultlist[0]['permanent_address'];
    } else {
        $address = "Jabalpur Madhya Pradesh";
    }

    $guardian_name  = $resultlist[0]['guardian_name'];
    $admission_no   = $resultlist[0]['admission_no'];
    $roll_no        = $resultlist[0]['roll_no'];
    $class          = $resultlist[0]['class'];
    $section        = $resultlist[0]['section'];
    $gender         = $resultlist[0]['gender'];
    $admission_date = date('d-m-Y', strtotime($resultlist[0]['admission_date']));
    $category       = $resultlist[0]['category'];
    $cast           = $resultlist[0]['cast'];
    $father_name    = $resultlist[0]['father_name'];
    $mother_name    = $resultlist[0]['mother_name'];
    $religion       = $resultlist[0]['religion'];
    $email          = $resultlist[0]['email'];
    $phone          = $resultlist[0]['father_phone'];
    $created_at     = date('d-m-Y', strtotime($resultlist[0]['created_at']));
    $image          = $resultlist[0]['image'];

    $text = $certificateResult[0]->certificate_text;
    preg_match_all("/\[(.*?)\]/", $text, $matches);

    for ($i = 0; $i < count($matches[0]); $i++) {
        if ($matches[0][$i] == '[name]') {

            $text = str_replace($matches[0][$i], $name, $text);
        }

        if ($matches[0][$i] == '[dob]') {
            $text = str_replace($matches[0][$i], $dob, $text);
        }

        if ($matches[0][$i] == '[present_address]') {
            $text = str_replace($matches[0][$i], $address, $text);
        }

        if ($matches[0][$i] == '[guardian]') {
            $text = str_replace($matches[0][$i], $guardian_name, $text);
        }

        if ($matches[0][$i] == '[created_at]') {
            $text = str_replace($matches[0][$i], $admission_date, $text);
        }

        if ($matches[0][$i] == '[admission_no]') {
            $text = str_replace($matches[0][$i], $admission_no, $text);
        }

        if ($matches[0][$i] == '[roll_no]') {
            $text = str_replace($matches[0][$i], $roll_no, $text);
        }

        if ($matches[0][$i] == '[class]') {
            $text = str_replace($matches[0][$i], $class, $text);
        }

        if ($matches[0][$i] == '[section]') {
            $text = str_replace($matches[0][$i], $section, $text);
        }

        if ($matches[0][$i] == '[gender]') {
            $text = str_replace($matches[0][$i], $gender, $text);
        }

        if ($matches[0][$i] == '[admission_date]') {
            $text = str_replace($matches[0][$i], $admission_date, $text);
        }

        if ($matches[0][$i] == '[category]') {
            $text = str_replace($matches[0][$i], $category, $text);
        }

        if ($matches[0][$i] == '[cast]') {
            $text = str_replace($matches[0][$i], $cast, $text);
        }

        if ($matches[0][$i] == '[father_name]') {
            $text = str_replace($matches[0][$i], $father_name, $text);
        }

        if ($matches[0][$i] == '[mother_name]') {
            $text = str_replace($matches[0][$i], $mother_name, $text);
        }

        if ($matches[0][$i] == '[religion]') {
            $text = str_replace($matches[0][$i], $religion, $text);
        }

        if ($matches[0][$i] == '[email]') {
            $text = str_replace($matches[0][$i], $email, $text);
        }

        if ($matches[0][$i] == '[phone]') {
            $text = str_replace($matches[0][$i], $phone, $text);
        }
    }
    ?>
        <body>
            <div class="tc-container">
                <img src="<?php echo base_url('uploads/certificate/'); ?><?php echo $certificateResult[0]->background_image ?>" width="100%" height="100%" />
                <table width="100%" cellspacing="0" cellpadding="0" border="">

                    <tr style="position:absolute; margin-left: auto;margin-right: auto;left: 0;right: 0;  width:<?php echo $certificateResult[0]->content_width; ?>px; top:<?php echo $certificateResult[0]->enable_image_height; ?>px">
                        <td  valign="top" style="position: absolute;right: 0;">
                            <?php if ($certificateResult[0]->enable_student_image == 1) {?>
                                <img src="<?php echo base_url() ?>/<?php echo $image; ?>" width="100" height="auto">
                            <?php }?>
                        </td>
                    </tr>
                    <tr style="position:absolute; margin-left: auto;margin-right: auto;left: 0;right: 0;  width:<?php echo $certificateResult[0]->content_width; ?>px; top:<?php echo $certificateResult[0]->header_height; ?>px">
                        <td valign="top" style="width:<?php echo $certificateResult[0]->content_width; ?>px;font-size: 18px; text-align:left;position:relative;"><?php echo $certificateResult[0]->left_header; ?></td>
                        <td valign="top" style="width:<?php echo $certificateResult[0]->content_width; ?>px;font-size: 18px; text-align:center; position:relative; "><?php echo $certificateResult[0]->center_header; ?></td>
                        <td valign="top" style="width:<?php echo $certificateResult[0]->content_width; ?>px;font-size: 18px; text-align:right;position:relative;"><?php echo $certificateResult[0]->right_header; ?></td>
                    </tr>
                    <tr style="position:absolute;margin-left: auto;margin-right: auto;left: 0;right: 0; width:<?php echo $certificateResult[0]->content_width; ?>px; display: block; top:<?php echo $certificateResult[0]->content_height; ?>px;">
                        <td colspan="3" valign="top" align="center"><p style="font-size: 16px;position: relative;text-align:center; margin:0 auto; width: 100%; left:auto; right:0;"><?php echo $text; ?></p>
                        </td>
                    </tr>
                    <tr style="position:absolute; margin-left: auto;margin-right: auto;left: 0;right: 0;  width:<?php echo $certificateResult[0]->content_width; ?>px; top:<?php echo $certificateResult[0]->footer_height; ?>px">
                        <td valign="top" style="width:<?php echo $certificateResult[0]->content_width; ?>px; font-size:18px;text-align:left;"><?php echo $certificateResult[0]->left_footer; ?></td>
                        <td valign="top" style="width:<?php echo $certificateResult[0]->content_width; ?>px; font-size:18px;text-align:center;"><?php echo $certificateResult[0]->center_footer; ?></td>
                        <td valign="top" style="width:<?php echo $certificateResult[0]->content_width; ?>px;font-size:18px;text-align:right;"><?php echo $certificateResult[0]->right_footer; ?></td>
                    </tr>
                </table>
            </div>
        <?php }?>
    </body>
</html>
