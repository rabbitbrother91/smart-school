<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Start extends CI_Controller {

    private $error = '';

    function __construct() {
        parent::__construct();
        $this->load->library('Enc_lib');
    }

    public function index() {
        $config_path = APPPATH . 'config/config.php';
        $debug = '';
        $step = 1;
        $passed_steps = array(
            1 => false,
            2 => false,
            3 => false,
        );
        if ($this->input->post()) {
            if ($this->input->post('step') && $this->input->post('step') == 2) {
                if ($this->input->post('hostname') == '') {
                    $this->error = 'Hostname is required';
                } else if ($this->input->post('database') == '') {
                    $this->error = 'Enter database name';
                } else if ($this->input->post('password') == '' && strpos(site_url(), 'localhost') === false && strpos(site_url(), '[::1]') === false) {
                    $this->error = 'Enter database password';
                } else if ($this->input->post('username') == '') {
                    $this->error = 'Enter database username';
                }
                $step = 2;
                $passed_steps[1] = true;
                if ($this->error === '') {
                    $passed_steps[2] = true;
                    $link = @mysqli_connect($this->input->post('hostname'), $this->input->post('username'), $this->input->post('password'), $this->input->post('database'));
                    if (!$link) {
                        $this->error .= "Error: Unable to connect to MySQL Database." . PHP_EOL;
                    } else {
                        $debug .= "Success: Connection to " . $this->input->post('database') . " database is done successfully.";
                        if ($this->write_db_config()) {
                            $step = 3;
                        }
                        mysqli_close($link);
                    }
                }
            } else if ($this->input->post('step') && $this->input->post('step') == 3) {
                if ($this->input->post('admin_email') == '') {
                    $this->error = 'Enter admin email address';
                } else if (filter_var($this->input->post('admin_email'), FILTER_VALIDATE_EMAIL) === false) {
                    $this->error = 'Enter valid email address';
                } else if ($this->input->post('admin_password') == '') {
                    $this->error = 'Enter admin password';
                } else if ($this->input->post('admin_password') != $this->input->post('admin_passwordr')) {
                    $this->error = 'Your confirm password not match';
                }
                $passed_steps[1] = true;
                $passed_steps[2] = true;
                $step = 3;
            } else if ($this->input->post('requirements_success')) {
                $step = 2;
                $passed_steps[1] = true;
            }
            if ($this->error === '' && $this->input->post('step') && $this->input->post('step') == 3) {
                $database = read_file(APPPATH . 'controllers/install/database.sql');
                $this->load->database();
                if (mysqli_multi_query($this->db->conn_id, $database)) {
                    $this->clean_up_db_query();
                    $data = array(
                        'employee_id' => '9000',
                        'name' => 'Super Admin',
                        'dob' => '2020-01-01',
                        'gender' => 'Male',
                        'email' => $this->input->post('admin_email'),
                        'password' => $this->enc_lib->passHashEnc($this->input->post('admin_password')),
                        'is_active' => 1
                    );

                    $this->db->insert('staff', $data);
                    $insert_id = $this->db->insert_id();

                    $role_data = array(
                        'staff_id' => $insert_id,
                        'role_id' => 7
                    );

                    if ($this->db->insert('staff_roles', $role_data)) {

                        if (!is_really_writable($config_path)) {
                            show_error($config_path . ' should be writable. Database imported successfully. And admin user added successfully. You can set manually in application/config at bottom $config["installed"]  = "true"');
                        }
                        update_config_installed();
                        update_autoload_installed();
                        $passed_steps[1] = true;
                        $passed_steps[2] = true;
                        $passed_steps[3] = true;
                        $step = 4;
                    }
                }
            } else {
                $error = $this->error;
            }
        }
        include_once(APPPATH . 'controllers/install/html.php');
    }

    public function delete_install_dir() {
        if (is_dir(APPPATH . 'controllers/install')) {
            if (delete_dir(APPPATH . 'controllers/install')) {
                redirect(admin_url());
            }
        }
    }

    private function clean_up_db_query() {
        $CI = &get_instance();
        while (mysqli_more_results($CI->db->conn_id) && mysqli_next_result($CI->db->conn_id)) {
            $dummyResult = mysqli_use_result($CI->db->conn_id);
            if ($dummyResult instanceof mysqli_result) {
                mysqli_free_result($CI->db->conn_id);
            }
        }
    }

    private function write_db_config() {
        $hostname = $this->input->post('hostname');
        $database = $this->input->post('database');
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $new_database_file = '<?php defined(\'BASEPATH\') or exit(\'No direct script access allowed\');

$query_builder = true;

$db[\'default\'] = array(
    \'dsn\'          => \'\',
    \'hostname\' => \'' . $hostname . '\',
    \'username\' => \'' . $username . '\',
    \'password\' => \'' . $password . '\',
    \'database\' => \'' . $database . '\',
    \'dbdriver\'     => \'mysqli\',
    \'dbprefix\'     => \'\',
    \'pconnect\'     => false,
    \'db_debug\'     => (ENVIRONMENT !== \'production\'),
    \'cache_on\'     => false,
    \'cachedir\'     => \'\',
    \'char_set\'     => \'utf8\',
    \'dbcollat\'     => \'utf8_general_ci\',
    \'swap_pre\'     => \'\',
    \'encrypt\'      => false,
    \'compress\'     => false,
    \'stricton\'     => false,
    \'failover\'     => array(),
    \'save_queries\' => true,
    \'multi_branch\' => false,
);

$active_group = \'default\';

$mydb   = $db[\'default\'];
$mysqli = new mysqli($mydb[\'hostname\'], $mydb["username"], $mydb["password"], $mydb["database"]);

if ($mysqli->connect_errno) {
    printf("connection failed: %s\n", $mysqli->connect_error());
    exit();
}

if ($results = $mysqli->query("SHOW TABLES LIKE \'multi_branch\'")) {
    if ($results->num_rows == 1) {

        if ($result = $mysqli->query("SELECT * FROM multi_branch where is_verified =1")) {
            while ($row = $result->fetch_assoc()) {
                $short_name                      = "branch_" . $row[\'id\'];
                $db[$short_name][\'hostname\']     = $row[\'hostname\'];
                $db[$short_name][\'username\']     = $row[\'username\'];
                $db[$short_name][\'password\']     = $row[\'password\'];
                $db[$short_name][\'database\']     = $row[\'database_name\'];
                $db[$short_name][\'dbdriver\']     = \'mysqli\';
                $db[$short_name][\'dbprefix\']     = \'\';
                $db[$short_name][\'pconnect\']     = false;
                $db[$short_name][\'db_debug\']     = false;
                $db[$short_name][\'cache_on\']     = false;
                $db[$short_name][\'cachedir\']     = \'\';
                $db[$short_name][\'char_set\']     = \'utf8\';
                $db[$short_name][\'dbcollat\']     = \'utf8_general_ci\';
                $db[$short_name][\'swap_pre\']     = \'\';
                $db[$short_name][\'autoinit\']     = false;
                $db[$short_name][\'stricton\']     = false;
                $db[$short_name][\'multi_branch\'] = true;

            }
        }
    }
}

$mysqli->close();';

        $fp = fopen(APPPATH . 'config/database.php', 'w+');
        if ($fp) {
            if (fwrite($fp, $new_database_file)) {
                return true;
            }
            fclose($fp);
        }
        return false;
    }

}
