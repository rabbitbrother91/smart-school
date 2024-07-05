<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-empire"></i> <?php //echo $this->lang->line('front_cms'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">

            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary" id="holist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('event_list'); ?></h3>
                        <?php
                        if ($this->rbac->hasPrivilege('event', 'can_add')) {
                            ?> <div class="box-tools pull-right">
                                <a href="<?php echo site_url('admin/front/events/create'); ?>" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?></a>

                            </div>
                        <?php } ?>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('event_list'); ?></div>
                        <div class="mailbox-controls">
                            <div class="pull-right">
                            </div><!-- /.pull-right -->
                        </div>
                        <div class="mailbox-messages">
                            <div class="table-responsive overflow-visible">  
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
                                <?php } ?>

                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('title'); ?></th>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th><?php echo $this->lang->line('venue'); ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($listResult)) {
                                            ?>

                                            <?php
                                        } else {
                                            $count = 1;
                                            foreach ($listResult as $event) {
                                                ?>
                                                <tr>
                                                    <td class="mailbox-name">
                                                        <a href="#" data-toggle="popover" class="detail_popover"><?php echo $event['title'] ?></a>

                                                        <div class="fee_detail_popover" style="display: none">
                                                            <?php
                                                            if ($event['description'] == "") {
                                                                ?>
                                                                <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <p class="text text-info"><?php echo $event['description']; ?></p>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </td>
                                                    <td class="mailbox-name"> <?php
                                                        $start = strtotime($event['event_start']);
                                                        $end = strtotime($event['event_end']);
                                                        if ($event['event_start'] != "" && $event['event_end'] != "") {

                                                            if ($start == $end) {
                                                                echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($event['event_start']));
                                                            } else {

                                                                echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($event['event_start'])) . " - " . date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($event['event_end']));
                                                            }
                                                        } elseif ($event['event_start'] != "" && $event['event_end'] == "") {
                                                            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($event['event_start']));
                                                        }
                                                        ?></td>


                                                    <td class="mailbox-name"> <?php echo $event['event_venue'] ?></td>

                                                    <td class="mailbox-date pull-right no-print">
                                                        <?php
                                                        if ($this->rbac->hasPrivilege('event', 'can_edit')) {
                                                            ?>
                                                            <a href="<?php echo site_url('admin/front/events/edit/' . $event['slug']); ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>

                                                            <?php
                                                        }
                                                        if ($this->rbac->hasPrivilege('event', 'can_delete')) {
                                                            ?>
                                                            <a href="<?php echo site_url('admin/front/events/delete/' . $event['slug']); ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                <i class="fa fa-remove"></i>
                                                            </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            $count++;
                                        }
                                        ?>
                                    </tbody>
                                </table><!-- /.table -->
                            </div>  
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
        </div>
        <div class="row">
            <div class="col-md-12">
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
