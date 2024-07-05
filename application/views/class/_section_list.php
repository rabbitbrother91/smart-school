<option value=""><?php echo $this->lang->line('select'); ?></option>
<?php foreach ($sections as $value) { ?>
    <option value="<?php echo $value['id']; ?>"><?php echo $value['section'] ?></option>
    <?php
}
?>
