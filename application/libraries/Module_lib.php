<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Module_lib {

    private $allModules = array();
    protected $modules;
    var $perm_category;

    function __construct() {
        $this->CI = & get_instance();
        $this->modules = array();
        self::loadModule(); //Initiate the userroles
    }

    function loadModule() {
        $this->allModules = $this->CI->Module_model->get();

        if (!empty($this->allModules)) {
            foreach ($this->allModules as $mod_key => $mod_value) {

                if ($mod_value->is_active == 1) {
                    $this->modules[$mod_value->short_code] = true;
                } else {

                    $this->modules[$mod_value->short_code] = false;
                }
            }
        }
    }

    function hasActive($module = null) {

        if ($this->modules[$module]) {
            return true;
        }

        return false;
    }

    function hasModule($module_shortcode) {

        $count = $this->CI->Module_model->hasModule($module_shortcode);

        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

}
