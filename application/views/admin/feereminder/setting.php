<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> <?php echo $this->lang->line('fees') . " " . $this->lang->line('reminder'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- left column -->
                <form id="form1" action="<?php echo site_url('admin/feereminder/setting') ?>"  id="feereminder" name="feereminder" method="post" accept-charset="utf-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"> <?php echo $this->lang->line('fees_reminder'); ?></h3>
                        </div>
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php 
                                    echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg');
                                ?>
                            <?php } ?>
                            <!-- /.box-header -->
                            <!-- Button HTML (to Trigger Modal) -->
                            <table class="table table-hover ">
                                <thead>
                                    <th><?php echo $this->lang->line('action'); ?></th>
                                    <th><?php echo $this->lang->line('reminder_type'); ?></th>
                                    <th><?php echo $this->lang->line('days'); ?></th>
                                </thead>
                                <tbody>

                                    <?php
                                    $i = 1;
                                    $last_key = count($feereminderlist);
                                    foreach ($feereminderlist as $note_key => $note_value) {

                                        $hr = "";

                                        if ($i != $last_key) {
                                            $hr = "<hr>";
                                        }
                                        ?>

                                        <tr>
                                            <td width="15%">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="isactive_<?php echo $note_value->id; ?>" value="1" <?php echo set_checkbox('isactive_' . $note_value->id, 1, set_value('isactive_' . $note_value->id, $note_value->is_active) ? true : false); ?>> <?php echo $this->lang->line('active'); ?>
                                                </label>
                                            </td>
                                            <td width="15%">
                                                <input type="hidden" name="ids[]" value="<?php echo $note_value->id; ?>">
                                                <?php echo $this->lang->line($note_value->reminder_type); ?>
                                            </td>
                                            <td width="20%">
                                                <input type="number" name="days<?php echo $note_value->id; ?>" value="<?php echo set_value('days' . $note_value->id, $note_value->day) ?>" class="form-control">
                                            </td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="box-footer">
                            <?php if ($this->rbac->hasPrivilege('fees_reminder', 'can_edit')) {
                                ?>
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            <?php }
                            ?>
                            <?php ?>
                        </div>
                    </form>
                </div>
            </div>
        </div><!--./wrapper-->
    </section><!-- /.content -->
</div>