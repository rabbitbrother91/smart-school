<div class="wrapper">
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <section class="content-header">
            <h1>
                
<?php
$date = new DateTime('2018-05-01', new DateTimeZone('Asia/Kolkata'));
$time = $date->format('Y-m-d H:i:sP');
?>
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- /.col -->
                <div class="col-md-9 col-sm-9">
                    <div class="box box-primary">
                        <div class="box-body">
                            <!-- THE CALENDAR -->
                            <div id="calendar"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /. box -->
                </div>
                <div class="col-md-3 col-sm-3">
                    <div class="box box-primary">
                        <div class="box-header ptbnull">
                            <h3 class="box-title"><?php echo $this->lang->line('to_do_list'); ?></h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-primary btn-sm pull-right" onclick="add_task()"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="">
                            <?php foreach ($tasklist as $taskkey => $taskvalue) {
    ?>
                                <div class="media mt5" style="padding:0 10px;">
                                    <div class="media-left">
                                        <input type="checkbox" <?php
if ($taskvalue["is_active"] == 'yes') {
        echo "checked";
    }
    ?> id="check<?php echo $taskvalue["id"] ?>" onclick="markcomplete('<?php echo $taskvalue["id"] ?>')" name="eventcheck"  value="<?php echo $taskvalue["id"]; ?>">
                                    </div>
                                    <div class="media-body">
                                        <p class="tododesc" <?php if ($taskvalue["is_active"] == 'yes') {
        ?> style="text-decoration: line-through;color: #4f881d;" <?php }?> ><?php echo $taskvalue["event_title"]; ?></p>
                                        <small class="tododate">
                                            <?php echo $this->customlib->dateformat($taskvalue["start_date"]); ?>
                                            <a href="#" onclick="deleteevent('<?php echo $taskvalue["id"]; ?>', 'Task'); return false;" class="pull-right text-muted"><i class="fa fa-remove"></i></a><a href="#" onclick="edit_todo_task('<?php echo $taskvalue["id"]; ?>'); return false;" class="pull-right text-muted mright5" style="margin-right: 5px"><i class="fa fa-pencil"></i></a></small>
                                    </div>
                                </div>
                                <div class="todo_divider"></div>
                            <?php }?>
                            <div class="todopagination"><?php echo $this->pagination->create_links(); ?></div>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
</div>
<div id="newTask" class="modal fade " role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form role="form"  id="addtodo_form" method="post" enctype="multipart/form-data" action="">
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('title'); ?><small class="req"> *</small></label>
                            <input class="form-control" name="task_title"  id="task-title">
                            <span class="text-danger"><?php echo form_error('title'); ?></span>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?><small class="req"> *</small></label>
                            <input class="form-control" type="text" autocomplete="off"  name="task_date" placeholder="<?php echo $this->lang->line('date')?>" id="task-date">
                            <input class="form-control" type="hidden" name="eventid" id="taskid">
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="submit" class="btn btn-primary submit_addtask pull-right" value="<?php echo $this->lang->line('save'); ?>">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ./wrapper -->
<?php
$language = $this->customlib->getuserLanguage();
$language_name = $language["short_code"];
?>
<!--language js-->
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/bootstrap-select.min.js"></script>
<?php if ($language_name != 'en') {
    ?>
    <script src="<?php echo base_url(); ?>backend/dist/js/locale/<?php echo $language_name ?>.js"></script>
<?php } ?>
<script src="<?php echo base_url() ?>backend/fullcalendar/dist/fullcalendar.min.js"></script>
<script src="<?php echo base_url() ?>backend/fullcalendar/dist/locale-all.js"></script>
<?php if ($language_name != 'en') { ?>
    <script src="<?php echo base_url() ?>backend/fullcalendar/dist/locale/<?php echo $language_name ?>.js"></script>
<?php } ?>

<script type="text/javascript">
                                                var date = new Date();
                                                var today_date = new Date(date.getFullYear(), date.getMonth(), date.getDate());
                                                $(document).ready(function () {
                                                    var calendar_date_time_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'DD', 'm' => 'MM', 'M' => 'MMM', 'Y' => 'YYYY']) ?>';
                                                    var date_format = '<?php echo $result               = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
                                                    $('#task-date').datepicker({
                                                        format: date_format,
                                                        autoclose: true,
                                                        todayHighlight: true,
                                                            weekStart : start_week,
                                                    }).datepicker('setDate', today_date);

                                                });

                                                function add_task() {

                                                    $("#modal-title").html("<?php echo $this->lang->line('add_task')?>");
                                                    $("#task-title").val('');
                                                    $("#taskid").val('');
                                                    $('#newTask').modal('show');
                                                    $('#task-date').datepicker('setDate', today_date);



                                                }

</script>
<!-- Page specific script -->
<script>
    $calendar = $('#calendar');
    var base_url = '<?php echo base_url() ?>';
    today = new Date();
    y = today.getFullYear();
    m = today.getMonth();
    d = today.getDate();
    var viewtitle = 'month';
    var pagetitle = "<?php
if (isset($title)) {
    echo $title;
}
?>";

    if (pagetitle == "Dashboard") {

        viewtitle = 'agendaWeek';
    }

    $calendar.fullCalendar({
        viewRender: function (view, element) {

        },

        header: {
            center: 'title',
            right: 'month,agendaWeek,agendaDay',
            left: 'prev,next,today'
        },
        firstDay: start_week,
        defaultDate: today,
        defaultView: viewtitle,
        selectable: true,
        selectHelper: true,
        views: {
            month: {// name of view
                titleFormat: 'MMMM YYYY'
                        // other view-specific options here
            },
            week: {
                titleFormat: " MMMM D YYYY"
            },
            day: {
                titleFormat: 'D MMM, YYYY'
            } 
        },
        timezone: "Asia/Kolkata",
        draggable: false,
         lang: '<?php echo $language_name ?>',
        editable: false,
        eventLimit: false, // allow "more" link when too many events


        // color classes: [ event-blue | event-azure | event-green | event-orange | event-red ]
        events: {
            url: base_url + 'user/calendar/getevents'

        },

        eventRender: function (event, element) {
            element.attr('title', event.title +'- ' +event.description);
            element.attr('onclick', event.onclick);
            element.attr('data-toggle', 'tooltip');
            if ((!event.url) && (event.event_type != 'task')) {               
                
            }
        },
        dayClick: function (date, jsEvent, view) {
            var d = date.format();
            if (!$.fullCalendar.moment(d).hasTime()) {
                d += ' 05:30';
            }

            return false;
        }
    });

    function edit_todo_task(eventid) {
        $.ajax({
            url: "<?php echo site_url("user/calendar/gettaskbyid/") ?>" + eventid,
            type: "POST",
            data: {eventid: eventid},
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function (res)
            {
                $("#modal-title").html("<?php echo $this->lang->line('edit_task')?>");
                $("#task-title").val(res.event_title);
                $("#taskid").val(eventid);
                dt = new Date(res.start_date)
                var dat = new Date(dt.getFullYear(), dt.getMonth(), dt.getDate());
                $("#task-date").datepicker('update', dat);
                $('#newTask').modal('show');

            }
        });
    }

    $(document).ready(function (e) {
        $("#addtodo_form").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo site_url("user/calendar/addtodo") ?>",
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
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
                }
            });
        }));
    });

    function complete_event(id, status) {

        $.ajax({
            url: "<?php echo site_url("user/calendar/markcomplete/") ?>" + id,
            type: "POST",
            data: {id: id, active: status},
            dataType: 'json',

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
            }
        });
    }

    function markcomplete(id) {
        $('#check' + id).change(function () {
            if (this.checked) {
                complete_event(id, 'yes');
            } else {
                complete_event(id, 'no');
            }
        });
    }

    function deleteevent(id, msg) {
        if (typeof (id) == 'undefined') {
            return;
        }
        if (confirm("<?php echo $this->lang->line('delete_confirm')?>")) {
            $.ajax({
                url: base_url + 'user/calendar/delete_event/' + id,
                type: 'POST',               
                dataType: "json",
                success: function (res) {
                    if (res.status == "fail") {
                        errorMsg(res.message);
                    } else {
                        successMsg("<?php echo $this->lang->line('delete_message')?>");
                        window.location.reload(true);
                    }
                }
            })
        }
    }
</script>