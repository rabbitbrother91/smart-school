<?php
if (isset($resultlist)) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
            <title><?php echo $this->lang->line('example'); ?> 2</title>
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
                    margin-bottom: 7px;
                    border-bottom: 1px solid #AAAAAA;
                }

                #details {
                    margin-bottom: 10px;
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
                    padding: 8px;
                    background: #EEEEEE;
                    text-align: center;
                    border-bottom: 1px solid #FFFFFF;
                }

                table td {
                    padding: 2px;
                    background: #EEEEEE;
                    text-align: center;
                    border-bottom: 1px solid #FFFFFF;
                }
                table tbody{
                    font-size: 11px;
                }

                table th {
                    white-space: nowrap;
                    font-weight: normal;
                }

                table td {
                    text-align: right;
                }

                table td h3{
                    color: #0096D3;
                    font-size: 1.2em;
                    font-weight: normal;
                    margin: 0 0 0.2em 0;
                }

                table .no {
                    color: #555555;
                    font-size: 14px;
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
                    <h2 style="text-align: center;margin: 4px;">
                        <th><?php echo $this->lang->line('student_record'); ?></th></h2>
                    <div  style="text-align: left;">
                        <b>
                            <?php echo $this->lang->line('class'); ?>: <?php echo $class['class']; ?></b><br/>
                        <?php if (isset($section)) {
                            ?>
                            <b> <?php echo $this->lang->line('section'); ?>: <?php echo $section[0]['section']; ?></b><br/>
                        <?php }
                        ?>
                        <?php if (isset($category)) {
                            ?>
                            <b> <?php echo $this->lang->line('category'); ?>: <?php echo $category['category']; ?></b><br/>
                        <?php }
                        ?>
                        <?php if (isset($rte)) {
                            ?>
                            <b> <?php echo $this->lang->line('rte'); ?>: <?php echo $rte; ?></b><br/>
                        <?php }
                        ?>
                        <?php if (isset($gender)) {
                            ?>
                            <b> <?php echo $this->lang->line('gender'); ?>: <?php echo $gender; ?></b><br/>
                        <?php }
                        ?>
                    </div>
                    <div  style="text-align: right;">  <th><?php echo $this->lang->line('date'); ?></th>: <?php echo date($this->customlib->getSchoolDateFormat()); ?></div>                  
                </div>
                <div id="details" class="clearfix">                   
                    <table>
                        <thead>
                            <tr>
                                <th class="no">  <?php echo $this->lang->line('section'); ?></th>
                                <th class="no">  <?php echo $this->lang->line('admission_no'); ?></th>
                                <th class="no">  <?php echo $this->lang->line('student_name'); ?></th>
                                <th class="no">  <?php echo $this->lang->line('father_name'); ?></th>
                                <th class="no">  <?php echo $this->lang->line('date_of_birth'); ?></th>
                                <th class="no">  <?php echo $this->lang->line('mobile_no'); ?></th>
                                <th class="no">  <?php echo $this->lang->line('local_identification_no'); ?></th>
                                <th class="no">  <?php echo $this->lang->line('adhar_card_no'); ?></th>
                                <th class="no">  <?php echo $this->lang->line('rte'); ?></th>
                            </tr>
                        </thead>
                        <?php
                        $row_count = 1;
                        foreach ($resultlist as $key => $student) {
                            ?>
                            <tr>
                                <td class="desc" style="font-size: 11px;"><h3><?php echo $student['section']; ?></h3></td>
                                <td class="desc" style="font-size: 11px;"><h3><?php echo $student['admission_no']; ?></h3></td>
                                <td class="qty" style="text-align: left;font-size: 11px;"><?php echo $student['firstname'] . " " . $student['lastname']; ?></td>
                                <td class="qty"  style="text-align: left;font-size: 11px;"><?php echo $student['father_name']; ?></td>
                                <td  class="qty"  style="text-align: center;font-size: 11px;"><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob'])); ?></td>
                                <td class="qty"  style="text-align: center;font-size: 11px;"><?php echo $student['mobileno']; ?></td>
                                <td class="qty" style="text-align: center;font-size: 11px;"><?php echo $student['samagra_id']; ?></td>
                                <td class="qty"  style="text-align: center;font-size: 11px;"><?php echo $student['adhar_no']; ?></td>
                                <td class="qty"  style="text-align: center;font-size: 11px;"><?php echo $student['rte']; ?></td>
                            </tr>
                            <?php
                            $row_count++;
                        }
                        ?>
                    </table>
                </div>
            </main>
            <footer>               
            </footer>
        </body>
    </html>
    <?php
} else {
    ?>

    <?php
}
?>
