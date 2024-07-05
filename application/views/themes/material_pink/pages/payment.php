 <?php if($payment_type=='direct'){ ?>
 <div class="alert alert-success"><?php echo $this->lang->line('thanks_for_registration_please_note_your_reference_number'); ?>   <?php echo  $reference_no ;  ?>.  <?php echo $this->lang->line('for_further_communication') ?></div> 
<form action="<?php echo base_url().'welcome/saveadmissionpayment' ?>" method="post">
<div class="row"> 

        <div class="col-md-3">

            <div class="form-group">
              <button tye="submit" name="payment" id="payment" class="" ><?php echo $this->lang->line('pay') ?> <?php echo " ". $online_admission_amount ; ?></button>   
               <a name="payment" id="payment" class="btn btn-default btn-sm" href="<?php echo base_url()."welcome/skippayment" ?>" ><?php echo $this->lang->line('skip_payment') ?></a>
                
            </div>
        </div>
        </div>

    <?php } else{ ?>
    <div class="row"> 
            <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line("enter_reference_number_is"); ?></label>
                <input type="text" name="reference_no" id="reference_no" class="form-control">
            </div>
        </div>
        </div>
        <div class="row"> 
          <div class="col-md-3">

              <div class="form-group">
                <button tye="submit" name="payment" id="payment" class="" ><?php echo $this->lang->line("pay"); ?><?php echo " ". $online_admission_amount ; ?></button> 
                  <a name="payment" id="payment" class="" href="<?php echo base_url()."welcome/skippayment" ?>" ><?php echo $this->lang->line("skip_payment"); ?></a>
             
              </div>
          </div>

        </div>
       <?php }  ?>      
       <input type="hidden" name="payment_type" value="<?php echo $payment_type ; ?>">
       
       <input type="hidden" name="payment_type" value="<?php echo $payment_type ; ?>">
       </form>
    </div><!--./row--> 