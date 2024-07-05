<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Instamojo extends Studentgateway_Controller {

     public $api_config = "";

    public function __construct() {
        parent::__construct();

        $api_config = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->get();
        $this->load->library('mailsmsconf');
        $this->setting[0]['currency_symbol'] = $this->customlib->getSchoolCurrencyFormat();
    }
  
    public function index() {
 
        $data = array();
        $data['params'] = $this->session->userdata('params');
        $data['setting'] = $this->setting;
        $data['api_error'] = array();
        $data['student_data'] = $this->student_model->get($data['params']['student_id']);
        $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
        $this->load->view('user/gateway/instamojo/index', $data);
    }
 
    public function insta_pay() {
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
            $this->load->view('user/gateway/instamojo/index', $data);
        } else {

            $instadetails = $this->paymentsetting_model->getActiveMethod();
            $insta_apikey = $instadetails->api_secret_key;
            $insta_authtoken = $instadetails->api_publishable_key;
            $data = array();
            $data['name'] = $params['name'];
            
            $amount =number_format((float)($params['fine_amount_balance']+$params['total']), 2, '.', '');
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://www.instamojo.com/api/1.1/payment-requests/'); // for live https://www.instamojo.com/api/1.1/payment-requests/
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Api-Key:$insta_apikey",
                "X-Auth-Token:$insta_authtoken"));
            $payload = Array(
                'purpose' => 'Student Fess',
                'amount' => convertBaseAmountCurrencyFormat($amount),
                'phone' => $_POST['phone'],
                'buyer_name' => $data['name'],
                'redirect_url' => base_url() . 'user/gateway/instamojo/success',
                'send_email' => false,
                'webhook' => base_url() . 'webhooks/insta_webhook',
                'send_sms' => false,
                'email' => $_POST['email'],
                'allow_repeated_payments' => false
            );
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            $response = curl_exec($ch);
            curl_close($ch);
            $json = json_decode($response, true);

            if ($json['success']) {
                $url = $json['payment_request']['longurl'];
                header("Location: $url");
            } else {
                 
            $data = array();
            $data['params'] = $this->session->userdata('params');
            $data['setting'] = $this->setting;
            $data['api_error'] = array();
            $data['student_data'] = $this->student_model->get($data['params']['student_id']);
            $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
            $json = json_decode($response, true);
            $data['api_error'] = $json['message'];
            $this->load->view('user/gateway/instamojo/index', $data);
            }
        }
    }

    public function success() {
        
        if ($_GET['payment_status'] == 'Credit') {
                    $payment_id = $_GET['payment_id']; 
                    $bulk_fees=array();
                    $params     = $this->session->userdata('params');
                 
                    foreach ($params['student_fees_master_array'] as $fee_key => $fee_value) {
                    
                     $json_array = array(
                        'amount'          =>  $fee_value['amount_balance'],
                        'date'            => date('Y-m-d'),
                        'amount_discount' => 0,
                        'amount_fine'     => $fee_value['fine_balance'],
                        'description'     => $this->lang->line('online_fees_deposit_through_instamojo_txn_id') . $payment_id,
                        'received_by'     => '',
                        'payment_mode'    => 'Instamojo',
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
                   //================================
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

                    if ($response) {
                          redirect(base_url("user/gateway/payment/successinvoice"));                     
                    } else {
                      redirect(base_url('user/gateway/payment/paymentfailed'));
                    }

                } else {
                    redirect(base_url('user/gateway/payment/paymentfailed'));
                }
    }

}