<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Version_control  // for deprecreted function 
{

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function higher_version_8()
    {
        return version_compare(PHP_VERSION, '8.0.0', '>');
    }

    public function below_version_8()
    {
        return version_compare(PHP_VERSION, '7.0.0', '>=')  && version_compare(PHP_VERSION, '8.0.0', '<');
    }

    public function addeesss()   {

        if ($this->below_version_8()) {
            echo 'I am at least PHP version 8.0.0, my version: ' . PHP_VERSION . "\n";
        } elseif ($this->higher_version_8()) {
            echo 'I am at least PHP version 8.0.0, my version: ' . PHP_VERSION . "\n";
        }

    }

   
}
