<html lang="en">

<head>
  <title><?php echo $this->lang->line('timetable'); ?></title>

  <style type="text/css">
    @media print {
      body {
        line-height: 24px;
        font-family: 'Roboto', arial;
      }
      
      .col-md-1,
      .col-md-2,
      .col-md-3,
      .col-md-4,
      .col-md-5,
      .col-md-6,
      .col-md-7,
      .col-md-8,
      .col-md-9,
      .col-md-10,
      .col-md-11,
      .col-md-12 {
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

      .clear {
        clear: both;
      }

      .pb10 {
        padding-bottom: 10px;
      }

      .mb10 {
        margin-bottom: 10px !important;
      }

      .hrexam {
        margin-top: 5px;
        margin-bottom: 5px;
        border: 0;
        border-top: 1px solid #eee;
        width: 100%;
        clear: both;
      }

      .qulist_circle {
        margin: 0;
        padding: 0;
        list-style: none;
      }

      .qulist_circle li {
        display: block;
      }

      .qulist_circle li i {
        padding-right: 5px;
      }

      .font-weight-bold {
        font-weight: bold;
      }

      .text-center {
        text-align: center;
      }

      .section-box i {
        font-size: 18px;
        vertical-align: middle;
        padding-left: 2px;
      }
      .table{font-size:14px}
      .bolder{font-weight:bolder}
    }
  </style>

  <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/AdminLTE.min.css">
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-md-12 text">
        <h3 class="text-center bolder"><?php echo $this->lang->line('timetable'); ?></h3>
        <h4 class="bolds"><?php echo $staff['name'] . " " . $staff['surname'] . " (" . $staff['employee_id'] . ")"; ?></h4>
      </div>
    </div>

    <?php


    if (!empty($timetable)) {

      foreach ($timetable as $tm_key => $tm_value) {

    ?>

      <h5 class="bolder"><?php echo ($tm_key); ?></h5>
        <?php

        if (empty($timetable[$tm_key])) {
        ?>
          <table class="table table-hover table-bordered">
            <thead>
              <tr>
                <td width='40%' class="font-medium"><?php echo $this->lang->line('subject'); ?></td>
                <td width='25%' class="font-medium"><?php echo $this->lang->line('time'); ?></td>
                <td width='20%' class="font-medium"><?php echo $this->lang->line('class'); ?></td>
                <td width='15%' class="font-medium"><?php echo $this->lang->line('room_no'); ?></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan='4' class="text text-center"><?php echo $this->lang->line('no_record_found'); ?></td>

              </tr>
            </tbody>
          </table>
        <?php
        } else {
        ?>
          <table class="table table-hover table-bordered">
            <thead>
              <tr>
                <td width='40%' class="font-medium"><?php echo $this->lang->line('subject'); ?></td>
                <td width='25%' class="font-medium"><?php echo $this->lang->line('time'); ?></td>
                <td width='20%' class="font-medium"><?php echo $this->lang->line('class'); ?></td>
                <td width='15%' class="font-medium"><?php echo $this->lang->line('room_no'); ?></td>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($timetable[$tm_key] as $tm_k => $tm_kue) {
              ?>
                <tr>

                  <td class="text">
                    <?php
                    echo $tm_kue->subject_name;
                    if ($tm_kue->subject_code != '') {
                      echo " (" . $tm_kue->subject_code . ")";
                    }
                    ?>
                  </td>
                  <td class="text"><?php echo $tm_kue->time_from . " - " . $tm_kue->time_to; ?>
                  </td>
                  <td class="text"><?php echo $tm_kue->class . " (" . $tm_kue->section . ")"; ?></td>


                  <td class="text"><?php echo $tm_kue->room_no; ?></td>
                </tr>
              <?php

              }
              ?>

            </tbody>
          </table>
    <?php


        }
      }
    }
    ?>
  </div>
</body>

</html>