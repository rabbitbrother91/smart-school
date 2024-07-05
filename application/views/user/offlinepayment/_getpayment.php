<div class="row">
	<div class="col-md-8">	
		<h4 class="font15 bmedium text text-info"><?php echo $this->lang->line('request_id'); ?> : <?php echo $payment->id; ?></h4>
	</div>	
	<div class="col-md-4">
	<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
if ($payment->is_active == 0) {
    $status = '<span class="btn btn-md pull-right mt10 label label-warning">' . $this->lang->line('pending') . '</span>';
} elseif ($payment->is_active == 1) {
    $status = '<span class="btn btn-md pull-right mt10 label label-success">' . $this->lang->line('approved') . '</span>';
} elseif ($payment->is_active == 2) {
    $status = '<span class="btn btn-md pull-right mt10 label label-danger">' . $this->lang->line('rejected') . '</span>';
}
echo $status;
?>
</div>

</div>

<?php

if (IsNullOrEmptyString($payment->student_transport_fee_id)) {
    ?>
<div class="table-responsive white-space-nowrap w-100">
<table class="table table-stripped table-hover">
	<tbody>
		<tr>
			<th width="20%"><?php echo $this->lang->line('fees_group'); ?></th>
			<td> 			
			<?php
				if ($payment->is_system) {
            		echo $this->lang->line($payment->fee_group_name) . " (" . $this->lang->line($payment->type) . ")";
       			} else {
            		echo $payment->fee_group_name . " (" . $payment->type . ")";
        		}
        	?>
		</td>
			<th width="14%"><?php echo $this->lang->line('fees_code'); ?></th>
			<td><?php
					if ($payment->is_system) {
        			    echo $this->lang->line($payment->code);
        			} else {
        			    echo $payment->code;
        			}
        		?>
			</td>
		</tr>
		<tr>
			<th><?php echo $this->lang->line('date_of_submission'); ?></th>
			<td><?php echo $this->customlib->dateyyyymmddToDateTimeformat($payment->submit_date, false); ?></td>
			<th><?php echo $this->lang->line('approved_rejected_date'); ?></th>
			<td><?php echo $this->customlib->dateformat($payment->approve_date); ?></td>
		</tr>
		<tr>
			<th><?php echo $this->lang->line('amount'); ?></th>
			<td><?php echo $currency_symbol . '' . amountFormat($payment->amount); ?></td>
			<th><?php echo $this->lang->line('reference'); ?></th>
			<td><?php echo $payment->reference; ?></td>
		</tr>
		<tr>
			<th><?php echo $this->lang->line('date_of_payment'); ?></th>
			<td><?php echo $this->customlib->dateformat($payment->payment_date); ?></td>
			<th><?php echo $this->lang->line('payment_mode'); ?></th>
			<td><?php echo $payment->bank_from; ?></td>
		</tr>
		<tr>
			<th><?php echo $this->lang->line('payment_from'); ?></th>
			<td><?php echo $payment->bank_account_transferred; ?></td>
			<?php if ($payment->attachment) {?>
			<th><?php echo $this->lang->line('proof_of_payment'); ?></th>
			<td>
				<?php echo $this->media_storage->fileview($payment->attachment); ?><a href="<?php echo site_url('user/offlinepayment/download/' . $payment->id) ?>"><i class="fa fa-download"></i></a>
			</td>
			<?php }?>
		</tr>
	</tbody>
</table>
</div>
<?php
} else {
    ?>
<div class="table-responsive white-space-nowrap w-100">
<table class="table table-stripped table-hover">
	<tbody>
			<tr>
				<th><?php echo $this->lang->line('transport_fees_month'); ?></th>
				<td><?php echo $this->lang->line(strtolower($payment->month)); ?></td>
				<th><?php echo $this->lang->line('approved_rejected_date'); ?></th>
				<td><?php echo $this->customlib->dateformat($payment->approve_date); ?></td>
			</tr>
			<tr>
				<th><?php echo $this->lang->line('date_of_submission'); ?></th>
				<td><?php echo $this->customlib->dateyyyymmddToDateTimeformat($payment->submit_date, false); ?></td>
				<th><?php echo $this->lang->line('route_pickup_point'); ?></th>
				<td><?php echo $payment->route_title . " (" . $payment->pickup_point . ")"; ?></td>
			</tr>
			<tr>
				<th><?php echo $this->lang->line('date_of_payment'); ?></th>
				<td><?php echo $this->customlib->dateformat($payment->payment_date); ?></td>
				<th><?php echo $this->lang->line('payment_mode'); ?></th>
				<td><?php echo $payment->bank_from; ?></td>
			</tr>
			<tr>
				<th><?php echo $this->lang->line('payment_from'); ?></th>
				<td><?php echo $payment->bank_account_transferred; ?></td>
				<th><?php echo $this->lang->line('reference'); ?></th>
				<td><?php echo $payment->reference; ?></td>
			</tr>
			<tr>
				<th><?php echo $this->lang->line('amount'); ?></th>
				<td><?php echo $currency_symbol . '' .amountFormat($payment->amount); ?></td>
				<?php if ($payment->attachment) {?>			
				<th><?php echo $this->lang->line('proof_of_payment'); ?></th>
				<td>
					<?php echo $this->media_storage->fileview($payment->attachment); ?>  <a href="<?php echo site_url('user/offlinepayment/download/' . $payment->id) ?>"><i class="fa fa-download"></i></a>
				</td>			
			<?php }?>
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