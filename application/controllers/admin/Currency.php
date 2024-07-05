<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Currency extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!($this->rbac->hasPrivilege('currency', 'can_view'))) {
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'currency/index');
        $data['title'] = 'Currency List';

        $setting = $this->setting_model->getSchoolDetail();
        $languagelist  = $this->currency_model->get();

        $data['setting'] = $setting;

        $data['languagelist'] = $languagelist;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/currency/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function editprice()
    {
        $base_price   = $this->input->post('base_price');
        $currency_id  = $this->input->post('currency_id');
        $data = array(
            'id'         => $currency_id,
            'base_price' => $base_price,
        );
        $this->currency_model->add($data);
        echo json_encode(['status' => 1, 'message' => $this->lang->line('update_message')]);
    }
	
	public function editsymbol()
    {
        $symbol   = $this->input->post('symbol');
        $currency_id  = $this->input->post('currency_id');
        $data = array(
            'id'         => $currency_id,
            'symbol' => $symbol,
        );
        $this->currency_model->add($data);
        echo json_encode(['status' => 1, 'message' => $this->lang->line('update_message')]);
    }

    public function changestatus()
    {
        $status       = $this->input->post('status');
        $id  = $this->input->post('id');
        $data = array(
            'id'     => $id,
            'is_active' => $status,
        );
        $this->currency_model->add($data);
        echo json_encode(['status' => 1, 'message' => $this->lang->line('update_message')]);
    }


    public function changeactive()
    {
        $currency_id       = $this->input->post('currency_id');
        $id  = $this->input->post('id');
        $setting_data = array(
            'id'     => $id,
            'currency' =>$currency_id ,
        );

        $this->currency_model->update_currency($setting_data);
        echo json_encode(['status' => 1, 'message' => $this->lang->line('update_message')]);
    }

       public function change_currency()
    {
        // $session         = $this->session->userdata('admin');
        // $id              = $session['id'];
        $currency_id=$this->input->post('currency_id');
        //================
        $currency=$this->currency_model->get($currency_id);
        $staff_id= $this->customlib->getStaffID();
        $update_data=array('id'=>$staff_id,'currency_id'=>$currency_id);
        $this->staff_model->update($update_data);
      
        $this->session->userdata['admin']['currency_base_price'] = $currency->base_price;
        $this->session->userdata['admin']['currency_symbol'] = $currency->symbol;
        $this->session->userdata['admin']['currency'] = $currency_id;
        echo json_encode(['status' => 1, 'message' => $this->lang->line('currency_changed_successfully')]);
      
    }

    public function getAmountFormat(){
       $total_fees_alloted= $this->input->post('total_fees_alloted');
       $amount=amountFormat($total_fees_alloted);
       echo json_encode(['status' => 1, 'amount' => $amount]);
    }

}
