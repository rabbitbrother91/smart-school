<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
        <title>Example 2</title>
        <link rel="stylesheet" href="style.css" media="all" />
        <style type="text/css">

            .clearfix:after {
                content: "";
                display: table;
                clear: both;
            }

            a {
                color: #0087C3;
                text-decoration: none;
            }

            body {
                position: relative;
                width: 90%;
                height: 29.7cm;
                margin: 0 auto;
                color: #555555;
                background: #FFFFFF;

                font-size: 14px;
                font-family: SourceSansPro;
            }
            header {
                padding: 10px 0;
                margin-bottom: 20px;
                border-bottom: 1px solid #AAAAAA;
            }
            #details {
                margin-bottom: 0px;
            }

            #client {
                padding-left: 6px;
                border-left: 6px solid #0087C3;
                float: left;
            }
            #client .to {
                color: #777777;
            }

            h2.name {
                font-size: 1.4em;
                font-weight: normal;
                margin: 0;
            }

            #invoice {
                float: right;
                text-align: right;
            }

            #invoice h1 {
                color: #0087C3;
                font-size: 2.4em;
                line-height: 1em;
                font-weight: normal;
                margin: 0  0 10px 0;
            }

            #invoice .date {
                font-size: 1.1em;
                color: #777777;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0;
                margin-bottom: 20px;
            }

            table th{
                padding: 20px;
                background: #EEEEEE;
                text-align: center;
                border-bottom: 1px solid #FFFFFF;
            }
            
            table td {
                padding: 10px;
                background: #EEEEEE;
                text-align: center;
                border-bottom: 1px solid #FFFFFF;
            }

            table th {
                white-space: nowrap;
                font-weight: normal;
            }

            table td {
                text-align: right;
                padding: 2px;
            }

            table td h3{
                color: #0096D3;
                font-size: 1.2em;
                font-weight: normal;
                margin: 0 0 0.2em 0;
            }

            table .no {
                color: #555555;
                font-size: 1.2em;
            }

            table .desc {
                text-align: center;
            }

            table .unit {
                background: #DDDDDD;
            }
            
            table .unit {
                background: #DDDDDD;
            }

            table .qty {

            }

            table .total {
                color: #555555;

            }

            table td.unit,
            table td.qty,
            table td.total {
                font-size: 1.2em;
            }

            table tbody tr:last-child td {
                border: none;
            }

            table tfoot td {
                padding: 10px 20px;
                background: #FFFFFF;
                border-bottom: none;
                font-size: 1.2em;
                white-space: nowrap;
                border-top: 1px solid #AAAAAA;
            }

            table tfoot tr:first-child td {
                border-top: none;
            }

            table tfoot tr:last-child td {
                color: #57B223;
                font-size: 1.4em;
                border-top: 1px solid #57B223;
            }

            table tfoot tr td:first-child {
                border: none;
            }

            #thanks{
                font-size: 2em;
                margin-bottom: 50px;
            }

            #notices{
                padding-left: 6px;
                border-left: 6px solid #0087C3;
            }

            #notices .notice {
                font-size: 1.2em;
            }

            footer {
                color: #777777;
                width: 100%;
                height: 30px;
                position: absolute;
                bottom: 0;
                border-top: 1px solid #AAAAAA;
                padding: 8px 0;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <header class="clearfix">
            <table style="background: #ffffff !important;">
                <tr>
                    <td style="background: #ffffff !important;text-align: left; margin-top: 8px;">
                        <img style="height:70px; " src="<?php echo base_url(); ?>/images/<?php echo $settinglist[0]['image']; ?>">
                    </td>
                    <td style="background: #ffffff !important;float: right;
                        text-align: right;">                      
                        <h2 class="name"><?php echo $settinglist[0]['name']; ?></h2>
                        <div><?php echo $settinglist[0]['address']; ?></div>
                        <div><?php echo $settinglist[0]['phone']; ?></div>
                        <div><a href="mailto:company@example.com"><?php echo $settinglist[0]['email']; ?></a></div>
                    </td>
                </tr>
            </table>
        </header>
        <main>
            <div id="details" class="clearfix">
                <h3 style="text-align:center"> <?php echo $this->lang->line('marks_register'); ?></h3>
                <table cellpadding="0" cellspacing="0" style="width:100%; border:0; background:none; margin:0 0 2px 0; ">
                    <tr>
                        <td style="padding:20px;font-size: .65em; font-weight:bold;text-align:left; background:#fff;">
                            <b><?php echo $this->lang->line('exam'); ?>: <?php echo $exam_arrylist['name']; ?></b></td>
                        <td style="padding:20px;font-size: .65em; font-weight:bold;text-align:left;background:#fff;">
                            <b><?php echo $this->lang->line('class'); ?>:
                                <?php echo $class[0]['class'] . "(" . $class[0]['section'] . ")" ?>
                            </b></td>
                        <td style="padding:20px;font-size: .65em; font-weight:bold;text-align:left;background:#fff;">
                            <b style="text-align:center"><?php echo $this->lang->line('date'); ?>: <?php echo date($this->customlib->getSchoolDateFormat()); ?></b></td>
                    </tr>
                </table>  
            </div>
            <div id="details" class="clearfix">             
                <?php
                if (isset($examSchedule['status'])) {
                    ?>
                    <?php
                    if ($examSchedule['status'] == "yes") {
                        ?>
                        <form role="form" id="" class="" method="post" action="<?php echo site_url('admin/mark/create') ?>">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                            <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
                            <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="no">
                                                <?php echo $this->lang->line('admission_no'); ?>
                                            </th>
                                            <th class="no">
                                                <?php echo $this->lang->line('roll_no'); ?>
                                            </th>
                                            <th class="no">
                                                <?php echo $this->lang->line('student'); ?>
                                            </th>
                                            <th class="no">
                                                <?php echo $this->lang->line('father_name'); ?>
                                            </th>
                                            <?php
                                            $s = 0;
                                            if ($examSchedule['status'] == "yes") {
                                                foreach ($examSchedule['result'] as $key => $st) {
                                                    if ($s == 0) {
                                                        foreach ($st['exam_array'] as $key => $exam_schedule) {
                                                            ?>
                                                            <th class="no">
                                                                <?php
                                                                echo $exam_schedule['exam_name'] . "<br/> (" . substr($exam_schedule['exam_type'], 0, 2) . ": " . $exam_schedule['passing_marks'] . "/" . $exam_schedule['full_marks'] . ") ";
                                                                ?>
                                                            </th>
                                                            <?php
                                                        }
                                                    }
                                                    $s++;
                                                }
                                            } else {
                                                ?>
                                                <?php
                                            }
                                            ?>
                                            <th class="no"><?php echo $this->lang->line('grand_total'); ?></th>
                                            <th class="no"><?php echo $this->lang->line('percent') . ' (%)'; ?></th>
                                            <th class="no"><?php echo $this->lang->line('result'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $s = 0;
                                        foreach ($examSchedule['result'] as $key => $student) {
                                            ?>
                                        <input type="hidden" name="student[]" value="<?php echo $student['student_id'] ?>">
                                        <?php
                                        if (!empty($student['exam_array'])) {
                                            if ($s == 0) {
                                                foreach ($student['exam_array'] as $key => $exam_schedule) {
                                                    ?>
                                                    <input type="hidden" name="exam_schedule[]" value="<?php echo $exam_schedule['exam_schedule_id'] ?>">
                                                    <?php
                                                }
                                            }
                                        } else {
                                            ?>

                                            <?php
                                        }
                                        $s++;
                                    }
                                    ?>
                                    <?php
                                    foreach ($examSchedule['result'] as $key => $student) {
                                        $total_marks = 0;
                                        $obtain_marks = 0;
                                        $result = "Pass";
                                        ?>
                                        <tr>
                                            <td class="qty"  style="text-align: center;">
                                                <?php echo $student['admission_no'] ?>
                                            </td>
                                            <td class="qty"  style="text-align: center;">
                                                <?php echo $student['roll_no'] ?>
                                            </td>
                                            <td class="qty"  style="text-align: center;">
                                                <?php echo $student['firstname'] . " " . $student['lastname']; ?>
                                            </td>
                                            <td class="qty"  style="text-align: center;">
                                                <?php echo $student['father_name'] ?>
                                            </td>
                                            <?php
                                            if (!empty($student['exam_array'])) {
                                                count($student['exam_array']);
                                                $s = 0;
                                                foreach ($student['exam_array'] as $key => $exam_schedule) {
                                                    $total_marks = $total_marks + $exam_schedule['full_marks'];
                                                    ?>
                                                    <td class="qty"  style="text-align: center;">
                                                        <?php
                                                        if ($exam_schedule['attendence'] == "pre") {
                                                            echo $get_marks_student = $exam_schedule['get_marks'];
                                                            $passing_marks_student = $exam_schedule['passing_marks'];
                                                            if ($result == "Pass") {
                                                                if ($get_marks_student < $passing_marks_student) {
                                                                    $result = "Fail";
                                                                }
                                                            }
                                                            $obtain_marks = $obtain_marks + $exam_schedule['get_marks'];
                                                        } else {
                                                            $result = "Fail";
                                                            $s++;
                                                            echo ($exam_schedule['attendence']);
                                                        }
                                                        ?>
                                                    </td>
                                                    <?php
                                                }
                                                if ($s == count($student['exam_array'])) {
                                                    $obtain_marks = 0;
                                                }
                                                ?>
                                                <td class="qty"  style="text-align: center;"> <?php echo $obtain_marks . " /" . $total_marks; ?> </td>
                                                <td class="qty"  style="text-align: center;"> <?php
                                                    $per = $obtain_marks * 100 / $total_marks;
                                                    echo number_format($per, 2, '.', '');
                                                    ?>
                                                </td>
                                                <th><?php
                                                    if ($result == "Pass") {

                                                        echo "<b class='text text-success'>";
                                                    } else {

                                                        echo "<b class='text text-danger'>";
                                                    }
                                                    echo $result;
                                                    echo "<b/>";
                                                    ?></th>
                                                <?php
                                            } else {
                                                ?>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                        <?php
                    } else {
                        ?>
                        <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                        <?php
                    }
                    ?>
                    <?php
                } else {
                    
                }
                ?>
            </div>
        </main>
        <footer>        
        </footer>
    </body>
</html>