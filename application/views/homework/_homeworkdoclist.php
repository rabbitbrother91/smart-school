<?php foreach ($docs as $value) { ?>  
    <tr>
        <td><?php echo $this->customlib->getFullName($value['firstname'],$value['middlename'],$value['lastname'],$sch_setting->middlename,$sch_setting->lastname) . " (" . $value['admission_no'] . ")";

                         ?> 
						 </td>
        <td><?php echo $value["message"]; ?></td>
        <td class="mailbox-date pull-right">
        	<?php 
        	if($value['docs']!==''){ ?>
        		 <a class="btn btn-default btn-xs" href="<?php echo base_url(); ?>homework/assigmnetDownload/<?php echo $value['docs']; ?>" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('evaluation');?>">
                <i class="fa fa-download"></i></a>
        		<?php

        	}
        	?> 
           
        </td>
<?php } ?>