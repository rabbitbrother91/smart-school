<?php
if (!empty($attendence)) {
    ?>
    <table class="table">
        <thead>
            <tr>
                <th><?php echo $this->lang->line('subject'); ?></th>
                <th class="text text-center"><?php echo $this->lang->line('time_from'); ?></th>
                <th class="text text-center"><?php echo $this->lang->line('time_to'); ?></th>
                <th class="text text-center"><?php echo $this->lang->line('room_no'); ?></th>
                <th class="text text-center"><?php echo $this->lang->line('attendance'); ?></th>
                <th><?php echo $this->lang->line('note'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
foreach ($attendence as $att_key => $att_value) {
        ?>
                <tr>
                    <td><?php echo $att_value->name . " (" . $att_value->code . ")"; ?></td>
                    <td class="text text-center"><?php echo $att_value->time_from; ?></td>
                    <td class="text text-center"><?php echo $att_value->time_to; ?></td>
                    <td class="text text-center"><?php echo $att_value->room_no; ?></td>
                    <td class="text text-center"><?php
if ($att_value->attendence_type_id == "") {
            ?>
                            <span class="label label-danger"><?php echo $this->lang->line('n_a'); ?></span>
                            <?php
} else {
            echo getattendencetype($attendencetypeslist, $att_value->attendence_type_id);
        }
        ?></td>
                    <td><?php echo $att_value->remark; ?></td>
                </tr>
                <?php
}
    ?>
        </tbody>
    </table>
    <?php
} else {
    ?>
    <div class="alert alert-info">
        <?php echo $this->lang->line('no_record_found'); ?>
    </div>
    <?php
}
?>
<?php

function getattendencetype($attendencetype, $find)
{

    foreach ($attendencetype as $attendencetype_key => $attendencetype_value) {
        if ($attendencetype_value['id'] == $find) {
            return $attendencetype_value['key_value'];
        }
    }
    return false;
}
?>