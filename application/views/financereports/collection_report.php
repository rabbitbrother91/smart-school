<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header"></section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('financereports/_finance');?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header ">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form role="form" action="<?php echo site_url('financereports/collection_report') ?>" method="post" class="">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-2 col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search_duration'); ?><small class="req"> *</small></label>
                                    <select class="form-control" name="search_type" onchange="showdate(this.value)">

                                        <?php foreach ($searchlist as $key => $search) {
    ?>
                                            <option value="<?php echo $key ?>" <?php
if ((isset($search_type)) && ($search_type == $key)) {
        echo "selected";
    }
    ?>><?php echo $search ?></option>
                                                <?php }?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                </div>
                            </div>

                            <div class="col-sm-2 col-lg-2 col-md-2">
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

                            <div class="col-sm-2 col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label>
                                    <select  id="section_id" name="section_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                </div>
                            </div>

                            <div class="col-sm-2 col-lg-2 col-md-2">
                               <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('fees_type'); ?></label>

                                            <select  id="feetype_id" name="feetype_id" class="form-control" >
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
foreach ($feetypeList as $feetype) {
    ?>
                                                    <option value="<?php echo $feetype['id'] ?>"<?php
if (set_value('feetype_id') == $feetype['id']) {
        echo "selected =selected";
    }
    ?>><?php echo $feetype['type'] ?></option>

                                                    <?php
$count++;
}
?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('feetype_id'); ?></span>
                                        </div>
                            </div>
                            <div class="col-sm-2 col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('collect_by'); ?></label>
                                    <select class="form-control"  name="collect_by" >
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <?php
foreach ($collect_by as $key => $value) {
    ?>
                                            <option value="<?php echo $key ?>" <?php
if ((isset($received_by)) && ($received_by == $key)) {
        echo "selected";
    }
    ?> ><?php echo $value ?></option>
                                                <?php }?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('collect_by'); ?></span>
                                </div>
                            </div>
                            <div id='date_result'>
                            </div>
                            <div class="col-sm-2 col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('group_by'); ?></label>
                                    <select class="form-control" name="group" >
                                        <?php foreach ($group_by as $key => $value) {
    ?>
                                            <option value="<?php echo $key ?>" <?php
if ((isset($group_byid)) && ($group_byid == $key)) {
        echo "selected";
    }
    ?> ><?php echo $value ?></option>
                                                <?php }?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('group'); ?></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" id="search_btn" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
 <?php
if (empty($results)) {
    ?>
<div class="box-header ptbnull">
    <div class="alert alert-info">
       <?php echo $this->lang->line('no_record_found'); ?>
    </div>
</div>
                                        <?php
} else {
    ?>
                    <div class="">
                        <div class="box-header ptbnull"></div>
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php ?> <?php echo $this->lang->line('fees_collection_report'); ?></h3>
                        </div>
                        
                       
                                
                                
                        <div class="box-body table-responsive" id="transfee">
                        <div id="printhead"><center><b><h4><?php echo $this->lang->line('fees_collection_report') . "<br>";
    $this->customlib->get_postmessage();
    ?></h4></b></center></div>
                            <div class="download_label"><?php echo $this->lang->line('fees_collection_report') . "<br>";
    $this->customlib->get_postmessage();
    ?></div>
    
   

                            <a class="btn btn-default btn-xs pull-right" id="print" onclick="printDiv()" ><i class="fa fa-print"></i></a>
                            <a class="btn btn-default btn-xs pull-right" id="btnExport" onclick="fnExcelReport();"> <i class="fa fa-file-excel-o"></i> </a>

                            <table class="table table-striped table-bordered table-hover " id="headerTable">
                                <thead class="header">
                                    <tr>
                                        <th><?php echo $this->lang->line('payment_id'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('fee_type'); ?></th>
                                        <th><?php echo $this->lang->line('collect_by'); ?></th>
                                        <th><?php echo $this->lang->line('mode'); ?></th>
                                        <th style="mso-number-format:'\@'" class="text text-right"><?php echo $this->lang->line('paid'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th style="mso-number-format:'\@'" class="text text-right"><?php echo $this->lang->line('discount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th style="mso-number-format:'\@'" class="text text-right"><?php echo $this->lang->line('fine'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th style="mso-number-format:'\@'" class="text text-right"><?php echo $this->lang->line('total'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                   <?php

    $count            = 1;
    $grdamountLabel   = array();
    $grddiscountLabel = array();
    $grdfineLabel     = array();
    $grdTotalLabel    = array();
//print_r($results);die;
    foreach ($results as $key => $value) {
        $payment_id    = array();
        $date          = array();
        $student_name  = array();
        $student_class = array();
        $fees_type     = array();
        $pay_mode      = array();
        $collection_by = array();
        $amountLabel   = array();
        $discountLabel = array();
        $fineLabel     = array();
        $TotalLabel    = array();
        $admission_no  = array();
        foreach ($value as $collect) {
            // $payment_id[]   = $collect['id'] . "/" . $collect['inv_no'];
            // $date[]         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($collect['date']));
            // $student_name[] = $this->customlib->getFullName($collect['firstname'], $collect['middlename'], $collect['lastname'], $sch_setting->middlename, $sch_setting->lastname);

            // $admission_no[] = $collect['admission_no'];

            // $student_class[] = $collect['class'] . " (" . $collect['section'] . ")";       
          
            // if ( $collect['is_system']) {
                // $fees_type[]     = $this->lang->line($collect['type']);
            // } else {
                // $fees_type[]     =$collect['type'];
            // }     
            
            $pay_mode[]      = $collect['payment_mode'];
            // if (is_array($collect['received_byname'])) {
                // $collection_by[] = $collect['received_byname']['name'] . " (" . $collect['received_byname']['employee_id'] . ")";
            // }

            $amountLabel[]   = number_format($collect['amount'], 2, '.', '');
            $discountLabel[] = number_format($collect['amount_discount'], 2, '.', '');
            $fineLabel[]     = number_format($collect['amount_fine'], 2, '.', '');
            $t               = $collect['amount'] + $collect['amount_fine'];
            $TotalLabel[]    = number_format($t, 2, '.', '');
        }
        ?>
            
                <?php 
                 foreach ($value as $collect) { ?>
                        <tr>
                            <td><?php echo $collect['id'] . "/" . $collect['inv_no']; ?></td>                
                            <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($collect['date'])); ?></td>                
                            <td><?php echo $collect['admission_no']; ?></td>                
                            <td><?php echo $this->customlib->getFullName($collect['firstname'], $collect['middlename'], $collect['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></td>                
                            <td><?php echo $collect['class'] . " (" . $collect['section'] . ")";    ?></td>                
                            
                            <td>
                                <?php
                                    if ( $collect['is_system']) {
                                        echo $this->lang->line($collect['type']);
                                    } else {
                                        echo $collect['type'];
                                    }    
                                ?>
                            </td>
                            <td>
                                <?php
                                    if (is_array($collect['received_byname'])) {
                                        echo $collect['received_byname']['name'] . " (" . $collect['received_byname']['employee_id'] . ")";
                                    }
                                ?>
                            </td>
                            <td>
                                <?php echo $this->lang->line(strtolower($collect['payment_mode'])); ?>
                            </td>
                            <td class="text text-right">
                                <?php echo number_format($collect['amount'], 2, '.', ''); ?>
                            </td>
                            <td class="text text-right">
                                <?php echo number_format($collect['amount_discount'], 2, '.', ''); ?>
                            </td>
                            <td class="text text-right">
                                <?php echo number_format($collect['amount_fine'], 2, '.', ''); ?>
                            </td> 
                            <td class="text text-right">
                                <?php echo number_format($t, 2, '.', ''); ?>
                            </td>                               
                                            
                                            
                        </tr>                    
                                            
                 <?php } ?>                            
                                            
                                            <?php
$count++;
        if ($subtotal) {
            ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td style="font-weight:bold"><?php echo $this->lang->line('sub_total'); ?></td>
                                                <td class="text text-right" style="font-weight:bold"><?php echo amountFormat(array_sum($amountLabel)); ?></td>
                                                <td class="text text-right" style="font-weight:bold" ><?php echo amountFormat(array_sum($discountLabel)); ?></td>
                                                <td class="text text-right" style="font-weight:bold" ><?php echo amountFormat(array_sum($fineLabel)); ?></td>
                                                <td class="text text-right " style="font-weight:bold" ><?php echo amountFormat(array_sum($TotalLabel)); ?></td>
                                            </tr>
                                            <?php
}
        $grdamountLabel[]   = array_sum($amountLabel);
        $grddiscountLabel[] = array_sum($discountLabel);
        $grdfineLabel[]     = array_sum($fineLabel);
        $grdTotalLabel[]    = array_sum($TotalLabel);
    }
    ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td style="font-weight:bold"><?php echo $this->lang->line('grand_total'); ?></td>
                                            <td class="text text-right" style="font-weight:bold"><?php echo amountFormat(array_sum($grdamountLabel)); ?></td>
                                            <td class="text text-right" style="font-weight:bold" ><?php echo amountFormat(array_sum($grddiscountLabel)); ?></td>
                                            <td class="text text-right" style="font-weight:bold" ><?php echo amountFormat(array_sum($grdfineLabel)); ?></td>
                                            <td class="text text-right " style="font-weight:bold" ><?php echo amountFormat(array_sum($grdTotalLabel)); ?></td>
                                        </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                     <?php
}
?>
                </div>
            </div>
        </div>
</div>
</section>
</div>
<iframe id="txtArea1" style="display:none"></iframe>

<script>

$(document).ready(function(){
    var class_id = $('#class_id').val();
    var section_id = '<?php echo $selected_section; ?>';
    getSectionByClass(class_id, section_id);
})

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

<?php
if ($search_type == 'period') {
    ?>

        $(document).ready(function () {
            showdate('period');
        });

    <?php
}
?>

document.getElementById("print").style.display = "block";
document.getElementById("btnExport").style.display = "block";
document.getElementById("printhead").style.display = "none";

function printDiv() {
    document.getElementById("print").style.display = "none";
    document.getElementById("btnExport").style.display = "none";
     document.getElementById("printhead").style.display = "block";
    var divElements = document.getElementById('transfee').innerHTML;
    var oldPage = document.body.innerHTML;
    document.body.innerHTML =
            "<html><head><title><?php echo $this->lang->line('fee_collection_report'); ?></title></head><body>" +
            divElements + "</body>";
    window.print();
    document.body.innerHTML = oldPage;
    document.getElementById("printhead").style.display = "none";
    location.reload(true);
}
 
</script>