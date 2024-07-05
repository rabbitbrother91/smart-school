<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use Omnipay\Omnipay;

require_once(APPPATH . 'third_party/omnipay/vendor/autoload.php');

class Paymongo {

    private $_CI;

    public function __construct() {
        $this->_CI = &get_instance();
    }

    public function payment() {
        $gateway = Omnipay::create('Paymongo_Card');
        
        $gateway->setKeys('pk_test_xQBzFMDbKeiTD9GRovJLgkAK', 'sk_test_ttfPyF2BE396vQLVVMfAYGaK');
        $token = $gateway->authorize([
            'number' => '4123 4501 3100 0508',
            'expiryMonth' => '1',
            'expiryYear' => '22',
            'cvv' => '123',
        ]);
        print_r($token);
    }

}
