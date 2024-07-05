<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>backend/js/ckeditor_config.js"></script>
<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/adapters/jquery.js"></script>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form class = "post-list">
                            <input type = "hidden" value = "" />
                        </form>
                        <form role="form" action="<?php echo site_url('admin/onlineexam/assign/' . $id) ?>" method="post" class="row mb10" id="search_form">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="onlineexam_id" value="<?php echo $onlineexam->id; ?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('question'); ?>:</label>
                                        <select  id="question_id" name="question_id" class="form-control" autocomplete="off">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($onlineexam_questions as $onlineexam_question_key => $onlineexam_question_value) {
    ?>
                                                <option value="<?php echo $onlineexam_question_value->id; ?>">Q. <?php echo readmorelink($onlineexam_question_value->question); ?></option>
                                                <?php
}
?>
                                        </select>
                                        <span class="text-danger"></span>
                                    </div>
                                </div><!--./col-md-12-->
                            </div><!--./row-->
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('per_page') ?>: </label>
                                    <select class="form-control post_max" name="post_max">
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div><!--./col-lg-4-->
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('class'); ?></label>
                                    <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
foreach ($classlist as $class) {
    ?>
                                            <option value="<?php echo $class['id'] ?>" <?php
if (set_value('class_id') == $class['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $class['class'] ?></option>
                                                    <?php
}
?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label>
                                    <select  id="section_id" name="section_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm post_search_submit"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </form>
                        <div class = "wave-box-wrapper relative">
                            <div class="quesoverlay">
                                <div class="cv-spinner">
                                    <span class="spinner"></span>
                                </div>
                            </div>
                            <div class = "pagination-container question_pagination_content"></div>
                            <div class = "pagination-nav"></div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<script type="text/javascript">
    var app = {
        Posts: function () {

            /**
             * This method contains the list of functions that needs to be loaded
             * when the "Posts" object is instantiated.
             *
             */
            this.init = function () {
                this.get_items_pagination();
            }

            /**
             * Load items pagination.
             */
            this.get_items_pagination = function () {

                _this = this;

                /* Check if our hidden form input is not empty, meaning it's not the first time viewing the page. */
                if ($('form.post-list input').val()) {
                    /* Submit hidden form input value to load previous page number */
                    data = JSON.parse($('form.post-list input').val());
                    _this.ajax_get_items_pagination(data.page);
                } else {
                    /* Load first page */
                    _this.ajax_get_items_pagination(1);
                }

                var th_active = $('.table-post-list th.active');
                var th_name = $(th_active).attr('id');
                var th_sort = $(th_active).hasClass('DESC') ? 'DESC' : 'ASC';

                /* Search */
                $(document).on('submit', '#search_form', function (e) {
                    e.preventDefault(); // avoid to execute the actual submit of the form.
                    _this.ajax_get_items_pagination(1);
                });
                /* Search when Enter Key is triggered */
                $(".post_search_text").keyup(function (e) {
                    if (e.keyCode == 13) {
                        _this.ajax_get_items_pagination(1);
                    }
                });

                /* Pagination Clicks   */
                $(document).on('click', '.pagination-nav li.active_v', function () {
                    var page = $(this).attr('p');
                    var current_sort = $(th_active).hasClass('DESC') ? 'DESC' : 'ASC';
                    _this.ajax_get_items_pagination(page, th_name, current_sort);
                });
            }

            /**
             * AJAX items pagination.
             */
            this.ajax_get_items_pagination = function (page) {

                if ($(".pagination-container").length) {
                    var post_data = {};
                    post_data['page'] = page;
                    $.each($('#search_form').serializeArray(), function (i, field) {
                        post_data[field.name] = field.value;
                    });

                    $('form.post-list input').val(JSON.stringify(post_data));
                    var data = {
                        action: base_url + "onlineexam/getDescQues",
                        data: JSON.parse($('form.post-list input').val())
                    };

                    $.ajax({
                        url: base_url + "admin/onlineexam/getDescQues",
                        type: 'POST',
                        data: data,
                        beforeSend: function () {
                            $('.quesoverlay').css("display","block");
                        },
                        success: function (response) {
                            response = JSON.parse(response);
                          $(".pagination-container").html("").html(response.content);
                          $('[class*="remark"]').ckeditor(
                            {
                                 toolbar: 'Evalution',
                                 allowedContent : true,
                                 extraPlugins: 'ckeditor_wiris,wordcount,notification',
                                 enterMode : CKEDITOR.ENTER_BR,
                                 shiftEnterMode: CKEDITOR.ENTER_P,
                                 customConfig: baseurl+'/backend/js/ckeditor_config.js'
                                }
                            );

                           $('.pagination-nav').html(response.navigation);
                              $('body,html').animate({
                                    scrollTop: 0
                                }, 600);

                        },
                        error: function (xhr) { // if error occured
                            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                              $('.quesoverlay').css("display","none");

                        },
                        complete: function () {
                          $('.quesoverlay').css("display","none");
                        }
                    });
                }
            }
        }
    }

    /**
     * When the document has been loaded...
     *
     */
    jQuery(document).ready(function () {
        posts = new app.Posts(); /* Instantiate the Posts Class */
        posts.init(); /* Load Posts class methods */

    });
</script>

<script type="text/javascript">
    $(document).on('change', '#class_id', function (e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });

    function getSectionByClass(class_id, section_id) {
        if (class_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }

    $(document).on('submit', '.mark_fill_form', function (e) {
        e.preventDefault();
        var form = $(this);
        var submit_button = $(this).find(':submit');
        var formdata = form.serializeArray();

        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: formdata,
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function () {
                submit_button.button('loading');
            },
            success: function (response)
            {
                if (!response.status) {
                    var message = "";
                    $.each(response.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(response.message);
                }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                submit_button.button('reset');
            },
            complete: function () {
                submit_button.button('reset');
            }
        });

    });
</script>