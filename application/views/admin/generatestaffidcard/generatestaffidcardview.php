<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <?php if ($this->session->flashdata('msg')) {
    ?>
            <?php echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg'); ?>
        <?php }?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <form role="form" action="<?php echo site_url('admin/generatestaffidcard/search') ?>" method="post" class="">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('role'); ?></label>
                                        <select autofocus="" id="role_id" name="role_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
if (!empty($staffRolelist)) {
    foreach ($staffRolelist as $staffRolelist_value) {
        ?>
                                                <option value="<?php echo $staffRolelist_value['id'] ?>" <?php if (set_value('role_id') == $staffRolelist_value['id']) {
            echo "selected=selected";
        }
        ?>><?php echo $staffRolelist_value['type'] ?></option>
                                                <?php
}}?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('role_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('id_card_template'); ?></label><small class="req"> *</small>
                                        <select  id="id_card" name="id_card" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
if (isset($idcardlist)) {
    foreach ($idcardlist as $idcardlist_value) {
        ?>
                                                    <option value="<?php echo $idcardlist_value->id ?>" <?php if (set_value('id_card') == $idcardlist_value->id) {
            echo "selected=selected";
        }
        ?>><?php echo $idcardlist_value->title ?></option>
                                                <?php
}
}
?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('id_card'); ?></span>
                                    </div>
                                </div>
                               <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php
if (isset($resultlist)) {
    ?>
                    <form method="post" action="<?php echo base_url('admin/generatestaffidcard/generatemultiple') ?>">
                        <div  class="" id="duefee">
                          <div class="box-header ptbnull"></div>
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('staff_list'); ?></h3>
                                <button class="btn btn-info btn-sm printSelected pull-right" type="button" name="generate" title="<?php echo $this->lang->line('generate_certificate'); ?>"><?php echo $this->lang->line('generate'); ?></button>
                            </div>
                            <div class="box-body table-responsive">
                                <div class="tab-pane active table-responsive no-padding" id="tab_1">
                                    <div class="download_label"><?php echo $this->lang->line('staff_list'); ?></div>
                                    <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="select_all" /></th>
                                                <?php if (!$adm_auto_insert) {?>
                                                <th><?php echo $this->lang->line('staff_id'); ?></th>
                                                <?php }?>
                                                <th><?php echo $this->lang->line('staff_name'); ?></th>
                                                <th><?php echo $this->lang->line('role'); ?></th>
                                                <th><?php echo $this->lang->line('designation'); ?></th>
                                                <th><?php echo $this->lang->line('department'); ?></th>
                                                <th><?php echo $this->lang->line('father_name'); ?></th>
                                                <th><?php echo $this->lang->line('mother_name'); ?></th>
                                                <th><?php echo $this->lang->line('date_of_joining'); ?></th>
                                                <th><?php echo $this->lang->line('phone'); ?></th>
                                                <th><?php echo $this->lang->line('date_of_birth'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
if (empty($resultlist)) {
        ?>
                                                <?php
} else {
        $count = 1;
        foreach ($resultlist as $staff_value) {

            ?>
                                                    <tr>
                                                        <td class="text-center"><input type="checkbox" class="checkbox center-block" data-staff_id="<?php echo $staff_value['id'] ?>"  name="check" id="check" value="<?php echo $staff_value['id'] ?>">
                                                            <input type="hidden" name="class_id" id="class_id" value="<?php echo $staff_value['user_type'] ?>">
                                                            <input type="hidden" name="id_card_id" id="id_card_id" value="<?php echo $idcardResult[0]->id ?>">
                                                        </td>
                                                        <?php if (!$adm_auto_insert) {?>
                                                        <td><?php echo $staff_value['employee_id']; ?></td>
                                                        <?php }?>
                                                        <td>
                                                            <a href="<?php echo base_url(); ?>admin/staff/profile/<?php echo $staff_value['id']; ?>"><?php echo $staff_value['name'] . " " . $staff_value['surname']; ?>
                                                            </a>
                                                        </td>
                                                         <td><?php echo $staff_value['user_type']; ?></td>
                                                        <td><?php echo $staff_value['designation']; ?></td>
                                                        <td><?php echo $staff_value['department']; ?></td>
                                                         <td><?php echo $staff_value['father_name']; ?></td>
                                                        <td><?php echo $staff_value['mother_name']; ?></td>
                                                        <td><?php if (!empty($staff_value['date_of_joining'] && $staff_value['date_of_joining'] != '0000-00-00')) {echo $this->customlib->dateFormat($staff_value['date_of_joining']);}?></td>

                                                        <td><?php echo $staff_value['contact_no']; ?></td>
                                                        <td><?php echo $this->customlib->dateFormat($staff_value['dob']); ?></td>
                                                    </tr>
                                                    <?php
$count++;
        }
    }
    ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
}
?>
              </div>
            </div>
        </div>
    </section>
</div>
<div class="response">
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#select_all').on('click', function () {
            if (this.checked) {
                $('.checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $('.checkbox').each(function () {
                    this.checked = false;
                });
            }
        });
        $('.checkbox').on('click', function () {
            if ($('.checkbox:checked').length == $('.checkbox').length) {
                $('#select_all').prop('checked', true);
            } else {
                $('#select_all').prop('checked', false);
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.printSelected', function () {
            var array_to_print = [];
            var idCard = $("#id_card_id").val();
            $.each($("input[name='check']:checked"), function () {
                var staffId = $(this).data('staff_id');
                item = {}
                item ["staff_id"] = staffId;
                array_to_print.push(item);
            });
            if (array_to_print.length == 0) {
                alert("<?php echo $this->lang->line('no_record_selected'); ?>");
            } else {
                $.ajax({
                    url: '<?php echo site_url("admin/generatestaffidcard/generatemultiple") ?>',
                    type: 'post',
                    dataType: "json",
                    data: {'data': JSON.stringify(array_to_print),'id_card': idCard },
                    success: function (response) {
                        Popup(response.page);
                    }
                });
            }
        });
    });
</script>

<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';
    function Popup(data)
    {
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');       
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