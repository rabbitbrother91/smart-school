<style type="text/css">

  #fade {
    display: none;
    position:absolute;
    top: 0%;
    left: 0%;
    width: 100%;
    height: 100%;
    background-color: #edededcc;
    z-index: 1001;
    -moz-opacity: 0.8;
    opacity: .70;
    filter: alpha(opacity=80);
}

#modal {
    display: none;
    position: absolute;
    top: 35%;
    left: 45%;
    width: 83px;
    height: 83px;
    /* padding: 24px 15px 0px; */
    /* border: 3px solid #ababab; */
    /* box-shadow: 1px 1px 10px #ababab; */
    border-radius: 20px;
    /* background-color: white; */
    z-index: 1002;
    text-align: center;
    overflow: auto;
}
</style>
<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<div id="openTermsAndConditionsModal" class="modal fade in" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header modal-header-small">
        <button type="button" class="close closebtnmodal" data-dismiss="modal">&times;</button>
        <h4 ><?php echo $this->lang->line('online_admission_terms_conditions'); ?></h4>
      </div>
       <form action="<?php echo base_url() . 'welcome/checkadmissionstatus' ?>" method="post" class="onlineform" id="checkstatusform">
          <div class="modal-body">
      <?php echo $online_admission_conditions; ?>
          </div>
          <div class="modal-footer">
          <button type="button" class="modalclosebtn btn mdbtn" data-dismiss="modal"><?php echo $this->lang->line('close'); ?></button>
          </div>
      </form>
    </div>
  </div>
</div>

<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
if ($this->session->flashdata('msg')) {
    $message = $this->session->flashdata('msg');
    echo $message;
}

if ($student_pic != "") {
    $student_photo = base_url() . $student_pic;

} else {
    $student_photo = base_url() . "uploads/student_images/no_image.png";
}
if ($father_pic != "") {
    $father_photo = base_url() . $father_pic;

} else {
    $father_photo = base_url() . "uploads/student_images/no_image.png";
}
if ($mother_pic != "") {
    $mother_photo = base_url() . $mother_pic;

} else {
    $mother_photo = base_url() . "uploads/student_images/no_image.png";
}
if ($guardian_pic != "") {
    $guardian_photo = base_url() . $guardian_pic;

} else {
    $guardian_photo = base_url() . "uploads/student_images/no_image.png";
}
?>
 <div class="alert alert-success" id="completeformdiv" ><?php echo $this->lang->line('form_has_been_submitted_successfully'); ?> </div>
   <div id="divtoprint" class="spaceb40">
    <div class="row" id="printheader">
      <img src="<?php echo base_url() ?>/uploads/print_headerfooter/online_admission_receipt/<?php echo $this->setting_model->get_onlineadmissionheader(); ?>" style="height: 100px;width: 100%;" />
    </div>

      <div class="row">
        <div class="col-md-4 col-lg-4 col-sm-12">
           <h4 class="entered" id="headid"><?php echo $this->lang->line('review_entered_details_and_status'); ?></h4>
            <h4 class="entered" id="printheadid"><?php echo $this->lang->line('online_admission_details'); ?></h4>
        </div>
        <div class="col-md-8 col-lg-8 col-sm-12">
            <form action="<?php echo base_url() . 'welcome/editonlineadmission/' . $reference_no; ?>" method="post" class="">
                <div class="row">
                  <div class="col-md-10"></div>
                   <div class="col-md-2 col-lg-2 col-sm-12">
                    <div class="statusright">
                      <?php if ($form_status == 0 && $status == "") {?>
                        <button type="submit" id="editbtn" class="btn printbtndrakgray btn-sm" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-edit"></i></button>
                       <?php }?>
                       <button type="button" id="printbtn" class="btn printbtndrakgray btn-sm" data-toggle="tooltip" title="<?php echo $this->lang->line('print'); ?>" onclick="printDiv('divtoprint')"><i class="fa fa-print"></i></button>
                    </div>
                  </div>
              </div>
            </form>
        </div>
    </div>

     <hr/>

         <div class="row justify-content-center align-items-center flex-wrap d-flex">
            <div class="col-md-7 col-lg-7 col-sm-8">
              <ul class="reflist">
                <li><?php echo $this->lang->line('reference_no') ?><span><?php echo $reference_no; ?></span></li>

                <?php if ($form_status == 0) {?>
                <li><?php echo $this->lang->line('form_status') ?><span class="text-danger"><?php echo $this->lang->line('not_submitted') ?></span></li>
                <?php } else {?>
                     <li><?php echo $this->lang->line('form_status') ?><span class="text-success"><?php echo $this->lang->line('submitted') ?></span></li>
                  <?php }?>
                  <?php
if ($online_admission_payment == 'yes') {

    if ($paid_status == 1) {?>
                               <li><?php echo $this->lang->line('payment_status'); ?><span class="text-success"><?php echo $this->lang->line('paid'); ?></span></li>
                               <li><?php echo $this->lang->line('paid_amount') ?><span><?php echo $currency_symbol .''. amountFormat($transaction_paid_amount) ; ?></span></li>
                               <li><?php echo $this->lang->line('transaction_id') ?><span><?php echo $transaction_id; ?></span></li>
                            <?php } elseif ($paid_status == 2) {?>
                               <li><?php echo $this->lang->line('payment_status'); ?><span class="text-success"><?php echo $this->lang->line('processing'); ?></span></li>
                               <li><?php echo $this->lang->line('transaction_id') ?><span><?php echo $transaction_id; ?></span></li>
                            <?php } else {?>
                                <li><?php echo $this->lang->line('payment_status') ?><span class="text-danger"><?php echo $this->lang->line('unpaid') ?></span></li>
                              <?php }
}?>
                 <li><?php echo $this->lang->line('application_date') ?><span><?php echo $application_date; ?></span></li>
              </ul>

            </div>
            <div class="col-md-5 col-lg-5 col-sm-4">
               <ul class="statusimg">
                <?php if ($this->customlib->getfieldstatus('student_photo')) {?>
                   <li>
                       <img src="<?php echo $student_photo . img_time(); ?>" />

                       <p><?php echo $this->lang->line('student'); ?></p>
                   </li>
                 <?php }if ($this->customlib->getfieldstatus('father_pic')) {?>
                   <li>
                       <img src="<?php echo $father_photo . img_time(); ?>" />
                      <p><?php echo $this->lang->line('father'); ?></p>
                   </li>
                 <?php }if ($this->customlib->getfieldstatus('mother_pic')) {?>
                   <li>
                      <img src="<?php echo $mother_photo . img_time(); ?>" />
                      <p><?php echo $this->lang->line('mother'); ?></p>
                   </li>
                 <?php }if ($this->customlib->getfieldstatus('guardian_photo')) {?>
                   <li>
                       <img src="<?php echo $guardian_photo . img_time(); ?>" />
                       <p><?php echo $this->lang->line('guardian'); ?></p>
                   </li>
                 <?php }?>
               </ul>
            </div>
         </div><!--./row-->
        <br/>

          <div class="printcontent">
            <div class="row">
              <h4 class="pagetitleh2"><?php echo $this->lang->line('basic_details'); ?></h4>
              <div class="col-md-3 col-lg-3 col-sm-3">
                  <div class="form-group">
                    <label><b><?php echo $this->lang->line('class'); ?></b></label>
                    <p><?php echo $class_name; ?></p>
                  </div>
              </div>

              <div class="col-md-3 col-lg-3 col-sm-3">
                  <div class="form-group">
                      <label><b><?php echo $this->lang->line('first_name'); ?></b></label>
                      <p><?php echo $firstname; ?></p>
                  </div>
              </div>
             <?php if ($this->customlib->getfieldstatus('middlename')) {?>
             <div class="col-md-3 col-lg-3 col-sm-3">
                <div class="form-group">
                  <label><b><?php echo $this->lang->line('middle_name'); ?></b></label>
                  <p><?php if ($middlename != "") {echo $middlename;} else {echo "--";}?></p>
                </div>
            </div>
            <?php }?>
             <?php if ($this->customlib->getfieldstatus('lastname')) {?>
             <div class="col-md-3 col-lg-3 col-sm-3">
                <div class="form-group">
                  <label><b><?php echo $this->lang->line('last_name'); ?></b></label>
                  <p><?php if ($lastname != "") {echo $lastname;} else {echo "--";}?></p>
                </div>
            </div>
            <?php }?>
        </div>
      <div class="row">
        <div class="col-md-3 col-lg-3 col-sm-3">
              <div class="form-group">
                <label><b><?php echo $this->lang->line('gender'); ?></b></label>
                <p><?php echo $gender; ?></p>
              </div>
            </div>
             <div class="col-md-3 col-lg-3 col-sm-3">
                <div class="form-group">

                  <label><b><?php echo $this->lang->line('date_of_birth'); ?></b></label>
                  <p><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($dob)); ?></p>
                </div>
            </div>
            <?php if ($this->customlib->getfieldstatus('mobile_no')) {?>
            <div class="col-md-3 col-lg-3 col-sm-3">
              <div class="form-group">
                  <label><b><?php echo $this->lang->line('mobile_number'); ?></b></label>
                  <p><?php if ($mobileno != "") {echo $mobileno;} else {echo "--";}?></p>
              </div>
            </div>
            <?php }?>
            <?php if ($this->customlib->getfieldstatus('student_email')) {?>
             <div class="col-md-3 col-lg-3 col-sm-3">
                <div class="form-group">
                  <label><b><?php echo $this->lang->line('email'); ?></b></label>
                  <p><?php echo $email; ?></p>
                </div>
            </div>
             <?php }?>
      </div>

      <div class="row">
       <?php if ($this->customlib->getfieldstatus('category')) {?>
          <div class="col-md-3 col-lg-3 col-sm-3">
            <div class="form-group">
              <label><b><?php echo $this->lang->line('category'); ?></b></label>
              <p><?php if ($category != "") {echo $category;} else {echo "--";}?></p>
            </div>
        </div>
        <?php }?>
         <?php if ($this->customlib->getfieldstatus('religion')) {?>
        <div class="col-md-3 col-lg-3 col-sm-3">
            <div class="form-group">
              <label><b><?php echo $this->lang->line('religion'); ?></b></label>
              <p><?php if ($religion != "") {echo $religion;} else {echo "--";}?></p>
            </div>
        </div>
        <?php }?>
         <?php if ($this->customlib->getfieldstatus('cast')) {?>
        <div class="col-md-3 col-lg-3 col-sm-3">
            <div class="form-group">
              <label><b><?php echo $this->lang->line('caste'); ?></b> </label>
              <p><?php if ($cast != "") {echo $cast;} else {echo "--";}?></p>
            </div>
        </div>
        <?php }?>

        <?php if ($this->customlib->getfieldstatus('is_student_house')) {?>
                  <div class="col-md-3 col-lg-3 col-sm-3">
                      <div class="form-group">
                        <label><b><?php echo $this->lang->line('house'); ?></b></label>
                        <p><?php if ($house_name != "") {echo $house_name;} else {echo "--";}?></p>
                      </div>
                  </div>
              <?php }?>
      </div>

      <div class="row">
        <?php if ($this->customlib->getfieldstatus('is_blood_group')) {?>
                <div class="col-md-3 col-lg-3 col-sm-3">
                    <div class="form-group">
                      <label><b><?php echo $this->lang->line('blood_group'); ?></b></label>
                      <p><?php if ($blood_group != "") {echo $blood_group;} else {echo "--";}?></p>
                    </div>
                </div>
            <?php }?>


            <?php if ($this->customlib->getfieldstatus('student_height')) {?>
                <div class="col-md-3 col-lg-3 col-sm-3">
                    <div class="form-group">
                      <label><b><?php echo $this->lang->line('height'); ?></b></label>
                      <p><?php if ($height != "") {echo $height;} else {echo "--";}?></p>
                    </div>
                </div>
            <?php }?>
            <?php if ($this->customlib->getfieldstatus('student_weight')) {?>
                <div class="col-md-3 col-lg-3 col-sm-3">
                    <div class="form-group">
                      <label><b><?php echo $this->lang->line('weight'); ?></b></label>
                      <p><?php if ($weight != "") {echo $weight;} else {echo "--";}?></p>
                    </div>
                </div>
            <?php }?>
            <?php if ($this->customlib->getfieldstatus('measurement_date')) {?>
                <div class="col-md-3 col-lg-3 col-sm-3">
                    <div class="form-group">
                      <label><b><?php echo $this->lang->line('measurement_date'); ?></b></label>
                      <p><?php if ($measurement_date != "") {echo $measurement_date;} else {echo "--";}?></p>
                    </div>
                </div>
            <?php }?>
        </div>

        <div class="row">
            <?php
$cutom_fields_data = get_onlineadmission_custom_table_values($id, 'students');
if (!empty($cutom_fields_data)) {
    foreach ($cutom_fields_data as $field_key => $field_value) {

        if ($this->customlib->getfieldstatus($field_value->name)) {
            ?>
                          <div class="col-md-3 col-lg-3 col-sm-3">
                            <div class="form-group">
                                <label><b><?php echo $field_value->name; ?></b> </label>
                                <p>
                                    <?php
if (is_string($field_value->field_value) && is_array(json_decode($field_value->field_value, true)) && (json_last_error() == JSON_ERROR_NONE)) {
                $field_array = json_decode($field_value->field_value);

                foreach ($field_array as $each_key => $each_value) {
                    echo $each_value;
                }

            } else {
                $display_field = $field_value->field_value;

                if ($field_value->type == "link") {
                    $display_field = "<a href=" . $field_value->field_value . " target='_blank'>" . $field_value->field_value . "</a>";
                }
                echo $display_field;
            }
            ?>
                                  </p>
                                </div>
                                </div>

                          <?php
}
    }
}
?>
          </div>
       </div><!--./printcontent-->

    <?php if ($this->customlib->getfieldstatus('father_name') || $this->customlib->getfieldstatus('father_phone') || $this->customlib->getfieldstatus('father_occupation') || $this->customlib->getfieldstatus('father_pic') || $this->customlib->getfieldstatus('mother_name') || $this->customlib->getfieldstatus('mother_phone') || $this->customlib->getfieldstatus('mother_occupation') || $this->customlib->getfieldstatus('mother_pic')) {?>

        <div class="printcontent">
          <div class="row">
            <h4 class="pagetitleh2"><?php echo $this->lang->line('parent_detail'); ?></h4>
            <?php if ($this->customlib->getfieldstatus('father_name')) {?>
             <div class="col-md-3 col-lg-3 col-sm-3">
                <div class="form-group">
                    <label><b><?php echo $this->lang->line('father_name'); ?></b> </label>
                    <p><?php if ($father_name != "") {echo $father_name;} else {echo "--";}?></p>
                </div>
            </div>
            <?php }?>
            <?php if ($this->customlib->getfieldstatus('father_phone')) {?>
             <div class="col-md-3 col-lg-3 col-sm-3">
                <div class="form-group">
                 <label><b><?php echo $this->lang->line('father_phone'); ?></b> </label>
                  <p><?php if ($father_phone != "") {echo $father_phone;} else {echo "--";}?></p>
                </div>
            </div>
            <?php }?>
              <?php if ($this->customlib->getfieldstatus('father_occupation')) {?>
            <div class="col-md-3 col-lg-3 col-sm-3">
                <div class="form-group">
                  <label><b><?php echo $this->lang->line('father_occupation'); ?></b> </label>
                  <p><?php if ($father_occupation != "") {echo $father_occupation;} else {echo "--";}?></p>
                </div>
            </div>
            <?php }?>
        </div>

        <div class="row">
             <?php if ($this->customlib->getfieldstatus('mother_name')) {?>
             <div class="col-md-3 col-lg-3 col-sm-3">
                <div class="form-group">
                  <label><b><?php echo $this->lang->line('mother_name'); ?></b> </label>
                  <p><?php if ($mother_name != "") {echo $mother_name;} else {echo "--";}?></p>
                </div>
            </div>
            <?php }?>
             <?php if ($this->customlib->getfieldstatus('mother_phone')) {?>
             <div class="col-md-3 col-lg-3 col-sm-3">
                <div class="form-group">
                    <label><b><?php echo $this->lang->line('mother_phone'); ?></b> </label>
                    <p><?php if ($mother_phone != "") {echo $mother_phone;} else {echo "--";}?></p>
                </div>
            </div>
            <?php }?>
             <?php if ($this->customlib->getfieldstatus('mother_occupation')) {?>
             <div class="col-md-3 col-lg-3 col-sm-3">
                <div class="form-group">
                 <label><b><?php echo $this->lang->line('mother_occupation'); ?></b> </label>
                  <p><?php if ($mother_occupation != "") {echo $mother_occupation;} else {echo "--";}?></p>
                </div>
            </div>
             <?php }?>
        </div>
      </div><!--./printcontent-->
    <?php }?>
     <?php if ($this->customlib->getfieldstatus('if_guardian_is')) {?>

        <div class="printcontent">
          <div class="row">
            <h4 class="pagetitleh2"><?php echo $this->lang->line('guardian_details'); ?></h4>
             <div class="col-md-3 col-lg-3 col-sm-3">
                  <div class="form-group">
                      <label><b><?php echo $this->lang->line('if_guardian_is'); ?></b> </label>
                      <p><?php echo $this->lang->line($guardian_is); ?></p>
                  </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-3">
                  <div class="form-group">
                   <label><b><?php echo $this->lang->line('guardian_name'); ?></b> </label>
                    <p><?php if ($guardian_name != "") {echo $guardian_name;} else {echo "--";}?></p>
                  </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-3">
                  <div class="form-group">
                      <label><b><?php echo $this->lang->line('guardian_relation'); ?></b> </label>
                      <p><?php if ($guardian_relation != "") {echo $guardian_relation;} else {echo "--";}?></p>
                  </div>
              </div>
                <?php if ($this->customlib->getfieldstatus('guardian_email')) {?>
               <div class="col-md-3 col-lg-3 col-sm-3">
                  <div class="form-group">
                      <label><b><?php echo $this->lang->line('guardian_email'); ?></b> </label>
                      <p><?php if ($guardian_email != "") {echo $guardian_email;} else {echo "--";}?></p>
                  </div>
              </div>
              <?php }?>
          </div>
          <div class="row">
            <?php if ($this->customlib->getfieldstatus('guardian_phone')) {?>
            <div class="col-md-3 col-lg-3 col-sm-3">
                <div class="form-group">
                    <label><b><?php echo $this->lang->line('guardian_phone'); ?></b> </label>
                    <p><?php if ($guardian_phone != "") {echo $guardian_phone;} else {echo "--";}?></p>
                </div>
            </div>
             <?php }?>
             <?php if ($this->customlib->getfieldstatus('guardian_occupation')) {?>
             <div class="col-md-3 col-lg-3 col-sm-3">
                <div class="form-group">
                    <label><b><?php echo $this->lang->line('guardian_occupation'); ?></b> </label>
                    <p><?php if ($guardian_occupation != "") {echo $guardian_occupation;} else {echo "--";}?></p>
                </div>
            </div>
            <?php }?>

            <?php if ($this->customlib->getfieldstatus('guardian_address')) {?>
             <div class="col-md-3 col-lg-3 col-sm-3">
                <div class="form-group">
                    <label><b><?php echo $this->lang->line('guardian_address'); ?></b> </label>
                    <p><?php if ($guardian_address != "") {echo $guardian_address;} else {echo "--";}?></p>
                </div>
            </div>
            <?php }?>
          </div>
        </div><!--./printcontent-->
        <?php }?>

        <?php if ($this->customlib->getfieldstatus('current_address') || $this->customlib->getfieldstatus('permanent_address')) {?>

        <div class="printcontent">
          <div class="row">
            <h4 class="pagetitleh2"><?php echo $this->lang->line('student_address_details'); ?></h4>
              <?php if ($this->customlib->getfieldstatus('current_address')) {?>
                  <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                          <label><b><?php echo $this->lang->line('current_address'); ?></b></label>
                         <p><?php if ($current_address != "") {echo $current_address;} else {echo "--";}
    ;?></p>
                      </div>
                  </div>
              <?php }?>
                <?php if ($this->customlib->getfieldstatus('permanent_address')) {?>
                  <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                        <label><b><?php echo $this->lang->line('permanent_address'); ?></b></label>
                        <p><?php if ($permanent_address != "") {echo $permanent_address;} else {echo "--";}?></p>
                      </div>
                  </div>
              <?php }?>
          </div>
        </div>
        <?php }?>
        <?php if ($this->customlib->getfieldstatus('bank_account_no') || $this->customlib->getfieldstatus('bank_name') || $this->customlib->getfieldstatus('ifsc_code') || $this->customlib->getfieldstatus('national_identification_no') || $this->customlib->getfieldstatus('local_identification_no') || $this->customlib->getfieldstatus('rte') || $this->customlib->getfieldstatus('previous_school_details') || $this->customlib->getfieldstatus('student_note')) {?>

      <div class="printcontent">
        <div class="row">
          <h4 class="pagetitleh2"><?php echo $this->lang->line('miscellaneous_details'); ?></h4>
           <?php if ($this->customlib->getfieldstatus('bank_account_no')) {?>
             <div class="col-md-4 col-lg-4 col-sm-4">
                <div class="form-group">
                    <label><b><?php echo $this->lang->line('bank_account_number'); ?></b></label>
                    <p><?php if ($bank_account_no != "") {echo $bank_account_no;} else {echo "--";}?></p>
                </div>
            </div>
            <?php }?>
          <?php if ($this->customlib->getfieldstatus('bank_name')) {?>
             <div class="col-md-4 col-lg-4 col-sm-4">
                <div class="form-group">
                    <label><b><?php echo $this->lang->line('bank_name'); ?></b></label>
                    <p><?php if ($bank_name != "") {echo $bank_name;} else {echo "--";}?></p>
                </div>
            </div>
            <?php }?>

            <?php if ($this->customlib->getfieldstatus('ifsc_code')) {?>
             <div class="col-md-4 col-lg-4 col-sm-4">
                <div class="form-group">
                    <label><b><?php echo $this->lang->line('ifsc_code'); ?></b> </label>
                    <p><?php if ($ifsc_code != "") {echo $ifsc_code;} else {echo "--";}?></p>
                </div>
            </div>
            <?php }?>
         </div>

          <div class="row">
              <?php if ($this->customlib->getfieldstatus('national_identification_no')) {?>
             <div class="col-md-4 col-lg-4 col-sm-4">
                <div class="form-group">
                    <label><b><?php echo $this->lang->line('national_identification_number'); ?></b> </label>
                    <p><?php if ($adhar_no != "") {echo $adhar_no;} else {echo "--";}?></p>
                </div>
            </div>
            <?php }?>
              <?php if ($this->customlib->getfieldstatus('local_identification_no')) {?>
               <div class="col-md-4 col-lg-4 col-sm-4">
                  <div class="form-group">
                      <label><b><?php echo $this->lang->line('local_identification_number'); ?></b> </label>
                      <p><?php if ($samagra_id != "") {echo $samagra_id;} else {echo "--";}?></p>
                  </div>
              </div>
              <?php }?>

               <?php if ($this->customlib->getfieldstatus('rte')) {?>
               <div class="col-md-4 col-lg-4 col-sm-4">
                  <div class="form-group">
                      <label><b><?php echo $this->lang->line('rte'); ?></b> </label>
                      <p><?php echo $rte; ?></p>
                  </div>
              </div>
              <?php }?>
           </div>

          <div class="row">
            <?php if ($this->customlib->getfieldstatus('previous_school_details')) {?>
               <div class="col-md-6 col-sm-6">
                  <div class="form-group">
                      <label><b><?php echo $this->lang->line('previous_school_details'); ?></b> </label>
                      <p><?php if ($previous_school != "") {echo $previous_school;} else {echo "--";}?></p>
                  </div>
              </div>
              <?php }?>
              <?php if ($this->customlib->getfieldstatus('student_note')) {?>
               <div class="col-md-6 col-sm-6">
                  <div class="form-group">
                      <label><b><?php echo $this->lang->line('note'); ?></b></label>
                      <p><?php if ($note != "") {echo $note;} else {echo "--";}?></p>
                  </div>
              </div>
              <?php }?>
          </div>
        </div><!--./printcontent-->
        <?php }?>

                 <?php if ($online_admission_payment == 'yes') {?>
                   <form id="paymentform" action="<?php echo base_url(); ?>onlineadmission/checkout" method="post" >
                   <?php } else {?>
                     <form id="admissionform" action="<?php echo base_url(); ?>welcome/submitadmission" method="post">
                    <?php }?>
                    <input type="hidden" name="admission_id" value="<?php echo $admission_id; ?>">
                    <input type="hidden" name="reference_no" value="<?php echo $reference_no; ?>">

                     <?php
if ($online_admission_payment == 'yes' && $paid_status == 0 && $status == "") {
    ?>
                        <div class="row btnprint relative">
                          <div class="col-md-6">
                            <div class="form-inline">
                              <div class="form-group">
                                <label for="email"><?php echo $this->lang->line('change_currency'); ?>:</label>
                                <select class="form-control currency_list" name="currency">
                                  <?php
foreach ($currencies as $currencie_key => $currencie_value) {
        ?>
                                  <option value="<?php echo $currencie_value->id; ?>" <?php
if ($currencie_value->id == $this->customlib->getSchoolCurrency()) {
            echo "Selected";
        }
        ?> ><?php echo $currencie_value->short_name . " (" . $currencie_value->symbol . ")"; ?></option>
                                  <?php
}
    ?>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group pull-right">
                              <input type="checkbox" id="checkterm" name="checkterm">&nbsp; <a href="#openTermsAndConditionsModal" data-toggle="modal" data-target="#openTermsAndConditionsModal"><?php echo $this->lang->line('i_agree_to_the_terms_and_conditions'); ?></a>
                                <span class="text-danger" id="termerror"></span>
                                   <button type="submit" id="paybtn" class="btn btn-danger" > <?php echo $this->lang->line('pay') ?>  <?php echo $currency_symbol . amountFormat($online_admission_amount) ?></button>
                                </div>
                            </div>
                            <div id="fade"></div>
                            <div id="modal">
                              <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>
                              <img id="loader" src="">
                            </div>
                        </div>
                      <?php } else if ($form_status == 0 && $status == "") {?>
                        <div class="row btnprint">
                            <div class="col-md-12">
                                <div class="form-group pull-right">
                                <input type="checkbox" id="checkterm" name="checkterm">

                <a href="#openTermsAndConditionsModal" data-toggle="modal" data-target="#openTermsAndConditionsModal"><?php echo $this->lang->line('i_agree_to_the_terms_and_conditions'); ?></a>

                                    <span class="text-danger" id="termerror"></span>
                                    <button type="submit" class="btn btn-danger" id="submitbtn"><?php echo $this->lang->line('submit'); ?></button>
                                </div>
                            </div>
                        </div>

                       <?php }?>
                   </form>
                  <div class ="printcontent" id="printfooter">
                    <?php $this->setting_model->get_onlineadmissionfooter();?>
                 </div>
    </div>
</div>
<script type="text/javascript">

 $(document).ready(function () {
$("#printheadid").css('display','none');

$("#printheader").css('display','none');
$("#printfooter").css('display','none');
$("#completeformdiv").css('display','none');
      if (sessionStorage.getItem("formsubmit") === null) {
        $("#completeformdiv").css('display','none');
      }else{
          $("#completeformdiv").css('display','block');
           sessionStorage.removeItem('formsubmit');
      }
    });
</script>
<script type="text/javascript">

    function refreshCaptcha(){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('site/refreshCaptcha'); ?>",
            data: {},
            success: function(captcha){
                $("#captcha_image").html(captcha);
            }
        });
    }

</script>
<script>

        function printDiv(){
          
          $("#printbtn").css('display','none');
          $("#editbtn").css('display','none');
          $(".btnprint").css('display','none');
          $("#headid").css('display','none');
           $("#printheadid").css('display','block');
           $("#printheader").css('display','block');
           $("#printfooter").css('display','block');
			$('.statusright').css('display','none');
			
            var printContents=document.getElementById('divtoprint').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;

            $("#headid").css('display','block');
            $("#printbtn").removeAttr('style');
            $("#editbtn").removeAttr('style');
            $(".btnprint").css('display','block');
            $("#printheadid").css('display','none');
             $("#printheader").css('display','none');
             $("#printfooter").css('display','none');
			$('.statusright').css('display','block');
        }

    </script>

<script type="text/javascript">

$(document).ready(function(){
$(document).on('submit','#admissionform',function(e){
   e.preventDefault(); // avoid to execute the actual submit of the form.
    var form = $(this);
    $("#submitbtn").prop('disabled',true);
    var url = form.attr('action');
    var form_data = form.serializeArray();
    sessionStorage.removeItem('formsubmit');

    $.ajax({
           url: url,
           type: "POST",
           dataType:'JSON',
           data: form_data, // serializes the form's elements.
              beforeSend: function () {
               },
              success: function(response) { // your success handler
                if(response.status==0){
                    $('#termerror').html(response.error);
                } else{

                  var admission_id= response.id;
                   var reference_no= response.reference_no;
                  sessionStorage.setItem("formsubmit", "done");
                 window.location.href="<?php echo base_url() . 'welcome/online_admission_review/' ?>"+reference_no ;
                }
              },
             error: function() { // your error handler

             },
             complete: function() {

             }
         });
});

});

</script>
<script>

  $(document).ready(function() {
    if($('#checkterm').prop("checked")==true){
       $("#paybtn").prop('disabled',false);
       $("#submitbtn").prop('disabled',false);
    }else{
      $("#paybtn").prop('disabled',true);
      $("#submitbtn").prop('disabled',true);
    }

    $('#checkterm').change(function() {
        if(this.checked) {
          $("#paybtn").prop('disabled',false);
          $("#submitbtn").prop('disabled',false);
        }else{
          $("#paybtn").prop('disabled',true);
           $("#submitbtn").prop('disabled',true);
        }

    });
});

</script>
<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});

$(document).on('change','.currency_list',function(e){
   let currency_id=$(this).val();
   $.ajax({
      type: 'POST',
      url: base_url+'welcome/changeCurrencyFormat',
      data: {'currency_id':currency_id},
       dataType: 'json',
      beforeSend: function() {
     $('#fade').css("display", "block");
     $('#modal').css("display", "block");
      },
      success: function(data) {
         $("#fade").fadeOut(2000);
         $("#modal").fadeOut(2000);
         window.location.reload();
      },
      error: function(xhr) { // if error occured

      },
      complete: function() {

      }
  });
});
</script>