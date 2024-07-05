<?php
$error = false;
if (phpversion() < "8.1") {
    $error = true;
    $requirement1 = "<span class='label label-warning'>Your PHP version is " . phpversion() . "</span>";
} else {
    $requirement1 = "<span class='label label-success'>v." . phpversion() . "</span>";
}
if (!extension_loaded('mysqli')) {
    $error = true;
    $requirement3 = "<span class='label label-danger'>Not enabled</span>";
} else {
    $requirement3 = "<span class='label label-success'>Enabled</span>";
}
if (!extension_loaded('gd')) {
    $error = true;
    $requirement6 = "<span class='label label-danger'>Not enabled</span>";
} else {
    $requirement6 = "<span class='label label-success'>Enabled</span>";
}
if (!extension_loaded('curl')) {
    $error = true;
    $requirement8 = "<span class='label label-warning'>Not enabled</span>";
} else {
    $requirement8 = "<span class='label label-success'>Enabled</span>";
}
if (!extension_loaded('mbstring')) {
    $error = true;
    $requirement5 = "<span class='label label-danger'>Not enabled</span>";
} else {
    $requirement5 = "<span class='label label-success'>Enabled</span>";
}
if (ini_get('allow_url_fopen') != "1") {
    $error = true;
    $requirement9 = "<span class='label label-danger'>Allow_url_fopen is not enabled!</span>";
} else {
    $requirement9 = "<span class='label label-success'>Enabled</span>";
}
if (!extension_loaded('zip')) {
    $error = true;
    $requirement12 = "<span class='label label-danger'>Zip Extension is not enabled</span>";
} else {
    $requirement12 = "<span class='label label-success'>Enabled</span>";
}
if (!is_really_writable($config_path)) {
    $error = true;
    $requirement13 = "<span class='label label-danger'>No (Make application/config/config.php writable) - Permissions 755</span>";
} else {
    $requirement13 = "<span class='label label-success'>Ok</span>";
}
if (!is_really_writable(APPPATH . 'config/database.php')) {
    $error = true;
    $requirement14 = "<span class='label label-danger'>No (Make application/config/database.php writable) - Permissions - 755</span>";
} else {
    $requirement14 = "<span class='label label-success'>Ok</span>";
}
if (!is_really_writable(FCPATH . 'temp')) {
    $error = true;
    $requirement15 = "<span class='label label-danger'>No (Make temp folder writable) - Permissions 755</span>";
} else {
    $requirement15 = "<span class='label label-success'>Ok</span>";
}
?>

<?php
if ($error == true) {
    echo '<div class="text-center alert alert-danger">Please fix the requirements to begin Smart School Installation.</div>';
} else {
    
}
?>

<h3>Server Requirements</h3>
<table class="table table-hover">
    <thead>
        <tr>
            <th><b>Requirements</b></th>
            <th><b>Result</b></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>PHP 8.1+ </td>
            <td><?php echo $requirement1; ?></td>
        </tr>
        <tr>
            <td>MySQLi PHP Extension</td>
            <td><?php echo $requirement3; ?></td>
        </tr>
        <tr>
            <td>GD PHP Extension</td>
            <td><?php echo $requirement6; ?></td>
        </tr>
        <tr>
            <td>CURL PHP Extension</td>
            <td><?php echo $requirement8; ?></td>
        </tr>
        <tr>
            <td>MBString PHP Extension</td>
            <td><?php echo $requirement5; ?></td>
        </tr>
        <tr>
            <td>Allow allow_url_fopen</td>
            <td><?php echo $requirement9; ?></td>
        </tr>
        <tr>
            <td>Zip Extension</td>
            <td><?php echo $requirement12; ?></td>
        </tr>
        <tr>
            <td>config.php Writable</td>
            <td><?php echo $requirement13; ?></td>
        </tr>
        <tr>
            <td>database.php Writable</td>
            <td><?php echo $requirement14; ?></td>
        </tr>
        <tr>
            <td>/temp folder Writable</td>
            <td><?php echo $requirement15; ?></td>
        </tr>
    </tbody>
</table>
<hr />
<?php
if ($error == true) {
    
} else {
    echo '<div class="text-right">';
    echo form_open($this->uri->uri_string());
    echo form_hidden('requirements_success', 'true');
    echo '<button type="submit" class="btn btn-primary">Database Setup</button>';
    echo form_close();
    echo '</div>';
}
?>
