
                
<?php 
if (empty($marksheet)) {
    ?>
    <div class="alert alter-info">
        <?php echo $this->lang->line('no_record_found'); ?>
    </div>
    <?php
} else {

    if ($marksheet['exam_connection'] == 0) {
        if (!empty($marksheet['students'])) {
                $total_students= count($marksheet['students']);
                $to_be_print=0;
            foreach ($marksheet['students'] as $student_key => $student_value) {
                $to_be_print+=1;
                $result_status = 1;
                $absent_status = false;
                $percentage_total = 0;
                ?>

    <div style="width: 100%; margin: 0 auto; border:1px solid #000; padding: 0px 5px 5px">
       
        <?php if($template->header_image){ ?>
        <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $template->header_image); ?>" width="100%" height="300px;">
        <?php } ?>
        <table cellpadding="0" cellspacing="0" width="100%">       
          <tr>
            <td valign="top">
             <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td valign="top" align="center">
                             <?php
                                            if ($template->left_logo) {
                                                ?>
                            <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $template->left_logo); ?>" width="70" height="70"> 
                                                <?php
                                            }
                                                ?>
                        </td>
                        <td valign="top" align="center">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td valign="top" style="font-size: 20px; font-weight: bold; text-align: center;">
                                    <?php echo $template->exam_name; ?></td>
                                </tr>
                                 <?php
                                                if ($template->exam_session) {
                                                    ?>
                                <tr>
                                    <td valign="top" style="font-weight: bold; text-align: center; text-transform: uppercase; display: inline-block; margin-top: -10px; padding-bottom: 5px;">
                                    <?php echo $exam->session; ?></td>
                                </tr>
                                                    <?php
                                                }
                                                    ?>
                            </table>
                        </td>
                        <td valign="top" align="center">
                             <?php
                                            if ($template->right_logo) {
                                                ?>
                            <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $template->right_logo); ?>" width="70" height="70">
                                                <?php
}
?>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>
            <?php
                        if ($template->is_admission_no || $template->is_roll_no || $template->is_photo ) {
                            ?>
                          <tr>
            <td valign="top">
                <table cellpadding="0" cellspacing="0" width="100%" class="">
                    <tr>
                        <td valign="top">
                                <table cellpadding="0" cellspacing="0" width="98%" class="denifittable marks">
                 <tr>
                       <?php 
                                                        if ($template->is_admission_no) { 
                                                            ?>
                        <th valign="top" style="text-align: center; text-transform: uppercase;"  width="50%"><?php echo $this->lang->line('admission_no') ?></th>
                                                            <?php
                                                        }
                                                        ?>                    
                      <?php 

                           if ($template->is_roll_no) {
                                                            ?>
                       <th valign="top" style="text-align: center; text-transform: uppercase;"  width="50%"><?php echo $this->lang->line('roll_number') ?></th>
                                                            <?php
                                                            }  ?>
                    </tr>
                    <tr>
                    <?php
                                                        if ($template->is_admission_no) { 
                                                            ?>
                        <td style="text-transform: uppercase;text-align: center;"  width="50%"><?php echo $student_value['admission_no']; ?></td>
                                                            <?php
                                                        }
                                                        ?>
                     <?php
                                                          if ($template->is_roll_no) {                                                            
                                                            ?>
                                 <td style="text-transform: uppercase;text-align: center;border-right:1px solid #999"  width="50%"><?php echo   $roll_no=($exam->use_exam_roll_no) ? $student_value['exam_roll_no']:$student_value['student_roll_no']; ?></td>
              <?php
                                                          }
                                                        ?>                        
                    </tr>
                    <tr>
                        <td valign="top" colspan="5" style="text-align: center; text-transform: uppercase; border:0">                           
                        Certificated That</td>
                    </tr>
                </table>
                        </td>
                        <td valign="top" align="right" style="border: 1px solid #000000; padding:2px; width:120px">
     <?php
                                            if ($template->is_photo) {
if ($student_value['image'] != '') { 
    ?>
    <img src="<?php echo $this->media_storage->getImageURL($student_value['image']); ?>"  width="120" height="150" >
                                                    <?php 
                                                }                                               
                                            }
                                            ?>
</td>
                    </tr>                
                </table>
            </td>
          </tr>
                            <?php
                        }
                        ?>       
                        <tr>
                            <td valign="top">
                                <table cellpadding="0" cellspacing="0" width="100%">
                                    <?php
                                    if ($template->is_name) {
                                        ?>
                                        <tr>
                                        <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('name_prefix'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $this->customlib->getFullName($student_value['firstname'],$student_value['middlename'],$student_value['lastname'],$sch_setting->middlename,$sch_setting->lastname);  ?></span></td>
                                        </tr>
                                        <?php
                                    }

                                    if ($template->is_father_name) {
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('marksheet_father_name') ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $student_value['father_name']; ?></span></td>
                                        </tr>
                                        <?php
                                    }
                                    if ($template->is_mother_name) {
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('exam_mother_name'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $student_value['mother_name']; ?></span></td>
                                        </tr>
                                        <?php
                                    }
                                    if ($template->is_dob) {
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('date_of_birth'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $this->customlib->dateformat($student_value['dob']); ?></span></td>
                                        </tr>
                                        <?php
                                    }
                                    if ($template->is_class && $template->is_section) {
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('class'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $student_value['class'] . " (" . $student_value['section'] . ")"; ?> </span></td>
                                        </tr>
                                        <?php
                                    } elseif ($template->is_class) {
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('class'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $student_value['class']; ?> </span></td>
                                        </tr>
                                        <?php
                                    } elseif ($template->is_section) {
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('class'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $student_value['section']; ?> </span></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($template->school_name != "") {
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"> <?php echo $this->lang->line('school_name'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $template->school_name; ?></span></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($template->exam_center != "") {
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-transform: uppercase; padding-top: 15px; font-weight: bold; padding-bottom: 20px; padding-left: 30px;"><?php echo $this->lang->line('exam') . " " . $this->lang->line('center') ?><span style="text-transform: uppercase; padding-top: 15px; font-weight: bold; padding-bottom: 20px; padding-left: 30px;"><?php echo $template->exam_center; ?></span></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($template->content != "") {
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-transform: uppercase; padding-bottom: 15px; line-height: normal;"><?php echo $template->content ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table>
                            </td>
                        </tr>
            <tr>
            <td valign="top">
         <table cellpadding="0" cellspacing="0" width="100%" class="denifittable marks" style="text-align: center; text-transform: uppercase;">
        
    <col width=100><col width=100><col width=100>
    <tbody>
       <tr>
                                                <th><?php echo $this->lang->line('subjects') ?></th>
                                                <?php
                                                if ($exam->exam_group_type != "gpa") {
                                                    ?>
                                                    <th valign="middle"><?php echo $this->lang->line('max') . " " . $this->lang->line('marks') ?></th>
                                                    <th valign="middle"><?php echo $this->lang->line('min') . " " . $this->lang->line('marks') ?></th>
                                                    <th valign="middle"><?php echo $this->lang->line('marks') . " " . $this->lang->line('obtained') ?></th>
                                                    <?php
                                                } else if ($exam->exam_group_type == "gpa") {
                                                    ?>
                                                    <th valign="middle"><?php echo $this->lang->line('grade') . " " . $this->lang->line('point'); ?></th>
                                                    <th valign="middle"><?php echo $this->lang->line('credit_hours')   ?></th>
                                                    <th valign="middle"><?php echo $this->lang->line('quality_points') ?></th>
                                                    <?php
                                                }

                                                if ($exam->exam_group_type == "school_grade_system" || $exam->exam_group_type == "coll_grade_system") {
                                                    ?>
                                                    <th><?php echo $this->lang->line('grade'); ?></th>
                                                    <?php
                                                }
                                                ?>
                                                <th valign="top" style="text-align: left;border-right:1px solid #999"><?php echo $this->lang->line('note') ?></th>
                                            </tr>
                                               <?php
                                            $total_max_marks = 0;
                                            $total_obtain_marks = 0;
                                            $total_points = 0;
                                            $total_hours = 0;
                                            $total_quality_point = 0;
                                               foreach ($student_value['exam_result'] as $exam_result_key => $exam_result_value) {
                                          
                                                $total_max_marks = $total_max_marks + $exam_result_value->max_marks;
                                                $total_obtain_marks = $total_obtain_marks + $exam_result_value->get_marks;
                                                ?>
                                                 <tr>
                                                    <td>
                                                        <?php echo $exam_result_value->name ; ?> <?php if($exam_result_value->code){ echo ' ('.$exam_result_value->code.')'; } ?>
                                                    </td>
                                                    <?php
                                                    if ($exam->exam_group_type != "gpa") {
                                                        ?>
                                                        <td>
                                                            <?php echo $exam_result_value->max_marks; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $exam_result_value->min_marks; ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo $exam_result_value->get_marks;
                                                            if ($exam_result_value->attendence == "absent") {
                                                                echo "&nbsp;" . $this->lang->line('exam_absent');
                                                                $absent_status = true;
                                                            }
                                                            if ($exam_result_value->get_marks < $exam_result_value->min_marks) {
                                                                $result_status = 0;
                                                            }
                                                            ?>
                                                        </td>
                                                        <?php
                                                    } else if ($exam->exam_group_type == "gpa") {
                                                        ?>
                                                        <td class="text-center">
                                                            <?php
                                                            $percentage_grade = ($exam_result_value->get_marks * 100) / $exam_result_value->max_marks;
                                                            $point = findGradePoints($exam_grades, $percentage_grade);

                                                            $total_points = $total_points + $point;
                                                            echo $point;
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            $total_hours = $total_hours + $exam_result_value->credit_hours;
                                                            echo ($exam_result_value->credit_hours);
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            echo ($exam_result_value->credit_hours * $point);
                                                            $total_quality_point = $total_quality_point + ($exam_result_value->credit_hours * $point);
                                                            ?>
                                                        </td>
                                                        <?php
                                                    }

                                                    if ($exam->exam_group_type == "school_grade_system" || $exam->exam_group_type == "coll_grade_system") {
                                                        ?>
                                                        <td style="border-right:0">
                                                            <?php
                                                            $percentage_grade = ($exam_result_value->get_marks * 100) / $exam_result_value->max_marks;
                                                            echo findGrade($exam_grades, $percentage_grade);
                                                            ?>
                                                        </td>
                                                        <?php
                                                    }
                                                    ?>
                                                    <td valign="top" style="text-align: left;border-right:1px solid #999">
                                                        <?php
                                                        if ($exam->exam_group_type == "basic_system") {
                                                            if ($exam_result_value->get_marks < $exam_result_value->min_marks) {
                                                                $result_status = 0;
                                                                echo "(F) ";
                                                            }
                                                        }
                                                        echo $exam_result_value->note;
                                                        ?>
                                                    </td>
                                                </tr>
                                                    <?php
                                                }
                                                    ?>
                                                         <?php
                                            if ($exam->exam_group_type != "gpa") {
                                                ?>
                                                <tr>
                                                    <td></td>
                                                    <td><?php echo number_format($total_max_marks, 2, '.', ''); ?></td>
                                                    <td><?php echo $this->lang->line('grand_total') ?></td>
                                                    <td><?php echo number_format($total_obtain_marks, 2, '.', ''); ?></td>
                                                    <td valign="top" style="text-align: left;">
                                                    </td>
                                                    <?php
                                                    if ($exam->exam_group_type == "school_grade_system" || $exam->exam_group_type == "coll_grade_system") {
                                                        ?>
                                                        <td valign="top" style="text-align: left;border-right:1px solid #999;"></td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $this->lang->line('percentage') ?></td>
                                                    <td>
                                                        <?php
                                                        echo number_format((($total_obtain_marks * 100) / $total_max_marks), 2, '.', '');

                                                        $percentage_total = (($total_obtain_marks * 100) / $total_max_marks);
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($exam->exam_group_type == "basic_system") {
                                                            ?>
                                                            <?php echo $this->lang->line('result') ?>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($exam->exam_group_type == "basic_system") {
                                                            if ($result_status == 0) {
                                                                echo $this->lang->line('fail');
                                                            }
                                                            if ($result_status == 1) {
                                                                echo $this->lang->line('pass');
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td valign="top" style="text-align: left;"></td>
                                                    <?php
                                                    if ($exam->exam_group_type == "school_grade_system" || $exam->exam_group_type == "coll_grade_system") {
                                                        ?>
                                                        <td valign="top" style="text-align: left;border-right:1px solid #999"></td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <?php
                                            } else if ($exam->exam_group_type == "gpa") {
                                                ?>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-center">
                                                        <?php
                                                        echo number_format($total_hours, 2, '.', '');
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        if ($total_hours <= 0) {
                                                            echo "--";
                                                        } else {
                                                            $total_grade_percentage = ($total_obtain_marks * 100) / $total_max_marks;
                                                            $exam_qulity_point = number_format($total_quality_point / $total_hours, 2, '.', '');
                                                           
                                                            $percentage_total=($exam_qulity_point*100)/10;

                                                            echo $total_quality_point . "/" . $total_hours . "=" . $exam_qulity_point . " [" . findGrade($exam_grades, $total_grade_percentage) . "]";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td valign="top" style="text-align: left;border-right:1px solid #999"></td>
                                                    <?php
                                                    if ($exam->exam_group_type == "school_grade_system" || $exam->exam_group_type == "coll_grade_system") {
                                                        ?>
                                                        <td valign="top" style="text-align: left;border-right:1px solid #999"></td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <?php
                                            }
                                            ?>
    </tbody>
</table>
            </td>
          </tr>
  <?php
                        if ($exam->exam_group_type != "gpa") {
                            ?>
               <tr>
                <td valign="top" style="padding-top: 10px;">
                 <table cellpadding="0" cellspacing="0" width="100%" class="">                    
                    <tr>                        
                        <td valign="top" width="30%"> <?php echo $this->lang->line('result') ?></td>
                        <td valign="top" style="font-weight: bold;">
                              <?php

                                 if($exam->exam_group_type == "average_passing"){
                                    if($exam->passing_percentage <= $percentage_total){
                                     echo $this->lang->line('pass');
                                    }else{
                                     echo $this->lang->line('fail');
                                    }
                              }else{

                                 if ($result_status == 0 || $absent_status) {
                                     echo $this->lang->line('fail');
                                 } else {
                                     if ($percentage_total > 40) {
                                         echo $this->lang->line('pass');
                                     } else {
                                         echo $this->lang->line('fail');
                                     }
                                 }
                              }
                                        ?>
                        </td>
                    </tr>
                </table>
                </td>
               </tr>
                            <?php
                        }
                        ?>


           <?php
                            if ($template->is_division) {
                                ?>
   <tr>
            <td valign="top" style="padding-top: 10px;">
                <table cellpadding="0" cellspacing="0" width="100%%" class="">
                    <tr>
                      <td valign="top" width="30%"> <?php echo $this->lang->line('division') ?></td>
                        <td valign="top" style="font-weight: bold;">
                               <?php                            
                                    echo findExamDivision( $marks_division, $percentage_total);                                
                                 ?>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>
                                <?php
                            }
                            if ($template->is_rank) {
                                ?>
   <tr>
            <td valign="top" style="padding-top: 10px;">
                <table cellpadding="0" cellspacing="0" width="100%%" class="">
                    <tr>
                      <td valign="top" width="30%"> <?php echo $this->lang->line('rank') ?></td>
                        <td valign="top" style="font-weight: bold;">
                              <?php echo ($student_value['rank']);   ?>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>
                                <?php
                            }
                            ?>
        <?php
        if ($template->is_teacher_remark) {
                                ?>
                                 <tr>
            <td valign="top" style="padding-top: 10px;">
                <table cellpadding="0" cellspacing="0" width="100%" class="">                    
                    <tr>                    
                        <td valign="top" width="30%"><?php echo $this->lang->line('teacher_remark'); ?></td>
                        <td valign="top" style="font-weight: bold;"><?php echo $student_value['teacher_remark']; ?></td>
                    </tr>
                </table>
            </td>
          </tr>
                                <?php
                            }
                            ?>
        <tr>
            <td valign="top" style="padding-top: 10px;">
                <table cellpadding="0" cellspacing="0" width="100%" class="">                    
                    <tr>                    
                        <td valign="top" width="30%"><?php echo $this->lang->line('date'); ?></td>
                        <td valign="top" style="font-weight: bold;"><?php echo $template->date; ?></td>
                    </tr>
                </table>
            </td>
        </tr>       
        
            <tr>
            <td valign="top" height="30"></td>
        </tr>
         <?php
                        if ($template->content_footer != "") {
                            ?>
                             <tr>
             <td valign="bottom" style="font-size: 12px;">
                         <?php echo $template->content_footer ?>
                        </td>
        </tr>
                            <?php
                        }
                        ?>
                               <?php
                        if ($template->left_sign != "" || $template->middle_sign != "" || $template->right_sign != "") {
                            ?>
      <tr>
            <td valign="top">
                <table cellpadding="0" cellspacing="0" width="100%" class="">
                    <tr>
                         <?php
                                            if ($template->left_sign != "") {
                                                ?>
                        <td valign="bottom" align="center" style="text-transform: uppercase;">
                     <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $template->left_sign); ?>"  width="100" height="50">
                       </td>
                                                <?php
                                            }
                                            ?>
                                                <?php
                                            if ($template->middle_sign != "") {
                                                ?>
                        <td valign="bottom" align="center" style="text-transform: uppercase;">
                     <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $template->middle_sign); ?>"  width="100" height="50">
                        </td>
                                                <?php
                                            }
                                            ?>
                                                <?php
                                            if ($template->right_sign != "") {
                                                ?>
                        <td valign="middle" align="center" style="text-transform: uppercase;">
                    <img src="<?php echo $this->media_storage->getImageURL('uploads/marksheet/' . $template->right_sign); ?>"  width="100" height="50">
                    </td>
                                                <?php
                                            }
                                            ?>                       
                    </tr>
                </table>
            </td>
          </tr>
                            <?php
                        }
                        ?>

      <tr><td valign="top" height="20"></td></tr>      
      </table>
  </div>
<?php 

if($to_be_print < $total_students)
{
  ?>
  <pagebreak />
  <?php  
}

 ?>
                <?php

}
}
}


 if ($marksheet['exam_connection'] == 1) {
                $total_students= count($marksheet['students']);
                $to_be_print=0;
        foreach ($marksheet['students'] as $student_key => $student_value) {
                $to_be_print+=1;
            $percentage_total = 0;
?>

                 <div style="width: 100%; margin: 0 auto; border:1px solid #000; padding: 0px 5px 5px">
                 
            <?php if($template->header_image){ ?>     
            <img src="<?php echo base_url('uploads/marksheet/' . $template->header_image); ?>" width="100%" height="300px;">
            <?php } ?>
            
                 <table cellpadding="0" cellspacing="0" width="100%">                     
                                  <tr>
            <td valign="top">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td valign="top" align="center">
                             <?php
                                            if ($template->left_logo) {
                                                ?>
                            <img src="<?php echo base_url('uploads/marksheet/' . $template->left_logo); ?>" width="70" height="70"> 
                                                <?php
                                            }
                                                ?>
                        </td>
                        <td valign="top">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td valign="top" style="font-size: 20px; font-weight: bold; text-align: center;">
                                    <?php echo $template->exam_name; ?></td>
                                </tr>                                
                                <?php   if ($template->exam_session) {   ?>
                                <tr>
                                    <td valign="top" style="font-weight: bold; text-align: left; text-transform: uppercase; display: inline-block; margin-top: -10px; padding-bottom: 5px;">
                                    <?php echo $exam->session; ?></td>
                                </tr>
                                                    <?php
                                                }
                                                    ?>
                            </table>
                        </td>
                        <td valign="top" align="center">
                             <?php
                                            if ($template->right_logo) {
                                                ?>
                            <img src="<?php echo base_url('uploads/marksheet/' . $template->right_logo); ?>" width="70" height="70">
                                                <?php
}
?>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>

            <?php
                        if ($template->is_admission_no || $template->is_roll_no || $template->is_photo ) {

                            ?>
                          <tr>
            <td valign="top">
                <table cellpadding="0" cellspacing="0" width="100%" class="">
                    <tr>
                        <td valign="top">
                         <table cellpadding="0" cellspacing="0" width="98%" class="denifittable marks">
                    <tr>
                       <?php 
                                                        if ($template->is_admission_no) { 
                                                            ?>
                      <th valign="top" style="text-align: center; text-transform: uppercase;"  width="50%"><?php echo $this->lang->line('admission_no') ?></th>
                                                            <?php
                                                        }
                                                        ?>                    
                      <?php 

                           if ($template->is_roll_no) {
                                                            ?>
                                  <th valign="top" style="text-align: center; text-transform: uppercase;"  width="50%"><?php echo $this->lang->line('roll_number') ?></th>
                                                            <?php
                                                            }  ?>
                    </tr>
                    <tr>
                    <?php
                                                        if ($template->is_admission_no) { 
                                                            ?>
                        <td style="text-transform: uppercase;text-align: center;"><?php echo $student_value['admission_no']; ?></td>
                                                            <?php
                                                        }
                                                        ?>
                     <?php      
                            if ($template->is_roll_no) {
                                                            
                                                            ?>

                                 <td style="text-transform: uppercase;text-align: center;border-right:1px solid #999"><?php echo $roll_no=($exam->use_exam_roll_no)?$student_value['exam_result']['exam_roll_no_' . $exam->id]:$student_value['student_roll_no']; ?></td>
              <?php
                                                          }
                                                        ?>
                        
                    </tr>
                    <tr>
                        <td valign="top" colspan="5" style="text-align: center; text-transform: uppercase; border:0">         
                        Certificated That</td>
                    </tr>
                </table>
                        </td>
                             <td valign="top" align="right" style="border: 1px solid #000000; padding:2px; width:120px">
     <?php
                                            if ($template->is_photo) {
if ($student_value['image'] != '') { 
    ?>
    <img src="<?php echo base_url() . $student_value['image']; ?>"  width="120" height="150" >
                                                    <?php 
                                                } 
                                               
                                            }
                                            ?>
</td>
                    </tr>                
                </table>
            </td>
          </tr>
                            <?php
                        }
                        ?>
                         <tr>
                            <td valign="top">
                                <table cellpadding="0" cellspacing="0" width="100%">
                                    <?php
                                    if ($template->is_name) {
                                        ?>
                                        <tr>
                                        <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('name_prefix'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $this->customlib->getFullName($student_value['firstname'],$student_value['middlename'],$student_value['lastname'],$sch_setting->middlename,$sch_setting->lastname);  ?></span></td>
                                        </tr>
                                        <?php
                                    }

                                    if ($template->is_father_name) {
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('marksheet_father_name') ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $student_value['father_name']; ?></span></td>
                                        </tr>
                                        <?php
                                    }

                                    if ($template->is_mother_name) {
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('exam_mother_name'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $student_value['mother_name']; ?></span></td>
                                        </tr>
                                        <?php
                                    }
                                      if ($template->is_dob) {
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('date_of_birth'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $this->customlib->dateformat($student_value['dob']); ?></span></td>
                                        </tr>
                                        <?php
                                    }

                                    if ($template->is_class && $template->is_section) {
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('class'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $student_value['class'] . " (" . $student_value['section'] . ")"; ?> </span></td>
                                        </tr>
                                        <?php
                                    } elseif ($template->is_class) {
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('class'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $student_value['class']; ?> </span></td>
                                        </tr>
                                        <?php
                                    } elseif ($template->is_section) {
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"><?php echo $this->lang->line('class'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $student_value['section']; ?> </span></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($template->school_name != "") {
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-transform: uppercase; padding-bottom: 15px;"> <?php echo $this->lang->line('school_name'); ?> &nbsp;&nbsp;&nbsp;<span style="font-weight: bold;" class="span"><?php echo $template->school_name; ?></span></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($template->exam_center != "") {
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-transform: uppercase; padding-top: 15px; font-weight: bold; padding-bottom: 20px; padding-left: 30px;"><?php echo $this->lang->line('exam') . " " . $this->lang->line('center') ?><span style="text-transform: uppercase; padding-top: 15px; font-weight: bold; padding-bottom: 20px; padding-left: 30px;"><?php echo $template->exam_center; ?></span></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($template->content != "") {
                                        ?>
                                        <tr>
                                            <td valign="top" style="text-transform: uppercase; padding-bottom: 15px; line-height: normal;"><?php echo $template->content ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table>
                            </td>
                        </tr> 
                    <tr>
            <td valign="top">
                         <table cellpadding="0" cellspacing="0" width="100%" class="denifittable marks" style="text-align: center; text-transform: uppercase;">
        
        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('subjects') ?></th>
                                                <?php
                                                if ($exam->exam_group_type != "gpa") {
                                                    ?>
                                                    <th><?php echo $this->lang->line('max') ?></th>
                                                    <th><?php echo $this->lang->line('min') ?></th>
                                                    <th><?php echo $this->lang->line('marks') . " " . $this->lang->line('obtained') ?></th>
                                                    <?php
                                                } else if ($exam->exam_group_type == "gpa") {
                                                    ?>
                                                    <th class="text-center"><?php echo $this->lang->line('grade') . " " . $this->lang->line('point') ?></th>
                                                    <th class="text-center"><?php echo $this->lang->line('credit') . " " . $this->lang->line('hours') ?></th>
                                                    <th class="text-center"><?php echo $this->lang->line('quality') . " " . $this->lang->line('point') ?></th>
                                                    <?php
                                                }

                                                if ($exam->exam_group_type == "school_grade_system" || $exam->exam_group_type == "coll_grade_system") {
                                                    ?>
                                                    <th><?php echo $this->lang->line('grade') ?></th>
                                                    <?php
                                                }
                                                ?>
                                                <th valign="top" style="text-align: left;border-right:1px solid #999"><?php echo $this->lang->line('note') ?></th>
                                            </tr>
                                        </thead>
                                         <tbody>
                                            <?php
                                            $total_max_marks = 0;
                                            $total_obtain_marks = 0;
                                            $total_points = 0;
                                            $total_hours = 0;
                                            $total_quality_point = 0;
                                            $result_status = 1;
                                            $absent_status = false;

                                            foreach ($student_value['exam_result']['exam_result_' . $exam->id] as $exam_result_key => $exam_result_value) {

                                                $total_max_marks = $total_max_marks + $exam_result_value->max_marks;
                                                $total_obtain_marks = $total_obtain_marks + $exam_result_value->get_marks;
                                                if ($exam_result_value->attendence == "absent") {
                                                    $absent_status = true;
                                                }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $exam_result_value->name; ?> <?php if($exam_result_value->code){ echo ' ('.$exam_result_value->code.')'; } ?>
                                                    </td>
                                                    <?php
                                                    if ($exam->exam_group_type != "gpa") {
                                                        ?>
                                                        <td>
                                                            <?php echo $exam_result_value->max_marks; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $exam_result_value->min_marks; ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo $exam_result_value->get_marks;

                                                            if ($exam_result_value->attendence == "absent") {
                                                                echo "&nbsp;" . $this->lang->line('exam_absent');
                                                            }
                                                            if ($exam_result_value->get_marks < $exam_result_value->min_marks) {

                                                                $result_status = 0;
                                                            }
                                                            ?>
                                                        </td>
                                                        <?php
                                                    } else if ($exam->exam_group_type == "gpa") {
                                                        ?>
                                                        <td class="text-center">
                                                            <?php
                                                            $percentage_grade = ($exam_result_value->get_marks * 100) / $exam_result_value->max_marks;
                                                            $point = findGradePoints($exam_grades, $percentage_grade);
                                                            $total_points = $total_points + $point;
                                                            echo $point;
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            $total_hours = $total_hours + $exam_result_value->credit_hours;
                                                            echo ($exam_result_value->credit_hours);
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            echo ($exam_result_value->credit_hours * $point);
                                                            $total_quality_point = $total_quality_point + ($exam_result_value->credit_hours * $point);
                                                            ?>
                                                        </td>
                                                        <?php
                                                    }

                                                    if ($exam->exam_group_type == "school_grade_system" || $exam->exam_group_type == "coll_grade_system") {
                                                        ?>
                                                        <td>
                                                            <?php
                                                            $percentage_grade = ($exam_result_value->get_marks * 100) / $exam_result_value->max_marks;
                                                            echo findGrade($exam_grades, $percentage_grade);
                                                            ?>
                                                        </td>
                                                        <?php
                                                    }
                                                    ?>
                                                    <td valign="top" style="text-align: left;border-right:1px solid #999">
                                                        <?php
                                                        if ($exam->exam_group_type == "basic_system") {
                                                            if ($exam_result_value->get_marks < $exam_result_value->min_marks) {
                                                                $result_status = 0;
                                                                echo "(F) ";
                                                            }
                                                        }
                                                        echo $exam_result_value->note;
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            if ($exam->exam_group_type != "gpa") {
                                                ?>
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        <?php echo number_format($total_max_marks, 2, '.', ''); ?>
                                                    </td>
                                                    <td><?php echo $this->lang->line('grand_total') ?></td>
                                                    <td>
                                                        <?php echo number_format($total_obtain_marks, 2, '.', ''); ?>
                                                    </td>
                                                    <td valign="top" style="text-align: left;border-right:1px solid #999"></td>
                                                    <?php
                                                    if ($exam->exam_group_type == "school_grade_system" || $exam->exam_group_type == "coll_grade_system") {
                                                        ?>
                                                        <td valign="top" style="text-align: left;border-right:1px solid #999"></td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $this->lang->line('percentage') ?></td>
                                                    <td>
                                                        <?php
                                                        echo number_format((($total_obtain_marks * 100) / $total_max_marks), 2, '.', '');
                                                        $percentage_total = (($total_obtain_marks * 100) / $total_max_marks);
                                                        ?>
                                                    </td>
                                                    <td></td>
                                                    <td>
                                                    </td>
                                                    <td valign="top" style="text-align: left;border-right:1px solid #999"></td>
                                                    <?php
                                                    if ($exam->exam_group_type == "school_grade_system" || $exam->exam_group_type == "coll_grade_system") {
                                                        ?>
                                                        <td valign="top" style="text-align: left;border-right:1px solid #999"></td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <?php
                                            } else if ($exam->exam_group_type == "gpa") {
                                                ?>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-center">
                                                        <?php
                                                        echo number_format($total_hours, 2, '.', '');
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        if ($total_hours <= 0) {
                                                            echo "--";
                                                        } else {
                                                            $total_grade_percentage = ($total_obtain_marks * 100) / $total_max_marks;
                                                            $exam_qulity_point = number_format($total_quality_point / $total_hours, 2, '.', '');
                                                             $percentage_total=($exam_qulity_point*100)/10;

                                                            echo $total_quality_point . "/" . $total_hours . "=" . $exam_qulity_point . " [" . findGrade($exam_grades, $total_grade_percentage) . "]";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td></td>
                                                    <?php
                                                }
                                                ?>
                                        </tbody>         
</table>
</td>
</tr>
  <tr>
                            <td valign="top" height="30"></td>
                        </tr>

                        <?php
                        if ($exam->exam_group_type != "gpa") {
                            ?>
               <tr>
            <td valign="top" style="padding-top: 10px;">
                <table cellpadding="0" cellspacing="0" width="100%" class="">                    
                    <tr>                        
                        <td valign="top" width="30%"> <?php echo $this->lang->line('result') ?></td>
                        <td valign="top" style="font-weight: bold;">
                              <?php

                               if($exam->exam_group_type == "average_passing"){
                                    if($exam->passing_percentage <= $percentage_total){
                                     echo $this->lang->line('pass');
                                    }else{
                                     echo $this->lang->line('fail');
                                    }
                              }else{
                                        if ($result_status == 0 || $absent_status) {
                                            echo $this->lang->line('fail');
                                        } else {

                                            if ($percentage_total > 40) {
                                                echo $this->lang->line('pass');
                                            } else {
                                                echo $this->lang->line('fail');
                                            }
                                        }
                             
                              }
                                        ?>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>
                            <?php
                        }
                        ?>

                            <?php
                            if ($template->is_division) {
                                ?>
   <tr>
            <td valign="top" style="padding-top: 10px;">
                <table cellpadding="0" cellspacing="0" width="100%%" class="">
                    <tr>
                      <td valign="top" width="30%"> <?php echo $this->lang->line('division') ?></td>
                        <td valign="top" style="font-weight: bold;">
                                   <?php
                            
                                       echo findExamDivision( $marks_division, $percentage_total);
                                    ?>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>
                                <?php
                            }

                                       if ($template->is_rank) {
                                ?>
   <tr>
            <td valign="top" style="padding-top: 10px;">
                <table cellpadding="0" cellspacing="0" width="100%%" class="">
                    <tr>
                      <td valign="top" width="30%"> <?php echo $this->lang->line('rank') ?></td>
                        <td valign="top" style="font-weight: bold;">
                              <?php echo ($student_value['rank']);  ?>
                        </td>
                    </tr>
                </table>
            </td>
          </tr>
                                <?php
                            }
                            ?>

                               <?php
        if ($template->is_teacher_remark) {
                                ?>
                                 <tr>
            <td valign="top" style="padding-top: 10px;">
                <table cellpadding="0" cellspacing="0" width="100%" class="">                    
                    <tr>                    
                        <td valign="top" width="30%"><?php echo $this->lang->line('teacher_remark'); ?></td>
                        <td valign="top" style="font-weight: bold;"><?php echo $student_value['teacher_remark']; ?></td>
                    </tr>
                </table>
            </td>
          </tr>
                                <?php
                            }
                            ?>          
        <tr>
            <td valign="top" style="padding-top: 10px;">
                <table cellpadding="0" cellspacing="0" width="100%" class="">                    
                    <tr>                    
                        <td valign="top" width="30%"><?php echo $this->lang->line('date'); ?></td>
                        <td valign="top" style="font-weight: bold;"><?php echo $template->date; ?></td>
                    </tr>
                </table>
            </td>
        </tr>        
          <tr>
            <td valign="top" height="30"></td>
          </tr>          
          <tr>
                            <td valign="top" style="padding-top: 10px;">
                                    <?php
                                if (!empty($student_value['exam_result'])) {
                                    $consolidate_weightage_marks_total = 0;
                                    $consolidate_get_total_percentage=0;
                                    $consolidate_marks_exam_total = 0;
                                    $is_consoledate = 1;
                                    $consolidate_exam_status = true;

                                    foreach ($marksheet['exams'] as $each_exam_key => $each_exam_value) {

                                        if (empty($marksheet['students'][$student_key]['exam_result']['exam_result_' . $each_exam_value->id])) {
                                            $is_consoledate = 0;
                                        }
                                    }
                                    ?>
                                    <table cellpadding="0" cellspacing="0" width="100%" class="denifittable" style="text-align: center; text-transform: uppercase;">
                                        <thead>
                                            <tr>
                                                <th> <?php echo $this->lang->line('exam'); ?></th>
                                                <?php
                                              
                                                foreach ($marksheet['exams'] as $each_exam_key => $each_exam_value) {
                                                    ?>
                                                    <th> <?php echo $each_exam_value->exam; ?></th>
                                                    <?php
                                                }
                                                ?>
                                                <th valign="top" style="text-align: left;border-right:1px solid #999"><?php echo $this->lang->line('consolidate') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?php echo $this->lang->line('marks_obtained'); ?></td>
                                                <?php

                                                foreach ($marksheet['exams'] as $each_exam_key => $each_exam_value) {
                                                  
                                                    if ($is_consoledate) {
                                                        ?>
                                                        <td>
                                                            <?php
                                                     
          $s = examTotalResult($marksheet['students'][$student_key]['exam_result']['exam_result_' . $each_exam_value->id],$exam->exam_group_type,$exam_grades);

                      $sss = json_decode($s);
                           $exam_status=$sss->exam_result;                             

      if($exam->exam_group_type != "gpa"){
                
        $percentage_grade = (($sss->get_marks * 100) / $sss->max_marks);                                                         
        $consolidate_marks_exam_total = $consolidate_marks_exam_total + $sss->max_marks;
        $weightage_marks = getWeightageExam($marksheet['exam_connection_list'], $each_exam_value->id, $sss->get_marks, $percentage_grade);
        
           $consolidate_get_total_percentage += ($weightage_marks['exam_consolidate_percentage']);
         $consolidate_get_exam_total_percentage=$consolidate_get_total_percentage;

         echo two_digit_float($weightage_marks['exam_consolidate_marks'])." (".$weightage_marks['exam_weightage']."%)";
            if($each_exam_value->exam_group_type == "average_passing"){
               if( $each_exam_value->passing_percentage > $percentage_grade ){
                 $consolidate_exam_status = false;
                 $exam_status=false;
              
               }
            }else{
                 if (!$sss->exam_result) {
                     $consolidate_exam_status = false;
                  }
            }
                                 if (!$exam_status) {
                                     echo "&nbsp;(F) ";
                                 }

}else{


         $consolidate_exam_result = ($sss->quality_point / $sss->credit_hours);
         $consolidate_marks_exam_total +=  $sss->max_marks;
         $weightage_marks = getWeightageExam($marksheet['exam_connection_list'], $each_exam_value->id, $consolidate_exam_result,100);      

         $consolidate_get_total_percentage +=$weightage_marks['exam_consolidate_marks'];
         $consolidate_get_exam_total_percentage=($consolidate_get_total_percentage*100)/10;
         echo two_digit_float($weightage_marks['exam_consolidate_marks']);
}

                                ?>
                                 </td>
                                         <?php
                                     } else {
                                      ?>
                                       <td>
                                            --
                                         </td>
                                        <?php
                                                    }
                                                }

                                                if ($is_consoledate) {
                                                    ?>
                                                    <td valign="top" style="text-align: left;border-right:1px solid #999">
                                                       <?php
                                                    if($exam->exam_group_type != "gpa"){
 echo two_digit_float($consolidate_get_total_percentage, 2, '.', '')  . " [" . findGrade($exam_grades, $consolidate_get_total_percentage) . "]";

                                                    }else{
 echo two_digit_float($consolidate_get_total_percentage, 2, '.', '')  . " [" . findGrade($exam_grades, (($consolidate_get_total_percentage)*100)/10) . "]";
                                                    }

                                                        ?>
                                                    </td>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <td>
                                                        --
                                                    </td>
                                                    <?php
                                                }
                                                ?>
                                            </tr>
                                        </tbody>
                                    </table>                                 
                                 
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
  <tr>
            <td valign="top" style="padding-top: 10px;">
                <table cellpadding="0" cellspacing="0" width="100%" class="">                    
                    <tr>                      
                        <td valign="top" width="30%"><?php echo $this->lang->line('result'); ?></td>
                        <td valign="top" style="font-weight: bold;"> 
                            <?php
                                    if (!$consolidate_exam_status) {
                                        echo  $this->lang->line('fail');
                                    } else {
                                        echo  $this->lang->line('pass');
                                    }
                                    ?>                                        
                                    </td>
                    </tr>
                </table>
            </td>
          </tr>

              <?php
                            if ($template->is_division) {
                                ?>

                                      <tr>
            <td valign="top" style="padding-top: 10px;">
                <table cellpadding="0" cellspacing="0" width="100%" class="">                    
                    <tr>                      
                        <td valign="top" width="30%"><?php echo $this->lang->line('division'); ?></td>
                        <td valign="top" style="font-weight: bold;">  
                              <?php
                               if($consolidate_marks_exam_total > 0){                        

                             echo findExamDivision( $marks_division, $consolidate_get_exam_total_percentage);
                             
                               }
                             ?> </td>
                    </tr>
                </table>
            </td>
          </tr>          
                                <?php
                            }
                            ?> 
                <tr>
                        <td valign="top" height="30"></td>
                    </tr>
 <?php
                        if ($template->content_footer != "") {
                            ?>
                             <tr>
             <td valign="bottom" style="font-size: 12px;">
                         <?php echo $template->content_footer ?>
                        </td>
        </tr>
                            <?php
                        }
                        ?>
                                  <?php
                        if ($template->left_sign != "" || $template->middle_sign != "" || $template->right_sign != "") {
                            ?>
      <tr>
            <td valign="top">
                <table cellpadding="0" cellspacing="0" width="100%" class="">
                    <tr>
                         <?php
                                            if ($template->left_sign != "") {
                                                ?>
                        <td valign="bottom" align="center" style="text-transform: uppercase;">
                     <img src="<?php echo base_url('uploads/marksheet/' . $template->left_sign); ?>"  width="100" height="50">
                       </td>
                                                <?php
                                            }
                                            ?>
                                                <?php
                                            if ($template->middle_sign != "") {
                                                ?>
                        <td valign="bottom" align="center" style="text-transform: uppercase;">
                     <img src="<?php echo base_url('uploads/marksheet/' . $template->middle_sign); ?>"  width="100" height="50">
                        </td>
                                                <?php
                                            }
                                            ?>
                                                <?php
                                            if ($template->right_sign != "") {
                                                ?>
                        <td valign="middle" align="center" style="text-transform: uppercase;">
                    <img src="<?php echo base_url('uploads/marksheet/' . $template->right_sign); ?>"  width="100" height="50">
                    </td>
                                                <?php
                                            }
                                            ?>                       
                    </tr>
                </table>
            </td>
          </tr>
                            <?php
                        }
                        ?>
   <tr><td valign="top" height="20"></td></tr>
                 </table>
             </div>
            <?php

                if($to_be_print < $total_students)
                {
                  ?>
                  <pagebreak />
             
                  <?php  
                }
            ?>
<?php
         
        }
    }
}
?>

<?php

function findGrade($exam_grades, $percentage) {

    if (!empty($exam_grades)) {
        foreach ($exam_grades as $exam_grade_key => $exam_grade_value) {

            if ($exam_grade_value->mark_from >= $percentage && $exam_grade_value->mark_upto <= $percentage) {
                return $exam_grade_value->name;
            }
        }
    }

    return "-";
}

function findExamDivision( $marks_division, $percentage)
{  
            if (!empty($marks_division)) {
                foreach ($marks_division as $division_key => $division_value) {
                    if ($division_value->percentage_from >= $percentage && $division_value->percentage_to <= $percentage) {
                        return $division_value->name;
                    }
                }
            }       
  
    return "";
}

function findGradePoints($exam_grades, $percentage) {

    if (!empty($exam_grades)) {
        foreach ($exam_grades as $exam_grade_key => $exam_grade_value) {

            if ($exam_grade_value->mark_from >= $percentage && $exam_grade_value->mark_upto <= $percentage) {
                return $exam_grade_value->point;
            }
        }
    }

    return 0;
}

function examTotalResult($array,$exam_type,$exam_grade) {
   
    $return_array = array('max_marks' => 0, 'min_marks' => 0, 'credit_hours' => 0, 'get_marks' => 0, 'exam_result' => true);

    if (!empty($array)) {
        $max_marks = 0;
        $min_marks = 0;
        $credit_hours = 0;
        $get_marks = 0;
        $exam_result = true;
        $total_points=0;
        $quality_point=0;
        foreach ($array as $array_key => $array_value) {
         
            if ($array_value->attendence == "absent") {
                $exam_result = false;
            }

                $percentage_grade    = ($array_value->get_marks * 100) / $array_value->max_marks;
               
                 $point               = findGradePoints($exam_grade, $percentage_grade);

                $total_points +=  $point;
                $quality_point += ($point*$array_value->credit_hours);
            
            $max_marks = $max_marks + $array_value->max_marks;
            $min_marks = $min_marks + $array_value->min_marks;
            $credit_hours = $credit_hours + $array_value->credit_hours;
            $get_marks = $get_marks + $array_value->get_marks;
        }

        $return_array = array('max_marks' => $max_marks, 'min_marks' => $min_marks, 'credit_hours' => $credit_hours, 'get_marks' => $get_marks, 'exam_result' => $exam_result,'total_points'=>$total_points,'quality_point'=>$quality_point);
    }
    return json_encode($return_array);
}

function getWeightageExam($exam_connection_list, $examid, $get_marks,$exam_get_percentage) {

    foreach ($exam_connection_list as $exam_connection_key => $exam_connection_value) {
        if ($exam_connection_value->exam_group_class_batch_exams_id == $examid) {
            return  [ 'exam_weightage'=>$exam_connection_value->exam_weightage,
                      'exam_consolidate_marks'=>($get_marks * $exam_connection_value->exam_weightage) / 100,
                      'exam_consolidate_percentage'=> ($exam_get_percentage * $exam_connection_value->exam_weightage) / 100];
        }
    }
    return "";
}
?>