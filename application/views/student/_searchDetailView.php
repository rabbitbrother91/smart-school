<?php

$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
if (!empty($students->data)) {

    foreach ($students->data as $student_key => $student) {

        if (empty($student->image)) {
            if ($student->gender == 'Female') {
                $image = "uploads/student_images/default_female.jpg";
            } else {
                $image = "uploads/student_images/default_male.jpg";
            }
        } else {
            $image = $student->image;
        }
        ?>
          <div class="carousel-row">
          <div class="slide-row">
        <div id="carousel-2" class="carousel slide slide-carousel" data-ride="carousel">
                  <div class="carousel-inner">
                                 <div class="item active">
             <a href="<?php echo base_url(); ?>student/view/<?php echo $student->id ?>">
           <?php if ($sch_setting->student_photo) {
            ?>
           <img class="img-responsive img-thumbnail width150" alt="<?php echo $student->firstname . " " . $student->lastname ?>" src="<?php echo $this->media_storage->getImageURL($image); ?>" alt="Image">
           <?php
}
        ?></a>
                      </div>
                </div>
            </div>
            <div class="slide-content">
                 <h4><a href="<?php echo base_url(); ?>student/view/<?php echo $student->id ?>"> <?php echo $this->customlib->getFullName($student->firstname, $student->middlename, $student->lastname, $sch_setting->middlename, $sch_setting->lastname); ?></a></h4>
                                <div class="row">
                         <div class="col-xs-6 col-md-6">
                           <address>
            <strong><b><?php echo $this->lang->line('class'); ?>: </b><?php echo $student->class . "(" . $student->section . ")" ?></strong><br>
                      <b><?php echo $this->lang->line('admission_no'); ?>: </b><?php echo $student->admission_no ?><br/>
                      <b><?php echo $this->lang->line('date_of_birth'); ?>:
            <?php
if ($student->dob != null && $student->dob != '0000-00-00') {
            echo $this->customlib->dateFormat($student->dob);
        }
        ?>
               <br>
            <b><?php echo $this->lang->line('gender'); ?>:&nbsp;</b><?php echo $this->lang->line(strtolower($student->gender)) ?><br>
                    </address>
                     </div>
                  <div class="col-xs-6 col-md-6">
                <b><?php echo $this->lang->line('local_identification_number'); ?>:&nbsp;</b><?php echo $student->samagra_id ?><br>
                    <?php if ($sch_setting->guardian_name) {
            ?>
                    <b><?php echo $this->lang->line('guardian_name'); ?>:&nbsp;</b><?php echo $student->guardian_name ?><br>
                    <?php
}
        if ($sch_setting->guardian_name) {
            ?>
          <b><?php echo $this->lang->line('guardian_phone'); ?>: </b> <abbr title="Phone"><i class="fa fa-phone-square"></i>&nbsp;</abbr> <?php echo $student->guardian_phone ?><br>
             <?php
}
        ?>
         <b><?php echo $this->lang->line('current_address'); ?>:&nbsp;</b><?php echo $student->current_address ?> <?php echo $student->city ?><br>
                                                                    </div>
                                                                    </div>
                                                                    </div>
                    <div class="slide-footer">
                          <span class="pull-right buttons">
            <a href="<?php echo base_url(); ?>student/view/<?php echo $student->id ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>" >
                   <i class="fa fa-reorder"></i>
                             </a>
                                             <?php
if ($this->rbac->hasPrivilege('student', 'can_edit')) {
            ?>
                  <a href="<?php echo base_url(); ?>student/edit/<?php echo $student->id ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                          <i class="fa fa-pencil"></i>
                                 </a>
                                  <?php
}
        if ($this->module_lib->hasActive('fees_collection') &&  $this->rbac->hasPrivilege('collect_fees', 'can_add')) {
            ?>
                   <a href="<?php echo base_url(); ?>studentfee/addfee/<?php echo $student->student_session_id ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('add_fees'); ?>">
                                 <?php echo $currency_symbol; ?>
                                                                                </a>
            <?php
}
        ?>
                        </span>
                 </div>
                </div>
                 </div>
                                                                    <?php
}

} else {
    ?>
   <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
<?php
}
?>