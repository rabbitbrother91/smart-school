<table class="table table-striped">
    <tbody>
    <tr>
        <th class="border0"><?php echo $this->lang->line('complain'); ?> #</th>
        <td class="border0"><?php echo $complaint_data['id']; ?></td>
        <th class="border0"><?php echo $this->lang->line('complaint_type'); ?></th>
        <td class="border0"><?php echo $complaint_data['complaint_type']; ?></td>
    </tr>
    <tr>
        <th><?php echo $this->lang->line('source'); ?></th>
        <td><?php echo $complaint_data['source']; ?></td>
        <th><?php echo $this->lang->line('name'); ?></th>
        <td><?php echo $complaint_data['name']; ?>   <?php if(!empty($complaint_data['email'])){ echo "(".$complaint_data['email'].")"; }?></td>
    </tr>
    <tr>
        <th><?php echo $this->lang->line('phone'); ?></th>
        <td><?php echo $complaint_data['contact']; ?></td>
        <th><?php echo $this->lang->line('date'); ?></th>
        <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($complaint_data['date'])); ?></td>
    </tr>
    <tr>
        <th><?php echo $this->lang->line('assigned'); ?></th>
        <td><?php echo $complaint_data['assigned']; ?></td>
        <th><?php echo $this->lang->line('action_taken'); ?></th>
        <td><?php echo $complaint_data['action_taken']; ?></td>
    </tr>
    <tr>
        <th><?php echo $this->lang->line('description'); ?></th>
        <td colspan="3"><?php echo $complaint_data['description']; ?></td>
    </tr>
    <tr>        
        <th><?php echo $this->lang->line('note'); ?></th>
        <td colspan="3"><?php echo $complaint_data['note']; ?></td>
    </tr>
</tbody>
</table>