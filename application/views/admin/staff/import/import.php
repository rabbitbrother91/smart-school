<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> <?php //echo $this->lang->line('staff'); ?> <small><?php //echo $this->lang->line('student1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info" style="padding:5px;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('staff_import'); ?></h3>
                        <div class="pull-right box-tools">
                            <a href="<?php echo site_url('admin/staff/exportformat') ?>">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-download"></i> <?php echo $this->lang->line('download_sample_import_file'); ?></button>
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {?> <div>  <?php echo $this->session->flashdata('msg') ?> </div> <?php }?>
                        <br/>
                        1. <?php echo $this->lang->line('import_staff_step1'); ?><br/>
                        2. <?php echo $this->lang->line('import_staff_step2'); ?><br/>

                        <hr/></div>
                    <div class="box-body table-responsive" style="overflow-x:auto;">
                        <table class="table table-striped table-bordered table-hover" id="sampledata">
                            <thead>
                                <tr>
                                    <?php
foreach ($field as $key => $value) {
    if ($value == 'staff_id' || $value == 'first_name' || $value == 'email' || $value == 'gender' || $value == 'date_of_birth') {
        $req = "<span class='text-red'>*</span>";
    } else {
        $req = "";
    }
    ?>
                                        <th><?php echo "<span>" . $this->lang->line($value) . "</span>" . $req; ?></th>
                                    <?php }?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php foreach ($field as $key => $value) {
    ?>
                                        <td><?php echo "XYZ" ?></td>
                                    <?php }?>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                    <hr/>
                    <form action="<?php echo site_url('admin/staff/import') ?>"  id="employeeform" name="employeeform" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-3">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('role'); ?></label><small class="req"> *</small>
                                        <select  id="role" name="role" class="form-control" >
                                            <option value=""   ><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($roles as $key => $role) {
    ?>
                                                <option value="<?php echo $role['id'] ?>" <?php echo set_select('role', $role['id'], set_value('role')); ?>><?php echo $role["name"] ?></option>
                                            <?php }
?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('role'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('designation'); ?></label>

                                        <select id="designation" name="designation" placeholder="" type="text" class="form-control" >
                                            <option value="select"><?php echo $this->lang->line('select') ?></option>
                                            <?php foreach ($designation as $key => $value) {
    ?>
                                                <option value="<?php echo $value["id"] ?>" <?php echo set_select('designation', $value['id'], set_value('designation')); ?> ><?php echo $value["designation"] ?></option>
                                            <?php }
?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('designation'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('department'); ?></label>
                                        <select id="department" name="department" placeholder="" type="text" class="form-control" >
                                            <option value="select"><?php echo $this->lang->line('select') ?></option>
                                            <?php foreach ($department as $key => $value) {
    ?>
                                                <option value="<?php echo $value["id"] ?>" <?php echo set_select('department', $value['id'], set_value('department')); ?>><?php echo $value["department_name"] ?></option>
                                            <?php }
?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('department'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('select_csv_file'); ?></label><small class="req"> *</small>
                                        <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' />
                                            <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                    </div></div>
                                <div class="col-md-6 pt20">
                                    <button type="submit" class="btn btn-info pull-right"> <?php echo $this->lang->line('staff') . " " . $this->lang->line('import'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div>
                    </div>
                </div>
                </section>
            </div>