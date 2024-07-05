<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-bus"></i> <?php echo $this->lang->line('transport'); ?> </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('edit_vehicle'); ?></h3>
                    </div>
                    <form id="form1" action="<?php echo site_url('admin/vehicle/edit/' . $id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) {
    ?>
                                <?php echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg'); ?>
                            <?php }?>
                            <?php
if (isset($error_message)) {
    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
}
?>
                            <?php echo $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="id" value="<?php echo set_value('id', $editvehicle->id); ?>" >
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('vehicle_no'); ?></label>
                                <input autofocus="" id="vehicle_no" name="vehicle_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('vehicle_no', $editvehicle->vehicle_no); ?>" />
                                <span class="text-danger"><?php echo form_error('vehicle_no'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('vehicle_model'); ?></label>
                                <input id="vehicle_model" name="vehicle_model" placeholder="" type="text" class="form-control"  value="<?php echo set_value('vehicle_model', $editvehicle->vehicle_model); ?>" />
                                <span class="text-danger"><?php echo form_error('vehicle_model'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('year_made'); ?> </label>
                                <input id="manufacture_year" name="manufacture_year" placeholder="" type="text" class="form-control"  value="<?php echo set_value('manufacture_year', $editvehicle->manufacture_year); ?>" />
                                <span class="text-danger"><?php echo form_error('manufacture_year'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('driver_name'); ?></label>
                                <input id="driver_name" name="driver_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('driver_name', $editvehicle->driver_name); ?>" />
                                <span class="text-danger"><?php echo form_error('driver_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('driver_license'); ?></label>
                                <input id=" driver_licence" name="driver_licence" placeholder="" type="text" class="form-control"  value="<?php echo set_value('driver_licence', $editvehicle->driver_licence); ?>" />
                                <span class="text-danger"><?php echo form_error('driver_licence'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('driver_contact'); ?></label>
                                <input id="driver_contact" name="driver_contact" placeholder="" type="text" class="form-control"  value="<?php echo set_value('driver_contact', $editvehicle->driver_contact); ?>" />
                                <span class="text-danger"><?php echo form_error('driver_contact'); ?></span>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('vehicle_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                            <div class="pull-right">
                            </div>
                        </div>
                        <div class="table-responsive mailbox-messages overflow-visible">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('vehicle_no'); ?></th>
                                        <th><?php echo $this->lang->line('vehicle_model'); ?></th>
                                        <th><?php echo $this->lang->line('year_made'); ?></th>
                                        <th><?php echo $this->lang->line('driver_name'); ?></th>
                                        <th><?php echo $this->lang->line('driver_license'); ?></th>
                                        <th><?php echo $this->lang->line('driver_contact'); ?></th>
                                        <th class="text-right no-print"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($listVehicle)) {
    ?>
                                        <tr>
                                            <td colspan="12" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                        </tr>
                                        <?php
} else {
    $count = 1;
    foreach ($listVehicle as $data) {
        ?>
                                            <tr>
                                                <td class="mailbox-name"> <?php echo $data['vehicle_no'] ?></td>
                                                <td class="mailbox-name"> <?php echo $data['vehicle_model'] ?></td>
                                                <td class="mailbox-name"> <?php echo $data['manufacture_year'] ?></td>      <td class="mailbox-name"> <?php echo $data['driver_name'] ?></td>
                                                <td class="mailbox-name"> <?php echo $data['driver_licence'] ?></td>
                                                <td class="mailbox-name"> <?php echo $data['driver_contact'] ?></td>
                                                <td class="mailbox-date pull-right no-print">
                                                    <a href="<?php echo base_url(); ?>admin/vehicle/edit/<?php echo $data['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="<?php echo base_url(); ?>admin/vehicle/delete/<?php echo $data['id'] ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                </td>
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