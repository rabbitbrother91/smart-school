<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div id="frame">
                    <div id="sidepanel">
                        <input type="hidden" name="chat_connection_id" value="0">
                        <input type="hidden" name="chat_to_user" value="0">
                        <input type="hidden" name="last_chat_id" value="0">

                        <div id="search">
                            <label for=""><?php echo $this->lang->line('chat_system') ?></label>              
                            <div id="bottom-bar">
                              <button id="addcontact" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i></button>                              
                            </div>
                        </div>
                        <div id="contacts">
                            <ul>

                            </ul>
                        </div>
                    </div>
                    <div class="chatcontent">
                        <div class="contact-profile">
                            <img src="<?php echo base_url('uploads/student_images/no_image.png' . img_time()); ?>" alt="" />
                            <p><?php echo $this->lang->line('select_any_user_to_start_your_chat') ?></p>
                        </div>
                        <div class="messages">
                            <ul>

                            </ul>
                        </div>
                        <div class="message-input relative">
                            <div class="wrap">
                                <input type="text" placeholder="<?php echo $this->lang->line('write_your_message') ?>" class="chat_input" />                                
                                <button class="submit input_submit" disabled="disabled"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.box-header -->
        </div><!-- /.box-header -->
    </section>
</div><!-- /.box-body -->
</div>
</div><!--/.col (left) -->
<!-- right column -->
</div>
</section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <form id="addUser" action="<?php echo site_url('user/chat/adduser') ?>" method="POST">

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('add_contact'); ?><small style="color:red;"> *</small></h4>
                </div>
                <div class="modal-body">
                    <div id="custom-search-input">
                        <div>
                            <input type="text" class="search-query form-control" placeholder="<?php echo $this->lang->line('search'); ?>" />
                        </div>
                    </div>
                    <div class="usersearchlist">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please wait"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    var interval;
    var intervalchat;
    var intervalchatnew;
    var timestamp = '<?php echo time(); ?>';
    var date_time_temp = "";
    function updateTime() {
        date_time_temp = js_yyyy_mm_dd_hh_mm_ss(Date(timestamp));
        timestamp++;
    }
    $(function () {
        setInterval(updateTime, 1000);
    });

$(document).on('input','.chat_input',function(){
     if ($.trim($(this).val()) == '') {
        $('.input_submit').prop('disabled', true);
    }else{
        $('.input_submit').prop('disabled', false);
    }
});

    $(document).on('keyup', '.search-query', function () {
        $this = $(this);
        var keyword = $(this).val();
        if (keyword.length >= 2) {
            $.ajax({
                type: "POST",
                url: baseurl + 'user/chat/searchuser',
                data: {'keyword': keyword},
                dataType: "JSON",
                beforeSend: function () {
                    $this.addClass('dropdownloading');

                },
                success: function (data) {
                    $('.usersearchlist').html("").html(data.page);
                    $this.removeClass('dropdownloading');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $this.removeClass('dropdownloading');
                },
                complete: function (data) {
                    $this.removeClass('dropdownloading');
                }
            })
        } else if (keyword.length >= 0) {
            $('.usersearchlist').html("")
        }
    });

    $(document).ready(function () {
        $.ajax({
            type: "POST",
            url: baseurl + 'user/chat/myuser',
            data: {},
            dataType: "JSON",
            beforeSend: function () {

            },
            success: function (data) {
                $("#contacts ul").html(data.page);
                if (data.status === "1") {
                    clearInterval(intervalchat);
                    intervalchat = setInterval(getChatNotification, 15000);

                    clearInterval(intervalchatnew);
                    intervalchat = setInterval(mynewUser, 25000);

                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

            },
            complete: function (data) {

            }
        })
    });

    $(document).on('click', '.contact', function () {
        var chat_connection_id = $(this).data('chatConnectionId');
        var $this = $(this);
        $.ajax({
            type: "POST",
            url: baseurl + 'user/chat/getChatRecord',
            data: {'chat_connection_id': chat_connection_id},
            dataType: "JSON",
            beforeSend: function () {
                $(".chat_input").val("");
                $('.contact-profile').find('p').html($this.find('.name').text());
                $('.contact-profile').find('img').attr("src", $this.find('img').attr('src'));
                $this.addClass('active').siblings().removeClass('active');
            },
            success: function (data) {

                $this.find('span.notification_count').css("display", "none");
                $(".messages ul").html(data.page);
                $("input[name='chat_connection_id']").val(data.chat_connection_id);
                $("input[name='chat_to_user']").val(data.chat_to_user);
                $("input[name='last_chat_id']").val(data.user_last_chat.id);
                $('.messages').animate({
                    scrollTop: $('.messages')[0].scrollHeight}, "slow"
                        );
                clearInterval(interval);
                interval = setInterval(getChatsUpdates, 2000);
            },
            error: function (jqXHR, textStatus, errorThrown) {

            },
            complete: function (data) {

            }
        })
    });

    $(document).on('keydown', '.chat_input', function (e) {

        switch (e.which) {
            case 13:
                newChatMessage();
                break;
        }
    });

    $(document).on('click', '.input_submit', function (e) {

        message = $(".message-input input").val();
        if ($.trim(message) == '') {
            return false;
        }
        newChatMessage();
        e.preventDefault(); // To prevent the default
    });

    function htmlEncode(str) {
        return String(str).replace(/[^\w. ]/gi, function (c) {
            return '&#' + c.charCodeAt(0) + ';';
        });
    }

    function newChatMessage() {
        message = htmlEncode($(".message-input input").val());
        $('.input_submit').prop('disabled', true);
        if ($.trim(message) == '') {
            return false;
        }

        var chat_connection_id = $("input[name='chat_connection_id']").val();
        var chat_to_user = $("input[name='chat_to_user']").val();

        if (chat_connection_id > 0 && chat_to_user > 0) {

            $.ajax({
                type: "POST",
                url: baseurl + 'user/chat/newMessage',
                data: {'chat_connection_id': chat_connection_id, 'message': message, 'chat_to_user': chat_to_user, 'time': date_time_temp},

                dataType: "JSON",
                beforeSend: function () {

                },
                success: function (data) {
                    var last_chat_id = $("input[name='last_chat_id']").val(data.last_insert_id);
                    $('<li class="replies"><p>' + message + '</p> <span class="time_date_send"> ' + date_time_temp + '</span></li>').appendTo($('.messages ul'));
                    $('.chat_input').val(null);
                    $('.contact.active .preview').html('<span><?php echo $this->lang->line('you') ?>: </span>' + message);

                    $('.messages').animate({
                        scrollTop: $('.messages')[0].scrollHeight}, "slow");

                },
                error: function (jqXHR, textStatus, errorThrown) {

                },
                complete: function (data) {

                }
            })
        }
    }
    ;

    function getChatsUpdates() {
        var end_reach = false;
        var chat_connection_id = $("input[name='chat_connection_id']").val();
        var chat_to_user = $("input[name='chat_to_user']").val();
        var last_chat_id = $("input[name='last_chat_id']").val();
        $.ajax({
            type: "POST",
            url: baseurl + 'user/chat/chatUpdate',
            data: {'chat_connection_id': chat_connection_id, 'chat_to_user': chat_to_user, 'last_chat_id': last_chat_id},
            dataType: "JSON",
            beforeSend: function () {

            },
            success: function (data) {
                var scrollTop = $('.messages').scrollTop();
                if (scrollTop + $('.messages').innerHeight() >= $('.messages')[0].scrollHeight) {
                    end_reach = true;
                }
                $("input[name='last_chat_id']").val(data.user_last_chat.id);
                $('.messages ul').append(data.page);

                if (end_reach) {

                    $('.messages').animate({
                        scrollTop: $('.messages')[0].scrollHeight}, "slow");

                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

            },
            complete: function (data) {

            }
        })
    }

    $(document).on('click', '.usersearchlist ul li', function () {
        $this = $(this);
        $this.addClass('active').siblings().removeClass('active');
    });

    $("#addUser").submit(function (event) {
        event.preventDefault();
        var img = "";
        var userrecord = $('.usersearchlist').find("ul li.active");
        var userId = userrecord.data('userId');
        var userType = userrecord.data('userType');
        var $form = $(this),
                url = $form.attr('action');
        var $button = $form.find("button[type=submit]:focus");
        $.ajax({
            type: "POST",
            url: url,
            data: {'user_type': userType, 'user_id': userId},
            dataType: "JSON",

            beforeSend: function () {
                $button.button('loading');

            },
            success: function (data) {
                if (data.status == 0) {
                    var message = "";
                    $.each(data.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {

                    $("#contacts ul").prepend(newUserLi(data.new_user, data.chat_connection_id)).find('li').addClass('active').siblings().not('li:first').removeClass('active');

                    $(".messages ul").html(data.chat_records);

                    $("input[name='chat_connection_id']").val(data.chat_connection_id);
                    $("input[name='chat_to_user']").val(data.new_user.chat_user_id);
                    $("input[name='last_chat_id']").val(data.user_last_chat.id);
                    $(".chat_input").val("");
                    if (data.new_user.user_type == "student") {
                        new_user_type = "Student";
                        img = baseurl + data.new_user.image;
                    } else if (data.new_user.user_type == "staff") {
                        new_user_type = "Staff";
                        img = baseurl + "uploads/staff_images/" + data.new_user.image;
                    }
                    $('.contact-profile').find('p').html(data.new_user.name);
                    $('.contact-profile').find('img').attr("src", img);
                    $('.messages').animate({
                        scrollTop: $('.messages')[0].scrollHeight}, "slow"
                            );
                    clearInterval(interval);
                    interval = setInterval(getChatsUpdates, 2000);



                    $('#myModal').modal('hide');
                    successMsg(data.message);
                }
                $button.button('reset');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $button.button('reset');
            },
            complete: function (data) {
                $button.button('reset');
            }
        });

    });

    $('#myModal').on('hidden.bs.modal', function (e) {

        $('.usersearchlist').html("");
        $('#addUser').trigger("reset");
    });

    function newUserLi(user_array, chat_connection_id) {

        var new_user_type = "<?php echo $this->lang->line('staff') ?>";
        var img = "";
        if (user_array.user_type == "student") {
            new_user_type = "<?php echo $this->lang->line('student') ?>";
            img = baseurl + user_array.image;
        } else if (user_array.user_type == "staff") {
            new_user_type = "<?php echo $this->lang->line('staff') ?>";
            img = baseurl + "uploads/staff_images/" + user_array.image;
        }
        var newli = "<li class='contact' data-chat-connection-id='" + chat_connection_id + "'>";
        newli += "<div class='wrap'>";
        newli += "<img src='" + img + "' alt=''>";
        newli += "<div class='meta'>";
        newli += "<p class='name'>" + user_array.name + " (" + new_user_type + ")" + "</p>";
        newli += "<p class='preview'></p>";
        newli += "</div>";
        newli += "</div>";
        newli += "<span class='chatbadge notification_count' style='display: none;'>0</span>";
        newli += "</li>";
        return newli;
    }

    function getChatNotification() {
        $.ajax({
            type: "POST",
            url: baseurl + 'user/chat/mychatnotification',
            data: {},
            dataType: "JSON",
            beforeSend: function () {

            },
            success: function (data) {
                var active_user = $('#contacts').find("ul li.active");

                if (data.notifications.length > 0) {

                    $.each(data.notifications, function (index, value) {
                        if (active_user.data('chatConnectionId') != value.chat_connection_id) {

                            $('#contacts').find("ul li[data-chat-connection-id='" + value.chat_connection_id + "']").find('span.notification_count').text(value.no_of_notification).css("display", "block");
                        }

                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

            },
            complete: function (data) {

            }
        })
    }

    function mynewUser() {
        var users_Array = []; // more efficient than new Array()
        $("#contacts ul li").each(function (n) {
            var as = $(this).data('chatConnectionId');
            users_Array.push(as);
        });

        $.ajax({
            type: "POST",
            url: baseurl + 'user/chat/mynewuser',
            data: {'users': users_Array},
            dataType: "JSON",
            beforeSend: function () {

            },
            success: function (data) {
                $("#contacts ul").prepend(data.new_user_list);
            },
            error: function (jqXHR, textStatus, errorThrown) {

            },
            complete: function (data) {

            }
        })
    }

    function js_yyyy_mm_dd_hh_mm_ss(now) {

        var date_format = '<?php echo $this->customlib->getSchoolDateFormat() ?>';
        var new_str = date_format;
        var month_String = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        now = new Date();
        var day = String(now.getDate()).padStart(2, '0');
        var month = String(now.getMonth() + 1).padStart(2, '0'); //January is 0!
        var year = now.getFullYear();
        hour = "" + now.getHours();
        if (hour.length == 1) {
            hour = "0" + hour;
        }
        minute = "" + now.getMinutes();
        if (minute.length == 1) {
            minute = "0" + minute;
        }
        second = "" + now.getSeconds();
        if (second.length == 1) {
            second = "0" + second;
        }
        var inputAttr = {};
        inputAttr["m"] = month;
        inputAttr["M"] = month_String[now.getMonth()];
        inputAttr["d"] = day;
        inputAttr["Y"] = year;
        for (var key in inputAttr) {
            if (!inputAttr.hasOwnProperty(key)) {
                continue;
            }
            new_str = new_str.replace(key, inputAttr[key]);
        }
        return new_str + " " + hour + ":" + minute + ":" + second;
    }
</script>