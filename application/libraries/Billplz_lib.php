<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
use Billplz\API;
use Billplz\Connect;

require_once(APPPATH . 'third_party/billplz/autoload.php');

class Billplz_lib {

    private $_CI;
    public $api_config;

    function __construct() {
        $this->_CI = & get_instance();
        $this->api_config = $this->_CI->paymentsetting_model->getActiveMethod();
    }

    public function payment($parameter, $optional,$api_key) {
        $connect = (new Connect($api_key))->detectMode();
        $connect->setMode(true); // true: production | false: staging (default)
        $billplz = new API($connect);
        
        $response = $billplz->createOpenCollection($parameter, $optional);

        if($response[0]==200){
            $payment_data=json_decode($response[1]);
            header("Location: $payment_data->url");
          
        }else{
            echo ($response[1]);
        }

        }
}
