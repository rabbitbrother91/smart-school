<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Langconvert {

    private $_CI;

    function __construct() {
        $this->_CI = & get_instance();
        $this->session_name = $this->_CI->setting_model->getCurrentSessionName();
    }

    protected $rootURL = 'https://translate.yandex.net/api/v1.5/tr.json';
    protected $translatePath = '/translate';
    public $eolSymbol = '<br />';
    protected $cURLHeaders = array(
        'User-Agent' => "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0; .NET CLR 2.0.50727)",
        'Accept' => "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
        'Accept-Language' => "ru,en-us;q=0.7,en;q=0.3",
        'Accept-Encoding' => "gzip,deflate",
        'Accept-Charset' => "windows-1251,utf-8;q=0.7,*;q=0.7",
        'Keep-Alive' => '300',
        'Connection' => 'keep-alive',
    );

    protected function yandexConnect($path, $transferData = array()) {
        $res = curl_init();
        $url = $this->rootURL . $path . '?' . http_build_query($transferData) . "&[callback=JSON]";

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $this->cURLHeaders,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 30,
        );

        curl_setopt($res, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($res, $options);
        $response = curl_exec($res);

        if (!curl_errno($res)) {
            $http_code = curl_getinfo($res, CURLINFO_HTTP_CODE);

            if ($http_code == 200) {
                return $response;
            }
        }

        curl_close($res);
    }

    public function yandexTranslate($fromLang, $toLang, $text) {

        $transferData = array(
            'key' => 'trnsl.1.1.20170328T154056Z.8b07168622735883.6cd68c2f1d1cf80bb3c55e2505e086af6b7674f6',
            // 'key'=>'trnsl.1.1.20191001T080822Z.114211cde45db473.64d076985b8a701da6f0ae6b6806bdc92f060dd1',
            'lang' => $fromLang . '-' . $toLang,
            'text' => $text,
        );

        $rawTranslate = $this->yandexConnect($this->translatePath, $transferData);

        $rawTranslate = trim($rawTranslate, '"');

        $translate = str_replace('\n', $this->eolSymbol, $rawTranslate);

        return $translate;
    }

}

?>