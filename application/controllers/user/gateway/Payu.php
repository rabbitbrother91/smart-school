<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Payu extends Studentgateway_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->setting = $this->setting_model->get();
        $this->setting[0]['currency_symbol'] = $this->customlib->getSchoolCurrencyFormat();
        $this->load->library('mailsmsconf');
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/index');
        $pre_session_data           = $this->session->userdata('params');
        $txnid                      = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
        $pre_session_data['txn_id'] = $txnid;
        $this->session->set_userdata("params", $pre_session_data);
        $session_data                   = $this->session->userdata('params');
        $session_data['name']           = ($session_data['name'] != "") ? $session_data['name'] : "noname";
        $session_data['email']          = ($session_data['email'] != "") ? $session_data['email'] : "noemail@gmail.com";
        $session_data['guardian_phone'] = ($session_data['guardian_phone'] != "") ? $session_data['guardian_phone'] : "0000000000";
        $session_data['address']        = ($session_data['address'] != "") ? $session_data['address'] : "noaddress";
        $pay_method                     = $this->paymentsetting_model->getActiveMethod();
        //payumoney details
        $amount           = round(number_format((float) (convertBaseAmountCurrencyFormat($session_data['fine_amount_balance'] + $session_data['total'])), 2, '.', ''));
        $customer_name    = $session_data['name'];
        $customer_emial   = $session_data['email'];
        $customer_mobile  = $session_data['guardian_phone'];
        $customer_address = $session_data['address'];

        $product_info = 'Online Fees Payment';
        $MERCHANT_KEY = $pay_method->api_secret_key;
        $SALT         = $pay_method->salt;

        //optional udf values
        $udf1 = '';
        $udf2 = '';
        $udf3 = '';
        $udf4 = '';
        $udf5 = '';

        $hashstring = $MERCHANT_KEY . '|' . $txnid . '|' . $amount . '|' . $product_info . '|' . $customer_name . '|' . $customer_emial . '|' . $udf1 . '|' . $udf2 . '|' . $udf3 . '|' . $udf4 . '|' . $udf5 . '||||||' . $SALT;
        $hash       = strtolower(hash('sha512', $hashstring));

        $success = base_url('user/gateway/payu/success');
        $fail    = base_url('user/gateway/payu/success');
        $cancel  = base_url('user/gateway/payu/success');
        $data    = array(
            'mkey'                      => $MERCHANT_KEY,
            'tid'                       => $txnid,
            'hash'                      => $hash,
            'amount'                    => $amount,
            'student_fees_master_array' => $session_data['student_fees_master_array'],
            'name'                      => $customer_name,
            'productinfo'               => $product_info,
            'mailid'                    => $customer_emial,
            'phoneno'                   => $customer_mobile,
            'address'                   => $customer_address,
            'action'                    => "https://secure.payu.in", //for live change action  https://secure.payu.in
            'sucess'                    => $success,
            'failure'                   => $fail,
            'cancel'                    => $cancel,
        );
        $data['session_data'] = $session_data;
        $data['setting']      = $this->setting;

        $this->load->view('user/gateway/payu/index', $data);
    }

    public function checkout()
    {

        $this->form_validation->set_rules('firstname', $this->lang->line('customer_name'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('phone', $this->lang->line('mobile_number'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'required|valid_email|trim|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'firstname' => form_error('firstname'),
                'phone'     => form_error('phone'),
                'email'     => form_error('email'),
                'amount'    => form_error('amount'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {

            $array = array('status' => 'success', 'error' => '');
            echo json_encode($array);
        }
    }

    public function success()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $session_data = $this->session->userdata('params');

            if ($this->input->post('status') == "success") {
                $mihpayid      = $this->input->post('mihpayid');
                $transactionid = $this->input->post('txnid');
                $txn_id        = $session_data['txn_id'];

                if ($txn_id == $transactionid) {
                    $bulk_fees = array();
                    $params    = $this->session->userdata('params');

                    foreach ($params['student_fees_master_array'] as $fee_key => $fee_value) {

                        $json_array = array(
                            'amount'          => $fee_value['amount_balance'],
                            'date'            => date('Y-m-d'),
                            'amount_discount' => 0,
                            'amount_fine'     => $fee_value['fine_balance'],
                            'description'     => $this->lang->line('online_fees_deposit_through_payu_txn_id') . $txn_id . " PayU Ref ID: " . $mihpayid,
                            'received_by'     => '',
                            'payment_mode'    => 'PayU',
                        );

                        $insert_fee_data = array(
                            'fee_category'=>$fee_value['fee_category'],
                            'student_transport_fee_id'=>$fee_value['student_transport_fee_id'],
                            'student_fees_master_id' => $fee_value['student_fees_master_id'],
                            'fee_groups_feetype_id'  => $fee_value['fee_groups_feetype_id'],
                            'amount_detail'          => $json_array,
                        );
                        $bulk_fees[] = $insert_fee_data;
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

                } else {
                    redirect(base_url('user/gateway/payment/paymentfailed'));
                }
            } else {

                redirect(base_url('user/gateway/payment/paymentfailed'));
            }
        }
    }

}