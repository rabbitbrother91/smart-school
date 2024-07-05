<?php
$my_role = $this->customlib->getStaffRole();
$role    = json_decode($my_role);
if ($role->id == 7) {
    $staff_id = $_POST['staff_id'];
} else {
    $staff_id = $staff_id;
}

?>
<div class="box-header text-center">
    <i class="fa fa-angle-left datearrow" onclick="get_weekdates('pre_week', '<?php echo $prev_week_start; ?>', '<?php echo $staff_id; ?>')"></i><h3 class="box-title bmedium"> <?php echo $this_week_start . " " . $this->lang->line('to') . " " . $this_week_end; ?></h3> <i class="fa fa-angle-right datearrow" onclick="get_weekdates('next_week', '<?php echo $next_week_start; ?>', '<?php echo $staff_id; ?>')"></i>
    <input type="hidden" id="this_week_start" value="<?php echo $this_week_start; ?>">
</div>

<div class="table-responsive">
    <?php if (!empty($timetable)) {
    ?>
        <table class="table table-stripped">
            <thead>
                <tr>
                    <?php
$day_counter = 0;
    foreach ($timetable as $tm_key => $tm_value) {

        $new_date1 = $this->customlib->dateFormatToYYYYMMDD($this_week_start);
        $next_date = date('Y-m-d', strtotime($new_date1 . ' +' . $day_counter . ' day'));

        $day_counter++;
        ?>
                        <th class="text"><?php echo $this->lang->line(strtolower($tm_key)); ?><br/><span class="bmedium"><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($next_date)); ?></span></th>
                    <?php }
    ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
$day_counter = 0;
    foreach ($timetable as $tm_key => $tm_value) {

        $new_date1 = $this->customlib->dateFormatToYYYYMMDD($this_week_start);
        $new_date  = date('Y-m-d', strtotime($new_date1 . ' +' . $day_counter . ' day'));

        $day_counter++;
        ?>
                        <td class="text" width="14%">
                            <?php if (!$timetable[$tm_key]) {?>
                                <div class="attachment-block block-b-noraml clearfix">
                                    <b class="text text-danger"><i class="fa fa-times-circle text-danger"></i> <?php echo $this->lang->line('not_scheduled'); ?></b><br>
                                </div>
                                <?php
} else {
            foreach ($timetable[$tm_key] as $tm_k => $tm_kue) {

                $subject_group_subject_class_section = $this->lessonplan_model->getsubject_group_class_sectionsId($tm_kue->class_id, $tm_kue->section_id, $tm_kue->subject_group_id);

                $subject_syllabus = $this->syllabus_model->get_subject_syllabusdata($tm_kue->subject_group_subject_id, date('Y-m-d', strtotime($new_date)), $role->id, $staff_id, $tm_kue->time_from, $tm_kue->time_to, $subject_group_subject_class_section['id']);

                if ($subject_syllabus[0]['total'] > 0) {
                    $action = $subject_syllabus[0]['id'];
                } else {
                    $action = 0;
                }
                if ($action != 0) {
                    ?>
                                        <div class="mright5 text-right float-right" id="hide_<?php echo $action; ?>">
                                            <a class="btn btn-default btn-xs mt5 mb5 pull-left"  data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('view') ?>" onclick="get_subject_syllabus(<?php echo $action; ?>)"><i class="fa fa-reorder"></i></a>

                                            <?php if ($this->rbac->hasPrivilege('manage_lesson_plan', 'can_edit')) {?>
                                                <a class="btn btn-default btn-xs mt5 mb5 pull-left"  data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('edit') ?>" onclick="subject_syllabusedit(<?php echo $action; ?>)"><i class="fa fa-pencil"></i></a>
                                                <a class="btn btn-default btn-xs mt5 mb5 pull-left"  data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('delete') ?>" onclick="subject_syllabusdelete('<?php echo $action; ?>', '<?php echo $tm_kue->subject_group_subject_id; ?>', '<?php echo $tm_kue->time_from ?>', '<?php echo $tm_kue->time_to; ?>', '<?php echo $this->customlib->dateformat($new_date); ?>', '<?php echo $subject_group_subject_class_section['id']; ?>')"><i class="fa fa-remove"></i></a>
                                                <?php
}
                    ?>
                                        </div>
                                        <?php
} else {
                    ?>
                                        <?php if ($this->rbac->hasPrivilege('manage_lesson_plan', 'can_add')) {?>
                                        <div class="mright5 text-right float-right">
                                            <a class="btn btn-default btn-xs mt5 mb5 pull-left"  data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('add') ?>" onclick="add_syllabus('<?php echo $tm_kue->subject_group_subject_id; ?>', '<?php echo $tm_kue->time_from ?>', '<?php echo $tm_kue->time_to; ?>', '<?php echo date($this->customlib->getSchoolDateFormat(), strtotime($new_date)); ?>', '<?php echo $subject_group_subject_class_section['id']; ?>', '<?php echo $staff_id; ?>')"><i class="fa fa-plus"></i></a>
                                        </div>
                                            <?php
}
                }
                ?>
                                     <div class="attachment-block attachment-block-normal clearfix">
                                        <div class="relative attachment-left-space-blue mt15"><i class="fa fa-book"></i><?php echo $this->lang->line('subject') ?>: <?php
echo $tm_kue->subject_name;
                if ($tm_kue->subject_code != '') {
                    echo " (" . $tm_kue->subject_code . ")";
                }
                ?></div>
                                        <div class="relative attachment-left-space-blue"><i class="fa fa-clock-o"></i><?php echo $this->lang->line('class') ?>: <?php echo $tm_kue->class . "(" . $tm_kue->section . ")"; ?>
                                        <strong class="text-blue-light"><?php echo $tm_kue->time_from ?></strong>
                                        <b class="text text-center">-</b>
                                        <strong class="text-blue-light"><?php echo $tm_kue->time_to; ?></strong>
                                        </div>
                                        <div class="relative attachment-left-space-blue"><i class="fa fa-building"></i><?php echo $this->lang->line('room_no'); ?>: <?php echo $tm_kue->room_no; ?></div>
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
</div>
<script>
    $('#created_for').val('<?php echo $staff_id; ?>');
    function subject_syllabusdelete(syllabus_id) {
        if (confirm('<?PHP echo $this->lang->line('delete_confirm') ?>')) {
            $.ajax({
                type: "POST",
                url: base_url + "admin/syllabus/delete_subject_syllabus",
                data: {'id': syllabus_id},
                success: function (data) {
                    successMsg('<?php echo $this->lang->line("delete_message"); ?>');
                    $('#hide_' + syllabus_id).html('');
                    get_weekdates('pre_week', '<?php echo $this_week_start; ?>', '<?php echo $staff_id; ?>');
                },
            });
        }
    }
</script>