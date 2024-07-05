<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . 'third_party/omnipay/vendor/autoload.php';

class Payment extends Studentgateway_Controller
{

    public $payment_method;
    public $school_name;
    public $school_setting;
    public $setting;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Customlib');
        $this->load->library('Paypal_payment');
        $this->load->library('Stripe_payment');
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->payment_method     = $this->paymentsetting_model->get();
        $this->school_name        = $this->customlib->getAppName();
        $this->school_setting     = $this->setting_model->get();
        $this->setting            = $this->setting_model->get();
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }
    
    public function grouppay()
    {
        $this->session->unset_userdata("params");
        if ($this->input->server('REQUEST_METHOD') == "POST") {

            $row_counter = $this->input->post('row_counter');
            if (!empty($row_counter)) {
                $fees_master_array    = array();
                $total_amount_balance = 0;

                $total_fine_amount_balance = 0;
                foreach ($row_counter as $row_key => $row_value) {
                    $fine_amount_balance = 0;
                    $amount_paid         = 0;
                    $amount_fine_paid    = 0;
                    $amount_discount     = 0;
                    $fee_record          = array();

                    $fee_record['fee_category']             = $this->input->post("fee_category_" . $row_value);
                    $fee_record['student_transport_fee_id'] = $this->input->post("trans_fee_id_" . $row_value);

                    $fee_record['fee_groups_feetype_id']  = $this->input->post("fee_groups_feetype_id_" . $row_value);
                    $fee_record['student_fees_master_id'] = $this->input->post("student_fees_master_id_" . $row_value);

                    if ($fee_record['fee_category'] == "transport") {
                        $result = $this->studentfeemaster_model->studentTRansportDeposit($fee_record['student_transport_fee_id']);

                        $fee_record['fee_group_name'] = $this->lang->line("transport_fees");
                        $fee_record['fee_type_code']  = $result->month;
                        $fee_record['is_system']             = 0;
                        //===========================
                        
                        $amount_detail = json_decode($result->amount_detail);

                        if (is_object($amount_detail)) {
                            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                $amount_paid      = $amount_paid + $amount_detail_value->amount;
                                $amount_discount  = $amount_discount + $amount_detail_value->amount_discount;
                                $amount_fine_paid = $amount_fine_paid + $amount_detail_value->amount_fine;
                            }
                        }

                        $fees_balance = $result->fees - ($amount_paid + $amount_discount);

                        if (($result->due_date != "0000-00-00" && $result->due_date != null) && (strtotime($result->due_date) < strtotime(date('Y-m-d'))) && $fees_balance > 0) {
                            $fine_amount_balance = is_null($result->fine_percentage) ? $result->fine_amount : percentageAmount($result->fees, $result->fine_percentage);

                        }

                    } elseif ($fee_record['fee_category'] == "fees") {

                        $result                       = $this->studentfeemaster_model->studentDeposit($fee_record);
                        $fee_record['fee_group_name'] = $result->fee_group_name;
                        $fee_record['fee_type_code']  = $result->fee_type_code;
                        //===========================
                        $fee_record['is_system']             = $result->is_system;
                        $amount_detail = json_decode($result->amount_detail);

                        if (is_object($amount_detail)) {
                            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                $amount_paid      = $amount_paid + $amount_detail_value->amount;
                                $amount_discount  = $amount_discount + $amount_detail_value->amount_discount;
                                $amount_fine_paid = $amount_fine_paid + $amount_detail_value->amount_fine;
                            }
                        }

                        $fees_balance = $result->amount - ($amount_paid + $amount_discount);

                        if ($result->is_system) {
                            $fees_balance = $result->student_fees_master_amount - ($amount_paid + $amount_discount);
                        }

                        if (($result->due_date != "0000-00-00" && $result->due_date != null) && (strtotime($result->due_date) < strtotime(date('Y-m-d'))) && $fees_balance > 0) {
                            $fine_amount_balance = $result->fine_amount;
                        }
                    }

                    $fee_record['fine_balance']   = ($fine_amount_balance - $amount_fine_paid);
                    $fee_record['amount_balance'] = $fees_balance;
                    $fees_master_array[]          = $fee_record;
                    $total_fine_amount_balance += ($fine_amount_balance - $amount_fine_paid);
                    $total_amount_balance += $fees_balance;

                    //===========================

                }

                $student_id     = $this->customlib->getStudentSessionUserID();
                $pay_method     = $this->paymentsetting_model->getActiveMethod();
                $student_record = $this->student_model->get($student_id);

                //=================
                $page                = new stdClass();
                $page->symbol        = $this->setting[0]['currency_symbol'];
                $page->currency_name = $this->session->userdata('student')['currency_name'];

                $params = array( //payment session
                    'key'                       => $pay_method->api_secret_key,
                    'api_publishable_key'       => $pay_method->api_publishable_key,
                    'invoice'                   => $page,
                    'total'                     => ($total_amount_balance),
                    'fine_amount_balance'       => ($total_fine_amount_balance),
                    'student_session_id'        => $student_record['student_session_id'],
                    'name'                      => $this->customlib->getFullName($student_record['firstname'], $student_record['middlename'], $student_record['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname),
                    'email'                     => $student_record['email'],
                    'guardian_phone'            => $student_record['guardian_phone'],
                    'mobileno'                  => $student_record['mobileno'],
                    'guardian_email'            => $student_record['guardian_email'],
                    'address'                   => $student_record['permanent_address'],
                    'student_fees_master_array' => $fees_master_array,
                    'student_id'                => $student_id,
                );
                //=================

                if ($pay_method->payment_type == "payu") {
                    if ($pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('payu_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/payu"));
                    }
                } else if ($pay_method->payment_type == "instamojo") {
                    //==========Start Instamojo==========
                    if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "" || $pay_method->salt == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('instamojo_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/instamojo"));
                    }
                    //==========End Instamojo==========
                } else if ($pay_method->payment_type == "paypal") {
                    //==========Start Paypal==========
                    if ($pay_method->api_username == "" || $pay_method->api_password == "" || $pay_method->api_signature == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('paypal_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/paypal"));
                    }
                    //==========End Paypal==========
                } else if ($pay_method->payment_type == "stripe") {
                    ///=====================
                    if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('stripe_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/stripe"));
                    }
                    //=======================
                } else if ($pay_method->payment_type == "paystack") {
                    ///=====================
                    if ($pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('paystack_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/paystack"));
                    }
                    //=======================
                } else if ($pay_method->payment_type == "razorpay") {
                    if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('razorpay_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/razorpay"));
                    }
                } else if ($pay_method->payment_type == "paytm") {
                    if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "" || $pay_method->paytm_website == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('paytm_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/paytm"));
                    }
                } else if ($pay_method->payment_type == "midtrans") {
                    if ($pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('midtrans_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/midtrans"));
                    }
                } else if ($pay_method->payment_type == "pesapal") {
                    if ($pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('pesapal_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/pesapal"));
                    }

                } else if ($pay_method->payment_type == "flutterwave") {
                    if ($pay_method->api_publishable_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('flutterwave_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/flutterwave"));
                    }

                } else if ($pay_method->payment_type == "ipayafrica") {
                    if ($pay_method->api_publishable_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('ipay_africa_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/ipayafrica"));
                    }

                } else if ($pay_method->payment_type == "jazzcash") {
                    if ($pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('jazzcash_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/jazzcash"));
                    }
                } else if ($pay_method->payment_type == "billplz") {
                    if ($pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('billplz_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/billplz"));
                    }
                } else if ($pay_method->payment_type == "ccavenue") {
                    if ($pay_method->api_secret_key == "" || $pay_method->salt == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('ccavenue_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/ccavenue"));
                    }
                } else if ($pay_method->payment_type == "sslcommerz") {
                    if ($pay_method->api_publishable_key == "" || $pay_method->api_password == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('sslcommerz_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/sslcommerz"));
                    }
                } else if ($pay_method->payment_type == "walkingm") {
                    if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('walkingm_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/walkingm"));
                    }
                } else if ($pay_method->payment_type == "mollie") {
                    if ($pay_method->api_publishable_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('mollie_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/mollie"));
                    }
                } else if ($pay_method->payment_type == "cashfree") {
                    if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('cashfree_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/cashfree"));
                    }
                } else if ($pay_method->payment_type == "payfast") {
                    if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('payfast_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/payfast"));
                    }
                } else if ($pay_method->payment_type == "toyyibpay") {
                    if ($pay_method->api_signature == "" || $pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('toyyibpay_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/toyyibpay"));
                    }
                } else if ($pay_method->payment_type == "twocheckout") {
                    if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('twocheckout_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/twocheckout"));
                    }
                } else if ($pay_method->payment_type == "skrill") {
                    if ($pay_method->api_email == "" || $pay_method->salt == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('skrill_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/skrill"));
                    }
                } else if ($pay_method->payment_type == "payhere") {
                    if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('payhere_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/payhere"));
                    }
                } else if ($pay_method->payment_type == "onepay") {
                    if ($pay_method->api_publishable_key == "") {
                        $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('onepay_settings_not_available') . '</div>');
                        redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_userdata("params", $params);
                        redirect(base_url("user/gateway/onepay"));
                    }
                } else {
                    $this->session->set_flashdata('error', $this->lang->line('something_went_wrong'));
                    redirect($_SERVER['HTTP_REFERER']);
                }

            }
        }
    }

    public function pay()
    {
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $this->session->unset_userdata("params");
            ///=======================get balance fees
            $fee_category = $this->input->post('fee_category');

            $student_transport_fee_id = $this->input->post('student_transport_fee_id');
            $student_fees_master_id   = $this->input->post('student_fees_master_id');
            $fee_groups_feetype_id    = $this->input->post('fee_groups_feetype_id');
            $student_id               = $this->input->post('student_id');
            if ($this->input->post('submit_mode') == "offline_payment") {

                $fee_record = array();

                if ($fee_category == "transport") {

                    $result = $this->studentfeemaster_model->studentTRansportDeposit($student_transport_fee_id);

                    $fee_record['fee_category']             = $fee_category;
                    $fee_record['student_transport_fee_id'] = $student_transport_fee_id;

                    $fee_record['fee_groups_feetype_id']  = $fee_groups_feetype_id;
                    $fee_record['student_fees_master_id'] = $student_fees_master_id;
                    $fee_record['fee_group_name']         = $this->lang->line("transport_fees");
                    $fee_record['fee_type_code']          = $result->month;

                } elseif ($fee_category == "fees") {
                    $data = array();

                    $data['fee_groups_feetype_id']  = $fee_groups_feetype_id;
                    $data['student_fees_master_id'] = $student_fees_master_id;

                    $result = $this->studentfeemaster_model->studentDeposit($data);

                    $fee_record['fee_category']             = $fee_category;
                    $fee_record['student_transport_fee_id'] = $student_transport_fee_id;

                    $fee_record['fee_groups_feetype_id']  = $fee_groups_feetype_id;
                    $fee_record['student_fees_master_id'] = $student_fees_master_id;
                    $fee_record['fee_group_name']         = $result->fee_group_name;
                    $fee_record['fee_type_code']          = $result->fee_type_code;

                }
                $this->session->set_userdata("params", $fee_record);

                redirect("user/offlinepayment");

            } elseif ($this->input->post('submit_mode') == "online_payment") {

                if (!empty($this->payment_method)) {

                    $amount_balance      = 0;
                    $amount              = 0;
                    $amount_fine         = 0;
                    $amount_discount     = 0;
                    $fine_amount_balance = 0;

                    $data = array();

                    $data['fee_groups_feetype_id']  = $fee_groups_feetype_id;
                    $data['student_fees_master_id'] = $student_fees_master_id;

                    if ($fee_category == "transport") {
                        $result = $this->studentfeemaster_model->studentTRansportDeposit($student_transport_fee_id);
 
                        $fee_record = array();
                        $fee_record['is_system']             = 0;
                        $fee_record['fee_category']             = $fee_category;
                        $fee_record['student_transport_fee_id'] = $student_transport_fee_id;

                        $fee_record['fee_groups_feetype_id']  = $fee_groups_feetype_id;
                        $fee_record['student_fees_master_id'] = $student_fees_master_id;
                        $fee_record['fee_group_name']         = $this->lang->line("transport_fees");
                        $fee_record['fee_type_code']          = $result->month;

                        $fees_master_array = array();

                        $amount_detail = json_decode($result->amount_detail);

                        if (is_object($amount_detail)) {
                            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                $amount          = $amount + $amount_detail_value->amount;
                                $amount_discount = $amount_discount + $amount_detail_value->amount_discount;
                                $amount_fine     = $amount_fine + $amount_detail_value->amount_fine;
                            }
                        }

                        $amount_balance = $result->fees - ($amount + $amount_discount);

                        if (($result->due_date != "0000-00-00" && $result->due_date != null) && (strtotime($result->due_date) < strtotime(date('Y-m-d'))) && $amount_balance > 0) {
                            $fine_amount_balance = is_null($result->fine_percentage) ? $result->fine_amount : percentageAmount($result->fees, $result->fine_percentage);

                        }

                    } elseif ($fee_category == "fees") {
                        $result = $this->studentfeemaster_model->studentDeposit($data);                        
                        $fee_record = array();                    
                        $fee_record['is_system']             = $result->is_system;
                        $fee_record['fee_category']             = $fee_category;
                        $fee_record['student_transport_fee_id'] = $student_transport_fee_id;
                        $fee_record['fee_groups_feetype_id']  = $fee_groups_feetype_id;
                        $fee_record['student_fees_master_id'] = $student_fees_master_id;
                        $fee_record['fee_group_name']         = $result->fee_group_name;
                        $fee_record['fee_type_code']          = $result->fee_type_code;
                        
                        $fees_master_array = array();

                        $amount_detail = json_decode($result->amount_detail);

                        if (strtotime($result->due_date) < strtotime(date('Y-m-d'))) {
                            $fine_amount_balance = $result->fine_amount;
                        }

                        if (($result->due_date != "0000-00-00" && $result->due_date != null) && (strtotime($result->due_date) < strtotime(date('Y-m-d'))) && $fees_balance > 0) {
                            $fine_amount_balance = $result->fine_amount;
                        }

                        if (is_object($amount_detail)) {
                            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                $amount          = $amount + $amount_detail_value->amount;
                                $amount_discount = $amount_discount + $amount_detail_value->amount_discount;
                                $amount_fine     = $amount_fine + $amount_detail_value->amount_fine;
                            }
                        }

                        $amount_balance = $result->amount - ($amount + $amount_discount);
                        if ($result->is_system) {

                            $amount_balance = $result->student_fees_master_amount - ($amount + $amount_discount);
                        }
                    }

                    $fine_amount_balance = $fine_amount_balance - $amount_fine;

                    $student_record               = $this->student_model->get($student_id);
                    $pay_method                   = $this->paymentsetting_model->getActiveMethod();
                    $fee_record['fine_balance']   = $fine_amount_balance;
                    $fee_record['amount_balance'] = $amount_balance;

                    $fees_master_array[]          = $fee_record;
                    //======================================

                    $page                = new stdClass();
                    $page->symbol        = $this->setting[0]['currency_symbol'];
                    $page->currency_name = $this->session->userdata('student')['currency_name'];
                    $params              = array(
                        'key'                       => $pay_method->api_secret_key,
                        'api_publishable_key'       => $pay_method->api_publishable_key,
                        'invoice'                   => $page,
                        'total'                     => $amount_balance,
                        'fine_amount_balance'       => ($fine_amount_balance),
                        'student_session_id'        => $student_record['student_session_id'],
                        'guardian_phone'            => $student_record['guardian_phone'],
                        'name'                      => $this->customlib->getFullName($student_record['firstname'], $student_record['middlename'], $student_record['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname),
                        'email'                     => $student_record['email'],
                        'guardian_phone'            => $student_record['guardian_phone'],
			'guardian_email'            => $student_record['guardian_email'],
			'mobileno'            => $student_record['mobileno'],
                        'address'                   => $student_record['permanent_address'],                 
                        'student_id'                => $student_id, 
                        'student_fees_master_array' => $fees_master_array,
                    );

                    //=====================================
               
                    if ($pay_method->payment_type == "paypal") {
                        //==========Start Paypal==========
                        if ($pay_method->api_username == "" || $pay_method->api_password == "" || $pay_method->api_signature == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('paypal_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {

                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/paypal"));
                        }
                        //==========End Paypal==========
                    } else if ($pay_method->payment_type == "paystack") {
                        ///=====================

                        if ($pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('paystack_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/paystack"));
                        }

                        //=======================
                    } else if ($pay_method->payment_type == "stripe") {
                        //=====================
                        
                        if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('stripe_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/stripe"));
                        }

                        //=======================
                    } else if ($pay_method->payment_type == "payu") {

                        if ($pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('payu_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/payu"));
                        }
                    } else if ($pay_method->payment_type == "ccavenue") {
                        if ($pay_method->api_secret_key == "" || $pay_method->salt == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('ccavenue_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/ccavenue"));
                        }
                    } else if ($pay_method->payment_type == "instamojo") {

                        if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "" || $pay_method->salt == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('instamojo_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/instamojo"));
                        }
                    } else if ($pay_method->payment_type == "razorpay") {

                        if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('razorpay_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/razorpay"));
                        }
                    } else if ($pay_method->payment_type == "paytm") {
                        if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "" || $pay_method->paytm_website == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('paytm_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/paytm"));
                        }
                    } else if ($pay_method->payment_type == "midtrans") {
                        if ($pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('midtrans_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/midtrans"));
                        }
                    } else if ($pay_method->payment_type == "pesapal") {
                        if ($pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('pesapal_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/pesapal"));
                        }
                    } else if ($pay_method->payment_type == "flutterwave") {
                        if ($pay_method->api_publishable_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('flutterwave_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/flutterwave"));
                        }
                    } else if ($pay_method->payment_type == "ipayafrica") {
                        if ($pay_method->api_publishable_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('ipay_africa_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/ipayafrica"));
                        }
                    } else if ($pay_method->payment_type == "jazzcash") {
                        if ($pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('jazzcash_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/jazzcash"));
                        }
                    } else if ($pay_method->payment_type == "billplz") {
                        if ($pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('billplz_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/billplz"));
                        }
                    } else if ($pay_method->payment_type == "sslcommerz") {
                        if ($pay_method->api_publishable_key == "" || $pay_method->api_password == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('sslcommerz_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/sslcommerz"));
                        }
                    } else if ($pay_method->payment_type == "walkingm") {
                        if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('walkingm_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/walkingm"));
                        }
                    } else if ($pay_method->payment_type == "mollie") {
                        if ($pay_method->api_publishable_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('mollie_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/mollie"));
                        }
                    } else if ($pay_method->payment_type == "cashfree") {
                        if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('cashfree_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/cashfree"));
                        }
                    } else if ($pay_method->payment_type == "payfast") {
                        if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('payfast_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/payfast"));
                        }
                    } else if ($pay_method->payment_type == "toyyibpay") {
                        if ($pay_method->api_signature == "" || $pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('toyyibpay_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/toyyibpay"));
                        }
                    } else if ($pay_method->payment_type == "twocheckout") {
                        if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('twocheckout_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/twocheckout"));
                        }
                    } else if ($pay_method->payment_type == "skrill") {
                        if ($pay_method->api_email == "" || $pay_method->salt == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('skrill_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/skrill"));
                        }
                    } else if ($pay_method->payment_type == "payhere") {
                        if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('payhere_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/payhere"));
                        }
                    } else if ($pay_method->payment_type == "onepay") {
                        if ($pay_method->api_publishable_key == "") {
                            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('onepay_settings_not_available') . '</div>');
                            redirect($_SERVER['HTTP_REFERER']);
                        } else {
                            $this->session->set_userdata("params", $params);
                            redirect(base_url("user/gateway/onepay"));
                        }
                    } else {
                        $this->session->set_flashdata('error', $this->lang->line('something_went_wrong'));
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                } else {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('payment_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }
        }
    }

    public function pay1($student_fees_master_id, $fee_groups_feetype_id, $student_id)
    {
        $this->session->unset_userdata("params");
        ///=======================get balance fees

        if (!empty($this->payment_method)) {
            $data                           = array();
            $data['fee_groups_feetype_id']  = $fee_groups_feetype_id;
            $data['student_fees_master_id'] = $student_fees_master_id;
            $result                         = $this->studentfeemaster_model->studentDeposit($data);

            $fee_record                           = array();
            $fee_record['fee_groups_feetype_id']  = $fee_groups_feetype_id;
            $fee_record['student_fees_master_id'] = $student_fees_master_id;
            $fee_record['fee_group_name']         = $result->fee_group_name;
            $fee_record['fee_type_code']          = $result->fee_type_code;

            $fees_master_array = array();

            $amount_balance      = 0;
            $amount              = 0;
            $amount_fine         = 0;
            $amount_discount     = 0;
            $fine_amount_balance = 0;
            $amount_detail       = json_decode($result->amount_detail);

            if (strtotime($result->due_date) < strtotime(date('Y-m-d'))) {
                $fine_amount_balance = $result->fine_amount;
            }

            if (is_object($amount_detail)) {
                foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                    $amount          = $amount + $amount_detail_value->amount;
                    $amount_discount = $amount_discount + $amount_detail_value->amount_discount;
                    $amount_fine     = $amount_fine + $amount_detail_value->amount_fine;
                }
            }

            $amount_balance = $result->amount - ($amount + $amount_discount);
            if ($result->is_system) {
                $amount_balance = $result->student_fees_master_amount - ($amount + $amount_discount);
            }
            $fine_amount_balance = $fine_amount_balance - $amount_fine;

            $student_record               = $this->student_model->get($student_id);
            $pay_method                   = $this->paymentsetting_model->getActiveMethod();
            $fee_record['fine_balance']   = $fine_amount_balance;
            $fee_record['amount_balance'] = $amount_balance;
            $fees_master_array[]          = $fee_record;
            //======================================

            $page                = new stdClass();
            $page->symbol        = $this->setting[0]['currency_symbol'];
            $page->currency_name = $this->setting[0]['currency'];
            $params              = array(
                'key'                       => $pay_method->api_secret_key,
                'api_publishable_key'       => $pay_method->api_publishable_key,
                'invoice'                   => $page,
                'total'                     => ($amount_balance),
                'fine_amount_balance'       => convertBaseAmountCurrencyFormat($fine_amount_balance),
                'student_session_id'        => $student_record['student_session_id'],
                'guardian_phone'            => $student_record['guardian_phone'],
                'name'                      => $this->customlib->getFullName($student_record['firstname'], $student_record['middlename'], $student_record['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname),

                'email'                     => $student_record['email'],
                'mobileno'                  => $student_record['mobileno'],
                'guardian_email'            => $student_record['guardian_email'],
                'address'                   => $student_record['permanent_address'],            
                'student_id'                => $student_id,
                'student_fees_master_array' => $fees_master_array,
            );
            //=====================================
            if ($pay_method->payment_type == "paypal") {
                //==========Start Paypal==========
                if ($pay_method->api_username == "" || $pay_method->api_password == "" || $pay_method->api_signature == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('paypal_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/paypal"));
                }
                //==========End Paypal==========
            } else if ($pay_method->payment_type == "paystack") {
                ///=====================
                if ($pay_method->api_secret_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('paystack_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/paystack"));
                }
                //=======================
            } else if ($pay_method->payment_type == "stripe") {
                ///=====================
                if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('stripe_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/stripe"));
                }
                //=======================
            } else if ($pay_method->payment_type == "payu") {

                if ($pay_method->api_secret_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('payu_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/payu"));
                }
            } else if ($pay_method->payment_type == "ccavenue") {
                if ($pay_method->api_secret_key == "" || $pay_method->salt == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('ccavenue_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/ccavenue"));
                }
            } else if ($pay_method->payment_type == "instamojo") {

                if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "" || $pay_method->salt == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('instamojo_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/instamojo"));
                }
            } else if ($pay_method->payment_type == "razorpay") {

                if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('razorpay_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/razorpay"));
                }
            } else if ($pay_method->payment_type == "paytm") {
                if ($pay_method->api_secret_key == "" || $pay_method->api_publishable_key == "" || $pay_method->paytm_website == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('paytm_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/paytm"));
                }
            } else if ($pay_method->payment_type == "midtrans") {
                if ($pay_method->api_secret_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('midtrans_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/midtrans"));
                }
            } else if ($pay_method->payment_type == "pesapal") {
                if ($pay_method->api_secret_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('pesapal_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/pesapal"));
                }
            } else if ($pay_method->payment_type == "flutterwave") {
                if ($pay_method->api_publishable_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('flutterwave_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/flutterwave"));
                }
            } else if ($pay_method->payment_type == "ipayafrica") {
                if ($pay_method->api_publishable_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('ipay_africa_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/ipayafrica"));
                }
            } else if ($pay_method->payment_type == "jazzcash") {
                if ($pay_method->api_secret_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('jazzcash_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/jazzcash"));
                }
            } else if ($pay_method->payment_type == "billplz") {
                if ($pay_method->api_secret_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('billplz_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/billplz"));
                }
            } else if ($pay_method->payment_type == "sslcommerz") {
                if ($pay_method->api_publishable_key == "" || $pay_method->api_password == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('sslcommerz_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/sslcommerz"));
                }
            } else if ($pay_method->payment_type == "walkingm") {
                if ($pay_method->api_publishable_key == "" || $pay_method->api_secret_key == "") {
                    $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('walkingm_settings_not_available') . '</div>');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_userdata("params", $params);
                    redirect(base_url("students/walkingm"));
                }
            } else {
                $this->session->set_flashdata('error', $this->lang->line('something_went_wrong'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->session->set_flashdata('error', '<div class="alert alert-danger">' . $this->lang->line('payment_settings_not_available') . '</div>');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function paymentfailed()
    {
        $this->session->set_userdata('top_menu', 'Fees');
        $data['title']       = 'Invoice';
        $data['message']     = "dfsdfds";
        $setting_result      = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $this->load->view('layout/student/header', $data);
        $this->load->view('user/paymentfailed', $data);
        $this->load->view('layout/student/footer', $data);
    }

    public function paymentprocessing()
    {
        $params         = $this->session->userdata('params');

        $student_record = $this->student_model->getByStudentSession($params['student_session_id']);
        $this->session->set_userdata('top_menu', 'Fees');
        $data['title']                 = 'Invoice';
        $data['message']               = "dfsdfds";
        $setting_result                = $this->setting_model->get();
        $data['settinglist']           = $setting_result;
        $mailsms_array                 = (object) array();
        $mailsms_array->transaction_id = $params['transaction_id'];
        $mailsms_array->guardian_phone = $params['guardian_phone'];
        $mailsms_array->email          = $params['email'];
        $mailsms_array->class          = $student_record['class'];
        $mailsms_array->section        = $student_record['section'];
        $mailsms_array->fee_amount     = $params['total'];
        $mailsms_array->guardian_email = $params['guardian_email'];
        $mailsms_array->mobileno       = $params['mobileno'];
        $mailsms_array->parent_app_key = $student_record['parent_app_key'];
        $mailsms_array->app_key = $student_record['app_key'];
        $mailsms_array->student_name   = $this->customlib->getFullName($student_record['firstname'], $student_record['middlename'], $student_record['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname);
        $this->mailsmsconf->mailsms('fee_processing', $mailsms_array);
        $this->load->view('layout/student/header', $data);
        $this->load->view('user/paymentprocessing', $data);
        $this->load->view('layout/student/footer', $data);
    }

    public function successinvoice()
    {
        $this->session->set_userdata('top_menu', 'fees');
        $this->session->set_userdata('sub_menu', 'student/getFees');
        $data = array();
        $this->load->view('layout/student/header', $data);
        $this->load->view('user/invoice', $data);
        $this->load->view('layout/student/footer', $data);
    }
}
