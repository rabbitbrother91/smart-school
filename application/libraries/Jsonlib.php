<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jsonlib {

    var $CI;

    function __construct() {
        $this->CI = & get_instance();
    }

    public function output($status = 200, $array = array()) {
        return $this->CI->output
                        ->set_content_type('application/json')
                        ->set_status_header($status)
                        ->set_output(json_encode($array));
    }

}
