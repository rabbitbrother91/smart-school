<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Smscountry {

    private $_CI;
    public $user = "";
    public $password = "";
    public $senderId = "";
    public $AuthKey = "";
    public $AuthToken = "";
    
    public $url = "http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
    public $messagetype = "N"; //Type Of Your Message
    public $DReports = "Y"; //Delivery Reports

    function __construct($array) {
        $this->_CI = & get_instance();
        $this->user = $array['username'];
        $this->password = $array['password'];
        $this->senderId = $array['sernderid'];
        $this->AuthKey = $array['authkey'];
        $this->AuthToken = $array['api_id'];
        
        
    }

    function sendSMS($to, $message) {          

        $AuthKey= $this->AuthKey; 

        $AuthToken= $this->AuthToken;        
        
        $Authorization = base64_encode($AuthKey.":".$AuthToken);

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://restapi.smscountry.com/v0.1/Accounts/'.$AuthKey.'/SMSes/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "Text": "'.$message.'",
        "Number": "'.$to.'",
        "SenderId": "'.$this->senderId.'",
        "DRNotifyUrl": "https://www.domainname.com/notifyurl",
        "DRNotifyHttpMethod": "POST",
        "Tool": "API"
        }',
        
        
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic '.$Authorization.'',
            'Content-Type: application/json'
        ),
        ));
    
        $response = curl_exec($curl);
        
        curl_close($curl);
        // echo $response;
        return true;

    }

}

?>