<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('front_cms_setting'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="">
                        <form role="form" id="custom" action="<?php echo site_url('admin/frontcms') ?>" class="form-horizontal form-horizontal2" method="post" enctype="multipart/form-data">
                            <div class="box-body">
                                <div class="row">
                                    <div class="box-body">
                                        <div class="col-md-6 col-sm-12">
                                            <input type="hidden" name="id" value="<?php echo set_value('id', $frontcmslist->id) ?>">
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('front_cms'); ?></label>
                                                <div class="col-sm-7">
                                                    <div class="material-switch">
                                                        <input id="enable_frontcms" name="is_active_front_cms" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_front_cms', '1', (set_value('is_active_front_cms', $frontcmslist->is_active_front_cms) == 1) ? TRUE : FALSE); ?>>
                                                        <label for="enable_frontcms" class="label-success"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('sidebar'); ?></label>
                                                <div class="col-sm-7">
                                                    <div class="material-switch">
                                                        <input id="enable_sidebar" name="is_active_sidebar" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_sidebar', '1', (set_value('is_active_sidebar', $frontcmslist->is_active_sidebar) == 1) ? TRUE : FALSE); ?>>
                                                        <label for="enable_sidebar" class="label-success"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('language_rtl_text_mode'); ?></label>
                                                <div class="col-sm-7">
                                                    <div class="material-switch">
                                                        <input id="enable_rtl" name="is_active_rtl" type="checkbox" class="chk" value="1" <?php echo set_checkbox('is_active_rtl', '1', (set_value('is_active_rtl', $frontcmslist->is_active_rtl) == 1) ? TRUE : FALSE); ?>>
                                                        <label for="enable_rtl" class="label-success"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('sidebar_option'); ?></label>
                                                <div class="col-sm-7">
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" name="sidebar_options[]" value="news" <?php echo set_checkbox('sidebar_options[]', 'news', (set_value('sidebar_options[]', in_array("news", json_decode($frontcmslist->sidebar_options))) == 1) ? TRUE : FALSE); ?>> <?php echo $this->lang->line('news'); ?>
                                                    </label>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" name="sidebar_options[]" value="complain" <?php echo set_checkbox('sidebar_options[]', 'complain', (set_value('sidebar_options[]', in_array("complain", json_decode($frontcmslist->sidebar_options))) == 1) ? TRUE : FALSE); ?>> <?php echo $this->lang->line('complain'); ?>
                                                    </label>
                                                </div>
                                            </div>                                            
                                            
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('language'); ?></label>
                                                <div class="col-sm-7">
                                                    <div class="material-switch">
                                                        <select  id="language_id" name="sch_lang_id" class="form-control" >                                                            
                                                            <?php foreach ($languagelist as $language) {  ?>
                                                            <option value="<?php echo $language['id']; ?>" <?php
                                                            if ($language['id'] == $result->lang_id) {
                                                                echo "selected";
                                                            }
                                                            ?> ><?php echo $language['language']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <span class="text-danger"><?php echo form_error('language_id'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group hide">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('contact_us_page_email'); ?> </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="contact_us_email" value="<?php echo set_value('contact_us_email', $frontcmslist->contact_us_email) ?>">
                                                    <span class="text text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="form-group hide">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('complain_page_email'); ?> </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="complain_form_email" value="<?php echo set_value('complain_form_email', $frontcmslist->complain_form_email) ?>">
                                                    <span class="text text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('logo'); ?> (369px X 76px)</label>
                                                <div class="col-sm-7">
                                                    <input type="file" data-default-file="<?php echo $this->customlib->getBaseUrl().$frontcmslist->logo; ?>" class="filestyle form-control-file" name="logo" id="exampleInputFile" data-height="100">
                                                    <span class="text-danger"><?php echo form_error('logo'); ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('favicon'); ?> (32px X 32px)</label>
                                                <div class="col-sm-7">
                                                    <input type="file" class="filestyle form-control-file" name="fav_icon" id="exampleInputFile" data-height="50" data-default-file="<?php echo $this->customlib->getBaseUrl().$frontcmslist->fav_icon; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('footer_text'); ?></label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="footer_text" value="<?php echo set_value('footer_text', $frontcmslist->footer_text) ?>">
                                                    <span class="text text-danger"></span>
                                                </div>
                                            </div>
											<div class="form-group">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('cookie_consent'); ?></label>
                                                <div class="col-sm-7">
                                                    <textarea class="form-control" name="cookie_consent" rows="5"><?php echo set_value('cookie_consent', $frontcmslist->cookie_consent) ?></textarea>
                                                    <span class="text text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('google_analytics'); ?> </label>
                                                <div class="col-sm-7">
                                                    <textarea class="form-control" name="google_analytics" rows="5"><?php echo set_value('google_analytics', $frontcmslist->google_analytics) ?></textarea>

                                                    <span class="text text-danger"></span>
                                                </div>
                                            </div>
                                        </div><!--./col-md-7-->
                                        <div class="col-md-5 col-md-offset-1 col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('whatsapp_url'); ?> </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="whatsapp_url" value="<?php echo set_value('whatsapp_url', $frontcmslist->whatsapp_url) ?>">
                                                    <span class="text text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('facebook_url'); ?> </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="fb_url" value="<?php echo set_value('fb_url', $frontcmslist->fb_url) ?>">
                                                    <span class="text text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('twitter_url'); ?> </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="twitter_url" value="<?php echo set_value('twitter_url', $frontcmslist->twitter_url) ?>">
                                                    <span class="text text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('youtube_url'); ?> </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="youtube_url" value="<?php echo set_value('youtube_url', $frontcmslist->youtube_url) ?>">
                                                    <span class="text text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('google_plus_url'); ?> </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="google_plus" value="<?php echo set_value('google_plus', $frontcmslist->google_plus) ?>">
                                                    <span class="text text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('linkedin_url'); ?> </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="linkedin_url" value="<?php echo set_value('linkedin_url', $frontcmslist->linkedin_url) ?>">
                                                    <span class="text text-danger"></span>
                                                </div>
                                            </div>                                          
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('instagram_url'); ?> </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="instagram_url" value="<?php echo set_value('instagram_url', $frontcmslist->instagram_url) ?>">
                                                    <span class="text text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label"><?php echo $this->lang->line('pinterest_url'); ?> </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="pinterest_url" value="<?php echo set_value('pinterest_url', $frontcmslist->pinterest_url) ?>">
                                                    <span class="text text-danger"></span>
                                                </div>
                                            </div>
                                        </div><!--./col-md-5-->
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <hr/>
                                <div>
                                 
                                        <label for="input-type"><?php echo $this->lang->line('current_theme'); ?></label>
                                        <div id="input-type" class="mediarow">
                                            <div class="row">
                                                <?php
                                                foreach ($front_themes as $theme_key => $theme_value) {
                                                    ?>
                                                    <div class="col-md-2 col-sm-4 col-xs-6 img_div_modal">
                                                        <label class="radio-img w-100">
                                                            <input name="theme"  value="<?php echo $theme_key; ?>" type="radio" <?php echo set_radio('theme', $theme_key, (set_value('theme', $frontcmslist->theme) == $theme_key) ? TRUE : FALSE); ?> />

                                                            <img src="<?php echo $this->media_storage->getImageURL('backend/images/front_theme/' . $theme_value); ?>">
                                                            <span class="radiotext"><?php echo $theme_key;?></span>
                                                        </label>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>                                   
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <?php if ($this->rbac->hasPrivilege('front_cms_setting', 'can_edit')) {
                                    ?>
                                    <button type="submit" id="submitbtn" class="btn btn-primary pull-right"> <?php echo $this->lang->line('save') ?></button>&nbsp;&nbsp;<span class="custom_loader"></span>
                                <?php }
                                ?>
                            </div>
                        </form>
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
    $(function(){
        $('#custom'). submit( function() {          
            $("#submitbtn").button('loading');
        });
    })
</script>
