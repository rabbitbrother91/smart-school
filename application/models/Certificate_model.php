<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Certificate_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function addcertificate($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('certificates', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  certificates id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
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
        } else {
            $this->db->insert('certificates', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On certificates id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
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
            return $insert_id;
        }
    }

    public function certificateList()
    {
        $this->db->select('*');
        $this->db->from('certificates');
        $this->db->where('status = 1');
        $this->db->where('created_for = 2');
        $query = $this->db->get();
        return $query->result();
    }

    public function get($id)
    {
        $this->db->select('*');
        $this->db->from('certificates');
        $this->db->where('status = 1');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('certificates');
        $message   = DELETE_RECORD_CONSTANT . " On certificates id " . $id;
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

    public function getstudentcertificate()
    {
        $this->db->select('*');
        $this->db->from('certificates');
        $this->db->where('created_for = 2');
        $query = $this->db->get();
        return $query->result();
    }

    public function certifiatebyid($id)
    {
        $this->db->select('*');
        $this->db->from('certificates');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

}
