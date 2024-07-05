<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');



require_once(APPPATH . 'third_party/pesapal/Aouth.php');

class Pesapal_lib { 

    private $_CI;
    public $api_config;
    public $currency;

    function __construct() {
        $this->_CI = & get_instance();
        $this->api_config = $this->_CI->paymentsetting_model->getActiveMethod();
        $this->currency = $this->_CI->setting_model->getCurrency();
    

    }

    function create_payment($data){
    	return $data;
    }

}