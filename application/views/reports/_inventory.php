<div class="row">
    <div class="col-md-12">
        <div class="box box-primary border0 mb0 margesection">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i>  <?php echo $this->lang->line('inventory_report'); ?></h3>
            </div>
            <div class="">
                <ul class="reportlists">
                    <?php if ($this->rbac->hasPrivilege('stock_report', 'can_view')) { ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/inventory/inventorystock'); ?>"><a href="<?php echo base_url() ?>report/inventorystock"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('stock_report'); ?></a></li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('add_item_report', 'can_view')) {
                        ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/inventory/additem'); ?>"><a href="<?php echo base_url() ?>report/additem"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('add_item_report'); ?></a></li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('issue_item_report', 'can_view')) {
                        ?>
                        <li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/inventory/issueinventory'); ?>"><a href="<?php echo base_url() ?>report/issueinventory"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('issue_item_report'); ?></a></li>
<?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>