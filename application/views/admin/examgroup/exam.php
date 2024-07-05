<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <section class="content-header">
        <h1><i class="fa fa-credit-card"></i><?php echo $this->lang->line('exam'); ?></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
if ($this->rbac->hasPrivilege('expense', 'can_add')) {
    ?>
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <input type="hidden" name="session_id" id="current_session_id"  value="<?php echo $current_session; ?>">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('exam_subject_list'); ?> </h3>
                        </div><!-- /.box-header -->

                        <div class="box-body">
                            <dl class="dl-horizontal">
                                <dt><?php echo $this->lang->line('exam'); ?></dt>
                                <dd><?php echo $examgroupDetail->exam; ?></dd>
                                <dt><?php echo $this->lang->line('exam_group'); ?></dt>
                                <dd><?php echo $examgroupDetail->exam_group_name; ?></dd>
                            </dl>
                            <table class="table table-bordered" id="subjects_table">
                                <thead>
                                    <tr>
                                        <th class="col-sm-3"><?php echo $this->lang->line('subject'); ?> </th>
                                        <th class="col-sm-2"><?php echo $this->lang->line('date_from'); ?></th>
                                        <th class="col-sm-2"><?php echo $this->lang->line('date_to'); ?></th>
                                        <th class="col-sm-2"><?php echo $this->lang->line('room_no'); ?> </th>
                                        <th class="col-sm-1"><?php echo $this->lang->line('marks_max'); ?></th>
                                        <th class="col-sm-1"><?php echo $this->lang->line('marks_min'); ?></th>
                                        <th class="col-sm-1 text-center"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
if (!empty($exam_subjects)) {
        foreach ($exam_subjects as $exam_subject_key => $exam_subject_value) {
            ?>
                                            <tr>

                                                <td><?php echo $exam_subject_value->subject_name; ?></td>
                                                <td><?php echo $exam_subject_value->date_from; ?></td>
                                                <td><?php echo $exam_subject_value->date_to; ?></td>
                                                <td><?php echo $exam_subject_value->room_no; ?></td>
                                                <td><?php echo $exam_subject_value->max_marks; ?></td>
                                                <td><?php echo $exam_subject_value->min_marks; ?></td>
                                                <td class="col-sm-1 text-center">
                                                    <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#subjectModal" data-subject_name="<?php echo $exam_subject_value->subject_name; ?>" data-subject_id="<?php echo $exam_subject_value->id; ?>"><i class="fa fa-newspaper-o" aria-hidden="true"></i></button>

                                                </td>
                                            </tr>
                                            <?php
}
    }
    ?>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
                <?php
}
?>

        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- Modal -->
<div id="subjectModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title subjectmodal_header"></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="searchStudentForm" action="<?php echo site_url('admin/examgroup/subjectstudent') ?>" method="post" class="form-horizontal">
                    <input type="hidden" name="subject_id" value="0" class="subject_id">
                    <div class="form-group">
                        <div class="col-sm-4">
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
}
?>
                            </select>
                            <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                <select  id="section_id" name="section_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label><?php echo $this->lang->line('session'); ?></label>
                            <select  id="session_id" name="session_id" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
foreach ($sessionlist as $session) {
    ?>
                                    <option value="<?php echo $session['id'] ?>" <?php echo set_select('session_id', 'session_id', (($current_session == $session['id']) ? true : false)); ?>><?php echo $session['session'] ?></option>
                                    <?php
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
                <div class="marksEntryForm">

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#subjectModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        })
    });
    
    $('#subjectModal').on('shown.bs.modal', function (e) {
        var subject_id = $(e.relatedTarget).data('subject_id');
        var subject_name = $(e.relatedTarget).data('subject_name');
        $('.subjectmodal_header').html("").html(subject_name);
        $('.marksEntryForm').html("");
        $('.subject_id').val("").val(subject_id);
        $(e.currentTarget).find('input[name="subject_name"]').val(subject_name);
    })

    $('#subjectModal').on('hidden.bs.modal', function () {
        var current_session = $('#current_session_id').val();
        $('.subjectmodal_header').html("");
        $('.marksEntryForm').html("");
        $('.subject_id').val("");
        $("#searchStudentForm").find('input:text,select,textarea').val('');
        $('#section_id').find('option').not(':first').remove();
        $('#session_id').val(current_session);
    });
    
    $(document).on('change', '#class_id', function (e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, section_id);
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

    $("form#searchStudentForm").on('submit', (function (e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);
        var $this = form.find("button[type=submit]:focus");
        var url = form.attr('action');
        $.ajax({
            url: url,
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (res)
            {
                $('.marksEntryForm').html(res.page);
            },
            error: function (xhr) { // if error occured
                alert("Error occured.please try again");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
    }
    ));

</script>
<script type="text/javascript">
    $.validator.addMethod("uniqueUserName", function (value, element, options)
    {
        var max_mark = $('#max_mark').val();
        //we need the validation error to appear on the correct element
        return parseFloat(value) <= parseFloat(max_mark);
    },
            "Invalid Marks"
            );
    $(document).ready(function () {
        var numberIncr = 1; // used to increment the name for the inputs

        $(document).on('submit', 'form#assign_form11', function (event) {
            event.preventDefault();
            $('form#assign_form11').validate();
            $('.marksssss').each(function () {
                $(this).rules("add",
                        {
                            required: true,
                            uniqueUserName: true,
                            messages: {
                                required: "Required",
                            }
                        });
            });

            // test if form is valid
            if ($('form#assign_form11').validate().form()) {
                var $this = $('.allot-fees');
                $.ajax({
                    type: "POST",
                    dataType: 'Json',
                    url: $("#assign_form11").attr('action'),
                    data: $("#assign_form11").serialize(), // serializes the form's elements.
                    beforeSend: function () {
                        $this.button('loading');
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

                        $this.button('reset');
                    },
                    complete: function () {
                        $this.button('reset');
                    }
                });
            } else {
                console.log("does not validate");
            }
        }) 
    });
</script>