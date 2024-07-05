<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class App extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('setting_model');
        $this->load->library('customlib');
    }

    public function index()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $setting_result = $this->setting_model->getSetting();

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'url'                      => $setting_result->mobile_api_url,
                    'site_url'                 => site_url(),
                    'app_logo'                 => $setting_result->app_logo,
                    'app_primary_color_code'   => $setting_result->app_primary_color_code,
                    'app_secondary_color_code' => $setting_result->app_secondary_color_code,
                    'lang_code'                => $setting_result->language_code,
                    'app_ver'                  => $this->customlib->getAppVersion(),
                    'languages'                => $setting_result->activelanguage2,
                )));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode(array(
                    'error' => "Method Not Allowed",
                )));
        }
    }

    public function zoom()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => "https://api.zoom.us/v2/users?status=active&page_size=30&page_number=1",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => array(
                "authorization: Bearer sY6xc8tAS7Wj8-MXyXxheg",
                "content-type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    public function admin()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $setting_result = $this->setting_model->getSetting();

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'url'                      => $setting_result->admin_mobile_api_url,
                    'site_url'                 => site_url(),
                    'app_logo'                 => $setting_result->app_logo,
                    'app_primary_color_code'   => $setting_result->admin_app_primary_color_code,
                    'app_secondary_color_code' => $setting_result->admin_app_secondary_color_code,
                    'lang_code'                => $setting_result->language_code,
                    'app_ver'                  => $this->customlib->getAppVersion(),
                    'languages'                => $setting_result->activelanguage2,
                )));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(405)
                ->set_output(json_encode(array(
                    'error' => "Method Not Allowed",
                )));
        }
    }

}
