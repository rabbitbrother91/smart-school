<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Msgnineone
{

    private $_CI;
    public $route    = "4";
    public $authKey  = "";
    public $senderId = "";
    public $url      = "https://api.msg91.com/api/v2/sendsms";

    public function __construct($array)
    {
        $this->_CI = &get_instance();

        $this->authKey     = $array['authkey'];
        $this->senderId    = $array['senderid'];
        $this->template_id = $array['templateid'];
    }

    public function sendSMS($to, $message)
    {
         
        $DLT_TE_ID = $this->template_id;

        $postData = array(
            "sender"    => $this->senderId,
            'DLT_TE_ID' => $DLT_TE_ID,
            'route'     => $this->route,
            "country"   => "91",
            "unicode"   => 1,
            'sms'       => array(array('message' => $message, 'to' => array($to))),

        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => json_encode($postData),
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER     => array(
                "authkey:" . $this->authKey,
                "content-type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        curl_close($curl);
        return true;

    }

}
