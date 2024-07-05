<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
  
class Nexmo_lib {

    private $_CI;
    var $from; //your AUTH_KEY here
    var $api_key; //your senderId here
    var $api_secret;
    function __construct($params) {
 
    	$this->from=$params['from'];
    	$this->api_key=$params['api_key'];
        $this->api_secret=$params['api_secret'];
        $this->_CI = & get_instance();
        $this->session_name = $this->_CI->setting_model->getCurrentSessionName();
    } 
  
    function sendSMS($to, $message) {
        
        $message = urlencode($message);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://rest.nexmo.com/sms/json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "from=$this->from&text=$message&to=$to&api_key=$this->api_key&api_secret=$this->api_secret");
        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }



}
?>