<?php
$admin_session = $this->session->userdata('admin');
$currency=$admin_session['currency'];
$currencies=get_currency_list();
foreach ($currencies as $currencie_key => $currencie_value) {
  
    ?>
    <option data-content='<?php echo $currencie_value->short_name." (".$currencie_value->symbol.")"; ?>' value="<?php echo $currencie_value->id; ?>" <?php
    if ( $currencie_value->id == $currency) {
        echo "Selected";
    }
    ?>></option>
    <?php
}
?>