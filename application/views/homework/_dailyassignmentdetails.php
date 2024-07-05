<style type="text/css">
    .paddlr {
    padding: 0px 15px 6px 5px !important; 
} 
</style>

<div class="row row-eq">
    <?php
    $admin = $this->customlib->getLoggedInUserData();
    ?>
    <div class="col-lg-9 col-md-9 col-sm-9 paddlr">
        <!-- general form elements -->
        <form id="evaluation_data" method="post">
            <div class="row">
                <div class="scroll-area">            
                    <?php if(!empty($assignmentlist)){ ?>                          
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                               <input type="hidden" name="assigment_id" value="<?php echo $assignmentlist['id']; ?>">
                                    <tr>
                                        <th><?php echo $this->lang->line('title') ?></th>
                                        <td class="valign-middle"><?php echo $assignmentlist['title']; ?></td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('description') ?></th>
                                        <td class="valign-middle"><?php echo $assignmentlist['description']; ?></td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('download') ?></th>
                                        <td class="valign-middle"><a class="btn btn-default btn-xs" href="<?php echo base_url(); ?>homework/dailyassigmnetdownload/<?php echo $assignmentlist['attachment']; ?>"  data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('download'); ?>"><i class="fa fa-download"></i></a></td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('remark') ?></th>
                                        <td class="valign-middle">
                                            <textarea name="remark" id="remark" rows="4" cols="141"><?php echo $assignmentlist['remark']; ?></textarea>
                                        </td>
                                    </tr>
                            </table>
                        </div>                             
                    <?php } ?>                 
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <hr class="mt0 mb10" />
                    <div class="row">   
                        <div class="col-md-2 col-sm-2 col-xs-2">  
                            <div class="form-group">
                                <label class="pt5"><?php echo $this->lang->line('evaluation_date'); ?> <small class="req"> *</small></label>
                            </div>
                        </div> 
                        <div class="col-md-8 col-sm-8 col-xs-8">
                            <div class="form-group">
                                <?php 
                                    if($assignmentlist['evaluation_date'] !=''){
                                       $evaluation_date = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($assignmentlist['evaluation_date']));
                                    }else{
                                       $evaluation_date = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date('Y-m-d')));
                                    }
                                ?>
                                <input type="text" id="evaluation_date" name="evaluation_date" class="form-control modalddate97 date" value="<?php echo $evaluation_date; ?>" readonly="">
                                <span class="text-danger" id="evaluation_date_error"></span>
                            </div>
                        </div>   
                        <div class="col-md-2 col-sm-2 col-xs-2"> 
                            <?php if ($this->rbac->hasPrivilege('homework_evaluation', 'can_add')) { ?> 
                                <div class="form-group">
                                    <input type="submit" name="" class="btn btn-info pull-right" value="<?php echo $this->lang->line('save'); ?>" />
                                </div>
                            <?php } ?>
                        </div> 
                    </div>  
                </div>
            </div><!-- /.row--> 
        </form>
    </div><!--/.col (left) -->
    <div class="col-lg-3 col-md-3 col-sm-3 col-eq">
        <div class="taskside">
            <h4 class="mt0"><?php echo $this->lang->line('summary'); ?></h4>
            <!-- <h5 class="pt0 task-info-created"></h5> -->
            <hr class="taskseparator mt12" />
            <div class="task-info task-single-inline-wrap ptt10">
                <?php if($assignmentlist['evaluation_date'] !=''){ ?>
                <label><span><?php echo $this->lang->line('evaluated_by'); ?></span>: <?php echo $evaluated_data['name'].' '.$evaluated_data['surname'].' ('.$evaluated_data['employee_id'].')'; ?></label>
                <?php } ?>            
                <label><span><?php echo $this->lang->line('class') ?></span>: <?php echo $assignmentlist['class']; ?></label>
                <label><span><?php echo $this->lang->line('section') ?></span>: <?php echo $assignmentlist['section']; ?></label>
                <label><span><?php echo $this->lang->line('subject') ?></span>: <?php echo $assignmentlist['subject_name']; ?></label>
            </div> 
        </div>
    </div>  
</div>

<script>
    $(document).ready(function () {
        $("#evaluation_data").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo site_url("homework/submitassignmentremark") ?>",
                type: "POST",
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (res)
                {
                    if (res.status == "fail") {
                        var message = "";
                        $.each(res.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(res.message);
                        window.location.reload(true);
                    }
                }
            });
        }));
    });
</script>