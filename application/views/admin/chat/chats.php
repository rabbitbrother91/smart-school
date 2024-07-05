<div class="content-wrapper" style="min-height: 946px;">
    <section class="content">
        <div class="box box-primary">
            <div class="row">
                <div class="col-md-4">
                    <div class="chatleftside">
                        <div class="custom-search-input">
                            <div class="input-group col-md-11">
                                <input type="text" class="  search-query form-control" placeholder="Search" />
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                                <button type="button" class="chataddbtn" onclick="new_message()" ><i class="fa fa-plus"></i></button>

                            </div>
                        </div>
                        <div class="chatfrientlist">
                            <ul>
                                <?php
if (!empty($conversation_staff)) {
    foreach ($conversation_staff as $value) {
        if ($value['receiver_id'] != $sender_id) {
            ?>
                                            <li <?php if ($value['receiver_id'] == $receiver_id) {?>  class="active" <?php }?>  onclick="user_chat('<?php echo $sender_id; ?>', '<?php echo $value['receiver_id'] ?>', '1')">
                                                <img src="<?php echo $this->media_storage->getImageURL('uploads/staff_images'.$value['image']); ?>" alt="">
                                                <div class="chat-body">
                                                    <h4><?php echo $value['name'] . " " . $value['surname'] ?></h4>
                                                    <p><?php echo $value['role'] ?></p>
                                                </div>
                                            </li>
                                        <?php
}
    }
}
?>

                                <?php
if (!empty($conversation_parent)) {
    foreach ($conversation_parent as $value) {
        if ($value['receiver_id'] != $sender_id) {
            ?>
                                            <li <?php if ($value['receiver_id'] == $receiver_id) {?>  class="active" <?php }?>  onclick="user_chat('<?php echo $sender_id; ?>', '<?php echo $value['receiver_id'] ?>', '3')">
                                                <img src="<?php echo base_url(); ?>uploads/staff_images/<?php echo $value['image']; ?>" alt="">
                                                <div class="chat-body">
                                                    <h4><?php echo $value['father_name'] ?></h4>
                                                    <p><?php echo $value['role'] ?></p>
                                                </div>
                                            </li>
                                        <?php
}
    }
}
?>
                                <?php
if (!empty($conversation_student)) {
    foreach ($conversation_student as $value) {
        if ($value['receiver_id'] != $sender_id) {
            ?>
                                            <li <?php if ($value['receiver_id'] == $receiver_id) {?>  class="active" <?php }?>  onclick="user_chat('<?php echo $sender_id; ?>', '<?php echo $value['receiver_id'] ?>', '2')">
                                                <img src="<?php echo base_url(); ?>uploads/staff_images/<?php echo $value['image']; ?>" alt="">
                                                <div class="chat-body">
                                                    <h4><?php echo $value['firstname'] . " " . $value['lastname'] ?></h4>
                                                    <p><?php echo $value['role'] ?></p>
                                                </div>
                                            </li>
        <?php
}
    }
}
?>
                            </ul>
                        </div><!--./chatfrientlist-->
                    </div><!--./chatleftside-->
                </div><!--./col-md-4-->
                <div class="col-md-8" id="messages1">
<?php if (!empty($conversation)) {
    ?>
                        <div class="chatrightside">
                            <div class="chat-header">
                                <img src="<?php echo base_url(); ?>uploads/staff_images/<?php echo $recever_name['image']; ?>" alt="">
                                <div class="chat-headerbody">
                                    <h4><?php echo $recever_name['name'] . " " . $recever_name['surname']; ?></h4>
                                    <p><?php echo $recever_name['role']; ?></p>
                                </div>
                                <div class="social-media">
                                    <i class="fa fa-search fa-lg"></i>
                                    <i class="fa fa-paperclip fa-lg" ></i>
                                    <i class="fa fa-bars fa-lg"></i>
                                </div>
                            </div><!--./chat-header-->
                            <div class="mobile" id="parentDiv">
                                <div class="chat-w">
                                    <ul class="chatmsg" >
                                                <?php
foreach ($result as $value) {
        if ($sender_id == $value['sender_id']) {
            ?>
                                                <li>
                                                    <div class="bubble2"><?php
if ($value['status'] == '0') {
                echo $this->lang->line('this_message_has_been_deleted');
            } else {
                echo $value['message'];
            }
            ?><a style="color:white" onclick="delete_message('<?php echo $value['id']; ?>', '<?php echo $sender_id; ?>', '<?php echo $value['receiver_id'] ?>', '<?php echo $type; ?>')"><i class="fa fa-trash"></i></a>
                                                        <br>
            <?php echo date('h:i:s', strtotime($value['created_at'])); ?>
                                                    </div>
                                                </li>
                                                        <?php
} else {
            ?>
                                                <li>
                                                    <div class="bubble"><?php
if ($value['status'] == '0') {
                echo $this->lang->line('this_message_has_been_deleted');
            } else {
                echo $value['message'];
            }
            ?>
                                                        <br>
                                                <?php echo date('h:i:s', strtotime($value['created_at'])); ?>
                                                    </div>
                                                </li>
            <?php
}
    }
    ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="row reply">
                                <div class="col-sm-1 col-xs-1 reply-emojis">
                                    <i class="fa fa-smile-o"></i>
                                </div>
                                <div class="col-sm-10 col-xs-9 reply-main">
                                    <textarea class="form-control" rows="1" id="message"></textarea>
                                </div>
                                <div class="col-sm-1 col-xs-1 reply-send">
                                    <i class="fa fa-send fa-2x" id="reply_message12" onclick="reply_message('<?php echo $sender_id; ?>', '<?php echo $receiver_id; ?>', '<?php echo $type; ?>')" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div><!--./chatrightside-->
<?php } else {
    ?>
                        <div class="chatrightside">
                            <div class="chat-header">
                                <div class="chat-headerbody">

                                </div>
                                <div class="social-media">
                                    <i class="fa fa-search fa-lg"></i>
                                    <i class="fa fa-paperclip fa-lg" ></i>
                                    <i class="fa fa-bars fa-lg"></i>
                                </div>
                            </div><!--./chat-header-->
                            <div class="mobile" id="parentDiv">
                                <div class="chat-w">

                                </div>
                            </div>
                        </div><!--./chatrightside-->
<?php }
?>
                </div><!--./col-md-8-->
            </div><!--./row-->
        </div><!--./box box-primary-->
    </section>
</div>

<div class="modal" id="message_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('new_message'); ?></h4>
            </div>
            <!-- Modal body -->
            <div class="">
                <div class="">
                    <div class="">
                        <form id="form1" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <input type="hidden" name="ci_csrf_token" value=""> 
                                <div id="upload_documents_hide_show">
                                <h4></h4>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('serach') ?><small class="req"> *</small></label>
                                        <input id="user_name" onkeyup="get_user()" name="first_title" placeholder="" type="text" class="form-control" value="" autocomplete="off">
                                        <span class="text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-12" id="users">

                                </div>
                            </div>
                            <div class="modal-footer" style="clear:both">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    function new_message() {
        $('#message_model').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    }

    function reply_message(sender_id, receiver_id, type) {
        var message = $('#message').val();
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
                $('#message').val('');
                load_page(sender_id, receiver_id, type);
            }
        });
    }

    function get_user() {
        var start =<?php echo $start; ?>;
        var user_name = $('#user_name').val();
        var start_status =<?php echo $start; ?>;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>admin/chat/user_list",
            data: {

                start: start,
                user_name: user_name,
            },

            success: function (data) {
                $('#users').html(data);
            }
        });
    }
</script>