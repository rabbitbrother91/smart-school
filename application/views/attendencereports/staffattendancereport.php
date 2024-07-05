<style type="text/css">
    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php //echo $this->lang->line('human_resource'); 
                                            ?></h1>
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
                    <form id='form1' action="<?php echo site_url('attendencereports/staffattendancereport') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('role'); ?></label>
                                        <select id="role" name="role" class="form-control">
                                            <option value="select"><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($role as $role_key => $value) {
                                            ?>
                                                <option value="<?php echo $value["type"] ?>" <?php
                                                                                                if ($role_selected == $value["type"]) {
                                                                                                    echo "selected =selected";
                                                                                                }
                                                                                                ?>><?php echo $value["type"]; ?></option>
                                            <?php
                                                $count++;
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('role'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('month'); ?></label><small class="req"> *</small>
                                        <select id="month" name="month" class="form-control">
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('year'); ?></label>
                                        <select id="year" name="year" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($yearlist as $y_key => $year) {
                                            ?>
                                                <option value="<?php echo $year["year"] ?>" <?php
                                                                                            if ($year["year"] == date("Y")) {
                                                                                                echo "selected";
                                                                                            }
                                                                                            ?>><?php echo $year["year"]; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('year'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                    if (isset($resultlist)) {
                    ?>
                        <div class="" id="attendencelist">
                            <div class="box-header ptbnull"></div>
                            <div class="box-header with-border">
                                <div class="row">
                                    <div class="col-md-4 col-sm-4">
                                        <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('staff_attendance_report'); ?></h3>
                                    </div>
                                    <div class="col-md-8 col-sm-8">
                                        <div class="pull-right">
                                            <?php
                                            foreach ($attendencetypeslist as $key_type => $value_type) {
                                            ?>
                                                &nbsp;&nbsp;
                                                <b>
                                                    <?php
                                                    $att_type = str_replace(" ", "_", strtolower($value_type['type']));
                                                    echo $this->lang->line($att_type) . ": " . $value_type['key_value'] . "";
                                                    ?>
                                                </b>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-body table-responsive">
                                <?php
                                if (!empty($resultlist)) {
                                ?>
                                    <div class="mailbox-controls">
                                        <div class="pull-right">
                                        </div>
                                    </div>
                                    <div class="download_label"><?php echo $this->lang->line('staff_attendance_report'); ?></div>
                                    <div> <?php echo
                                            $this->customlib->get_postmessage();
                                            ?></div>
                                    <table class="table table-striped table-bordered table-hover example xyz">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <?php echo $this->lang->line('staff_date'); ?>
                                                </th>
                                                <th><br /><span data-toggle="tooltip" title="<?php echo $this->lang->line("gross_present_percentage"); ?>"> (%)</span></th>
                                                <?php
                                                if (!empty($attendence_array)) {
                                                    foreach ($attendencetypeslist as $key => $value) {
                                                ?>
                                                        <th colspan=""><br /><span data-toggle="tooltip" title="<?php echo $this->lang->line('total') . ' ' . $value["type"]; ?>"><?php echo strip_tags($value["key_value"]); ?>

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
                                                                $this->lang->line(strtolower(date('D', $this->customlib->dateyyyymmddTodateformat($at_value))));
                                                            ?>
                                                        </th>

                                                    <?php
                                                    } else {
                                                    ?>
                                                        <th class="tdcls text text-center">
                                                            <?php
                                                            echo date('d', $this->customlib->dateyyyymmddTodateformat($at_value)) . "<br/>" .
                                                                $this->lang->line(strtolower(date('D', $this->customlib->dateyyyymmddTodateformat($at_value))));
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
                                                $i         = 0;
                                                foreach ($student_array as $student_key => $student_value) {
                                                    $total_present = ($monthAttendance[$i][$student_value['id']]['present'] + $monthAttendance[$i][$student_value['id']]['late'] + $monthAttendance[$i][$student_value['id']]['half_day']);

                                                    $total_days = $monthAttendance[$i][$student_value['id']]['present'] + $monthAttendance[$i][$student_value['id']]['late'] + $monthAttendance[$i][$student_value['id']]['absent'] + $monthAttendance[$i][$student_value['id']]['half_day'];

                                                    if ($total_days == 0) {
                                                        $percentage       = -1;
                                                        $print_percentage = "-";
                                                    } else {

                                                        $percentage       = ($total_present / $total_days) * 100;
                                                        $print_percentage = round($percentage, 0);
                                                    }

                                                    if (($percentage < 75) && ($percentage >= 0)) {
                                                        $label = "class='label label-danger'";
                                                    } else if ($percentage > 75) {
                                                        $label = "class='label label-success'";
                                                    } else {
                                                        $label = "class='label label-default'";
                                                    }
                                                ?>
                                                    <tr>

                                                        <td class="tdclsname">
                                                            <span data-toggle="popover" class="detail_popover" data-original-title="" title="">
                                                                <a href="#" style="color:#333"><?php echo $student_value['name'] . " " . $student_value['surname']; ?></a>
                                                            </span>
                                                            <div class="fee_detail_popover" style="display: none"><?php echo $this->lang->line('staff_id'); ?>: <?php echo $student_value['employee_id']; ?></div>
                                                        </td>
                                                        <th><?php echo "<label $label>" . $print_percentage . "</label>"; ?></th>
                                                        <th><?php echo $monthAttendance[$i][$student_value['id']]['present']; ?></th>
                                                        <th><?php echo $monthAttendance[$i][$student_value['id']]['late']; ?></th>
                                                        <th><?php echo $monthAttendance[$i][$student_value['id']]['absent']; ?></th>
                                                        <th><?php echo $monthAttendance[$i][$student_value['id']]['half_day']; ?></th>
                                                        <th><?php echo $monthAttendance[$i][$student_value['id']]['holiday']; ?></th>
                                                        <?php
                                                        foreach ($attendence_array as $at_key => $at_value) {
                                                        ?>
                                                            <th class="tdcls text text-center">
                                                                <span data-toggle="popover" class="detail_popover" data-original-title="" title=""><a href="#" style="color:#333"><?php
                                                                                                                                                                                    print_r($resultlist[$at_value][$student_value['id']]['key']);
                                                                                                                                                                                    ?></a></span>
                                                                <div class="fee_detail_popover" style="display: none"><?php
                                                                                                                        if (!empty($resultlist[$at_value][$student_value['id']]['remark'])) {
                                                                                                                            echo $resultlist[$at_value][$student_value['id']]['remark'];
                                                                                                                        }
                                                                                                                        ?></div>
                                                            </th>
                                                        <?php
                                                        }
                                                        ?>
                                                    </tr>
                                            <?php
                                                    $i++;
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
                    <?php
                    }
                    ?>
                </div><!--./box box-primary-->
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $('.detail_popover').popover({
        placement: 'right',
        title: '',
        trigger: 'hover',
        container: 'body',
        html: true,
        content: function() {
            return $(this).closest('td').find('.fee_detail_popover').html();
        }
    });

    var base_url = '<?php echo base_url() ?>';

    function printDiv(elem) {
        Popup(jQuery(elem).html());
    }

    function Popup(data) {
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({
            "position": "absolute",
            "top": "-1000000px"
        });
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
        setTimeout(function() {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);

        return true;
    }
</script>