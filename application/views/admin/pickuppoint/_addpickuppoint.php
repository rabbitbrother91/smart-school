<?php 
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div id="<?php echo $delete_string;?>">
          <div class="row"> 
        <div class="col-md-4">
            <div class="form-group" >
                <label for="exampleInputEmail1"><?php echo $this->lang->line('pickup_point'); ?></label> <small class="req"> *</small>
                <input type="hidden" name="pickup_point_id[]" value="<?php echo $result['id']?>">
                <select class="form-control" name="pickup_point[]" style="width:95%" >
                    <option value=""><?php echo $this->lang->line('select');?></option>
                    <?php 
                    foreach ($listpickup_point as $key => $value) {
                        ?>
                        <option value="<?php echo $value['id'];?>" <?php if($value['id']==$result['pickup_point_id']){ echo "selected"; }?>> <?php echo $value['name']?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('distance'); ?></label>
                <div class="input-group">
                  <input type="text" value="<?php echo $result['destination_distance']?>" name="destination_distance[]"  class="form-control">
                  <span class="input-group-addon"><?php echo $this->lang->line('km');?></span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('pickup_time'); ?></label> <small class="req"> *</small>
                <div class="input-group">
                    <input value="<?php echo $this->customlib->timeFormat($result['pickup_time'],$this->customlib->getSchoolTimeFormat());?>" class="form-control time" name="time[]" />
                    <div class="input-group-addon"><span class="fa fa-clock-o"></span></div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('monthly_fees'); ?> <?php echo "(" . $currency_symbol . ")"; ?> </label> <small class="req"> *</small>
                <input value="<?php echo convertBaseAmountCurrencyFormat($result['fees']) ?>" class="form-control full-width" name="monthly_fees[]" />
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group text-center">
                <label>&nbsp;</label>
               <div class="pt3" <?php if(empty($result)){ ?> onclick="remove_pickpoint('<?php echo $delete_string;?>')" <?php }else{ ?> onclick="remove_editpickpoint('<?php echo $delete_string;?>')" <?php }?>  class="section_id_error text-danger">&nbsp;<i class="fa fa-remove"></i></div>               
            </div>
        </div>
     </div>
</div>