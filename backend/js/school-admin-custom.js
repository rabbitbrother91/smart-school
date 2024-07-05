$(document).ready(function() {
    if ($('.purchasemodal').length <= 0 && chk_validate == "") {
        $("#activelicmodal").modal('show');
    }
    $(document).on('click', '.purchasemodal', function() {
        $("#activelicmodal").modal('show');
    })
});