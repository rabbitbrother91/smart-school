<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-bus"></i> <?php echo $this->lang->line('transport_routes'); ?> </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('transport_routes'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('route_title'); ?></th>
                                        <th><?php echo $this->lang->line('no_of_vehicle'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('fare'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($listroute)) {
    ?>
                                        <tr>
                                            <td colspan="12" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                        </tr>
                                        <?php
} else {
    $count = 1;
    foreach ($listroute as $data) {
        ?>
                                            <tr>
                                                <td class="mailbox-name"> <?php echo $data['route_title'] ?></td>
                                                <td class="mailbox-name"> <?php echo $data['no_of_vehicle'] ?></td>
                                                <td class="mailbox-name text-right"> <?php echo ($currency_symbol . $data['fare']); ?></td>
                                            </tr>
                                            <?php
}
    $count++;
}
?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            </div>
        </div>
    </section>
</div>
