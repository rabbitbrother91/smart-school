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
                                
                                <div class="card-body-logo">
                                <h4> <?php echo $this->lang->line('print_logo'); ?></h4> 
                                    <div class="text-center">     
                                        <?php
                                        if ($result->image == "") {
                                            ?>
                                            <div class="card-body-logo-img"><img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/logo/images.png') ?>" class="" alt="" width="304" height="236"></div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="card-body-logo-img"><img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/logo/'.$result->image); ?>" class="" alt="" width="304" height="236"></div>
                                            <?php
                                        }
                                        ?>
                                        <p class="bolds ptt10">(170px X 184px)</p>
                                    </div>    
                                    <a href="#schsetting" role="button" class="btn btn-primary btn-sm upload_logo"><?php echo $this->lang->line('update'); ?></a>
                                    <a href="#schsetting" role="button" class="btn btn-primary btn-sm upload_logo" style="display: none"> data-toggle="tooltip" title="<?php echo $this->lang->line('edit_print_logo'); ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><i class="fa fa-picture-o"></i> <?php echo $this->lang->line('edit_print_logo'); ?></a>
                                </div>    
                            </div> 

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card-body-logo">
                                    <h4><?php echo $this->lang->line('admin_logo'); ?> </h4>
                                    <div class="text-center"> 
                                        <?php
                                        if ($result->admin_logo == "") {
                                            ?>
                                            <div class="card-body-logo-img"><img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/admin_logo/images.png'); ?>" class="" alt="" width="204" height="60"></div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="card-body-logo-img"><img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/admin_logo/'.$result->admin_logo); ?>" class="" alt="" width="204" height="60"></div>
                                            <?php
                                        }
                                        ?>
                                        
                                        <p class="bolds ptt10">(290px X 51px)</p>
                                    </div> 
                                    <a href="#schsetting" role="button" class="btn btn-primary btn-sm upload_admin_logo"><?php echo $this->lang->line('update'); ?></a>   
                                    <a href="#admin_logo" role="button" class="btn btn-primary btn-sm upload_admin_logo" style="display:none"> data-toggle="tooltip" title="<?php echo $this->lang->line('edit_admin_logo'); ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><i class="fa fa-picture-o"></i> <?php echo $this->lang->line('edit_admin_logo'); ?></a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                
                                <div class="card-body-logo">
                                <h4><?php echo $this->lang->line('admin_small_logo'); ?></h4>   
                                    <div class="text-center"> 
                                        <?php
                                        if ($result->admin_small_logo == "") {
                                            ?>
                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/logo/images.png'); ?>" alt="">
                                            <?php
                                        } else {
                                            ?>
                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/admin_small_logo/'.$result->admin_small_logo); ?>" width="32" height="32">
                                            <?php
                                        }
                                        ?>
                                        
                                        <p class="bolds ptt10">(32px X 32px)</p>
                                    </div>    
                                    <a href="#schsetting" role="button" class="btn btn-primary btn-sm upload_admin_small_logo"><?php echo $this->lang->line('update'); ?></a>
                                    <a href="#admin_small_logo" role="button" class="btn btn-primary btn-sm upload_admin_small_logo" style="display: none" data-toggle="tooltip" title="<?php echo $this->lang->line('edit_admin_small_logo'); ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><i class="fa fa-picture-o"></i> <?php echo $this->lang->line('edit_admin_small_logo'); ?></a>
                                </div>
                            </div>

                             <div class="col-lg-3 col-md-6 col-sm-6">
                                
                                <div class="card-body-logo">
                                <h4><?php echo $this->lang->line('app_logo'); ?> </h4> 
                                    <div class="text-center">    
                                        <?php
                                        if ($result->app_logo == "") {
                                            ?>
                                            <div class="card-body-logo-img"><img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/logo/images.png'); ?>" class="" alt="" width="290" height="51"></div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="card-body-logo-img"><img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/logo/app_logo/'.$result->app_logo); ?>" width="290" height="51"></div>
                                            <?php
                                        }
                                        ?>
                                        
                                        <p class="bolds ptt10">(290px X 51px)</p>
                                    </div>
                                    <a href="#schsetting" role="button" class="btn btn-primary btn-sm upload_app_logo"><?php echo $this->lang->line('update'); ?></a>    
                                    <a href="#app_logo" role="button" class="btn btn-primary btn-sm upload_app_logo" style="display: none" data-toggle="tooltip" title="<?php echo $this->lang->line('edit_app_logo'); ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><i class="fa fa-picture-o"></i> <?php echo $this->lang->line('edit_app_logo'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>    
            </div><!--/.col (left) -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<div class="modal fade" id="modal-upload_admin_logo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('edit_admin_logo'); ?></h4>
            </div>
            <div class="modal-body upload_logo_body">
                <!-- ==== -->
                <form class="box_upload boxupload has-advanced-upload" method="post" action="<?php echo site_url('schsettings/ajax_editlogo') ?>" enctype="multipart/form-data">
                    <input value="<?php echo $result->id ?>" type="hidden" name="id" id="id_logo_admin"/>
                    <input type="file" name="file" id="file_admin">
                    <!-- Drag and Drop container-->
                    <div class="box__input upload-admin_area"  id="uploadfile_admin">
                        <i class="fa fa-download box__icon"></i>
                        <label><strong><?php echo $this->lang->line('choose_a_file_or_drag_it_here'); ?></strong>
                            <!-- <span class="box__dragndrop"> <?php //echo $this->lang->line('or') ?> <span><?php //echo $this->lang->line('drag') ?></span><?php //echo $this->lang->line('it_here') ?></span> --></label>

                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="modal-upload_app_logo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('edit_app_logo'); ?></h4>
            </div>
            <div class="modal-body upload_logo_body">
                <!-- ==== -->
                <form class="box_upload boxupload has-advanced-upload" method="post" action="<?php echo site_url('schsettings/ajax_editlogo') ?>" enctype="multipart/form-data">
                    <input value="<?php echo $result->id ?>" type="hidden" name="id" id="id_app_logo"/>
                    <input type="file" name="file" id="file_applogo">
                    <!-- Drag and Drop container-->
                    <div class="box__input upload-app_logo_area"  id="uploadapp_logo">
                        <i class="fa fa-download box__icon"></i>
                        <label><strong><?php echo $this->lang->line('choose_a_file_or_drag_it_here'); ?></strong><!-- <span class="box__dragndrop"> <?php //echo $this->lang->line('or') ?> <span><?php //echo $this->lang->line('drag') ?></span><?php //echo $this->lang->line('it_here') ?></span>. --></label>

                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modal-upload_admin_small_logo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('edit_admin_small_logo'); ?></h4>
            </div>
            <div class="modal-body upload_logo_body">
                <!-- ==== -->
                <form class="box_upload boxupload has-advanced-upload" method="post" action="<?php echo site_url('schsettings/ajax_editlogo') ?>" enctype="multipart/form-data">
                    <input value="<?php echo $result->id ?>" type="hidden" name="id" id="id_logo_small"/>
                    <input type="file" name="file" id="file_small">
                    <!-- Drag and Drop container-->
                    <div class="box__input upload-small_area"  id="uploadfile_small">
                        <i class="fa fa-download box__icon"></i>
                        <label><strong><?php echo $this->lang->line('choose_a_file_or_drag_it_here'); ?></strong><!-- <span class="box__dragndrop"> <?php //echo $this->lang->line('or') ?> <span><?php //echo $this->lang->line('drag') ?></span><?php //echo $this->lang->line('it_here') ?></span>. --></label>

                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modal-uploadfile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('edit_logo'); ?></h4>
            </div>
            <div class="modal-body upload_logo_body">
                <!-- ==== -->
                <form class="box_upload boxupload has-advanced-upload" method="post" action="<?php echo site_url('schsettings/ajax_editlogo') ?>" enctype="multipart/form-data">
                    <input value="<?php echo $result->id ?>" type="hidden" name="id" id="id_logo"/>
                    <input type="file" name="file" id="file">
                    <!-- Drag and Drop container-->
                    <div class="box__input upload-area"  id="uploadfile">
                        <i class="fa fa-download box__icon"></i>
                        <label><strong><?php echo $this->lang->line('choose_a_file_or_drag_it_here'); ?></strong><!-- <span class="box__dragndrop"> <?php //echo $this->lang->line('or') ?> <span><?php //echo $this->lang->line('drag') ?></span><?php //echo $this->lang->line('it_here') ?></span>. --></label>

                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<div id="andappModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('register_your_android_app_purchase_code') ?></h4>
            </div>
            <form action="<?php echo site_url('admin/admin/updateandappCode') ?>" method="POST" id="andapp_code">
                <div class="modal-body andapp_modal-body">
                    <div class="error_message">

                    </div>
                    <div class="form-group">
                        <label class="ainline"><span><?php echo $this->lang->line('envato_market_purchase_code_for_smart_school_android_app') ?> ( <a target="_blank" href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-"> <?php echo $this->lang->line('how_to_find_it') ?></a> )</span></label>
                        <input type="text" class="form-control" id="input-app-envato_market_purchase_code" name="app-envato_market_purchase_code">
                        <div id="error" class="input-error text text-danger"></div>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo $this->lang->line('your_email_registered_with_envato') ?></label>
                        <input type="text" class="form-control" id="input-app-email" name="app-email">
                        <div id="error" class="input-error text text-danger"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('saving') ?>"><?php echo $this->lang->line('save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
    var logo_type = "logo";
    $('.upload_logo').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        logo_type = $this.data('logo_type');

        $this.button('loading');
        $('#modal-uploadfile').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    }); 
// set focus when modal is opened
    $('#modal-uploadfile').on('shown.bs.modal', function () {
        $('.upload_logo').button('reset');
    });

    $('.upload_admin_logo').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        logo_type = $this.data('logo_type');
        $this.button('loading');
        $('#modal-upload_admin_logo').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    });
// set focus when modal is opened
    $('#modal-upload_admin_logo').on('shown.bs.modal', function () {
        $('.upload_admin_logo').button('reset');
    });

    $('.upload_admin_small_logo').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        logo_type = $this.data('logo_type');

        $this.button('loading');
        $('#modal-upload_admin_small_logo').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    });
// set focus when modal is opened
    $('#modal-upload_admin_small_logo').on('shown.bs.modal', function () {
        $('.upload_admin_small_logo').button('reset');
    });

    $(".edit_setting").on('click', function (e) {
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: '<?php echo site_url("schsettings/ajax_schedit") ?>',
            type: 'POST',
            data: $('#schsetting_form').serialize(),
            dataType: 'json',

            success: function (data) {

                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);
                    window.location.reload(true);
                }

                $this.button('reset');
            }
        });
    });
</script>
<script type="text/javascript">
    $(function () {

        // Drag enter
        $('.upload-area').on('dragenter', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        // Drag over
        $('.upload-area').on('dragover', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        // Drop
        $('.upload-area').on('drop', function (e) {
            e.stopPropagation();
            e.preventDefault();

            $("h1").text("Upload");

            var file = e.originalEvent.dataTransfer.files;
            var fd = new FormData();

            fd.append('file', file[0]);
            fd.append("id", $('#id_logo').val());
            fd.append("logo_type", logo_type);

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
            fd.append("id", $('#id_logo').val());
            fd.append("logo_type", logo_type);
            uploadData(fd);
        });
    });

// Sending AJAX request and upload file
    function uploadData(formdata) {

        $.ajax({
            url: '<?php echo site_url('schsettings/ajax_editlogo') ?>',
            type: 'post',
            data: formdata,
            contentType: false,
            processData: false,
            dataType: 'json',
            cache: false,

            beforeSend: function () {
                $('#modal-uploadfile').addClass('modal_loading');
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
                $('#modal-uploadfile').removeClass('modal_loading');

            }


        });
    }

    $(function () {

        // Drag enter
        $('.upload-small_area').on('dragenter', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        // Drag over
        $('.upload-small_area').on('dragover', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        // Drop
        $('.upload-small_area').on('drop', function (e) {
            e.stopPropagation();
            e.preventDefault();

            $("h1").text("Upload");

            var file = e.originalEvent.dataTransfer.files;
            var fd = new FormData();

            fd.append('file', file[0]);
            fd.append("id", $('#id_logo_small').val());
            fd.append("logo_type", logo_type);

            uploadSmallData(fd);
        });

        // Open file selector on div click
        $("#uploadfile_small").click(function () {
            $("#file_small").click();
        });

        // file selected
        $("#file_small").change(function () {
            var fd = new FormData();

            var files = $('#file_small')[0].files[0];

            fd.append('file', files);
            fd.append("id", $('#id_logo_small').val());
            fd.append("logo_type", logo_type);
            uploadSmallData(fd);
        });
    });

// Sending AJAX request and upload file
    function uploadSmallData(formdata) {

        $.ajax({
            url: '<?php echo site_url('schsettings/ajax_editadmin_smalllogo') ?>',
            type: 'post',
            data: formdata,
            contentType: false,
            processData: false,
            dataType: 'json',
            cache: false,

            beforeSend: function () {
                $('#modal-upload_admin_small_logo').addClass('modal_loading');
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
                $('#modal-upload_admin_small_logo').removeClass('modal_loading');

            }


        });
    }

    $(function () {

        // Drag enter
        $('.upload-admin_area').on('dragenter', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        // Drag over
        $('.upload-admin_area').on('dragover', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        // Drop
        $('.upload-admin_area').on('drop', function (e) {
            e.stopPropagation();
            e.preventDefault();

            $("h1").text("Upload");

            var file = e.originalEvent.dataTransfer.files;
            var fd = new FormData();

            fd.append('file', file[0]);
            fd.append("id", $('#id_logo_small').val());
            fd.append("logo_type", logo_type);

            uploadadminlData(fd);
        });

        // Open file selector on div click
        $("#uploadfile_admin").click(function () {
            $("#file_admin").click();
        });

        // file selected
        $("#file_admin").change(function () {
            var fd = new FormData();

            var files = $('#file_admin')[0].files[0];

            fd.append('file', files);
            fd.append("id", $('#id_logo_small').val());
            fd.append("logo_type", logo_type);
            uploadadminData(fd);
        });
    });

// Sending AJAX request and upload file
    function uploadadminData(formdata) {

        $.ajax({
            url: '<?php echo site_url('schsettings/ajax_editadmin_adminlogo') ?>',
            type: 'post',
            data: formdata,
            contentType: false,
            processData: false,
            dataType: 'json',
            cache: false,

            beforeSend: function () {
                $('#modal-upload_admin_logo').addClass('modal_loading');
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
                $('#modal-upload_admin_logo').removeClass('modal_loading');

            }


        });
    }

</script>


<script type="text/javascript">
    $('.upload_app_logo').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        logo_type = $this.data('logo_type');

        $this.button('loading');
        $('#modal-upload_app_logo').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    });
// set focus when modal is opened
    $('#modal-upload_app_logo').on('shown.bs.modal', function () {
        $('.upload_app_logo').button('reset');
    });

    $(function () {

        // Drag enter
        $('.upload-app_logo_area').on('dragenter', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        // Drag over
        $('.upload-app_logo_area').on('dragover', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        // Drop
        $('.upload-app_logo_area').on('drop', function (e) {
            e.stopPropagation();
            e.preventDefault();

            $("h1").text("Upload");

            var file = e.originalEvent.dataTransfer.files;
            var fd = new FormData();

            fd.append('file', file[0]);
            fd.append("id", $('#id_app_logo').val());
            // fd.append("logo_type", logo_type);

            uploadSmallData(fd);
        });

        // Open file selector on div click
        $("#uploadapp_logo").click(function () {
            $("#file_applogo").click();
        });

        // file selected
        $("#file_applogo").change(function () {
            var fd = new FormData();

            var files = $('#file_applogo')[0].files[0];


            fd.append('file', files);
            fd.append("id", $('#id_app_logo').val());
            // fd.append("logo_type", logo_type);
            uploadAppData(fd);
        });
    });

// Sending AJAX request and upload file
    function uploadAppData(formdata) {

        $.ajax({
            url: '<?php echo site_url('schsettings/ajax_applogo') ?>',
            type: 'post',
            data: formdata,
            contentType: false,
            processData: false,
            dataType: 'json',
            cache: false,

            beforeSend: function () {
                $('#modal-upload_app_logo').addClass('modal_loading');
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
                $('#modal-upload_app_logo').removeClass('modal_loading');

            }


        });
    }
</script>