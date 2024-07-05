<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bulk_sms_lib {

    private $_CI;
    var $username; //your AUTH_KEY here
    var $password; //your senderId here

    function __construct($params) {

    	$this->username=$params['username'];
    		$this->password=$params['password'];
        $this->_CI = & get_instance();
        $this->session_name = $this->_CI->setting_model->getCurrentSessionName();
    }

    function sendSMS($to, $message) {
        $username = $this->username;
        $password = $this->password;
        
        $messages = array(
            array('to' => $to, 'body' => $message)
        );
       
        $result = $this->send_message(json_encode($messages), 'https://api.bulksms.com/v1/messages', $username, $password);

        if ($result['http_status'] != 201) {
            // print "Error sending.  HTTP status " . $result['http_status'];
            // print " Response was " . $result['server_response'];
        } else {
            // print "Response " . $result['server_response'];
            // exit();
            // Use json_decode($result['server_response']) to work with the response further
        }
        return true;
    }

    function send_message($post_body, $url, $username, $password) {
        $ch = curl_init();
        $headers = array(
            'Content-Type:application/json',
            'Authorization:Basic ' . base64_encode("$username:$password")
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
        // Allow cUrl functions 20 seconds to execute
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        // Wait 10 seconds while trying to connect
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $output = array();
        $output['server_response'] = curl_exec($ch);
        $curl_info = curl_getinfo($ch);
        $output['http_status'] = $curl_info['http_code'];
        curl_close($ch);
        return $output;
    }

}
?>