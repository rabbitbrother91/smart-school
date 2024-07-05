<style type="text/css">
    .table-sortable tbody tr {
        cursor: move;
    }

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-empire"></i> <?php echo $this->lang->line('front_cms'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Menu --r</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?php echo site_url('admin/front/menus/edit/' . $result['slug']) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg');
                                $this->session->unset_userdata('msg'); ?>
                            <?php } ?>
                            <?php
                            if (isset($error_message)) {
                            
                            }
                            ?>
                            <?php echo $this->customlib->getCSRF(); ?>
                            <input  type="hidden" name="id" value="<?php echo set_value('id', $result['id']); ?>" >
                            <div class="form-group">
                                <label for="exampleInputEmail1">Menu List --r</label>
                                <input  id="menu" name="menu" placeholder="" type="text" class="form-control"  value="<?php echo set_value('menu', $result['menu']); ?>" />
                                <span class="text-danger"><?php echo form_error('menu'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Page --r</label>
                                <select  id="page_id" name="page_id" class="form-control"  >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    foreach ($listPages as $page) {
                                        ?>
                                        <option value="<?php echo $page['id'] ?>"<?php if (set_value('page_id', $result['page_id']) == $page['id']) echo "selected=selected" ?>><?php echo $page['page_title'] ?></option>
                                        <?php
                                        $count++;
                                    }
                                    ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder=""><?php echo set_value('description', $result['description']); ?></textarea>
                                <span class="text-danger"><?php echo form_error('description'); ?></span>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>

            </div><!--/.col (right) -->
            <!-- left column -->
            <div class="col-md-8">
                <!-- general form elements -->
                <div class="box box-primary" id="holist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Menu List --r</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-controls">
                            <div class="pull-right">
                            </div><!-- /.pull-right -->
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label">Menu List --r</div>
                            <table class="table table-striped table-bordered table-hover table-sortable">
                                <thead>
                                    <tr>
                                        <th>Title --r</th>
                                        <th class="text-right no-print">
                                            <?php echo $this->lang->line('action'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($listMenus)) {
                                        ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($listMenus as $menu) {
                                            ?>
                                            <tr id="<?php echo $menu["id"]; ?>">
                                        <input type="hidden" value="<?php echo $menu['id']; ?>" id="item" name="item">
                                        <td class="mailbox-name">
                                            <a href="#" data-toggle="popover" class="detail_popover"><?php echo $menu['menu'] ?></a>

                                            <div class="fee_detail_popover" style="display: none">
                                                <?php
                                                if ($menu['description'] == "") {
                                                    ?>
                                                    <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <p class="text text-info"><?php echo $menu['description']; ?></p>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </td>

                                        <td class="mailbox-date pull-right no-print">
                                            <?php if ($this->rbac->hasPrivilege('menus', 'can_edit')) {  ?>
                                                <a href="<?php echo base_url(); ?>admin/front/menus/edit/<?php echo $menu['slug'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>"> i class="fa fa-pencil"></i> </a>
                                            <?php } if ($this->rbac->hasPrivilege('menus', 'can_delete')) { ?>
                                                <a href="<?php echo base_url(); ?>admin/front/menus/delete/<?php echo $menu['slug'] ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');"><i class="fa fa-remove"></i></a>
                                            <?php } ?>
                                        </td>
                                        </tr>
                                        <?php
                                    }
                                    $count++;
                                }
                                ?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
        </div>
        <div class="row">
            <div class="col-md-12">
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
    $(document).ready(function () {
    $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });


</script>

<script>
    var $sortable = $(".table-sortable > tbody");
    $sortable.sortable({
        update: function (event, ui) {
            var parameters = $sortable.sortable("toArray");
            updateRow(parameters);
        },
    });
    function updateRow(parameters) {
        var urls = baseurl + "admin/front/menus/updatePosition";

        $.ajax({
            data: {'row': parameters},
            type: 'POST',
            url: urls,
            success: function (response) {

            }, error: function (response) {

            },
            complete: function () {


            }
        });
    }
</script>