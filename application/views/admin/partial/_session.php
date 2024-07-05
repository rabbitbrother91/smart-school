<div class="form-group">
    <label class="control-label col-sm-3" for="email"><?php echo $this->lang->line('session'); ?></label>
    <div class="col-sm-9">
        <select class="form-control" name="popup_session">
            <?php
foreach ($sessionList as $session_key => $session_value) {
    ?>
                <option value="<?php echo $session_value['id']; ?>" <?php
if ($sessionData['session_id'] == $session_value['id']) {
        echo "selected='selected'";
    }
    ?>><?php echo $session_value['session']; ?></option>
                        <?php
}
?>
        </select>
    </div>
</div>

