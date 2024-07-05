<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<style type="text/css">
    .page-break { display: block; page-break-before: always; }
    @media print {
        .page-break { display: block; page-break-before: always; }
        .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
            float: left;
        }
        .col-sm-12 {
            width: 100%;
        }
        .col-sm-11 {
            width: 91.66666667%;
        }
        .col-sm-10 {
            width: 83.33333333%;
        }
        .col-sm-9 {
            width: 75%;
        }
        .col-sm-8 {
            width: 66.66666667%;
        }
        .col-sm-7 {
            width: 58.33333333%;
        }
        .col-sm-6 {
            width: 50%;
        }
        .col-sm-5 {
            width: 41.66666667%;
        }
        .col-sm-4 {
            width: 33.33333333%;
        }
        .col-sm-3 {
            width: 25%;
        }
        .col-sm-2 {
            width: 16.66666667%;
        }
        .col-sm-1 {
            width: 8.33333333%;
        }
        .col-sm-pull-12 {
            right: 100%;
        }
        .col-sm-pull-11 {
            right: 91.66666667%;
        }
        .col-sm-pull-10 {
            right: 83.33333333%;
        }
        .col-sm-pull-9 {
            right: 75%;
        }
        .col-sm-pull-8 {
            right: 66.66666667%;
        }
        .col-sm-pull-7 {
            right: 58.33333333%;
        }
        .col-sm-pull-6 {
            right: 50%;
        }
        .col-sm-pull-5 {
            right: 41.66666667%;
        }
        .col-sm-pull-4 {
            right: 33.33333333%;
        }
        .col-sm-pull-3 {
            right: 25%;
        }
        .col-sm-pull-2 {
            right: 16.66666667%;
        }
        .col-sm-pull-1 {
            right: 8.33333333%;
        }
        .col-sm-pull-0 {
            right: auto;
        }
        .col-sm-push-12 {
            left: 100%;
        }
        .col-sm-push-11 {
            left: 91.66666667%;
        }
        .col-sm-push-10 {
            left: 83.33333333%;
        }
        .col-sm-push-9 {
            left: 75%;
        }
        .col-sm-push-8 {
            left: 66.66666667%;
        }
        .col-sm-push-7 {
            left: 58.33333333%;
        }
        .col-sm-push-6 {
            left: 50%;
        }
        .col-sm-push-5 {
            left: 41.66666667%;
        }
        .col-sm-push-4 {
            left: 33.33333333%;
        }
        .col-sm-push-3 {
            left: 25%;
        }
        .col-sm-push-2 {
            left: 16.66666667%;
        }
        .col-sm-push-1 {
            left: 8.33333333%;
        }
        .col-sm-push-0 {
            left: auto;
        }
        .col-sm-offset-12 {
            margin-left: 100%;
        }
        .col-sm-offset-11 {
            margin-left: 91.66666667%;
        }
        .col-sm-offset-10 {
            margin-left: 83.33333333%;
        }
        .col-sm-offset-9 {
            margin-left: 75%;
        }
        .col-sm-offset-8 {
            margin-left: 66.66666667%;
        }
        .col-sm-offset-7 {
            margin-left: 58.33333333%;
        }
        .col-sm-offset-6 {
            margin-left: 50%;
        }
        .col-sm-offset-5 {
            margin-left: 41.66666667%;
        }
        .col-sm-offset-4 {
            margin-left: 33.33333333%;
        }
        .col-sm-offset-3 {
            margin-left: 25%;
        }
        .col-sm-offset-2 {
            margin-left: 16.66666667%;
        }
        .col-sm-offset-1 {
            margin-left: 8.33333333%;
        }
        .col-sm-offset-0 {
            margin-left: 0%;
        }
        .visible-xs {
            display: none !important;
        }
        .hidden-xs {
            display: block !important;
        }
        table.hidden-xs {
            display: table;
        }
        tr.hidden-xs {
            display: table-row !important;
        }
        th.hidden-xs,
        td.hidden-xs {
            display: table-cell !important;
        }
        .hidden-xs.hidden-print {
            display: none !important;
        }
        .hidden-sm {
            display: none !important;
        }
        .visible-sm {
            display: block !important;
        }
        table.visible-sm {
            display: table;
        }
        tr.visible-sm {
            display: table-row !important;
        }
        th.visible-sm,
        td.visible-sm {
            display: table-cell !important;
        }
        .font14{
            font-size: 18px;
        }
        .w-30{width: 300px;}
        .v-align-top{vertical-align: top;}
    }
</style>

<html lang="en">
    <head>
        <title><?php echo ('defaulter list'); ?></title>
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css"> 
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/AdminLTE.min.css">
    </head>
    <body>       
        <div class="container"> 
            <div class="row">
                <div id="content" class="col-lg-12 col-sm-12 ">
                    <div class="invoice">                    
                        <div class="row">                           
                            <div class="col-xs-9">                               
                                <h3> <?php echo $this->lang->line('balance_fees_report_with_remark'); ?></h3>                  
                                <div class="font14"> <strong>Class</strong> <?php echo $class['class'] ."-". $section['section']; ?></div>
                            </div>
                            <div class="col-xs-3 text-right">
                                <br/>
                                <address>
                                    <strong>Date: <?php
                                        $date = date('Y-m-d');
                                        echo $this->customlib->dateformat($date);
                                        ?></strong><br/>
                                </address>                                                            
                            </div>
                        </div>
                        <hr style="margin-top: 0px;margin-bottom: 0px;" />
                        <div class="row">
                        <?php
                                    if (!empty($student_remain_fees)) {
                                        ?>
      <div class="table-responsive">
                                                   <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr>

                                          
                                            <th style="vertical-align: top"><?php echo $this->lang->line('student_name')."<br/>". "(".$this->lang->line('admission_no').")"; ?></th>
                                            <th style="vertical-align: top"><?php echo $this->lang->line('class'); ?></th>    
                                            <th style="vertical-align: top"><?php echo $this->lang->line('fees'); ?></th>  
                                            <th style="vertical-align: top"><?php echo $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th style="vertical-align: top"><?php echo $this->lang->line('paid'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th style="vertical-align: top"><?php echo $this->lang->line('balance'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th style="vertical-align: top"><?php echo $this->lang->line('guardian_phone'); ?></th>
                                            <th style="vertical-align: top"><?php echo $this->lang->line('remark'); ?></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($student_remain_fees)) {
                                            ?>

                                            <?php 
                                        } else {
                                            $count = 1;
                                            foreach ($student_remain_fees as $student) {
                                                
                    $amount=0;
                    $amount_deposite=0;
                    $amount_discount=0;
                    $amount_fine=0;

                                                if(!empty($student['fees'])){
                                                           foreach ($student['fees'] as $fee_key => $fee_value) {
                                                          
                                                             $amount+=$fee_value['amount'];
                                                             $amount_deposite+=$fee_value['amount_deposite'];
                                                             $amount_discount+=$fee_value['amount_discount'];
                                                             $amount_fine+=$fee_value['amount_fine'];
                                                            } 
                                                       
                                                        }   
                                             
                                                ?>
                                                <tr>
                                                    <td><?php echo $this->customlib->getFullName($student['firstname'],$student['middlename'],$student['lastname'],$sch_setting->middlename,$sch_setting->lastname) ."<br/>"."(".$student['admission_no'].")";?></td>                                         
                                                    <td><?php echo $student['class']."-".$student['section']; ?></td>          
                                                    <td>
                                                        <?php   
                                                        if(!empty($student['fees'])){


                                                        echo implode(', <br/>', array_map(
                                                         function ($v) {
                                                           
                                                           return ($v['is_system']) ? $this->lang->line($v['fee_group']) . ' (' . $this->lang->line($v['fee_type']) . ')' :$v['fee_group'] . ' (' . $v['fee_type'] . ' : ' . $v['fee_code'] . ')';
                                                                      },
                                                             $student['fees']));
                                                        }                                                       
                                                       
                                                    ?>
                                                    </td>
                                                    <td class="text text-right"><?php echo amountFormat($amount); ?></td>
                                                    <td class="text text-right"><?php echo amountFormat($amount_deposite+$amount_discount); ?></td>                                                
                                                    <td class="text text-right"><?php
                                            echo amountFormat(($amount - ($amount_deposite + $amount_discount)));
                                                ?></td> 
                                                  <td ><?php
                                            echo $student['guardian_phone'];
                                                ?></td>
                                                  <td class="text">
                                                      <div style="height: 100px; overflow:hidden;">
   
  </div>
                                                  </td>
                                                </tr>
                                                <?php
                                            }
                                            $count++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                            </div>
                                        <?php
                                     
                                    } else {
                                        ?>
                                        <div class="alert alert-info">
                                           <?php echo $this->lang->line('no_record_found') ; ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                        </div>
                    </div>
                </div>             
            </div>            
        </div>
        <div class="clearfix"></div>     
    </body>
</html>