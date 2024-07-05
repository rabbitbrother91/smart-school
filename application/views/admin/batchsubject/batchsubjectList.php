<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat();?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <section class="content-header">
        <h1><i class="fa fa-credit-card"></i> Batch Subject--r</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
if ($this->rbac->hasPrivilege('expense', 'can_add')) {
    ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Batch Subject--r</h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo site_url('admin/batchsubject') ?>"  id="batchform" name="batchform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) {?>
                                    <?php 
                                        echo $this->session->flashdata('msg');
                                        $this->session->unset_userdata('msg');
                                    ?>
                                <?php }?>
                                <?php
if (isset($error_message)) {
        echo "<div class='alert alert-danger'>" . $error_message . "</div>";
    }
    ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                    <select  id="class_id" name="class_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
foreach ($classlist as $class) {
        ?>
                                            <option value="<?php echo $class['id'] ?>"<?php
if (set_value('class_id') == $class['id']) {
            echo "selected=selected";
        }
        ?>><?php echo $class['class'] ?></option>
                                                    <?php
}
    ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                    <select  id="section_id" name="section_id" class="form-control" >
                                        <option value=""   ><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Batch --r</label><small class="req"> *</small>
                                    <select  id="batch_id" name="batch_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('batch_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('subject'); ?></label><small class="req"> *</small>
                                    <select  id="subject_id" name="subject_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
foreach ($subjectlist as $subject) {
        ?>
                                            <option value="<?php echo $subject['id'] ?>"<?php
if (set_value('subject_id') == $subject['id']) {
            echo "selected=selected";
        }
        ?>><?php echo $subject['name'] ?></option>
                                                    <?php
}
    ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('subject_id'); ?></span>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="1" name="is_exam"> No Exam --r
                                    </label>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
            <?php }?>
            <div class="col-md-<?php
if ($this->rbac->hasPrivilege('expense', 'can_add')) {
    echo "8";
} else {
    echo "12";
}
?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Batch Subject List --r</h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('expense_list'); ?></div>
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('section'); ?></th>
                                        <th>Batch --r</th>
                                        <th><?php echo $this->lang->line('subject'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
foreach ($batchlist as $batch) {
    ?>
                                        <tr>

                                            <td class="mailbox-name">
                                                <a href="#" data-toggle="popover" class="detail_popover"><?php echo $batch->class; ?></a>
                                            </td>
                                            <td class="mailbox-name"><?php echo $batch->section; ?></td>
                                            <td class="mailbox-name"><?php echo $batch->name; ?></td>
                                            <td class="mailbox-name">
                                                <ul class="liststyle1">
                                                    <?php
foreach ($batch->batch_subjects as $batchsubject_key => $batchsubject_value) {
        ?>
                                                        <li> <i class="fa fa-file"></i>
                                                            <?php echo $batchsubject_value->subject_name; ?> &nbsp;&nbsp;
                                                            <?php if ($this->rbac->hasPrivilege('fees_master', 'can_edit')) {?>
                                                                <a href="<?php echo base_url(); ?>admin/batchsubject/edit/<?php echo $batchsubject_value->id ?>"   data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                    <i class="fa fa-pencil"></i>
                                                                </a>&nbsp;
                                                                <?php
}
        if ($this->rbac->hasPrivilege('fees_master', 'can_delete')) {
            ?>
                                                                <a href="<?php echo base_url(); ?>admin/batchsubject/delete/<?php echo $batchsubject_value->id ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                    <i class="fa fa-remove"></i>
                                                                </a>
                                                            <?php }?>

                                                        </li>
                                                        <?php
}
    ?>
                                                </ul>
                                            </td>
                                            <td class="mailbox-date pull-right">
                                                <?php if ($this->rbac->hasPrivilege('fees_master', 'can_delete')) {?>
                                                    <a href="<?php echo base_url(); ?>admin/batchsubject/deletegrp/<?php echo $batch->id ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php }?>

                                            </td>
                                        </tr>
                                        <?php
}
?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    $(document).ready(function () {

        var class_id = '<?php echo set_value('class_id', 0) ?>';
        var section_id = '<?php echo set_value('section_id', 0) ?>';
        var batch_id = '<?php echo set_value('batch_id', 0) ?>';

        getSectionByClass(class_id, section_id);
        getBatchStudents(section_id, batch_id);

        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            getSectionByClass(class_id, 0);
        });

        $(document).on('change', '#section_id', function (e) {
            getBatchStudents($(this).val(), 0);
        });

        function getSectionByClass(class_id, section_id) {

            if (class_id != "") {
                $('#section_id').html("");
                var base_url = '<?php echo base_url() ?>';
                var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                $.ajax({
                    type: "GET",
                    url: base_url + "sections/getByClass",
                    data: {'class_id': class_id},
                    dataType: "json",
                    beforeSend: function () {
                        $('#section_id').addClass('dropdownloading');
                    },
                    success: function (data) {
                        $.each(data, function (i, obj)
                        {
                            var sel = "";
                            if (section_id == obj.section_id) {
                                sel = "selected";
                            }
                            div_data += "<option value=" + obj.id + " " + sel + ">" + obj.section + "</option>";
                        });
                        $('#section_id').append(div_data);
                    },
                    complete: function () {
                        $('#section_id').removeClass('dropdownloading');
                    }
                });
            }
        }

        function getBatchStudents(section_id, batch_id) {

            if (section_id != "") {
                $('#batch_id').html("");
                var base_url = '<?php echo base_url() ?>';
                var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

                $.ajax({
                    type: "POST",
                    url: base_url + "admin/batch/getByClassSection",
                    data: {'section_id': section_id},
                    dataType: "JSON",
                    beforeSend: function () {
                        $('#batch_id').addClass('dropdownloading');
                    },
                    success: function (data) {
                        $.each(data, function (i, obj)
                        {
                            var sel = "";
                            if (batch_id == obj.batch_id) {
                                sel = "selected";
                            }
                            div_data += "<option value=" + obj.batch_id + " " + sel + ">" + obj.name + "</option>";
                        });
                        $('#batch_id').append(div_data);
                    },
                    complete: function () {
                        $('#batch_id').removeClass('dropdownloading');
                    }
                });
            }
        }
    });
</script>