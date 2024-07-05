<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?> <small><?php echo $this->lang->line('student1'); ?></small>        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('fees_type', 'can_add') || $this->rbac->hasPrivilege('fees_type', 'can_edit')) {
                ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $title; ?></h3>
                        </div>
                        <form action="<?php echo site_url('feetype/edit/' . $id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php 
                                        echo $this->session->flashdata('msg');
                                        $this->session->unset_userdata('msg');
                                     ?>
                                <?php } ?>   
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('fee_category'); ?></label>
                                    <select autofocus="" id="feecategory_id" name="feecategory_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($feecategorylist as $feecategory) {
                                            ?>
                                            <option value="<?php echo $feecategory['id'] ?>" <?php
                                            if ($feetype['feecategory_id'] == $feecategory['id']) {
                                                echo "selected =selected";
                                            }
                                            ?>><?php echo $feecategory['category'] ?></option>
                                                    <?php
                                                    $count++;
                                                }
                                                ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('feecategory_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('fee_type'); ?></label>
                                    <input id=" type" name=" type" placeholder="type" type="text" class="form-control"  value="<?php echo set_value('type', $feetype['type']); ?>" />
                                    <span class="text-danger"><?php echo form_error('type'); ?></span>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="button" class="btn btn-default"><?php echo $this->lang->line('cancel'); ?></button>
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>          
                <div class="col-md-<?php
                if ($this->rbac->hasPrivilege('fees_type', 'can_add') || $this->rbac->hasPrivilege('fees_type', 'can_edit')) {
                    echo "8";
                } else {
                    echo "12";
                }
                ?>">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs pull-right">
                            <?php
                            $count = 1;
                            $total = count($category_array);
                            $array = array();
                            foreach ($category_array as $key => $category) {
                                $first_key = key($category);
                                $first_value = $category[$first_key];
                                $array[$first_key] = $category[$first_value];
                                $exapanded = "";
                                if ($total == $count) {
                                    $exapanded = "active";
                                }
                                ?>
                                <li class="<?php echo $exapanded; ?>"><a href="#tab_1-<?php echo $count ?>" data-toggle="tab" class="tab_new"><?php echo $first_value ?></a></li>

                                <?php
                                $count++;
                            }
                            ?>
                            <li class="pull-left header"><?php echo $title_list; ?></li>
                        </ul>
                        <div class="tab-content">
                            <?php
                            $counter = 1;
                            count($array);
                            foreach ($array as $arrkey => $arrvalue) {
                                $exapanded = "";
                                if ($total == $counter) {
                                    $exapanded = "active";
                                }
                                ?>
                                <div class="tab-pane <?php echo $exapanded; ?>" id="tab_1-<?php echo $counter; ?>">
                                    <input id="fee_master_id" type="hidden" value="0" >
                                    <div class="form-horizontal">
                                        <div class="box-body">
                                            <?php
                                            if (!empty($arrvalue)) {
                                                ?>
                                                <table class="table table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th> <?php echo $this->lang->line('fee_type'); ?>
                                                            </th>
                                                            <th class="pull-right"> <?php echo $this->lang->line('action'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (empty($arrvalue)) {
                                                            ?>
                                                            <tr>
                                                                <td colspan="12" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>

                                                            </tr>
                                                            <?php
                                                        } else {
                                                            $count = 1;
                                                            foreach ($arrvalue as $arkey => $arvalue) {
                                                                ?>

                                                                <tr>
                                                                    <td  class="mailbox-name">
                                                                        <?php echo $arvalue; ?></td>
                                                                    <td  class="mailbox-date pull-right">
                                                                        <a href="<?php echo base_url(); ?>feetype/edit/<?php echo $arkey; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                            <i class="fa fa-pencil"></i>
                                                                        </a>
                                                                        <a href="<?php echo base_url(); ?>feetype/delete/<?php echo $arkey; ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                            <i class="fa fa-remove"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            $count++;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                $counter++;
                            }
                            ?>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#btnreset").click(function () {
            $("#form1")[0].reset();
        });
    });
</script>