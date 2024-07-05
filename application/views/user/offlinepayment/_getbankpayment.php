<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<table class="table table-striped table-bordered table-hover payment-list" data-export-title="<?php echo $this->lang->line('offline_bank_payments'); ?>">
        <thead>
                <tr>
                        <th><?php echo $this->lang->line('request_id'); ?></th>
                        <th><?php echo $this->lang->line('payment_date'); ?></th>
                        <th><?php echo $this->lang->line('submit_date'); ?></th>
                        <th><?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)</th>
                        <th><?php echo $this->lang->line('status'); ?></th>
                        <th><?php echo $this->lang->line('status_date'); ?></th>
                        <th><?php echo $this->lang->line('payment_id'); ?></th>
                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                </tr>
        </thead>
        <tbody>
        </tbody>
</table>