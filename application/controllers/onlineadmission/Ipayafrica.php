<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ipayafrica extends OnlineAdmission_Controller
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
 
    public function index()
    {
        $setting             = $this->setting;
        $data                = array();
        $data['setting'] = $this->setting;
        $total_amount = $this->amount;
        $data['amount'] = $total_amount;
        $data['productinfo'] = "bill payment";
        $total                       = 0;
        $api_publishable_key         = ($this->pay_method->api_publishable_key);
        $api_secret_key              = ($this->pay_method->api_secret_key);
        $data['api_publishable_key'] = $api_publishable_key;
        $data['api_secret_key']      = $api_secret_key;
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
        $fields                      = array(
            "live" => "1",
            "oid"  => uniqid(),
            "inv"  => time(),
            "ttl"  => convertBaseAmountCurrencyFormat($amount),
            "tel"  => $customer_phone,
            "eml"  => $customer_email,
            "vid"  => ($this->pay_method->api_publishable_key),
            "curr" => $this->customlib->get_currencyShortName(),
            "p1"   => "airtel",
            "p2"   => "",
            "p3"   => "",
            "p4"   => $amount,
            "cbk"  => base_url() . 'onlineadmission/ipayafrica/complete',
            "cst"  => "1",
            "crl"  => "2",
        );

            $datastring = $fields['live'] . $fields['oid'] . $fields['inv'] . $fields['ttl'] . $fields['tel'] . $fields['eml'] . $fields['vid'] . $fields['curr'] . $fields['p1'] . $fields['p2'] . $fields['p3'] . $fields['p4'] . $fields['cbk'] . $fields['cst'] . $fields['crl'];

            $hashkey                = ($this->pay_method->api_secret_key);
            $generated_hash         = hash_hmac('sha1', $datastring, $hashkey);
            $data['fields']         = $fields;
            $data['generated_hash'] = $generated_hash;
            $this->load->view('onlineadmission/ipayafrica/index', $data);
    }
 
    public function complete() {
    	$amount = $this->amount;
        $reference  = $this->session->userdata('reference');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $apply_date=date("Y-m-d H:i:s");
        
        $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date))));
        
        $data   = array();
        if (!empty($_GET['status'])) {
            
            $currentdate = date('Y-m-d');
            $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
            $this->onlinestudent_model->edit($adddata);
                    
            $transactionid                      = $_GET['txncd'];
            $gateway_response['online_admission_id']   = $reference; 
            $gateway_response['paid_amount']    = $amount;
            $gateway_response['transaction_id'] = $transactionid;
            $gateway_response['payment_mode']   = 'ipayafrica';
            $gateway_response['payment_type']   = 'online';
            $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_ipayafrica_txn_id')  . $transactionid;
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