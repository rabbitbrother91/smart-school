<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sidebarmenu_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null)
    {
        $this->db->select()->from('sidebar_menus');
        if ($id != null) {
            $this->db->where('sidebar_menus.id', $id);
        } else {
            $this->db->order_by('sidebar_menus.id');
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
        $this->db->delete('sidebar_menus');
        $message   = DELETE_RECORD_CONSTANT . " On Menu id " . $id;
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
        if (isset($data['id']) && $data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('sidebar_menus', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  Menu id " . $data['id'];
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
            $this->db->insert('sidebar_menus', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On Menu id " . $insert_id;
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

    public function addSubMenu($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id']) && $data['id'] != 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('sidebar_sub_menus', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  SubMenu id " . $data['id'];
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
            $this->db->insert('sidebar_sub_menus', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On SubMenu id " . $insert_id;
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

    public function getMenuwithSubmenus($sidebar_display = -1)
    {
        $this->db->select('sidebar_menus.*,permission_group.name as `permission_group_name`,permission_group.short_code as `short_code`')->from('sidebar_menus');
        $this->db->join('permission_group','permission_group.id=sidebar_menus.permission_group_id','LEFT');

        if ($sidebar_display > -1) {
            $this->db->where('sidebar_menus.sidebar_display', $sidebar_display);
        }

        if ($sidebar_display == 1) {
            $this->db->order_by('level', 'asc');
        } else {
            $this->db->order_by('menu', 'asc');
        }

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            foreach ($result as $result_key => $result_value) {
                $result[$result_key]->{'submenus'} = $this->getSubmenusByMenuId($result_value->id,$sidebar_display);

            }
            return $result;
        }
        return false;
    }

    public function getSubmenusByMenuId($menu_id,$sidebar_display= 0)
    {
        $this->db->select('sidebar_sub_menus.*,permission_group.name as `permission_group_name`,permission_group.short_code as `short_code`')->from('sidebar_sub_menus');
        $this->db->join('permission_group','permission_group.id=sidebar_sub_menus.permission_group_id','LEFT');
        $this->db->where('sidebar_menu_id', $menu_id);        
        $this->db->where('sidebar_sub_menus.is_active',1);        
        $this->db->order_by('level', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getSubmenuById($id)
    {
        $this->db->select('*')->from('sidebar_sub_menus');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function update_menu_order($data, $id_not_to_be_reset)
    {
        if (!empty($id_not_to_be_reset)) {
            $this->db->set('sidebar_display', 0, false);
            $this->db->where_not_in('id', $id_not_to_be_reset);
            $this->db->update('sidebar_menus');
        }
        
        if (!empty($data)) {
            $this->db->update_batch('sidebar_menus', $data, 'id');
        }
    }
    public function update_submenu_order($data)
    {
        $this->db->update_batch('sidebar_sub_menus', $data, 'id');
    }

    public function update_submenu_by_key($data)
    {
        if (!empty($data)) {
            $this->db->update_batch('sidebar_sub_menus', $data, 'key');
        }
       
    }

}