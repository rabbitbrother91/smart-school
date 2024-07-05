<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Rolepermission_model extends CI_Model
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
    public function getPermissionByRole($role_id)
    {
        $this->db->select('`roles_permissions`.*, permission_category.id as permission_category_id,permission_category.name as permission_category_name,permission_category.short_code as permission_category_code');
        $this->db->from('roles_permissions');

        $this->db->join('permission_category', 'permission_category.id=roles_permissions.perm_cat_id');
        $this->db->where('roles_permissions.role_id', $role_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function getPermissionByRoleandCategory($role_id, $category)
    {
        $this->db->select('`roles_permissions`.*, permission_category.id as permission_category_id,permission_category.name as permission_category_name,permission_category.short_code as permission_category_code');
        $this->db->from('roles_permissions');
        $this->db->join('permission_category', 'permission_category.id=roles_permissions.perm_cat_id');
        $this->db->where('roles_permissions.role_id', $role_id);
        $this->db->where('permission_category.short_code', $category);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return false;
    }

    public function getInsertBatch($insert_array, $role_id, $delete_array)
    {

        $this->db->trans_start();
        $this->db->trans_strict(false);
        if (!empty($insert_array)) {

            $this->db->insert_batch('role_permissions', $insert_array);
        }

# Updating data
        if (!empty($delete_array)) {
            $this->db->where('role_id', $role_id);
            $this->db->where_in('permission_id', $delete_array);
            $this->db->delete('role_permissions');
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {

            $this->db->trans_rollback();
            return false;
        } else {

            $this->db->trans_commit();
            return true;
        }
    }

    public function getPermissionWithSelectedByRole($role_id)
    {
        $sql = "SELECT permissions.*, role_permissions.id as `role_permission_id`,IF(role_permissions.id IS NULL,0,1) AS role_permission_state FROM `permissions` LEFT JOIN role_permissions on permissions.id=role_permissions.permission_id and role_permissions.role_id =$role_id";

        $query = $this->db->query($sql);
        return $query->result();
    }

}
