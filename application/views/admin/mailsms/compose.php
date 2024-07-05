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
                <!-- Custom Tabs (Pulled to the right) -->
                <div class="nav-tabs-custom theme-shadow">
                    <ul class="nav nav-tabs pull-right">
                        <li><a href="#tab_birthday" data-toggle="tab"><?php echo $this->lang->line('todays_birtday'); ?></a></li>
                        <li><a href="#tab_class" data-toggle="tab"><?php echo $this->lang->line('class'); ?></a></li>
                        <li><a href="#tab_perticular" data-toggle="tab"><?php echo $this->lang->line('individual'); ?></a></li>
                        <li class="active"><a href="#tab_group" data-toggle="tab"><?php echo $this->lang->line('group'); ?></a></li>
                        <li class="pull-left header"> <?php echo $this->lang->line('send_email'); ?></li>
                    </ul>
                    <div class="tab-content pb0">
                        <div class="tab-pane active" id="tab_group">
                            <form action="<?php echo site_url('admin/mailsms/send_group') ?>" method="post" id="group_form">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                 <label><?php echo $this->lang->line('email_template'); ?></label>
                                                 <select name="template_id" id="template_id" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                     <?php foreach ($email_template_list as $email_template_list_value) {?>
                                                        <option value="<?php echo $email_template_list_value['id']; ?>"><?php echo $email_template_list_value['title']; ?></option>
                                                     <?php }?>
                                                 </select>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                                <input autofocus="" class="form-control" name="group_title" id="group_title">
                                            </div>
                                            <input type="hidden" name="group_send_by" value="email">
                                            <div id="group_file" class="row"></div>
                                            <div id="my_attachment" class="row"></div>
                                            <div class="form-group">
                                                <label class="pr20"><?php echo $this->lang->line('attachment'); ?></label>
                                                <input onchange="preview()" type="file" id="group_attachment" class="filestyle form-control" name="group_attachment[]" multiple>
                                                <span class="text-danger"><?php echo form_error('message'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('message'); ?></label><small class="req"> *</small>
                                                <textarea id="group_msg_text" name="group_message" class="form-control compose-textarea ckeditor" cols="35" rows="20">
                                                    <?php echo set_value('message'); ?>
                                                </textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('message_to'); ?></label><small class="req"> *</small>
                                                <div class="well minheight303">
                                                    <div class="checkbox mt0">
                                                        <label><input type="checkbox" name="user[]" value="student"> <b><?php echo $this->lang->line('students'); ?></b> </label>
                                                    </div>
                                                    <?php
if ($sch_setting->guardian_name) {?>
                                                    <div class="checkbox">
                                                        <label><input type="checkbox" name="user[]" value="parent"> <b><?php echo $this->lang->line('guardians'); ?></b></label>
                                                    </div>
                                                    <?php }
?>

                                                    <?php
foreach ($roles as $role_key => $role_value) {
    if ($role_value["name"] != 'Super Admin' || $superadmin_restriction != 'disabled') {?>

                                                        <div class="checkbox">
                                                            <label><input type="checkbox" name="user[]" value="<?php echo $role_value['id']; ?>"> <b><?php echo $role_value['name']; ?></b></label>
                                                        </div>

                                                        <?php
}
}
?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="pull-lg-right pull-sm-left">
                                                <label class="radio-inline">
                                                    <input type="radio"  name="send_type" value="send_now" checked=""> <?php echo $this->lang->line('send_now'); ?></label>
                                                    <label class="radio-inline pr-lg-1">
                                                    <input type="radio"  name="send_type" value="schedule"> <?php echo $this->lang->line('schedule'); ?></label>
                                                    <div id="schedule_div" class="d-lg-flex mt-sm-1 justify-content-center align-items-lg-center align-items-sm-start flex-direction-column"></div>
                                                <button type="submit" class="btn btn-primary submit_group ml-lg-1 mb-sm-1" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('sending'); ?>" ><i class="fa fa-envelope-o"></i> <?php echo $this->lang->line('submit'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-footer -->
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_perticular">
                            <form action="<?php echo site_url('admin/mailsms/send_individual') ?>" method="post" id="individual_form">
                                <!-- /.box-header -->
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                 <label><?php echo $this->lang->line('email_template'); ?></label>
                                                 <select name="template_id" id="individual_email_template" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                     <?php foreach ($email_template_list as $email_template_list_value) {?>
                                                        <option value="<?php echo $email_template_list_value['id']; ?>"><?php echo $email_template_list_value['title']; ?></option>
                                                     <?php }?>
                                                 </select>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('title'); ?></label>
                                                <small class="req"> *</small>
                                                <input class="form-control" name="individual_title" id="individual_title">
                                            </div>
                                            <input type="hidden" name="individual_send_by" value="email">
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
                                                    <?php echo set_value('message'); ?>
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
                                                <label class="radio-inline">
                                                    <input type="radio"  name="individual_send_type" value="send_now" checked=""> <?php echo $this->lang->line('send_now'); ?></label>
                                                    <label class="radio-inline pr-lg-1">
                                                    <input type="radio"  name="individual_send_type" value="schedule"> <?php echo $this->lang->line('schedule'); ?></label>
                                                    <div id="individual_schedule_div" class="flex-direction-lg-column d-sm-flex d-lg-flex justify-content-center align-items-lg-center align-items-sm-start sm-full-width"></div>
                                                <button type="submit" class="btn btn-primary submit_individual ml-lg-1" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('sending'); ?>" ><i class="fa fa-envelope-o"></i> <?php echo $this->lang->line('submit'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-footer -->
                            </form>
                        </div>
                        <div class="tab-pane" id="tab_class">
                            <form action="<?php echo site_url('admin/mailsms/send_class') ?>" method="post" id="class_form">
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                 <label><?php echo $this->lang->line('email_template'); ?></label>
                                                 <select name="template_id" id="class_email_template" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                     <?php foreach ($email_template_list as $email_template_list_value) {?>
                                                        <option value="<?php echo $email_template_list_value['id']; ?>"><?php echo $email_template_list_value['title']; ?></option>
                                                     <?php }?>
                                                 </select>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('title'); ?></label>
                                                <small class="req"> *</small>
                                                <input class="form-control" name="class_title" id="class_title">
                                            </div>
                                            <input type="hidden" name="class_send_by" value="email">
                                            <div id="class_group_file" class="row"></div>
                                            <div id="class_my_attachment" class="row"></div>
                                            <div class="form-group">
                                                <label class="pr20"><?php echo $this->lang->line('attachment'); ?></label>
                                                <input onchange="class_preview()"  type="file" id="class_group_attachment" class="filestyle form-control" name="class_group_attachment[]" multiple="multiple">
                                                <span class="text-danger"><?php echo form_error('message'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('message'); ?></label><small class="req"> *</small>
                                                <textarea id="class_msg_text" name="class_message" class="form-control compose-textarea ckeditor">
                                                    <?php echo set_value('message'); ?>
                                                </textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="form-group col-xs-10 col-sm-12 col-md-12 col-lg-12">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('message_to'); ?></label><small class="req"> *</small>
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
}
?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="dual-list list-right">
                                                <div class="well minheight260">
                                                    <div class="wellscroll">
                                                        <b><?php echo $this->lang->line('section'); ?></b>
                                                        <ul class="list-group section_list listcheckbox">

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
                                                <label class="radio-inline">
                                                    <input type="radio"  name="class_send_type" value="send_now" checked=""> <?php echo $this->lang->line('send_now'); ?></label>
                                                    <label class="radio-inline pr-lg-1">
                                                    <input type="radio"  name="class_send_type" value="schedule"> <?php echo $this->lang->line('schedule'); ?></label>
                                                    <div id="class_schedule_div" class="flex-direction-column d-sm-flex d-lg-flex justify-content-center align-items-lg-center align-items-sm-start sm-full-width"></div>
                                                <button type="submit" class="btn btn-primary submit_class ml-lg-1" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('sending'); ?>" ><i class="fa fa-envelope-o"></i> <?php echo $this->lang->line('submit'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="tab_birthday">
                            <form action="<?php echo site_url('admin/mailsms/send_birthday') ?>" method="post" id="birthday_form">
                                <!-- /.box-header -->
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                 <label><?php echo $this->lang->line('email_template'); ?></label>
                                                 <select name="template_id" id="birthday_email_template" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                     <?php foreach ($email_template_list as $email_template_list_value) {?>
                                                        <option value="<?php echo $email_template_list_value['id']; ?>"><?php echo $email_template_list_value['title']; ?></option>
                                                     <?php }?>
                                                 </select>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                                <input autofocus="" class="form-control" name="birthday_title" id="birthday_title">
                                            </div>
                                            <input type="hidden" name="birthday_send_by" value="email">
                                            <div id="birthday_group_file" class="row"></div>
                                            <div id="birthday_my_attachment" class="row"></div>

                                            <div class="form-group">
                                                <label class="pr20"><?php echo $this->lang->line('attachment'); ?></label>
                                                <input onchange="birthday_preview()"  type="file" id="birthday_group_attachment" class="filestyle form-control" name="birthday_group_attachment[]" multiple="multiple">
                                                <span class="text-danger"><?php echo form_error('message'); ?></span>
                                            </div>

                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('message'); ?></label><small class="req"> *</small>
                                                <textarea id="birthday_msg_text" name="birthday_message" class="form-control compose-textarea ckeditor" cols="35" rows="20">
                                                    <?php echo set_value('message'); ?>
                                                </textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('message_to'); ?></label><small class="req"> *</small>
                                                <div class="well minheight303">
                                                    <?php
if (!empty($birthDaysList)) {

    if (isset($birthDaysList['students'])) {
        ?>
                                                            <h4><?php echo $this->lang->line('students'); ?></h4>
                                                            <div class="wellscroll">
                                                                <?php  
foreach ($birthDaysList['students'] as $student_key => $student_value) {
            ?>
                                                                    <div class="checkbox">
                                                                        <label><input type="checkbox" name="user[]" value="<?php echo $student_value['email'] ?>" checked> <b><?php echo $student_value['name']; ?> (<?php echo $student_value['admission_no']; ?>) </b></label>
                                                                    </div>
                                                                    <?php
}
        ?>
                                                            </div>
                                                            <?php
}

    if (isset($birthDaysList['staff'])) {
        ?>

                                                            <h4><?php echo $this->lang->line('staff'); ?> </h4>
                                                            <div class="wellscroll">
                                                                <?php
foreach ($birthDaysList['staff'] as $staff_key => $staff_value) {
            ?>
                                                                    <div class="checkbox">
                                                                        <label><input type="checkbox" name="user[]" value="<?php echo $staff_value['email'] ?>" checked> <b><?php echo $staff_value['name']; ?> (<?php echo $staff_value['employee_id']; ?>)</b></label>
                                                                    </div>
                                                                    <?php
}
        ?>
                                                            </div><?php
}
}
?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer row">
                                    <div class="pull-right">
                                        <button type="submit" class="btn btn-primary submit_birthday" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('sending'); ?>" ><i class="fa fa-envelope-o"></i> <?php echo $this->lang->line('send'); ?></button>
                                    </div>
                                </div>
                                <!-- /.box-footer -->
                            </form>
                        </div>
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
                    $("#search-query").css("background", "#FFF url(../../backend/images/loading.gif) no-repeat 165px");
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
                    category_selected_lang = '<?php echo $this->lang->line('student'); ?>';
                }

                if(category_selected == 'parent'){
                    category_selected_lang = '<?php echo $this->lang->line('parent'); ?>';
                }

                if(category_selected == 'staff'){
                    category_selected_lang = '<?php echo $this->lang->line('staff'); ?>';
                }

                if(category_selected == 'student_guardian'){
                    category_selected_lang = '<?php echo $this->lang->line('student_guardian'); ?>';
                }

                $("#search-query").attr('value', "").val("");
                $("#search-query").attr('data-record', "");

                $(".send_list").append('<li class="list-group-item" id="' + category_selected + '-' + record_id + '"><i class="fa fa-user"></i> ' + value + ' ('+ category_selected_lang +') <i class="fa fa-trash pull-right text-danger" onclick="delete_record(' + "'" + category_selected + '-' + record_id + "'" + ')"></i></li>');
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
        console.log(record);
        console.log(record);

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
                    $('#individual_my_attachment').html('');
                    $('#individual_group_file').html('');
                    $(".filestyle").next(".dropify-clear").trigger("click");
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].setData(" ");
                    }
                    $("ul.send_list").empty();
                    attr = {};
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }, complete: function (data) {
                $this.button('reset');
            }
        })
    });

    $("#group_form").submit(function (event) {

        event.preventDefault();
        for (var instanceName in CKEDITOR.instances) {
            CKEDITOR.instances[instanceName].updateElement();
        }
        var formData = new FormData();
        var other_data = $(this).serializeArray();
        $.each(other_data, function (key, input) {
            formData.append(input.name, input.value);
        });

//===========

        var ins = document.getElementById('group_attachment').files.length;
        for (var x = 0; x < ins; x++) {
            formData.append("files[]", document.getElementById('group_attachment').files[x]);
        }
//==========

        var $form = $(this),
        url = $form.attr('action');
        var $this = $('.submit_group');
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
                    $('#group_form')[0].reset();
                    $('#my_attachment').html('');
                    $('#group_file').html('');
                    $(".filestyle").next(".dropify-clear").trigger("click");
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].setData(" ");
                    }
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }, complete: function (data) {
                $this.button('reset');
            }
        })
    });

    $("#birthday_form").submit(function (event) {
        event.preventDefault();
        for (var instanceName in CKEDITOR.instances) {
            CKEDITOR.instances[instanceName].updateElement();
        }
        var formData = new FormData();
        var other_data = $(this).serializeArray();
        $.each(other_data, function (key, input) {
            formData.append(input.name, input.value);
        });

//===========

        var ins = document.getElementById('birthday_group_attachment').files.length;
        for (var x = 0; x < ins; x++) {
            formData.append("files[]", document.getElementById('birthday_group_attachment').files[x]);
        }
//==========

        var $form = $(this),
            url = $form.attr('action');
        var $this = $('.submit_birthday');
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
                    $('#birthday_form')[0].reset();
                    $('#birthday_my_attachment').html('');
                    $('#birthday_group_file').html('');
                    $(".filestyle").next(".dropify-clear").trigger("click");

                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].setData(" ");
                    }
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }, complete: function (data) {
                $this.button('reset');
            }
        })
    });

    $(document).on('change', '#class_id', function (e) {
        $('.section_list').html("");
        var class_id = $(this).val();
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
                $('.section_list').append(div_data);
            }
        });
    });

    $("#class_form").submit(function (event) {
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
        var ins = document.getElementById('class_group_attachment').files.length;

        for (var x = 0; x < ins; x++) {
            formData.append("files[]", document.getElementById('class_group_attachment').files[x]);
        }

        var $form = $(this),
        url = $form.attr('action');
        var $this = $('.submit_class');
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
                    $('#class_form')[0].reset();
                    $('#class_my_attachment').html('');
                    $('#class_group_file').html('');
                    $(".filestyle").next(".dropify-clear").trigger("click");
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].setData(" ");
                    }
                    $('.section_list').html("");
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }, complete: function (data) {
                $this.button('reset');
            }
        });
    });

$('#template_id').change(function(){
   var template_id =  $('#template_id').val();
    $("#group_file").html('');
   $.ajax({
    url : '<?php echo base_url(); ?>admin/mailsms/templatedata',
    type: 'post',
    data : {template_id:template_id},
    dataType: 'json',
    success:function(response){
        $('#group_title').val(response.data.title);
        CKEDITOR.instances['group_msg_text'].setData(response.data.message);
        $("#group_file").html(response.attachment_list);

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

function preview() {

    $("#my_attachment").html('');
    var saida = document.getElementById("group_attachment");
    console.log(event.target.files);
        var quantos = saida.files.length;
        for(i = 0; i < quantos; i++){
            var urls = URL.createObjectURL(event.target.files[i]);

             $.ajax({
    url : '<?php echo base_url(); ?>admin/mailsms/get_preview',
    type: 'post',
    data : {img_name:event.target.files[i]['name'],dir_path:urls,file_type:event.target.files[i]['type'],delete_id:i},
    dataType: 'json',
    success:function(response){
        $('#my_attachment').append(response);
    }
   })
        }
}

$(function() {
    $('input:radio[name="send_type"]').change(function() {
        if ($(this).val() == 'schedule') {
            $('#schedule_div').html('<label for="exam_to"><?php echo $this->lang->line('schedule_date_time'); ?><small class="req"> * </small></label> <div class="input-group datewidth-schedule"><input class="form-control datetime" name="schedule_date_time" type="text" id="schedule_date_time" ><span class="input-group-addon" id="basic-addon2"><i class="fa fa-calendar"></i></span></div>');
        } else {
            $('#schedule_div').html('');
        }
    });
});
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

$(function() {
    $('input:radio[name="individual_send_type"]').change(function() {
        if ($(this).val() == 'schedule') {
            $('#individual_schedule_div').html('<label for="exam_to"><?php echo $this->lang->line('schedule_date_time'); ?></label><small class="req"> *</small><div class="input-group"><input class="form-control tddm200 datetime" name="schedule_date_time" type="text" id="schedule_date_time" ><span class="input-group-addon" id="basic-addon2"><i class="fa fa-calendar"></i></span></div>');
        } else {
            $('#individual_schedule_div').html('');
        }
    });
});
</script>

<script>
$('#birthday_email_template').change(function(){
   var template_id =  $('#birthday_email_template').val();
   $("#birthday_group_file").html('');
   $.ajax({
    url : '<?php echo base_url(); ?>admin/mailsms/templatedata',
    type: 'post',
    data : {template_id:template_id,type:'class'},
    dataType: 'json',
    success:function(response){
        $('#birthday_title').val(response.data.title);
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData(response.data.message);
        }
        $("#birthday_group_file").html(response.attachment_list);
    }
   })
});

function birthday_removeAttachment(id){
    $('#birthday_image_div_'+id).html('');
}

function birthday_removemyAttachment(id){
    $('#birthday_myattach_'+id).html('');
    birthday_removeFileFromFileList(id);
}

function birthday_removeFileFromFileList(index) {
  const dt = new DataTransfer()
  const input = document.getElementById('birthday_group_attachment')
  const { files } = input

  for (let i = 0; i < files.length; i++) {
    const file = files[i]
    if (index !== i)
      dt.items.add(file) // here you exclude the file. thus removing it.
  }
  input.files = dt.files // Assign the updates list
  console.log(input.files);
}

function birthday_preview() {
    $("#birthday_my_attachment").html('');
    var saida = document.getElementById("birthday_group_attachment");
    console.log(event.target.files);
        var quantos = saida.files.length;
        for(i = 0; i < quantos; i++){
            var urls = URL.createObjectURL(event.target.files[i]);
             $.ajax({
    url : '<?php echo base_url(); ?>admin/mailsms/get_birthday_preview',
    type: 'post',
    data : {img_name:event.target.files[i]['name'],dir_path:urls,file_type:event.target.files[i]['type'],delete_id:i},
    dataType: 'json',
    success:function(response){
        $('#birthday_my_attachment').append(response);
    }
   })
        }
}

$(function() {
    $('input:radio[name="birthday_send_type"]').change(function() {
        if ($(this).val() == 'schedule') {
            $('#birthday_schedule_div').html('<label for="exam_to"><?php echo $this->lang->line('schedule_date_time'); ?></label><small class="req"> *</small><div class="input-group"><input class="form-control tddm200 datetime" name="schedule_date_time" type="text" id="schedule_date_time" ><span class="input-group-addon" id="basic-addon2"><i class="fa fa-calendar"></i></span></div>');
        } else {
            $('#birthday_schedule_div').html('');
        }
    });
});
</script>

<script>
$('#class_email_template').change(function(){
   var template_id =  $('#class_email_template').val();
   $("#class_group_file").html('');
   $.ajax({
    url : '<?php echo base_url(); ?>admin/mailsms/templatedata',
    type: 'post',
    data : {template_id:template_id,type:'class'},
    dataType: 'json',
    success:function(response){
        console.log(response);
        $('#class_title').val(response.data.title);
       CKEDITOR.instances['class_msg_text'].setData(response.data.message);
        $("#class_group_file").html(response.attachment_list);
    }
   })
});

function class_removeAttachment(id){
    $('#class_image_div_'+id).html('');
}

function class_removemyAttachment(id){
    $('#class_myattach_'+id).html('');
    class_removeFileFromFileList(id);
}

function class_removeFileFromFileList(index) {
  const dt = new DataTransfer()
  const input = document.getElementById('class_group_attachment')
  const { files } = input

  for (let i = 0; i < files.length; i++) {
    const file = files[i]
    if (index !== i)
      dt.items.add(file) // here you exclude the file. thus removing it.
  }

  input.files = dt.files // Assign the updates list
  console.log(input.files); 
}

function class_preview() {
    $("#class_my_attachment").html('');
    var saida = document.getElementById("class_group_attachment");
    console.log(event.target.files);
        var quantos = saida.files.length;
        for(i = 0; i < quantos; i++){
            var urls = URL.createObjectURL(event.target.files[i]);
             $.ajax({
    url : '<?php echo base_url(); ?>admin/mailsms/get_class_preview',
    type: 'post',
    data : {img_name:event.target.files[i]['name'],dir_path:urls,file_type:event.target.files[i]['type'],delete_id:i},
    dataType: 'json',
    success:function(response){
        $('#class_my_attachment').append(response);
    }
   })
        }
}

$(function() {
    $('input:radio[name="class_send_type"]').change(function() {
        if ($(this).val() == 'schedule') {
            $('#class_schedule_div').html('<label for="exam_to"><?php echo $this->lang->line('schedule_date_time'); ?></label><small class="req"> *</small><div class="input-group"><input class="form-control tddm200 datetime" name="schedule_date_time" type="text" id="schedule_date_time" ><span class="input-group-addon" id="basic-addon2"><i class="fa fa-calendar"></i></span></div>');
        } else {
            $('#class_schedule_div').html('');
        }
    });
});
</script>

<script>
$(document).ready(function () {
      CKEDITOR.env.isCompatible = true;
        CKEDITOR.replaceClass = 'ckeditor';
    });
</script>