<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <i class="fa fa-credit-card"></i> <?php //echo $this->lang->line('transport'); ?></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('assign_vehicle', 'can_add')) { ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('assign_vehicle_on_route'); ?></h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo base_url() ?>admin/vehroute"  method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg'); ?>
                                <?php } ?>
                                <?php
                                if (isset($error_message)) {
                                    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                }
                                ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('route'); ?></label> <small class="req"> *</small>

                                    <select autofocus="" id="route_id" name="route_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($routelist as $route) {
                                            ?>
                                            <option value="<?php echo $route['id'] ?>"<?php
                                            if (set_value('route_id') == $route['id']) {
                                                echo "selected =selected";
                                            }
                                            ?>><?php echo $route['route_title'] ?></option>

                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('route_id'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('vehicle'); ?></label> <small class="req"> *</small>


                                    <?php
                                    foreach ($vehiclelist as $vehicle) {
                                        ?>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="vehicle[]" value="<?php echo $vehicle['id'] ?>" <?php echo set_checkbox('vehicle[]', $vehicle['id']); ?> ><?php echo $vehicle['vehicle_no'] ?>
                                            </label>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                    <span class="text-danger"><?php echo form_error('vehicle[]'); ?></span>
                                </div>

                            </div><!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>

                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('assign_vehicle', 'can_add')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('vehicle_route_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('vehicle_route_list'); ?></div>
                            <div class="table-responsive overflow-visible">  
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr>

                                            <th><?php echo $this->lang->line('route'); ?>
                                            </th>
                                            <th><?php echo $this->lang->line('vehicle'); ?>
                                            </th>

                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (empty($vehroutelist)) {
                                            ?>

                                            <?php
                                        } else {
                                            foreach ($vehroutelist as $vehroute) {

                                                ?>
                                                <tr>
                                                    <td class="mailbox-name">
                                                        <?php

                                                         echo $vehroute['route_title']; ?>

                                                    </td>


                                                    <td>
                                                        <?php
                                                        $vehicles = $vehroute['vehicles'];
                                                        if (!empty($vehicles)) {


                                                            foreach ($vehicles as $key => $value) {


                                                                echo "<div><b>" . $value->vehicle_no . "</b></div>";
                                                            }
                                                        }
                                                        ?>

                                                    </td>
                                                    <td class="mailbox-date pull-right">
                                                        <?php if ($this->rbac->hasPrivilege('assign_vehicle', 'can_edit')) { ?>
                                                            <a href="<?php echo base_url(); ?>admin/vehroute/edit/<?php echo $vehroute['id']; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        <?php } if ($this->rbac->hasPrivilege('assign_vehicle', 'can_delete')) { ?>
                                                            <a href="<?php echo base_url(); ?>admin/vehroute/delete/<?php echo $vehroute['id']; ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                <i class="fa fa-remove"></i>
                                                            </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>

                                    </tbody>
                                </table><!-- /.table -->
                            </div>


                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->


        </div>
        <div class="row">
            <!-- left column -->

            <!-- right column -->
            <div class="col-md-12">

            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

