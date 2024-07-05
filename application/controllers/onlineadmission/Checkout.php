<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Checkout extends OnlineAdmission_Controller
{
    public $pay_method;
    public $setting;

    public function __construct()
    {
        parent::__construct();
        $this->setting = $this->setting_model->getSetting();
        $this->pay_method = $this->paymentsetting_model->getActiveMethod();
    }

    public function index()
    {
        
        $post_data = $this->security->xss_clean($this->input->post());
        $reference = $post_data['admission_id'];
        $this->session->set_userdata("reference",$reference);
        $data = array();
        if (!empty($this->pay_method)) {
            if ($this->pay_method->payment_type == "payu") {
                redirect(base_url("onlineadmission/payu"));
            } elseif ($this->pay_method->payment_type == "stripe") {
                redirect(base_url("onlineadmission/stripe"));
            } elseif ($this->pay_method->payment_type == "ccavenue") {
                redirect(base_url("onlineadmission/ccavenue"));
            } elseif ($this->pay_method->payment_type == "paypal") {
                redirect(base_url("onlineadmission/paypal"));
            } elseif ($this->pay_method->payment_type == "instamojo") {
                redirect(base_url("onlineadmission/instamojo"));
            } elseif ($this->pay_method->payment_type == "paytm") {
                redirect(base_url("onlineadmission/paytm"));
            } elseif ($this->pay_method->payment_type == "razorpay") {
                redirect(base_url("onlineadmission/razorpay"));
            } elseif ($this->pay_method->payment_type == "paystack") {
                redirect(base_url("onlineadmission/paystack"));
            } elseif ($this->pay_method->payment_type == "midtrans") {
                redirect(base_url("onlineadmission/midtrans"));
            }elseif ($this->pay_method->payment_type == "ipayafrica") {
                redirect(base_url("onlineadmission/ipayafrica"));
            }elseif ($this->pay_method->payment_type == "jazzcash") {
                redirect(base_url("onlineadmission/jazzcash"));
            }elseif ($this->pay_method->payment_type == "pesapal") {
                redirect(base_url("onlineadmission/pesapal"));
            }elseif ($this->pay_method->payment_type == "flutterwave") {
                redirect(base_url("onlineadmission/flutterwave"));
            }elseif ($this->pay_method->payment_type == "billplz") {
                redirect(base_url("onlineadmission/billplz"));
            }elseif ($this->pay_method->payment_type == "sslcommerz") {
                redirect(base_url("onlineadmission/sslcommerz"));
            }elseif ($this->pay_method->payment_type == "walkingm") {
                redirect(base_url("onlineadmission/walkingm"));
            }elseif ($this->pay_method->payment_type == "mollie") {
                redirect(base_url("onlineadmission/mollie"));
            }elseif ($this->pay_method->payment_type == "cashfree") {
                redirect(base_url("onlineadmission/cashfree"));
            }elseif ($this->pay_method->payment_type == "payfast") {
                redirect(base_url("onlineadmission/payfast"));
            }elseif ($this->pay_method->payment_type == "toyyibpay") {
                redirect(base_url("onlineadmission/toyyibpay"));
            }elseif ($this->pay_method->payment_type == "twocheckout") {
                redirect(base_url("onlineadmission/twocheckout"));
            }elseif ($this->pay_method->payment_type == "skrill") {
                redirect(base_url("onlineadmission/skrill"));
            }elseif ($this->pay_method->payment_type == "payhere") {
                redirect(base_url("onlineadmission/payhere"));
            }elseif ($this->pay_method->payment_type == "onepay") {
                redirect(base_url("onlineadmission/onepay"));
            }
        }
    }

    public function successinvoice($reference_no){
        $data['setting'] = $this->setting;
        $data["reference_no"] = $reference_no;
        $this->load->view("onlineadmission/success_invoice",$data);
    }

    public function processinginvoice($reference_no=null){
       $data['setting'] = $this->setting;
       $data["reference_no"] = $reference_no;
       $this->load->view("onlineadmission/processing_invoice",$data);  
    }

    public function paymentfailed($reference_no=null){
        $data['setting'] = $this->setting;
        $data["reference_no"] = $reference_no;
        $this->load->view("onlineadmission/payment_failed",$data);
    }    

}