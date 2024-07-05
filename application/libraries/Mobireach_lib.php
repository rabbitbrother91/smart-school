<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mobireach_lib
{

    private $_CI;
    public $URL            = "https://api.mobireach.com.bd/SendTextMessage?";
    var $AUTH_KEY;     
    var $senderId; 
    var $routeId; 
    var $smsContentType; 

    public function __construct($params)
    {
    	
    $this->_CI          = &get_instance();
	$this->AUTH_KEY=$params['authkey'];     
	$this->senderId=$params['senderid']; 
	$this->routeId=$params['routeid']; 
	$this->smsContentType="";
        $this->session_name = $this->_CI->setting_model->getCurrentSessionName();
    }

    public function sendSMS($to, $message)
    {

        $url = $this->URL . 'Username=' . rawurlencode($this->AUTH_KEY) .
        '&Password=' . rawurlencode($this->senderId) .
        '&From=' . rawurlencode($this->routeId) .
        '&To=' . rawurlencode($to) .
        '&Message=' . rawurlencode($message);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response    = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);
        return $response;
    }

}
