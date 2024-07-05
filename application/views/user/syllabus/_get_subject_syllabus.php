<style type="text/css">
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 5px 0;}
    </style>
    <div class="col-lg-12" id="transfee">
<div class="row">
 <div class="col-lg-6 col-md-6 col-sm-6">
    <div class="d-flex justify-content-between">
    <h4 class="box-title bmedium mb0" id="title"> <?php echo $this->lang->line('lesson_plan'); ?></h4>

    <?php if (!empty($result)) {
    ?>
            <ul class="classlist">
                <li><a class="" data-original-title="<?php echo $this->lang->line('print') ?>" data-toggle="tooltip" id="print" onclick="printDiv()" ><i class="fa fa-print"></i></a></li>
                <li> <a data-original-title="<?php echo $this->lang->line('download_excel') ?>" data-toggle="tooltip" class="" id="btnExport" onclick="fnExcelReport();"> <i class="fa fa-file-excel-o"></i> </a>
                </li>
                <?php if ($result['attachment'] != '') {
        ?>
                    <li><a data-original-title="<?php echo $this->lang->line('download_attachment') ?>" data-toggle="tooltip" href="<?php echo base_url() ?>user/syllabus/download/<?php echo $result['id'] ?>"><i class="fa fa-file-text-o"></i></a></li>
                    <?php
}
    if ($result['lacture_youtube_url'] != '') {
        ?>
                    <li><a data-original-title="<?php echo $this->lang->line('youtube_link') ?>" data-toggle="tooltip" onclick="run_video('<?php echo $result['lacture_youtube_url'] ?>')" ><i class="fa fa-youtube"></i></a></li>
                <?php
}
    if ($result['lacture_video'] != '') {
        ?>
                    <li><a data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('download_video') ?>" href="<?php echo base_url() ?>user/syllabus/lacture_video_download/<?php echo $result['id'] ?>"><i class="fa fa-file-video-o"></i></a></li>
    <?php }?>

            </ul>
       </div>
        <div class="divider"></div>
        <div class="scroll-area">
         <div class="table-responsive overflow-visible">
            <table class="table table-bordered pt15 mb0" id="headerTable">
                <tr class="hide" id="visible">
                    <td colspan="2"><b><?php echo $this->lang->line('lesson_plan'); ?></b></td>
                </tr>
                <tr>
                    <th class="border0"><?php echo $this->lang->line('class') ?></th>
                    <td class="border0"><?php echo $result['cname'] . "(" . $result['sname'] . ")"; ?></td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('subject') ?></th>
                    <td><?php
echo $result['subname'];
    if ($result['scode'] != '') {
        echo " (" . $result['scode'] . ")";
    }
    ?></td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('date') ?></th>
                    <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($result['date'])); ?> <?php echo $result['time_from'] . " " . $this->lang->line('to') . " " . $result['time_to'] ?></td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('lesson'); ?></th>
                    <td><?php echo $result['lessonname'] ?></td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('topic') ?></th>
                    <td><?php echo $result['topic_name'] ?></td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('sub_topic'); ?></th>
                    <td><?php echo $result['sub_topic'] ?></td>
                </tr>
                <tr>
                    <td colspan=2><b><?php echo $this->lang->line('general_objectives') ?></b><br><?php echo $result['general_objectives'] ?></td>
                </tr>
                <tr>
                    <td colspan=2><b><?php echo $this->lang->line('teaching_method') ?></b><br><?php echo $result['teaching_method'] ?></td>
                </tr>
                <tr>
                    <td colspan=2><b><?php echo $this->lang->line('previous_knowledge') ?></b><br><?php echo $result['previous_knowledge'] ?></td>
                </tr>
                <tr>
                    <td colspan=2><b><?php echo $this->lang->line('comprehensive_questions') ?></b><br><?php echo $result['comprehensive_questions'] ?></td>
                </tr>
                <tr>
                    <td colspan=2><b><?php echo $this->lang->line('presentation') ?></b><br><?php echo $result['presentation']; ?></td>
                </tr>
            </table>
        </div>
      </div>
    </div><!--./col-lg-12-->
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="pl-2">
            <h4 class="box-title bmedium"><?php echo $this->lang->line('comments'); ?></h4>
            <div class="divider mt0 mb0"></div>
               <form id="formadd" method="post" class="ptt10 mb10 place-italic" enctype="multipart/form-data">
                    <input type="hidden" name="subject_syllabus_id" value="<?php echo $result['id']; ?>">
                    <div class="clearfix">
                         <div class="d-flex justify-content-between gap-1">
                            <textarea cols="78" rows="2" placeholder="<?php echo $this->lang->line('type_your_comment'); ?>" name="message" class="form-control resize-auto"></textarea>

                        <button type="submit" class="btn btn-info overflow-inherit" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('send') ?></button>
                        </div>
                    </div>
                </form>
                <div class="scroll-area">
                    <ul class="user-progress">
                        <div id="messagedetails"></div>
                    </ul>
                </div>
            </div>
        </div><!--./classtopic-->
<?php } else {
    ?>
        <div class="classtopic"><br>
            <div class="alert alert-danger"><?php echo $this->lang->line('no_record_found') ?></div>
        </div>
<?php }
?>
</div>
</div>
</div>

<script>
$(document).ready(function(){
    var subject_syllabus_id = '<?php echo $result['id']; ?>';
    getmessage(subject_syllabus_id);
});

$("#formadd").on('submit', (function (e) {
    e.preventDefault();

    var $this = $(this).find("button[type=submit]:focus");
    $.ajax({
        url: "<?php echo site_url("user/syllabus/addmessage"); ?>",
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
                $('#formadd')[0].reset();
                getmessage('<?php echo $result['id']; ?>');
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

function getmessage(subject_syllabus_id){
    $('#messagedetails').html('');
    $.ajax({
        url: "<?php echo site_url("user/syllabus/getmessage"); ?>",
        type: "POST",
        data: {subject_syllabus_id:subject_syllabus_id},
        dataType: 'json',
        success: function (res)
        {
            if (res.status == "success") {
                $('#messagedetails').html(res.page);
            }else{
               $('#messagedetails').html('');
            }
        }
    });
}

function deletemessage(fourm_id){
    if(confirm('<?php echo $this->lang->line('are_you_sure_want_to_delete'); ?>')){
    $.ajax({
        url: "<?php echo site_url("user/syllabus/deletemessage"); ?>",
        type: "POST",
        data: {fourm_id:fourm_id},
        success: function (data)
        {
            getmessage('<?php echo $result['id']; ?>');
        }
    });
    }
}
</script>