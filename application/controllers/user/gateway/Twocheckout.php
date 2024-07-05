<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Twocheckout extends Studentgateway_Controller {

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
        $this->load->view('user/gateway/twocheckout/index', $data);
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
             
            $this->load->view('user/gateway/twocheckout/index', $data);
        } else {
            $data['currency']=$data['params']['invoice']->currency_name;;
            $data['amount'] =number_format((float)(convertBaseAmountCurrencyFormat($data['params']['fine_amount_balance']+$data['params']['total'])), 2, '.', '');
            $data['api_config']=$this->api_config;
            $this->load->view('user/gateway/twocheckout/pay', $data);
            
                }
                
            
            
            
        
       
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