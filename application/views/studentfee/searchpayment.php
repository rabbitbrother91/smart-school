<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i>
                            <?php echo $this->lang->line('search_fees_payment'); ?>
                        </h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form role="form" action="<?php echo site_url('studentfee/searchpayment') ?>" method="post" class="form-inline">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label><?php echo $this->lang->line('payment_id'); ?>
                                            </label><small class="req"> *</small>
                                            <input autofocus="" id="paymentid" name="paymentid" placeholder="" type="text" class="form-control" value="<?php echo set_value('paymentid'); ?>" />
                                            <span class="text-danger"><?php echo form_error('paymentid'); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group align-text-top">
                                        <div class="col-sm-12">
                                            <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle mmius15 smallbtn28"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
                    if (isset($feeList)) {
                    ?>
                        <div class="ptt10">
                            <div class="box-header ptbnull"></div>
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo $this->lang->line('payment_id_detail'); ?></h3>
                                <div class="box-tools pull-right"></div>
                            </div>
                            <div class="box-body table-responsive">
                                <div class="download_label"><?php echo $this->lang->line('payment_id_detail'); ?></div>
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('payment_id'); ?></th>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <th><?php echo $this->lang->line('fees_group'); ?></th>
                                            <th><?php echo $this->lang->line('fee_type'); ?></th>
                                            <th><?php echo $this->lang->line('mode'); ?></th>
                                            <th class="text text-right"><?php echo $this->lang->line('paid'); ?></th>
                                            <th class="text text-right"><?php echo $this->lang->line('discount'); ?></th>
                                            <th class="text text-right"><?php echo $this->lang->line('fine'); ?></th>
                                            <th class="text text-right noExport"><?php echo $this->lang->line('action'); ?></th>
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
                                        <?php
                                        } else {

                                            $count = 1;

                                            $a = json_decode($feeList->amount_detail);

                                            $record = $a->{$sub_invoice_id};
                                        ?>
                                            <tr>
                                                <td>
                                                    <?php echo $feeList->id . "/" . $sub_invoice_id; ?>
                                                </td>
                                                <td>
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), strtotime($record->date)); ?>
                                                </td>
                                                <td>
                                                    <?php echo $this->customlib->getFullName($feeList->firstname, $feeList->middlename, $feeList->lastname, $sch_setting->middlename, $sch_setting->lastname) . " (" . $feeList->admission_no . ")"; ?>
                                                </td>
                                                <td>
                                                    <?php echo $feeList->class . " (" . $feeList->section . ")"; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($feeList->is_system) {
                                                        echo $this->lang->line($feeList->name);
                                                    } else {
                                                        echo $feeList->name;
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($feeList->is_system) {
                                                        echo $this->lang->line($feeList->type) . " (" . $this->lang->line($feeList->code) . ")";
                                                    } else {
                                                        echo $feeList->type . " (" . $feeList->code . ")";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php echo $this->lang->line(strtolower($record->payment_mode)); ?>
                                                </td>
                                                <td class="text text-right">
                                                    <?php
                                                    $amount = amountFormat($record->amount);
                                                    echo $currency_symbol . $amount;
                                                    ?>
                                                </td>
                                                <td class="text text-right">
                                                    <?php
                                                    $amount_discount = amountFormat($record->amount_discount);
                                                    echo $currency_symbol . $amount_discount;
                                                    ?>
                                                </td>
                                                <td class="text text-right">
                                                    <?php
                                                    $amount_fine = amountFormat($record->amount_fine);
                                                    echo $currency_symbol . $amount_fine;
                                                    ?>
                                                </td>
                                                <td class="text text-right">
                                                    <?php

                                                    if ($current_session['session_id'] == $feeList->session_id) {
                                                    ?>
                                                        <a href="<?php echo base_url() ?>studentfee/addfee/<?php echo $feeList->student_session_id ?>" class="btn btn-primary btn-xs" data-toggle="tooltip"> <i class="fa fa-list-alt"></i> <?php echo $this->lang->line('view'); ?>
                                                        </a>
                                                    <?php
                                                    }
                                                    ?>

                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
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