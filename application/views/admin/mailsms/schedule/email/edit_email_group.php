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
                        <li class="pull-left header"> <?php echo $this->lang->line('send_email_group'); ?></li>
                    </ul>
                            <form action="<?php echo site_url('admin/mailsms/update_group_schedule') ?>" method="post" id="group_form">
                            <input type="hidden" name="message_id" value="<?php echo $messagelist['id']; ?>">
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                 <label><?php echo $this->lang->line('email_template'); ?></label>
                                                 <select name="template_id" id="template_id" class="form-control">
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
                                                <label><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                                <input autofocus="" class="form-control" name="group_title" id="group_title" value="<?php echo $messagelist['title']; ?>">
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
                                                    <?php echo $messagelist['message']; ?>
                                                </textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('message_to'); ?></label><small class="req"> *</small>
                                                <div class="well minheight303">
                                                <?php
$student_checked = '';
$parent_checked  = '';
if ($messagelist['group_list'] != '') {

    foreach ($group_list as $group_list_value) {
        if ($group_list_value == 'student') {
            $student_checked = 'checked';
        }

        if ($group_list_value == 'parent') {
            $parent_checked = 'checked';
        }
    }

}
?>
                                                    <div class="checkbox mt0">
                                                        <label><input type="checkbox" name="user[]" value="student" <?php echo $student_checked; ?>> <b><?php echo $this->lang->line('students'); ?></b> </label>
                                                    </div>
                                                    <?php
if ($sch_setting->guardian_name) {?>
                                                    <div class="checkbox">
                                                        <label><input type="checkbox" name="user[]" value="parent" <?php echo $parent_checked; ?>> <b><?php echo $this->lang->line('guardians'); ?></b></label>
                                                    </div>
                                                    <?php }
?>
                                                    <?php
foreach ($roles as $role_key => $role_value) {
    $role_ckecked = '';
    foreach ($group_list as $group_list_value) {

        if ($group_list_value == $role_value['id']) {
            $role_ckecked = 'checked';
        }
    }

    if ($role_value["name"] != 'Super Admin' || $superadmin_restriction != 'disabled') {
        ?>

                                                        <div class="checkbox">
                                                            <label><input type="checkbox" name="user[]" value="<?php echo $role_value['id']; ?>" <?php echo $role_ckecked; ?>> <b><?php echo $role_value['name']; ?></b></label>
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

<script type="text/javascript">

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
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].setData(" ");
                    }
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
</script>

<script>
$(document).ready(function(){
   var message_id = '<?php echo $messagelist['id']; ?>';
   $("#group_file").html('');
   $.ajax({
    url : '<?php echo base_url(); ?>admin/mailsms/schedule_templatedata',
    type: 'post',
    data : {message_id:message_id,type:'class'},
    dataType: 'json',
    success:function(response){
       CKEDITOR.instances['group_msg_text'].setData(response.message);
        $("#group_file").html(response.attachment_list);
    }
   })
})

$(document).ready(function () {
    $('#attachment_hide').show();

    CKEDITOR.env.isCompatible = true;
    CKEDITOR.replaceClass = 'ckeditor';
});
</script>