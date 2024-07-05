<footer class="main-footer">
    &copy;  <?php echo date('Y'); ?>
    <?php echo $this->customlib->getAppName(); ?> <?php echo $this->customlib->getAppVersion(); ?>
</footer>
<div class="control-sidebar-bg"></div>
</div>
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>

<link href="<?php echo base_url(); ?>backend/toast-alert/toastr.css" rel="stylesheet"/>
<script src="<?php echo base_url(); ?>backend/toast-alert/toastr.js"></script>

<script src="<?php echo base_url(); ?>backend/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>backend/dist/js/raphael-min.js"></script>
<script src="<?php echo base_url(); ?>backend/plugins/morris/morris.min.js"></script>
<script src="<?php echo base_url(); ?>backend/plugins/sparkline/jquery.sparkline.min.js"></script>
<script src="<?php echo base_url(); ?>backend/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo base_url(); ?>backend/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="<?php echo base_url(); ?>backend/plugins/knob/jquery.knob.js"></script>
<script src="<?php echo base_url(); ?>backend/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url(); ?>backend/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script src="<?php echo base_url(); ?>backend/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo base_url(); ?>backend/plugins/fastclick/fastclick.min.js"></script>

<!--language js-->
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/bootstrap-select.min.js"></script>

 <script type="text/javascript">
    $(function(){
      $('.languageselectpicker').selectpicker();
   });
</script>

<script src="<?php echo base_url(); ?>backend/dist/js/app.min.js"></script>
<!--nprogress-->
<script src="<?php echo base_url(); ?>backend/dist/js/nprogress.js"></script>
<!--file dropify-->
<script src="<?php echo base_url(); ?>backend/dist/js/dropify.min.js"></script>
<script src="<?php echo base_url(); ?>backend/dist/js/demo.js"></script>

<!--print table-->
<!--print table-->
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/datatables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/datatables/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/datatables/js/jszip.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/datatables/js/pdfmake.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/datatables/js/vfs_fonts.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/datatables/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/datatables/js/buttons.print.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/datatables/js/buttons.colVis.min.js" ></script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/datatables/js/dataTables.responsive.min.js" ></script>

<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/datatables/js/ss.custom.js" ></script>
</body>
</html>

<!-- Modal -->
<div id="classSwitchModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <form action="<?php echo site_url('common/getStudentClass') ?>" method="POST" id="frmclschg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('switch_class'); ?></h4>
                </div>
                <div class="modal-body classSwitchbody">

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('update'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
     var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy', 'M' => 'M']) ?>';

    $(document).ready(function () {
        $('body').on('focus',".date", function(){
            $(this).datepicker({
                todayHighlight: false,
                format: date_format,
                autoclose: true,
                weekStart : start_week
            });
        });
        });
</script>
<script type="text/javascript">

    $("#frmclschg").on('submit', (function (e) {
        e.preventDefault();

        var form = $(this);
        var $this = $(this).find("button[type=submit]:focus");
        $.ajax({
            url: form.attr('action'),
            type: "POST",
            data: form.serialize(), // serializes the form's elements.
            dataType: 'json',

            beforeSend: function () {
                $this.button('loading');

            },
            success: function (res)
            {
                    if (res.status) {
                        successMsg(res.message);
                        window.location.href = baseurl + "user/user/dashboard";

                    } else {

                        errorMsg(res.message);

                    }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }

        });
    }));

    $(document).on('change', '.clschg', function () {
        if ($(this).is(":checked")) {

            $('input.clschg').not(this).prop('checked', false);
        } else {
            $(this).prop("checked", true);
        }
    });

    $('#classSwitchModal').on('show.bs.modal', function (event) {
        var $modalDiv = $(event.delegateTarget);
        $('.classSwitchbody').html("");
        $.ajax({
            type: "POST",
            url: baseurl + "common/getStudentSessionClasses",
            dataType: 'JSON',
            data: {},
            beforeSend: function () {
                $modalDiv.addClass('modal_loading');
            },
            success: function (data) {
                $('.classSwitchbody').html(data.page);
            },
            error: function (xhr) { // if error occured
                $modalDiv.removeClass('modal_loading');
            },
            complete: function () {
                $modalDiv.removeClass('modal_loading');
            },
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
<?php
if ($this->session->flashdata('success_msg')) {
    ?>
            successMsg("<?php echo $this->session->flashdata('success_msg'); ?>");
            $this->session->unset_userdata('success_msg');
    <?php
} else if ($this->session->flashdata('error_msg')) {
    ?>
            errorMsg("<?php echo $this->session->flashdata('error_msg'); ?>");
            $this->session->unset_userdata('error_msg');
    <?php
} else if ($this->session->flashdata('warning_msg')) {
    ?>
            infoMsg("<?php echo $this->session->flashdata('warning_msg'); ?>");
            $this->session->unset_userdata('warning_msg');
    <?php
} else if ($this->session->flashdata('info_msg')) {
    ?>
            warningMsg("<?php echo $this->session->flashdata('info_msg'); ?>");
            $this->session->unset_userdata('info_msg');
    <?php
}
?>
    });
</script>

<script type="text/javascript">

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

    function markc(id) {
        $('#newcheck' + id).change(function () {
            if (this.checked) {
                complete_event(id, 'yes');
            } else {
                complete_event(id, 'no');
            }
        });
    }

</script>
<script type="text/javascript">
    $(document).on('change','#currencySwitcher',function(e){
            $.ajax({
                type: 'POST',
                url: baseurl+'user/user/change_currency',
                data: {currency_id: $(this).val()},
                dataType: 'JSON',
                beforeSend: function() {

                },
                success: function(data) {
                    successMsg(data.message);
                  window.location.reload('true');
                },
                error: function(xhr) { // if error occured
                    alert("Error occured.please try again");

                },
                complete: function() {

                }
            });

    });
</script>
<script type="text/javascript">
     var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy', 'M' => 'M']) ?>';

    $(document).ready(function () {
        $('body').on('focus',".guestbirthdate", function(){
            $(this).datepicker({
                todayHighlight: false,
                format: date_format,
                autoclose: true,
                // weekStart : start_week
            });
        });
        });
</script>

<!-- Button trigger modal -->
<!-- Modal -->

<div class="modal fade" id="user_sessionModal" tabindex="-1" role="dialog" aria-labelledby="sessionModalLabel">
    <form action="#" id="form_modal_usersession" class="">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="sessionModalLabel"><?php echo $this->lang->line('current_session'); ?></h4>
                </div>
                <div class="modal-body user_sessionmodal_body">

                </div>
                <div class="modal-footer">
                   <div class="col-md-12">
                    <button type="button" class="btn btn-primary submit_usersession" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save'); ?></button>
                  </div>
                </div>
            </div>
        </div>
    </form>
</div>