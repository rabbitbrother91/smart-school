<style type="text/css">
    .nav-tabs-custom>.nav-tabs>li.active {
        border-top-color: #faa21c;
    }
</style>

<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php //echo $this->lang->line('human_resource'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
         <?php $this->load->view('reports/_human_resource');?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                      <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form role="form" action="<?php echo site_url('admin/payroll/payrollreport') ?>" method="post">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="">
                                     <div class="row">
                                        <div class="col-sm-4">
                                          <div class="form-group">
                                            <label><?php echo $this->lang->line('role'); ?></label>
                                            <select name="role" class="form-control">
                                                <option value="select"><?php echo $this->lang->line('select') ?></option>
                                                <?php foreach ($role as $rolekey => $rolevalue) {
    ?>
                                                    <option <?php
if ($rolevalue["type"] == $role_select) {
        echo "selected";
    }
    ?> value="<?php echo $rolevalue["type"] ?>"><?php echo $rolevalue["type"]; ?></option>
<?php }?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('role'); ?></span>
                                           </div>
                                        </div>
                                        <div class="col-sm-4">
                                          <div class="form-group">
                                            <label><?php echo $this->lang->line('month'); ?></label>
                                            <select name="month" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($monthlist as $monthkey => $monthvalue) {
    ?>
                                                    <option <?php
if ($month == $monthvalue) {
        echo "selected";
    }
    ?> value="<?php echo $monthvalue ?>"><?php echo $monthvalue; ?></option>
<?php }?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('month'); ?></span>
                                          </div>
                                        </div>
                                        <div class="col-sm-4">
                                          <div class="form-group">
                                            <label><?php echo $this->lang->line('year'); ?><small class="req"> *</small></label>
                                            <select name="year" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
<?php foreach ($yearlist as $yearkey => $yearvalue) {
    ?>
                                                    <option <?php
if (($year == $yearvalue["year"])) {
        echo "selected";
    }
    ?> value="<?php echo $yearvalue["year"]; ?>"><?php echo $yearvalue["year"]; ?></option>
<?php }?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('year'); ?></span>
                                          </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                            <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                        </div>
                                    </div>
                                  </div>
                                 </div>
                                </form>
                    </div><!--./box-body-->

<?php if (isset($result)) {
    ?>
                    <div class="">
                         <div class="box-header ptbnull"></div>
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('payroll_report'); ?></h3>
                        </div>
                        <div class="box-body table-responsive">
                            <div class="tab-content">
                                <div class="tab-pane active table-responsive" id="tab_parent">
                                    <div class="download_label"><?php echo $this->lang->line('payroll_report_for') . ' ' . "<br>";
    $this->customlib->get_postmessage(); ?></div>
                                    <table class="table table-striped table-bordered table-hover example table-fixed-header">
                                        <thead class="header">
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
$basic     = 0;
    $gross     = 0;
    $net       = 0;
    $earnings  = 0;
    $deduction = 0;
    $tax       = 0;

    if (empty($result)) {
        ?>

                                            <?php
} else {
        $count = 1;

        foreach ($result as $key => $value) {

            $basic += $value["basic"];
            $gross += $value["basic"] + $value["total_allowance"];
            $net += $value["net_salary"];
            $earnings += $value["total_allowance"];
            $deduction += $value["total_deduction"];
            $tax += $value["tax"];
            $total     = 0;
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
                                                        <span  data-original-title="" title=""><?php echo $value['designation'];

            ?></span>
                                                    </td>
                                                    <td>
            <?php echo $this->lang->line(strtolower($value['month'])) . " - " . $value['year']; ?>
                                                    </td>
                                                    <td>

                                                        <span data-toggle="popover" class="detail_popover" data-original-title="" title=""><a href="#"><?php echo $value['id']; ?></a></span>
                                                        <div class="fee_detail_popover" style="display: none"><?php echo $this->lang->line('mode'); ?>: <?php echo $payment_mode[$value["payment_mode"]] ?></div>

                                                    </td>
                                                    <td class="text text-right">
                                                        <?php echo amountFormat($value['basic']); ?>
                                                    </td>
                                                    <td class="text text-right">
            <?php echo amountFormat($value['total_allowance']); ?>
                                                    </td>
                                                    <td class="text text-right">
                                                        <?php
$t = ($value['total_deduction']);
            echo amountFormat($t);
            ?>
                                                    </td>
                                                    <td class="text text-right">
                                                        <?php echo amountFormat($value['basic'] + $value['total_allowance'] - $t); ?>
                                                    </td>
                                                    <td class="text text-right">
            <?php
$t = ($value['tax']);
            echo amountFormat($t);
            ?>
                                                    </td>
                                                    <td class="text text-right">
            <?php
$t = ($value['net_salary']);
            echo amountFormat($t);
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
                                                <td class="text text-right"><?php echo $currency_symbol . amountFormat($basic); ?></td>
                                                <td class="text text-right"><?php echo $currency_symbol . amountFormat($earnings); ?></td>
                                                <td class="text text-right"><?php echo $currency_symbol . amountFormat($deduction); ?></td>
                                                <td class="text text-right"><?php echo $currency_symbol . amountFormat($gross - $deduction); ?></td>
                                                <td class="text text-right"><?php echo $currency_symbol . amountFormat($tax); ?></td>
                                                <td class="text text-right"><?php echo $currency_symbol . amountFormat($net); ?></td>

                                            </tr>
                    <?php }?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div><!--./tabs-->
                  </div><!--./box box-primary-->
    <?php
}
?>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
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