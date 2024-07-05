<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Twocheckout extends OnlineAdmission_Controller
{

    public $pay_method = "";
    public $amount = 0;

    function __construct() {
        parent::__construct();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->getSetting();
        $this->amount = $this->setting->online_admission_amount;
        $this->load->library('mailsmsconf');
        $this->load->model(array('onlinestudent_model','gateway_ins_model'));
    }
 
    public function index()
    {
        $setting             = $this->setting;
        $data                = array();
        $data['setting'] = $setting;
        $total_amount = $this->amount;
        $data['amount'] = $total_amount;
        $total                       = 0;
        $amount                      = $total_amount;
        $data['total']               = $amount;
        $data['currency']            = $this->customlib->get_currencyShortName();
        $reference = $this->session->userdata('reference');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $customer_email = $online_data->email;
        $data['pay_method']=$this->pay_method;
        if($online_data->mobileno!=''){
            $customer_phone = $online_data->mobileno;
        }else{
            $customer_phone = '9999999999';
        } 
            $cartTotal = $data['total'];// This amount needs to be sourced from your application
            
            $this->load->view('onlineadmission/twocheckout/index', $data);
    }

  
 
 public function success() {
        

    }

}