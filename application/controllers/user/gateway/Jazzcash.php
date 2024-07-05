<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jazzcash extends Studentgateway_Controller {

    public $api_config = "";

    function __construct() {
        parent::__construct();
        $this->api_config = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->get();
        $this->load->library('mailsmsconf');
        $this->setting[0]['currency_symbol'] = $this->customlib->getSchoolCurrencyFormat();
    }

    public function index() {

        $params = $this->session->userdata('params');
        $data = array();
        $data['params'] = $params;
        $data['setting'] = $this->setting;
        $data['api_error'] = array();
        $student_id = $params['student_id'];
        $total = number_format((float)($params['fine_amount_balance']+$params['total']), 2, '.', '');;
        $data['name'] = $params['name'];
        $data['title'] = 'Student Fee';
        $data['total'] = $total * 100;
        $data['amount'] = $total;
        $data['guardian_phone'] = $params['guardian_phone'];
        $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
        $this->load->view('user/gateway/jazzcash/index', $data);
    }

    public function pay(){

    	$params = $this->session->userdata('params');
        $amount =number_format((float)(convertBaseAmountCurrencyFormat($params['fine_amount_balance']+$params['total'])), 2, '.', '');
        $data = array();
        $data['params'] = $params;
        $data['setting'] = $this->setting;
        $data['api_error'] = array();
        $student_id = $params['student_id'];
        $data['name'] = $params['name'];
        $data['title'] = 'Student Fee';
        $data['return_url'] = base_url() . 'user/gateway/jazzcash/callback';
        $data['pp_MerchantID'] = $this->api_config->api_secret_key;
        $data['pp_Password'] = $this->api_config->api_password;
        $data['currency_code'] = $params['invoice']->currency_name;
        $data['guardian_phone'] = $params['guardian_phone'];
		$data['ExpiryTime'] = date('YmdHis', strtotime("+3 hours"));
		$data['TxnDateTime'] = date('YmdHis', strtotime("+0 hours"));
		$data['TxnRefNumber'] = "T". date('YmdHis');
        $input_para["pp_Version"]="2.0";
        $input_para["pp_IsRegisteredCustomer"]="Yes";
        $input_para["pp_TxnType"]="MPAY";
        $input_para["pp_TokenizedCardNumber"]="";
        $input_para["pp_CustomerID"]=time();
        $input_para["pp_CustomerEmail"]="";
        $input_para["pp_CustomerMobile"]="";
        $input_para["pp_MerchantID"]=$data['pp_MerchantID'];
        $input_para["pp_Language"]="EN";
        $input_para["pp_SubMerchantID"]="";
        $input_para["pp_Password"]=$data['pp_Password'];
        $input_para["pp_TxnRefNo"]=$data['TxnRefNumber'];
        $input_para["pp_Amount"]=$amount*100;
        $input_para["pp_DiscountedAmount"]="";
        $input_para["pp_DiscountBank"]="";
        $input_para["pp_TxnCurrency"]="PKR";
        $input_para["pp_TxnDateTime"]=$data['TxnDateTime'];
        $input_para["pp_TxnExpiryDateTime"]=$data['ExpiryTime'];
        $input_para["pp_BillReference"]=time();
        $input_para["pp_Description"]=$data['title'];
        $input_para["pp_ReturnURL"]=$data['return_url'];
        $input_para["pp_SecureHash"]="0123456789";
        $input_para["ppmpf_1"]="1";
        $input_para["ppmpf_2"]="2";
        $input_para["ppmpf_3"]="3";
        $input_para["ppmpf_4"]="4";
        $input_para["ppmpf_5"]="5";
        $data['payment_data']=$input_para;
    	$this->load->view('user/gateway/jazzcash/jazzcash_pay', $data);
    }

    public function callback() {
    	
        $params = $this->session->userdata('params');
        $data = array();

        if($_POST['pp_ResponseCode']=='000'){
        	$payment_id = $_POST['pp_TxnRefNo'];
            $bulk_fees=array();
            $params     = $this->session->userdata('params');
         
            foreach ($params['student_fees_master_array'] as $fee_key => $fee_value) {
           
             $json_array = array(
                'amount'          =>  $fee_value['amount_balance'],
                'date'            => date('Y-m-d'),
                'amount_discount' => 0,
                'amount_fine'     => $fee_value['fine_balance'],
                'description'     => $this->lang->line('online_fees_deposit_through_jazzcash_txn_id') . $payment_id,
                'received_by'     => '',
                'payment_mode'    => 'Jazzcash',
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
        }elseif($_POST['pp_ResponseCode']=='112'){
        		 redirect(base_url("user/gateway/payment/paymentfailed"));
        }else{
            $this->session->set_flashdata('msg',$_POST['pp_ResponseMessage']);
                redirect(site_url('user/gateway/jazzcash'));
        }
        
      
    }

}