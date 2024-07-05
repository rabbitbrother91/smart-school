<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('assign_permission'); ?> (<?php echo $role['name'] ?>) </h3>
                    </div>
                    <form id="form1" action="<?php echo site_url('admin/roles/permission/' . $role['id']) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                        <div class="box-body">

                            <?php echo $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="role_id" value="<?php echo $role['id'] ?>"/>
                            <div class="table-responsive">
                                 
                                <table class="table table-striped table-bordered table-hover  table-fixed-header">
                                    <thead class="header">
                                        <tr>
                                            <th><?php echo $this->lang->line('module'); ?></th>
                                            <th><?php echo $this->lang->line('feature'); ?></th>
                                            <th><?php echo $this->lang->line('view'); ?></th>
                                            <th><?php echo $this->lang->line('add'); ?></th>
                                            <th><?php echo $this->lang->line('edit'); ?></th>
                                            <th><?php echo $this->lang->line('delete'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

foreach ($role_permission as $key => $value) {
    ?>
                                            <tr>
                                                <th><?php echo $value->name ?></th>
                                                <?php
if (!empty($value->permission_category)) {
        ?>
                                                    <td>

                                                        <input type="hidden" name="per_cat[]" value="<?php echo $value->permission_category[0]->id; ?>" />
                                                        <input type="hidden" name="<?php echo "roles_permissions_id_" . $value->permission_category[0]->id; ?>" value="<?php echo $value->permission_category[0]->roles_permissions_id; ?>" />
                                                        <?php echo $value->permission_category[0]->name ?></td>
                                                    <td>
                                                        <?php
if ($value->permission_category[0]->enable_view == 1) {
            ?>
                                                            <label class="">
                                                                <input type="checkbox" name="<?php echo "can_view-perm_" . $value->permission_category[0]->id; ?>" value="<?php echo $value->permission_category[0]->id; ?>" <?php echo set_checkbox("can_view-perm_" . $value->permission_category[0]->id, $value->permission_category[0]->id, ($value->permission_category[0]->can_view == 1) ? true : false); ?>>
                                                            </label>
                                                            <?php
}
        ?>
                                                    </td>
                                                    <td>
                                                        <?php
if ($value->permission_category[0]->enable_add == 1) {
            ?>
                                                            <label class="">
                                                                <input type="checkbox" name="<?php echo "can_add-perm_" . $value->permission_category[0]->id; ?>" value="<?php echo $value->permission_category[0]->id; ?>" <?php echo set_checkbox("can_view-perm_" . $value->permission_category[0]->id, $value->permission_category[0]->id, ($value->permission_category[0]->can_add == 1) ? true : false); ?>>
                                                            </label>
                                                            <?php
}
        ?>
                                                    </td>
                                                    <td>
                                                        <?php
if ($value->permission_category[0]->enable_edit == 1) {
            ?>
                                                            <label class="">
                                                                <input type="checkbox" name="<?php echo "can_edit-perm_" . $value->permission_category[0]->id; ?>" value="<?php echo $value->permission_category[0]->id; ?>" <?php echo set_checkbox("can_view-perm_" . $value->permission_category[0]->id, $value->permission_category[0]->id, ($value->permission_category[0]->can_edit == 1) ? true : false); ?>>
                                                            </label>
                                                            <?php
}
        ?>
                                                    </td>
                                                    <td>
                                                        <?php
if ($value->permission_category[0]->enable_delete == 1) {
            ?>
                                                            <label class="">
                                                                <input type="checkbox" name="<?php echo "can_delete-perm_" . $value->permission_category[0]->id; ?>" value="<?php echo $value->permission_category[0]->id; ?>" <?php echo set_checkbox("can_view-perm_" . $value->permission_category[0]->id, $value->permission_category[0]->id, ($value->permission_category[0]->can_delete == 1) ? true : false); ?>>
                                                            </label>
                                                            <?php
}
        ?>
                                                    </td>
                                                    <?php
} else {
        ?>
                                                    <td colspan="5"></td>
                                                    <?php
}
    ?>
                                            </tr>
                                            <?php
if (!empty($value->permission_category) && count($value->permission_category) > 1) {
        unset($value->permission_category[0]);
        foreach ($value->permission_category as $new_feature_key => $new_feature_value) {
            ?>
                                                    <tr>
                                                        <td></td>
                                                        <td>
                                                            <input type="hidden" name="per_cat[]" value="<?php echo $new_feature_value->id; ?>" />
                                                            <input type="hidden" name="<?php echo "roles_permissions_id_" . $new_feature_value->id; ?>" value="<?php echo $new_feature_value->roles_permissions_id; ?>" />
                                                            <?php echo $new_feature_value->name ?></td>
                                                        <td>
                                                            <?php
if ($new_feature_value->enable_view == 1) {
                ?>
                                                                <label class="">
                                                                    <input type="checkbox" name="<?php echo "can_view-perm_" . $new_feature_value->id; ?>" value="<?php echo $new_feature_value->id; ?>" <?php echo set_checkbox("can_view-perm_" . $new_feature_value->id, $new_feature_value->id, ($new_feature_value->can_view == 1) ? true : false); ?>>
                                                                </label>
                                                                <?php
}
            ?>
                                                        </td>
                                                        <td>
                                                            <?php
if ($new_feature_value->enable_add == 1) {
                ?>
                                                                <label class="">
                                                                    <input type="checkbox" name="<?php echo "can_add-perm_" . $new_feature_value->id; ?>" value="<?php echo $new_feature_value->id; ?>" <?php echo set_checkbox("can_view-perm_" . $new_feature_value->id, $new_feature_value->id, ($new_feature_value->can_add == 1) ? true : false); ?>>
                                                                </label>
                                                                <?php
}
            ?>
                                                        </td>
                                                        <td>
                                                            <?php
if ($new_feature_value->enable_edit == 1) {
                ?>
                                                                <label class="">
                                                                    <input type="checkbox" name="<?php echo "can_edit-perm_" . $new_feature_value->id; ?>" value="<?php echo $new_feature_value->id; ?>" <?php echo set_checkbox("can_view-perm_" . $new_feature_value->id, $new_feature_value->id, ($new_feature_value->can_edit == 1) ? true : false); ?>>
                                                                </label>
                                                                <?php
}
            ?>
                                                        </td>
                                                        <td>
                                                            <?php
if ($new_feature_value->enable_delete == 1) {
                ?>
                                                                <label class="">
                                                                    <input type="checkbox" name="<?php echo "can_delete-perm_" . $new_feature_value->id; ?>" value="<?php echo $new_feature_value->id; ?>" <?php echo set_checkbox("can_view-perm_" . $new_feature_value->id, $new_feature_value->id, ($new_feature_value->can_delete == 1) ? true : false); ?>>
                                                                </label>
                                                                <?php
}
            ?>
                                                        </td>
                                                    </tr>
                                                    <?php
}
    }
    ?>
                                            <?php
}
?>
                                    </tbody>
                                </table>
                            </div><!--./table-responsive-->
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
$(document).ready(function () {
        $('.table-fixed-header').fixedHeader();
    });

    (function ($) {

        $.fn.fixedHeader = function (options) {
            var config = {
                topOffset: 50
                        //bgColor: 'white'
            };
            if (options) {
                $.extend(config, options);
            }

            return this.each(function () {
                var o = $(this);

                var $win = $(window);
                var $head = $('thead.header', o);
                var isFixed = 0;
                var headTop = $head.length && $head.offset().top - config.topOffset;

                function processScroll() {
                    if (!o.is(':visible')) {
                        return;
                    }
                    if ($('thead.header-copy').size()) {
                        $('thead.header-copy').width($('thead.header').width());
                    }
                    var i;
                    var scrollTop = $win.scrollTop();
                    var t = $head.length && $head.offset().top - config.topOffset;
                    if (!isFixed && headTop !== t) {
                        headTop = t;
                    }
                    if (scrollTop >= headTop && !isFixed) {
                        isFixed = 1;
                    } else if (scrollTop <= headTop && isFixed) {
                        isFixed = 0;
                    }
                    isFixed ? $('thead.header-copy', o).offset({
                        left: $head.offset().left
                    }).removeClass('hide') : $('thead.header-copy', o).addClass('hide');
                }
                $win.on('scroll', processScroll);

                // hack sad times - holdover until rewrite for 2.1
                $head.on('click', function () {
                    if (!isFixed) {
                        setTimeout(function () {
                            $win.scrollTop($win.scrollTop() - 47);
                        }, 10);
                    }
                });

                $head.clone().removeClass('header').addClass('header-copy header-fixed').appendTo(o);
                var header_width = $head.width();
                o.find('thead.header-copy').width(header_width);
                o.find('thead.header > tr:first > th').each(function (i, h) {
                    var w = $(h).width();
                    o.find('thead.header-copy> tr > th:eq(' + i + ')').width(w);
                });
                $head.css({
                    margin: '0 auto',
                    width: o.width(),
                    'background-color': config.bgColor
                });
                processScroll();
            });
        };

    })(jQuery);
</script>