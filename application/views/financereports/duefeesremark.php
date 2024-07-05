<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$month_list= $this->customlib->getMonthDropdown($start_month);
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
                    <form action="<?php echo site_url('financereports/duefeesremark') ?>"  method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                                ?>
                                                <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                                <?php
                                                $count++;
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($section_list as $value) {
                                                ?>
                                                <option  <?php
                                                if ($value['section_id'] == $section_id) {
                                                    echo "selected";
                                                }
                                                ?> value="<?php echo $value['section_id']; ?>"><?php echo $value['section']; ?></option>
                                                    <?php
                                                }
                                                ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="resp">                                
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search') ?></button>   </div>
                    </form>
                    <div class="row">
                        <?php
                        if (isset($student_remain_fees)) {
                            ?>
                            <div class="" id="transfee">
                                <div class="box-header ptbnull">
                                    <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('balance_fees_report_with_remark'); ?> </h3>
                                </div>                              
                                <div class="box-body">
                                    <?php
                                    if (!empty($student_remain_fees)) {
                                        ?>
                                        
                                    <button type="button" class="btn btn-primary btn-sm pull-right print" id="load" data-class-id="<?php echo $class_id;?>"  data-section-id="<?php echo $section_id;?>" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please wait"><i class="fa fa-print"></i> <?php echo $this->lang->line('print') ?> </button>
                    <div class="clearfix"></div>

      <div class="table-responsive">
                                                   <table class="table table-striped table-bordered table-hover ">
                                    <thead>
                                        <tr>

                                          
                                            <th><?php echo $this->lang->line('student_name')."<br/>". "(".$this->lang->line('admission_no').")"; ?></th>
                                            <th><?php echo $this->lang->line('class'); ?></th>                                
                                            <th width="30%"><?php echo $this->lang->line('fees'); ?></th>                     
                                            <th class="text text-right"><?php echo $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th class="text text-right"><?php echo $this->lang->line('paid'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>         

                                            <th class="text text-right"><?php echo $this->lang->line('balance'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th ><?php echo $this->lang->line('guardian_phone'); ?></th>
                                          <th class="text text-right"><?php echo $this->lang->line('remark'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($student_remain_fees)) {
                                            ?>

                                            <?php 
                                        } else {
                                            $count = 1;
                                            foreach ($student_remain_fees as $student) {
                                                
                    $amount=0;
                    $amount_deposite=0;
                    $amount_discount=0;
                    $amount_fine=0;

                                                if(!empty($student['fees'])){
                                                           foreach ($student['fees'] as $fee_key => $fee_value) {
                                                          
                                                             $amount+=$fee_value['amount'];
                                                             $amount_deposite+=$fee_value['amount_deposite'];
                                                             $amount_discount+=$fee_value['amount_discount'];
                                                             $amount_fine+=$fee_value['amount_fine'];
                                                            }                                                        
                                                        }                                              
                                                ?>
                                                <tr>
                                                    <td><?php echo $this->customlib->getFullName($student['firstname'],$student['middlename'],$student['lastname'],$sch_setting->middlename,$sch_setting->lastname) ."<br/>"."(".$student['admission_no'].")";?></td>                                         
                                                    <td><?php echo $student['class']."-".$student['section']; ?></td>                             
                                                    <td>
                                                        <?php   
                                                        if(!empty($student['fees'])){


                                                        echo implode(', <br/>', array_map(
                                                         function ($v) {
                                                           
                                                           return ($v['is_system']) ? $this->lang->line($v['fee_group']) . ' (' . $this->lang->line($v['fee_type']) . ')' :$v['fee_group'] . ' (' . $v['fee_type'] . ' : ' . $v['fee_code'] . ')';
                                                                      },
                                                             $student['fees']));
                                                        }                                                       
                                                       
                                                    ?>
                                                    </td>
                                                    <td class="text text-right"><?php echo amountFormat($amount); ?></td>
                                                    <td class="text text-right"><?php echo amountFormat($amount_deposite+$amount_discount); ?></td>                                                    
                                                    <td class="text text-right"><?php
                                            echo amountFormat(($amount - ($amount_deposite + $amount_discount)));
                                                ?></td> 
                                                  <td ><?php
                                            echo $student['guardian_phone'];
                                                ?></td>
                                                  <td class="text text-right">
                                                      <div style="height: 100px; overflow:hidden;">
   
  </div>
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
                                        <?php
                                     
                                    } else {
                                        ?>
                                        <div class="alert alert-info">
                                           <?php echo $this->lang->line('no_record_found') ; ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>                            
                            </div>                 
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id', 0) ?>';
        getSectionByClass(class_id, section_id);


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

    $(document).on('change', '#class_id', function (e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });

    function getSectionByClass(class_id, section_id) {

        if (class_id != "") {
            $('#section_id').html("");
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
    
   $(document).on('click', '.print', function (e) {
   
                var $this = $(this);           
                var class_id=$this.data('classId');
                var section_id=$this.data('sectionId');
  $.ajax({
            type: "POST",
            url: base_url+'financereports/printduefeesremark',
            dataType: 'JSON',
            data: {'class_id':class_id,'section_id':section_id}, // serializes the form's elements.
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (response) {
                Popup(response.page);
            },
            error: function (xhr) { // if error occured

                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

            },
            complete: function () {
                $this.button('reset');
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.

        });
        
    function Popup(data, winload = false)
    {
        var frame1 = $('<iframe />').attr("id", "printDiv");
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
        document.getElementById('printDiv').contentWindow.focus();
        document.getElementById('printDiv').contentWindow.print();
        $("#printDiv", top.document).remove();
            // frame1.remove();
            if (winload) {
                window.location.reload(true);
            }
        }, 500);

        return true;
    }  

</script>