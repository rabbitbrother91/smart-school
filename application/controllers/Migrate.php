<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Migrate extends CI_Controller
{

    public function index()
    {
        $this->load->library('migration');

        if ($this->migration->current() === false) {
            show_error($this->migration->error_string());
        } else {
            echo "Database updated successfully.";
        }
    }

}
