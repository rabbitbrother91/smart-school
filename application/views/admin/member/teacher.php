<style type="text/css">
    @media print {
       .noprint {
          visibility: hidden !important ;
       }
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">  
    <section class="content-header">
        <h1><i class="fa fa-book"></i> <?php //echo $this->lang->line('library'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row"> 
            <div class="col-md-12">              
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('staff_member_list'); ?></h3>
                        <div class="box-tools pull-right">

                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages overflow-visible-lg">
                            <div class="download_label"><?php echo $this->lang->line('staff_member_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('member_id'); ?></th>
                                        <th><?php echo $this->lang->line('library_card_no'); ?></th>
                                        <th><?php echo $this->lang->line('staff_name'); ?></th>
                                        <th><?php echo $this->lang->line('email'); ?></th>
                                        <th><?php echo $this->lang->line('date_of_birth'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th class="text text-right noExport" ><?php echo $this->lang->line('action'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($teacherlist)) {
                                        ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($teacherlist as $teacher) {
                                            $staff_id = '';
                                            if($teacher['employee_id'] !=''){
                                                $staff_id = ' ('.$teacher['employee_id'].')';
                                            }

                                            $clsactive = "a";
                                            $member_id = "";
                                            $library_card_no = "";
                                            if ($teacher['libarary_member_id'] != 0) {
                                                $clsactive = "success";
                                                $member_id = $teacher['libarary_member_id'];
                                                $library_card_no = $teacher['library_card_no'];
                                            }
                                            ?>
                                            <tr class="<?php echo $clsactive; ?>">
                                                <td><?php echo $member_id; ?></td>
                                                <td><?php echo $library_card_no; ?></td>
                                                <td class="mailbox-name"> <?php echo $teacher['name'] . " " . $teacher['surname']." ". $staff_id; ?></td>
                                                <td class="mailbox-name"> <?php echo $teacher['email'] ?></td>
                                                <td class="mailbox-name"> 
                                                <?php
                                                    if (!empty($teacher['dob']) && $teacher['dob'] != '0000-00-00') {
                                                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($teacher['dob']));
                                                    }
                                                ?>
                                                </td>
                                                <td class="mailbox-name"> <?php echo $teacher['contact_no'] ?></td>
                                                <td class="text text-right">
                                                    <?php
                                                    if ($teacher['libarary_member_id'] == 0) {
                                                        ?>

                                                        <button  data-stdid="<?php echo $teacher['id'] ?>" class="btn btn-default btn-xs add-teacher"  data-toggle="tooltip" title="<?php echo $this->lang->line('add'); ?>" >
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <button type="button" class="btn btn-default btn-xs surrender-teacher" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"  data-toggle="tooltip" data-memberid="<?php echo $member_id; ?>" title="<?php echo $this->lang->line('surrender_membership'); ?>"><i class="fa fa-mail-reply"></i></button>
                                                        <?php
                                                    }
                                                    ?>
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
            </div> 
        </div>
    </section>
</div>

<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="window.location.reload
                (true);" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="lineModalLabel"><?php echo $this->lang->line('add_member'); ?></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="click_member_id" value="0" id="click_member_id">
                <!-- content goes here -->
                <form action="<?php echo site_url('admin/member/addteacher') ?>" id="add_member" method="post">
                    <input type="hidden" name="member_id" value="0" id="member_id">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo $this->lang->line('library_card_no'); ?></label>
                        <input type="name" class="form-control" name="library_card_no" id="library_card_no" >
                        <span class="text-danger" id="library_card_no_error"></span>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm add-member" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('add'); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#squarespaceModal").modal({
            show: false,
            backdrop: 'static'
        });

        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $('#dob,#admission_date').datepicker({
            format: date_format,
            autoclose: true
        });
        $("#btnreset").click(function () {
            $("#form1")[0].reset();
        });
    });
</script>

<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';

    $(".add-teacher").click(function () {
        var student = $(this).data('stdid');
        $('#click_member_id').val(student);
        $('#member_id').val(student);
        $('#squarespaceModal').modal('show');
    });

    $(".surrender-teacher").click(function () {
        if (confirm('<?php echo $this->lang->line("are_you_sure_you_want_to_surrender_membership"); ?>')) {
            var memberid = $(this).data('memberid');
            var $this = $('.surrender-teacher');
            $this.button('loading');
            $.ajax({
                type: "POST",
                url: '<?php echo site_url('admin/member/surrender') ?>',
                data: {'member_id': memberid}, // serializes the form's elements.
                dataType: 'JSON',
                success: function (response)
                {
                    if (response.status == "success") {
                        successMsg(response.message);
                        $this.button('reset');
                        // window.setTimeout('location.reload()', 3000);
                        location.reload();
                    }
                }
            });
        }
    });

    $("#add_member").submit(function (e) {
        var student = $('#click_member_id').val();
        var $this = $('.add-member');
        $this.button('loading');
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $("#add_member").serialize(), // serializes the form's elements.
            dataType: 'JSON',
            success: function (response)
            {
                if (response.status == "success") {
                    $('#squarespaceModal').modal('hide');
                    $('#add_member')[0].reset();
                    successMsg(response.message);
                    // $this.button('reset');
                    location.reload();
                    // $('*[data-stdid="' + student + '"]').closest('tr').find('td:first').text(response.inserted_id);
                    // $('*[data-stdid="' + student + '"]').closest('tr').find('td:nth-child(2)').text(response.library_card_no);
                    // $('*[data-stdid="' + student + '"]').closest("tr").addClass("success");
                    // $('*[data-stdid="' + student + '"]').closest("td").empty();
                    
                } else if (response.status == "fail") {
                    $.each(response.error, function (index, value) {
                        var errorDiv = '#' + index + '_error';
                        $(errorDiv).empty().append(value);
                    });
                    $this.button('reset');
                }
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });
</script>

<script>
$(document).ready(function(){
  $(".buttons-print").click(function(){
     alert("hlo");
  });
});
</script>