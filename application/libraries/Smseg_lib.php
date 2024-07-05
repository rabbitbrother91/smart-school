<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Smseg_lib {

    private $_CI;

    function __construct() {
        $this->_CI = & get_instance();
        $this->session_name = $this->_CI->setting_model->getCurrentSessionName();
        $this->sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
    } 

    function sendSMS($to, $message) {
        $content = 'username=' . rawurlencode($this->sms_detail->username) .
                '&password=' . rawurlencode($this->sms_detail->password) .
                '&sendername=' . rawurlencode($this->sms_detail->senderid) .
                '&mobiles=' . rawurlencode($to) .
                '&message=' . rawurlencode($message);
        $ch = curl_init($this->sms_detail->url. $content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        print_r($response);
        return $response;
    }

} 

?>