<style type="text/css">
    .list-group-item.active, .list-group-item.active:focus, .list-group-item.active:hover {
        z-index: 2;
        color: #444;
        background-color: #fff;
        border-color: #ddd;
    }

    a:link {
      color: black;
      background-color: transparent;
    }     
</style>

<div class="row row-eq h-85vh h-100vh-m">
    <?php
    $admin = $this->customlib->getLoggedInUserData();
    ?>
    <div class="col-lg-9 col-md-9 col-sm-9 paddlr">
        <!-- general form elements -->
        <form id="evaluation_data" method="post">
            <?php if (!empty($report)) { ?>
                <div class="alert alert-info"><?php echo $this->lang->line('homework_already_evaluated'); ?></div>
            <?php } ?>
            <div class="row">

                <div class="scroll-area">
                    <div class="test">                         
                        <div class="">
                        <?php if(!empty($studentlist)){ ?>
                            <ul multiple="" class="list-group mb0" id="slist">
                              <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="white-space-nowrap"><?php echo $this->lang->line('student_name') ?><small class="req"> *</small></th>
                                            <th><?php echo $this->lang->line('message') ?></th>
                                            <th><?php echo $this->lang->line('uploaded_documents'); ?></th>
                                        <?php if($marks !=0){ ?>
                                            <th><?php echo $this->lang->line('marks') ?> (<?php echo $result['marks']; ?>)</th>
                                        <?php } ?>
                                        </tr>
                                    </thead>
                                <?php  
                                foreach ($studentlist as $key => $value) {
                                    $active_status = false;
                                    if ($value["homework_evaluation_id"] != 0) {
                                        $active_status = true;
                                    }
                                    ?>                                    
                                        <tr>
                                            <li class="<?php echo ($active_status) ? "active" : "" ?>">
                                            <?php foreach($value['assignmentlist'] as $assign_key => $assign_value){ ?>
                                                <tr>
                                                <td class="white-space-nowrap">
                                                    <div class="checkbox mt0">
                                                        <label><input type="checkBox" <?php echo ($active_status) ? "checked='checked'" : "" ?>name="student_list[<?php echo $value["id"] ?>]" value="<?php echo $value["homework_evaluation_id"] ?>">                    
                                                            <?php echo $this->customlib->getFullName($value['firstname'],$value['middlename'],$value['lastname'],$sch_setting->middlename,$sch_setting->lastname) . " (" . $value['admission_no'] . ")"; ?>       
                                                                                         
                                                          
                                                          <input type="hidden" name="student_id[<?php echo $value["id"] ?>]"  value="<?php echo $value["student_id"] ?>"class="form-control">
                                                          
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="overflow-wrap-anywhere"><?php echo $assign_value['message']; ?></td>
                                                <td class="">
                                                <?php if($assign_value['docs'] !=''){ ?>                           
                                                    
                                                    <?php echo $this->media_storage->fileview($assign_value['docs']) ?>
                                                    
                                                    <a href="<?php echo site_url('homework/assigmnetDownload/'. $assign_value['submit_assignment_id']); ?>"  data-toggle="tooltip" data-placement="right" data-original-title="<?php echo $this->lang->line('download'); ?>"> <i class="fa fa-download"></i></a>

                                                <?php } ?>
                                                </td>
                                            <?php if($marks !=0){ ?>
                                                <td class="w-150"><input type="text" name="marks[<?php echo $value["id"] ?>]"  value="<?php echo $value["marks"] ?>"class="form-control w-150"></td>
                                            <?php } ?>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td colspan="3">
                                                        <textarea class="form-control" name="note[<?php echo $value["id"] ?>]" rows="2" placeholder="<?php echo $this->lang->line('note'); ?>"><?php echo $value["note"] ?></textarea>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </li>
                                        </tr>                 

                                    <?php
                                }
                                ?>
                                </table>
                              </div>  
                            </ul>
                            <?php } ?>
                        </div>
                    </div>
                </div>
               <div class="sticky-footer"> 
                    <div class="col-lg-12 col-md-12 col-sm-12">                   
                        <div class="row">   
                            <div class="col-md-2 col-sm-2 col-xs-12">  
                                <div class="form-group">
                                    <label class="pt5"><?php echo $this->lang->line('evaluation_date'); ?> <small class="req"> *</small></label>
                                </div>
                            </div> 
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <div class="form-group">
                                    <?php
                                    $evl_date = $this->customlib->dateformat(date('Y-m-d'));
                                    if (!IsNullOrEmptyString($result['evaluation_date'])) {
                                        $evl_date = $this->customlib->dateformat($result['evaluation_date']);
                                    }
                                    ?>
                                    <input type="text" id="evaluation_date" name="evaluation_date" class="form-control modalddate97 date" value="<?php echo $evl_date; ?>" readonly="">
                                    <input type="hidden" name="homework_id" value="<?php echo $result["id"] ?>">
                                    <?php
                                    if (!empty($report)) {
                                        foreach ($report as $key => $report_value) {
                                            ?>
                                            <input type="hidden" name="evalid[]" value="<?php echo $report_value["evalid"] ?>">
                                            <?php
                                        }
                                    }
                                    ?>
                                    <span class="text-danger" id="date_error"></span>
                                </div>
                            </div>   
                            <div class="col-md-2 col-sm-2 col-xs-12"> 
                                <?php if ($this->rbac->hasPrivilege('homework_evaluation', 'can_add')) { ?> 
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>
                                    </div>
                                <?php } ?>
                            </div> 
                        </div>  
                    </div>    
                </div>    
            </div><!-- /.row--> 
        </form>
    </div><!--/.col (left) -->
    <div class="col-lg-3 col-md-3 col-sm-3 col-eq">
        <div class="taskside scroll-area">
            <h4 class="mt0"><?php echo $this->lang->line('summary'); ?></h4>
            <hr class="taskseparator mt12" />
            <div class="task-info task-single-inline-wrap task-info-start-date">
                <h5><i class="fa task-info-icon fa-fw fa-lg fa-calendar-plus-o pull-left fa-margin"></i>
                    <span><?php echo $this->lang->line('homework_date'); ?></span>:<?php echo ($this->customlib->dateformat($result['homework_date'])); ?>                                      
                </h5>
            </div>
            <div class="task-info task-single-inline-wrap task-info-start-date">
                <h5><i class="fa task-info-icon fa-fw fa-lg fa-calendar-plus-o pull-left fa-margin"></i>
                    <span><?php echo $this->lang->line('submission_date'); ?></span>:<?php echo ($this->customlib->dateformat($result['submit_date'])); ?>                                      
                </h5>
            </div>
            <div class="task-info task-single-inline-wrap task-info-start-date">
                <h5><i class="fa task-info-icon fa-fw fa-lg fa-calendar-plus-o pull-left fa-margin"></i>
                    <span>
                    <?php echo $this->lang->line('evaluation_date'); ?></span>:
                    <?php
                    $evl_date = "";
                    if (!IsNullOrEmptyString($result['evaluation_date'])) {
                        echo $this->customlib->dateformat($result['evaluation_date']);
                    }
                    ?>
                </h5>
            </div>

            <div class="task-info task-single-inline-wrap ptt10">
                <label><span><?php echo $this->lang->line('created_by'); ?></span>: <?php echo $created_by; ?></label>
                <label><span><?php echo $this->lang->line('evaluated_by'); ?></span>: <?php echo $evaluated_by; ?></label>            
                <label><span><?php echo $this->lang->line('class') ?></span>: <?php echo $result['class']; ?></label>
                <label><span><?php echo $this->lang->line('section') ?></span>: <?php echo $result['section']; ?></label>
                <label><span><?php echo $this->lang->line('subject_group'); ?></span>: <?php echo $result['subject_group']; ?></label>
                <label><span><?php echo $this->lang->line('subject') ?></span>: <?php echo $result['name']; ?> <?php if($result['code']){ echo '('.$result['code'].')'; } ?></label>
                <label><span><?php echo $this->lang->line('total_marks') ?></span>: <?php if($result['marks'] != '0.00' || $result['marks'] !='0'){echo $result['marks']; } ?></label>
                <?php if (!empty($result["document"])) { ?>
                    <label><span><?php echo $this->lang->line('download'); ?></span>:<br> 
                    <?php echo $this->media_storage->fileview($result["document"]) ?>
                    <a data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>" href="<?php echo site_url("homework/download/" . $result["id"]) ?>"><i class="fa fa-download"></i></a></label>
                    <?php
                }
                if ((!empty($report)) && $report[0]['assignments'] != 0) {
                    ?>
                    <label><span><?php echo "Submited Assignment" ?></span>:</label>
                    <a data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>" class="btn btn-default btn-xs" onclick="homework_docs(<?php echo $result['id']; ?>);" data-toggle="tooltip"  data-original-title="Assignments">
                        <i class="fa fa-download"></i></a>
                    <?php } ?>
                <label><span><?php echo $this->lang->line('description'); ?></span>: <br/><?php echo $result['description']; ?></label>

            </div> 
        </div>
    </div>  
</div>

<script type="text/javascript">
    $(function () {

        $('body').on('click', '.list-group .list-group-item', function () {
            $(this).removeClass('active');
            $(this).toggleClass('active');
        });
        
        $('.list-arrows a').click(function () {
            var $button = $(this), actives = '';
            if ($button.hasClass('move-left')) {
                actives = $('#hlist option.active');
                actives.clone().appendTo('#slist');
                actives.remove();
            } else if ($button.hasClass('move-right')) {

                actives = $('#slist option.active');
                actives.clone().appendTo('#hlist');
                actives.remove();

            }
        });
        
        $('.dual-list .selector').click(function () {

            var $checkBox = $(this);
            if (!$checkBox.hasClass('selected')) {
                $checkBox.addClass('selected').closest('.test').find('select option:not(.active)').addClass('list-group-item active');

                $checkBox.children('i').removeClass('glyphicon-unchecked').addClass('glyphicon-check');
            } else {
                $checkBox.removeClass('selected').closest('.test').find('select option.active').removeClass('active');

                $checkBox.children('i').removeClass('glyphicon-check').addClass('glyphicon-unchecked');
            }
        });
        
        $('[name="SearchDualList"]').keyup(function (e) {
            var code = e.keyCode || e.which;
            if (code == '9')
                return;
            if (code == '27')
                $(this).val(null);
            var $rows = $(this).closest('.dual-list').find('.list-group option');
            var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
            $rows.show().filter(function () {
                var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                return !~text.indexOf(val);
            }).hide();
        });
    });
</script>
<script>

    function listbox_moveacross(sourceID, destID) {
        var src = document.getElementById(sourceID);
        var dest = document.getElementById(destID);

        for (var count = 0; count < src.options.length; count++) {

            if (src.options[count].selected == true) {
                var option = src.options[count];

                var newOption = document.createElement("option");
                newOption.value = option.value;
                newOption.text = option.text;
                newOption.selected = true;
                try {
                    dest.add(newOption, null); //Standard
                    src.remove(count, null);
                } catch (error) {
                    dest.add(newOption); // IE only
                    src.remove(count);
                }
                count--;
            }
        }
    }
</script>