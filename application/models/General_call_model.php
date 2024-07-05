<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class general_call_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session      = $this->setting_model->getCurrentSession();
        $this->current_session_name = $this->setting_model->getCurrentSessionName();
        $this->start_month          = $this->setting_model->getStartMonth();
    }

    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->insert('general_calls', $data);
        $id        = $this->db->insert_id();
        $message   = INSERT_RECORD_CONSTANT . " On  Phone Call Log id " . $id;
        $action    = "Insert";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function call_list($id = null)
    {
        $this->db->select()->from('general_calls');
        if ($id != null) {
            $this->db->where('general_calls.id', $id);
        } else {
            $this->db->order_by('general_calls.id','desc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getcalllist($id = null)
    {

        if ($id != null) {
            $this->datatables->where('general_calls.id', $id);
        }  
        $this->datatables->sort('general_calls.id','desc');        
        $this->datatables
            ->select('general_calls.id,general_calls.name,general_calls.contact,general_calls.call_type,general_calls.follow_up_date,general_calls.date')
            ->searchable('general_calls.name,general_calls.contact,general_calls.date,general_calls.follow_up_date,general_calls.call_type')
            ->orderable('general_calls.name,general_calls.contact,general_calls.date,general_calls.follow_up_date,general_calls.call_type')
            ->from('general_calls');
        return $this->datatables->generate('json');
    }

    public function delete($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('general_calls');
        $message   = DELETE_RECORD_CONSTANT . " On Phone Call Log id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function call_update($id, $data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->update('general_calls', $data);
        $message   = UPDATE_RECORD_CONSTANT . " On Phone Call Log id " . $id;
        $action    = "Update";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

}
