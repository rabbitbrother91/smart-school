<script src="<?php echo base_url() ?>backend/fullcalendar/dist/fullcalendar.min.js"></script>
<script src="<?php echo base_url() ?>backend/fullcalendar/dist/locale-all.js"></script>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-body">
                        <div id="calendar_event"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title"> <?php echo $this->lang->line('event_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('events', 'can_add')) {?> <button class="btn btn-primary btn-sm pull-right" onclick="add_event()"><?php echo $this->lang->line('add_event'); ?></button>
                            <?php }?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $title; ?></div>
                        <div class="table-responsive overflow-visible">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr class="white-space-nowrap">
                                        <th><?php echo $this->lang->line('event_title'); ?></th>
                                        <th><?php echo $this->lang->line('class') .' <br>'. $this->lang->line('section'); ?></th>
                                        <th><?php echo $this->lang->line('pass_out_session'); ?></th>
                                        <th><?php echo $this->lang->line('from'); ?></th>
                                        <th><?php echo $this->lang->line('to'); ?></th>
                                        <th class=" noExport" width="15%"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
$sl = '';
foreach ($eventlist as $key => $value) {
    $sl++;
    ?>
                                        <tr>
                                            <td><?php echo $value['title']; ?></td>
                                            <td><?php
if ($value['event_for'] == 'class') {
        echo $eventclass[$key];
    } else {
        echo $this->lang->line($value['event_for']);
    }
    ?>
                                            <br><?php
if ($value['event_for'] == 'class') {
        $sl = '';
        foreach ($eventsection[$key] as $eventsection_value) {

            $json_array = json_decode($value['section']);
            if (in_array($eventsection_value['id'], $json_array)) {
                $sl++;

                echo $eventsection_value['section'];
                if (count($json_array) > $sl) {
                    echo ", ";
                }
            }
        }
    }
    ?>

                                            </td>
                                            <td><?php
if ($value['event_for'] == 'class') {
        echo $eventsession[$key];
    }
    ?></td>
                                            <td><?php echo $this->customlib->dateformat($value['from_date']); ?></td>
                                            <td><?php echo $this->customlib->dateformat($value['to_date']); ?></td>
                                            <td class="white-space-nowrap">
                                                <a onclick="view_event_data('<?php echo $value['id']; ?>')" class="btn btn-default btn-xs"  data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('view') ?>"><i class="fa fa-reorder"></i></a>
                                                
                                                <?php if ($this->rbac->hasPrivilege('events', 'can_edit')) {?>
                                                    <a class="btn btn-default btn-xs" onclick="edit('<?php echo $value['id']; ?>')" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>
                                                <?php }if ($this->rbac->hasPrivilege('events', 'can_delete')) {?>
                                                    <a onclick="event_delete('<?php echo $value['id']; ?>')" class="btn btn-default btn-xs"  data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('delete') ?>"><i class="fa fa-remove"></i></a>
                                                <?php }?>                                       
                                                    
                                                    
                                                </td>
                                        </tr>
    <?php
}
?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="newevent" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal-title"></h4>
            </div>
            <form role="form" id="addevent_form11" method="post" enctype="multipart/form-data" action="">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label><?php echo $this->lang->line('event_for'); ?></label><small class="req"> *</small>&nbsp;&nbsp;&nbsp;
                            <label class="radio-inline">
                                <input onchange="hideshowclass()" type="radio" name="event_for" id="all" value="all" autocomplete="off" checked> <?php echo $this->lang->line('all_alumni'); ?>&nbsp;&nbsp;&nbsp;
                            </label>
                            <label class="radio-inline">
                                <input onchange="hideshowclass()" type="radio" name="event_for" id="class" value="class" autocomplete="off"> <?php echo $this->lang->line('class'); ?>
                            </label>
                        </div>
                        <div id="sessionlist" class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 hide">
                            <label class="displayblock"><?php echo $this->lang->line('pass_out_session'); ?> <small class="req"> *</small></label>
                            <select autofocus="" id="session_id" name="session_id" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
foreach ($sessionlist as $sessions) {
    ?>
                                    <option value="<?php echo $sessions['id'] ?>" <?php if (set_value('session_id') == $sessions['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $sessions['session'] ?></option>
    <?php
 
}
?>
                            </select>
                            <span class="text-danger"><?php echo form_error('session_id'); ?></span>
                        </div>
                        <div id="classlist" class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 hide">
                            <label class="displayblock"><?php echo $this->lang->line('select_class'); ?><small class="req"> *</small></label>
                            <select onchange="getsectionlist()" id="class_id" name="class_id" class="form-control"  >
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
}
?>
                            </select>
                        </div>
                        <div id="sectionlist" class="form-group col-md-12 hide">
                            <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                            <ul id="section_list" class="list-group section_list listcheckbox">
                            </ul>
                        </div>
                        <div class="form-group col-md-12">
                            <label><?php echo $this->lang->line('event_title'); ?></label><small class="req"> *</small>
                            <input type="hidden" name="id" id="id">
                            <input class="form-control" id="event_title" name="event_title">
                            <span class="text-danger"><?php echo form_error('title'); ?></span>
                        </div>
                        <div class="form-group col-md-4">
                            <label><?php echo $this->lang->line('event_from_date'); ?></label><small class="req"> *</small>                          
                                <input type="text" autocomplete="off" id="from_date" name="from_date" class="form-control date " >                            
                        </div>
                         <div class="form-group col-md-4">
                            <label><?php echo $this->lang->line('event_to_date'); ?></label><small class="req"> *</small>                          
                                <input type="text" autocomplete="off" id="to_date" name="to_date" class="form-control date">
                            
                        </div>
                         <div class="form-group col-md-4">
                            <label><?php echo $this->lang->line('photo'); ?> (100px X 100px)</label>
                                    <input id="photo" name="file" placeholder="" type="file" class="filestyle form-control" />
                        </div>
                        <div class="form-group col-md-12">
                            <label><?php echo $this->lang->line("note"); ?></label>
                            <textarea name="note" id="note" class="form-control"></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label><?php echo $this->lang->line("event_notification_message"); ?></label>
                            <textarea class="form-control" id="event_notification_message" type="text" autocomplete="off" name="event_notification_message" placeholder=""></textarea>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="checkbox-inline"><input type="checkbox" name="email" value="1"> <?php echo $this->lang->line('email'); ?>
                                </label>
                                <label class="checkbox-inline"><input type="checkbox" name="sms" value="1"> <?php echo $this->lang->line('sms'); ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('template_id'); ?>  </label> (<?php echo $this->lang->line('this_field_is_reqiured_only_for_indian_sms_gateway'); ?>)
                                <input type="text" name="template_id" id="template_id" class="form-control" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="">
                        <button type="submit" class="btn btn-primary pull-right" data-loading-text="<?php echo $this->lang->line('submitting') ?>" value=""><?php echo $this->lang->line('save'); ?></button></div>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="event_details" class="modal fade " role="dialog">
    <div class="modal-dialog modal-dialog2 modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" >&times;</button>
                <h4 class="modal-title" id="modal-title" ><?php echo $this->lang->line('event_description');?></h4>
            </div>
            <div> 
                <div class="scroll-area">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-8">
                                <label class="bolds displayblock pb5" id="alumni_title"></label>                   
                                <div class='font12 minus8 widget-user-desc'><i class='fa fa-clock-o'></i> <span id="alumni_dates"></span> </div>
                                
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">                                
                                <span id="event_photo" class="pull-right"></span>
                            </div>
                        </div>
                        <hr class="mb10 mt10" />    
                        <div class="row">
                            <div class="col-md-12">
                                <label class="bolds displayblock"><?php echo $this->lang->line("note"); ?></label>
                                <span id="alumni_note"></span>
                            </div>
                            
                        </div>
                        <hr class="mb10 mt10" />  
                        <div class="row">
                            <div class="col-md-12">
                                <label class="bolds displayblock"><?php echo $this->lang->line("event_notification_message"); ?></label>
                                <span id="alumni_notification_message"></span>
                            </div>
                        </div>   
                    </div>
                </div> 
            </div>
       </div>
    </div>
</div>

<script type="application/javascript">    
    
    function view_event_data(id){   
        $.ajax({
            type: "POST",
            url: base_url + "admin/alumni/get_event/"+id,
            data: {'id': id},
            dataType: "json",
            success: function (data) {
               $('#event_details').modal('show');
               $('#alumni_note').html(data.note);
               $('#alumni_notification_message').html(data.event_notification_message);
               $('#alumni_title').html(data.title);
               $('#alumni_dates').html(data.from_date+" - "+data.to_date);
               $('#event_photo').html('<img class="img-responsive img-rounded sfborder-img-shadow" width="50%" src="'+data.photo+'"/>');
            }
        });
    } 
</script>
<script>
    function hideshowclass() {
       
        var event_for = $("input[name='event_for']:checked").val();
        if (event_for == 'class') {
            $("#classlist").removeClass("hide");
            $("#sectionlist").removeClass("hide");
            $("#sessionlist").removeClass("hide");
            $("#session_id").val("");
            $("#class_id").val("");
            getsectionlist();
        } else if (event_for == 'all') {
            $("#classlist").addClass("hide");
            $("#sectionlist").addClass("hide");
            $("#sessionlist").addClass("hide");
        }
    }
</script>
<script >
    function getsectionlist(newsection) {
        $('#section_list').html("");
        var class_id = $('#class_id').val();
        var base_url = '<?php echo base_url() ?>';
        var url = "<?php
$userdata = $this->customlib->getUserData();
if (($userdata["role_id"] == 2)) {
    echo "getClassTeacherSection";
} else {
    echo "getByClass";
}
?>";
        var div_data = '';
        $.ajax({
            type: "GET",
            url: base_url + "sections/getByClass",
            data: {'class_id': class_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data += '<li class="checkbox"><a href="#" class="small"><label><input type="checkbox" name="user[]" value ="' + obj.section_id + '"/>' + obj.section + '</label></a></li>';
                });

                $('#section_list').append(div_data);
                var JSONObject = JSON.parse(newsection);
                for (var i = 0, l = JSONObject.length; i < l; i++) {
                    $('input[name="user[]"][value="' + JSONObject[i] + '"]').prop("checked", true);
                }

            }
        });
    }
</script>
<script type="application/javascript">  
    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy','M'=>'MM']) ?>';
    function event_delete(id){

        var result = confirm("<?php echo $this->lang->line('delete_confirm'); ?>");
        if(result){
            $.ajax({
                url: "<?php echo base_url(); ?>admin/alumni/delete_event/"+id,
                type: "POST",  
            
                success: function (res)
                {
                successMsg('<?php echo $this->lang->line("delete_message"); ?>');
                window.location.reload(true);
                },
                error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            
                },
                complete: function () {
            
                }
        
            });
        }
    }

    function edit(id,row){
    $('#modal-title').html('<?php echo $this->lang->line('edit_event') ?>');
    $.ajax({
    url: "<?php echo site_url("admin/alumni/get_event") ?>/"+id,
    type: "POST",

    dataType: 'json',
    contentType: false,
    cache: false,
    processData: false,

    success: function (res)
    {

    if (res.event_for == 'class'){
    $("#class").prop("checked", true);
    hideshowclass();
    $('#session_id').val(res.session_id);
    $('#class_id').val(res.class_id);
    getsectionlist(res.section);

    } else {
    $("#all").prop("checked", true);
    hideshowclass();
    }



 $("#from_date").datepicker({
                        format: date_format,
                        autoclose: true
                    }).datepicker("update", new Date(res.from_date)); 


 $("#to_date").datepicker({
                        format: date_format,
                        autoclose: true
                    }).datepicker("update", new Date(res.to_date)); 


    $('#event_title').val(res.title);
    // $("#from_date").val(res.from_date);
    // $("#to_date").val(res.to_date);
    $('#Visibilty').val(res.show_onwebsite);
    $('#id').val(res.id);
    $('#note').val(res.note);
    $('#event_notification_message').val(res.event_notification_message);
    $('#newevent').modal('show');
    },
    error: function (xhr) { // if error occured
    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

    },
    complete: function () {

    }

    });
    }

    function myNavFunction(id) {

    }

    function myDateFunction(id, fromModal) {
    var date = $("#" + id).data("date");

    }

    function add_event(){ 
    $('#event_title').val('');
    $('#note').val('');
    $('#id').val('');
    $("#all").prop("checked", true);
    hideshowclass();
    $('#event_notification_message').val('');
    $('#modal-title').html('<?php echo $this->lang->line('add_event'); ?>');
    $('#newevent').modal('show');
    }

    $("#addevent_form11").on('submit', (function (e) {
    e.preventDefault();

    var $this = $(this).find("button[type=submit]:focus");
    $this.button('loading');
    $.ajax({
    url: "<?php echo site_url("admin/alumni/add_event") ?>",
    type: "POST",
    data: new FormData(this),
    dataType: 'json',
    contentType: false,
    cache: false,
    processData: false,
    beforeSend: function () {
    $this.button('loading');

    },
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
</script> 