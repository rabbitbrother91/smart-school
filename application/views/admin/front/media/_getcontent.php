<?php 
      if (!empty($all_contents)) {
            $check_empty = 1;
            foreach ($all_contents as $res_key => $result) {


// ========================

        $is_image = "0";
        $is_video = "0";
        if ($result->file_type == 'image/png' || $result->file_type == 'image/jpeg' || $result->file_type == 'image/jpeg' || $result->file_type == 'image/jpeg' || $result->file_type == 'image/gif') {

            $thumb_file     = base_url() . $result->thumb_path . $result->thumb_name;
            $file     = base_url() . $result->dir_path . $result->img_name;
            $file_src = base_url() . $result->dir_path . $result->img_name;
            $is_image = 1;
        } elseif ($result->file_type == 'video') {
            $thumb_file     = base_url() . $result->thumb_path . $result->thumb_name;
            $file     = base_url() . $result->thumb_path . $result->thumb_name;
            $file_src = $result->vid_url;
            $is_video = 1;
        } elseif ($result->file_type == 'text/plain') {
            $thumb_file     = base_url('backend/images/txticon.png');
            $file     = base_url('backend/images/txticon.png');
            $file_src = base_url() . $result->dir_path . $result->img_name;
        } elseif ($result->file_type == 'application/zip' || $result->file_type == 'application/x-rar') {
            $thumb_file     = base_url('backend/images/zipicon.png');
            $file     = base_url('backend/images/zipicon.png');
            $file_src = base_url() . $result->dir_path . $result->img_name;
        } elseif ($result->file_type == 'application/pdf') {
            $thumb_file     = base_url('backend/images/pdficon.png');
            $file     = base_url('backend/images/pdficon.png');
            $file_src = base_url() . $result->dir_path . $result->img_name;
        } elseif ($result->file_type == 'application/msword') {
            $thumb_file     = base_url('backend/images/wordicon.png');
            $file     = base_url('backend/images/wordicon.png');
            $file_src = base_url() . $result->dir_path . $result->img_name;
        } elseif ($result->file_type == 'application/vnd.ms-excel') {
            $thumb_file     = base_url('backend/images/excelicon.png');
            $file     = base_url('backend/images/excelicon.png');
            $file_src = base_url() . $result->dir_path . $result->img_name;
        } else {
            $thumb_file     = base_url('backend/images/docsicon.png');
            $file     = base_url('backend/images/docsicon.png');
            $file_src = base_url() . $result->dir_path . $result->img_name;
        }
//==============
        $output = '';
        $output .= "<div class='col-sm-3 col-md-2 col-xs-6 img_div_modal image_div div_record_" . $result->id . "'>";
        $output .= "<div class='fadeoverlay'>";
        $output .= "<div class='fadeheight'>";
        $output .= "<img class='' data-fid='" . $result->id . "' data-content_type='" . $result->file_type . "' data-content_name='" . $result->img_name . "' data-is_image='" . $is_image . "' data-vid_url='" . $result->vid_url . "' data-img='" . base_url() . $result->dir_path . $result->img_name . "' src='" . $thumb_file . img_time() . "'>";
        $output .= "</div>";
        if ($is_video == 1) {
            $output .= "<i class='fa fa-youtube-play videoicon'></i>";
        }
        if ($is_image == 1) {
            $output .= "<i class='fa fa-picture-o videoicon'></i>";
        }
       
            $output .= "<div class='overlay3'>";
            $output .= "<a href='#' class='uploadcheckbtn' data-record_id='" . $result->id . "' data-toggle='modal' data-target='#detail' data-thumb_image='" . $thumb_file . img_time() ."' data-image='" . $file .img_time() ."' data-source='" . $file_src. "' data-media_name='" . $this->media_storage->fileview($result->img_name) . "' data-media_size='" . $result->file_size . "' data-media_type='" . $result->file_type . "'><i class='fa fa-navicon'></i></a>";
            $output .= "<a href='#' class='uploadclosebtn' data-record_id='" . $result->id . "' data-toggle='modal' data-target='#confirm-delete'><i class=' fa fa-trash-o'></i></a>";
            $output .= "<p class='processing'>" . $this->lang->line('processing') . "</p>";
            $output .= "</div>";
       
        if ($is_video == 1) {
            $output .= "<p class=''>" . $result->vid_title . "</p>";
        } else {
            $output .= "<p class=''>" .  $this->media_storage->fileview($result->img_name) . "</p>";
        }
        $output .= "</div>";
        $output .= "</div>";

        echo $output;

// ========================

            }
        }else{
            ?>
<div class="alert alert-info">
    <?php echo $this->lang->line('no_record_found'); ?>
</div>
            <?php
        }

 ?>