<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-envelope"></i> <?php echo $this->lang->line('file_types') ?></h3>
                    </div>
                    <form id="form1" action="<?php echo base_url() ?>emailconfig/index"   name="form_filetype" class="form_filetype" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php
if ($this->session->flashdata('msg')) {
    echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg');
}
?>
                            <?php echo $this->customlib->getCSRF(); ?>
                             <h4><?php echo $this->lang->line('setting_for_files'); ?></h4>
                             <hr/>
                                   <div class="form-group">
                  <label> <?php echo $this->lang->line('allowed_extension'); ?> <small class="req"> *</small></label>
                  <textarea class="form-control" rows="6"  name="file_extension" placeholder="" cols="50"><?php echo set_value('file_extension', $filetype->file_extension); ?></textarea>
                   <span class="text-danger"><?php echo form_error('file_extension'); ?></span>
                </div>
                <div class="form-group">
                  <label> <?php echo $this->lang->line('allowed_mime_type'); ?> <small class="req"> *</small></label>
                  <textarea class="form-control" rows="6"  name="file_mime" placeholder="" cols="50"><?php echo set_value('file_mime', $filetype->file_mime); ?></textarea>
                   <span class="text-danger"><?php echo form_error('file_mime'); ?></span>
                </div>
                <div class="form-group">
                  <label> <?php echo $this->lang->line('upload_size_in_bytes'); ?> <small class="req"> *</small></label>
                  <input class="form-control"  name="file_size" placeholder="" value="<?php echo set_value('file_size', $filetype->file_size); ?>">
                   <span class="text-danger"><?php echo form_error('file_size'); ?></span>
                </div>
               
                   <h4 class="pt20"><?php echo $this->lang->line('setting_for_image'); ?></h4>
                   <hr />
                                   <div class="form-group">
                  <label> <?php echo $this->lang->line('allowed_extension'); ?> <small class="req"> *</small></label>
                  <textarea class="form-control" rows="6"  name="image_extension" placeholder="" cols="50"><?php echo set_value('image_extension', $filetype->image_extension); ?></textarea>
                   <span class="text-danger"><?php echo form_error('image_extension'); ?></span>
                </div>
                <div class="form-group">
                  <label> <?php echo $this->lang->line('allowed_mime_type'); ?> <small class="req"> *</small></label>
                  <textarea class="form-control" rows="6"  name="image_mime" placeholder="" cols="50"><?php echo set_value('image_mime', $filetype->image_mime); ?></textarea>
                   <span class="text-danger"><?php echo form_error('image_mime'); ?></span>
                </div>
                <div class="form-group">
                  <label> <?php echo $this->lang->line('upload_size_in_bytes'); ?> <small class="req"> *</small></label>
                  <input class="form-control"  name="image_size" placeholder="" value="<?php echo set_value('image_size', $filetype->image_size); ?>">
                   <span class="text-danger"><?php echo form_error('image_size'); ?></span>
                </div>
                        </div>
                        <div class="box-footer">
                          <button type="submit" class="btn btn-primary pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">

    $(document).ready(function (e) {
        $(document).on('submit','.form_filetype', function (e) {
            e.preventDefault();
            $.ajax({
                url: base_url+'admin/admin/addfiletype',
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.href = base_url+"admin/admin/filetype";
                    }
                },
                error: function () {}
            });
        });
    });
</script>