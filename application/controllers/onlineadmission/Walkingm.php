<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Walkingm extends OnlineAdmission_Controller
{

    public $pay_method = "";
    public $amount = 0;

    function __construct() {
        parent::__construct();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->getSetting();
        $this->amount = $this->setting->online_admission_amount;
        $this->load->library(array('walkingm_lib','mailsmsconf'));
        $this->load->model('onlinestudent_model');
    }

    public function index() {

        $reference = $this->session->userdata('reference');
        $data['setting'] = $this->setting;
        $total = $this->amount;
        $data['api_error'] = "";
        $data['amount'] = $total;
        $this->load->view('onlineadmission/walkingm/index', $data);
    } 

    public function pay(){

    	$this->form_validation->set_rules('email', $this->lang->line('walkingm_email'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', $this->lang->line('walkingm_password'), 'trim|required|xss_clean');
        $params = $this->session->userdata('params');
        if ($this->form_validation->run() == false) {
              $reference = $this->session->userdata('reference');
        $data['setting'] = $this->setting;
        $total = $this->amount;
        $data['amount'] = $total;
        $data['api_error'] = "";
        $this->load->view('onlineadmission/walkingm/index', $data);
        }else{
           
          $payment_array['payer']="Walkingm";
          $payment_array['amount']=convertBaseAmountCurrencyFormat($this->amount);
          $payment_array['currency']=$this->customlib->get_currencyShortName();
          $payment_array['successUrl']=base_url()."onlineadmission/walkingm/success";
          $payment_array['cancelUrl']=base_url()."onlineadmission/walkingm/cancel";
          $responce= $this->walkingm_lib->walkingm_login($_POST['email'],$_POST['password'],$payment_array);

          if($responce!=""){
            $reference = $this->session->userdata('reference');
        $data['setting'] = $this->setting;
        $total = $this->amount;
        $data['api_error'] = $responce;
        $data['amount'] = $total;
        $this->load->view('onlineadmission/walkingm/index', $data);
          }
        }
    }

    public function success() {
        $this->output->enable_profiler();
    	$amount = $this->amount;
        $reference  = $this->session->userdata('reference');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $apply_date=date("Y-m-d H:i:s");
        
        $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date)))); 

        $data   = array();
          $responce= base64_decode($_SERVER["QUERY_STRING"]);
        $payment_responce=json_decode($responce);
        
        if ($responce != '' && $payment_responce->status=200) {
            
            $currentdate = date('Y-m-d');
            $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
            $this->onlinestudent_model->edit($adddata);
                    
            $transaction_id = $payment_responce->transaction_id; 
            $gateway_response['online_admission_id']   = $reference; 
            $gateway_response['paid_amount']    = $this->amount;
            $gateway_response['transaction_id'] = $transaction_id;
            $gateway_response['payment_mode']   = 'billplz';
            $gateway_response['payment_type']   = 'online';
            $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_walkingm_txn_id') . $transaction_id;
            $gateway_response['date']           = date("Y-m-d H:i:s");
            $return_detail                      = $this->onlinestudent_model->paymentSuccess($gateway_response);
             $sender_details = array('firstname' => $online_data->firstname, 'lastname' => $online_data->lastname, 'email' => $online_data->email,'date'=>$date,'reference_no'=>$online_data->reference_no,'mobileno'=>$online_data->mobileno,'paid_amount'=>$this->amount,'guardian_email'=>$online_data->guardian_email,'guardian_phone'=>$online_data->guardian_phone);
            $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
            redirect(base_url("onlineadmission/checkout/successinvoice/".$online_data->reference_no));
        } else {
            redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
        }
    }

    public function cancel(){
        $reference  = $this->session->userdata('reference');
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
    }

}