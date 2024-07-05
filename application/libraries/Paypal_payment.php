<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Omnipay\Omnipay;

require_once(APPPATH . 'third_party/omnipay/vendor/autoload.php');

class Paypal_payment {

    private $_CI;
    public $api_config;
    public $currency;

    function __construct() {
        $this->_CI = & get_instance();
        $this->api_config = $this->_CI->paymentsetting_model->getActiveMethod();
        $this->currency = $this->_CI->setting_model->getCurrency();
    }

    public function payment($data) {
 

        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername($this->api_config->api_username);
        $gateway->setPassword($this->api_config->api_password);
        $gateway->setSignature($this->api_config->api_signature);
        $gateway->setTestMode(FALSE);
        $response = $gateway->purchase($data)->send();
        return $response;
    }

    public function success($data) {

        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername($this->api_config->api_username);
        $gateway->setPassword($this->api_config->api_password);
        $gateway->setSignature($this->api_config->api_signature);
        $gateway->setTestMode(FALSE);
        $response = $gateway->completePurchase($data)->send();
        return $response;
    }

}

?>