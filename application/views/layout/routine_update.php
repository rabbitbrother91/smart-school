<div id="activelicmodal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Register your purchase code</h4>
            </div>
            <form action="<?php echo site_url('admin/admin/updatePurchaseCode') ?>" method="POST" id="purchase_code">
                <div class="modal-body lic_modal-body">
                    <div class="form-group">
                        <div class="req"><b>Important:</b> Smart School Regular License allows to use Smart School for single school/branch/end/client but for customer convenience registering Smart School allows to register Smart School licence purchase code on upto 3 urls e.g. 1. For localhost 2. For testing environment and 3. For your production url (testing and production url should be on same domain).</div>
                    </div>
                    <div class="error_message">

                    </div>
                    <div class="form-group">
                        <label class="ainline"><span>Envato Market Purchase Code for Smart School ( <a target="_blank" href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-"> How to find it?</a> )</span></label>
                        <input type="text" class="form-control" id="input-envato_market_purchase_code" name="envato_market_purchase_code">
                        <div id="error" class="text text-danger"></div>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Your Email registered with Envato</label>
                        <input type="text" class="form-control" id="input-email" name="email">
                        <div id="error" class="text text-danger"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving...">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
