<div class="content-wrapper">
    <section class="content pb0">
    	<div class="row">
    		<div class="col-lg-6 col-md-6 col-sm-12">
	    		<div class="box box-primary borderwhite">
	                <div class="box-body direct-top-equal-scroll-22">
	                	<div class="row">
	                		<div class="col-lg-3 col-md-3 col-sm-3">
								<?php 
									if (!empty($student_data["image"])) {
                                        $file = base_url() . $student_data["image"].img_time();
                                    } else {                            
                                        if ($student_data['gender'] == 'Female') {
                                            $file = base_url() . "uploads/student_images/default_female.jpg".img_time();
                                        } else {
                                            $file = base_url() . "uploads/student_images/default_male.jpg".img_time();
                                        }
                                    }
								?>
							
	                			<img src="<?php echo $file.''.img_time(); ?>" class="img-rounded img-responsive img-h-150 mb-xs-1">						
								
	                		</div><!--./col-lg-3-->
	                		<div class="col-lg-9 col-md-9 col-sm-9">
								
								<?php 								
								if($attendence_percentage == '-1' ){ ?>
								
									<h4 class="mt0"><?php echo $this->lang->line('welcome'); ?>, <?php echo $studentsession_username; ?></h4>
									
								<?php } elseif($attendence_percentage > 0 && $attendence_percentage < $low_attendance_limit && $low_attendance_limit != '0.00' ){ ?>
								
									<h4 class="mt0"><?php echo $this->lang->line('welcome'); ?>, <?php echo $studentsession_username; ?>! <?php echo $this->lang->line('need_improvement'); ?>.</h4>
									<p class="text-danger"><?php echo $this->lang->line('your_current_attendance_is'); ?> <?php echo $attendence_percentage ; ?>% <?php echo $this->lang->line('which_is_lower_than');?> <?php echo $low_attendance_limit; ?>% <?php echo $this->lang->line('of_minimum_attendance_mark'); ?>. </p>				
								
								<?php } elseif($attendence_percentage > 0 && $attendence_percentage >= $low_attendance_limit && $low_attendance_limit != '0.00'){ ?>
								
									<h4 class="mt0"><?php echo $this->lang->line('welcome'); ?>, <?php echo $studentsession_username; ?>! <?php echo $this->lang->line('keep_going'); ?>.</h4>
									<p class="text-success  "><?php echo $this->lang->line('your_current_attendance_is'); ?> <?php echo $attendence_percentage ; ?>% <?php echo $this->lang->line('which_is_above'); ?> <?php echo $low_attendance_limit; ?>% <?php echo $this->lang->line('of_minimum_attendance_mark'); ?>.</p>	
									
								<?php }else{ ?>	
									
									<h4 class="mt0"><?php echo $this->lang->line('welcome'); ?>, <?php echo $studentsession_username; ?></h4>
								<?php } ?>
	                		 
	                		</div><!--./col-lg-10-->
	                	</div><!--./row-->
	                </div>
	            </div>   
	        </div><!--./col-lg-6-->
			<div class="col-lg-6 col-md-6 col-sm-12">	    		 
				<div class="box box-primary borderwhite">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('notice_board'); ?></h3>      
                    </div>
                    <div class="box-body pb0">
						<?php if(!empty($notificationlist)){ ?>
                    	<ul class="user-progress ps mb0">
                    		<?php for($i=0;$i<4;$i++){
                    			$notification = array();
                    			if(!empty($notificationlist[$i])){
	                    			$notification=$notificationlist[$i];
                                }
	                    	?>
	                    <?php if(!empty($notification)){ ?>
			                <li class="doc-file-type">			                   
				                <div class="set-flex">
					                <div class="media-title"><?php if(!empty($notification)){ ?>
									<a href="<?php echo base_url(); ?>user/notification" class="displayinline text-muted" target="_blank">
									
					                	<?php if ($notification['notification_id'] == "read") { ?>
                                            <img src="<?php echo base_url() ?>/backend/images/read_one.png">
                                        <?php } else { ?>
                                            <img src="<?php echo base_url() ?>backend/images/unread_two.png">
                                        <?php }?>
										
										&nbsp;<?php  echo $notification['title']; ?> (<?php if(!empty($notification)){ echo "<i class='fa fa-clock-o text-aqua'></i>". ' '. date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($notification['date']));} ?>)
					                </a><?php } ?>
									</div>                

			            		</div>   
				               
			                </li><!-- /.item -->
			            <?php } } ?>
			                
			            </ul>  
						<?php }else{ ?>
							<img src="https://smart-school.in/ssappresource/images/addnewitem.svg"  width="150" class="center-block mt20">
						<?php } ?>
                    </div>                   
                </div>	 
	        </div><!--./col-lg-6-->  
    	</div><!--./row-->	
		 
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-12">
				<div class="box box-primary borderwhite">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('subject_progress'); ?></h3>      
                    </div>
                    <div class="box-body direct-chat-messages">
                    	<div class="table-responsive">
							<?php   if (!empty($subjects_data)) {  ?>
                    		<table class="table table-striped table-hover">
                    			<tr class="active">
                    				<th><?php echo $this->lang->line('subject'); ?></th>
                    				<th><?php echo $this->lang->line('progress'); ?></th>
                    				<!-- <th>Duration</th> -->
                    			</tr>
                    		<?php 
                                    foreach ($subjects_data as $key => $value) {
                            ?>
                    			<tr>
                    				<td><?php echo $value['lebel']; ?></td>
                    				<td><?php echo $value['complete']; ?>%
                    					<div class="progress progress-minibar">
										  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow=""
										  aria-valuemin="0" aria-valuemax="100" style="width:<?php if($value['complete'] !=0){ echo $value['complete'];} ?>%">
										  </div>
										</div>
                    				</td>
                    				<!-- <td>2 Months</td> -->
                    			</tr>
                    		<?php }  ?>
                    			
                    		</table>
							<?php }else{  ?>
								<img src="https://smart-school.in/ssappresource/images/addnewitem.svg"  width="150" class="center-block mt20">
							<?php } ?>
                    	</div>
                    </div>
                </div>
			</div><!--./col-lg-4-->

			<div class="col-lg-4 col-md-4 col-sm-12">
				<div class="box box-primary borderwhite">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('upcomming_class'); ?></h3>      
                    </div>
                    <div class="box-body direct-chat-messages">					 
					
						<?php if (!empty($timetable)) { ?>
                    	<ul class="user-progress">

                    	<?php 
                    		foreach ($timetable as $tm_key => $tm_value) {

                    			if (!$timetable[$tm_key]) {
                    	 ?>
			            <?php }else{ 
                                for($i=0;$i<5;$i++){

	                                $timetablelist = array();
	                    			if(!empty($timetable[$tm_key][$i])){
	                    				
		                    			$timetablelist=$timetable[$tm_key][$i];
		                 
	                                }
 
			             if(!empty($timetablelist)){ ?>
			            	<li class="lecture-list">

			            		<?php $profile_pic = '';
			            		if($timetablelist->image !=''){
				            	    $profile_pic = 'uploads/staff_images/'.$timetablelist->image;
				            	}else{
				            	    if($timetablelist->gender == 'Male'){
	                                    $profile_pic = 'uploads/staff_images/default_male.jpg';
				            		 }else{
	                                    $profile_pic = 'uploads/staff_images/default_female.jpg';
				            		} 
			            		}?>
			                    <img src="<?php echo base_url(); ?><?php echo $profile_pic.img_time(); ?>" alt="" class="img-circle msr-3 object-fit-cover fit-image-40" width="40" height="40">

				                <div class="set-flex">
					                <div class="media-title bmedium"><?php echo $timetablelist->name.' '.$timetablelist->surname.' (' . $timetablelist->employee_id .')'; ?> 
					                </div>
					                <div class="text-muted mb0">
					                	<?php
					                	if(!empty($timetablelist)){
                                            echo $timetablelist->subject_name;
                                            if ($timetablelist->code != '') {
                                                echo " (" . $timetablelist->code . ")";
                                            }
                                        }
                                        ?>                          	
                                    </div>
			            		</div>    
				                 <div class="ms-auto">
					                <div class="bmedium"><?php echo $this->lang->line('room_no'); ?>:<?php echo $timetablelist->room_no; ?></div>
					                <div class="text-muted mb0"><?php echo $timetablelist->time_from ?>-<?php echo $timetablelist->time_to; ?></div>
				                 </div>
			                </li>
			           <?php } } } }  ?>
			               
			            </ul>
						<?php }else{  ?>
							<img src="https://smart-school.in/ssappresource/images/addnewitem.svg"  width="150" class="center-block mt20">
						<?php } ?>
                    </div>
                </div>
			</div><!--./col-lg-4-->

			<div class="col-lg-4 col-md-4 col-sm-12">
				<div class="box box-primary borderwhite">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('homework'); ?></h3>      
                    </div>

                    <div class="box-body direct-chat-messages">
                    	
						<?php if(!empty($homeworklist)){ ?>
                    	<ul class="user-progress ps">
                    		<?php for($i=0;$i<5;$i++){
                    			$homework = array();
                    			if(!empty($homeworklist[$i])){
	                    			$homework=$homeworklist[$i];
                                }
	                    	?>
	                    <?php if(!empty($homework)){ ?>
			                <li class="doc-file-type">
				                <div class="set-flex">
					                <div class="media-title font-16"><?php if(!empty($homework)){ ?><a href="<?php echo base_url(); ?>user/homework" class="displayinline text-muted" target="_blank"><?php  echo $homework['subject_name']; ?> (<?php  echo $homework['subject_code']; ?>)									
									</a><?php } ?></div>
					                <div class="text-muted mb0"><?php if(!empty($homework)){ echo $this->lang->line('homework_date').': '. date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($homework['homework_date'])) .',';} ?> <?php if(!empty($homework)){ echo $this->lang->line('submission_date'). ': '. date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($homework['submit_date'])) .',';} ?> <?php if(!empty($homework)){

                                        if ($homework["status"] == 'submitted') {
                                            $status_class = "class= 'label label-warning'";
                                            $status_homework = $this->lang->line('submitted');
                                        }else{
                                            $status_class = "class= 'label label-danger'";
                                            $status_homework = $this->lang->line("pending");
                                        }
                      
                                        if ($homework["homework_evaluation_id"] != 0) {
                                           
                                            $status_class = "class= 'label label-success'";
                                            $status_homework = $this->lang->line("evaluated");
                                        }

                                        echo $this->lang->line('status').': ';
                                        ?>
                                        <label <?php echo $status_class; ?>><?php echo $status_homework; ?></label>
								    <?php	
								    }
									?>

					            </div>
			            		</div> 
			                </li><!-- /.item -->
			            <?php } } ?>			                
			            </ul> 
						<?php }else{ ?>
							<img src="https://smart-school.in/ssappresource/images/addnewitem.svg"  width="150" class="center-block mt20">
						<?php } ?>
                    </div>
                </div>
			</div><!--./col-lg-4-->			
		</div><!--./row-->	

		<div class="row">
		
			<div class="col-lg-3 col-md-3 col-sm-12">
				<div class="box box-primary borderwhite">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('teacher_list'); ?></h3>      
                    </div>

                    <div class="box-body direct-chat-messages">                    	
						<?php  					 
						 
							if(!empty($teacherlist)){   
								
						?>
                    	<ul class="user-progress ps">
                    		<?php foreach ($teacherlist as $teacher) {							 
								
								$class_teacher = '';
							
								if ($teacher[0]->class_teacher == $teacher[0]->staff_id) {
									$class_teacher = '<span class="label label-success bolds">' . $this->lang->line('class_teacher') . '</span>' ;
								}
							?>
							<li class="lecture-list">

			            		<?php 
										$profile_pic = '';
										
										if($teacher[0]->image !=''){
											$profile_pic = 'uploads/staff_images/'.$teacher[0]->image;
										}else{
											if($teacher[0]->gender == 'Male'){
												$profile_pic = 'uploads/staff_images/default_male.jpg';
											}else{
												$profile_pic = 'uploads/staff_images/default_female.jpg';
											} 
										}
								?>
			                    <img src="<?php echo base_url(); ?><?php echo $profile_pic.img_time(); ?>" alt="" class="img-circle msr-3 object-fit-cover fit-image-40" width="40" height="40">

				                <div class="set-flex">
					                <div class="media-title bmedium"><?php echo $teacher[0]->name . " " . $teacher[0]->surname . "<br> (" . $teacher[0]->employee_id . ") " . $class_teacher ?>	 
					                </div>
					                 
			            		</div>  
			                </li>
							
			                <?php } ?>		            		                
			            </ul> 
						<?php  }else{ ?>
							<img src="https://smart-school.in/ssappresource/images/addnewitem.svg"  width="150" class="center-block mt20">
						<?php } ?>
                    </div>
                </div>
			</div><!--./col-lg-4-->	
		
			<div class="col-lg-4 col-md-4 col-sm-12">
				<div class="box box-primary borderwhite">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('visitor_list'); ?></h3>      
                    </div>
                    <div class="box-body direct-chat-messages">
						<?php if(!empty($visitor_list)){ ?>
                    	<ul class="user-progress ps">                    		 
	                    <?php 
							foreach($visitor_list as $visitor_value){
							?>
			                <li class="doc-file-type">			                   
				                <div class="set-flex">
					                <div class="text-muted mb0">
										<b><?php echo $visitor_value['name']; ?></b><br> (<?php echo $this->lang->line('purpose'); ?>: <?php echo $visitor_value['purpose']; ?>)				
									</div>									
					                <div class="text-muted mb0"><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($visitor_value['date'])); ?>
					               </div>
			            		</div> 			               
			                </li><!-- /.item -->
			            <?php } ?>
			                
			            </ul>
						<?php }else{ ?>
						
						<img src="https://smart-school.in/ssappresource/images/addnewitem.svg"  width="150" class="center-block mt20">
						
						<?php } ?>					
                    </div>                   
                </div>    
			</div><!--./col-lg-4-->

			<div class="col-lg-5 col-md-5 col-sm-12">
				<div class="box box-primary borderwhite">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('library_book_issue_list');?></h3>      
                    </div>

                    <div class="box-body direct-chat-messages">
                    	<div class="table-responsive">
                    		<table class="table table-striped table-hover">
                    			<tr class="active">
                    				<th><?php echo $this->lang->line('book_no')?></th>
                    				<th><?php echo $this->lang->line('book_title')?></th>
                    				<th><?php echo $this->lang->line('issue_date')?></th>                    				 
                    				<th><?php echo $this->lang->line('due_return')?></th>
                    			</tr>                    			
                                <tbody>
                                <?php if (empty($bookList)) { ?>
									<tr>
										<td colspan="6">
											<img src="https://smart-school.in/ssappresource/images/addnewitem.svg"  width="150" class="center-block mt20">
										</td>
									</tr>
                                <?php
                                    } else {									 
									foreach($bookList as $key => $value){ 
									if($value['is_returned'] == 0){
                                ?>
                                    <tr>	
										<td class="mailbox-name"> <?php echo $value['book_no'] ?></td>
                                        <td class="mailbox-name"> <?php echo $value['book_title'] ?> <br> (<?php echo $value['author'] ?>)</td>                                                         
                                        <td class="mailbox-name"> 
                                            <?php
                                            if ($value['issue_date'] != '') {
                                                echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['issue_date']));
                                            }
                                            ?>
                                        </td> 
                                        <td >

                                            <?php
                                            if ($value['duereturn_date'] != '') {
                                                echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['duereturn_date']));
                                            }
                                            ?> 
											
                                        </td>                                   
                                    </tr>
									<?php } } } ?>      
                                            
                                
                                </tbody>
                            </table>                    			
                    	</div>
                    </div>
                </div>    
			</div><!--./col-lg-4-->
		</div><!--./row--> 
	</section>
</div>		