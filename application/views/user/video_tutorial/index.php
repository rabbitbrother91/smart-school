<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('video_tutorial_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="mediarow over overflow-hidden pb10">
                        <div class="" id="media_div"></div>
                    </div>
                    <div align="right" id="pagination_link" class="pr-1"></div>
                    <div class="box-header ptbnull"><div id='no_record_found' class="alert alert-info hide"> </div></div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content fullshadow">
            <button type="button" class="ukclose" data-dismiss="modal">&times;</button>
            <div class="smcomment-header">
                <div class="row">
                    <div class="col-md-8 col-sm-8 popup_image">

                    </div>
                    <div class="col-md-4 col-sm-4 smcomment-title">
                        <dl class="mediaDL">
                            <dt><?php echo $this->lang->line('title'); ?></dt>
                            <dd id="modal_title"></dd>
                            <dt><?php echo $this->lang->line('description'); ?></dt>
                            <dd id="modal_description"></dd>
                            <dt><?php echo $this->lang->line('created_by'); ?></dt>
                            <dd id="modal_role_name"></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var page = '1';
$(document).ready(function () {
    load(page);
    $(document).on("click", ".pagination li a", function (event) {
        event.preventDefault();
        page = $(this).data("ci-pagination-page");
        load(page);
    });
});

function load(page) {
    var class_id = '<?php echo $class_id; ?>';
    var section_id = '<?php echo $section_id; ?>';
    $("#no_record_found").addClass("hide");
    $.ajax({
        url: "<?php echo base_url(); ?>user/video_tutorial/getPage/" + page,
        method: "GET",
        data: {'class_id': class_id,section_id:section_id},
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

$('#detail').on('show.bs.modal', function (e) {
    var data = $(e.relatedTarget).data();
    var media_content_path = "<a href='" + data.source + "' target='_blank'>" + data.source + "</a>";
    $('#modal_title').text("").text(data.title);
    $('#modal_description').text("").text(data.description);
    $('#modal_role_name').text("").text(data.role_name);
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

$('.ukclose').click(function(){
    $('.popup_image').html('');
})
</script>