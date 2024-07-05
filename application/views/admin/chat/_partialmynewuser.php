<?php
$response_json = isJSON($new_user_list);
if ($response_json) {

    $userList = (json_decode($new_user_list));
    if (!empty($userList)) {
        foreach ($userList->chat_users as $user_key => $user_value) {
            if (!empty($user_value->messages)) {
                $count_noti = getConnectionNotification($userList, $user_value->id);
                ?>
                <li class="contact" data-chat-connection-id="<?php echo $user_value->id; ?>">
                    <div class="wrap">
                        <?php
                        if ($user_value->user_details->image == "") {
                            $img = base_url() . "uploads/staff_images/no_image.png";
                        } else {
                            $img = ($user_value->user_details->user_type == "staff") ? base_url() . "uploads/staff_images/" . $user_value->user_details->image : base_url() . $user_value->user_details->image;
                        }
                        ?>
                        <img src="<?php echo $this->media_storage->getImageURL($img); ?>" alt="">
                        <div class="meta">
                            <p class="name">
                                <?php
                                echo $user_value->user_details->name;
                                echo ($user_value->user_details->user_type == "staff") ? " (" . $this->lang->line('staff') . ")" : " (" . $this->lang->line('student') . ")";
                                ?></p>

                            <p class="preview">
                                <?php
                                if ($chat_user->id != $user_value->messages->chat_user_id) {
                                    echo "<span>" . $this->lang->line('you') . ": </span>";
                                }
                                ?>
                                <?php echo $user_value->messages->message; ?></p>
                        </div>
                    </div>
                    <?php
                    if ($count_noti > 0) {
                        ?>
                        <span class="chatbadge notification_count"><?php echo $count_noti; ?></span> 
                        <?php
                    } else {
                        ?>
                        <span class="chatbadge notification_count displaynone">0</span> 
                        <?php
                    }
                    ?>

                </li>
                <?php
            }
        }
    }
}

function getConnectionNotification($userList, $chat_connection_id) {
    if (!empty($userList->chat_user_notification)) {
        foreach ($userList->chat_user_notification as $notifiction_key => $notifiction_value) {
            if ($notifiction_value->chat_connection_id == $chat_connection_id) {
                return $notifiction_value->no_of_notification;
            }
        }
    }
    return 0;
}
?>