<script type="text/javascript">

    $('.date').datepicker(format: datetime_format).trigger('change');</script>
<?php
if (isset($_POST['date_from']) && !empty($_POST['date_from'])) {
    $date_from = date($this->customlib->getSchoolDateFormat(), $this->customlib->datetostrtotime($_POST['date_from']));
} else {
    $date_from = date($this->customlib->getSchoolDateFormat());
}
if (isset($_POST['date_to']) && !empty($_POST['date_to'])) {
    $date_to = date($this->customlib->getSchoolDateFormat(), $this->customlib->datetostrtotime($_POST['date_to']));
} else {
    $date_to = date($this->customlib->getSchoolDateFormat());
}
?>
<div class="col-sm-6 col-md-3">
    <div class="form-group">
        <label><?php echo $this->lang->line('date_from'); ?></label>
        <input name="date_from" id="date_from" placeholder="" type="text" class="form-control date" value="<?php echo $date_from; ?>"  />
        <span class="text-danger"><?php echo form_error('date_from'); ?></span>
    </div>
</div> 

<div class="col-sm-6 col-md-3">
    <div class="form-group">
        <label><?php echo $this->lang->line('date_to'); ?></label>
        <input  name="date_to" id="date_to" placeholder="" type="text" class="form-control date" value="<?php echo $date_to; ?>"  />
        <span class="text-danger"><?php echo form_error('date_to'); ?></span>
    </div>
</div>