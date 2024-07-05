<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Gateway_ins_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function add_gateway_ins_response($gateway_ins_response)
    {
        $this->db->insert('gateway_ins_response', $gateway_ins_response);
        return $this->db->insert_id();
    }

    public function get($unique_id, $gateway_name)
    {
        return $this->db->select('gateway_ins.unique_id,gateway_ins.id as gateway_ins_id,gateway_ins.parameter_details,student_fees_processing.*')->from('gateway_ins')->join('student_fees_processing', '`student_fees_processing`.`gateway_ins_id`=gateway_ins.id')->where(array('gateway_ins.gateway_name' => $gateway_name, 'gateway_ins.unique_id' => $unique_id))->get()->result_array();
    }

    public function get_gateway_ins($unique_id, $gateway_name)
    {
        return $this->db->select('*')->from('gateway_ins')->where(array('gateway_ins.gateway_name' => $gateway_name, 'gateway_ins.unique_id' => $unique_id))->get()->row_array();
    }

    public function add_gateway_ins($gateway_ins)
    {
        $this->db->insert('gateway_ins', $gateway_ins);
        return $this->db->insert_id();
    }

    public function update_gateway_ins($gateway_ins)
    {
        $this->db->where('id', $gateway_ins['id']);
        $this->db->update('gateway_ins', $gateway_ins);
        return $gateway_ins['id'];
    }

    public function get_gateway_details($type)
    {
        $this->db->select('*')->from('payment_settings');
        $this->db->where('payment_type', $type);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_statusByUnique_id($unique_id, $gateway_name)
    {
        return $this->db->select('*')->from('gateway_ins')->where(array('gateway_ins.gateway_name' => $gateway_name, 'gateway_ins.unique_id' => $unique_id))->get()->row_array();
    }

    public function deleteBygateway_ins_id($id)
    {
        $this->db->where('gateway_ins_id', $id);
        $this->db->delete('student_fees_processing');
    }

    public function onlineAdmissionStatus($admission_id, $paid_status)
    {
        $this->db->update("online_admissions", array("paid_status" => $paid_status), array("id" => $admission_id));
        if ($paid_status == 0) {
            $this->db->where('admission_id', $admission_id);
            $this->db->delete('online_admission_payment');
        }
    }

    public function fee_processing($bulk_data)
    {
        foreach ($bulk_data as $fee_key => $fee_data) {
            if ($fee_data['fee_category'] == "fees") {
                $fee_data['student_transport_fee_id'] = null;
                $this->db->where('student_fees_master_id', $fee_data['student_fees_master_id']);
                $this->db->where('fee_groups_feetype_id', $fee_data['fee_groups_feetype_id']);

            } elseif ($fee_data['student_transport_fee_id'] > 0 && $fee_data['fee_category'] == "transport") {

                $fee_data['student_fees_master_id'] = null;
                $fee_data['fee_groups_feetype_id']  = null;

                $this->db->where('student_transport_fee_id', $fee_data['student_transport_fee_id']);
            }
            $desc                      = $fee_data['amount_detail']['description'];
            $fee_data['amount_detail'] = json_encode($fee_data['amount_detail']);
            $this->db->insert('student_fees_processing', $fee_data);
            $inserted_id = $this->db->insert_id();
        }
        return $inserted_id;
    }

    public function fee_deposit_bulk($bulk_data)
    {
        foreach ($bulk_data as $fee_key => $fee_data) {
            if ($fee_data['fee_category'] == "fees") {
                $fee_data['student_transport_fee_id'] = null;
                $this->db->where('student_fees_master_id', $fee_data['student_fees_master_id']);
                $this->db->where('fee_groups_feetype_id', $fee_data['fee_groups_feetype_id']);
            } elseif ($fee_data['student_transport_fee_id'] > 0 && $fee_data['fee_category'] == "transport") {
                $fee_data['student_fees_master_id'] = null;
                $fee_data['fee_groups_feetype_id']  = null;
                $this->db->where('student_transport_fee_id', $fee_data['student_transport_fee_id']);
            }

            unset($fee_data['fee_category']);

            $q = $this->db->get('student_fees_deposite');
            if ($q->num_rows() > 0) {
                $desc = $fee_data['amount_detail']['description'];
                $row  = $q->row();
                $this->db->where('id', $row->id);
                $a                                   = json_decode($row->amount_detail, true);
                $inv_no                              = max(array_keys($a)) + 1;
                $fee_data['amount_detail']['inv_no'] = $inv_no;
                $a[$inv_no]                          = $fee_data['amount_detail'];
                $fee_data['amount_detail']           = json_encode($a);
                $this->db->update('student_fees_deposite', $fee_data);
                $inserted_id = $row->id;
            } else {
                $fee_data['amount_detail']['inv_no'] = 1;
                $desc                                = $fee_data['amount_detail']['description'];
                $fee_data['amount_detail']           = json_encode(array('1' => $fee_data['amount_detail']));
                $this->db->insert('student_fees_deposite', $fee_data);
                $inserted_id = $this->db->insert_id();

            }
        }

        return $inserted_id;
    }
    
    public function add_course_payment($data,$delete_id)
    {
        $this->db->insert('online_course_payment', $data);
        if($delete_id){
            $this->db->where('gateway_ins_id',$delete_id);
            $this->db->delete('online_course_processing_payment'); 
        }        
    }

    public function get_processing_payment($gateway_ins_id)
    {
        return  $this->db->select('*')->from('online_course_processing_payment')->where('gateway_ins_id',$gateway_ins_id)->get()->row_array();
    }

    public function deleteprocessingpaymentByid($id)
    {        
        $this->db->where('gateway_ins_id', $id);
        $this->db->delete('online_course_processing_payment');
    }
        
}
