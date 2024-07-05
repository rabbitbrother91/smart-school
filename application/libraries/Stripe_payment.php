<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Omnipay\Omnipay;

require_once(APPPATH . 'third_party/omnipay/vendor/autoload.php');

class Stripe_payment {

    private $_CI;
    public $api_config;
 
    function __construct() {
        $this->_CI = & get_instance();
        $this->api_config = $this->_CI->paymentsetting_model->getActiveMethod();
    }
 
    public function payment($data) {

        $gateway = Omnipay::create('Stripe');
        $secret_key = $this->api_config->api_secret_key;
        $gateway->setApiKey($secret_key);
        $params = array(
            'cancelUrl' => base_url('parent/payment/getsuccesspayment'),
            'returnUrl' => base_url('parent/payment/getsuccesspayment'),
            'amount' => number_format($data['total'], 2, '.', ''),
            'description' => $data['description'],
            'currency' => $data['currency'],
            'token' => $data['stripeToken']
        );
        $response = $gateway->purchase($params)->send();
        return $response;
    }

}

?>