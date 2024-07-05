<?php 
    if (empty($assignmentlist)) {    ?>
        <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
<?php
    } else { 
?>

<p><b><?php echo $this->lang->line('student_name'); ?>:</b> <?php if(!empty($assignmentlist[0])){ echo $this->customlib->getFullName($assignmentlist[0]['firstname'],$assignmentlist[0]['middlename'],$assignmentlist[0]['lastname'],$sch_setting->middlename,$sch_setting->lastname) . " (" . $assignmentlist[0]['student_admission_no'] . ")"; }
 ?></p>
<hr>
<div class="mailbox-messages table-responsive">
    <div class="download_label"><?php echo $this->lang->line('daily_assignment_details'); ?></div>        
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?php echo $this->lang->line('subject'); ?></th>
                <th><?php echo $this->lang->line('title'); ?></th>
                <th><?php echo $this->lang->line('description'); ?></th>
                <th><?php echo $this->lang->line('remark'); ?></th>
                <th><?php echo $this->lang->line('submission_date'); ?></th>
                <th><?php echo $this->lang->line('evaluation_date') ?></th>
                <th><?php echo $this->lang->line('evaluated_by'); ?></th>
                <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($assignmentlist as $key => $assignmentlist_value) {

                $evaluated_by = '';
                if($assignmentlist_value['evaluated_by'] !=0 ){
                    if($staffrole->id == 7){
                    
                        $evaluated_by = $assignmentlist_value['name'].' '.$assignmentlist_value['surname'].' ('.$assignmentlist_value['employee_id'].')';
                    
                    }elseif($superadmin_rest == 'enabled'){
                        
                        $evaluated_by = $assignmentlist_value['name'].' '.$assignmentlist_value['surname'].' ('.$assignmentlist_value['employee_id'].')';
                        
                    }elseif($assignmentlist_value['evaluated_by'] == $login_staff_id){
                        
                        $evaluated_by = $assignmentlist_value['name'].' '.$assignmentlist_value['surname'].' ('.$assignmentlist_value['employee_id'].')';
                        
                    }                    
                }

                if($assignmentlist_value['evaluation_date'] !=''){
                    $evaluation_date =  '';
                    $evaluation_date =  date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($assignmentlist_value['evaluation_date']));
                }else{
                    $evaluation_date = "" ;
                }

                ?>
                <tr>
                    <td class="mailbox-name"> <?php echo $assignmentlist_value['subject_name']; ?> <?php if($assignmentlist_value['subject_code']){ echo ' ('.$assignmentlist_value['subject_code'].')'; } ?></td>
                    <td class="mailbox-name"> <?php echo $assignmentlist_value['title']; ?></td>
                    <td class="mailbox-name"> <?php echo $assignmentlist_value['description']; ?></td>
                    <td class="mailbox-name"> <?php echo $assignmentlist_value['remark']; ?></td>
                    <td class="mailbox-name"> <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($assignmentlist_value['date'])); ?></td>
                    <td class="mailbox-name"> <?php echo $evaluation_date; ?></td>
                    <td class="mailbox-name"> <?php echo $evaluated_by; ?></td>
                    <td class="text-right">
                        <?php if($assignmentlist_value["attachment"] !=''){ ?>
                            <a class="btn btn-default btn-xs" href="<?php echo base_url(); ?>homework/dailyassigmnetdownload/<?php echo $assignmentlist_value['id']; ?>" data-toggle='tooltip' title="<?php echo $this->lang->line("download"); ?>"><i class="fa fa-download"></i></a>
                        <?php } ?>
                    </td>
                </tr>
                <?php
            }       
        ?>
        </tbody>
    </table>
</div><?php }?>