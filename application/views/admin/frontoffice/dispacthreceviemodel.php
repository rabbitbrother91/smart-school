<div class="table-responsive">    
    <table class="table table-striped">      
        <tr>
            <th class="border0 white-space-nowrap" width="10%"><?php echo $this->lang->line('to_title'); ?></th>
            <td class="border0"><?php echo $data['to_title']; ?></td>
            <th class="border0" width="20%"><?php echo $this->lang->line('reference_no'); ?></th>
            <td class="border0" width="20%"><?php echo $data['reference_no']; ?></td>
        </tr>
        <tr>
            <th class="white-space-nowrap"><?php echo $this->lang->line('from_title'); ?></th>
            <td><?php echo $data['from_title']; ?></td>
            <th><?php echo $this->lang->line('date'); ?></th>
            <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($data['date'])); ?></td>
        </tr>
        <tr>
            <th class="white-space-nowrap"><?php echo $this->lang->line('address'); ?></th>
            <td colspan="3"><?php echo $data['address']; ?></td>
        </tr>
        <tr>
            <th><?php echo $this->lang->line('note'); ?></th>
            <td colspan="3"><?php echo $data['note']; ?></td>
        </tr>
    </table>
</div>    