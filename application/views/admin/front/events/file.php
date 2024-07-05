<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-empire"></i> <?php echo $this->lang->line('front_cms'); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Event --r</h3>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <div class="row">
                            <form  action="<?php echo site_url('admin/front/events/edit/' . $result['slug']) ?>"  id="eventform" name="employeeform" method="post" accept-charset="utf-8">

                                <div class="col-md-12">
                                    <input type="hidden" name="id" value="<?php echo $result['id']; ?>" id="content_id">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Title --r</label>
                                        <input autofocus=""  id="title" name="title" placeholder="" type="text" class="form-control"  value="<?php echo set_value('title', $result['title']); ?>" />
                                        <span class="text-danger"><?php echo form_error('title'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Event Start --r</label>
                                        <input id="start_date" name="start_date" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('start_date', date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($result['event_start']))); ?>" readonly="readonly" />
                                        <span class="text-danger"><?php echo form_error('event_start'); ?></span>
                                    </div> 
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Event end --r</label>
                                        <input id="end_date" name="end_date" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('end_date', date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($result['event_end']))); ?>" readonly="readonly" />

                                        <span class="text-danger"><?php echo form_error('event_end'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Venue --r</label>
                                        <input autofocus=""  id="venue" name="venue" placeholder="" type="text" class="form-control"  value="<?php echo set_value('venue', $result['event_venue']); ?>" />
                                        <span class="text-danger"><?php echo form_error('venue'); ?></span>
                                    </div>
                                    <div class="form-group"> <!-- Submit button !-->
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mediaModal"><i class="fa fa-plus"></i>
                                            Add Media --r
                                        </button>
                                    </div> 
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Description --r</label>
                                        <textarea id="editor1" name="description" placeholder="" type="text" class="form-control compose-textarea" >
                                            <?php echo set_value('description', $result['description']); ?>
                                 
                                        </textarea>   
                                        <span class="text-danger"><?php echo form_error('description'); ?></span>
                                    </div>

                                </div>
                            </form>
                            <div class="col-md-12">
                                <h3> Add new files --r</h3>
                                </hr>
                                <form action="<?php echo site_url('admin/front/events/ajaxupload') ?>" method="post" enctype="multipart/form-data" id="upload_form">
                                    <input type="hidden" name="record_id" value="<?php echo $result['id']; ?>" id="record_id">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Upload file --r</label>
                                                <input class="filestyle form-control" type='file' name='files[]'  id="multiFiles" size='20' />
                                                <p class="help-block">Allowed .png, .jpg, .jpeg --r</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">




                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary upload">Upload --r</button>

                                </form>
                                <div class="progress uploadprogress">
                                    <div class="progress-bar bg-warning progress-bar-striped myprogress" role="progressbar" style="width:0%">0%</div>
                                </div>
                                <div id="output"></div>
                                <div class="row">
                                    <div class='list-group gallery'>
                                        <?php
                                        if (!empty($result['images'])) {
                                            foreach ($result['images'] as $img_key => $img_value) {
                                                $featured_img = "";
                                                if ($img_value->featured_img == "yes") {
                                                    $featured_img = "active";
                                                }
                                                ?>
                                                <div class='col-sm-6 col-md-3 col-xs-6 image_div'>
                                                    <div class="fadeoverlay <?php echo $featured_img; ?>">
                                                        <img class='img-responsive' alt='' src='<?php echo $this->media_storage->getImageURL($img_value->thumb_path . $img_value->thumb_name); ?>' />


                                                        <div class="overlay3">
                                                            <a href="#" class="uploadclosebtn" data-record_id="<?php echo $img_value->id ?>"><i class="fa fa-times-circle"></i></a>
                                                            <a href="#" class="uploadcheckbtn" data-record_id="<?php echo $img_value->id ?>"><i class="fa fa-check-circle"></i></a>                    
                                                            <p class="processing">Processing...</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>

                                    </div> <!-- list-group / end -->
                                </div> <!-- row / end -->
                            </div>
                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">

                        <button type="button" class="btn btn-info edit_form pull-right" ><?php echo $this->lang->line('save'); ?></button>
                    </div>


                </div>

            </div><!--/.col (right) -->

        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
    CKEDITOR.env.isCompatible = true;
    CKEDITOR.replace('editor1');
    $(document).ready(function () {
        $('#mediaModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
        $('#mediaModal').on('show.bs.modal', function (event) {

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

        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $('.date').datepicker({
            format: date_format,
            autoclose: true
        });

    });

    $(document).on('click', '.img_div_modal', function (event) {
        $('.img_div_modal div.fadeoverlay').removeClass('active');
        $(this).closest('.img_div_modal').find('.fadeoverlay').addClass('active');


    });

    $(document).on('click', '.add_media', function (event) {
        var content_html = $('div#media_div').find('.fadeoverlay.active').find('img').data('img');
        var content = "";

        if (typeof content_html != "undefined") {
            content = '<img src="' + content_html + '">';
            InsertHTML(content);
            $('#mediaModal').modal('hide');
        }
    });

    function InsertHTML(content_html) {
        // Get the editor instance that we want to interact with.
        var editor = CKEDITOR.instances.editor1;


        // Check the active editing mode.
        if (editor.mode == 'wysiwyg')
        {
            // Insert HTML code.
            // https://docs.ckeditor.com/ckeditor4/docs/#!/api/CKEDITOR.editor-method-insertHtml
            editor.insertHtml(content_html);
        } else
            alert('You must be in WYSIWYG mode!');
    }
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



<script type="text/javascript">
//configuration
    var max_file_size = 2048576; //allowed file size. (1 MB = 1048576)
    var allowed_file_types = ['image/png', 'image/gif', 'image/jpeg', 'image/jpg']; //allowed file types
    var result_output = '#output'; //ID of an element for response output
    var my_form_id = '#upload_form'; //ID of an element for response output
    var progress_bar_id = '#progress-wrp'; //ID of an element for response output
    var total_files_allowed = 1; //Number files allowed to upload


//on form submit
    $(my_form_id).on("submit", function (event) {
        event.preventDefault();
        var error = []; //errors
        var proceed = true; //set proceed flag
        $('.myprogress').css('width', '0');
        $('.msg').text('');
        var form_data = new FormData();
        var post_url = $(this).attr("action"); //get action URL of form
        form_data.append("record_id", $('#record_id').val());
        var ins = document.getElementById('multiFiles').files.length;
        for (var x = 0; x < ins; x++) {
            form_data.append("files[]", document.getElementById('multiFiles').files[x]);
        }


        $(this.elements['files[]'].files).each(function (i, ifile) {
            if (ifile.value !== "") { //continue only if file(s) are selected

                if (allowed_file_types.indexOf(ifile.type) === -1) { //check unsupported file
                    error.push("<b>" + ifile.name + "</b> is unsupported file type!"); //push error text
                    proceed = false; //set proceed flag to false
                }


            }
        });

        if (proceed) {
            $.ajax({
                url: post_url, // point to server-side PHP script 
                dataType: 'json', // what to expect back from the PHP script
                cache: false,
                contentType: false,

                processData: false,
                data: form_data,
                type: 'post',
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            $('.myprogress').text(percentComplete + '%');
                            $('.myprogress').css('width', percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                success: function (response) {
                    if (response.status == 1) {
                        errorMsg(response.msg);
                    } else {

                        successMsg(response.msg);
                        appendImage(response.image_array);
                    }
                },
                error: function (response) {
                    $('#msg').html(response); // display error response from the PHP script
                },
                complete: function () {
                    $(my_form_id)[0].reset(); //reset form

                }

            });
        }
        $(error).each(function (i) { //output any error to output element
            errorMsg('<div class="error">' + error[i] + "</div>");
        });

    });
    function appendImage(data) {
        var image = baseurl + data.thumb_path + data.thumb_name;
        var image_date = "<div class='col-sm-6 col-md-3 col-xs-6 image_div'>";
        image_date += "<div class='fadeoverlay processing-bg'>";
        image_date += "<img class='img-responsive' alt='' src='" + image + "'>";

        image_date += "<div class='overlay3'>";
        image_date += "<a href='#' class='uploadclosebtn' data-record_id='19'><i class='fa fa-times-circle'></i></a>";
        image_date += "<a href='#' class='uploadcheckbtn' data-record_id='19'><i class='fa fa-check-circle'></i></a>";
        image_date += "<p class='processing'>Processing...</p>";
        image_date += "</div>";
        image_date += "</div>";
        image_date += "</div>";
        $(image_date).appendTo(".gallery");
    }
    $(document).on('click', '.edit_form', function () {
        $('form#eventform').submit();
    });




</script>



<script type="text/javascript">


    $(document).on('click', '.uploadcheckbtn', function () {
        var $this = $(this);
        var record_id = $(this).data('record_id');
        var id = $('#content_id').val();
        var featured_url = baseurl + "admin/front/events/enableFeatured";
        $.ajax({
            url: featured_url, // point to server-side PHP script 
            dataType: 'json', // what to expect back from the PHP script
            cache: false,
            data: {'id': id, 'record_id': record_id},
            type: 'post',
            success: function (response) {
                if (response.status === 1) {
                    errorMsg(response.msg);

                } else {
                    successMsg(response.msg);

                    $this.closest('.fadeoverlay').removeClass('processing-bg').addClass('active');

                    $this.closest('.image_div').siblings('div.image_div').children('.fadeoverlay').removeClass('active');
                }
            },
            error: function (response) {

            },
            beforeSend: function () {
                $this.closest('.fadeoverlay').addClass('processing-bg');
                $this.closest('.overlay3').removeClass('overlay3').addClass('overlay4');
            },
            complete: function () {

                $this.closest('.fadeoverlay').removeClass('processing-bg');
                $this.closest('.overlay4').addClass('overlay3').removeClass('overlay4');
            }

        });
        return false;
    });
    $(document).on('click', '.uploadclosebtn', function () {
        var $this = $(this);
        var record_id = $(this).data('record_id');
        var del_img_url = baseurl + "admin/front/events/delete_image";
        $.ajax({
            url: del_img_url, // point to server-side PHP script 
            dataType: 'json', // what to expect back from the PHP script
            cache: false,
            data: {'id': record_id},
            type: 'post',
            success: function (response) {
                if (response.status === 1) {
                    errorMsg(response.msg);

                } else {
                    successMsg(response.msg);
                    $this.closest('.image_div').remove();


                }
            },
            error: function (response) {

            },
            beforeSend: function () {
                $this.closest('.fadeoverlay').addClass('processing-bg');
                $this.closest('.overlay3').removeClass('overlay3').addClass('overlay4');
            },
            complete: function () {
                $this.closest('.fadeoverlay').removeClass('processing-bg');
                $this.closest('.overlay4').addClass('overlay3').removeClass('overlay4');

            }

        });
        return false;
    });
</script>