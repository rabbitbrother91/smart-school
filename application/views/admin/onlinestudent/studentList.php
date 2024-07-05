<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat();?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('student_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                      <div class="table-responsive">
                        <div class="mailbox-messages">
                             <?php if ($this->session->flashdata('msg')) {
    echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg');}?>
                            <table class="table table-striped table-bordered table-hover student-list" data-export-title="<?php echo $this->lang->line('student_list'); ?>">
                                <thead>
                                    <tr>
                                        <th style="width:5%"><?php echo $this->lang->line('reference_no'); ?></th>
                                        <th><?php echo $this->lang->line('student_name'); ?></th>
                                        <th class="white-space-nowrap"><?php echo $this->lang->line('class'); ?></th>
                                         <?php if ($sch_setting->father_name) {?>
                                            <th><?php echo $this->lang->line('father_name'); ?></th>
                                        <?php }?>
                                        <th><?php echo $this->lang->line('date_of_birth'); ?></th>
                                        <th><?php echo $this->lang->line('gender'); ?></th>
                                        <th><?php echo $this->lang->line('category'); ?></th>
                                          <?php if ($sch_setting->mobile_no) {?>
                                        <th style="width:10%"><?php echo $this->lang->line('student_mobile_number'); ?></th>
                                       <?php }?>
                                        <th><?php echo $this->lang->line('form_status'); ?></th>
                                        <?php if ($sch_setting->online_admission_payment == 'yes') {?>
                                            <th><?php echo $this->lang->line('payment_status'); ?></th>
                                            <?php }?>
                                        <th><?php echo $this->lang->line('enrolled'); ?></th>
                                        <th><?php echo $this->lang->line('created_at'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                       </div><!--./table-responsive-->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
    ( function ( $ ) {
    'use strict';
    $(document).ready(function () {
        initDatatable('student-list','admin/onlinestudent/getstudentlist',[],[],100);
    });
} ( jQuery ) )
</script>

<script>
    function checkpaymentstatus(id){
       $.ajax({
            url: '<?php echo base_url(); ?>admin/onlinestudent/checkpaymentstatus',
            type: "POST",
            data: {id:id},
            success: function (data) {

               if(data!=""){
                    if(confirm(data)){
                      window.location.href="<?php echo base_url() . 'admin/onlinestudent/edit/' ?>"+id ;
                    }else{
                         return false ;
                    }
                }else{
                     window.location.href="<?php echo base_url() . 'admin/onlinestudent/edit/' ?>"+id ;
                }
            }
        });
    }
</script>