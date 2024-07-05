<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcomes extends CI_Controller {

  public function __construct() {
      parent::__construct(); 
    // $this->load->database();
   
 
   }
 
    public function show()
    {
        print_r($this->db);
        $data['projects'] = $this->setting_model->get();
        $this->load->view('welcome_message');
    }
}
