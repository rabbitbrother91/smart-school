<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . "third_party/omnipay/vendor/autoload.php";

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QR_Code
{

    public function __construct()
    {
        $CI = &get_instance();
    }

    public function generate($upload_path,$text)
    {
        $data = $text;
        $options = new QROptions([
            'version' => 5,
            'eccLevel' => QRCode::ECC_H,
            'scale' => 2,
            'imageBase64' => false,
            'imageTransparent' => true,
            'foregroundColor' => '#000000',
            'backgroundColor' => '#ffffff',
            'qrCodeHeight'    => 10,
            'qrCodeWidth'     => 10,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG
        ]);

        // Instantiating the code QR code class
        $qrCode = new QRCode($options);

        // generating the QR code image happens here
        $qrCodeImage = $qrCode->render($data, $upload_path.$text.'.png');
      

    }
}
