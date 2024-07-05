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
                font-size: 1.0em;
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
                <h3 style="text-align:center"> <?php echo $this->lang->line('exam_schedule'); ?></h3>
                <table cellpadding="0" cellspacing="0" style="width:100%; border:0; background:none; margin:0 0 2px 0; ">
                    <tr>
                        <td style="padding:20px;font-size: .65em; font-weight:bold;text-align:left; background:#fff;">
                            <b><?php echo $this->lang->line('exam'); ?>: <?php echo $exam['name']; ?></b></td>
                        <td style="padding:20px;font-size: .65em; font-weight:bold;text-align:left;background:#fff;">
                            <b><?php echo $this->lang->line('class'); ?>:
                                <?php
                                ?>
                                <?php echo $class['class'] . "(" . $section[0]['section'] . ")" ?>
                            </b></td>
                        <td style="padding:20px;font-size: .65em; font-weight:bold;text-align:left;background:#fff;">
                            <b style="text-align:center"><?php echo $this->lang->line('date'); ?>: <?php echo date($this->customlib->getSchoolDateFormat()); ?></b></td></tr></table>   
            </div>
            <div id="details" class="clearfix">  
                <form role="form" id="" class="" method="post" action="<?php echo site_url('admin/mark/create') ?>">
                    <?php echo $this->customlib->getCSRF(); ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('subject'); ?></th>
                                    <th><?php echo $this->lang->line('date'); ?></th>
                                    <th><?php echo $this->lang->line('start_time'); ?></th>
                                    <th><?php echo $this->lang->line('end_time'); ?></th>
                                    <th><?php echo $this->lang->line('room'); ?></th>
                                    <th><?php echo $this->lang->line('full_marks'); ?></th>
                                    <th><?php echo $this->lang->line('passing_marks'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($examSchedule as $key => $value) {
                                    ?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo $value['name'] . " (" . substr($value['type'], 0, 2) . ".)"; ?></td>
                                        <td style="text-align: center;"><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($value['date_of_exam'])) ?></td>
                                        <td style="text-align: center;"><?php echo $value['start_to'] ?></td>
                                        <td style="text-align: center;"><?php echo $value['end_from'] ?></td>
                                        <td style="text-align: center;"><?php echo $value['room_no'] ?></td>
                                        <td style="text-align: center;"><?php echo $value['full_marks'] ?></td>
                                        <td style="text-align: center;"><?php echo $value['passing_marks'] ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </main>
        <footer>          
        </footer>
    </body>
</html>