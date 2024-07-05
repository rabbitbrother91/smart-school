<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-user"></i> <?php echo $this->lang->line('search_student'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
               
                    <div class="nav-tabs-custom theme-shadow">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><i class="fa fa-list"></i> <?php echo $this->lang->line('student_list'); ?></a></li>
                            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><i class="fa fa-newspaper-o"></i> <?php echo $this->lang->line('details_view'); ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active table-responsive no-padding" id="tab_1">
                                <div class="download_label"><?php echo $this->lang->line('student_list'); ?></div>
                                 <table class="table table-striped table-bordered table-hover header-student-list" data-export-title="<?php echo $this->lang->line('student_list'); ?>">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('admission_no'); ?></th>
                                            <th><?php echo $this->lang->line('student_name'); ?></th>
                                            <th><?php echo $this->lang->line('roll_no'); ?></th>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <?php if ($sch_setting->father_name) { ?>
                                                <th><?php echo $this->lang->line('father_name'); ?></th>
                                            <?php } ?>
                                            <th><?php echo $this->lang->line('date_of_birth'); ?></th>
                                            <th><?php echo $this->lang->line('gender'); ?></th>
                                            <?php if ($sch_setting->category) { ?>
                                                <th><?php echo $this->lang->line('category'); ?></th>
                                            <?php }if ($sch_setting->mobile_no) { ?>
                                                <th><?php echo $this->lang->line('mobile_number'); ?></th>
                                            <?php } ?>

                                            <?php
                                            if (!empty($fields)) {

                                                foreach ($fields as $fields_key => $fields_value) {
                                                    ?>
                                                    <th><?php echo $fields_value->name; ?></th>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="tab_2">
                            
                            </div>
                        </div>
                    </div>
                
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
     var search_text ='<?php echo utf8_decode($search_text); ?>' ;
     
     if(search_text!=""){
        search_text = search_text
     }else{
        search_text=0
     }
     
     $.ajax({
           url: base_url+'admin/admin/search_text',
           type: "POST",
           dataType:'JSON',
           data: {search_text:'<?php echo $search_text; ?>'}, // serializes the form's elements.
              beforeSend: function () {               
              
               },
              success: function(response) { // your success handler
                
                if(!response.status){
                    $.each(response.error, function(key, value) {
                    $('#error_' + key).html(value);
                    });
                }else{
                 initDatatable_page('header-student-list','admin/admin/dtstudentlist',response.params,[],10);
               
                }
              },
             error: function() { // your error handler
                
             },
             complete: function() {
             
             }
         });      

});

var table_selected;

   function initDatatable_page(_selector,_url,params={},rm_export_btn=[],pageLength=100,aoColumnDefs=[{ "bSortable": false, "aTargets": [ -1 ] ,'sClass': 'dt-body-right'}],searching=true,aaSorting=[],dataSrc="data"){
      
        var table_selected= $('.'+_selector).DataTable({
        // "scrollX": true,

       dom: '<"top"f><Bl>r<t>ip',
         lengthMenu: [[100, -1], [100, "All"]],
          buttons: [
            {
                extend:    'copy',
                text:      '<i class="fa fa-files-o"></i>',
                titleAttr: 'Copy',
                 className: "btn-copy",
                title: $('.'+_selector).data("exportTitle"),
                  exportOptions: {
                    columns: ["thead th:not(.noExport)"]
                  }
            },
            {
                extend:    'excel',
                text:      '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Excel',
                     className: "btn-excel",
                title: $('.'+_selector).data("exportTitle"),
                  exportOptions: {
                    columns: ["thead th:not(.noExport)"]
                  }
            },
            {
                extend:    'csv',
                text:      '<i class="fa fa-file-text-o"></i>',
                titleAttr: 'CSV',
                className: "btn-csv",
                title: $('.'+_selector).data("exportTitle"),
                  exportOptions: {
                    columns: ["thead th:not(.noExport)"]
                  }
            },
            {
                extend:    'pdf',
                text:      '<i class="fa fa-file-pdf-o"></i>',
                titleAttr: 'PDF',
                className: "btn-pdf",
                title: $('.'+_selector).data("exportTitle"),
                  exportOptions: {
                    columns: ["thead th:not(.noExport)"]
                  },

            },
            {
                extend:    'print',
                text:      '<i class="fa fa-print"></i>',
                titleAttr: 'Print',
                className: "btn-print",
                title: $('.'+_selector).data("exportTitle"),
                customize: function ( win ) {

                    $(win.document.body).find('th').addClass('display').css('text-align', 'center');
                    $(win.document.body).find('table').addClass('display').css('font-size', '14px');
                     $(win.document.body).find('td').addClass('display').css('text-align', 'left');
                    $(win.document.body).find('h1').css('text-align', 'center');
                },
                exportOptions: {
                    columns: ["thead th:not(.noExport)"]
                    
                  }

            }
        ],
      
         // "scrollY":        "320px",
         
           "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">Loading...</span> ',
             sLengthMenu: "_MENU_"
        },
        "pageLength": pageLength,
        "searching": searching,
        "aaSorting": aaSorting, // default sorting [ [0,'asc'], [1,'asc'] ]
        "aoColumnDefs": aoColumnDefs, //disable sorting { "bSortable": false, "aTargets": [ 1,2 ] }
        "processing": true,
        "serverSide": true,

        "ajax":{
        "url": baseurl+_url,
        "dataSrc": dataSrc,
        "type": "POST",
        'data': params,
     },
       "drawCallback": function( settings ) {
      
        $('div#tab_2').html(settings.json.resultlist_view);
        // Output the data for the visible rows to the browser's console
  
    }
     
    });
    }


</script>