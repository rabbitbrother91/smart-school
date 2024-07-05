<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Payhere extends OnlineAdmission_Controller
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
 
    public function index(){
        $setting             = $this->setting;
        $data                = array();
        $data['setting'] = $setting;
        $total_amount = $this->amount;
        $data['amount'] = $total_amount;
        $total                       = 0;
        $amount                      = $total_amount;
        $data['total']               = $amount;
        $data['currency']            =$this->customlib->get_currencyShortName();
        $this->load->view('onlineadmission/payhere/index', $data);
    }
  
    public function pay()
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

        $htmlform=array(
            'merchant_id'=>$this->pay_method->api_publishable_key,
            'return_url'=>base_url().'onlineadmission/payhere/success',
            'cancel_url'=>base_url().'onlineadmission/payhere/cancel',
            'notify_url'=>base_url().'gateway_ins/payhere',
            'order_id'=>time().rand(99,999),
            'items'=>$this->lang->line('online_admission_form_fees'), 
            'currency'=>$this->customlib->get_currencyShortName(),
            'amount'=>convertBaseAmountCurrencyFormat($amount),
            'first_name'=>$online_data->firstname,
            'last_name'=>$online_data->lastname,
            'email'=>$customer_email,
            'phone'=>$customer_phone,
            'address'=>'',
            'city'=>'',
            'country'=>''
            );  
          
            $ins_data=array(
            'unique_id'=>$htmlform['order_id'],
            'parameter_details'=>json_encode($htmlform),
            'gateway_name'=>'payhere',
            'online_admission_id'=>$reference,
            'module_type'=>'online_admission',
            'payment_status'=>'processing',
            );

            $gateway_ins_id=$this->gateway_ins_model->add_gateway_ins($ins_data);
            $this->session->set_userdata("order_id",$htmlform['order_id']);
            $data['htmlform']=$htmlform;
            $this->load->view('onlineadmission/payhere/pay', $data);            
    } 
 
    public function success() {
        $amount = $this->amount;
        $reference  = $this->session->userdata('reference');
        
        $currentdate = date('Y-m-d');
        $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
        $this->onlinestudent_model->edit($adddata);
        
        $billExternalReferenceNo  = $this->session->userdata('order_id');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $apply_date=date("Y-m-d H:i:s");
        
        $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date))));    
        
        $parameter_data=$this->gateway_ins_model->get_gateway_ins($billExternalReferenceNo,'payhere');
        if($parameter_data['payment_status']!='-2'){
            
            $currentdate = date('Y-m-d');
            $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
            $this->onlinestudent_model->edit($adddata);
                    
            if($parameter_data['payment_status']=='2'){
                $gateway_response['paid_status']= 1;
            }else{
                $gateway_response['paid_status']= 2;
            }
            $transactionid                      = $billExternalReferenceNo;
            $gateway_response['online_admission_id']   = $reference; 
            $gateway_response['paid_amount']    = $amount;
            $gateway_response['transaction_id'] = $transactionid;
            $gateway_response['payment_mode']   = 'payhere';
            $gateway_response['payment_type']   = 'online';
            $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_payhere_txn_id') . $transactionid;
            $gateway_response['date']           = date("Y-m-d H:i:s");
            $return_detail                      = $this->onlinestudent_model->paymentSuccess($gateway_response);
             $sender_details = array('firstname' => $online_data->firstname, 'lastname' => $online_data->lastname, 'email' => $online_data->email,'date'=>$date,'reference_no'=>$online_data->reference_no,'mobileno'=>$online_data->mobileno,'paid_amount'=>$this->amount,'guardian_email'=>$online_data->guardian_email,'guardian_phone'=>$online_data->guardian_phone);
           
            if($gateway_response['paid_status']==2){
                $sender_details['transaction_id']=$transactionid;
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