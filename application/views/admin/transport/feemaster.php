<?php 
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-bus"></i> <?php //echo $this->lang->line('transport'); ?></h1>
    </section>
    <section class="content"> 
        <div class="row">                 
            <div class="col-md-12">
                <div class="box box-primary" id="route">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('transport_fees_master'); ?></h3>   
                    </div>
                    <form action="" id="fee_form" method="POST">
                        <div class="box-body feemaster">
                            <?php 
                            if(!empty($month_list)){
                                $count =1; 
                            ?>

                            <?php if($this->session->flashdata('msg') !=''){ ?>
                            <div class="alert alert-success"><?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?></div>
                            <?php } ?>
  
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="checkbox mb0 mt0">
                                    <label for="copy_other">
                                        <input class="copy_other" id="copy_other" value="1" type="checkbox" > <?php echo $this->lang->line('copy_first_fees_detail_for_all_months'); ?>
                                    </label></div>
                                </div>
                            </div>
                            <?php
                            foreach ($month_list as $month_key => $month_value) {

                                $chk=false;
                                $inserted_array=array(
                                        'id'=>'0',
                                        'month'=>'',
                                        'fine_type'=>'',
                                        'due_date'=>'',
                                        'fine_percentage'=>'',
                                        'monthly_fees'=>0,
                                        'fine_amount'=>'',
                                );
                                $record=multiKeyExists($transportfees, 'month' ,$month_key);


                                if($record > -1){
                                $chk=true;
                                $inserted_array=$transportfees[$record];
                              
                                  if(isset($_POST['fine_type_'.$count])){ 
                                        $inserted_array['fine_type'] =$_POST['fine_type_'.$count];
                                    } 
                               
                                ?>
                                <input type="hidden" name="old_ids[]" value="<?php echo $inserted_array['id']; ?>">
                                <?php
                                }
                            ?>

                            <div class="row block_row">       
                                <hr class="hrexam">
                                <div class="col-sm-2 col-lg-2 col-md-2">         
                                    <h4 class="transport_fee_line"><?php echo $month_value; ?></h4>
                                </div>
                                <div class="col-sm-10 col-lg-10 col-md-10">
                                    <input type="hidden" name="rows[]" value="<?php echo $count; ?>">
                    
                                    <input type="hidden" name="prev_id_<?php echo $count; ?>" value="<?php echo $inserted_array['id']; ?>">
                                    <input type="hidden" name="month_<?php echo $count;?>" value="<?php echo $month_key; ?>">
                                    <div class="form-group row">               

                                        <div class="col-sm-12 col-lg-2 col-md-2">
                                            <div class="form-group">
                                            <label for="inputFirstname"><?php echo $this->lang->line('due_date'); ?></label>
                                            <input type="text" name="due_date_<?php echo $count;?>" class="form-control date_to" id="due_date_<?php echo $count;?>" autocomplete="off" value="<?php echo set_value('due_date_'.$count,$this->customlib->dateformat($inserted_array['due_date'])) ?>">
                                            <span class="text text-danger"><?php echo form_error('due_date_'.$count); ?></span>
                                        </div>
                                        </div>
                   
 
                                        <div class="col-sm-12 col-lg-9 col-md-10 col-lg-offset-1">
                                            <div class="row">
                                                <div class="col-sm-12 col-lg-12 col-md-12">
                                                    <label for="input-type"><?php echo $this->lang->line('fine_type'); ?></label>
                                                </div>    
                                                <div id="input-type">
                                                    <div class="col-sm-2 col-lg-2 col-md-2">
                                                        <div class="form-group">
                                                        <label class="radio-inline">
                                                        <input name="fine_type_<?php echo $count;?>" class="finetype" id="input-type-tutor" value="" type="radio" <?php echo ($inserted_array['fine_type']== "") ? "checked": "" ?>><?php echo $this->lang->line('none'); ?> </label></div>
                                                    </div>
                                                    <div class="col-sm-12 col-lg-5 col-md-5">
                                                        <div class="row">
                                                            <div class="col-sm-4 col-lg-6 col-md-6 col-xs-5 text-end">
                                                                <div class="form-group">
                                                                <label class="radio-inline pt4">
                                                                <input name="fine_type_<?php echo $count;?>" class="finetype" id="input-type-student" value="percentage" type="radio" <?php echo ($inserted_array['fine_type']== "percentage") ? "checked": "" ?>><?php echo $this->lang->line('percentage'); ?> (%)</label>
                                                            </div>
                                                           </div>
                                                        <div class="col-sm-8 col-lg-6 col-md-6 col-xs-7"> 
                                                           
                                                         <input id="percentage" name="percentage_<?php echo $count;?>" name="percentage_<?php echo $count;?>" type="text" class="form-control percentage" value="<?php echo set_value("percentage_".$count,$inserted_array['fine_percentage']) ?>" <?php echo ($inserted_array['fine_type'] == "percentage") ? "" : "readonly" ?> autocomplete="off"> 
                                                         
                                                         <span class="text text-danger"><?php echo form_error('percentage_'.$count); ?></span>
                                                         
                                                        </div> 
                                                     </div>    
                                                    </div>
                                                    <div class="col-sm-12 col-lg-5 col-md-5">
                                                        <div class="row">
                                                            <div class="col-sm-4 col-lg-6 col-md-6 col-xs-5 text-end">
                                                                <div class="form-group">
                                                                    <label class="radio-inline pt4">
                                                                    <input name="fine_type_<?php echo $count;?>" class="finetype" id="input-type-tutor" value="fix" type="radio"  <?php echo ($inserted_array['fine_type']== "fix") ? "checked": "" ?>>
                                                                    <?php echo $this->lang->line('fix_amount'); ?> (<?php echo $currency_symbol; ?> )</label>
                                                                </div>    
                                                            </div>
                                                       <div class="col-sm-8 col-lg-6 col-md-6 col-xs-7">
                                                          <input type="text" class="form-control fine_amount" name="fine_amount_<?php echo $count;?>" id="inputLastname" autocomplete="off" <?php echo ($inserted_array['fine_type']== "fix") ? "" : "readonly" ?> value="<?php if($inserted_array['fine_amount']){ echo set_value("fine_amount_".$count, convertBaseAmountCurrencyFormat($inserted_array['fine_amount'])); } ?>">
                                                          
                                                          <span class="text text-danger"><?php echo form_error('fine_amount_'.$count); ?></span>
                                                          
                                                      </div>
                                                     </div> 
                                                    </div>
                                                </div>
                                    
                                        </div> 
                                        </div><!--  -->                 
                                    </div>
                                              
                                </div>         
                            </div>
                            <?php 
                            $count+=1;
                            }
                            }

                            ?>
                        </div>
                        <div class="box-footer">
                            <?php if ($this->rbac->hasPrivilege('transport_fees_master', 'can_edit')) {  ?>
                                <button type="submit" class="btn btn-info pull-right" autocomplete="off"><?php echo $this->lang->line('save'); ?></button>
                            <?php } ?>    
                        </div>
                    </form>
                </div>
            </div>
        </div>       
    </section>
</div>

<script type="text/javascript">
    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy','M'=>'MM']) ?>';

$(document).ready(function(){
	 $('.date_to').datepicker({
                todayHighlight: true,
                format: date_format,
                autoclose: true,
                weekStart : 1,
                language: 'en'
            });
});
    $(document).on('change','.finetype',function(){
    
        var radio_value=($(this).val());
         
        if (radio_value == "percentage") {
   
            $(this).closest("div.block_row").find('input.fine_amount').val("").prop('readonly', true);
            $(this).closest("div.block_row").find('input.percentage').prop('readonly', false);

        }else if (radio_value == "fix") {
            
            $(this).closest("div.block_row").find('input.percentage').val("").prop('readonly', true);
            $(this).closest("div.block_row").find('input.fine_amount').prop('readonly', false);
 
        }else if (radio_value == "") {
            
            $(this).closest("div.block_row").find('input.percentage').val("").prop('readonly', true);
            $(this).closest("div.block_row").find('input.fine_amount').val("").prop('readonly', true);
 
        }
    });

    $(document).on('change','.copy_other',function(){
        if(this.checked) {
            $('input:checkbox').not(this).prop('checked', this.checked);
            var first_due= $('form#fee_form').find('input.date').filter(':visible:first').val();
            var first_percentage=$('form#fee_form').find('input.percentage').filter(':visible:first').val();
            var first_fine_amount=$('form#fee_form').find('input.fine_amount').filter(':visible:first').val();
            var first_fine_type= $('form#fee_form').find('input.finetype:checked').val();
             
            if(first_due != ""){
            	// console.log($('form#fee_form').find('.date_to').data().datepicker.viewDate);

                var date= $('form#fee_form').find('.date_to').data().datepicker.viewDate;
                
                var date = new Date(date);  
                for (var i = 2; i <= 12; i++) {
                    var x=new Date(date.getFullYear(), date.getMonth()+(i-1), date.getDate());
                  
                    $("#due_date_"+i).datepicker({
                        format: date_format,
                        autoclose: true
                    }).datepicker("update", x); 
                }              
            }
            
            $('form#fee_form').find('.date').val(first_due);            
            $('form#fee_form').find('.percentage').val(first_percentage);
            $('form#fee_form').find('.fine_amount').val(first_fine_amount);
            
            $("input[class=finetype][value='"+first_fine_type+"']").prop("checked",true);
            
        }
    });
</script>