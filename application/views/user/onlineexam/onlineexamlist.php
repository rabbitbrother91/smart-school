<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
            </div>
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('online_exam'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="nav-tabs-custom mb5">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#exam" data-toggle="tab"><?php echo $this->lang->line('upcoming_exams'); ?></a></li>
                            <li><a href="#closed-exam" class="closed-exam" data-toggle="tab"><?php echo $this->lang->line('closed_exam'); ?></a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                    <div class="tab-pane active" id="exam">
                    <div class="box-body ">
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover exam-list" data-export-title="<?php echo $this->lang->line('online_exam'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('exam'); ?></th>
                                        <th><?php echo $this->lang->line('quiz'); ?></th>
                                        <th><?php echo $this->lang->line('date_from'); ?></th>
                                        <th><?php echo $this->lang->line('date_to'); ?></th>
                                        <th><?php echo $this->lang->line('duration'); ?></th>
                                        <th><?php echo $this->lang->line('total_attempt'); ?></th>
                                        <th><?php echo $this->lang->line('attempted'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                    <div class="tab-pane" id="closed-exam">
                    <div class="box-body ">
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover closed-exam-list" data-export-title="<?php echo $this->lang->line('online_exam'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('exam'); ?></th>
                                        <th><?php echo $this->lang->line('quiz'); ?></th>
                                        <th><?php echo $this->lang->line('date_from'); ?></th>
                                        <th><?php echo $this->lang->line('date_to'); ?></th>
                                        <th><?php echo $this->lang->line('duration'); ?></th>
                                        <th><?php echo $this->lang->line('total_attempt'); ?></th>
                                        <th><?php echo $this->lang->line('attempted'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
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
    ( function ( $ ) {
    'use strict';
    $(document).ready(function () {
      initDatatable('exam-list','user/onlineexam/getexamlist',[],[],100);
       $("a[href='#closed-exam']").on('shown.bs.tab', function (e) {
           initDatatable('closed-exam-list','user/onlineexam/getclosedexamlist',[],[],100);
        });
    });
} ( jQuery ) )
</script>
