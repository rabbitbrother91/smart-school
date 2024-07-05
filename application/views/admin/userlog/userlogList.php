<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-line-chart"></i> <?php //echo $this->lang->line('reports'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row"> 

            <div class="col-md-12">
                <div class="nav-tabs-custom theme-shadow">
                    <ul class="nav nav-tabs pull-right">

                        <li><a href="#tab_parent" data-toggle="tab" data-list="parent-list"><?php echo $this->lang->line('parent'); ?></a></li>
                        <li><a href="#tab_student" data-toggle="tab" data-list="student-list"><?php echo $this->lang->line('students'); ?></a></li>

                        <li><a href="#tab_staff" data-toggle="tab" data-list="staff-list"><?php echo $this->lang->line('staff') ?></a></li>
                        <li class="active"><a href="#tab_allusers" data-toggle="tab" data-list="all-list"><?php echo $this->lang->line('all_users'); ?></a></li>

                        <li class="pull-left header"><?php echo $this->lang->line('user_log'); ?></li>
                    </ul>				
					
                    <div class="tab-content">					
						
						
                        <div class="tab-pane active table-responsive" id="tab_allusers">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<a class="btn btn-primary btn-sm pull-right checkbox-toggle clear_userlog" ><?php echo $this->lang->line('clear_userlog_record'); ?>  </a>
								</div>	
							</div>	
						</div>
                            <table class="table table-striped table-bordered table-hover all-list" data-export-title="<?php echo $this->lang->line('user_log'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('users'); ?></th>
                                        <th width="150"><?php echo $this->lang->line('role'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('ip_address'); ?></th>
                                        <th width="200"><?php echo $this->lang->line('login_date_time'); ?></th>
                                        <th><?php echo $this->lang->line('user_agent'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>


                        <!-- /.tab-pane -->
                        <div class="tab-pane table-responsive" id="tab_staff">
                            <table class="table table-striped table-bordered table-hover staff-list" data-export-title="<?php echo $this->lang->line('user_log'); ?>" data-target="staff-list">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('users'); ?></th>
                                        <th width="150"><?php echo $this->lang->line('role'); ?></th>
                                        <th><?php echo $this->lang->line('ip_address'); ?></th>
                                        <th width="200"><?php echo $this->lang->line('login_date_time'); ?></th>
                                        <th><?php echo $this->lang->line('user_agent'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane table-responsive" id="tab_student">
                            <table class="table table-striped table-bordered table-hover student-list" data-export-title="<?php echo $this->lang->line('user_log'); ?>" data-target="student-list">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('users'); ?></th>
                                        <th width="150"><?php echo $this->lang->line('role'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('ip_address'); ?></th>
                                        <th width="200"><?php echo $this->lang->line('login_date_time'); ?></th>
                                        <th><?php echo $this->lang->line('user_agent'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

                        <!-- /.tab-pane -->
                        <div class="tab-pane table-responsive" id="tab_parent">
                            <table class="table table-striped table-bordered table-hover parent-list" data-export-title="<?php echo $this->lang->line('user_log'); ?>" data-target="parent-list">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('users'); ?></th>
                                        <th width="150"><?php echo $this->lang->line('role'); ?></th>
                                        <th><?php echo $this->lang->line('ip_address'); ?></th>
                                        <th width="200"><?php echo $this->lang->line('login_date_time'); ?></th>
                                        <th><?php echo $this->lang->line('user_agent'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.tab-content -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
	$(function () {
		$('.clear_userlog').on('click', function () {			
			if (confirm("<?php echo $this->lang->line('user_log_delete') ?>")) {				
				$.ajax({
					url: '<?php echo base_url(); ?>admin/userlog/delete/',
					success: function (data) {
						if (data.status == "fail") {                        
							errorMsg(message);
						} else {
							successMsg(data.message);
							window.location.reload(true);
						}
					}
				});
			}
		});
	});
</script>
<!-- //========datatable start===== -->
<script type="text/javascript">

    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        var target_ = $(e.target).attr("href"); // activated tab
        var target = $(e.target).data('list'); // activated tab
        if (target == "staff-list") {
            initDatatable(target, 'admin/userlog/getStaffDatatable', [],[], 100);
        } else if (target == "student-list") {
            initDatatable(target, 'admin/userlog/getStudentDatatable', [],[], 100);
        } else if (target == "parent-list") {
            initDatatable(target, 'admin/userlog/getParentDatatable', [],[], 100);
        } else if (target == "all-list") {
            initDatatable(target, 'admin/userlog/getDatatable', [],[], 100);
        }

    });

    (function ($) {
        'use strict';
        $(document).ready(function () {
            initDatatable('all-list', 'admin/userlog/getDatatable', [],[], 100);
        });
    }(jQuery))
</script>