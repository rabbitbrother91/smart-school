<style type="text/css">

    .table .pull-right {text-align: initial; width: auto; float: right !important;}
</style>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom theme-shadow">
                    <ul class="nav nav-tabs pull-right">

                        <li><a href="#tab_parent" data-toggle="tab"><?php echo $this->lang->line('parent') ?></a></li>
                        <li><a href="#tab_students" data-toggle="tab"><?php echo $this->lang->line('student') ?></a></li>
                        <li class="active"><a href="#tab_system" data-toggle="tab"><?php echo $this->lang->line('system') ?></a></li>

                        <li class="pull-left header"> <?php echo $this->lang->line('modules'); ?></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane table-responsive active" id="tab_system">
                            <div class="download_label"><?php echo $this->lang->line('modules'); ?></div>
                            <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                 <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                <tbody>
                                     <?php
if (!empty($permissionList)) {
    $count = 1;
    foreach ($permissionList as $system) {
        ?>
                                            <tr>
                                                <td class="text-rtl-right" width="100%"><?php echo $system['name']; ?></td>
                                                <td class="relative">
                                                    <div class="material-switch pull-right">

                                                        <input id="system<?php echo $system['id'] ?>" name="someSwitchOption001" type="checkbox" data-role="system" class="chk" data-rowid="<?php echo $system['id'] ?>" value="checked" <?php if ($system['is_active'] == 1) {
            echo "checked='checked'";
        }
        ?> />
                                                        <label for="system<?php echo $system['id'] ?>" class="label-success"></label>
                                                    </div>

                                                </td>
                                            </tr>
                                            <?php
$count++;
    }
}
?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane table-responsive" id="tab_students">
                            <div class="download_label"><?php echo $this->lang->line('modules'); ?></div>
                            <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                 <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('name') ?></th>

                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                <tbody>
                                     <?php
if (!empty($studentpermissionList)) {
    $count = 1;
    foreach ($studentpermissionList as $student) {
        ?>
                                            <tr>
                                                <td class="text-rtl-right" width="100%"><?php echo $student['name']; ?></td>
                                                <td class="relative">
                                                    <div class="material-switch pull-right">

                                                        <input id="student<?php echo $student['id'] ?>" name="someSwitchOption001" type="checkbox" data-role="student" class="chk" data-rowid="<?php echo $student['id'] ?>" value="checked" <?php if ($student['student'] == 1) {
            echo "checked='checked'";
        }
        ?> />
                                                        <label for="student<?php echo $student['id'] ?>" class="label-success"></label>
                                                    </div>

                                                </td>
                                            </tr>
                                            <?php
$count++;
    }
}
?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane table-responsive" id="tab_parent">
                            <div class="download_label"><?php echo $this->lang->line('modules'); ?></div>
                           <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                 <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('name') ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                <tbody>
                                     <?php
if (!empty($parentpermissionList)) {
    $count = 1;
    foreach ($parentpermissionList as $parent) {
        ?>
                                            <tr>
                                                <td class="text-rtl-right" width="100%"><?php echo $parent['name']; ?></td>
                                                <td class="relative">
                                                    <div class="material-switch pull-right">

                                                        <input id="parent<?php echo $parent['id'] ?>" name="someSwitchOption001" type="checkbox" data-role="parent" class="chk" data-rowid="<?php echo $parent['id'] ?>" value="checked" <?php if ($parent['parent'] == 1) {
            echo "checked='checked'";
        }
        ?> />
                                                        <label for="parent<?php echo $parent['id'] ?>" class="label-success"></label>
                                                    </div>

                                                </td>
                                            </tr>
                                            <?php
$count++;
    }
}
?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">
    $(document).ready(function () {

        $(document).on('click', '.chk', function () {
            var checked = $(this).is(':checked');
            var rowid = $(this).data('rowid');
            var role = $(this).data('role');

            if (checked) {
                if (!confirm('<?php echo $this->lang->line('are_you_sure'); ?>')) {
                    $(this).removeAttr('checked');

                } else {
                    var status = "1";
                    if(role=='system'){
                         changeStatus(rowid, status, role);

                    }else if(role=='parent'){

                        changeParentStatus(rowid, status, role);

                    }else if(role=='student'){

                        changeStudentStatus(rowid, status, role);

                    }


                }

            } else if (!confirm('<?php echo $this->lang->line('are_you_sure'); ?>')) {
                $(this).prop("checked", true);
            } else {
                var status = "0";
                if(role=='system'){
                         changeStatus(rowid, status, role);

                    }else if(role=='parent'){

                        changeParentStatus(rowid, status, role);

                    }else if(role=='student'){

                        changeStudentStatus(rowid, status, role);

                    }
            }
        });
    });

     function changeStatus(rowid, status, role) {

        var base_url = '<?php echo base_url() ?>';

        $.ajax({
            type: "POST",
            url: base_url + "admin/module/changeStatus",
            data: {'id': rowid, 'status': status, 'role': role},
            dataType: "json",
            success: function (data) {
                successMsg(data.msg);
                window.location.reload();
            }
        });
    }

function changeStudentStatus(rowid, status, role) {

        var base_url = '<?php echo base_url() ?>';

        $.ajax({
            type: "POST",
            url: base_url + "admin/module/changeStudentStatus",
            data: {'id': rowid, 'status': status, 'role': role},
            dataType: "json",
            success: function (data) {
                successMsg(data.msg);
                window.location.reload();
            }
        });
    }

    function changeParentStatus(rowid, status, role) {

        var base_url = '<?php echo base_url() ?>';

        $.ajax({
            type: "POST",
            url: base_url + "admin/module/changeStudentStatus",
            data: {'id': rowid, 'status': status, 'role': role},
            dataType: "json",
            success: function (data) {
                successMsg(data.msg);
                window.location.reload();
            }
        });
    }

</script>