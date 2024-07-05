<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-money"></i> <?php //echo $this->lang->line('fees_collection'); ?> </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('reports/_online_examinations');?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <form id='feesforward' action="<?php echo site_url('admin/onlineexam/searchloginvalidation') ?>"  method="post"  accept-charset="utf-8">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                            <div class="box-tools pull-right">
                            </div>
                        </div>
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <?php if ($this->session->flashdata('msg')) {
    ?>
                                        <?php echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg'); ?>
                                    <?php }?>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('exam') ?><small class="req"> *</small></label>
                                        <select  id="exam_id" name="exam_id" class="form-control select2"  >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($examList as $exam_key => $exam_value) {
    ?>
                                                <option value="<?php echo $exam_value->id; ?>"<?php
if (set_value('exam_id') == $exam_value->id) {
        echo "selected=selected";
    }
    ?>><?php echo $exam_value->exam; ?></option>
                                                        <?php
$count++;
}
?>
                                        </select>
                                       <span class="text-danger" id="error_exam_id"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select  id="class_id" name="class_id" class="form-control"  >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($classlist as $class) {
    ?>
                                                <option value="<?php echo $class['id'] ?>"<?php
if (set_value('class_id') == $class['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $class['class'] ?></option>
                                                        <?php
$count++;
}
?>
                                        </select>
                                       <span class="text-danger" id="error_class_id"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""   ><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                         <span class="text-danger" id="error_section_id"></span>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="submit" name="action" value ="search" class="btn btn-primary pull-right btn-sm"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="">
                                <div class="box-header ptbnull"></div>
                                <div class="box-header with-border">
                                    <h3 class="box-title titlefix"> <?php echo $this->lang->line('result_report'); ?></h3>
                                </div>
                                <div class="box-body">
                                    <div class="download_label"><?php
echo $this->lang->line('result_report'); ?></div>
                                    <div class="table-responsive">
                                          <table class="table table-striped table-bordered table-hover record-list" data-export-title="<?php echo $this->lang->line('result_report'); ?>">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                    <th><?php echo $this->lang->line('student_name'); ?></th>
                                                    <th><?php echo $this->lang->line('class'); ?></th>
                                                    <th><?php echo $this->lang->line('total_attempt'); ?></th>
                                                    <th><?php echo $this->lang->line('remaining_attempt'); ?></th>
                                                    <th><?php echo $this->lang->line('exam_submitted'); ?></th>
                                                    <th class="noExport"><?php echo $this->lang->line('action') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog pup100">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('exam') ?></h4>
            </div>
            <div class="modal-body">
                <div class="result_exam" ></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2();
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id', 0) ?>';
        var hostel_id = $('#hostel_id').val();
        var hostel_room_id = '<?php echo set_value('hostel_room_id', 0) ?>';
        getSectionByClass(class_id, section_id);
    });

    $(document).on('change', '#class_id', function (e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });

    function getSectionByClass(class_id, section_id) {
        if (class_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }
</script>

<script type="text/javascript">

    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
    });

    $(document).on('click', '.student_result', function () {
        var $this = $(this);
        var recordid = $this.data('recordid');
        var examid = $this.data('examid');
        var student_session_id = $this.data('student_session_id');
        $('input[name=recordid]').val(recordid);
        $.ajax({
            type: 'POST',
            url: baseurl + "admin/onlineexam/getstudentresult",
            data: {'recordid': recordid, 'examid': examid,'student_session_id':student_session_id},
            dataType: 'JSON',
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (data) {
                console.log(data.result);
                if (data.status) {
                    $('.result_exam').html(data.result);
                    $('#myModal').modal('show');
                }
                $this.button('reset');
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
    });
</script>

<script type="text/javascript">
   var base_url = '<?php echo base_url() ?>';
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
       frameDoc.document.write('<link rel=\"stylesheet\" href=\"' + base_url + 'backend/dist/css/font-awesome.min.css\" type=\"text/css\" media=\"all\" > ' );
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

    $(document).on('click', '.print_div', function () {
        var $this = $(this);
        $this.button('loading');
        var recordid = $this.data('recordid');
        var examid = $this.data('examid');
        var student_session_id = $this.data('student_session_id');
        $.ajax(
                {
                    url: "<?php echo site_url('admin/onlineexam/getstudentresult') ?>",
                    type: "POST",
                    data: {'recordid': recordid,'examid': examid,'student_session_id':student_session_id,'print':'print'},
                    dataType: 'Json',
                    success: function (data, textStatus, jqXHR)
                    {
                        console.log(data.result);
                         Popup(data.result);
                        $this.button('reset');
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        $this.button('reset');
                    }
                });
    });
</script>

<script>
$(document).ready(function() {
     emptyDatatable('record-list','fees_data');

});
</script>

<script type="text/javascript">
$(document).ready(function(){
$(document).on('submit','#feesforward',function(e){
    e.preventDefault(); // avoid to execute the actual submit of the form.
    var $this = $(this).find("button[type=submit]:focus");
    var form = $(this);
    var url = form.attr('action');
    var form_data = form.serializeArray();
    $.ajax({
           url: url,
           type: "POST",
           dataType:'JSON',
           data: form_data, // serializes the form's elements.
              beforeSend: function () {
                $('[id^=error]').html("");
                $this.button('loading');

               },
              success: function(response) { // your success handler
                if(!response.status){
                    $.each(response.error, function(key, value) {
                    $('#error_' + key).html(value);
                    });
                }else{
                   initDatatable('record-list','admin/onlineexam/dtreportlist',response.params);
                }
              },
             error: function() { // your error handler
                 $this.button('reset');
             },
             complete: function() {
             $this.button('reset');
             }
         });
        });
    });
</script>