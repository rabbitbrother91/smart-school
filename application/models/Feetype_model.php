<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Feetype_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = null)
    {
        $this->db->select()->from('feetype');
        $this->db->where('is_system', 0);
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->where('is_system', 0);
        $this->db->delete('feetype');
        $message   = DELETE_RECORD_CONSTANT . " On  fee type id " . $id;
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

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('feetype', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  fee type id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('feetype', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On  fee type id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);

        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $id;
        }
    }

    public function check_exists($str)
    {
        $name = $this->security->xss_clean($str);
        $id   = $this->input->post('id');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_data_exists($name, $id)) {
            $this->form_validation->set_message('check_exists', $this->lang->line('already_exists'));
            return false;
        } else {
            return true;
        }
    }

    public function check_data_exists($name, $id)
    {
        $this->db->where('type', $name);
        $this->db->where('id !=', $id);
        $query = $this->db->get('feetype');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkFeetypeByName($name)
    {
        $this->db->where('type', $name);
        $query = $this->db->get('feetype');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

}
