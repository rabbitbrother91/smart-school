<FORM action="https://pay.skrill.com" name="pay_now">
            <?php  
                 foreach ($form_fields as $key => $value) {
                     
                     echo ' <input name="'.$key.'" type="hidden" value="'.$value.'"></br>';
                 }
                ?>

            
            
            
            
         </FORM>
         <script type="text/javascript">
         	window.onload = function(){
  document.forms['pay_now'].submit();
}
         </script>