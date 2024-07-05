<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Department_model extends MY_model
{

    public function valid_department($str)
    {
        $type = $this->input->post('type');
        $id   = $this->input->post('departmenttypeid');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_department_exists($type, $id)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return false;
        } else {
            return true;
        }
    }

    public function check_department_exists($name, $id)
    {
        if ($id != 0) {
            $data  = array('id != ' => $id, 'department_name' => $name);
            $query = $this->db->where($data)->get('department');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->where('department_name', $name);
            $query = $this->db->get('department');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function deleteDepartment($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $id)->delete("department");
        $message   = DELETE_RECORD_CONSTANT . " On department id " . $id;
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

    public function getDepartmentType($id = null)
    {
        if (!empty($id)) {
            $query = $this->db->where("id", $id)->get('department');
            return $query->row_array();
        } else {
            $query = $this->db->get("department");
            return $query->result_array();
        }
    }

    public function addDepartmentType($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('department', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On department id " . $data['id'];
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
            $this->db->insert('department', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On department id " . $id;
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
            return $id;
        }
    }

}
