<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
if (!empty($student_list)) {
    ?>

<div class="table-responsive">
    <div class="download_label"><?php echo $this->lang->line('student_list'); ?></div>
     <table class="table table-striped table-bordered table-hover" id="ViewData">
        <thead>
            <tr>
                <th><?php echo $this->lang->line('admission_no'); ?></th>
                <th><?php echo $this->lang->line('student_name'); ?></th>
                <th><?php echo $this->lang->line('class'); ?></th>
                 <?php if ($sch_setting->father_name) {?>
                <th><?php echo $this->lang->line('father_name'); ?></th>
                <?php }?>
                <th><?php echo $this->lang->line('date_of_birth'); ?></th>
                <th><?php echo $this->lang->line('gender'); ?></th>
                <?php if ($sch_setting->category) {
?>
                  <?php if ($sch_setting->category) {?>
                <th><?php echo $this->lang->line('category'); ?></th>
                <?php }
}if ($sch_setting->mobile_no) {
?>
                <th><?php echo $this->lang->line('mobile_number'); ?></th>
                <?php
}

if (!empty($fields)) {

foreach ($fields as $fields_key => $fields_value) {
?>
                <th><?php echo $fields_value->name; ?></th>
                        <?php
}
    }

    ?>
            </tr>
        </thead>
   <tbody>
                                        <?php
if (empty($student_list)) {
        ?>

                                            <?php
} else {
        $count = 1;

        foreach ($student_list as $student_key => $student) {
            ?>
                                                <tr>

                                    <td><?php echo $student['admission_no']; ?></td>

                                                <td>

                     <a target="_blank" href="<?php echo site_url('student/view/' . $student['id']); ?>"><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?>
                                                        </a>
                                                    </td>
                                                    <td><?php echo $student['class'] . "(" . $student['section'] . ")" ?></td>
                                                    <?php if ($sch_setting->father_name) {?>
                                                    <td><?php echo $student['father_name']; ?></td>
                                                    <?php }?>
                                                    <td><?php
if ($student["dob"] != null && $student["dob"] != '0000-00-00') {
                echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']));
            }
            ?></td>
                                                    <td><?php echo $this->lang->line(strtolower($student['gender'])); ?></td>
                                                    <?php if ($sch_setting->category) {?>
                                                    <td><?php echo $student['category']; ?></td>
                                                    <?php }if ($sch_setting->mobile_no) {?>
                                                    <td><?php echo $student['mobileno']; ?></td>
                                                    <?php }
            if (!empty($fields)) {

                foreach ($fields as $fields_key => $fields_value) {
                    $display_field = $student[$fields_value->name];
                    if ($fields_value->type == "link") {
                        $display_field = "<a href=" . $student[$fields_value->name] . " target='_blank'>" . $student[$fields_value->name] . "</a>";

                    }
                    ?>
                                                            <td>
                                                                <?php echo $display_field; ?>

                                                                </td>
                                                            <?php
}
            }
            ?>


                                                </tr>
                                                <?php
$count++;
        }
    }
    ?>
                                    </tbody>

</table>
</div>
<?php

} else {
    ?>
                                        <div class="alert alert-info">
                                            <?php echo $this->lang->line('no_record_found'); ?>
                                        </div>
                                        <?php
}
?>

<script type="text/javascript">
$(document).ready(function () {
        var table = $('#ViewData').DataTable({
            "aaSorting": [],
            rowReorder: {
            selector: 'td:nth-child(2)'
            },
            dom: "Bfrtip",
            buttons: [

                {
                    extend: 'copyHtml5',
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: 'Copy',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',

                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: 'CSV',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'

                    }
                },

                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: 'Print',
                    title: $('.download_label').html(),
                        customize: function ( win ) {
                    $(win.document.body)
                        .css( 'font-size', '10pt' );

                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                },
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'colvis',
                    text: '<i class="fa fa-columns"></i>',
                    titleAttr: 'Columns',
                    title: $('.download_label').html(),
                    postfixButtons: ['colvisRestore']
                },
            ]
        });
    });
</script>