<?php 
$admin_session   = $this->session->userdata('admin');
$currency_symbol = $admin_session['currency_symbol'];
?>
<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>backend/js/ckeditor_config.js"></script>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom theme-shadow">
                    <div class="box-header with-border">
                       <h3 class="box-title titlefix"><?php echo $this->lang->line('online_admission'); ?></h3>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab"><?php echo $this->lang->line('online_admission_form_setting'); ?></a></li>
                        <li><a href="#tab_2" data-toggle="tab"><?php echo $this->lang->line('online_admission_fields_setting'); ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <?php if ($this->session->flashdata('msg')) {
    ?>
                                        <?php
echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg');
    ?>
                                    <?php }?>
                            <form action="<?php echo site_url('admin/onlineadmission/admissionsetting') ?>" method="post" id="form1" accept-charset="utf-8" enctype="multipart/form-data">
                                <div class="row">
                                        <label class="col-lg-3 col-md-4 col-sm-5 control-label"><?php echo $this->lang->line('online_admission'); ?></label>
                                        <div class="col-lg-9 col-md-8 col-sm-7">
                                            <div class="form-group">
                                                 <div class="material-switch">
                                                    <input id="online_admission" name="online_admission" type="checkbox" data-role="field_" class="enableexam"   value="1" <?php echo set_checkbox('online_admission', '1', (set_value('online_admission', $result->online_admission) == 1) ? true : false); ?>>
                                                      <label for="online_admission" class="label-success"></label>
                                                 </div>
                                           </div>
                                        </div>
                                    <div  id="settingclm">
                                       <div id="paymentclm">
                                           <label class="col-lg-3 col-md-4 col-sm-5 col-sm-5"><?php echo $this->lang->line('online_admission_payment_option') ?></label>
                                           <div class="col-lg-9 col-md-8 col-sm-7">
                                                <div class="form-group">
                                                    <div class="material-switch">
                                                     <input id="chk_yes" name="online_admission_payment" type="checkbox" data-role="field_" class="amountenable"   value="yes" <?php echo set_checkbox('online_admission_payment', 'yes', (set_value('online_admission_payment', $result->online_admission_payment) == 'yes') ? true : false); ?>>
                                                     <label for="chk_yes" class="label-success"></label>
                                                </div>
                                                </div>
                                             </div>
                                        </div>
                                        <div id="amountclm">
                                            <label class="col-lg-3 col-md-4 col-sm-5 col-sm-5 control-label"><?php echo $this->lang->line('online_admission_form_fees') . ' (' . $currency_symbol . ')' ?></label>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <input type="text" name="online_admission_amount" id="online_admission_amount" class="form-control" value="<?php echo convertBaseAmountCurrencyFormat($result->online_admission_amount); ?> " onkeypress="return IsNumeric(event);" >
                                                    <span class="text-danger" id="error"></span>
                                                    <span class="text-danger"><?php echo form_error('online_admission_amount'); ?></span>
                                               </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group row">
                                                <label class="col-lg-3 col-md-4 col-sm-5 col-sm-5"><?php echo $this->lang->line('upload_admission_application_form'); ?></label>
                                                <div class="col-lg-6 col-md-8 col-sm-7">
                                                    <div class="row">
                                                        <div class="col-md-8 col-xs-10">
                                                            <div class="form-group">
                                                                <input type="file" data-height="26" name="file" class="form-control filestyle">
                                                                <span class="text-danger"><?php echo form_error('file'); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 col-xs-2">
                                                            <div class="form-group">
                                                                <a href="<?php echo base_url(); ?>admin/onlineadmission/download/<?php echo $sch_setting_detail->id; ?>" data-toggle="tooltip" data-original-title='<?php echo $this->lang->line('download_application_form'); ?>' class='btn btn-info btn-sm btn-sm-md'><i class="fa fa-download"></i></a>                                                          
                                                                 
                                                            </div>
                                                        </div>
                                                     </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <label class=" control-label"><?php echo $this->lang->line('online_admission_instructions'); ?></label>
                                                <div class="form-group">
                                                     <textarea name="online_admission_instruction" id="online_admission_instruction" class="form-control ckeditor"  ><?php echo $result->online_admission_instruction; ?></textarea>
                                               </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <label class=" control-label"><?php echo $this->lang->line('terms_conditions'); ?></label>
                                                <div class="form-group">
                                                     <textarea name="online_admission_conditions" id="online_admission_conditions" class="form-control ckeditor"  ><?php echo $result->online_admission_conditions; ?></textarea>
                                                    <span class="text-danger"></span>
                                               </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="row">
                                <div class="box-footer pull-right">
                                    <button type="submit" name="submitbtn" id="submitbtn" value="submitbtn" class="btn btn-primary"> <?php echo $this->lang->line('save'); ?></button>
                                </div>
                            </div>
                          </form>
                        </div><!-- box body-->
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <form role="form" id="twilio" id="twilio" action="<?php echo site_url('smsconfig/twilio') ?>" method="post">
                                <div class="">
                                    <div class="row">
                                            <div class="col-sm-10">
                                              <h4 class="box-title"><?php echo $this->lang->line('online_admission_form_fields'); ?></h4>
                                            </div>
                                            <div class="col-sm-12">
                                                 <div class="download_label"><?php echo $this->lang->line('online_admission_form_fields'); ?></div>
                                                 <table class="table table-striped table-bordered table-hover example tableswitch" cellspacing="0" width="100%" data-export-title="<?php echo $this->lang->line('online_admission_form_fields'); ?>" >
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('name'); ?></th>
                                                            <th class="noExport"><?php echo $this->lang->line('action'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php

$sch_setting_array = json_decode(json_encode($sch_setting_detail), true);
if (!empty($fields)) {

    foreach ($fields as $fields_key => $fields_value) {
        if (array_key_exists($fields_key, $sch_setting_array)) {
            if (($sch_setting_detail->$fields_key)) {
                ?>
                                                                <tr>
                                                                    <td class="text-rtl-right" width="100%"><?php echo $fields_value; ?></td>
                                                                    <td class="text-right">
                                                                        <div class="material-switch pull-right">
                                                                    <input id="field_<?php echo $fields_key ?>" name="<?php echo $fields_key; ?>" type="checkbox" data-role="field_<?php $fields_key?>" class="chk"  value="" <?php echo set_checkbox($fields_key, $fields_key, findSelected($inserted_fields, $fields_key)); ?>/>
                                                                            <label for="field_<?php echo $fields_key ?>" class="label-success"></label>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                              <?php
}
        } else {
            ?>
                                                                    <tr>
                                                                        <td><?php echo $fields_value; ?></td>
                                                                        <td  class="text-right">
                                                                        <div class="material-switch pull-right">
                                                                        <input id="field_<?php echo $fields_key ?>" name="<?php echo $fields_key; ?>" type="checkbox" data-role="field_<?php $fields_key?>" class="chk"  value="" <?php echo set_checkbox($fields_key, $fields_key, findSelected($inserted_fields, $fields_key)); ?>/>
                                                                        <label for="field_<?php echo $fields_key ?>" class="label-success"></label>
                                                                        </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php
}
    }
}
if (!empty($custom_fields)) {

    foreach ($custom_fields as $custom_fields) {

        $exist = $this->customlib->checkfieldexist($custom_fields['name']);

        if ($exist == 1) {
            $value = $this->customlib->getfieldstatus($custom_fields['name']);
        } else {
            $value = 0;
        }
        ?>
                                                 <tr>
                                                    <td><?php echo $custom_fields['name']; ?></td>
                                                    <td  class="text-right">
                                                    <div class="material-switch pull-right">
                                                    <input id="field_<?php echo $custom_fields['name']; ?>" name="<?php echo $custom_fields['name']; ?>" type="checkbox" data-role="field_<?php $custom_fields['name'];?>" class="chk"  value="<?php echo $value; ?>" <?php if ($value == 1) {echo 'checked';}?> />
                                                    <label for="field_<?php echo $custom_fields['name']; ?>" class="label-success"></label>
                                                    </div>
                                                    </td>
                                                </tr>
                                        <?php }
}
?>
                                         </tbody>
                                     </table>
                                  </div>
                          </div>
                     </div>
                                <!-- /.box-body -->
                            </form>
                        </div>

                    </div>
                    <!-- /.tab-content -->
                </div>
            </div>
        </div>
    </section>
</div>
</div>
</div>

<?php

function findSelected($inserted_fields, $find)
{
    foreach ($inserted_fields as $inserted_key => $inserted_value) {
        if ($find == $inserted_value->name && $inserted_value->status) {
            return true;
        }

    }
    return false;
}
?>

<script>

   $(".ckeditor").each(function (_, ckeditor) {
        CKEDITOR.env.isCompatible = true;
        CKEDITOR.replace(ckeditor, {
            toolbar: 'Ques',
            customConfig: baseurl + '/backend/js/ckeditor_config.js'
        });
    });

</script>

<script>

(function ($) {
    $(function(){
        if ( $('#online_admission').prop('checked')==true){

           $("#tblclm").css('display','block');
           $("#settingclm").css('display','block');

        }else{

            $("#tblclm").css('display','none');
            $("#settingclm").css('display','none');

        }

        if ( $('#chk_yes').prop('checked')==true){

           $("#amountclm").css('display','block');
           $("#online_admission_amount").removeAttr('readonly');

        }else{

            $("#amountclm").css('display','none');
            $("#online_admission_amount").attr('readonly','true');

        }
    });
    })(jQuery);

    $(document).ready(function () {

        $(document).on('click', '#online_admission', function(event) {

        var name=$(this).attr('name');

        if(this.checked) {
           $("#tblclm").css('display','block');
           $("#settingclm").css('display','block');
        } else {
            $("#tblclm").css('display','none');
            $("#settingclm").css('display','none');
        }

        });
    });

</script>

<script type="text/javascript">

(function ($) {
    $(document).ready(function () {

        $(document).on('click', '.chk', function(event) {

        var name=$(this).attr('name');
        var status=1;
        if(this.checked) {
            status=1;
        } else {
            status=0;
        }

        if(confirm("<?php echo $this->lang->line('confirm_status'); ?>")){
            changeStatus(name, status);
        }
        else{
                 event.preventDefault();
        }
        
        });

    });

    function changeStatus(name, status) {

        var base_url = '<?php echo base_url() ?>';

        $.ajax({
            type: "POST",
            url: base_url + "admin/onlineadmission/changeformfieldsetting",
            data: {'name': name, 'status': status},
            dataType: "json",
            success: function (data) {
                successMsg(data.msg);

                if(name=='if_guardian_is'){

                    $("#field_guardian_relation").prop('disabled', 'disabled');
                    $("#field_guardian_name").prop('disabled', 'disabled');
                    $("#field_guardian_phone").prop('disabled', 'disabled');
                    $("#field_guardian_email").prop('disabled', 'disabled');
                    $("#field_guardian_occupation").prop('disabled', 'disabled');
                    $("#field_guardian_photo").prop('disabled', 'disabled');
                    $("#field_guardian_address").prop('disabled', 'disabled');

                    if(status==0)
                    {
                        $("#field_guardian_relation").prop('checked',false);
                        $("#field_guardian_name").prop('checked',false);
                        $("#field_guardian_phone").prop('checked',false);
                        $("#field_guardian_email").prop('checked',false);
                        $("#field_guardian_occupation").prop('checked',false);
                        $("#field_guardian_photo").prop('checked',false);
                        $("#field_guardian_address").prop('checked',false);
                    }else{
                        $("#field_guardian_relation").prop('checked',true);
                        $("#field_guardian_name").prop('checked',true);
                        $("#field_guardian_phone").prop('checked',true);
                        $("#field_guardian_email").prop('checked',true);
                        $("#field_guardian_occupation").prop('checked',true);
                        $("#field_guardian_photo").prop('checked',true);
                        $("#field_guardian_address").prop('checked',true);
                    }
                }
            }
        });
    }

 })(jQuery);

</script>

<script>
(function ($) {
    $(".amountenable").click(function () {
       if(this.checked){
            $("#online_admission_amount").removeAttr('readonly','false');
            $("#amountclm").css('display','block');
       }else{
            $("#amountclm").css('display','none');
            $("#online_admission_amount").attr('readonly','true');
       }
    });
})(jQuery);
</script>

<script>
(function ($) {
    $(".enableexam").click(function () {
       if(this.checked){
            $("#tblclm").css('display','block');
            $("#settingclm").css('display','block');
       }else{
            $("#tblclm").css('display','none');
            $("#settingclm").css('display','none');
       }
    });
  })(jQuery);
</script>

<script type="text/javascript">
(function ($) {
    var specialKeys = new Array();
    specialKeys.push(8); //Backspace
    function IsNumeric(e)
    {
        var keyCode = e.which ? e.which : e.keyCode
        var ret = ((keyCode >= 48 && keyCode <= 57) ||  keyCode==46);
        document.getElementById("error").style.display = ret ? "none" : "inline";
        return ret;
    }
 })(jQuery);
</script>

<script>
    $(function(){
        $('#form1'). submit( function() {
            $("#submitbtn").button('loading');
        });
    })
</script>
