<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>backend/js/ckeditor_config.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<form id="syllabus_form" method="post" class="ptt10" enctype="multipart/form-data" method="post" accept-charset="utf-8">
    <div class="modal-body pt0 pb0">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo ('lesson --r'); ?></label><small class="req"> *</small>
                            <select  id="editlessonid" name="lesson_id" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                            </select>
                            <input type="hidden" id="editcreated_for" name="created_for">
                            <span class="section_id_error text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo ('topic --r'); ?></label><small class="req"> *</small>
                            <select  id="edittopicid" name="topic_id" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                            </select>
                            <span class="section_id_error text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="pwd"><?php echo ('date --r'); ?></label><small class="req"> *</small>
                            <input type="text" id="editdate" name="date" class="form-control date" >
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="pwd"><?php echo ('time_from --r'); ?></label><small class="req"> *</small>
                            <div class="input-group ">
                                <input class="form-control time" name="time_from" id="edittime_from" type="text" value="">
                                <span class="input-group-addon" id="basic-addon2">
                                    <i class="fa fa-clock-o"></i>
                                </span>
                                </input>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="pwd"><?php echo ('time_to --r'); ?></label><small class="req"> *</small>
                            <div class="input-group "><input type="text" id="edittime_to" name="time_to" class="form-control time">
                                <span class="input-group-addon" id="basic-addon2">
                                    <i class="fa fa-clock-o"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="pwd"><?php echo ('sub_topic --r'); ?></label>
                            <textarea type="text" id="editsub_topic" name="sub_topic" class="form-control " ></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="pwd"><?php echo ('teaching_method --r'); ?></label>
                            <textarea type="text" id="editteaching_method" name="teaching_method" class="form-control " ></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="pwd"><?php echo ('general_objectives --r'); ?></label>
                            <textarea type="text" id="editgeneral_objectives" name="general_objectives" class="form-control " ></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="pwd"><?php echo ('previous_knowledge --r'); ?></label>
                            <textarea type="text" id="editprevious_knowledge" name="previous_knowledge" class="form-control " ></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="pwd"><?php echo ('comprehensive_questions --r'); ?>  </label>
                            <textarea type="text" id="editcomprehensive_questions" name="comprehensive_questions" class="form-control " ></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo ('presentation --r'); ?></label><small class="req"> *</small>
                            <button class="btn btn-primary pull-right btn-xs" type="button" id="question" data-toggle="modal" data-location="question" data-target="#myimgModal"><i class="fa fa-plus"></i> Add Image --r</button>
                            <textarea name="presentation" id="question_textbox1"  class="form-control ckeditor" >
                            </textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="pwd"><?php echo $this->lang->line('attachment'); ?></label>
                            <input type="file" id="file"  name="file" class="form-control filestyle">
                        </div>
                    </div>
                </div><!--./row-->
            </div><!--./col-md-12-->
        </div><!--./row-->
    </div>
    <div class="box-footer">
        <div class="pull-right paddA10">
            <button type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please wait"><?php echo $this->lang->line('save') ?></button>
        </div>
    </div>
</form>

<script>

    $(".ckeditor").each(function (_, ckeditor) {
        CKEDITOR.replace(ckeditor, {
            toolbar: 'Ques',
            customConfig: baseurl + '/backend/js/ckeditor_config.js'
        });
    });

    $(document).ready(function () {
        var target_textbox = "";
        $(document).on('click', '#question', function () {
            getImages(1);
        });
    });

    function getImages(page, query = "") {
        $.ajax({
            type: "POST",
            url: baseurl + 'admin/question/getimages',
            data: {page: page, query: query},
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function () {
                $('.loading-overlay').css("display", "block");
            },
            success: function (data)
            {
                $('label.total').html("").html("Total Record --r :" + data.count).css("display", "block");

                $('.imgModal-body #media_div').html("").html(data.page);
                $('.imgModal-body #pagination').html("").html(data.pagination);
                $('.loading-overlay').css("display", "none");
            },
            error: function (xhr) { // if error occured

                alert("Error occured.please try again");
                $('.loading-overlay').css("display", "none");
            },
            complete: function () {
                $('.loading-overlay').css("display", "none");
            }
        });
    }

    $(document).on('click', '.img_div_modal', function (event) {
        $('.img_div_modal div.fadeoverlay').removeClass('active');
        $(this).closest('.img_div_modal').find('.fadeoverlay').addClass('active');
    });

    $(document).on('click', '.add_media', function (event) {

        var content_html = $('div#media_div').find('.fadeoverlay.active').find('img').data('img');
        var is_image = $('div#media_div').find('.fadeoverlay.active').find('img').data('is_image');
        var content_name = $('div#media_div').find('.fadeoverlay.active').find('img').data('content_name');
        var content_type = $('div#media_div').find('.fadeoverlay.active').find('img').data('content_type');
        var vid_url = $('div#media_div').find('.fadeoverlay.active').find('img').data('vid_url');
        var content = "";

        if (typeof content_html !== "undefined") {
            if (is_image === 1) {
                content = '<img src="' + content_html + '">';
            }
            InsertHTML(content);
            $('#myimgModal').modal('hide');
        }
    });

    function InsertHTML(content_html) {
        var aaa = target_textbox + "_textbox";
        // Get the editor instance that we want to interact with.
        var editor = CKEDITOR.instances[aaa];
        console.log(editor);

        // Check the active editing mode.
        if (editor.mode == 'wysiwyg')
        {
            editor.insertHtml(content_html);
        } else
            alert('You must be in WYSIWYG mode!');
    }

    $('#myimgModal').on('shown.bs.modal', function (event) {
        button = $(event.relatedTarget);
        target_textbox = button.data('location');
        console.log(target_textbox);
    })

    $('.modal').on("hidden.bs.modal", function (e) { //fire on closing modal box

        if ($('.modal:visible').length) { // check whether parent modal is opend after child modal close
            $('body').addClass('modal-open'); // if open mean length is 1 then add a bootstrap css class to body of the page
        }
    });

    function CKupdate() {
        for (instance in CKEDITOR.instances) { 
            CKEDITOR.instances[instance].setData('');
        }
    }

    $(document).on('keyup', '#search_box', function (event) {
        var query = $('#search_box').val();
        getImages(1, query);
    });

    $(document).on('click', '.page-link', function () {
        var page = $(this).data('page_number');
        var query = $('#search_box').val();
        getImages(page, query);
    });

</script>