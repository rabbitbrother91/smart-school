<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Transportfee_model extends MY_Model
{

    public function add($insert_data, $update_data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($insert_data)) {
            $this->db->insert_batch('transport_feemaster', $insert_data);
        }
        if (!empty($update_data)) {
            $this->db->update_batch('transport_feemaster', $update_data, 'id');
        }

        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }

    }

    public function getSessionFees($session_id)
    {
        $data = $this->db->select('*')->from('transport_feemaster')->where('session_id', $session_id)->get()->result_array();
        return $data;
    }

    public function transportfesstype($session_id,$month)
    {
        $data = $this->db->select('transport_feemaster.*,transport_feemaster.month as type,"transport_fees" as code')->from('transport_feemaster')->where('month', $month)->where('session_id', $session_id)->get()->row();      
        return $data;
    }

}
