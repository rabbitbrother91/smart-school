<div class="col-md-12">
    <div class="box box-warning">
        <div class="box-header ptbnull">
            <h3 class="box-title titlefix"> <?php echo $this->lang->line('lesson_plan'); ?></h3>
        </div>
        <div class="box-header ">
            <?php
?>
            <div class="box-header text-center">
                <i class="fa fa-angle-left datearrow" onclick="get_weekdates('pre_week', '<?php echo $prev_week_start; ?>')"></i><h3 class="box-title bmedium"> <?php echo $this_week_start . " " . $this->lang->line('to') . " " . $this_week_end; ?></h3> <i class="fa fa-angle-right datearrow" onclick="get_weekdates('next_week', '<?php echo $next_week_start; ?>')"></i>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <div class="download_label"><?php echo $this->lang->line('class_timetable'); ?></div>
                <?php
if (!empty($timetable)) {
    $weekstart = $this->customlib->dateFormatToYYYYMMDD($this_week_start);
    ?>
                    <table class="table table-stripped">
                        <thead>
                            <tr>
                                <?php
$day_counter = 0;
    foreach ($timetable as $tm_key => $tm_value) {

        $next_date = date('Y-m-d', strtotime($weekstart . ' +' . $day_counter . ' day'));
        ?>
                                    <th class="text"><?php echo $this->lang->line(strtolower($tm_key)); ?><br><span class="bmedium"><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($next_date)); ?></span></th>
                                    <?php
$day_counter++;
    }
    ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php  if(!empty($student_data)){
$day_count_timetable = 0;
    foreach ($timetable as $tm_key => $tm_value) {

        $new_date      = date('Y-m-d', strtotime($weekstart . ' +' . $day_count_timetable . ' day'));
        $data          = array('date' => $new_date, 'subject_group_class_section_id' => $student_data[0]->subject_group_class_section_id);
        $syllabus_data = $this->syllabus_model->get_subject_syllabus_student_byDate($data);

        ?>
                                    <td class="text" width="14%">

                                        <?php
if (empty($syllabus_data)) {
            ?>
                                            <div class="attachment-block block-b-noraml clearfix">
                                                <b class="text text-danger"><i class="fa fa-times-circle text-danger"></i><?php echo $this->lang->line('not_scheduled'); ?></b><br>
                                            </div>
                                            <?php
} else {
            foreach ($syllabus_data as $tm_k => $tm_kue) {
                ?>
                                            <div class="mright5 text-right float-right">
                                                <a class="btn btn-default btn-xs pull-left" data-toggle="tooltip" onclick="get_subject_syllabus('<?php echo $tm_kue['id']; ?>')" title="" data-original-title="<?php echo $this->lang->line('view'); ?>" ><i class="fa fa-reorder"></i></a>
                                            </div>

                                                <div class="attachment-block attachment-block-normal clearfix">

                                                    <div class="relative attachment-left-space-blue mt15"><i class="fa fa-book"></i>
                                                        <?php echo $this->lang->line('subject') ?>: <?php
echo $tm_kue['subname'];
                if ($tm_kue['scode'] != '') {
                    echo " (" . $tm_kue['scode'] . ")";
                }
                ?>
                                                    </div>
                                                    <div class="relative attachment-left-space-blue"><i class="fa fa-clock-o"></i><?php echo $tm_kue['time_from']; ?>
                                                    <b class="text text-center">-</b>

                                                    <strong class="text-blue-light"><?php echo $tm_kue['time_to']; ?></strong></div>

                                                </div>
                                                <?php
}
        }
        ?>
                                    </td>
                                    <?php
$day_count_timetable++;
    }
    ?>
                            </tr>
                        </tbody>
                    </table>
                    <?php
}  }
?>
            </div>
        </div>
    </div>
</div>