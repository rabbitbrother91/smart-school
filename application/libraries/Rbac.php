<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rbac
{

    private $userRoles = array();
    protected $permissions;
    public $perm_category;

    public function __construct()
    {

        $this->CI          = &get_instance();
        $this->permissions = array();
        $this->CI->config->load('mailsms');
        $this->perm_category = $this->CI->config->item('perm_category');
      
    }
 
    public function hasPrivilege($category = null, $permission = null)
    {    
        $roles            = $this->CI->customlib->getStaffRole();
        $logged_user_role = json_decode($roles)->name;

        if ($logged_user_role == 'Super Admin') {
            return true;
        }

        $admin = $this->CI->session->userdata('admin');

        $roles    = $admin['roles'];
        $role_key = key($roles);
        $role_id  = $roles[$role_key];

        $role_perm = $this->CI->rolepermission_model->getPermissionByRoleandCategory($role_id, trim($category));

        if ($role_perm) {
            if (array_key_exists($permission, $role_perm)) {
               return ($role_perm[$permission]);
            }

        }       

        return false;
    }

  
    public function module_permission($module_name)
    {
        $module_perm = $this->CI->Module_model->getPermissionByModulename($module_name);
        return $module_perm;
    }

    public function unautherized()
    {
        $this->CI->load->view('layout/header');
        $this->CI->load->view('unauthorized');
        $this->CI->load->view('layout/footer');
    }

}
