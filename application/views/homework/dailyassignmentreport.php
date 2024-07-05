<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('homework/_homeworkordailyassignmentreport'); ?>
        <div class="box removeboxmius">
            <div class="box-header ptbnull"></div>
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
            </div>          
            
            <form  class="search_daily_assignment_form" action="<?php echo base_url(); ?>homework/dailyassignmentreportvalidation" method="post" enctype="multipart/form-data">             
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if ($this->session->flashdata('msg')) {?>
                                <?php 
                                    echo $this->session->flashdata('msg'); 
                                    $this->session->unset_userdata('msg');
                                ?>
                            <?php } ?>
                            <?php echo $this->customlib->getCSRF(); ?>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('search_type'); ?></label><small class="req"> *</small>
                                <select class="form-control" name="search_type" onchange="showdate(this.value)">

                                    <?php foreach ($searchlist as $key => $search) { ?>
                                    <option value="<?php echo $key ?>" <?php
                                            if ((isset($search_type)) && ($search_type == $key)) {

                                                echo "selected";
                                            }
                                            ?>><?php echo $search ?></option>
                                    <?php } ?>
                                </select>
                                <span class="text-danger" id="error_search_type"></span>
                                </div>
                        </div>
                        <div id='date_result'></div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('class'); ?><small class="req"> *</small></label>
                                <select autofocus="" id="searchclassid" name="class_id" onchange="getSectionByClass(this.value, 0, 'secid')"  class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                     foreach ($classlist as $class) {
                                    ?>
                                    <option value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <span class="text-danger" id="error_class_id"></span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('section'); ?><small class="req"> *</small></label>
                                <select  id="secid" name="section_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="text-danger" id="error_section_id"></span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('subject_group'); ?><small class="req"> *</small></label>
                                <select  id="subject_group_id" name="subject_group_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="text-danger" id="error_subject_group_id"></span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('subject'); ?><small class="req"> *</small></label>
                                <select  id="subid" name="subject_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="text-danger" id="error_subject_id"></span>
                                
                            </div>
                        </div>
                    </div>
                    <button type="submit" id="search_filter" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                </div>
            </form>

            <div class="col-md-12" id="errorinfo">
            </div>
            <div class="" id="box_display">
                <div class="box-header ptbnull"></div>
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"> </i> <?php echo $this->lang->line('daily_assignment_report'); ?> </h3>
                </div>
                <div class="box-body table-responsive">
                    
                    <table class="table table-striped table-bordered table-hover dailyassignmentlist" data-export-title="<?php echo $this->lang->line('daily_assignment_report'); ?>">
                        <thead>
                            <tr>
                                <th><?php echo $this->lang->line('student_name') ?></th>
                                <th><?php echo $this->lang->line('class'); ?> </th>
                                <th><?php echo $this->lang->line('section'); ?></th>
                                <th><?php echo $this->lang->line('total_assignment'); ?></th>
                                <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!--./box box-primary-->
    </section>
</div>

<div class="modal fade" id="dailyassignmentdetails" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('daily_assignment_details'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="scroll-area-inside">
                    <div id="assigndata"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function dailyassignmentdetails(student_id)
    {
        var search_type = $('select[name="search_type"] option:selected').val();
        var subject_id = $('select[name="subject_id"] option:selected').val();        
        var date_from = $("#date_from").val();
        var date_to = $("#date_to").val();     

        $.ajax({
            type: 'POST',
            url: base_url + 'homework/dailyassignmentdetails',
            data: {'student_id': student_id,'search_type':search_type,'subject_id':subject_id,'date_from':date_from,'date_to':date_to},
            dataType: 'JSON',
            success: function (response) {
               $('#assigndata').html(response.page);
            }  
        });
    }
</script>

<script type="text/javascript">

    var save_method; //for save method string
    var update_id; //for save method string

    function getSectionByClass(class_id, section_id, select_control) {
        if (class_id != "") {
            $('#' + select_control).html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('#' + select_control).addClass('dropdownloading');
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
                    $('#' + select_control).append(div_data);
                },
                complete: function () {
                    $('#' + select_control).removeClass('dropdownloading');
                }
            });
        }
    }

    $(document).on('change', '#secid', function () {
        var class_id = $('#searchclassid').val();
        var section_id = $(this).val();
        getSubjectGroup(class_id, section_id, 0, 'subject_group_id');
    });

    $(document).on('change', '#subject_group_id', function () {
        var class_id = $('#searchclassid').val();
        var section_id = $('#secid').val();
        var subject_group_id = $(this).val();
        getsubjectBySubjectGroup(class_id, section_id, subject_group_id, 0, 'subid');
    });

    function getSubjectGroup(class_id, section_id, subjectgroup_id, subject_group_target) {
        if (class_id != "" && section_id != "") {

            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: 'POST',
                url: base_url + 'admin/subjectgroup/getGroupByClassandSection',
                data: {'class_id': class_id, 'section_id': section_id},
                dataType: 'JSON',
                beforeSend: function () {
                    // setting a timeout
                    $('#' + subject_group_target).html("").addClass('dropdownloading');
                },
                success: function (data) {

                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (subjectgroup_id == obj.subject_group_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.subject_group_id + " " + sel + ">" + obj.name + "</option>";
                    });
                    $('#' + subject_group_target).append(div_data);
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                },
                complete: function () {
                    $('#' + subject_group_target).removeClass('dropdownloading');
                }
            });
        }
    }

    function getsubjectBySubjectGroup(class_id, section_id, subject_group_id, subject_group_subject_id, subject_target) {
        if (class_id != "" && section_id != "" && subject_group_id != "") {

            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: 'POST',
                url: base_url + 'admin/subjectgroup/getGroupsubjects',
                data: {'subject_group_id': subject_group_id},
                dataType: 'JSON',
                beforeSend: function () {
                    // setting a timeout
                    $('#' + subject_target).html("").addClass('dropdownloading');
                },
                success: function (data) {
                    console.log(data);
                    $.each(data, function (i, obj)
                    {
                        var code ='';
                        if(obj.code){
                            code = " (" + obj.code + ") ";
                        }
                        
                        var sel = "";
                        if (subject_group_subject_id == obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.name + code + "</option>";
                    });
                    $('#' + subject_target).append(div_data);
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

                },
                complete: function () {
                    $('#' + subject_target).removeClass('dropdownloading');
                }
            });
        }
    }
    
    $(document).on('submit','.search_daily_assignment_form',function(e){
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
                 
                   initDatatable('dailyassignmentlist','homework/searchdailyassignmentreport',response.params,[],100,

                    [{ "bSortable": false, "aTargets": [ -1,] ,'sClass': 'dt-body-right'},{ "bSortable": false, "aTargets": [ -2 ] }],                    

                    );
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
</script>