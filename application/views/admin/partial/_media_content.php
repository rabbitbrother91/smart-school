<?php
foreach ($img_array as $key => $gallery_value) {
    if ($gallery_value['file_type'] == 'image/png' || $gallery_value['file_type'] == 'image/jpeg' || $gallery_value['file_type'] == 'image/jpeg' || $gallery_value['file_type'] == 'image/jpeg' || $gallery_value['file_type'] == 'image/gif') {
        $file = base_url() . $gallery_value['thumb_path'] . $gallery_value['img_name'];
    } elseif ($gallery_value['file_type'] == 'video') {
        $file = base_url() . $gallery_value['thumb_path'] . $gallery_value['img_name'];
    } elseif ($gallery_value['file_type'] == 'text/plain') {
        $file = base_url('backend/images/txticon.png');
    } elseif ($gallery_value['file_type'] == 'application/zip' || $gallery_value['file_type'] == 'application/x-rar') {
        $file = base_url('backend/images/zipicon.png');
    } elseif ($gallery_value['file_type'] == 'application/pdf') {
        $file = base_url('backend/images/pdficon.png');
    } elseif ($gallery_value['file_type'] == 'application/msword') {
        $file = base_url('backend/images/wordicon.png');
    } elseif ($gallery_value['file_type'] == 'application/vnd.ms-excel') {
        $file = base_url('backend/images/excelicon.png');
    } else {
        $file = base_url('backend/images/docicon.png');
    }
    ?>
    <div class='col-sm-6 col-md-2 col-xs-6 image_div div_record_<?php echo $gallery_value['record_id'] ?>'>
        <div class="fadeoverlay">
            <img class='img-responsive' alt='' src='<?php echo $file; ?>' />
            <div class="overlay3">
                <a href="#" class="uploadclosebtn" data-record_id="<?php echo $gallery_value['record_id'] ?>" data-toggle="modal" data-target="#confirm-delete"><i class=" fa fa-trash-o"></i></a>
                <a href="#" class="uploadcheckbtn" data-record_id="<?php echo $gallery_value['record_id'] ?>" data-toggle="modal" data-target="#detail" data-source="<?php echo base_url() . $gallery_value['dir_path'] . $gallery_value['img_name']; ?>" data-media_name="<?php echo $gallery_value['img_name'] ?>" data-media_size="<?php echo $gallery_value['file_size'] ?>" data-media_type="<?php echo $gallery_value['file_type'] ?>"><i class="fa fa-navicon"></i></a>
                <p class="processing">Processing...</p>
            </div>
        </div>
    </div>
    <?php
}
?>