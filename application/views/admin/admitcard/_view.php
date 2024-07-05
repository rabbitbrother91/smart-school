<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="icon" type="image/png" href="assets/img/s-favican.png">
        <meta http-equiv="X-UA-Compatible" content="" />
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <meta name="theme-color" content="" />
        <style type="text/css">
            *{padding: 0; margin:0;}
            /*            body{padding: 0; margin:0; font-family: arial; color: #000; font-size: 14px; line-height: normal;}*/
            .tableone{}
            .tableone td{padding:5px 10px}
            table.denifittable  {border: 1px solid #999;border-collapse: collapse;}
            .denifittable th {padding: 10px 10px; font-weight: normal;  border-collapse: collapse;border-right: 1px solid #999; border-bottom: 1px solid #999;}
            .denifittable td {padding: 10px 10px; font-weight: bold;border-collapse: collapse;border-left: 1px solid #999;}

            .mark-container{
                width: 1000px;position: relative;z-index: 2; margin: 0 auto; padding: 20px 30px;}

            .tcmybg {
                background:top center;
                background-size: 100% 100%;
                position: absolute;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 1;
            }

        </style>
    </head>
    <body>
        <?php
if ($admitcard->background_img != "") {
    ?>
            <img src="<?php echo $this->media_storage->getImageURL('uploads/admit_card/' . $admitcard->background_img); ?>" class="tcmybg" width="100%" height="100%" />
            <?php
}
?>
        <div class="mark-container">
            <table cellpadding="0" cellspacing="0" width="100%">
                <?php
if ($admitcard->title != "" || $admitcard->heading != "" || $admitcard->left_logo != "") {
    ?>
                    <tr>
                        <td valign="top">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td valign="top" align="center" width="100">
                                        <?php
if ($admitcard->left_logo != "") {
        ?>
                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/admit_card/' . $admitcard->left_logo); ?>" width="100" height="100"/>
                                            <?php
}
    ?>
                                    </td>
                                    <td valign="top">
                                        <table cellpadding="0" cellspacing="0" width="100%">
                                            <?php
if ($admitcard->heading != "") {
        ?>
                                                <tr>
                                                    <td valign="top" style="font-size: 26px; font-weight: bold; text-align: center; text-transform: uppercase; padding-top: 10px;"><?php echo $admitcard->heading; ?></td>
                                                </tr>
                                                <?php
}
    ?>
                                            <tr><td valign="top" height="5"></td></tr>
                                            <?php
if ($admitcard->title != "") {
        ?>
                                                <tr>
                                                    <td valign="top" style="font-size: 20px;text-align: center; text-transform: uppercase; text-decoration: underline;">
                                                        <?php echo $admitcard->title; ?></td>
                                                </tr>
                                                <?php
}
    ?>
                                        </table>
                                    </td>
                                    <td width="100" valign="top" align="center">
                                        <?php
if ($admitcard->right_logo != "") {
        ?>
                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/admit_card/' . $admitcard->right_logo); ?>" width="100" height="100">
                                            <?php
}
    ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?php
}
?>
                <?php
if ($admitcard->exam_name) {
    ?>
                    <tr>
                        <td valign="top" style="text-align: center; text-transform: capitalize; text-decoration: underline; font-weight: bold; padding-top: 5px;">May-June <?php echo date('Y') . " " . $this->lang->line('examinations'); ?> </td>
                    </tr>
                    <?php
}
?>
                <tr><td valign="top" height="10"></td></tr>
                <tr>
                    <td valign="top">
                        <table cellpadding="0" cellspacing="0" width="100%" style="text-transform: uppercase;">
                            <tr>
                                <td valign="top">
                                    <table cellpadding="0" cellspacing="0" width="100%" >
                                        <tr>
                                            <?php
if ($admitcard->is_roll_no) {
    ?>
                                                <td valign="top" width="25%" style="padding-bottom: 10px;"> <?php echo $this->lang->line('roll_number') ?></td>
                                                <td valign="top" width="30%" style="font-weight: bold;padding-bottom: 10px;">161066</td>
                                                <?php
}
?>
                                            <?php
if ($admitcard->is_admission_no) {
    ?>
                                                <td valign="top" width="20%" style="padding-bottom: 10px;"> <?php echo $this->lang->line('admission_no') ?></td>
                                                <td valign="top" width="25%" style="font-weight: bold;padding-bottom: 10px;">18S168375</td>
                                                <?php
}
?>
                                        </tr>
                                        <tr>
                                            <?php
if ($admitcard->is_name) {
    ?>
                                                <td valign="top" style="padding-bottom: 10px;"> <?php echo $this->lang->line('candidates_name'); ?></td>
                                                <td valign="top" style="text-transform: uppercase; font-weight: bold;padding-bottom: 10px;">Edward Thomas</td>
                                                <?php
}
?>
                                            <?php
if ($admitcard->is_class || $admitcard->is_section) {
    ?>
                                                <td valign="top" style="padding-bottom: 10px;"> <?php echo $this->lang->line('class'); ?></td>
                                                <td valign="top" style="text-transform: uppercase; font-weight: bold;padding-bottom: 10px;">
                                                    <?php
if ($admitcard->is_class && $admitcard->is_section) {
        ?>
                                                        1 (A)
                                                        <?php
} elseif ($admitcard->is_class) {
        ?>
                                                        1
                                                        <?php
} elseif ($admitcard->is_section) {
        ?>
                                                        (A)
                                                        <?php
}
    ?>
                                                </td>
                                                <?php
}
?>
                                        </tr>
                                        <tr>
                                            <?php
if ($admitcard->is_dob) {
    ?>
                                                <td valign="top" style="padding-bottom: 10px;"><?php echo $this->lang->line('date_of_birth'); ?></td>
                                                <td valign="top" style="text-transform: uppercase; font-weight: bold;padding-bottom: 10px;">8/10/2002</td>
                                                <?php
}
?>
                                            <?php
if ($admitcard->is_gender) {
    ?>
                                                <td valign="top" style="padding-bottom: 10px;"> <?php echo $this->lang->line('gender'); ?></td>
                                                <td valign="top" style="text-transform: uppercase; font-weight: bold;padding-bottom: 10px;"><?php echo $this->lang->line('male'); ?></td>
                                                <?php
}
?>
                                        </tr>
                                        <tr>
                                            <?php
if ($admitcard->is_father_name) {
    ?>
                                                <td valign="top" style="padding-bottom: 10px;"><?php echo $this->lang->line('fathers_name'); ?></td>
                                                <td valign="top" style="text-transform: uppercase; font-weight: bold;padding-bottom: 10px;">Olivier Thomas</td>
                                                <?php
}
?>
                                            <?php
if ($admitcard->is_mother_name) {
    ?>
                                                <td valign="top" style="padding-bottom: 10px;"><?php echo $this->lang->line('mothers_name'); ?></td>
                                                <td valign="top" style="text-transform: uppercase; font-weight: bold;padding-bottom: 10px;">Caroline Thomas</td>
                                                <?php
}
?>
                                        </tr>
                                        <tr>
                                            <?php
if ($admitcard->is_address) {
    ?>
                                                <td valign="top" style="padding-bottom: 10px;"><?php echo $this->lang->line('address'); ?></td>
                                                <td colspan="3" valign="top" style="text-transform: uppercase; font-weight: bold;padding-bottom: 10px;">56 Main Street, Suite 3, Brooklyn, NY 11210-0000</td>
                                                <?php
}
?>
                                        </tr>
                                        <?php
if ($admitcard->school_name != "") {
    ?>
                                            <tr>
                                                <td valign="top" style="padding-bottom: 10px;"><?php echo $this->lang->line('school_name') ?></td>
                                                <td valign="top" colspan="3" style="text-transform: uppercase; font-weight: bold;padding-bottom: 10px;"><?php echo $admitcard->school_name; ?></td>
                                            </tr>
                                            <?php
}
?>
                                        <?php
if ($admitcard->exam_center != "") {
    ?>
                                            <tr>
                                                <td valign="top" style="padding-bottom: 10px;"><?php echo $this->lang->line('exam_center'); ?></td>
                                                <td valign="top" colspan="3" style="text-transform: uppercase; font-weight: bold;padding-bottom: 10px;"> <?php echo $admitcard->exam_center; ?></td>
                                            </tr>
                                            <?php
}
?>
                                    </table>
                                </td>
                                <?php
if ($admitcard->is_photo) {
    ?>
                                    <td valign="top" width="25%" align="right">
                                        <img src="<?php echo $this->media_storage->getImageURL('uploads/student_images/no_image.png'); ?>" width="100" height="100">
                                    </td>
                                    <?php
}
?>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><td valign="top" height="10"></td></tr>
                <tr>
                    <td valign="top">
                        <table cellpadding="0" cellspacing="0" width="100%" class="denifittable">
                            <tr>
                                <th valign="top" style="text-align: center; text-transform: uppercase;"><?php echo $this->lang->line('theory_exam_date_time'); ?></th>
                                <th valign="top" style="text-align: center; text-transform: uppercase;"><?php echo $this->lang->line('paper_code') ?></th>
                                <th valign="top" style="text-align: center; text-transform: uppercase;"><?php echo $this->lang->line('subject'); ?></th>
                                <th valign="top" style="text-align: center; text-transform: uppercase;"><?php echo $this->lang->line('obtained_by_student') ?></th>
                            </tr>
                            <tr>
                                <td valign="top" style="text-align: center;">03-Jun-<?php echo date('Y') ?> 2 P.M. - 5 P.M.</td>
                                <td style="text-align: center;text-transform: uppercase;">7713</td>
                                <td style="text-align: center;text-transform: uppercase;">Mathematics</td>
                                <td style="text-align: center;text-transform: uppercase;">TH</td>
                            </tr>
                            <tr>
                                <td valign="top" style="text-align: center;">03-Jun-<?php echo date('Y') ?> 2 P.M. - 5 P.M.</td>
                                <td style="text-align: center;text-transform: uppercase;">7714</td>
                                <td style="text-align: center;text-transform: uppercase;">Sceince</td>
                                <td style="text-align: center;text-transform: uppercase;">TH</td>
                            </tr>
                            <tr>
                                <td valign="top" style="text-align: center;">03-Jun-<?php echo date('Y') ?> 2 P.M. - 5 P.M.</td>
                                <td style="text-align: center;text-transform: uppercase;">7715</td>
                                <td style="text-align: center;text-transform: uppercase;">English</td>
                                <td style="text-align: center;text-transform: uppercase;">TH</td>
                            </tr>
                            <tr>
                                <td valign="top" style="text-align: center;">03-Jun-<?php echo date('Y') ?> 2 P.M. - 5 P.M.</td>
                                <td style="text-align: center;text-transform: uppercase;">7716</td>
                                <td style="text-align: center;text-transform: uppercase;">Social Science</td>
                                <td style="text-align: center;text-transform: uppercase;">TH</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><td valign="top" height="5"></td></tr>
                <?php
if ($admitcard->content_footer != "") {
    ?>
                    <tr>
                        <td valign="top" style="padding-bottom: 15px; line-height: normal;"> <?php echo htmlspecialchars_decode($admitcard->content_footer); ?></td>
                    </tr>
                    <?php
}
?>
                <tr><td valign="top" height="20px"></td></tr>
                <?php
if ($admitcard->sign != "") {
    ?>
                    <tr>
                        <td align="right" valign="top">
                            <table cellpadding="0" cellspacing="0" width="100%" style="text-align: center;">
                                <tr>
                                    <td valign="top">
                                        <img src="<?php echo $this->media_storage->getImageURL('uploads/admit_card/' . $admitcard->sign); ?>" width="100" height="38"  />

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?php
}
?>
            </table>
        </div>
    </body>
</html>