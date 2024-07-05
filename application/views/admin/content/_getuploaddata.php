<div class="row flex-column-sm grid_div <?php echo ($grid_view) ? "displayblock" : "displaynone" ?>">
    <div class="col-lg-12 col-md-12 col-sm-12  order-2 order-lg-1">
        <div class="row">

        <?php
        if (!empty($all_contents)) {
            foreach ($all_contents as $content_key => $content_value) {      
        ?>
        
            <div class="col-lg-4 col-sm-12 col-md-6 top_list_div" data-record-id="<?php echo $content_value->id; ?>" data-real_name="<?php echo $content_value->real_name; ?>" data-short_name="<?php echo $this->media_storage->fileview($content_value->img_name); ?>" data-type-id="<?php echo $content_value->content_type_id; ?>"  data-file-type="<?php echo $content_value->file_type; ?>"  data-name="<?php echo ($content_value->file_type == "video") ? $content_value->vid_url: $content_value->img_name; ?>"  data-path="<?php echo $content_value->dir_path; ?>">
                <article class="card card-product-list">
                    <div class="">
                        <aside class="img-wrap-fix-mobile div_image">
                        <a href="javascript:void(0);" class="img-wrap image_content">
                        <?php if ($content_value->file_type == 'xls' || $content_value->file_type == 'xlsx') {  ?>                 
                            <img class='p-2' src="<?php echo $this->media_storage->getImageURL('backend/images/excelicon.png'); ?>">
                            
                        <?php } elseif ($content_value->file_type == 'ppt' || $content_value->file_type == 'pptx') {   ?>
                        
                            <img class='p-2' src="<?php echo $this->media_storage->getImageURL('backend/images/pptxicon.png'); ?>">
                        
                        <?php } elseif ($content_value->file_type == 'doc' || $content_value->file_type == 'docx') {  ?>
                        
                            <img class='p-2' src="<?php echo $this->media_storage->getImageURL('backend/images/wordicon.png'); ?>">
                        
                        <?php } elseif ($content_value->file_type == "csv") {   ?>
                        
                            <img class='p-2' src="<?php echo $this->media_storage->getImageURL('backend/images/txticon.png'); ?>">
                            
                        <?php } elseif ($content_value->file_type == "pdf") {   ?>
                        
                            <img class='p-2' src="<?php echo $this->media_storage->getImageURL('backend/images/pdficon.png'); ?>">
                            
                        <?php } elseif ($content_value->file_type == "text/plain") {  ?>
                        
                            <img class='p-2' src="<?php echo $this->media_storage->getImageURL('backend/images/txticon.png'); ?>">
                            
                        <?php } elseif ($content_value->file_type == "zip" || $content_value->file_type == "rar") {   ?>
                        
                            <img class='p-2' src="<?php echo $this->media_storage->getImageURL('backend/images/zipicon.png'); ?>">
                            
                        <?php } elseif ($content_value->file_type == 'video' || $content_value->file_type == 'gif' || $content_value->file_type == 'jpeg' || $content_value->file_type == 'jpg' || $content_value->file_type == 'jpe' || $content_value->file_type == 'jp2' || $content_value->file_type == 'j2k' || $content_value->file_type == 'jpf' || $content_value->file_type == 'jpg2' || $content_value->file_type == 'jpx' || $content_value->file_type == 'jpm' || $content_value->file_type == 'mj2' || $content_value->file_type == 'mjp2' || $content_value->file_type == 'png' || $content_value->file_type == 'tiff' || $content_value->file_type == 'tif' ) { ?>
                        
                            <img src="<?php echo $this->media_storage->getImageURL($content_value->thumb_path . $content_value->thumb_name); ?>">
     
                        <?php } elseif ( $content_value->file_type == '3g2' || $content_value->file_type == '3gp' || $content_value->file_type == 'mp4'  || $content_value->file_type == 'm4a' || $content_value->file_type == 'f4v' || $content_value->file_type == 'flv' || $content_value->file_type == 'webm') {  ?>
                        
                            <img class='p-2' src="<?php echo $this->media_storage->getImageURL('backend/images/video-icon.png'); ?>">
                            
                        <?php }else { ?>
                        
                            <img class='p-2' src="<?php echo $this->media_storage->getImageURL('backend/images/docsicon.png'); ?>">
                            
                        <?php } ?>                        
                        </a> 
                        </aside>
                        <!-- col.// -->
                        <div class="img-wrap-fix-right content_list" >
                            <div class="content-card-body relative flex-column">
                                <div class="radio-title">
                                    <input type="checkbox" name="share_checkbox[]" data-real_name="<?php echo $content_value->real_name; ?>" value="<?php echo $content_value->id; ?>" data-name="<?php echo $content_value->img_name; ?> " class="float-end share_checkbox relative z-index-1" <?php echo make_selected($content_value->id, $selected_content) ? "checked" : "";?>>
                                    <span><?php echo ($content_value->file_type == "video") ? $content_value->vid_title : $content_value->real_name; ?></span>
                                </div>                                  <!-- price-dewrap // -->
                                <div class="price-wrap me-3">
                                    <a href="#"><?php echo $this->customlib->getStaffFullName($content_value->staff_name,$content_value->surname,$content_value->employee_id);   ?></a>
                                </div>
                                <!-- price-dewrap // -->                                
                            </div>                            
                            <div class="d-flex justify-content-between content-footer-bottom">
                                <div>
                                    <span class="price h6"> <?php echo $this->customlib->dateyyyymmddToDateTimeformat($content_value->created_at); ?> </span>
                                </div>
                                <div class="inline-anchor">
                                      <a href="<?php echo site_url('admin/content/download_content/'.$content_value->id) ?>" class="text-default download_file pr-05" data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>"><i class="fa fa-download"></i></a>
                                      <?php if($this->rbac->hasPrivilege('upload_content', 'can_delete')){ ?>
                                    <a href="#" class="text-danger delete_file" data-record-id="<?php echo $content_value->id; ?>" data-name="<?php echo ($content_value->file_type == "video") ? $content_value->vid_title : $content_value->real_name; ?>" data-toggle="modal" data-target="#single-delete"><span class="display-inline-block" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash-o"></i></span></a>
                                <?php } ?>
                                </div>
                            </div>
                            <!-- card-body .// -->
                        </div>
                        <!-- col.// -->
                    </div>
                    <!-- row.// -->
                </article>
            </div>
            <?php
            }
        } else {
        ?>
            <div class="col-12 col-sm-6 col-md-12">
                <div class="alert alert-info">
                    <?php echo $this->lang->line('no_record_found'); ?>
                </div>
            </div>
        <?php }
        ?>
        </div>
    </div>
</div>

<!-- //================list view============ -->
<div class="row list_div <?php echo (!$grid_view) ? "displayblock" : "displaynone" ?>">
<?php
    if (!empty($all_contents)) {
?>

    <div class="col-lg-12">
      <div class="table-responsive">
         <table class="table table-bordered table_contents">
            <thead>
                <tr>
                    <th width="30">#</th>
                    <th width="30"><?php echo $this->lang->line('document'); ?></th>
                    <th width="30"><?php echo $this->lang->line('content_type'); ?></th>
                    <th width="30"><?php echo $this->lang->line('size'); ?></th>
                    <th width="30"><?php echo $this->lang->line('upload_by'); ?></th>
                    <th width="30" class="pull-right"><?php echo $this->lang->line('created_on'); ?></th>    
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($all_contents as $content_key => $content_value) {
                ?>                
                <tr data-record-id="<?php echo $content_value->id; ?>" data-real_name="<?php echo $content_value->real_name; ?>" data-short_name="<?php echo $this->media_storage->fileview($content_value->img_name); ?>" data-type-id="<?php echo $content_value->content_type_id; ?>"  data-file-type="<?php echo $content_value->file_type; ?>"  data-name="<?php echo ($content_value->file_type == "video") ? $content_value->vid_url: $content_value->img_name; ?>"  data-path="<?php echo $content_value->dir_path; ?>">
                    <td><input type="checkbox" name="share_checkbox[]" data-real_name="<?php echo $content_value->real_name; ?>" value="<?php echo $content_value->id; ?>" data-name="<?php echo $content_value->img_name; ?> " class="share_checkbox_list" <?php echo make_selected($content_value->id, $selected_content) ? "checked" : "";?>>
                    <?php
                       $image="";
                    if ($content_value->file_type == 'xls' || $content_value->file_type == 'xlsx') {
                            
                        $image= $this->media_storage->getImageURL('backend/images/excelicon.png');
                    
                    } elseif ($content_value->file_type == 'ppt' || $content_value->file_type == 'pptx') {
                            
                        $image= $this->media_storage->getImageURL('backend/images/pptxicon.png');
                    
                    } elseif ($content_value->file_type == 'doc' || $content_value->file_type == 'docx') {
                            
                        $image= $this->media_storage->getImageURL('backend/images/wordicon.png');
                    
                    } elseif ($content_value->file_type == "csv") {
           
                        $image= $this->media_storage->getImageURL('backend/images/txticon.png');
                    
                    } elseif ($content_value->file_type == "pdf") {
                            
                        $image= $this->media_storage->getImageURL('backend/images/pdficon.png');
                    
                    } elseif ($content_value->file_type == "text/plain") {
                            
                        $image= $this->media_storage->getImageURL('backend/images/txticon.png');
                    
                    } elseif ($content_value->file_type == "zip" || $content_value->file_type == "rar") {
                            
                        $image= $this->media_storage->getImageURL('backend/images/zipicon.png');
                    
                    } elseif ($content_value->file_type == 'video' || $content_value->file_type == 'gif' || $content_value->file_type == 'jpeg' || $content_value->file_type == 'jpg' || $content_value->file_type == 'jpe' || $content_value->file_type == 'jp2' || $content_value->file_type == 'j2k' || $content_value->file_type == 'jpf' || $content_value->file_type == 'jpg2' || $content_value->file_type == 'jpx' || $content_value->file_type == 'jpm' || $content_value->file_type == 'mj2' || $content_value->file_type == 'mjp2' || $content_value->file_type == 'png' || $content_value->file_type == 'tiff' || $content_value->file_type == 'tif' ) {
           
                        $image= $this->media_storage->getImageURL($content_value->thumb_path . $content_value->thumb_name);

                    } elseif ( $content_value->file_type == '3g2'  || $content_value->file_type == '3gp'  || $content_value->file_type == 'mp4'  || $content_value->file_type == 'm4a'  || $content_value->file_type == 'f4v'  || $content_value->file_type == 'flv'  || $content_value->file_type == 'webm'  ) {
           
                        $image= $this->media_storage->getImageURL('backend/images/video-icon.png');

                    }else {

                }
            ?>
                    <input type="hidden" name="image_display" value="<?php echo $image; ?>">
                    </td>
                    <td>
                        <?php echo ($content_value->file_type == "video") ? "<a href=" . $content_value->vid_url . " target='_blank'>" . $content_value->vid_title . "</a>" : "<a href='javascript:void(0);'>" . $content_value->real_name . "</a>" ?>
                    </td>
                    <td><?php echo $content_value->content_type; ?></td>
                    <td><?php echo ($content_value->file_type == "video") ? $this->lang->line('n_a') : format_file_size($content_value->file_size); ?></td>
                    <td><?php echo $this->customlib->getStaffFullName($content_value->staff_name,$content_value->surname,$content_value->employee_id);  ?></td>                
                    <td  class="pull-right"><?php echo $this->customlib->dateyyyymmddToDateTimeformat($content_value->created_at); ?></td>
                </tr>
                <?php }   ?>
            </tbody>
         </table>
      </div>
   </div>
<?php
} else {
    ?>
    <div class="col-12 col-sm-6 col-md-12">
        <div class="alert alert-info">
            <?php echo $this->lang->line('no_record_found'); ?>
        </div>
    </div>
<?php
}
?>
</div>

<?php
function make_selected($find, $selected_content)
{
    if (!empty($selected_content)) {
        if (in_array($find, $selected_content)) {
            return true;
        }
    }
    return false;
}

?>