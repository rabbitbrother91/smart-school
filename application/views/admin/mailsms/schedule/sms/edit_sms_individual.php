<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-bullhorn"></i> <?php //echo $this->lang->line('communicate'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                <!-- Custom Tabs (Pulled to the right) -->
                <div class="nav-tabs-custom theme-shadow">
                    <ul class="nav nav-tabs pull-right">
                        <li class="pull-left header"> <?php echo $this->lang->line('send_sms_individual'); ?></li>
                    </ul>
                    <form action="<?php echo site_url('admin/mailsms/update_individual_sms_schedule') ?>" method="post" id="individual_form">
                        <input type="hidden" name="message_id" value="<?php echo $messagelist['id']; ?>">
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                 <label><?php echo $this->lang->line('sms_template'); ?></label>
                                                 <select name="template_id" id="template_id" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                     <?php foreach ($sms_template_list as $sms_template_list_value) {
    $selected = '';
    if ($messagelist['sms_template_id'] == $sms_template_list_value['id']) {
        $selected = 'selected';
    }
    ?>
                                                        <option value="<?php echo $sms_template_list_value['id']; ?>" <?php echo $selected; ?>><?php echo $sms_template_list_value['title']; ?></option>
                                                     <?php }?>
                                                 </select>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('title'); ?></label>
                                                <small class="req"> *</small>
                                                <input class="form-control" id="individual_title" name="individual_title" value="<?php echo $messagelist['title']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="pr20"><?php echo $this->lang->line('send_through'); ?><small class="req"> *</small></label>

                                                <?php
foreach ($send_through_list as $key => $send_through_list_value) {
    $selected_value = '';
    foreach ($selected_send_through as $selected_send_through_value) {
        if ($selected_send_through_value == $key) {
            $selected_value = 'checked';
        }
    }?>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" value="<?php echo $key; ?>" name="individual_send_by[]" <?php echo $selected_value; ?>> <?php echo $send_through_list_value; ?>
                                                    </label>

                                               <?php }
?>
                                                <span class="text-danger"><?php echo form_error('message'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('template_id'); ?> </label> (<?php echo $this->lang->line('this_field_is_reqiured_only_for_indian_sms_gateway'); ?>)
                                                <input type="text" name="individual_template_id" id="individual_template_id" class="form-control" autocomplete="off" value="<?php echo $messagelist['template_id'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('message'); ?></label><small class="req"> *</small>
                                                <textarea id="individual_msg_text" name="individual_message" class="form-control compose-textarea" rows="12"><?php echo $messagelist['message']; ?></textarea>
                                                <span class="text-muted tot_count_individual_msg_text pull-right word_counter" id="individual_word_counter"><?php echo $this->lang->line('character_count'); ?>: 0</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="inpuFname"><?php echo $this->lang->line('message_to'); ?></label><small class="req"> *</small>
                                                <div class="input-group">
                                                    <div class="input-group-btn bs-dropdown-to-select-group">
                                                        <button type="button" class="btn btn-default btn-searchsm dropdown-toggle as-is bs-dropdown-to-select" data-toggle="dropdown">
                                                            <span data-bind="bs-drp-sel-label"><?php echo $this->lang->line('select'); ?></span>
                                                            <input type="hidden" name="selected_value" data-bind="bs-drp-sel-value" value="">
                                                            <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu" role="menu" style="">
                                                            <li data-value="student"><a href="#" ><?php echo $this->lang->line('students'); ?></a></li>
                                                            <?php if ($sch_setting->guardian_name) {
    ?>
                                                                 <li data-value="parent"><a href="#"><?php echo $this->lang->line('guardians'); ?></a></li>
                                                            <li data-value="student_guardian"><a href="#" ><?php echo $this->lang->line('students_guardians'); ?></a></li>
                                                                <?php
}?>
                                                            <?php
foreach ($roles as $role_key => $role_value) {
    if ($role_value["name"] != 'Super Admin' || $superadmin_restriction != 'disabled') {?>

                                                                <li data-value="staff"><a href="#"><?php echo $role_value['name']; ?></a></li>
                                                                <?php
}}
?>
                                                        </ul>
                                                    </div>
                                                    <input type="text" value="" data-record="" data-email="" data-mobileno="" class="form-control" autocomplete="off" name="text" id="search-query">

                                                    <div id="suggesstion-box"></div>
                                                    <span class="input-group-btn">
                                                        <button  class="btn btn-primary btn-searchsm add-btn" type="button"><?php echo $this->lang->line('add'); ?></button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="dual-list list-right">
                                                <div class="well minheight260">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="input-group">
                                                                <input type="text" name="SearchDualList" class="form-control" placeholder="<?php echo $this->lang->line('search'); ?>..." />
                                                                <div class="input-group-btn"><span class="btn btn-default input-group-addon bright" style="height: 28px;"><i class="fa fa-search"></i></span></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <ul class="list-group send_list seach-list-with-icon">
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                 <div class="box-footer">
                                   <div class="row">
                                        <div class="col-md-12">
                                            <div class="pull-right">
                                                <div class="flex-direction-column d-sm-flex d-lg-flex justify-content-center align-items-lg-center align-items-sm-start sm-full-width">
                                                        <label for="exam_to"><?php echo $this->lang->line('schedule_date_time'); ?></label><small class="req"> *</small>
                                                        <div class="input-group">
                                                            <input class="form-control tddm200 datetime " name="schedule_date_time" type="text" id="schedule_date_time" value="<?php echo $this->customlib->dateyyyymmddToDateTimeformat($messagelist['schedule_date_time'], false); ?>">
                                                            <span class="input-group-addon" id="basic-addon2"><i class="fa fa-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary submit_group ml-lg-1" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('sending'); ?>" ><i class="fa fa-envelope-o"></i> <?php echo $this->lang->line('submit'); ?></button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-footer -->
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).on('click', '.dropdown-menu li', function () {
        $("#suggesstion-box ul").empty();
        $("#suggesstion-box").hide();
    });

    $(document).ready(function (e) {
        $(document).on('click', '.bs-dropdown-to-select-group .dropdown-menu li', function (event) {
            var $target = $(event.currentTarget);
            $target.closest('.bs-dropdown-to-select-group')
                    .find('[data-bind="bs-drp-sel-value"]').val($target.attr('data-value'))
                    .end()
                    .children('.dropdown-toggle').dropdown('toggle');
            $target.closest('.bs-dropdown-to-select-group')
                    .find('[data-bind="bs-drp-sel-label"]').text($target.context.textContent);
            return false;
        });
    });
</script>

<script type="text/javascript">
    var attr = {};

$(document).ready(function(){

    var user_list = '<?php echo $user_list; ?>';

    var cList = $('<ul/>').addClass('selector-list');
    $.each(JSON.parse(user_list), function (i, value)
    {
        var decode = JSON.stringify(value);
        var obj = JSON.parse(decode);
        var record_id = obj.user_id;
        var category_selected = obj.category;
        var mobileno = obj.mobileno;
        var app_key = obj.app_key;
        var email = obj.email;
        var guardianEmail = "";

        if(category_selected == "student_guardian"){
            guardianEmail = obj.guardianEmail;
            category_selected = '<?php echo $this->lang->line('student_guardian'); ?>';
        }

    if (record_id != "" || category_selected != "") {
        var chkexists = checkRecordExists(category_selected + "-" + record_id);
        if (chkexists) {
            var arr = [];
            arr.push({
                'category': category_selected,
                'record_id': record_id,
                'email': email,
                'guardianEmail': guardianEmail,
                'mobileno': mobileno,
                'app_key': app_key
            });

            attr[category_selected + "-" + record_id] = arr;
            $("#search-query").attr('value', "").val("");
            $("#search-query").attr('data-record', "");
            $(".send_list").append('<li class="list-group-item" id="' + category_selected + '-' + record_id + '"><i class="fa fa-user"></i> ' + obj.user_name + ' (' +category_selected.charAt(0).toUpperCase() + category_selected.slice(1) + ') <i class="fa fa-trash pull-right text-danger" onclick="delete_record(' + "'" + category_selected + '-' + record_id + "'" + ')"></i></li>');
        } else {
            errorMsg("<?php echo $this->lang->line('record_already_exist'); ?>");
        }
    } else {
        errorMsg("<?php echo $this->lang->line('incorrect_record'); ?>");
    }

        getTotalRecord();

    })
})

$(document).ready(function () {
    $("#search-query").keyup(function () {
        $("#search-query").attr('data-record', "");
        $("#search-query").attr('data-email', "");
        $("#search-query").attr('data-mobileno', "");
        $("#suggesstion-box").hide();
        var category_selected = $("input[name='selected_value']").val();

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('admin/mailsms/search') ?>",
            data: {'keyword': $(this).val(), 'category': category_selected},
            dataType: 'JSON',
            beforeSend: function () {
                $("#search-query").css("background", "#FFF url(../../../backend/images/loading.gif) no-repeat 165px");
            },
            success: function (data) {
                if (data.length > 0) {
                    setTimeout(function () {
                        $("#suggesstion-box").show();
                        var cList = $('<ul/>').addClass('selector-list');
                        $.each(data, function (i, obj)
                        {
                            if (category_selected == "student") {
                                //console.log(obj);
                                if (obj.app_key == null) {
                                    obj.app_key = "";
                                }
                                var app_key = obj.app_key;
                                var email = obj.email;
                                var contact = obj.mobileno;
                                var name = obj.fullname +  "(" + obj.admission_no + ")";
                            } else if (category_selected == "student_guardian") {
                                var app_key = '';
                                var email = obj.email;
                                var guardian_email = obj.guardian_email;
                                var contact = obj.mobileno;
                                var name = obj.fullname  + "(" + obj.admission_no + ")";
                            } else if (category_selected == "parent") {
                                if (obj.parent_app_key == null) {
                                    obj.app_key = "";
                                }
                                var app_key = obj.parent_app_key;
                                var email = obj.guardian_email;
                                var contact = obj.guardian_phone;
                                var name = obj.guardian_name;
                            } else if (category_selected == "staff") {
                                var app_key = '';
                                var email = obj.email;
                                var contact = obj.contact_no;
                                var name = obj.name + ' ' + obj.surname + '(' + obj.employee_id + ')';
                            }

                            var li = $('<li/>')
                                    .addClass('ui-menu-item')
                                    .attr('category', category_selected)
                                    .attr('record_id', obj.id)
                                    .attr('email', email)
                                    .attr('mobileno', contact)
                                    .attr('app_key', app_key)
                                    .text(name);

                            if (category_selected == "student_guardian") {
                                li.attr('data-guardian-email', guardian_email);
                            }
                            li.appendTo(cList);
                        });

                        $("#suggesstion-box").html(cList);
                        $("#search-query").css("background", "#FFF");

                    }
                    , 1000);
                } else {
                    $("#suggesstion-box").hide();
                    $("#search-query").css("background", "#FFF");
                }
            }
        });
    });
});

    $(document).on('click', '.selector-list li', function () {
        var val = $(this).text();
        var record_id = $(this).attr('record_id');
        var email = $(this).attr('email');
        var mobileno = $(this).attr('mobileno');
        var app_key = $(this).attr('app_key');

        $("#search-query").attr('value', val).val(val);
        $("#search-query").attr('data-record', record_id);
        $("#search-query").attr('data-email', email);
        if ($(this).data('guardianEmail') != undefined) {
            $("#search-query").attr('data-guardian-email', $(this).data('guardianEmail'));
        }
        $("#search-query").attr('data-mobileno', mobileno);
        $("#search-query").attr('data-app_key', app_key);
        $("#suggesstion-box").hide();
    });

    $(document).on('click', '.add-btn', function () {

        var guardianEmail = "";
        var value = $("#search-query").val();
        if ($.trim(value) != "") {
            var record_id = $("#search-query").attr('data-record');
            var app_key = $("#search-query").attr('data-app_key');
            var email = $("#search-query").attr('data-email');
            var mobileno = $("#search-query").attr('data-mobileno');
            if ($("#search-query").data('guardianEmail') != undefined) {
                var guardianEmail = $("#search-query").data('guardianEmail');
            }

            var category_selected = $("input[name='selected_value']").val();
            if (record_id != "" || category_selected != "") {
                var chkexists = checkRecordExists(category_selected + "-" + record_id);
                if (chkexists) {
                    var arr = [];
                    arr.push({
                        'category': category_selected,
                        'record_id': record_id,
                        'email': email,
                        'guardianEmail': guardianEmail,
                        'mobileno': mobileno,
                        'app_key': app_key
                    });

                    attr[category_selected + "-" + record_id] = arr;

                    if(category_selected == 'student'){
                        category_selected = '<?php echo $this->lang->line('student'); ?>';
                    }

                    if(category_selected == 'parent'){
                        category_selected = '<?php echo $this->lang->line('parent'); ?>';
                    }

                    if(category_selected == 'staff'){
                        category_selected = '<?php echo $this->lang->line('staff'); ?>';
                    }

                    if(category_selected == 'student_guardian'){
                        category_selected = '<?php echo $this->lang->line('student_guardian'); ?>';
                    }

                    $("#search-query").attr('value', "").val("");
                    $("#search-query").attr('data-record', "");
                    $(".send_list").append('<li class="list-group-item" id="' + category_selected + '-' + record_id + '"><i class="fa fa-user"></i> ' + value + ' (' + category_selected + ') <i class="fa fa-trash pull-right text-danger" onclick="delete_record(' + "'" + category_selected + '-' + record_id + "'" + ')"></i></li>');

                } else {
                    errorMsg("<?php echo $this->lang->line('record_already_exist'); ?>");
                }
            } else {
                errorMsg("<?php echo $this->lang->line('incorrect_record'); ?>");
            }
        } else {
            errorMsg("<?php echo $this->lang->line('please_select_record'); ?>");
        }

        getTotalRecord();
    });
</script>

<script type="text/javascript">
    function getTotalRecord() {
        $.each(attr, function (key, value) {
             
        });
    }

    function checkRecordExists(find) {
        if (find in attr) {
            return false;
        }
        return true;
    }

    $(function () {
        $('[name="SearchDualList"]').keyup(function (e) {
            var code = e.keyCode || e.which;
            if (code == '9')
                return;
            if (code == '27')
                $(this).val(null);
            var $rows = $(this).closest('.dual-list').find('.list-group li');
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
            $rows.show().filter(function () {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
    });

    function delete_record(record) {
        delete attr[record];
        $('#' + record).remove();
        getTotalRecord();
        return false;
    };

    $("#individual_form").submit(function (event) {
        event.preventDefault();
        var formData = new FormData();
        var other_data = $(this).serializeArray();
        $.each(other_data, function (key, input) {
            formData.append(input.name, input.value);
        });
        var objArr = [];
        var user_list = (!jQuery.isEmptyObject(attr)) ? JSON.stringify(attr) : "";
        formData.append('user_list', user_list);
        var $form = $(this),
        url = $form.attr('action');
        var $this = $('.submit_individual');
        $this.button('loading');
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            cache: false,
            async: false,
              beforeSend: function () {
                $this.button('loading');
            },
            success: function (data) {
                if (data.status == 1) {
                    var message = "";
                    $.each(data.msg, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    $('#individual_form')[0].reset();
                    $("ul.send_list").empty();
                    attr = {};
                    successMsg(data.msg);
                    window.location = "<?php echo base_url(); ?>admin/mailsms/schedule";
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }, complete: function (data) {
                $this.button('reset');
            }
        })
    });

    $(document).on('keypress keyup keydown paste change focus blur', '.compose-textarea', function (event) {
        var total_length = checkTextAreaMaxLength(this, event);
        $(this).next('span.word_counter').html("<?php echo $this->lang->line('character_count') ?>: " + total_length)
    });

    function checkTextAreaMaxLength(textBox, e) {
        return textBox.value.length;
    }
</script>

<script type="text/javascript">
    $('#template_id').change(function(){
    var template_id =  $('#template_id').val();

        $.ajax({
            url : '<?php echo base_url(); ?>admin/mailsms/smstemplatedata',
            type: 'post',
            data : {template_id:template_id},
            dataType: 'json',
            success:function(response){
                $('#individual_title').val(response.data.title);
                $('#individual_msg_text').val(response.data.message);
                $('#individual_word_counter').html("<?php echo $this->lang->line('character_count') ?>: " + (response.data.message.length));
            }
        })
    });

    $(document).on('keypress keyup keydown paste change focus blur', '.compose-textarea', function (event) {
        var total_length = checkTextAreaMaxLength(this, event);
        $(this).next('span.word_counter').html("<?php echo $this->lang->line('character_count') ?>: " + total_length)
    });
    function checkTextAreaMaxLength(textBox, e) {
        return textBox.value.length;
    }

    $(document).ready(function(){
        var total_text = $('#individual_msg_text').val();
        $('span.word_counter').html("<?php echo $this->lang->line('character_count') ?>: " + total_text.length);
    })
</script>