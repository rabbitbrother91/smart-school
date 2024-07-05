<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Paypal extends OnlineAdmission_Controller
{

    public $pay_method = "";
    public $amount = 0;

    function __construct() {
        parent::__construct();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->getSetting();
        $this->amount = $this->setting->online_admission_amount;
        $this->currency = $this->customlib->get_currencyShortName();
        $this->load->library('paypal_payment');
        $this->load->library('mailsmsconf');
        $this->load->model('onlinestudent_model');
    }

    public function index() {
        $this->session->set_userdata('payment_amount',$this->amount);
        $data['setting'] = $this->setting;
        $total = $this->amount;
        $data['amount'] = $total;
        $this->load->view('onlineadmission/paypal/index', $data);
    } 
 

    public function checkout()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            if ($this->session->has_userdata('payment_amount')) {
                $setting                = $this->setting;
                $reference = $this->session->userdata('reference');
                $total = $this->amount;
                $online_data = $this->onlinestudent_model->getAdmissionData($reference);
                $data["id"]             = $reference;
                $data['total']          = convertBaseAmountCurrencyFormat($total);
                $data['productinfo']    = "Online Admission Fees";
                $data['guardian_phone'] = "";
                $data['name']           = $online_data->firstname." ".$online_data->lastname;
                $payment = array(
                'guardian_phone' => $data['guardian_phone'],
                'name' => $data['name'],
                'description' => $data['productinfo'],
                'amount' => $data['total'],
                'currency' => $this->customlib->get_currencyShortName(),
                );

                $payment['cancelUrl'] = base_url('onlineadmission/paypal/getsuccesspayment');
                $payment['returnUrl'] = base_url('onlineadmission/paypal/getsuccesspayment');
                $response               = $this->paypal_payment->payment($payment);
                if ($response->isSuccessful()) {

                } elseif ($response->isRedirect()) {
                    $response->redirect();
                } else {

                    echo $response->getMessage();
                }
            }
        }
    }

    //paypal successpayment
    public function getsuccesspayment() {
        $reference = $this->session->userdata('reference');
        $total = $this->amount;
        $online_data = $this->onlinestudent_model->getAdmissionData($reference);
        $data["id"]             = $reference;
        $data['total']          = $total;
        $data['productinfo']    = "Online Admission Fees";
        $data['guardian_phone'] = "";
        $data['name']           = $online_data->firstname." ".$online_data->lastname;
        $success_data = array(
            'guardian_phone' => $data['guardian_phone'],
            'name' => $data['name'],
            'description' => "Online Admission Fees",
            'amount' => $data['total'],
            'currency' => $this->currency,
        );

        $success_data['cancelUrl'] = base_url('onlineadmission/paypal/getsuccesspayment');
        $success_data['returnUrl'] = base_url('onlineadmission/paypal/getsuccesspayment');
        $response = $this->paypal_payment->success($success_data);

        $paypalResponse = $response->getData();
        if ($response->isSuccessful()) {
            $purchaseId = $_GET['PayerID'];
 
            if (isset($paypalResponse['PAYMENTINFO_0_ACK']) && $paypalResponse['PAYMENTINFO_0_ACK'] === 'Success') {
                if ($purchaseId) {
                    
                    $currentdate = date('Y-m-d');
                    $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
                    $this->onlinestudent_model->edit($adddata);
                    
                    $ref_id = $paypalResponse['PAYMENTINFO_0_TRANSACTIONID'];
                    $gateway_response['online_admission_id']   = $reference;
                    $gateway_response['paid_amount']    = $total;
                    $gateway_response['transaction_id'] = $ref_id;
                    $gateway_response['payment_mode']   = 'paypal';
                    $gateway_response['payment_type']   = 'online';
                    $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_paypal_txn_id') . $ref_id;
                    $gateway_response['date']           = date("Y-m-d H:i:s");
                    $return_detail                      = $this->onlinestudent_model->paymentSuccess($gateway_response);
                    
                    $apply_date =   date("Y-m-d H:i:s");
        
                    $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date))));  
        
                    $sender_details = array('firstname' => $online_data->firstname, 'lastname' => $online_data->lastname, 'email' => $online_data->email,'date'=>$date,'reference_no'=>$online_data->reference_no,'mobileno'=>$online_data->mobileno,'paid_amount'=>$this->amount,'guardian_email'=>$online_data->guardian_email,'guardian_phone'=>$online_data->guardian_phone);
                    $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
                    redirect(base_url("onlineadmission/checkout/successinvoice/".$online_data->reference_no));
                }
            }
        } elseif ($response->isRedirect()) {
            $response->redirect();
        } else {
            redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
        }
    }
 
}

?>