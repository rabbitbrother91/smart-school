<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sslcommerz extends Studentgateway_Controller
{
    public $api_config = "";
    public function __construct()
    {
        parent::__construct();

        $this->api_config = $this->paymentsetting_model->getActiveMethod();        
        $this->setting    = $this->setting_model->get();
        $this->setting[0]['currency_symbol'] = $this->customlib->getSchoolCurrencyFormat();
        $this->load->library('mailsmsconf');
      
    }
 
    public function index()
    {

        $data = array();
        $data['params'] = $this->session->userdata('params');

        $data['setting'] = $this->setting;
        $data['api_error'] ='';
        $data['student_data'] = $this->student_model->get($data['params']['student_id']);
        $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
        $this->load->view('user/gateway/sslcommerz/index', $data);

    }

    public function pay()
    {
        $params       = $this->session->userdata('params');
        $student_data = $this->student_model->get($params['student_id']);

        $requestData        = array();
        $CURLOPT_POSTFIELDS = array(
            'store_id'         => $this->api_config->api_publishable_key,
            'store_passwd'     => $this->api_config->api_password,
            'total_amount'     => number_format((float) (convertBaseAmountCurrencyFormat($params['fine_amount_balance'] + $params['total'])), 2, '.', ''),
            'currency'         => $params['invoice']->currency_name,
            'tran_id'          => abs(crc32(uniqid())),
            'success_url'      => base_url() . 'user/gateway/sslcommerz/success',
            'fail_url'         => base_url() . 'user/gateway/sslcommerz/fail',
            'cancel_url'       => base_url() . 'user/gateway/sslcommerz/cancel',
            'cus_name'         => $params['name'],
            'cus_email'        => !empty($_POST['email']) ? $_POST['email'] : "example@email.com",
            'cus_add1'         => !empty($student_data['permanent_address']) ? $student_data['permanent_address'] : "Dhaka",
            'cus_phone'        => !empty($_POST['phone']) ? $_POST['phone'] : "01711111111",
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
        curl_setopt($ch, CURLOPT_URL, 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//https://securepay.sslcommerz.com/gwprocess/v4/api.php
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
        $data = array();
        $data['params'] = $this->session->userdata('params');
        $data['setting'] = $this->setting;
        $data['api_error'] = $response->failedreason;
        $data['student_data'] = $this->student_model->get($data['params']['student_id']);
        $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
        $this->load->view('user/gateway/sslcommerz/index', $data);
        }else{
            header("Location: $response->GatewayPageURL");
        }

    }

    public function success()
    {

        if ($_POST['status'] == 'VALID') {
            $params = $this->session->userdata('params');

            $payment_id = $_POST['val_id'];
            $bulk_fees=array();
                    $params     = $this->session->userdata('params');
                 
                    foreach ($params['student_fees_master_array'] as $fee_key => $fee_value) {
                   
                     $json_array = array(
                        'amount'          =>  $fee_value['amount_balance'],
                        'date'            => date('Y-m-d'),
                        'amount_discount' => 0,
                        'amount_fine'     => $fee_value['fine_balance'],
                        'description'     => $this->lang->line('online_fees_deposit_through_sslcommerz_txn_id') . $payment_id,
                        'received_by'     => '',
                        'payment_mode'    => 'Sslcommerz',
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
            redirect(base_url("user/gateway/payment/successinvoice/" . $invoice_detail->invoice_id . "/" . $invoice_detail->sub_invoice_id));
        } else {

            redirect(base_url("user/gateway/payment/paymentfailed"));
        }

    }

    public function fail()
    {

        redirect(base_url("students/payment/paymentfailed"));

    }
    public function cancel()
    {

        redirect(base_url("students/payment/paymentfailed"));

    }

}