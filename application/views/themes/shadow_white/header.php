<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <nav class="navbar">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-3">
                        <span class="sr-only"><?php echo $this->lang->line('toggle_navigation'); ?></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand logo" href="<?php echo base_url(); ?>"><img src="<?php echo base_url($front_setting->logo); ?>" alt=""></a>
                </div>

                <div class="collapse navbar-collapse" id="navbar-collapse-3">
                    <ul class="nav navbar-nav navbar-right">
                        <?php  
                        foreach ($main_menus as $menu_key => $menu_value) {
                            $submenus = false;
                            $cls_menu_dropdown = "";
                            $menu_selected = "";
                            if ($menu_value['page_slug'] == $active_menu) {
                                $menu_selected = "active";
                            }
                            if (!empty($menu_value['submenus'])) {
                                $submenus = true;
                                $cls_menu_dropdown = "dropdown";
                            }
                            ?>

                            <li class="<?php echo $menu_selected . " " . $cls_menu_dropdown; ?>" >
                                <?php
                                if (!$submenus) {
                                    $top_new_tab = '';
                                    $url = '#';
                                    if ($menu_value['open_new_tab']) {
                                        $top_new_tab = "target='_blank'";
                                    }
                                    if ($menu_value['ext_url']) {
                                        $url = $menu_value['ext_url_link'];
                                    } else {
                                        $url = site_url($menu_value['page_url']);
                                    }
                                    ?>

                                    <a href="<?php echo $url; ?>" <?php echo $top_new_tab; ?>><?php echo $menu_value['menu']; ?></a>

                                    <?php
                                } else {
                                    $child_new_tab = '';
                                    $url = '#';
                                    ?>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $menu_value['menu']; ?> <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <?php
                                        foreach ($menu_value['submenus'] as $submenu_key => $submenu_value) {
                                            if ($submenu_value['open_new_tab']) {
                                                $child_new_tab = "target='_blank'";
                                            }
                                            if ($submenu_value['ext_url']) {
                                                $url = $submenu_value['ext_url_link'];
                                            } else {
                                                $url = site_url($submenu_value['page_url']);
                                            }
                                            ?>
                                            <li><a href="<?php echo $url; ?>" <?php echo $child_new_tab; ?> ><?php echo $submenu_value['menu'] ?></a></li>
                                            <?php
                                        }
                                        ?>

                                    </ul>

                                    <?php
                                }
                                ?>


                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </nav><!-- /.navbar -->
        </div><!--./col-md-12-->
    </div><!--./row-->
</div><!--./container-->