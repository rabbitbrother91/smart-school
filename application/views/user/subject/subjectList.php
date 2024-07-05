<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-language"></i> <?php echo $this->lang->line('subjects'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
            </div>
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('subjects'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('subject'); ?></th>
                                        <th><?php echo $this->lang->line('subject_code'); ?></th>
                                        <th><?php echo $this->lang->line('teacher'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('subject_type'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($subjectlist)) {
    ?>
                                        <tr>
                                            <td colspan="12" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                        </tr>
                                        <?php
} else {
    $count = 1;
    foreach ($subjectlist as $subject) {
        ?>
                                            <tr>
                                                <td class="mailbox-name"><?php echo $subject['name'] ?></td>
                                                <td class="mailbox-name"><?php echo $subject['code'] ?></td>
                                                <td class="mailbox-name"><?php echo $subject['teacher_name'] . " " . $subject['surname'] ?></td>
                                                <td class="mailbox-name text-right"><?php echo $subject['type'] ?></td>
                                            </tr>
                                            <?php
}
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
<script type="text/javascript">
    $(document).ready(function () {
        $("#btnreset").click(function () {
            $("#form1")[0].reset();
        });
    });
</script>