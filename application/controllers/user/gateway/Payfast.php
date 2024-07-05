<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Payfast extends Studentgateway_Controller
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
        $this->load->model(array('gateway_ins_model'));
        $this->patient_data   = $this->session->userdata('patient');
        $this->payment_method = $this->paymentsetting_model->get();
        $this->pay_method     = $this->paymentsetting_model->getActiveMethod();
        $this->marital_status = $this->config->item('marital_status');
        $this->payment_mode   = $this->config->item('payment_mode');
        $this->blood_group    = $this->config->item('bloodgroup');
        $this->setting        = $this->setting_model->get();
        $this->setting[0]['currency_symbol'] = $this->customlib->getSchoolCurrencyFormat();
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
        $this->load->view('user/gateway/payfast/index', $data);
    }

    public function pay() {
        $data = array();
        $data['params'] = $this->session->userdata('params');
        $data['setting'] = $this->setting;
        $data['api_error'] = array();
        $data['student_data'] = $this->student_model->get($data['params']['student_id']);
        $data['student_fees_master_array']=$data['params']['student_fees_master_array'];
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
              $this->load->view('user/gateway/payfast/index', $data);
        } else {
            $params = $this->session->userdata('params');
            $data = array();
            $student_id = $params['student_id'];
            $data['symbol'] = $params['invoice']->symbol;
            $data['currency_name'] = $params['invoice']->currency_name;
            $data['name'] = $params['name'];
            $data['guardian_phone'] = $params['guardian_phone'];
            $cartTotal = convertBaseAmountCurrencyFormat($params['fine_amount_balance']+$params['total']);// This amount needs to be sourced from your application
            $data = array(
            'merchant_id' => $this->pay_method->api_publishable_key,
            'merchant_key' => $this->pay_method->api_secret_key,
            'return_url' => base_url().'user/gateway/payfast/success',
            'cancel_url' => base_url().'user/gateway/payfast/cancel',
            'notify_url' => base_url().'gateway_ins/payfast',
            'name_first' => $params['name'],
            'name_last'  => 'name_last',
            'email_address'=> $_POST['email'],
            'm_payment_id' => time().rand(99,999), //Unique payment ID to pass through to notify_url
            'amount' => number_format( sprintf( '%.2f', $cartTotal ), 2, '.', '' ),
            'item_name' => 'fees#'.rand(99,999),
            );
           
            $signature = $this->generateSignature($data,$this->pay_method->salt);
            $data['signature'] = $signature;
           
            $params['transaction_id']=$data['m_payment_id'];
            
             $ins_data=array(
            'unique_id'=>$data['m_payment_id'],
            'parameter_details'=>json_encode($data),
            'gateway_name'=>'payfast',
            'module_type'=>'fees',
            'payment_status'=>'processing',
            );
            $gateway_ins_id=$this->gateway_ins_model->add_gateway_ins($ins_data);
            $bulk_fees=array();
         
            foreach ($params['student_fees_master_array'] as $fee_key => $fee_value) {
           
             $json_array = array(
                'amount'          =>  $fee_value['amount_balance'],
                'date'            => date('Y-m-d'),
                'amount_discount' => 0,
                'amount_fine'     => $fee_value['fine_balance'],
                'description'     => $this->lang->line('online_fees_deposit_through_payfast_txn_id') . $data['m_payment_id'],
                'received_by'     => '',
                'payment_mode'    => 'Payfast'
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
            $this->session->set_userdata("params", $params);
            // If in testing mode make use of either sandbox.payfast.co.za or www.payfast.co.za
            $testingMode = true;
            $pfHost = $testingMode ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
            $htmlForm = '<form action="https://'.$pfHost.'/eng/process" method="post" name="pay_now">';
            foreach($data as $name=> $value)
            {
            $htmlForm .= '<input name="'.$name.'" type="hidden" value=\''.$value.'\' />';
            }
            $htmlForm .= '</form>';
            $data['htmlForm']= $htmlForm;
 
            $this->load->view('user/gateway/payfast/pay', $data);
            
        }
    }
 
    public  function generateSignature($data, $passPhrase = null) {
    // Create parameter string
    $pfOutput = '';
    foreach( $data as $key => $val ) {
        if($val !== '') {
            $pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
        }
    } 

    // Remove last ampersand
    $getString = substr( $pfOutput, 0, -1 );
    if( $passPhrase !== null ) {
        $getString .= '&passphrase='. urlencode( trim( $passPhrase ) );
    }
    return md5( $getString );
}
 
    public function success()
    {              
            $params = $this->session->userdata('params');
            $parameter_data=$this->gateway_ins_model->get_gateway_ins($params['transaction_id'],'payfast');
            if($parameter_data['payment_status']=='success'){
                 redirect(base_url("user/gateway/payment/successinvoice"));
            }elseif($parameter_data['payment_status']=='CANCELLED'){
                $this->gateway_ins_model->deleteBygateway_ins_id($parameter_data['id']); 
                redirect(base_url("user/gateway/payment/paymentfailed"));
            }else{
                redirect(base_url("user/gateway/payment/paymentprocessing"));
            }
    }

    public function cancel(){
        $params = $this->session->userdata('params');
        $parameter_data=$this->gateway_ins_model->get_gateway_ins($params['payfast_payment_id'],'payfast');
        $this->gateway_ins_model->deleteBygateway_ins_id($parameter_data['id']); 
        redirect(base_url("user/gateway/payment/paymentfailed"));
    }
}