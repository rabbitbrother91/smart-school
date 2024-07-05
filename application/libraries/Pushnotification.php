<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pushnotification {

    public $CI;
    //com.qdocs.smartschool
    public $API_ACCESS_KEY = "AAAAkGeJt3E:APA91bEITzLONxgN9bFDgpvZTgXdqCe23JVcC_96cfHiLJzMiN-oOO1lc15OULp1pUxH0uHljFnTX72b2ELQazuFNTjt3dK-flPO_iDUXnSEFjFlHJQW2r2mF4SuzkaL0wJPE-6UealO";
    public $fcmUrl = "https://fcm.googleapis.com/fcm/send";

    public function __construct() {
        $this->CI = &get_instance();
    }

    public function send($tokens, $msg, $action = "") {


        $notificationData = [
            'title' => $msg['title'],
            'body' => $msg['body'],
            'action' => $action,
            'sound' => 'mySound',
        ];

        $fcmNotification = [
            'to' => $tokens, //single token
            'collapseKey' => "{$tokens}",
            'data' => $notificationData,
        ];
        $headers = [
            'Authorization: key=' . $this->API_ACCESS_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);

        return true;
    }

}
