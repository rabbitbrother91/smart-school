<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
if (!empty($student_list)) {

?>
<div class="row">
	<div class="col-md-12">
<div class="pull-right mb10">
<strong><?php echo $this->lang->line('collection_date'); ?>: </strong><?php echo $this->customlib->dateformat(date('Y-m-d',$date)); ?>
	</div>
</div>
</div>
<div class="table-responsive">
	<div class="download_label"><?php echo $this->lang->line('collection_list'); ?></div>	
     <table class="table table-striped table-bordered table-hover" id="ViewData" >
        <thead>
            <tr>            
                <th><?php echo $this->lang->line('admission_no') ?></th>              
                <th><?php echo $this->lang->line('name') ?></th>
                <th><?php echo $this->lang->line('father_name') ?> </th>
                <th><?php echo $this->lang->line('class') ?></th>
                <th><?php echo $this->lang->line('payment_mode') ?></th>
                <th><?php echo $this->lang->line('payment_id') ?></th>
                <th><?php echo $this->lang->line('collected_by') ?></th>
                <th class="text text-right"><?php echo $this->lang->line('fine') ?></th>
                <th class="text text-right"><?php echo $this->lang->line('amount') ?></th>
                <th><?php echo $this->lang->line('total') ?></th>                
            </tr>
        </thead>
    <tbody>
    	<?php 
  $total_fine=0;
  $total_amount=0;
foreach ($student_list as $student_key => $student_value) {
	if (isJSON($student_value->amount_detail)) {
		    $fees_details = (json_decode($student_value->amount_detail));		 
			 
		    foreach ($fees_details as $fees_key => $fees_value) {
		    
		    	if(strtotime($fees_value->date) == $date){
		    		
		    		$total_fine+=$fees_value->amount_fine;
		    		$total_amount+=$fees_value->amount;					
					
		    ?>
		    <tr>
		    	<td><?php echo $student_value->admission_no;?></td>
		    	<td><?php echo $this->customlib->getFullName($student_value->firstname,$student_value->middlename,$student_value->lastname,$sch_setting->middlename,$sch_setting->lastname); ?></td>
		    	<td><?php echo $student_value->father_name;?></td>
		    	<td><?php echo $student_value->class . " (" . $student_value->section . ")" ?></td>
		    	<td><?php echo $this->lang->line(strtolower($fees_value->payment_mode));  ?></td>
		    	<td><?php echo $student_value->student_fees_deposite_id."/".$fees_value->inv_no;  ?></td>
		    	<td>
					<?php 
                  

					if( is_object($fees_value) && (property_exists($fees_value, 'collected_by') )) { 			
						echo $fees_value->collected_by;	
					} 	
									
					?>
				</td>
		    	<td class="text text-right"><strong><?php echo $currency_symbol.amountFormat($fees_value->amount_fine);  ?></strong></td>		
		    	<td class="text text-right"><strong><?php echo $currency_symbol.amountFormat($fees_value->amount);  ?></strong></td>
		    	<td class="text text-right"><strong><?php echo $currency_symbol.amountFormat($fees_value->amount_fine+$fees_value->amount);  ?></strong></td>		    	
		    </tr>
		    <?php		
		    	}		    
		    }
	}
	}

    	 ?> 
			<tr>
			<td ></td>
    	 	<td ></td>
			<td ></td>
    	 	<td ></td>
    	 	<td ></td>
    	 	<td ></td>
    	 	<td ></td>
    	 	<td ></td>
			<td  class="text text-right"><strong> <?php echo $this->lang->line('grand_total'); ?></strong></td>
    	 	<td class="text text-right"><strong><?php echo $currency_symbol.amountFormat($total_amount + $total_fine); ?></strong></td>    	
    	 </tr>
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
            //responsive: 'false',
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