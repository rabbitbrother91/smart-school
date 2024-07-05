<div class="content-wrapper">   
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?> <small><?php echo $this->lang->line('fee_type1'); ?></small>        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('fees_type', 'can_add')) {
                ?>
                <div class="col-md-4">               
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_fees_type'); ?></h3>
                        </div>  
                        <form id="form1" action="<?php echo site_url('feetype') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php 
                                        echo $this->session->flashdata('msg');
                                        $this->session->unset_userdata('msg');
                                    ?>
                                <?php } ?>  
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('fee_category'); ?></label>
                                    <select autofocus="" id="feecategory_id" name="feecategory_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($feecategorylist as $feecategory) {
                                            ?>
                                            <option value="<?php echo $feecategory['id'] ?>"
                                            <?php
                                            if (set_value('feecategory_id') == $feecategory['id']) {
                                                echo "selected =selected";
                                            }
                                            ?>
                                                    ><?php echo $feecategory['category'] ?></option>
                                                    <?php
                                                    $count++;
                                                }
                                                ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('feecategory_id'); ?></span>
                                </div>
                                <div class="form-group">dgh
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('fee_type'); ?></label>
                                    <input id=" type" name=" type" placeholder="" type="text" class="form-control"  value="<?php echo set_value(' type'); ?>" />
                                    <span class="text-danger"><?php echo form_error('type'); ?></span>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="button"  id="btnreset" class="btn btn-default"><?php echo $this->lang->line('reset'); ?></button>
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>          
                <div class="col-md-<?php
                if ($this->rbac->hasPrivilege('fees_type', 'can_add')) {
                    echo "8";
                } else {
                    echo "12";
                }
                ?>">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs pull-right">
                            <?php
                            $count = 1;
                            $total = count($category_array);
                            $array = array();
                            foreach ($category_array as $key => $category) {
                                $first_key = key($category);
                                $first_value = $category[$first_key];
                                $array[$first_key] = $category[$first_value];
                                $exapanded = "";
                                if ($total == $count) {
                                    $exapanded = "active";
                                }
                                ?>
                                <li class="<?php echo $exapanded; ?>"><a href="#tab_1-<?php echo $count; ?>" data-toggle="tab" class="tab_new"><?php echo $first_value ?></a></li>
                                <?php
                                $count++;
                            }
                            ?>
                            <li class="pull-left header"><?php echo $this->lang->line('fees_type_list'); ?></li>
                        </ul>
                        <div class="tab-content">
                            <?php
                            $counter = 1;
                            count($array);
                            foreach ($array as $arrkey => $arrvalue) {
                                $exapanded = "";
                                if ($total == $counter) {
                                    $exapanded = "active";
                                }
                                ?>
                                <div class="tab-pane <?php echo $exapanded; ?>" id="tab_1-<?php echo $counter; ?>">
                                    <input id="fee_master_id" type="hidden" value="0" >
                                    <div class="form-horizontal">
                                        <div class="box-body">
                                            <div class="print">
                                                <center> <b><?php echo $this->lang->line('fees_category'); ?>: </b> <span class="ftype"></span>
                                                </center>
                                            </div>
                                            <div class="box-tools pull-right">
                                                <button type='button' class="btn btn-default btn-sm pull-right no-print" onclick="printDiv('#ft');"><i class="fa fa-print"></i></button>
                                            </div>
                                            <table class="table table-hover table-striped" id="ft">
                                                <thead>
                                                    <tr>
                                                        <th> <?php echo $this->lang->line('fee_type'); ?> </th>
                                                        <th class="pull-right no-print"> <?php echo $this->lang->line('action'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (empty($arrvalue)) {
                                                        ?>
                                                        <tr>
                                                            <td colspan="12" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                                        </tr>
                                                        <?php
                                                    } else {
                                                        $count = 1;

                                                        foreach ($arrvalue as $arkey => $arvalue) {
                                                            ?>

                                                            <tr>
                                                                <td  class="mailbox-name">
                                                                    <?php echo $arvalue; ?></td>
                                                                <td  class="mailbox-date pull-right no-print">
                                                                    <a href="<?php echo base_url(); ?>feetype/edit/<?php echo $arkey; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                        <i class="fa fa-pencil"></i>
                                                                    </a>
                                                                    <a href="<?php echo base_url(); ?>feetype/delete/<?php echo $arkey; ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                        <i class="fa fa-remove"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        $count++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $counter++;
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#btnreset").click(function () {
                $("#form1")[0].reset();
            });
        });
    </script>

    <script type="text/javascript">
        var base_url = '<?php echo base_url() ?>';
    function printDiv(elem) {
        var ftype = $('.nav-tabs-custom').find("li.active").text();
        $('.ftype').html(ftype);
        Popup(jQuery('div.tab-pane.active').html());
    }

    function Popup(data)
    {

        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');


        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);


        return true;
    }
</script>