<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Onepay extends Studentgateway_Controller {

     public $api_config = "";

    public function __construct() {
        parent::__construct();

        $this->api_config = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->get();
        $this->setting[0]['currency_symbol'] = $this->customlib->getSchoolCurrencyFormat();
        $this->load->library('mailsmsconf');
    }
  
    public function index() {
 
        $data = array();
        $data['params'] = $this->session->userdata('params');
        $data['setting'] = $this->setting;
        $data['api_error'] = array();
        $data['student_data'] = $this->student_model->get($data['params']['student_id']);
        $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
        $this->load->view('user/gateway/onepay/index', $data);
    }
 
    public function pay() {
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required|xss_clean');
        $params = $this->session->userdata('params');
        if ($this->form_validation->run() == false) {
            $data = array();
            $data['params'] = $this->session->userdata('params');
            $data['setting'] = $this->setting;
            $data['api_error'] = array();
            $data['student_data'] = $this->student_model->get($data['params']['student_id']);
            $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
            $data['api_error'] = $data['api_error'] = array();
            $data['student_data'] = $this->student_model->get($params['student_id']);
            $this->load->view('user/gateway/onepay/index', $data);
        } else {
			
            $data = array();
            $data['name'] = $params['name'];
            $amount =number_format((float)(convertBaseAmountCurrencyFormat($params['fine_amount_balance']+$params['total'])), 2, '.', '');
            $params       = $this->session->userdata('params');
        $student_data = $this->student_model->get($params['student_id']);
        $appendAmp = 0;
        $SECURE_SECRET =$this->api_config->api_signature;
        $payment_data=array(
        'AVS_City' => '',
        'AVS_Country' =>'',
        'AVS_PostCode' => '',
        'AVS_StateProv' => '',
        'AVS_Street01' => '',
        'AgainLink' => urlencode($_SERVER['HTTP_REFERER']),
        'Title' => '',
        'display' => '',
        'vpc_AccessCode' => $this->api_config->salt,
        'vpc_Amount' => $amount*100,
        'vpc_Command' => 'pay',
        'vpc_Customer_Email' => '',
        'vpc_Customer_Id' => '',
        'vpc_Customer_Phone' => '',
        'vpc_Locale' => 'en',
        'vpc_MerchTxnRef' => date('YmdHis') . rand(),
        'vpc_Merchant' => $this->api_config->api_publishable_key,
        'vpc_OrderInfo' => 'JSECURETEST01',
        'vpc_ReturnURL' => base_url() . 'user/gateway/onepay/success',
        'vpc_SHIP_City' => '',
        'vpc_SHIP_Country' => '',
        'vpc_SHIP_Provice' => '',
        'vpc_SHIP_Street01' => '',
        'vpc_TicketNo' => $_SERVER ['REMOTE_ADDR'],
        'vpc_Version' => '2');
        $vpcURL="https://mtf.onepay.vn/paygate/vpcpay.op?";
        foreach($payment_data as $key => $value) {
            if (strlen($value) > 0) {
                if ($appendAmp == 0) {
                    $vpcURL .= urlencode($key) . '=' . urlencode($value);
                    $appendAmp = 1;
                } else {
                    $vpcURL .= '&' . urlencode($key) . "=" . urlencode($value);
                }

                if ((strlen($value) > 0) && ((substr($key, 0,4)=="vpc_") || (substr($key,0,5) =="user_"))) {
                    $md5HashData .= $key . "=" . $value . "&";
                }
            }
        }

        $md5HashData = rtrim($md5HashData, "&");

        if (strlen($SECURE_SECRET) > 0) {

            $vpcURL .= "&vpc_SecureHash=" . strtoupper(hash_hmac('SHA256', $md5HashData, pack('H*',$SECURE_SECRET)));
        }


        header("Location: ".$vpcURL);
        }
    }

       public function success()
    {
$SECURE_SECRET = $this->api_config->api_signature;
$vpc_Txn_Secure_Hash = $_GET["vpc_SecureHash"];
$vpc_MerchTxnRef = $_GET["vpc_MerchTxnRef"];
$vpc_AcqResponseCode = $_GET["vpc_AcqResponseCode"];
unset($_GET["vpc_SecureHash"]);
$errorExists = false;
if (strlen($SECURE_SECRET) > 0 && $_GET["vpc_TxnResponseCode"] != "7" && $_GET["vpc_TxnResponseCode"] != "No Value Returned") {
    ksort($_GET);
    $md5HashData = "";
    foreach ($_GET as $key => $value) {
        if ($key != "vpc_SecureHash" && (strlen($value) > 0) && ((substr($key, 0,4)=="vpc_") || (substr($key,0,5) =="user_"))) {
            $md5HashData .= $key . "=" . $value . "&";
        }
    }

    $md5HashData = rtrim($md5HashData, "&");
    if (strtoupper ( $vpc_Txn_Secure_Hash ) == strtoupper(hash_hmac('SHA256', $md5HashData, pack('H*',$SECURE_SECRET)))) {
        $hashValidated = "CORRECT";
    } else {
        $hashValidated = "INVALID HASH";
    }
} else {

    $hashValidated = "INVALID HASH";
}

$txnResponseCode = $this->null2unknown($_GET["vpc_TxnResponseCode"]);

$verType = array_key_exists("vpc_VerType", $_GET) ? $_GET["vpc_VerType"] : "No Value Returned";
$verStatus = array_key_exists("vpc_VerStatus", $_GET) ? $_GET["vpc_VerStatus"] : "No Value Returned";
$token = array_key_exists("vpc_VerToken", $_GET) ? $_GET["vpc_VerToken"] : "No Value Returned";
$verSecurLevel = array_key_exists("vpc_VerSecurityLevel", $_GET) ? $_GET["vpc_VerSecurityLevel"] : "No Value Returned";
$enrolled = array_key_exists("vpc_3DSenrolled", $_GET) ? $_GET["vpc_3DSenrolled"] : "No Value Returned";
$xid = array_key_exists("vpc_3DSXID", $_GET) ? $_GET["vpc_3DSXID"] : "No Value Returned";
$acqECI = array_key_exists("vpc_3DSECI", $_GET) ? $_GET["vpc_3DSECI"] : "No Value Returned";
$authStatus = array_key_exists("vpc_3DSstatus", $_GET) ? $_GET["vpc_3DSstatus"] : "No Value Returned";

$errorTxt = "";

if ($txnResponseCode == "7" || $txnResponseCode == "No Value Returned" || $errorExists) {
    $errorTxt = "Error ";
}

$transStatus = "";
if($hashValidated=="CORRECT" && $txnResponseCode=="0"){
    $transStatus = "success";
    $params = $this->session->userdata('params');

            $payment_id = $_GET["vpc_MerchTxnRef"];
            $bulk_fees=array();
                    $params     = $this->session->userdata('params');
                 
                    foreach ($params['student_fees_master_array'] as $fee_key => $fee_value) {
                   
                     $json_array = array(
                        'amount'          =>  $fee_value['amount_balance'],
                        'date'            => date('Y-m-d'),
                        'amount_discount' => 0,
                        'amount_fine'     => $fee_value['fine_balance'],
                        'description'     => $this->lang->line('online_fees_deposit_through_onepay_txn_id'). $payment_id,
                        'received_by'     => '',
                        'payment_mode'    => 'Onepay',
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
            }elseif ($hashValidated=="INVALID HASH" && $txnResponseCode=="0"){
                $transStatus = "pending";
            }else {
                $transStatus = "fail";
                $this->fail();
            }

    }

    public function fail()
    {

        redirect(base_url('user/gateway/payment/paymentfailed'));

    }
    
    public function cancel()
    {

       redirect(base_url('user/gateway/payment/paymentfailed'));

    }


// If input is null, returns string "No Value Returned", else returns input
public function null2unknown($data)
{
    if ($data == "") {
        return "No Value Returned";
    } else {
        return $data;
    }
}

}