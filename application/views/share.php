<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#424242" />
        <title><?php echo $this->setting_model->get_appname(); ?></title>
        <!--favican-->
        <link href="<?php echo base_url(); ?>uploads/school_content/admin_small_logo/<?php $this->setting_model->getAdminsmalllogo();?>" rel="shortcut icon" type="image/x-icon">
        <!-- CSS -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/css/form-elements.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/css/style.css">
        <style type="text/css">
            body{ background: #f6f6f6; /*background:linear-gradient(to right,#676767 0,#dadada 100%); text-align: left;*/}
            .pt0{padding-top: 0;}
            .pb0{padding-bottom: 0;}
            .mb0{margin-bottom: 0;}
            .form-bottom b{color: #000; font-weight: 500;}
            .form-bottom p{font-size: 14px;}
            .list-group-item:hover{
              background-color: #f5f4f4bf;
            }

        </style>
    </head>
    <body>
        <!-- Top content -->
        <div class="top-content2">
            <div class="inner-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-offset-2 col-sm-8 form-box">
                            <div class="shadow_bg">
                                <div>
                                    <div class="form-top">
                                        <div class="form-top-left logowidth">
                                            <img src="<?php echo base_url(); ?>uploads/school_content/admin_logo/<?php echo $this->setting_model->getAdminlogo();?>" class="logowidth">
                                        </div>
                                    </div>

                                    <div class="form-bottom pt0 pb0">
                                        <?php

if (isset($share_data->share_date) && strtotime(date('Y-m-d')) >= strtotime($share_data->share_date) && (IsNullOrEmptyString($share_data->valid_upto) || (!IsNullOrEmptyString($share_data->valid_upto) && strtotime(date('Y-m-d')) <= strtotime($share_data->valid_upto)))) {

    ?>
                                <h1 class="font-white bolds mb0"><?php echo $share_data->title; ?></h1>
                                <div class="row ptt10 pb10">
                                    <div class="col-md-4 col-lg-4 col-sm-12">
                                <p><b><?php echo $this->lang->line('upload_date'); ?></b> : <?php echo $this->customlib->dateformat($share_data->share_date); ?></p>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-12">
                                <p><b><?php echo $this->lang->line('valid_upto'); ?></b> : <?php echo $this->customlib->dateformat($share_data->valid_upto); ?></p>

                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-12">

                                <p><b><?php echo $this->lang->line('shared_by'); ?></b> : <?php echo $this->customlib->getStaffFullName($share_data->name, $share_data->surname, $share_data->employee_id);
    ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">


                                                        <?php

    if (!empty($share_data->upload_contents)) {
        ?>

                <ul class="list-group checked-list-box">
<?php

        foreach ($share_data->upload_contents as $content_key => $content_value) {

            ?>
 <li class="list-group-item" data-style="button" style="cursor: pointer;">

             <?php
if ($content_value->file_type == 'xls' || $content_value->file_type == 'xlsx') {
                ?>
     <img class='p-2' src="<?php echo base_url('backend/images/excelicon.png'); ?>">
<?php
} elseif ($content_value->file_type == 'ppt' || $content_value->file_type == 'pptx') {
                ?>
     <img class='p-2' src="<?php echo base_url('backend/images/pptxicon.png'); ?>">
<?php
} elseif ($content_value->file_type == 'doc' || $content_value->file_type == 'docx') {
                ?>
     <img class='p-2' src="<?php echo base_url('backend/images/wordicon.png'); ?>">
<?php
} elseif ($content_value->file_type == "csv") {
                ?>
     <img class='p-2' src="<?php echo base_url('backend/images/txticon.png'); ?>">
<?php
} elseif ($content_value->file_type == "pdf") {
                ?>
     <img class='p-2' src="<?php echo base_url('backend/images/pdficon.png'); ?>">
<?php
} elseif ($content_value->file_type == "text/plain") {
                ?>
     <img class='p-2' src="<?php echo base_url('backend/images/txticon.png'); ?>">
<?php
} elseif ($content_value->file_type == "zip" || $content_value->file_type == "rar") {
                ?>
     <img class='p-2' src="<?php echo base_url('backend/images/zipicon.png'); ?>">
<?php
} elseif ($content_value->file_type == 'video' || $content_value->file_type == 'gif' || $content_value->file_type == 'jpeg' || $content_value->file_type == 'jpg' || $content_value->file_type == 'jpe' || $content_value->file_type == 'jp2' || $content_value->file_type == 'j2k' || $content_value->file_type == 'jpf' || $content_value->file_type == 'jpg2' || $content_value->file_type == 'jpx' || $content_value->file_type == 'jpm' || $content_value->file_type == 'mj2' || $content_value->file_type == 'mjp2' || $content_value->file_type == 'png' || $content_value->file_type == 'tiff' || $content_value->file_type == 'tif') {
                ?>
     <img src="<?php echo base_url($content_value->thumb_path . $content_value->thumb_name); ?>">
<?php
} elseif ($content_value->file_type == '3g2' || $content_value->file_type == '3gp' || $content_value->file_type == 'mp4' || $content_value->file_type == 'm4a' || $content_value->file_type == 'f4v' || $content_value->file_type == 'flv' || $content_value->file_type == 'webm') {
                ?>
   <img class='p-2' src="<?php echo base_url('backend/images/video-icon.png'); ?>">
<?php
} else {
                ?>
<img class='p-2' src="<?php echo base_url('backend/images/docsicon.png'); ?>">
<?php
}
            if ($content_value->file_type == "video") {

                ?>

          <a href="<?php echo $content_value->vid_url; ?>" target="_blank">
             <?php echo $content_value->vid_title; ?>
          </a>

      <?php
} else {
                ?>
          <a title="<?php echo $this->lang->line('download'); ?>" href="<?php echo site_url('site/download_content/' . $content_value->upload_content_id . "/" . $this->enc_lib->encrypt($content_value->share_content_id)) ?>">
             <?php echo $this->media_storage->fileview($content_value->img_name); ?> <i class="fa fa-download"></i>
          </a>

      <?php
}
            ?>
</li>
<?php
}
        ?>
                </ul>
<?php
} else {
        ?>
<div class="alert alert-info">
   <?php echo $this->lang->line('no_record_found'); ?>
</div>
                                                          <?php
}

    ?>
                                    </div>
                        </div>
<?php
} else {
    ?>
   <h1 class="font-white bolds mb0"><?php echo $this->lang->line('this_link_is_invalid_or_expired'); ?>  </h1>
                                          <?php

}

?>

                                </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Javascript -->
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery-1.11.1.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery.backstretch.min.js"></script>
    </body>
</html>