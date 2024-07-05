<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <?php $this->load->view('setting/_settingmenu'); ?>
            
            <!-- left column -->
            <div class="col-md-10">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                
                                <div class="card-body-logo h-290">
                                <h4><?php echo $this->lang->line('admin_penal'); ?></h4> 
                                    <div class="text-center">    
                                        <div class="card-h-hidden">
                                            <?php
                                            if ($result->image == "") {
                                                ?>
                                                <div class="card-body-logo-img"><img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/login_image/images.png'); ?>" class="" alt="" width="304" height="236"></div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="card-body-logo-img"><img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/login_image/'. $result->admin_login_page_background); ?>" class="" alt="" width="304" height="236"></div>
                                                <?php
                                            }
                                            ?>
                                        </div>    
                                        <p class="bolds ptt10">(1460px X 1080px)</p> 
                                    </div>    
                                    <a href="#schsetting" role="button" class="btn btn-primary btn-sm upload_logo"><?php echo $this->lang->line('update'); ?></a>
                                    <a href="#schsetting" role="button" class="btn btn-primary btn-sm upload_logo" style="display: none"> data-toggle="tooltip" title="<?php echo $this->lang->line('edit_print_logo'); ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><i class="fa fa-picture-o"></i> <?php echo $this->lang->line('edit_print_logo'); ?></a>
                                </div>    
                            </div> 
                            
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                
                                <div class="card-body-logo h-290">
                                <h4> <?php echo $this->lang->line('user_penal'); ?></h4> 
                                    <div class="text-center">     
                                        <div class="card-h-hidden">
                                            <?php
                                            if ($result->image == "") {
                                                ?>
                                                <div class="card-body-logo-img"><img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/login_image/images.png'); ?>" class="" alt="" width="304" height="236"></div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="card-body-logo-img"><img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/login_image/'. $result->user_login_page_background); ?>" class="" alt="" width="304" height="236"></div>
                                                <?php
                                            }
                                            ?>
                                        </div>    
                                        <p class="bolds ptt10">(1460px X 1080px)</p> 
                                    </div>    
                                    <a href="#schsetting" role="button" class="btn btn-primary btn-sm user_login"><?php echo $this->lang->line('update'); ?></a>
                                    <a href="#schsetting" role="button" class="btn btn-primary btn-sm user_login" style="display: none"> data-toggle="tooltip" title="<?php echo $this->lang->line('edit_print_logo'); ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><i class="fa fa-picture-o"></i> <?php echo $this->lang->line('edit_print_logo'); ?></a>
                                </div>    
                            </div>                             
                        </div>
                    </div>    
                </div>    
            </div><!--/.col (left) -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<div class="modal fade" id="admin_login_page_background" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><span id="myModalHeading"></span> </h4>
            </div>
            <div class="modal-body upload_logo_body">
                <!-- ==== -->
                <form class="box_upload boxupload has-advanced-upload" method="post" action="<?php echo site_url('schsettings/ajax_editlogo') ?>" enctype="multipart/form-data">
                    <input value="<?php echo $result->id ?>" type="hidden" name="id" id="setting_admin_id"/>
                    <input value=" " type="hidden" name="logo_type" id="logo_type"/>
                    <input type="file" name="file" id="file">
                    <!-- Drag and Drop container-->
                    <div class="box__input upload-area"  id="uploadfile">
                        <i class="fa fa-download box__icon"></i>
                        <label><strong><?php echo $this->lang->line('choose_a_file_or_drag_it_here'); ?></strong> </label>

                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
 
<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
    
    $('.upload_logo').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        
        $('#logo_type').val("admin_logo");
        $('#myModalHeading').html("<?php echo $this->lang->line('admin_login_page_background') ?>");
        
        $this.button('loading');
        $('#admin_login_page_background').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    });

    $('.user_login').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        
        $('#logo_type').val("user_login");
        $('#myModalHeading').html("<?php echo $this->lang->line('user_login_page_background') ?>");
        
        $this.button('loading');
        $('#admin_login_page_background').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    });     
    
// set focus when modal is opened
    $('#admin_login_page_background').on('shown.bs.modal', function () {
        $('.upload_logo').button('reset');
        $('.user_login').button('reset');        
    });

    $(function () {       

        // Drop
        $('.upload-area').on('drop', function (e) {
            e.stopPropagation();
            e.preventDefault();

            $("h1").text("Upload");

            var file = e.originalEvent.dataTransfer.files;
            var fd = new FormData();

            fd.append('file', file[0]);
            fd.append("id", $('#setting_admin_id').val());
            fd.append("logo_type", $('#logo_type').val());

            uploadData(fd);
        });

        // Open file selector on div click
        $("#uploadfile").click(function () {
            $("#file").click();
        });

        // file selected
        $("#file").change(function () {
            var fd = new FormData();
            var files = $('#file')[0].files[0];
            fd.append('file', files);
            fd.append("id", $('#setting_admin_id').val());
            fd.append("logo_type", $('#logo_type').val());
            uploadData(fd);
        });
    });

// Sending AJAX request and upload file
    function uploadData(formdata) {

        $.ajax({
            url: '<?php echo site_url('schsettings/add_admin_login_background') ?>',
            type: 'post',
            data: formdata,
            contentType: false,
            processData: false,
            dataType: 'json',
            cache: false,

            beforeSend: function () {
                $('#admin_login_page_background').addClass('modal_loading');
            },
            success: function (response) {
                if (response.success) {
                    successMsg(response.message);
                    window.location.reload(true);
                } else {
                    errorMsg(response.error.file);
                }
            },
            error: function (xhr) { // if error occured

            },
            complete: function () {
                $('#admin_login_page_background').removeClass('modal_loading');
            }

        });
    }    

</script> 