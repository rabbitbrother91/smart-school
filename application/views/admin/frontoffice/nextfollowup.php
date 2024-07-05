<div class="content-wrapper" style="min-height: 348px;">  
    <section class="content-header">
        <h1><i class="fa fa-phone"></i> Enquiry --r</h1>
    </section>
    <!-- Main content -->

    <?php
    $response = $this->customlib->getResponse();
    $enquiry_type = $this->customlib->getenquiryType();
    $Source = $this->customlib->getComplaintSource();
    $Reference = $this->customlib->getReference();
    $admin = $this->customlib->getLoggedInUserData();
    ?>
    <!-- New Desgine start -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Follow Up's of(<?php echo $enquiry_data['name']; ?>)</h3>
                    </div><!-- /.box-header -->

                    <form id="form1" action="<?php echo site_url('admin/enquiry/follow_up/' . $id) ?>"   method="post" >
                        <div class="box-body">                  
                             
                            <div class="form-group">
                                <label for="exampleInputEmail1">Follow Up Date --r</label>
                                <input type="text" id="date" name="date" class="form-control" value="<?php echo set_value('date'); ?>" readonly="">
                                <span class="text-danger"><?php echo form_error('date'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="pwd">Next Follow-Up Date --r</label>
                                <input type="text" id="date_of_call" name="follow_up_date"class="form-control" value="<?php echo set_value('follow_up_date'); ?>" readonly="">
                            </div>
                            <div class="form-group">
                                <label for="pwd">Response --r</label>  
                                <textarea name="response" class="form-control" ><?php echo set_value('response'); ?></textarea>   
                                <span class="text-danger"><?php echo form_error('response'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="pwd"><?php echo $this->lang->line('note'); ?></label> 
                                <textarea name="note" class="form-control" ><?php echo set_value('note'); ?></textarea>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div><!--/.col (right) -->
            <!-- left column -->
            <div class="col-md-8">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> Follow Up's List of (<?php echo $enquiry_data['name']; ?>) --r</h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="box-body">
                            <div class="tab-pane active" id="timeline">
                                <!-- The timeline -->
                                <ul class="timeline timeline-inverse">
                                    <?php
                                    if (empty($follow_up_list)) {
                                        ?>

                                        <?php
                                    } else {
                                        foreach ($follow_up_list as $key => $value) {
                                            ?>
                                            <!-- timeline time label -->

                                            <li class="time-label">
                                                <span class="bg-blue">
                                                    <?php
                                                    echo $value['date'];
                                                    ?>
                                                </span>
                                            </li>
                                            <!-- /.timeline-label -->
                                            <!-- timeline item -->
                                            <li>
                                                <i class="fa fa-phone bg-blue"></i>
                                                <div class="timeline-item">
                                                    <span class="time"><i class="fa fa-clock-o"></i> <?php echo $value['next_date']; ?></span>

                                                    <h3 class="timeline-header"><a href="#"> <?php echo $value['followup_by']; ?></a> </h3>

                                                    <div class="timeline-body">
                                                        <?php echo $value['response']; ?> 
                                                        <hr>
                                                        <?php echo $value['note']; ?> 
                                                    </div>
                                                    <div class="timeline-footer">
                                                        <a href="<?php echo base_url(); ?>admin/enquiry/follow_up_edit/<?php echo $id; ?>/<?php echo $value['id']; ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="" data-original-title="Edit">
                                                            Edit
                                                        </a>
                                                        <a href="<?php echo base_url(); ?>admin/enquiry/follow_up_delete/<?php echo $id; ?>/<?php echo $value['id']; ?>" class="btn btn-danger btn-xs" data-toggle="tooltip" title="" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');" data-original-title="Delete">

                                                            Delete</a>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!-- new END -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $('#date').datepicker({
            format: date_format,
            autoclose: true
        });
    });

    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';

        $('#date_of_call').datepicker({           
            format: date_format,
            autoclose: true
        });
    });

</script>
