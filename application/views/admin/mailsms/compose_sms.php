<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Custom Tabs (Pulled to the right) -->
                <div class="nav-tabs-custom theme-shadow">
                    <ul class="nav nav-tabs pull-right">
                        <li class="pull-left header w-xs-100"><?php echo $this->lang->line('send_sms'); ?></li>
                        <li><a href="#tab_birthday" data-toggle="tab"><?php echo $this->lang->line('todays_birtday'); ?></a></li>
                        <li><a href="#tab_class" data-toggle="tab"><?php echo $this->lang->line('class'); ?></a></li>
                        <li><a href="#tab_perticular" data-toggle="tab"><?php echo $this->lang->line('individual'); ?></a></li>
                        <li class="active"><a href="#tab_group" data-toggle="tab"><?php echo $this->lang->line('group'); ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_group">
                            <form action="<?php echo site_url('admin/mailsms/send_group_sms') ?>" method="post" id="group_form">
                                <!-- /.box-header -->
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                 <label><?php echo $this->lang->line('sms_template'); ?></label>
                                                 <select name="template_id" id="template_id" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                     <?php foreach ($sms_template_list as $sms_template_list_value) {?>
                                                        <option value="<?php echo $sms_template_list_value['id']; ?>"><?php echo $sms_template_list_value['title']; ?></option>
                                                     <?php }?>
                                                 </select>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('title'); ?></label> <small class="req">*</small>
                                                <input autofocus="" class="form-control" name="group_title" id="group_title" >
                                            </div>
                                            <div class="form-group">
                                                <label class="pr20"><?php echo $this->lang->line('send_through'); ?><small class="req"> *</small></label>

                                                <?php
foreach ($send_through_list as $key => $send_through_list_value) {
    ?>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" value="<?php echo $key; ?>" name="group_send_by[]"> <?php echo $send_through_list_value; ?>
                                                    </label>
                                               <?php }
?>
                                                <span class="text-danger"><?php echo form_error('message'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('template_id'); ?>  </label> (<?php echo $this->lang->line('this_field_is_reqiured_only_for_indian_sms_gateway'); ?>)
                                                <input type="text" name="group_template_id" id="group_template_id" class="form-control" autocomplete="off">
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('message'); ?></label><small class="req"> *</small>
                                                <textarea id="group_msg_text" name="group_message" class="form-control compose-textarea" rows="12"><?php echo set_value('message'); ?></textarea>
                                                <span class="text-muted tot_count_group_msg_text pull-right word_counter" id="group_word_counter"><?php echo $this->lang->line('character_count'); ?>: 0</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('message_to'); ?></label><small class="req"> *</small>
                                                <div class="well minheight303">
                                                    <div class="checkbox mt0">
                                                        <label><input type="checkbox" name="user[]" value="student"> <b><?php echo $this->lang->line('students'); ?></b> </label>
                                                    </div>
                                                    <?php if ($sch_setting->guardian_name) {
    ?>
                                                         <div class="checkbox">
                                                        <label><input type="checkbox" name="user[]" value="parent"> <b><?php echo $this->lang->line('guardians'); ?></b></label>
                                                    </div>
                                                        <?php
}?>
                                                    <?php
foreach ($roles as $role_key => $role_value) {

    if ($role_value["name"] != 'Super Admin' || $superadmin_restriction != 'disabled') {?>
                                                        <div class="checkbox">
                                                            <label><input type="checkbox" name="user[]" value="<?php echo $role_value['id']; ?>"> <b><?php echo $role_value['name']; ?></b></label>
                                                        </div>
                                                        <?php
}}
?>
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
                                                    <input type="radio"  name="send_type" value="send_now" checked=""> <?php echo $this->lang->line('send_now'); ?></label>
                                                    <label class="radio-inline pr-lg-1">
                                                    <input type="radio"  name="send_type" value="schedule"> <?php echo $this->lang->line('schedule'); ?></label>
                                                    <div id="schedule_div" class="flex-direction-column d-sm-flex d-lg-flex justify-content-center align-items-lg-center align-items-sm-start sm-full-width"></div>
                                                <button type="submit" class="btn btn-primary submit_group ml-lg-1" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('sending'); ?>" ><i class="fa fa-envelope-o"></i> <?php echo $this->lang->line('submit'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-footer -->
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_perticular">
                            <form action="<?php echo site_url('admin/mailsms/send_individual_sms') ?>" method="post" id="individual_form">
                                <!-- /.box-header -->
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                 <label><?php echo $this->lang->line('sms_template'); ?></label>
                                                 <select name="template_id" id="individual_template_id" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                     <?php foreach ($sms_template_list as $sms_template_list_value) {?>
                                                        <option value="<?php echo $sms_template_list_value['id']; ?>"><?php echo $sms_template_list_value['title']; ?></option>
                                                     <?php }?>
                                                 </select>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('title'); ?></label>
                                                <small class="req"> *</small>
                                                <input class="form-control" id="individual_title" name="individual_title">
                                            </div>
                                            <div class="form-group">
                                                <label class="pr20"><?php echo $this->lang->line('send_through'); ?><small class="req"> *</small></label>

                                                <?php
foreach ($send_through_list as $key => $send_through_list_value) {
    ?>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" value="<?php echo $key; ?>" name="individual_send_by[]"> <?php echo $send_through_list_value; ?>
                                                    </label>

                                               <?php }
?>
                                                <span class="text-danger"><?php echo form_error('message'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('template_id'); ?> </label> (<?php echo $this->lang->line('this_field_is_reqiured_only_for_indian_sms_gateway'); ?>)
                                                <input type="text" name="individual_template_id" id="individual_template_id" class="form-control" autocomplete="off">
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('message'); ?></label><small class="req"> *</small>
                                                <textarea id="individual_msg_text" name="individual_message" class="form-control compose-textarea" rows="12"><?php echo set_value('message'); ?></textarea>
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
                            <form action="<?php echo site_url('admin/mailsms/send_class_sms') ?>" method="post" id="class_form">
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                 <label><?php echo $this->lang->line('sms_template'); ?></label>
                                                 <select name="template_id" id="class_template_id" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                     <?php foreach ($sms_template_list as $sms_template_list_value) {?>
                                                        <option value="<?php echo $sms_template_list_value['id']; ?>"><?php echo $sms_template_list_value['title']; ?></option>
                                                     <?php }?>
                                                 </select>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('title'); ?></label>
                                                <small class="req"> *</small>
                                                <input class="form-control" id="class_title" name="class_title">
                                            </div>
                                            <div class="form-group">
                                                <label class="pr20"><?php echo $this->lang->line('send_through'); ?><small class="req"> *</small></label>
                                                <?php
foreach ($send_through_list as $key => $send_through_list_value) {
    ?>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" value="<?php echo $key; ?>" name="class_send_by[]"> <?php echo $send_through_list_value; ?>
                                                    </label>

                                               <?php }
?>                                                

                                                <span class="text-danger"><?php echo form_error('message'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('template_id'); ?></label> (<?php echo $this->lang->line('this_field_is_reqiured_only_for_indian_sms_gateway'); ?>)
                                                <input type="text" name="class_template_id" id="class_template_id" class="form-control" autocomplete="off">
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('message'); ?></label><small class="req"> *</small>
                                                <textarea id="class_msg_text" name="class_message" class="form-control compose-textarea" rows="12"><?php echo set_value('message'); ?></textarea>
                                                <span class="text-muted tot_count_class_msg_text pull-right word_counter" id="class_word_counter"><?php echo $this->lang->line('character_count'); ?>: 0</span>
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
                            <form action="<?php echo site_url('admin/mailsms/send_birthday_sms') ?>" method="post" id="birthday_form">
                                <!-- /.box-header -->
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                 <label><?php echo $this->lang->line('sms_template'); ?></label>
                                                 <select name="template_id" id="birthday_template_id" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                     <?php foreach ($sms_template_list as $sms_template_list_value) {?>
                                                        <option value="<?php echo $sms_template_list_value['id']; ?>"><?php echo $sms_template_list_value['title']; ?></option>
                                                     <?php }?>
                                                 </select>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                                <input autofocus="" class="form-control" id="birthday_title" name="birthday_title">
                                            </div>
                                            <div class="form-group">
                                                <label class="pr20"><?php echo $this->lang->line('send_through'); ?><small class="req"> *</small></label>

                                                <?php
foreach ($send_through_list as $key => $send_through_list_value) {
    ?>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" value="<?php echo $key; ?>" name="birthday_send_by[]"> <?php echo $send_through_list_value; ?>
                                                    </label>

                                               <?php }
?>

                                                <span class="text-danger"><?php echo form_error('message'); ?></span>
                                            </div>
                                             <div class="form-group">
                                                <label><?php echo $this->lang->line('template_id'); ?></label> (<?php echo $this->lang->line('this_field_is_reqiured_only_for_indian_sms_gateway'); ?>)
                                                <input type="text" name="birthday_template_id" id="birthday_template_id" class="form-control" autocomplete="off">
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('message'); ?></label><small class="req"> *</small>
                                                <textarea id="birthday_msg_text" name="birthday_message" class="form-control compose-textarea ckeditor" cols="35" rows="20"><?php echo set_value('message'); ?></textarea>
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
                                                            <h4 class="mt0"><?php echo $this->lang->line('students'); ?></h4>
                                                            <hr class="mb0 mt10">
                                                            <div class="wellscroll">
                                                                <?php
foreach ($birthDaysList['students'] as $student_key => $student_value) {
            ?>
                                                                    <div class="checkbox">
                                                                        <label><input type="checkbox" name="user[]" app-key="<?php echo $student_value['app_key']; ?>" value="<?php echo $student_value['contact_no'] ?>" checked> <b><?php echo $student_value['name']; ?> (<?php echo $student_value['admission_no']; ?>)</b></label>
                                                                    </div>
                                                                    <?php
}
        ?>
                                                            </div>
                                                            <?php
}

    if (isset($birthDaysList['staff'])) {
        ?>
                                                            <h4><?php echo $this->lang->line('staff'); ?></h4>
                                                            <hr>
                                                            <div class="wellscroll">
                                                                <?php
foreach ($birthDaysList['staff'] as $staff_key => $staff_value) {
            ?>
                                                                    <div class="checkbox">
                                                                        <label><input type="checkbox" name="user[]" app-key="" value="<?php echo $staff_value['contact_no'] ?>" checked> <b><?php echo $staff_value['name']; ?> (<?php echo $staff_value['employee_id']; ?>)</b></label>
                                                                    </div>
                                                                    <?php
}
        ?>
                                                            </div>
                                                            <?php
}
}
?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
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
                                    console.log(obj);
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
                                    var name = obj.fullname;
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

                    if(category_selected == 'student_guardian'){
                        category_selected = '<?php echo $this->lang->line('student_guardian'); ?>';
                    }

                    if(category_selected == 'student'){
                        category_selected = '<?php echo $this->lang->line('student'); ?>';
                    }

                    if(category_selected == 'parent'){
                        category_selected = '<?php echo $this->lang->line('parent'); ?>';
                    }

                    if(category_selected == 'staff'){
                        category_selected = '<?php echo $this->lang->line('staff'); ?>';
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
                    $('#individual_word_counter').html('<?php echo $this->lang->line('character_count'); ?>: 0');
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

    $("#birthday_form").submit(function (event) {
        var user_list = [];
        $.each($("input[name='user[]']:checked"), function () {
            user_list.push($(this).attr("app-key"));
        });

        event.preventDefault();

        var formData = new FormData();
        var other_data = $(this).serializeArray();
        $.each(other_data, function (key, input) {
            if (input.value != "") {
                formData.append(input.name, input.value);
            } else {
                formData.append(input.name, 0);
            }
        });
        $.each(user_list, function (index, value) {
            formData.append('app-key[]', value);
        });

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
        var formData = new FormData();
        var other_data = $(this).serializeArray();
        $.each(other_data, function (key, input) {
            formData.append(input.name, input.value);
        });

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
                    $('#group_form')[0].reset();
                    $('#group_word_counter').html('<?php echo $this->lang->line('character_count'); ?>: 0');

                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

            },
            complete: function (data) {
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

        var formData = new FormData();
        var other_data = $(this).serializeArray();
        $.each(other_data, function (key, input) {
            formData.append(input.name, input.value);
        });

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
                    $('#class_form')[0].reset();
                    $('#class_word_counter').html('<?php echo $this->lang->line('character_count'); ?>: 0');
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
                $('#group_title').val(response.data.title);
                $('#group_msg_text').val(response.data.message);
                $('#group_word_counter').html("<?php echo $this->lang->line('character_count') ?>: " + (response.data.message.length));
            }
        })
    });

    $(function() {
        $('input:radio[name="send_type"]').change(function() {
        if ($(this).val() == 'schedule') {
            $('#schedule_div').html('<label for="exam_to"><?php echo $this->lang->line('schedule_date_time'); ?></label><small class="req"> *</small><div class="input-group"><input class="form-control tddm200 datetime" name="schedule_date_time" type="text" id="schedule_date_time" ><span class="input-group-addon" id="basic-addon2"><i class="fa fa-calendar"></i></span></div>');
        } else {
            $('#schedule_div').html('');
        }
    });
});
</script>

<script type="text/javascript">
    $('#individual_template_id').change(function(){
    var template_id =  $('#individual_template_id').val();

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

<script type="text/javascript">
    $('#class_template_id').change(function(){
    var template_id =  $('#class_template_id').val();

    $.ajax({
        url : '<?php echo base_url(); ?>admin/mailsms/smstemplatedata',
        type: 'post',
        data : {template_id:template_id},
        dataType: 'json',
        success:function(response){
            $('#class_title').val(response.data.title);
            $('#class_msg_text').val(response.data.message);
            $('#class_word_counter').html("<?php echo $this->lang->line('character_count') ?>: " + (response.data.message.length));
        }
    })
    });

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

<script type="text/javascript">
    $('#birthday_template_id').change(function(){
   var template_id =  $('#birthday_template_id').val();

   $.ajax({
    url : '<?php echo base_url(); ?>admin/mailsms/smstemplatedata',
    type: 'post',
    data : {template_id:template_id},
    dataType: 'json',
    success:function(response){
        $('#birthday_title').val(response.data.title);
        $('#birthday_msg_text').val(response.data.message);

    }
   })
});
</script>