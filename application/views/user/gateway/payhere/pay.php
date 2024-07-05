<form method="post" name="redirect" action="https://sandbox.payhere.lk/pay/checkout">   
    <?php 
    
    foreach ($htmlform as $key => $value) {
?>
    <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
<?php
    }
    ?>   
</form>  
<script language='javascript'>document.redirect.submit();</script>