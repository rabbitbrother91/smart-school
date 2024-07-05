<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat();?>
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
    }
</style>

<html lang="en">
    <head>
        <title><?php echo $this->lang->line('fees_receipt'); ?></title>
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/AdminLTE.min.css">
    </head>
    <body>
      <?php 
$print_copy=explode(',', $settinglist[0]['is_duplicate_fees_invoice']);
         ?>
        <div class="container">
            <div class="row">
                <div id="content" class="col-lg-12 col-sm-12 ">

                        <?php 
if(in_array('0', $print_copy)){
    ?>
                    <div class="invoice">
                        <div class="row header ">
                            <div class="col-sm-12">                                

                                <img  src="<?php echo $this->media_storage->getImageURL('/uploads/print_headerfooter/student_receipt/'.$this->setting_model->get_receiptheader());?>" style="height: 100px;width: 100%;">
                                
                            </div>

                        </div>
 
                            <div class="row">
                                <div class="col-md-12 text text-center">
                                    <?php echo $this->lang->line('office_copy'); ?>
                                </div>
                            </div>

                        <div class="row">
                            <div class="col-xs-6 text-left">
                                <br/>
                                <address>
 
                                    <strong><?php echo $this->customlib->getFullName($feeList->firstname, $feeList->middlename, $feeList->lastname, $sch_setting->middlename, $sch_setting->lastname); ?></strong><?php echo " (" . $feeList->admission_no . ")"; ?> <br>

                                    <?php echo $this->lang->line('father_name'); ?>: <?php echo $student['father_name']; ?><br>
                                    <?php echo $this->lang->line('class'); ?>: <?php echo $feeList->class . " (" . $feeList->section . ")"; ?>
                                </address>
                            </div>
                            <div class="col-xs-6 text-right">
                                <br/>
                                <address>
                                    <strong><?php echo $this->lang->line('date'); ?>: <?php
$date = date('d-m-Y');

echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($date));
?></strong><br/>
                     <strong> <?php echo $this->lang->line('payment_id'); ?>:<?php echo $feeList->id . "/" . $sub_invoice_id; ?></strong>
                     <br/>
                     
                     
                    <strong> <?php echo $this->lang->line('collected_by'); ?>:
                    <?php  
                        if (isJSON($feeList->amount_detail)) {
                            $fee    = json_decode($feeList->amount_detail);
                            $record = $fee->{$sub_invoice_id};
                            if (!empty($record->received_by)) {
                                echo $record->collected_by;
                            }
                            
                        }
                    ?>                    
                    </strong>
                       
                     
                     
                                </address>
                            </div>
                        </div>
                        <hr style="margin-top: 0px;margin-bottom: 0px;" />
                        <div class="row">
                            <?php
if (!empty($feeList)) {
    ?>

                                <table class="table table-striped table-responsive" style="font-size: 8pt;">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th><?php echo $this->lang->line('fees_group'); ?></th>
                                            <th><?php echo $this->lang->line('fees_code'); ?></th>
                                            <th><?php echo $this->lang->line('mode'); ?></th>
                                            <th class="text text-right"><?php echo $this->lang->line('amount'); ?></th>
                                            <th class="text text-right"><?php echo $this->lang->line('discount'); ?></th>
                                            <th class="text text-right"><?php echo $this->lang->line('fine'); ?></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
$amount    = 0;
    $discount  = 0;
    $fine      = 0;
    $total     = 0;
    $grd_total = 0;
    if (empty($feeList)) {
        ?>
                                            <tr>
                                                <td colspan="11" class="text-danger text-center">
                                                    <?php echo $this->lang->line('no_transaction_found'); ?>
                                                </td>
                                            </tr>
                                            <?php
} else {
        $count = 1;

        ?>
                                            <tr>
                                                <td>
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($record->date)); ?>
                                                </td>
                                                <td>                                                     
                                                    <?php
                                                    if ($feeList->is_system) {
                                                        echo $this->lang->line($feeList->name) . " (" . $this->lang->line($feeList->type) . ")";
                                                    } else {
                                                        echo $feeList->name . " (" . $feeList->type . ")";
                                                    }
                                                    ?>
                                                </td>
                                                <td>                                                     
                                                    <?php
                                                        if ($feeList->is_system) {
                                                            echo $this->lang->line($feeList->code) ;
                                                        } else {
                                                            echo $feeList->code ;
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php echo $this->lang->line(strtolower($record->payment_mode)); ?>
                                                </td>
                                                <td class="text text-right">
                                                    <?php
$amount = $record->amount;
        echo $currency_symbol . (amountFormat($amount));
        ?>
                                                </td>
                                                <td class="text text-right">
                                                    <?php
$amount_discount = $record->amount_discount;
        echo $currency_symbol . (amountFormat($amount_discount));
        ?>
                                                </td>
                                                <td class="text text-right">
                                                    <?php
$amount_fine = $record->amount_fine;
        echo $currency_symbol . (amountFormat($amount_fine));
        ?>
                                                </td>

                                            </tr>
                                            <?php
}
    ?>
                                    </tbody>
                                </table>
                                <?php
}
?>

                        </div>
                        <div class="row header">
                            <div class="col-sm-12">
                                <?php echo $this->lang->line('note');?>:  <?php echo $record->description; ?>
                            </div>
                        </div>
                            
                        <div class="row header">
                            <div class="col-sm-12">
                                <?php echo $this->setting_model->get_receiptfooter();?>
                            </div>
                        </div>
                    </div>

    <?php
}
?>

                    <?php
if (in_array('1', $print_copy)) {   
    ?>
    
        
                    <?php
                        if (!$sch_setting->single_page_print) {   
                    ?>
                        <div class="page-break"></div>
                    <?php 
                        }else{ 
                            echo "<br><br><hr style='width:100%;'>"; 
                    } ?>    
                        
                        
                        <div class="invoice">
                            <div class="row header ">
                                <div class="col-sm-12">
                                    <?php
?>

                                    <img  src="<?php echo $this->media_storage->getImageURL('/uploads/print_headerfooter/student_receipt/'.$this->setting_model->get_receiptheader());?>" style="height: 100px;width: 100%;">
                                    <?php ?>
                                </div>

                            </div>
             
                                <div class="row">
                                    <div class="col-md-12 text text-center">
                                        <?php echo $this->lang->line('student_copy'); ?>
                                    </div>
                                </div>
    
                            <div class="row">
                                <div class="col-xs-6">
                                    <br/>
                                    <address>
                                        <strong>                            
                                        
                                        <?php echo $this->customlib->getFullName($feeList->firstname, $feeList->middlename, $feeList->lastname, $sch_setting->middlename, $sch_setting->lastname); ?></strong><?php echo " (" . $feeList->admission_no . ")"; ?> 
                                        
                                        <br>

                                        <?php echo $this->lang->line('father_name'); ?>: <?php echo $student['father_name']; ?><br>
                                        <?php echo $this->lang->line('class'); ?>: <?php echo $feeList->class . " (" . $feeList->section . ")"; ?>
                                    </address>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <br/>
                                    <address>
                                        <strong>
                                            Date: <?php
                                            $date = date('d-m-Y');
                                            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($date));
                                        ?></strong><br/>
                                        <strong> <?php echo $this->lang->line('payment_id'); ?>:  <?php echo $feeList->id . "/" . $sub_invoice_id; ?></strong><br>                                       
                                        
                                        
                                        <strong> <?php echo $this->lang->line('collected_by'); ?>:
                                        <?php  
                                            if (isJSON($feeList->amount_detail)) {
                                                $fee    = json_decode($feeList->amount_detail);
                                                $record = $fee->{$sub_invoice_id};
                                                if (!empty($record->received_by)) {
                                                    echo $record->collected_by;
                                                }
                                                
                                            }
                                        ?>                     
                                        </strong>                                      
                                        
                                    </address>
                                </div>
                            </div>
                            <hr style="margin-top: 0px;margin-bottom: 0px;" />
                            <div class="row">
                                <?php
                                    if (!empty($feeList)) {
                                 ?>
                                    <table class="table table-striped table-responsive" style="font-size: 8pt;">
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('date'); ?></th>
                                                <th><?php echo $this->lang->line('fees_group'); ?></th>
                                                <th><?php echo $this->lang->line('fees_code'); ?></th>
                                                <th><?php echo $this->lang->line('mode'); ?></th>
                                                <th class="text text-right"><?php echo $this->lang->line('amount'); ?></th>
                                                <th class="text text-right"><?php echo $this->lang->line('discount'); ?></th>
                                                <th class="text text-right"><?php echo $this->lang->line('fine'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
$amount    = 0;
        $discount  = 0;
        $fine      = 0;
        $total     = 0;
        $grd_total = 0;
        if (empty($feeList)) {
            ?>
                                                <tr>
                                                    <td colspan="11" class="text-danger text-center">
                                                        <?php echo $this->lang->line('no_transaction_found'); ?>
                                                    </td>
                                                </tr>
                                                <?php
} else {
            $count = 1;

            $a      = json_decode($feeList->amount_detail);
            $record = $a->{$sub_invoice_id};
            ?>
                                                <tr>
                                                    <td>
                                                        <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($record->date)); ?>

                                                    </td>
                                                    <td>
                                                         
                                                        <?php
                                                    if ($feeList->is_system) {
                                                        echo $this->lang->line($feeList->name) . " (" . $this->lang->line($feeList->type) . ")";
                                                    } else {
                                                        echo $feeList->name . " (" . $feeList->type . ")";
                                                    }
                                                    ?>
                                                    </td>
                                                    <td>                                                        
                                                        <?php
                                                        if ($feeList->is_system) {
                                                            echo $this->lang->line($feeList->code) ;
                                                        } else {
                                                            echo $feeList->code ;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $this->lang->line(strtolower($record->payment_mode)); ?>
                                                    </td>
                                                    <td class="text text-right">
                                                        <?php
$amount = $record->amount;
            echo $currency_symbol . (amountFormat($amount));
            ?>
                                                    </td>
                                                    <td class="text text-right">
                                                        <?php
$amount_discount = $record->amount_discount;
            echo $currency_symbol . (amountFormat($amount_discount));
            ?>
                                                    </td>
                                                    <td class="text text-right">
                                                        <?php
$amount_fine = $record->amount_fine;
            echo $currency_symbol . (amountFormat($amount_fine));
            ?>
                                                    </td>

                                                </tr>
                                                <?php
}
        ?>
                                        </tbody>
                                    </table>
                                    <?php
}
    ?>

                            </div>
                            <div class="row header ">
                                <div class="col-sm-12">
                                    <?php echo $this->lang->line('note');?>:  <?php echo $record->description; ?>
                                </div>
                            </div>
                            <div class="row header ">
                                <div class="col-sm-12">
                                    <?php echo $this->setting_model->get_receiptfooter();?>

                                </div>
                            </div>
                        </div>
                        <?php
}
?>

                 <?php
if (in_array('2', $print_copy)) {   
    ?>
    
        
                    <?php
                        if (!$sch_setting->single_page_print) {   
                    ?>
                        <div class="page-break"></div>
                    <?php 
                        }else{ 
                            echo "<br><br><hr style='width:100%;'>"; 
                    } ?>    
                        
                        
                        <div class="invoice">
                            <div class="row header ">
                                <div class="col-sm-12">
                                    <?php
?>

                                    <img  src="<?php echo $this->media_storage->getImageURL('/uploads/print_headerfooter/student_receipt/'.$this->setting_model->get_receiptheader());?>" style="height: 100px;width: 100%;">
                                    <?php ?>
                                </div>

                            </div>
             
                                <div class="row">
                                    <div class="col-md-12 text text-center">
                                        <?php echo $this->lang->line('bank_copy'); ?>
                                    </div>
                                </div>
    
                            <div class="row">
                                <div class="col-xs-6">
                                    <br/>
                                    <address>
                                        <strong><?php echo $this->customlib->getFullName($feeList->firstname, $feeList->middlename, $feeList->lastname, $sch_setting->middlename, $sch_setting->lastname); ?></strong><?php echo " (" . $feeList->admission_no . ")"; ?><br>

                                        <?php echo $this->lang->line('father_name'); ?>: <?php echo $student['father_name']; ?><br>
                                        <?php echo $this->lang->line('class'); ?>: <?php echo $feeList->class . " (" . $feeList->section . ")"; ?>
                                    </address>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <br/>
                                    <address>
                                        <strong>
                                            Date: <?php
                                            $date = date('d-m-Y');
                                            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($date));
                                        ?></strong><br/>
                                        <strong> <?php echo $this->lang->line('payment_id'); ?>:  <?php echo $feeList->id . "/" . $sub_invoice_id; ?></strong><br>                                       
                                        
                                        
                                        <strong> <?php echo $this->lang->line('collected_by'); ?>:
                                        <?php  
                                            if (isJSON($feeList->amount_detail)) {
                                                $fee    = json_decode($feeList->amount_detail);
                                                $record = $fee->{$sub_invoice_id};
                                                if (!empty($record->received_by)) {
                                                    echo $record->collected_by;
                                                }
                                                
                                            }
                                        ?>                     
                                        </strong>                                      
                                        
                                    </address>
                                </div>
                            </div>
                            <hr style="margin-top: 0px;margin-bottom: 0px;" />
                            <div class="row">
                                <?php
                                    if (!empty($feeList)) {
                                 ?>
                                    <table class="table table-striped table-responsive" style="font-size: 8pt;">
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('date'); ?></th>
                                                <th><?php echo $this->lang->line('fees_group'); ?></th>
                                                <th><?php echo $this->lang->line('fees_code'); ?></th>
                                                <th><?php echo $this->lang->line('mode'); ?></th>
                                                <th class="text text-right"><?php echo $this->lang->line('amount'); ?></th>
                                                <th class="text text-right"><?php echo $this->lang->line('discount'); ?></th>
                                                <th class="text text-right"><?php echo $this->lang->line('fine'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
$amount    = 0;
        $discount  = 0;
        $fine      = 0;
        $total     = 0;
        $grd_total = 0;
        if (empty($feeList)) {
            ?>
                                                <tr>
                                                    <td colspan="11" class="text-danger text-center">
                                                        <?php echo $this->lang->line('no_transaction_found'); ?>
                                                    </td>
                                                </tr>
                                                <?php
} else {
            $count = 1;

            $a      = json_decode($feeList->amount_detail);
            $record = $a->{$sub_invoice_id};
            ?>
                                                <tr>
                                                    <td>
                                                        <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($record->date)); ?>

                                                    </td>
                                                    <td>                                                         
                                                        <?php
                                                    if ($feeList->is_system) {
                                                        echo $this->lang->line($feeList->name) . " (" . $this->lang->line($feeList->type) . ")";
                                                    } else {
                                                        echo $feeList->name . " (" . $feeList->type . ")";
                                                    }
                                                    ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($feeList->is_system) {
                                                            echo $this->lang->line($feeList->code) ;
                                                        } else {
                                                            echo $feeList->code ;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $this->lang->line(strtolower($record->payment_mode)); ?>
                                                    </td>
                                                    <td class="text text-right">
                                                        <?php
$amount = $record->amount;
            echo $currency_symbol . (amountFormat($amount));
            ?>
                                                    </td>
                                                    <td class="text text-right">
                                                        <?php
$amount_discount = $record->amount_discount;
            echo $currency_symbol . (amountFormat($amount_discount));
            ?>
                                                    </td>
                                                    <td class="text text-right">
                                                        <?php
$amount_fine = $record->amount_fine;
            echo $currency_symbol . (amountFormat($amount_fine));
            ?>
                                                    </td>

                                                </tr>
                                                <?php
}
        ?>
                                        </tbody>
                                    </table>
                                    <?php
}
    ?>

                            </div>
                            <div class="row header ">
                                <div class="col-sm-12">
                                    <?php echo $this->lang->line('note');?>:  <?php echo $record->description; ?>
                                </div>
                            </div>
                            <div class="row header ">
                                <div class="col-sm-12">
                                    <?php echo $this->setting_model->get_receiptfooter();?>

                                </div>
                            </div>
                        </div>
                        <?php
}
?>

                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <footer>
        </footer>
    </body>
</html>