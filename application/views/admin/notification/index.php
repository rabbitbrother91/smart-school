<style>
    /* Style the element that is used to open and close the accordion class */
    .accordion {
        color: #444;
        cursor: pointer;
        width: 100%;
        text-align: left;
        border: none;
        outline: none;
        transition: 0.4s;
    }

    /* Unicode character for "plus" sign (+) */
    .table.notetable tr:hover .accordion:after{opacity: 100;}
    .accordion:after {
        content: "\f106";
        font-size: 20px;
        color: #777;
        float: right;
        opacity: 0;
        margin-left: 5px;
        transition: all 0.3s;
        /*position: absolute;right: 30px;*/
        font-family:"FontAwesome";
    }

    /* Unicode character for "minus" sign (-) */
    .accordion.active:after {opacity: 100;}
    .accordion.active:after {
        content: "\f106";
        transform: rotate(180deg);
    }

    /* Style the element that is used for the panel class */
    .selected{background: #eff4f9;}
    div.panel {
        max-height: 0;
        overflow: hidden;
        transition: 0.4s ease-in-out;
        opacity: 0;
        background: transparent;
        box-shadow: none;
    }

    div.panel.show {
        opacity: 1;
        max-height: 500px;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function (event) {
        var acc = document.getElementsByClassName("accordion");
        var panel = document.getElementsByClassName('panel');

        for (var i = 0; i < acc.length; i++) {
            acc[i].onclick = function () {
                id = $(this).attr("id");
                updateStatus(id);
                var setClasses = !this.classList.contains('active');
                setClass(acc, 'active', 'remove');
                setClass(panel, 'show', 'remove');

                if (setClasses) {
                    this.classList.toggle("active");
                    this.nextElementSibling.classList.toggle("show");
                }
            }
        }

        function setClass(els, className, fnName) {
            for (var i = 0; i < els.length; i++) {
                els[i].classList[fnName](className);
            }
        }

    });

    function updateStatus(id) {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/systemnotification/updateStatus/',
            type: 'POST',
            data: {id: id},
            dataType: "json",
            success: function (res) {

            }
        })
    }
</script>

<script>
    /* Toggle between adding and removing the "active" and "show" classes when the user clicks on one of the "Section" buttons. The "active" class is used to add a background color to the current button when its belonging panel is open. The "show" class is used to open the specific accordion panel */
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].onclick = function () {
            this.classList.toggle("active");
            this.nextElementSibling.classList.toggle("show");
        };
    }
</script>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><i class="fa fa-bell-o"></i> Notifications</h3>
                    </div>
                    <div class="box-body">
                        <table class="notetable table table-border table-hover" width="100%">
                            <tr>
                                <th width="8%">Type</th>
                                <th width="70%">Subject</th>
                                <th  width="22%">Date</th>
                            </tr>
                            <?php
if (empty($notifications)) {
    ?>
                                <?php
} else {
    $count = 1;
    $color = "";
    foreach ($notifications as $result) {

        ?>
                                    <tr class="<?php echo $color ?>">
                                        <td>
                                            <div class="bellcircle"><i class="fa fa-bell-o"></i></div>
                                        </td>
                                        <td>
                                            <p class="accordion" id="<?php echo $result["id"] ?>"><b><?php echo $result['notification_title']; ?></b></p>

                                            <div class="panel">
                                                <p><?php echo $result['notification_desc']; ?>welcome</p>
                                            </div>
                                        </td>
                                        <td><?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($result['date'])); ?></td>
                                    </tr>
                                    <?php
$count++;
    }
}
?>
                        </table>

                        <ul class="pagination">
                            <li class="disabled"><span><i class="fa fa-angle-left"></i></span></li>
                            <li><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a rel="next" href="#"><i class="fa fa-angle-right"></i></a></li>
                        </ul>
                        <div class="pagination">
                            <?php echo $this->pagination->create_links(); ?>
                        </div>
                    </div>
                </div>
            </div><!--./row-->
    </section>
</div>

<script src="<?php echo base_url() ?>backend/js/Chart.bundle.js"></script>
<script src="<?php echo base_url() ?>backend/js/utils.js"></script>

<script type="text/javascript">

    $('table.notetable tr').on('click', function () {
        $('table.notetable tr').removeClass('selected');
        $(this).addClass('selected');
    });
    
    $(document).ready(function () {

        $(document).on('click', '.close_notice', function () {
            var data = $(this).data();
            $.ajax({
                type: "POST",
                url: base_url + "admin/notification/read",
                data: {'notice': data.noticeid},
                dataType: "json",
                success: function (data) {
                    if (data.status == "fail") {

                        errorMsg(data.msg);
                    } else {
                        successMsg(data.msg);
                    }
                }
            });
        });
    });
</script>
