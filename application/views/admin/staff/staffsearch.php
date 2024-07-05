<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?>
            <?php if ($this->rbac->hasPrivilege('staff', 'can_add')) {?>
                <small class="pull-right">
                    <a href="<?php echo base_url(); ?>admin/staff/create" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_staff'); ?>
                    </a>
                </small>
            <?php }?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="box-tools pull-right">
                            <small class="pull-right">
                                <?php if ($this->rbac->hasPrivilege('staff', 'can_add')) {?> <a href="<?php echo base_url(); ?>admin/staff/create" class="btn btn-primary btn-sm"   >
                                <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_staff'); ?>
                            </a><?php }?>
                        </small>
                       </div>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {
    echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg');
}?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('admin/staff') ?>" method="post" class="">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line("role"); ?></label><small class="req"> *</small>
                                                <select name="role" class="form-control">
                                                    <option value=""><?php echo $this->lang->line("select"); ?></option>
                                                    <?php foreach ($role as $key => $role_value) {
    ?>
                                                        <option <?php
if ($role_id == $role_value["id"]) {
        echo "selected";
    }
    ?> value="<?php echo $role_value['id'] ?>"><?php echo $role_value['type'] ?></option>
<?php }
?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('role'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                    </form>
                                  </div>

                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('admin/staff') ?>" method="post" class="">
<?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_keyword'); ?></label>
                                                <input type="text" name="search_text" class="form-control" value="<?php echo set_value('search_text'); ?>"  placeholder="<?php echo $this->lang->line('search_by_staff'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_full" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php
if (isset($resultlist)) {
    ?>
                  <div class="box-header ptbnull"></div>
                    <div class="nav-tabs-custom border0">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false"><i class="fa fa-newspaper-o"></i> <?php echo $this->lang->line('card_view'); ?></a></li>
                            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="true"><i class="fa fa-list"></i> <?php echo $this->lang->line('list_view'); ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="download_label"><?php echo $title; ?></div>
                            <div class="tab-pane table-responsive no-padding" id="tab_2">
                                <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('staff_id'); ?></th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('role'); ?></th>
                                            <th><?php echo $this->lang->line('department'); ?></th>
                                            <th><?php echo $this->lang->line('designation'); ?></th>
                                            <th><?php echo $this->lang->line('mobile_number'); ?></th>
                                             <?php
if (!empty($fields)) {

        foreach ($fields as $fields_key => $fields_value) {
            ?>
                                                    <th><?php echo $fields_value->name; ?></th>
                                                    <?php
}
    }
    ?>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
if (empty($resultlist)) {

    } else {
        $count = 1;
        foreach ($resultlist as $staff) {
            ?>
                                                <tr>
                                                    <td><?php echo $staff['employee_id']; ?></td>
                                                    <td>
                                                        <a <?php if ($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) {?> href="<?php echo base_url(); ?>admin/staff/profile/<?php echo $staff['id']; ?>"
            <?php }?>><?php echo $staff['name'] . " " . $staff['surname']; ?>
                                                        </a>
                                                    </td>
                                                    <td><?php echo $staff['user_type']; ?></td>
                                                    <td><?php echo $staff['department']; ?></td>
                                                    <td><?php echo $staff['designation']; ?></td>
                                                    <td><?php echo $staff['contact_no']; ?></td>
   <?php
if (!empty($fields)) {

                foreach ($fields as $fields_key => $fields_value) {
                    $display_field = $staff[$fields_value->name];
                    if ($fields_value->type == "link") {
                        $display_field = "<a href=" . $staff[$fields_value->name] . " target='_blank'>" . $staff[$fields_value->name] . "</a>";

                    }
                    ?>
                                                            <td>
                                                                <?php echo $display_field; ?></td>
                                                            <?php
}
            }
            ?>
                                                    <td class="pull-right">
            <?php
$userdata = $this->customlib->getUserData();
            if (($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) || ($userdata["id"] == $staff["id"])) {?>
                                                            <a href="<?php echo base_url(); ?>admin/staff/profile/<?php echo $staff['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>" >
                                                                <i class="fa fa-reorder"></i>
                                                            </a>
            <?php }

            $a           = 0;
            $sessionData = $this->session->userdata('admin');

            $staff["user_type"];
            if (($staff["user_type"] == "Super Admin") && $userdata["id"] == $staff["id"]) {
                $a = 1;
            } elseif (($this->rbac->hasPrivilege('staff', 'can_edit')) && ($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view'))) {
                $a = 1;
            }
            if ($a == 1) {

                ?>
                                                            <a href="<?php echo base_url(); ?>admin/staff/edit/<?php echo $staff['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                <?php }?>
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
                            <div class="tab-pane active" id="tab_1">
                                <div class="mediarow">
                                    <div class="row">
                                        <?php if (empty($resultlist)) {?>
                                            <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                            <?php
} else {
        $count = 1;
        foreach ($resultlist as $staff) {
            ?>

                                                <div class="col-lg-3 col-md-4 col-sm-6 img_div_modal">
                                                    <div class="staffinfo-box">
                                                        <div class="staffleft-box">
                                                            <?php
if (!empty($staff["image"])) {
                $image = $staff["image"];
            } else {
                if ($staff['gender'] == 'Male') {
                    $image = "default_male.jpg";
                } else {
                    $image = "default_female.jpg";
                }

            }
            ?>
                                                            <img  src="<?php echo $this->media_storage->getImageURL( "uploads/staff_images/" . $image) ?>" />
                                                        </div>
                                                        <div class="staffleft-content">
                                                            <h5><span data-toggle="tooltip" title="<?php echo $this->lang->line('name'); ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $staff["name"] . " " . $staff["surname"]; ?></span></h5>
                                                            <p><font data-toggle="tooltip" title="<?php echo "Employee Id"; ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $staff["employee_id"] ?></font></p>
                                                            <p><font data-toggle="tooltip" title="<?php echo "Contact Number"; ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $staff["contact_no"] ?></font></p>
                                                            <p><font data-toggle="tooltip" title="<?php echo 'Location'; ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php
if (!empty($staff["location"])) {
                echo $staff["location"] . ",";
            }
            ?></font><font data-toggle="tooltip" title="<?php echo 'Department'; ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $staff["department"]; ?></font></p>
                                                            <p class="staffsub" ><span data-toggle="tooltip" title="<?php echo $this->lang->line('role'); ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><?php echo $staff["user_type"] ?></span> <span data-toggle="tooltip" title="<?php echo 'Designation'; ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $staff["designation"] ?></span></p>
                                                        </div>
                                                        <div class="overlay3">
                                                            <div class="stafficons">

             <?php
$userdata = $this->customlib->getUserData();
            if (($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) || ($userdata["id"] == $staff["id"])) {?>
                                                                    <a title="<?php echo $this->lang->line('view'); ?>"  href="<?php echo base_url() . "admin/staff/profile/" . $staff["id"] ?>"><i class="fa fa-navicon"></i></a>
            <?php }
            $a           = 0;
            $sessionData = $this->session->userdata('admin');

            $staff["user_type"];
            if (($staff["user_type"] == "Super Admin") && $userdata["id"] == $staff["id"]) {
                $a = 1;
            } elseif (($this->rbac->hasPrivilege('staff', 'can_edit')) && ($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view'))) {
                $a = 1;
            }
            if ($a == 1) {
                ?>
                <a title="<?php echo $this->lang->line('edit'); ?>"  href="<?php echo base_url() . "admin/staff/edit/" . $staff["id"] ?>"><i class=" fa fa-pencil"></i></a>
                                                <?php }?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!--./col-md-3-->
        <?php
}
    }
    ?>


                                    </div><!--./col-md-3-->
                                </div><!--./row-->
                            </div><!--./mediarow-->
                        </div>
                    </div>
                  </div>
                </div>
    <?php
}
?>
        </div>
</div>
</section>
</div>

<script type="text/javascript">
    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }

    $(document).ready(function () {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });
    });
</script>