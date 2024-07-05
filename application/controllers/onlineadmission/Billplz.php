<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Billplz extends OnlineAdmission_Controller
{

    public $pay_method = "";
    public $amount = 0;

    function __construct() {
        parent::__construct();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->getSetting();
        $this->amount = $this->setting->online_admission_amount;
        $this->load->library(array('billplz_lib','mailsmsconf'));
        $this->load->model('onlinestudent_model');
    }

    public function index() {

        $reference = $this->session->userdata('reference');
        $data['setting'] = $this->setting;
        $total = $this->amount;
        $data['amount'] = $total;
        $this->load->view('onlineadmission/billplz/index', $data);
    } 

    public function pay(){

    	$this->session->set_userdata('payment_amount',$this->amount);
        $data['return_url']  = base_url() . 'onlineadmission/billplz/complete';
        $data['total']       = $this->amount;
        $data['productinfo'] = "bill payment smart school";
        $parameter           = array(
            'title'       => $this->lang->line('online_admission_form_fees'),
            'description' => $data['productinfo'],
            'amount'      =>  convertBaseAmountCurrencyFormat($data['total']) * 100,
        );

        $optional = array(
            'fixed_amount'   => 'true',
            'fixed_quantity' => 'true',
            'payment_button' => 'pay',
            'redirect_uri'   => $data['return_url'],
            'photo'          => '',
            'split_header'   => false,
            'split_payments' => array(
                ['split_payments[][email]' => $this->pay_method->api_email],
                ['split_payments[][fixed_cut]' => '0'],
                ['split_payments[][variable_cut]' => ''],
                ['split_payments[][stack_order]' => '0'],
            ),
        );

        $api_key = $this->pay_method->api_secret_key;
        $this->billplz_lib->payment($parameter, $optional, $api_key);
    }

    public function complete() {
        $this->output->enable_profiler();
    	$amount = $this->amount;
        $reference  = $this->session->userdata('reference');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $apply_date = date("Y-m-d H:i:s");
        
        $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date))));      

        $data   = array();
        if ($_GET['billplz']['paid'] == 'true') {
            
            $currentdate = date('Y-m-d');
            $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
            $this->onlinestudent_model->edit($adddata);
        
            $transactionid                      = $_GET['billplz']['id'];
            $gateway_response['online_admission_id']   = $reference; 
            $gateway_response['paid_amount']    = $this->amount;
            $gateway_response['transaction_id'] = $transactionid;
            $gateway_response['payment_mode']   = 'billplz';
            $gateway_response['payment_type']   = 'online';
            $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_billplz_txn_id')  . $transactionid;
            $gateway_response['date']           = date("Y-m-d H:i:s");
            $return_detail                      = $this->onlinestudent_model->paymentSuccess($gateway_response);
             $sender_details = array('firstname' => $online_data->firstname, 'lastname' => $online_data->lastname, 'email' => $online_data->email,'date'=>$date,'reference_no'=>$online_data->reference_no,'mobileno'=>$online_data->mobileno,'paid_amount'=>$this->amount,'guardian_email'=>$online_data->guardian_email,'guardian_phone'=>$online_data->guardian_phone);
            $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
            redirect(base_url("onlineadmission/checkout/successinvoice/".$online_data->reference_no));
        } else {
            redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
        }
    }

}