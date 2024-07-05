<div class="row">
<input type="hidden" name="id" value="<?php echo set_value('id', $editvehicle->id); ?>" >
        <div class="col-sm-3 col-md-2 col-lg-2">
            <div class="form-group">
                <label ><?php echo  $this->lang->line('vehicle_photo'); ?></label>
                <?php if(!empty($editvehicle->vehicle_photo)){ ?>
                    <img class="profile-user-img img-responsive img-rounded me-0" src="<?php echo $this->media_storage->getImageURL('/uploads/vehicle_photo/'.$editvehicle->vehicle_photo); ?>" alt="User profile picture">
                <?php }else{ ?>
                    <div class="route-bus-icon"><i class="fa fa-bus"></i></div>
                <?php } ?>
                 
            </div>
        </div>

        <div class="col-lg-10 col-md-10 col-sm-9">
            <div class="row">        
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="route-text"><b><?php echo $this->lang->line('vehicle_number'); ?>: </b><span><?php echo $editvehicle->vehicle_no; ?></span></div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="route-text"><b><?php echo $this->lang->line('vehicle_model'); ?>: </b><span><?php echo $editvehicle->vehicle_model; ?></span></div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="route-text"><b><?php echo $this->lang->line('year_made'); ?>: </b><span><?php echo $editvehicle->manufacture_year; ?></span></div>
                </div> 
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="route-text"><b><?php echo $this->lang->line('registration_number'); ?>: </b><span><?php echo $editvehicle->registration_number; ?></span></div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="route-text"><b><?php echo $this->lang->line('chasis_number'); ?>: </b><span><?php echo $editvehicle->chasis_number; ?></span></div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="route-text"><b><?php echo $this->lang->line('max_seating_capacity'); ?>: </b><span><?php echo $editvehicle->max_seating_capacity; ?></span></div>
                </div> 
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="route-text"><b><?php echo $this->lang->line('driver_name'); ?>: </b><span><?php echo $editvehicle->driver_name; ?></span></div>
                </div> 
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="route-text"><b><?php echo $this->lang->line('driver_license'); ?>: </b><span><?php echo $editvehicle->driver_licence; ?></span></div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="route-text"><b><?php echo $this->lang->line('driver_contact'); ?>: </b><span><?php echo $editvehicle->driver_contact; ?></span></div>
                </div>  
            </div>
            
         </div><!--./col-md-12-->    
    </div><!--./row-->     
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="route-text pb10"><b><?php echo $this->lang->line('note'); ?>: </b><span><?php echo $editvehicle->note; ?></span></div>
                </div>  
            </div>
           
