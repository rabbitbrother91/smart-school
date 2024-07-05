<div class="table-responsive">    
    <table class="table table-striped">    
        <tr>
            <th class="border0" width="20%"><?php echo $this->lang->line('purpose'); ?></th>
            <td class="border0" width="40%"><?php echo $data['purpose']; ?></td>
            <th class="border0" width="15%"><?php echo $this->lang->line('meeting_with'); ?></th>
            <td class="border0" width="25%">
                <?php echo $this->lang->line($data['meeting_with']); ?>
                <?php if($data['staff_id'] !=0){ echo ' ('.$data['staff_name'].' '.$data['staff_surname']. ' - '.$data['staff_employee_id'].')'; } ?>
                <?php if($data['student_session_id'] !=0){ echo ' ('.$data['student_firstname'].' '.$data['student_middlename'].' '.$data['student_lastname'].' - '.$data['admission_no'].')'; } ?>
            </td>
        </tr>
        <?php if($data['student_session_id'] !=0){ ?>
        <tr>
            <th><?php echo $this->lang->line('class'); ?></th>
            <td><?php echo $data['class']; ?></td>
            <th><?php echo $this->lang->line('section'); ?></th>
            <td><?php echo $data['section']; ?></td>
        </tr>
       <?php } ?>
        <tr>
            <th><?php echo $this->lang->line('visitor_name'); ?></th>
            <td><?php echo $data['name']; ?></td>
            <th><?php echo $this->lang->line('phone'); ?></th>
            <td><?php echo $data['contact']; ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('number_of_person'); ?></th>
            <td><?php echo $data['no_of_people']; ?></td>
            <th><?php echo $this->lang->line('date'); ?></th>
            <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($data['date'])); ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('in_time'); ?></th>
            <td><?php echo $data['in_time']; ?></td>
            <th><?php echo $this->lang->line('out_time'); ?></th>
            <td><?php echo $data['out_time']; ?></td>
        </tr>
        <tr>           
            <th><?php echo $this->lang->line('id_card'); ?></th>
            <td><?php echo $data['id_proof']; ?></td>
            <th></th>
            <td></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('note'); ?></th>
            <td colspan="3"><?php echo $data['note']; ?></td>            
        </tr>
    </table>
</div>    