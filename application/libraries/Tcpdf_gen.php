<?php

use TCPDF;

class Tcpdf_gen
{
    public function __construct()
    {
        // Correct path to autoload.php
        require_once FCPATH . 'vendor/autoload.php';

        try {
            // Create an instance of TCPDF
            $pdf = new TCPDF();

            // Set document information
            $pdf->SetCreator(PDF_CREATOR);
            // $pdf->SetAuthor('Your Name');
            // $pdf->SetTitle('Student Report');
            // $pdf->SetSubject('Student List');
            // $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

            // Set default header data
            // $pdf->SetHeaderData('', 0, 'Student Report', 'Generated using TCPDF', [0,0,0], [0,0,0]);
            // $pdf->setFooterData([0,0,0], [0,0,0]);

            // Set header and footer fonts
            $pdf->setHeaderFont(['dejavusans', '', 10]);
            $pdf->setFooterFont(['dejavusans', '', 8]);

            // Set default monospaced font
            // $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // Set margins
            // $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // Set auto page breaks
            // $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // Set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // Set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                require_once(dirname(__FILE__).'/lang/eng.php');
                $pdf->setLanguageArray($l);
            }

            // Set font
            $pdf->SetFont('dejavusans', '', 12);

            // Add a page
            $pdf->AddPage();

            // Get CodeIgniter instance
            $CI =& get_instance();

            // Assign the TCPDF instance to the CI object
            $CI->tcpdf = $pdf;
        } catch (Exception $e) {
            // Log the exception message
            log_message('error', 'TCPDF initialization failed: ' . $e->getMessage());
        }
    }
}
