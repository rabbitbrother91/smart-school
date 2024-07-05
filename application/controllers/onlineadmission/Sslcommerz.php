<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sslcommerz extends OnlineAdmission_Controller
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
        $this->reference       = $this->session->userdata('reference');
    }

    public function index()
    {

        $reference       = $this->session->userdata('reference');
        $data['setting'] = $this->setting;
        $total           = $this->amount;
        $data['amount']  = $total;
        $data['error']="";
        $this->load->view('onlineadmission/sslcommerz/index', $data);
    }

    public function pay()
    {
        $this->session->set_userdata('payment_amount', $this->amount);
        $online_data        = $this->onlinestudent_model->getAdmissionData($this->reference);
        $amount             = $this->amount;
        $requestData        = array();
        $CURLOPT_POSTFIELDS = array(
            'store_id'         => $this->pay_method->api_publishable_key,
            'store_passwd'     => $this->pay_method->api_password,
            'total_amount'     => convertBaseAmountCurrencyFormat($amount),
            'currency'         => $this->customlib->get_currencyShortName(),
            'tran_id'          => abs(crc32(uniqid())),
            'success_url'      => base_url() . 'onlineadmission/sslcommerz/success',
            'fail_url'         => base_url() . 'onlineadmission/sslcommerz/fail',
            'cancel_url'       => base_url() . 'onlineadmission/sslcommerz/cancel',
            'cus_name'         => $online_data->firstname . " " . $online_data->lastname,
            'cus_email'        => !empty($online_data->email) ? $online_data->email : "example@email.com",
            'cus_add1'         => !empty($online_data->permanent_address) ? $online_data->permanent_address : "Dhaka",
            'cus_phone'        => !empty($online_data->mobileno) ? $online_data->mobileno : "01711111111",
            'cus_city'         => '',
            'cus_country'      => '',
            'multi_card_name'  => 'mastercard,visacard,amexcard,internetbank,mobilebank,othercard ',
            'shipping_method'  => 'NO',
            'product_name'     => 'test',
            'product_category' => 'Electronic',
            'product_profile'  => 'general',
        );
        $string = "";
        foreach ($CURLOPT_POSTFIELDS as $key => $value) {
            $string .= $key . '=' . $value . "&";
            if ($key == 'product_profile') {
                $string .= $key . '=' . $value;
            }
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://securepay.sslcommerz.com/gwprocess/v4/api.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$string");

        $headers   = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $response = json_decode($result);
       
        if($response->status=='FAILED'){
        $reference       = $this->session->userdata('reference');
        $data['setting'] = $this->setting;
        $total           = $this->amount;
        $data['amount']  = $total;
        $data['error']  = $response->failedreason;
        $this->load->view('onlineadmission/sslcommerz/index', $data);
        }else{
            header("Location: $response->GatewayPageURL");
        }
    }

    public function success()
    {
        if ($_POST['status'] == 'VALID') {
            $amount                             = $this->amount;
            $reference                          = $this->session->userdata('reference');
            
            $currentdate = date('Y-m-d');
            $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
            $this->onlinestudent_model->edit($adddata);
                    
            $online_data                        = $this->onlinestudent_model->getAdmissionData($reference);
            $apply_date=date("Y-m-d H:i:s");
            
            $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date)))); 
            
            $transactionid                      = $_POST['val_id'];
            $gateway_response['online_admission_id']   = $reference;
            $gateway_response['paid_amount']    = $amount;
            $gateway_response['transaction_id'] = $transactionid;
            $gateway_response['payment_mode']   = 'sslcommerz';
            $gateway_response['payment_type']   = 'online';
            $gateway_response['note']           = $this->lang->line('online_fees_deposit_through_sslcommerz_txn_id') . $transactionid;
            $gateway_response['date']           = date("Y-m-d H:i:s");
            $return_detail                      = $this->onlinestudent_model->paymentSuccess($gateway_response);
             $sender_details = array('firstname' => $online_data->firstname, 'lastname' => $online_data->lastname, 'email' => $online_data->email,'date'=>$date,'reference_no'=>$online_data->reference_no,'mobileno'=>$online_data->mobileno,'paid_amount'=>$this->amount,'guardian_email'=>$online_data->guardian_email,'guardian_phone'=>$online_data->guardian_phone);
            $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
            redirect(base_url("onlineadmission/checkout/successinvoice/" . $online_data->reference_no));
        } else {

            redirect(base_url("onlineadmission/checkout/paymentfailed/" . $online_data->reference_no));
        }

    }

    public function fail()
    {

        redirect(base_url("onlineadmission/checkout/paymentfailed/" . $online_data->reference_no));

    }
    public function cancel()
    {

        redirect(base_url("onlineadmission/checkout/paymentfailed/" . $online_data->reference_no));

    }

}