<style type="text/css">
    .checked{
        color:orange;
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-secret"></i> <?php //echo $this->lang->line('teachers'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
            </div>
            <div class="col-md-12">
                <div class="box box-primary"><div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('teachers_reviews'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('teachers_reviews'); ?></div>

                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('teacher_name'); ?></th>
                                        <th><?php echo $this->lang->line('subject') ?></th>
                                        <th><?php echo $this->lang->line('time') ?></th>
                                        <th><?php echo $this->lang->line('room_no'); ?></th>
                                        <th><?php echo $this->lang->line('email'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <?php
if ($role == 'student') {
    ?>
                                            <th width="10%" class="text-right"><?php echo $this->lang->line('my_rating'); ?></th>
                                            <th width="15%"><?php echo $this->lang->line('comment'); ?></th>
                                            <th class="text-center"><?php echo $this->lang->line('rate'); ?></th>

                                            <?php
} elseif ($role == 'parent') {
    ?>
                                            <th><center><?php echo $this->lang->line('rating'); ?></center></th>
                                            <?php
}
?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($teacherlist)) {
    ?>

                                        <?php
} else {
    $count = 1;

    foreach ($teacherlist as $teacher) {

        $teacher1 = array();
        foreach ($teacher as $key => $value) {
            $teacher1[] = $value;
        }

        $class_teacher = '';
        if ($teacher[0]->class_teacher == $teacher[0]->staff_id) {
            $class_teacher = '<span class="label label-success bolds">' . $this->lang->line('class_teacher') . '</span>';
        }
        ?>
                                            <tr>
                                                <td class="mailbox-name"><?php echo $teacher[0]->name . " " . $teacher[0]->surname . " (" . $teacher[0]->employee_id . ") " . $class_teacher ?></td>
                                                <td><?php
foreach ($teacher1 as $value) {
            if ($value->day != '') {
                echo $value->subject_name . " " . $value->type;
                if ($value->code != '') {
                    echo " (" . $value->code . ")";
                }
                echo "<br/>";
            }
        }
        ?></td>
                                                <td><?php
foreach ($teacher1 as $value) {
            if ($value->day != '') {
                
                
                echo $this->lang->line(strtolower($value->day)) . " (" . $value->time_from . ' ' . $this->lang->line('to') . ' ' . $value->time_to . ") <br/>";
            }
        }
        ?></td>
                                                <td>

                                                    <?php
foreach ($teacher1 as $room_no) {
            if ($room_no->day != '') {
                echo $room_no->room_no . "<br/>";
            }
        }
        ?></td>
                                                <td><?php echo $teacher[0]->email ?></td>
                                                <td><?php echo $teacher[0]->contact_no ?></td>
                                                <?php
if ($role == 'student') {
            ?>
                                                    <td>
                                            <center>
                                                <?php
if (isset($reviews[$teacher[0]->staff_id])) {

                for ($i = 1; $i <= 5; $i++) {
                    ?>
                                                        <span class="fa fa-star" <?php if ($reviews[$teacher[0]->staff_id] >= $i) {?> style="color:orange;"<?php }?>></span>
                                                        <?php
}
            }
            ?></h3>
                                                <?php if (!empty($reviews[$teacher[0]->staff_id])) {?>
                                                <span style="display:none;" id="ratevalue"> <?php echo $reviews[$teacher[0]->staff_id]; ?></span>
                                                <?php }?>
                                                </center>
                                            </td>
                                            <td width="15%"> <?php if(isset($comment[$teacher[0]->staff_id])){ echo $comment[$teacher[0]->staff_id]; } ?>  </td>
                                            <td class="text-right">
                                                <?php
$reted = 0;
            foreach ($user_ratedstafflist as $value) {
                if ($value['staff_id'] == $teacher[0]->staff_id) {
                    $reted = 1;
                }
            }

            if ($reted == '0') {
                ?>
                                                    <a class="btn btn-default btn-xs" onclick="rating('<?php echo $teacher[0]->staff_id ?>')" data-toggle="tooltip" title=""  data-original-title="<?php echo $this->lang->line('add'); ?>" ><i class="fa fa-plus"></i></a><?php }?></td>

                                            <?php
} elseif ($role == 'parent') {
            ?>
                                            <td>
                                            <center>
                                                <h5><?php
if (isset($avg_rate[$teacher[0]->staff_id]) && $rate_canview == '1') {
                $stage     = (int) ($avg_rate[$teacher[0]->staff_id]);
                $stagehalf = "";
                $half      = fmod($avg_rate[$teacher[0]->staff_id], 1);
                if ($half != 0) {
                    $stagehalf = $stage + 1;
                }

                for ($i = 1; $i <= 5; $i++) {
                    ?>
                                                            <span class="fa fa-star<?php
if ($i == $stagehalf && ($half > 0 && $half < 1)) {
                        echo '-half-o checked';
                    }
                    ?> " <?php if ($stage >= $i) {?> style="color:orange;"<?php }?>></span>
                                                                  <?php
}
                ?></h5></center>
                                                <center><h5><?php echo substr($avg_rate[$teacher[0]->staff_id], 0, 3); ?> <?php echo $this->lang->line('average_based_on'); ?> <?php echo $reviews[$teacher[0]->staff_id]; ?> <?php echo $this->lang->line('reviews'); ?></h5></center>
                                            <?php }?>
                                            </td>
                                            <?php
}
        ?>
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
                    <div class="box-footer">
                        <div class="mailbox-controls">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="myModal" class="modal fade in" role="dialog" aria-hidden="true" >
    <div class="modal-dialog modal-dialog2">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?php echo $this->lang->line('rate') ?></h4>
            </div>
            <form id="sendform" action="<?php echo base_url() ?>emailconfig/test_mail" name="employeeform"   class="" method="post" accept-charset="utf-8">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="pwd"><?php echo $this->lang->line('rating') ?><small class="req"> *</small></label>
                                <span onclick="rate('1')" id='rate1' class="fa fa-star"></span>
                                <span onclick="rate('2')" id='rate2' class="fa fa-star"></span>
                                <span onclick="rate('3')" id='rate3' class="fa fa-star"></span>
                                <span onclick="rate('4')" id='rate4' class="fa fa-star"></span>
                                <span onclick="rate('5')" id='rate5' class="fa fa-star"></span>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="pwd"><?php echo $this->lang->line('comment'); ?><small class="req"> *</small></label>
                                <input type="text"  autocomplete="off" class="form-control" value="" name="comment">
                                <input type="hidden"  autocomplete="off" class="form-control" id="rate" name="rate">
                                <input type="hidden"  autocomplete="off" class="form-control" value="<?php echo $role; ?>" name="role" >
                                <input type="hidden"  autocomplete="off" class="form-control" value="<?php echo $user_id; ?>" name="user_id" >
                                <input type="hidden"  autocomplete="off" class="form-control" id="staff_id" name="staff_id" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
</div>
<script type="text/javascript">
    (function ($) {
        "use strict";
        $('#myModal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
            $(".select2").select2().select2("val", '');
        })
    })(jQuery);   

     function rating(id) {
        for (i = 1; i <= 5; i++) {
            $("#rate" + i).attr("style", "color:none;");
        }
        $('#myModal').modal('show');
        $('#staff_id').val(id);
    }

    function rate(val) {

        $('#rate').val(val);

        for (i = 1; i <= 5; i++) {
            $("#rate" + i).attr("style", "color:none;");
        }

        for (i = 1; i <= val; i++) {
            $("#rate" + i).attr("style", "color:#f1b624f0;");
        }

    }

    $("#sendform").on('submit', (function (e) {
        e.preventDefault();

        var $this = $(this).find("button[type=submit]:focus");
        $.ajax({
            url: "<?php echo site_url("user/teacher/rating") ?>",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $this.button('loading');

            },
            success: function (res)
            {
                if (res.status == "fail") {

                    var message = "";
                    $.each(res.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);

                } else {
                    successMsg(res.message);
                    window.location.reload(true);
                }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }

        });
    }));
</script>