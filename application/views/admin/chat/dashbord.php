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

                            </ul>
                        </div><!--./chatfrientlist-->  	
                    </div><!--./chatleftside-->      
                </div><!--./col-md-4--> 
                <div class="col-md-8" id="messages1">
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
                    <?php // } ?>
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
                <h4 class="modal-title"><?php echo $this->lang->line('new') . " " . $this->lang->line('message'); ?></h4>
            </div>
            <!-- Modal body -->
            <div class="">
                <div class="">
                    <div class="">
                        <form id="form1" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <input type="hidden" name="ci_csrf_token" value="">                            <div id="upload_documents_hide_show">                                               

                                <h4></h4>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('search') ?><small class="req"> *</small></label>
                                        <input id="user_name" onkeyup="get_user()" name="first_title" placeholder="" type="text" class="form-control" value="" autocomplete="off">
                                        <span class="text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-12" id="users">

                                </div>
                            </div>
                            <div class="modal-footer" style="clear:both">
                                <a href="<?php echo base_url(); ?>admin/chat" class="btn btn-info pull-right"><?php echo $this->lang->line('close') ?></a>
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
    
    $(document).ready(function () {
        var objDiv = document.getElementById("parentDiv");
        objDiv.scrollTop = objDiv.scrollHeight;
    });

    function get_user() {
        var start =<?php echo $start; ?>;
        var user_name = $('#user_name').val();      

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