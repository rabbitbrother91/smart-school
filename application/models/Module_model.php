<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Module_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getPermission()
    {
        $query = $this->db->where("system", 0)->get("permission_group");
        return $query->result_array();
    }

    public function getParentPermission()
    {
        $query = $this->db->where("system", 0)->get("permission_student");
        return $query->result_array();
    }

    public function getStudentPermission()
    {
        $query = $this->db->where("system", 0)->get("permission_student");
        return $query->result_array();
    }

    public function changeStatus($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $permission_parent = array('student' => $data['is_active'], 'parent' => $data['is_active']);
        $this->db->where("id", $data["id"])->update("permission_group", $data);
        $this->db->where("group_id", $data["id"])->update("permission_student", $permission_parent);
        $message   = UPDATE_RECORD_CONSTANT . " On permission group id " . $data['id'];
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
    }

    public function getPermissionByModulename($module_name)
    {
        $sql = "select is_active from permission_group where short_code='" . $module_name . "'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function get($id = null)
    {
        $this->db->select()->from('permission_group');
        if ($id != null) {
            $this->db->where('permission_group.id', $id);
        } else {
            $this->db->order_by('permission_group.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result();
        }
    }

    public function get_parent($id = null)
    {
        $this->db->select()->from('permission_parent');
        if ($id != null) {
            $this->db->where('permission_parent.id', $id);
        } else {
            $this->db->order_by('permission_parent.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result();
        }
    }

    public function get_student($id = null)
    {
        $this->db->select()->from('permission_student');
        if ($id != null) {
            $this->db->where('permission_student.id', $id);
        } else {
            $this->db->order_by('permission_student.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result();
        }
    }

    public function get_userpermission($role)
    {
        $this->db->select()->from('permission_student');      
        $this->db->order_by('permission_student.id');
        $query = $this->db->get();
        return $query->result();
    }

    public function changeParentStatus($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $data["id"])->update("permission_parent", $data);
        $message   = UPDATE_RECORD_CONSTANT . " On permission parent id " . $data['id'];
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
    }

    public function changeStudentStatus($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $data["id"])->update("permission_student", $data);
        $message   = UPDATE_RECORD_CONSTANT . " On  permission student id " . $data['id'];
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
    }

    public function hasModule($module_shortcode)
    {
        $query = $this->db->where('short_code', $module_shortcode)->get("permission_group");
        $count = $query->num_rows();
        return $count;
    }

}
