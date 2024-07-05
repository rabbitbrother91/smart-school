<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <style type="text/css">
        .replaceable{
                font-size: 1em;
    line-height: 1.5em;
    font-weight: 400;
    font-style: italic;
    padding: 0px;
        }
    </style>
    <section class="content">
        <div class="row">
            <div class="col-md-12">             
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-envelope"></i> <?php echo $this->lang->line('media_storage'); ?></h3>
                    </div>   
                    
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Local --r</a></li>
        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Amazon S3 --r</a></li>
    </ul>
<div class="tab-content">

<div class="tab-pane active" id="tab_1">
    <form role="form"  action="<?php echo site_url('admin/filesystem/update') ?>" class="form-horizontal update_form" method="post">
   <div class="box-body">
                        <div class="row">
                        
                            <?php echo $this->customlib->getCSRF(); ?>                          
                         
                                <?php
                                    $local = check_in_array('local', $media_storages);
                                ?>
                                <input type="hidden" name="storage_type" value="local">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1">
                                        <?php echo $this->lang->line('path'); ?>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input id="name" name="folder_path" placeholder="" type="text" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('path', ($local->path != "") ? $local->path : FCPATH); ?>" />
                                        <span class="text-danger"><?php echo form_error('folder_path'); ?></span>
                                    </div>
                                </div>
                                     <div class="form-group">
                                                   <label class="control-label col-md-3 col-sm-3 col-xs-12"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select class="form-control" name="status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                                ?>
                                                                <option 
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($local->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>
                                                                    ><?php echo $s_value; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                        </select>
                                                        <span class=" text text-danger status_error"></span>
                                                    </div>
                                                </div>                           
                          </div>                            
                        </div>

                        <div class="box-footer">
                           <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">  

                                    <button type="submit" class="btn btn-info btnleftinfo" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Saving.."><?php echo $this->lang->line('update'); ?></button>
                                 
                            </div>
                          </div>
                        </div>
                    </form>
</div>

<div class="tab-pane" id="tab_2">
      <?php
                                                $amazon_s3 = check_in_array('amazon_s3', $media_storages);
                                            
                                                ?>
    <form role="form"  action="<?php echo site_url('admin/filesystem/update') ?>" class="form-horizontal update_form" method="post">
<div class="box-body"> 
      <input type="hidden" name="storage_type" value="amazon_s3">
                                    <div class="row">
                                        <div class="minheight170">
                                            <div class="col-md-7">
                                                 <div class="form-group">
                                                    <label class="col-sm-5 control-label">api_key --r<small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="api_key" value="<?php echo set_value('api_key', $amazon_s3->api_key); ?>">
                                                        <span class="text text-danger api_key_error"></span>
                                                    </div>
                                                </div>
                                                     <div class="form-group">
                                                    <label class="col-sm-5 control-label">secret --r<small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="secret" value="<?php echo set_value('secret', $amazon_s3->secret); ?>">
                                                        <span class="text text-danger secret_error"></span>
                                                    </div>
                                                </div>
                                             <div class="form-group">
                                                    <label class="col-sm-5 control-label">version --r<small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="version" value="<?php echo set_value('version', $amazon_s3->version); ?>" autocomplete="off">
                                                        <span class="text text-danger version_error"></span>
                                                    </div>
                                                </div>                                            
                                               <div class="form-group">
                                                    <label class="col-sm-5 control-label">region --r<small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="region" value="<?php echo set_value('region', $amazon_s3->region); ?>">
                                                        <span class="text text-danger region_error"></span>
                                                    </div>
                                                </div>                                                
                                                    <div class="form-group">
                                                    <label class="col-sm-5 control-label">bucket --r<small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="bucket" value="<?php echo set_value('bucket', $amazon_s3->bucket); ?>">
                                                        <span class="text text-danger bucket_error"></span>
                                                    </div>
                                                </div>
                                                   
                                                              <div class="form-group">
                                                   <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                                ?>
                                                                <option 
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($amazon_s3->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>
                                                                    ><?php echo $s_value; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                        </select>
                                                        <span class=" text text-danger status_error"></span>
                                                    </div>
                                                </div>
                                         
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://aws.amazon.com/s3/" target="_blank"><img src="https://mk0alessioangel8kd7h.kinstacdn.com/wp-content/uploads/2017/10/S3.jpg"><p>https://aws.amazon.com/s3/</p></a>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                        <div class="box-footer">
                           <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                 <button type="submit" class="btn btn-info btnleftinfo"><?php echo $this->lang->line('update'); ?></button>                                
                            </div>
                          </div>
                        </div>
</form>
</div>



</div>

</div>
                
                
                </div>
            </div>           
        </div>
    </section>

    </div>
    

<?php 
function check_in_array($find, $array) {

    foreach ($array as $element) {
        if ($find == $element->storage_type) {
            return $element;
        }
    }
    $object = new stdClass();
    $object->id = "";
    $object->storage_type     = "";
    $object->path = "";
    $object->version = "";
    $object->region = "";
    $object->api_key = "";
    $object->secret = "";
    $object->bucket = "";
    $object->endpoint = "";
    $object->is_active = "";
    return $object;
}
?>

<script type="text/javascript">
    
    $(document).on('submit','.update_form',function(e){
   e.preventDefault(); // avoid to execute the actual submit of the form.
      var smt_btn = $(this).find("button[type=submit]");
    var form = $(this);
    var actionUrl = form.attr('action');
    
    $.ajax({
        type: "POST",
        url: actionUrl,
        data: form.serialize(), // serializes the form's elements.
        dataType: 'JSON',
        beforeSend: function () {
          smt_btn.button('loading');
        },
        success: function(data)
        {
             var message = "";
                        if (!data.status) {

                            $.each(data.error, function (index, value) {

                                message += value;
                            });

                            errorMsg(message);

                        } else {
                            successMsg(data.message);
                            
                        }
        },
        error: function (xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                      smt_btn.button('reset');
        },
        complete: function () {
            smt_btn.button('reset');
        }
    });
    });
</script>
