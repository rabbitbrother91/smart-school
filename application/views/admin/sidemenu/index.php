
<style type="text/css">

.well-list {
    min-height: 20px;
    padding: 8px;
    margin-bottom: 20px;
    /* background-color: #f5f5f5; */
    border: 1px solid #e3e3e3;
    /* border-radius: 4px; */
    -webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 5%);
    box-shadow: inset 0 1px 1px rgb(0 0 0 / 5%);
}

#dragdrop  .header {
    color: #000;
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 6px;
}

.placeholder {
    background-color: #e7e7e7;
    border: 1px dashed #666;
    height: 40px;
    margin-bottom: 5px
}

.panel-group .panel {
    border-radius: 0;
    box-shadow: none;
    border-color: #EEEEEE;
}

.panel-default > .panel-heading {
    padding: 0;
    border-radius: 0;
    color: #212121;
    background-color: #FAFAFA;
    border-color: #EEEEEE;
}

    .panel-title {
        font-size: 14px;
        min-height: 36px;
    }

    .panel-title > a {
        display: block;
        padding:10px 15px;
        text-decoration: none;
    }

    .more-less {
        float: right;
        line-height: 16px;
        color: #212121;
    }

    .panel-default > .panel-heading + .panel-collapse > .panel-body {
        border-top-color: #EEEEEE;
    }
.more-less-right{position: absolute; right: 0rem; top: 0rem}
#div_fade {
    display: none;
    position:absolute;
    top: 0%;
    left: 0%;
    width: 100%;
    height: 100%;
    background-color: #edededcc;
    z-index: 1001;
    -moz-opacity: 0.8;
    opacity: .6;
    filter: alpha(opacity=80);
}

#div_modal {
    display: none;
    position: absolute;
    top: 35%;
    left: 45%;
    z-index: 1002;
    text-align: center;
    overflow: auto;
}

.panel-group {
    margin-bottom: 10px;
    min-height: 30px;
}

</style>

<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat();?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
if ($this->rbac->hasPrivilege('expense', 'can_add')) {
    ?>

                <!-- left column -->
            <?php }?>
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('sidebar_menu'); ?></h3>

                        <div class="box-tools pull-right hidden">
                            <?php if ($this->rbac->hasPrivilege('sidebar_menu', 'can_view')) {?>
                            <button class="btn btn-primary btn-sm hidden" data-toggle="modal" data-target="#menuModal"> <?php echo $this->lang->line('add_menu'); ?></button>
                            <?php }?>

                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="ptt10 pb10 minheight303 overflow-hidden">

 <div id="div_fade"></div>
     <div id="div_modal">
                            <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span>
                            <img id="loader" src="<?php //echo base_url('backend/images/chatloading.gif'); ?>">
                        </div>
                        <div class="content_body">

                        </div>
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- Modal -->
<div id="menuModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
       <form class="form-horizontal" method="POST" action="<?php echo site_url("admin/sidemenu/add_menu") ?>" id="form_add_menu">
              <input type="hidden" name="menu_id" value="0" id="menu_id">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo $this->lang->line('add_menu'); ?></h4>
      </div>
      <div class="modal-body">
        <div class="box-body pt0 pb0">
  <div class="form-group row">
    <label class="control-label col-sm-3" for="menu"><?php echo $this->lang->line('menu'); ?></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="menu" name="menu" placeholder="<?php echo $this->lang->line('enter_menu'); ?>">
    </div>
  </div>
  <div class="form-group row">
    <label class="control-label col-sm-3" for="lang_key"><?php echo $this->lang->line('language_key'); ?></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="lang_key" name="lang_key" placeholder="<?php echo $this->lang->line('enter_language_key'); ?>">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-3" for="icon"><?php echo $this->lang->line('icon'); ?></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="icon" name="icon" placeholder="<?php echo $this->lang->line('enter_icon'); ?>">
    </div>
  </div>
    <div class="form-group">
    <label class="control-label col-sm-3" for="activate_menu"><?php echo $this->lang->line('active_menu_array_key'); ?></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="activate_menu" name="activate_menu" placeholder="<?php echo $this->lang->line('enter_active_menu_array_key'); ?>">
       <small class="text-secondary">Like Front Office Top Array Key</small>
    </div>
  </div>
 <div class="form-group">
    <label class="control-label col-sm-3" for="access_permissions"><?php echo $this->lang->line('access_permissions'); ?></label>
    <div class="col-sm-9">
      <textarea class="form-control" id="access_permissions" name="access_permissions" placeholder="<?php echo $this->lang->line('enter_permissions'); ?>" rows='5'></textarea>
      <small class="text-secondary">Like ('collect_fees', 'can_view') || ('search_fees_payment', 'can_view')</small>
    </div>
  </div>
  <div class="form-group mb0">
    <div class="col-sm-offset-3 col-sm-9">
      <div class="checkbox">
        <label><input type="checkbox" name="sidebar_view" value="1" checked="checked"> <?php echo $this->lang->line('list_in_sidebar'); ?></label>
      </div>
    </div>
  </div>
      </div></div>
      <div class="modal-footer">
       <button type="submit" class="btn btn-primary btn-sm" id="load" data-action="collect" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i><?php echo $this->lang->line('processing'); ?> " autocomplete="off"> <?php echo $this->lang->line('save'); ?> </button>
      </div>
</form>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="submenuModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
       <form class="form-horizontal" method="POST" action="<?php echo site_url("admin/sidemenu/add_sub_menu") ?>" id="form_add_submenu">
        <input type="hidden" name="menu_id" value="0" id="menu_id">
        <input type="hidden" name="submenu_id" value="0" id="submenu_id">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo $this->lang->line('add_sub_menu'); ?></h4>
      </div>
      <div class="modal-body">
  <div class="form-group">
    <label class="control-label col-sm-3" for="menu"><?php echo $this->lang->line('sub_menu'); ?></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="menu" name="menu" placeholder="<?php echo $this->lang->line('enter_menu'); ?>">
    </div>
  </div>
    <div class="form-group">
    <label class="control-label col-sm-3" for="lang_key"><?php echo $this->lang->line('language_key'); ?></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="lang_key" name="lang_key" placeholder="<?php echo $this->lang->line('enter_language_key'); ?>">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-3" for="url"><?php echo $this->lang->line('url_controller_action'); ?></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="url" name="url" placeholder="<?php echo $this->lang->line('enter_url'); ?>">
    </div>
  </div>
    <div class="form-group">
    <label class="control-label col-sm-3" for="controller"><?php echo $this->lang->line('controller'); ?></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="activate_controller" name="activate_controller" placeholder="<?php echo $this->lang->line('enter_controller'); ?>">
    </div>
  </div>
     <div class="form-group">
    <label class="control-label col-sm-3" for="controller"><?php echo $this->lang->line('methods'); ?></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="activate_methods" name="activate_methods" placeholder="<?php echo $this->lang->line('enter_methods'); ?>">
       <small class="text-secondary">Like index,edit,view,delete</small>
    </div>
  </div>
   <div class="form-group">
    <label class="control-label col-sm-3" for="access_permissions"><?php echo $this->lang->line('Access Permissions'); ?></label>
    <div class="col-sm-9">
      <textarea class="form-control" id="access_permissions" name="access_permissions" placeholder="<?php echo $this->lang->line('enter_permissions'); ?>" rows='5'></textarea>
      <small class="text-secondary">Like ('collect_fees', 'can_view') || ('search_fees_payment', 'can_view')</small>
    </div>
  </div>
   <div class="form-group">
    <label class="control-label col-sm-3" for="controller"><?php echo $this->lang->line('check_addon_permission'); ?></label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="addon_permission" name="addon_permission" placeholder="<?php echo $this->lang->line('enter_permissions'); ?>">
       <small class="text-secondary">if link is for addon, Provide addon short code to check addon is registered</small>
    </div>
  </div>
      </div>
      <div class="modal-footer">
       <button type="submit" class="btn btn-primary btn-sm" id="load" data-action="collect" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i><?php echo $this->lang->line('processing'); ?> " autocomplete="off"> <?php echo $this->lang->line('save'); ?> </button>
      </div>
</form>
    </div>
  </div>
</div>

<script type="text/javascript">
     $(document).ready(function(){
get_menus();
$("#form_add_menu").on('submit', (function (e) {
    e.preventDefault();

        var $this = $(this).find("button[type=submit]:focus");
        var form = $(this);
        var url = form.attr('action');
        var form_data = form.serializeArray();

            $.ajax({
            url: url,
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
            $this.button('loading');

            },
            success: function (res)
            {

                if (res.status == 0) {
                var message = "";
                $.each(res.error, function (index, value) {
                message += value;
                });
                errorMsg(message);

                } else {
                $('#menuModal').modal('hide');
                successMsg(res.message);
                get_menus();

                }
            },
            error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                    $this.button('reset');
            },
            complete: function () {
                    $this.button('reset');
            }
            });
    }));

$("#form_add_submenu").on('submit', (function (e) {
    e.preventDefault();
        var $this = $(this).find("button[type=submit]:focus");
        var form = $(this);
        var url = form.attr('action');
        var form_data = form.serializeArray();
            $.ajax({
            url: url,
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
            $this.button('loading');

            },
            success: function (res)
            {

                if (res.status == 0) {
                var message = "";
                $.each(res.error, function (index, value) {
                message += value;
                });
                errorMsg(message);
                } else {
                $('#submenuModal').modal('hide');
                successMsg(res.message);
                get_menus();

                }
            },
            error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                    $this.button('reset');
            },
            complete: function () {
                    $this.button('reset');
            }
            });
    }));
    });

let get_menus = () => {

            $.ajax({
            url: base_url+'admin/sidemenu/ajax_list_menu',
            type: "POST",
            dataType: 'json',
            beforeSend: function () {
            $('#div_fade').css("display", "block");
            $('#div_modal').css("display", "block");
            },
            success: function (res)
            {
                $('.content_body').html(res.page);
                $('.sortable-list_main').sortable({
                   connectWith: '#sortable-div-menu .panel-group',
                    placeholder: 'placeholder',
                     handle: '.panel-title',
                    opacity: 0.8,
                    cursor: 'move' ,
                     refresh: function( event, ui ) {
                        console.log("sdfdsfs");
                     }
                });

                $('.sortable-list_sidebar').sortable({
                   connectWith: '#sortable-div-menu .panel-group',
                    placeholder: 'placeholder',
                     handle: '.panel-title',
                    opacity: 0.8,
                    cursor: 'move' ,
        update: function (event, ui) {
            var data = $(this).sortable('toArray');
            $.ajax({
                type: "POST",
                url: base_url + "admin/sidemenu/menu_updateorder",
                data: {"items": data},
                dataType: "json",

                beforeSend: function () {
                    $('#div_fade,#div_modal').css({'display': 'block'});
                },
                success: function (data) {
                    if (data.status) {
                        successMsg(data.msg);
                    } else {
                        errorMsg(data.msg);
                    }
                    $('#div_fade,#div_modal').css({'display': 'none'});
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                    $('#div_fade,#div_modal').css({'display': 'none'});
                },
                complete: function () {
                    $('#div_fade,#div_modal').css({'display': 'none'});
                }
            });
        }
                });

                //============submenu======

   $('.sortable-item').sortable({
        connectWith: '.sortable-item',
        update: function (event, ui) {
            var record_name = $(this).data('record_name');
            var data = $(this).sortable('toArray');            
            $.ajax({
                type: "POST",
                url: base_url + "admin/sidemenu/submenu_updateorder",
                data: {"items": data, "belong_to": record_name},
                dataType: "json",

                beforeSend: function () {
                    $('#div_fade,#div_modal').css({'display': 'block'});
                },
                success: function (data) {
                    if (data.status) {
                        successMsg(data.msg);
                    } else {
                        errorMsg(data.msg);
                    }
                    $('#div_fade,#div_modal').css({'display': 'none'});
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                    $('#div_fade,#div_modal').css({'display': 'none'});
                },
                complete: function () {
                    $('#div_fade,#div_modal').css({'display': 'none'});
                }
            });
        }
    });

            //====================


              $('#div_fade').fadeOut(400);
              $('#div_modal').fadeOut(400);
            },
            error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
              $('#div_fade').fadeOut(400);
              $('#div_modal').fadeOut(400);
            },
            complete: function () {
              $('#div_fade').fadeOut(400);
              $('#div_modal').fadeOut(400);
            }

            });
}

$('#menuModal,#submenuModal').on('hidden.bs.modal', function (event) {
     reset_form('#form_add_menu');
     reset_form('#form_add_submenu');
});

$(document).on('click','.add_sub_menu',function(){
     var $this = $(this);
     let record_data= $(this).data();
     $('#menu_id',$('#submenuModal')).val(record_data.menuId);
     $('#submenuModal').modal('show');
});

$(document).on('click','.edit_sub_menu',function(){
   var $this = $(this);
   let record_data= $(this).data();
   $.ajax({
                type: "POST",
                url: base_url + "admin/sidemenu/getsubmenu",
                data: {"submenu_id": record_data.recordId},
                dataType: "json",

                beforeSend: function () {
                     $this.button('loading');
                },
                success: function (data) {
                  let submenu=(data.sub_menu);
                    $('#submenu_id',$('#submenuModal')).val(submenu.id);
                    $('#menu_id',$('#submenuModal')).val(submenu.sidebar_menu_id);
                    $("input[name='lang_key']",$('#submenuModal')).val(submenu.lang_key);
                    $("textarea[name='access_permissions']",$('#submenuModal')).val(submenu.access_permissions);
                    $("input[name='menu']",$('#submenuModal')).val(submenu.menu);
                    $("input[name='activate_controller']",$('#submenuModal')).val(submenu.activate_controller);
                    $("input[name='activate_methods']",$('#submenuModal')).val(submenu.activate_methods);
                    $("input[name='addon_permission']",$('#submenuModal')).val(submenu.addon_permission);
                    $("input[name='url']",$('#submenuModal')).val(submenu.url);
                    $('#submenuModal').modal('show');
                  $this.button('reset');
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                  $this.button('reset');
                },
                complete: function () {
                  $this.button('reset');
                }
            });
});

$(document).on('click','.edit_menu',function(){
   var $this = $(this);
   let record_data= $(this).data();
   $.ajax({
                type: "POST",
                url: base_url + "admin/sidemenu/getmenu",
                data: {"menu_id": record_data.recordId},
                dataType: "json",

                beforeSend: function () {
                     $this.button('loading');
                },
                success: function (data) {
                  let menu=(data.menu);
                    $('#menu_id',$('#menuModal')).val(menu.id);
                    $('#menu',$('#menuModal')).val(menu.menu);
                    $("input[name='lang_key']",$('#menuModal')).val(menu.lang_key);
                    $("input[name='icon']",$('#menuModal')).val(menu.icon);
                    $("textarea[name='access_permissions']",$('#menuModal')).val(menu.access_permissions);
                    $("input[name='activate_menu']",$('#menuModal')).val(menu.activate_menu);
                    $('#menuModal').modal('show');
                  $this.button('reset');
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                  $this.button('reset');
                },
                complete: function () {
                  $this.button('reset');
                }
            });
});
</script>