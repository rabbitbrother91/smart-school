<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="glyphicon glyphicon-th"></i> <?php echo $this->lang->line('report'); ?> <small> <?php echo $this->lang->line('filter_by_class'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form action="<?php echo site_url('studentfee/reportbyclass') ?>"  method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($classlist as $class) {
    ?>
                                                <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) {
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
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value="" ><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                </section>
                <?php
if (isset($student_fees_array)) {
    if (!empty($student_fees_array)) {
        ?>

                        <?php
foreach ($student_fees_array as $key => $student_array) {
            $st_detail = ($student_array['student_detail']);
            ?>
                            <section class="invoice" style="margin: 10px 15px;">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h2 class="page-header">
                                            <i class="fa fa-file-text-o"></i> <?php echo $st_detail['firstname'] . " " . $st_detail['lastname']; ?>
                                            <small class="pull-right"><?php echo $this->lang->line('date'); ?> <?php echo date($this->customlib->getSchoolDateFormat()); ?></small>
                                        </h2>
                                    </div>
                                </div>
                                <div class="row invoice-info">
                                    <div class="col-sm-4 invoice-col">
                                        <b><?php echo form_error('id'); ?> <?php echo $this->lang->line('id'); ?> :</b> <?php echo $st_detail['id']; ?><br>
                                        <b><?php echo form_error('roll_no'); ?> <?php echo $this->lang->line('roll_no'); ?> :</b> <?php echo $st_detail['roll_no']; ?><br>
                                        <b><?php echo form_error('admission_no'); ?> <?php echo $this->lang->line('admission_no'); ?> :</b> <?php echo $st_detail['admission_no']; ?><br>
                                    </div>
                                    <div class="col-sm-4 invoice-col">
                                        <b><?php echo form_error('date_of_birth'); ?> <?php echo $this->lang->line('dob'); ?> :</b> <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($st_detail['dob'])); ?><br>
                                        <b><?php echo form_error('mobile_no'); ?> <?php echo $this->lang->line('mobile_no'); ?> :</b> <?php echo $st_detail['mobileno']; ?><br>
                                        <b><?php echo form_error('email'); ?> <?php echo $this->lang->line('email'); ?> :</b> <?php echo $st_detail['email']; ?><br>
                                    </div>
                                    <div class="col-sm-4 invoice-col">
                                        <b><?php echo form_error('guardian_name'); ?> <?php echo $this->lang->line('guardian_name'); ?> :</b> <?php echo $st_detail['guardian_name']; ?><br>
                                        <b><?php echo form_error('guardian_phone'); ?> <?php echo $this->lang->line('guardian_phone'); ?> :</b> <?php echo $st_detail['guardian_phone']; ?><br>
                                        <b><?php echo form_error('guardian_address'); ?> <?php echo $this->lang->line('guardian_address'); ?> :</b> <?php echo $st_detail['guardian_address']; ?><br>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 table-responsive">
                                        <div class="download_label"> <?php echo $this->lang->line('student') . " " . $this->lang->line('profile'); ?></div>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $this->lang->line('invoice_no'); ?></th>
                                                    <th><?php echo $this->lang->line('date'); ?></th>
                                                    <th><?php echo $this->lang->line('category'); ?></th>
                                                    <th><?php echo $this->lang->line('type'); ?></th>
                                                    <th><?php echo $this->lang->line('status'); ?></th>
                                                    <th><?php echo $this->lang->line('amount'); ?></th>
                                                    <th><?php echo $this->lang->line('fine'); ?></th>
                                                    <th><?php echo $this->lang->line('discount'); ?></th>
                                                    <th><?php echo $this->lang->line('total'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
$target_amount   = "0";
            $deposite_amount = "0";
            foreach ($student_array['fee_detail'] as $key => $fee) {
                $target_amount  = $target_amount + $fee['amount'];
                $cls            = "";
                $total_row      = "xxx";
                $payment_status = "<span class='label label-success'><?php echo $this->lang->line('paid'); ?></span>";
                if ($fee['date'] == "xxx") {
                    $cls            = "text-red";
                    $payment_status = '<span class="label label-danger">Pending</span>';
                } else {
                    $deposite_amount = $deposite_amount + $fee['amount'];
                    $total_row       = number_format(($fee['amount'] + $fee['discount']) - $fee['fine'], 2, '.', '');
                }
                ?>
                                                    <tr>
                                                        <td ><a href="#" class="<?php echo $cls; ?>"><?php echo $fee['invoiceno']; ?></a></td>
                                                        <td class="<?php echo $cls; ?>"><?php echo $fee['date']; ?></td>
                                                        <td><?php echo $fee['category']; ?></td>
                                                        <td><?php echo $fee['type']; ?></td>
                                                        <td style="text-align: center;"><?php echo $payment_status; ?></td>
                                                        <td><?php echo $fee['amount']; ?></td>
                                                        <td class="<?php echo $cls; ?>"><?php echo $fee['discount']; ?></td>
                                                        <td class="<?php echo $cls; ?>"><?php echo $fee['fine']; ?></td>
                                                        <td class="<?php echo $cls; ?>"><?php echo $total_row; ?></td>
                                                    </tr>
                                                    <?php
}
            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                    </div>
                                    <div class="col-md-6">
                                        <p class="lead"><?php echo $this->lang->line('balanace_description'); ?></p>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th style="width:50%"><?php echo $this->lang->line('total_fees'); ?></th>
                                                        <td><?php echo number_format($target_amount, 2, '.', ''); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('total_paid_fees'); ?> :</th>
                                                        <td><?php echo number_format($deposite_amount, 2, '.', ''); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('total_balance'); ?>:</th>
                                                        <td><?php echo number_format(($target_amount - $deposite_amount), 2, '.', ''); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row no-print">
                                    <div class="col-xs-12">
                                        <a href="<?php echo base_url(); ?>studentfee/addfee/<?php echo $st_detail['id']; ?>" class="btn btn-primary pull-right"><i class="fa fa-credit-card"></i> <?php echo $this->lang->line('collect_fees'); ?></button>
                                            <a href="<?php echo base_url(); ?>report/pdfStudentFeeRecord/<?php echo $class_id; ?>/<?php echo $section_id ?>/<?php echo $st_detail['id']; ?>" class="btn bg-orange pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> <?php echo $this->lang->line('download_pdf'); ?></a>
                                    </div>
                                </div>
                            </section>
                            <?php
}
    } else {
        ?>
                        <section class="content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h4><i class="icon fa fa-info"></i> Info!</h4>
                                        <?php echo $this->lang->line('no_search_record_found'); ?>
                                    </div>
                                </div>
                            </div></section>
                        <?php
}
} else {

}
?>
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

                    $(document).on('change', '#section_id', function (e) {
                        getStudentsByClassAndSection();

                    });

                    var class_id = $('#class_id').val();
                    var section_id = '<?php echo set_value('section_id') ?>';
                    getSectionByClass(class_id, section_id);
                });
                
                function getStudentsByClassAndSection() {
                    $('#student_id').html("");
                    var section_id = $('#section_id').val();
                    var base_url = '<?php echo base_url() ?>';
                    var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                    $.ajax({
                        type: "GET",
                        url: base_url + "student/getByClassAndSection",
                        data: {'section_id': section_id},
                        dataType: "json",
                        success: function (data) {
                            $.each(data, function (i, obj)
                            {
                                div_data += "<option value=" + obj.id + " >" + obj.firstname + " " + obj.lastname + "</option>";
                            });
                            $('#student_id').append(div_data);
                        }
                    });
                }
            </script>