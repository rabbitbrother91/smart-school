<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Paystack extends OnlineAdmission_Controller
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
        $data['error'] = '';
        $this->load->view('onlineadmission/paystack/index', $data);
    } 

    public function pay()
    {
        $reference = $this->session->userdata('reference');

        $amount       = convertBaseAmountCurrencyFormat($this->amount) * 100;
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $this->session->set_userdata('payment_amount',$this->amount);
        $ref          = time() . "02";
        $callback_url = base_url() . 'onlineadmission/paystack/complete/' . $ref;
        $postdata     = array('email' => $online_data->email, 'amount' => $amount, "reference" => $ref, "callback_url" => $callback_url);
        $url          = "https://api.paystack.co/transaction/initialize";
        $ch           = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata)); //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $headers = [
            'Authorization: Bearer ' . $this->pay_method->api_secret_key,
            'Content-Type: application/json',
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $request = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($request, true);
        
        if ($result['status']) {

            $redir = $result['data']['authorization_url'];
            header("Location: " . $redir);
        }else{
            $data['error']="<div class='alert alert-danger'>".$result['message']."</div>";
            $reference = $this->session->userdata('reference');
        $data['setting'] = $this->setting;
        $total = $this->amount;
        $data['amount'] = $total;
        $this->load->view('onlineadmission/paystack/index', $data);
        }
    }

    public function complete($ref)
    {
        $amount = $this->amount;
        $reference  = $this->session->userdata('reference');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $apply_date=date("Y-m-d H:i:s");
        
        $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date))));
        
        $result   = array();
        $url      = 'https://api.paystack.co/transaction/verify/' . $ref;
        $ch       = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $this->pay_method->api_secret_key]
        );
        $request = curl_exec($ch);
        curl_close($ch);

        if ($request) {
            $result = json_decode($request, true);

            if ($result) {
                if ($result['data']) {
                    //something came in
                    if ($result['data']['status'] == 'success') {
                        
                        $currentdate = date('Y-m-d');
                        $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
                        $this->onlinestudent_model->edit($adddata);
                    
                        $gateway_response['online_admission_id']   = $reference; 
                        $gateway_response['paid_amount']    = $amount;
                        $gateway_response['transaction_id'] = $ref;
                        $gateway_response['payment_mode']   = 'paystack';
                        $gateway_response['payment_type']   = 'online';
                        $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_paystack_txn_id') . $transactionid;
                        $gateway_response['date']           = date("Y-m-d H:i:s");
                        $return_detail                      = $this->onlinestudent_model->paymentSuccess($gateway_response);
                         $sender_details = array('firstname' => $online_data->firstname, 'lastname' => $online_data->lastname, 'email' => $online_data->email,'date'=>$date,'reference_no'=>$online_data->reference_no,'mobileno'=>$online_data->mobileno,'paid_amount'=>$this->amount,'guardian_email'=>$online_data->guardian_email,'guardian_phone'=>$online_data->guardian_phone);
                        $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
                        redirect(base_url("onlineadmission/checkout/successinvoice/".$online_data->reference_no));
                    } else {
                        // the transaction was not successful, do not deliver value'
                        //uncomment this line to inspect the result, to check why it failed.
                        redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
                    }
                } else {

                    redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
                }
            } else {

                //die("Something went wrong while trying to convert the request variable to json. Uncomment the print_r command to see what is in the result variable.");
                redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
            }
        } else {           
            //die("Something went wrong while executing curl. Uncomment the var_dump line above this line to see what the issue is. Please check your CURL command to make sure everything is ok");
            redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
        }
    }

}