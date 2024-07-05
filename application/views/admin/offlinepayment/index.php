<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat();?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
            </div>
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('offline_bank_payments'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="table-responsive overflow-visible-lg">
                            <table class="table table-striped table-bordered table-hover payment-list" data-export-title="<?php echo $this->lang->line('offline_bank_payments'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('request_id'); ?></th>
                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('payment_date'); ?></th>
                                        <th><?php echo $this->lang->line('submit_date'); ?></th>
                                        <th class="text-right">
                                        <?php echo $this->lang->line('amount'); ?> (<?php echo $currency_symbol; ?>)</th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('status_date'); ?></th>
                                        <th><?php echo $this->lang->line('payment_id'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="myPaymentModel" class="modal fade">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('payment_details'); ?></h4>
            </div>
            <div class="modal-body modalminheight scroll-area">
                <div class="modal_inner_loader"></div>
                <div class="payment_detail"></div>
           </div><!-- ./row -->
        </div>
    </div>
</div>

<script>
    ( function ( $ ) {
    'use strict';
    $(document).ready(function () {
      $('#myPaymentModel').modal({
         backdrop: 'static',
         keyboard: false,
         show: false
     });
      initDatatable('payment-list','admin/offlinepayment/getlist',[],[],100,[
                { "bSortable": true, "aTargets": [ -5 ] ,'sClass': 'dt-body-right'}
            ]);

    });
} ( jQuery ) );

           $(document).on('click', '.download_exam', function () {
            var $this=$(this);
            var recordid = $(this).data('recordid');
                  $('.payment_detail',$('#myPaymentModel')).html("");
             $('#myPaymentModel').modal('show');
            $.ajax({
                type: 'POST',
                url: baseurl + "admin/offlinepayment/getpayment",
                data: {'recordid': recordid},
                dataType: 'JSON',
                beforeSend: function () {
                    $this.button('loading');

                },
                success: function (data) {

                  $('.payment_detail',$('#myPaymentModel')).html(data.page);
                  $('.modal_inner_loader').fadeOut("slow");
                    $this.button('reset');
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                    $this.button('reset');
                },
                complete: function () {
                    $this.button('reset');
                }
            });
        });

$(document).on('submit','.change_status',function(e){

        e.preventDefault();
var form = $(this);
        var $this = form.find("button[type=submit]:focus");
var actionUrl = form.attr('action');
        $.ajax({
            url: actionUrl,
            type: "POST",
            data: form.serialize(),
            dataType: 'json',

            beforeSend: function () {
                $this.button('loading');
            },
            success: function (res)
            {
                if (res.status === 0) {
                    var message = "";
                    $.each(res.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);

                } else {

                   $this.button('reset');
                    successMsg(res.message);
                    $('#myPaymentModel').modal('hide');
                    table.ajax.reload( null, false );

                }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
})
</script>