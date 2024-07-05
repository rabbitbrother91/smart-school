<?php
$currency = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="pb30">
    <table class="table table-striped mb0 font13">
        <tbody>
                                                    <tr>
                                                        <th class="bozero"><?php echo $this->lang->line('name'); ?></th>
                                                        <td class="bozero"><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></td>
                                                        <th class="bozero"><?php echo $this->lang->line('class_section'); ?></th>
                                                        <td class="bozero"><?php echo $student['class'] . " (" . $student['section'] . ")" ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('father_name'); ?></th>
                                                        <td><?php echo $student['father_name']; ?></td>
                                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                        <td><?php echo $student['admission_no']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('mobile_number'); ?></th>
                                                        <td><?php echo $student['mobileno']; ?></td>
                                                        <th><?php echo $this->lang->line('roll_number'); ?></th>
                                                        <td> <?php echo $student['roll_no']; ?>
                                                        </td>
                                                    </tr>
                                                     <tr>
                                                        <th><?php echo $this->lang->line("pickup"); ?></th>
                                                        <td><?php echo $route_pickup_point->name; ?></td>
                                                        <th><?php echo $this->lang->line("pickup_time"); ?></th>
                                                        <td> <?php echo $route_pickup_point->pickup_time; ?>
                                                        </td>
                                                    </tr>
                                                       <tr>
                                                        <th><?php echo $this->lang->line("fees"); ?> (<?php echo $currency; ?>)</th>
                                                        <td><?php echo amountFormat($route_pickup_point->fees); ?> </td>
                                                        <th><?php echo $this->lang->line("distance_km"); ?></th>
                                                        <td> <?php echo $route_pickup_point->destination_distance; ?>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
<hr class="hrexamfirstrow">

<?php

if (!empty($fees)) {

    ?>
<input type="hidden" name="student_session_id" value="<?php echo $student_session_id; ?>">
<input type="hidden" name="route_pickup_point_id" value="<?php echo $route_pickup_point_id; ?>">
<div class="scroll-area">
<table class="table table-striped table-bordered table-list">
                  <thead>
                    <tr>
                        <th>
                        <div class="chk">
                        <input type="checkbox"  class="chkall">
                        <label >  <?php echo $this->lang->line("month"); ?></label>
                      </div>
                      </th>
                        <th><?php echo $this->lang->line("due_date"); ?></th>
                        <th class="text text-center"><?php echo $this->lang->line("fine_type"); ?></th>
                        <th class="text text-right"><?php echo $this->lang->line("amount"); ?></th>
                    </tr>
                  </thead>
                  <tbody>
  <?php
foreach ($fees as $fee_key => $fee_value) {

        ?>
 <input type="hidden" name="prev_ids[]" value="<?php echo $fee_value['student_transport_fee_id']; ?>">
            <input type="hidden" name="student_transport_fee_id_<?php echo $fee_value['id']; ?>" value="<?php echo $fee_value['student_transport_fee_id']; ?>">
            <input type="hidden" name="transport_feemaster_id" value="<?php echo $fee_value['id']; ?>">
            <input type="hidden" name="monthly_fees_<?php echo $fee_value['id']; ?>" value="<?php echo $route_pickup_point->fees; ?>">
            <input type="hidden" name="due_date_<?php echo $fee_value['id']; ?>" value="<?php echo $fee_value['due_date']; ?>">
            <input type="hidden" name="fine_type_<?php echo $fee_value['id']; ?>" value="<?php echo $fee_value['fine_type']; ?>">
            <input type="hidden" name="fine_percentage_<?php echo $fee_value['id']; ?>" value="<?php echo $fee_value['fine_percentage']; ?>">
            <input type="hidden" name="fine_amount_<?php echo $fee_value['id']; ?>" value="<?php echo $fee_value['fine_amount']; ?>">
         <tr>
                            <td>
                              <div class="chk">
                        <input type="checkbox"  name="transport_route_fee[]" value="<?php echo $fee_value['id'] ?>" <?php echo ($fee_value['student_transport_fee_id'] != "") ? "checked" : ""; ?> class="check_month">
                        <label ><?php echo $this->lang->line(strtolower($fee_value['month'])) ?></label>
                      </div>
                            </td>
                            <td><?php echo $this->customlib->dateformat($fee_value['due_date']); ?></td>
                            <td class="text text-center"><?php echo $this->lang->line($fee_value['fine_type']) ?></td>
                            <td class="text text-right"><?php
if ($fee_value['fine_type'] == "fix") {
            echo amountFormat($fee_value['fine_amount']) . ' ' . $currency;
        } elseif ($fee_value['fine_type'] == "percentage") {
            echo $fee_value['fine_percentage'] . "%";
        }
        ?></td>
                          </tr>
<?php
}
    ?>
                        </tbody>
                </table>
            </div>
</div>
 <div class="sticky-footer">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php if (($this->rbac->hasPrivilege('student_transport_fees', 'can_add')) || ($this->rbac->hasPrivilege('student_transport_fees', 'can_edit'))) {?>
        <button type="submit" class="btn btn-primary pull-right" data-loading-text="<?php echo $this->lang->line('submitting') ?>" value=""><?php echo $this->lang->line('save'); ?></button>
        <?php }?>
    </div>
 </div>
<?php
} else {
    ?>
<div class="alert alert-info">
  <?php echo "(You have not created Transport fees master please add before assign --r)" ?>
</div>
  <?php
}
?>