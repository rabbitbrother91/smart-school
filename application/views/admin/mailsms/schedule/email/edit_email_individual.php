<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>backend/js/ckeditor_config.js"></script>
<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/adapters/jquery.js"></script>
<script src="<?php echo base_url() ?>backend/plugins/ckeditor/plugins/ckeditor_wiris/integration/WIRISplugins.js?viewer=image"></script>

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
                        <li class="pull-left header"> <?php echo $this->lang->line('send_email_individual'); ?> </li>
                    </ul>
                            <form action="<?php echo site_url('admin/mailsms/update_individual_schedule') ?>" method="post" id="individual_form">
                                <input type="hidden" name="message_id" value="<?php echo $messagelist['id']; ?>">
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                 <label><?php echo $this->lang->line('email_template'); ?></label>
                                                 <select name="template_id" id="individual_email_template" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                     <?php foreach ($email_template_list as $email_template_list_value) {
    $selected = '';
    if ($messagelist['email_template_id'] == $email_template_list_value['id']) {
        $selected = 'selected';
    }
    ?>
                                                        <option value="<?php echo $email_template_list_value['id']; ?>" <?php echo $selected; ?>><?php echo $email_template_list_value['title']; ?></option>
                                                     <?php }?>
                                                 </select>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('title'); ?></label>
                                                <small class="req"> *</small>
                                                <input class="form-control" name="individual_title" id="individual_title" value="<?php echo $messagelist['title']; ?>">
                                            </div>
                                            <div id="individual_group_file" class="row"></div>
                                            <div id="individual_my_attachment" class="row"></div>
                                            <div class="form-group">
                                                <label class="pr20"><?php echo $this->lang->line('attachment'); ?></label>
                                                <input onchange="individual_preview()"  type="file" id="individual_group_attachment" class="filestyle form-control" name="induvidual_group_attachment[]" multiple="multiple">
                                                <span class="text-danger"><?php echo form_error('message'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('message'); ?></label><small class="req"> *</small>
                                                <textarea id="individual_msg_text" name="individual_message" class="form-control compose-textarea ckeditor">
                                                   <?php echo $messagelist['message']; ?>
                                                </textarea>
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
                                                            <?php
if ($sch_setting->guardian_name) {
    ?>
                                                                <li data-value="parent"><a href="#"><?php echo $this->lang->line('guardians'); ?></a></li>
                                                            <li data-value="student_guardian"><a href="#" ><?php echo $this->lang->line('students_guardians'); ?></a></li>
                                                                <?php
}
?>

                                                            <?php
foreach ($roles as $role_key => $role_value) {

    if ($role_value["name"] != 'Super Admin' || $superadmin_restriction != 'disabled') {?>

                                                                <li data-value="staff"><a href="#"><?php echo $role_value['name']; ?></a></li>
                                                                <?php
}
}
?>
                                                        </ul>
                                                    </div>
                                                    <input type="text" value="" data-record="" data-email="" data-mobileno="" class="form-control" autocomplete="off" name="text" id="search-query">

                                                    <div id="suggesstion-box"></div>
                                                    <span class="input-group-btn">
                                                        <button  class="btn btn-primary btn-searchsm add-btn" type="button"><?php echo $this->lang->line('add') ?></button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="dual-list list-right">
                                                <div class="well minheight260">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="input-group">
                                                                <input type="text" name="SearchDualList" class="form-control" placeholder="<?php echo $this->lang->line('search') ?>..." />
                                                                <div class="input-group-btn"><span class="btn btn-default input-group-addon bright"><i class="fa fa-search"></i></span></div>
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
                                                <div class="flex-direction-lg-column d-sm-flex d-lg-flex justify-content-center align-items-lg-center align-items-sm-start sm-full-width">
                                                    <label for="exam_to"><?php echo $this->lang->line('schedule_date_time'); ?></label><small class="req"> * </small>
                                                    <div class="input-group">
                                                        <input class="form-control tddm200 datetime " name="schedule_date_time" type="text" id="schedule_date_time" value="<?php echo $this->customlib->dateyyyymmddToDateTimeformat($messagelist['schedule_date_time'], false); ?>">
                                                        <span class="input-group-addon" id="basic-addon2"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary submit_individual ml-lg-1" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('sending'); ?>" ><i class="fa fa-envelope-o"></i> <?php echo $this->lang->line('submit'); ?></button>
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
        }

        console.log(obj);

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

            $(".send_list").append('<li style="height:auto" class="list-group-item" id="' + category_selected + '-' + record_id + '"><i class="fa fa-user"></i> ' + obj.user_name + ' ('+ category_selected +') <i class="fa fa-trash pull-right text-danger" onclick="delete_record(' + "'" + category_selected + '-' + record_id + "'" + ')"></i></li>');

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
                                    var email = obj.email;
                                    var contact = obj.mobileno;
                                    var name = obj.fullname +  "(" + obj.admission_no + ")";
                                } else if (category_selected == "student_guardian") {
                                    var email = obj.email;
                                    var guardian_email = obj.guardian_email;
                                    var contact = obj.mobileno;
                                    var name =  obj.fullname;
                                } else if (category_selected == "parent") {
                                    var email = obj.guardian_email;
                                    var contact = obj.guardian_phone;
                                    var name = obj.guardian_name;
                                } else if (category_selected == "staff") {
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

        $("#search-query").attr('value', val).val(val);
        $("#search-query").attr('data-record', record_id);
        $("#search-query").attr('data-email', email);
        if ($(this).data('guardianEmail') != undefined) {
            $("#search-query").attr('data-guardian-email', $(this).data('guardianEmail'));
        }
        $("#search-query").attr('data-mobileno', mobileno);
        $("#suggesstion-box").hide();
    });

    $(document).on('click', '.add-btn', function () {
        var guardianEmail = "";
        var value = $("#search-query").val();
        var record_id = $("#search-query").attr('data-record');
        var email = $("#search-query").attr('data-email');
        var mobileno = $("#search-query").attr('data-mobileno');
        if ($("#search-query").data('guardianEmail') != undefined) {
            var guardianEmail = $("#search-query").data('guardianEmail');
        }
        var category_selected = $("input[name='selected_value']").val();
        if (record_id != "" && category_selected != "") {
            var chkexists = checkRecordExists(category_selected + "-" + record_id);
            if (chkexists) {
                var arr = [];
                arr.push({
                    'category': category_selected,
                    'record_id': record_id,
                    'email': email,
                    'guardianEmail': guardianEmail,
                    'mobileno': mobileno
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
                $(".send_list").append('<li class="list-group-item" id="' + category_selected + '-' + record_id + '"><i class="fa fa-user"></i> ' + value + ' ('+ category_selected +') <i class="fa fa-trash pull-right text-danger" onclick="delete_record(' + "'" + category_selected + '-' + record_id + "'" + ')"></i></li>');

            } else {
                errorMsg('<?php echo $this->lang->line('record_already_exist') ?>');
            }
        } else {
            errorMsg("<?php echo $this->lang->line('message_to_field_is_required'); ?>");
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
        for (var instanceName in CKEDITOR.instances) {
            CKEDITOR.instances[instanceName].updateElement();
        }
        var formData = new FormData();
        var other_data = $(this).serializeArray();
        $.each(other_data, function (key, input) {
            formData.append(input.name, input.value);
        });
        //For image file
        var ins = document.getElementById('individual_group_attachment').files.length;
        for (var x = 0; x < ins; x++) {
            formData.append("files[]", document.getElementById('individual_group_attachment').files[x]);
        }

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
                    $('#individual_my_ttachment').html('');
                    $('#individual_group_file').html('');
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].setData(" ");
                    }
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

$('#individual_email_template').change(function(){
   var template_id =  $('#individual_email_template').val();
    $("#individual_group_file").html('');
   $.ajax({
    url : '<?php echo base_url(); ?>admin/mailsms/templatedata',
    type: 'post',
    data : {template_id:template_id},
    dataType: 'json',
    success:function(response){
        $('#individual_title').val(response.data.title);
        CKEDITOR.instances['individual_msg_text'].setData(response.data.message);
         $("#individual_group_file").html(response.attachment_list);
    }
   })
});

$(document).ready(function(){
   var message_id = '<?php echo $messagelist['id']; ?>';
   $("#individual_group_file").html('');
   $.ajax({
    url : '<?php echo base_url(); ?>admin/mailsms/schedule_templatedata',
    type: 'post',
    data : {message_id:message_id,type:'class'},
    dataType: 'json',
    success:function(response){
       CKEDITOR.instances['individual_msg_text'].setData(response.message);
        $("#individual_group_file").html(response.attachment_list);
    }
   })
})
</script>

<script>
function individual_removeAttachment(id){
    $('#individual_image_div_'+id).html('');
}

function individual_removemyAttachment(id){
        $('#individual_myattach_'+id).html('');
        individual_removeFileFromFileList(id);
}

function individual_removeFileFromFileList(index) {
  const dt = new DataTransfer()
  const input = document.getElementById('individual_group_attachment')
  const { files } = input

  for (let i = 0; i < files.length; i++) {
    const file = files[i]
    if (index !== i)
      dt.items.add(file) // here you exclude the file. thus removing it.
  }

  input.files = dt.files // Assign the updates list
  console.log(input.files);
}

function removeAttachment(id){
    $('#image_div_'+id).html('');
}

function removemyAttachment(id){
    $('#myattach_'+id).html('');
    removeFileFromFileList(id);
}

function removeFileFromFileList(index) {
  const dt = new DataTransfer()
  const input = document.getElementById('group_attachment')
  const { files } = input

  for (let i = 0; i < files.length; i++) {
    const file = files[i]
    if (index !== i)
      dt.items.add(file) // here you exclude the file. thus removing it.
  }
  input.files = dt.files // Assign the updates list
  console.log(input.files);
}

function individual_preview() {
    $("#individual_my_attachment").html('');
    var saida = document.getElementById("individual_group_attachment");
    console.log(event.target.files);
        var quantos = saida.files.length;
        for(i = 0; i < quantos; i++){
            var urls = URL.createObjectURL(event.target.files[i]);
             $.ajax({
    url : '<?php echo base_url(); ?>admin/mailsms/get_individual_preview',
    type: 'post',
    data : {img_name:event.target.files[i]['name'],dir_path:urls,file_type:event.target.files[i]['type'],delete_id:i},
    dataType: 'json',
    success:function(response){
        $('#individual_my_attachment').append(response);
    }
   })
        }
}
</script>

<script>
$(document).ready(function () {
    CKEDITOR.env.isCompatible = true;
    CKEDITOR.replaceClass = 'ckeditor';
});
</script>