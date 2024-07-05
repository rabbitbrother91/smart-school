<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?> <small><?php echo $this->lang->line('student1'); ?></small></h1>
    </section>
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
    ?> <div class="alert alert-success">  <?php echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg'); ?> </div> <?php }?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('student/disablestudentslist') ?>" method="post" class="">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
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
                                        </div><!--./col-md-6-->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('section'); ?></label>
                                                <select  id="section_id" name="section_id" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                            </div>
                                        </div><!--./col-md-6-->

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div><!--./row-->
                            </div><!--./col-md-6-->
                            <div class="col-md-6">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('student/disablestudentslist') ?>" method="post" class="">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_keyword'); ?></label>
                                                <input type="text" name="search_text" class="form-control"   placeholder="<?php echo $this->lang->line('search_by_student_name'); ?>">
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

                    <div class="box-header ptbnull"></div>
                    <?php
if (isset($resultlist)) {
    ?>
                        <div class="nav-tabs-custom border0">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><i class="fa fa-list"></i> <?php echo $this->lang->line('list_view'); ?></a></li>
                                <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><i class="fa fa-newspaper-o"></i> <?php echo $this->lang->line('details_view'); ?></a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="download_label"> <?php echo $this->lang->line('disable_student_list'); ?></div>
                                <div class="tab-pane active table-responsive no-padding overflow-visible-lg" id="tab_1">
                                    <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                <th><?php echo $this->lang->line('student_name'); ?></th>
                                                <th><?php echo $this->lang->line('class'); ?></th>
                                                <?php if ($sch_setting->father_name) {?>
                                                    <th><?php echo $this->lang->line('father_name'); ?></th>
                                                <?php }?>
                                                <th><?php echo $this->lang->line('disable_reason'); ?></th>
                                                <th><?php echo $this->lang->line('gender'); ?></th>
                                                <?php if ($sch_setting->mobile_no) {?>
                                                    <th><?php echo $this->lang->line('mobile_number'); ?></th>
                                                <?php }?>
                                                <th class="pull-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
if (empty($resultlist)) {
        ?>

                                                <?php
} else {
        $count = 1;
        foreach ($resultlist as $student) {
            $reason_id = $student['dis_reason'];
            ?>
                                                    <tr>
                                                        <td><?php echo $student['admission_no']; ?></td>
                                                        <td>
                                                            <a href="<?php echo base_url(); ?>student/view/<?php echo $student['id']; ?>"><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?>
                                                            </a>
                                                        </td>
                                                        <td><?php echo $student['class'] . "(" . $student['section'] . ")" ?></td>
                                                        <?php if ($sch_setting->father_name) {?>
                                                            <td><?php echo $student['father_name']; ?></td>
                                                        <?php }?>
                                                        <td><span data-toggle="popover" class="detail_popover" data-original-title="" title=""><?php
if (array_key_exists($reason_id, $disable_reason)) {
                echo $disable_reason[$reason_id]['reason'];
            }
            ?></span>
                                                            <div class="fee_detail_popover" style="display: none"><?php echo $student['dis_note']; ?></div></td>
                                                        <td><?php echo $this->lang->line(strtolower($student['gender'])); ?></td>
                                                        <?php if ($sch_setting->category) {?>

                                                        <?php }if ($sch_setting->mobile_no) {?>
                                                            <td><?php echo $student['mobileno']; ?></td>
            <?php }?>
                                                        <td class="pull-right">
                                                            <a href="<?php echo base_url(); ?>student/view/<?php echo $student['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>" >
                                                                <i class="fa fa-reorder"></i>
                                                            </a>
                                                           
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
                                <div class="tab-pane" id="tab_2">
                                    <?php if (empty($resultlist)) {
        ?>
                                        <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                        <?php
} else {
        $count = 1;
        foreach ($resultlist as $student) {

            if (empty($student["image"])) {
                $image = "uploads/student_images/no_image.png";
            } else {
                $image = $student['image'];
            }
            ?>
                                            <div class="carousel-row">
                                                <div class="slide-row">
                                                    <div id="carousel-2" class="carousel slide slide-carousel" data-ride="carousel">
                                                        <div class="carousel-inner">
                                                            <div class="item active">
                                                                <a href="<?php echo base_url(); ?>student/view/<?php echo $student['id'] ?>"> <img class="img-responsive img-thumbnail width150" alt="<?php echo $student["firstname"] . " " . $student["lastname"] ?>" src="<?php echo $this->media_storage->getImageURL($image); ?>" alt="Image"></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="slide-content">
                                                        <h4><a href="<?php echo base_url(); ?>student/view/<?php echo $student['id'] ?>"> <?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></a></h4>
                                                        <div class="row">
                                                            <div class="col-xs-6 col-md-6">
                                                                <address>
                                                                    <strong><b><?php echo $this->lang->line('class'); ?>: </b><?php echo $student['class'] . "(" . $student['section'] . ")" ?></strong><br>

                                                                    <b><?php echo $this->lang->line('admission_no'); ?>: </b><?php echo $student['admission_no'] ?><br/>
                                                                    <b><?php echo $this->lang->line('date_of_birth'); ?>:
                                                                        <?php
if ((!empty($student['dob'])) && ($student['dob'] != null)) {
                echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']));
            }
            ?><br>
                                                                        <b><?php echo $this->lang->line('gender'); ?>:&nbsp;</b><?php echo $this->lang->line(strtolower($student['gender'])) ?><br>
                                                                        </address>
                                                                        </div>
                                                                        <div class="col-xs-6 col-md-6">
            <?php if ($sch_setting->local_identification_no) {?>
                                                                                <b><?php echo $this->lang->line('local_identification_number'); ?>:&nbsp;</b><?php echo $student['samagra_id'] ?><br>
                                                                            <?php }if ($sch_setting->guardian_name) {?>
                                                                            <b><?php echo $this->lang->line('guardian_name'); ?>:&nbsp;</b><?php echo $student['guardian_name'] ?><br><?php }if ($sch_setting->guardian_phone) {?>
                                                                            <b><?php echo $this->lang->line('guardian_phone'); ?>: </b> <abbr title="Phone"><i class="fa fa-phone-square"></i>&nbsp;</abbr> <?php echo $student['guardian_phone'] ?><br>
            <?php }if ($sch_setting->current_address) {?>
                                                                                <b><?php echo $this->lang->line('current_address'); ?>:&nbsp;</b><?php echo $student['current_address'] ?> <?php echo $student['city'] ?><br>
            <?php }?>
                                                                        </div>
                                                                        </div>
                                                                        </div>
                                                                        <div class="slide-footer">
                                                                            <span class="pull-right buttons">
                                                                                <a href="<?php echo base_url(); ?>student/view/<?php echo $student['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>" >
                                                                                    <i class="fa fa-reorder"></i>
                                                                                </a>       
                                                                            </span>
                                                                        </div>
                                                                        </div>
                                                                        </div>
                                                                        <?php
}
        $count++;
    }
    ?>
                                                                </div>
                                                                </div>
                                                                </div>

                                                                </div><!--./box box-primary-->
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
                                                                $(document).ready(function () {
                                                                    $('.detail_popover').popover({
                                                                        placement: 'right',
                                                                        title: '',
                                                                        trigger: 'hover',
                                                                        container: 'body',
                                                                        html: true,
                                                                        content: function () {
                                                                            return $(this).closest('td').find('.fee_detail_popover').html();
                                                                        }
                                                                    });
                                                                });
                                                            </script>