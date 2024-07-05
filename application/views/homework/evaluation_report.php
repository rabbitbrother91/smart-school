<div class="row row-eq">
<?php
$admin = $this->customlib->getLoggedInUserData();
?>
    <div class="col-lg-8 col-md-8 col-sm-8 paddlr">
        <!-- general form elements -->
        <form id="evaluation_data" method="post" class="ptt10" style="min-height: 500px;">
            <div class="download_label"><?php echo $this->lang->line("homework_evaluation") . ' ' . $this->lang->line("report") . ' ' . $result[0]["class"] . "(" . $result[0]["section"] . ")  " . $result[0]["name"]; ?></div>
            <div class="table-responsive mailbox-messages">
                <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%" id="modaltable">
                    <thead>
                        <tr>
                            <th><?php echo $this->lang->line("admission_no") ?></th>
                            <th><?php echo $this->lang->line("name") ?></th>
                            <th><?php echo $this->lang->line("status") ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($studentlist as $key => $value) {  ?>
                            <tr>
                                <td><?php echo $value["admission_no"] ?></td>
                                <td><?php echo $value["firstname"] . " " . $value["lastname"] ?></td>
                                <td style="text-transform:capitalize;"> <?php echo searchForId($value["id"], $result); ?></td>
                            </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-eq">
        <div class="taskside">
            <h4><?php echo $this->lang->line('summary'); ?></h4>
            <div class="box-tools pull-right">
            </div><!-- /.box-tools -->
            <h5 class="pt0 task-info-created"></h5>
            <hr class="taskseparator" />
            <div class="task-info task-single-inline-wrap task-info-start-date">
                <h5><i class="fa task-info-icon fa-fw fa-lg fa-calendar-plus-o pull-left fa-margin"></i>
                    <span><?php echo $this->lang->line('homework_date'); ?><span>:<?php print_r(date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($result[0]['homework_date'])));?>
                            </h5>
                            </div>
                            <div class="task-info task-single-inline-wrap task-info-start-date">
                                <h5><i class="fa task-info-icon fa-fw fa-lg fa-calendar-plus-o pull-left fa-margin"></i>
                                    <span><?php echo $this->lang->line('submission_date'); ?><span>:<?php print_r(date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($result[0]['submit_date'])));?>
                                            </h5>
                                            </div>
                                            <div class="task-info task-single-inline-wrap task-info-start-date">
                                                <h5><i class="fa task-info-icon fa-fw fa-lg fa-calendar-plus-o pull-left fa-margin"></i>
                                                    <span><?php echo $this->lang->line('evaluation_date'); ?><span>:<?php print_r(date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($result[0]['date'])));?>
                                                            </h5>
                                                            </div>
                                                            <div class="task-info task-single-inline-wrap ptt10">
                                                                <label><span><?php echo $this->lang->line('created_by'); ?></span>: <?php echo $created_by; ?></label>
                                                                <label><span><?php echo $this->lang->line('evaluated_by'); ?></span>: <?php echo $evaluated_by; ?></label>
                                                                <label><span><?php echo $this->lang->line("class") ?></span>: <?php echo $result[0]['class']; ?></label>
                                                                <label><span><?php echo $this->lang->line("section") ?></span>: <?php echo $result[0]['section']; ?></label>
                                                                <label><span><?php echo $this->lang->line("subject") ?></span>: <?php echo $result[0]['name']; ?></label>

                                                                <?php if (!empty($result[0]["document"])) {?>
                                                                    <label><a href="<?php echo base_url() . "homework/download/" . $result[0]["id"] . "/" . $result[0]["document"] ?>"><?php echo $this->lang->line('download') ?> <i class="fa fa-download"></i></a></label>
                                                                        <?php }?>
                                                                <label><span><?php echo $this->lang->line('description'); ?></span>: <br/><?php echo $result[0]['description']; ?></label>

                                                            </div>
                                                            </div>
                                                            </div>
                                                            </div>
                                                            <?php

function searchForId($id, $array)
{
    foreach ($array as $key => $val) {
        if ($val['student_id'] === $id) {
            return "<label class='label label-success'>" . $val["status"] . "</label>";
        }
    }
    return "<label class='label label-danger'>Incomplete</label>";
}
?>
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