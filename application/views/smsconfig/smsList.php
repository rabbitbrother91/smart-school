<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> <?php //echo $this->lang->line('system_settings'); ?><small><?php //echo $this->lang->line('setting1'); ?></small>

            <small class="pull-right">
                <a type="button" onclick="sms_test()" class="btn btn-primary btn-sm"><?php echo $this->lang->line('sms_test'); ?></a>
            </small></h1>
    </section> 
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom theme-shadow">
                    <div class="box-header with-border">
                       <h3 class="box-title titlefix"></i> <?php echo $this->lang->line('sms_setting'); ?></h3>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab"><?php echo $this->lang->line('clickatell_sms_gateway'); ?></a></li>
                        <li><a href="#tab_2" data-toggle="tab"><?php echo $this->lang->line('twilio_sms_gateway'); ?></a></li>
                        <li><a href="#tab_4" data-toggle="tab"><?php echo $this->lang->line('msg_ninty_one'); ?></a></li>
                        <li><a href="#tab_6" data-toggle="tab"><?php echo $this->lang->line('text_local'); ?></a></li>
                        <li><a href="#tab_5" data-toggle="tab"><?php echo $this->lang->line('sms_country'); ?></a></li>
                        <li><a href="#tab_7" data-toggle="tab"><?php echo $this->lang->line('bulk_sms'); ?></a></li>    
                        <li><a href="#tab_8" data-toggle="tab"><?php echo $this->lang->line('mobireach'); ?></a></li>
                        <li><a href="#tab_9" data-toggle="tab"><?php echo $this->lang->line('nexmo'); ?></a></li>
                        <li><a href="#tab_10" data-toggle="tab"><?php echo $this->lang->line('africastalking'); ?></a></li>
                        <li><a href="#tab_11" data-toggle="tab"><?php echo $this->lang->line('sms_egypt'); ?></a></li>
                        <li><a href="#tab_3" data-toggle="tab"><?php echo $this->lang->line('custom_sms_gateway'); ?></a></li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <form role="form" id="clickatell" action="<?php echo site_url('smsconfig/clickatell') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $clickatell_result = check_in_array('clickatell', $smslist);
                                                ?>

                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('clickatell_username'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input autofocus="" type="text" class="form-control" name="clickatell_user" value="<?php echo $clickatell_result->username; ?>">
                                                        <span class=" text text-danger clickatell_user_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('clickatell_password'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="password" class="form-control" name="clickatell_password"  value="<?php echo $clickatell_result->password; ?>">
                                                        <span class=" text text-danger clickatell_password_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('api_key'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="clickatell_api_id"  value="<?php echo $clickatell_result->api_id; ?>">
                                                        <span class=" text text-danger clickatell_api_id_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">

                                                        <select class="form-control" name="clickatell_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                                ?>
                                                                <option 
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($clickatell_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>
                                                                    ><?php echo $s_value; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                        </select>
                                                        <span class=" text text-danger clickatell_status_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.clickatell.com/" target="_blank"><img src="<?php echo base_url() ?>backend/images/clickatell.png<?php echo img_time(); ?>"><p>https://www.clickatell.com</p></a>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                        <div class="col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) {
                                                ?>
                                                <button type="submit" class="btn btn-primary btnleftinfo"><?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="clickatell_loader"></span>
                                            <?php } ?>
                                        </div>       
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <form role="form" id="twilio" id="twilio" action="<?php echo site_url('smsconfig/twilio') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $twilio_result = check_in_array('twilio', $smslist);
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('twilio_account_sid'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="twilio_account_sid" value="<?php echo $twilio_result->api_id; ?>">
                                                        <span class="text text-danger twilio_account_sid_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('authentication_token'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="twilio_auth_token" value="<?php echo $twilio_result->password; ?>">
                                                        <span class="text text-danger twilio_auth_token_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('registered_phone_number'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="twilio_sender_phone_number" value="<?php echo $twilio_result->contact; ?>">
                                                        <span class="text text-danger twilio_sender_phone_number_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="twilio_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                                ?>
                                                                <option
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($twilio_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>
                                                                    ><?php echo $s_value; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                        </select>
                                                        <span class=" text text-danger twilio_status_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.twilio.com/?v=t" target="_blank"><img src="<?php echo base_url() ?>backend/images/twilio.png<?php echo img_time(); ?>"><p>https://www.twilio.com</p></a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                        <div class="col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) {
                                                ?>
                                                <button type="submit" class="btn btn-primary btnleftinfo"><?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="twilio_loader"></span>
                                            <?php }
                                            ?>
                                        </div>      

                                </div>
                            </form>
                        </div> 
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_3">
                            <form role="form" id="custom" id="custom" action="<?php echo site_url('smsconfig/custom') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $custom_result = check_in_array('custom', $smslist);
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('gateway_name'); ?><small class="req"> *</small>
                                                    </label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="name" value="<?php echo $custom_result->name; ?>">
                                                        <span class="text text-danger name_error"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                 <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="custom_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                                ?>
                                                                <option 
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($custom_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>
                                                                    ><?php echo $s_value; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                        </select>
                                                        <span class=" text text-danger custom_status_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href=""><img src="<?php echo base_url() ?>backend/images/custom-sms.png<?php echo img_time(); ?>"></a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <div class="col-md-offset-3">
                                        <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) {
                                            ?>
                                            <button type="submit" class="btn btn-primary btnleftinfo"><?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="custom_loader"></span>
                                        <?php } ?>
                                    </div>    
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_4">
                            <form role="form" id="msg_nineone" id="msg_nineone" action="<?php echo site_url('smsconfig/msgnineone') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $msg_nineone_result = check_in_array('msg_nineone', $smslist);
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('auth_Key'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="authkey" value="<?php echo $msg_nineone_result->authkey; ?>">
                                                        <span class="text text-danger authkey_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('sender_id'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="senderid" value="<?php echo $msg_nineone_result->senderid; ?>">
                                                        <span class="text text-danger senderid_error"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                   <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="msg_nineone_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                                ?>
                                                                <option 
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($msg_nineone_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>
                                                                    ><?php echo $s_value; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                        </select>
                                                        <span class=" text text-danger msg_nineone_status_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://msg91.com/" target="_blank"><img src="<?php echo base_url() ?>backend/images/msg91.png<?php echo img_time(); ?>"><p>https://msg91.com</p></a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="col-md-offset-3">
                                        <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) {
                                            ?>
                                            <button type="submit" class="btn btn-primary btnleftinfo"><?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="msg_nineone_loader"></span>
                                        <?php } ?>
                                    </div>    
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_5">
                            <form role="form" id="smscountry" id="smscountry" action="<?php echo site_url('smsconfig/smscountry') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $smscountry_result = check_in_array('smscountry', $smslist);
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('username'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="smscountry" value="<?php echo $smscountry_result->username; ?>">
                                                        <span class="text text-danger smscountry_error"></span>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('auth_Key'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="smscountryauthKey" value="<?php echo $smscountry_result->authkey; ?>">
                                                        <span class="text text-danger smscountryauthKey_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('authentication_token'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="smscountryauthtoken" value="<?php echo $smscountry_result->api_id; ?>">
                                                        <span class="text text-danger smscountryauthtoken_error"></span>
                                                    </div>
                                                </div> 
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('sender_id'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="smscountrysenderid" value="<?php echo $smscountry_result->senderid; ?>">
                                                        <span class="text text-danger smscountrysenderid_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('password'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="password" class="form-control" name="smscountrypassword" value="<?php echo $smscountry_result->password; ?>">
                                                        <span class="text text-danger smscountrypassword_error"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                   <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="smscountry_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                                ?>
                                                                <option 
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($smscountry_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>
                                                                    ><?php echo $s_value; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                        </select>
                                                        <span class=" text text-danger smscountry_status_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5  text text-center disblock">
                                                <a href="https://www.smscountry.com/" target="_blank"><img src="<?php echo base_url() ?>backend/images/sms-country.jpg<?php echo img_time(); ?>"><p>https://www.smscountry.com</p></a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <div class="col-md-offset-3">
                                        <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) {
                                            ?>
                                            <button type="submit" class="btn btn-primary btnleftinfo"><?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="smscountry_loader"></span>
                                        <?php } ?>
                                    </div>    
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_6">

                            <form role="form" id="text_local" id="text_local" action="<?php echo site_url('smsconfig/textlocal') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $text_local_result = check_in_array('text_local', $smslist);
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('username'); ?></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="text_local" value="<?php echo $text_local_result->username; ?>">
                                                        <span class="text text-danger text_local_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('hashkey'); ?></label>
                                                    <div class="col-sm-7">
                                                        <input type="password" class="form-control" name="text_localpassword" value="<?php echo $text_local_result->password; ?>">
                                                        <span class="text text-danger text_localpassword_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('sender_id'); ?></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="text_localsenderid" value="<?php echo $text_local_result->senderid; ?>">
                                                        <span class="text text-danger text_localsenderid_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                  <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="text_local_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                                ?>
                                                                <option 
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($text_local_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>
                                                                    ><?php echo $s_value; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                        </select>
                                                        <span class=" text text-danger text_local_status_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.textlocal.in/" target="_blank"><img src="<?php echo base_url() ?>backend/images/textlocal.png<?php echo img_time(); ?>"><p>https://www.textlocal.in</p></a>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <div class="col-md-offset-3">
                                        <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) {
                                            ?>
                                            <button type="submit" class="btn btn-primary btnleftinfo"><?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="text_local_loader"></span>
                                        <?php } ?>
                                    </div>    
                                </div>
                            </form>
                        </div>
                         <div class="tab-pane" id="tab_7">
 
                            <form role="form"  id="bulk_sms" action="<?php echo site_url('smsconfig/bulk_sms') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $bulk_sms_result = check_in_array('bulk_sms', $smslist);
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('username'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="bulk_sms_user_name" value="<?php echo $bulk_sms_result->username; ?>">
                                                        <span class="text text-danger bulk_sms_user_name_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('password'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="password" class="form-control" name="bulk_sms_user_password" value="<?php echo $bulk_sms_result->password; ?>">
                                                        <span class="text text-danger bulk_sms_user_password_error"></span>
                                                    </div>
                                                </div>
                                              
                                                 <div class="form-group">
                                                  <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="bulk_sms_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                                ?>
                                                                <option 
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($bulk_sms_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>
                                                                    ><?php echo $s_value; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                        </select>
                                                        <span class=" text text-danger bulk_sms_status_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.bulksms.com/" target="_blank"><img src="<?php echo base_url() ?>backend/images/bulk_sms.png<?php echo img_time(); ?>" class="img-responsive center-block"><p>https://www.bulksms.com/</p></a>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <div class="col-md-offset-3">
                                        <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) {
                                            ?>
                                            <button type="submit" class="btn btn-primary btnleftinfo"><?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="bulk_sms_loader"></span>
                                        <?php } ?>
                                    </div>    
                                </div>
                            </form>
                        </div>
                         <div class="tab-pane" id="tab_8">
 
                            <form role="form"  id="mobireach" action="<?php echo site_url('smsconfig/mobireach') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $mobireach_result = check_in_array('mobireach', $smslist);
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('auth_Key'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="mobireach_auth_key" value="<?php echo $mobireach_result->authkey; ?>">
                                                        <span class="text text-danger mobireach_auth_key_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('sender_id'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="mobireach_sender_id" value="<?php echo $mobireach_result->senderid; ?>">
                                                        <span class="text text-danger mobireach_sender_id_error"></span>
                                                    </div>
                                                </div>
                                               <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('route_id'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="mobireach_route_id" value="<?php echo $mobireach_result->api_id; ?>">
                                                        <span class="text text-danger mobireach_route_id_error"></span>
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                   <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="mobireach_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                                ?>
                                                                <option 
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($mobireach_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>
                                                                    ><?php echo $s_value; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                        </select>
                                                        <span class=" text text-danger mobireach_status_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://user.mobireach.com.bd/" target="_blank"><img src="<?php echo base_url() ?>backend/images/mobireach.jpg<?php echo img_time(); ?>"><p>https://user.mobireach.com.bd/</p></a>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <div class="col-md-offset-3">
                                        <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) {
                                            ?>
                                            <button type="submit" class="btn btn-primary btnleftinfo"><?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="mobireach_loader"></span>
                                        <?php } ?>
                                    </div>     
                                </div>
                            </form>
                        </div>

                         <div class="tab-pane" id="tab_9">
 
                            <form role="form"  id="nexmo" action="<?php echo site_url('smsconfig/nexmo') ?>" class="form-horizontal" method="post">
                                <div class="box-body"> 
                                    <div class="row">
                                        <div class="minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $nexmo_result = check_in_array('nexmo', $smslist);
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('nexmo_api_key'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="nexmo_api_key" value="<?php echo $nexmo_result->api_id; ?>">
                                                        <span class="text text-danger nexmo_api_key_error"></span>
                                                    </div>
                                                </div>
                                               
                                               <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('nexmo_api_secret'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="nexmo_api_secret" value="<?php echo $nexmo_result->authkey; ?>">
                                                        <span class="text text-danger nexmo_api_secret_error"></span>
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('registered_from_number'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="nexmo_registered_phone_number" value="<?php echo $nexmo_result->senderid; ?>">
                                                        <span class="text text-danger nexmo_registered_phone_number_error"></span>
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                   <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="nexmo_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                                ?>
                                                                <option 
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($nexmo_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>
                                                                    ><?php echo $s_value; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                        </select>
                                                        <span class=" text text-danger nexmo_status_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://dashboard.nexmo.com/sign-up" target="_blank"><img src="<?php echo base_url() ?>backend/images/nexmo.jpg<?php echo img_time(); ?>"><p>https://dashboard.nexmo.com/sign-up</p></a>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <div class="col-md-offset-3">
                                        <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) {
                                            ?>
                                            <button type="submit" class="btn btn-primary btnleftinfo"><?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="nexmo_loader"></span>
                                        <?php } ?>
                                    </div>    
                                </div>
                            </form>
                        </div>


                        <div class="tab-pane" id="tab_10">
  
                            <form role="form" id="africastalking" action="<?php echo site_url('smsconfig/africastalking') ?>" class="form-horizontal" method="post">
                                <div class="box-body"> 
                                    <div class="row">
                                        <div class="minheight170">
                                            <div class="col-md-7">
                                                <?php 
                                                $africastalking_result = check_in_array('africastalking', $smslist);
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('username'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="africastalking_username" value="<?php echo $africastalking_result->username; ?>">
                                                        <span class="text text-danger africastalking_username_error"></span>
                                                    </div>
                                                </div>
                                               
                                               <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('api_key'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="africastalking_apikey" value="<?php echo $africastalking_result->api_id; ?>">
                                                        <span class="text text-danger africastalking_apikey_error"></span>
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('short_code'); ?></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="africastalking_short_code" value="<?php echo $africastalking_result->senderid; ?>">
                                                        <span class="text text-danger africastalking_short_code_error"></span>
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                   <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="africastalking_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                                ?>
                                                                <option 
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($africastalking_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>
                                                                    ><?php echo $s_value; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                        </select>
                                                        <span class=" text text-danger africastalking_status_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://africastalking.com/" target="_blank"><img src="<?php echo base_url() ?>backend/images/africastalking.png<?php echo img_time(); ?>"><p>https://africastalking.com/</p></a>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <div class="col-md-offset-3">
                                        <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) {
                                            ?>
                                            <button type="submit" class="btn btn-primary btnleftinfo"><?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="nexmo_loader"></span>
                                        <?php } ?>
                                    </div>    
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="tab_11">
  
                            <form role="form" id="smseg" action="<?php echo site_url('smsconfig/smseg') ?>" class="form-horizontal" method="post">
                                <div class="box-body"> 
                                    <div class="row">
                                        <div class="minheight170">
                                            <div class="col-md-7">
                                                <?php 
                                                $smseg_result = check_in_array('smseg', $smslist);
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('username'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="smseg_username" value="<?php echo $smseg_result->username; ?>">
                                                        <span class="text text-danger smseg_username_error"></span>
                                                    </div>
                                                </div>
                                               
                                               <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('password'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="smseg_password" value="<?php echo $smseg_result->password; ?>">
                                                        <span class="text text-danger smseg_password_error"></span>
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('sender_id'); ?></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="smseg_sender_id" value="<?php echo $smseg_result->senderid; ?>">
                                                        <span class="text text-danger smseg_sender_id_error"></span>
                                                    </div>
                                                </div>
                                                  <div class="form-group">
                                                   <label class="col-sm-5 control-label"><?php echo $this->lang->line('type'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="smseg_type">
                                                           <option value="">
                                                               <?php echo $this->lang->line('select')?>
                                                           </option>
                                                           <option value="https://smssmartegypt.com/sms/api/?" <?php if($smseg_result->url=='https://smssmartegypt.com/sms/api/?'){ echo "selected";} ?>>
                                                               <?php echo $this->lang->line('local_sms')?>
                                                           </option>
                                                           <option value="https://smssmartegypt.com/sms/api/InterAPI?" <?php if($smseg_result->url=='https://smssmartegypt.com/sms/api/InterAPI?'){ echo "selected";} ?>>
                                                               <?php echo $this->lang->line('international_sms')?>
                                                           </option>
                                                        </select>
                                                        <span class=" text text-danger smseg_type_error"></span>
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                   <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="smseg_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                                ?>
                                                                <option 
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($smseg_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>
                                                                    ><?php echo $s_value; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                        </select>
                                                        <span class=" text text-danger smseg_status_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://smseg.com/" target="_blank"><img src="<?php echo base_url() ?>backend/images/smseg.png<?php echo img_time(); ?>"><p>https://smseg.com/</p></a>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <div class="col-md-offset-3">
                                        <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) {
                                            ?>
                                            <button type="submit" class="btn btn-primary btnleftinfo"><?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="nexmo_loader"></span>
                                        <?php } ?>
                                    </div>    
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.tab-content -->
                </div>
            </div>
        </div>  
    </section>
</div>
<div id="myModal" class="modal fade in" role="dialog" aria-hidden="true" >
    <div class="modal-dialog modal-dialog2">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?php echo $this->lang->line('test_sms'); ?></h4>
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <div class="">
                            <form id="sendform" action="<?php echo base_url() ?>emailconfig/test_mail"   name="employeeform" class="form-horizontal form-label-left" method="post" accept-charset="utf-8"> 
                                <div class="">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="pwd"><?php echo $this->lang->line('mobile_number'); ?></label><small class="req"> *</small>  
                                            <input type="text" id="title" autocomplete="off" class="form-control" value="" name="mobile">
                                            <span id="name_add_error" class="text-danger"></span>
                                        </div>

                                    </div>
                                </div>

                        </div><!--./row--> 
                        <div class="box-footer">
                            <div class="pull-right paddA10">

                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('send'); ?></button>
                            </div>
                        </div>
                        </form>  
                    </div>                     
                </div><!--./col-md-12-->       

            </div><!--./row--> 

        </div>
    </div>
</div>
</div>
</div>
<?php

function check_in_array($find, $array) {

    foreach ($array as $element) {
        if ($find == $element->type) {
            return $element;
        }
    }
    $object = new stdClass();
    $object->id = "";
    $object->type = "";
    $object->api_id = "";
    $object->username = "";
    $object->url = "";
    $object->name = "";
    $object->contact = "";
    $object->password = "";
    $object->authkey = "";
    $object->senderid = "";
    $object->is_active = "";
    return $object;
}
?>


<script type="text/javascript">
    function sms_test() {
        $('#myModal').modal('show');
    }

    $(document).ready(function (e) {
        $("#sendform").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url() ?>admin/mailsms/test_sms',
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                },
                error: function () {}
            });
        }));
    });
    var img_path = "<?php echo base_url() . '/backend/images/loading.gif' ?>";
    $("#clickatell").submit(function (e) {
        $("[class$='_error']").html("");

        $(".clickatell_loader").html('<img src="' + img_path + '">');
        var url = $(this).attr('action'); // the script where you handle the form input.

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#clickatell").serialize(), // serializes the form's elements.
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
                $(".clickatell_loader").html("");

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".clickatell_loader").html("");
                //if fails      
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    $("#twilio").submit(function (e) {
        $("[class$='_error']").html("");

        $(".twilio_loader").html('<img src="' + img_path + '">');
        var url = $(this).attr('action'); // the script where you handle the form input.

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#twilio").serialize(), // serializes the form's elements.
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
                $(".twilio_loader").html("");

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".twilio_loader").html("");
                //if fails      
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });


    $("#custom").submit(function (e) {
        $("[class$='_error']").html("");

        $(".custom_loader").html('<img src="' + img_path + '">');
        var url = $(this).attr('action'); // the script where you handle the form input.

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#custom").serialize(), // serializes the form's elements.
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
                $(".custom_loader").html("");

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
                //if fails      
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    $("#msg_nineone").submit(function (e) {
        $("[class$='_error']").html("");

        $(".msg_nineone_loader").html('<img src="' + img_path + '">');
        var url = $(this).attr('action'); // the script where you handle the form input.

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#msg_nineone").serialize(), // serializes the form's elements.
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
                $(".msg_nineone_loader").html("");

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".msg_nineone_loader").html("");
                //if fails      
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    $("#smscountry").submit(function (e) {
        $("[class$='_error']").html("");

        $(".smscountry_loader").html('<img src="' + img_path + '">');
        var url = $(this).attr('action'); // the script where you handle the form input.

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#smscountry").serialize(), // serializes the form's elements.
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
                $(".smscountry_loader").html("");

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".msg_nineone_loader").html("");
                //if fails      
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });


    $("#text_local").submit(function (e) {
        $("[class$='_error']").html("");
        $(".text_local_loader").html('<img src="' + img_path + '">');
        var url = $(this).attr('action'); // the script where you handle the form input.
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#text_local").serialize(), // serializes the form's elements.
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
                $(".text_local_loader").html("");

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".text_local_loader").html("");
                //if fails      
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });
   $("#bulk_sms").submit(function (e) {
        $("[class$='_error']").html("");
        $(".bulk_sms_loader").html('<img src="' + img_path + '">');
        var url = $(this).attr('action'); // the script where you handle the form input.
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#bulk_sms").serialize(), // serializes the form's elements.
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else { 
                    successMsg(data.msg);
                }
                $(".bulk_sms_loader").html("");

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".bulk_sms_loader").html("");
                //if fails      
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });


      $("#mobireach").submit(function (e) {
        $("[class$='_error']").html("");
        $(".mobireach_loader").html('<img src="' + img_path + '">');
        var url = $(this).attr('action'); // the script where you handle the form input.
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#mobireach").serialize(), // serializes the form's elements.
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }

                $(".mobireach_loader").html("");

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".mobireach_loader").html("");
                //if fails      
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

       $("#nexmo").submit(function (e) {
        $("[class$='_error']").html("");
        $(".nexmo_loader").html('<img src="' + img_path + '">');
        var url = $(this).attr('action'); // the script where you handle the form input.
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#nexmo").serialize(), // serializes the form's elements.
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }

                $(".nexmo_loader").html("");

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".nexmo_loader").html("");
                //if fails      
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

        $("#africastalking").submit(function (e) {
        $("[class$='_error']").html("");
        $(".africastalking_loader").html('<img src="' + img_path + '">');
        var url = $(this).attr('action'); // the script where you handle the form input.
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#africastalking").serialize(), // serializes the form's elements.
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }

                $(".africastalking_loader").html("");

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".africastalking_loader").html("");
                //if fails      
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });
 
         $("#smseg").submit(function (e) {
        $("[class$='_error']").html("");
        $(".smseg_loader").html('<img src="' + img_path + '">');
        var url = $(this).attr('action'); // the script where you handle the form input.
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#smseg").serialize(), // serializes the form's elements.
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }

                $(".smseg_loader").html("");

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".smseg_loader").html("");
                //if fails      
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });
</script>