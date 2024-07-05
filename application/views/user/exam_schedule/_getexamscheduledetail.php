<?php   
if (!empty($subject_list)) {
    ?>
    
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover example1">
            <thead>
                <tr>
                    <th><?php echo $this->lang->line('subject'); ?></th>
                    <th><?php echo $this->lang->line('date_from'); ?></th>
                    <th><?php echo $this->lang->line('start_time'); ?></th>
                    <th><?php echo $this->lang->line('duration'); ?></th>
                    <th><?php echo $this->lang->line('credit_hours'); ?></th>
                    <th><?php echo $this->lang->line('room_no'); ?></th>
                    <th><?php echo $this->lang->line('marks_max'); ?></th>
                    <th><?php echo $this->lang->line('marks_min'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
foreach ($subject_list as $subjet_key => $subjet_value) {
        ?>
                    <tr>
                        <td> <?php
echo $subjet_value->subject_name;
        if ($subjet_value->subject_code != '') {
            echo " (" . $subjet_value->subject_code . ")";
        }
        ?></td>
                        <td><?php echo $this->customlib->dateformat($subjet_value->date_from); ?></td>
                        <td> <?php echo $subjet_value->time_from; ?></td>
                        <td> <?php echo $subjet_value->duration; ?></td>
                        <td> <?php echo $subjet_value->credit_hours; ?></td>
                        <td> <?php echo $subjet_value->room_no; ?></td>
                        <td> <?php echo $subjet_value->max_marks; ?></td>
                        <td> <?php echo $subjet_value->min_marks; ?></td>
                    </tr>
                    <?php
}
    ?>
            </tbody>
        </table>
    </div>
    <?php
}
?>