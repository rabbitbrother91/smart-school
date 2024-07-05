<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <form id="form1" action="<?php echo site_url('admin/front/gallery/create') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                <div class="col-md-9">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Gallery --r</h3>

                        </div><!-- /.box-header -->
                        <!-- form start -->

                        <div class="box-body">

                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php 
                                    echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg');
                                ?>
                            <?php } ?>
                            <?php
                            if (isset($error_message)) {
                                echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                            }
                            ?>      
                            <?php echo $this->customlib->getCSRF(); ?>  



                            <div class="form-group">
                                <label for="exampleInputEmail1">Title --r</label>
                                <input id="title" name="title" placeholder="" type="text" class="form-control"  value="<?php echo set_value('title'); ?>" />
                                <span class="text-danger"><?php echo form_error('title'); ?></span>
                            </div>



                            <div class="form-group"> <!-- Submit button !-->
                                <button type="button" class="btn btn-primary" id="media_images" data-toggle="modal" data-target="#mediaModal"><i class="fa fa-plus"></i>
                                    Add Media --r
                                </button>
                            </div>  
                            <div class="form-group">
                                <label for="exampleInputEmail1">Description --r</label>
                                <textarea id="editor1" name="description" placeholder="" type="text" class="form-control ss" >
                                    <?php echo set_value('description'); ?>
                                </textarea>   
                                <span class="text-danger"><?php echo form_error('description'); ?></span>
                            </div>
                            <h3>SEO Detail --r</h3>
                            <hr>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Meta Title --r</label>
                                <input id="meta_title" name="meta_title" placeholder="" type="text" class="form-control"  value="<?php echo set_value('meta_title'); ?>" />
                                <span class="text-danger"><?php echo form_error('meta_title'); ?></span>
                            </div> 
                            <div class="form-group">
                                <label for="exampleInputEmail1">Meta Keywords --r</label>
                                <input id="meta_keywords" name="meta_keywords" placeholder="" type="text" class="form-control"  value="<?php echo set_value('meta_keywords'); ?>" />
                                <span class="text-danger"><?php echo form_error('meta_keywords'); ?></span>
                            </div> 
                            <div class="form-group">
                                <label for="exampleInputEmail1">Meta Description --r</label>
                                <textarea id="editor1" name="meta_description" placeholder="" type="text" class="form-control" >
                                    <?php echo set_value('meta_description'); ?>
                                </textarea>
                                <span class="text-danger"><?php echo form_error('meta_description'); ?></span>
                            </div> 

                        </div><!-- /.box-body -->


                    </div>

                </div><!--/.col (right) -->
                <!-- left column -->
                <div class="col-md-3 col-sm-12">

                    <!-- Save button -->
                    <div class="box box-success">
                        <div class="box-body">

                            <button type="submit" class="btn btn-success btn-block"><i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?></button>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                    <!-- page settings -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Page Setting --r</h3>
                            <div class="box-tools pull-right">
                                <!-- Buttons, labels, and many other things can be placed here! -->
                                <!-- Here is a label for example -->
                                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                            </div><!-- /.box-tools -->
                        </div><!-- /.box-header -->

                        <div class="box-body">
                            <div class="form-group">
                                <label class="control-label" for="email">
                                    Publish Date --r
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control date" id="publish_date" name="publish_date" type="text"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Publish --r&nbsp;</label>
                                <div class="material-switch pull-right">
                                    <input id="publish" name="publish" type="checkbox" class="chk"  value="1"  />
                                    <label for="publish" class="label-success"></label>
                                </div>
                            </div>                       
                            <div class="form-group">
                                <label for="exampleInputEmail1">Sidebar --r&nbsp;</label>
                                <div class="material-switch pull-right">
                                    <input id="sidebar" name="sidebar" type="checkbox" class="chk"  value="1"  />
                                    <label for="sidebar" class="label-success"></label>
                                </div>
                            </div>                      

                        </div><!-- /.box-body -->
                    </div><!-- /.box -->              

                    <!-- page image -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Featured Image --r</h3>
                            <div class="box-tools pull-right">
                                <!-- Buttons, labels, and many other things can be placed here! -->
                                <!-- Here is a label for example -->
                                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                            </div><!-- /.box-tools -->
                        </div><!-- /.box-header -->

                        <div class="box-body">


                            <div class="form-group">
                                <div class="input-group input-group-sm">
                                    <input class="form-control iframe-btn" placeholder="Select Image --r" type="text" name="image" id="image">
                                    <span class="input-group-btn">
                                        <a href="#" class="btn btn-danger delete_media" id="image" data-toggle="tooltip" data-title="remove" type="button"><i class="fa fa-trash"></i></a>
                                        <a href="#" class="btn btn-success feature_image_btn" id="feature_image" data-toggle="tooltip" data-title="Select Image --r" type="button" ><i class="fa fa-folder-open"></i></a>
                                    </span>
                                </div>
                                <div id="image_preview" class="thumbnail" style="margin-top: 10px; display: none">
                                    <img src="" class="img-responsive feature_image_url" >
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                    <!-- page image -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Gallery --r</h3>
                            <div class="box-tools pull-right">

                                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                            </div><!-- /.box-tools -->
                        </div><!-- /.box-header -->

                        <div class="box-body">

                            <div class="row">
                                <div class="col-md-12">

                                    <button type="button" class="btn btn-primary gallery_image" id="gallery_images"><i class="fa fa-plus"></i>  Add Gallery--r

                                    </button>

                                    <div class="row gallery_content"></div> 
                                </div>

                            </div>


                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col-md-4 -->

            </form>
        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script>
    $(document).ready(function () {
        var popup_target = 'media_images';
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $('.date').datepicker({
            format: date_format,
            autoclose: true
        });
        CKEDITOR.env.isCompatible = true;
        CKEDITOR.replace('editor1',
                {
                    enterMode: CKEDITOR.ENTER_BR
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
            var content_id = $('div#media_div').find('.fadeoverlay.active').find('img').data('fid');
            var is_image = $('div#media_div').find('.fadeoverlay.active').find('img').data('is_image');
            var content_type = $('div#media_div').find('.fadeoverlay.active').find('img').data('content_type');
            var content_name = $('div#media_div').find('.fadeoverlay.active').find('img').data('content_name');
            var content = "";

            if (popup_target === "media_images") {
                if (typeof content_html !== "undefined") {
                    if (is_image === 1) {
                        content = '<img src="' + content_html + '">';
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

                    insert_gallery(content_html, content_id, content_name, is_image);
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

    function addImage(content_html) {
        $('.feature_image_url').attr('src', content_html);
        $('#image').val(content_html);
        $('#image_preview').css("display", "block");
    }

    function InsertHTML(content_html) {
        // Get the editor instance that we want to interact with.
        var editor = CKEDITOR.instances.editor1;


        // Check the active editing mode.
        if (editor.mode == 'wysiwyg')
        {
            editor.insertHtml(content_html);
        } else
            alert('You must be in WYSIWYG mode!');
    }

    function insert_gallery(content_image, content_id, content_name, is_image) {
        var output = '';
        output += "<div class='col-sm-6 col-md-6 col-xs-6 gallery_img div_record_" + content_id + "'>";
        output += "<div class='fadeoverlay'>";
        output += "<img class='img-responsive' data-fid='" + content_id + "' data-content_name='" + content_name + "' data-is_image='" + is_image + "' data-img='" + content_image + "' src='" + content_image + "'>";
        output += "<input type='hidden' value='" + content_id + "' name='gallery_images[]'>";
        if (is_image == 1) {
            output += "<i class='fa fa-picture-o videoicon'></i>";
        } else {
            output += "<i class='fa fa-youtube-play videoicon'></i>";
        }
        output += "<div class='overlay3'>";
        output += "<a href='#' class='uploadclosebtn delete_gallery_img' data-record_id='" + content_id + "' data-toggle='modal' data-target='#confirm-delete'><i class=' fa fa-trash-o'></i></a>";
        output += "<p class='processing'>Processing...</p>";
        output += "</div>";
        output += "<p class=''>" + content_name + "</p>";
        output += "</div>";
        output += "</div>";
        $(output).appendTo(".gallery_content");
    }

    $(document).on('click', '.delete_gallery_img', function () {
        $(this).closest('.gallery_img').remove();

    });

</script>



<!-- Modal -->
<div class="modal fade" id="mediaModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-media-title" id="myModalLabel">Media --r</h4>
            </div>
            <div class="modal-body modal-media-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close --r</button>
                <button type="button" class="btn btn-primary add_media">Add --r</button>
            </div>
        </div>
    </div>
</div>


