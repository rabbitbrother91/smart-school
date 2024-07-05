<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Midtrans extends OnlineAdmission_Controller
{

    public $pay_method = "";
    public $amount = 0;

    function __construct() {
        parent::__construct();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->getSetting();
        $this->amount = $this->setting->online_admission_amount;
        $this->load->library('midtrans_lib');
        $this->load->library('mailsmsconf');
        $this->load->model('onlinestudent_model');
    }

    public function index() {
        $data = array();
        $reference = $this->session->userdata('reference');
        $data['setting'] = $this->setting;
        $total = $this->amount;
        $data['amount'] = $total;
        $enable_payments = array('credit_card');
        $transaction = array(
            'enabled_payments' => $enable_payments,
            'transaction_details' => array(
            'order_id' => time(),
            'gross_amount' => round(convertBaseAmountCurrencyFormat($total)), // no decimal allowed
            ),
        );
        $data['return_url'] = base_url() . "onlineadmission/midtrans/complete";
        $snapToken = $this->midtrans_lib->getSnapToken($transaction, $this->pay_method->api_secret_key);
        $data['snap_Token'] = $snapToken;
        $this->load->view('onlineadmission/midtrans/index', $data);
    }

    public function complete()
    {
        $reference  = $this->session->userdata('reference');
        
        $currentdate = date('Y-m-d');
        $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
        $this->onlinestudent_model->edit($adddata);
            
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $apply_date=date("Y-m-d H:i:s");
        
        $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date))));        
        
        $amount = $this->amount;
        $response                           = json_decode($_POST['result_data']);
        $payment_id                         = $response->transaction_id;
        $transactionid                      = $payment_id;
        $gateway_response['online_admission_id']   = $reference; 
        $gateway_response['paid_amount']    = $this->amount;
        $gateway_response['transaction_id'] = $transactionid;
        $gateway_response['payment_mode']   = 'midtrans';
        $gateway_response['payment_type']   = 'online';
        $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_midtrance_txn_id')  . $transactionid;
        $gateway_response['date']           = date("Y-m-d H:i:s");
        $return_detail                      = $this->onlinestudent_model->paymentSuccess($gateway_response);
         $sender_details = array('firstname' => $online_data->firstname, 'lastname' => $online_data->lastname, 'email' => $online_data->email,'date'=>$date,'reference_no'=>$online_data->reference_no,'mobileno'=>$online_data->mobileno,'paid_amount'=>$this->amount,'guardian_email'=>$online_data->guardian_email,'guardian_phone'=>$online_data->guardian_phone);
        $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
        echo $online_data->reference_no;
    }
}