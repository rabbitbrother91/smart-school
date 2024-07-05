<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Payfast extends OnlineAdmission_Controller
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
        
        if($online_data->mobileno!=''){
            $customer_phone = $online_data->mobileno;
        }else{
            $customer_phone = '9999999999';
        }
            $cartTotal = convertBaseAmountCurrencyFormat($data['total']);// This amount needs to be sourced from your application
            $payfast_data = array(
            'merchant_id' => $this->pay_method->api_publishable_key,
            'merchant_key' => $this->pay_method->api_secret_key,
            'return_url' => base_url().'onlineadmission/payfast/success',
            'cancel_url' => base_url().'onlineadmission/payfast/cancel',
            'notify_url' => base_url().'gateway_ins/payfast',
            'name_first' => $online_data->firstname." ".$online_data->middlename,
            'name_last'  => $online_data->lastname,
            'email_address'=>$customer_email,
            'm_payment_id' => time().rand(99,999).time(), //Unique payment ID to pass through to notify_url
            'amount' => number_format( sprintf( '%.2f', $cartTotal ), 2, '.', '' ),
            'item_name' => 'reference_id#'.$reference,
            );
           
            $signature = $this->generateSignature($payfast_data,$this->pay_method->salt);
            $payfast_data['signature'] = $signature;           
             $ins_data=array(
            'unique_id'=>$payfast_data['m_payment_id'],
            'parameter_details'=>json_encode($payfast_data),
            'gateway_name'=>'payfast',
            'online_admission_id'=>$reference,
            'module_type'=>'online_admission',
            'payment_status'=>'processing',
            );

            $gateway_ins_id=$this->gateway_ins_model->add_gateway_ins($ins_data);
            $this->session->set_userdata("payfast_payment_id",$payfast_data['m_payment_id']);
            // If in testing mode make use of either sandbox.payfast.co.za or www.payfast.co.za
            $testingMode = false;
            $pfHost = $testingMode ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
            $data['pfHost']=$pfHost;
            $data['htmlForm']= $payfast_data;
            
            $this->load->view('onlineadmission/payfast/index', $data);
    }

    public  function generateSignature($data, $passPhrase = null) {
        // Create parameter string
        $pfOutput = '';
        foreach( $data as $key => $val ) {
            if($val !== '') {
                $pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
            }
        }
        // Remove last ampersand
        $getString = substr( $pfOutput, 0, -1 );
        if( $passPhrase !== null ) {
            $getString .= '&passphrase='. urlencode( trim( $passPhrase ) );
        }
        return md5( $getString );
    }
 
    public function success() {
    	$amount = $this->amount;
        $reference  = $this->session->userdata('reference');
        
        $currentdate = date('Y-m-d');
        $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
        $this->onlinestudent_model->edit($adddata);
        
        $payfast_payment_id  = $this->session->userdata('payfast_payment_id');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $apply_date=date("Y-m-d H:i:s");
        
        $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date))));
        
        $parameter_data=$this->gateway_ins_model->get_gateway_ins($payfast_payment_id,'payfast');
        if($parameter_data['payment_status']!='CANCELLED'){
            
            $currentdate = date('Y-m-d');
            $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
            $this->onlinestudent_model->edit($adddata);
                    
            if($parameter_data['payment_status']=='COMPLETE'){
                $gateway_response['paid_status']= 1;
            }else{
                $gateway_response['paid_status']= 2;
            }
            $transactionid                      = $payfast_payment_id;
            $gateway_response['online_admission_id']   = $reference; 
            $gateway_response['paid_amount']    = $amount;
            $gateway_response['transaction_id'] = $transactionid;
            $gateway_response['payment_mode']   = 'payfast';
            $gateway_response['payment_type']   = 'online';
            $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_payfast_txn_id') . $transactionid;
            $gateway_response['date']           = date("Y-m-d H:i:s");
            $return_detail                      = $this->onlinestudent_model->paymentSuccess($gateway_response);
             $sender_details = array('firstname' => $online_data->firstname, 'lastname' => $online_data->lastname, 'email' => $online_data->email,'date'=>$date,'reference_no'=>$online_data->reference_no,'mobileno'=>$online_data->mobileno,'paid_amount'=>$this->amount,'guardian_email'=>$online_data->guardian_email,'guardian_phone'=>$online_data->guardian_phone);
            $sender_details['transaction_id']=$transactionid;
            if($gateway_response['paid_status']==2){
                 
                $this->mailsmsconf->mailsms('online_admission_fees_processing', $sender_details);
            }else{
                $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
                 redirect(base_url("onlineadmission/checkout/successinvoice/".$online_data->reference_no));   
            }
           
        }else{
            redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
        }

    }

    public function cancel(){
        $reference  = $this->session->userdata('reference');
        redirect(base_url("onlineadmission/checkout/paymentfailed/".$reference));
    }
}