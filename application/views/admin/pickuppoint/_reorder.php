<?php
$s = 1;
foreach ($result as $key => $value) {
    ?>
<tr style="cursor: all-scroll" id="<?php echo $value->id; ?>">
	<td><?php echo $s; ?></td>
	<td><?php echo $value->pickup_point_name; ?></td>
	<td ><?php echo $value->destination_distance; ?></td>
	<td ><?php echo $this->customlib->timeFormat($value->pickup_time, $this->customlib->getSchoolTimeFormat()); ?></td>
	<td class="text-right"><?php echo amountFormat($value->fees); ?></td>
</tr>
<?php
$s++;
}
?>

