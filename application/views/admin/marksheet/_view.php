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
            .body{padding: 0; margin:0; font-family: arial; color: #000; font-size: 14px; line-height: normal;}
            .tableone{}
            .tableone td{border:1px solid #000; padding: 5px 0}
            .denifittable th{border-top: 1px solid #999;}
            .denifittable th,
            .denifittable td {border-bottom: 1px solid #999;
                              border-collapse: collapse;border-left: 1px solid #999;}
            .denifittable tr th {padding: 10px 10px; font-weight: bold;}
            .denifittable tr td {padding: 10px 10px; font-weight: bold;}
            .tcmybg {
                background:top center;
                background-size: 100% 100%;
                position: absolute;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 0;
            }
        </style>
    </head>
    <body class="body">       
        
        <?php
if ($marksheet->background_img != "") {
    ?>
            <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $marksheet->background_img); ?>" class="tcmybg" width="100%" height="100%" />
            <?php
}
?>

        <div style="width: 100%; margin: 0 auto; border:1px solid #000; padding: 10px 5px 5px;position: relative;">
            <?php if($marksheet->header_image){ ?>
        <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $marksheet->header_image); ?>" width="100%" height="300px;">
        <?php } ?>

            <table cellpadding="0" cellspacing="0" width="100%">
                <?php
if ($marksheet->heading != "" || $marksheet->title != "") {
    ?>
                    <tr>
                        <td valign="top">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <?php
if ($marksheet->heading != "") {
        ?>
                                    <tr>
                                        <td valign="top" style="font-size: 42px; font-weight: bold; text-align: center;"><?php echo $marksheet->heading; ?></td>
                                    </tr>
                                    <?php
}
    ?>
                                <?php
if ($marksheet->title != "") {
        ?>
                                    <tr>
                                        <td valign="top" style="font-size: 20px; font-weight: 900; text-align: center; text-transform: uppercase;"><?php echo $marksheet->title; ?></td>
                                    </tr>
                                    <?php
}
    ?>
                                <tr><td valign="top" height="5"></td></tr>
                            </table>
                        </td>
                    </tr>
                    <?php
}
?>
                <tr>
                    <td valign="top">
                        <table cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td width="100" valign="top" align="center" style="padding-left: 0px;">
                                    <?php
if ($marksheet->left_logo != "") {
    ?>
                                        <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $marksheet->left_logo); ?>" width="100" height="100">
                                        <?php
}
?>
                                </td>
                                <td valign="top">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <?php
if ($marksheet->exam_name != "") {
    ?>
                                            <tr>
                                                <td valign="top" style="font-size: 20px; font-weight: bold; text-align: center; text-transform: uppercase;">
                                                    <?php echo $marksheet->exam_name; ?></td>
                                            </tr>
                                            <?php
}
?>
                                        <tr><td valign="top" height="5"></td></tr>
                                        <?php
if ($marksheet->exam_session) {
    ?>
                                            <tr>
                                                <td style="text-align: center; font-weight: bold" valign="top">
                                                    2021
                                                </td>
                                            </tr>
                                            <?php
}
?>
                                    </table>
                                </td>
                                <td width="100" valign="top" align="right" style="padding-right: 0px;">
                                    <?php
if ($marksheet->right_logo != "") {
    ?>
                                        <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $marksheet->right_logo); ?>" width="100" height="100">
                                        <?php
}
?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><td valign="top" height="10"></td></tr>
                <tr>
                    <td valign="top">
                        <table cellpadding="0" cellspacing="0" width="100%" class="">
                            <tr>
                                <td valign="top">
                                    <table cellpadding="0" cellspacing="0" width="100%" class="denifittable">
                                        <tr>
                                            <?php
if ($marksheet->is_admission_no) {
    ?>
                                                <th valign="top" style="text-align: center; text-transform: uppercase;">
                                                    <?php echo $this->lang->line('admission_no') ?></th>
                                                <?php
}
?>

                                            <?php
if ($marksheet->is_roll_no) {
    ?>
                                                <th valign="top" style="text-align: center; text-transform: uppercase; border-right:1px solid #999"><?php echo $this->lang->line('roll_number') ?></th>
                                                <?php
}
?>

                                        </tr>
                                        <tr>
                                            <?php
if ($marksheet->is_admission_no) {
    ?>
                                                <td valign="" style="text-transform: uppercase;text-align: center;">XXXXXX</td>
                                                <?php
}
?>
                                            <?php
if ($marksheet->is_roll_no) {
    ?>
                                                <td valign="" style="text-transform: uppercase;text-align: center;border-right:1px solid #999">XXXXXX</td>
                                                <?php
}
?>
                                        </tr>
                                        <tr>
                                            <td valign="top" colspan="5" style="text-align: center; text-transform: uppercase; border:0">

                                                <?php echo $this->lang->line('certificated_that') ?></td>
                                        </tr>
                                    </table>
                                </td>
                                <?php
if ($marksheet->is_photo) {
    ?>
                                    <td width="100" valign="top" align="center" style="padding-left: 5px;">
                             <img src="<?php echo $this->media_storage->getImageURL('uploads/student_images/no_image.png'); ?>" width="100" height="100" />
                                    </td>
                                    <?php
}
?>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><td valign="top" height="5"></td></tr>
                <tr>
                    <td valign="top">
                        <table cellpadding="0" cellspacing="0" width="100%" class="">
                            <?php
if ($marksheet->is_name) {
    ?>
                                <tr>
                                    <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('name_prefix'); ?><span style="padding-left: 30px; font-weight: bold;">Reeta singh</span></td>
                                </tr>
                                <?php
}
?>
                            <?php
if ($marksheet->is_father_name) {
    ?>
                                <tr>
                                    <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"> <?php echo $this->lang->line('marksheet_father_name') ?><span style="padding-left: 30px; font-weight: bold;">Mangu singh</span></td>
                                </tr>
                                <?php
}
?>
                            <?php
if ($marksheet->is_mother_name) {
    ?>
                                <tr>
                                    <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"> <?php echo $this->lang->line('exam_mother_name'); ?><span style="padding-left: 30px; font-weight: bold;">sombati singh</span></td>
                                </tr>
                                <?php
}
?>
                            <?php
if ($marksheet->is_dob) {
    ?>
                                <tr>
                                    <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"> <?php echo $this->lang->line('date_of_birth'); ?><span style="padding-left: 30px; font-weight: bold;">12-01-2022</span></td>
                                </tr>
                                <?php
}
?>
                            <?php
if ($marksheet->is_class || $marksheet->is_section) {
    ?>
                                <tr>
                                    <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"> <?php echo $this->lang->line('class'); ?><span style="padding-left: 30px; font-weight: bold;">
                                            <?php
if ($marksheet->is_class && $marksheet->is_section) {
        ?>
                                                1 (A)
                                                <?php
} elseif ($marksheet->is_class) {
        ?>
                                                1
                                                <?php
} elseif ($marksheet->is_section) {
        ?>
                                                (A)
                                                <?php
}
    ?>
                                        </span></td>
                                </tr>
                                <?php
}
?>
                            <?php
if ($marksheet->school_name != "") {
    ?>
                                <tr>
                                    <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"> <?php echo $this->lang->line('school_name'); ?><span style="padding-left: 30px; font-weight: bold;"><?php echo $marksheet->school_name; ?></span></td>
                                </tr>
                                <?php
}
?>
                            <?php
if ($marksheet->exam_center != "") {
    ?>
                                <tr>
                                    <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('exam_center'); ?><span style="text-transform: uppercase; padding-top: 15px; font-weight: bold; padding-bottom: 20px; padding-left: 30px;"><?php echo $marksheet->exam_center; ?></span></td>
                                </tr>
                                <?php
}
?>
                            <?php
if ($marksheet->content != "") {
    ?>
                                <tr>
                                    <td valign="top" style="text-transform: uppercase; padding-bottom: 15px; line-height: normal;"> <?php echo $marksheet->content; ?></td>
                                </tr>
                                <?php
}
?>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <table cellpadding="0" cellspacing="0" width="100%" class="denifittable" style="text-align: center; text-transform: uppercase;">
                            <tr>
                                <th valign="middle" width="35%"><?php echo $this->lang->line('subjects') ?></th>
                                <th valign="middle" style="text-align: center;"><?php echo $this->lang->line('max_marks'); ?></th>
                                <th valign="middle" style="text-align: center;"><?php echo $this->lang->line('min_marks'); ?></th>
                                <th valign="top" style="text-align: center;"><?php echo $this->lang->line('marks_obtained'); ?></th>
                                <th valign="middle" style="border-right:1px solid #999; text-align: center;"><?php echo $this->lang->line('remarks') ?></th>
                            </tr>
                            <tr>
                                <td valign="top" style="text-align: left;">Hindi [special]</td>
                                <td valign="top" style="text-align: center;">100</td>
                                <td valign="top" style="text-align: center;">33</td>
                                <td valign="top" style="text-align: center;">085</td>
                                <td valign="top" style="text-align: center;border-right:1px solid #999;">Distin</td>
                            </tr>
                            <tr>
                                <td valign="top" style="text-align: left;">English [General]</td>
                                <td valign="top" style="text-align: center;">100</td>
                                <td valign="top" style="text-align: center;">33</td>
                                <td valign="top" style="text-align: center;">051</td>
                                <td valign="top" style="text-align: center;border-right:1px solid #999"></td>
                            </tr>
                            <tr>
                                <td valign="top" style="text-align: left;">Physics</td>
                                <td valign="top" style="text-align: center;">100</td>
                                <td valign="top" style="text-align: center;">25</td>
                                <td valign="top" style="text-align: center;">066</td>
                                <td valign="top" style="text-align: center;border-right:1px solid #999"></td>
                            </tr>
                            <tr>
                                <td valign="top" style="text-align: left;">Chemistry</td>
                                <td valign="top" style="text-align: center;">100</td>
                                <td valign="top" style="text-align: center;">027</td>
                                <td valign="top" style="text-align: center;">049</td>
                                <td valign="top" style="text-align: center;border-right:1px solid #999"></td>
                            </tr>
                            <tr>
                                <td valign="top" style="text-align: left;">Mathematics</td>
                                <td valign="top" style="text-align: center;">100</td>
                                <td valign="top" style="text-align: center;">33</td>
                                <td valign="top" style="text-align: center;">033</td>
                                <td valign="top" style="text-align: center;border-right:1px solid #999"></td>
                            </tr>
                            <tr>
                                <td valign="top"></td>
                                <td valign="top" colspan="0" style="border-left:0">500</td>
                                <td valign="top" colspan="0"><?php echo $this->lang->line('grand_total'); ?></td>
                                <td valign="top" style="text-align: center;">284</td>
                                <td valign="top" style="text-align: center;border-right:1px solid #999"></td>
                            </tr>
                            <tr>
                                <td valign="top" colspan="5" width="20%" style="font-weight: normal; text-align: left; border-right: 1px solid #999;"><?php echo $this->lang->line('grand_total_in_words') ?>: <span style="text-align: left;font-weight: bold; padding-left: 30px;">Two hundred eighty four</span></td>
                            </tr>
                            <?php
if ($marksheet->is_division) {
    ?>
                                <tr>
                                    <td valign="top" colspan="5" width="20%" style="font-weight: normal; text-align: left; border-top:0;border-right: 1px solid #999;"><?php echo $this->lang->line('result'); ?><span style="text-align: left;font-weight: bold; padding-left: 30px;"><?php echo $this->lang->line('pass_in_second_division'); ?></span></td>
                                </tr>
                                <?php
}
?>
                        </table>
                    </td>
                </tr>
                <tr><td valign="top" height="10"></td></tr>
                <?php
if ($marksheet->date != "") {
    ?>
                    <tr>
                        <td valign="top" style="font-weight: bold; padding-left: 30px; padding-top: 10px;"><?php echo $marksheet->date; ?></td>
                    </tr>
                    <?php
}
?>
                <tr><td valign="top" height="30"></td></tr>
                <?php
if ($marksheet->content_footer != "") {
    ?>
                    <tr>
                        <td valign="top" style="text-transform: uppercase; padding-bottom: 15px; line-height: normal;"> <?php echo $marksheet->content_footer; ?></td>
                    </tr>
                    <?php
}
?>
                <tr>
                    <td valign="top">
                        <table cellpadding="0" cellspacing="0" width="100%" class="">
                            <tr>
                                <td valign="bottom" style="font-size: 12px;">
                                </td>
                                <?php
if ($marksheet->left_sign != "") {
    ?>
                                    <td valign="bottom" align="center" style="text-transform: uppercase;">
                                        <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $marksheet->left_sign); ?>"  width="100" height="50">
                                    </td>
                                    <?php
}
?>
                                <?php
if ($marksheet->middle_sign != "") {
    ?>
                                    <td valign="bottom" align="center" style="text-transform: uppercase;">

                                        <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $marksheet->middle_sign); ?>" width="100" height="50">
                                    </td>
                                    <?php
}
?>
                                <?php
if ($marksheet->right_sign != "") {
    ?>
                                    <td valign="bottom" align="center" style="text-transform: uppercase;">
                                        <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $marksheet->right_sign); ?>" width="100" height="50">
                                    </td>
                                    <?php
}
?>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><td valign="top" height="20"></td></tr>
            </table>
        </div>
    </body>
</html>