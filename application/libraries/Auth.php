<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Auth
{
    public $CI;
    protected $errors;
    protected $messages;

    //this is the expiration for a non-remember session
    //var $session_expire    = 600;

    public function __construct()
    {
        $this->CI       = &get_instance();
        $this->messages = array();
        $this->errors   = array();
        $this->CI->load->library('Enc_lib');
        $this->set_timezone();
        $this->CI->load->database();
    }

    /*
    this checks to see if the admin is logged in
    we can provide a link to redirect to, and for the login page, we have $default_redirect,
    this way we can check if they are already logged in, but we won't get stuck in an infinite loop if it returns false.
     */

    public function logged_in()
    {
        return (bool) $this->CI->session->userdata('admin');
    }

    public function user_logged_in()
    {
        return (bool) $this->CI->session->userdata('student');
    }

    public function user_redirect()
    {
        if ($this->CI->session->has_userdata('student')) {
            $user = $this->CI->session->userdata('student');
            $role = $user['role'];
            if ($role == "student") {
                redirect('user/user/dashboard');
            } else if ($role == "parent") {
                redirect('user/user/dashboard');
            } else if ($role == "teacher") {
                redirect('teacher/teacher/dashboard');
            } else if ($role == "accountant") {
                redirect('accountant/accountant/dashboard');
            } else if ($role == "librarian") {
                redirect('librarian/librarian/dashboard');
            } else {
                redirect('site/userlogin');
            }
        } else {
            redirect('site/userlogin');
        }
    }

    public function is_logged_in($default_redirect = false)
    {
        $admin = $this->CI->session->userdata('admin');

        if (!$admin) {

            $_SESSION['redirect_to'] = current_url();
            redirect('site/login');

            return false;
        } else {
            $active_status = $this->CI->db->select('is_active')->from('staff')->where('id', $admin['id'])->get()->row_array();

            if ($active_status['is_active'] == 1) {
              

                if ($default_redirect) {
                    redirect('admin/admin/dashboard');
                }
                return true;
            } else {

                $_SESSION['redirect_to'] = current_url();
                $this->logout();
                redirect('site/login');
                return false;
            }

        }
    }

        public function is_logged_in_user($role = false)
    {

        if ($this->CI->session->has_userdata('student')) {


            $user = $this->CI->session->userdata('student');

            if($user['role'] == "guest"){

                return true;
            }

            if (!$this->CI->session->has_userdata('current_class')) {
                if ($this->CI->router->fetch_method() != "choose") {
                 
                    redirect('user/user/choose');
                }

            }

            if (!$role) {
                redirect('site/userlogin');
            }

        } else {
            $_SESSION['redirect_to_user'] = current_url();
            redirect('site/userlogin');
        }
    }

    /*
    this function does the logging out
     */
    public function logout()
    {
        delete_cookie('branch_cookie');
        $this->CI->session->unset_userdata('admin');
        $this->CI->session->sess_destroy();
    }

    public function set_timezone()
    {

        if ($this->CI->customlib->getTimeZone()) {
            date_default_timezone_set($this->CI->customlib->getTimeZone());
        } else {
            return date_default_timezone_set('UTC');
        }
    }

    /*
    This function resets the admins password and emails them a copy
     */

    public function reset_password($email)
    {
        $admin = $this->get_admin_by_email($email);
        if ($admin) {
            $this->CI->load->helper('string');
            $this->CI->load->library('email');
            $new_password      = random_string('alnum', 8);
            $admin['password'] = sha1($new_password);
            $this->save_admin($admin);
            $this->CI->email->from($this->CI->config->item('email'), $this->CI->config->item('site_name'));
            $this->CI->email->to($email);
            $this->CI->email->subject($this->CI->config->item('site_name') . ': Admin Password Reset');
            $this->CI->email->message('Your password has been reset to ' . $new_password . '.');
            $this->CI->email->send();
            return true;
        } else {
            return false;
        }
    }

    /*
    This function gets the admin by their email address and returns the values in an array
    it is not intended to be called outside this class
     */

    private function get_admin_by_email($email)
    {
        $this->CI->db->select('*');
        $this->CI->db->where('email', $email);
        $this->CI->db->limit(1);
        $result = $this->CI->db->get('admin');
        $result = $result->row_array();

        if (sizeof($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /*
    This function takes admin array and inserts/updates it to the database
     */

    public function save($admin)
    {
        if ($admin['id']) {
            $this->CI->db->where('id', $admin['id']);
            $this->CI->db->update('admin', $admin);
        } else {
            $this->CI->db->insert('admin', $admin);
        }
    }

    /*
    This function gets a complete list of all admin
     */

    public function get_admin_list()
    {
        $this->CI->db->select('*');
        $this->CI->db->order_by('lastname', 'ASC');
        $this->CI->db->order_by('firstname', 'ASC');
        $this->CI->db->order_by('email', 'ASC');
        $result = $this->CI->db->get('admin');
        $result = $result->result();

        return $result;
    }

    /*
    This function gets an individual admin
     */

    public function get_admin($id)
    {
        $this->CI->db->select('*');
        $this->CI->db->where('id', $id);
        $result = $this->CI->db->get('admin');
        $result = $result->row();

        return $result;
    }

    public function check_id($str)
    {
        $this->CI->db->select('id');
        $this->CI->db->from('admin');
        $this->CI->db->where('id', $str);
        $count = $this->CI->db->count_all_results();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function check_email($str, $id = false)
    {
        $this->CI->db->select('email');
        $this->CI->db->from('admin');
        $this->CI->db->where('email', $str);
        if ($id) {
            $this->CI->db->where('id !=', $id);
        }
        $count = $this->CI->db->count_all_results();

        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($id)
    {
        if ($this->check_id($id)) {
            $admin = $this->get_admin($id);
            $this->CI->db->where('id', $id);
            $this->CI->db->limit(1);
            $this->CI->db->delete('admin');
            return $admin->firstname . ' ' . $admin->lastname . ' has been removed.';
        } else {
            return 'The admin could not be found.';
        }
    }

    public function validate_child($id = null)
    {
        $parent         = $this->CI->session->userdata('student');
        $parent_id      = $parent['id'];
        $students_array = $this->CI->student_model->read_siblings_students($parent_id);
        if ($id) {
            foreach ($students_array as $stu_key => $stu_value) {
                if ($stu_value->id == $id) {
                    return true;
                }
            }
            redirect('parent/unauthorized');
        }
    }


    public function multiupdate($branch_url, $purchase_code)
    {     
        $url =$this->CI->enc_lib->dycrypt(DEBUG_SYSTEM_MBANCH);
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        $data = array(
            'branch_url'    => $branch_url,
            'purchase_code' => $purchase_code,
            'base_url'      => base_url(),
        );

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output   = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $json_response = json_decode($output);
        if ($httpcode == 200) {
            return json_encode(array(
                'status'   => $json_response->status,
                'response' => $json_response->response,
            ));

        } else {
            return false;
        }
    }

    public function app_update()
    {
        $email                       = $this->CI->input->post('email');
        $envato_market_purchase_code = $this->CI->input->post('envato_market_purchase_code');
        $url                         = $this->CI->enc_lib->dycrypt(DEBUG_SYSTEM_UPDATE);
        $ch                          = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        $data = array(
            'email'         => $email,
            'purchase_code' => $envato_market_purchase_code,
            'base_url'      => base_url(),
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output   = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $json_response = json_decode($output);
        if ($httpcode != 200) {
            return $this->CI->output
                ->set_content_type('application/json')
                ->set_status_header($httpcode)
                ->set_output(json_encode(array(
                    'response' => $json_response->response, true,
                )));

        } else {
            $fname         = APPPATH . 'config/license.php';
            $update_handle = fopen($fname, "r");
            $content       = fread($update_handle, filesize($fname));
            $file_contents = str_replace('$config[\'SSLK\'] = \'\'', '$config[\'SSLK\'] = \'' . $json_response->response . '\'', $content);
            $update_handle = fopen($fname, 'w') or die("can't open file");
            if (fwrite($update_handle, $file_contents)) {

            }
            fclose($update_handle);
            $array = array('status' => 1, 'message' => 'Thank you for registering your product');
            return $this->CI->output
                ->set_content_type('application/json')
                ->set_status_header($httpcode)
                ->set_output(json_encode($array));
        }
    }

    public function andapp_update()
    {
        $email                       = $this->CI->input->post('app-email');
        $envato_market_purchase_code = $this->CI->input->post('app-envato_market_purchase_code');
        $sslk                        = $this->CI->config->item('SSLK');
        $url                         = $this->CI->enc_lib->dycrypt(DEBUG_SYSTEM_APP);
        $ch                          = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        $data = array(
            'email'         => $email,
            'sslk'          => $sslk,
            'purchase_code' => $envato_market_purchase_code,
            'base_url'      => base_url(),
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output   = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $json_response = json_decode($output);
        if ($httpcode != 200) {
            return $this->CI->output
                ->set_content_type('application/json')
                ->set_status_header($httpcode)
                ->set_output(json_encode(array(
                    'response' => $json_response->response, true,
                )));

        } else {
            $fname         = APPPATH . 'config/license.php';
            $update_handle = fopen($fname, "r");
            $content       = fread($update_handle, filesize($fname));
            if (strpos($content, '$config[\'app_ver\']') == false) {
                $update_handle = fopen($fname, 'a') or die("can't open file");
                $file_contents = '$config[\'app_ver\'] = 1;' . "\n";
                if (fwrite($update_handle, $file_contents)) {

                }
            } else {
                $file_contents = str_replace('$config[\'app_ver\'] = 0', '$config[\'app_ver\'] = 1', $content);
                $update_handle = fopen($fname, 'w') or die("can't open file");
                if (fwrite($update_handle, $file_contents)) {

                }
            }
            fclose($fhandle);
            $array = array('status' => 1, 'message' => 'Thank you for registering your product');
            return $this->CI->output
                ->set_content_type('application/json')
                ->set_status_header($httpcode)
                ->set_output(json_encode($array));
        }
    }

    public function andapp_validate()
    {
		return true;
        $sslk = $this->CI->config->item('SSLK');
        $url  = $this->CI->enc_lib->dycrypt(DEBUG_SYSTEM_APP_REG);
        $ch   = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        $data = array(
            'sslk'     => $sslk,
            'base_url' => base_url(),
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output   = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $json_response = json_decode($output);
        if ($httpcode == 200) {
            return true;
        } else {
            return false;
        }
    }

    public function addon_update()
    {
        $email                       = $this->CI->input->post('app-email');
        $addon                       = $this->CI->input->post('addon');
        $addon_version               = $this->CI->input->post('addon_version');
        $envato_market_purchase_code = $this->CI->input->post('app-envato_market_purchase_code');
        $sslk                        = $this->CI->config->item('SSLK');
        $url                         = $this->CI->enc_lib->dycrypt(DEBUG_SYSTEM_ADDON);
        $ch                          = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        $data = array(
            'email'         => $email,
            'sslk'          => $sslk,
            'purchase_code' => $envato_market_purchase_code,
            'addon_version' => $addon_version,
            'addon'         => $addon,
            'base_url'      => base_url(),
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output   = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $json_response = json_decode($output);

        if ($httpcode != 200) {
            return $this->CI->output
                ->set_content_type('application/json')
                ->set_status_header($httpcode)
                ->set_output(json_encode(array(
                    'response' => $json_response->response, true,
                )));

        } else {

            $fname         = APPPATH . 'config/license.php';
            $update_handle = fopen($fname, "r");
            $content       = fread($update_handle, filesize($fname));
            if (strpos($content, '$config[\'addon_prod\']') == false) {
                $update_handle = fopen($fname, 'a') or die("can't open file");
                $file_contents = '$config[\'addon_prod\'] = array(' . $json_response->token . ');' . "\n";
                if (fwrite($update_handle, $file_contents)) {

                }

            }
            if (strpos($content, '$config[\'addon_ver\']') == false) {
                $update_handle = fopen($fname, 'a') or die("can't open file");
                $file_contents = '$config[\'addon_ver\'] = array(' . $json_response->license . ');' . "\n";
                if (fwrite($update_handle, $file_contents)) {

                }

            }
            if (strpos($content, '$config[\'addon_prod\']') == true && strpos($content, '$config[\'addon_ver\']') == true) {
                $contents            = file_get_contents($fname);
                $update_write_handle = fopen($fname, 'w') or die("can't open file");
                $pattern             = preg_quote('$config[\'addon_prod\']', '/');
                $pattern             = "/^.*$pattern.*\$/m";
                if (preg_match_all($pattern, $contents, $matches)) {
                    $file_contents = str_replace($matches[0], '$config[\'addon_prod\'] = array(' . $json_response->token . ');', $contents);

                    file_put_contents($fname, $file_contents);
                }
                //=============================================
                $contents            = file_get_contents($fname);
                $update_write_handle = fopen($fname, 'w') or die("can't open file");
                $pattern             = preg_quote('$config[\'addon_ver\']', '/');
                $pattern             = "/^.*$pattern.*\$/m";
                if (preg_match_all($pattern, $contents, $matches)) {
                    $file_contents = str_replace($matches[0], '$config[\'addon_ver\'] = array(' . $json_response->license . ');', $contents);

                    file_put_contents($fname, $file_contents);
                }

            }
            fclose($update_handle);
            $back  = $_SERVER['HTTP_REFERER'];
            $array = array('status' => 1, 'back' => $back, 'message' => 'Thank you for registering your product');
            return $this->CI->output
                ->set_content_type('application/json')
                ->set_status_header($httpcode)
                ->set_output(json_encode($array));
        }
    }

    public function autoupdate()
    {
        if (!$this->CI->session->has_userdata('version')) {
            $this->set_message('Internal error, Please contact to service provider.');
            return false;
        }
        $version_dt  = $this->CI->session->userdata('version');
        $dw_filename = $version_dt['filename'];
        $fd_name     = $this->filename($dw_filename);
        $url         = $this->CI->enc_lib->dycrypt(DEBUG_SYSTEM_AUTO_UPDATE);
        $file        = './temp/' . $dw_filename;
        $sslk        = $this->CI->config->item('SSLK');
        $app_version = $this->CI->customlib->getAppVersion();
        $post_data   = [
            'sslk'        => $sslk,
            'site_url'    => site_url(),
            'app_version' => $app_version,
        ];
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_CONNECTTIMEOUT => 50,
            CURLOPT_POSTFIELDS     => $post_data,
            CURLOPT_USERAGENT      => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)',
        ]);

        $response = curl_exec($curl);
        $info     = curl_getinfo($curl);
        curl_close($curl);
        $this->CI->session->unset_userdata('version');
        if ($info['http_code'] == 0 && $info['header_size'] == 0) {
            $this->set_error('Unable to connect updater, please try after sometime!');
            return false;
        }

        if ($info['http_code'] == 200) {
            if ($info['content_type'] == "application/zip") {
//=========
                file_put_contents($file, $response);
                if (filesize($file) > 0) {
                    $zip = new ZipArchive;
                    $res = $zip->open('./temp/' . $dw_filename);
                    if ($res === true) {
                        $zip->extractTo('./temp/');
                        $zip->close();
                        if (!$this->import_dump($fd_name)) {
                            unlink('./temp/' . $fd_name . '/db_import.sql');
                            unlink('./temp/' . $dw_filename);
                            $this->deleteDir('./temp/' . $fd_name);
                            return false;
                        }
                        unlink('./temp/' . $fd_name . '/db_import.sql');
                        $this->recurse_copy('./temp/' . $fd_name, '.');
                        unlink('./temp/' . $dw_filename);
                        $this->deleteDir('./temp/' . $fd_name);
                        $this->set_message('Update successful!');
                    } else {
                        $this->set_message('Update error! There is some issue occurred during update, please contact to support.');
                        return false;
                    }

                }
                //==================
            } else if (is_string($response) && is_array(json_decode($response, true))) {
                $result = json_decode($response);
                $this->set_message($result->response);
                return false;
            } else {

            }

        } else {
            $result = json_decode($response);
            $this->set_error($result->response);
        }

    }

    public function import_dump($fd_name)
    {
        $filename = './temp/' . $fd_name . '/db_import.sql';
        if (file_exists($filename)) {
            $progressFilename = $filename . '_filepointer';
            $errorFilename    = $filename . '_error';
            $fp               = fopen($filename, 'r');
            if (!$fp) {
                $this->set_error('Update error! There is some issue occurred during update, please contact to support.');
                return false;
            }
            $queryCount = 0;
            $query      = '';

            $db_debug               = $this->CI->db->db_debug;
            $this->CI->db->db_debug = false;
            while ($line = fgets($fp, 1024000)) {
                if (substr($line, 0, 2) == '--' or trim($line) == '') {
                    continue;
                }

                $query .= $line;
                if (substr(trim($query), -1) == ';') {
                    if (!$this->CI->db->query($query)) {
                        $db_error = $this->CI->db->error();
                        $this->set_error($db_error['message']);
                        return false;
                    }
                    $query = '';
                    $queryCount++;
                }
            }
            $this->CI->db->db_debug = $db_debug;
            if (feof($fp)) {
                return true;
            } else {
                $this->set_error('Update error! There is some issue occurred during update, please contact to support.');
                return false;
            }
        }
        $this->set_error('Update error! There is some issue occurred during update, please contact to support.');
        return false;
    }

    public function set_error($error)
    {
        $this->errors[] = $error;
        return $error;
    }

    public function set_message($message)
    {
        $this->messages[] = $message;
        return $message;
    }

    public function clear_messages()
    {
        $this->messages = array();
        return true;
    }

    public function clear_error()
    {
        $this->errors = array();
        return true;
    }

    public function messages()
    {
        return $this->messages;
    }

    public function error()
    {
        return $this->errors;

    }

    public function recurse_copy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    public function deleteDir($dirPath)
    {
        if (!is_dir($dirPath)) {
            return false;
        }

        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }

        $files = glob($dirPath . '{,.}[!.,!..]*', GLOB_MARK | GLOB_BRACE);

        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    public function checkupdate()
    {
        $this->CI->session->unset_userdata('version');
        $url         = $this->CI->enc_lib->dycrypt(DEBUG_SYSTEM_CHECK_UPDATE);
        $sslk        = $this->CI->config->item('SSLK');
        $app_version = $this->CI->customlib->getAppVersion();
        $post_data   = [
            'sslk'        => $sslk,
            'site_url'    => site_url(),
            'app_version' => $app_version,
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output   = curl_exec($ch);
        $httpcode = curl_getinfo($ch);
        curl_close($ch);

        if ($httpcode['http_code'] == 0 && $httpcode['header_size'] == 0) {
            $this->set_error('Internal error or Connection problem. please try after sometime!');
            return false;
        }

        if ($httpcode['http_code'] != 200) {
            $result = json_decode($output);
            $this->set_error($result->response);
            return false;

        }
        if ($httpcode['http_code'] == 200) {
            if (is_string($output) && is_array(json_decode($output, true))) {
                $result = json_decode($output);
                if (isset($result->version)) {
                    $this->CI->session->set_userdata('version', array('version' => $result->version->nxtversion, 'filename' => $result->version->filename));
                }

                $this->set_message($result->response);

                return true;
            }
        }
    }

    public function filename($filename)
    {
        return preg_replace('/.[^.]*$/', '', $filename);
    }

    public function addonchk($prod = null, $return_url = false)
    {
		return true;
        if ($prod != null) {
            $addon_prod = $this->CI->config->item('addon_prod');
            $addon_ver  = $this->CI->config->item('addon_ver');
            $products   = array();

            if (!empty($addon_ver)) {
                foreach ($addon_ver as $ver_key => $ver_value) {
                    $key         = $addon_prod[$ver_key];
                    $decrypt_val = $this->CI->aes->decode($ver_value, $key);
                    if ($decrypt_val !== "") {
                        $arr               = explode('!!', $decrypt_val);
                        $products[$arr[0]] = $arr[1];
                        if ($arr[0] == $prod && $arr[1] == base_url()) {
                            return true;
                        }
                    }
                }
            }
        }
        if ($return_url) {
            redirect($return_url);
            exit;
        }
        return false;
    }
    
    public function userlogout()
    {
        if ($this->CI->session->has_userdata('student')) {
            $this->CI->session->unset_userdata('student');
        }
    }

}