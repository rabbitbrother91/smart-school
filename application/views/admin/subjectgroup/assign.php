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
                        <form role="form" action="<?php echo site_url('admin/subjectgroup/assign/' . $id) ?>" method="post" class="form-horizontal">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="form-group">
                                <div class="col-sm-3">
                                    <label><?php echo $this->lang->line('class'); ?></label>
                                    <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
foreach ($classlist as $class) {
    ?>
                                            <option value="<?php echo $class['id'] ?>" <?php
if (set_value('class_id') == $class['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $class['class'] ?></option>
                                                    <?php
$count++;
}
?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                </div>
                                <div class="col-sm-3">
                                    <label><?php echo $this->lang->line('section'); ?></label>
                                    <select  id="section_id" name="section_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                </div>
                                <div class="col-sm-2">
                                    <label><?php echo $this->lang->line('category'); ?></label>
                                    <select  id="category_id" name="category_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
foreach ($categorylist as $category) {
    ?>
                                            <option value="<?php echo $category['id'] ?>" <?php
if (set_value('category_id') == $category['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $category['category'] ?></option>
                                                    <?php
$count++;
}
?>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label><?php echo $this->lang->line('gender'); ?></label>
                                    <select class="form-control" name="gender">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
foreach ($genderList as $key => $value) {
    ?>
                                            <option value="<?php echo $key; ?>" <?php
if (set_value('gender') == $key) {
        echo "selected";
    }
    ?>><?php echo $value; ?></option>
                                                    <?php
}
?>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label><?php echo $this->lang->line('rte'); ?></label>
                                    <select  id="rte" name="rte" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
foreach ($RTEstatusList as $k => $rte) {
    ?>
                                            <option value="<?php echo $k; ?>" <?php
if (set_value('rte') == $k) {
        echo "selected";
    }
    ?>><?php echo $rte; ?></option>

                                            <?php
$count++;
}
?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <form method="post" action="<?php echo site_url('admin/subjectgroup/addsubjectgroup') ?>" id="assign_form">

                    <?php
if (isset($resultlist)) {
    ?>
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-users"></i> Assign Subject Group
                                    </i> <?php echo form_error('student'); ?></h3>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <div class="box-body no-padding">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-4">
                                            <div class="table-responsive">
                                                <?php
foreach ($subjectgroupList as $subjectgroupList) {
        ?>
                                                    <h4>
                                                        <input type="hidden" name="subject_group_id" value="<?php echo $subjectgroupList->id; ?>">
                                                        <a href="#" data-toggle="popover" class="detail_popover"><?php echo $subjectgroupList->name; ?></a>
                                                    </h4>
                                                    <table class="table">
                                                        <thead>
                                                        <th>Subject --r</th>
                                                        <th>Code --r</th>
                                                        </thead>
                                                        <tbody>
                                                            <?php
if (empty($subjectgroupList->group_subject)) {
            ?>

                                                            <td colspan="5" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                                            <?php
} else {

            foreach ($subjectgroupList->group_subject as $subject_grp_key => $subject_grp_value) {
                ?>
                                                                <tr class="mailbox-name">
                                                                    <td>
                                                                        <?php echo $subject_grp_value->name; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $subject_grp_value->code; ?>
                                                                    </td>
                                                                </tr>
                                                                <?php
}
        }
        ?>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                    <?php
}
    ?>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class=" table-responsive">
                                                <table class="table table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <th><input type="checkbox" id="select_all"/> <?php echo $this->lang->line('all'); ?></th>
                                                            <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                            <th><?php echo $this->lang->line('student_name'); ?></th>
                                                            <th><?php echo $this->lang->line('class'); ?></th>
                                                            <th><?php echo $this->lang->line('father_name'); ?></th>
                                                            <th><?php echo $this->lang->line('category'); ?></th>
                                                            <th><?php echo $this->lang->line('gender'); ?></th>
                                                        </tr>
                                                        <?php
if (empty($resultlist)) {
        ?>
                                                            <tr>
                                                                <td colspan="7" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                                            </tr>
                                                            <?php
} else {
        $count = 1;
        foreach ($resultlist as $student) {
            ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php
if ($student['student_subject_group_id'] != 0) {
                $sel = "checked='checked'";
            } else {
                $sel = "";
            }
            ?>
                                                                        <input class="checkbox" type="checkbox" name="student_session_id[]"  value="<?php echo $student['student_session_id']; ?>" <?php echo $sel; ?>/>
                                                                        <input type="hidden" name="student_subject_group_id_<?php echo $student['student_session_id']; ?>" value="<?php echo $student['student_subject_group_id']; ?>">
                                                                        <input type="hidden" name="student_ids[]" value="<?php echo $student['student_session_id']; ?>">
                                                                    </td>
                                                                    <td><?php echo $student['admission_no']; ?></td>
                                                                    <td><?php echo $student['firstname'] . " " . $student['lastname']; ?></td>
                                                                    <td><?php echo $student['class'] . "(" . $student['section'] . ")" ?></td>
                                                                    <td><?php echo $student['father_name']; ?></td>
                                                                    <td><?php echo $student['category']; ?></td>
                                                                    <td><?php echo $student['gender']; ?></td>
                                                                </tr>
                                                                <?php
}
        $count++;
    }
    ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <button type="button" data-toggle="modal" data-target="#confirm-group_update" class="allot-group btn btn-primary btn-sm pull-right" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."><?php echo $this->lang->line('save'); ?>
                                            </button>

                                            <br/>
                                            <br/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
}
?>
                </form>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">

//select all checkboxes
    $("#select_all").change(function () {  //"select all" change
        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

//".checkbox" change
    $('.checkbox').change(function () {
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if (false == $(this).prop("checked")) { //if this item is unchecked
            $("#select_all").prop('checked', false); //change "select all" checked status to false
        }
        //check "select all" if all checkbox items are checked
        if ($('.checkbox:checked').length == $('.checkbox').length) {
            $("#select_all").prop('checked', true);
        }
    });

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
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }

    $(document).ready(function () {
        $("#confirm-group_update").modal({
            backdrop: false,
            show: false
        });
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
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        });
    });

    $(document).on('click', '.update_sub_group', function (e) {
        var $modalDiv = $('#confirm-group_update');
        $.ajax({
            type: "POST",
            dataType: 'Json',
            url: $("#assign_form").attr('action'),
            data: $("#assign_form").serialize(), // serializes the form's elements.
            beforeSend: function () {
                $modalDiv.addClass('modal_loading');
            },
            success: function (data)
            {
                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);
                }
            },

            error: function (xhr) { // if error occured
                alert("Error occured.please try again");

            },
            complete: function () {
                $modalDiv.modal('hide').removeClass('modal_loading');
            }
        });
    });
</script>

<div class="modal fade" id="confirm-group_update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Confirm Update --r</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to update record?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success update_sub_group">Update</button>
            </div>
        </div>
    </div>
</div>