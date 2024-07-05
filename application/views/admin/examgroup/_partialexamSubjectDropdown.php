<?php
$return_string = "";
if (empty($batch_subjects)) {
    
} else {
    ?>
    <option value=""><?php echo $this->lang->line('select') ?></option>

    <?php
    if (!empty($batch_subjects)) {
        foreach ($batch_subjects as $subject_key => $subject_value) {
          $sub_code=($subject_value['code'] != "") ? " (".$subject_value['code'].")":"";
            ?>
            <option value="<?php echo $subject_value['id'] ?>"><?php 
            echo $subject_value['name'].$sub_code; ?></option>
            <?php
        }
    }
}
?>
