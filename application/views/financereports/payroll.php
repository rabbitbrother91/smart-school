<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">
    /*REQUIRED*/
    .carousel-row {
        margin-bottom: 10px;
    }
    .slide-row {
        padding: 0;
        background-color: #ffffff;
        min-height: 150px;
        border: 1px solid #e7e7e7;
        overflow: hidden;
        height: auto;
        position: relative;
    }
    .slide-carousel {
        width: 20%;
        float: left;
        display: inline-block;
    }
    .slide-carousel .carousel-indicators {
        margin-bottom: 0;
        bottom: 0;
        background: rgba(0, 0, 0, .5);
    }
    .slide-carousel .carousel-indicators li {
        border-radius: 0;
        width: 20px;
        height: 6px;
    }
    .slide-carousel .carousel-indicators .active {
        margin: 1px;
    }
    .slide-content {
        position: absolute;
        top: 0;
        left: 20%;
        display: block;
        float: left;
        width: 80%;
        max-height: 76%;
        padding: 1.5% 2% 2% 2%;
        overflow-y: auto;
    }
    .slide-content h4 {
        margin-bottom: 3px;
        margin-top: 0;
    }
    .slide-footer {
        position: absolute;
        bottom: 0;
        left: 20%;
        width: 78%;
        height: 20%;
        margin: 1%;
    }
    /* Scrollbars */
    .slide-content::-webkit-scrollbar {
        width: 5px;
    }
    .slide-content::-webkit-scrollbar-thumb:vertical {
        margin: 5px;
        background-color: #999;
        -webkit-border-radius: 5px;
    }
    .slide-content::-webkit-scrollbar-button:start:decrement,
    .slide-content::-webkit-scrollbar-button:end:increment {
        height: 5px;
        display: block;
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-bus"></i> <?php //echo $this->lang->line('transport'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('financereports/_finance'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>

                    <form role="form" action="<?php echo site_url('financereports/payroll') ?>" method="post" class="">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-6 col-md-3" >
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search_type'); ?></label>
                                    <select class="form-control" name="search_type" onchange="showdate(this.value)">

                                        <?php foreach ($searchlist as $key => $search) {
                                            ?>
                                            <option value="<?php echo $key ?>" <?php
                                            if ((isset($search_type)) && ($search_type == $key)) {

                                                echo "selected";
                                            }
                                            ?>><?php echo $search ?></option>
                                                <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                </div>
                            </div>
                            <div id='date_result'>

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
                            <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo $this->lang->line('payroll_report'); ?></h3>
                        </div>
                        <div class="box-body table-responsive">
                            <div class="download_label"><?php
                                echo $this->lang->line('payroll_report').' '.
                                $this->customlib->get_postmessage();
                            
                                ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('role'); ?></th>
                                        <th><?php echo $this->lang->line('designation'); ?></th>
                                        <th><?php echo $this->lang->line('month_year'); ?></th>
                                        <th><?php echo $this->lang->line('payslip'); ?> #</th>
                                        <th class="text text-right"><?php echo $this->lang->line('basic_salary'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('earning'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('deduction'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('gross_salary'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('tax'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                        <th class="text text-right"><?php echo $this->lang->line('net_salary'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $basic = 0;
                                    $gross = 0;
                                    $net = 0;
                                    $earnings = 0;
                                    $deduction = 0;
                                    $tax = 0;

                                    if (empty($payrollList)) {
                                        ?>

                                        <?php
                                    } else {
                                        $count = 1;

                                        foreach ($payrollList as $key => $value) {
                                            $basic += $value["basic"];
                                            $gross += $value["basic"] + $value["total_allowance"]-$value['total_deduction'];                                       
                                            $earnings += $value["total_allowance"];
                                            $deduction += $value["total_deduction"];
                                            if ($value["tax"] != '') {
                                                $taxdata = $value["tax"];
                                            } else {
                                                $taxdata = 0;
                                            }
                                            $tax += $taxdata;
                                            $total = 0;
                                            $grd_total = 0;
                                            ?>
                                            <tr>
                                                <td style="text-transform: capitalize;">
                                                    <span data-toggle="popover" class="detail_popover" data-original-title="" title=""><a href="<?php echo base_url() ?>admin/staff/profile/<?php echo $value['staff_id']; ?>"><?php echo $value['name'] . " " . $value['surname']." (".$value['employee_id'].")"; ?></a></span>
                                                   
                                                </td>
                                                <td>
        <?php echo $value['user_type']; ?>
                                                </td>
                                                <td>
                                                    <span  data-original-title="" title=""><?php
                                                        echo $value['designation'];
                                                        ;
                                                        ?></span>

                                                </td>
                                                <td>
        <?php echo $this->lang->line(strtolower($value['month'])) . " - " . $value['year']; ?>
                                                </td>
                                                <td>

                                                    <span data-toggle="popover" class="detail_popover" data-original-title="" title=""><a href="#"><?php echo $value['id']; ?></a></span>
                                                    <div class="fee_detail_popover" style="display: none"><?php echo $this->lang->line('mode'); ?>: <?php
                                                        if (array_key_exists($value["payment_mode"], $payment_mode)) {
                                                            echo $payment_mode[$value["payment_mode"]];
                                                        }
                                                        ?></div>

                                                </td>
                                                <td class="text text-right">
        <?php if($value['basic'] > 0){ echo amountFormat($value['basic']); } ?>
                                                </td>

                                                <td class="text text-right">
                                                    <?php if($value['total_allowance'] > 0){ echo amountFormat($value['total_allowance']); } ?>
                                                </td>
                                                <td class="text text-right">
                                                    <?php
                                                    $total_deduction = ($value['total_deduction']);
                                                    if($total_deduction > 0){ echo amountFormat($total_deduction); }
                                                    ?>
                                                </td>
                                                <td class="text text-right">
                                                    <?php 
                                                    $gross = $value['basic'] + $value['total_allowance'] - $total_deduction ;
                                                    if($gross > 0){
                                                    echo amountFormat($gross); 
                                                    }                                                
                                                    
                                                    ?>
                                                </td>
                                                <td class="text text-right">
                                                    <?php
                                                    if ($value['tax']) {
                                                        $total_tax = amountFormat($value['tax']);
                                                    } else {
                                                        $total_tax = 0;
                                                    }

                                                    echo $total_tax;
                                                    ?>
                                                </td>
                                                <td class="text text-right">
                                                    <?php
                                                    $gross_amount = $value['basic'] + $value['total_allowance'] - $total_deduction-$taxdata;
                                                    
                                                    if($gross_amount > 0){
                                                        echo amountFormat($gross_amount);
                                                    }else{
                                                        echo 0;
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $count++;
                                        }
                                        ?>                                     
                                </tbody>
									<tr class="box box-solid total-bg">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><?php echo $this->lang->line('grand_total'); ?> </td>
                                        <td class="text text-right"><?php if($basic > 0){ echo $currency_symbol . amountFormat($basic); } ?></td>
                                        <td class="text text-right"><?php if($earnings > 0){ echo $currency_symbol . amountFormat($earnings); } ?></td>
                                        <td class="text text-right"><?php if($deduction > 0){ echo $currency_symbol . amountFormat($deduction); } ?></td>
                                        <td class="text text-right"><?php if($gross > 0){ echo $currency_symbol . amountFormat($gross); } ?></td>
                                        <td class="text text-right"><?php if($tax > 0){ echo $currency_symbol . amountFormat($tax); } ?></td>
                                        <td class="text text-right"><?php $grandtotal = $gross - $tax; if($grandtotal > 0){ echo $currency_symbol . amountFormat($grandtotal); }  ?></td>
                                    </tr> 
                                    <?php } ?>
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
<?php
if ($search_type == 'period') {
    ?>

        $(document).ready(function () {
            showdate('period');
        });

    <?php
}
?>

</script>