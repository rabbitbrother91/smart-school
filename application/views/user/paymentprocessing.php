<div class="content-wrapper" style="min-height: 1126px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('payment'); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="invoice">

        <div class="row">
            <div class="alert alert-warning alert-dismissible">

                <h4><i class="icon fa fa-warning"></i><?php echo $this->lang->line('cancelled'); ?></h4>
                <?php echo $this->lang->line('your_payment_processing'); ?> <a style='color: #3c8dbc;  display: inline-table;' href="<?php echo site_url('user/user/getfees') ?>"><?php echo $this->lang->line('click_here'); ?></a> <?php echo $this->lang->line('to_fees_payment_page'); ?>
            </div>       
        </div>      
    </section>    
    <div class="clearfix"></div>
</div>