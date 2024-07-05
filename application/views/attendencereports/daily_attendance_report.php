<style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-calendar-check-o"></i> <?php //echo $this->lang->line('attendance'); ?> <small> <?php //echo $this->lang->line('by_date1'); ?></small>        </h1>
    </section>
    <section class="content">
        <?php $this->load->view('attendencereports/_attendance'); ?>
        <div class="row">   
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form id='form1' action="<?php echo site_url('attendencereports/daily_attendance_report') ?>"  method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label><span class="req"> *</span>
                                        <input type="text" name="date" value="<?php echo set_value('date', $date); ?>" class="form-control date">

                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>  
                                </div>
                            </div>
                        </div>  
                    </form>
                    <div class="">
                        <div class="box-header ptbnull"></div>
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-money"></i>
                                <?php echo $this->lang->line('daily_attendance_report') ?></h3>
                        </div>
                        <div class="box-body table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('daily_attendance_report'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr> 
                                        <th><?php echo $this->lang->line('class_section'); ?></th>
                                        <th><?php echo $this->lang->line('total_present'); ?></th>
                                        <th><?php echo $this->lang->line('total_absent'); ?></th>
                                        <th><?php echo $this->lang->line('present') . " %"; ?></th>
                                        <th><?php echo $this->lang->line('absent') . " %"; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($result)) {
                                        foreach ($resultlist as $key => $value) {
                                            ?>
                                            <tr>
                                                <td><?php echo $value['class_section'] ?></td>
                                                <td><?php echo $value['total_present'] ?></td>
                                                <td><?php echo $value['total_absent'] ?></td>
                                                <td><?php echo $value['present_percent'] ?></td>
                                                <td><?php echo $value['absent_persent'] ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                         <tr style="font-weight: bold;">
                                            <td></td>
                                            <td><?php echo $all_present ?></td>
                                            <td><?php echo $all_absent ?></td>
                                            <td><?php echo $all_present_percent ?></td>
                                            <td><?php echo $all_absent_percent ?></td>
                                        </tr>                                      
                                </tbody>                                 
                                    <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div> 
    </section>
</div>