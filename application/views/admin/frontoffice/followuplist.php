<ul class="timeline timeline-inverse">
    <?php
    if (empty($follow_up_list)) {
        ?>
        <?php
    } else {
        
        foreach ($follow_up_list as $key => $value) {
            ?>           
            <li class="time-label">
                <span class="bg-blue">
                    <?php
                    echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['date']));
                    ?>
                </span>
            </li>
            <li>
                <i class="fa fa-phone bg-blue"></i>
                <div class="timeline-item">
                    <span class="time">
                        <?php
                        if ($this->rbac->hasPrivilege('follow_up_admission_enquiry', 'can_delete')) {
                            ?>
                            <a class="defaults-c text-right" data-toggle="tooltip" title="" onclick="delete_next_call(<?php echo $value['id']; ?>,<?php echo $id; ?>,'<?php echo $value['created_by']; ?>')" data-original-title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash"></i></a>
                        <?php } ?></span>
                    <h3 class="timeline-header"><a href="#"> <?php echo $value['name'].' '.$value['surname']; ?> (<?php echo $value['employee_id']; ?>)</a> </h3>
                    <div class="timeline-body">
                        <?php echo $value['response']; ?> 
                        <div class="divider"></div>
                        <?php echo $value['note']; ?> 
                    </div>
                    <div class="">

                    </div>
                </div>
            </li>
            <?php
        }
    }
    ?>
    <li><i class="fa fa-clock-o bg-gray"></i></li>
</ul>   
<script>
    var status = $('#status_data').val();
    function delete_next_call(follow_up_id, enquiry_id, created_by) {
       
        var permission = confirm("<?php echo $this->lang->line('are_you_sure_you_want_to_delete'); ?>");
        if (permission == false) {
           
        } else {
           
            $.ajax({
                url: '<?php echo base_url(); ?>admin/enquiry/follow_up_delete/' + follow_up_id + '/' + enquiry_id,

                success: function (data) {
                    follow_up(enquiry_id, created_by);

                },

                error: function () {
                    alert("<?php echo $this->lang->line('fail'); ?>");
                }
            });
        }
    }

    function follow_up(id, created_by) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/enquiry/follow_up/' + id + "/" + status+ "/" + created_by,
            success: function (data) {
                $('#getdetails_follow_up').html(data);
                $.ajax({

                    url: '<?php echo base_url(); ?>admin/enquiry/follow_up_list/' + id,

                    success: function (data) {
                        $('#timeline').html(data);
                    },

                    error: function () {
                        alert("<?php echo $this->lang->line('fail'); ?>");
                    }
                });
            },

            error: function () {
                alert("<?php echo $this->lang->line('fail'); ?>");
            }
        });
    }
</script>