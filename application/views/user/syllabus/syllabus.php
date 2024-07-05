<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-calendar-times-o"></i> <?php //echo $this->lang->line('class') . " " . $this->lang->line('syllabus'); ?> </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row" >
            <div id="weekdates_result"></div>
        </div>
    </section>
</div>

<div class="modal fade syllbus" id="assignsyllabus" tabindex="-1" role="dialog" aria-labelledby="evaluation" style="padding-left: 0 !important">
    <div class="modal-dialog full-width" role="document">
        <div class="modal-content modal-media-content">
            <div class="">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="row">
                            <div id="syllabus_result"></div>
                        </div><!--./row-->
                    </div><!--./col-md-12-->
                </div><!--./row-->
            </div>
        </div>
    </div>
</div>
<div class="modal fade syllbus" id="lacture_youtube_modal" role="dialog" aria-labelledby="evaluation">
    <div class="modal-dialog video-lg" role="document">
        <button type="button" class="close" data-dismiss="modal" onclick="videoUrlBlank()">&times;</button>
        <div class="modal-body" style="padding: 0;">
            <div id="video_url"></div>
        </div>
    </div>
</div>

<script>
    function videoUrlBlank() {
        $('#video_url').html('');
    }

    function run_video(lacture_youtube_url) {
        $('#lacture_youtube_modal').modal('show');
        var str = lacture_youtube_url;
        var res = str.split("=");
        $('#video_url').html('<iframe width="100%" class="videoradius" src="//www.youtube.com/embed/' + res[1] + '?rel=0&version=3&modestbranding=1&autoplay=1&controls=1&showinfo=0&loop=1" frameborder="0" allowfullscreen></iframe>');
    }

    function get_subject_syllabus(id) {
        $('#assignsyllabus').modal('show');
        $('#syllabus_result').html('');
        $('#syllabus_result').html('');
        $.ajax({
            type: "POST",
            url: base_url + "user/syllabus/get_subject_syllabus",
            data: {'subject_syllabus_id': id},
            success: function (data) {
                $('#syllabus_result').html(data);
            },
        });
    }

    var base_url = "<?php echo base_url(); ?>";
    get_weekdates('current_week', '<?php echo $this_week_start; ?>');
    function get_weekdates(status, date) {

        $.ajax({
            type: "POST",
            url: base_url + "user/syllabus/get_weekdates",
            data: {'status': status, 'date': date},
            beforeSend: function () {

            },
            success: function (data) {
                $('#weekdates_result').html(data);
            },
            complete: function () {

            }
        });
    }

    document.getElementById("print").style.display = "block";
    document.getElementById("btnExport").style.display = "block";

    function printDiv() {
        $("#visible").removeClass("hide");
        $(".pull-right").addClass("hide");

        document.getElementById("print").style.display = "none";
        document.getElementById("btnExport").style.display = "none";
        var divElements = document.getElementById('transfee').getElementsByClassName("scroll-area")[0].innerHTML;
        var oldPage = document.body.innerHTML;
        document.body.innerHTML =
                "<html><head><title></title></head><body>" +
                divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;
        location.reload(true);
    }

    function fnExcelReport()
    {
        var tab_text = "<table border='2px'><tr >";
        var textRange;
        var j = 0;
        tab = document.getElementById('headerTable'); // id of table

        for (j = 0; j < tab.rows.length; j++)
        {
            tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
        }

        tab_text = tab_text + "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");
        $("#visible").addClass("hide");
        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
        {
            txtArea1.document.open("txt/html", "replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus();
            sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Sumit.xls");
        } else                 //other browser not tested on IE 11
            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));

        return (sa);
    }
</script>