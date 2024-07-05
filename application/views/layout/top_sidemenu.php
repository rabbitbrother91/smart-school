<ul class="sessionul fixedmenu">
    <?php
    if ($this->rbac->hasPrivilege('quick_session_change', 'can_view')) {
    ?>
        <li class="removehover">
            <a data-toggle="modal" data-target="#sessionModal"><span><?php echo $this->lang->line('current_session') . ": " . $this->setting_model->getCurrentSessionName(); ?></span><i class="fa fa-pencil pull-right"></i></a>

        </li>
    <?php } ?>

    <li class="dropdown mega-dropdown">
        <a class="dropdown-toggle drop5" data-toggle="dropdown" href="#" aria-expanded="false">
            <span><?php echo $this->lang->line('quick_links'); ?></span> <i class="fa fa-th pull-right"></i>
        </a>
        <div class="dropdown-menu verticalmenu side-navbar-vertical ">
            <div class="side-navbar-width scroll-area mCustomScrollbar">
                <div class="card-columns-sidebar" style="color: black;">
                    <?php
                    $side_list = side_menu_list('-1');

                    if (!empty($side_list)) {
                        foreach ($side_list as $side_list_key => $side_list_value) {

                            $module_permission = access_permission_sidebar_remove_pipe($side_list_value->access_permissions);

                            $module_access = false;
                            if (!empty($module_permission)) {
                                foreach ($module_permission as $m_permission_key => $m_permission_value) {
                                    $cat_permission = access_permission_remove_comma($m_permission_value);

                                    if ($this->rbac->hasPrivilege($cat_permission[0], $cat_permission[1])) {

                                        $module_access = true;
                                        break;
                                    }
                                }
                            }

                            if ($module_access) {

                                if ($this->module_lib->hasModule($side_list_value->short_code) && $this->module_lib->hasActive($side_list_value->short_code)) {

                    ?>
                                    <div class="card-sidebar">
                                        <h4><i class="<?php echo $side_list_value->icon; ?>"></i> <?php echo $this->lang->line($side_list_value->lang_key); ?></h4>

                                        <?php

                                        if (!empty($side_list_value->submenus)) {
                                        ?>
                                            <ul>
                                                <?php
                                                foreach ($side_list_value->submenus as $submenu_key => $submenu_value) {

                                                    $sidebar_permission = access_permission_sidebar_remove_pipe($submenu_value->access_permissions);
                                                    $sidebar_access     = false;

                                                    if (!empty($sidebar_permission)) {
                                                        foreach ($sidebar_permission as $sidebar_permission_key => $sidebar_permission_value) {
                                                            $sidebar_cat_permission = access_permission_remove_comma($sidebar_permission_value);

                                                            if ($submenu_value->addon_permission != "") {
                                                                if (
                                                                    $this->rbac->hasPrivilege($sidebar_cat_permission[0], $sidebar_cat_permission[1])
                                                                    && $this->auth->addonchk($submenu_value->addon_permission, false)
                                                                ) {
                                                                    $sidebar_access = true;
                                                                    break;
                                                                }
                                                            } else {
                                                                if ($this->rbac->hasPrivilege($sidebar_cat_permission[0], $sidebar_cat_permission[1])) {
                                                                    $sidebar_access = true;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    }

                                                    if ($sidebar_access) {
                                                        if (!empty($submenu_value->permission_group_id)) {
                                                            if (!$this->module_lib->hasActive($submenu_value->short_code)) {
                                                                continue;
                                                            }
                                                        }

                                                ?>
                                                        <li>
                                                            <a href="<?php echo site_url($submenu_value->url); ?>"><i class="fa fa-angle-double-right"></i><?php echo $this->lang->line($submenu_value->lang_key); ?></a>
                                                        </li>

                                                <?php
                                                    }
                                                }

                                                ?>
                                            </ul>

                                        <?php

                                        }
                                        ?>

                                    </div>
                            <?php
                                }
                            }

                            ?>

                    <?php
                        }
                    }
                    ?>


                </div>
            </div>

        </div>
    </li>
</ul>
<script>
    $('.verticalmenu').click(function(event) {
        event.stopPropagation();
    });

    $(".mCustomScrollbar").mCustomScrollbar({
        scrollInertia: 1000,
        mouseWheelPixels: 170,
        autoDraggerLength: false,
    });
</script>