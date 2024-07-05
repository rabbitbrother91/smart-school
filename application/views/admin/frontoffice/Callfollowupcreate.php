<div class="content-wrapper" style="min-height: 348px;">  
    <section class="content-header">
        <h1>
            <i class="fa fa-ioxhost"></i> <?php echo $this->lang->line('front_office'); ?></h1>
    </section>
    <!-- Main content -->
    <?php $response = $this->customlib->getResponse(); ?>
    <!-- New Desgine start -->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Call --r</h3>
                    </div><!-- /.box-header -->
                    <form id="form1" action="<?php echo site_url('admin/callfollowup/addcall') ?>"   method="post" >
                        <div class="box-body">                           

                            <div class="form-group">
                                <label for="exampleInputEmail1">Responce  --r</label>

                                <select  name="responce" class="form-control">


                                    <option value="0"<?php if (set_value('responce') == '0') { ?> selected="" <?php } ?>><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    foreach ($response as $value) {
                                        ?>
                                        <option value="<?php echo $value ?>"<?php
                                        if (set_value('responce') == $value) {
                                            echo "selected = selected";
                                        }
                                        ?>><?php echo $value ?></option>

                                        <?php
                                        $count++;
                                    }
                                    ?>

                                </select>
                                <span class="text-danger"><?php echo form_error('responce'); ?></span>

                            </div>

                            <div class="form-group">
                                <label for="pwd">Mobile  --r</label>  
                                <input type="text" class="form-control" value="<?php echo set_value('mobile'); ?>" name="mobile">
                                <span class="text-danger"><?php echo form_error('mobile'); ?></span>
                            </div>

                            <div class="form-group">
                                <label for="pwd">Follow Up Date  --r</label>
                                <input id="date" name="date" placeholder="" type="text" class="form-control"  value="<?php echo set_value('date'); ?>" readonly="readonly" />
                                <span class="text-danger"><?php echo form_error('date'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="email">Name  --r</label> <input type="text" value="<?php echo set_value('name'); ?>" class="form-control" name="name">
                                <span class="text-danger"><?php echo form_error('name'); ?></span>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="pwd">Landline  --r</label>     <input type="text" class="form-control" value="<?php echo set_value('landline'); ?>"  name="landline">
                                    <span class="text-danger"><?php echo form_error('landline'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pwd">Responce  --r</label>
                                <textarea class="form-control" data-validate="maxlength[100]" name="responsedetails"><?php echo set_value('responsedetails'); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="email">Date Of Call  --r</label><input id="date_of_call" name="doc" placeholder="" type="text" class="form-control"  value="<?php echo set_value('doc'); ?>" readonly="readonly" />
                                <span class="text-danger"><?php echo form_error('doc'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for="pwd">Address  --r</label>
                                <textarea class="form-control" id="description"  name="address" rows="3"><?php echo set_value('address'); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="pwd">No Of Child  --r</label> 
                                <input type="number" class="form-control" min="1" value="<?php echo set_value('nochild'); ?>" name="nochild">
                                <span class="text-danger"><?php echo form_error('nochild'); ?></span>
                            </div>
                        </div><!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div><!--/.col (right) -->
            <!-- left column -->
            <div class="col-md-8">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> Call List --r</h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?>
                                        </th>
                                        <th>Mobile --r
                                        </th>
                                        <th>No of Child --r
                                        </th>
                                        <th>Date of Call --r</th>
                                        <th>Follow Up Date --r
                                        </th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($CallList)) {
                                        ?>

                                        <?php
                                    } else {
                                        foreach ($CallList as $key => $value) {
                                            ?>
                                            <tr>
                                                <td class="mailbox-name"><?php echo $value->name; ?></td>
                                                <td class="mailbox-name"><?php echo $value->mobile; ?></td>
                                                <td class="mailbox-name"><?php echo $value->no_of_child; ?> </td>
                                                <td class="mailbox-name"> <?php echo $value->date_of_call; ?></td>
                                                <td class="mailbox-name"> <?php echo $value->follow_up_date; ?></td>
                                                <td class="mailbox-date pull-right">

                                                    <a href="#" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('edit') ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');" data-original-title="<?php echo $this->lang->line('delete') ?>">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!-- new END -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $('#date').datepicker({        
            format: date_format,
            autoclose: true
        });
    });

    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $('#date_of_call').datepicker({            
            format: date_format,
            autoclose: true
        });
    });
</script>