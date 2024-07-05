<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . "third_party/omnipay/vendor/autoload.php";

use Mpdf\Mpdf;

class M_pdf
{

    public function __construct()
    {
        $CI = &get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }

    public function load($param = null)
    {
        return  new Mpdf([
            'tempDir' => __DIR__ . '/../tmp',
            'mode' => 'utf-8',
            'default_font' => 'roboto',
            'margin_left' => 2,
            'margin_right' => 2,
            'margin_top' => 2,
            'margin_bottom' => 2,
            'format' => 'Legal'
        ]);
    }
}
