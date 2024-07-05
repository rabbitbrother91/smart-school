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
        <h1>
            <i class="fa fa-calendar-check-o"></i> <?php //echo $this->lang->line('attendance'); 
                                                    ?> <small> <?php //echo $this->lang->line('by_date1'); 
                                                                ?></small>
        </h1>
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
                    <form id='form1' action="<?php echo site_url('attendencereports/daywiseattendancereport') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control">
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

                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?> <small class="req"> *</small></label>
                                        <input name="date" placeholder="" type="text" class="form-control date" value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly" />
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('source'); ?></label>
                                        <select id="attendance_mode" name="attendance_mode" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <option value="1" <?php echo set_select('attendance_mode',1, (set_value('attendance_mode')== 1) ? TRUE :FALSE); ?> ><?php echo $this->lang->line('manual'); ?></option>
                                            <option value="2" <?php echo set_select('attendance_mode',2, (set_value('attendance_mode')== 2) ? TRUE :FALSE); ?> ><?php echo $this->lang->line('qrcode')." / ".$this->lang->line('barcode'); ?></option>
                                            <option value="3" <?php echo set_select('attendance_mode',3, (set_value('attendance_mode')== 3) ? TRUE :FALSE); ?> ><?php echo $this->lang->line('biometric'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('attendance_mode'); ?></span>
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
                                <div class="box-header with-border">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('student_day_wise_attendance_report'); ?> </h3>
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
                                        <div class="download_label"><?php echo $this->lang->line('student_day_wise_attendance_report'); ?> </div>
                                        <table class="table table-hover table-striped example">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                    <th><?php echo $this->lang->line('roll_number'); ?></th>
                                                    <th><?php echo $this->lang->line('name'); ?></th>
                                                    <th width="10%" class="text text-center"><?php echo $this->lang->line('attendance'); ?></th>
                                                    <?php
                                                    if ($sch_setting->biometric) {
                                                    ?>
                                                        <th><?php echo $this->lang->line('date'); ?></th>
                                                      
                                                    <?php
                                                    }
                                                    ?>    
                                                    <th width="10%"><?php echo $this->lang->line('source'); ?></th>
                                                    <?php
                                                    if ($sch_setting->biometric) {
                                                    ?>
                                                      <th><?php echo $this->lang->line('ip_address'); ?></th>
                                                      <th><?php echo $this->lang->line('agent'); ?></th>
                                                     
                                                    <?php
                                                    }
                                                    ?>  

                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                $row_count = 1;
                                                foreach ($resultlist as $key => $value) {
                                                ?>
                                                    <tr>
                                                        <td>

                                                            <?php echo $row_count; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $value['admission_no']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $value['roll_no']; ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo $this->customlib->getFullName($value['firstname'], $value['middlename'], $value['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?>
                                                        </td>
                                                        <td class="text text-center">
                                                            <?php
                                                        
                                                             if (IsNullOrEmptyString($value['attendence_type_id'])) {
                                                              
                                                                ?>
                                                                <span class="label label-danger"><?php echo $this->lang->line('n_a'); ?></span>
                                                                <?php

                                                            }else{
                                                                ?>
                                                                <span class="<?php echo $value['long_name_style'];?>"><?php echo $this->lang->line(strtolower($value['long_lang_name'])); ?></span>
                                                                <?php
                                                              
                                                            }


                                                           
                                                            ?>
                                                        </td>
                                                        <?php
                                                        if ($sch_setting->biometric) {
                                                        ?>
                                                            <td>
                                                                <?php
                                                                if ($value['biometric_attendence'] || $value['qrcode_attendance']) {

                                                                    echo $this->customlib->dateyyyymmddToDateTimeformat($value['attendence_dt']);
                                                                }
                                                                ?>
                                                            </td>
                                                           
                                                        <?php
                                                        }
                                                        ?>
                                                       
                                                        <td>
                                                            <?php

                                                            if (IsNullOrEmptyString($value['biometric_attendence']) && IsNullOrEmptyString($value['qrcode_attendance'])) {
                                                                echo $this->lang->line('n_a');
                                                            } elseif (($value['biometric_attendence'] == 0) && ($value['qrcode_attendance']  == 0)) {
                                                                echo $this->lang->line('manual');
                                                            } elseif ($value['biometric_attendence']) {
                                                                echo $this->lang->line('biometric');
                                                            } elseif ($value['qrcode_attendance']) {
                                                                echo $this->lang->line('qrcode')." / ".$this->lang->line('barcode');
                                                            }

                                                            ?>
                                                        </td>

                                                        <?php
                                                    if ($sch_setting->biometric) {
                                                    ?>
                                                     <td>
                                                                <?php
                                                                if ($value['biometric_attendence'] || $value['qrcode_attendance']) {
                                                                if(isJSON($value['biometric_device_data'])){
                                                                    $json_data=json_decode($value['biometric_device_data']);
                                                                    echo ($json_data->ip);
                                                                }
                                                                }
                                                                ?>
                                                            </td>
                                                        <td class="text-rtl-left"><?php  echo $value['user_agent'];; ?></td>
                                                     
                                                    <?php
                                                    }
                                                    ?>  


                                                    </tr>
                                                <?php
                                                    $row_count++;
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
    $(document).ready(function() {

        $('.detail_popover').popover({
            placement: 'right',
            title: '',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function() {
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
                data: {
                    'class_id': class_id_post
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
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

        $(document).on('change', '#class_id', function(e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
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