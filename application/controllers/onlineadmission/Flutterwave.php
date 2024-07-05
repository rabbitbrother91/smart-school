<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Flutterwave extends OnlineAdmission_Controller
{

    public $pay_method = "";
    public $amount     = 0;

    public function __construct()
    {
        parent::__construct();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
        $this->setting    = $this->setting_model->getSetting();
        $this->amount     = $this->setting->online_admission_amount;
        $this->load->library('mailsmsconf');
        $this->load->model('onlinestudent_model');
    }

    public function index()
    {
        $reference       = $this->session->userdata('reference');
  $online_data    = $this->onlinestudent_model->getAdmissionData($reference);
        $data['setting'] = $this->setting;
        $total           = $this->amount;
        $data['amount']  = $total;        
        $this->load->view('onlineadmission/flutterwave/index', $data);
    }

    public function pay()
    {
        $amount         = $this->amount;
        $curl           = curl_init();
        $reference      = $this->session->userdata('reference');
        $customer_email = $this->onlinestudent_model->getAdmissionData($reference)->email;
        $currency       = $this->customlib->get_currencyShortName();
        $txref          = "rave" . uniqid(); // ensure you generate unique references per transaction.
        // get your public key from the dashboard.
        $PBFPubKey    = $this->pay_method->api_publishable_key;
        $redirect_url = base_url() . 'onlineadmission/flutterwave/complete'; // Set your own redirect URL

        curl_setopt_array($curl, array(
            CURLOPT_URL            => "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/hosted/pay",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => json_encode([
                'amount'         => convertBaseAmountCurrencyFormat($amount),
                'customer_email' => $customer_email,
                'currency'       => $currency,
                'txref'          => $txref,
                'PBFPubKey'      => $PBFPubKey,
                'redirect_url'   => $redirect_url,
            ]),
            CURLOPT_HTTPHEADER     => [
                "content-type: application/json",
                "cache-control: no-cache",
            ],
        ));

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        if ($err) {
            // there was an error contacting the rave API
            die('Curl returned error: ' . $err);
        }

        $transaction = json_decode($response);


 if (!$transaction->data && !$transaction->data->link) {
            // there was an error from the API
            print_r('API returned error: ' . $transaction->message);
redirect(base_url("onlineadmission/checkout/paymentfailed/" . $online_data->reference_no));
        }elseif(isset($transaction->status) && ($transaction->status=='error')){
  print_r('API returned error: ' . $transaction->message);
redirect(base_url("onlineadmission/checkout/paymentfailed/" . $online_data->reference_no));
}
       

        // redirect to page so User can pay

        header('Location: ' . $transaction->data->link);
    }

    public function complete()
    {
        $details        = $this->paymentsetting_model->getActiveMethod();
        $api_secret_key = $details->api_secret_key;
        $reference      = $this->session->userdata('reference');
        $online_data    = $this->onlinestudent_model->getAdmissionData($reference);
        $apply_date     = date("Y-m-d H:i:s");
        
        $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date)))); 
        
        if (isset($_GET['txref']) && $_GET['cancelled'] != 'true') {
            $ref       = $_GET['txref'];
            $amount    = $this->session->userdata('payment_amount');
            // $reference = $this->session->userdata('reference');
            $currency  = $this->setting->currency; //Correct Currency from Server

            $query = array(
                "SECKEY" => $api_secret_key,
                "txref"  => $ref,
            );

            $data_string = json_encode($query);

            $ch = curl_init('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

            $response = curl_exec($ch);

            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header      = substr($response, 0, $header_size);
            $body        = substr($response, $header_size);

            curl_close($ch);

            $resp = json_decode($response, true);

            $paymentStatus      = $resp['data']['status'];
            $chargeResponsecode = $resp['data']['chargecode'];
            $chargeAmount       = $resp['data']['amount'];
            $chargeCurrency     = $resp['data']['currency'];

            if (($chargeResponsecode == "00" || $chargeResponsecode == "0") && ($chargeAmount == $amount) && ($chargeCurrency == $currency)) {
                
                $currentdate = date('Y-m-d');
                $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
                $this->onlinestudent_model->edit($adddata);
                    
                $transactionid                      = $ref;
                $gateway_response['paid_amount']    = $amount;
                $gateway_response['online_admission_id']   = $reference;
                $gateway_response['transaction_id'] = $transactionid;
                $gateway_response['payment_mode']   = 'flutterwave';
                $gateway_response['payment_type']   = 'online';
                $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_flutterwave_txn_id')  . $transactionid;
                $gateway_response['total_amount']   = $amount;
                $return_detail                      = $this->onlinestudent_model->paymentSuccess($gateway_response);

                $sender_details = array(
                    'firstname'      => $online_data->firstname,
                    'lastname'       => $online_data->lastname,
                    'email'          => $online_data->email,
                    'date'           => $date,
                    'reference_no'   => $online_data->reference_no,
                    'mobileno'       => $online_data->mobileno,
                    'paid_amount'    => $amount,
                    'guardian_email' => $online_data->guardian_email,
                    'guardian_phone' => $online_data->guardian_phone,
                );

                $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
                redirect(base_url("onlineadmission/checkout/successinvoice/" . $online_data->reference_no));
            } else {
                redirect(base_url("onlineadmission/checkout/paymentfailed/" . $online_data->reference_no));
            }
        } else {
            die('No reference supplied');
        }
    }

}
