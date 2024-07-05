<div class="content-wrapper" style="min-height: 946px;"> 
    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> <?php echo $this->lang->line('academics'); ?> <small><?php echo $this->lang->line('student_fees1'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">          
            <div class="col-md-3">              
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url() . $teacher['image'] ?>" alt="User profile picture">
                        <h3 class="profile-username text-center"><?php echo $teacher['name'] ?></h3>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b><?php echo $this->lang->line('gender'); ?></b> <a class="pull-right text-aqua"><?php echo $teacher['sex'] ?></a>
                            </li>
                            <li class="list-group-item">
                                <b><?php echo $this->lang->line('date_of_birth'); ?></b> <a class="pull-right text-aqua"><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($teacher['dob'])); ?></a>
                            </li>
                            <li class="list-group-item">
                                <b><?php echo $this->lang->line('phone'); ?></b> <a class="pull-right text-aqua"><?php echo $teacher['phone'] ?></a>
                            </li>
                            <li class="list-group-item">
                                <b><?php echo $this->lang->line('email'); ?></b> <a class="pull-right text-aqua"><?php echo $teacher['email'] ?></a>
                            </li>
                            <li class="list-group-item">
                                <b><?php echo $this->lang->line('address'); ?></b> <a class="pull-right text-aqua"><?php echo $teacher['address'] ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">              
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('teacher_subject'); ?></h3>
                        <div class="box-tools pull-right">
                            <a href="#"  class="schedule_modal text-green pull-right" data-toggle="tooltip" title="<?php echo $this->lang->line('login_detail'); ?>"><i class="fa fa-key"></i>
                                <?php echo $this->lang->line('login_details'); ?>
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls"> 
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('teacher_subject'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">                          
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('subject'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>                                     
                                    <?php
                                    $count = 1;
                                    foreach ($teachersubject as $subject) {
                                        ?>
                                        <tr>
                                            <td class="mailbox-name"><?php echo $subject->class . "(" . $subject->section . ")" ?></td>
                                            <td class="mailbox-name text-right"> <?php echo $subject->name; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="">
                        <div class="mailbox-controls"> 
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </section>
</div>

<script type="text/javascript">
    $(document).on('click', '.schedule_modal', function () {
        $('.modal-title').html("");
        $('.modal-title').html("<?php echo $this->lang->line('login_details'); ?>");
        var base_url = '<?php echo base_url() ?>';
        var teacher_id = '<?php echo $teacher["id"] ?>';
        var teacher_name = '<?php echo $teacher["name"] ?>';
        $.ajax({
            type: "post",
            url: base_url + "admin/teacher/getlogindetail",
            data: {'teacher_id': teacher_id},
            dataType: "json",
            success: function (response) {
                var data = "";
                data += '<div class="table-responsive">';
                data += '<p class="lead text text-center">' + teacher_name + '</p>';
                data += '<table class="table table-hover">';
                data += '<thead>';
                data += '<tr>';
                data += '<th>' + "<?php echo $this->lang->line('user_type'); ?>" + '</th>';
                data += '<th class="text text-center">' + "<?php echo $this->lang->line('username'); ?>" + '</th>';
                data += '<th class="text text-center">' + "<?php echo $this->lang->line('password'); ?>" + '</th>';
                data += '</tr>';
                data += '</thead>';
                data += '<tbody>';
                $.each(response, function (i, obj)
                {
                    console.log(obj);
                    data += '<tr>';
                    data += '<td><b>' + firstToUpperCase(obj.role) + '</b></td>';
                    data += '<td class="text text-center">' + obj.username + '</td> ';
                    data += '<td class="text text-center">' + obj.password + '</td> ';
                    data += '</tr>';
                });
                data += '</tbody>';
                data += '</table>';
                data += '<b class="lead text text-danger" style="font-size:14px;"> ' + "<?php echo $this->lang->line('login_url'); ?>" + ': ' + base_url + 'site/userlogin</b>';
                data += '</div>  ';
                $('.modal-body').html(data);
                $("#scheduleModal").modal('show');
            }
        });
    });

    function firstToUpperCase(str) {
        return str.substr(0, 1).toUpperCase() + str.substr(1);
    }
</script>

<div id="scheduleModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>