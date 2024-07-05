<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Toyyibpay extends OnlineAdmission_Controller
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
            $cartTotal = $data['total'];// This amount needs to be sourced from your application
            $payment_data = array(
                'userSecretKey'=>$this->pay_method->api_secret_key,
                'categoryCode'=>$this->pay_method->api_signature,
                'billName'=>'Fees',
                'billDescription'=>'Student Fees',
                'billPriceSetting'=>1,
                'billPayorInfo'=>1,
                'billAmount'=>convertBaseAmountCurrencyFormat($amount),
                'billReturnUrl'=>base_url().'onlineadmission/toyyibpay/success',
                'billCallbackUrl'=>base_url().'gateway_ins/toyyibpay',
                'billExternalReferenceNo' => time().rand(99,999),
                'billTo'=>$online_data->firstname." ".$online_data->middlename." ".$online_data->lastname,
                'billEmail'=>$customer_email,
                'billPhone'=>$customer_phone,
                'billSplitPayment'=>0,
                'billSplitPaymentArgs'=>'',
                'billPaymentChannel'=>'0',
                'billContentEmail'=>'Thank you for fees submission!',
                'billChargeToCustomer'=>1
              );  

              $curl = curl_init();
              curl_setopt($curl, CURLOPT_POST, 1);
              curl_setopt($curl, CURLOPT_URL, 'https://dev.toyyibpay.com/index.php/api/createBill');  
              curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($curl, CURLOPT_POSTFIELDS, $payment_data);

              $result = curl_exec($curl);
              $info = curl_getinfo($curl);  
              curl_close($curl);
              $obj = json_decode($result);          
             $ins_data=array(
            'unique_id'=>$payment_data['billExternalReferenceNo'],
            'parameter_details'=>json_encode($payment_data),
            'gateway_name'=>'toyyibpay',
            'online_admission_id'=>$reference,
            'module_type'=>'online_admission',
            'payment_status'=>'processing',
            );

            $gateway_ins_id=$this->gateway_ins_model->add_gateway_ins($ins_data);
            $this->session->set_userdata("billExternalReferenceNo",$payment_data['billExternalReferenceNo']);
            $data['url']=$data['error']="";
            
              if (!empty($obj)) {
                if(isset($obj->status) && $obj->status=='error'){
                 $data['error']=$obj->msg;   
                }else{
                    $data['url'] = "https://dev.toyyibpay.com/".$obj[0]->BillCode;
                }
             
                }else{
                    $data['error']=$result;
                }
            $this->load->view('onlineadmission/toyyibpay/index', $data);
    } 
 
    public function success() {
        $amount = $this->amount;
        $reference  = $this->session->userdata('reference');
        $billExternalReferenceNo  = $this->session->userdata('billExternalReferenceNo');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $apply_date=date("Y-m-d H:i:s");
        
        $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date))));
        
        $parameter_data=$this->gateway_ins_model->get_gateway_ins($billExternalReferenceNo,'toyyibpay');
        if($parameter_data['payment_status']!='3'){
            
            $currentdate = date('Y-m-d');
            $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
            $this->onlinestudent_model->edit($adddata);
                    
            if($parameter_data['payment_status']=='1'){
                $gateway_response['paid_status']= 1;
            }else{
                $gateway_response['paid_status']= 2;
            }
            $transactionid                      = $_GET['transaction_id'];
            $gateway_response['online_admission_id']   = $reference; 
            $gateway_response['paid_amount']    = $amount;
            $gateway_response['transaction_id'] = $transactionid;
            $gateway_response['payment_mode']   = 'toyyibPay';
            $gateway_response['payment_type']   = 'online';
            $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_toyyibpay_txn_id') . $transactionid;
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

}