<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once APPPATH . 'third_party/midtrans/midtrans-php/Midtrans.php';

class Midtrans_lib {

    public function __construct() {
        $this->CI = &get_instance();
    }

    public function getSnapToken($transaction, $server_key) {

        //Set Your server key
        \Midtrans\Config::$serverKey = $server_key;

        // Uncomment for production environment
         \Midtrans\Config::$isProduction = true;

        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;



        $snapToken = \Midtrans\Snap::getSnapToken($transaction);
        return $snapToken;
    }

}
