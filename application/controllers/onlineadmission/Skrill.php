<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Skrill extends OnlineAdmission_Controller
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
        $amount                      = convertBaseAmountCurrencyFormat($total_amount);
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
            $cartTotal = $data['total']; // This amount needs to be sourced from your application
            $payment_data = array(
                'userSecretKey'=>$this->pay_method->api_secret_key,
                'categoryCode'=>$this->pay_method->api_signature,
                'billName'=>'Fees',
                'billDescription'=>'Student Fees',
                'billPriceSetting'=>1,
                'billPayorInfo'=>1,
                'billAmount'=>convertBaseAmountCurrencyFormat($amount),
                'billReturnUrl'=>base_url().'onlineadmission/skrill/success',
                'billCallbackUrl'=>base_url().'gateway_ins/skrill',
                'billExternalReferenceNo' => time().rand(99,999),
                'billTo'=>$online_data->firstname." ".$online_data->middlename." ".$online_data->lastname,
                'billEmail'=>$customer_email,
                'billPhone'=>$customer_phone,
                'billSplitPayment'=>0,
                'billSplitPaymentArgs'=>'',
                'billPaymentChannel'=>'0',
                'billContentEmail'=>'Thank you for fees submission!',
                'billChargeToCustomer'=>1,
              );  

            $payment_data['pay_to_email'] =$this->pay_method->api_email;
            $payment_data['transaction_id'] ='A'.time();
            $payment_data['return_url'] =base_url().'onlineadmission/skrill/success';
            $payment_data['cancel_url'] =base_url().'onlineadmission/skrill/cancel';
            $payment_data['status_url'] =base_url().'gateway_ins/skrill';
            $payment_data['language'] ='EN';
            $payment_data['merchant_fields'] ='customer_number,session_id';
            $payment_data['customer_number'] ='C'.time();
            $payment_data['session_ID'] ='A3D'.time();;
            $payment_data['pay_from_email'] =$customer_email;
            $payment_data['amount2_description'] ='';
            $payment_data['amount2'] ='';
            $payment_data['amount3_description'] ='';
            $payment_data['amount3'] ='';
            $payment_data['amount4_description'] ='';
            $payment_data['amount4'] ='';
            $payment_data['amount'] =$amount;
            $payment_data['currency'] =$data['currency'];
            $payment_data['firstname'] =$online_data->firstname;
            $payment_data['lastname'] =$online_data->lastname;
            $payment_data['address'] ='';
            $payment_data['postal_code'] ='';
            $payment_data['city'] ='';
            $payment_data['country'] ='';
            $payment_data['detail1_description'] ='';
            $payment_data['detail1_text'] ='';
            $payment_data['detail2_description'] ='';
            $payment_data['detail2_text'] ='';
            $payment_data['detail3_description'] ='';
            $payment_data['detail3_text'] ='';
            $data['form_fields']=$payment_data;
            $data['url']='https://pay.skrill.com';
            $ins_data=array(
            'unique_id'=>$payment_data['transaction_id'],
            'parameter_details'=>json_encode($payment_data),
            'gateway_name'=>'skrill',
            'online_admission_id'=>$reference,
            'module_type'=>'online_admission',
            'payment_status'=>'processing',
            );
 
            $gateway_ins_id=$this->gateway_ins_model->add_gateway_ins($ins_data);
            $this->session->set_userdata("transaction_id",$payment_data['transaction_id']);
            $this->load->view('onlineadmission/skrill/index', $data);
    } 
 
    public function success() {
        $amount = $this->amount;
        $reference  = $this->session->userdata('reference');
        $billExternalReferenceNo  = $this->session->userdata('transaction_id');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $apply_date=date("Y-m-d H:i:s");
        
        $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date)))); 
        
        $parameter_data=$this->gateway_ins_model->get_gateway_ins($billExternalReferenceNo,'skrill');
        if($parameter_data['payment_status']!='0'){

            $currentdate = date('Y-m-d');
            $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
            $this->onlinestudent_model->edit($adddata);
        
            if($parameter_data['payment_status']=='2'){
                $gateway_response['paid_status']= 1;
            }else{
                $gateway_response['paid_status']= 2;
            }

            $transactionid                      = $_GET['transaction_id'];
            $gateway_response['online_admission_id']   = $reference; 
            $gateway_response['paid_amount']    = $amount;
            $gateway_response['transaction_id'] = $transactionid;
            $gateway_response['payment_mode']   = 'skrill';
            $gateway_response['payment_type']   = 'online';
            $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_skrill_txn_id')  . $transactionid;
            $gateway_response['date']           = date("Y-m-d H:i:s");
            $return_detail                      = $this->onlinestudent_model->paymentSuccess($gateway_response);
             $sender_details = array('firstname' => $online_data->firstname, 'lastname' => $online_data->lastname, 'email' => $online_data->email,'date'=>$date,'reference_no'=>$online_data->reference_no,'mobileno'=>$online_data->mobileno,'paid_amount'=>$this->amount,'guardian_email'=>$online_data->guardian_email,'guardian_phone'=>$online_data->guardian_phone);
           $sender_details['transaction_id']=$transactionid;
            if($gateway_response['paid_status']==2){
                
                $this->mailsmsconf->mailsms('online_admission_fees_processing', $sender_details);
                 redirect(base_url("onlineadmission/checkout/processinginvoice/".$online_data->reference_no));
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
		$online_data = $this->onlinestudent_model->getAdmissionData($reference);
		
        redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
    }

}