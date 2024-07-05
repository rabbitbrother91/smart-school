<style type="text/css">
.inpwidth40{width: 50px;height: 20px;}
</style>

<html lang="en">
    <head>
        <title><?php echo $this->lang->line('online_exam'); ?></title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <script src="<?php echo base_url() ?>backend/plugins/ckeditor/plugins/ckeditor_wiris/integration/WIRISplugins.js?viewer=image"></script>
    <style type="text/css">

@media print {
  body{line-height: 24px;font-family:'Roboto', arial;}
  .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
    float: left;
  }
  .col-md-12 {
    width: 100%;
  }
  .col-md-11 {
    width: 91.66666667%;
  }
  .col-md-10 {
    width: 83.33333333%;
  }
  .col-md-9 {
    width: 75%;
  }
  .col-md-8 {
    width: 66.66666667%;
  }
  .col-md-7 {
    width: 58.33333333%;
  }
  .col-md-6 {
    width: 50%;
  }
  .col-md-5 {
    width: 41.66666667%;
  }
  .col-md-4 {
    width: 33.33333333%;
  }
  .col-md-3 {
    width: 25%;
  }
  .col-md-2 {
    width: 16.66666667%;
  }
  .col-md-1 {
    width: 8.33333333%;
  }

  .clear{clear: both;}
  .row {
    margin-right: -5px;
    margin-left: -5px;
}
  .pb3{margin-bottom: 0px;
    line-height: normal;}
  .pb10{padding-bottom: 10px;}
  .pb15{padding-bottom: 15px;}
  .mb10{margin-bottom: 10px !important;}
  .hrexam {margin-top: 5px;margin-bottom: 5px;border: 0;border-top: 1px solid #eee; width: 100%; clear: both;}
  .qulist_circle{margin: 0; padding: 0; list-style: none;}
  .qulist_circle li{display: block;}
  .qulist_circle li i{padding-right: 5px;}
  .font-weight-bold{font-weight: bold;}
  .text-center{text-align: center;}
  .section-box i {
    font-size: 18px;
    vertical-align: middle; padding-left: 2px;
}
.pull-right{
  float: right;
}
.hrtop {
margin: 5px;
}
}

</style>
    </head>
    <body>
        <div class="container">
              <div class="row header ">
                            <div class="col-sm-12">

                                <img  src="<?php echo $this->media_storage->getImageURL('/uploads/print_headerfooter/online_exam/'. $onlineexamfooter['header_image']); ?>" style="height: 100px;width: 100%;">

                            </div>
                        </div>
             <div class="text-center">
                  <h3 class="titlefix"> <?php echo $this->lang->line('exam'); ?>: <?php echo $exam->exam; ?></h3>
               </div>
               <hr class="hrtop">
                <div class="row clear">
                               <div class="col-md-4">
                                 <div><span class="font-weight-bold"><?php echo $this->lang->line('passing') ?>  (%) : </span>
                                 <?php echo $exam->passing_percentage; ?></div>
                        </div>
                         <div class="col-md-4">
                              <div>
                                <span class="font-weight-bold"><?php echo $this->lang->line('total_questions') ?> : </span> <?php echo $exam->total_ques; ?></div>

                              </div>
<div class="col-md-4">
                                 <div class="pull-right"><span class="font-weight-bold"><?php echo $this->lang->line('duration') ?> : </span>
                                 <?php echo $exam->duration; ?></div>
                               </div>
                </div>
                <hr class="hrtop">
<?php

$dispaly_negative_marks = $exam->is_neg_marking;
if (!empty($questions)) {

    foreach ($questions as $question_key => $question_value) {

        ?>
<div class="mb10">
                       <div class="rltpaddleft">
                        <div class="row">
                          <div class="col-md-4">
                         <span class="font-weight-bold"> <?php echo $this->lang->line('q_id') ?> :  </span> <?php echo $question_value->id; ?>
                          </div>
                            <div class="col-md-8">
                             <span class="text text-danger pull-right">
                             <span class="font-weight-bold"> <?php echo $this->lang->line('marks') ?>: </span>(<?php echo $question_value->onlineexam_question_marks ?>)&nbsp;&nbsp;&nbsp;
                                <?php
if ($dispaly_negative_marks && $question_value->question_type != "descriptive") {

            ?>
                                 <span class="font-weight-bold"><?php echo $this->lang->line('negative_marks') ?>: </span>(<?php echo $question_value->neg_marks; ?>)&nbsp;&nbsp;&nbsp;

                                <?php
}
        ?>
   <span >
                                    <label><?php echo $this->lang->line('subject') ?>:</label>
                                        <?php echo $question_value->subject_name; ?> <?php if($question_value->subject_code){ echo ' ('.$question_value->subject_code.')'; } ?>
                                </span>
                       </span>
                          </div>
                        </div>

                         <?php echo $question_value->question; ?>

 <?php
if ($question_value->question_type != "descriptive") {

            if ($question_value->question_type == "singlechoice") {
                $question_total_option = 1;
                $question_display      = true;
                foreach ($questionOpt as $question_opt_key => $question_opt_value) {
                    if ($question_value->{$question_opt_key} == "") {
                        $question_display = false;
                    }
                    if ($question_display) {

                        ?>
                           <div class="text text-success">
                              <i class="fa fa-circle-o"></i> <?php echo $question_value->{$question_opt_key}; ?>
                           </div>
                           <?php
}
                    $question_total_option++;
                }
            } elseif ($question_value->question_type == "true_false") {
                foreach ($question_true_false as $question_true_false_key => $question_true_false_value) {

                    ?>
                           <div class="text text-success">
                              <i class="fa fa-circle-o"></i> <?php echo $question_true_false_value; ?>
                           </div>
                           <?php
}
            } elseif ($question_value->question_type == "multichoice") {
                $question_total_option = 1;
                $question_display      = true;

                foreach ($questionOpt as $question_opt_key => $question_opt_value) {
                    if ($question_value->{$question_opt_key} == "") {
                        $question_display = false;
                    }
                    if ($question_display) {

                        ?>
                           <div class="">
                              <i class="fa fa-square-o"></i> <?php echo $question_value->{$question_opt_key}; ?>
                           </div>
                           <?php
}
                    $question_total_option++;
                }
            }
        }

        if ($question_value->question_type == "descriptive") {

            ?>
                           <p>
                              <b><?php echo $this->lang->line('your_answer'); ?>: </b><br>
                              <hr/>
                              <hr/>
                           </p>

                           <?php
}
        ?>

                </div>
                <div class="hrexamtopbottom"></div>

            </div>
    <?php
}
    ?>

<div class="row header">
                            <div class="col-sm-12">
                                <?php echo $onlineexamfooter['footer_content']; ?>
                            </div>
                        </div>
                        <?php

} else {
    ?>
    <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
      <?php
}
?>
</div>
</body>
</html>