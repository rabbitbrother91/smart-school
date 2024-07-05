<style type="text/css">
     @media print {
               .no-print {
                 visibility: hidden !important;
                  display:none !important;
               }
            }

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">            
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> <?php echo  $this->lang->line('content_share_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body">                       
                        <div class="table-responsive mailbox-messages overflow-visible-lg">
                                 <table class="table table-striped table-bordered table-hover content-list" data-export-title="<?php echo  $this->lang->line('content_share_list'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('send_to'); ?></th>
                                        <th><?php echo $this->lang->line('share_date'); ?></th>
                                        <th><?php echo $this->lang->line('valid_upto'); ?></th>
                                        <th><?php echo $this->lang->line('shared_by'); ?></th>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th class="pull-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) --> 
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<div id="linkModal" class="modal fade modalmark" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" autocomplete="off">×</button>
                <h4 class="modal-title"><?php echo $this->lang->line('link'); ?></h4>
            </div>
            <div class="modal-body">  
                         </div>
        </div>
    </div>
</div>

<div id="viewShareModal" class="modal fade modalmark" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" autocomplete="off">×</button>
                <h4 class="modal-title"><?php echo $this->lang->line('shared_contents'); ?></h4>
            </div>
            <div class="modal-body minheight260"> 

                <div class="modal_loader_div" style="display: none;"></div>

                <div class="modal-body-inner">
                    
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // Fill modal with content from link href
$("#viewShareModal").on("show.bs.modal", function(e) {
    var link = $(e.relatedTarget);
   let recordid=link.data('recordid');
     $.ajax({
                    url: baseurl+'admin/content/getsharedcontents',
                    type: "POST",
                    data: {"share_content_id" : recordid},
                    dataType: 'json',                   
                    beforeSend: function () {
                        $('#viewShareModal .modal-body .modal-body-inner').html(""); 
                        $('#viewShareModal .modal-body .modal_loader_div').css("display", "block"); 
                   
                    },
                    success: function (data)
                    {
                          $('#viewShareModal .modal-body .modal-body-inner').html(data.page); 
                          $('#viewShareModal .modal-body .modal_loader_div').fadeOut(400);
                    },
                    error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                 
                    },
                    complete: function () {
            
                    }
            });
});

    ( function ( $ ) {
    'use strict';
    $(document).ready(function () {

        $('#viewShareModal,#linkModal').modal({
            backdrop: 'static',
            keyboard: false,
            show:false
        });

        initDatatable('content-list','admin/content/getsharelist',[],[],100,
            [
                { "bSortable": true, "aTargets": [ -2 ] ,'sClass': 'dt-body-left', "width": "20%"},
                 { "bSortable": false, "aTargets": [ -1 ] ,'sClass': 'dt-body-right'}
            ]);
    });
} ( jQuery ) )

    $("#linkModal").on('hidden.bs.modal', function () {
    $('#linkModal .modal-body').html("");
});

$('#linkModal').on('show.bs.modal', function (event) {
    var filtre=$(event.relatedTarget).data();
    var link_model= $('<a/>', 
                    {
                        class:'share_link',
                    href:filtre.link,
                    target:"_blank"
                    }).text(filtre.link);

     var span =$('<span/>', 
                    {
                          class:"btn btn-xs btn-info",
                          onclick:"copylink()",
                          id:"basic-addon1",  
                         'data-toggle': 'tooltip',
                         "data-original-title":"<?php echo $this->lang->line('copy'); ?>"
                    }).append($('<i/>',{class:"fa fa-copy"

                    }));



 
var sss=$('<div/>').append(link_model).append(span);

    

    $('.modal-body',this).html(sss);
     
});

function copylink(){
  

    var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($('a.share_link').text()).select();
  document.execCommand("copy");
  $temp.remove();

}

</script>