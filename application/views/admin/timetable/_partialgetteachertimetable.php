<?php
if (!empty($timetable)) {
    ?>
    <button type="submit" title="<?php echo $this->lang->line('print'); ?>" class="btn btn-primary btn-xs pull-right print_timetable"  data-staff_id="<?php echo $staff_id;?>" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-print"></i></button>
    <table class="table table-stripped">
        <thead>
            <tr>
                <?php
                foreach ($timetable as $tm_key => $tm_value) {
                ?>

                    <th class="text"><?php echo $tm_key; ?></th>
                <?php
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php
                foreach ($timetable as $tm_key => $tm_value) {
                ?>
                    <td class="text" width="14%">

                        <?php
                        if (!$timetable[$tm_key]) {
                        ?>
                            <div class="attachment-block block-b-noraml clearfix">
                                <b class="text text-danger"><i class="fa fa-times-circle text-danger"></i><?php echo $this->lang->line('not_scheduled'); ?> </b><br>
                            </div>
                            <?php
                        } else {
                            foreach ($timetable[$tm_key] as $tm_k => $tm_kue) {
                            ?>
                                <div class="attachment-block attachment-block-normal clearfix">
                                    <div class="relative attachment-left-space"><i class="fa fa-book"></i><?php echo $this->lang->line('class') ?>: <?php echo $tm_kue->class . "(" . $tm_kue->section . ")"; ?>
                                        <?php echo $this->lang->line('subject') ?>: <?php
                                                                                    echo $tm_kue->subject_name;
                                                                                    if ($tm_kue->subject_code != '') {
                                                                                        echo " (" . $tm_kue->subject_code . ")";
                                                                                    }
                                                                                    ?>
                                    </div>


                                    <div class="relative attachment-left-space"><i class="fa fa-clock-o"></i><?php echo $tm_kue->time_from ?>
                                        <b class="text text-center">-</b>
                                        <strong><?php echo $tm_kue->time_to; ?></strong>
                                    </div>
                                    <div class="relative attachment-left-space"><i class="fa fa-building"></i><?php echo $this->lang->line('room_no') ?>: <?php echo $tm_kue->room_no; ?></div>

                                </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </td>
                <?php
                }
                ?>
            </tr>
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