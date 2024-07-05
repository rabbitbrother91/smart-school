<style type="text/css">

    .table .pull-right {text-align: initial; width: auto; float: right !important;}
</style>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-language"></i> <?php echo $this->lang->line('currencies'); ?></h3>
                        <div class="box-tools pull-right">                           
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                       
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
                        <?php } ?>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped table-switch">                                
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo $this->lang->line('currency'); ?></th>
                                        <th><?php echo $this->lang->line('short_code'); ?></th>
                                        <th><?php echo $this->lang->line('currency_symbol'); ?></th>
                                        <th><?php echo $this->lang->line('conversion_rate'); ?></th>
                                        <th><?php echo $this->lang->line('base_currency'); ?></th>
                                        <th><?php echo $this->lang->line('active'); ?></th>       
                                        <th class="text-right"><?php echo $this->lang->line('enabled'); ?></th>
                                    </tr>
                                    <tbody id="result_data">
                                    
                                    <?php
$count = 1;
foreach ($languagelist as $language) {	

    ?>
    <tr>
        <td><?php echo $count . "."; ?></td>
        <td class="mailbox-name"> <?php echo $language->name ?></td>
        <td><?php echo $language->short_name; ?></td>
        <td width="12%">
			<input type="text" name="symbol" data-id="<?php echo $language->id; ?>" class="form-control currency_symbol" value="<?php echo $language->symbol; ?>">
		</td>
        <td width="10%">
          <?php
            $read_status=false;
            if ($language->id == $language->currency_id) {               
                 $read_status=true;                
            } 
            ?>
          <input type="text" <?php echo ($read_status) ? "disabled": ""; ?> name="currency" data-id="<?php echo $language->id; ?>" class="form-control currency_value" value="<?php echo $language->base_price; ?>">
        </td>
        <td>
     <?php            
            if ($language->id == $language->currency_id) {
                ?>
                <span class="label bg-green"><?php echo $this->lang->line('active'); ?></span>
                <?php
            } 
            ?>
        </td>
      <td>       	
        	 <?php 
                if ($language->is_active) {
                    ?>
                    <input type="radio" value="<?php echo $language->id ?>" class="change_active" data-settingid="<?php echo $setting->id ?>" name="is_active"  <?php  if ($language->id == $language->currency_id) {  echo "checked"; }
                           ?>>
        <?php
         }   

    ?> 
        </td>        
        <td class="relative text-right">
        	<?php  if ($language->id != $language->currency_id) {  
?>     <div class="material-switch pull-right">
                    <input type="checkbox" id="currency_<?php echo $language->id ?>" name="someSwitchOption001"                
                             <?php echo ($language->is_active) ? "checked" :""; ?>   class="change_status" data-rowid="<?php echo $language->id ?>" value="1"  />
                        <label for="currency_<?php echo $language->id ?>" class="label-success"></label>
                    </div>
<?php
        	 }
                           ?>
        </td>
    </tr>
    <?php
    $count++;
}
?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <div class="mailbox-controls">
                        </div>
                    </div>
                </div>
            </div>
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div>

<script type="text/javascript">
	$(document).on('keyup','.currency_value',function(){
        let currency_id = $(this).data('id');     
        let base_price=$(this).val();
            $.ajax({
                url:baseurl +'admin/currency/editprice',
                type:'POST',
                dataType:'JSON',
                data:{currency_id:currency_id,base_price:base_price},
                success:function(response){

                }
            });
	});
	
	$(document).on('keyup','.currency_symbol',function(){
        let currency_id = $(this).data('id');     
        let symbol=$(this).val();
		 
            $.ajax({
                url:baseurl +'admin/currency/editsymbol',
                type:'POST',
                dataType:'JSON',
                data:{currency_id:currency_id,symbol:symbol},
                success:function(response){

                }
            });
	});	 

$(document).on('change','.change_status',function(e){ 
 var checked = $(this).is(':checked');
 var rowid = $(this).data('rowid');
 let route_url=baseurl+'admin/currency/changestatus';
 let is_confirm=false;
 let status;
    if(checked) {
        if(!confirm('<?php echo $this->lang->line('are_you_sure_you_want_to_enable');?>')){
            $(this).prop("checked",false) ;
        }else{
          is_confirm=true;
          status=1;
        }
    } else{
    if(!confirm('<?php echo $this->lang->line('are_you_sure_you_want_to_disable');?>')){
             $(this).prop("checked",true) ;
    }else{
          is_confirm=true;
          status=0;
    }
    }
    if(is_confirm){
        $.ajax({
            type: 'POST',
            url: route_url,
            data: {'status':status,
                   'id':rowid                  
                  },
             dataType: 'JSON',
            beforeSend: function() {
             // setting a timeout
             
            },
            success: function(data) {
              successMsg(data.message);
                 window.location.reload('true');
            },
            error: function(xhr) { // if error occured
                alert("Error occured.please try again");
           
            },
            complete: function() {
              
            }           
        });
    }
});

$(document).on('change','.change_active',function(e){ 
 var checked = $(this).is(':checked');
 var rowid = $(this).data('settingid');
 let route_url=baseurl+'admin/currency/changeactive';
 let is_confirm=false;
 let status;
    if(checked) {
        if(!confirm('<?php echo $this->lang->line('are_you_sure_you_want_to_enable');?>')){
            $(this).prop("checked",false) ;
        }else{
          is_confirm=true;
          status=1;
        }
    } else{
    if(!confirm('<?php echo $this->lang->line('are_you_sure_you_want_to_disable');?>')){
             $(this).prop("checked",true) ;
    }else{
          is_confirm=true;
          status=0;
    }
    }
    if(is_confirm){
        $.ajax({
            type: 'POST',
            url: route_url,
            data: {'status':status,
                   'id':rowid,
                   'currency_id':$(this).val()
                  
                  },
             dataType: 'JSON',
            beforeSend: function() {
             // setting a timeout
             
            },
            success: function(data) {
              successMsg(data.message);
                 window.location.reload('true');
            },
            error: function(xhr) { // if error occured
                alert("Error occured.please try again");
           
            },
            complete: function() {
              
            }           
        });
    }
});
</script>