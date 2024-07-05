<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pesapal extends Studentgateway_Controller {
public $api_config = "";
	public function __construct()
	{
		parent::__construct();

		$api_config = $this->paymentsetting_model->getActiveMethod();
		$this->setting = $this->setting_model->get();
		$this->setting[0]['currency_symbol'] = $this->customlib->getSchoolCurrencyFormat();
		$this->load->library('pesapal_lib');
		$this->load->library('mailsmsconf');
	}
 
 
	public function index()
	{  
        $data = array();
        $data['params'] = $this->session->userdata('params');
        $data['setting'] = $this->setting;
        $data['api_error']=array();
        $data['student_data'] = $this->student_model->get($data['params']['student_id']);
        $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
        $this->load->view('user/gateway/pesapal/index', $data);

	}

	public function pesapal_pay(){

		$this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required|xss_clean');

		if ($this->form_validation->run()==false) {
		$data = array();
		$data['params'] = $this->session->userdata('params');
		$data['setting'] = $this->setting;
		$data['api_error']=$data['api_error']=array();
		$data['student_data']=$this->student_model->get($data['params']['student_id']);
		$data['student_fees_master_array']=$data['params']['student_fees_master_array'];

		 $this->load->view('user/gateway/pesapal/index', $data);

		}else{

 
		$pesapal_details=$this->paymentsetting_model->getActiveMethod();
		$params = $this->session->userdata('params');
		$data = array();
		$student_id = $params['student_id'];
		$amount=number_format((float)(convertBaseAmountCurrencyFormat($params['fine_amount_balance']+$params['total'])), 2, '.', '');
		$data['total'] = $amount;
		$data['symbol'] = $params['invoice']->symbol;
		$data['currency_name'] = $params['invoice']->currency_name;
		$data['name'] = $params['name'];
		$data['guardian_phone'] = $params['guardian_phone'];
		$token = $params = NULL;
		$consumer_key = $pesapal_details->api_publishable_key;					
		$consumer_secret = $pesapal_details->api_secret_key;
		$signature_method = new OAuthSignatureMethod_HMAC_SHA1();
		$iframelink = 'https://www.pesapal.com/API/PostPesapalDirectOrderV4';     
		$amount = number_format($amount, 2);
		$desc = "Student Fee Payment";
		$type = 'MERCHANT'; 
		$reference = time();
		$first_name = $data['name']; 
		$last_name = ''; 
		$email = $_POST['email'];
		$phonenumber = $_POST['phone']; 
		$callback_url = base_url('user/gateway/pesapal/pesapal_response'); 
		$post_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><PesapalDirectOrderInfo xmlns:xsi=\"http://www.w3.org/2001/XMLSchemainstance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" Amount=\"".$amount."\" Description=\"".$desc."\" Type=\"".$type."\" Reference=\"".$reference."\" FirstName=\"".$first_name."\" LastName=\"".$last_name."\" Email=\"".$email."\" PhoneNumber=\"".$phonenumber."\" xmlns=\"http://www.pesapal.com\" />";
		$post_xml = htmlentities($post_xml);
		$consumer = new OAuthConsumer($consumer_key, $consumer_secret);
		$iframe_src = OAuthRequest::from_consumer_and_token($consumer, $token, "GET",
		$iframelink, $params);
		$iframe_src->set_parameter("oauth_callback", $callback_url);
		$iframe_src->set_parameter("pesapal_request_data", $post_xml);
		$iframe_src->sign_request($signature_method, $consumer, $token);
		$consumer = new OAuthConsumer($consumer_key, $consumer_secret);
		$iframe_src = OAuthRequest::from_consumer_and_token($consumer, $token, "GET",
		$iframelink, $params);
		$iframe_src->set_parameter("oauth_callback", $callback_url);
		$iframe_src->set_parameter("pesapal_request_data", $post_xml);
		$iframe_src->sign_request($signature_method, $consumer, $token);
		$data['iframe_src']=$iframe_src;
        $this->load->view('user/gateway/pesapal/pay', $data);
		
		}
}
      
 public function pesapal_response(){

			$pesapal_details=$this->paymentsetting_model->getActiveMethod();
			$reference = null;
			$pesapal_tracking_id = null;

			if(isset($_GET['pesapal_merchant_reference'])){
			$reference = $_GET['pesapal_merchant_reference'];
			}

			if(isset($_GET['pesapal_transaction_tracking_id'])){
			$pesapal_tracking_id = $_GET['pesapal_transaction_tracking_id'];
			}

			$consumer_key = $pesapal_details->api_publishable_key;
			$consumer_secret = $pesapal_details->api_secret_key;
			$statusrequestAPI = 'https://www.pesapal.com/api/querypaymentstatus';
			$pesapalTrackingId=$_GET['pesapal_transaction_tracking_id'];
			$pesapal_merchant_reference=$_GET['pesapal_merchant_reference'];



			if($pesapalTrackingId!='')

			{

			   $token = $params = NULL;

			   $consumer = new OAuthConsumer($consumer_key, $consumer_secret);

			   $signature_method = new OAuthSignatureMethod_HMAC_SHA1();


			   $request_status = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $statusrequestAPI, $params);

			   $request_status->set_parameter("pesapal_merchant_reference", $pesapal_merchant_reference);

			   $request_status->set_parameter("pesapal_transaction_tracking_id",$pesapalTrackingId);

			   $request_status->sign_request($signature_method, $consumer, $token);

			 

			   $ch = curl_init();

			   curl_setopt($ch, CURLOPT_URL, $request_status);

			   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			   curl_setopt($ch, CURLOPT_HEADER, 1);

			   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

			   if(defined('CURL_PROXY_REQUIRED')) if (CURL_PROXY_REQUIRED == 'True')

			   {

			      $proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;

			      curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);

			      curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);

			      curl_setopt ($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);

			   }
			   
			   $response = curl_exec($ch);
			   $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			   $raw_header  = substr($response, 0, $header_size - 4);
			   $headerArray = explode("\r\n\r\n", $raw_header);
			   $header      = $headerArray[count($headerArray) - 1];
			   $elements = preg_split("/=/",substr($response, $header_size));
			   $status = $elements[1];
			   if($status=='COMPLETED'){
				    $params = $this->session->userdata('params');
	                
	                $bulk_fees=array();
                    $params     = $this->session->userdata('params');
                 
                    foreach ($params['student_fees_master_array'] as $fee_key => $fee_value) {
                   
                     $json_array = array(
                        'amount'          =>  $fee_value['amount_balance'],
                        'date'            => date('Y-m-d'),
                        'amount_discount' => 0,
                        'amount_fine'     => $fee_value['fine_balance'],
                        'description'     => $this->lang->line('online_fees_deposit_through_pesapal_txn_id') . $reference.', Tracking_id' .$pesapalTrackingId,
                        'received_by'     => '',
                        'payment_mode'    => 'Pesapal',
                    );
 
                    $insert_fee_data = array(
                    	'fee_category'=>$fee_value['fee_category'],
                		'student_transport_fee_id'=>$fee_value['student_transport_fee_id'],
                        'student_fees_master_id' => $fee_value['student_fees_master_id'],
                        'fee_groups_feetype_id'  => $fee_value['fee_groups_feetype_id'],
                        'amount_detail'          => $json_array,
                    );                 
                   $bulk_fees[]=$insert_fee_data;
                    //========
                    }
                    $send_to     = $params['guardian_phone'];
                    $response = $this->studentfeemaster_model->fee_deposit_bulk($bulk_fees, $send_to);
                     //========================
                $student_id            = $this->customlib->getStudentSessionUserID();
                $student_current_class = $this->customlib->getStudentCurrentClsSection();
                $student_session_id    = $student_current_class->student_session_id;
                $fee_group_name        = [];
                $type                  = [];
                $code                  = [];

                $amount          = [];
                $fine_type       = [];
                $due_date        = [];
                $fine_percentage = [];
                $fine_amount     = [];
               
                $invoice     = []; 

                $student = $this->student_model->getStudentByClassSectionID($student_current_class->class_id, $student_current_class->section_id, $student_id);

                if ($response && is_array($response)) {
                    foreach ($response as $response_key => $response_value) {
                        $fee_category = $response_value['fee_category'];
                           $invoice[]   = array(
                            'invoice_id'     => $response_value['invoice_id'],
                            'sub_invoice_id' => $response_value['sub_invoice_id'],
                            'fee_category' => $fee_category,
                        );


                        if ($response_value['student_transport_fee_id'] != 0 && $response_value['fee_category'] == "transport") {

                            $data['student_fees_master_id']   = null;
                            $data['fee_groups_feetype_id']    = null;
                            $data['student_transport_fee_id'] = $response_value['student_transport_fee_id'];

                            $mailsms_array     = $this->studenttransportfee_model->getTransportFeeMasterByStudentTransportID($response_value['student_transport_fee_id']);
                            $fee_group_name[]  = $this->lang->line("transport_fees");
                            $type[]            = $mailsms_array->month;
                            $code[]            = "-";
                            $fine_type[]       = $mailsms_array->fine_type;
                            $due_date[]        = $mailsms_array->due_date;
                            $fine_percentage[] = $mailsms_array->fine_percentage;
                            $fine_amount[]     = $mailsms_array->fine_amount;
                            $amount[]          = $mailsms_array->amount;



                        } else {

                            $mailsms_array = $this->feegrouptype_model->getFeeGroupByIDAndStudentSessionID($response_value['fee_groups_feetype_id'], $student_session_id);

                            $fee_group_name[]  = $mailsms_array->fee_group_name;
                            $type[]            = $mailsms_array->type;
                            $code[]            = $mailsms_array->code;
                            $fine_type[]       = $mailsms_array->fine_type;
                            $due_date[]        = $mailsms_array->due_date;
                            $fine_percentage[] = $mailsms_array->fine_percentage;
                            $fine_amount[]     = $mailsms_array->fine_amount;

                            if ($mailsms_array->is_system) {
                                $amount[] = $mailsms_array->balance_fee_master_amount;
                            } else {
                                $amount[] = $mailsms_array->amount;
                            }

                        }

                    }
                    $obj_mail                     = [];
                    $obj_mail['student_id']  = $student_id;
                    $obj_mail['student_session_id'] = $student_session_id;

                    $obj_mail['invoice']         = $invoice;
                    $obj_mail['contact_no']      = $student['guardian_phone'];
                    $obj_mail['email']           = $student['email'];
                    $obj_mail['parent_app_key']  = $student['parent_app_key'];
                    $obj_mail['amount']         = "(".implode(',', $amount).")";
                    $obj_mail['fine_type']       = "(".implode(',', $fine_type).")";
                    $obj_mail['due_date']        = "(".implode(',', $due_date).")";
                    $obj_mail['fine_percentage'] = "(".implode(',', $fine_percentage).")";
                    $obj_mail['fine_amount']     = "(".implode(',', $fine_amount).")";
                    $obj_mail['fee_group_name']  = "(".implode(',', $fee_group_name).")";
                    $obj_mail['type']            = "(".implode(',', $type).")";
                    $obj_mail['code']            = "(".implode(',', $code).")";
                    $obj_mail['fee_category']    = $fee_category;
                    $obj_mail['send_type']    = 'group';


                    $this->mailsmsconf->mailsms('fee_submission', $obj_mail);

                }

                //=============================
                     if ($response) {
                          redirect(base_url("user/gateway/payment/successinvoice"));                     
                    } else {
                      redirect(base_url('user/gateway/payment/paymentfailed'));
                    }
                    
			    }else{
			       redirect(base_url("user/gateway/payment/paymentfailed"));
			    }
			}
			 
 
			   curl_close ($ch);

} 
}