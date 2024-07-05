<?php

defined('BASEPATH') or exit('No direct script access allowed');

class StudentModule_lib
{

    private $allModules = array();
    protected $modules;
    public $perm_category;

    public function __construct()
    {
        $this->CI      = &get_instance();
        $this->modules = array();
        self::loadModule(); //Initiate the userroles
        $this->CI->load->library('session');
    }

    public function loadModule()
    {
        if ($this->CI->session->has_userdata('student')) {
            $this->student = $this->CI->session->userdata('student');

            $this->allModules = $this->CI->Module_model->get_userpermission($this->student['role']);
            $role_name        = $this->student['role'];
            if (!empty($this->allModules)) {
                foreach ($this->allModules as $mod_key => $mod_value) {

                    if (array_key_exists($role_name, (array)$mod_value)) {

                        if ($mod_value->{$role_name} == 1) {
                            $this->modules[$mod_value->short_code] = true;
                        } else {
                            $this->modules[$mod_value->short_code] = false;
                        }

                    }else{
                         $this->modules[$mod_value->short_code] = false;
                    }
                }
            }
        }
    }

    public function hasActive($module = null)
    {

        if ($this->modules[$module]) {
            return true;
        }

        return false;
    }

}
