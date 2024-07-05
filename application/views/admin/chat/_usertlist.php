<?php
if (!empty($staff)) {
    foreach ($staff as $value) {
        if ($value['id'] != $sender_id) {
            ?>
            <div class="row reply" style="corsor:pointer" >
                <div class="col-sm-5 col-xs-5 reply-emojis">
                    <a href="#" onclick="start_chat('<?php echo $value['id'] ?>', '<?php echo $sender_id; ?>', '1')" >
                        <?php echo $value['name'] . " (" . $this->lang->line(strtolower($value['role'])) . ")"; ?></a>
                </div>

            </div>
            <?php
        }
    }
}

if (!empty($parent)) {
    foreach ($parent as $value) {
        if ($value['parent_id'] != $sender_id) {
            ?>
            <div class="row reply"  >
                <div class="col-sm-5 col-xs-5 reply-emojis">
                    <a onclick="start_chat('<?php echo $value['parent_id'] ?>', '<?php echo $sender_id; ?>', '3')" ><?php echo $value['father_name'] . " (" . $this->lang->line(strtolower($value['role'])) . ")"; ?></a>

                </div>

            </div>
            <?php
        }
    }
}
if (!empty($student)) {
    foreach ($student as $value) {
        if ($value['id'] != $sender_id) {
            ?>
            <div class="row reply"  >
                <div class="col-sm-5 col-xs-5 reply-emojis">
                    <a onclick="start_chat('<?php echo $value['id'] ?>', '<?php echo $sender_id; ?>', '2')" ><?php echo $value['firstname'] . " " . $value['lastname'] . " (" . $this->lang->line(strtolower($value['role'])) . ")"; ?></a>

                </div>

            </div>
            <?php
        }
    }
}
?>
<input type="hidden" value="<?php echo $start_status ?>" id="start_status">
<script>
    function start_chat(receiver_id, sender_id, type) {        
        var message = 'Hii';
        var start_status = $('#start_status').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>admin/chat/reply",
            data: {
                sender_id: sender_id,
                receiver_id: receiver_id,
                message: message,
                type: type
            },

            success: function (data) {

                $('#user_name').val('');
                $('#users').html('');
                if (data == 0) {

                } else {
                    $('#message_model').modal('hide');
                    $('#message').val('');
                    load_page(sender_id, receiver_id, type);
                }
            }
        });

    }
</script>