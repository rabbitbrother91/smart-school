<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">   
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('financereports/_finance'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form role="form" action="<?php echo site_url('financereports/onlineadmission') ?>" method="post" class="">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-6 col-md-3" >
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search_type'); ?><small class="req"> *</small></label>
                                    <select class="form-control" name="search_type" id="search_type" onchange="showdate(this.value)">

                                        <?php foreach ($searchlist as $key => $search) {
                                            ?>
                                            <option value="<?php echo $key ?>" <?php
                                            if ((isset($search_type)) && ($search_type == $key)) {
                                                echo "selected";
                                            }
                                            ?>><?php echo $search ?></option>
                                                <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                </div>
                            </div>
                            <div id='date_result'>

                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>

					<?php
                        if (empty($collectlist)) { ?>
						<br/>
									<div class="box-header ptbnull">
										<div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                    </div>
                    <?php    } else { ?>
                    <div class="">
                        <div class="box-header ptbnull"></div>
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo $this->lang->line('online_admission_fees_collection_report'); ?></h3> 
                        </div>
                        <div class="box-body table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('online_admission_fees_collection_report');
                                                $this->customlib->get_postmessage();
                                                ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead class="header">
                                    <tr>
										<th><?php echo $this->lang->line('reference_no'); ?></th>
										<th><?php echo $this->lang->line('name'); ?></th>
										<th><?php echo $this->lang->line('admission_no'); ?></th>
										<th><?php echo $this->lang->line('email'); ?></th>
										<th><?php echo $this->lang->line('mobile_number'); ?></th>
										<th><?php echo $this->lang->line('class'); ?></th>                                   
										<th><?php echo $this->lang->line('payment_methods'); ?></th>
										<th><?php echo $this->lang->line('transaction_id'); ?></th>					
                                        <th><?php echo $this->lang->line('date'); ?></th>                               
                                        <th class="text text-right"><?php echo $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $total = 0; 
                                        $count = 1;
                                        foreach ($collectlist as $key => $collect) {                               

                                    ?>
                                    <tr>
										<td><?php echo $collect->reference_no ; ?></td>
										
										<td><?php echo $this->customlib->getFullName($collect->firstname,$collect->middlename,$collect->lastname,$sch_setting->middlename,$sch_setting->lastname); ?>
                                        </td>
										<td><?php echo $collect->admission_no ; ?></td>
										<td><?php echo $collect->email ; ?></td>
										<td><?php echo $collect->mobileno ; ?></td>
										<td><?php echo $collect->class . " (" . $collect->section . ")"; ?></td>        
										<td><?php echo $this->lang->line(strtolower($collect->payment_mode)); ?></td>
										<td><?php echo $collect->transaction_id ; ?></td>
                                        <td><?php $data	= date('Y-m-d', strtotime($collect->date)); 
										echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($data)); ?></td>		
                                        <td class="text text-right"><?php echo $amount = amountFormat($collect->paid_amount);  ?></td>                                                
                                    </tr>
                                    <?php
                                            $count++;
                                    ?>

                                    <?php
                                      $total = $total+$collect->paid_amount;  }
                                    ?>                        

                                </tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>                                
                                    <td></td>                                
									<td></td>
									<td></td>
									<td></td>
									<td></td>
                                    <td style="font-weight:bold"><?php echo $this->lang->line('total'); ?></td>                                            
                                    <td class="text text-right" style="font-weight:bold"><?php echo amountFormat($total); ?></td>                                                
                                </tr>
                                      
                            </table>
                        </div>
                    </div>
                </div>
                 <?php
                                    }
                                    ?>
            </div>
        </div>   
</div>  
</section>
</div>
<script type="text/javascript">
    $(document).ready(function(){
    var search_type=$('select[name=search_type] option').filter(':selected').val();
    showdate(search_type);
    });
</script>