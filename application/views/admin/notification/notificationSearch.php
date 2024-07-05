<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="glyphicon glyphicon-th"></i> <?php echo $this->lang->line('fee'); ?> <small><?php echo $this->lang->line('collection'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="">
                            <div class="col-md-6">
                                <form role="form" action="<?php echo site_url('admin/expense/expenseSearch') ?>" method="post" class="form-horizontal">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <label><?php echo $this->lang->line('date_from'); ?></label>
                                            <input autofocus="" id="datefrom"  name="date_from" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('date_from', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly"/>
                                            <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                        </div>
                                        <div class="col-sm-6">
                                            <label><?php echo $this->lang->line('date_to'); ?></label>
                                            <input id="dateto" name="date_to" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('date_to', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <form role="form" action="<?php echo site_url('admin/expense/expenseSearch') ?>" method="post" class="form-horizontal">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label><?php echo $this->lang->line('search'); ?></label>
                                            <input type="text" value="<?php echo set_value('search_text', ""); ?>" name="search_text"  class="form-control" placeholder="Search by Exp..">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <button type="submit" name="search" value="search_full" class="btn btn-primary btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (isset($resultList)) {
    ?><div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-money"></i> <?php echo $exp_title; ?></h3>
                            <div class="box-tools pull-right">
                            </div>
                        </div>
                        <div class="box-body table-responsive no-padding">
                            <?php
if (empty($resultList)) {
        ?>
                                <div class="col-md-12">
                                    <div class="alert alert-danger">
                                        <?php echo $this->lang->line('no_record_found'); ?>
                                    </div>
                                </div>
                                <?php
} else {
        ?>
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('amount'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
$count       = 1;
        $grand_total = 0;
        foreach ($resultList as $key => $value) {
            $grand_total = $grand_total + $value['amount'];
            ?>
                                            <tr>
                                                <td><?php echo $count; ?></td>
                                                <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['date'])); ?></td>
                                                <td><?php echo $value['name']; ?></td>
                                                <td><?php echo $value['note']; ?></td>
                                                <td><?php echo $value['amount']; ?></td>
                                            </tr>
                                            <?php
$count++;
        }
        ?>
                                        <tr>
                                            <th colspan="4" class="text-right"><?php echo $this->lang->line('grand_total'); ?></th>
                                            <th><?php echo number_format($grand_total, 2, '.', ''); ?></th>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php
}
    ?>
                        </div>
                        <div class="box-footer">
                            <div class="mailbox-controls">
                                <div class="pull-right">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
}
?>
            </div>
        </div>
    </section>
</div>