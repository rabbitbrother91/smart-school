<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mollie extends OnlineAdmission_Controller
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

        $reference = $this->session->userdata('reference');
        $data['setting'] = $this->setting;
        $total = $this->amount;
        $data['amount'] = $total;
        $data['error']=array();
        $this->load->view('onlineadmission/mollie/index', $data);
    }  
    
    public function pay()
    {
            $this->session->set_userdata('payment_amount',$this->amount);
            $reference = $this->session->userdata('reference');
            $buyer_data = $this->onlinestudent_model->getAdmissionData($reference);
            $amount =number_format((float)(convertBaseAmountCurrencyFormat($this->amount)), 2, '.', '');
            $api=' '.$this->pay_method->api_publishable_key;
            $order=time();
            $currency=$this->customlib->get_currencyShortName();
            $redirectUrl=base_url()."onlineadmission/mollie/complete";

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.mollie.com/v2/payments');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "amount[currency]=".$currency."&amount[value]=".$amount."&description=#".$order."&redirectUrl=".$redirectUrl);

            $headers = array();
            $headers[] = 'Authorization: Bearer'.$api;
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            $json = json_decode($result, true);
            
            if ($json['status']=='open') {
                $url = $json['_links']['checkout']['href'];
                $this->session->set_userdata("mollie_payment_id", $json['id']);
                header("Location: $url");
            }else {

            $data = array();
            $json = json_decode($result, true);
           
            $error = array();

        $reference = $this->session->userdata('reference');
        $data['setting'] = $this->setting;
        $total = $this->amount;
        $data['amount'] = $total;
        $data['error']=$json['detail'];
        $this->load->view('onlineadmission/mollie/index', $data);
        }
    }

    /**
     * This is a callback function for movies payment completion
     */
    public function complete()
    {
        $reference  = $this->session->userdata('reference');    
        
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $apply_date = date("Y-m-d H:i:s");
        
        $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date))));
        
        $api=' '.$this->pay_method->api_publishable_key;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.mollie.com/v2/payments/'.$this->session->userdata('mollie_payment_id'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $headers = array();
        $headers[] = 'Authorization: Bearer'.$api;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $json=json_decode($result);
        if ($json->status=='paid') {

            $amount = $this->session->userdata('payment_amount');
            $reference  = $this->session->userdata('reference');
            
            $currentdate = date('Y-m-d');
            $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
            $this->onlinestudent_model->edit($adddata);
        
            $transactionid                      = $json->id; 
            $gateway_response['online_admission_id']   = $reference;
            $gateway_response['paid_amount']    = $amount;
            $gateway_response['transaction_id'] = $transactionid;
            $gateway_response['payment_mode']   = 'mollie';
            $gateway_response['payment_type']   = 'online';
            $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_mollie_txn_id')  . $transactionid;
            $gateway_response['date']           = date("Y-m-d H:i:s");
            $return_detail                      = $this->onlinestudent_model->paymentSuccess($gateway_response);
            $sender_details = array('firstname' => $online_data->firstname, 'lastname' => $online_data->lastname, 'email' => $online_data->email,'date'=>$date,'reference_no'=>$online_data->reference_no,'mobileno'=>$online_data->mobileno,'paid_amount'=>$amount,'guardian_email'=>$online_data->guardian_email,'guardian_phone'=>$online_data->guardian_phone);
            $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
            redirect(base_url("onlineadmission/checkout/successinvoice/".$online_data->reference_no));

        } else {

            redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
        }

    }
}