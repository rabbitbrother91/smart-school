<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">
    /*REQUIRED*/
    .carousel-row {
        margin-bottom: 10px;
    }
    .slide-row {
        padding: 0;
        background-color: #ffffff;
        min-height: 150px;
        border: 1px solid #e7e7e7;
        overflow: hidden;
        height: auto;
        position: relative;
    }
    .slide-carousel {
        width: 20%;
        float: left;
        display: inline-block;
    }
    .slide-carousel .carousel-indicators {
        margin-bottom: 0;
        bottom: 0;
        background: rgba(0, 0, 0, .5);
    }
    .slide-carousel .carousel-indicators li {
        border-radius: 0;
        width: 20px;
        height: 6px;
    }
    .slide-carousel .carousel-indicators .active {
        margin: 1px;
    }
    .slide-content {
        position: absolute;
        top: 0;
        left: 20%;
        display: block;
        float: left;
        width: 80%;
        max-height: 76%;
        padding: 1.5% 2% 2% 2%;
        overflow-y: auto;
    }
    .slide-content h4 {
        margin-bottom: 3px;
        margin-top: 0;
    }
    .slide-footer {
        position: absolute;
        bottom: 0;
        left: 20%;
        width: 78%;
        height: 20%;
        margin: 1%;
    }
    /* Scrollbars */
    .slide-content::-webkit-scrollbar {
        width: 5px;
    }
    .slide-content::-webkit-scrollbar-thumb:vertical {
        margin: 5px;
        background-color: #999;
        -webkit-border-radius: 5px;
    }
    .slide-content::-webkit-scrollbar-button:start:decrement,
    .slide-content::-webkit-scrollbar-button:end:increment {
        height: 5px;
        display: block;
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-bus"></i> <?php //echo $this->lang->line('transport'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('reports/_online_examinations');?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form role="form" action="<?php echo site_url('report/onlineexamrank') ?>" method="POST" class="">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <?php if ($this->session->flashdata('msg')) {?>
                                        <?php 
                                            echo $this->session->flashdata('msg');
                                            $this->session->unset_userdata('msg');
                                        ?>
                                    <?php }?>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('exam') ?><small class="req"> *</small></label>
                                        <select  id="exam_id" name="exam_id" class="form-control select2"  >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($examList as $exam_key => $exam_value) {
    ?>
                                                <option value="<?php echo $exam_value->id; ?>"<?php
if (set_value('exam_id') == $exam_value->id) {
        echo "selected=selected";
    }
    ?>><?php echo $exam_value->exam; ?></option>
                                                        <?php
$count++;
}
?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('exam_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?></label>
                                        <select  id="class_id" name="class_id" class="form-control"  >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($classlist as $class) {
    ?>
                                                <option value="<?php echo $class['id'] ?>"<?php
if (set_value('class_id') == $class['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $class['class'] ?></option>
                                                        <?php
$count++;
}
?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('section'); ?></label>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""   ><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="submit" name="action" value ="search" class="btn btn-primary pull-right btn-sm"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

<!-- //=============== -->
<?php
if (isset($student_data)) {
    ?>
 <div class="">
                        <div class="box-header ptbnull"></div>
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo $this->lang->line('exam_rank_report'); ?></h3>
                        </div>
                        <div class="box-body table-responsive">
                            <?php
if(!empty($exam)){
    if (!$exam->is_rank_generated) {
        ?>

<div class="alert alert-info">
  <?php echo $this->lang->line('exam_rank_not_generated'); ?>
</div>
    <?php
}
}
    ?>
                            <div class="download_label"><?php echo $this->lang->line('exam_rank_report').' '.
    $this->customlib->get_postmessage();
    ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('rank') ?></th>
                                        <th><?php echo $this->lang->line('admission_no') ?></th>
                                        <th><?php echo $this->lang->line('student') ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <?php
                                            if ($sch_setting->father_name) {
                                        ?>
                                        <th><?php echo $this->lang->line('father_name'); ?></th>
                                        <?php 
                                            }
                                        ?>
                                        <th><?php echo $this->lang->line('exam_submitted')?></th>
                                            <th><?php echo $this->lang->line('total_questions') ?></th>
                                            <th> <?php echo $this->lang->line('descriptive'); ?></th>
                                            <th><?php echo $this->lang->line('correct_answer') ?></th>
                                            <th><?php echo $this->lang->line('wrong_answer') ?></th>
                                            <th><?php echo $this->lang->line('not_attempted'); ?></th>
                                            <th><?php echo $this->lang->line('total_exam_marks') ?></th>
                                            <th><?php echo $this->lang->line('total_negative_marks') ?></th>
                                            <th><?php echo $this->lang->line('total_scored_marks') ?></th>
                                            <th><?php echo $this->lang->line('score'); ?> (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     <?php
if (empty($student_data)) {
        ?>
            <tr>
                <td colspan="7" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
            </tr>
            <?php
} else {
        $count = 1;

        $display_negative_marks = $exam->is_neg_marking;
        foreach ($student_data as $student_key => $student) {
            //====================
            $correct_ans            = 0;
            $wrong_ans              = 0;
            $not_attempted          = 0;
            $total_question         = 0;
            $exam_total_scored      = 0;
            $exam_total_marks       = 0;
            $exam_total_neg_marks   = 0;
            $exam_total_descriptive = 0;
            if (!empty($student['questions_results'])) {
                $total_question = count($student['questions_results']);

                foreach ($student['questions_results'] as $result_key => $question_value) {
                    $total_marks_json  = getMarks($question_value);
                    $total_marks_array = (json_decode($total_marks_json));
                    $exam_total_marks  = $exam_total_marks + $total_marks_array->get_marks;
                    $exam_total_scored = $exam_total_scored + $total_marks_array->scr_marks;
                    if ($question_value->question_type == "descriptive") {
                        $exam_total_descriptive++;
                    }
                    if ($question_value->select_option != null) {
                        if ($question_value->question_type == "singlechoice" || $question_value->question_type == "true_false") {
                            if ($question_value->select_option == $question_value->correct) {
                                $correct_ans++;
                            } else {
                                $exam_total_neg_marks = $exam_total_neg_marks + $question_value->neg_marks;
                                $wrong_ans++;
                            }
                        } elseif ($question_value->question_type == "multichoice") {

                            if (array_equal(json_decode($question_value->correct), json_decode($question_value->select_option))) {
                                $correct_ans++;
                            } else {
                                $exam_total_neg_marks = $exam_total_neg_marks + $question_value->neg_marks;
                                $wrong_ans++;
                            }

                        }
                    } else {
                        $not_attempted++;
                        $exam_total_neg_marks = $exam_total_neg_marks + $question_value->neg_marks;
                    }
                }
                if (!$display_negative_marks) {
                    $exam_total_neg_marks = 0;
                }
            }

            //=====================

            ?>
                <tr>
                    <td class="pull-right"><?php echo $student['exam_rank']; ?></td>
                    <td><?php echo $student['admission_no']; ?></td>
                    <td><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></td>
                    <td>
                        <?php echo $student['class'] . " (" . $student['section'] . ")"; ?>

                        </td>
                        <?php
if ($sch_setting->father_name) {
                ?>
                        <td><?php echo $student['father_name']; ?></td>
                    <?php
}
            ?>
                      <td  class="text text-center"><?php

            if ($student['is_attempted']) {
                ?>
<i class="fa fa-check-square-o"></i>
<?php
} else {
                ?>
<i class="fa fa-remove"></i>
<?php
}

            ?></td>
                                <td class="text text-center"><?php echo $total_question; ?></td>
                                <td class="text text-center"><?php echo $exam_total_descriptive; ?></td>
                                <td class="text text-center"><?php echo $correct_ans; ?></td>
                                <td class="text text-center"><?php echo $wrong_ans; ?></td>
                                <td class="text text-center"><?php echo $not_attempted; ?></td>
                                <td class="text text-center"><?php echo $exam_total_marks; ?></td>
                                <td class="text text-center"><?php echo $exam_total_neg_marks; ?></td>
                                <td class="text text-center"><?php echo $exam_total_scored - $exam_total_neg_marks; ?></td>
                                <td class="text text-center">
                                    <?php

            echo ($exam_total_marks === 0) ? 0 : number_format(((($exam_total_scored - $exam_total_neg_marks) * 100) / $exam_total_marks), 2, '.', '');
            ?>

                        </td>
                </tr>
                <?php
}
        $count++;
    }
    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

    <?php
}

?>

                    <!-- //=========== -->
                </div>
            </div>
        </div>
    </div>
</section>
</div>

<script>
<?php
if ($search_type == 'period') {
    ?>
        $(document).ready(function () {
            showdate('period');
        });
    <?php
}
?>
</script>

<script type="text/javascript">
    $(document).ready(function () {
  $('.select2').select2();
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id', 0) ?>';
        var hostel_id = $('#hostel_id').val();
        var hostel_room_id = '<?php echo set_value('hostel_room_id', 0) ?>';

        getSectionByClass(class_id, section_id);
    });

    $(document).on('change', '#class_id', function (e) {
        $('#section_id').html("<option><?php echo $this->lang->line('select'); ?></option>");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });
    
    function getSectionByClass(class_id, section_id) {

        if (class_id != "") {
            $('#section_id').html("<?php echo $this->lang->line('select'); ?>");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }
</script>

<?php
function array_equal($a, $b)
{
    return (
        is_array($a) && is_array($b) && count($a) == count($b) && array_diff($a, $b) === array_diff($b, $a)
    );
}

function getMarks($question)
{

    if ($question->select_option != null) {

        if ($question->question_type == "singlechoice" || $question->question_type == "true_false") {

            if ($question->correct == $question->select_option) {
                return json_encode(array('get_marks' => $question->marks, 'scr_marks' => $question->marks));
            }

        } elseif ($question->question_type == "descriptive") {

            return json_encode(array('get_marks' => $question->marks, 'scr_marks' => $question->score_marks));

        } elseif ($question->question_type == "multichoice") {
            $cr_ans  = json_decode($question->correct);
            $sel_ans = json_decode($question->select_option);
            if (array_equal($cr_ans, $sel_ans)) {
                return json_encode(array('get_marks' => $question->marks, 'scr_marks' => $question->marks));
            }

        }
    }

    return json_encode(array('get_marks' => $question->marks, 'scr_marks' => 0));
}
?>