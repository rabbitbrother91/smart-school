<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?> </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">  
            <form id='feesforward' action="<?php echo site_url('admin/feesforward/index') ?>"  method="post" accept-charset="utf-8">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                            <div class="box-tools pull-right">
                            </div>
                        </div>
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-12">                                   
                                    <?php if ($this->session->flashdata('msg')) { ?>
                                        <?php echo $this->session->flashdata('msg');
                                        $this->session->unset_userdata('msg'); ?>
                                    <?php } ?>
                                </div>
                                <div class="col-md-6">                                   
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select  id="class_id" name="class_id" class="form-control"  >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                                ?>
                                                <option value="<?php echo $class['id'] ?>"<?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
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
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""   ><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <button type="submit" name="action" value ="search" class="btn btn-primary pull-right"><?php echo $this->lang->line('search'); ?></button>
                            </div>
                        </div> 

                        <?php
                        if (isset($student_due_fee)) {
                            ?>
                            <div class="box-header ptbnull"></div>   
                            <div class="">
                                <div class="box-header with-border">
                                    <h3 class="box-title titlefix"><?php echo $this->lang->line('previous_session_balance_fees'); ?></h3>
                                    <div class="pull-right">
                                        <span class="text text-danger pt6 bolds"><?php echo $this->lang->line('due_date'); ?>:</span> <?php echo set_value('due_date', $due_date_formated); ?>
                                        <input id="due_date" name="due_date" placeholder="" type="hidden" class="form-control date"  value="<?php echo set_value('due_date', $due_date_formated); ?>" readonly /> 
                                    </div>  
                                </div>
                                <div class="box-body">
                                    <?php
                                    if (!empty($student_due_fee)) {
                                        ?>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php
                                                if ($is_update) {
                                                    ?>
                                                    <div class="alert alert-info"><?php echo $this->lang->line('previous_balance_already_forwarded'); ?></div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="row col-xs-12">
                                                <div class="col-md-4">                          

                                                </div>
                                            </div>
                                            <div class="col-xs-12 table-responsive">
                                                <div class="download_label"><?php echo $this->lang->line('previous_session_balance_fees'); ?></div>
                                                <table class="table table-striped example">
                                                    <thead>
                                                        <tr>
                                                            <th class="text text-left"><?php echo $this->lang->line('student_name'); ?></th> 
                                                            <th class="text text-left"><?php echo $this->lang->line('admission_no'); ?></th>
                                                            <?php if ($sch_setting->admission_date) { ?>
                                                                <th class="text text-left"><?php echo $this->lang->line('admission_date'); ?></th>
                                                            <?php } if ($sch_setting->roll_no) { ?>
                                                                <th class="text text-left"><?php echo $this->lang->line('roll_number'); ?></th>
                                                            <?php } if ($sch_setting->father_name) { ?>
                                                                <th class="text text-left"><?php echo $this->lang->line('father_name'); ?></th><?php } ?>
                                                            <th class="text-right"><?php echo $this->lang->line('balance'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                        </tr>
                                                    </thead> 
                                                    <tbody>
                                                        <?php
                                                        $i = 1;
                                                        foreach ($student_due_fee as $due_fee_key => $due_fee_value) {
                                                           
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="student_counter[]" value="<?php echo $i; ?>">
                                                                    <input type="hidden" name="student_sesion[<?php echo $i; ?>]" value="<?php echo $due_fee_value->student_session_id; ?>">
                                                                    <?php echo $due_fee_value->name ?></td>  

                                                                <td><?php echo $due_fee_value->admission_no; ?></td>
                                                                <?php if ($sch_setting->admission_date) { ?>
                                                                    <td><?php echo $due_fee_value->admission_date; ?></td>
                                                                <?php } if ($sch_setting->roll_no) { ?>
                                                                    <td><?php echo $due_fee_value->roll_no; ?></td>
                                                                <?php } if ($sch_setting->father_name) { ?>
                                                                    <td><?php echo $due_fee_value->father_name; ?></td>
                                                                <?php } ?>
                                                                <td class="text text-right">
																	<span class="hidden"><?php echo convertBaseAmountCurrencyFormat($due_fee_value->balance); ?></span>
                                                                    <input type="text" name="amount[<?php echo $i; ?>]" class="form-control tddm200" value="<?php echo convertBaseAmountCurrencyFormat($due_fee_value->balance); ?>">
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            $i++;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="box-footer">
                                                <button type="submit" name="action" value="fee_submit" class="btn btn-primary pull-right"><?php echo $this->lang->line('save'); ?></button>
                                            </div>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div> 
                        <?php
                    }
                    ?>
                </div>
            </form>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id', 0) ?>';
        var hostel_id = $('#hostel_id').val();
        var hostel_room_id = '<?php echo set_value('hostel_room_id', 0) ?>';

        getSectionByClass(class_id, section_id);
    });

    $(document).on('change', '#class_id', function (e) {
        $('#section_id').html("");
        var class_id = $(this).val();

        getSectionByClass(class_id, 0);
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
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $.extend($.fn.dataTable.defaults, {
            searching: true,
            ordering: true,
            paging: false,
            retrieve: true,
            destroy: true,
            info: false
        });
    });
</script>
