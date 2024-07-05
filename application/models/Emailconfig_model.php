<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Emailconfig_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = null)
    {
        $this->db->select()->from('email_config');
        $this->db->order_by('id');
        $query = $this->db->get();
        return $query->row();
    }

    public function get_emailbytype($email_type)
    {
        $this->db->select()->from('email_config');
        $this->db->where('email_type', $email_type);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function update_emailconfig($email_type)
    {
        $data = array(
            'smtp_username' => $this->input->post('smtp_username'),
            'smtp_password' => $this->input->post('smtp_password'),
        );

        $this->db->where('email_type', $email_type);
        $this->db->update('email_config', $data);
    }

    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->select()->from('email_config');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            $result = $q->row();
            $this->db->where('id', $result->id);
            $this->db->update('email_config', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  email config id " . $result->id;
            $action    = "Update";
            $record_id = $result->id;
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
            $this->db->insert('email_config', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On email config id " . $insert_id;
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

    public function getActiveEmail()
    {
        $this->db->select()->from('email_config');
        $this->db->where('is_active', 'yes');
        $query = $this->db->get();
        return $query->row();
    }

}
