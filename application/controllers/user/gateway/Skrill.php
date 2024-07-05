<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Skrill extends Studentgateway_Controller
{
    public $payment_method = array();
    public $pay_method     = array();
    public $patient_data;
    public $setting;

    public function __construct()
    {
        parent::__construct();
        $this->config->load("payroll");
        $this->load->library('Enc_lib');
        $this->load->library('Customlib');
        $this->patient_data   = $this->session->userdata('patient');
        $this->payment_method = $this->paymentsetting_model->get();
        $this->pay_method     = $this->paymentsetting_model->getActiveMethod();
        $this->marital_status = $this->config->item('marital_status');
        $this->payment_mode   = $this->config->item('payment_mode');
        $this->blood_group    = $this->config->item('bloodgroup');
        $this->setting        = $this->setting_model->get();
        $this->setting[0]['currency_symbol'] = $this->customlib->getSchoolCurrencyFormat();
        $this->load->model(array('gateway_ins_model'));
        $this->load->library('mailsmsconf');
    }
 
    public function index()
    {
     $data = array();
        $data['params'] = $this->session->userdata('params');

        $data['setting'] = $this->setting;
        $data['api_error'] = array();
        $data['student_data'] = $this->student_model->get($data['params']['student_id']);
        $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
        $this->load->view('user/gateway/skrill/index', $data);
    }

    public function pay() {

        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $data = array();
            $data['params'] = $this->session->userdata('params');
            $data['setting'] = $this->setting;
            $data['api_error'] = $data['api_error'] = array();
            $data['student_data'] = $this->student_model->get($data['params']['student_id']);
            $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
            $this->load->view('user/gateway/skrill/index', $data);

        } else {

            $params = $this->session->userdata('params');
            $data['params']=$params;            
            $student_id = $params['student_id'];
            $data['total'] =number_format((float)(convertBaseAmountCurrencyFormat($params['fine_amount_balance']+$params['total'])), 2, '.', '');;
            $data['symbol'] = $params['invoice']->symbol;
            $data['currency_name'] = $params['invoice']->currency_name;
            $data['name'] = $params['name'];
            $data['guardian_phone'] = $params['guardian_phone'];           
            $payment_data['pay_to_email'] =$this->pay_method->api_email;
            $payment_data['transaction_id'] ='A'.time();
            $payment_data['return_url'] =base_url().'user/gateway/skrill/success';
            $payment_data['cancel_url'] =base_url().'user/gateway/skrill/cancel';
            $payment_data['status_url'] =base_url().'gateway_ins/skrill';
            $payment_data['language'] ='EN';
            $payment_data['merchant_fields'] ='customer_number,session_id';
            $payment_data['customer_number'] ='C'.time();
            $payment_data['session_ID'] ='A3D'.time();;
            $payment_data['pay_from_email'] =$_POST['email'];
            $payment_data['amount2_description'] ='';
            $payment_data['amount2'] ='';
            $payment_data['amount3_description'] ='';
            $payment_data['amount3'] ='';
            $payment_data['amount4_description'] ='';
            $payment_data['amount4'] ='';
            $payment_data['amount'] =$data['total'];
            $payment_data['currency'] =$data['currency_name'];
            $payment_data['firstname'] =$params['name'];
            $payment_data['lastname'] ='';
            $payment_data['address'] ='';
            $payment_data['postal_code'] ='';
            $payment_data['city'] ='';
            $payment_data['country'] ='';
            $payment_data['detail1_description'] ='';
            $payment_data['detail1_text'] ='';
            $payment_data['detail2_description'] ='';
            $payment_data['detail2_text'] ='';
            $payment_data['detail3_description'] ='';
            $payment_data['detail3_text'] ='';
            
            $data['form_fields']=$payment_data;
             $data['params']['transaction_id']=$payment_data['transaction_id'];
            $this->session->set_userdata("params", $data['params']);
            $ins_data=array(
            'unique_id'=>$payment_data['transaction_id'],
            'parameter_details'=>json_encode($payment_data),
            'gateway_name'=>'skrill',
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
                'description'     => $this->lang->line('online_fees_deposit_through_skrill_txn_id') . $payment_data['transaction_id'],
                'received_by'     => '',
                'payment_mode'    => 'skrill'
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
            $this->load->view('user/gateway/skrill/pay', $data);
            
        }
    } 
 
    public function success(){        
         $params = $this->session->userdata('params');
            $parameter_data=$this->gateway_ins_model->get_gateway_ins($_GET['transaction_id'],'skrill');
            if($parameter_data['payment_status']=='2'){
                 redirect(base_url("user/gateway/payment/successinvoice"));
            }elseif(($parameter_data['payment_status']=='-1') || ($parameter_data['payment_status']=='-2')){
                $this->gateway_ins_model->deleteBygateway_ins_id($parameter_data['id']); 
                redirect(base_url("user/gateway/payment/paymentfailed"));
            }else{
                redirect(base_url("user/gateway/payment/paymentprocessing"));
            }
    }

    public function cancel(){        
        redirect(base_url("user/gateway/payment/paymentfailed"));            
    }
}