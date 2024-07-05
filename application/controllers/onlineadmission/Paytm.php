<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Paytm extends OnlineAdmission_Controller
{

    public $pay_method = "";
    public $amount = 0;

    function __construct() {
        parent::__construct();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->getSetting();
        $this->amount = $this->setting->online_admission_amount;
        $this->load->library('paytm_lib');
        $this->load->library('mailsmsconf');
        $this->load->model('onlinestudent_model');
    }

    public function index() {
        $reference = $this->session->userdata('reference');
        $params = $this->session->userdata('params');
        $data = array();
        $data['params'] = $params;
        $data['setting'] = $this->setting;
        $data['api_error'] = array();
        $amount= $this->amount;
        $data['amount'] = ($amount);
        $paytmParams = array();
        $ORDER_ID = time();
        $CUST_ID = time();

        $paytmParams = array(
            "MID" => $this->pay_method->api_publishable_key,
            "WEBSITE" => $this->pay_method->paytm_website,
            "INDUSTRY_TYPE_ID" => $this->pay_method->paytm_industrytype,
            "CHANNEL_ID" => "WEB",
            "ORDER_ID" => $ORDER_ID,
            "CUST_ID" => $reference,
            "TXN_AMOUNT" => convertBaseAmountCurrencyFormat($data['amount']),
            "CALLBACK_URL" => base_url() . "onlineadmission/paytm/complete",
        );

        $paytmChecksum = $this->paytm_lib->getChecksumFromArray($paytmParams, $this->pay_method->api_secret_key);
        $paytmParams["CHECKSUMHASH"] = $paytmChecksum;
		  //$transactionURL              = 'https://securegw-stage.paytm.in/order/process';//for sand-box
         $transactionURL = 'https://securegw.paytm.in/order/process';
        $data['paytmParams'] = $paytmParams;
        $data['transactionURL'] = $transactionURL;
        $this->load->view("onlineadmission/paytm/index", $data);
    } 

    public function complete()
    {
        $reference  = $this->session->userdata('reference');
        $paytmChecksum = "";
        $paramList = array();
        $isValidChecksum = "FALSE";
        $paramList = $_POST;
        $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : "";
        $isValidChecksum = $this->paytm_lib->verifychecksum_e($paramList, $this->pay_method->api_secret_key, $paytmChecksum);
         $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        
        if ($isValidChecksum == "TRUE") {

            if ($_POST["STATUS"] == "TXN_SUCCESS") {
            
            $currentdate = date('Y-m-d');
            $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
            $this->onlinestudent_model->edit($adddata);                    
           
            $apply_date=date("Y-m-d H:i:s");
            
            $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date)))); 
            
            $amount = $this->session->userdata('payment_amount');
            $transactionid=$_POST['TXNID'];
            $gateway_response['online_admission_id']   = $reference; 
            $gateway_response['paid_amount']    = $amount;
            $gateway_response['transaction_id'] = $transactionid;
            $gateway_response['payment_mode']   = 'paytm';
            $gateway_response['payment_type']   = 'online';
            $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_paytm_txn_id') . $transactionid;
            $gateway_response['date']           = date("Y-m-d H:i:s");
            $return_detail                      = $this->onlinestudent_model->paymentSuccess($gateway_response);
             $sender_details = array('firstname' => $online_data->firstname, 'lastname' => $online_data->lastname, 'email' => $online_data->email,'date'=>$date,'reference_no'=>$online_data->reference_no,'mobileno'=>$online_data->mobileno,'paid_amount'=>$amount,'guardian_email'=>$online_data->guardian_email,'guardian_phone'=>$online_data->guardian_phone);
            $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
			if(!empty($online_data->reference_no)){
				redirect(base_url("onlineadmission/checkout/successinvoice/".$online_data->reference_no));
			}else{
				redirect(base_url("onlineadmission/checkout/successinvoice/0"));
			}
            
              
            } else { 
			if(!empty($online_data->reference_no)){
				redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
			}else{
				redirect(base_url("onlineadmission/checkout/paymentfailed/0"));
			}
               
            }
        } else {
           if(!empty($online_data->reference_no)){
				redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
			}else{
				redirect(base_url("onlineadmission/checkout/paymentfailed/0"));
			}
        }

    }

}

?>