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
                    <form id='form1' action="<?php echo site_url('attendencereports/classattendencereport') ?>"  method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                                ?>
                                                <option value="<?php echo $class['id'] ?>" <?php
                                                if ($class_id == $class['id']) {
                                                    echo "selected =selected";
                                                }
                                                ?>><?php echo $class['class'] ?></option>
                                                        <?php
                                                        $count++;
                                                    }
                                                    ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('month'); ?></label><small class="req"> *</small>
                                        <select  id="month" name="month" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($monthlist as $m_key => $month) {
                                                ?>
                                                <option value="<?php echo $m_key ?>" <?php
                                                if ($month_selected == $m_key) {
                                                    echo "selected =selected";
                                                }
                                                ?>><?php echo $month; ?></option>
                                                        <?php
                                                        $count++;
                                                    }
                                                    ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('month'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('year'); ?></label>
                                        <select  id="year" name="year" class="form-control" >

                                            <?php
                                             
                                            foreach ($yearlist as $y_key => $year) {
                                                ?>
                                                <option value="<?php echo $year["year"] ?>" <?php
                                                if ($year_selected == $year["year"]) {
                                                    echo "selected =selected";
                                                }
                                                ?>><?php echo $year["year"]; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('year'); ?></span>
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

                    <?php
                    if ($this->module_lib->hasActive('student_attendance')) {

                        if (isset($resultlist)) {
                            ?>
                            <div class="" id="attendencelist">
                                <div class="box-header ptbnull"></div>  
                                <div class="box-header with-border" >
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4">
                                            <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('student_attendance_report'); ?></h3>
                                        </div>
                                        <div class="col-md-8 col-sm-8">
                                            <div class="lateday">
                                                <?php
                                                foreach ($attendencetypeslist as $key_type => $value_type) {
                                                    ?>
                                                    &nbsp;&nbsp;
                                                    <b>
                                                        <?php
                                                        $att_type = str_replace(" ", "_", strtolower($value_type['type']));
                                                        if (strip_tags($value_type["key_value"]) != "E") {

                                                            echo $this->lang->line($att_type) . ": " . $value_type['key_value'] . "";
                                                        }
                                                        ?>
                                                    </b>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div></div>
                                <div class="box-body table-responsive">
                                    <?php
                                    if (!empty($resultlist)) {
                                        ?>
                                        <div class="mailbox-controls">
                                            <div class="pull-right">
                                            </div>
                                        </div>
                                        <div class="download_label"><?php echo $this->lang->line('student_attendance_report').' '.
                            $this->customlib->get_postmessage();
                                        ?></div>
                                        <table class="table table-striped table-bordered table-hover example">
                                            <thead>
                                                <tr>
                                                    <th>
            <?php echo $this->lang->line('student_date'); ?>
                                                    </th>
                                                    <th><br/><span data-toggle="tooltip" title="<?php echo $this->lang->line("gross_present_percentage"); ?>"> (%)</span></th>

                                                    <?php
                                                    foreach ($attendencetypeslist as $key => $value) {                                                       
                                                        if (strip_tags($value["key_value"]) != "E") {
                                                            ?>
                                                            <th colspan="" ><span data-toggle="tooltip" title="<?php echo $this->lang->line("total") .' '. $value["type"]; ?>"><?php echo strip_tags($value["key_value"]); ?>

                                                                </span></th>

                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    <?php
                                                    foreach ($attendence_array as $at_key => $at_value) {
                                                        if (date('D', $this->customlib->dateyyyymmddTodateformat($at_value)) == "Sun") {
                                                            ?>
                                                            <th class="tdcls text text-center bg-danger">
                                                                <?php
                                                                echo date('d', $this->customlib->dateyyyymmddTodateformat($at_value)) . "<br/>" .
                                                                $this->lang->line(strtolower(date('D', $this->customlib->dateyyyymmddTodateformat($at_value))))
                                                                ;
                                                                ?>
                                                            </th>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <th class="tdcls text text-center">
                                                                <?php
                                                                echo date('d', $this->customlib->dateyyyymmddTodateformat($at_value)) . "<br/>" .
                                                                $this->lang->line(strtolower(date('D', $this->customlib->dateyyyymmddTodateformat($at_value))))
                                                                ;
                                                                ?>
                                                            </th>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($student_array)) {
                                                    ?>
                                                    <tr>
                                                        <td colspan="32" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                                    </tr>
                                                    <?php
                                                } else {
                                                    $row_count = 1;
                                                    $i = 0;

                                                    foreach ($student_array as $student_key => $student_value) {                                                      
                                                        ?>
                                                        <tr>
                                                            <th class="tdclsname">
                                                                <span data-toggle="popover" class="detail_popover" data-original-title="" title=""><a href="#" style="color:#333"><?php echo $this->customlib->getFullName($student_value['firstname'],$student_value['middlename'],$student_value['lastname'],$sch_setting->middlename,$sch_setting->lastname); ?></a></span>
                                                                <div class="fee_detail_popover" style="display: none"><?php echo $this->lang->line("admission_no").": " . $student_value['admission_no']; ?></div> 
                                                            </th>
                                                            <th><?php
                                                                $total_present = ($monthAttendance[$i][$student_value['student_session_id']]['present'] + $monthAttendance[$i][$student_value['student_session_id']]['late_with_excuse'] + $monthAttendance[$i][$student_value['student_session_id']]['half_day'] + $monthAttendance[$i][$student_value['student_session_id']]['late']);
                                                                $month_number = date("m", strtotime($month_selected));
                                                                $num_of_days = cal_days_in_month(CAL_GREGORIAN, $month_number, date("Y"));
                                                                $total_school_days = $monthAttendance[$i][$student_value['student_session_id']]['present'] + $monthAttendance[$i][$student_value['student_session_id']]['late_with_excuse'] + $monthAttendance[$i][$student_value['student_session_id']]['late'] + $monthAttendance[$i][$student_value['student_session_id']]['half_day'] + $monthAttendance[$i][$student_value['student_session_id']]['absent'];
                                                                if ($total_school_days == 0) {
                                                                    $percentage = -1;
                                                                    $print_percentage = "-";
                                                                } else {

                                                                    $percentage = ($total_present / $total_school_days) * 100;
                                                                    $print_percentage = round($percentage, 0);
                                                                }

                                                                if (($percentage < $low_attendance_limit) && ($percentage >= 0)) {
                                                                    $label = "class='label label-danger'";
                                                                } else if ($percentage > $low_attendance_limit) {

                                                                    $label = "class='label label-success'";
                                                                } else {

                                                                    $label = "class='label label-success'";
                                                                }
                                                                echo "<label $label>" . $print_percentage . "</label>";
                                                                ?></th>

                                                            <th><?php echo ($monthAttendance[$i][$student_value['student_session_id']]['present']); ?></th>       
                                                            <th><?php echo ($monthAttendance[$i][$student_value['student_session_id']]['late'] + $monthAttendance[$i][$student_value['student_session_id']]['late_with_excuse']); ?></th>
                                                            <th><?php echo ($monthAttendance[$i][$student_value['student_session_id']]['absent']); ?></th>
                                                            <th><?php echo ($monthAttendance[$i][$student_value['student_session_id']]['holiday']); ?></th>
                                                            <th><?php echo ($monthAttendance[$i][$student_value['student_session_id']]['half_day']); ?></th>

                                                            <?php
                                                            foreach ($attendence_array as $at_key => $at_value) {
                                                                ?>
                                                                <th class="tdcls text text-center">

                                                                    <span data-toggle="popover" class="detail_popover" data-original-title="" title=""><a href="#" style="color:#333">
                                                                    <?php
                                                              
                                                                            if (!is_null($resultlist[$at_value][$student_value['student_session_id']]['key']) && strip_tags($resultlist[$at_value][$student_value['student_session_id']]['key']) == "E") {

                                                                                $attendence_key = "L";
                                                                                $remark = "Late With Excuse";
                                                                            } else {

                                                                                $attendence_key = $resultlist[$at_value][$student_value['student_session_id']]['key'];
                                                                                $remark = $resultlist[$at_value][$student_value['student_session_id']]['remark'];
                                                                            }

                                                                            echo ($attendence_key);
                                                                            ?>
                                                                            </a></span>
                                                                    <div class="fee_detail_popover" style="display: none"><?php echo $remark; ?></div>
                                                                </th>
                                                            <?php }
                                                            ?>

                                                            <?php
                                                            $i++;
                                                            ?>

                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="alert alert-info">
                                        <?php echo $this->lang->line('no_attendance_prepared'); ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div><!-- ./box box-primary -->  
                        <?php
                    }
                }
                ?>
            </div>
        </div> 
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $('.detail_popover').popover({
            placement: 'right',
            title: '',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('th').find('.fee_detail_popover').html();
            }
        });

        var section_id_post = '<?php echo $section_id; ?>';
        var class_id_post = '<?php echo $class_id; ?>';
        populateSection(section_id_post, class_id_post);
        function populateSection(section_id_post, class_id_post) {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id_post},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var select = "";
                        if (section_id_post == obj.section_id) {
                            var select = "selected=selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + select + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }

        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });

        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $('#date').datepicker({
            format: date_format,
            autoclose: true
        });
    });
</script>

<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';
    function printDiv(elem) {
        Popup(jQuery(elem).html());
    }
    function Popup(data)
    {
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);

        return true;
    }
</script>