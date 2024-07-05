<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('reports/_inventory'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <form role="form" action="<?php echo site_url('report/getsearchtypeparam') ?>" method="post" class="" id="reportform">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search_type'); ?></label>
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
                    <div class="">
                        <div class="box-header ptbnull"></div>
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo $this->lang->line('issue_item_report'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="download_label"> <?php echo $this->lang->line('issue_item_report').' '.$this->customlib->get_postmessage();  ?></div>
                                <div class="table-responsive">
                                     <table class="table table-striped table-bordered table-hover item-list" data-export-title="<?php echo $this->lang->line('issue_item_report').' '. $this->customlib->get_postmessage();  ?>">
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('item'); ?></th>
                                                <th width = "20%"><?php echo $this->lang->line('note'); ?></th>
                                                <th><?php echo $this->lang->line('item_category'); ?></th>
                                                <th><?php echo $this->lang->line('issue_return'); ?></th>
                                                <th><?php echo $this->lang->line('issue_to'); ?></th>
                                                <th><?php echo $this->lang->line('issued_by'); ?></th>
                                                <th><?php echo $this->lang->line('quantity'); ?></th>
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

<script>
    ( function ( $ ) {
    'use strict';
    $(document).ready(function () {
       initDatatable('item-list','report/getissueinventorylistbydt',[],[],100);
    });
} ( jQuery ) )
</script>

<script type="text/javascript">
$(document).ready(function(){ 
$(document).on('submit','#reportform',function(e){
    e.preventDefault(); // avoid to execute the actual submit of the form.
    var $this = $(this).find("button[type=submit]:focus");  
    var form = $(this);
    var url = form.attr('action');
    var form_data = form.serializeArray();
    $.ajax({
           url: url,
           type: "POST",
           dataType:'JSON',
           data: form_data, // serializes the form's elements.
              beforeSend: function () {
                $('[id^=error]').html("");
                $this.button('loading');
              
               },
              success: function(response) { // your success handler
                if(response.status=='fail'){
                    $.each(response.error, function(key, value) {
                    $('#error_' + key).html(value);
                });
                }else{                    
                    initDatatable('item-list','report/getissueinventorylistbydt',response.params);
                }
              },
             error: function() { // your error handler
                 $this.button('reset');
             },
             complete: function() {
                 $this.button('reset');
             }
         });
        });
    });   
</script>