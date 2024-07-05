<?php

$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$language        = $this->customlib->getLanguage();
$language_name   = $language["short_code"];

if (IsNullOrEmptyString($payment->student_transport_fee_id)) {
    ?>

<div class="row mb10">
	<div class="col-lg-6 col-md-6 col-sm-12">
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-12">
		<h4 class="mt0 font15 bmedium"><?php echo $this->lang->line('student_details'); ?></h4>

			</div>
			<div class="col-lg-6 col-md-6 col-sm-12">
				<h4 class="mt0 font15 bmedium pull-right text text-info"><?php echo $this->lang->line('request_id'); ?> : <?php echo $payment->id; ?></h4>
			</div>

		</div>
	<div class="table-responsive white-space-nowrap">
		<table class="table table-stripped table-hover">

                                <tr>
                                    <th width="36%"><?php echo $this->lang->line('admission_no'); ?></th>
                                    <td><?php echo $payment->admission_no; ?></td>
                                </tr>

								<tr class="bordertop">
                                    <th width="36%"><?php echo $this->lang->line('name'); ?></th>
                                    <td>
                                      <?php echo $this->customlib->getFullname($payment->firstname, $payment->middlename, $payment->lastname, $sch_setting->middlename, $sch_setting->lastname); ?>
                                    </td>
                                </tr>

								<tr class="bordertop">
                                    <th><?php echo $this->lang->line('class'); ?></th>
                                    <td>
                                       <?php

    echo $payment->class . "(" . $payment->section . ")";

    ?>
                                    </td>
                                </tr>

                                <?php

    if ($sch_setting->mobile_no) {
        ?>
                                 <tr>
                                    <th><?php echo $this->lang->line('mobile_number'); ?></th>
                                    <td><?php echo $payment->mobileno; ?></td>
                                 </tr>
                                 <?php
}

    if ($sch_setting->student_email) {
        ?>
                                 <tr>
                                    <th><?php echo $this->lang->line('email'); ?></th>
                                    <td><?php echo $payment->email; ?></td>
                                 </tr>
                                 <?php
}
    ?>
		</table>
      </div>
	</div>
	<div class="col-lg-5 col-md-5 col-sm-12 col-md-offset-1">
		<?php
if ($payment->is_active == 0) {
        ?>

<form class="change_status" action="<?php echo site_url('admin/offlinepayment/update') ?>" method="POST">
	 <input type="hidden" name="offline_fees_payment_id" value="<?php echo $payment->id ?>">
   <div class="form-group">
   	  <label for="reply"><?php echo $this->lang->line('status'); ?></label>
    	<div class="mb15">
	      <div>
	        <label class="radio-inline">
	           <input type="radio" name="payment_status"  value="1" checked><?php echo $this->lang->line('approve'); ?>
	        </label>
			<label class="radio-inline">
			    <input type="radio" name="payment_status" value="2" ><?php echo $this->lang->line('reject'); ?>
			</label>
	      </div>
    	</div>
    </div>

	<div class="form-group">
	    <label for="amount"><?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)<small class="req"> *</small></label>
	    <input type="text" class="form-control" id="amount" name="amount" value="<?php echo ($amount_to_paid['amount']); ?>">
	  </div>

  <div class="form-group">
    <label for="fine"><?php echo $this->lang->line('fine'); ?> (<?php echo $currency_symbol; ?>)<small class="req">*</small></label>
    <input type="text" class="form-control" id="fine" name="fine" value="<?php echo ($amount_to_paid['fine']); ?>">
  </div>

    <div class="form-group">
	    <label for="reply"> <?php echo $this->lang->line('comment_reason'); ?></label>
	    <div>
	    	<textarea class="form-control" id="reply" name="reply" placeholder="Enter your reply" rows="3"></textarea>
	    </div>
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('update') ?></button>
    </div>
</form>

	<?php
}
    ?>
	</div>
</div>

<div class="row mb20">
	<div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
		<h4 class="pt7 font15 bmedium"><?php echo $this->lang->line('payment_details'); ?></h4>
	</div>
<div class="col-lg-4 col-md-4 col-sm-5 col-xs-5">
	<?php

    if ($payment->is_active == 0) {
        $status = '<span class="btn btn-md pull-right mt10 label label-warning font-weight-normal">' . $this->lang->line('pending') . '</span>';
    } elseif ($payment->is_active == 1) {

        $status = '<span class="btn btn-md pull-right mt10 label label-success font-weight-normal">' . $this->lang->line('approved') . '</span>';
    } elseif ($payment->is_active == 2) {
        $status = '<span class="btn btn-md pull-right mt10 label label-danger font-weight-normal">' . $this->lang->line('rejected') . '</span>';
    }
    echo $status;
    ?>
</div>
</div>
<div class="table-responsive white-space-nowrap">
<table class="table table-stripped table-hover mt10">
	<tbody>

		<tr>
			<th width="18%"><?php echo $this->lang->line('fees_group'); ?></th>
			<td>				 
				<?php
					if ($payment->is_system) {
						echo $this->lang->line($payment->fee_group_name) . " (" . $this->lang->line($payment->type) . ")";
					} else {
						echo $payment->fee_group_name . " (" . $payment->type . ")";
					}
				?>
			</td>
			<th width="19%"><?php echo $this->lang->line('fees_code'); ?></th>
			<td>				 
				<?php
					if ($payment->is_system) {
						echo $this->lang->line($payment->code);
					} else {
						echo $payment->code;
					}
				?>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo $this->lang->line('date_of_submission'); ?>
			</th>
			<td>
				<?php echo $this->customlib->dateyyyymmddToDateTimeformat($payment->submit_date, false); ?>
			</td>
			<th>
				<?php echo $this->lang->line('approved_rejected_date'); ?>
			</th>
			<td>
				<?php echo $this->customlib->dateformat($payment->approve_date); ?>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo $this->lang->line('amount'); ?>
			</th>
			<td>
				<?php echo $currency_symbol . amountFormat($payment->amount); ?>
			</td>
				<th>
				<?php echo $this->lang->line('reference'); ?>
			</th>
			<td>
				<?php echo $payment->reference; ?>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo $this->lang->line('date_of_payment'); ?>
			</th>
			<td>
				<?php echo $this->customlib->dateformat($payment->payment_date); ?>
			</td>
			<th>
				<?php echo $this->lang->line('payment_mode'); ?>
			</th>
			<td>
				<?php echo $payment->bank_from; ?>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo $this->lang->line('payment_from'); ?>
			</th>
			<td>
				<?php echo $payment->bank_account_transferred; ?>
			</td>

			<th>
				<?php echo $this->lang->line('proof_of_payment'); ?>
			</th>
			<td>
				<?php

    if (!IsNullOrEmptyString($payment->attachment)) {
        ?>
<?php echo $this->media_storage->fileview($payment->attachment); ?><a data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>" href="<?php echo site_url('admin/offlinepayment/download/' . $payment->id) ?>"><i class="fa fa-download"></i></a>
<?php
}

    ?>
			</td>
		</tr>
	</tbody>
</table>
</div>
<?php
} else {
    ?>
<div class="row mb10">
	<div class="col-lg-6 col-md-6 col-sm-12">
			<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-12">
		<h4 class="mt0 font15 bmedium"><?php echo $this->lang->line('student_details'); ?></h4>

			</div>
			<div class="col-lg-6 col-md-6 col-sm-12">
				<h4 class="mt0 font15 bmedium pull-right text text-info"><?php echo $this->lang->line('request_id'); ?> : <?php echo $payment->id; ?></h4>
			</div>
		</div>
		<div class="table-responsive white-space-nowrap">
		<table class="table table-stripped table-hover">

                                 <tr>
                                    <th width="35%"><?php echo $this->lang->line('admission_no'); ?></th>
                                    <td><?php echo $payment->admission_no; ?></td>
                                 </tr>


<tr class="bordertop">
                                    <th width="35%"><?php echo $this->lang->line('name'); ?></th>
                                    <td>
                                      <?php echo $this->customlib->getFullname($payment->firstname, $payment->middlename, $payment->lastname, $sch_setting->middlename, $sch_setting->lastname); ?>
                                    </td>
                                 </tr>
	<tr class="bordertop">
                                    <th><?php echo $this->lang->line('class'); ?></th>
                                    <td>
                                       <?php

    echo $payment->class . "(" . $payment->section . ")";

    ?>
                                    </td>
                                 </tr>

                                 <?php

    if ($sch_setting->mobile_no) {
        ?>
                                 <tr>
                                    <th><?php echo $this->lang->line('mobile_number'); ?></th>
                                    <td><?php echo $payment->mobileno; ?></td>
                                 </tr>
                                 <?php
}

    if ($sch_setting->student_email) {
        ?>
                                 <tr>
                                    <th><?php echo $this->lang->line('email'); ?></th>
                                    <td><?php echo $payment->email; ?></td>
                                 </tr>
                                 <?php
}
    ?>
		</table>
	 </div>
	</div>
	<div class="col-lg-5 col-md-5 col-sm-12 col-md-offset-1">
		<?php
if ($payment->is_active == 0) {
        ?>

<form class="change_status" action="<?php echo site_url('admin/offlinepayment/update') ?>" method="POST">
	 <input type="hidden" name="offline_fees_payment_id" value="<?php echo $payment->id ?>">
   <div class="form-group">
   	  <label for="reply"> <?php echo $this->lang->line('status'); ?> </label>
    	<div class="mb15">
	      <div>
	        <label class="radio-inline">
	           <input type="radio" name="payment_status"  value="1" checked> <?php echo $this->lang->line('approve'); ?>
	        </label>
			<label class="radio-inline">
			    <input type="radio" name="payment_status" value="2" > <?php echo $this->lang->line('reject'); ?>
			</label>
	      </div>
    	</div>
    </div>
	<div class="form-group">
	    <label for="amount"><?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)<small class="req"> *</small></label>
	    <input type="text" class="form-control" id="amount" name="amount" value="<?php echo ($amount_to_paid['amount']); ?>">
	  </div>
  <div class="form-group">
    <label for="fine"><?php echo $this->lang->line('fine'); ?> (<?php echo $currency_symbol; ?>)<small class="req">*</small></label>
    <input type="text" class="form-control" id="fine" name="fine" value="<?php echo ($amount_to_paid['fine']); ?>">
  </div>
    <div class="form-group">
	    <label for="reply"><?php echo $this->lang->line('comment_reason'); ?></label>
	    <div>
	    	<textarea class="form-control" id="reply" name="reply" placeholder="Enter your reply" rows="3"></textarea>
	    </div>
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('update') ?></button>
    </div>
</form>

	<?php
}

    ?>
	</div>
</div>
<div class="row mb20">
<div class="col-md-8">
<h4 class="pt7 font15 bmedium"><?php echo $this->lang->line('payment_detail'); ?></h4>
</div>
<div class="col-md-4">
	<?php

    if ($payment->is_active == 0) {
        $status = '<span class="btn btn-md pull-right mt10 label label-warning font-weight-normal">' . $this->lang->line('pending') . '</span>';
    } elseif ($payment->is_active == 1) {

        $status = '<span class="btn btn-md pull-right mt10 label label-success font-weight-normal">' . $this->lang->line('approved') . '</span>';
    } elseif ($payment->is_active == 2) {
        $status = '<span class="btn btn-md pull-right mt10 label label-danger font-weight-normal">' . $this->lang->line('rejected') . '</span>';
    }
    echo $status;
    ?>
</div>
</div>
<div class="table-responsive white-space-nowrap w-100">
<table class="table table-stripped table-hover mt10">
	<tbody>
			<tr>
			<th>
				<?php echo $this->lang->line('transport_fees_month'); ?>
			</th>
			<td>
				<?php echo $payment->month; ?>
			</td>
			<th>
				<?php echo $this->lang->line('approved_rejected_date'); ?>
			</th>
			<td>
				<?php echo $this->customlib->dateformat($payment->approve_date); ?>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo $this->lang->line('date_of_submission'); ?>
			</th>
			<td>
				<?php echo $this->customlib->dateyyyymmddToDateTimeformat($payment->submit_date, false); ?>
			</td>
			<th>
				<?php echo $this->lang->line('route_pickup_point'); ?>
			</th>
			<td>
				<?php echo $payment->route_title . " (" . $payment->pickup_point . ")"; ?>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo $this->lang->line('date_of_payment'); ?>
			</th>
			<td>
				<?php echo $this->customlib->dateformat($payment->payment_date); ?>
			</td>
			<th>
				<?php echo $this->lang->line('payment_mode'); ?>
			</th>
			<td>
				<?php echo $payment->bank_from; ?>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo $this->lang->line('payment_from'); ?>
			</th>
			<td>
				<?php echo $payment->bank_account_transferred; ?>
			</td>
			<th>
				<?php echo $this->lang->line('reference'); ?>
			</th>
			<td>
				<?php echo $payment->reference; ?>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo $this->lang->line('amount'); ?>
			</th>
			<td>
				<?php echo $currency_symbol . amountFormat($payment->amount); ?>
			</td>

			<th>
				<?php echo $this->lang->line('proof_of_payment'); ?>
			</th>
			<td>

				<?php

    if (!IsNullOrEmptyString($payment->attachment)) {
        ?>
<?php echo $this->media_storage->fileview($payment->attachment); ?>  <a data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>" href="<?php echo site_url('admin/offlinepayment/download/' . $payment->id) ?>"><i class="fa fa-download"></i></a>
<?php
}

    ?>

			</td>
		</tr>
	</tbody>
</table>
</div>
<?php
}
?>

<div>
	<label><b><?php echo $this->lang->line('comment_reason'); ?> </b></label> : <?php echo $payment->reply; ?>
</div>
