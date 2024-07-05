<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Transport extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array("transportfee_model", "routepickuppoint_model"));
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->library("datatables");
    }

    public function feemaster()
    {
        if (!($this->rbac->hasPrivilege('transport_fees_master', 'can_view'))) {
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'transport/feemaster');
        $current_session               = $this->setting_model->getCurrentSession();
        $data                          = array();
        $month_list                    = $this->customlib->getMonthDropdown($this->sch_setting_detail->start_month);

        $data['title']                 = 'student fees';
        $data['month_list']            = $month_list;
        
         $month_list= $this->customlib->getMonthDropdown($this->sch_setting_detail->start_month);
       
        foreach($month_list as $key => $value){
            $data['transportfees'][]=(array)$this->transportfee_model->transportfesstype($current_session,$key);
        }
        
        $route_pickup_point_id         = $this->input->post('route_pickup_point_id');
        $data['route_pickup_point_id'] = $route_pickup_point_id;
        $route_pickup_point            = $this->routepickuppoint_model->get($route_pickup_point_id);
        $data['route_pickup_point']    = $route_pickup_point;

        $month_row = 1;
        foreach ($month_list as $month_key => $month_value) {
            $this->form_validation->set_rules('due_date_' . $month_row, $this->lang->line('due_date'), 'trim|required|xss_clean');
            $month_row += 1;
        }
        
        $rows        = $this->input->post('rows');
        if(!empty($rows)){
            foreach ($rows as $row_key => $row_value) {
                   
                    $fine_type =     $this->input->post('fine_type_' . $row_value);
                    if($fine_type == 'fix'){
                        $this->form_validation->set_rules('fine_amount_' . $row_value, $this->lang->line('fix_amount'), 'trim|required|numeric|xss_clean'); 
                    }elseif($fine_type == 'percentage'){
                        $this->form_validation->set_rules('percentage_' . $row_value, $this->lang->line('percentage'), 'trim|required|numeric|xss_clean'); 
                    }else{
                        
                    }            
            }
        }         
        
        if ($this->form_validation->run() == true) {
            $rows        = $this->input->post('rows');
            $insert_data = array();
            $update_data = array();

            foreach ($rows as $row_key => $row_value) {

                $prev_id = $this->input->post('prev_id_' . $row_value);
                $fine_amount    =   empty2null($this->input->post('fine_amount_' . $row_value));
                
                if($fine_amount){
                   $fine_amount =  convertCurrencyFormatToBaseAmount($fine_amount);                    
                }
                
                if ($prev_id > 0) {
                    
                    $old_update                    = array();
                    $old_update['id']              = $prev_id;
                    $old_update['month']           = $this->input->post('month_' . $row_value);
                    $old_update['due_date']        = $this->customlib->dateFormatToYYYYMMDD($this->input->post('due_date_' . $row_value));
                    $old_update['fine_type']       = $this->input->post('fine_type_' . $row_value);
                    $old_update['fine_percentage'] = empty2null($this->input->post('percentage_' . $row_value));
                    $old_update['fine_amount']     = $fine_amount;
                    $old_update['session_id']      = $current_session;
                    $update_data[]                 = $old_update;

                } else {

                    $new_insert                    = array();
                    $new_insert['month']           = $this->input->post('month_' . $row_value);
                    $new_insert['due_date']        = $this->customlib->dateFormatToYYYYMMDD($this->input->post('due_date_' . $row_value));
                    $new_insert['fine_type']       = $this->input->post('fine_type_' . $row_value);
                    $new_insert['fine_percentage'] = empty2null($this->input->post('percentage_' . $row_value));
                    $new_insert['fine_amount']     = $fine_amount;
                    $new_insert['session_id']      = $current_session;
                    $insert_data[]                 = $new_insert;
                    
                }

            }

            $this->transportfee_model->add($insert_data, $update_data);
            $this->session->set_flashdata('msg', $this->lang->line('success_message'));
            redirect('admin/transport/feemaster');
        }

        $this->load->view('layout/header');
        $this->load->view('admin/transport/feemaster', $data);
        $this->load->view('layout/footer');
    }

}
