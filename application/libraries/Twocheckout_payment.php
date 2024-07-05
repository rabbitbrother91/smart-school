<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Omnipay\Omnipay;

require_once(APPPATH . 'third_party/omnipay/vendor/autoload.php');

class Twocheckout_payment {

    private $_CI;
    public $api_config;

    function __construct() {
        $this->_CI = & get_instance();
        $this->api_config = $this->_CI->paymentsetting_model->getActiveMethod();
    }

    public function payment1($data) {

        $fee_groups_feetype_id = $data['fee_groups_feetype_id'];
        $payment_details = $this->_CI->feegrouptype_model->getFeeGroupByID($fee_groups_feetype_id);
        $gateway = Omnipay::create('TwoCheckoutPlus_Token');
        $secret_key = $this->api_config->api_secret_key;
        $gateway->setApiKey($secret_key);

        $params = array(
            'amount' => number_format($data['total'], 2, '.', ''),
            'description' => $payment_details->type . " - " . $payment_details->code,
            'currency' => $data['currency'],
            'token' => $this->ci->input->post('token'),
            'card' => ""
        );
        $response = $gateway->purchase($params)->send();
        return $response;
    }

    public function payment($data) {
        $account_no = $this->api_config->account_no;
        $fee_groups_feetype_id = $data['fee_groups_feetype_id'];
        $payment_details = $this->_CI->feegrouptype_model->getFeeGroupByID($fee_groups_feetype_id);
        $api_secret_key = $this->api_config->api_secret_key;
        $gateway = Omnipay::create('TwoCheckout');
        $gateway->setAccountNumber($account_no);
        $gateway->setSecretWord($api_secret_key);
        $gateway->setTestMode(true);
        $response = $gateway->purchase(array(
                    "merchantOrderId" => "123",
                    'amount' => number_format($data['amount'], 2, '.', ''),
                    'description' => $payment_details->type . " - " . $payment_details->code,
                    'currency' => $data['currency'],
                    'token' => $data['token'],
                    'card' => "",
                    'returnUrl' => 'https://www.example.com/return',
                ))->send();

        return $response;
    }

}

?>