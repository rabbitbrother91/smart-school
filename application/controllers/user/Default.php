<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Defaulta extends Student_Controller {

   
    public function __construct()
    {
        parent::__construct();
        $this->load->library("customlib");
       
    }

   
}
?>