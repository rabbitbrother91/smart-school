<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-map-o"></i> <?php echo $this->lang->line('examinations'); ?> <small><?php echo $this->lang->line('student_fee1'); ?></small>  </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form role="form" action="<?php echo site_url('admin/examresult/marksheet') ?>" method="post" class="row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-6 col-lg-4 col-md-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('exam_group'); ?></label><small class="req"> *</small>
                                    <select autofocus="" id="exam_group_id" name="exam_group_id" class="form-control select2" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($examgrouplist as $ex_group_key => $ex_group_value) {
                                            ?>
                                            <option value="<?php echo $ex_group_value->id ?>" <?php
                                            if (set_value('exam_group_id') == $ex_group_value->id) {
                                                echo "selected=selected";
                                            }
                                            ?>><?php echo $ex_group_value->name; ?></option>
                                                    <?php
                                                }
                                                ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('exam_group_id'); ?></span>
                                </div>  
                            </div><!--./col-md-3-->
                            <div class="col-sm-6 col-lg-4 col-md-4">
                                <div class="form-group">   
                                    <label><?php echo $this->lang->line('exam'); ?></label><small class="req"> *</small>
                                    <select  id="exam_id" name="exam_id" class="form-control select2" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('exam_id'); ?></span>
                                </div>  
                            </div><!--./col-md-3-->
                            <div class="col-sm-6 col-lg-4 col-md-4">
                                <div class="form-group">  
                                    <label><?php echo $this->lang->line('session'); ?></label><small class="req"> *</small>
                                    <select  id="session_id" name="session_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($sessionlist as $session) {
                                            ?>
                                            <option value="<?php echo $session['id'] ?>" <?php
                                            if (set_value('session_id') == $session['id']) {
                                                echo "selected=selected";
                                            }
                                            ?>><?php echo $session['session'] ?></option>
                                                    <?php
                                                }
                                                ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('session_id'); ?></span>
                                </div>  
                            </div>
                            <div class="col-sm-6 col-lg-4 col-md-4">
                                <div class="form-group">   
                                    <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                    <select id="class_id" name="class_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($classlist as $class) {
                                            ?>
                                            <option value="<?php echo $class['id'] ?>" <?php
                                            if (set_value('class_id') == $class['id']) {
                                                echo "selected=selected";
                                            }
                                            ?>><?php echo $class['class'] ?></option>
                                                    <?php
                                                }
                                                ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                </div>  
                            </div>
                            <div class="col-sm-6 col-lg-4 col-md-4">
                                <div class="form-group"> 
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                    <select  id="section_id" name="section_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4 col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('marksheet_template'); ?></label><small class="req"> *</small>
                                    <select  id="marksheet" name="marksheet" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($marksheetlist as $marksheet) {
                                            ?>
                                            <option value="<?php echo $marksheet->id ?>" <?php
                                            if (set_value('marksheet') == $marksheet->id) {
                                                echo "selected=selected";
                                            }
                                            ?>><?php echo $marksheet->template; ?></option>
                                                    <?php
                                                }
                                                ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('marksheet'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="aa"></div>
                    <?php
                    if (isset($studentList)) {
                        ?>
                        <form method="post" action="<?php echo base_url('admin/examresult/printmarksheet') ?>" id="printMarksheet">
                            <input type="hidden" name="marksheet_template" value="<?php echo $marksheet_template; ?>">
                            <div class="box-header ptbnull"></div>  
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('student_list'); ?></h3>
                                <button  class="btn btn-info btn-sm printSelected pull-right" type="submit" name="generate" title="generate multiple certificate"  data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('please_wait') ; ?>" > <?php echo $this->lang->line('bulk_download'); ?></button>
                            </div>
                            <div class="box-body">
                                <input type="hidden" name="post_exam_id" id="post_exam_id" value="<?php echo $exam_id; ?>">
                                <input type="hidden" name="post_exam_group_id" id="post_exam_group_id" value="<?php echo $exam_group_id; ?>">
                                <div class="tab-pane active table-responsive no-padding" id="tab_1">
                                <?php if (!empty($studentList)) { ?>
                                    <table class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="select_all" /></th>
                                                <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                <th><?php echo $this->lang->line('student_name'); ?></th>
                                                <th><?php echo $this->lang->line('father_name'); ?></th>
                                                <th><?php echo $this->lang->line('date_of_birth'); ?></th>
                                                <th><?php echo $this->lang->line('gender'); ?></th>
                                                <th><?php echo $this->lang->line('category'); ?></th>
                                                <th class=""><?php echo $this->lang->line('mobile_number'); ?></th>
                                                <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $count = 1;
                                                foreach ($studentList as $student_key => $student_value) {
                                                $student_name = $this->customlib->getFullName($student_value->firstname,$student_value->middlename,$student_value->lastname,$sch_setting->middlename,$sch_setting->lastname); 
                                                    ?>

                                                    <tr>
                                                        <td class="text-center"><input type="checkbox" class="checkbox center-block"  name="exam_group_class_batch_exam_student_id[]" data-student_id="<?php echo $student_value->exam_group_class_batch_exam_student_id; ?>" value="<?php echo $student_value->exam_group_class_batch_exam_student_id; ?>">
                                                        </td>
                                                        <td><?php echo $student_value->admission_no; ?></td>        
                                                        <td><?php echo $student_name; ?></td> 
                                                        <td><?php echo $student_value->father_name;?></td>
                                                        <td><?php 
															if (!empty($student_value->dob) && $student_value->dob != '0000-00-00') {
															echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student_value->dob)); }?></td>
                                                        <td><?php echo $this->lang->line(strtolower($student_value->gender)); ?></td>
                                                        <td><?php echo $student_value->category; ?></td>
                                                        <td><?php echo $student_value->mobileno; ?></td>
                                                        <td class="text-right white-space-nowrap">

                        <button  type="button" class="btn btn-default btn-xs download_pdf" data-action="download" data-toggle="tooltip"  data-original-title="<?php echo $this->lang->line('download'); ?>"  data-exam_group_class_batch_exam_student_id="<?php echo $student_value->exam_group_class_batch_exam_student_id; ?>" data-admission_no="<?php echo $student_value->admission_no;?>" data-student_name="<?php echo $student_name; ?>" data-student_id="<?php echo $student_value->student_id?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i>" ><i class="fa fa-download"></i></button>
                       <button  type="button"  class="btn btn-default btn-xs email_pdf" data-action="email" data-exam_group_class_batch_exam_student_id="<?php echo $student_value->exam_group_class_batch_exam_student_id; ?>" id="load1" data-toggle="tooltip"  data-original-title="<?php echo $this->lang->line('sent_to_email'); ?>" data-student_id="<?php echo $student_value->student_id?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i>" ><i class="fa fa-envelope"></i></button>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $count++;
                                                }
                                            ?>
                                        </tbody>
                                    <?php }else{ ?>
                                        <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                    <?php } ?>
                                    </table>
                                </div>                                                            
                            </div> 
                        </form>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
   $(document).on('click','.download_pdf',function(){

      var admission_no = $(this).attr('data-admission_no');
      var student_name = $(this).attr('data-student_name');
      let $button_ = $(this);
      let post_exam_id=$('#post_exam_id').val();
      let post_exam_group_id=$('#post_exam_group_id').val();
      var student_id=$button_.data('student_id');
      var action=($button_.data('action'));
      var exam_group_class_batch_exam_student_id=$button_.data('exam_group_class_batch_exam_student_id');
      let marksheet_template=$("input[name=marksheet_template]").val();

         $.ajax({
            type: 'POST',
            url: baseurl+'admin/examresult/pdftmarksheet',
            data: {
                'marksheet_template':marksheet_template,
                'type':action,
                'exam_group_class_batch_exam_student_id':exam_group_class_batch_exam_student_id,
                'student_id':student_id,
                'post_exam_id':post_exam_id,
                "post_exam_group_id":post_exam_group_id
            },
         
            beforeSend: function() { 
               $button_.button('loading');    
            },
             xhr: function () {// Seems like the only way to get access to the xhr object
                var xhr = new XMLHttpRequest();
                xhr.responseType = 'blob'
                return xhr;
            },
           success: function (data, jqXHR, response) {           
                
                   var blob = new Blob([data], {type: 'application/pdf'});
                   var link = document.createElement('a');
                   link.href = window.URL.createObjectURL(blob);
                   link.download =  student_name+'_'+admission_no;
                   document.body.appendChild(link);
                   link.click();
                   document.body.removeChild(link);
                   $button_.button('reset');
            },
            error: function(xhr) { // if error occured
              
                $button_.button('reset');
            },
            complete: function() {
             
                    $button_.button('reset');
              
            }
        });
    });

 $(document).on('click','.email_pdf',function(){
      let $button_ = $(this);
      let post_exam_id=$('#post_exam_id').val();
      let post_exam_group_id=$('#post_exam_group_id').val();
      var student_id=($button_.data('student_id'));
      var action=($button_.data('action'));
      var exam_group_class_batch_exam_student_id=($button_.data('exam_group_class_batch_exam_student_id'));
      let marksheet_template=$("input[name=marksheet_template]").val();
    $.ajax({
    type: 'POST',
    url: baseurl+'admin/examresult/pdftmarksheet',
    data: {
          'marksheet_template':marksheet_template,
        'type':action,
           'student_id':student_id,
           'exam_group_class_batch_exam_student_id':exam_group_class_batch_exam_student_id,
           'post_exam_id':post_exam_id,
           "post_exam_group_id":post_exam_group_id
          },
    dataType: 'JSON',
    beforeSend: function() {       
       $button_.button('loading');      
    },
   success: function (data, jqXHR, response) {
             if (data.status == 1) {
                  successMsg(data.message);
                } else {
                  errorMsg(data.message);
                }
            },
    error: function(xhr) { // if error occured      
        $button_.button('reset');
    },
    complete: function() {     
            $button_.button('reset');      
    }
});
    });

 $(document).ready(function () {
        $('.select2').select2();
    });
    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
    var class_id = '<?php echo set_value('class_id') ?>';
    var section_id = '<?php echo set_value('section_id') ?>';
    var session_id = '<?php echo set_value('session_id') ?>';
    var exam_group_id = '<?php echo set_value('exam_group_id') ?>';
    var exam_id = '<?php echo set_value('exam_id') ?>';
    getSectionByClass(class_id, section_id);
    getExamByExamgroup(exam_group_id, exam_id);
    $(document).on('change', '#exam_group_id', function (e) {
        $('#exam_id').html("");
        var exam_group_id = $(this).val();
        getExamByExamgroup(exam_group_id, 0);
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

    function getExamByExamgroup(exam_group_id, exam_id) {
        if (exam_group_id !== "") {
            $('#exam_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: "POST",
                url: base_url + "admin/examgroup/getExamByExamgroup",
                data: {'exam_group_id': exam_group_id},
                dataType: "json",
                beforeSend: function () {
                    $('#exam_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (exam_id === obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.exam + "</option>";
                    });

                    $('#exam_id').append(div_data);
                    $('#exam_id').trigger('change');
                },
                complete: function () {
                    $('#exam_id').removeClass('dropdownloading');
                }
            });
        }
    }
</script>
<script>

    $(document).on('submit', 'form#printMarksheet', function (e) {
        e.preventDefault();
        var form = $(this);
        var subsubmit_button = $(this).find(':submit');
        var formdata = form.serializeArray();
        var exam_name = $('#exam_id').find('option:selected').text();
        var session = $('#session_id').find('option:selected').text();
        var class_name = $('#class_id').find('option:selected').text();
        var section_name = $('#section_id').find('option:selected').text();
        var list_selected =  $('form#printMarksheet input[name="exam_group_class_batch_exam_student_id[]"]:checked').length;
      if(list_selected > 0){
        
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: formdata, // serializes the form's elements.
            beforeSend: function () {
                subsubmit_button.button('loading');
            },
             xhr: function () {// Seems like the only way to get access to the xhr object
                var xhr = new XMLHttpRequest();
                xhr.responseType = 'blob'
                return xhr;
            },
            success: function (data, jqXHR, response) {
       
                   var date_time = new Date().getTime();
                   var blob = new Blob([data], {type: 'application/pdf'});
                   var link = document.createElement('a');
                   link.href = window.URL.createObjectURL(blob);
                   link.download = exam_name +'_'+ session +'_'+ class_name +'_'+ section_name + ".pdf";
                   document.body.appendChild(link);
                   link.click();
                   document.body.removeChild(link);
                   subsubmit_button.button('reset');
            },
            error: function (xhr) { // if error occured

                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                subsubmit_button.button('reset');
            },
            complete: function () {
                subsubmit_button.button('reset');
            }
        });
      }else{
         confirm("<?php echo $this->lang->line('please_select_student'); ?>");
      }
    });

    $(document).on('click', '#select_all', function () {
        $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
    });
</script>

<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';
    function Popup(data)
    {
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
//Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
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