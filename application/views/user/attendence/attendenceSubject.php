<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-calendar-check-o"></i> <?php //echo $this->lang->line('attendance'); ?><!-- </small> --></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('attendance'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label> <small class="req"> *</small>
                                    <input id="dob" name="dob" placeholder="" type="text" class="form-control date" value="<?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date('Y-m-d'))); ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="attendance_result">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
        attendance.result($('#dob').val());
        $('.date').datepicker({
            format: date_format,
            autoclose: true,
            weekStart : start_week,

        }).on('changeDate', dateChanged);

        function dateChanged(ev) {
            var date = $('#dob').val();
           if(date!=""){
             attendance.result(date);
         }else{
            errorMsg("<?php echo $this->lang->line('date_field_is_required'); ?>");
            $('.attendance_result').html("");
         }

        }
    });

    attendance = {
        result: function (date_var) {

            $.ajax({
                url: baseurl + "user/attendence/getdaysubattendence",
                type: "POST",
                data: {'date': date_var},
                dataType: 'json',

                beforeSend: function () {

                },
                success: function (res)
                {
                    $('.attendance_result').html(res.result_page);
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                },
                complete: function () {

                }

            });
        }
    }
//function call

</script>