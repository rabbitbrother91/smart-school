<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Check whether the site is offline or not.
 *
 */
class Maintenance_hook
{
    public function __construct()
    {
        log_message('debug', 'Accessing maintenance hook!');
    }

    public function offline_check()
    {
        if (file_exists(APPPATH . 'config/maintenance_config.php')) {
            include APPPATH . 'config/maintenance_config.php';

            if (!in_array($_SERVER['REMOTE_ADDR'], $config['maintenance_ips']) 
                && (isset($config['maintenance_mode']) && $config['maintenance_mode'] === true)) {
                include APPPATH . 'views/maintenance.php';
                exit;
            }
        }
    }
}
