<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Smsconfig extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('sms_setting', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'smsconfig/index');
        $data['title']      = 'SMS Config List';
        $sms_result         = $this->smsconfig_model->get();
        $data['statuslist'] = $this->customlib->getStatus();
        $data['smslist']    = $sms_result;
        $this->load->view('layout/header', $data);
        $this->load->view('smsconfig/smsList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function clickatell()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('clickatell_user', $this->lang->line('username'), 'required');
        $this->form_validation->set_rules('clickatell_password', $this->lang->line('password'), 'required');
        $this->form_validation->set_rules('clickatell_api_id', $this->lang->line('api_id'), 'required');
        $this->form_validation->set_rules('clickatell_status', $this->lang->line('status'), 'required');
        if ($this->form_validation->run()) {

            $data = array(
                'type'      => 'clickatell',
                'username'  => $this->input->post('clickatell_user'),
                'password'  => $this->input->post('clickatell_password'),
                'api_id'    => $this->input->post('clickatell_api_id'),
                'is_active' => $this->input->post('clickatell_status'),
            );
            $this->smsconfig_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'clickatell_user'     => form_error('clickatell_user'),
                'clickatell_password' => form_error('clickatell_password'),
                'clickatell_api_id'   => form_error('clickatell_api_id'),
                'clickatell_status'   => form_error('clickatell_status'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function twilio()
    {

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('twilio_account_sid', $this->lang->line('twilio_account_sid'), 'required');
        $this->form_validation->set_rules('twilio_auth_token', $this->lang->line('authentication_token'), 'required');
        $this->form_validation->set_rules('twilio_sender_phone_number', $this->lang->line('registered_phone_number'), 'required');
        $this->form_validation->set_rules('twilio_status', $this->lang->line('status'), 'required');

        if ($this->form_validation->run()) {

            $data = array(
                'type'      => 'twilio',
                'api_id'    => $this->input->post('twilio_account_sid'),
                'password'  => $this->input->post('twilio_auth_token'),
                'contact'   => $this->input->post('twilio_sender_phone_number'),
                'is_active' => $this->input->post('twilio_status'),
            );
            $this->smsconfig_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'twilio_account_sid'         => form_error('twilio_account_sid'),
                'twilio_auth_token'          => form_error('twilio_auth_token'),
                'twilio_sender_phone_number' => form_error('twilio_sender_phone_number'),
                'twilio_status'              => form_error('twilio_status'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function custom()
    {
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('name', $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('custom_status', $this->lang->line('status'), 'required');

        if ($this->form_validation->run()) {

            $data = array(
                'type'      => 'custom',
                'name'      => $this->input->post('name'),
                'is_active' => $this->input->post('custom_status'),
            );
            $this->smsconfig_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'name'          => form_error('name'),
                'custom_status' => form_error('custom_status'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function msgnineone()
    {
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('authkey', $this->lang->line('auth_key'), 'required');
        $this->form_validation->set_rules('senderid', $this->lang->line('sender_id'), 'required');
        $this->form_validation->set_rules('msg_nineone_status', $this->lang->line('status'), 'required');

        if ($this->form_validation->run()) {

            $data = array(
                'type'      => 'msg_nineone',
                'authkey'   => $this->input->post('authkey'),
                'senderid'  => $this->input->post('senderid'),
                'is_active' => $this->input->post('msg_nineone_status'),
            );
            $this->smsconfig_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'authkey'            => form_error('authkey'),
                'senderid'           => form_error('senderid'),
                'msg_nineone_status' => form_error('msg_nineone_status'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function smscountry()
    {
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('smscountry', $this->lang->line('username'), 'required');
        $this->form_validation->set_rules('smscountrypassword', $this->lang->line('password'), 'required');
        $this->form_validation->set_rules('smscountrysenderid', $this->lang->line('sender_id'), 'required');
        $this->form_validation->set_rules('smscountry_status', $this->lang->line('status'), 'required');
        $this->form_validation->set_rules('smscountryauthKey', $this->lang->line('auth_Key'), 'required');
        $this->form_validation->set_rules('smscountryauthtoken', $this->lang->line('authentication_token'), 'required');

        if ($this->form_validation->run()) {

            $data = array(
                'type'      => 'smscountry',
                'username'  => $this->input->post('smscountry'),
                'password'  => $this->input->post('smscountrypassword'),
                'senderid'  => $this->input->post('smscountrysenderid'),
                'is_active' => $this->input->post('smscountry_status'),
                'authkey'   => $this->input->post('smscountryauthKey'),
                'api_id'    => $this->input->post('smscountryauthtoken'),
            );
            $this->smsconfig_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'smscountry'            => form_error('smscountry'),
                'smscountrypassword'    => form_error('smscountrypassword'),
                'smscountrysenderid'    => form_error('smscountrysenderid'),
                'smscountry_status'     => form_error('smscountry_status'),
                'smscountryauthKey'     => form_error('smscountryauthKey'),
                'smscountryauthtoken'   => form_error('smscountryauthtoken'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function textlocal()
    {
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('text_local', $this->lang->line('username'), 'required');
        $this->form_validation->set_rules('text_localpassword', $this->lang->line('password'), 'required');
        $this->form_validation->set_rules('text_localsenderid', $this->lang->line('sender_id'), 'required');
        $this->form_validation->set_rules('text_local_status', $this->lang->line('status'), 'required');

        if ($this->form_validation->run()) {

            $data = array(
                'type'      => 'text_local',
                'username'  => $this->input->post('text_local'),
                'password'  => $this->input->post('text_localpassword'),
                'senderid'  => $this->input->post('text_localsenderid'),
                'is_active' => $this->input->post('text_local_status'),
            );
            $this->smsconfig_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'text_local'         => form_error('text_local'),
                'text_localpassword' => form_error('text_localpassword'),
                'text_localsenderid' => form_error('text_localsenderid'),
                'text_local_status'  => form_error('text_local_status'),

            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function bulk_sms()
    {
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('bulk_sms_user_name', $this->lang->line('username'), 'required');
        $this->form_validation->set_rules('bulk_sms_user_password', $this->lang->line('password'), 'required');
        $this->form_validation->set_rules('bulk_sms_status', $this->lang->line('status'), 'required');

        if ($this->form_validation->run()) {

            $data = array(
                'type'      => 'bulk_sms',
                'username'  => $this->input->post('bulk_sms_user_name'),
                'password'  => $this->input->post('bulk_sms_user_password'),
                'is_active' => $this->input->post('bulk_sms_status'),
            );
            $this->smsconfig_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'bulk_sms_user_name'     => form_error('bulk_sms_user_name'),
                'bulk_sms_user_password' => form_error('bulk_sms_user_password'),
                'bulk_sms_status'        => form_error('bulk_sms_status'),

            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function mobireach()
    {
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('mobireach_auth_key', $this->lang->line('auth_Key'), 'required');
        $this->form_validation->set_rules('mobireach_sender_id', $this->lang->line('sender_id'), 'required');
        $this->form_validation->set_rules('mobireach_route_id', $this->lang->line('route_id'), 'required');
        $this->form_validation->set_rules('mobireach_status', $this->lang->line('status'), 'required');

        if ($this->form_validation->run()) {

            $data = array(
                'type'      => 'mobireach',
                'authkey'   => $this->input->post('mobireach_auth_key'),
                'senderid'  => $this->input->post('mobireach_sender_id'),
                'api_id'    => $this->input->post('mobireach_route_id'),
                'is_active' => $this->input->post('mobireach_status'),
            );
            $this->smsconfig_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {

            $data = array(
                'mobireach_auth_key'  => form_error('mobireach_auth_key'),
                'mobireach_sender_id' => form_error('mobireach_sender_id'),
                'mobireach_route_id'  => form_error('mobireach_route_id'),
                'mobireach_status'    => form_error('mobireach_status'),

            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

     public function nexmo()
     {      
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('nexmo_api_key', $this->lang->line('nexmo_api_key'), 'required');
        $this->form_validation->set_rules('nexmo_api_secret', $this->lang->line('nexmo_api_secret'), 'required');
        $this->form_validation->set_rules('nexmo_registered_phone_number', $this->lang->line('nexmo_registered_phone_number'), 'required');
        $this->form_validation->set_rules('nexmo_status', $this->lang->line('status'), 'required');

        if ($this->form_validation->run()) {

            $data = array(
                'type'      => 'nexmo',
                'authkey'   => $this->input->post('nexmo_api_secret'),
                'senderid'  => $this->input->post('nexmo_registered_phone_number'),
                'api_id'    => $this->input->post('nexmo_api_key'),
                'is_active' => $this->input->post('nexmo_status'),
            );
            $this->smsconfig_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else { 

            $data = array(
                'nexmo_api_secret'  => form_error('nexmo_api_secret'),
                'nexmo_registered_phone_number' => form_error('nexmo_registered_phone_number'),
                'nexmo_api_key'  => form_error('nexmo_api_key'),
                'nexmo_status'    => form_error('nexmo_status'),

            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
     }

      public function africastalking()
      {      
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('africastalking_username', $this->lang->line('africastalking_username'), 'required');
        $this->form_validation->set_rules('africastalking_apikey', $this->lang->line('africastalking_apikey'), 'required');
        $this->form_validation->set_rules('africastalking_status', $this->lang->line('status'), 'required');

        if ($this->form_validation->run()) {

            $data = array(
                'type'      => 'africastalking',
                'username'   => $this->input->post('africastalking_username'),
                'api_id'  => $this->input->post('africastalking_apikey'),
                'senderid'    => $this->input->post('africastalking_short_code'),
                'is_active' => $this->input->post('africastalking_status'),
            );
            $this->smsconfig_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {

            $data = array(
                'africastalking_username'  => form_error('africastalking_username'),
                'africastalking_apikey' => form_error('africastalking_apikey'),
                'africastalking_status'    => form_error('africastalking_status'),

            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
     }

    public function smseg()
    {      
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('smseg_username', $this->lang->line('username'), 'required');
        $this->form_validation->set_rules('smseg_password', $this->lang->line('password'), 'required');
        $this->form_validation->set_rules('smseg_sender_id', $this->lang->line('sender_id'), 'required');
        $this->form_validation->set_rules('smseg_type', $this->lang->line('type'), 'required');

        $this->form_validation->set_rules('smseg_status', $this->lang->line('status'), 'required');

        if ($this->form_validation->run()) {
            $url=$this->input->post('smseg_type');

            $data = array(
                'type'      => 'smseg',
                'username'   => $this->input->post('smseg_username'),
                'password'  => $this->input->post('smseg_password'),
                'senderid'    => $this->input->post('smseg_sender_id'),
                'is_active' => $this->input->post('smseg_status'),
                'url'=>$url,
            );
            $this->smsconfig_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));

        } else {

            $data = array(
                'smseg_username'  => form_error('smseg_username'),
                'smseg_password' => form_error('smseg_password'),
                'smseg_sender_id'    => form_error('smseg_sender_id'),
                'smseg_status'    => form_error('smseg_status'),
                'smseg_type'    => form_error('smseg_type'),

            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
     }

}