<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-download"></i> <?php //echo $this->lang->line('download_center'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line("content_list"); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible-lg">
                            <div class="download_label"><?php echo $this->lang->line("content_list"); ?></div>
                                          <div class="table-responsive mailbox-messages overflow-visible">
                                 <table class="table table-striped table-bordered table-hover content-list" data-export-title="<?php echo $this->lang->line('content_list'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('share_date'); ?></th>
                                        <th><?php echo $this->lang->line('valid_upto'); ?></th>
                                        <th><?php echo $this->lang->line('shared_by'); ?></th>
                                        <th class="pull-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
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

<script>
    ( function ( $ ) {
    'use strict';
    $(document).ready(function () {
        initDatatable('content-list','user/content/getsharelist',[],[],100,
            [                
                { "bSortable": true, "aTargets": [ 1,2,3 ] ,'sClass': 'dt-body-left',"sWidth": "20%"}
            ]);
    });
} ( jQuery ) )

</script>