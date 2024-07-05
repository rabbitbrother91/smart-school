<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Toyyibpay extends Studentgateway_Controller {

    public $api_config = "";

    public function __construct() {
        parent::__construct();
 
        $this->api_config = $this->paymentsetting_model->getActiveMethod();
        $this->setting = $this->setting_model->get();
        $this->setting[0]['currency_symbol'] = $this->customlib->getSchoolCurrencyFormat();
        $this->load->model(array('gateway_ins_model'));
    }
  
    public function index() {
 
        $data = array();
        $data['params'] = $this->session->userdata('params');
       
        $data['setting'] = $this->setting;
        $data['api_error'] = array();
        $data['student_data'] = $this->student_model->get($data['params']['student_id']);
        $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
        $this->load->view('user/gateway/toyyibpay/index', $data);
    }
 
    public function pay()
    {
        $result=array();
        $data['params'] = $this->session->userdata('params');
        $data['setting'] = $this->setting;
        $data['api_error'] = array();
        $data['student_data'] = $this->student_model->get($data['params']['student_id']);
        $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
        $data['api_error'] = $data['api_error'] = array();
        $data['student_data'] = $this->student_model->get($data['params']['student_id']);
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            
            
        } else {

            $data['name'] = $data['params']['name'];
            
            $amount =number_format((float)(convertBaseAmountCurrencyFormat($data['params']['fine_amount_balance']+$data['params']['total'])), 2, '.', ''); 
            $payment_data = array(
                'userSecretKey'=>$this->api_config->api_secret_key,
                'categoryCode'=>$this->api_config->api_signature,
                'billName'=>'Fees',
                'billDescription'=>'Student Fees',
                'billPriceSetting'=>1,
                'billPayorInfo'=>1,
                'billAmount'=>$amount,
                'billReturnUrl'=>base_url().'user/gateway/toyyibpay/success',
                'billCallbackUrl'=>base_url().'gateway_ins/toyyibpay',
                'billExternalReferenceNo' => time().rand(99,999),
                'billTo'=>$data['name'],
                'billEmail'=>$_POST['email'],
                'billPhone'=>$_POST['phone'],
                'billSplitPayment'=>0,
                'billSplitPaymentArgs'=>'',
                'billPaymentChannel'=>'0',
                'billContentEmail'=>'Thank you for fees submission!',
                'billChargeToCustomer'=>1
              );  

              $curl = curl_init();
              curl_setopt($curl, CURLOPT_POST, 1);
              curl_setopt($curl, CURLOPT_URL, 'https://dev.toyyibpay.com/index.php/api/createBill');  
              curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($curl, CURLOPT_POSTFIELDS, $payment_data);

              $result = curl_exec($curl);
              $info = curl_getinfo($curl);  
              curl_close($curl);
              $obj = json_decode($result);

            if (!empty($obj)) {
            $data['params']['transaction_id']=$payment_data['billExternalReferenceNo'];
            $this->session->set_userdata("params", $data['params']);
            $ins_data=array(
            'unique_id'=>$payment_data['billExternalReferenceNo'],
            'parameter_details'=>json_encode($payment_data),
            'gateway_name'=>'toyyibpay',
            'module_type'=>'fees',
            'payment_status'=>'processing',
            );
            $gateway_ins_id=$this->gateway_ins_model->add_gateway_ins($ins_data);
            $bulk_fees=array();
         
            foreach ($data['params']['student_fees_master_array'] as $fee_key => $fee_value) {
           
            $json_array = array(
                'amount'          =>  $fee_value['amount_balance'],
                'date'            => date('Y-m-d'),
                'amount_discount' => 0,
                'amount_fine'     => $fee_value['fine_balance'],
                'description'     => $this->lang->line('online_fees_deposit_through_toyyibpay_txn_id') . $payment_data['billExternalReferenceNo'],
                'received_by'     => '',
                'payment_mode'    => 'toyyibPay'
            );

            $insert_fee_data = array(
                'gateway_ins_id'=>$gateway_ins_id,
                'fee_category'=>$fee_value['fee_category'],
                'student_transport_fee_id'=>$fee_value['student_transport_fee_id'],
                'student_fees_master_id' => $fee_value['student_fees_master_id'],
                'fee_groups_feetype_id'  => $fee_value['fee_groups_feetype_id'],
                'amount_detail'          => $json_array,
            ); 
            
                $bulk_fees[]=$insert_fee_data;
                //========
            }

            $fee_processing=$this->gateway_ins_model->fee_processing($bulk_fees);

                if((isset($obj->status) && $obj->status=='error')){
                    $result=$obj->msg;  
                    
                }else{
                  $url = "https://dev.toyyibpay.com/".$obj[0]->BillCode;
                    header("Location: $url");
                }
                 
                }
                }                
             
            $data['api_error'] = $result;
            
        
        $this->load->view('user/gateway/toyyibpay/index', $data);
    }

    public function success(){
        
            $params = $this->session->userdata('params');
            $parameter_data=$this->gateway_ins_model->get_gateway_ins($params['transaction_id'],'toyyibpay');
            if($parameter_data['payment_status']=='1'){
                 redirect(base_url("user/gateway/payment/successinvoice"));
            }elseif($parameter_data['payment_status']=='3'){
                $this->gateway_ins_model->deleteBygateway_ins_id($parameter_data['id']); 
                redirect(base_url("user/gateway/payment/paymentfailed"));
            }else{
                redirect(base_url("user/gateway/payment/paymentprocessing"));
            }
    }

}