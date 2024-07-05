<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('email_template_list'); ?></h3>
                        <div class="box-tools pull-right">
                        <?php if ($this->rbac->hasPrivilege('email_template', 'can_add')) {?>
                            <button type="button" class="btn btn-sm btn-primary pull-right" onclick="add()"  data-backdrop="static" ><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?></button>
                        <?php }?>
                        </div>
                    </div>
                    <?php if ($this->session->flashdata('message') != '') {?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('message'); $this->session->unset_userdata('message'); ?>
                        </div>
                    <?php }?>
                    <div class="box-body">
                        <div class="table-responsive overflow-visible">
                            <div class="download_label"><?php echo $this->lang->line('email_template_list'); ?></div>
                            <table class="table table-hover table-striped table-bordered example ">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('message'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

foreach ($email_template_list as $key => $email_template_list_value) {?>
                                        <tr>
                                            <td class="mailbox-name"> <?php echo $email_template_list_value['title'] ?></td>
                                            <td class="mailbox-name"> <?php echo $email_template_list_value['message'] ?></td>
                                            <td class="mailbox-date pull-right no-print">
                                                <?php if ($this->rbac->hasPrivilege('email_template', 'can_edit')) {?>
                                                <a class="btn btn-default btn-xs editemailtemplate" data-id="<?php echo $email_template_list_value['id'] ?>"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i>
                                                </a>
                                                <?php }if ($this->rbac->hasPrivilege('email_template', 'can_delete')) {?>
                                                <a href="<?php echo base_url(); ?>admin/mailsms/delete_email_template/<?php echo $email_template_list_value['id'] ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');"><i class="fa fa-remove"></i>
                                                </a>
                                                <?php }?>
                                            </td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('add_email_template'); ?></h4>
            </div>
            <form id="addemailtemplate" action="<?php echo base_url(); ?>admin/mailsms/add_email_template" method="post" enctype="multipart/form-data">
                <div class="modal-body ptt10 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                        <input id="title" name="title" type="text" class="form-control"  value="<?php echo set_value('title'); ?>" />
                                        <span class="text-danger"><?php echo form_error('title'); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('attachment'); ?></label>
                                        <input id="files" name="files[]" type="file" class="form-control filestyle" multiple="multiple" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group">
                                        <label><?php echo $this->lang->line('message'); ?></label><small class="req"> *</small>
                                        <textarea id="" name="message" class="form-control ckeditor" cols="35" rows="20"><?php echo set_value('message'); ?></textarea>
                                        <span class="text-danger"><?php echo form_error('message'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-info pull-right add_button" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editemailtemplatemodal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('edit_email_template'); ?></h4>
            </div>

            <form id="editemailtemplateform" action="<?php echo base_url(); ?>admin/mailsms/update_email_template" method="post" class="ptt10" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <input name="id" type="hidden" id="edit_id" class="form-control"/>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                        <input name="title" type="text"  id="edit_title" class="form-control"/>
                                        <span class="text-danger"><?php echo form_error('title'); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div id="file_attachment"  class="row"></div>
                            <div id="my_attachment" class="row"></div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('attachment'); ?></label>
                                        <input onchange="preview()" id="attachment" name="files[]" type="file" class="form-control filestyle" multiple="multiple" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group">
                                        <label><?php echo $this->lang->line('message'); ?></label><small class="req"> *</small>
                                        <textarea name="message" class="form-control ckeditor" id="edit_message" cols="35" rows="20"></textarea>
                                        <span class="text-danger"><?php echo form_error('message'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-info pull-right edit_button" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function add(){
        for (var instanceName in CKEDITOR.instances) {
            CKEDITOR.instances[instanceName].updateElement();
        }

        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData(" ");
        }

        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    $("#addemailtemplate").submit(function (event) {
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

        var ins = document.getElementById('files').files.length;
        for (var x = 0; x < ins; x++) {
            formData.append("files[]", document.getElementById('files').files[x]);
        }

//==========

        var $form = $(this),
        url = $form.attr('action');
        var $this = $('.add_button');
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

                if (data.status == 0) {
                    var message = "";
                    $.each(data.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {
                    $('#addemailtemplate')[0].reset();
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].setData(" ");
                    }
                    successMsg(data.message);
                    location.reload();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }, complete: function (data) {
                $this.button('reset');
            }
        })
    });

    $('.editemailtemplate').click(function(){
    $('#editemailtemplatemodal').modal({
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).attr('data-id');
    for (var instanceName in CKEDITOR.instances) {
            CKEDITOR.instances[instanceName].updateElement();
        }
   $.ajax({
       url:'<?php echo site_url("admin/mailsms/edit_email_template"); ?>',
       type:'post',
       data:{id:id},
       dataType:'json',
       success:function(response){
        console.log(response);
            $('#edit_title').val(response.data.title);
            $('#edit_id').val(response.data.id);
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].setData(response.data.message);
            }
            $("#file_attachment").html(response.attachment_list);
       }
   });
})

    $("#editemailtemplateform").submit(function (event) {
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

        var ins = document.getElementById('attachment').files.length;
        for (var x = 0; x < ins; x++) {
            formData.append("attachment[]", document.getElementById('attachment').files[x]);
        }
//==========

        var $form = $(this),
        url = $form.attr('action');
        var $this = $('.edit_button');
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

                if (data.status == 0) {
                    var message = "";
                    $.each(data.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {
                    $('#editemailtemplateform')[0].reset();
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].setData(" ");
                    }
                    successMsg(data.message);
                    location.reload();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }, complete: function (data) {
                $this.button('reset');
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
  const input = document.getElementById('attachment')
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
    var saida = document.getElementById("attachment");
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