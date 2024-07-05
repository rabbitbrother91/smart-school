<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('financereports/_finance'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form role="form" action="<?php echo site_url('financereports/onlinefees_report') ?>" method="post" class="">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-6 col-md-3" >
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search_type'); ?><small class="req"> *</small></label>
                                    <select class="form-control" name="search_type" onchange="showdate(this.value)">

                                        <?php foreach ($searchlist as $key => $search) {
                                            ?>
                                            <option value="<?php echo $key ?>" <?php
                                            if ((isset($search_type)) && ($search_type == $key)) {
                                                echo "selected";
                                            }
                                            ?>><?php echo $search ?></option>
                                                <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                </div>
                            </div>
                            <div id='date_result'>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
<?php
                                    $grd_total = 0;
                                    $allamount = 0;
                                    $alldiscount = 0;
                                    $finetotal = 0;
                                    $alltotal = 0;
                                    if (empty($collectlist)) { ?>
									<br/>
									<div class="box-header ptbnull">
										<div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                    </div> 
                                 <?php    } else { ?>
                    <div class="">
                        <div class="box-header ptbnull"></div>
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php ?> <?php echo $this->lang->line('online_fees_report'); ?></h3> 
                        </div>
                        <div class="box-body table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('online_fees_report');
                                                $this->customlib->get_postmessage();
                                                ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead class="header">
                                    <tr>
                                        <th><?php echo $this->lang->line('payment_id'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('fee_type'); ?></th>
                                        <th><?php echo $this->lang->line('mode'); ?></th>
                                        <th class="text text-right"><?php echo $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('discount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('fine'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('total'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $count = 1;
 
                                        foreach ($collectlist as $key => $collect) {

                                            $amount = 0;
                                            $discount = 0;
                                            $fine = 0;
                                            $total = 0;
                                            $amountLabel = "";
                                            $discountLabel = "";
                                            $fineLabel = "";
                                            $TotalLabel = "";

                                            $amount += $collect['amount'];
                                            $amountLabel .= amountFormat($collect['amount']) . "<br>";
                                            $discount += $collect['amount_discount'];
                                            $discountLabel .= amountFormat($collect['amount_discount']) . "</br>";
                                            $fine += $collect['amount_fine'];
                                            $fineLabel .= amountFormat($collect['amount_fine']) . "</br>";
                                            $t = $collect['amount'] + $collect['amount_fine'];
                                            $TotalLabel .= amountFormat($t) . "</br>";
                                            $amountLabeltot = amountFormat($amount);
                                            $discountLabeltot = amountFormat($discount);
                                            $fineLabeltot = amountFormat($fine);
                                            $TotalLabeltot = amountFormat($t);
                                            $total += ($amount + $fine);
                                            $allamount += $amount;
                                            $alldiscount += $discount;
                                            $finetotal += $fine;
                                            $alltotal += $total;
                                            ?>
                                            <tr>
                                                <td> <?php echo $collect['id'] . "/" . $collect['inv_no']; ?></td>
                                                <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($collect['date'])); ?> </td>
                                                <td> <?php echo $collect['admission_no']; ?> </td>
                                                <td> <?php echo $this->customlib->getFullName($collect['firstname'],$collect['middlename'],$collect['lastname'],$sch_setting->middlename,$sch_setting->lastname); ?> </td>
                                                <td><?php echo $collect['class'] . " (" . $collect['section'] . ")"; ?> </td>
                                                <td>  
                                                    <?php
                                                        if ($collect['is_system']) {
                                                            echo $this->lang->line($collect['type']);
                                                        } else {
                                                            echo $collect['type'];
                                                        }
                                                    ?>
                                                </td>
                                                <td><?php echo $this->lang->line(strtolower($collect['payment_mode'])); ?></td>
                                                <td class="text text-right"> <?php echo $amountLabel; ?></td>
                                                <td class="text text-right"><?php echo $discountLabel; ?> </td>
                                                <td class="text text-right"><?php echo $fineLabel; ?></td>
                                                <td class="text text-right"> <?php $t = ($amount + $fine); echo $TotalLabel; ?> </td>
                                            </tr>
                                            <?php
                                            $count++;
                                            ?>

                                            <?php
                                        }
                                        ?>                       
                                    
                                </tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="font-weight:bold"><?php echo $this->lang->line('total') ?></td>
                                    <td class="text text-right" style="font-weight:bold" ><?php echo amountFormat($allamount); ?></td>
                                    <td class="text text-right" style="font-weight:bold"><?php echo amountFormat($alldiscount); ?></td>
                                    <td class="text text-right" style="font-weight:bold"><?php echo amountFormat($finetotal); ?></td>
                                    <td class="text text-right" style="font-weight:bold"><?php echo amountFormat($alltotal); ?></td>                                                
                                </tr>      
                            </table>
                        </div>
                    </div>
                </div>
                 <?php
                                    }
                                    ?>
            </div>
        </div>   
</div>  
</section>
</div>
<script>
<?php
if ($search_type == 'period') {
    ?>

        $(document).ready(function () {
            showdate('period');
        });

    <?php
}
?>
</script>