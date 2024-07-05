<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Flutterwave extends Studentgateway_Controller {

    public $api_config = "";

    public function __construct() {
        parent::__construct();

        $api_config = $this->paymentsetting_model->getActiveMethod();
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
        $this->load->view('user/gateway/flutterwave/index', $data);
    }

    public function pay() {
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array();
        $data['params'] = $this->session->userdata('params');
        $data['setting'] = $this->setting;
        $data['api_error'] = array();
        $data['student_data'] = $this->student_model->get($data['params']['student_id']);
        $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
        $this->load->view('user/gateway/flutterwave/index', $data);
        } else {

            $details = $this->paymentsetting_model->getActiveMethod();
            $api_secret_key = $details->api_secret_key;
            $api_publishable_key = $details->api_publishable_key;

            $params = $this->session->userdata('params');
            $data = array();
            $student_id = $params['student_id'];
            $data['total'] =number_format((float)($params['fine_amount_balance']+$params['total']), 2, '.', '');
            $data['symbol'] = $params['invoice']->symbol;
            $data['currency_name'] = $params['invoice']->currency_name;
            $data['name'] = $params['name'];
            $data['guardian_phone'] = $params['guardian_phone'];

            $curl = curl_init();
            $customer_email = $_POST['email'];
            $currency = $data['currency_name'];
            $txref = "rave" . uniqid(); // ensure you generate unique references per transaction.
            // get your public key from the dashboard.
            $PBFPubKey = $api_publishable_key; 
            $redirect_url = base_url() . 'user/gateway/flutterwave/success'; // Set your own redirect URL


            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/hosted/pay",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => json_encode([
                'amount'=>convertBaseAmountCurrencyFormat($data['total']),
                'customer_email'=>$customer_email,
                'currency'=>$currency,
                'txref'=>$txref,
                'PBFPubKey'=>$PBFPubKey,
                'redirect_url'=>$redirect_url,
              ]),
              CURLOPT_HTTPHEADER => [
                "content-type: application/json",
                "cache-control: no-cache"
              ],
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            if($err){
              // there was an error contacting the rave API
              die('Curl returned error: ' . $err);
            }

            $transaction = json_decode($response);

            if(!$transaction->data && !$transaction->data->link){
              // there was an error from the API
              print_r('API returned error: ' . $transaction->message);
            }
 if (!$transaction->data && !$transaction->data->link) {
            // there was an error from the API
            print_r('API returned error: ' . $transaction->message);
redirect(base_url('user/gateway/payment/paymentfailed'));
        }elseif(isset($transaction->status) && ($transaction->status=='error')){
  print_r('API returned error: ' . $transaction->message);
redirect(base_url('user/gateway/payment/paymentfailed'));
}

            // redirect to page so User can pay

            header('Location: ' . $transaction->data->link);
            
        }
    }
 
    public function success() {
        $details = $this->paymentsetting_model->getActiveMethod();
        $api_secret_key = $details->api_secret_key;
        $params = $this->session->userdata('params');
       if (isset($_GET['txref'])) {
        if(isset($_GET['cancelled']) && $_GET['cancelled']=='true'){
             redirect(base_url("user/gateway/payment/paymentfailed"));
        }else{
        $ref = $_GET['txref'];
        $amount=number_format((float)($params['fine_amount_balance']+$params['total']), 2, '.', ''); //Get the correct amount of your product
        $currency = $params['invoice']->currency_name;; //Correct Currency from Server

        $query = array(
            "SECKEY" => $api_secret_key,
            "txref" => $ref
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
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        curl_close($ch);
 
        $resp = json_decode($response, true);

        $paymentStatus = $resp['data']['status'];
        $chargeResponsecode = $resp['data']['chargecode'];
        $chargeAmount = $resp['data']['amount'];
        $chargeCurrency = $resp['data']['currency'];
        $txid= $resp['data']['txref'];
        if (($chargeResponsecode == "00" || $chargeResponsecode == "0") && ($chargeAmount == $amount)  && ($chargeCurrency == $currency)) {
          // transaction was successful...
          // please check other things like whether you already gave value for this ref
          // if the email matches the customer who owns the product etc
          //Give Value and return to Success page
            $payment_id = $txid; 
            $bulk_fees=array();
            $params     = $this->session->userdata('params');
         
            foreach ($params['student_fees_master_array'] as $fee_key => $fee_value) {
           
             $json_array = array(
                'amount'          =>  $fee_value['amount_balance'],
                'date'            => date('Y-m-d'),
                'amount_discount' => 0,
                'amount_fine'     => $fee_value['fine_balance'],
                'description'     => $this->lang->line('online_fees_deposit_through_flutterwave_txn_id') . $payment_id,
                'received_by'     => '',
                'payment_mode'    => 'Flutter_wave',
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
            if ($inserted_id) {
                  redirect(base_url("user/gateway/payment/successinvoice"));                     
            } else {
              redirect(base_url('user/gateway/payment/paymentfailed'));
            }
             
        } else {
           redirect(base_url("user/gateway/payment/paymentfailed"));
        } 
        }
       
    }
        else {
      die('No reference supplied');
    }
    }

}