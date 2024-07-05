<FORM action="https://payments.ipayafrica.com/v3/ke" name="pay_now">
            <?php  
                 foreach ($fields as $key => $value) {
                     
                     echo ' <input name="'.$key.'" type="hidden" value="'.$value.'"></br>';
                 }
                ?>

            
            <INPUT name="hsh" type="hidden" value="<?php echo $generated_hash ?>">
            
            
         </FORM>
         <script type="text/javascript">
         	window.onload = function(){
  document.forms['pay_now'].submit();
}
         </script>