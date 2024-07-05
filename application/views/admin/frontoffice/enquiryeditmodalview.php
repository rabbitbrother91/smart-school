
        <form  action="<?php echo site_url('admin/enquiry') ?>" id="myForm1"  method="post"  class="ptt10">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="pwd"><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>  
                        <input type="text" class="form-control" id="name_value" value="<?php echo set_value('name', $enquiry_data['name']); ?>" name="name">
                        <span class="text-danger" id="name"></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="pwd"><?php echo $this->lang->line('phone'); ?></label><small class="req"> *</small>
                        <input id="number" name="contact" placeholder="" type="text" class="form-control"  value="<?php echo set_value('contact', $enquiry_data['contact']); ?>" />
                        <span class="text-danger"><?php echo form_error('contact'); ?></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('email'); ?></label>
                        <input type="text" value="<?php echo set_value('email', $enquiry_data['email']); ?>" name="email" class="form-control">
                        <span class="text-danger"><?php echo form_error('email'); ?></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="email"><?php echo $this->lang->line('address'); ?></label> 
                        <textarea name="address" class="form-control"><?php echo set_value('address', trim($enquiry_data['address'])) ?></textarea>
                        <span class="text-danger"><?php echo form_error('address'); ?></span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="email"><?php echo $this->lang->line('description'); ?></label>
                        <textarea name="description" class="form-control" ><?php echo set_value('description', $enquiry_data['description']); ?></textarea>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="pwd"><?php echo $this->lang->line('note'); ?></label> 
                        <textarea name="note" class="form-control" ><?php echo set_value('note', $enquiry_data['note']); ?></textarea>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="pwd"><?php echo $this->lang->line('date'); ?><small class="req"> *</small></label>
                        <input type="text" id="date_edit" name="date" class="form-control date" value="<?php
                        if (!empty($enquiry_data['date'])) {
                            echo set_value('date', date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($enquiry_data['date'])));
                        }
                        ?>" readonly="">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="pwd"><?php echo $this->lang->line('next_follow_up_date'); ?><small class="req"> *</small></label>
                        <input type="text" id="date_of_call_edit" name="follow_up_date"class="form-control date" value="<?php
                        if (!empty($enquiry_data['follow_up_date']) && $enquiry_data['follow_up_date'] != '0000-00-00') {
                            echo set_value('follow_up_date', date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($enquiry_data['follow_up_date'])));
                        }
                        ?>" readonly="">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('assigned'); ?></label>
						<select name="assigned" class="form-control">
                            <option value=""><?php echo $this->lang->line('select') ?></option>  
                            <?php foreach ($stff_list as $key => $stff_list_value) { ?>
                                 <option value="<?php echo $stff_list_value['id']; ?>" <?php if ($stff_list_value['id'] == $enquiry_data['assigned']) { ?>selected=""<?php } ?> ><?php echo $stff_list_value['name'].' '.$stff_list_value['surname']; ?> (<?php echo $stff_list_value['employee_id']; ?>)</option>    
                            <?php }   ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="pwd"><?php echo $this->lang->line('reference'); ?></label>   
                        <select name="reference" class="form-control">
                            <option value=""><?php echo $this->lang->line('select') ?></option>
                            <?php foreach ($Reference as $key => $value) { ?>
                                <option value="<?php echo $value['reference']; ?>" <?php if (set_value('reference', $enquiry_data['reference']) == $value['reference']) { ?>selected=""<?php } ?>><?php echo $value['reference']; ?></option>    
                            <?php }
                            ?>
                        </select>
                        <span class="text-danger"><?php echo form_error('reference'); ?></span>
                    </div>
                </div>    
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="pwd"><?php echo $this->lang->line('source'); ?></label><small class="req"> *</small>
                        <select name="source" class="form-control">
                            <option value=""><?php echo $this->lang->line('select') ?></option>
                            <?php foreach ($source as $key => $value) { ?>
                                <option value="<?php echo $value['source']; ?>"<?php
                                if ($enquiry_data['source'] == $value['source']) {
                                    echo "selected";
                                }
                                ?>><?php echo $value['source']; ?></option>
<?php }
?> 
                        </select>
                    </div>
                </div>      
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="pwd"><?php echo $this->lang->line('class'); ?></label> 
                        <select name="class" class="form-control"  >
                            <option value="" selected=""><?php echo $this->lang->line('select') ?></option>
                            <?php
                            foreach ($class_list as $key => $value) {
                                ?>
                                <option value="<?php echo $value['id'] ?>" <?php if (set_value('class', $enquiry_data['class_id']) == $value['id']) { ?> selected="" <?php } ?>><?php echo $value['class'] ?></option>

                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">                
                    <div class="form-group">
                        <label for="pwd"><?php echo $this->lang->line('number_of_child'); ?></label> 
                        <input type="number" class="form-control" min="1" value="<?php echo set_value('no_of_child', $enquiry_data['no_of_child']); ?>" name="no_of_child">
                        <span class="text-danger"><?php echo form_error('no_of_child'); ?></span>
                    </div>
                </div> 
            </div><!--./row-->                        
                <div class="row">    
                    <div class="box-footer row">
                        <a onclick="postRecord(<?php echo $enquiry_data['id'] ?>)" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></a>
                    </div>
                </div>  
            
        </form>
