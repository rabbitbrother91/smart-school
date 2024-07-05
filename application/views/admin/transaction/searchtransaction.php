<style type="text/css">
    .nav-tabs-custom>.nav-tabs>li.active {
        border-top-color: #faa21c;
    }
</style>
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-line-chart"></i> <?php echo $this->lang->line('reports'); ?> <small> </small>   </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('search_transaction'); ?></h3>
                    </div>
                    <div class="box-body">

                        <form role="form" action="<?php echo site_url('admin/transaction/searchtransaction') ?>" method="post" class="">
                            <div class="row">

                                <?php echo $this->customlib->getCSRF(); ?>

                                <div class="col-sm-6 col-md-3" >
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('search') . " " . $this->lang->line('type'); ?></label>
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
                            </div><!--./row--> 
                        </form>

                    </div><!--./box-body-->

                    <div class="box-header ptbnull"></div>   
                    <?php if (isset($expenseList) && isset($feeList) && isset($incomeList) && isset($payrollList)) {
                        ?>
                        <div class="box bozero">
                            <h3 class="titless pull-left"></h3>
                            <div class="nav-tabs-custom">    
                                <ul class="nav nav-tabs pull-right">
                                    <li ><a href="#tab_payroll" data-toggle="tab"><?php echo $this->lang->line('payroll') . " " . $this->lang->line('details'); ?></a></li>  
                                    <?php
                                    if ($this->module_lib->hasActive('expense')) {
                                        ?>
                                        <li><a href="#tab_parent" data-toggle="tab"><?php echo $this->lang->line('expense_details'); ?></a></li>
                                    <?php } ?>
                                    <?php
                                    if ($this->module_lib->hasActive('income')) {
                                        ?>                      
                                        <li><a href="#tab_income" data-toggle="tab"><?php echo $this->lang->line('income_details'); ?></a></li>
                                    <?php } ?>   
                                    <?php
                                    if ($this->module_lib->hasActive('fees_collection')) {
                                        ?>                     
                                        <li class="active" ><a  href="#tab_students" data-toggle="tab"><?php echo $this->lang->line('fees_collection_details'); ?></a></li>
                                    <?php } ?> 

                                </ul>
                                <div class="box-body table-responsive">     
                                    <div class="tab-content">

                                        <div class="tab-pane table-responsive" id="tab_payroll"> 
                                            <div class="download_label"><?php echo $collection_title; ?></div> 
                                            <table class="table table-striped table-bordered table-hover example">
                                                <thead>
                                                    <tr>



                                                        <th><?php echo $this->lang->line('name'); ?></th>
                                                        <th><?php echo $this->lang->line('role'); ?></th>
                                                        <th><?php echo $this->lang->line('designation'); ?></th>
                                                        <th><?php echo $this->lang->line('month'); ?> - <?php echo $this->lang->line('year') ?></th>

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
                                                            $gross += $value["basic"] + $value["total_allowance"];
                                                            $net += $value["net_salary"];
                                                            $earnings += $value["total_allowance"];
                                                            $deduction += $value["total_deduction"];
                                                            $tax += $value["tax"];
                                                            $total = 0;
                                                            $grd_total = 0;
                                                            ?>
                                                            <tr>


                                                                <td style="text-transform: capitalize;">
                                                                    <span data-toggle="popover" class="detail_popover" data-original-title="" title=""><a href="#"><?php echo $value['name'] . " " . $value['surname']; ?></a></span>
                                                                    <div class="fee_detail_popover" style="display: none"><?php echo $this->lang->line('staff_id'); ?><?php echo ": " . $value['employee_id']; ?></div>
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
                                                                    <?php echo $value['month'] . " - " . $value['year']; ?>
                                                                </td>
                                                                <td>

                                                                    <span data-toggle="popover" class="detail_popover" data-original-title="" title=""><a href="#"><?php echo $value['id']; ?></a></span>
                                                                    <div class="fee_detail_popover" style="display: none"><?php echo $this->lang->line('mode'); ?>: <?php echo $payment_mode[$value["payment_mode"]] ?></div>

                                                                </td>
                                                                <td class="text text-right">
                                                                    <?php echo number_format($value['basic'], 2, '.', ''); ?>
                                                                </td>

                                                                <td class="text text-right">
                                                                    <?php echo (number_format($value['total_allowance'], 2, '.', '')); ?>
                                                                </td>
                                                                <td class="text text-right">
                                                                    <?php
                                                                    $t = ($value['total_deduction']);
                                                                    echo (number_format($t, 2, '.', ''))
                                                                    ?>
                                                                </td>
                                                                <td class="text text-right">
                                                                    <?php echo number_format($value['basic'] + $value['total_allowance'], 2, '.', ''); ?>
                                                                </td>
                                                                <td class="text text-right">
                                                                    <?php
                                                                    $t = ($value['tax']);
                                                                    echo (number_format($t, 2, '.', ''))
                                                                    ?>
                                                                </td>
                                                                <td class="text text-right">
                                                                    <?php
                                                                    $t = ($value['net_salary']);
                                                                    echo (number_format($t, 2, '.', ''))
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            $count++;
                                                        }
                                                        ?>
                                                        <tr class="box box-solid total-bg">

                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-right"><?php echo $this->lang->line('grand_total'); ?> </td>
                                                            <td class="text text-right"><?php echo ($currency_symbol . number_format($basic, 2, '.', '')); ?></td>

                                                            <td class="text text-right"><?php echo ($currency_symbol . number_format($earnings, 2, '.', '')); ?></td>
                                                            <td class="text text-right"><?php echo ($currency_symbol . number_format($deduction, 2, '.', '')); ?></td>
                                                            <td class="text text-right"><?php echo ($currency_symbol . number_format($gross, 2, '.', '')); ?></td>
                                                            <td class="text text-right"><?php echo ($currency_symbol . number_format($tax, 2, '.', '')); ?></td>
                                                            <td class="text text-right"><?php echo ($currency_symbol . number_format($net, 2, '.', '')); ?></td>



                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane active table-responsive" id="tab_students">
                                            <div class="download_label"><?php echo $income_title; ?></div>
                                            <table class="table table-striped table-bordered table-hover example table-fixed-header">
                                                <thead class="header">
                                                    <tr>
                                                        <th><?php echo $this->lang->line('payment_id'); ?></th>
                                                        <th><?php echo $this->lang->line('date'); ?></th>
                                                        <th><?php echo $this->lang->line('name'); ?></th>
                                                        <th><?php echo $this->lang->line('class'); ?></th>
                                                        <th><?php echo $this->lang->line('fee_type'); ?></th>
                                                        <th><?php echo $this->lang->line('mode'); ?></th>

                                                        <th class="text text-right"><?php echo $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                        <th class="text text-right"><?php echo $this->lang->line('discount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                        <th class="text text-right"><?php echo $this->lang->line('fine'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                        <th class="text text-right"><?php echo $this->lang->line('total'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $amount = 0;
                                                    $discount = 0;
                                                    $fine = 0;
                                                    $total = 0;
                                                    $grd_total = 0;
                                                    if (empty($feeList)) {
                                                        ?>

                                                        <?php
                                                    } else {
                                                        $count = 1;

                                                        foreach ($feeList as $key => $value) {


                                                            $amount = $amount + $value['amount'];
                                                            $discount = $discount + $value['amount_discount'];
                                                            $fine = $fine + $value['amount_fine'];
                                                            $total = ($amount + $fine);
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <?php echo $value['id'] . "/" . $value['inv_no']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['date'])); ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $value['firstname'] . " " . $value['lastname']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $value['class'] . " (" . $value['section'] . ")"; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $value['type']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $value['payment_mode']; ?>
                                                                </td>
                                                                <td class="text text-right">
                                                                    <?php echo number_format($value['amount'], 2, '.', ''); ?>
                                                                </td>
                                                                <td class="text text-right">
                                                                    <?php echo number_format($value['amount_discount'], 2, '.', ''); ?>
                                                                </td>
                                                                <td class="text text-right">
                                                                    <?php echo (number_format($value['amount_fine'], 2, '.', '')); ?>
                                                                </td>
                                                                <td class="text text-right">
                                                                    <?php
                                                                    $t = ($value['amount'] + $value['amount_fine']);
                                                                    echo (number_format($t, 2, '.', ''))
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            $count++;
                                                        }
                                                        ?>
                                                        <tr class="box box-solid total-bg">
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-right"><?php echo $this->lang->line('grand_total'); ?> </td>
                                                            <td class="text text-right"><?php echo ($currency_symbol . number_format($amount, 2, '.', '')); ?></td>
                                                            <td class="text text-right"><?php echo ($currency_symbol . number_format($discount, 2, '.', '')); ?></td>
                                                            <td class="text text-right"><?php echo ($currency_symbol . number_format($fine, 2, '.', '')); ?></td>
                                                            <td class="text text-right"><?php echo ($currency_symbol . number_format($total, 2, '.', '')); ?></td></tr>
                                                        <?php
                                                    }
                                                    ?>

                                                </tbody>
                                            </table>
                                        </div> 
                                        <div class="tab-pane table-responsive" id="tab_parent">
                                            <div class="download_label"><?php echo $expense_title; ?></div>
                                            <table class="table table-striped table-bordered table-hover example">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('expense_id'); ?></th>
                                                        <th><?php echo $this->lang->line('date'); ?></th>
                                                        <th><?php echo $this->lang->line('expense_head'); ?></th>
                                                        <th><?php echo $this->lang->line('name'); ?></th>
                                                        <th><?php echo $this->lang->line('invoice_no'); ?></th>
                                                        <th class="text text-right"><?php echo $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $count = 1;
                                                    $grand_total = 0;
                                                    if (empty($expenseList)) {
                                                        ?>

                                                        <?php
                                                    } else {
                                                        foreach ($expenseList as $key => $value) {
                                                            $grand_total = $grand_total + $value['amount'];
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <?php echo $value['id']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['date'])); ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $value['exp_category']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $value['name']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $value['invoice_no']; ?>
                                                                </td>
                                                                <td class="text text-right">
                                                                    <?php echo ($value['amount']); ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            $count++;
                                                        }
                                                        ?>
                                                        <tr class="box box-solid total-bg">
                                                            <td align="left"></td>
                                                            <td align="left"></td>
                                                            <td align="left"></td>
                                                            <td align="left"></td>
                                                            <td class="text-right"><?php echo $this->lang->line('grand_total'); ?></td>
                                                            <td class="text text-right">
                                                                <?php echo ($currency_symbol . number_format($grand_total, 2, '.', '')); ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>

                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="tab-pane table-responsive" id="tab_income"> 
                                            <div class="download_label"><?php echo $income_title; ?></div> 
                                            <table class="table table-striped table-bordered table-hover example">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('income_id'); ?></th>
                                                        <th><?php echo $this->lang->line('date'); ?></th>
                                                        <th><?php echo $this->lang->line('income_head'); ?></th>
                                                        <th><?php echo $this->lang->line('name'); ?></th>
                                                        <th><?php echo $this->lang->line('invoice_no'); ?></th>
                                                        <th class="text text-right"><?php echo $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $count = 1;
                                                    $grand_total = 0;
                                                    if (empty($incomeList)) {
                                                        ?>

                                                        <?php
                                                    } else {
                                                        foreach ($incomeList as $key => $values) {
                                                            $grand_total = $grand_total + $values['amount'];
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <?php echo $values['id']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($values['date'])); ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $values['income_category']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $values['name']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $values['invoice_no']; ?>
                                                                </td>
                                                                <td class="text text-right">
                                                                    <?php echo ($values['amount']); ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            $count++;
                                                        }
                                                        ?>
                                                        <tr class="box box-solid total-bg">
                                                            <td align="left"></td>
                                                            <td align="left"></td>
                                                            <td align="left"></td>
                                                            <td align="left"></td>
                                                            <td class="text-right"><?php echo $this->lang->line('grand_total'); ?></td>
                                                            <td class="text text-right">
                                                                <?php echo ($currency_symbol . number_format($grand_total, 2, '.', '')); ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                </div>
                                <div class="box-footer">
                                    <div class="mailbox-controls"> 
                                        <div class="pull-right">
                                        </div>
                                    </div>
                                </div>
                            </div></div><!--./tabs--> 
                        <?php
                    }
                    ?>



                    </section>
                </div>

                <script type="text/javascript">
                    $(document).ready(function () {
                        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
                        $(".date").datepicker({
                            format: date_format,
                            autoclose: true,
                            todayHighlight: true
                        });
                    });

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

                <script type="text/javascript">
                    $(document).ready(function () {
                        $.extend($.fn.dataTable.defaults, {
                            ordering: false,
                            paging: false,
                            bSort: false,
                            info: false
                        });
                    })
                    $(document).ready(function () {
                        $('.table-fixed-header').fixedHeader();
                    });

                    (function ($) {

                        $.fn.fixedHeader = function (options) {
                            var config = {
                                topOffset: 50

                            };
                            if (options) {
                                $.extend(config, options);
                            }

                            return this.each(function () {
                                var o = $(this);

                                var $win = $(window);
                                var $head = $('thead.header', o);
                                var isFixed = 0;
                                var headTop = $head.length && $head.offset().top - config.topOffset;

                                function processScroll() {
                                    if (!o.is(':visible')) {
                                        return;
                                    }
                                    if ($('thead.header-copy').size()) {
                                        $('thead.header-copy').width($('thead.header').width());
                                    }
                                    var i;
                                    var scrollTop = $win.scrollTop();
                                    var t = $head.length && $head.offset().top - config.topOffset;
                                    if (!isFixed && headTop !== t) {
                                        headTop = t;
                                    }
                                    if (scrollTop >= headTop && !isFixed) {
                                        isFixed = 1;
                                    } else if (scrollTop <= headTop && isFixed) {
                                        isFixed = 0;
                                    }
                                    isFixed ? $('thead.header-copy', o).offset({
                                        left: $head.offset().left
                                    }).removeClass('hide') : $('thead.header-copy', o).addClass('hide');
                                }
                                $win.on('scroll', processScroll);

                                // hack sad times - holdover until rewrite for 2.1
                                $head.on('click', function () {
                                    if (!isFixed) {
                                        setTimeout(function () {
                                            $win.scrollTop($win.scrollTop() - 47);
                                        }, 10);
                                    }
                                });

                                $head.clone().removeClass('header').addClass('header-copy header-fixed').appendTo(o);
                                var header_width = $head.width();
                                o.find('thead.header-copy').width(header_width);
                                o.find('thead.header > tr:first > th').each(function (i, h) {
                                    var w = $(h).width();
                                    o.find('thead.header-copy> tr > th:eq(' + i + ')').width(w);
                                });
                                $head.css({
                                    margin: '0 auto',
                                    width: o.width(),
                                    'background-color': config.bgColor
                                });
                                processScroll();
                            });
                        };

                    })(jQuery);

                </script>
