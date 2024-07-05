<?php
$theme = $this->customlib->getCurrentTheme();

if ($this->customlib->getRTL() != "") {
    if ($theme == "white") {
        ?>
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/bootstrap-rtl/css/bootstrap-rtl.min.css"/>
        <!-- Theme RTL style -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/white-rtl.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/AdminLTE-rtl.min.css" />

        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/skins/_all-skins-rtl.min.css" />

        <?php
} else {
        ?>
        <!-- Bootstrap 3.3.5 RTL -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/bootstrap-rtl/css/bootstrap-rtl.min.css"/>
        <!-- Theme RTL style -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/AdminLTE-rtl.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/ss-rtlmain.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/skins/_all-skins-rtl.min.css" />
        <?php
}
}

if ($theme == "white") {
    ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/themes/white/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/themes/white/ss-main.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/main.css">

    <?php
} elseif ($theme == "default") {
    ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/style-main.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/themes/default/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/themes/default/ss-main.css">

    <?php
} elseif ($theme == "red") {
    ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/style-main.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/themes/red/skins/skin-red.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/themes/red/ss-main-red.css">
    <?php
} elseif ($theme == "blue") {
    ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/style-main.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/themes/blue/skins/skin-darkblue.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/themes/blue/ss-main-darkblue.css">
    <?php
} elseif ($theme == "gray") {
    ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/style-main.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/themes/gray/skins/skin-light.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/themes/gray/ss-main-light.css">
    <?php
}