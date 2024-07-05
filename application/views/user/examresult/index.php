<div class="content-wrapper" style="min-height: 946px;">
   <section class="content-header">
      <h1>
         <i class="fa fa-map-o"></i> <?php //echo $this->lang->line('examinations'); ?> <small><?php //echo $this->lang->line('student_fee1'); ?></small>
      </h1>
   </section>
   <!-- Main content -->
   <section class="content" id="exam">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-warning">
               <div class="box-header ptbnull">
                  <h3 class="box-title titlefix"><?php echo $this->lang->line('exam_result'); ?> </h3>
                  <div class="box-tools pull-right">
                     <div class="dt-buttons btn-group btn-group2 pt5">
                    <?php if (!empty($exam_result)) {?>
                        <a class="dt-button btn btn-default btn-xs no_print" data-toggle="tooltip"  title="<?php echo $this->lang->line('print'); ?>" id="print" onclick="printDiv()" ><i class="fa fa-print"></i></a>
                    <?php }?>
                     </div>
                  </div>
               </div>
               <div class="box-body table-responsive" >
                  <div class="download_label"><?php echo $this->lang->line('exam_result'); ?></div>
                  <?php
if (!empty($exam_result)) {
    foreach ($exam_result as $exam_key => $exam_value) {
        ?>
                  <div class="tshadow mb25">
                     <h4 class="pagetitleh">
                        <?php
echo $exam_value->exam;
        ?>
                     </h4>
                     <?php
if (!empty($exam_value->exam_result)) {
            if ($exam_value->exam_result['exam_connection'] == 0) {
                if (!empty($exam_value->exam_result['result'])) {
                    $exam_quality_points = 0;
                    $exam_total_points   = 0;
                    $exam_credit_hour    = 0;
                    $exam_grand_total    = 0;
                    $exam_get_total      = 0;
                    $exam_pass_status    = 1;
                    $exam_absent_status  = 0;
                    $total_exams         = 0;
                    ?>
                     <div class="table-responsive">
                        <table class="table table-striped table-hover ptt10" id="headerTable">
                           <thead>
                              <th><?php echo $this->lang->line('subject'); ?></th>
                              <?php
if ($exam_value->exam_type == "gpa") {
                        ?>
                              <th><?php echo $this->lang->line('grade_point'); ?></th>
                              <th><?php echo $this->lang->line('credit_hours'); ?></th>
                              <th><?php echo $this->lang->line('quality_points'); ?></th>
                              <?php
}
                    ?>
                              <?php
if ($exam_value->exam_type != "gpa") {

                        ?>

                              <th><?php echo $this->lang->line('max_marks'); ?></th>
                              <?php
if ($exam_value->exam_type != "average_passing") {
                            ?>
 <th><?php echo $this->lang->line('min_marks'); ?></th>
<?php
}
                        ?>

                              <th><?php echo $this->lang->line('marks_obtained'); ?></th>
                              <?php
}
                    ?>
                              <?php
if ($exam_value->exam_type == "coll_grade_system" || $exam_value->exam_type == "school_grade_system") {
                        ?>
                              <th><?php echo $this->lang->line('grade'); ?> </th>
                              <?php
}

                    if ($exam_value->exam_type == "basic_system") {
                        ?>
                              <th>
                                 <?php echo $this->lang->line('result'); ?>
                              </th>
                              <?php
}
                    ?>
                              <th><?php echo $this->lang->line('note'); ?></th>
                           </thead>
                           <tbody>
                              <?php
if (!empty($exam_value->exam_result['result'])) {
                        $total_exams = 1;
                        foreach ($exam_value->exam_result['result'] as $exam_result_key => $exam_result_value) {
                            $exam_grand_total = $exam_grand_total + $exam_result_value->max_marks;
                            $exam_get_total   = $exam_get_total + $exam_result_value->get_marks;
                            $percentage_grade = ($exam_result_value->get_marks * 100) / $exam_result_value->max_marks;
                            if ($exam_result_value->get_marks < $exam_result_value->min_marks) {
                                $exam_pass_status = 0;
                            }
                            ?>
                              <tr>
                                 <td><?php echo ($exam_result_value->name); ?> <?php if($exam_result_value->code){ echo ' ('.$exam_result_value->code.')'; } ?></td>
                                 <?php
if ($exam_value->exam_type != "gpa") {
                                ?>
                                 <td><?php echo ($exam_result_value->max_marks); ?></td>
                                 <?php
if ($exam_value->exam_type != "average_passing") {
                                    ?>
    <td><?php echo ($exam_result_value->min_marks); ?></td>
  <?php
}
                                ?>
                                 <td>
                                    <?php
echo $exam_result_value->get_marks;

                                if ($exam_result_value->attendence == "absent") {
                                    $exam_absent_status = 1;
                                    echo "&nbsp;" . $this->lang->line('abs');
                                }
                                ?>
                                 </td>
                                 <?php
} elseif ($exam_value->exam_type == "gpa") {
                                ?>
                                 <td>
                                    <?php
$percentage_grade  = ($exam_result_value->get_marks * 100) / $exam_result_value->max_marks;
                                $point             = findGradePoints($exam_grade, $exam_value->exam_type, $percentage_grade);
                                $exam_total_points = $exam_total_points + $point;
                                echo two_digit_float($point);
                                ?>
                                 </td>
                                 <td> <?php
echo $exam_result_value->credit_hours;
                                $exam_credit_hour = $exam_credit_hour + $exam_result_value->credit_hours;
                                ?></td>
                                 <td><?php
echo two_digit_float($exam_result_value->credit_hours * $point);
                                $exam_quality_points = $exam_quality_points + ($exam_result_value->credit_hours * $point);
                                ?></td>
                                 <?php
}
                            ?>
                                 <?php
if ($exam_value->exam_type == "coll_grade_system" || $exam_value->exam_type == "school_grade_system") {
                                ?>
                                 <td><?php echo findExamGrade($exam_grade, $exam_value->exam_type, $percentage_grade); ?></td>
                                 <?php
}
                            if ($exam_value->exam_type == "basic_system") {
                                ?>
                                 <td>
                                    <?php
if ($exam_result_value->get_marks < $exam_result_value->min_marks) {
                                    ?>
                                    <label class="label label-danger bolds ps-1"><?php echo $this->lang->line('fail') ?></label>
                                    <?php
} else {
                                    ?>
                                    <label class="label label-success bolds ps-1"><?php echo $this->lang->line('pass') ?></label>
                                    <?php
}
                                ?>
                                 </td>
                                 <?php
}
                            ?>
                                 <td><?php echo ($exam_result_value->note); ?></td>
                              </tr>
                              <?php
}
                    }
                    ?>
                           </tbody>
                        </table>
                     </div>
                     <?php
?>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="bgtgray">
                              <?php
if ($exam_value->exam_type != "gpa") {
                        ?>
                              <div class="col-sm-2 pull ">
                                 <div class="description-block">
                                    <h5 class="description-header"><?php echo $this->lang->line('percentage') ?> :  <span class="description-text"><?php
$exam_percentage = ($exam_get_total * 100) / $exam_grand_total;
                        echo two_digit_float($exam_percentage);
                        ?></span></h5>
                                 </div>
                              </div>
                      <div class="col-sm-2 pull ">
                        <div class="description-block">
                          <h5 class="description-header"><?php echo $this->lang->line('rank') ?> :  <span class="description-text">
                                      <?php

                        echo $exam_value->rank;
                        ?>

                        </span>
                      </h5>
                                 </div>
                              </div>
                              <div class="col-sm-3 border-right ">
                                 <div class="description-block">
                                    <h5 class="description-header"><?php echo $this->lang->line('result') ?> :<span class="description-text">
                                       <?php
if ($total_exams) {

                            if ($exam_value->exam_type == "average_passing") {
                                if ($exam_value->passing_percentage <= $exam_percentage) {

                                    ?>
                                          <span class='label bg-green ps-1' style="margin-right: 5px;">
                                          <?php
echo $this->lang->line('pass');
                                    ?>
                                          </span> <?php
} else {
                                    ?>
                                          <span class='label label-danger ps-1'>
                                          <?php
echo $this->lang->line('fail');
                                    ?>
                                          </span>
                                          </span><?php
}

                                echo $this->lang->line('division') . " : " . findExamDivision($marks_division, $exam_percentage);

                            } else {
                                if ($exam_absent_status) {
                                    ?>
                                       <span class='label label-danger' style="margin-right: 5px;">
                                       <?php
echo $this->lang->line('fail');
                                    ?>
                                       </span>
                                       <?php
} else {
                                    if ($exam_pass_status) {
                                        ?>
                                       <span class='label bg-green ps-1' style="margin-right: 5px;">
                                       <?php
echo $this->lang->line('pass');
                                        ?>
                                       </span> <?php
} else {
                                        ?>
                                       <span class='label label-danger ps-1' style="margin-right: 5px;">
                                       <?php
echo $this->lang->line('fail');
                                        ?>
                                       </span>
                                       </span><?php
}

                                    echo $this->lang->line('division') . " : " . findExamDivision($marks_division, $exam_percentage);

                                }
                            }
                        }
                        ?>
                                    </h5>
                                 </div>
                              </div>
                              <div class="col-sm-2 border-right ">
                                 <div class="description-block">
                                    <h5 class="description-header"><?php echo $this->lang->line('grand_total') ?> : <span class="description-text"><?php echo $exam_grand_total; ?></span></h5>
                                 </div>
                              </div>
                              <div class="col-sm-3 border-right ">
                                 <div class="description-block">
                                    <h5 class="description-header"><?php echo $this->lang->line('total_obtain_marks') ?> :  <span class="description-text"><?php echo $exam_get_total; ?></span></h5>
                                 </div>
                              </div>
                              <?php
} elseif ($exam_value->exam_type == "gpa") {
                        ?>
                              <div class="col-sm-2 pull ">
                                 <div class="description-block">
                                    <h5 class="description-header"><?php echo $this->lang->line('credit_hours'); ?> :  <span class="description-text"><?php echo $exam_credit_hour; ?></span></h5>
                                 </div>
                              </div>
                          <div class="col-sm-3 pull ">
                                 <div class="description-block">
                                    <h5 class="description-header"><?php echo $this->lang->line('rank'); ?> :  <span class="description-text"><?php echo $exam_value->rank; ?></span></h5>
                                 </div>
                              </div>
                              <div class="col-sm-3 pull ">
                                 <div class="description-block">
                                    <h5 class="description-header">
                                       <?php echo $this->lang->line('quality_points'); ?> :
                                       <span class="description-text">
                                       <?php
if ($exam_credit_hour <= 0) {
                            echo "--";
                        } else {
                            $exam_grade_percentage = ($exam_get_total * 100) / $exam_grand_total;
                            echo $exam_quality_points . "/" . $exam_credit_hour . '=' . two_digit_float($exam_quality_points / $exam_credit_hour) . " [" . findExamGrade($exam_grade, $exam_value->exam_type, $exam_grade_percentage) . "]";
                        }
                        ?>
                                       </span>
                                    </h5>
                                 </div>
                              </div>
                              <?php
}
                    ?>
                           </div>
                        </div>
                     </div>
                  </div>
                  <?php
}
            } elseif ($exam_value->exam_result['exam_connection'] == 1) {

                $exam_connected_exam = ($exam_value->exam_result['exam_result']['exam_result_' . $exam_value->exam_group_class_batch_exam_id]);

                if (!empty($exam_connected_exam)) {
                    $exam_quality_points = 0;
                    $exam_total_points   = 0;
                    $exam_credit_hour    = 0;
                    $exam_grand_total    = 0;
                    $exam_get_total      = 0;
                    $exam_pass_status    = 1;
                    $exam_absent_status  = 0;
                    $total_exams         = 0;
                    ?>
                  <table class="table table-striped  ">
                     <thead>
                        <th><?php echo $this->lang->line('subject') ?></th>
                        <?php
if ($exam_value->exam_type == "gpa") {
                        ?>
                        <th><?php echo $this->lang->line('grade_point'); ?> </th>
                        <th><?php echo $this->lang->line('credit_hours'); ?></th>
                        <th><?php echo $this->lang->line('quality_points'); ?></th>
                        <?php
}
                    ?>
                        <?php
if ($exam_value->exam_type != "gpa") {
                        ?>
                        <th><?php echo $this->lang->line('max_marks'); ?></th>
                        <?php
if ($exam_value->exam_type != "average_passing") {
                            ?>
   <th><?php echo $this->lang->line('min_marks') ?></th>
  <?php
}
                        ?>

                        <th><?php echo $this->lang->line('marks_obtained'); ?> </th>
                        <?php
}
                    ?>
                        <?php
if ($exam_value->exam_type == "coll_grade_system" || $exam_value->exam_type == "school_grade_system") {
                        ?>
                        <th><?php echo $this->lang->line('grade'); ?></th>
                        <?php
}

                    if ($exam_value->exam_type == "basic_system") {
                        ?>
                        <th>
                           <?php echo $this->lang->line('result'); ?>
                        </th>
                        <?php
}
                    ?>
                        <th><?php echo $this->lang->line('note') ?></th>
                     </thead>
                     <tbody>
                        <?php
if (!empty($exam_connected_exam)) {
                        $total_exams = 1;
                        foreach ($exam_connected_exam as $exam_result_key => $exam_result_value) {
                            $exam_grand_total = $exam_grand_total + $exam_result_value->max_marks;
                            $exam_get_total   = $exam_get_total + $exam_result_value->get_marks;
                            $percentage_grade = ($exam_result_value->get_marks * 100) / $exam_result_value->max_marks;
                            if ($exam_result_value->get_marks < $exam_result_value->min_marks) {
                                $exam_pass_status = 0;
                            }
                            ?>
                        <tr>
                           <td><?php echo ($exam_result_value->name); ?> <?php if($exam_result_value->code){ echo ' ('.$exam_result_value->code.')'; } ?></td>
                           <?php
if ($exam_value->exam_type != "gpa") {
                                ?>
                           <td><?php echo ($exam_result_value->max_marks); ?></td>

                             <?php
if ($exam_value->exam_type != "average_passing") {
                                    ?>
 <td><?php echo ($exam_result_value->min_marks); ?></td>
  <?php
}
                                ?>

                           <td>
                              <?php
echo $exam_result_value->get_marks;

                                if ($exam_result_value->attendence == "absent") {
                                    $exam_absent_status = 1;
                                    echo "&nbsp; " . $this->lang->line('abs');
                                }
                                ?>
                           </td>
                           <?php
} elseif ($exam_value->exam_type == "gpa") {
                                ?>
                           <td>
                              <?php
$percentage_grade  = ($exam_result_value->get_marks * 100) / $exam_result_value->max_marks;
                                $point             = findGradePoints($exam_grade, $exam_value->exam_type, $percentage_grade);
                                $exam_total_points = $exam_total_points + $point;
                                echo two_digit_float($point);
                                ?>
                           </td>
                           <td> <?php
echo $exam_result_value->credit_hours;
                                $exam_credit_hour = $exam_credit_hour + $exam_result_value->credit_hours;
                                ?></td>
                           <td><?php
echo two_digit_float($exam_result_value->credit_hours * $point);
                                $exam_quality_points = $exam_quality_points + ($exam_result_value->credit_hours * $point);
                                ?></td>
                           <?php
}
                            ?>
                           <?php
if ($exam_value->exam_type == "coll_grade_system" || $exam_value->exam_type == "school_grade_system") {
                                ?>
                           <td><?php echo findExamGrade($exam_grade, $exam_value->exam_type, $percentage_grade); ?></td>
                           <?php
}
                            if ($exam_value->exam_type == "basic_system") {
                                ?>
                           <td>
                              <?php
if ($exam_result_value->get_marks < $exam_result_value->min_marks) {
                                    ?>
                              <label class="label label-danger"><?php echo $this->lang->line('fail') ?><label>
                              <?php
} else {
                                    ?>
                              <label class="label label-success"><?php echo $this->lang->line('pass') ?><label>
                              <?php
}
                                ?>
                           </td>
                           <?php
}
                            ?>
                           <td><?php echo ($exam_result_value->note); ?></td>
                        </tr>
                        <?php
}
                    }
                    ?>
                     </tbody>
                  </table>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="bgtgray">
                           <?php
if ($exam_value->exam_type != "gpa") {
                        ?>
                           <div class="col-sm-2 pull ">
                              <div class="description-block">
                                 <h5 class="description-header"> <?php echo $this->lang->line('percentage') ?> :  <span class="description-text">
                                    <?php
$exam_percentage = ($exam_get_total * 100) / $exam_grand_total;
                        echo two_digit_float($exam_percentage);
                        ?>
                                    </span>
                                 </h5>
                              </div>
                           </div>
                             <div class="col-sm-2 pull ">
                              <div class="description-block">
                                 <h5 class="description-header"> <?php echo $this->lang->line('rank') ?> :  <span class="description-text">
                                    <?php
echo $exam_value->rank;
                        ?>
                                    </span>
                                 </h5>
                              </div>
                           </div>
                           <div class="col-sm-3 border-right ">
                              <div class="description-block">
                                 <h5 class="description-header"><?php echo $this->lang->line('result'); ?> :<span class="description-text">
                                    <?php
if ($total_exams) {

                            if ($exam_value->exam_type == "average_passing") {
                                if ($exam_value->passing_percentage <= $exam_percentage) {

                                    ?>
                                          <span class='label bg-green' style="margin-right: 5px;">
                                          <?php
echo $this->lang->line('pass');
                                    ?>
                                          </span> <?php
} else {
                                    ?>
                                          <span class='label label-danger' style="margin-right: 5px;">
                                          <?php
echo $this->lang->line('fail');
                                    ?>
                                          </span>
                                          <?php
}

                            } else {
                                if ($exam_absent_status) {
                                    ?>
                                    <span class='label label-danger' style="margin-right: 5px;">
                                    <?php
echo $this->lang->line('fail');
                                    ?>
                                    </span>
                                    <?php
} else {
                                    if ($exam_pass_status) {
                                        ?>
                                    <span class='label bg-green' style="margin-right: 5px;">
                                    <?php
echo $this->lang->line('pass');
                                        ?>
                                    </span>
                                    <?php
} else {
                                        ?>
                                    <span class='label label-danger' style="margin-right: 5px;">
                                    <?php
echo $this->lang->line('fail');
                                        ?>
                                    </span>
                                    <?php
}
                                }
                            }
                        }
                        ?>
                                    <?php
if ($total_exams) {

                            if ($exam_pass_status) {
                                echo $this->lang->line('division') . " : " . findExamDivision($marks_division, $exam_percentage);
                            }
                        }
                        ?>
                                    </span>
                                 </h5>
                              </div>
                           </div>
                           <div class="col-sm-2 border-right ">
                              <div class="description-block">
                                 <h5 class="description-header"><?php echo $this->lang->line('grand_total'); ?> : <span class="description-text"><?php echo $exam_grand_total; ?></span></h5>
                              </div>
                           </div>
                           <div class="col-sm-3 border-right ">
                              <div class="description-block">
                                 <h5 class="description-header"><?php echo $this->lang->line('total_obtain_marks'); ?> :  <span class="description-text"><?php echo $exam_get_total; ?></span></h5>
                              </div>
                           </div>
                           <?php
} elseif ($exam_value->exam_type == "gpa") {
                        ?>
                           <div class="col-sm-2 pull ">
                              <div class="description-block">
                                 <h5 class="description-header">
                                    <?php echo $this->lang->line('credit_hours'); ?> :
                                    <span class="description-text"><?php echo $exam_credit_hour; ?>
                                    </span>
                                 </h5>
                              </div>
                           </div>
                             <div class="col-sm-3 pull ">
                              <div class="description-block">
                                 <h5 class="description-header">
                                    <?php echo $this->lang->line('rank'); ?> :
                                    <span class="description-text"><?php echo $exam_value->rank; ?>
                                    </span>
                                 </h5>
                              </div>
                           </div>
                           <div class="col-sm-3 pull ">
                              <div class="description-block">
                                 <h5 class="description-header">
                                    <?php echo $this->lang->line('quality_points'); ?> :
                                    <span class="description-text">
                                    <?php
if ($exam_credit_hour <= 0) {
                            echo "--";
                        } else {
                            $exam_grade_percentage = ($exam_get_total * 100) / $exam_grand_total;
                            echo $exam_quality_points . "/" . $exam_credit_hour . '=' . two_digit_float($exam_quality_points / $exam_credit_hour) . " [" . findExamGrade($exam_grade, $exam_value->exam_type, $exam_grade_percentage) . "]";
                        }
                        ?>
                                    </span>
                                 </h5>
                              </div>
                           </div>
                           <?php
}
                }
                ?>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="tshadow mb25">
                  <h4 class="pagetitleh">
                     <?php echo $this->lang->line('consolidated_result'); ?>
                  </h4>

                  <?php
$consolidate_exam_result            = false;
                $consolidate_exam_result_percentage = false;
                if ($exam_value->exam_type == "coll_grade_system" || $exam_value->exam_type == "school_grade_system") {
                    ?>
                <div class="table-responsive">
                  <table class="table table-striped">
                     <thead>
                        <th><?php echo $this->lang->line('exam') ?></th>
                        <?php
foreach ($exam_value->exam_result['exams'] as $each_exam_key => $each_exam_value) {
                        ?>
                        <th>
                           <?php echo $each_exam_value->exam; ?>
                        </th>
                        <?php
}
                    ?>
                        <th><?php echo $this->lang->line('consolidate') ?></th>
                     </thead>
                     <tbody>
                        <tr>
                           <td><?php echo $this->lang->line('marks_obtained'); ?></td>
                           <?php
$consolidate_get_total            = 0;
                    $consolidate_max_total            = 0;
                    $consolidate_get_total_percentage = 0;

                    if (!empty($exam_value->exam_result['exams'])) {
                        $consolidate_exam_result = "pass";
                        foreach ($exam_value->exam_result['exams'] as $each_exam_key => $each_exam_value) {
                            ?>
                           <td>
                              <?php
$consolidate_each = getCalculatedExam($exam_value->exam_result['exam_result'], $each_exam_value->id);

                            $exam_get_percentage = ($consolidate_each->get_marks * 100) / $consolidate_each->max_marks;

                            $consolidate_get_percentage_mark = getConsolidateRatio($exam_value->exam_result['exam_connection_list'], $each_exam_value->id, $consolidate_each->get_marks, $exam_get_percentage);
                            if ($consolidate_each->exam_status == "fail") {
                                $consolidate_exam_result = "fail";
                            }

                            echo two_digit_float($consolidate_get_percentage_mark['exam_consolidate_marks']) . " (" . $consolidate_get_percentage_mark['exam_weightage'] . "%)";

                            $consolidate_get_total_percentage += ($consolidate_get_percentage_mark['exam_consolidate_percentage']);

                            $consolidate_get_total = $consolidate_get_total + ($consolidate_get_percentage_mark['exam_consolidate_marks']);
                            $consolidate_max_total = $consolidate_max_total + ($consolidate_each->max_marks);

                            ?>
                           </td>
                           <?php
}
                    }
                    ?>
                           <td>
                              <?php

                    // $consolidate_percentage_grade = ($consolidate_get_total * 100) / $consolidate_max_total;

                    echo two_digit_float($consolidate_get_total_percentage) . " [" . findExamGrade($exam_grade, $exam_value->exam_type, $consolidate_get_total_percentage) . "]";

                    $consolidate_exam_result_percentage = $consolidate_get_total_percentage;
                    ?>
                           </td>
                        </tr>
                     </tbody>
                  </table>
                </div>
                  <?php
} elseif ($exam_value->exam_type == "basic_system" || $exam_value->exam_type == "average_passing") {
                    ?>
                  <table class="table table-striped ">
                     <thead>
                        <th><?php echo $this->lang->line('exam'); ?></th>
                        <?php
foreach ($exam_value->exam_result['exams'] as $each_exam_key => $each_exam_value) {
                        ?>
                        <th>
                           <?php echo $each_exam_value->exam; ?>
                        </th>
                        <?php
}
                    ?>
                        <th><?php echo $this->lang->line('consolidate'); ?></th>
                     </thead>
                     <tbody>
                        <tr>
                           <td><?php echo $this->lang->line('marks_obtained') ?></td>
                           <?php
$consolidate_get_total            = 0;
                    $consolidate_get_total_percentage = 0;
                    $consolidate_max_total            = 0;
                    if (!empty($exam_value->exam_result['exams'])) {
                        $consolidate_exam_result = "pass";
                        foreach ($exam_value->exam_result['exams'] as $each_exam_key => $each_exam_value) {

                            ?>
                           <td>
                              <?php
$consolidate_each = getCalculatedExam($exam_value->exam_result['exam_result'], $each_exam_value->id);

                            if ($consolidate_each->max_marks > 0) {
                                $exam_get_percentage = ($consolidate_each->get_marks * 100) / $consolidate_each->max_marks;
                            } else {
                                $exam_get_percentage = 0;
                            }
                            $consolidate_get_percentage_mark = getConsolidateRatio($exam_value->exam_result['exam_connection_list'], $each_exam_value->id, $consolidate_each->get_marks, $exam_get_percentage);

                            if ($exam_value->exam_type == "average_passing") {
                                if ($each_exam_value->passing_percentage > $exam_get_percentage) {
                                    $consolidate_exam_result = "fail";
                                }

                            } elseif ($consolidate_each->exam_status == "fail") {
                                $consolidate_exam_result = "fail";
                            }

                            echo two_digit_float($consolidate_get_percentage_mark['exam_consolidate_marks']) . " (" . $consolidate_get_percentage_mark['exam_weightage'] . "%)";
                            $consolidate_get_total += ($consolidate_get_percentage_mark['exam_consolidate_marks']);
                            $consolidate_get_total_percentage += ($consolidate_get_percentage_mark['exam_consolidate_percentage']);
                            $consolidate_max_total = $consolidate_max_total + ($consolidate_each->max_marks);

                            ?>
                           </td>
                           <?php
}
                    }
                    ?>
                           <td><?php

                    $consolidate_percentage_grade = ($consolidate_get_total * 100) / $consolidate_max_total;
                    echo two_digit_float($consolidate_get_total_percentage) . " [" . findExamGrade($exam_grade, $exam_value->exam_type, $consolidate_get_total_percentage) . "]";
                    $consolidate_exam_result_percentage = $consolidate_get_total_percentage;
                    ?></td>
                        </tr>
                     </tbody>
                  </table>
                  <?php
} elseif ($exam_value->exam_type == "gpa") {
                    ?>
                  <table class="table table-striped ">
                     <thead>
                        <th><?php echo $this->lang->line('exam') ?></th>
                        <?php
foreach ($exam_value->exam_result['exams'] as $each_exam_key => $each_exam_value) {
                        ?>
                        <th>
                           <?php echo $each_exam_value->exam; ?>
                        </th>
                        <?php
}
                    ?>
                        <th><?php echo $this->lang->line('consolidate'); ?></th>
                     </thead>
                     <tbody>
                        <tr>
                           <td><?php echo $this->lang->line('marks_obtained') ?></td>
                           <?php
$consolidate_get_total      = 0;
                    $consolidate_subjects_total = 0;

                    foreach ($exam_value->exam_result['exams'] as $each_exam_key => $each_exam_value) {

                        ?>
                           <td>
                              <?php
$consolidate_each = getCalculatedExamGradePoints($exam_value->exam_result['exam_result'], $each_exam_value->id, $exam_grade, $exam_value->exam_type);



                        $consolidate_exam_result = ($consolidate_each->return_quality_point / $consolidate_each->return_credit_hours);

                        $consolidate_each->total_points . "/" . $consolidate_each->total_exams . "=" . two_digit_float($consolidate_exam_result);

                        $consolidate_get_percentage_mark = getConsolidateRatio($exam_value->exam_result['exam_connection_list'], $each_exam_value->id, $consolidate_exam_result, 100);
                        echo two_digit_float($consolidate_get_percentage_mark['exam_consolidate_marks']) . " (" . $consolidate_get_percentage_mark['exam_weightage'] . "%)";

                        $consolidate_get_total      = $consolidate_get_total + ($consolidate_get_percentage_mark['exam_consolidate_marks']);

                        $consolidate_subjects_total = $consolidate_subjects_total + $consolidate_each->total_exams;
                        ?>
                           </td>
                           <?php
}
                    ?>
                           <td>
                    <?php

                    $consolidate_percentage_grade = ($consolidate_get_total * 100) / 10;
  $consolidate_exam_result_percentage = $consolidate_percentage_grade;
                    echo (two_digit_float($consolidate_get_total)) . " [" . findExamGrade($exam_grade, $exam_value->exam_type, $consolidate_percentage_grade) . "]";
                    ?>

                       </td>
                        </tr>
                     </tbody>
                  </table>
                  <?php
}

                if ($consolidate_exam_result) {

                    ?>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="bgtgray">
                          <?php 

if($exam_value->exam_type != "gpa"){
?>
 <div class="col-sm-3 pull ">
                              <div class="description-block">
                                 <h5 class="description-header"><?php echo $this->lang->line('result') ?> :
                                    <span class="description-text">
                                    <?php
if ($consolidate_exam_result == "pass") {
                        ?>
                                    <span class='label label-success' style="margin-right: 5px;">
                                    <?php
echo $this->lang->line('pass');
                        ?>
                                    </span>
                                    <?php
} else {
                        ?>
                                    <span class='label label-danger' style="margin-right: 5px;">
                                    <?php
echo $this->lang->line('fail');
                        ?>
                                    </span>
                                    <?php
}
                    ?>
                                    </span>
                                 </h5>
                              </div>
                           </div>
<?php
}
                           ?>
                          
                           <?php
if ($consolidate_exam_result_percentage) {

                        ?>
                           <div class="col-sm-3 border-right no-print">
                              <div class="description-block">
                                 <h5 class="description-header"><?php echo $this->lang->line('division'); ?> :<span class="description-text">
                                    <?php

                        echo findExamDivision($marks_division, $consolidate_exam_result_percentage);

                        ?>
                                    </span>
                                 </h5>
                              </div>
                           </div>
                           <?php
}
                }
                ?>
                        </div>
                     </div>
                  </div>
               </div>
               <?php
}
        }
    }
} else {?>
    <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
               <?php
}

?>
            </div>
         </div>
      </div>
   </section>
</div>
<?php
function findGradePoints($exam_grade, $exam_type, $percentage)
{
    foreach ($exam_grade as $exam_grade_key => $exam_grade_value) {
        if ($exam_grade_value['exam_key'] == $exam_type) {

            if (!empty($exam_grade_value['exam_grade_values'])) {
                foreach ($exam_grade_value['exam_grade_values'] as $grade_key => $grade_value) {
                    if ($grade_value->mark_from >= $percentage && $grade_value->mark_upto <= $percentage) {
                        return $grade_value->point;
                    }
                }
            }
        }
    }
    return 0;
}

function findExamDivision($marks_division, $percentage)
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

function findExamGrade($exam_grade, $exam_type, $percentage)
{
    foreach ($exam_grade as $exam_grade_key => $exam_grade_value) {
        if ($exam_grade_value['exam_key'] == $exam_type) {

            if (!empty($exam_grade_value['exam_grade_values'])) {
                foreach ($exam_grade_value['exam_grade_values'] as $grade_key => $grade_value) {
                    if ($grade_value->mark_from >= $percentage && $grade_value->mark_upto <= $percentage) {
                        return $grade_value->name;
                    }
                }
            }
        }
    }
    return "";
}

function getConsolidateRatio($exam_connection_list, $examid, $get_marks, $exam_get_percentage)
{
    if (!empty($exam_connection_list)) {
        foreach ($exam_connection_list as $exam_connection_key => $exam_connection_value) {

            if ($exam_connection_value->exam_group_class_batch_exams_id == $examid) {
                return ['exam_weightage'      => $exam_connection_value->exam_weightage,
                    'exam_consolidate_marks'      => ($get_marks * $exam_connection_value->exam_weightage) / 100,
                    'exam_consolidate_percentage' => ($exam_get_percentage * $exam_connection_value->exam_weightage) / 100];
            }
        }
    }
    return 0;
}

function getCalculatedExamGradePoints($array, $exam_id, $exam_grade, $exam_type)
{
    $object               = new stdClass();
    $return_total_points  = 0;
    $return_total_exams   = 0;
    $return_max_marks     = 0;
    $return_quality_point = 0;
    $return_get_marks     = 0;
    $return_credit_hours  = 0;
    if (!empty($array)) {

        if (!empty($array['exam_result_' . $exam_id])) {

            foreach ($array['exam_result_' . $exam_id] as $exam_key => $exam_value) {
                $return_total_exams++;
                $percentage_grade    = ($exam_value->get_marks * 100) / $exam_value->max_marks;
                $point               = findGradePoints($exam_grade, $exam_type, $percentage_grade);
                $return_total_points = $return_total_points + $point;
                $return_quality_point += ($point * $exam_value->credit_hours);
                $return_credit_hours += $exam_value->credit_hours;
                $return_max_marks += $exam_value->max_marks;
                $return_get_marks += $exam_value->get_marks;
            }
        }
    }

    $object->total_max_marks      = $return_max_marks;
    $object->total_get_marks      = $return_get_marks;
    $object->total_points         = $return_total_points;
    $object->total_exams          = $return_total_exams;
    $object->return_quality_point = $return_quality_point;
    $object->return_credit_hours  = $return_credit_hours;

    return $object;
}

function getCalculatedExam($array, $exam_id)
{
    $object              = new stdClass();
    $return_max_marks    = 0;
    $return_get_marks    = 0;
    $return_credit_hours = 0;
    $return_exam_status  = false;
    if (!empty($array)) {
        $return_exam_status = 'pass';

        if (!empty($array['exam_result_' . $exam_id])) {
            foreach ($array['exam_result_' . $exam_id] as $exam_key => $exam_value) {
                if ($exam_value->get_marks < $exam_value->min_marks || $exam_value->attendence != "present") {
                    $return_exam_status = "fail";
                }
                $return_max_marks    = $return_max_marks + ($exam_value->max_marks);
                $return_get_marks    = $return_get_marks + ($exam_value->get_marks);
                $return_credit_hours = $return_credit_hours + ($exam_value->credit_hours);
            }
        }
    }
    $object->credit_hours = $return_credit_hours;
    $object->get_marks    = $return_get_marks;
    $object->max_marks    = $return_max_marks;
    $object->exam_status  = $return_exam_status;
    return $object;
}
?>
<script type="text/javascript">
   var base_url = '<?php echo base_url() ?>';
   function printDiv(elem) {
       Popup(jQuery(elem).html());
   }

   function Popup(data)
   {
       var frame1 = $('<iframe />');
       frame1[0].name = "frame1";
       frame1.css({"position": "absolute", "top": "-1000000px"});
       $("body").append(frame1);
       var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
       frameDoc.document.open();
       frameDoc.document.write('<html>');
       frameDoc.document.write('<head>');
       frameDoc.document.write('<title></title>');
       frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
       frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
       frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
       frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
       frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
       frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
       frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
       frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
       frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
       frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
       frameDoc.document.write('</head>');
       frameDoc.document.write('<body>');
       frameDoc.document.write(data);
       frameDoc.document.write('</body>');
       frameDoc.document.write('</html>');
       frameDoc.document.close();
       setTimeout(function () {
           window.frames["frame1"].focus();
           window.frames["frame1"].print();
           frame1.remove();
       }, 500);

       return true;
   }

   $(document).ready(function () {
       $.extend($.fn.dataTable.defaults, {
           searching: false,
           ordering: false,
           paging: false,
           bSort: false,
           info: false
       });

       $("#feetable").DataTable({
           searching: false,
           ordering: false,
           paging: false,
           bSort: false,
           info: false,
           dom: "Bfrtip",
           buttons: [
               {
                   extend: 'copyHtml5',
                   text: '<i class="fa fa-files-o"></i>',
                   titleAttr: 'Copy',
                   title: $('.download_label').html(),
                   exportOptions: {
                       columns: ':visible'
                   }
               },
               {
                   extend: 'excelHtml5',
                   text: '<i class="fa fa-file-excel-o"></i>',
                   titleAttr: 'Excel',
                   title: $('.download_label').html(),
                   exportOptions: {
                       columns: ':visible'
                   }
               },
               {
                   extend: 'csvHtml5',
                   text: '<i class="fa fa-file-text-o"></i>',
                   titleAttr: 'CSV',
                   title: $('.download_label').html(),
                   exportOptions: {
                       columns: ':visible'
                   }
               },
               {
                   extend: 'pdfHtml5',
                   text: '<i class="fa fa-file-pdf-o"></i>',
                   titleAttr: 'PDF',
                   title: $('.download_label').html(),
                   exportOptions: {
                       columns: ':visible'

                   }
               },
               {
                   extend: 'print',
                   text: '<i class="fa fa-print"></i>',
                   titleAttr: 'Print',
                   title: $('.download_label').html(),
                   customize: function (win) {
                       $(win.document.body)
                               .css('font-size', '10pt');

                       $(win.document.body).find('table')
                               .addClass('compact')
                               .css('font-size', 'inherit');
                   },
                   exportOptions: {
                       columns: ':visible'
                   }
               },
               {
                   extend: 'colvis',
                   text: '<i class="fa fa-columns"></i>',
                   titleAttr: 'Columns',
                   title: $('.download_label').html(),
                   postfixButtons: ['colvisRestore']
               },
           ]
       });
   });

   $(document).ready(function () {
       $('.detail_popover').popover({
           placement: 'right',
           title: '',
           trigger: 'hover',
           container: 'body',
           html: true,
           content: function () {
               return $(this).closest('td').find('.fee_detail_popover').html();
           }
       });
   });

   $(document).ready(function () {
       $('table.display').DataTable();
   });

</script>

<script type="text/javascript">
   $(".myTransportFeeBtn").click(function () {
       $("span[id$='_error']").html("");
       $('#transport_amount').val("");
       $('#transport_amount_discount').val("0");
       $('#transport_amount_fine').val("0");
       var student_session_id = $(this).data("student-session-id");
       $('.transport_fees_title').html("<b><?php echo $this->lang->line('upload_documents') ?></b>");
       $('#transport_student_session_id').val(student_session_id);
       $('#myTransportFeesModal').modal({
           backdrop: 'static',
           keyboard: false,
           show: true
       });
   });

   document.getElementById("print").style.display = "block";

   function printDiv() {
   document.getElementById("print").style.display = "none";

   $('.bg-green').removeClass('label');
   $('.label-danger').removeClass('label');
   $('.label-success').removeClass('label');
   var divElements = document.getElementById('exam').innerHTML;
   var oldPage = document.body.innerHTML;
   document.body.innerHTML =
   "<html><head><title></title></head><body>" +
   divElements + "</body>";
   window.print();
   document.body.innerHTML = oldPage;

   location.reload(true);
   }

</script>