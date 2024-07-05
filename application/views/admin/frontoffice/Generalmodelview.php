 <div class="table-responsive">   
    <table class="table table-striped mb0">     
        <tr>
            <th class="border0"><?php echo $this->lang->line('name'); ?></th>
            <td class="border0"><?php echo ($Call_data['name']); ?></td>
            <th class="border0"><?php echo $this->lang->line('phone'); ?></th>
            <td class="border0"> <?php echo ($Call_data['contact']); ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('date'); ?></th>
            <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($Call_data['date'])); ?></td>
            <th><?php echo $this->lang->line('next_follow_up_date'); ?></th>
            <td><?php if($Call_data['follow_up_date']!='' && $Call_data['follow_up_date']!='0000-00-00'){ echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($Call_data['follow_up_date'])); } ?></td>
        </tr>
        <tr>
            <th class="white-space-nowrap"><?php echo $this->lang->line('call_duration'); ?></th>
            <td><?php echo ($Call_data['call_duration']); ?></td>
            <th><?php echo $this->lang->line('call_type'); ?></th>
            <td><?php echo ($Call_data['call_type']); ?></td>
        </tr>       
        <tr>
            <th><?php echo $this->lang->line('description'); ?></th>
            <td colspan="3"><?php echo ($Call_data['description']); ?></td>
        </tr> 
        <tr>
            <th><?php echo $this->lang->line('note'); ?></th>
            <td colspan="3"><?php echo ($Call_data['note']); ?></td>
        </tr> 
    </table>
</div>    