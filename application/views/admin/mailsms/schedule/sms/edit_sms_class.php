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
                        <li class="pull-left header"> <?php echo $this->lang->line('send_sms_class'); ?></li>
                    </ul>

                    <form action="<?php echo site_url('admin/mailsms/update_class_sms_schedule') ?>" method="post" id="class_form">
                        <input type="hidden" name="message_id" value="<?php echo $messagelist['id']; ?>">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                 <label><?php echo $this->lang->line('sms_template'); ?></label>
                                                 <select name="template_id" id="class_template_id" class="form-control">
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
                                                <input class="form-control" id="class_title" name="class_title" value="<?php echo $messagelist['title']; ?>">
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
                                                        <input type="checkbox" value="<?php echo $key; ?>" name="class_send_by[]" <?php echo $selected_value; ?>> <?php echo $send_through_list_value; ?>
                                                    </label>
                                               <?php }
?>
                                                <span class="text-danger"><?php echo form_error('message'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('template_id'); ?></label> (<?php echo $this->lang->line('this_field_is_reqiured_only_for_indian_sms_gateway'); ?>)
                                                <input type="text" name="class_template_id" id="class_template_id" value="<?php echo $messagelist['template_id']; ?>" class="form-control" autocomplete="off">
                                            </div>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('message'); ?></label><small class="req"> *</small>
                                                <textarea id="class_msg_text" name="class_message" class="form-control compose-textarea" rows="12"><?php echo $messagelist['message']; ?></textarea>
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
    $selected_class = '';
    if ($messagelist['schedule_class'] == $class['id']) {
        $selected_class = 'selected';
    }
    ?>
                                                            <option value="<?php echo $class['id'] ?>"<?php echo $selected_class; ?>><?php echo $class['class'] ?></option>
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
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">

$(document).ready(function(){
    $('.section_list').html("");
    var class_id = '<?php echo $messagelist['schedule_class']; ?>';
    var selected_section = '<?php echo $selected_section; ?>';

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
                var selected_checked = '';
                $.each(JSON.parse(selected_section), function (index, value)
                {
                    if(obj.section_id == value){
                        selected_checked = 'checked';
                    }
                });

                div_data += '<li class="checkbox"><a href="#" class="small"><label><input type="checkbox" name="user[]" value ="' + obj.section_id +'"'+ selected_checked +'/>' + obj.section + '</label></a></li>';

            });
            $('.section_list').append(div_data);
        }
    });
})

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
                    $('.section_list').html("");
                    successMsg(data.msg);
                    window.location = "<?php echo base_url(); ?>admin/mailsms/schedule";
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

    $(document).on('keypress keyup keydown paste change focus blur', '.compose-textarea', function (event) {
        var total_length = checkTextAreaMaxLength(this, event);
        $(this).next('span.word_counter').html("<?php echo $this->lang->line('character_count') ?>: " + total_length)
    });

    function checkTextAreaMaxLength(textBox, e) {
        return textBox.value.length;
    }
    
    $(document).ready(function(){
        var total_text = $('#class_msg_text').val();
        $('span.word_counter').html("<?php echo $this->lang->line('character_count') ?>: " + total_text.length);
    })
</script>