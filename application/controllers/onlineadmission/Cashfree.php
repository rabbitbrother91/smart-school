<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cashfree extends OnlineAdmission_Controller
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
        $reference = $this->session->userdata('reference');
        $data['buyer_data'] = $this->onlinestudent_model->getAdmissionData($reference);
        $this->load->view('onlineadmission/cashfree/index', $data);
    }  
    
    public function pay()
    {
        $this->session->set_userdata('payment_amount',$this->amount);
        $insta_apikey    = $this->pay_method->api_secret_key;
        $insta_authtoken = $this->pay_method->api_publishable_key;
        $reference = $this->session->userdata('reference');
        $buyer_data = $this->onlinestudent_model->getAdmissionData($reference);
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required|xss_clean');
        $params = $this->session->userdata('params');
        if ($this->form_validation->run() == false) {
            $data['setting'] = $this->setting;
            $total = $this->amount;
            $data['amount'] = $total;
            $data['error']=array();
            $data['buyer_data'] = $buyer_data;
            $this->load->view('onlineadmission/cashfree/index', $data);
        }else{
        $amount =number_format((float)(convertBaseAmountCurrencyFormat($this->amount)), 2, '.', '');
        $customer_id="Reference_id_".$reference;
        $order_id="order_".time().mt_rand(100,999);
        $currency=$this->customlib->get_currencyShortName();
        
        $redirectUrl=base_url()."onlineadmission/cashfree/success?order_id={order_id}&order_token={order_token}";

        $my_array=array(
            "order_id"=>$order_id,
            "order_amount"=>($amount),
            "order_currency"=>$currency,
            "customer_details"=>array(
            "customer_id"=>$customer_id,
            "customer_name"=>$buyer_data->firstname." ".$buyer_data->middlename." ".$buyer_data->lastname,
            "customer_email"=>$_POST['email'],
            "customer_phone"=>$_POST['phone'],
            ),
            "order_meta"=> array(
            "return_url"=> $redirectUrl,
            "notify_url"=> base_url() .'webhooks/cashfree',
            "payment_methods"=> ""
            )
        );

        $new_arrya=(object)$my_array;
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.cashfree.com/pg/orders');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($new_arrya));

            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'X-Api-Version: 2021-05-21';
            $headers[] = 'X-Client-Id: '.$this->pay_method->api_publishable_key;
            $headers[] = 'X-Client-Secret: '.$this->pay_method->api_secret_key;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            $json=json_decode($result);

            if (isset($json->order_status) && $json->order_status="ACTIVE") {
                $url = $json->payment_link;
                header("Location: $url");
            } else {

            $data = array();
            
            $error = array();
            $data['setting'] = $this->setting;
            $total = $this->amount;
            $data['amount'] = $total;
            $data['error']=$json->message;
            $data['buyer_data'] = $buyer_data;
            $this->load->view('onlineadmission/cashfree/index', $data);
        }
    }
    }

    /**
     * This is a callback function for movies payment completion
     */
    public function success()
    {
        $reference  = $this->session->userdata('reference');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $apply_date = date("Y-m-d H:i:s");
        
        $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date)))); 
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.cashfree.com/pg/orders/'.$_GET['order_id']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'X-Api-Version: 2021-05-21';
        $headers[] = 'X-Client-Id: '.$this->pay_method->api_publishable_key;
        $headers[] = 'X-Client-Secret: '.$this->pay_method->api_secret_key;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $payment_data=json_decode($result);

       if (isset($payment_data->order_status) && $payment_data->order_status=="PAID") {
            
            $currentdate = date('Y-m-d');
            $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
            $this->onlinestudent_model->edit($adddata);
                
            $amount = $this->session->userdata('payment_amount');
             
            $transactionid                      = $_GET['order_id']; 
            $gateway_response['online_admission_id']   = $reference;
            $gateway_response['paid_amount']    = $amount;
            $gateway_response['transaction_id'] = $transactionid;
            $gateway_response['payment_mode']   = 'cashfree';
            $gateway_response['payment_type']   = 'online';
            $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_cashfree_txn_id')  . $transactionid;
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