<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-warning">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('exam_schedule'); ?> </h3>
                        <div class="box-tools pull-right"></div>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="download_label"><?php echo $this->lang->line('exam_schedule'); ?></div>
                        <table class="table table-striped table-bordered table-hover example">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('s_no'); ?></th>
                                    <th><?php echo $this->lang->line('exam'); ?></th>
                                    <th><?php echo $this->lang->line('description'); ?></th>
                                    <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php  if (empty($examSchedule)) {
    ?>
                                    <?php
} else {
    $count = 1;
    foreach ($examSchedule as $exam) {
        ?>
                                        <tr>
                                            <td><?php echo $count; ?>.</td>
                                            <td><?php echo $exam->exam; ?></td>
                                            <td><?php echo $exam->description; ?></td>
                                            <td class="pull-right">
                                                <a  class="btn btn-primary btn-xs schedule_modal" data-toggle="tooltip" title="" data-examname="<?php echo $exam->exam; ?>" data-examid="<?php echo $exam->exam_group_class_batch_exam_id; ?>" >
                                                    <i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
$count++;
    }
}
?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="scheduleModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">    
        <div class="modal-content" id="tabledata">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <div class="box-tools pull-right">
                     <div class="dt-buttons btn-group btn-group2 pt5">
                    
                        <a class="dt-button btn btn-default btn-xs no_print" data-toggle="tooltip"  title="<?php echo $this->lang->line('print'); ?>" id="print" onclick="printDiv()" ><i class="fa fa-print"></i></a>
                     
                     </div>
                  </div>
                <h4 class="modal-title"></h4>
                
                
                
            </div>
            <div class="modal-body" >
            </div>
             
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
   function printDiv() {
    document.getElementById("print").style.display = "none";
    
    $('.bg-green').removeClass('label');
    $('.label-danger').removeClass('label');
    $('.label-success').removeClass('label');
    $('.modal-footer').addClass('hide');
    
    var divElements = document.getElementById('tabledata').innerHTML;
    var oldPage = document.body.innerHTML;
    document.body.innerHTML =
    "<html><head><title></title></head><body>" +
    divElements + "</body>";
    window.print();
    document.body.innerHTML = oldPage;
    
    location.reload(true);
   }
</script>
<script>
    $(document).ready(function () {
        $('#scheduleModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });
</script>
<script type="text/javascript">
    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'MM', 'Y' => 'yyyy']) ?>';
    $(document).on('click', '.schedule_modal', function () {
        $('.modal-title').html("");
        var exam_id = $(this).data('examid');
        var examname = $(this).data('examname');

        $('.modal-title').html(examname);
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            type: "post",
            url: base_url + "user/examschedule/getexamscheduledetail",
            data: {'exam_id': exam_id},
            dataType: "json",
            success: function (response) {
                $('.modal-body').html(response.result);
                $("#scheduleModal").modal('show');         
 

            }
        });
    });
</script>