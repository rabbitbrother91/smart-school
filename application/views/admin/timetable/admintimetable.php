<style type="text/css">
    @media(max-width:767px){
        .dhide{display:none !important}
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i><?php echo $this->lang->line('timetable'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">


                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('teacher_time_table'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>

                    <div class="box-body">
                        <form action="<?php echo site_url('admin/timetable/getteachertimetable'); ?>" id="getTimetable" class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4">   
                                <div class="form-group">
                                    <label for="teacher"><?php echo $this->lang->line('teachers'); ?><small class="req"> *</small></label>
                                    <select class="form-control" name="teacher" id="teacher">
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <?php
                                        if (!empty($staff_list)) {
                                            foreach ($staff_list as $staff_key => $staff_value) {
                                                ?>
                                                <option value="<?php echo $staff_value['id']; ?>"><?php echo $staff_value["name"] . " " . $staff_value["surname"] . " (" . $staff_value['employee_id'] . ")"; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>    
                            <div class="col-lg-4 col-md-4 col-sm-4"> 
                                <div class="form-group">
                                    <label class="dhide" style="display: block; visibility:hidden;"><?php echo $this->lang->line('teacher') ?></label>
                                    <button type="submit" class="btn btn-primary btn-sm smallbtn28" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('search') ?></button>
                                </div>    
                            </div>   
                        </form>
                        <div class="timetable_data table-responsive clearboth">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $("form#getTimetable").submit(function (e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
        $this = $(this).find("button[type=submit]:focus");

        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(), // serializes the form's elements.
            dataType: "json",
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (data) {
                if (data.status == "0") {
                    var message = "";
                    $.each(data.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {
                    $('.timetable_data').html(data.message);
                }

                $this.button('reset');
            }, error: function (jqXHR, textStatus, errorThrown) {
                alert('<?php echo $this->lang->line('error_occurred_please_try_again'); ?>');
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });


    });

    $(document).on('click', '.print_timetable', function (e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var $this = $(this);
        var id = $this.data('staff_id');
        $.ajax(
                {
                    url: "<?php echo site_url('admin/timetable/printteachertimetable') ?>",
                    type: "POST",
                    data: {'staff_id': id},
                    dataType: 'Json',
                    beforeSend: function () {
                $this.button('loading');
            },
                    success: function (data, textStatus, jqXHR)
                    {
                        console.log(data.page);
                         Popup(data.page);
                        $this.button('reset');
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        $this.button('reset');
                    }
                });
    });


    function Popup(data, winload = false)
    {
        var frameDoc=window.open('', 'Print-Window');
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
        frameDoc.document.write('<body onload="window.print()">');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
            frameDoc.close();      
            if (winload) {
                window.location.reload(true);
            }
        }, 5000);

        return true;
    }

</script>