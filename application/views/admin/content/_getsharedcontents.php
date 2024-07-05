<div class="">
<div class="row">
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-12">
                <h4 class="box-title mt0 bmedium font16"><?php echo $shared_contents->title;?></h4>  
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <p><label><?php echo $this->lang->line('upload_date'); ?></label>: <?php echo $this->customlib->dateformat($shared_contents->share_date);?></p>  
            </div>
            <?php 

if(!IsNullOrEmptyString($shared_contents->valid_upto)){
?>
 <div class="col-md-4">                 
                <p><label><?php echo $this->lang->line('valid_upto'); ?></label>: <?php echo $this->customlib->dateformat($shared_contents->valid_upto);?></p>
            </div>
<?php
}
             ?>
           
               <div class="col-md-4">             
                <p><label><?php echo $this->lang->line('share_date'); ?></label>: <?php echo $this->customlib->dateformat($shared_contents->share_date);?></p>
            </div>
          
        </div>
             <div class="row">
         
            <div class="col-md-4">             
                <p><label><?php echo $this->lang->line('shared_by'); ?></label>: <?php echo $this->customlib->getStaffFullName($shared_contents->name , $shared_contents->surname,$shared_contents->employee_id);
                ?></p>
            </div>
         
            <div class="col-md-4">             
                <p><label><?php echo $this->lang->line('send_to'); ?></label>: <?php echo $this->lang->line($shared_contents->send_to);?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <label><?php echo $this->lang->line('description'); ?></label>: <?php echo $shared_contents->description;?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 ptt10">
                <h4 class="box-title bmedium mb0 font16"><?php echo $this->lang->line('attachments'); ?></h4>  
                <ul class="list-group content-share-list">                            
                                 
                <?php
                if(!empty($shared_contents->upload_contents)){
                    foreach ($shared_contents->upload_contents as $shared_content_key => $shared_content_value) {
                ?>
                    <li class="list-group-item overflow-hidden mb5">

                    <?php
                        if ($shared_content_value->file_type == 'xls' || $shared_content_value->file_type == 'xlsx') {
                    ?>
                        <img class='' src="<?php echo $this->media_storage->getImageURL('backend/images/excelicon.png'); ?>">
                    <?php
                        } elseif ($shared_content_value->file_type == 'ppt' || $shared_content_value->file_type == 'pptx') {
                    ?>
                        <img class='' src="<?php echo $this->media_storage->getImageURL('backend/images/pptxicon.png'); ?>">
                    <?php
                        } elseif ($shared_content_value->file_type == 'doc' || $shared_content_value->file_type == 'docx') {
                    ?>
                        <img class='' src="<?php echo $this->media_storage->getImageURL('backend/images/wordicon.png'); ?>">
                    <?php
                        } elseif ($shared_content_value->file_type == "csv") {
                    ?>
                        <img class='' src="<?php echo $this->media_storage->getImageURL('backend/images/txticon.png'); ?>">
                    <?php
                        } elseif ($shared_content_value->file_type == "pdf") {
                    ?>
                        <img class='' src="<?php echo $this->media_storage->getImageURL('backend/images/pdficon.png'); ?>">
                    <?php
                        } elseif ($shared_content_value->file_type == "text/plain") {
                    ?>
                        <img class='' src="<?php echo $this->media_storage->getImageURL('backend/images/txticon.png'); ?>">
                    <?php
                        } elseif ($shared_content_value->file_type == "zip" || $shared_content_value->file_type == "rar") {
                    ?>
                        <img class='' src="<?php echo $this->media_storage->getImageURL('backend/images/zipicon.png'); ?>">
                    <?php
                        } elseif ($shared_content_value->file_type == 'video' || $shared_content_value->file_type == 'gif' || $shared_content_value->file_type == 'jpeg' || $shared_content_value->file_type == 'jpg' || $shared_content_value->file_type == 'jpe' || $shared_content_value->file_type == 'jp2' || $shared_content_value->file_type == 'j2k' || $shared_content_value->file_type == 'jpf' || $shared_content_value->file_type == 'jpg2' || $shared_content_value->file_type == 'jpx' || $shared_content_value->file_type == 'jpm' || $shared_content_value->file_type == 'mj2' || $shared_content_value->file_type == 'mjp2' || $shared_content_value->file_type == 'png' || $shared_content_value->file_type == 'tiff' || $shared_content_value->file_type == 'tif' ) {
                    ?>
                        <img src="<?php echo $this->media_storage->getImageURL($shared_content_value->thumb_path . $shared_content_value->thumb_name);   ?>">
                    <?php
                        } elseif ( $shared_content_value->file_type == '3g2'  || $shared_content_value->file_type == '3gp'  || $shared_content_value->file_type == 'mp4'  || $shared_content_value->file_type == 'm4a'  || $shared_content_value->file_type == 'f4v'  || $shared_content_value->file_type == 'flv'  || $shared_content_value->file_type == 'webm'  ) {
                    ?>
                        <img class='' src="<?php echo $this->media_storage->getImageURL('backend/images/video-icon.png'); ?>">
                    <?php
                        }else {
                    ?>
                        <img class='' src="<?php echo $this->media_storage->getImageURL('backend/images/docsicon.png'); ?>">
                    <?php
                        }
                        if($shared_content_value->file_type == "video"){
                    ?>             
                        <a href="<?php echo $shared_content_value->vid_url;?>" target="_blank">
                            <?php echo  $shared_content_value->vid_title;  ?>
                        </a>   
                
                    <?php
                        }else{
                    ?>
                        <a href="<?php echo site_url('site/download_content/'.$shared_content_value->upload_content_id."/".$this->enc_lib->encrypt($shared_content_value->share_content_id))?>"> <?php echo $shared_content_value->real_name; ?> <i class="fa fa-download"></i></a>                        
                    <?php
                        }
                    ?>    
                    </li>
                <?php 
                    }
                }
                ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-3">        
        <div class="row">
            <div class="col-md-12">
            <?php
                $user_list=array();

                if (!empty($result_array)) {
                    foreach ($result_array as $result_key => $result_value) {
                    
                        if ($result_value->group_id != "") {
                        $user_list[]= intval($result_value->group_id) ? $result_value->role_name : $result_value->group_id;
                        }elseif ($result_value->staff_id != "") {
                            $staff_employee_id = '';
                            if($result_value->staff_employee_id !=''){
                                $staff_employee_id = " (".$result_value->staff_employee_id.") ";
                            }
            
                            $user_list[]= $result_value->staff_first_name ." ".$result_value->staff_surname.$staff_employee_id." (" .$result_value->staff_role_name.")";
            
                        }elseif ($result_value->user_parent_id != "") {
            
                            $user_list[]= $result_value->guardian_name ." (". $this->lang->line('guardian') .")";                    
                        }
                        elseif ($result_value->student_id != "") {
                            $user_list[]= $this->customlib->getFullName($result_value->student_first_name,$result_value->student_middle_name,$result_value->student_last_name,$sch_setting->middlename,$sch_setting->lastname) . " (" . $result_value->student_admission_on . ")";
                            
                            # code...
                        }elseif ($result_value->class_section_id != "") {
                            $user_list[]= $result_value->class." (".$result_value->section.")";
                            # code...
                        }
                    }
                }
                ?>

                <h4 class="box-title bmedium mt10 font16"><?php echo $this->lang->line('shared_groups_users'); ?></h4>
                <div class="table-responsive"> 
                    <table class="table table-hover">
                        <tbody>
                            <?php foreach ($user_list as $result_key => $result_value) { ?>
                            <tr>
                                <td>
                                    <?php echo ucfirst($result_value); ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>