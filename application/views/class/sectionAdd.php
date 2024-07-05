<div class="content-wrapper"> 
    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> <?php echo $this->lang->line('academics'); ?> <small><?php echo $this->lang->line('student_fees1'); ?></small>        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">           
            <div class="col-md-4">       
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('add') . " " . $title . " " . $this->lang->line('section'); ?></h3>
                    </div>  
                    <form action="<?php echo site_url('classes/addsection/' . $id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                        <div class="box-body">

                            <?php 
                                if ($this->session->flashdata('msg')) {
                                    echo $this->session->flashdata('msg') 
                                    $this->session->unset_userdata('msg');
                                }
                            ?> 

                            <?php echo $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="id" value="<?php echo set_value('id', $id); ?>" >
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label>
                                <select autofocus="" id="section_id" name="section_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    foreach ($sectionlist as $section) {
                                        ?>
                                        <option value="<?php echo $section['id'] ?>" <?php if (set_value('section_id') == $section['id']) echo "selected=selected" ?>><?php echo $section['section'] ?></option>
                                        <?php
                                        $count++;
                                    }
                                    ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-8">              
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $title_list . " " . $this->lang->line('section_list') ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">                            
                            <div class="pull-right">
                            </div>
                        </div>
                        <div class="table-responsive mailbox-messages overflow-visible">
                            <table class="table table-hover table-striped">
                                <thead>
                                <th><?php echo $this->lang->line('section'); ?></th>
                                <th class="pull-right"><?php echo $this->lang->line('action'); ?></th>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($classSectionlist)) {
                                        ?>
                                        <tr>
                                            <td colspan="2" class="text-danger text-center">No Record Found.</td>

                                        </tr>
                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($classSectionlist as $classSection) {
                                            ?>
                                            <tr>
                                                <td class="mailbox-name"><a href="#"> <?php echo $classSection['section'] ?></a></td>
                                                <td class="mailbox-date pull-right">
                                                    <a href="<?php echo base_url(); ?>classes/deletesection/<?php echo $classSection['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');" >
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                            $count++;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="mailbox-controls">  
                            <div class="pull-right">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">          
            <div class="col-md-12">
            </div>
        </div> 
    </section>
</div>
