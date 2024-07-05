<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-warning">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('class_timetable'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('class_timetable'); ?></div>

                            <?php
                            if (!empty($timetable)) {
                            ?>
                                <button type="submit" title="<?php echo $this->lang->line('print'); ?>" class="btn btn-primary btn-xs pull-right smallbtn28 print_timetable" data-class_id="<?php echo set_value('class_id'); ?>" data-section_id="<?php echo set_value('section_id'); ?>" id="load" data-loading-text="<i class='fa fa-spinner fa-spin'></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-print"></i></button>
                                <table class="table table-stripped">
                                    <thead>
                                        <tr>
                                            <?php
                                            foreach ($timetable as $tm_key => $tm_value) {
                                            ?>
                                                <th class="text"><?php echo $this->lang->line(strtolower($tm_key)); ?></th>
                                            <?php
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php
                                            foreach ($timetable as $tm_key => $tm_value) {
                                            ?>
                                                <td class="text" width="14%">

                                                    <?php
                                                    if (!$timetable[$tm_key]) {
                                                    ?>
                                                        <div class="attachment-block block-b-noraml clearfix">
                                                            <b class="text text-danger"><i class="fa fa-times-circle text-danger"></i><?php echo $this->lang->line('not_scheduled'); ?></b>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        foreach ($timetable[$tm_key] as $tm_k => $tm_kue) {
                                                        ?>
                                                            <div class="attachment-block attachment-block-normal clearfix">

                                                                <div class="relative attachment-left-space"><i class="fa fa-book"></i><?php echo $this->lang->line('subject') ?>:
                                                                    <?php
                                                                    echo $tm_kue->subject_name;
                                                                    if ($tm_kue->code != '') {
                                                                        echo " (" . $tm_kue->code . ")";
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <div class="relative attachment-left-space"><i class="fa fa-clock-o"></i><?php echo $tm_kue->time_from ?>
                                                                    <b class="text text-center">-</b>
                                                                    <strong class="text-green"><?php echo $tm_kue->time_to; ?></strong>
                                                                </div>
                                                                <div class="relative attachment-left-space"><i class="fa fa-user"></i> <?php echo $tm_kue->name." ".$tm_kue->surname." (".$tm_kue->employee_id.")";?>
                                                                    </div>
                                                                <div class="relative attachment-left-space"><i class="fa fa-building"></i><?php echo $this->lang->line('room_no'); ?>: <?php echo $tm_kue->room_no; ?>
                                                                </div>
                                                            </div>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                            <?php
                                            }
                                            ?>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



<script>
    $(document).on('click', '.print_timetable', function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var $this = $(this);
       
        $.ajax({
            url: baseurl  + 'user/timetable/printclasstimetable',
            type: "POST",
            data: {},
            dataType: 'Json',
            beforeSend: function() {
                $this.button('loading');
            },
            success: function(data, textStatus, jqXHR) {

                Popup(data.page);
                $this.button('reset');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $this.button('reset');
            },
            complete: function() {
                $this.button('reset');
            }
        });
    });

    function Popup(data, winload = false) {
        var frameDoc = window.open('', 'Print-Window');
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body onload="window.print()">');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function() {
            frameDoc.close();
            if (winload) {
                window.location.reload(true);
            }
        }, 5000);

        return true;
    }
</script>