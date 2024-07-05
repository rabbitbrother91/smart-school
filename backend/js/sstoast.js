
 $(document).ready(function() {

     toastr.options = {
         "closeButton": true, // true/false
         "debug": false, // true/false
         "newestOnTop": false, // true/false
         "progressBar": false, // true/false
         "positionClass": "toast-top-right", // toast-top-right / toast-top-left / 
         "preventDuplicates": false,
         "onclick": null,
         "showDuration": "300", // in milliseconds
         "hideDuration": "1000", // in milliseconds
         "timeOut": "5000", // in milliseconds
         "extendedTimeOut": "1000", // in milliseconds
         "showEasing": "swing",
         "hideEasing": "linear",
         "showMethod": "fadeIn",
         "hideMethod": "fadeOut"
     }
     //=============Sticky header==============

 });

 function successMsg(msg) {
     toastr.success(msg);
 }

 function errorMsg(msg) {
     toastr.error(msg);
 }

 function infoMsg(msg) {
     toastr.info(msg);
 }

 function warningMsg(msg) {
     toastr.warning(msg);
 }
 // header afix//