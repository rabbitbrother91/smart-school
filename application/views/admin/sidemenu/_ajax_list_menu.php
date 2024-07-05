
<div id="sortable-div-menu" class="col-sm-12 col-xs-12 col-md-12 addSub">
   <div class="padLR10"></div>

   <div id="dragdrop" class="col-sm-6 col-md-6">
      <div class="well-list clearfix">
         <div class="header"><?php echo $this->lang->line('menu_list'); ?></div>

<div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel-group sortable-list_main" id="accordion" role="tablist" aria-multiselectable="true">

         <?php
if (!empty($menus)) {

    foreach ($menus as $menu_key => $menu_value) {
        ?>
                                            <div class="panel panel-default" id="<?php echo $menu_value->id; ?>">
          <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title relative">

              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $menu_value->id ?>" aria-expanded="true" aria-controls="collapse<?php echo $menu_value->id ?>">

                                <?php echo $this->lang->line($menu_value->lang_key); ?>
                            </a>

                            <?php if ($this->rbac->hasPrivilege('sidebar_menu', 'can_view')) {?>
                            <a href="javascript:void(0);" data-record-id="<?php echo $menu_value->id; ?>" class="btn btn-xs edit_menu more-less-right hidden" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('edit'); ?>"  data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i>" ><i class="more-less fa fa-pencil"></i></a>
                            <?php }?>

            </h4>
          </div>
          <div id="collapse<?php echo $menu_value->id ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
                <?php if ($this->rbac->hasPrivilege('sidebar_menu', 'can_view')) {?>
                <button class="btn btn-primary btn-xs pull-right mb10 add_sub_menu hidden" data-menu-id="<?php echo $menu_value->id ?>" > <?php echo $this->lang->line('add_sub_menu'); ?></button>
                <?php }?>
     <div class="clearfix"></div>
                                                <?php
if (!empty($menu_value->submenus)) {
            ?>
                                                    <ul class="sortable-item ui-sortable list-group mb0" data-record_name="<?php echo $menu_value->id ?>">
                                                        <?php
foreach ($menu_value->submenus as $submenu_key => $submenu_value) {
                ?>
                                                            <li id="<?php echo $submenu_value->id; ?>" class="list-group-item-sort text-left">
                                                                <span class="sort-action">
                                                                     <?php if ($this->rbac->hasPrivilege('sidebar_menu', 'can_view')) {?>
                                                                    <a href="javascript:void(0);" data-record-id="<?php echo $submenu_value->id; ?>" class="btn btn-xs edit_sub_menu hidden" data-toggle="tooltip"
                                                                       data-original-title="<?php echo $this->lang->line('edit'); ?>"  data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i>"><i class="fa fa-pencil"></i>
                                                                   </a>
                                                                     <?php }?>


                                                                </span> <i class="fa fa-arrows"></i> <?php
echo $this->lang->line($submenu_value->lang_key);
                ?>
                                                            </li>
                                                            <?php
}
            ?>

                                                        </ol>
                                                        <?php
} else {
            ?>
                                                        <div class="alert alert-danger">
                                                            <?php echo $this->lang->line('no_record_found') ?>
                                                        </div>
                                                        <?php
}
        ?>
            </div>
          </div>
        </div>

                                    <?php
}
}
?>
      </div>
    </div>
  </div>
</div>
      </div>
   </div>
   <div id="dragdrop" class="col-sm-6 col-md-6">
      <div id="my-timesheet" class="well-list clearfix">
         <div class="header"><?php echo $this->lang->line('selected_sidebar_menus'); ?></div>
<div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel-group sortable-list_sidebar" id="accordion" role="tablist" aria-multiselectable="true">
           <?php
if (!empty($active_menus)) {

    foreach ($active_menus as $menu_key => $menu_value) {
        ?>
                                            <div class="panel panel-default" id="<?php echo $menu_value->id; ?>">
          <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title relative">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $menu_value->id ?>" aria-expanded="true" aria-controls="collapse<?php echo $menu_value->id ?>">
                                <?php echo $this->lang->line($menu_value->lang_key); ?>
                            </a>
                             <a href="javascript:void(0);" data-record-id="<?php echo $menu_value->id; ?>" class="btn btn-xs edit_menu more-less-right hidden" data-toggle="tooltip"
                                                                       data-original-title="<?php echo $this->lang->line('edit'); ?>"  data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i>" ><i class="more-less fa fa-pencil"></i></a>
            </h4>
          </div>
          <div id="collapse<?php echo $menu_value->id ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
                <button class="btn btn-primary btn-xs pull-right mb10 add_sub_menu hidden" data-menu-id="<?php echo $menu_value->id ?>" > <?php echo $this->lang->line('add_sub_menu'); ?></button>
     <div class="clearfix"></div>

                                                <?php

        if (!empty($menu_value->submenus)) {
            ?>
                                                    <ul class="sortable-item ui-sortable list-group mb0" data-record_name="<?php echo $menu_value->id ?>">
                                                        <?php
foreach ($menu_value->submenus as $submenu_key => $submenu_value) {
                ?>
                                                            <li id="<?php echo $submenu_value->id; ?>" class="list-group-item-sort text-left">
                                                                <span class="sort-action">

                                                                    <a href="javascript:void(0);" data-record-id="<?php echo $submenu_value->id; ?>" class="btn btn-xs edit_sub_menu hidden" data-toggle="tooltip"
                                                                       data-original-title="<?php echo $this->lang->line('edit'); ?>"  data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i>"><i class="fa fa-pencil"></i>
                                                                   </a>
                                                                </span> <i class="fa fa-arrows"></i> <?php
echo $this->lang->line($submenu_value->lang_key);
                ?>
                                                            </li>
                                                            <?php
}
            ?>

                                                        </ol>
                                                        <?php
} else {
            ?>
                                                        <div class="alert alert-danger">
                                                            <?php echo $this->lang->line('no_record_found') ?>
                                                        </div>
                                                        <?php
}
        ?>
            </div>
          </div>
        </div>

                                    <?php
}
}
?>
      </div>
    </div>
  </div>
</div>
      </div>
   </div>
</div>