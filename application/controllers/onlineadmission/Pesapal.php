<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pesapal extends OnlineAdmission_Controller
{

    public $pay_method = "";
    public $amount = 0;

    function __construct() {
        parent::__construct();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->getSetting();
        $this->amount = $this->setting->online_admission_amount;
		$this->load->library('pesapal_lib');
        $this->load->library('mailsmsconf');
        $this->load->model('onlinestudent_model');
    }

    public function index() {

        $reference = $this->session->userdata('reference');
        $data['setting'] = $this->setting;
        $total = $this->amount;
        $data['amount'] = $total;
        $this->load->view('onlineadmission/pesapal/index', $data);
    } 

	public function pay()
    {
    	$this->session->set_userdata('payment_amount',$this->amount);
		$reference_id = $this->session->userdata('reference');
		$online_data = $this->onlinestudent_model->getAdmissionData($reference_id);
        $data['amount']   = convertBaseAmountCurrencyFormat($this->amount);
        $token            = $params            = null;
        $consumer_key     = $this->pay_method->api_publishable_key;
        $consumer_secret  = $this->pay_method->api_secret_key;
        $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
        $iframelink       = 'https://www.pesapal.com/API/PostPesapalDirectOrderV4';
        $amount           = number_format(convertBaseAmountCurrencyFormat($data['amount']), 2);
        $desc             = $this->lang->line('online_admission_form_fees');
        $type             = 'MERCHANT';
        $reference        = time();
        $first_name       = $online_data->firstname;
        $last_name        = $online_data->lastname;
        $email            = $online_data->email;
        $phonenumber      = $online_data->mobileno;
        $callback_url     = base_url('onlineadmission/pesapal/complete');
        $post_xml         = "<?xml version=\"1.0\" encoding=\"utf-8\"?><PesapalDirectOrderInfo xmlns:xsi=\"http://www.w3.org/2001/XMLSchemainstance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" Amount=\"" . $amount . "\" Description=\"" . $desc . "\" Type=\"" . $type . "\" Reference=\"" . $reference . "\" FirstName=\"" . $first_name . "\" LastName=\"" . $last_name . "\" Email=\"" . $email . "\" PhoneNumber=\"" . $phonenumber . "\" xmlns=\"http://www.pesapal.com\" />";
        $post_xml         = htmlentities($post_xml);
        $consumer         = new OAuthConsumer($consumer_key, $consumer_secret);
        $iframe_src       = OAuthRequest::from_consumer_and_token($consumer, $token, "GET",
            $iframelink, $params);
        $iframe_src->set_parameter("oauth_callback", $callback_url);
        $iframe_src->set_parameter("pesapal_request_data", $post_xml);
        $iframe_src->sign_request($signature_method, $consumer, $token);
        $consumer   = new OAuthConsumer($consumer_key, $consumer_secret);
        $iframe_src = OAuthRequest::from_consumer_and_token($consumer, $token, "GET",
            $iframelink, $params);
        $iframe_src->set_parameter("oauth_callback", $callback_url);
        $iframe_src->set_parameter("pesapal_request_data", $post_xml);
        $iframe_src->sign_request($signature_method, $consumer, $token);
        $data['iframe_src'] = $iframe_src;
        $this->load->view("onlineadmission/pesapal/pay", $data);
    }
      
	public function complete()
    {

        $reference           = null;
        $pesapal_tracking_id = null;

        if (isset($_GET['pesapal_merchant_reference'])) {
            $reference = $_GET['pesapal_merchant_reference'];
        }

        if (isset($_GET['pesapal_transaction_tracking_id'])) {
            $pesapal_tracking_id = $_GET['pesapal_transaction_tracking_id'];
        }

        $consumer_key               = $this->pay_method->api_publishable_key;
        $consumer_secret            = $this->pay_method->api_secret_key;
        $statusrequestAPI           = 'https://www.pesapal.com/api/querypaymentstatus';
        $pesapalTrackingId          = $_GET['pesapal_transaction_tracking_id'];
        $pesapal_merchant_reference = $_GET['pesapal_merchant_reference'];

        if ($pesapalTrackingId != '') {

            $token = $params = null;

            $consumer = new OAuthConsumer($consumer_key, $consumer_secret);

            $signature_method = new OAuthSignatureMethod_HMAC_SHA1();

            $request_status = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $statusrequestAPI, $params);

            $request_status->set_parameter("pesapal_merchant_reference", $pesapal_merchant_reference);

            $request_status->set_parameter("pesapal_transaction_tracking_id", $pesapalTrackingId);

            $request_status->sign_request($signature_method, $consumer, $token);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $request_status);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_HEADER, 1);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            if (defined('CURL_PROXY_REQUIRED')) {
                if (CURL_PROXY_REQUIRED == 'True') {

                    $proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;

                    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);

                    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);

                    curl_setopt($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);

                }
            }

            $response    = curl_exec($ch);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $raw_header  = substr($response, 0, $header_size - 4);
            $headerArray = explode("\r\n\r\n", $raw_header);
            $header      = $headerArray[count($headerArray) - 1];
            $elements    = preg_split("/=/", substr($response, $header_size));
            $status      = $elements[1];
            if ($status == 'COMPLETED') {
                $reference  = $this->session->userdata('reference');
                
                $currentdate = date('Y-m-d');
                $adddata = array('id' => $reference, 'form_status' => 1, 'submit_date' => $currentdate);
                $this->onlinestudent_model->edit($adddata);
                    
                $online_data = $this->onlinestudent_model->getAdmissionData($reference);
                $apply_date=date("Y-m-d H:i:s");
                
                $date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($apply_date))));  

                $amount                           = $this->amount;
                $transactionid                      = $pesapal_tracking_id;
                $gateway_response['online_admission_id']   = $reference; 
				$gateway_response['paid_amount']    = $amount;
				$gateway_response['transaction_id'] = $transactionid;
				$gateway_response['payment_mode']   = 'pesapal';
				$gateway_response['payment_type']   = 'online';
				$gateway_response['note']           = $this->lang->line('online_fees_deposit_through_pesapal_txn_id') . $transactionid;
				$gateway_response['date']           = date("Y-m-d H:i:s");
				$return_detail                      = $this->onlinestudent_model->paymentSuccess($gateway_response);
                 $sender_details = array('firstname' => $online_data->firstname, 'lastname' => $online_data->lastname, 'email' => $online_data->email,'date'=>$date,'reference_no'=>$online_data->reference_no,'mobileno'=>$online_data->mobileno,'paid_amount'=>$this->amount,'guardian_email'=>$online_data->guardian_email,'guardian_phone'=>$online_data->guardian_phone);
                $this->mailsmsconf->mailsms('online_admission_fees_submission', $sender_details);
                redirect(base_url("onlineadmission/checkout/successinvoice/".$online_data->reference_no));
            } else {
                redirect(base_url("onlineadmission/checkout/paymentfailed/".$online_data->reference_no));
            }
        }

        curl_close($ch);
    }
   
}