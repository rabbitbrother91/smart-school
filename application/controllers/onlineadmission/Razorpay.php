<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Razorpay extends OnlineAdmission_Controller
{

    public $pay_method = "";
    public $amount = 0;

    function __construct() {
        parent::__construct();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->getSetting();
        $this->amount = $this->setting->online_admission_amount;
        $this->load->library('mailsmsconf');
        $this->load->model('onlinestudent_model');
    }
 
    public function index() {
        $data = array();
        $amount = $this->amount;
        $data['amount'] = $amount; 
        $data['setting'] = $this->setting;
        $data['api_error'] = array();
        $reference = $this->session->userdata('reference');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $data['name'] = $online_data->firstname.' '.$online_data->lastname;
        $data['merchant_order_id'] = time() . "01";
        $data['txnid'] = time() . "02";
        $data['title'] = $this->lang->line('online_admission_form_fees');
        $data['return_url'] = site_url() . 'onlineadmission/razorpay/complete';
        $data['total'] = convertBaseAmountCurrencyFormat($amount) * 100;
        $data['key_id'] = $this->pay_method->api_publishable_key;
        $data['currency_code'] = $this->customlib->get_currencyShortName();
        $ch = curl_init(); 
        $order_data=array('amount'=>$data['total'],'currency'=>$data['currency_code'],'receipt'=>'R#'.$data['merchant_order_id']);
        curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order_data));
        curl_setopt($ch, CURLOPT_USERPWD, $this->pay_method->api_publishable_key . ':' . $this->pay_method->api_secret_key);

        $headers = array(); 
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);die;
        }
        curl_close($ch);

        if(array_key_exists('error', (array)json_decode($result))){
        $order_id="";
        }else{
           $order_id=json_decode($result)->id; 
        }

        $data['order_id']=$order_id;
        $this->load->view('onlineadmission/razorpay/index', $data);
    }

    public function complete() {
    	$amount = $this->session->userdata('payment_amount');
        $reference  = $this->session->userdata('reference');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $apply_date=date("Y-m-d H:i:s");
        
        $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date)))); 
        
        $data   = array();
        if (isset($_POST['razorpay_payment_id'])) {
            
            $currentdate = date('Y-m-d');
            $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
            $this->onlinestudent_model->edit($adddata);
                    
            $transactionid                      = $_POST['razorpay_payment_id'];
            $gateway_response['online_admission_id']   = $reference; 
            $gateway_response['paid_amount']    = $this->amount;
            $gateway_response['transaction_id'] = $transactionid;
            $gateway_response['payment_mode']   = 'razorpay';
            $gateway_response['payment_type']   = 'online';
            $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_razorpay_txn_id') . $transactionid;
            $gateway_response['date']           = date("Y-m-d H:i:s");
            $return_detail                      = $this->onlinestudent_model->paymentSuccess($gateway_response); 
             $sender_details = array('firstname' => $online_data->firstname, 'lastname' => $online_data->lastname, 'email' => $online_data->email,'date'=>$date,'reference_no'=>$online_data->reference_no,'mobileno'=>$online_data->mobileno,'paid_amount'=>$this->amount,'guardian_email'=>$online_data->guardian_email,'guardian_phone'=>$online_data->guardian_phone);
            $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
            echo $online_data->reference_no;
        } else {
            redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
        }
    }

}