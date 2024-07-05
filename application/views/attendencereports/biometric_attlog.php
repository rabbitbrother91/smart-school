<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }

    .pagination ul {margin: 0; padding: 0; padding-left: 10px;}
    .pagination ul li{list-style: none; display: inline-block;}
    .pagination ul li a {
        color: #000;
        position: relative;
        float: left;
        min-width: 1.5em;
        padding: 2px 8px;
        font-size: 12px;
        line-height: 1.5;
        text-decoration: none;
        transition: background-color .3s;
        border: 1px solid #ddd;
    }

    .pagination ul li a.active {
        background-color: #eee;
        color: #23527c;
        border: 1px solid #ddd;
    }

    /*.active{
      background-color: #eee;
      color: #23527c;
      border: 1px solid #ddd;
    
    }*/

    .pagination  ul li a:hover:not(.active) {background-color: #ddd;}
    .pagination> ul li:first-child>a, .pagination>ul li:first-child>span {
        margin-left: 0;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }
    .pagination> ul li:last-child>a, .pagination>ul li:last-child>span {
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
    }
</style>
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
        <h1>
            <i class="fa fa-bus"></i> <?php echo $this->lang->line('biometric') . " " . $this->lang->line('attendance') . " " . $this->lang->line('log'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('attendencereports/_attendance'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="">
                        <div class="box-header ptbnull"></div>
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo $this->lang->line('biometric') . " " . $this->lang->line('attendance') . " " . $this->lang->line('log'); ?></h3>
                        </div>
                        <div class="box-body table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('biometric') . " " . $this->lang->line('attendance') . " " . $this->lang->line('log'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <?php if (!$adm_auto_insert) { ?>
                                            <th><?php echo $this->lang->line('admission_no'); ?></th>
                                        <?php } ?>
                                        <th><?php echo $this->lang->line('student_name'); ?></th>
                                        <th><?php echo $this->lang->line('punch_in'); ?></th>	
                                        <th><?php echo $this->lang->line('device') . " " . $this->lang->line('serial') . " " . $this->lang->line('number'); ?></th>	
                                        <th><?php echo $this->lang->line('ip_address'); ?></th>			
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 0;
                                    if (empty($resultlist)) {
                                        ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($resultlist as $logs) {
                                            $device_info = json_decode($logs['biometric_device_data']);
                                            ?>
                                            <tr>
                                                <?php if (!$adm_auto_insert) { ?>
                                                    <td>
                                                        <?php
                                                        if (!empty($device_info)) {
                                                            echo $device_info->user_id;
                                                        }
                                                        ?>
                                                    </td> 
                                                    <?php
                                                }
                                                ?>
                                                <td>
                                                    <?php echo $logs['name']; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if (!empty($device_info)) {
                                                        echo $logs['created_at']; 
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if (!empty($device_info)) {
                                                        echo $device_info->serial_number;
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if (!empty($device_info)) {
                                                        echo $device_info->ip;
                                                    }
                                                    ?>
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row"><?php echo $this->pagination->create_links(); ?></div> 
                            </div>
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