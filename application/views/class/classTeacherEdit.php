<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> <?php echo $this->lang->line('academics') ?></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('assign_class_teacher', 'can_add') || $this->rbac->hasPrivilege('assign_class_teacher', 'can_edit')) {
                ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_assign_class_teacher') ?></h3>

                        </div>
                        <form id="form1" action="<?php echo base_url('admin/teacher/update_class_teacher/' . $class_id . "/" . $section_id); ?>"  method="post" accept-charset="utf-8">
                            <input type="hidden" name="prev_class_id" value="<?php echo $class_id; ?>">
                            <input type="hidden" name="prev_section_id" value="<?php echo $section_id; ?>">
                            <div class="box-body">

                                <?php 
                                    if ($this->session->flashdata('msg')) {                           
                                        echo $this->session->flashdata('msg');
                                        $this->session->unset_userdata('msg'); 
                                    } 
                                ?>

                                <?php
                                if (isset($error_message)) {
                                    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                }
                                ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                    <select class="form-control" name="class"  id="class_id" readonly>

                                        <?php
                                        foreach ($classlist as $class_key => $class_value) {
                                            ?>

                                            <option value="<?php echo $class_value["id"] ?>" <?php echo set_select('class', $class_value["id"], (set_value('class', $class_value["id"]) == $class_id ) ? TRUE : FALSE ); ?>><?php echo $class_value["class"]; ?></option>

                                            <?php
                                        }
                                        ?>
                                    </select>

                                    <span class="text-danger"><?php echo form_error('class'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>

                                    <select class="form-control" id="section_id" name="section" readonly>
                                        <option value=""><?php echo $this->lang->line('select') ?></option> 
                                    </select>

                                    <span class="text-danger"><?php echo form_error('section'); ?></span>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('class_teacher'); ?></label><small class="req"> *</small>

                                    <?php foreach ($result as $result_key => $result_value) {
                                        ?>
                                        <input autofocus="" id="class" name="classteacherid[]" placeholder="" type="hidden" class="form-control"  value="<?php echo $result_value["id"]; ?>" />
                                        <input autofocus="" id="class" name="previd[]" placeholder="" type="hidden" class="form-control"  value="<?php echo $result_value["ctid"]; ?>" />

                                    <?php }
                                    ?> 

                                    <?php
                                    $i = 0;

                                    foreach ($teacherlist as $tvalue) {

                                        $check = "";
                                        ?>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="teachers[]" value="<?php echo $tvalue['id'] ?>" <?php echo $check; ?> <?php echo set_checkbox('teachers[]', $tvalue['id'], check_in_array($tvalue['id'], $result)); ?> ><?php echo $tvalue['name'] . " " . $tvalue['surname'] . " (" . $tvalue['employee_id'] . ")"; ?>
                                            </label>
                                        </div>

                                        <?php
                                        $i++;
                                    }
                                    ?>

                                    <span class="text-danger"><?php echo form_error('teachers[]'); ?></span>
                                </div>

                            </div><!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>

                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('assign_class_teacher', 'can_add') || $this->rbac->hasPrivilege('assign_class_teacher', 'can_edit')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('class_teacher_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible">
                            <div class="download_label"><?php echo $this->lang->line('class_teacher_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>

                                        <th><?php echo $this->lang->line('class'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('section'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('class_teacher'); ?>
                                        </th>

                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;

                                    foreach ($assignteacherlist as $teacher) {
                                        ?>

                                        <tr>
                                            <td class="mailbox-name">
                                                <?php echo $teacher["class"]; ?>

                                            </td>


                                            <td>

                                                <?php echo $teacher["section"]; ?>

                                            </td>
                                            <td>
                                                <?php foreach ($tlist[$i] as $key => $tsvalue) {
                                                    ?>

                                                    <?php echo $tsvalue['name'] . " " . $tsvalue['surname'] . " (" . $tsvalue['employee_id'] . ")" . "<br/>"; ?>
                                                    <input type="hidden"  name="teacherid[]" value="<?php echo $tsvalue["id"] ?>" >
                                                <?php } ?>
                                            </td>
                                            <td class="mailbox-date pull-right"">
                                                <?php
                                                if ($this->rbac->hasPrivilege('assign_class_teacher', 'can_edit')) {
                                                    ?>
                                                    <a href="<?php echo base_url(); ?>admin/teacher/update_class_teacher/<?php echo $teacher["class_id"]; ?>/<?php echo $teacher["section_id"]; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <?php
                                                }
                                                if ($this->rbac->hasPrivilege('assign_class_teacher', 'can_delete')) {
                                                    ?>
                                                    <a href="<?php echo base_url(); ?>admin/teacher/classteacherdelete/<?php echo $teacher["class_id"]; ?>/<?php echo $teacher["section_id"]; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>

                                </tbody>
                            </table><!-- /.table -->



                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->


        </div>
        <div class="row">
            <!-- left column -->

            <!-- right column -->
            <div class="col-md-12">

            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    function getSectionByClass(class_id, section_id) {

        if (class_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    div_data += "<option value='' selected >" + "<?php echo $this->lang->line('select'); ?>" + "</option>";
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

    var section = '';
<?php
if (isset($_POST['section'])) {
    ?>
        section = $('#section_id').val();
<?php } else {
    ?>
        section = "<?php echo $section_id ?>";
    <?php
}
?>

    getSectionByClass('<?php echo $class_id ?>', section);
    var section_id = "<?php echo $section_id ?>";


</script>

<?php

function check_in_array($find, $array) {

    foreach ($array as $element) {
        if ($find == $element["id"]) {
            return TRUE;
        }
    }
    return FALSE;
}
?>