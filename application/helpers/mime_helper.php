<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('find_file_type')) {

    function find_file_type($_find)
    {
  
        $mime = get_mimes();
        foreach ($mime as $mime_key => $mime_value) {
            if (is_array($mime_value) && in_array($_find, $mime_value)) {
                return $mime_key;
            } elseif ($mime_value == $_find) {
                return $mime_key;
            }
        }
        return false;
    }

}
