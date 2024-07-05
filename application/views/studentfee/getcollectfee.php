<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">
    .collect_grp_fees{
      font-size: 15px;
    font-weight: 600;
    padding-bottom: 15px;
    }

    .fees-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .fees-list>.item {
        border-radius: 3px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        padding: 10px 0;
        background: #fff;
    }
    .fees-list>.item:before, .fees-list>.item:after {
        content: " ";
        display: table;
    }
    .fees-list>.item:after {
        clear: both;
    }
    .fees-list .product-img {
        float: left;
    }
    .fees-list .product-img img {
        width: 50px;
        height: 50px;
    }
    .fees-list .product-info {
        margin-left: 0px;
    }
    .fees-list .product-title {
        font-weight: 600;
        font-size: 15px;
        display: inline;
    }
    .fees-list .product-title span{

        font-size: 15px;
        display: inline;
        font-weight: 100 !important;
    }
    .fees-list .product-description {
        display: block;
        color: #999;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .fees-list-in-box>.item {
        -webkit-box-shadow: none;
        box-shadow: none;
        border-radius: 0;
        border-bottom: 1px solid #f4f4f4;
    }
    .fees-list-in-box>.item:last-of-type {
        border-bottom-width: 0;
    }

.fees-footer {
    border-top-color: #f4f4f4;
}
.fees-footer {
    padding: 15px 0px 0px 0px;
    text-align: right;
    border-top: 1px solid #e5e5e5;
}
</style>

<div class=" ">
  <div class="col-lg-12">
    <div class="form-horizontal">
        <div class="col-lg-12">
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-3 control-label"><?php echo $this->lang->line('date'); ?> <small class="req"> *</small></label>
                <div class="col-sm-9">
                    <input id="date" name="collected_date" placeholder="" type="text" class="form-control date_fee" value="" readonly="readonly" autocomplete="off">
                    <span id="form_collection_collected_date_error" class="text text-danger"></span>
                </div>
            </div>    
        </div>
        <div class="col-lg-12">
          <div class="form-group row">  
            <label for="inputPassword3" class="col-sm-3 control-label"> <?php echo $this->lang->line('payment_mode'); ?></label>
            <div class="col-sm-9">
                <label class="radio-inline">
                    <input type="radio" name="payment_mode_fee" value="Cash" checked="checked"> <?php echo $this->lang->line('cash'); ?></label>
                <label class="radio-inline">
                    <input type="radio" name="payment_mode_fee" value="Cheque"> <?php echo $this->lang->line('cheque'); ?></label>
                <label class="radio-inline">
                    <input type="radio" name="payment_mode_fee" value="DD"><?php echo $this->lang->line('dd'); ?></label>
                <label class="radio-inline">
                    <input type="radio" name="payment_mode_fee" value="bank_transfer"><?php echo $this->lang->line('bank_transfer'); ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="payment_mode_fee" value="upi"><?php echo $this->lang->line('upi'); ?>
                </label>
                <label class="radio-inline">
                    <input type="radio" name="payment_mode_fee" value="card"><?php echo $this->lang->line('card'); ?>
                </label>
                <span class="text-danger" id="payment_mode_error"></span>
            </div>
            <span id="form_collection_payment_mode_fee_error" class="text text-danger"></span>
          </div>  
        </div>
        <div class="col-lg-12">
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-3 control-label"> <?php echo $this->lang->line('note') ?></label>
                <div class="col-sm-9">
                    <textarea class="form-control" rows="3" name="fee_gupcollected_note" id="description" placeholder=""></textarea>
                    <span id="form_collection_fee_gupcollected_note_error" class="text text-danger"></span>
                </div>
            </div>    
        </div>
    </div>
<ul class="fees-list fees-list-in-box">
    <?php
    $row_counter = 1;
    $total_amount = 0;
    foreach ($feearray as $fee_key => $fee_value) {
        $amount_prev_paid = 0;
        $fees_fine_amount = 0;
        $fine_amount_paid = 0;
        $fine_amount_status = false;

        if($fee_value->fee_category == "fees"){
        $amount_to_be_pay = $fee_value->amount;

        if ($fee_value->is_system) {
            $amount_to_be_pay = $fee_value->student_fees_master_amount;
        }
    
        if (is_string(($fee_value->amount_detail)) && is_array(json_decode(($fee_value->amount_detail), true))) {
            $amount_data = json_decode($fee_value->amount_detail);
            foreach ($amount_data as $amount_data_key => $amount_data_value) {
                      $fine_amount_paid+=$amount_data_value->amount_fine;
                $amount_prev_paid = $amount_prev_paid + ($amount_data_value->amount + $amount_data_value->amount_discount);
            }

            if ($fee_value->is_system) {
                $amount_to_be_pay = $fee_value->student_fees_master_amount - $amount_prev_paid;
            } else {
                $amount_to_be_pay = $fee_value->amount - $amount_prev_paid;
            }
        }

    if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != NULL) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d'))) && $amount_to_be_pay > 0) {
         $fees_fine_amount=$fee_value->fine_amount-$fine_amount_paid;
         $total_amount=$total_amount+$fees_fine_amount;
         $fine_amount_status=true;
        }

        $total_amount = $total_amount + $amount_to_be_pay;
        if ($amount_to_be_pay > 0) {
            ?>

            <li class="item">
                <input name="row_counter[]" type="hidden" value="<?php echo $row_counter; ?>">
                <input name="student_fees_master_id_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->id; ?>">
                <input name="fee_groups_feetype_id_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->fee_groups_feetype_id; ?>">
                 <input name="fee_groups_feetype_fine_amount_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fees_fine_amount; ?>">
                <input name="fee_amount_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $amount_to_be_pay; ?>">
                <input name="fee_category_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->fee_category; ?>">
                <input name="trans_fee_id_<?php echo $row_counter; ?>" type="hidden" value="0">
                <div class="product-info">
                    <a href="#"  onclick="return false;" class="product-title">                       
                         
                        <?php
                            if ($fee_value->is_system) {
                                echo $this->lang->line($fee_value->name) . " (" . $this->lang->line($fee_value->type) . ")";
                            } else {
                                echo $fee_value->name . " (" . $fee_value->type . ")";
                            }
                        ?>
                        <span class="pull-right"><?php echo  $currency_symbol.amountFormat((float) $amount_to_be_pay, 2, '.', ''); ?></span></a>
                        <span class="product-description">                       
                        
                        <?php
                            if ($fee_value->is_system) {
                                echo $this->lang->line($fee_value->code);
                            } else {
                                echo $fee_value->code;
                            }                    
                        ?>
        
                        </span>
                        <?php 
if($fine_amount_status){
    ?>
                       <a href="#"  onclick="return false;" class="product-title text text-danger"><?php echo $this->lang->line('fine'); ?>
                        <span class="pull-right">
                            <?php echo  $currency_symbol.amountFormat((float) $fees_fine_amount, 2, '.', ''); ?>                                
                        </span>
                    </a>
    <?php
}
                         ?>
                </div>
            </li>

            <?php
        }
        }elseif ($fee_value->fee_category == "transport") {
   
        $amount_to_be_pay = $fee_value->fees;       
    
        if (is_string(($fee_value->amount_detail)) && is_array(json_decode(($fee_value->amount_detail), true))) {

            $amount_data = json_decode($fee_value->amount_detail);

            foreach ($amount_data as $amount_data_key => $amount_data_value) {
                      $fine_amount_paid+=$amount_data_value->amount_fine;
                $amount_prev_paid = $amount_prev_paid + ($amount_data_value->amount + $amount_data_value->amount_discount);
            }

                $amount_to_be_pay = $fee_value->fees - $amount_prev_paid;
         
        }

    if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != NULL) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d'))) && $amount_to_be_pay > 0) {

$transport_fine_amount  =  is_null($fee_value->fine_percentage) ? $fee_value->fine_amount : percentageAmount($fee_value->fees,$fee_value->fine_percentage);
         $fees_fine_amount=$transport_fine_amount-$fine_amount_paid;
         $total_amount=$total_amount+$fees_fine_amount;
         $fine_amount_status=true;
        }

        $total_amount = $total_amount + $amount_to_be_pay;
        if ($amount_to_be_pay > 0) {
            ?>

            <li class="item">
                <input name="row_counter[]" type="hidden" value="<?php echo $row_counter; ?>">
                <input name="student_fees_master_id_<?php echo $row_counter; ?>" type="hidden" value="0">
                <input name="fee_groups_feetype_id_<?php echo $row_counter; ?>" type="hidden" value="0">
                 <input name="fee_groups_feetype_fine_amount_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fees_fine_amount; ?>">
                <input name="fee_amount_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $amount_to_be_pay; ?>">
                <div class="product-info">
                    <input name="fee_category_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->fee_category; ?>">
                <input name="trans_fee_id_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->id; ?>">
                
                    <a href="#"  onclick="return false;" class="product-title"><?php echo $this->lang->line("transport_fees") ?>
                        <span class="pull-right"><?php echo  $currency_symbol.amountFormat((float) $amount_to_be_pay, 2, '.', ''); ?></span></a>
                         <span class="product-description">
                        <?php echo $fee_value->month; ?>
                        </span>
                        <?php 
if($fine_amount_status){
    ?>
                       <a href="#"  onclick="return false;" class="product-title text text-danger"><?php echo $this->lang->line('fine'); ?>
                        <span class="pull-right">
                            <?php echo  $currency_symbol.amountFormat((float) $fees_fine_amount, 2, '.', ''); ?>                                
                        </span>
                    </a>
    <?php
}
                         ?>
                </div>
            </li>

            <?php
        }
        }       

        $row_counter++;
    }
    ?>
</ul>
</div>
</div>
<?php if ($total_amount > 0) { ?>
<div class="row collect_grp_fees">
    <div class="col-md-8">
        <span class="pull-right">
            <?php echo $this->lang->line('total_pay'); ?>
        </span>
    </div>
    <div class="col-md-4">
        <span class="pull-right">
            <?php echo $currency_symbol.amountFormat((float) $total_amount, 2, '.', ''); ?>
        </span>
    </div>
</div>
<div class="row fees-footer">
    <div class="col-md-12">
          <button type="submit" class="btn btn-primary pull-right payment_collect" data-loading-text="<i class='fa fa-spinner fa-spin '></i><?php echo $this->lang->line('processing')?>"><i class="fa fa-money"></i> <?php echo $this->lang->line('pay'); ?></button>
    </div>
</div>
<?php }else{
    ?>
    <div class="row">
    <div class="col-md-12">
<div class="alert alert-info mb0">
    <?php echo $this->lang->line('no_fees_found'); ?>
</div>
</div>
    <?php
}
 ?>