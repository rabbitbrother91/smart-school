<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<style type="text/css">
    .select2-container--open {z-index: 9001;}
</style>
<div class="content-wrapper">
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('video_tutorial_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php  if ($this->rbac->hasPrivilege('video_tutorial', 'can_add')) { ?>
                            <button type="button" class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-backdrop="static" data-target="#myModal"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?></button>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                <form role="form" action="<?php echo site_url('admin/video_tutorial/searchvalidation') ?>" method="post" class="class_search_form">
                                    <div class="col-md-6">
                                        <div class="row">
                                          <div class="col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('class'); ?></label><small class="req"> </small>
                                                <select autofocus="" id="search_class_id" name="search_class_id" class="form-control search_class_id" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                        foreach ($classlist as $class) {
                                                    ?>
                                                            <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) {
                                                                echo "selected=selected";
                                                            }
                                                            ?>><?php echo $class['class'] ?></option>
                                                    <?php
                                                        }
                                                    ?>
                                                </select>
                                                <span class="text-danger" id="error_search_class_id"></span>
                                            </div>
                                        </div>    
                                        <div class="col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('section'); ?></label><small class="req"> </small>

                                                <select  id="search_section_id" name="search_section_id" class="form-control search_section_id">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                </select>
                                                <span class="text-danger" id="error_search_section_id"></span>
                                            </div> 
                                        </div> 
                                      </div>     
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="control-label"><?php echo $this->lang->line('search_by_title'); ?></label>
                                            <input type="text" name="search_text" id="search_text" class="form-control search_text" value="<?php echo set_value('search_text'); ?>"  placeholder="<?php echo $this->lang->line('search_by_title'); ?>">
                                        </div>

                                            <div class="form-group">
                                                <button type="submit" name="search_full" value="search_full" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button><br>
                                            </div>                                        
                                    </div>
                                </form>
                                </div>
                            </div>
                                <div class="mediarow">   
                                    <div class="" id="media_div"></div>                                    
                                </div>
                                <div align="right" id="pagination_link"></div>
                            </div>
                            <div id='no_record_found' class="alert alert-info hide"> </div>
                        </div>
                    </div><!-- /.box-body -->  
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
                <h4 class="box-title"><?php echo $this->lang->line('add_video_tutorial'); ?></h4>
            </div>
            <form id="addvideotutorial" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?></label> <small class="req"> *</small>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                                foreach ($classlist as $class) {
                                            ?>
                                                    <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) {
                                                        echo "selected=selected";
                                                    }
                                                    ?>><?php echo $class['class'] ?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                        <span class="text-danger" id="error_class_id"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group select2-container-3">
                                        <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select id="section_id" name="section_id[]" class="form-control  section-list select2" multiple="multiple">

                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger" id="error_section_id"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                        <input autofocus="" id="title" name="title" placeholder="" type="text" class="form-control"  value="<?php echo set_value('title'); ?>" />
                                        <span class="text-danger"><?php echo form_error('title'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('video_link'); ?></label><small class="req"> *</small>
                                        <input autofocus="" id="video_url" name="video_link" placeholder="" type="text" class="form-control"  value="<?php echo set_value('video_link'); ?>" />
                                        <span class="text-danger"><?php echo form_error('video_link'); ?></span>
                                    </div>
                                </div>
                            </div>                           
                            <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group">
                                        <label><?php echo $this->lang->line('description'); ?></label>
                                        <textarea class="form-control" id="description" name="description" placeholder="" rows="3"><?php echo set_value('description'); ?></textarea>
                                        <span class="text-danger"><?php echo form_error('description'); ?></span>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="paddA10">
                            <button type="submit" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>
                        </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editvideotutorialmodal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('edit_video_tutorial'); ?></h4>
            </div>
            <form id="editvideotutorialform" method="post" class="ptt10" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0 ">
                    <div id="videotutorialdata"></div>
                </div>
                <div class="box-footer">
                    <div class="paddA10">
                        <button type="submit" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content fullshadow">
            <button type="button" class="ukclose" data-dismiss="modal">&times;</button>
            <div class="smcomment-header">
                <div class="row">
                    <div class="col-md-8 col-sm-8 popup_image">

                    </div>
                    <div class="col-md-4 col-sm-4 smcomment-title scroll-area-half">
                        <dl class="mediaDL">
                            <dt><?php echo $this->lang->line('class'); ?></dt>
                            <dd id="modal_class"></dd>
                            <dt><?php echo $this->lang->line('section'); ?></dt>
                            <dd id="modal_sectionlist"></dd>
                            <dt><?php echo $this->lang->line('title'); ?></dt>
                            <dd id="modal_title"></dd>
                            <dt><?php echo $this->lang->line('description'); ?></dt>
                            <dd id="modal_description"></dd>
                            <dt><?php echo $this->lang->line('created_by'); ?></dt>
                            <dd id="modal_created_by"></dd>                           
                            
                        </dl>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <input type="hidden" id="record_id" name="record_id" value="0">
            <div class="modal-header">
                <h4 class="modal-title del_modal_title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body del_modal_body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <a class="btn btn_delete btn-danger" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('loading');?>"><?php echo $this->lang->line('delete'); ?></a>
            </div>
        </div>
    </div>
</div>

<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2({
    allowClear: true});
  })
</script>
<script>
        $('#myModal').on('hidden.bs.modal', function(e) {  
          $('.section-list option:not(:first)').remove();               
           reset_form('#addvideotutorial');
        });

$("#addvideotutorial").on('submit', (function (e) {    
    e.preventDefault();

    var $this = $(this).find("button[type=submit]:focus");

    $.ajax({
        url: "<?php echo site_url("admin/video_tutorial/add") ?>",
        type: "POST",
        data: new FormData(this),
        dataType: 'json',
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $this.button('loading');

        },
        success: function (res)
        {
            if (res.status == "fail") {
                var message = "";
                $.each(res.error, function (index, value) {
                    message += value;
                });
                
                errorMsg(message);

            } else {

                successMsg(res.message);
                window.location.reload(true);
            }
        },
        error: function (xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            $this.button('reset');
        },
        complete: function () {
            $this.button('reset');
        }
    });
}));

$(document).on('click', '.uploadclosebtn', function(event) {
    var videotutorialid = $(this).attr('data-id');
   $.ajax({
       url:'<?php echo site_url("admin/video_tutorial/get"); ?>',
       type:'post',
       data:{videotutorialid:videotutorialid},
       dataType:'json',
       success:function(response){        
            $('#videotutorialdata').html(response.page);
            var class_id = $('#edit_class_id').val();
            getallsection(class_id, videotutorialid);
       }
   });
})

$("#editvideotutorialform").on('submit', (function (e) {
    e.preventDefault();
    var $this = $(this).find("button[type=submit]:focus");
    $.ajax({
        url: "<?php echo site_url("admin/video_tutorial/edit") ?>",
        type: "POST",
        data: new FormData(this),
        dataType: 'json',
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $this.button('loading');

        },
        success: function (res)
        {
            if (res.status == "fail") {
                var message = "";
                $.each(res.error, function (index, value) {
                    message += value;
                });
                errorMsg(message);

            } else {

                successMsg(res.message);
                window.location.reload(true);
            }
        },
        error: function (xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            $this.button('reset');
        },
        complete: function () {
            $this.button('reset');
        }
    });
}));

    function getSectionByClass(class_id, section_id) {
        $('.section-list option:not(:first)').remove();   
    if (class_id != "") {
        $('#section_id').html("");
        $('#edit_section_id').html("");
        $('#search_section_id').html("");
        var base_url = '<?php echo base_url() ?>';    
        var div_data = '';
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "GET",
            url: base_url + "sections/getByClass",
            data: {'class_id': class_id},
            dataType: "json",
            beforeSend: function () {
        
            $('.section-list').val([]).trigger('change');
        },
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    var sel = "";
                    if (section_id == obj.id) {
                        sel = "selected";
                    }
                    div_data += "<option value=" + obj.id + " " + sel + ">" + obj.section + "</option>";
                });
                $('#section_id').append(div_data);
                $('#edit_section_id').append(div_data);
                $('#search_section_id').append(div_data);
            }
        });
    }
}

$(document).ready(function () {
    var class_id = $('#class_id').val();
    var section_id = '<?php echo set_value('edit_section_id') ?>';
    getSectionByClass(class_id, section_id);
    
    $(document).on('change', '#class_id', function (e) {
        var class_id = $('#class_id').val();
        getSectionByClass(class_id,'')
    });

    $(document).on('change', '#edit_class_id', function (e) {
        var class_id = $('#edit_class_id').val();
        getSectionByClass(class_id,'');
    });

    $(document).on('change', '#search_class_id', function (e) {
        var class_id = $('#search_class_id').val();
        getSectionByClass(class_id,'')
    });

});
</script>
<script>
var page = '1';

$(document).ready(function () {
    load(1,'');
    $(document).on("click", ".pagination li a", function (event) {
        event.preventDefault();
        page = $(this).data("ci-pagination-page");
        class_id = $('#search_class_id').val();
        search_section_id = $('#search_section_id').val();
        load(page, class_id, search_section_id);
    });

    $('#confirm-delete').on('show.bs.modal', function (e) {
        var record_id = $(e.relatedTarget).data('record_id');
        $('#record_id').val(0).val(record_id);
        $('.del_modal_title').html("<?php echo $this->lang->line('delete_confirm'); ?>");
        $('.del_modal_body').html('<p><?php echo $this->lang->line('are_you_sure_want_to_delete'); ?></p>');
    });

});

function load(page, class_id, class_section_id) {
    $('#search_class_id').val(class_id);
    getSectionByClass(class_id, class_section_id);

    $("#no_record_found").addClass("hide"); 
    var keyword = $('.search_text').val(); 

    $.ajax({
        url: "<?php echo base_url(); ?>admin/video_tutorial/getPage/" + page,
        method: "GET",
        data: {'keyword': keyword,class_id:class_id,class_section_id:class_section_id},
        dataType: "json",
        beforeSend: function () {
            $('#media_div').empty();
        },

        success: function (data)
        {
            $('#media_div').empty();
            if (data.result_status === 1) {
                $.each(data.result, function (index, value) {                 
                    $("#media_div").append(data.result[index]);                     
                });

                $('#pagination_link').html(data.pagination_link);

            } else {
                $("#no_record_found").html("<?php echo $this->lang->line('no_record_found'); ?>"); 
                $("#no_record_found").removeClass("hide"); 
            }
        },
        complete: function () {
        }
    });
}

$(document).on('click', '.btn_delete', function () {
    var $this = $('.btn_delete');
    var record_id = $('#record_id').val();
    
    $.ajax({
        url: "<?php echo site_url('admin/video_tutorial/delete') ?>",
        type: "POST",
        data: {'record_id': record_id},
        dataType: 'Json',
        beforeSend: function () {
            $this.button("<?php echo $this->lang->line('loading');?>");
        },
        success: function (data, textStatus, jqXHR)
        {
            if (data.status === 1) {
                successMsg(data.msg);
                load(1);
            } else {
                errorMsg(data.msg);
            }
            $("#confirm-delete").modal('hide');
            
        },

        complete: function () {
            $this.button('reset');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {

        }
    });
});

$('#detail').on('show.bs.modal', function (e) {
    var data = $(e.relatedTarget).data();
    var media_content_path = "<a href='" + data.source + "' target='_blank'>" + data.source + "</a>"; 
    $('#modal_class').text("").text(data.class);
    $('#modal_title').text("").text(data.title);
    $('#modal_description').text("").text(data.description);
    $('#modal_sectionlist').text("").text(data.sectionlist);
    $('#modal_created_by').text("").text(data.role_name);
    updateMediaDetailPopup(data.media_type, data.source, data.image);
});

function updateMediaDetailPopup(media_type, url, thumb_path) {
    var youtubeID = YouTubeGetID(url);
    content_popup = '<object data="https://www.youtube.com/embed/' + youtubeID + '" width="100%" height="400"></object>';
    $('.popup_image').html("").html(content_popup);
}

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

$("form.class_search_form button[type=submit]").click(function() {
    $("button[type=submit]", $(this).parents("form")).removeAttr("clicked");
    $(this).attr("clicked", "true");
});

$(document).on('submit','.class_search_form',function(e){
   e.preventDefault(); // avoid to execute the actual submit of the form.

    var $this = $("button[type=submit][clicked=true]");
    var form = $(this);
    var url = form.attr('action');
    var form_data = form.serializeArray();
    form_data.push({name: 'search_type', value: $this.attr('value')});
    $.ajax({
           url: url,
           type: "POST",
           dataType:'JSON',
           data: form_data, // serializes the form's elements.
              beforeSend: function () {
                $('[id^=error]').html("");
                $this.button('loading');
                resetFields($this.attr('value'));
               },
              success: function(response) { // your success handler

                if(!response.status){
                    $.each(response.error, function(key, value) {
                    $('#error_' + key).html(value);
                    });
                }else{
                    load(page,response.params.class_id,response.params.class_section_id);
                }
              },
             error: function() { // your error handler
                 $this.button('reset');
             },
             complete: function() {
             $this.button('reset');
             }
         });

});

function resetFields(search_type){
    if(search_type == "search_full"){
        $('#search_class_id').prop('selectedIndex',0);
        $('#search_section_id').find('option').not(':first').remove();
    }else if (search_type == "search_filter") {

         $('#search_text').val("");
    }
}

function getallsection(class_id, videotutorialid) {
if (class_id != "") {
    $('#section_id').html("");
    $('#edit_section_id').html("");
    $('#search_section_id').html("");
     $('#edit_section_id').val([]);
    var base_url = '<?php echo base_url() ?>';
    var div_data = ' ';
    // var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
    $.ajax({
        type: "post",
        url: base_url + "admin/video_tutorial/getsection",
        data: {'class_id': class_id,videotutorialid:videotutorialid},
        dataType: "json",
        success: function (data) {
            var selectedValues = [];
  
  

            $.each(data.sectionlist, function (i, obj)
            {
                var selected = "";
                $.each(data.multipalsection, function (index, value)
                {
                    if (value == obj.id) {
                      selectedValues.push(obj.id);
                    }
                });

                div_data += "<option value=" + obj.id + " " + selected + ">" + obj.section + "</option>";
            });
            $('#edit_section_id').append(div_data);

             $('#edit_section_id').val(selectedValues).change();;
        }
    });
}
}

$('.ukclose').click(function(){   
    $('.popup_image').html('');
})
</script>