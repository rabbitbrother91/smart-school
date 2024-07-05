<div class="row row-eq">
<?php
    $admin = $this->customlib->getLoggedInUserData();
?>
    <div class="col-lg-8 col-md-8 col-sm-8 paddlr">      
        <!-- general form elements -->
        <form id="upload" method="post" class="ptt10" style="min-height: 500px;">
            <div class="scroll-area">  
            <div class="form-group">
                <label><?php echo $this->lang->line('description'); ?></label>
            </div>
            <p><?php echo $result['description']; ?></p>
            <hr>
            <input type="hidden" name="homework_id" value="<?php echo $homework_id; ?>">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="pwd"><?php echo $this->lang->line('message'); ?></label><small class="req"> *</small>
                                <textarea id="assigment_message" name="message" class="form-control"><?php if (!empty($homeworkdocs[0]['message'])){ echo $homeworkdocs[0]['message']; } ?></textarea>
                            </div>
                        </div>                       
                            <?php if ($homework_status != 1) { ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('attach_document'); ?></label>
                                        <input type="file"  id="file" name="file" class="form-control filestyle">
                                    </div>
                                </div>
                             <?php } ?>  
                                
                              
                        
                    </div>  
                </div>
            </div>
            <?php if ($homework_status != 1) { ?>
            <div class="row">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-info" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </div>
            <?php } ?>
            </div>  
            <?php if (!empty($homeworkdocs[0]['docs'])) { ?>

            <div class="row">
            <div class="col-md-12 ptt10">
                <h4 class="box-title bmedium mb0 font16"><?php echo $this->lang->line('uploaded_document'); ?></h4>  
                <ul class="list-group content-share-list">                        
                <li class="list-group-item overflow-hidden mb5">

                    <img src="<?php echo base_url('backend/images/upload-file.png'); ?>">
                    <?php echo $this->media_storage->fileview($homeworkdocs[0]['docs']) ?>
                    <a href="<?php echo base_url(); ?>user/homework/assigmnetDownload/<?php echo $homework_id; ?>"  data-toggle="tooltip" data-placement="right" data-original-title=""> <i class="fa fa-download"></i></a>
                
                </li>
                </ul>
            </div>
        </div>
              <?php
                                }
                                ?>
        </form> 
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-eq">
        <div class="scroll-area">
            <div class="taskside">                
                <h4><?php echo $this->lang->line('summary'); ?></h4>
                <div class="box-tools pull-right">
                </div><!-- /.box-tools -->
                <h5 class="pt0 task-info-created">
                     
                </h5>
                <hr class="taskseparator" />
                <div class="task-info task-single-inline-wrap task-info-start-date">
                    <h5><i class="fa task-info-icon fa-fw fa-lg fa-calendar-plus-o pull-left fa-margin"></i>
                        <span><?php echo $this->lang->line('homework_date'); ?></span>:<?php echo $this->customlib->dateformat($result['homework_date']); ?>
                    </h5>
                </div>
                <div class="task-info task-single-inline-wrap task-info-start-date">
                    <h5><i class="fa task-info-icon fa-fw fa-lg fa-calendar-plus-o pull-left fa-margin"></i>
                        <span><?php echo $this->lang->line('submission_date'); ?></span>:<?php echo $this->customlib->dateformat($result['submit_date']); ?>
                    </h5>
                </div>
                <div class="task-info task-single-inline-wrap task-info-start-date">
                    <h5><i class="fa task-info-icon fa-fw fa-lg fa-calendar-plus-o pull-left fa-margin"></i>
                        <span>
                            <?php echo $this->lang->line('evaluation_date'); ?></span>:
                        <?php
                        $evl_date = "";
                        if (!IsNullOrEmptyString($result['evaluation_date'])) {
                            echo $this->customlib->dateformat($result['evaluation_date']);
                        }
                        ?>
                    </h5>
                </div>

                <div class="task-info task-single-inline-wrap ptt10">
                    <label><span><?php echo $this->lang->line('created_by'); ?></span>: <?php echo $created_by; ?>  </label>
                    <label><span><?php echo $this->lang->line('evaluated_by'); ?></span>: <?php echo $evaluated_by; ?> </label>
                    <label><span><?php echo $this->lang->line("class") ?></span>: <?php echo $result['class']; ?></label>
                    <label><span><?php echo $this->lang->line("section") ?></span>: <?php echo $result['section']; ?></label>
                    <label><span><?php echo $this->lang->line("subject") ?></span>: <?php echo $result['name']; ?> <?php if($result['code']) { echo '('.$result['code'].')'; } ?></label>
                    <label><?php echo $this->lang->line("status") ?>: <?php
                        if ($homework_status == 1) {
                            $status_lable = $this->lang->line("evaluated");
                            $class = "class= 'label label-success'";
                        } else {                       

                            if ($status == 'submitted') {
                                $class = "class= 'label label-warning'";
                                $status_lable = $this->lang->line('submitted');
                            }else{
                                $class = "class= 'label label-danger'";
                                $status_lable = $this->lang->line("pending");
                            }
                        }
                        echo "<font $class >" . $status_lable . "</font>";
                        ?>
                    </label>

                    <?php                   

                    if (!empty($result["document"])) { ?>
                        <label><?php echo $this->lang->line('homework_documents'); ?></label>

                             <ul class="list-group content-share-list">                        
                <li class="overflow-hidden mb5">

              <img src="<?php echo base_url('backend/images/upload-file.png'); ?>">
              <?php echo $this->media_storage->fileview($result['document']) ?> 
            <a href="<?php echo base_url() . "user/homework/download/" . $result["id"] ?>" data-toggle="tooltip" data-original-title=""><i class="fa fa-download"></i></a>               
                        
                    </li>
                                </ul>
                        
                    <?php } ?>
                </div>
            </div>
        </div>    
    </div>
</div>
<?php

function searchForId($id, $array) {
    foreach ($array as $key => $val) {
        if ($val['student_id'] === $id) {
            return "<label class='label label-success'>" . $val["status"] . "</label>";
        }
    }
    return "<label class='label label-danger'>".$this->lang->line('incomplete')."</label>";
}
?>

<script type="text/javascript">
    
       $('.filestyle').dropify();

</script>
<script>
    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
        $('#evaluation_date').datepicker({
            format: date_format,
            autoclose: true
        });
    });

    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
        $('#follow_date_of_call').datepicker({
            format: date_format,
            autoclose: true
        });

        $("#modaltable").DataTable({
            dom: "Bfrtip",
            buttons: [

                {
                    extend: 'copyHtml5',
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: 'Copy',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',

                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: 'CSV',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'

                    }
                },

                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: 'Print',
                    title: $('.download_label').html(),
                    customize: function (win) {
                        $(win.document.body)
                                .css('font-size', '10pt');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                    },
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'colvis',
                    text: '<i class="fa fa-columns"></i>',
                    titleAttr: 'Columns',
                    title: $('.download_label').html(),
                    postfixButtons: ['colvisRestore']
                },
            ]
        });
    });
</script>

<script>
$("#upload").on('submit', (function (e) {
        e.preventDefault();

        var $this = $(this).find("button[type=submit]:focus");

        $.ajax({
            url: "<?php echo site_url("user/homework/upload_docs") ?>",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $this.button('loading');

            },
            success: function (res)
            { 
                if (res.status == "fail") {
                    var message = "";
                    $.each(res.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);

                } else {
                    successMsg(res.message);
                    window.location.reload(true);
                }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }

        });
    }));
</script>