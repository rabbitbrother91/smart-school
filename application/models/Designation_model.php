<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Designation_model extends MY_model
{

    public function get($id = null)
    {
        if (!empty($id)) {
            $query = $this->db->where("id", $id)->get("staff_designation");
            return $query->row_array();
        } else {
            $query = $this->db->where("is_active", "yes")->get("staff_designation");

            return $query->result_array();
        }
    }

    public function valid_designation()
    {
        $type = $this->input->post('type');
        $id   = $this->input->post('designationid');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_designation_exists($type, $id)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return false;
        } else {
            return true;
        }
    }

    public function check_designation_exists($name, $id)
    {
        if ($id != 0) {
            $data  = array('id != ' => $id, 'designation' => $name);
            $query = $this->db->where($data)->get('staff_designation');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {

            $this->db->where('designation', $name);
            $query = $this->db->get('staff_designation');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function deleteDesignation($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $id)->delete("staff_designation");
        $message   = DELETE_RECORD_CONSTANT . " On staff designation id " . $id;
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

    public function addDesignation($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data["id"])) {

            $this->db->where("id", $data["id"])->update("staff_designation", $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  staff designation id " . $data['id'];
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

            $this->db->insert("staff_designation", $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On  staff designation id " . $id;
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
    }

}
