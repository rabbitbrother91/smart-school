<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payhere extends Studentgateway_Controller {

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
        $this->load->view('user/gateway/payhere/index', $data);
    }
 
    public function pay(){

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
        $this->load->view('user/gateway/payhere/index', $data);
    } else {


        $data['name'] = $data['params']['name'];
        $amount =number_format((float)(convertBaseAmountCurrencyFormat($data['params']['fine_amount_balance']+$data['params']['total'])), 2, '.', ''); 
        $htmlform=array(
            'merchant_id'=>$this->api_config->api_publishable_key,
            'return_url'=>base_url().'user/gateway/payhere/success',
            'cancel_url'=>base_url().'user/gateway/payhere/cancel',
            'notify_url'=>base_url().'gateway_ins/payhere',
            'order_id'=>time().rand(99,999),
            'items'=>'Student Fees',
            'currency'=>$data['params']['invoice']->currency_name,
            'amount'=>$amount,
            'first_name'=>$data['name'],
            'last_name'=>'',
            'email'=>$_POST['email'],
            'phone'=>$_POST['phone'],
            'address'=>'',
            'city'=>'',
            'country'=>''
        );

        $data['htmlform']=$htmlform;
        $data['params']['transaction_id']=$htmlform['order_id'];
        $this->session->set_userdata("params", $data['params']);
        $ins_data=array(
        'unique_id'=>$htmlform['order_id'],
        'parameter_details'=>json_encode($htmlform),
        'gateway_name'=>'payhere',
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
            'description'     => $this->lang->line('online_fees_deposit_through_payhere_txn_id') . $htmlform['order_id'],
            'received_by'     => '',
            'payment_mode'    => 'payhere'
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
        $this->load->view('user/gateway/payhere/pay', $data);
        }                 
    }

    public function success(){
        
         $params = $this->session->userdata('params');
            $parameter_data=$this->gateway_ins_model->get_gateway_ins($params['transaction_id'],'payhere');
            if($parameter_data['payment_status']=='2'){
                 redirect(base_url("user/gateway/payment/successinvoice"));
            }elseif($parameter_data['payment_status']=='-2'){
                $this->gateway_ins_model->deleteBygateway_ins_id($parameter_data['id']); 
                redirect(base_url("user/gateway/payment/paymentfailed"));
            }elseif($parameter_data['payment_status']=='0'){
                redirect(base_url("user/gateway/payment/paymentprocessing"));
            }else{
                 redirect(base_url("user/gateway/payment/paymentfailed"));  
            }
    }

    public function cancel(){
        redirect(base_url("user/gateway/payment/paymentfailed"));  
    }

}