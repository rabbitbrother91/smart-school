<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('content_list'); ?></h3>
                        <?php if ($this->rbac->hasPrivilege('upload_content', 'can_add')) {?>
                            <div class="box-tools pull-right">
                        <button type="button"  class="btn btn-sm btn-primary" autocomplete="off" data-toggle="modal" data-target="#addModal"><i class="fa fa-cloud-upload"></i> <?php echo $this->lang->line('upload'); ?></button>
                        </div><!-- /.box-tools -->
                        <?php }?>
                    </div><!-- /.box-header -->
                    <div class="box-body">
       <div class="row pb20">
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-8">
            <form method="POST" action="#" id="searchform">
                <div class="input-group input-group-sm">
                    <input type="text" name="table_search" class="form-control pull-right post_search_text"  placeholder="<?php echo $this->lang->line('search'); ?>">
                    <div class="input-group-btn">
                    <button type="submit" class="btn btn-default  post_search_submit"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-4 col-xs-4 pt5">
            <div class="pull-right">
                <div class="btn-group">
                    <button class="btn btn-default btn-sm" title="<?php echo $this->lang->line('list_view'); ?>" id="list">
                      <span class="fa fa-1-2x fa-th-list"></span>
                    </button>
                    <button class="btn btn-default btn-sm" title="<?php echo $this->lang->line('card_view'); ?>" id="grid">
                      <span class="fa fa-1-2x fa-th"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <form class = "post-list">
        <input type = "hidden" value = "" />
    </form>
 <div style="position: relative; min-height: 300px;">
     <!-- <div id="div_fade"></div> -->
     <div class="modal_loader_div" style="display: none;"></div>
   <!--   <div id="div_modal">
        <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>
        <img id="loader" src="<?php //echo base_url('backend/images/chatloading.gif'); ?>">
    </div> -->
    <div class="row">
       <div class="col-md-9">
                                <div class= "pagination-container"></div>
        <div class= "pagination-nav"></div>
                           </div>
                            <div class="col-md-3">
    <div class="documents_sidebar">
        <div class="d-preview text-center p-2">
            <div class="d-preview-position">
                <img src="<?php echo $this->media_storage->getImageURL('backend/images/upload-file.png'); ?>"
                <?php if ($this->rbac->hasPrivilege('upload_content', 'can_add')) {?> class="add_image" <?php }?>>

            </div>
        </div>
        <div class="documents_sidebar_info p-2">
<div class="sidear-info">
          <table class="table no-border">
                <tbody>
                    <tr>
                        <td><?php echo $this->lang->line('total_documents'); ?></td>
                        <td class="total_files"><?php echo $count->number; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line('size'); ?></td>
                        <td class="total_size"><?php echo format_file_size($count->file_size); ?></td>
                    </tr>
                </tbody>
           </table>
</div>
<div class="sidebar-form-content displaynone">
            <div class="btn-group">
              <button type="button" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('share'); ?>" class="btn btn-sm btn-default btn-white" id="share_files"><i class="fa fa-share-alt"></i></button>
              <button type="button"  data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('generate_url'); ?>" class="btn btn-sm btn-default btn-white" id="share_url"><i class="fa fa-globe"></i></button>
            </div>
            <?php if ($this->rbac->hasPrivilege('upload_content', 'can_delete')) {?>
              <button href="#" class="btn btn-sm btn-default btn-white pull-right side_delete_btn"  data-toggle="modal" data-target="#confirm-delete" data-original-title="<?php echo $this->lang->line('delete'); ?>" data-toggle="tooltip"><i class="fa fa-trash"></i></button>
            <?php }?>
            <form class="pt20" action="<?php echo site_url('admin/content/ajaxupdate'); ?>" method="POST" id="side_form">
                <input type="hidden" name="id" value="">
              <div class="form-group row">
                <label class="control-label col-sm-4 col-xs-4"><?php echo $this->lang->line('file_name'); ?> <small class="req"> *</small></label>
                <div class="col-sm-8 col-xs-8">
                  <input type="text" class="form-control" value="" name="name">
                </div>
              </div><!--./form-group-->
               <div class="form-group row">
                <label class="control-label col-sm-4 col-xs-4"><?php echo $this->lang->line('content_type'); ?> <small class="req"> *</small></label>
                <div class="col-sm-8 col-xs-8">
                <select  id="content_type" name="content_type" class="form-control" >
                 <option value=""><?php echo $this->lang->line('select'); ?></option>
                  <?php
foreach ($content_types as $content_type_key => $content_type_value) {
    ?>
                        <option value="<?php echo $content_type_value->id; ?>" ><?php echo $content_type_value->name; ?></option>
                     <?php
}
?>
                </select>
                </div>
              </div><!--./form-group-->
              <div class="clearfix"></div>
                <button type="submit" class="btn btn-sm btn-primary pull-right"><?php echo $this->lang->line('save'); ?></button>
                  <div class="clearfix"></div>
            </form>
</div>
        </div>
    </div>
                           </div>
                        </div>
 </div>
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<div id="addModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="fileupload" action="<?php echo site_url('admin/content/ajaxupload'); ?>" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close_btn" data-dismiss="modal">×</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('upload'); ?> </h4>
                </div>
                <div class="modal-body">
         <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                   <div class="form-group">
                        <label for="content_type"><?php echo $this->lang->line('content_type'); ?> <small class="req"> *</small></label>
                           <select  id="content_type" name="content_type" class="form-control" >
                 <option value=""><?php echo $this->lang->line('select'); ?></option>
                  <?php
foreach ($content_types as $content_type_key => $content_type_value) {
    ?>
                        <option value="<?php echo $content_type_value->id; ?>" ><?php echo $content_type_value->name; ?></option>
                     <?php
}
?>
                           </select>
                      </div>
            </div>
         </div>

<div class="row">
       <div class="col-xs-12 col-sm-4 col-md-4 col-lg-5">
                   <div class="form-group">
                             <label><?php echo $this->lang->line('upload_your_file'); ?></label>
                               <div class="files">
                                <input type="file" name="upload[]"  class="filestyle form-control" data-height="26"  id="file">
                                    </div>
                                </div>
             </div>
        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
            <div class="text-center">
                <label class="displayblock opacity d-sm-none">&nbsp;</label>
                <div class="orline"><span>or</span></div>
            </div>
        </div>     
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-5">
                               <div class="form-group">
                             <label><?php echo $this->lang->line('upload_youtube_video_link'); ?></label>
                                <input type="text" name="url" class="form-control" id="url">
                                </div>
    </div>
</div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="load" data-loading-text="<i class='fa fa-spinner fa-spin'></i> <?php echo $this->lang->line('saving'); ?>"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="shareURLModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="shareurl" action="<?php echo site_url('admin/content/generate_url'); ?>" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('generate_url'); ?></h4>
                </div>
                <div class="modal-body minheight199">

                  <div class="share_link_div displaynone">
                    <a class="share_link" href="#" target="_blank"></a>
  <span class="btn btn-xs btn-info" onclick="copylink()" id="basic-addon1" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('copy'); ?>"><i class="fa fa-copy"></i></span>
</div>


<!-- <div class="input-group share_link_div displaynone">
  <input type="text" class="form-control share_link"><span class="btn btn-xs btn-info input-group-addon" onclick="copylink()" id="basic-addon1" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('copy'); ?>"><i class="fa fa-copy"></i></span>
</div> -->
                    <div class="from_content">
 <div class="row">
    <div class="col-md-12">
       <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                            <input id="title" name="title" placeholder="" type="text" class="form-control">
                                            <span class="text-danger"></span>
                                        </div>
    </div>

 </div>
  <div class="row">
    <div class="col-md-6">
       <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('share_date'); ?></label><small class="req"> *</small>
                                            <input id="share_date" name="share_date" placeholder="" type="text" class="form-control date">
                                            <span class="text-danger"></span>
                                        </div>
    </div>
        <div class="col-md-6">
       <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('valid_upto'); ?></label>
                                            <input id="valid_upto" name="valid_upto" placeholder="" type="text" class="form-control date">
                                            <span class="text-danger"></span>
                                        </div>
    </div>
 </div>
  <button type="submit" class="btn btn-primary" id="load" data-loading-text="<i class='fa fa-spinner fa-spin'></i> <?php echo $this->lang->line('saving'); ?>"><?php echo $this->lang->line('generate_url'); ?></button>
 </div>
 <div class="url_content_list">

<h4><?php echo $this->lang->line('selected_document'); ?></h4>
  <div class="content_list_uploaded">
                                        </div>
 </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="shareModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="share_form" action="<?php echo site_url('admin/content/share'); ?>" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close share_close_btn" data-dismiss="modal">×</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('share_selected'); ?></h4>
                </div>
                <div class="modal-body">
          <div class="row">
              <div class="col-md-8 ">
                  <form action="/action_page.php" >
 <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                <input autofocus="" id="title" name="title" placeholder="" type="text" class="form-control" value="" autocomplete="off">
             <span class="text-danger"></span>
 </div>
 <div class="row">
    <div class="col-md-6">
       <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('share_date'); ?></label><small class="req"> *</small>
                                            <input id="share_date" name="share_date" placeholder="" type="text" class="form-control date">
                                            <span class="text-danger"></span>
                                        </div>
    </div>
        <div class="col-md-6">
       <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('valid_upto'); ?></label>
                                            <input id="upload_date" name="valid_upto" placeholder="" type="text" class="form-control date">
                                            <span class="text-danger"></span>
                                        </div>
    </div>
 </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                            <textarea class="form-control" id="description" name="description" placeholder="" rows="3" autocomplete="off"></textarea>
                                            <span class="text-danger"></span>
                                        </div>
                                        <h4><?php echo $this->lang->line('selected_document'); ?></h4>
                                        <div class="content_list_uploaded">
                                        </div>
</form>
              </div>
              <div class="col-md-4">
                <div class="nav-tabs-radio" role="tablist">
  <div class="form-group">
     <label for="input-type"><?php echo $this->lang->line('send_to'); ?> <small class="req"> *</small></label>
     <div id="input-type" class="row">
        <div class="col-md-4">
                    <label class="radio-inline">
      <input value="group"  name="send_to" type="radio" data-target="#share_group" checked="checked" /><?php echo $this->lang->line('group'); ?>
    </label>
        </div>
               <div class="col-md-4">
    <label class="radio-inline">
     <input  value="class"  name="send_to" type="radio" data-target="#share_class" /><?php echo $this->lang->line('class'); ?>
    </label>
        </div>
        <div class="col-md-4">
    <label class="radio-inline">
       <input value="individual"  name="send_to" type="radio" data-target="#share_individual" /><?php echo $this->lang->line('individual'); ?>
    </label>
        </div>
</div>
</div>
</div>
<!-- //============== -->
<div class="tab-content">
    <div id="share_group" class="tab-pane active">
   <div class="form-group">
                                                <div class="well minheight303">
                                                    <div class="checkbox mt0">
                                                        <label><input type="checkbox" name="user[]" value="student"> <b><?php echo $this->lang->line('students'); ?></b> </label>
                                                    </div>
                                                    <?php
if ($sch_setting->guardian_name) {?>
                                                    <div class="checkbox">
                                                        <label><input type="checkbox" name="user[]" value="parent"> <b><?php echo $this->lang->line('guardians'); ?></b></label>
                                                    </div>
                                                    <?php }
?>
                                                    <?php
foreach ($roles as $role_key => $role_value) {
    if ($role_value["name"] != 'Super Admin' || $superadmin_restriction != 'disabled') {?>

                                                        <div class="checkbox">
                                                            <label><input type="checkbox" name="user[]" value="<?php echo $role_value['id']; ?>"> <b><?php echo $role_value['name']; ?></b></label>
                                                        </div>
                                                        <?php
}
}
?>
                                                </div>
                                            </div>
    </div>
    <div id="share_individual" class="tab-pane">
               <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-btn bs-dropdown-to-select-group">
                                                        <button type="button" class="btn btn-default btn-searchsm dropdown-toggle as-is bs-dropdown-to-select" data-toggle="dropdown">
                                                            <span data-bind="bs-drp-sel-label"><?php echo $this->lang->line('select'); ?></span>
                                                            <input type="hidden" name="selected_value" data-bind="bs-drp-sel-value" value="">
                                                            <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu" role="menu" style="">
                                                            <li data-value="student"><a href="#" ><?php echo $this->lang->line('students'); ?></a></li>
                                                            <?php
if ($sch_setting->guardian_name) {
    ?>
                                                                <li data-value="parent"><a href="#"><?php echo $this->lang->line('guardians'); ?></a></li>
                                                            <li data-value="student_guardian"><a href="#" ><?php echo $this->lang->line('students_guardians'); ?></a></li>
                                                                <?php
}
?>
                                                            <?php
foreach ($roles as $role_key => $role_value) {

    if ($role_value["name"] != 'Super Admin' || $superadmin_restriction != 'disabled') {?>

                                                                <li data-value="staff"><a href="#"><?php echo $role_value['name']; ?></a></li>
                                                                <?php
}
}
?>
                                                        </ul>
                                                    </div>
                                                    <input type="text" value="" data-record="" data-email="" data-mobileno="" class="form-control" autocomplete="off" name="text" id="search-query">

                                                    <div id="suggesstion-box"></div>
                                                    <span class="input-group-btn">
                                                        <button  class="btn btn-primary btn-searchsm add-btn" type="button"><?php echo $this->lang->line('add') ?></button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="dual-list list-right">
                                                <div class="well minheight260">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="input-group">
                                                                <input type="text" name="SearchDualList" class="form-control" placeholder="<?php echo $this->lang->line('search') ?>..." />
                                                                <div class="input-group-btn"><span class="btn btn-default input-group-addon bright"><i class="fa fa-search"></i></span></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="">
                                                        <ul class="list-group send_list">
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
    </div>
    <div id="share_class" class="tab-pane">
                                                <div class="form-group">
                                                    <select  id="class_id" name="class_id" class="form-control"  >
                                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                        <?php
foreach ($classlist as $class) {
    ?>
                                                            <option value="<?php echo $class['id'] ?>"<?php
if (set_value('class_id') == $class['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $class['class'] ?></option>
                                                                    <?php
}
?>
                                                    </select>
                                                </div>
                                            <div class="dual-list list-right">
                                                <div class="well minheight260">
                                                    <div class="wellscroll">
                                                        <b><?php echo $this->lang->line('section'); ?></b> <small class="req"> *</small>
                                                        <ul class="list-group section_list listcheckbox">

                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
    </div>
</div>

<!-- //============= -->
              </div>
          </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="load" data-loading-text="<i class='fa fa-spinner fa-spin'></i> <?php echo $this->lang->line('saving'); ?>"><?php echo $this->lang->line('send'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>

  <div class="modal fade" id="single-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirm_delete'); ?></h4>
                </div>
                <div class="modal-body">
                    <p><?php echo $this->lang->line('you_are_about_to_delete'); ?> <b><i class="title"></i></b> ,<?php echo $this->lang->line('this_procedure_is_irreversible_do_you_want_to_proceed'); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-ok" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('delete'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirm_delete'); ?></h4>
                </div>
                <div class="modal-body">
                    <p><?php echo $this->lang->line('you_are_about_to_delete'); ?></p>
                    <div class="delete_files_list">
                    </div>
                    <p><?php echo $this->lang->line('this_procedure_is_irreversible_do_you_want_to_proceed'); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-ok" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('delete'); ?></button>
                </div>
            </div>
        </div>
    </div>

<div id="viewModel" class="modal fade bg-transparent-alpha" role="dialog">
  <div class="modal-dialog full-width mt0">
    <div class="modal-content m-0 bg-transparent modal-body-scroll">
        <div class="modal-gradient">
          <div class="modal-header p0 border0">
            <div class="d-flex pdficon">
                <a href="#" data-dismiss="modal"><i class="fa fa-arrow-left"></i></a>
                <span class="text-white text-nowrap2 model_file_name"></span>
            </div>
            <a href="#" class="pdfdownload-icon"><i class="fa fa-download"></i></a>
            <button type="button" class="popupclose" data-dismiss="modal">&times;</button>
          </div>
        </div>
        <div class="h-50"></div>
            <div class="modal-body p0 w-75 mx-auto text-center w-sm-100 ">
            </div>
        </div>
    </div>
  </div>

<script type="text/javascript">
    let _grid_view = 1;
    var selected_data = [];
    var selected_sidebar_data = [];
    var branch_base_url="<?php echo $branch_url; ?>";
    var share_by_side_panel = false;
        $(document).on('click','.share_checkbox,.share_checkbox_list',function(){

        share_by_side_panel = false;
        if($(this).is(':checked')) {
         $('.pagination-container').find('input.share_checkbox[value='+$(this).val()+']').prop('checked',true);
         $('.pagination-container').find('input.share_checkbox_list[value='+$(this).val()+']').prop('checked',true);
            checkbox_selected('add',$(this).val(),$(this).data('real_name'));
        } else {
         $('.pagination-container').find('input.share_checkbox[value='+$(this).val()+']').prop('checked',false);
         $('.pagination-container').find('input.share_checkbox_list[value='+$(this).val()+']').prop('checked',false);
            checkbox_selected('remove',$(this).val());
        }

        var total_selected_check = selected_data.length;
        if(total_selected_check <= 0){

         $('.share_btn').prop("disabled", true);
              var img = $('<img />', {
                    id: 'image',
                    width: 'auto',
                    height: 'auto',
                    class: 'add_image',
                    src: baseurl+'backend/images/upload-file.png',
                });
            $('.documents_sidebar').find('div.d-preview').html(img);

            $('.sidear-info').css("display", "block");
            $('.sidebar-form-content').css("display", "none");

          }else if(total_selected_check > 0){

            $('.sidear-info').css("display", "none");
            $('.sidebar-form-content').css("display", "block");
            $('.sidebar-form-content').find('form').css("display", "none");

             $('.share_btn').prop("disabled", false);
                if(total_selected_check > 0){
                   var img = $('<img />', {
                    id: 'image',
                    width: 'auto',
                    height: 'auto',
                    class: '',
                    src: baseurl+'backend/images/txticon.png',
                });
                let div_count=$('<div />', {
                    class: 'd-preview-count',

                }).append($('<i>',{'class':'fa fa-plus'})).append(" "+total_selected_check.toString().padStart(2, '0')+ " "+files_selected+" ");

                   let div_tttt=$('<div />', {
                    class: 'd-preview-position',

                }).append(img).append(div_count);

            $('.documents_sidebar').find('div.d-preview').html(div_tttt);
             }else{
               
             }
          }
        });

        function checkbox_selected(action,recid,filename=""){
            if(action =="add"){
                 selected_data.push({
                     id: recid,
                     filename:  filename
                 });

            }else if(action =="remove"){

            $.each(selected_data, function(i, el){
              if (this.id == recid){
                selected_data.splice(i, 1);
              }
            });

            }
        }

    $(document).ready(function (e) {

        $('#shareModal,#addModal,#confirm-delete,#shareURLModal,#viewModel').modal({
            backdrop: 'static',
            keyboard: false,
            show:false
        });

        $(document).on('click', '.bs-dropdown-to-select-group .dropdown-menu li', function (event) {
            var $target = $(event.currentTarget);
            $target.closest('.bs-dropdown-to-select-group')
                    .find('[data-bind="bs-drp-sel-value"]').val($target.attr('data-value'))
                    .end()
                    .children('.dropdown-toggle').dropdown('toggle');
            $target.closest('.bs-dropdown-to-select-group')
                    .find('[data-bind="bs-drp-sel-label"]').text($target.context.textContent);
            return false;
        });
    });

 $('input[name="send_to"]').click(function () {
      $(this).tab('show');
      $(this).removeClass('active');
  });

     $(function () {
        $('[name="SearchDualList"]').keyup(function (e) {
            var code = e.keyCode || e.which;
            if (code == '9')
                return;
            if (code == '27')
                $(this).val(null);
            var $rows = $(this).closest('.dual-list').find('.list-group li');
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
            $rows.show().filter(function () {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
    });

    $(document).on('click','#share_files',function(){
        form_content_list();
        $('#shareModal').modal('show');
    });

     $(document).on('click','#share_url',function(){
        form_content_list();
        $('#shareURLModal').modal('show');
    });

    function form_content_list(){
          let new_selected=[];
          var sub_ul = $('<ul/>',{
            'class':'list-group content-share-list'
          });
              if(share_by_side_panel){
                     new_selected=selected_sidebar_data;
              }else{
                     new_selected=selected_data;
              }
            $.each(new_selected, function (key, input) {

                var sub_li = $('<li/>',{'class':'list-group-item overflow-hidden'}).append($('<a>').attr('href','javascript:void(0)') .text(input.filename))
                                .addClass('ui-menu-item')
                                .attr('role', 'menuitem')
                                .appendTo(sub_ul);
             });

            $(".content_list_uploaded").html(sub_ul);
    }

$(document).on('submit','form#side_form',function(e) {
    e.preventDefault();
    var form = $(this);
    var url = form.attr('action');
    var $this = form.find("button[type=submit]:focus");
    $this.button('loading');
            $.ajax({
                    url: url,
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend: function () {
                    $this.button('loading');
                    },
                    success: function (data)
                    {
                           if (!data.status) {
                                var message = "";
                                $.each(data.error, function (index, value) {
                                message += value;
                                });
                                errorMsg(message);
                                } else {
                                successMsg(data.msg);
                           posts.init();
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
    });

$(document).on('submit','form#fileupload',function(e) {
    e.preventDefault();
    var form = $(this);
    var url = form.attr('action');
    var $this = form.find("button[type=submit]:focus");
    $this.button('loading');
    $.ajax({
            url: url,
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function () {
            $this.button('loading');
            },
            success: function (data)
            {
              if (!data.status) {
                   var message = "";
                   $.each(data.error, function (index, value) {
                   message += value;
                   });
                   errorMsg(message);
                   } else {

                    $('.total_files').html(data.file_count);
                    $('.total_size').html(data.file_size);

                   successMsg(data.msg);
                    posts.init();
                   $('#addModal').modal('hide');
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
    });

$(document).on('submit','form#shareurl',function(e) {
    e.preventDefault();
    var form = $(this);

        var formData = new FormData();
        var other_data = $(this).serializeArray();
        var user_list = (!jQuery.isEmptyObject(attr)) ? JSON.stringify(attr) : "";
        $.each(other_data, function (key, input) {
            formData.append(input.name, input.value);
        });

              if(share_by_side_panel){
                      new_selected=selected_sidebar_data;
               }else{
                      new_selected=selected_data;
               }

                $.each(new_selected, function (key, input) {

                 formData.append('selected_contents[]', input.id);
                 });

    var url = form.attr('action');
    var $this = form.find("button[type=submit]:focus");
    $this.button('loading');
    $.ajax({
            url: url,
            type: "POST",
            data: formData,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function () {
            $this.button('loading');
            },
            success: function (data)
            {
             if (!data.status) {
                  var message = "";
                  $.each(data.error, function (index, value) {
                  message += value;
                  });
                  errorMsg(message);
                  } else {

                  reset_share_form();
                  successMsg(data.msg);

                  $('.from_content').fadeOut(400);
                  $('.share_link').attr('href',data.shared_url).text(data.shared_url);
                  $('.share_link_div').removeClass('displaynone');
                  $('.url_content_list').html("");
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
    });

     $('#shareURLModal').on('hidden.bs.modal', function(e) {
                 $('.from_content').css("display", "block");
                 $('.share_link_div').addClass('displaynone');
                 $('.share_link').attr('href',"").text("");
                  reset_form('#shareurl');
        });

     $('#addModal').on('hidden.bs.modal', function(e) {
                  reset_form('#fileupload');
                  $('#fileupload').find(".dropify-clear").trigger('click');
        });

$(document).on('click','.add_image',function(){
      $('#addModal').modal('show');
});

$(document).on('submit','form#share_form',function(e) {
            e.preventDefault();
            var form = $(this);
            let new_selected=[];

                var formData = new FormData();
                var other_data = $(this).serializeArray();
                var user_list = (!jQuery.isEmptyObject(attr)) ? JSON.stringify(attr) : "";

                $.each(other_data, function (key, input) {
                    formData.append(input.name, input.value);
                });

                formData.append('user_list', user_list);

                  if(share_by_side_panel){
                         new_selected=selected_sidebar_data;
                  }else{
                         new_selected=selected_data;
                  }

                $.each(new_selected, function (key, input) {

                 formData.append('selected_contents[]', input.id);
                 });

            var url = form.attr('action');
            var $this = form.find("button[type=submit]:focus");
            $this.button('loading');
            $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend: function () {
                    $this.button('loading');
                    },
                    success: function (data)
                    {
                     if (!data.status) {
                          var message = "";
                          $.each(data.error, function (index, value) {
                          message += value;
                          });
                          errorMsg(message);
                          } else {

                          reset_share_form();
                          successMsg(data.msg);

                          $('#shareModal').modal('hide');
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
    });

       function reset_share_form(_sidebar_request = false){
        $('.share_btn').prop("disabled", true);
        reset_form('#share_form');
        $('.nav-tabs-radio input[data-target="#share_group"]').tab('show');
        $("input:radio[value='group']").prop('checked',true);
        $('ul.send_list').html("");
        $('ul.section_list').html("");
        selected_sidebar_data=[];
        share_by_side_panel = false;
        selected_data=[];
        $('.pagination-container').find('input.share_checkbox').prop('checked',false);
        $('.pagination-container').find('input.share_checkbox_list').prop('checked',false);

                $('.sidear-info').css("display", "block");
                $('.sidebar-form-content').css("display", "none");
                var img = $('<img />', {
                    id: 'image',
                    width: 'auto',
                    height: 'auto',
                    class: 'add_image',
                    src: branch_base_url+'backend/images/upload-file.png',

                });
                 $('.documents_sidebar').find('div.d-preview').html(img);

        }

        function clearcheckbox(){
          selected_data=[];
          $('.pagination-container').find('input.share_checkbox').prop('checked',false);
          $('.pagination-container').find('input.share_checkbox_list').prop('checked',false);
        }

    </script>
    <script type="text/javascript">
        /**
 * App Class
 *
 * @author      Carl Victor Fontanos
 * @author_url  www.carlofontanos.com
 *
 */

/**
 * Setup a App namespace to prevent JS conflicts.
 */
var app = {

    Posts: function() {

        /**
         * This method contains the list of functions that needs to be loaded
         * when the "Posts" object is instantiated.
         *
         */
        this.init = function() {
            this.get_items_pagination();
        }

        /**
         * Load items pagination.
         */
        this.get_items_pagination = function() {

            _this = this;

            /* Check if our hidden form input is not empty, meaning it's not the first time viewing the page. */
            if($('form.post-list input').val()){
                /* Submit hidden form input value to load previous page number */
                data = JSON.parse($('form.post-list input').val());
                _this.ajax_get_items_pagination(data.page);
            } else {
                /* Load first page */
                _this.ajax_get_items_pagination(1, 'name');
            }

            /* Search */
            $(document).on('submit', 'form#searchform', function(e){
                 e.preventDefault(); // avoid to execute the actual submit of the form.
                _this.ajax_get_items_pagination(1);
            });

            $(document).on('click', '.pagination-nav .pagination li.unactive', function(){
                var page = $(this).attr('p');
                _this.ajax_get_items_pagination(page);
            });
        }

        /**
         * AJAX items pagination.
         */
        this.ajax_get_items_pagination = function(page){
            if($(".pagination-container").length){

                var post_data = {
                    page: page,
                    search: $('.post_search_text').val(),
                    grid:_grid_view,
                };

                $('form.post-list input').val(JSON.stringify(post_data));
                var data = {
                    data: JSON.parse($('form.post-list input').val()),

                };

  $.each(selected_data, function (key, input) {
         data['selected_content['+key+']']=input.id;
         });

                $.ajax({
                    url: baseurl+'admin/content/getuploaddata',
                    type: 'POST',
                    data: data,
                    dataType:'JSON',
                     beforeSend: function() {
                        $('.modal_loader_div').css("display", "block");
                       
                      },
                    success: function (response) {
                        $(".pagination-container").html(response.content);
                        $('.pagination-nav').html(response.navigation);
                        $('.modal_loader_div').fadeOut(400);
                       
                    },
                      error: function(xhr) { // if error occured
                       alert("<?php echo $this->lang->line('error_occured_please_try_again'); ?>");
                        $('.modal_loader_div').fadeOut(400);
                       
                     },
                     complete: function() {
                         $('.modal_loader_div').fadeOut(400);
                        
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
jQuery(document).ready( function () {
    posts = new app.Posts(); /* Instantiate the Posts Class */
    posts.init(); /* Load Posts class methods */

});
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#list').click(function(event){
                event.preventDefault();
                _grid_view=0;
                $('.list_div').addClass('displayblock').removeClass('displaynone');
                $('.grid_div').addClass('displaynone').removeClass('displayblock');
            });
            $('#grid').click(function(event){
                event.preventDefault();
                _grid_view=1;
                 $('.list_div').addClass('displaynone').removeClass('displayblock');
                $('.grid_div').addClass('displayblock').removeClass('displaynone');
            });
        });
    </script>

    <script type="text/javascript">
      var files_selected = "<?php echo $this->lang->line('files_selected'); ?>";

    var attr = {};
    $(document).ready(function () {
    $(document).on('keyup','#search-query',function(){
   
      $("#search-query").attr('data-record', "");
            $("#search-query").attr('data-email', "");
            $("#search-query").attr('data-mobileno', "");
            $("#suggesstion-box").hide();
            var category_selected = $("input[name='selected_value']").val();

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('admin/mailsms/search') ?>",
                data: {'keyword': $(this).val(), 'category': category_selected},
                dataType: 'JSON',
                beforeSend: function () {
                    $("#search-query").css("background", "#FFF url(../../backend/images/loading.gif) no-repeat 165px");
                },
                success: function (data) {
                    if (data.length > 0) {
                        setTimeout(function () {
                            $("#suggesstion-box").show();
                            var cList = $('<ul/>').addClass('selector-list');
                            $.each(data, function (i, obj)
                            {
                               let parent_id="";
                                if (category_selected == "student") {
                                    var email = obj.email;
                                    var contact = obj.mobileno;
                                    var name = obj.fullname +  "(" + obj.admission_no + ")";
                                } else if (category_selected == "student_guardian") {
                                    var email = obj.email;
                                    var guardian_email = obj.guardian_email;
                                    var contact = obj.mobileno;
                                    var name =  obj.fullname + "(" + obj.admission_no + ")";
                                    parent_id = obj.parent_id;
                                } else if (category_selected == "parent") {
                                    var email = obj.guardian_email;
                                    var contact = obj.guardian_phone;
                                    var name = obj.guardian_name;
                                     parent_id = obj.parent_id;
                                } else if (category_selected == "staff") {
                                    var email = obj.email;
                                    var contact = obj.contact_no;
                                    var name = obj.name + ' ' + obj.surname + '(' + obj.employee_id + ')';
                                }

                                var li = $('<li/>')
                                        .addClass('ui-menu-item')
                                        .attr('category', category_selected)
                                        .attr('record_id', obj.id)
                                        .attr('email', email)
                                        .attr('parent_id', parent_id)
                                        .attr('mobileno', contact)
                                        .text(name);

                                if (category_selected == "student_guardian") {
                                    li.attr('data-guardian-email', guardian_email);
                                }
                                li.appendTo(cList);
                            });
                            $("#suggesstion-box").html(cList);
                            $("#search-query").css("background", "#FFF");
                        }
                        , 1000);
                    } else {
                        $("#suggesstion-box").hide();
                        $("#search-query").css("background", "#FFF");
                    }
                }
            });
    });

    $(document).on('click', '.selector-list li', function () {
        var val = $(this).text();
        var record_id = $(this).attr('record_id');
        var email = $(this).attr('email');
        var mobileno = $(this).attr('mobileno');
        var parent_id = $(this).attr('parent_id');

        $("#search-query").attr('value', val).val(val);
        $("#search-query").attr('data-record', record_id);
        $("#search-query").attr('data-email', email);
        $("#search-query").attr('data-parent_id', parent_id);
        if ($(this).data('guardianEmail') != undefined) {
            $("#search-query").attr('data-guardian-email', $(this).data('guardianEmail'));
        }
        $("#search-query").attr('data-mobileno', mobileno);
        $("#suggesstion-box").hide();
    });

    $(document).on('click', '.add-btn', function () {
        console.log(attr);
        var guardianEmail = "";
        var value = $("#search-query").val();
        var parent_id = $("#search-query").attr('data-parent_id');
        var record_id = $("#search-query").attr('data-record');
        var email = $("#search-query").attr('data-email');
        var mobileno = $("#search-query").attr('data-mobileno');

        if ($("#search-query").data('guardianEmail') != undefined) {
            var guardianEmail = $("#search-query").data('guardianEmail');
        }

        var category_selected = $("input[name='selected_value']").val();
        if (record_id != "" && category_selected != "") {
            var chkexists = checkRecordExists(category_selected + "-" + record_id);
            if (chkexists) {
                var arr = [];
                arr.push({
                    'category': category_selected,
                    'record_id': record_id,
                    'parent_id': parent_id,

                });

            attr[category_selected + "-" + record_id] = arr;
            $("#search-query").attr('value', "").val("");
            $("#search-query").attr('data-record', "").attr('data-email', "").attr('data-mobileno', "").attr('data-parent_id', "");

               if(category_selected == 'student'){
                    category_selected_lang = '<?php echo $this->lang->line('student'); ?>';
                }

                if(category_selected == 'parent'){
                    category_selected_lang = '<?php echo $this->lang->line('parent'); ?>';
                }

                if(category_selected == 'staff'){
                    category_selected_lang = '<?php echo $this->lang->line('staff'); ?>';
                }

                if(category_selected == 'student_guardian'){
                    category_selected_lang = '<?php echo $this->lang->line('student_guardian'); ?>';
                }

                $(".send_list").append('<li class="list-group-item" id="' + category_selected + '-' + record_id + '"><i class="fa fa-user"></i> ' + value + ' (' + category_selected_lang + ') <i class="fa fa-trash pull-right text-danger" onclick="delete_record(' + "'" + category_selected + '-' + record_id + "'" + ')"></i></li>');
            } else {
                errorMsg('<?php echo $this->lang->line('record_already_exist') ?>');
            }
        } else {
            errorMsg("<?php echo $this->lang->line('message_to_field_is_required'); ?>");
        }
        getTotalRecord();
    });

    });

   function getTotalRecord() {
        $.each(attr, function (key, value) {

        });
    }

    function checkRecordExists(find) {
        if (find in attr) {
            return false;
        }
        return true;
    }

    function delete_record(record) {
        delete attr[record];
        $('#' + record).remove();
        getTotalRecord();
        return false;
    };

    $(document).on('change', '#class_id', function (e) {
        $('.section_list').html("");
        var class_id = $(this).val();
        var div_data = '';
        $.ajax({
            type: "GET",
            url: baseurl  + "sections/getByClass",
            data: {'class_id': class_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data += '<li class="checkbox"><a href="#" class="small"><label><input type="checkbox" name="class_section_id[]" value ="' + obj.id + '"/>' + obj.section + '</label></a></li>';
                });
                $('.section_list').append(div_data);
            }
        });
    });

$('#single-delete').on('show.bs.modal', function(e) {
            var data = $(e.relatedTarget).data();
            $('.title', this).text(data.name);
            $('.btn-ok', this).data('recordId', data.recordId);
        });

    $('#confirm-delete').on('show.bs.modal', function(e) {
             var delete_ul = $('<ul/>');
              if(share_by_side_panel){
                     new_selected=selected_sidebar_data;
              }else{
                     new_selected=selected_data;

              }
            $.each(new_selected, function (key, input) {

                 $('<li/>').append($('<a>').attr('href','javascript:void(0)') .text(input.filename))
                                .addClass('ui-menu-item')
                                .attr('role', 'menuitem')
                                .appendTo(delete_ul);

             });

            $(".delete_files_list").html(delete_ul);
    });

 $(document).on('click', '#single-delete .btn-ok', function(e) {
    let delete_btn=$(this);
            var $modalDiv = $(e.delegateTarget);
            var id = $(this).data('recordId');

          $.ajax({
             type: "POST",
             url: baseurl  + "admin/content/delete",
             data: {'id': id},
             dataType: "json",
             beforeSend: function() {
               delete_btn.button('loading');
              },
             success: function (data) {
                if(data.status){

                if(share_by_side_panel){
                   selected_sidebar_data=[];


                    $('.total_files').html(data.file_count);
                    $('.total_size').html(data.file_size);


                $('.sidear-info').css("display", "block");
                $('.sidebar-form-content').css("display", "none");

                var img = $('<img />', {
                    id: 'image',
                    width: 'auto',
                    height: 'auto',
                    class: 'add_image',
                  src: baseurl+'backend/images/upload-file.png',

                });
                 $('.documents_sidebar').find('div.d-preview').html(img);

                 }else{
                checkbox_selected('remove',id);
                    if(selected_data.length > 0){

                let div_count=$('<i />', {
                    class: 'fa fa-plus',
                });
                $('.d-preview-count').html(div_count).append(" "+selected_data.length.toString().padStart(2, '0')+ " "+files_selected+" ");
                }

                }

                 $('#single-delete').modal('hide');
                 posts.init();
                }else{

                }
                 delete_btn.button('reset');
             },
             error: function(xhr) { // if error occured
                 alert("<?php echo $this->lang->line('error_occured_please_try_again'); ?>");
                 delete_btn.button('reset');
             },
             complete: function() {
                 delete_btn.button('reset');
             }
         });
        });

    $(document).on('click', '#confirm-delete .btn-ok', function(e) {
              var delete_selected=[];
              var delete_selected_ids=[];
              if(share_by_side_panel){
                     delete_selected=selected_sidebar_data;
              }else{
                     delete_selected=selected_data;
              }

               $.each(delete_selected, function(i, el){
             delete_selected_ids.push(el.id);
            });

            let delete_btn=$(this);
          $.ajax({
             type: "POST",
             url: baseurl  + "admin/content/delete_array",
             data: {'id': delete_selected_ids},
             dataType: "json",
             beforeSend: function() {
              delete_btn.button('loading');
              },
             success: function (data) {
                if(data.status){

                $('.sidear-info').css("display", "block");
                $('.sidebar-form-content').css("display", "none");

                var img = $('<img />', {
                    id: 'image',
                    width: 'auto',
                    height: 'auto',
                    class: 'add_image',
                  src: branch_base_url+'backend/images/upload-file.png',
                });

                 $('.documents_sidebar').find('div.d-preview').html(img);
                 reset_share_form();
                 $('#confirm-delete').modal('hide');
                 posts.init();
                }else{

                }
                  delete_btn.button('reset');
             },
             error: function(xhr) { // if error occured
                 alert("<?php echo $this->lang->line('error_occured_please_try_again'); ?>");
  delete_btn.button('reset');
             },
             complete: function() {
  delete_btn.button('reset');
             }
         });
    });

function copylink(){
  

    var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($('a.share_link').text()).select();
  document.execCommand("copy");
  $temp.remove();

}

$(document).on('click','.top_list_div',function(event){
    var $target = $(event.target);
     if( !($target.is("input:checkbox[name='share_checkbox[]']")) && !($target.parent().hasClass('delete_file'))) {
      
      $('.d-preview-count').remove();
        update_sidebar($(this));
        clearcheckbox();
  }
});

function update_sidebar(selector){
     $('.sidear-info').css("display", "none");
    $('.sidebar-form-content').css("display", "block");
       $('.sidebar-form-content').find('form').css("display", "block");
     let content_div=selector.closest('div.top_list_div');
     let content_data=content_div.data();
     let content_image=content_div.find('.image_content img').attr("src");
     $('.side_delete_btn').data('recordId', content_data.recordId);
     $('.side_delete_btn').data('name', content_data.real_name);
     $('div.documents_sidebar').find('img').attr("src",content_image).removeClass('add_image');
     $('#side_form input[name="name"]').val(content_data.real_name);
     $('#side_form input[name="id"]').val(content_data.recordId);
     $('#side_form select option[value="' + content_data.typeId +'"]').prop("selected", true);

                selected_sidebar_data = [];
                share_by_side_panel = true;
                selected_sidebar_data.push({
                     id: content_data.recordId,
                     filename:  content_data.real_name
                 });
}

   $(document).on("click", '.table_contents tbody tr', function(event) {

    var $target = $(event.target);
     if( !($target.is("input:checkbox[name='share_checkbox[]']"))) {
      clearcheckbox();
        $('.d-preview-count').remove();
        $('.sidear-info').css("display", "none");
        $('.sidebar-form-content').css("display", "block");
        $('.sidebar-form-content').find('form').css("display", "block");

        let content_div=$(this);
        let content_data=content_div.data();
        let content_image=content_div.find('input[name="image_display"]').val();

        $('.side_delete_btn').data('recordId', content_data.recordId);
        $('.side_delete_btn').data('name', content_data.real_name);
        $('div.documents_sidebar').find('img').attr("src",content_image).removeClass('add_image');
        $('#side_form input[name="name"]').val(content_data.real_name);
        $('#side_form input[name="id"]').val(content_data.recordId);
        $('#side_form select option[value="' + content_data.typeId +'"]').prop("selected", true);

                selected_sidebar_data = [];
                share_by_side_panel = true;

                selected_sidebar_data.push({
                     id: content_data.recordId,
                     filename:  content_data.real_name
                 });
  }
    });

    $('#viewModel').on('hidden.bs.modal', function () {
    $('#viewModel .modal-body').html("");

});

$(document).on('click','.div_image',function(){
    let content_div=$(this).closest('div.top_list_div');
    let fileType=content_div.data('fileType');
    let real_name=content_div.data('real_name');
    let file_upload_name=content_div.data('name');
    let filepath=content_div.data('path');
    let recordId=content_div.data('recordId');
    $('.pdfdownload-icon').attr('href', baseurl  + "admin/content/download_content/"+recordId);
    $('.model_file_name').text(real_name);
    let modal_view=false;
        if(fileType == "jpg" || fileType == "jpeg" ||  fileType == "png"    || fileType ==  "svg" || fileType == "webp" || fileType == "gif"){
                    modal_view=true;;
                var img = $('<img />', {
                  id: 'image',
                   width: 'auto',
                    height: 'auto',
                    class: 'img-fluid',
                  src: branch_base_url+filepath+file_upload_name,
                  alt: real_name
                });
        $('#viewModel .modal-body').html(img)
        }else if(fileType == "pdf"){
        modal_view=true;
        var pdf = $('<embed />', {
                  src: branch_base_url+filepath+file_upload_name+"#toolbar=0",
                  width: '100%',
                  height: '100vh',

                });
        $('#viewModel .modal-body').html(pdf)

        }else if(fileType == "mp4" || fileType == "webm" || fileType == "3gp" || fileType == "m4a" ){
        modal_view=true;
       var video = $('<video />', {
                  src: branch_base_url+filepath+file_upload_name,
                   width: '100%',
                  height: '80vh',
                  controls: 'controls',

                });
        $('#viewModel .modal-body').html(video)

        }else if(fileType == "video" ){
        modal_view=true;
        var youtubeID = YouTubeGetID(file_upload_name);

          content_popup = '<object data="https://www.youtube.com/embed/' + youtubeID + '" width="100%" height="400"></object>';
        $('#viewModel .modal-body').html(content_popup);

        }else if(fileType == "mp4" || fileType == "webm" || fileType == "3gp" || fileType == "m4a" ){
        modal_view=true;
       var video = $('<video />', {
                  src: branch_base_url+filepath+file_upload_name,
                  controls: 'controls',

                });
        $('#viewModel .modal-body').html(video)

        }
        if(modal_view){

        $('#viewModel').modal('show');
        }
});

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

$('.close_btn').click(function(){
    $(".dropify-clear").trigger("click");
})

  $('#shareModal').on('hidden.bs.modal', function(e) {
    attr = {};
    // $('#share_form')[0].reset();

    reset_form('#share_form');
        $('.nav-tabs-radio input[data-target="#share_group"]').tab('show');
        $("input:radio[value='group']").prop('checked',true);
        
    $(".send_list").empty();
    $(".section_list").empty();
 });

</script>