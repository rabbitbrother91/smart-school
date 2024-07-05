<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-map-o"></i> <?php echo $this->lang->line('examinations'); ?> <small><?php echo $this->lang->line('student_fee1'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $exam['name'] . ' ' . $this->lang->line('exam_status'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <div class="pull-right">
                            </div><!-- /.pull-right -->
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <?php
if (!empty($classsectionList)) {
    ?>
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('class'); ?>(<?php echo $this->lang->line('section'); ?>)</th>
                                            <th class="text text-center"><?php echo $this->lang->line('exam_scheduled'); ?></th>
                                            <th class="text text-right"><?php echo $this->lang->line('marks_register_prepared'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
$count = 1;
    foreach ($classsectionList as $clssection) {
        ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <strong><?php echo $clssection['class'] . "(" . $clssection['section'] . ")" ?></strong>
                                                </td>
                                                <td  class="text text-center">
                                                    <span class="label label-success"><?php echo $this->lang->line('yes'); ?></span><br/>
                                                    <a href="#"  class="schedule_modal" data-toggle="tooltip" title="<?php echo $this->lang->line('view_schedule'); ?>"  data-examid="<?php echo $clssection['exam_id']; ?>" data-original-title="<?php echo $this->lang->line('view_detail'); ?>" data-sectionid="<?php echo $clssection['section_id'] ?>" data-classid="<?php echo $clssection['class_id'] ?>" data-classname="<?php echo $clssection['class'] ?>"  data-sectionname="<?php echo $clssection['section'] ?>">
                                                        <i class="fa fa-calendar-times-o"></i>  <?php echo $this->lang->line('view_schedule'); ?>
                                                    </a><br>
                                                </td>
                                                <td  class="text pull-right">
                                                    <?php
if ($clssection['result_prepare'] == "yes") {
            ?>
                                                        <span class="label label-success"><?php echo $this->lang->line('yes'); ?></span>
                                                        <?php
} else {
            ?>
                                                        <span class="label label-danger"><?php echo $this->lang->line('no'); ?></span>
                                                        <?php
}
        ?>
                                                </td>
                                            </tr>
                                            <?php
$count++;
    }
    ?>
                                    </tbody>
                                </table><!-- /.table -->
                                <?php
} else {
    ?>
                                <div class="">
                                    <div class="alert alert-info"><?php echo $this->lang->line('exam_not_allotted'); ?></div>
                                </div>
                                <?php
}
?>
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <div class="pull-right">
                            </div><!-- /.pull-right -->
                        </div>
                    </div>
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
        <div class="row">
            <!-- left column -->
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <!-- general form elements disabled -->
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    $(document).on('click', '.schedule_modal', function () {
        $('.modal-title').html("");
        var examname = '<?php echo $exam['name'] ?>';
        var exam_id = $(this).data('examid');
        var section_id = $(this).data('sectionid');
        var class_id = $(this).data('classid');
        var classname = $(this).data('classname');
        var sectionname = $(this).data('sectionname');
        $('.modal-title').html("<?php echo $this->lang->line('exam'); ?> " + examname);
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            type: "post",
            url: base_url + "admin/examschedule/getexamscheduledetail",
            data: {'exam_id': exam_id, 'section_id': section_id, 'class_id': class_id},
            dataType: "json",
            success: function (response) {
                var data = "";
                data += '<div class="table-responsive">';
                data += "<p class='lead titlefix pt0'><?php echo $this->lang->line('class'); ?>: " + classname + "(" + sectionname + ")</p>";
                data += '<table class="table table-hover sss">';
                data += '<thead>';
                data += '<tr>';
                data += "<th><?php echo $this->lang->line('subject'); ?></th>";
                data += "<th><?php echo $this->lang->line('date'); ?></th>";
                data += "<th class='text text-center'><?php echo $this->lang->line('start_time'); ?></th>";
                data += "<th class='text text-center'><?php echo $this->lang->line('end_time'); ?></th>";
                data += "<th class='text text-center'><?php echo $this->lang->line('room'); ?></th>";
                data += "<th class='text text-center'><?php echo $this->lang->line('full_marks'); ?></th>";
                data += "<th class='text text-center'><?php echo $this->lang->line('passing_marks'); ?></th>";
                data += '</tr>';
                data += '</thead>';
                data += '<tbody>';
                $.each(response, function (i, obj)
                {
                    data += '<tr>';
                    data += '<td class="">' + obj.name + ' (' + obj.type.substring(2, 0) + '.)</td>';
                    data += '<td class="">' + obj.date_of_exam + '</td> ';
                    data += '<td style="width:200px;" class="text text-center">' + obj.start_to + '</td> ';
                    data += '<td style="width:200px;" class="text text-center">' + obj.end_from + '</td> ';
                    data += '<td class="text text-center">' + obj.room_no + '</td> ';
                    data += '<td class="text text-center">' + obj.full_marks + '</td>';
                    data += '<td class="text text-center">' + obj.passing_marks + '</td>';
                    data += '</tr>';
                });
                data += '</tbody>';
                data += '</table>';
                data += '</div>  ';
                $('.modal-body').html(data);
                //===========

                var dtable = $('.sss').DataTable();
                $('div.dataTables_filter input').attr('placeholder', 'Search...');
                new $.fn.dataTable.Buttons(dtable, {

                    buttons: [

                        {
                            extend: 'copyHtml5',
                            text: '<i class="fa fa-files-o"></i>',
                            titleAttr: 'Copy',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },

                        {
                            extend: 'excelHtml5',
                            text: '<i class="fa fa-file-excel-o"></i>',
                            titleAttr: 'Excel',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },

                        {
                            extend: 'csvHtml5',
                            text: '<i class="fa fa-file-text-o"></i>',
                            titleAttr: 'CSV',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },

                        {
                            extend: 'pdfHtml5',
                            text: '<i class="fa fa-file-pdf-o"></i>',
                            titleAttr: 'PDF',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },

                        {
                            extend: 'print',
                            text: '<i class="fa fa-print"></i>',
                            titleAttr: 'Print',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },

                        {
                            extend: 'colvis',
                            text: '<i class="fa fa-columns"></i>',
                            titleAttr: 'Columns',
                            postfixButtons: ['colvisRestore']
                        },
                    ]
                });

                dtable.buttons(0, null).container().prependTo(
                        dtable.table().container()
                        );

//===========
                $("#scheduleModal").modal('show');
            }
        });
    });
</script>

<div id="scheduleModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>