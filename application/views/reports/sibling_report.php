<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-bus"></i> <?php //echo $this->lang->line('transport'); ?></h1>
    </section> 
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('reports/_studentinformation') ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form role="form" action="<?php echo site_url('report/sibling_report') ?>" method="post" class="" id="class_search_form"  >
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('class'); ?><small class="req"> *</small></label>
                                    <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($classlist as $class) {
                                            ?>
                                            <option value="<?php echo $class['id'] ?>" <?php
                                            if ($class_id == $class['id']) {
                                                echo "selected =selected";
                                            }
                                            ?>><?php echo $class['class'] ?></option>
                                                    <?php
                                                    $count++;
                                                }
                                                ?>
                                    </select>
                                    <span class="text-danger" id="error_class_id"><?php echo form_error('class_id'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('section'); ?><small class="req"> *</small></label>
                                    <select  id="section_id" name="section_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($section_list as $value) {
                                            ?>
                                            <option  <?php
                                            if ($value['section_id'] == $section_id) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $value['section_id']; ?>"><?php echo $value['section']; ?></option>
                                                <?php
                                            }
                                            ?>
                                    </select>
                                   <span class="text-danger" id="error_section_id"><?php echo form_error('section_id'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>    
                    </form>
                    <div class="">
                        <div class="box-header ptbnull"></div>
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-money"> </i> <?php echo $this->lang->line('sibling_report'); ?></h3>
                        </div>
                        <div class="box-body table-responsive">                            
                            <div class="download_label"><?php echo $this->lang->line('sibling_report')." ".$this->customlib->get_postmessage();
                                            ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <?php if ($sch_setting->father_name) { ?>
                                            <th><?php echo $this->lang->line('father_name'); ?></th>
<?php } if ($sch_setting->mother_name) { ?>
                                            <th><?php echo $this->lang->line('mother_name'); ?></th>
<?php } if($sch_setting->guardian_name){?>
                                        <th><?php echo $this->lang->line('guardian_name') ?></th>
                                        <?php } if($sch_setting->guardian_phone){?>
                                        <th><?php echo $this->lang->line('guardian_phone') ?></th>
                                    <?php } ?>
                                        <th><?php echo $this->lang->line('student_name_sibling'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
<?php if ($sch_setting->admission_date) { ?>
                                            <th><?php echo $this->lang->line('admission_date'); ?></th>
<?php } ?>
                                        <th><?php echo $this->lang->line('gender'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($resultlist)) {    ?>
                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($resultlist as $student) {
                                            if (count($student) > 1) {
                                                ?>
                                                <tr>
                                                <?php if ($sch_setting->father_name) { ?>
                                                    <td><?php echo $student[0]['father_name']; ?></td>
                                                <?php } if ($sch_setting->mother_name) { ?>
                                                    <td><?php echo $student[0]['mother_name']; ?></td>
                                                <?php } if($sch_setting->guardian_name){?>
                                                    <td><?php echo $student[0]['guardian_name']; ?></td>
                                                      <?php } if($sch_setting->guardian_phone){?>
                                                    <td><?php echo $student[0]['guardian_phone']; ?></td>
                                                <?php } ?>
                                                    <td>
                                                        <table>
                                                    <?php foreach ($student as $value) { ?>
                                                            <tr>
                                                                <td>
                                                                    <a href="<?php echo base_url(); ?>student/view/<?php echo $value['id']; ?>"><?php echo $this->customlib->getFullName($value['firstname'],$value['middlename'],$value['lastname'],$sch_setting->middlename,$sch_setting->lastname).' ('.$value['admission_no'].')'; ?></a> 
                                                                </td>
                                                            </tr>
                                                    <?php } ?>
                                                        </table>
                                                    </td>
                                                    <td>
                                                        <table>
                                                            <?php foreach ($student as $value) { ?>
                                                                <tr>
                                                                    <td>
                                                                <?php echo $value['class'] . " (" . $value['section'] . ")"; ?>
                                                                    </td>
                                                                </tr>                                                
                                                            <?php } ?>
                                                        </table>
                                                    </td>
                                                <?php if ($sch_setting->admission_date) { ?>
                                                    <td>
                                                        <table>
                                                            <?php foreach ($student as $value) { ?>  
                                                                <tr>
                                                                    <td>
                                                                        <?php
                                                                        if (!empty($value['admission_date'])) {
                                                                            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['admission_date']));
                                                                        }
                                                                        ?>     
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </table>
                                                    </td>
                                                <?php } ?>
                                        <td class="pull-right">
                                            <table width="100%">
                                                        <?php foreach ($student as $value) { ?>
                                                    <tr><td >
                                                            <?php
                                                            if (!empty($value['gender'])) {
                                                                echo $this->lang->line(strtolower($value['gender']));
                                                            }
                                                            ?>
                                                        </td></tr>
                                        <?php } ?>
                                            </table>
                                        </td>
                                        </tr>
                                        <?php
                                        $count++;
                                    }
                                }
                            }
                            ?>
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>  
        </div>   
</div>  
</section>
</div>

<script>
    $(document).on('change', '#class_id', function (e) {

        $('#section_id').html("");
        var class_id = $(this).val();

        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        var url = "";
        $.ajax({
            type: "GET",
            url: baseurl + "sections/getByClass",
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
</script>