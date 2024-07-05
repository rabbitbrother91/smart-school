<?php if (!empty($result)) {
    ?>
    <ul class="timeline timeline-inverse">
        <?php
        foreach ($result as $key => $value) {
            ?>      
            <li class="time-label">
                <span class="bg-blue">    <?php
                    echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['timeline_date']));
                    ?></span>
            </li> 
            <li>
                <i class="fa fa-list-alt bg-blue"></i>
                <div class="timeline-item">
                    <?php if ($this->rbac->hasPrivilege('staff_timeline', 'can_delete')) { ?>
                        <span class="time"><a class="defaults-c text-right" data-toggle="tooltip" title="" onclick="delete_timeline('<?php echo $value['id']; ?>')" data-original-title="Delete"><i class="fa fa-trash"></i></a></span>
                    <?php } ?>
                    <?php if ($this->rbac->hasPrivilege('staff_timeline', 'can_edit')) {?>
                      <span class="time">
                          <a data-toggle="tooltip" class="pull-right edit_timeline defaults-c text-right" data-id="<?php echo $value["id"]; ?>" data-original-title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>
                        </span>
                    <?php }?>    
                    <?php if (!empty($value["document"])) { ?>
                        <span class="time"><a class="defaults-c text-right" data-toggle="tooltip" title="" href="<?php echo base_url() . "admin/timeline/download/" . $value["id"] . "/" . $value["document"] ?>" data-original-title="Download"><i class="fa fa-download"></i></a></span>
                    <?php } ?>
                    
                    <h3 class="timeline-header text-aqua"> <?php echo $value['title']; ?> </h3>
                    <div class="timeline-body">
                        <?php echo $value['description']; ?> 
                    </div>
                </div>
            </li>
        <?php } ?>      
        <li><i class="fa fa-clock-o bg-gray"></i></li> 
    </ul>
<?php } else {
    ?>
    <br/>
    <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
<?php }
?>
<script>
    $('.edit_timeline').click(function(){
        $('#edittimelineModal').modal('show');       
       var id = $(this).attr('data-id');
        $.ajax({
                url: "<?php echo site_url("admin/timeline/getstaffsingletimeline") ?>",
                type: "POST",
                data: {id:id},
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    $('#edittimelinedata').html(response.page);
                }
               
            });
    });
</script>