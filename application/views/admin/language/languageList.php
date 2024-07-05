<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-language"></i> <?php echo $this->lang->line('language_list'); ?></h3>

                        <div class="box-tools pull-right">
                            <div class="box-tools pull-right">
                                <a href="<?php echo base_url(); ?>admin/language/create" class="btn btn-primary btn-sm" data-toggle="tooltip" >
                                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?>
                                </a>
                            </div>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="alert alert-warning text-break">
                            <?php echo $this->lang->line('to_change_language_key_phrases_go_your_language_directory'); ?>
                        </div>
                        <?php if ($this->session->flashdata('msg')) {
    ?>
                            <?php echo $this->session->flashdata('msg');
    $this->session->unset_userdata('msg'); ?>
                        <?php }?>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped table-switch">
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo $this->lang->line('language'); ?></th>
                                        <th><?php echo $this->lang->line('short_code'); ?></th>
                                        <th><?php echo $this->lang->line('country_code'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('active'); ?></th>
                                        <th><?php echo $this->lang->line('is_rtl'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                    <tbody id="result_data">
                                    <?php $this->load->view('admin/language/languageResult');?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <div class="mailbox-controls">
                        </div>
                    </div>
                </div>
            </div>
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var onload=  $('#languageSwitcher').val();   
        $(document).on('click', '.chk', function () {
            var checked = $(this).is(':checked');
            var rowid = $(this).data('rowid');
            var role = $(this).data('role');
            var confirm_msg='<?php echo $this->lang->line('status_changed') ?>';
            if (checked) {
                if (!confirm(confirm_msg)) {
                    $(this).removeAttr('checked');
                } else {
                    var status = "yes";
                    if(role=='2'){
                        changeStatusselect(rowid);
                    }else{
                        changeStatusunselect(rowid);
                    }
                }
            } else if (!confirm(confirm_msg)) {
                $(this).prop("checked", true);
            } else {
                var status = "no";
                if(role=='2'){
                        changeStatusselect(rowid);
                    }else{
                        changeStatusunselect(rowid);
                    }
            }
        });
    });

    function changeStatusselect(rowid) {
        var base_url = '<?php echo base_url() ?>';

        $.ajax({
            type: "POST",
            url: base_url + "admin/language/select_language/"+rowid,
            data: {},
            success: function (data) {
                successMsg("<?php echo $this->lang->line('status_change_successfully') ?>");
                $('#languageSwitcher').html(data);
               window.location.reload('true');
            }
        });
    }

    function rtl(id){
        var rtl_val ='#rtl_'+id;

        if ($(rtl_val).is(":checked")){
           status = '1';
        }else{
           status = '0';
        }
        if (confirm("<?php echo $this->lang->line('are_you_sure'); ?>")) {

            $.ajax({
            type: "POST",
            url: '<?php echo base_url() ?>admin/language/rtl',
            data: {'status':status,'id':id},
            dataType: "json",
            success: function (data) {
                if(data.status==1){
                    window.location.reload('true');
                }else{
                    alert("<?php echo $this->lang->line('something_went_wrong'); ?>");
                }
            }
            });
        }else{
            window.location.reload('true');
            // alert("<?php echo $this->lang->line('something_went_wrong'); ?>");
        }
    }

    function changeStatusunselect(rowid) {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            type: "POST",
            url: base_url + "admin/language/unselect_language/"+rowid,
            data: {},
            success: function (data) {
               successMsg("<?php echo $this->lang->line('status_change_successfully') ?>");
               window.location.reload('true');
            }
        });
    }

function load(){
    $.ajax({
        type: "POST",
        url: '<?php echo base_url() ?>admin/language/onloadlanguage',
        data: {},       
        success: function (data) {
           window.location.reload('true');
        }
    });
}

    function defoult(id){
        $.ajax({
        type: "POST",
        url: '<?php echo base_url() ?>admin/language/defoult_language/'+id,
        data: {},      
        success: function (data) {
           window.location.reload('true');
        }
        });
    }

    function delete_language(id){
        alert(id);
    }

    $(".country_code").keyup(function() {
        var languageid = $(this).attr('data-id');
        var countrycode = $('#country_code'+languageid).val();
            $.ajax({
               url:'<?php echo base_url(); ?>admin/language/editcountrycode',
               type:'post',
               data:{languageid:languageid,countrycode:countrycode},
               success:function(response){

               }
            });
    });
</script>