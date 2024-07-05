<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {
    echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg');
}?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('admin/staff/disablestafflist') ?>" method="post" class="">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line("role") ?></label><small class="req"> *</small>
                                                <select name="role" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                                    <?php foreach ($role as $key => $role_value) {
    ?>
                                                        <option <?php
if ($search_role == $role_value['id']) {
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
                                </div>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('admin/staff/disablestafflist') ?>" method="post" class="">
<?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_keyword'); ?></label>
                                                <input type="text" name="search_text" class="form-control"   placeholder="<?php echo $this->lang->line('search_by_staff'); ?>">
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
                </div>
                <?php
if (isset($resultlist)) {
    ?>
                    <div class="nav-tabs-custom theme-shadow">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false"><i class="fa fa-newspaper-o"></i> <?php echo $this->lang->line('card_view'); ?></a></li>
                            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="true"><i class="fa fa-list"></i> <?php echo $this->lang->line('list_view'); ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="download_label"><?php echo "Disable Staff List"; ?></div>
                            <div class="tab-pane  table-responsive no-padding" id="tab_2">
                                <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('staff_id'); ?></th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('role'); ?></th>
                                            <th><?php echo $this->lang->line('department'); ?></th>
                                            <th><?php echo $this->lang->line('designation'); ?></th>
                                            <th><?php echo $this->lang->line('mobile_number'); ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
if (empty($resultlist)) {
        ?>
                                            <tr>
                                                <td colspan="4" class="text-danger text text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                            </tr>
                                            <?php
} else {
        $count = 1;
        foreach ($resultlist as $staff) {
            ?>
                                                <tr>
                                                    <td><?php echo $staff['employee_id']; ?></td>
                                                    <td>
                                                        <a href="<?php echo base_url(); ?>admin/staff/profile/<?php echo $staff['id']; ?>"><?php echo $staff['name'] . " " . $staff['surname']; ?>
                                                        </a>
                                                    </td>
                                                    <td><?php echo $staff['user_type']; ?></td>
                                                    <td><?php echo $staff['department']; ?></td>
                                                    <td><?php echo $staff['designation']; ?></td>
                                                    <td><?php echo $staff['contact_no']; ?></td>
                                                    <td class="pull-right">
                                                        <?php
$userdata = $this->customlib->getUserData();
            if (($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) || ($userdata["id"] == $staff["id"])) {?>
                                                        <a href="<?php echo base_url(); ?>admin/staff/profile/<?php echo $staff['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>" >
                                                            <i class="fa fa-reorder"></i>
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
                                        <?php if (empty($resultlist)) {
        ?>
                                            <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                            <?php
} else {
        $count = 1;
        foreach ($resultlist as $staff) {
            ?>
                                                <div class="col-lg-3 col-md-6 col-sm-6 img_div_modal">
                                                    <div class="staffinfo-box">
                                                        <div class="staffleft-box">
                                                            <?php
if (!empty($staff["image"])) {
                $image = $staff["image"];
            } else {
                $image = "no_image.png";
            }
            ?>
                                                            <img src="<?php echo $this->media_storage->getImageURL("uploads/staff_images/" . $image); ?>" />
                                                        </div>
                                                        <div class="staffleft-content">
                                                            <h5><span   data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $staff["name"] . " " . $staff["surname"]; ?></span></h5>

                                                            <p><font   data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $staff["employee_id"] ?></font></p>

                                                            <p><font  data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $staff["contact_no"] ?></font></p>

                                                            <p><font  data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php
if (!empty($staff["location"])) {
                echo $staff["location"] . ",";
            }
            ?></font><font  data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $staff["department"]; ?></font></p>

                                                            <p class="staffsub" ><span  data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $staff["user_type"] ?></span>

                                                                <span  data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $staff["designation"] ?></span></p>
                                                        </div>
                                                        <div class="overlay3">
                                                            <div class="stafficons">
                                                                <?php
$userdata = $this->customlib->getUserData();
            if (($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) || ($userdata["id"] == $staff["id"])) {?>
                                                                <a title="<?php echo $this->lang->line('view'); ?>" href="<?php echo base_url() . "admin/staff/profile/" . $staff["id"] ?>"><i class="fa fa-navicon"></i></a>
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
                <?php
}
?>
        </div>
    </div>
</section>
</div>