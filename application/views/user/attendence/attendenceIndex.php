<script src="<?php echo base_url() ?>backend/fullcalendar/dist/fullcalendar.min.js"></script>
<script src="<?php echo base_url() ?>backend/fullcalendar/dist/locale-all.js"></script>

 <?php if ($language_shortcode['short_code'] != 'en') {?>
    <script src="<?php echo base_url() ?>backend/fullcalendar/dist/locale/<?php echo $language_shortcode['short_code']; ?>.js"></script>
<?php }?>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"> <?php echo $this->lang->line('attendance'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div id="calendar_attendance"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="application/javascript">
    $calendar_attendance = $('#calendar_attendance');
    var base_url = '<?php echo base_url() ?>';
    today = new Date();

    y = today.getFullYear();
    m = today.getMonth();
    d = today.getDate();

    var viewtitle = 'month';

    $calendar_attendance.fullCalendar({
        viewRender: function (view, element) {

        },

        header: {
            center: 'title',
            right: '',
            left: 'prev,next'
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
            }
        },
        timezone: 'UTC',
        draggable: false,
        lang: '<?php echo $language_shortcode['short_code']; ?>',
        editable: false,
        eventLimit: false, // allow "more" link when too many events

        // color classes: [ event-blue | event-azure | event-green | event-orange | event-red ]
        events: {
            url: base_url + 'user/attendence/getAttendence'

        },

        eventRender: function (event, element) {
            console.log(event);
            element.attr('title', event.title);
            element.attr('data-toggle', 'tooltip');
            if ((!event.url) && (event.event_type != 'task')) {
                element.attr('title', event.title + '-' + event.description);
                element.click(function () {

                });
            }
        },
        dayClick: function (date, jsEvent, view) {
           console.log('Clicked on the entire day: ' + date.format());

                var newEventModal= $('#newEventModal');
                $("#input-field").val('');
                $("#desc-field").text('');
                var event_start_from = new Date(date);
                console.log(event_start_from);

            return false;
        }
    });
</script>