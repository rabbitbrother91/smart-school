<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>backend/js/ckeditor_config.js"></script>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <form id="form1" action="<?php echo site_url('admin/front/gallery/create') ?>"  enctype="multipart/form-data" id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                <div class="col-md-9">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_gallery'); ?></h3>

                        </div><!-- /.box-header -->
                        <!-- form start -->

                        <div class="box-body">

                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
                            <?php } ?>
                            <?php
                            if (isset($error_message)) {
                                echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                            }
                            ?>      
                            <?php echo $this->customlib->getCSRF(); ?>  
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>  
                                <input id="title" name="title" placeholder="" type="text" class="form-control"  value="<?php echo set_value('title'); ?>" />
                                <span class="text-danger"><?php echo form_error('title'); ?></span>
                            </div>
                            <div class="dividerhr"></div>
                            <div class="formgroup10 form-group mb10">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label><small class="req"> *</small>
                                <button type="button" class="btn btn-primary btn-sm pull-right" id="media_images" data-toggle="modal" data-target="#mediaModal"><i class="fa fa-plus"></i>
                                    <?php echo $this->lang->line('add_media'); ?>
                                </button>
                            </div>  
                            <div class="form-group"> 
                                <textarea id="editor1" name="description" placeholder="" type="text" class="form-control ss"><?php echo set_value('description'); ?></textarea>   
                                <span class="text-danger"><?php echo form_error('description'); ?></span>
                            </div>
                            <div class="dividerhr"></div>

                            <div class="formgroup10">
                                <label><?php echo $this->lang->line('gallery_images'); ?></label>
                                <button type="button" class="btn btn-primary btn-sm gallery_image pull-right" id="gallery_images"><i class="fa fa-plus"></i>  <?php echo $this->lang->line('add_images'); ?></button>
                                <div class="mediarow">
                                    <div class="row">
                                        <div class="gallery_content"></div> 
                                    </div>
                                </div>   
                            </div><!-- /.box-header -->
                        </div><!-- /.box-body -->
                    </div>
                    <div class="panel box box-primary collapsed-box"> 
                        <div class="box-header with-border">
                            <a class="btn boxplus" data-widget="collapse" data-original-title="Collapse"><?php echo $this->lang->line('seo_detail'); ?><i class="fa fa-plus"></i>
                            </a>
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('meta_title'); ?></label>
                                <input id="meta_title" name="meta_title" placeholder="" type="text" class="form-control"  value="<?php echo set_value('meta_title'); ?>" />
                                <span class="text-danger"><?php echo form_error('meta_title'); ?></span>
                            </div> 
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('meta_keyword'); ?></label>
                                <input id="meta_keywords" name="meta_keywords" placeholder="" type="text" class="form-control"  value="<?php echo set_value('meta_keywords'); ?>" />
                                <span class="text-danger"><?php echo form_error('meta_keywords'); ?></span>
                            </div> 
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('meta_description'); ?></label>
                                <textarea id="editor1" name="meta_description" placeholder="" type="text" class="form-control" ><?php echo set_value('meta_description'); ?></textarea>
                                <span class="text-danger"><?php echo form_error('meta_description'); ?></span>
                            </div> 
                        </div>
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
                <div class="col-md-3 col-sm-12">
                    <div class="uploadbarfixes">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo $this->lang->line('sidebar_setting'); ?></h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-placement="bottom" data-widget="collapse" data-toggle="tooltip" title="<?php echo $this->lang->line('collapse'); ?>"><i class="fa fa-minus"></i></button>
                                </div><!-- /.box-tools -->
                            </div><!-- /.box-header -->

                            <div class="box-body">

                                <div class="">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('sidebar'); ?></label>
                                    <div class="material-switch pull-right">
                                        <input id="sidebar" name="sidebar" type="checkbox" class="chk"  value="1"  />
                                        <label for="sidebar" class="label-success"></label>
                                    </div>
                                </div>                      

                            </div><!-- /.box-body -->
                        </div><!-- /.box -->              

                        <!-- page image -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo $this->lang->line('featured_image'); ?></h3>
                                <div class="box-tools pull-right">
                                    <!-- Buttons, labels, and many other things can be placed here! -->
                                    <!-- Here is a label for example -->
                                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="<?php echo $this->lang->line('collapse'); ?>"><i class="fa fa-minus"></i></button>
                                </div><!-- /.box-tools -->
                            </div><!-- /.box-header -->

                            <div class="box-body">


                                <div class="form-group mb0">
                                    <div class="input-group input-group-sm">
                                        <input class="form-control iframe-btn" placeholder="<?php echo $this->lang->line('select_image'); ?>" type="text" name="image" id="image">
                                        <span class="input-group-btn">
                                            <a href="#" class="btn cfees feature_image_btn" id="feature_image" data-toggle="tooltip" data-title="<?php echo $this->lang->line('select_image'); ?>" type="button" ><i class="fa fa-folder-open"></i></a>
                                            <a href="#" class="btn removegraybtn delete_media" id="image" data-toggle="tooltip" data-title="<?php echo $this->lang->line('delete'); ?>" type="button"><i class="fa fa-trash"></i></a>
                                        </span>
                                    </div>
                                    <div id="image_preview" class="thumbnail mb0" style="margin-top: 10px; display: none">
                                        <img src="" class="img-responsive feature_image_url" >
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                        <div class="box box-primary">
                            <div class="box-body">

                                <button type="submit" class="btn cfees btn-block"><i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?></button>
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                        <!-- page image -->

                    </div>  
                </div><!-- /.col-md-4 -->

            </form>
        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script>
    $(document).ready(function () {
        var popup_target = 'media_images';
     CKEDITOR.env.isCompatible = true;
           CKEDITOR.replace('editor1',
                {
                    toolbar: "FrontCMS",
                    extraPlugins: '',
                    customConfig: baseurl + '/backend/js/ckeditor_config.js',
                    entities: false//
                });

        $('#mediaModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
        $(document).on('click', '.feature_image_btn', function (event) {
            $("#mediaModal").modal('toggle', $(this));
        });

        $(document).on('click', '.gallery_image', function (event) {
            $("#mediaModal").modal('toggle', $(this));
        });

        $('#mediaModal').on('show.bs.modal', function (event) {
            var a = $(event.relatedTarget) // Button that triggered the modal
            popup_target = a[0].id;
            var button = $(event.relatedTarget) // Button that triggered the modal
            console.log(popup_target);
            var $modalDiv = $(event.delegateTarget);
            $('.modal-media-body').html("");
            $.ajax({
                type: "POST",
                url: baseurl + "admin/front/media/getMedia",
                dataType: 'text',
                data: {},
                beforeSend: function () {

                    $modalDiv.addClass('modal_loading');
                },
                success: function (data) {
                    $('.modal-media-body').html(data);
                },
                error: function (xhr) { // if error occured
                    $modalDiv.removeClass('modal_loading');
                },
                complete: function () {
                    $modalDiv.removeClass('modal_loading');
                },
            });
        });

        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });

        $(document).on('click', '.img_div_modal', function (event) {
            $('.img_div_modal div.fadeoverlay').removeClass('active');
            $(this).closest('.img_div_modal').find('.fadeoverlay').addClass('active');

        });

        $(document).on('click', '.add_media', function (event) {
            var content_html = $('div#media_div').find('.fadeoverlay.active').find('img').data('img');
             var content_html_thumb = $('div#media_div').find('.fadeoverlay.active').find('img').data('thumb_img');
            var content_id = $('div#media_div').find('.fadeoverlay.active').find('img').data('fid');
            var is_image = $('div#media_div').find('.fadeoverlay.active').find('img').data('is_image');
            var content_type = $('div#media_div').find('.fadeoverlay.active').find('img').data('content_type');
            var content_name = $('div#media_div').find('.fadeoverlay.active').find('img').data('content_name');

            var vid_url = $('div#media_div').find('.fadeoverlay.active').find('img').data('vid_url');
            var content = "";

            if (popup_target === "media_images") {
                if (typeof content_html !== "undefined") {
                    if (is_image === 1) {
                        content = '<img src="' + content_html + '">';
                    } else if (content_type == "video") {

                        var youtubeID = YouTubeGetID(vid_url);


                        content = '<iframe id="video" width="420" height="315" src="//www.youtube.com/embed/' + youtubeID + '?rel=0" frameborder="0" allowfullscreen></iframe>';

                    } else {
                        content = '<a href="' + content_html + '">' + content_name + '</a>';

                    }
                    InsertHTML(content);
                    $('#mediaModal').modal('hide');
                }
            } else if (popup_target === "feature_image") {
                if (is_image === 1) {
                    addImage(content_html);
                } else {
                    //error show  
                }
                $('#mediaModal').modal('hide');
            } else if (popup_target === "gallery_images") {
                if (content_type === "image/gif" || content_type === "image/jpeg" || content_type === "image/png" || content_type === "video") {

                    insert_gallery(content_html_thumb, content_id, content_name, is_image);
                } else {
                    //error show  
                }


                $('#mediaModal').modal('hide');
            }

        });
        $(document).on("click", ".pagination li a", function (event) {
            event.preventDefault();
            var page = $(this).data("ci-pagination-page");
            load_country_data(page);
        });
    });
    function YouTubeGetID(url) {
        var ID = '';
        url = url.replace(/(>|<)/gi, '').split(/(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/)/);
        if (url[2] !== undefined) {
            ID = url[2].split(/[^0-9a-z_\-]/i);
            ID = ID[0];
        } else {
            ID = url;
        }
        return ID;
    }

    function addImage(content_html) {
        $('.feature_image_url').attr('src', content_html);
        $('#image').val(content_html);
        $('#image_preview').css("display", "block");
    }
    $(document).on('click', '.delete_media', function () {
        $('.feature_image_url').attr('src', '');
        $('#image').val('');
        $('#image_preview').css("display", "none");
    });
    function InsertHTML(content_html) {
        // Get the editor instance that we want to interact with.
        var editor = CKEDITOR.instances.editor1;


        // Check the active editing mode.
        if (editor.mode == 'wysiwyg')
        {
            editor.insertHtml(content_html);
        } else
            alert("<?php echo $this->lang->line('you_must_be_in_wysiwyg_mode') ?>");
    }

   

    function insert_gallery(content_image, content_id, content_name, is_image) {
        var output = '';
        output += "<div class='col-sm-3 col-md-3 col-lg-3 col-xs-6 img_div_modal gallery_img div_record_" + content_id + "'>";
        output += "<div class='fadeoverlay'>";
          output += "<div class='fadeheight'>";
        output += "<img class='img-responsive' data-fid='" + content_id + "' data-content_name='" + content_name + "' data-is_image='" + is_image + "' data-img='" + content_image + "' src='" + content_image + "'>";
        output += "<input type='hidden' value='" + content_id + "' name='gallery_images[]'>";
      
        output += "<div class='overlay3'>";
        output += "<a href='#' class='uploadclosebtn delete_gallery_img' data-record_id='" + content_id + "' data-toggle='modal' data-target='#confirm-delete'><i class=' fa fa-trash-o'></i></a>";
        output += "<p class='processing'><?php echo $this->lang->line('processing') ?></p>";
        output += "</div>";
        output += "</div>";
           if (is_image == 1) {
            output += "<i class='fa fa-picture-o videoicon'></i>";
        } else {
            output += "<i class='fa fa-youtube-play videoicon'></i>";
        }
        

        output += "</div>";
        output += "<p class='fadeoverlay-para'>" + content_name + "</p>";
        output += "</div>";
        $(output).appendTo(".gallery_content");
    }


    $(document).on('click', '.delete_gallery_img', function () {
        $(this).closest('.gallery_img').remove();

    });

</script>



<!-- Modal -->
<div class="modal fade" id="mediaModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-media-title" id="myModalLabel"><?php echo $this->lang->line('media_manager'); ?></h4>
            </div>
            <div class="modal-body modal-media-body pupscroll">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <button type="button" class="btn btn-primary add_media"><?php echo $this->lang->line('add'); ?></button>
            </div>
        </div>
    </div>
</div>


