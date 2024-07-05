<?php
$language = $this->customlib->getLanguage();
$language_name = $language["short_code"];
?>
<div class="content-wrapper">
    <section class="content">
        <?php $this->load->view('reports/_lesson_plan'); ?>
        <div class="box removeboxmius">
            <div class="box-header ptbnull">
                <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
            </div>
            <form class="assign_teacher_form" action="<?php echo base_url(); ?>report/teachersyllabusstatus/" method="post" enctype="multipart/form-data">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php 
                                    echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg');
                                ?>
                            <?php } ?>
                            <?php echo $this->customlib->getCSRF(); ?>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                <select autofocus="" id="searchclassid" name="class_id" onchange="getSectionByClass(this.value, 0, 'secid')"  class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    foreach ($classlist as $class) {
                                        ?>
                                        <option <?php
                                        if ($class_id == $class["id"]) {
                                            echo "selected";
                                        }
                                        ?> value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                            <?php
                                        }
                                        ?>
                                </select>
                                <span class="class_id_error text-danger"><?php echo form_error('class_id'); ?></span>
                            </div>
                        </div> 
                        <div class="col-md-3 col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                <select  id="secid" name="section_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="class_id_error text-danger"><?php echo form_error('section_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('subject_group') ?></label><small class="req"> *</small>
                                <select  id="subject_group_id" name="subject_group_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="section_id_error text-danger"><?php echo form_error('subject_group_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('subject'); ?></label><small class="req"> *</small>
                                <select  id="subid" name="subject_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="class_id_error text-danger"><?php echo form_error('subject_id'); ?></span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" id="search_filter" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
            </form>
        </div>

        <?php if (!empty($subjects_data) && $subject_name != '') {
            ?>
             
            <div class="box-body" id="transfee">                
                <div class="box-header ptbnull">
                    <h3 class="box-title titlefix"><i class="fa fa-money"></i>  <?php
                                echo $this->lang->line('subject_lesson_plan_report_for') . ": " . $subject_name;
                                if ($subject_name != '') {
                                    echo " " . $this->lang->line('complete') . " " . $subject_complete . "%";
                                }
                                ?></h3>
                </div>                        
                <div class="download_label">
                            <?php
                                echo $this->lang->line('subject_lesson_plan_report_for') . ": " . $subject_name;
                                if ($subject_name != '') {
                                    echo " " . $this->lang->line('complete') . " " . $subject_complete . "%";
                                }
                                ?>
                </div>
                <table class="table table-bordered syllbus" id="headerTable">                    
                    <tbody>
                        <?php
                        foreach ($subjects_data as $key => $value) {
                            ?>
                            <tr>
                                <td>
                                    <table class="table table-striped table-bordered table-hover example" >
                                        <thead>
                                            <tr>  
                                                <th class="text-left"><?php echo $this->lang->line('teacher'); ?></th>    
                                                <th class="text-left"><?php echo $this->lang->line('lesson_name'); ?></th>     
                                                <th class="text-left"><?php echo $this->lang->line('topic_name'); ?></th> 
                                                <th class="text-left"><?php echo $this->lang->line('sub_topic'); ?></th>
                                                <th class="text-left"><?php echo $this->lang->line('date'); ?></th>
                                                <th><?php echo $this->lang->line('time_from'); ?></th>
                                                <th class="text-left"><?php echo $this->lang->line('time_to'); ?></th>                
                                            </tr> 
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($value['teachers_summary'] as $teachers_summarykey => $teachers_summaryvalue) {
                                                foreach ($teachers_summaryvalue['summary_report'] as $summary_report_key => $summary_report_value) {
                                                    ?>
                                                    <tr>  
                                                        <td class="text-left"><?php echo $teachers_summaryvalue['name']; ?></td>   
                                                        <td class="text-left"><?php echo $summary_report_value['lesson_name'] ?></td>
                                                        <td class="text-left"><?php echo $summary_report_value['topic_name'] ?></td>
                                                        <td class="text-left"><?php echo $summary_report_value['sub_topic'] ?></td>                  
                                                        <td class="text-left"><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($summary_report_value['date'])); ?></td>
                                                        <td class="text-left"><?php echo $summary_report_value['time_from']; ?></td>
                                                        <td class="text-left"><?php echo $summary_report_value['time_to']; ?></td>
                                                    </tr>
                                                    <?php }
                                                }
                                                ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
            <?php } ?> 
                    </tbody>
                </table>
            </div>
    <?php
} else {
    ?>
            <div class="tab-pane active table-responsive box-body" >
                <div class="download_label"> <?php echo $this->lang->line('subject_lesson_plan_report'); ?></div>
                <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo $this->lang->line('teacher'); ?></th>    
                            <th><?php echo $this->lang->line('lesson_name'); ?></th>       
                            <th><?php echo $this->lang->line('topic_name'); ?></th> 
                            <th><?php echo $this->lang->line('sub_topic'); ?></th>             
                            <th><?php echo $this->lang->line('date'); ?></th>
                            <th><?php echo $this->lang->line('time_from'); ?></th>
                            <th><?php echo $this->lang->line('time_to'); ?></th>
                            <th class="pull-right noExport"><?php echo $this->lang->line('action'); ?></th>    
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
<?php }
?>
    </section>
</div>

<div class="modal fade syllbus" id="assignsyllabus" tabindex="-1" role="dialog" aria-labelledby="evaluation" style="padding-left: 0 !important">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title" ><?php echo $this->lang->line('lesson_plan'); ?> </h4>
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="row">                               
                            <div id="syllabus_result" class=""></div> 
                        </div><!--./row-->
                    </div><!--./col-md-12-->
                </div><!--./row-->
            </div>
        </div>
    </div>
</div>

<div class="modal fade syllbus" id="lacture_youtube_modal"  role="dialog" aria-labelledby="evaluation" style="background: rgba(0, 0, 0, 0.98);" >
    <div class="modal-dialog modal-lg" role="document" style="width:100%;height:100%">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" onclick="videoUrlBlank()" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title" ><?php echo $this->lang->line('youtube_link') ?></h4>
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="row"  style="width:100%;height:660px">
                            <div id="video_url" ></div>
                        </div><!--./row-->
                    </div><!--./col-md-12-->
                </div><!--./row-->
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('submit','.assign_teacher_form', function(){
        document.getElementById('search_filter').disabled = true;
    });
    
    function subject_syllabusdelete(syllabus_id) {
        if (confirm('<?PHP echo $this->lang->line('delete_confirm') ?>')) {
            $.ajax({
                type: "POST",
                url: base_url + "admin/syllabus/delete_subject_syllabus",
                data: {'id': syllabus_id},
                success: function (data) {
                    successMsg('<?php echo $this->lang->line("delete_message"); ?>');
                    window.location.reload(true);
                },
            });
        }
    }
    
    function videoUrlBlank() {
        $('#video_url').html('');
    }
</script>

<script>
    function run_video(lacture_youtube_url) {
        $('#lacture_youtube_modal').modal('show');
        var str = lacture_youtube_url;
        var res = str.split("=");
        $('#video_url').html('<iframe width="100%" height="650px" src="//www.youtube.com/embed/' + res[1] + '?rel=0&version=3&modestbranding=1&autoplay=1&controls=1&showinfo=1&loop=1mode=opaque&amp;rel=0&amp;autohide=1&amp;showinfo=0&amp;wmode=transparent" frameborder="0" allowfullscreen></iframe>');
    }
</script> 

<script>
    function get_subject_syllabus(id, staff_id) {
        $('#assignsyllabus').modal('show');
        $('#syllabus_result').html('');
        $.ajax({
            type: "POST",
            url: base_url + "admin/syllabus/get_subject_syllabus",
            data: {'id': id, 'staff_id': staff_id},
            success: function (data) {
                $('#syllabus_result').html(data);
            },
        });
    }

    $(document).ready(function () {
        $(document).on('click', '.chk', function () {           
            var checked = $(this).is(':checked');
            var rowid = $(this).data('rowid');
            var role = $(this).data('role');
            if (checked) {
                if (!confirm("<?php echo $this->lang->line('are_you_sure_you_active_account'); ?>")) {
                    $(this).removeAttr('checked');
                } else {
                    var status = "yes";
                }
            } else if (!confirm("<?php echo $this->lang->line('are_you_sure_you_deactive_account'); ?>")) {
                $(this).prop("checked", true);
            } else {
                var status = "no";
            }
        });
    });
</script>

<script>
    $(document).ready(function (e) {
        getSectionByClass("<?php echo $class_id ?>", "<?php echo $section_id ?>", 'secid');

        getSubjectGroup("<?php echo $class_id ?>", "<?php echo $section_id ?>", "<?php echo $subject_group_id ?>", 'subject_group_id')
        getsubjectBySubjectGroup("<?php echo $class_id ?>", "<?php echo $section_id ?>", "<?php echo $subject_group_id ?>", "<?php echo $subject_id ?>", 'subid');
    });
    
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

    $(document).on('change', '#subject_group_id', function () {
        var class_id = $('#searchclassid').val();
        var section_id = $('#secid').val();
        var subject_group_id = $(this).val();
        getsubjectBySubjectGroup(class_id, section_id, subject_group_id, 0, 'subid');
    });

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
                        var sel = "";
                        if (subject_group_subject_id == obj.id) {
                            sel = "selected";
                        }
                        
                        code ='';
                        if(obj.code){
                            code = " (" + obj.code + ") ";
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
</script> 