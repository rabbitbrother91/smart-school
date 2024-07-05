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
                        <li class="pull-left header"> <?php echo $this->lang->line('send_email_class'); ?></li>
                    </ul>

                        <form action="<?php echo site_url('admin/mailsms/update_class_schedule') ?>" method="post" id="class_form">
                            <input type="hidden" name="message_id" value="<?php echo $messagelist['id']; ?>">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                 <label><?php echo $this->lang->line('email_template'); ?></label>
                                                 <select name="template_id" id="class_email_template" class="form-control">
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
                                                <input class="form-control" name="class_title" id="class_title" value="<?php echo $messagelist['title']; ?>">
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
                                                    <?php echo $messagelist['message']; ?>
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
    $selected_class = '';
    if ($messagelist['schedule_class'] == $class['id']) {
        $selected_class = 'selected';
    }
    ?>
                                                            <option value="<?php echo $class['id'] ?>" <?php echo $selected_class; ?>><?php echo $class['class'] ?></option>
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
                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].setData(" ");
                }
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

$('#class_email_template').change(function(){
   var template_id =  $('#class_email_template').val();
   $("#class_group_file").html('');
   $.ajax({
    url : '<?php echo base_url(); ?>admin/mailsms/templatedata',
    type: 'post',
    data : {template_id:template_id,type:'class'},
    dataType: 'json',
    success:function(response){
        $('#class_title').val(response.data.title);
       CKEDITOR.instances['class_msg_text'].setData(response.data.message);
        $("#class_group_file").html(response.attachment_list);
    }
   })
});

$(document).ready(function(){
   var message_id = '<?php echo $messagelist['id']; ?>';
   $("#class_group_file").html('');
   $.ajax({
    url : '<?php echo base_url(); ?>admin/mailsms/schedule_templatedata',
    type: 'post',
    data : {message_id:message_id,type:'class'},
    dataType: 'json',
    success:function(response){
       CKEDITOR.instances['class_msg_text'].setData(response.message);
        $("#class_group_file").html(response.attachment_list);
    }
   })
})

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
</script>

<script>
$(document).ready(function () {
    CKEDITOR.env.isCompatible = true;
    CKEDITOR.replaceClass = 'ckeditor';
});
</script>