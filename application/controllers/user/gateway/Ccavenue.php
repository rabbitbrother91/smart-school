<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ccavenue extends Studentgateway_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->setting = $this->setting_model->get();
        $this->setting[0]['currency_symbol'] = $this->customlib->getSchoolCurrencyFormat();
        $this->load->library('Ccavenue_crypto');
         $this->load->library('mailsmsconf');
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/index');
        $data['params']         = $this->session->userdata('params');
        $data['setting']        = $this->setting;
        
        $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
        $this->load->view('user/gateway/ccavenue/index', $data);
    } 

    public function pay()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $session_data            = $this->session->userdata('params');
            $pay_method              = $this->paymentsetting_model->getActiveMethod();
            $details['tid']          = abs(crc32(uniqid()));
            $details['merchant_id']  = $pay_method->api_secret_key;
            $details['order_id']     = abs(crc32(uniqid()));
            $details['amount']       = number_format((float) ($session_data['fine_amount_balance'] + $session_data['total']), 2, '.', '');
            $details['currency']     = 'INR';
            $details['redirect_url'] = base_url('user/gateway/ccavenue/success');
            $details['cancel_url']   = base_url('user/gateway/ccavenue/cancel');
            $details['language']     = "EN";
            $details['billing_name']     = $session_data['name'];

            $merchant_data = "";
            foreach ($details as $key => $value) {
                $merchant_data .= $key . '=' . $value . '&';
            }
            $data['encRequest']  = $this->ccavenue_crypto->encrypt($merchant_data, $pay_method->salt);
            $data['access_code'] = $pay_method->api_publishable_key;

            $this->load->view('user/gateway/ccavenue/ccavenue_pay', $data);
        } else {
            redirect(base_url('user/user/dashboard'));
        }
    }

    public function success()
    {

        $status     = array();
        $rcvdString = "";
        $params     = $this->session->userdata('params');
        
        if (!empty($params)) {
            $pay_method  = $this->paymentsetting_model->getActiveMethod();
            $encResponse = $_POST["encResp"];
            $rcvdString  = $this->ccavenue_crypto->decrypt($encResponse, $pay_method->salt);

            if ($rcvdString !== '') {

                $decryptValues = explode('&', $rcvdString);
                $dataSize      = sizeof($decryptValues);
                for ($i = 0; $i < $dataSize; $i++) {
                    $information             = explode('=', $decryptValues[$i]);
                    $status[$information[0]] = $information[1];
                }
            }

            if (!empty($status)) {
                if ($status['order_status'] == "Success") {

                    $tracking_id = $status['tracking_id'];
                    $bank_ref_no = $status['bank_ref_no'];
                    $bulk_fees=array();
            
         
            foreach ($params['student_fees_master_array'] as $fee_key => $fee_value) {
           
             $json_array = array(
                'amount'          =>  $fee_value['amount_balance'],
                'date'            => date('Y-m-d'),
                'amount_discount' => 0,
                'amount_fine'     => $fee_value['fine_balance'],
                'description'     => $this->lang->line('online_fees_deposit_through_ccavenue_txn_id') . $tracking_id . " Bank Ref. No.: " . $bank_ref_no,
                'received_by'     => '',
                'payment_mode'    => 'CCAvenue',
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

                } else if ($status['order_status'] === "Aborted") {
                    echo "<br>".$this->lang->line('we_will_keep_you_posted_regarding_the_status_of_your_order_through_email');

                } else if ($status['order_status'] === "Failure") {
                    redirect(base_url("user/gateway/payment/paymentfailed"));} else {
                    echo "<br>".$this->lang->line('security_error_illegal_access_detected');

                }
            }

        } else {
           
        }
    }

    public function cancel()
    {

    }

}