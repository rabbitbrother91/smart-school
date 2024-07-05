<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-ioxhost"></i> <?php //echo $this->lang->line('front_office'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('visitor_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('visitor_list'); ?></div>
                        <div class="mailbox-messages table-responsive overflow-visible-lg">
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('purpose'); ?></th>
                                        <th><?php echo $this->lang->line('visitor_name'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th><?php echo $this->lang->line('id_card'); ?></th>
                                        <th><?php echo $this->lang->line('number_of_person'); ?></th>
                                        <th width="35%"><?php echo $this->lang->line('note'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('in_time'); ?></th>
                                        <th><?php echo $this->lang->line('out_time'); ?></th>
                                        <th class="noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
if (empty($visitor_list)) {

} else {
    foreach ($visitor_list as $key => $value) {?>
                                            <tr>
                                                <td class="mailbox-name"><?php echo $value['purpose']; ?></td>
                                                <td class="mailbox-name"><?php echo $value['name']; ?><?php if ($value['email'] != '') {?> <br><a href="mailto:<?php echo $value['email']; ?>">(<?php echo $value['email']; ?>)</a> <?php }?></td>
                                                <td class="mailbox-name"><?php echo $value['contact']; ?></td>
                                                <td class="mailbox-name"><?php echo $value['id_proof']; ?></td>
                                                <td class="mailbox-name"><?php echo $value['no_of_people']; ?></td>
                                                <td class="mailbox-name"><?php echo $value['note']; ?></td>
                                                <td class="mailbox-name"> <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['date'])); ?></td>
                                                <td class="mailbox-name"> <?php echo $value['in_time']; ?></td>
                                                <td class="mailbox-name"> <?php echo $value['out_time']; ?></td>
                                                <td class="mailbox-name">
                                                    <?php if ($value['image'] != "") {?>
                                                        <a href="<?php echo base_url(); ?>user/visitors/download/<?php echo $value['id']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('download'); ?>">
                                                            <i class="fa fa-download"></i>
                                                        </a>  <?php }?>
                                                </td>
                                            </tr>
                                            <?php
}
}
?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) col-8 end-->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->