<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jazzcash extends OnlineAdmission_Controller
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
        date_default_timezone_set("Asia/Karachi");
    }

    public function index() {

        $reference = $this->session->userdata('reference');
        $data['setting'] = $this->setting;
        $total = $this->amount;
        $data['amount'] = $total;
        $this->load->view('onlineadmission/jazzcash/index', $data);
    } 

    public function pay(){
        $this->session->set_userdata('payment_amount',$this->amount);
        $reference = $this->session->userdata('reference');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
		
        $data['total']                         = $this->amount;
        $amount =number_format((float)(convertBaseAmountCurrencyFormat($this->amount)), 2, '.', '');
        $data = array();
        $data['setting'] = $this->setting;
        $data['api_error'] = array();
        $data['name'] = $online_data->firstname.' '.$online_data->lastname;
        $data['title'] = 'Online Admission Fees';
        $data['return_url'] = base_url() . 'onlineadmission/jazzcash/complete';
        $data['pp_MerchantID'] = $this->pay_method->api_secret_key;
        $data['pp_Password'] = $this->pay_method->api_password;
        $data['currency_code'] = $this->customlib->get_currencyShortName();
        $data['guardian_phone'] = $online_data->mobileno;
		$data['ExpiryTime'] = date('YmdHis', strtotime("+3 hours"));
		$data['TxnDateTime'] = date('YmdHis', strtotime("+0 hours"));
		$data['TxnRefNumber'] = "T". date('YmdHis');
        $input_para["pp_Version"]="2.0";
        $input_para["pp_IsRegisteredCustomer"]="Yes";
        $input_para["pp_TxnType"]="MPAY";
        $input_para["pp_TokenizedCardNumber"]="";
        $input_para["pp_CustomerID"]=time();
        $input_para["pp_CustomerEmail"]="";
        $input_para["pp_CustomerMobile"]="";
        $input_para["pp_MerchantID"]=$data['pp_MerchantID'];
        $input_para["pp_Language"]="EN";
        $input_para["pp_SubMerchantID"]="";
        $input_para["pp_Password"]=$data['pp_Password'];
        $input_para["pp_TxnRefNo"]=$data['TxnRefNumber'];
        $input_para["pp_Amount"]=$amount*100;
        $input_para["pp_DiscountedAmount"]="";
        $input_para["pp_DiscountBank"]="";
        $input_para["pp_TxnCurrency"]=$this->customlib->get_currencyShortName();
        $input_para["pp_TxnDateTime"]=$data['TxnDateTime'];
        $input_para["pp_TxnExpiryDateTime"]=$data['ExpiryTime'];
        $input_para["pp_BillReference"]=time();
        $input_para["pp_Description"]=$this->lang->line('online_admission_form_fees');
        $input_para["pp_ReturnURL"]=$data['return_url'];
        $input_para["pp_SecureHash"]="0123456789";
        $input_para["ppmpf_1"]="1";
        $input_para["ppmpf_2"]="2";
        $input_para["ppmpf_3"]="3";
        $input_para["ppmpf_4"]="4";
        $input_para["ppmpf_5"]="5";
        $data['payment_data']=$input_para;
        //$this->load->view('onlineadmission/jazzcash/pay', $data);
    }

    public function complete()
    {
        $reference  = $this->session->userdata('reference');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $apply_date = date("Y-m-d H:i:s");
        
        $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date))));      
        
        $data = array();
        if ($_POST['pp_ResponseCode'] == '000') {
            $amount                           = $this->session->userdata('payment_amount');
            $reference  = $this->session->userdata('reference');
            
            $currentdate = date('Y-m-d');
            $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
            $this->onlinestudent_model->edit($adddata);
                
            $gateway_response['online_admission_id']   = $reference; 
            $transactionid                      = $_POST['pp_TxnRefNo'];
            $gateway_response['paid_amount']    = ($amount);
            $gateway_response['transaction_id'] = $transactionid;
            $gateway_response['payment_mode']   = 'jazzcash';
            $gateway_response['payment_type']   = 'online';
            $gateway_response['date']           = date("Y-m-d H:i:s");
            $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_jazzcash_txn_id')   . $transactionid;
            $return_detail                      = $this->onlinestudent_model->paymentSuccess($gateway_response);
             $sender_details = array('firstname' => $online_data->firstname, 'lastname' => $online_data->lastname, 'email' => $online_data->email,'date'=>$date,'reference_no'=>$online_data->reference_no,'mobileno'=>$online_data->mobileno,'paid_amount'=>$amount,'guardian_email'=>$online_data->guardian_email,'guardian_phone'=>$online_data->guardian_phone);
            $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
            redirect(base_url("onlineadmission/checkout/successinvoice/".$online_data->reference_no));

        } elseif ($_POST['pp_ResponseCode'] == '112') {
			if(!empty($online_data->reference_no)){
			redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));	
			}else{
			redirect(base_url("onlineadmission/checkout/paymentfailed/0"));		
			}
            
        } else {
            $this->session->set_flashdata('msg', $_POST['pp_ResponseMessage']);
            if(!empty($online_data->reference_no)){
			redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));	
			}else{
			redirect(base_url("onlineadmission/checkout/paymentfailed/0"));		
			}
        }
    }

}