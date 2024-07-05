<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stripe extends OnlineAdmission_Controller
{

    public $pay_method = "";
    public $amount = 0;

    function __construct() {
        parent::__construct();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->getSetting();
        $this->amount = $this->setting->online_admission_amount;
        $this->load->library('stripe_payment');
        $this->load->library('mailsmsconf');
        $this->load->model('onlinestudent_model');

    }

    public function index() {

        $reference = $this->session->userdata('reference');
        $data['setting'] = $this->setting;
        
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $total = $this->amount;
        $data['amount'] = ($total);
        $data['name'] = $online_data->firstname." ".$online_data->lastname;
        $data['currency_name'] = $this->customlib->get_currencyShortName();
        
        $data['api_publishable_key'] = $this->pay_method->api_publishable_key;
        $this->load->view('onlineadmission/stripe/index', $data);
    }

    public function complete() {
        
        $stripeToken         = $this->input->post('stripeToken');
        $stripeTokenType     = $this->input->post('stripeTokenType');
        $stripeEmail         = $this->input->post('stripeEmail');
        $data                = $this->input->post();
        $data['stripeToken'] = $stripeToken;
        $data['total']  = $this->amount;
        $data['description'] = $this->lang->line('online_admission_form_fees');
        $data['currency']    = $this->customlib->get_currencyShortName();
        $response            = $this->stripe_payment->payment($data);
  
        if ($response->isSuccessful()) {
            $transactionid = $response->getTransactionReference();
            $response      = $response->getData();
            if ($response['status'] == 'succeeded') {
                $amount = $this->session->userdata('payment_amount');
                $reference  = $this->session->userdata('reference');
                $online_data = $this->onlinestudent_model->getAdmissionData($reference);
                $apply_date=date("Y-m-d H:i:s");               
                
                $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date)))); 
                        
                $currentdate = date('Y-m-d');
                $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
                $this->onlinestudent_model->edit($adddata);
                
                $gateway_response['online_admission_id']   = $reference; 
                $gateway_response['paid_amount']    = $this->amount;
                $gateway_response['transaction_id'] = $transactionid;
                $gateway_response['payment_mode']   = 'stripe';
                $gateway_response['payment_type']   = 'online';
                $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_stripe_txn_id')   . $transactionid;
                $gateway_response['date']           = date("Y-m-d H:i:s");
                $return_detail                      = $this->onlinestudent_model->paymentSuccess($gateway_response);
				 
                $sender_details = array('firstname' => $online_data->firstname, 'lastname' => $online_data->lastname, 'email' => $online_data->email,'date'=>$date,'reference_no'=>$online_data->reference_no,'mobileno'=>$online_data->mobileno,'paid_amount'=>$this->amount,'guardian_email'=>$online_data->guardian_email,'guardian_phone'=>$online_data->guardian_phone);
              
 $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
                
                redirect(base_url("onlineadmission/checkout/successinvoice//".$online_data->reference_no));
            }
        } elseif ($response->isRedirect()) {
            $response->redirect();
        } else {
            redirect(site_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
        }
    }

}

?>