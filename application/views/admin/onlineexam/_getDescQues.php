<script src="<?php echo base_url() ?>backend/plugins/ckeditor/plugins/ckeditor_wiris/integration/WIRISplugins.js?viewer=image"></script>
<?php
if (!empty($result->total_result)) {
    ?>
<div class="pagination_info">Showing <?php echo $start; ?> to <?php echo $upto; ?> of <?php echo $total_row; ?> <?php echo $this->lang->line('records'); ?></div>
  <?php
    foreach ($result->total_result as $result_key => $result_item) {
        ?>
<div class="row">
   <div class="col-lg-9 col-md-8 col-sm-12">
    <span class="font-weight-bold"> <?php echo $this->lang->line('q_id') ?>: <?php echo $result_item->id; ?></span><br/>
    <?php echo $result_item->question; ?> <span class="ques_marks text text-danger">(<?php echo $this->lang->line('marks'); ?>: <?php echo $result_item->question_marks ?>)</span>
<div class="font-weight-bold"> <?php echo $this->lang->line('answer') ?>:</div>
<span class="displayblock pb5"><?php echo html_entity_decode($result_item->select_option); ?></span>
<?php if ($result_item->attachment_name != "") {
            ?>

<div class="font-weight-bold"> <?php echo $this->lang->line('attachment') ?>: <a href="<?php echo site_url('admin/onlineexam/downloadattachment/' . $result_item->attachment_upload_name); ?>" data-toggle="tooltip"  title="<?php echo $this->lang->line('download'); ?>"><?php echo $result_item->attachment_name; ?> <i class="fa fa-download"></i></a></div>
  <?php
}
        ?>
<form class="mark_fill_form" method="POST" action="<?php echo site_url('admin/onlineexam/fillmarks') ?>">
    <input type="hidden" name="onlineexam_student_result_id" value="<?php echo $result_item->id; ?>">
    <input type="hidden" name="question_marks" value="<?php echo $result_item->question_marks; ?>">
  <div class="form-group">
    <label for="fill_mark"> <?php echo $this->lang->line('your_marks'); ?>:</label>
    <input type="text" id="" name="fill_mark" class="form-control inputwidth70" value="<?php echo $result_item->marks; ?>" />
  </div>
    <div class="form-group">
    <label for="remark"> <?php echo $this->lang->line('your_remark'); ?>:</label>
    <textarea id="remark_<?php echo $result_item->id; ?>" name="remark" class="form-control remark" ><?php echo $result_item->remark; ?></textarea>
  </div>
<button type="submit" class="btn btn-info btn-sm smallbtn28" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save'); ?></button>
</form>
</div>
<div class="col-lg-3 col-md-4 col-sm-12">
  <div class="qbox">
      <ul class="queslist">
        <li><a href="#"> <?php echo $this->lang->line('name'); ?><span class="pull-right"><?php echo $this->customlib->getFullName($result_item->firstname, $result_item->middlename, $result_item->lastname, $sch_setting->middlename, $sch_setting->lastname); ?></span></a></li>
        <li><a href="#"> <?php echo $this->lang->line('class'); ?><span class="pull-right"><?php echo $result_item->class . "(" . $result_item->section . ")"; ?></span></a></li>
        <li><a href="#"><?php echo $this->lang->line('admission_no'); ?><span class="pull-right"><?php echo $result_item->admission_no; ?></span></a></li>
        <li><a href="#"><?php echo $this->lang->line('mobile_number'); ?><span class="pull-right"><?php echo $result_item->mobileno; ?></span></a></li>
        <li><a href="#"> <?php echo $this->lang->line('guardian_name'); ?><span class="pull-right"><?php echo $result_item->guardian_name; ?></span></a></li>
        <li><a href="#"> <?php echo $this->lang->line('guardian_phone'); ?><span class="pull-right"><?php echo $result_item->guardian_phone; ?></span></a></li>
      </ul>
  </div>
  </div>
</div>
    <hr />
        <?php
    }
} else {

}