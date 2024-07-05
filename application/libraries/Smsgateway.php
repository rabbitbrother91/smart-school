<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Smsgateway
{

    private $_CI;
    private $sch_setting;

    public function __construct()
    {
        $this->_CI = &get_instance();
        $this->_CI->load->model('setting_model');
        $this->_CI->load->model('student_model');
        $this->_CI->load->model('teacher_model');
        $this->_CI->load->model('studentfeemaster_model');
        $this->_CI->load->model('librarian_model');
        $this->_CI->load->model('accountant_model');
        $this->_CI->load->model('smsconfig_model');
        $this->sch_setting = $this->_CI->setting_model->get(); 
    }

    public function sentNotification($send_to, $detail, $subject, $template = '')
    {
        $this->_CI->load->library('pushnotification');
        $msg        = $this->getContent($detail, $template);
        $push_array = array(
            'title' => $subject,
            'body'  => $msg,
        );
        if ($send_to != "") {
            $this->_CI->pushnotification->send($send_to, $push_array, "mail_sms");
        }
    }

    public function sendSMS($send_to, $detail, $template_id, $template = '')
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();

        if ($template != "") {
            $msg = $this->getContent($detail, $template, $sms_detail->type);
        } else {
            $msg = $detail;
        }

        if (!empty($sms_detail)) {
            if ($sms_detail->type == 'clickatell') {

                $params = array(
                    'apiToken' => $sms_detail->api_id,
                );
                $this->_CI->load->library('clickatell', $params);
                try {
                    $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                    foreach ($result['messages'] as $message) {

                    }
                    return true;
                } catch (Exception $e) {
                    return true;
                }
            } else if ($sms_detail->type == 'twilio') {

                $params = array(
                    'mode'        => 'sandbox',
                    'account_sid' => $sms_detail->api_id,
                    'auth_token'  => $sms_detail->password,
                    'api_version' => '2010-04-01',
                    'number'      => $sms_detail->contact,
                );

                $this->_CI->load->library('twilio', $params);
                $from     = $sms_detail->contact;
                $to       = $send_to;
                $message  = $msg;
                $response = $this->_CI->twilio->sms($from, $to, $message);
                if ($response->IsError) {
                    return true;
                } else {
                    return true;
                }
            } else if ($sms_detail->type == 'msg_nineone') {
                $params = array(
                    'authkey'    => $sms_detail->authkey,
                    'senderid'   => $sms_detail->senderid,
                    'templateid' => $template_id,
                );
                $this->_CI->load->library('msgnineone', $params);
                $this->_CI->msgnineone->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'smscountry') {
                $params = array(
                    'username'  => $sms_detail->username,
                    'sernderid' => $sms_detail->senderid,
                    'password'  => $sms_detail->password,
                    'authkey'   => $sms_detail->authkey,
                    'api_id'    => $sms_detail->api_id,
                    
                );
                $this->_CI->load->library('smscountry', $params);
                $this->_CI->smscountry->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'text_local') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'hash'     => $sms_detail->password,
                );
                $this->_CI->load->library('textlocalsms', $params);
                $this->_CI->textlocalsms->sendSms(array($to), $msg, $sms_detail->senderid);
            } else if ($sms_detail->type == 'bulk_sms') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'password' => $sms_detail->password,
                );
                $this->_CI->load->library('bulk_sms_lib', $params);
                $this->_CI->bulk_sms_lib->sendSms(array($to), $msg);
            } else if ($sms_detail->type == 'mobireach') {
                $to     = $send_to;
                $params = array(
                    'authkey'  => $sms_detail->authkey,
                    'senderid' => $sms_detail->senderid,
                    'routeid'  => $sms_detail->api_id,
                );
                $this->_CI->load->library('mobireach_lib', $params);
                $this->_CI->mobireach_lib->sendSms(array($to), $msg);

            } else if ($sms_detail->type == 'nexmo') {
                $to     = $send_to;
                $params = array(
                    'from'       => $sms_detail->senderid,
                    'api_key'    => $sms_detail->api_id,
                    'api_secret' => $sms_detail->authkey,

                );
                $this->_CI->load->library('nexmo_lib', $params);
                $this->_CI->nexmo_lib->sendSms($to, $msg);
            } else if ($sms_detail->type == 'africastalking') {
                $to     = $send_to;
                $params = array(
                    'from'         => $sms_detail->senderid,
                    'api_key'      => $sms_detail->api_id,
                    'api_username' => $sms_detail->username,
                );
                $this->_CI->load->library('africastalking_lib', $params);
                $this->_CI->africastalking_lib->sendSms($to, $msg);
            } else if ($sms_detail->type == 'smseg') {
                $to = $send_to;
                $this->_CI->load->library('smseg_lib');
                $this->_CI->smseg_lib->sendSms($to, $msg);
            } else if ($sms_detail->type == 'custom') {
                $params = array(
                    'templateid' => $template_id,
                );
                $this->_CI->load->library('customsms', $params);
                $from    = $sms_detail->contact;
                $to      = $send_to;
                $message = $msg;
                $this->_CI->customsms->sendSMS($to, $message);
            } else {

            }
        }
        return true;
    }

    public function sentRegisterSMS($id, $send_to, $template, $template_id)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        $msg = $this->getStudentRegistrationContent($id, $template, $sms_detail->type);
        if (!empty($sms_detail)) {
            if ($sms_detail->type == 'clickatell') {
                $params = array(
                    'apiToken' => $sms_detail->api_id,
                );
                $this->_CI->load->library('clickatell', $params);
                try {
                    $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                    foreach ($result['messages'] as $message) {

                    }
                    return true;
                } catch (Exception $e) {
                    return true;
                }
            } else if ($sms_detail->type == 'twilio') {

                $params = array(
                    'mode'        => 'sandbox',
                    'account_sid' => $sms_detail->api_id,
                    'auth_token'  => $sms_detail->password,
                    'api_version' => '2010-04-01',
                    'number'      => $sms_detail->contact,
                );

                $this->_CI->load->library('twilio', $params);
                $from     = $sms_detail->contact;
                $to       = $send_to;
                $message  = $msg;
                $response = $this->_CI->twilio->sms($from, $to, $message);
                if ($response->IsError) {
                    return true;
                } else {
                    return true;
                }
            } else if ($sms_detail->type == 'msg_nineone') {
                $params = array(
                    'authkey'    => $sms_detail->authkey,
                    'senderid'   => $sms_detail->senderid,
                    'templateid' => $template_id,
                );
                $this->_CI->load->library('msgnineone', $params);
                $this->_CI->msgnineone->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'smscountry') {
                $params = array(
                    'username'  => $sms_detail->username,
                    'sernderid' => $sms_detail->senderid,
                    'password'  => $sms_detail->password,
                    'authkey'   => $sms_detail->authkey,
                    'api_id'    => $sms_detail->api_id,
                );
                $this->_CI->load->library('smscountry', $params);
                $this->_CI->smscountry->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'text_local') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'hash'     => $sms_detail->password,
                );
                $this->_CI->load->library('textlocalsms', $params);
                $this->_CI->textlocalsms->sendSms(array($to), $msg, $sms_detail->senderid);
            } else if ($sms_detail->type == 'bulk_sms') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'password' => $sms_detail->password,
                );
                $this->_CI->load->library('bulk_sms_lib', $params);
                $this->_CI->bulk_sms_lib->sendSms(array($to), $msg);
            } else if ($sms_detail->type == 'mobireach') {
                $to     = $send_to;
                $params = array(
                    'authkey'  => $sms_detail->authkey,
                    'senderid' => $sms_detail->senderid,
                    'routeid'  => $sms_detail->api_id,
                );
                $this->_CI->load->library('mobireach_lib', $params);
                $this->_CI->mobireach_lib->sendSms(array($to), $msg);
            } else if ($sms_detail->type == 'nexmo') {
                $to     = $send_to;
                $params = array(
                    'from'       => $sms_detail->senderid,
                    'api_key'    => $sms_detail->api_id,
                    'api_secret' => $sms_detail->authkey,
                );
                $this->_CI->load->library('nexmo_lib', $params);
                $this->_CI->nexmo_lib->sendSms($to, $msg);
            } else if ($sms_detail->type == 'africastalking') {
                $to     = $send_to;
                $params = array(
                    'from'         => $sms_detail->senderid,
                    'api_key'      => $sms_detail->api_id,
                    'api_username' => $sms_detail->username,
                );
                $this->_CI->load->library('africastalking_lib', $params);
                $this->_CI->africastalking_lib->sendSms($to, $msg);
            } else if ($sms_detail->type == 'smseg') {
                $to = $send_to;
                $this->_CI->load->library('smseg_lib');
                $this->_CI->smseg_lib->sendSms($to, $msg);
            } else if ($sms_detail->type == 'custom') {
                $params = array(
                    'templateid' => $template_id,
                );
                $this->_CI->load->library('customsms', $params);
                $from    = $sms_detail->contact;
                $to      = $send_to;
                $message = $msg;
                $this->_CI->customsms->sendSMS($to, $message);
            } else {

            }
        }
        return true;
    }

    public function sentFeeProcessingSMS($detail, $template, $template_id, $send_to)
    {

        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();

        if (!empty($sms_detail)) {
            $msg = $this->getFeeProcessingContent($detail, $template, $sms_detail->type);

            if ($sms_detail->type == 'clickatell') {
                $params = array(
                    'apiToken' => $sms_detail->api_id,
                );
                $this->_CI->load->library('clickatell', $params);
                try {
                    $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                    foreach ($result['messages'] as $message) {

                    }
                    return true;
                } catch (Exception $e) {
                    return false;
                }
            } else if ($sms_detail->type == 'twilio') {

                $params = array(
                    'mode'        => 'sandbox',
                    'account_sid' => $sms_detail->api_id,
                    'auth_token'  => $sms_detail->password,
                    'api_version' => '2010-04-01',
                    'number'      => $sms_detail->contact,
                );

                $this->_CI->load->library('twilio', $params);

                $from     = $sms_detail->contact;
                $to       = $send_to;
                $message  = $msg;
                $response = $this->_CI->twilio->sms($from, $to, $message);

                if ($response->IsError) {
                    return false;
                } else {
                    return true;
                }
            } else if ($sms_detail->type == 'msg_nineone') {
                $params = array(
                    'authkey'    => $sms_detail->authkey,
                    'senderid'   => $sms_detail->senderid,
                    'templateid' => $template_id,
                );
                $this->_CI->load->library('msgnineone', $params);
                $this->_CI->msgnineone->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'smscountry') {
                $params = array(
                    'username'  => $sms_detail->username,
                    'sernderid' => $sms_detail->senderid,
                    'password'  => $sms_detail->password,
                    'authkey'   => $sms_detail->authkey,
                    'api_id'    => $sms_detail->api_id,
                );
                $this->_CI->load->library('smscountry', $params);
                $this->_CI->smscountry->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'text_local') {
                $params = array(
                    'username' => $sms_detail->username,
                    'hash'     => $sms_detail->password,
                );
                $this->_CI->load->library('textlocalsms', $params);
                $this->_CI->textlocalsms->sendSms(array($send_to), $msg, $sms_detail->senderid);
            } else if ($sms_detail->type == 'bulk_sms') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'password' => $sms_detail->password,
                );
                $this->_CI->load->library('bulk_sms_lib', $params);
                $this->_CI->bulk_sms_lib->sendSms(array($to), $msg);
            } else if ($sms_detail->type == 'mobireach') {
                $to     = $send_to;
                $params = array(
                    'authkey'  => $sms_detail->authkey,
                    'senderid' => $sms_detail->senderid,
                    'routeid'  => $sms_detail->api_id,
                );
                $this->_CI->load->library('mobireach_lib', $params);
                $this->_CI->mobireach_lib->sendSms(array($to), $msg);

            } else if ($sms_detail->type == 'nexmo') {
                $to     = $send_to;
                $params = array(
                    'from'       => $sms_detail->senderid,
                    'api_key'    => $sms_detail->api_id,
                    'api_secret' => $sms_detail->authkey,
                );
                $this->_CI->load->library('nexmo_lib', $params);
                $this->_CI->nexmo_lib->sendSms($to, $msg);

            } else if ($sms_detail->type == 'africastalking') {
                $to     = $send_to;
                $params = array(
                    'from'         => $sms_detail->senderid,
                    'api_key'      => $sms_detail->api_id,
                    'api_username' => $sms_detail->username,
                );
                $this->_CI->load->library('africastalking_lib', $params);
                $this->_CI->africastalking_lib->sendSms($to, $msg);

            } else if ($sms_detail->type == 'smseg') {
                $to = $send_to;
                $this->_CI->load->library('smseg_lib');
                $this->_CI->smseg_lib->sendSms($to, $msg);

            } else if ($sms_detail->type == 'custom') {
                $params = array(
                    'templateid' => $template_id,
                );
                $this->_CI->load->library('customsms', $params);
                $from    = $sms_detail->contact;
                $to      = $send_to;
                $message = $msg;
                $this->_CI->customsms->sendSMS($to, $message);
            } else {

            }
        }
    }

    public function sentAddFeeSMS($detail, $template, $template_id, $send_to)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS(); 
        
        if (!empty($sms_detail)) {
            if (array_key_exists("send_type",$detail) && is_array($detail))
                {
                  $msg = $this->getGroupAddFeeContent($detail, $template, $sms_detail->type);
                }else{
                    $copy = clone $detail;
                  $msg = $this->getAddFeeContent($copy, $template, $sms_detail->type);
                }
 
            if ($sms_detail->type == 'clickatell') {
                $params = array(
                    'apiToken' => $sms_detail->api_id,
                );
                $this->_CI->load->library('clickatell', $params);
                try {
                    $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                    foreach ($result['messages'] as $message) {

                    }
                    return true;
                } catch (Exception $e) {
                    return false;
                }
            } else if ($sms_detail->type == 'twilio') {

                $params = array(
                    'mode'        => 'sandbox',
                    'account_sid' => $sms_detail->api_id,
                    'auth_token'  => $sms_detail->password,
                    'api_version' => '2010-04-01',
                    'number'      => $sms_detail->contact,
                );

                $this->_CI->load->library('twilio', $params);

                $from     = $sms_detail->contact;
                $to       = $send_to;
                $message  = $msg;
                $response = $this->_CI->twilio->sms($from, $to, $message);

                if ($response->IsError) {
                    return false;
                } else {
                    return true;
                }
            } else if ($sms_detail->type == 'msg_nineone') {
                $params = array(
                    'authkey'    => $sms_detail->authkey,
                    'senderid'   => $sms_detail->senderid,
                    'templateid' => $template_id,
                );
                $this->_CI->load->library('msgnineone', $params);
                $this->_CI->msgnineone->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'smscountry') {
                $params = array(
                    'username'  => $sms_detail->username,
                    'sernderid' => $sms_detail->senderid,
                    'password'  => $sms_detail->password,
                    'authkey'   => $sms_detail->authkey,
                    'api_id'    => $sms_detail->api_id,
                );
                $this->_CI->load->library('smscountry', $params);
                $this->_CI->smscountry->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'text_local') {
                $params = array(
                    'username' => $sms_detail->username,
                    'hash'     => $sms_detail->password,
                );
                $this->_CI->load->library('textlocalsms', $params);
                $this->_CI->textlocalsms->sendSms(array($send_to), $msg, $sms_detail->senderid);
            } else if ($sms_detail->type == 'bulk_sms') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'password' => $sms_detail->password,
                );
                $this->_CI->load->library('bulk_sms_lib', $params);
                $this->_CI->bulk_sms_lib->sendSms(array($to), $msg);
            } else if ($sms_detail->type == 'mobireach') {
                $to     = $send_to;
                $params = array(
                    'authkey'  => $sms_detail->authkey,
                    'senderid' => $sms_detail->senderid,
                    'routeid'  => $sms_detail->api_id,

                );
                $this->_CI->load->library('mobireach_lib', $params);
                $this->_CI->mobireach_lib->sendSms(array($to), $msg);

            } else if ($sms_detail->type == 'nexmo') {
                $to     = $send_to;
                $params = array(
                    'from'       => $sms_detail->senderid,
                    'api_key'    => $sms_detail->api_id,
                    'api_secret' => $sms_detail->authkey,

                );
                $this->_CI->load->library('nexmo_lib', $params);
                $this->_CI->nexmo_lib->sendSms($to, $msg);

            } else if ($sms_detail->type == 'africastalking') {
                $to     = $send_to;
                $params = array(
                    'from'         => $sms_detail->senderid,
                    'api_key'      => $sms_detail->api_id,
                    'api_username' => $sms_detail->username,

                );
                $this->_CI->load->library('africastalking_lib', $params);
                $this->_CI->africastalking_lib->sendSms($to, $msg);
            } else if ($sms_detail->type == 'smseg') {
                $to = $send_to;
                $this->_CI->load->library('smseg_lib');
                $this->_CI->smseg_lib->sendSms($to, $msg);

            } else if ($sms_detail->type == 'custom') {
                $params = array(
                    'templateid' => $template_id,
                );
                $this->_CI->load->library('customsms', $params);
                $from    = $sms_detail->contact;
                $to      = $send_to;
                $message = $msg;
                $this->_CI->customsms->sendSMS($to, $message);
            } else {

            }
        }
    }

    public function sentAbsentStudentSMS($detail, $template, $template_id, $send_to)
    {

        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();

        if (!empty($sms_detail)) {

            $msg = $this->getAbsentStudentContent($detail, $template, $sms_detail->type);            
            if ($sms_detail->type == 'clickatell') {
                $params = array(
                    'apiToken' => $sms_detail->api_id,
                );
                $this->_CI->load->library('clickatell', $params);
                try {
                    $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                    foreach ($result['messages'] as $message) {

                    }
                    return true;
                } catch (Exception $e) {
                    return false;
                }
            } else if ($sms_detail->type == 'twilio') {

                $params = array(
                    'mode'        => 'sandbox',
                    'account_sid' => $sms_detail->api_id,
                    'auth_token'  => $sms_detail->password,
                    'api_version' => '2010-04-01',
                    'number'      => $sms_detail->contact,
                );

                $this->_CI->load->library('twilio', $params);

                $from     = $sms_detail->contact;
                $to       = $send_to;
                $message  = $msg;
                $response = $this->_CI->twilio->sms($from, $to, $message);

                if ($response->IsError) {
                    return false;
                } else {
                    return true;
                }
            } else if ($sms_detail->type == 'msg_nineone') {
                $params = array(
                    'authkey'    => $sms_detail->authkey,
                    'senderid'   => $sms_detail->senderid,
                    'templateid' => $template_id,
                );
                $this->_CI->load->library('msgnineone', $params);
                $this->_CI->msgnineone->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'smscountry') {
                $params = array(
                    'username'  => $sms_detail->username,
                    'sernderid' => $sms_detail->senderid,
                    'password'  => $sms_detail->password,
                    'authkey'   => $sms_detail->authkey,
                    'api_id'    => $sms_detail->api_id,
                );
                $this->_CI->load->library('smscountry', $params);
                $this->_CI->smscountry->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'text_local') {
                $params = array(
                    'username' => $sms_detail->username,
                    'hash'     => $sms_detail->password,
                );
                $this->_CI->load->library('textlocalsms', $params);
                $this->_CI->textlocalsms->sendSms(array($send_to), $msg, $sms_detail->senderid);
            } else if ($sms_detail->type == 'bulk_sms') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'password' => $sms_detail->password,
                );
                $this->_CI->load->library('bulk_sms_lib', $params);
                $this->_CI->bulk_sms_lib->sendSms(array($to), $msg);
            } else if ($sms_detail->type == 'mobireach') {
                $to     = $send_to;
                $params = array(
                    'authkey'  => $sms_detail->authkey,
                    'senderid' => $sms_detail->senderid,
                    'routeid'  => $sms_detail->api_id,
                );
                $this->_CI->load->library('mobireach_lib', $params);
                $this->_CI->mobireach_lib->sendSms(array($to), $msg);

            } else if ($sms_detail->type == 'nexmo') {
                $to     = $send_to;
                $params = array(
                    'from'       => $sms_detail->senderid,
                    'api_key'    => $sms_detail->api_id,
                    'api_secret' => $sms_detail->authkey,

                );
                $this->_CI->load->library('nexmo_lib', $params);
                $this->_CI->nexmo_lib->sendSms($to, $msg);

            } else if ($sms_detail->type == 'africastalking') {
                $to     = $send_to;
                $params = array(
                    'from'         => $sms_detail->senderid,
                    'api_key'      => $sms_detail->api_id,
                    'api_username' => $sms_detail->username,

                );
                $this->_CI->load->library('africastalking_lib', $params);
                $this->_CI->africastalking_lib->sendSms($to, $msg);

            } else if ($sms_detail->type == 'smseg') {
                $to = $send_to;
                $this->_CI->load->library('smseg_lib');
                $this->_CI->smseg_lib->sendSms($to, $msg);

            } else if ($sms_detail->type == 'custom') {
                $params = array(
                    'templateid' => $template_id,

                );
                $this->_CI->load->library('customsms', $params);
                $from    = $sms_detail->contact;
                $to      = $send_to;
                $message = $msg;
                $this->_CI->customsms->sendSMS($to, $message);
            } else {

            }
        }
    }

    public function sentAbsentStudentNotification($detail, $template, $subject)
    {
        $msg        = $this->getAbsentStudentContent($detail, $template);
        $push_array = array(
            'title' => $subject,
            'body'  => $msg,
        );
        if ($detail['app_key'] != "") {
            $this->_CI->pushnotification->send($detail['app_key'], $push_array, "mail_sms");
        }
    }

    public function sentExamResultNotification($detail, $template, $subject)
    {
        $msg        = $this->getStudentResultContent($detail, $template);
        $subject    = $this->getmailsubject($detail['id'], $subject);
        $push_array = array(
            'title' => $subject,
            'body'  => $msg,
        );
        if ($detail['app_key'] != "") {
            $this->_CI->pushnotification->send($detail['app_key'], $push_array, "mail_sms");
        }
        if ($detail['parent_app_key'] != "") {
            $this->_CI->pushnotification->send($detail['parent_app_key'], $push_array, "mail_sms");
        }
    }

    public function sentExamResultSMS($detail, $template, $template_id)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        $msg        = $this->getStudentResultContent($detail, $template, $sms_detail->type);
        foreach ($detail['contact_numbers'] as $key => $contact_numbersvalue) {
            $send_to = $contact_numbersvalue;
            if (!empty($sms_detail)) {
                if ($sms_detail->type == 'clickatell') {
                    $params = array(
                        'apiToken' => $sms_detail->api_id,
                    );
                    $this->_CI->load->library('clickatell', $params);
                    try {
                        $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                        foreach ($result['messages'] as $message) {

                        }
                        return true;
                    } catch (Exception $e) {
                        return true;
                    }
                } else if ($sms_detail->type == 'twilio') {
                    $params = array(
                        'mode'        => 'sandbox',
                        'account_sid' => $sms_detail->api_id,
                        'auth_token'  => $sms_detail->password,
                        'api_version' => '2010-04-01',
                        'number'      => $sms_detail->contact,
                    );

                    $this->_CI->load->library('twilio', $params);

                    $from     = $sms_detail->contact;
                    $to       = $send_to;
                    $message  = $msg;
                    $response = $this->_CI->twilio->sms($from, $to, $message);

                    if ($response->IsError) {
                        return true;
                    } else {
                        return true;
                    }
                } else if ($sms_detail->type == 'msg_nineone') {
                    $params = array(
                        'authkey'    => $sms_detail->authkey,
                        'senderid'   => $sms_detail->senderid,
                        'templateid' => $template_id,
                    );
                    $this->_CI->load->library('msgnineone', $params);
                    $this->_CI->msgnineone->sendSMS($send_to, $msg);
                } else if ($sms_detail->type == 'smscountry') {
                    $params = array(
                        'username'  => $sms_detail->username,
                        'sernderid' => $sms_detail->senderid,
                        'password'  => $sms_detail->password,
                        'authkey'   => $sms_detail->authkey,
                        'api_id'    => $sms_detail->api_id,
                    );
                    $this->_CI->load->library('smscountry', $params);
                    $this->_CI->smscountry->sendSMS($send_to, $msg);
                } else if ($sms_detail->type == 'text_local') {
                    $to     = $send_to;
                    $params = array(
                        'username' => $sms_detail->username,
                        'hash'     => $sms_detail->password,
                    );
                    $this->_CI->load->library('textlocalsms', $params);
                    $this->_CI->textlocalsms->sendSms(array($to), $msg, $sms_detail->senderid);
                } else if ($sms_detail->type == 'bulk_sms') {
                    $to     = $send_to;
                    $params = array(
                        'username' => $sms_detail->username,
                        'password' => $sms_detail->password,
                    );
                    $this->_CI->load->library('bulk_sms_lib', $params);
                    $this->_CI->bulk_sms_lib->sendSms(array($to), $msg);
                } else if ($sms_detail->type == 'mobireach') {
                    $to     = $send_to;
                    $params = array(
                        'authkey'  => $sms_detail->authkey,
                        'senderid' => $sms_detail->senderid,
                        'routeid'  => $sms_detail->api_id,

                    );
                    $this->_CI->load->library('mobireach_lib', $params);
                    $this->_CI->mobireach_lib->sendSms(array($to), $msg);

                } else if ($sms_detail->type == 'nexmo') {
                    $to     = $send_to;
                    $params = array(
                        'from'       => $sms_detail->senderid,
                        'api_key'    => $sms_detail->api_id,
                        'api_secret' => $sms_detail->authkey,

                    );
                    $this->_CI->load->library('nexmo_lib', $params);
                    $this->_CI->nexmo_lib->sendSms($to, $msg);

                } else if ($sms_detail->type == 'africastalking') {
                    $to     = $send_to;
                    $params = array(
                        'from'         => $sms_detail->senderid,
                        'api_key'      => $sms_detail->api_id,
                        'api_username' => $sms_detail->username,

                    );
                    $this->_CI->load->library('africastalking_lib', $params);
                    $this->_CI->africastalking_lib->sendSms($to, $msg);
                } else if ($sms_detail->type == 'smseg') {
                    $to = $send_to;
                    $this->_CI->load->library('smseg_lib');
                    $this->_CI->smseg_lib->sendSms($to, $msg);
                } else if ($sms_detail->type == 'custom') {
                    $params = array(
                        'templateid' => $template_id,
                    );
                    $this->_CI->load->library('customsms', $params);
                    $from    = $sms_detail->contact;
                    $to      = $send_to;
                    $message = $msg;
                    $this->_CI->customsms->sendSMS($to, $message);
                } else {

                }
            }
        }

        return true;
    }

    public function sendLoginCredential($chk_mail_sms, $sender_details, $template, $template_id)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        $msg        = $this->getLoginCredentialContent($sender_details['credential_for'], $sender_details, $template, $sms_detail->type);
        $send_to = $sender_details['contact_no'];
        if (!empty($sms_detail)) {
            if ($sms_detail->type == 'clickatell') {

                $params = array(
                    'apiToken' => $sms_detail->api_id,
                );
                $this->_CI->load->library('clickatell', $params);
                try {
                    $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                    foreach ($result['messages'] as $message) {

                    }
                    return true;
                } catch (Exception $e) {
                    return true;
                }
            } else if ($sms_detail->type == 'twilio') {

                $params = array(
                    'mode'        => 'sandbox',
                    'account_sid' => $sms_detail->api_id,
                    'auth_token'  => $sms_detail->password,
                    'api_version' => '2010-04-01',
                    'number'      => $sms_detail->contact,
                );

                $this->_CI->load->library('twilio', $params);
                $from     = $sms_detail->contact;
                $to       = $send_to;
                $message  = $msg;
                $response = $this->_CI->twilio->sms($from, $to, $message);

                if ($response->IsError) {
                    return true;
                } else {
                    return true;
                }
            } else if ($sms_detail->type == 'msg_nineone') {
                $params = array(
                    'authkey'    => $sms_detail->authkey,
                    'senderid'   => $sms_detail->senderid,
                    'templateid' => $template_id,
                );

                $this->_CI->load->library('msgnineone', $params);
                $this->_CI->msgnineone->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'smscountry') {
                $params = array(
                    'username'  => $sms_detail->username,
                    'sernderid' => $sms_detail->senderid,
                    'password'  => $sms_detail->password,
                    'authkey'   => $sms_detail->authkey,
                    'api_id'    => $sms_detail->api_id,
                );
                $this->_CI->load->library('smscountry', $params);
                $this->_CI->smscountry->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'text_local') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'hash'     => $sms_detail->password,
                );
                $this->_CI->load->library('textlocalsms', $params);
                $this->_CI->textlocalsms->sendSms(array($to), $msg, $sms_detail->senderid);
            } else if ($sms_detail->type == 'bulk_sms') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'password' => $sms_detail->password,
                );
                $this->_CI->load->library('bulk_sms_lib', $params);
                $this->_CI->bulk_sms_lib->sendSms(array($to), $msg);
            } else if ($sms_detail->type == 'mobireach') {
                $to     = $send_to;
                $params = array(
                    'authkey'  => $sms_detail->authkey,
                    'senderid' => $sms_detail->senderid,
                    'routeid'  => $sms_detail->api_id,

                );
                $this->_CI->load->library('mobireach_lib', $params);
                $this->_CI->mobireach_lib->sendSms(array($to), $msg);

            } else if ($sms_detail->type == 'nexmo') {
                $to     = $send_to;
                $params = array(
                    'from'       => $sms_detail->senderid,
                    'api_key'    => $sms_detail->api_id,
                    'api_secret' => $sms_detail->authkey,

                );
                $this->_CI->load->library('nexmo_lib', $params);
                $this->_CI->nexmo_lib->sendSms($to, $msg);

            } else if ($sms_detail->type == 'africastalking') {
                $to     = $send_to;
                $params = array(
                    'from'         => $sms_detail->senderid,
                    'api_key'      => $sms_detail->api_id,
                    'api_username' => $sms_detail->username,

                );
                $this->_CI->load->library('africastalking_lib', $params);
                $this->_CI->africastalking_lib->sendSms($to, $msg);
            } else if ($sms_detail->type == 'smseg') {
                $to = $send_to;
                $this->_CI->load->library('smseg_lib');
                $this->_CI->smseg_lib->sendSms($to, $msg);
            } else if ($sms_detail->type == 'custom') {
                $param = array('template_id' => $template_id);
                $this->_CI->load->library('customsms', $param);
                $from    = $sms_detail->contact;
                $to      = $send_to;
                $message = $msg;
                $this->_CI->customsms->sendSMS($to, $message);
            } else {

            }
        }
        return true;
    }

    public function sentHomeworkStudentNotification($detail, $template, $subject)
    {
        foreach ($detail as $student_key => $student_value) {
            $msg        = $this->getHomeworkStudentContent($detail[$student_key], $template);
            $push_array = array(
                'title' => $subject,
                'body'  => $msg,
            );
            if ($student_value['app_key'] != "") {
                $this->_CI->pushnotification->send($student_value['app_key'], $push_array, "mail_sms");
            }
        }
    }
  
    public function sentOnlineexamStudentNotification($detail, $template, $subject)
    {
        foreach ($detail as $student_key => $student_value) {
            $msg        = $this->getOnlineexamStudentContent($detail[$student_key], $template);
            $push_array = array(
                'title' => $subject,
                'body'  => $msg,
            );

            if ($student_value['app_key'] != "") {
                $this->_CI->pushnotification->send($student_value['app_key'], $push_array, "mail_sms");
            }
        }
    }

    public function sentOnlineClassStudentNotification($detail, $template)
    {
        foreach ($detail as $student_key => $student_value) {
            $msg = $this->getOnlineClassStudentContent($detail[$student_key], $template);

            $push_array = array(
                'title' => 'Online Class',
                'body'  => $msg,
            );

            if ($student_value['app_key'] != "") {
                $this->_CI->pushnotification->send($student_value['app_key'], $push_array, "mail_sms");
            }
        }
    }

    public function sentAddFeeNotification($detail, $template, $subject)
    {
        if (is_array($detail) && array_key_exists("send_type",$detail)  )
        {
            $app_key=$detail['app_key'];
            $msg = $this->getGroupAddFeeContent($detail, $template);            
        }else{
            $app_key=$detail->app_key;
            $msg = $this->getAddFeeContent($detail, $template);           
        }

        $push_array = array(
            'title' => $subject,
            'body'  => $msg,
        );
       
        if ($app_key != "") {
            $this->_CI->pushnotification->send($app_key, $push_array, "mail_sms");
        }
    }

    public function sentFeeProcessingNotification($detail, $template, $subject)
    {
        $msg        = $this->getFeeProcessingContent($detail, $template);
        $push_array = array(
            'title' => $subject,
            'body'  => $msg,
        );
       
         if ($detail->app_keys != "") {          
            $this->_CI->pushnotification->send($detail->app_keys, $push_array, "mail_sms");
        }
    }

    public function sentHomeworkStudentSMS($detail, $template, $template_id)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        if (!empty($sms_detail)) {

            foreach ($detail as $student_key => $student_value) {
                $send_to = $student_key;
                if ($send_to != "") {
                    $msg     = $this->getHomeworkStudentContent($detail[$student_key], $template, $sms_detail->type);
                    $subject = "HomeWork Notice";
                    if ($sms_detail->type == 'clickatell') {
                        $params = array(
                            'apiToken' => $sms_detail->api_id,
                        );
                        $this->_CI->load->library('clickatell', $params);

                        try {
                            $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                            foreach ($result['messages'] as $message) {

                            }
                            return true;
                        } catch (Exception $e) {
                            return false;
                        }
                    } else if ($sms_detail->type == 'twilio') {

                        $params = array(
                            'mode'        => 'sandbox',
                            'account_sid' => $sms_detail->api_id,
                            'auth_token'  => $sms_detail->password,
                            'api_version' => '2010-04-01',
                            'number'      => $sms_detail->contact,
                        );

                        $this->_CI->load->library('twilio', $params);

                        $from     = $sms_detail->contact;
                        $to       = $send_to;
                        $message  = $msg;
                        $response = $this->_CI->twilio->sms($from, $to, $message);

                        if ($response->IsError) {
                            return false;
                        } else {
                            return true;
                        }
                    } else if ($sms_detail->type == 'msg_nineone') {

                        $params = array(
                            'authkey'    => $sms_detail->authkey,
                            'senderid'   => $sms_detail->senderid,
                            'templateid' => $template_id,
                        );
                        $this->_CI->load->library('msgnineone', $params);
                        $this->_CI->msgnineone->sendSMS($send_to, $msg);
                    } else if ($sms_detail->type == 'smscountry') {
                        $params = array(
                            'username'  => $sms_detail->username,
                            'sernderid' => $sms_detail->senderid,
                            'password'  => $sms_detail->password,
                            'authkey'   => $sms_detail->authkey,
                            'api_id'    => $sms_detail->api_id,
                        );
                        $this->_CI->load->library('smscountry', $params);
                        $this->_CI->smscountry->sendSMS($send_to, $msg);
                    } else if ($sms_detail->type == 'text_local') {
                        $params = array(
                            'username' => $sms_detail->username,
                            'hash'     => $sms_detail->password,
                        );
                        $this->_CI->load->library('textlocalsms', $params);
                        $this->_CI->textlocalsms->sendSms(array($send_to), $msg, $sms_detail->senderid);
                    } else if ($sms_detail->type == 'bulk_sms') {
                        $to     = $send_to;
                        $params = array(
                            'username' => $sms_detail->username,
                            'password' => $sms_detail->password,
                        );
                        $this->_CI->load->library('bulk_sms_lib', $params);
                        $this->_CI->bulk_sms_lib->sendSms(array($to), $msg);
                    } else if ($sms_detail->type == 'mobireach') {
                        $to     = $send_to;
                        $params = array(
                            'authkey'  => $sms_detail->authkey,
                            'senderid' => $sms_detail->senderid,
                            'routeid'  => $sms_detail->api_id,

                        );
                        $this->_CI->load->library('mobireach_lib', $params);
                        $this->_CI->mobireach_lib->sendSms(array($to), $msg);

                    } else if ($sms_detail->type == 'nexmo') {
                        $to     = $send_to;
                        $params = array(
                            'from'       => $sms_detail->senderid,
                            'api_key'    => $sms_detail->api_id,
                            'api_secret' => $sms_detail->authkey,

                        );
                        $this->_CI->load->library('nexmo_lib', $params);
                        $this->_CI->nexmo_lib->sendSms($to, $msg);

                    } else if ($sms_detail->type == 'africastalking') {
                        $to     = $send_to;
                        $params = array(
                            'from'         => $sms_detail->senderid,
                            'api_key'      => $sms_detail->api_id,
                            'api_username' => $sms_detail->username,

                        );
                        $this->_CI->load->library('africastalking_lib', $params);
                        $this->_CI->africastalking_lib->sendSms($to, $msg);
                    } else if ($sms_detail->type == 'smseg') {
                        $to = $send_to;
                        $this->_CI->load->library('smseg_lib');
                        $this->_CI->smseg_lib->sendSms($to, $msg);

                    } else if ($sms_detail->type == 'custom') {
                        $params = array(
                            'templateid' => $template_id,
                        );
                        $this->_CI->load->library('customsms', $params);
                        $from    = $sms_detail->contact;
                        $to      = $send_to;
                        $message = $msg;
                        $this->_CI->customsms->sendSMS($to, $message);
                    } else {

                    }
                }
            }
        }
    }

    public function sentOnlineexamStudentSMS($detail, $template, $template_id)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        if (!empty($sms_detail)) {

            foreach ($detail as $student_key => $student_value) {
                $send_to = $student_key;
                if ($send_to != "") {
                    $msg     = $this->getOnlineexamStudentContent($detail[$student_key], $template, $sms_detail->type);
                    $subject = "Online Examination Notice";
                    if ($sms_detail->type == 'clickatell') {
                        $params = array(
                            'apiToken' => $sms_detail->api_id,
                        );
                        $this->_CI->load->library('clickatell', $params);

                        try {
                            $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                            foreach ($result['messages'] as $message) {

                            }
                            return true;
                        } catch (Exception $e) {
                            return false;
                        }
                    } else if ($sms_detail->type == 'twilio') {

                        $params = array(
                            'mode'        => 'sandbox',
                            'account_sid' => $sms_detail->api_id,
                            'auth_token'  => $sms_detail->password,
                            'api_version' => '2010-04-01',
                            'number'      => $sms_detail->contact,
                        );

                        $this->_CI->load->library('twilio', $params);

                        $from     = $sms_detail->contact;
                        $to       = $send_to;
                        $message  = $msg;
                        $response = $this->_CI->twilio->sms($from, $to, $message);

                        if ($response->IsError) {
                            return false;
                        } else {
                            return true;
                        }
                    } else if ($sms_detail->type == 'msg_nineone') {

                        $params = array(
                            'authkey'    => $sms_detail->authkey,
                            'senderid'   => $sms_detail->senderid,
                            'templateid' => $template_id,
                        );
                        $this->_CI->load->library('msgnineone', $params);
                        $this->_CI->msgnineone->sendSMS($send_to, $msg);
                    } else if ($sms_detail->type == 'smscountry') {
                        $params = array(
                            'username'  => $sms_detail->username,
                            'sernderid' => $sms_detail->senderid,
                            'password'  => $sms_detail->password,
                            'authkey'   => $sms_detail->authkey,
                            'api_id'    => $sms_detail->api_id,
                        );
                        $this->_CI->load->library('smscountry', $params);
                        $this->_CI->smscountry->sendSMS($send_to, $msg);
                    } else if ($sms_detail->type == 'text_local') {
                        $params = array(
                            'username' => $sms_detail->username,
                            'hash'     => $sms_detail->password,
                        );
                        $this->_CI->load->library('textlocalsms', $params);
                        $this->_CI->textlocalsms->sendSms(array($send_to), $msg, $sms_detail->senderid);
                    } else if ($sms_detail->type == 'bulk_sms') {
                        $to     = $send_to;
                        $params = array(
                            'username' => $sms_detail->username,
                            'password' => $sms_detail->password,
                        );
                        $this->_CI->load->library('bulk_sms_lib', $params);
                        $this->_CI->bulk_sms_lib->sendSms(array($to), $msg);
                    } else if ($sms_detail->type == 'mobireach') {
                        $to     = $send_to;
                        $params = array(
                            'authkey'  => $sms_detail->authkey,
                            'senderid' => $sms_detail->senderid,
                            'routeid'  => $sms_detail->api_id,

                        );
                        $this->_CI->load->library('mobireach_lib', $params);
                        $this->_CI->mobireach_lib->sendSms(array($to), $msg);

                    } else if ($sms_detail->type == 'nexmo') {
                        $to     = $send_to;
                        $params = array(
                            'from'       => $sms_detail->senderid,
                            'api_key'    => $sms_detail->api_id,
                            'api_secret' => $sms_detail->authkey,

                        );
                        $this->_CI->load->library('nexmo_lib', $params);
                        $this->_CI->nexmo_lib->sendSms($to, $msg);

                    } else if ($sms_detail->type == 'africastalking') {
                        $to     = $send_to;
                        $params = array(
                            'from'         => $sms_detail->senderid,
                            'api_key'      => $sms_detail->api_id,
                            'api_username' => $sms_detail->username,

                        );
                        $this->_CI->load->library('africastalking_lib', $params);
                        $this->_CI->africastalking_lib->sendSms($to, $msg);

                    } else if ($sms_detail->type == 'smseg') {
                        $to = $send_to;
                        $this->_CI->load->library('smseg_lib');
                        $this->_CI->smseg_lib->sendSms($to, $msg);

                    } else if ($sms_detail->type == 'custom') {
                        $params = array(
                            'templateid' => $template_id,

                        );
                        $this->_CI->load->library('customsms', $params);
                        $from    = $sms_detail->contact;
                        $to      = $send_to;
                        $message = $msg;
                        $this->_CI->customsms->sendSMS($to, $message);
                    } else {

                    }
                }
            }
        }
    }

    /* send sms online admission sms */
    public function sentOnlineadmissionStudentSMS($detail, $template, $template_id, $send_to)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();

        if (!empty($sms_detail)) {

            if ($send_to != "") {
                $msg     = $this->getOnlineadmissionStudentContent($detail, $template);
                $subject = "Online Admission Confirmation";
                if ($sms_detail->type == 'clickatell') {
                    $params = array(
                        'apiToken' => $sms_detail->api_id,
                    );
                    $this->_CI->load->library('clickatell', $params);

                    try {
                        $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                        foreach ($result['messages'] as $message) {

                        }
                        return true;
                    } catch (Exception $e) {
                        return false;
                    }
                } else if ($sms_detail->type == 'twilio') {

                    $params = array(
                        'mode'        => 'sandbox',
                        'account_sid' => $sms_detail->api_id,
                        'auth_token'  => $sms_detail->password,
                        'api_version' => '2010-04-01',
                        'number'      => $sms_detail->contact,
                    );

                    $this->_CI->load->library('twilio', $params);

                    $from     = $sms_detail->contact;
                    $to       = $send_to;
                    $message  = $msg;
                    $response = $this->_CI->twilio->sms($from, $to, $message);

                    if ($response->IsError) {
                        return false;
                    } else {
                        return true;
                    }
                } else if ($sms_detail->type == 'msg_nineone') {

                    $params = array(
                        'authkey'    => $sms_detail->authkey,
                        'senderid'   => $sms_detail->senderid,
                        'templateid' => $template_id,
                    );
                    $this->_CI->load->library('msgnineone', $params);
                    $this->_CI->msgnineone->sendSMS($send_to, $msg);
                } else if ($sms_detail->type == 'smscountry') {
                    $params = array(
                        'username'  => $sms_detail->username,
                        'sernderid' => $sms_detail->senderid,
                        'password'  => $sms_detail->password,
                        'authkey'   => $sms_detail->authkey,
                        'api_id'    => $sms_detail->api_id,
                    );
                    $this->_CI->load->library('smscountry', $params);
                    $this->_CI->smscountry->sendSMS($send_to, $msg);
                } else if ($sms_detail->type == 'text_local') {
                    $params = array(
                        'username' => $sms_detail->username,
                        'hash'     => $sms_detail->password,
                    );
                    $this->_CI->load->library('textlocalsms', $params);
                    $this->_CI->textlocalsms->sendSms(array($send_to), $msg, $sms_detail->senderid);
                } else if ($sms_detail->type == 'bulk_sms') {
                    $to     = $send_to;
                    $params = array(
                        'username' => $sms_detail->username,
                        'password' => $sms_detail->password,
                    );
                    $this->_CI->load->library('bulk_sms_lib', $params);
                    $this->_CI->bulk_sms_lib->sendSms(array($to), $msg);
                } else if ($sms_detail->type == 'mobireach') {
                    $to     = $send_to;
                    $params = array(
                        'authkey'  => $sms_detail->authkey,
                        'senderid' => $sms_detail->senderid,
                        'routeid'  => $sms_detail->api_id,
                    );
                    $this->_CI->load->library('mobireach_lib', $params);
                    $this->_CI->mobireach_lib->sendSms(array($to), $msg);
                } else if ($sms_detail->type == 'nexmo') {
                    $to     = $send_to;
                    $params = array(
                        'from'       => $sms_detail->senderid,
                        'api_key'    => $sms_detail->api_id,
                        'api_secret' => $sms_detail->authkey,
                    );
                    $this->_CI->load->library('nexmo_lib', $params);
                    $this->_CI->nexmo_lib->sendSms($to, $msg);
                } else if ($sms_detail->type == 'africastalking') {
                    $to     = $send_to;
                    $params = array(
                        'from'         => $sms_detail->senderid,
                        'api_key'      => $sms_detail->api_id,
                        'api_username' => $sms_detail->username,

                    );
                    $this->_CI->load->library('africastalking_lib', $params);
                    $this->_CI->africastalking_lib->sendSms($to, $msg);
                } else if ($sms_detail->type == 'smseg') {
                    $to = $send_to;
                    $this->_CI->load->library('smseg_lib');
                    $this->_CI->smseg_lib->sendSms($to, $msg);
                } else if ($sms_detail->type == 'custom') {
                    $params = array(
                        'templateid' => $template_id,
                    );
                    $this->_CI->load->library('customsms', $params);
                    $from    = $sms_detail->contact;
                    $to      = $send_to;
                    $message = $msg;
                    $this->_CI->customsms->sendSMS($to, $message);
                } else {

                }
            }
        }
    }

    public function getOnlineadmissionStudentContent($student_detail, $template)
    {
        foreach ($student_detail as $key => $value) {
            $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : "";
        }
        return $template;
    }

    /* end online admission sms */
    public function getStudentRegistrationContent($id, $template, $sms_detail_type)
    {
        $session_name                    = $this->_CI->setting_model->getCurrentSessionName();
        $student                         = $this->_CI->student_model->get($id);
        $student['current_session_name'] = $session_name;
        $student['student_name']         = $student['firstname'] . " " . $student['lastname'];

        foreach ($student as $key => $value) {
            if ($sms_detail_type == 'msg_nineone') {
                if (strlen($value) > 30) {
                    $value = substr($value, 0, 29);
                }
            }
            $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : "";
        }

        return $template;
    }

    public function getAddFeeContent($data, $template, $sms_detail_type = null)
    {
        $currency_symbol      = $this->sch_setting[0]['currency_symbol'];
        $school_name          = $this->sch_setting[0]['name'];
        $invoice_data         = json_decode($data->invoice);
        $data->invoice_id     = $invoice_data->invoice_id;
        $data->sub_invoice_id = $invoice_data->sub_invoice_id;
        $data->payment_id     = $data->invoice_id."/".$data->sub_invoice_id;       
        $data->amount         = $currency_symbol . $data->amount;      
        
        if ($data->fee_category == "transport") {
            $fee = $this->_CI->studentfeemaster_model->getTransportFeeByInvoice($data->invoice_id, $data->sub_invoice_id);
        } else {
            $fee = $this->_CI->studentfeemaster_model->getFeeByInvoice($data->invoice_id, $data->sub_invoice_id);
        }
       
        $a                    = json_decode($fee->amount_detail);
        $record               = $a->{$data->sub_invoice_id};
        $fee_amount           = number_format((($record->amount + $record->amount_fine)), 2, '.', ',');
        $data->firstname      = $fee->firstname;
        $data->lastname       = $fee->lastname;
        $data->class          = $fee->class;
        $data->section        = $fee->section;
        $data->fee_amount     = $currency_symbol . $fee_amount;       
        $data->student_name   = $this->_CI->customlib->getFullName($fee->firstname, $fee->middlename, $fee->lastname, $this->sch_setting[0]['middlename'], $this->sch_setting[0]['lastname']); 
        
        foreach ($data as $key => $value) {

            if ($sms_detail_type == 'msg_nineone') {
                if ($key != 'url') {

                    if (strlen($value) > 30) {
                        $value = substr($value, 0, 29);
                    }
                }
            }
            
            $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : "";
        }

        return $template;
    }

    public function getGroupAddFeeContent($data, $template, $sms_detail_type = null)
    {
     $currency_symbol      = $this->sch_setting[0]['currency_symbol'];
        $school_name          = $this->sch_setting[0]['name'];
        $fee_amount=0;
        $data['payment_id']="";
        $payment_id=[];
        foreach ($data['invoice'] as $invoice_key => $invoice_value) {
            # code...
      
        $payment_id[]=$invoice_value['invoice_id']."/".$invoice_value['sub_invoice_id'];

        if ($invoice_value['fee_category'] == "transport") {

            $fee = $this->_CI->studentfeemaster_model->getTransportFeeByInvoice($invoice_value['invoice_id'], $invoice_value['sub_invoice_id']);

        } else {

        $fee = $this->_CI->studentfeemaster_model->getFeeByInvoice($invoice_value['invoice_id'], $invoice_value['sub_invoice_id']);
        }

        $a          = json_decode($fee->amount_detail);
        $record     = $a->{$invoice_value['sub_invoice_id']};
        $fee_amount += ($record->amount + $record->amount_fine);
        }
     
        $data['payment_id']            = "(".implode(',', $payment_id).")";
        $data['class']        = $fee->class;
        $data['section']      = $fee->section;
        $data['fee_amount']   = $currency_symbol . amountFormat($fee_amount);
        $data['student_name'] = $this->_CI->customlib->getFullName($fee->firstname, $fee->middlename, $fee->lastname, $this->sch_setting[0]['middlename'], $this->sch_setting[0]['lastname']); 
       
        unset($data['invoice']);

        foreach ($data as $key => $value) {
              if ($sms_detail_type == 'msg_nineone') {
                if ($key != 'url') {

                    if (strlen($value) > 30) {
                        $value = substr($value, 0, 29);
                    }
                }
            }

            $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : "";
        }

        return $template;
    }


    public function getFeeProcessingContent($data, $template, $sms_detail_type = null)
    {
        $currency_symbol  = $this->sch_setting[0]['currency_symbol'];
        $school_name      = $this->sch_setting[0]['name'];
        $fee_amount       = number_format((float)(($data->fee_amount)), 2, '.', ',');
        $data->fee_amount = $currency_symbol . $fee_amount;

        foreach ($data as $key => $value) {

            if ($sms_detail_type == 'msg_nineone') {
                if ($key != 'url') {

                    if (strlen($value) > 30) {
                        $value = substr($value, 0, 29);
                    }
                }
            }

            $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : "";
        }

        return $template;
    }

    public function sentOnlineClassStudentSMS($detail, $template)
    {

        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        if (!empty($sms_detail)) {

            foreach ($detail as $student_key => $student_value) {
                $send_to = $student_key;
                if ($send_to != "") {
                    $msg = $this->getOnlineClassStudentContent($detail[$student_key], $template);

                    $subject = "Online Class";
                    if ($sms_detail->type == 'clickatell') {
                        $params = array(
                            'apiToken' => $sms_detail->api_id,
                        );
                        $this->_CI->load->library('clickatell', $params);

                        try {
                            $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                            foreach ($result['messages'] as $message) {

                            }
                            return true;
                        } catch (Exception $e) {
                            return false;
                        }
                    } else if ($sms_detail->type == 'twilio') {

                        $params = array(
                            'mode'        => 'sandbox',
                            'account_sid' => $sms_detail->api_id,
                            'auth_token'  => $sms_detail->password,
                            'api_version' => '2010-04-01',
                            'number'      => $sms_detail->contact,
                        );

                        $this->_CI->load->library('twilio', $params);

                        $from     = $sms_detail->contact;
                        $to       = $send_to;
                        $message  = $msg;
                        $response = $this->_CI->twilio->sms($from, $to, $message);

                        if ($response->IsError) {
                            return false;
                        } else {
                            return true;
                        }
                    } else if ($sms_detail->type == 'msg_nineone') {

                        $params = array(
                            'authkey'  => $sms_detail->authkey,
                            'senderid' => $sms_detail->senderid,
                        );
                        $this->_CI->load->library('msgnineone', $params);
                        $this->_CI->msgnineone->sendSMS($send_to, $msg);
                    } else if ($sms_detail->type == 'smscountry') {
                        $params = array(
                            'username'  => $sms_detail->username,
                            'sernderid' => $sms_detail->senderid,
                            'password'  => $sms_detail->password,
                            'authkey'   => $sms_detail->authkey,
                            'api_id'    => $sms_detail->api_id,
                        );
                        $this->_CI->load->library('smscountry', $params);
                        $this->_CI->smscountry->sendSMS($send_to, $msg);
                    } else if ($sms_detail->type == 'text_local') {
                        $params = array(
                            'username' => $sms_detail->username,
                            'hash'     => $sms_detail->password,
                        );
                        $this->_CI->load->library('textlocalsms', $params);
                        $this->_CI->textlocalsms->sendSms(array($send_to), $msg, $sms_detail->senderid);
                    } else if ($sms_detail->type == 'bulk_sms') {
                        $to     = $send_to;
                        $params = array(
                            'username' => $sms_detail->username,
                            'password' => $sms_detail->password,
                        );
                        $this->_CI->load->library('bulk_sms_lib', $params);
                        $this->_CI->bulk_sms_lib->sendSms(array($to), $msg);
                    } else if ($sms_detail->type == 'mobireach') {
                        $to     = $send_to;
                        $params = array(
                            'authkey'  => $sms_detail->authkey,
                            'senderid' => $sms_detail->senderid,
                            'routeid'  => $sms_detail->api_id,

                        );
                        $this->_CI->load->library('mobireach_lib', $params);
                        $this->_CI->mobireach_lib->sendSms(array($to), $msg);

                    } else if ($sms_detail->type == 'nexmo') {
                        $to     = $send_to;
                        $params = array(
                            'from'       => $sms_detail->senderid,
                            'api_key'    => $sms_detail->api_id,
                            'api_secret' => $sms_detail->authkey,

                        );
                        $this->_CI->load->library('nexmo_lib', $params);
                        $this->_CI->nexmo_lib->sendSms($to, $msg);

                    } else if ($sms_detail->type == 'africastalking') {
                        $to     = $send_to;
                        $params = array(
                            'from'         => $sms_detail->senderid,
                            'api_key'      => $sms_detail->api_id,
                            'api_username' => $sms_detail->username,

                        );
                        $this->_CI->load->library('africastalking_lib', $params);
                        $this->_CI->africastalking_lib->sendSms($to, $msg);

                    } else if ($sms_detail->type == 'smseg') {
                        $to = $send_to;
                        $this->_CI->load->library('smseg_lib');
                        $this->_CI->smseg_lib->sendSms($to, $msg);

                    } else if ($sms_detail->type == 'custom') {

                        $params = array(
                            'templateid' => $template_id,

                        );
                        $this->_CI->load->library('customsms', $params);
                        $from    = $sms_detail->contact;
                        $to      = $send_to;
                        $message = $msg;
                        $this->_CI->customsms->sendSMS($to, $message);
                    } else {

                    }
                }
            }
        }
    }

    public function sentOnlineMeetingStaffSMS($detail, $template)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        if (!empty($sms_detail)) {

            foreach ($detail as $staff_key => $staff_value) {
                $send_to = $staff_key;
                if ($send_to != "") {
                    $msg = $this->getOnlineMeetingStaffContent($detail[$staff_key], $template);

                    $subject = "Online Meeting";
                    if ($sms_detail->type == 'clickatell') {
                        $params = array(
                            'apiToken' => $sms_detail->api_id,
                        );
                        $this->_CI->load->library('clickatell', $params);

                        try {
                            $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                            foreach ($result['messages'] as $message) {

                            }
                            return true;
                        } catch (Exception $e) {
                            return false;
                        }
                    } else if ($sms_detail->type == 'twilio') {

                        $params = array(
                            'mode'        => 'sandbox',
                            'account_sid' => $sms_detail->api_id,
                            'auth_token'  => $sms_detail->password,
                            'api_version' => '2010-04-01',
                            'number'      => $sms_detail->contact,
                        );

                        $this->_CI->load->library('twilio', $params);

                        $from     = $sms_detail->contact;
                        $to       = $send_to;
                        $message  = $msg;
                        $response = $this->_CI->twilio->sms($from, $to, $message);

                        if ($response->IsError) {
                            return false;
                        } else {
                            return true;
                        }
                    } else if ($sms_detail->type == 'msg_nineone') {

                        $params = array(
                            'authkey'  => $sms_detail->authkey,
                            'senderid' => $sms_detail->senderid,
                        );
                        $this->_CI->load->library('msgnineone', $params);
                        $this->_CI->msgnineone->sendSMS($send_to, $msg);
                    } else if ($sms_detail->type == 'smscountry') {
                        $params = array(
                            'username'  => $sms_detail->username,
                            'sernderid' => $sms_detail->senderid,
                            'password'  => $sms_detail->password,
                            'authkey'   => $sms_detail->authkey,
                            'api_id'    => $sms_detail->api_id,
                        );
                        $this->_CI->load->library('smscountry', $params);
                        $this->_CI->smscountry->sendSMS($send_to, $msg);
                    } else if ($sms_detail->type == 'text_local') {
                        $params = array(
                            'username' => $sms_detail->username,
                            'hash'     => $sms_detail->password,
                        );
                        $this->_CI->load->library('textlocalsms', $params);
                        $this->_CI->textlocalsms->sendSms(array($send_to), $msg, $sms_detail->senderid);
                    } else if ($sms_detail->type == 'bulk_sms') {
                        $to     = $send_to;
                        $params = array(
                            'username' => $sms_detail->username,
                            'password' => $sms_detail->password,
                        );
                        $this->_CI->load->library('bulk_sms_lib', $params);
                        $this->_CI->bulk_sms_lib->sendSms(array($to), $msg);
                    } else if ($sms_detail->type == 'mobireach') {
                        $to     = $send_to;
                        $params = array(
                            'authkey'  => $sms_detail->authkey,
                            'senderid' => $sms_detail->senderid,
                            'routeid'  => $sms_detail->api_id,

                        );
                        $this->_CI->load->library('mobireach_lib', $params);
                        $this->_CI->mobireach_lib->sendSms(array($to), $msg);

                    } else if ($sms_detail->type == 'nexmo') {
                        $to     = $send_to;
                        $params = array(
                            'from'       => $sms_detail->senderid,
                            'api_key'    => $sms_detail->api_id,
                            'api_secret' => $sms_detail->authkey,

                        );
                        $this->_CI->load->library('nexmo_lib', $params);
                        $this->_CI->nexmo_lib->sendSms($to, $msg);

                    } else if ($sms_detail->type == 'africastalking') {
                        $to     = $send_to;
                        $params = array(
                            'from'         => $sms_detail->senderid,
                            'api_key'      => $sms_detail->api_id,
                            'api_username' => $sms_detail->username,

                        );
                        $this->_CI->load->library('africastalking_lib', $params);
                        $this->_CI->africastalking_lib->sendSms($to, $msg);

                    } else if ($sms_detail->type == 'smseg') {
                        $to = $send_to;
                        $this->_CI->load->library('smseg_lib');
                        $this->_CI->smseg_lib->sendSms($to, $msg);

                    } else if ($sms_detail->type == 'custom') {

                        $params = array(
                            'templateid' => $template_id,

                        );
                        $this->_CI->load->library('customsms', $params);
                        $from    = $sms_detail->contact;
                        $to      = $send_to;
                        $message = $msg;
                        $this->_CI->customsms->sendSMS($to, $message);
                    } else {

                    }
                }
            }
        }
    }

    public function sentOnlineadmissionFeesSMS($detail, $template, $template_id)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();

        if (!empty($sms_detail)) {
            $send_to = $detail['mobileno'];
            if ($send_to != "") {
                $msg     = $this->getOnlineadmissionFeesContent($detail, $template, $sms_detail->type);
                $subject = "Online Admission Confirmation";
                if ($sms_detail->type == 'clickatell') {
                    $params = array(
                        'apiToken' => $sms_detail->api_id,
                    );
                    $this->_CI->load->library('clickatell', $params);

                    try {
                        $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                        foreach ($result['messages'] as $message) {

                        }
                        return true;
                    } catch (Exception $e) {
                        return false;
                    }
                } else if ($sms_detail->type == 'twilio') {

                    $params = array(
                        'mode'        => 'sandbox',
                        'account_sid' => $sms_detail->api_id,
                        'auth_token'  => $sms_detail->password,
                        'api_version' => '2010-04-01',
                        'number'      => $sms_detail->contact,
                    );

                    $this->_CI->load->library('twilio', $params);

                    $from     = $sms_detail->contact;
                    $to       = $send_to;
                    $message  = $msg;
                    $response = $this->_CI->twilio->sms($from, $to, $message);

                    if ($response->IsError) {
                        return false;
                    } else {
                        return true;
                    }
                } else if ($sms_detail->type == 'msg_nineone') {

                    $params = array(
                        'authkey'  => $sms_detail->authkey,
                        'senderid' => $sms_detail->senderid,
                    );
                    $this->_CI->load->library('msgnineone', $params);
                    $this->_CI->msgnineone->sendSMS($send_to, $msg);
                } else if ($sms_detail->type == 'smscountry') {
                    $params = array(
                        'username'  => $sms_detail->username,
                        'sernderid' => $sms_detail->senderid,
                        'password'  => $sms_detail->password,
                        'authkey'   => $sms_detail->authkey,
                        'api_id'    => $sms_detail->api_id,
                    );
                    $this->_CI->load->library('smscountry', $params);
                    $this->_CI->smscountry->sendSMS($send_to, $msg);
                } else if ($sms_detail->type == 'text_local') {
                    $params = array(
                        'username' => $sms_detail->username,
                        'hash'     => $sms_detail->password,
                    );
                    $this->_CI->load->library('textlocalsms', $params);
                    $this->_CI->textlocalsms->sendSms(array($send_to), $msg, $sms_detail->senderid);
                } else if ($sms_detail->type == 'bulk_sms') {
                    $to     = $send_to;
                    $params = array(
                        'username' => $sms_detail->username,
                        'password' => $sms_detail->password,
                    );
                    $this->_CI->load->library('bulk_sms_lib', $params);
                    $this->_CI->bulk_sms_lib->sendSms(array($to), $msg);
                } else if ($sms_detail->type == 'mobireach') {
                    $to     = $send_to;
                    $params = array(
                        'authkey'  => $sms_detail->authkey,
                        'senderid' => $sms_detail->senderid,
                        'routeid'  => $sms_detail->api_id,

                    );
                    $this->_CI->load->library('mobireach_lib', $params);
                    $this->_CI->mobireach_lib->sendSms(array($to), $msg);
                } else if ($sms_detail->type == 'nexmo') {
                    $to     = $send_to;
                    $params = array(
                        'from'       => $sms_detail->senderid,
                        'api_key'    => $sms_detail->api_id,
                        'api_secret' => $sms_detail->authkey,

                    );
                    $this->_CI->load->library('nexmo_lib', $params);
                    $this->_CI->nexmo_lib->sendSms($to, $msg);
                } else if ($sms_detail->type == 'africastalking') {
                    $to     = $send_to;
                    $params = array(
                        'from'         => $sms_detail->senderid,
                        'api_key'      => $sms_detail->api_id,
                        'api_username' => $sms_detail->username,

                    );
                    $this->_CI->load->library('africastalking_lib', $params);
                    $this->_CI->africastalking_lib->sendSms($to, $msg);
                } else if ($sms_detail->type == 'smseg') {
                    $to = $send_to;
                    $this->_CI->load->library('smseg_lib');
                    $this->_CI->smseg_lib->sendSms($to, $msg);

                } else if ($sms_detail->type == 'custom') {
                    $params = array(
                        'templateid' => $template_id,

                    );
                    $this->_CI->load->library('customsms', $params);
                    $from    = $sms_detail->contact;
                    $to      = $send_to;
                    $message = $msg;
                    $this->_CI->customsms->sendSMS($to, $message);
                } else {

                }
            }
        }
    }

    public function getOnlineadmissionFeesContent($student_detail, $template, $sms_detail_type = null)
    {

        foreach ($student_detail as $key => $value) {

            if ($sms_detail_type == 'msg_nineone') {

                if (strlen($value) > 30) {
                    $value = substr($value, 0, 29);
                }

            }
            $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : "";
        }

        return $template;
    }

    public function getLoginCredentialContent($credential_for, $sender_details, $template, $sms_detail_type)
    {
        if ($credential_for == "student") {
            $student                        = $this->_CI->student_model->get($sender_details['id']);
            $sender_details['url']          = base_url();
            $sender_details['display_name'] = $student['firstname'] . " " . $student['lastname'];
        } elseif ($credential_for == "parent") {
            $parent                         = $this->_CI->student_model->get($sender_details['id']);
            $sender_details['url']          = base_url();
            $sender_details['display_name'] = $parent['guardian_name'];
        } elseif ($credential_for == "staff") {
            $staff                          = $this->_CI->staff_model->get($sender_details['id']);
            $sender_details['url']          = base_url();
            $sender_details['display_name'] = $staff['name'];
        }

        foreach ($sender_details as $key => $value) {

            if ($sms_detail_type == 'msg_nineone') {
                if ($key != 'url') {

                    if (strlen($value) > 30) {
                        $value = substr($value, 0, 29);
                    }
                }
            }

            $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : "";
        }

        return $template;
    }

    public function getAbsentStudentContent($student_detail, $template, $sms_detail_type = null)
    {

        $session_name                           = $this->_CI->setting_model->getCurrentSessionName();
        $student_detail['current_session_name'] = $session_name;
        foreach ($student_detail as $key => $value) {

            if ($sms_detail_type == 'msg_nineone') {

                if (strlen($value) > 30) {
                    $value = substr($value, 0, 29);
                }
            }
            $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : "";
        }
        return $template;
    }

    public function getStudentResultContent($student_result_detail, $template, $sms_detail_type = null)
    {

        foreach ($student_result_detail as $key => $value) {

            if ($key != 'contact_numbers') {

                if ($sms_detail_type == 'msg_nineone') {

                    if (strlen($value) > 30) {
                        $value = substr($value, 0, 29);
                    }

                }
                $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : "";
            }

        }

        return $template;
    }

    public function getContent($sender_details, $template, $sms_detail_type = null)
    {

        foreach ($sender_details as $key => $value) {

            if ($sms_detail_type == 'msg_nineone') {

                if (strlen($value) > 30) {
                    $value = substr($value, 0, 29);
                }

            }
            $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : "";
        }

        return $template;
    }

    public function getHomeworkStudentContent($student_detail, $template, $sms_detail_type = null)
    {

        foreach ($student_detail as $key => $value) {

            if ($sms_detail_type == 'msg_nineone') {

                if (strlen($value) > 30) {
                    $value = substr($value, 0, 29);
                }

            }
            $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : "";
        }
        return $template;
    }
    
    public function getOnlineexamStudentContent($student_detail, $template, $sms_detail_type = null)
    {

        foreach ($student_detail as $key => $value) {

            if ($sms_detail_type == 'msg_nineone') {

                if (strlen($value) > 30) {
                    $value = substr($value, 0, 29);
                }

            }
            $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : "";
        }
        return $template;
    }

    public function getOnlineClassStudentContent($student_detail, $template)
    {

        foreach ($student_detail as $key => $value) {
            $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : "";
        }
        return $template;
    }

    public function getOnlineMeetingStaffContent($student_detail, $template)
    {

        foreach ($student_detail as $key => $value) {
            $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : "";
        }
        return $template;
    }

    public function sentSMSToAlumni($sender_details, $template_id = null)
    {

        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        $msg        = $sender_details['subject'] . " - Event From " . $sender_details['from_date'] . " To " . $sender_details['to_date'] . "\n" .
            $sender_details['body'];
        $send_to = $sender_details['contact_no'];

        if (!empty($sms_detail)) {
            if ($sms_detail->type == 'clickatell') {
                $params = array(
                    'apiToken' => $sms_detail->api_id,
                );
                $this->_CI->load->library('clickatell', $params);
                try {
                    $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                    foreach ($result['messages'] as $message) {

                    }
                    return true;
                } catch (Exception $e) {
                    return true;
                }
            } else if ($sms_detail->type == 'twilio') {
                $params = array(
                    'mode'        => 'sandbox',
                    'account_sid' => $sms_detail->api_id,
                    'auth_token'  => $sms_detail->password,
                    'api_version' => '2010-04-01',
                    'number'      => $sms_detail->contact,
                );

                $this->_CI->load->library('twilio', $params);

                $from     = $sms_detail->contact;
                $to       = $send_to;
                $message  = $msg;
                $response = $this->_CI->twilio->sms($from, $to, $message);

                if ($response->IsError) {
                    return true;
                } else {
                    return true;
                }
            } else if ($sms_detail->type == 'msg_nineone') {                  
                $params = array(
                    'authkey'    => $sms_detail->authkey,
                    'senderid'   => $sms_detail->senderid,
                    'templateid' => $template_id,
                );                    
                $this->_CI->load->library('msgnineone', $params);
                $this->_CI->msgnineone->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'smscountry') {
                $params = array(
                    'username'  => $sms_detail->username,
                    'sernderid' => $sms_detail->senderid,
                    'password'  => $sms_detail->password,
                    'authkey'   => $sms_detail->authkey,
                    'api_id'    => $sms_detail->api_id,
                );
                $this->_CI->load->library('smscountry', $params);
                $this->_CI->smscountry->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'text_local') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'hash'     => $sms_detail->password,
                );
                $this->_CI->load->library('textlocalsms', $params);
                $this->_CI->textlocalsms->sendSms(array($to), $msg, $sms_detail->senderid);
            } else if ($sms_detail->type == 'bulk_sms') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'password' => $sms_detail->password,
                );
                $this->_CI->load->library('bulk_sms_lib', $params);
                $this->_CI->bulk_sms_lib->sendSms(array($to), $msg);
            } else if ($sms_detail->type == 'mobireach') {
                $to     = $send_to;
                $params = array(
                    'authkey'  => $sms_detail->authkey,
                    'senderid' => $sms_detail->senderid,
                    'routeid'  => $sms_detail->api_id,

                );
                $this->_CI->load->library('mobireach_lib', $params);
                $this->_CI->mobireach_lib->sendSms(array($to), $msg);

            } else if ($sms_detail->type == 'nexmo') {
                $to     = $send_to;
                $params = array(
                    'from'       => $sms_detail->senderid,
                    'api_key'    => $sms_detail->api_id,
                    'api_secret' => $sms_detail->authkey,
                );
                $this->_CI->load->library('nexmo_lib', $params);
                $this->_CI->nexmo_lib->sendSms($to, $msg);

            } else if ($sms_detail->type == 'smseg') {
                $to = $send_to;
                $this->_CI->load->library('smseg_lib');
                $this->_CI->smseg_lib->sendSms($to, $msg);

            } else if ($sms_detail->type == 'africastalking') {
                $to     = $send_to;
                $params = array(
                    'from'         => $sms_detail->senderid,
                    'api_key'      => $sms_detail->api_id,
                    'api_username' => $sms_detail->username,

                );
                $this->_CI->load->library('africastalking_lib', $params);
                $this->_CI->africastalking_lib->sendSms($to, $msg);

            } else if ($sms_detail->type == 'custom') {
                $params = array(
                    'templateid' => $template_id,
                );
                $this->_CI->load->library('customsms', $params);
                $from    = $sms_detail->contact;
                $to      = $send_to;
                $message = $msg;
                $this->_CI->customsms->sendSMS($to, $message);
            } else {

            }
        }
        return true;
    }

    public function sendStudentLoginCredential($chk_mail_sms, $sender_details, $template, $template_id)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        $msg        = $this->getLoginCredentialContent($sender_details['credential_for'], $sender_details, $template, $sms_detail->type);

        $send_to = $sender_details['contact_no'];
        if (!empty($sms_detail)) {
            if ($sms_detail->type == 'clickatell') {

                $params = array(
                    'apiToken' => $sms_detail->api_id,
                );
                $this->_CI->load->library('clickatell', $params);
                try {
                    $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                    foreach ($result['messages'] as $message) {

                    }
                    return true;
                } catch (Exception $e) {
                    return true;
                }
            } else if ($sms_detail->type == 'twilio') {

                $params = array(
                    'mode'        => 'sandbox',
                    'account_sid' => $sms_detail->api_id,
                    'auth_token'  => $sms_detail->password,
                    'api_version' => '2010-04-01',
                    'number'      => $sms_detail->contact,
                );

                $this->_CI->load->library('twilio', $params);

                $from     = $sms_detail->contact;
                $to       = $send_to;
                $message  = $msg;
                $response = $this->_CI->twilio->sms($from, $to, $message);

                if ($response->IsError) {
                    return true;
                } else {
                    return true;
                }
            } else if ($sms_detail->type == 'msg_nineone') {
                $params = array(
                    'authkey'    => $sms_detail->authkey,
                    'senderid'   => $sms_detail->senderid,
                    'templateid' => $template_id,
                );

                $this->_CI->load->library('msgnineone', $params);
                $this->_CI->msgnineone->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'smscountry') {
                $params = array(
                    'username'  => $sms_detail->username,
                    'sernderid' => $sms_detail->senderid,
                    'password'  => $sms_detail->password,
                    'authkey'   => $sms_detail->authkey,
                    'api_id'    => $sms_detail->api_id,
                );
                $this->_CI->load->library('smscountry', $params);
                $this->_CI->smscountry->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'text_local') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'hash'     => $sms_detail->password,
                );
                $this->_CI->load->library('textlocalsms', $params);
                $this->_CI->textlocalsms->sendSms(array($to), $msg, $sms_detail->senderid);
            } else if ($sms_detail->type == 'bulk_sms') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'password' => $sms_detail->password,
                );
                $this->_CI->load->library('bulk_sms_lib', $params);
                $this->_CI->bulk_sms_lib->sendSms(array($to), $msg);
            } else if ($sms_detail->type == 'mobireach') {
                $to     = $send_to;
                $params = array(
                    'authkey'  => $sms_detail->authkey,
                    'senderid' => $sms_detail->senderid,
                    'routeid'  => $sms_detail->api_id,

                );
                $this->_CI->load->library('mobireach_lib', $params);
                $this->_CI->mobireach_lib->sendSms(array($to), $msg);

            } else if ($sms_detail->type == 'nexmo') {
                $to     = $send_to;
                $params = array(
                    'from'       => $sms_detail->senderid,
                    'api_key'    => $sms_detail->api_id,
                    'api_secret' => $sms_detail->authkey,

                );
                $this->_CI->load->library('nexmo_lib', $params);
                $this->_CI->nexmo_lib->sendSms($to, $msg);

            } else if ($sms_detail->type == 'africastalking') {
                $to     = $send_to;
                $params = array(
                    'from'         => $sms_detail->senderid,
                    'api_key'      => $sms_detail->api_id,
                    'api_username' => $sms_detail->username,

                );
                $this->_CI->load->library('africastalking_lib', $params);
                $this->_CI->africastalking_lib->sendSms($to, $msg);

            } else if ($sms_detail->type == 'smseg') {
                $to = $send_to;
                $this->_CI->load->library('smseg_lib');
                $this->_CI->smseg_lib->sendSms($to, $msg);

            } else if ($sms_detail->type == 'custom') {
                $param = array('template_id' => $template_id);
                $this->_CI->load->library('customsms', $param);
                $from    = $sms_detail->contact;
                $to      = $send_to;
                $message = $msg;
                $this->_CI->customsms->sendSMS($to, $message);
            } else {

            }
        }
        return true;
    }

    public function sendStaffLoginCredential($chk_mail_sms, $sender_details, $template, $template_id)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        $msg        = $this->getLoginCredentialContent($sender_details['credential_for'], $sender_details, $template, $sms_detail->type);

        $send_to = $sender_details['contact_no'];
        if (!empty($sms_detail)) {
            if ($sms_detail->type == 'clickatell') {

                $params = array(
                    'apiToken' => $sms_detail->api_id,
                );
                $this->_CI->load->library('clickatell', $params);
                try {
                    $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                    foreach ($result['messages'] as $message) {

                    }
                    return true;
                } catch (Exception $e) {
                    return true;
                }
            } else if ($sms_detail->type == 'twilio') {

                $params = array(
                    'mode'        => 'sandbox',
                    'account_sid' => $sms_detail->api_id,
                    'auth_token'  => $sms_detail->password,
                    'api_version' => '2010-04-01',
                    'number'      => $sms_detail->contact,
                );

                $this->_CI->load->library('twilio', $params);

                $from     = $sms_detail->contact;
                $to       = $send_to;
                $message  = $msg;
                $response = $this->_CI->twilio->sms($from, $to, $message);

                if ($response->IsError) {
                    return true;
                } else {
                    return true;
                }
            } else if ($sms_detail->type == 'msg_nineone') {
                $params = array(
                    'authkey'    => $sms_detail->authkey,
                    'senderid'   => $sms_detail->senderid,
                    'templateid' => $template_id,
                );

                $this->_CI->load->library('msgnineone', $params);
                $this->_CI->msgnineone->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'smscountry') {
                $params = array(
                    'username'  => $sms_detail->username,
                    'sernderid' => $sms_detail->senderid,
                    'password'  => $sms_detail->password,
                    'authkey'   => $sms_detail->authkey,
                    'api_id'    => $sms_detail->api_id,
                );
                $this->_CI->load->library('smscountry', $params);
                $this->_CI->smscountry->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'text_local') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'hash'     => $sms_detail->password,
                );
                $this->_CI->load->library('textlocalsms', $params);
                $this->_CI->textlocalsms->sendSms(array($to), $msg, $sms_detail->senderid);
            } else if ($sms_detail->type == 'bulk_sms') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'password' => $sms_detail->password,
                );
                $this->_CI->load->library('bulk_sms_lib', $params);
                $this->_CI->bulk_sms_lib->sendSms(array($to), $msg);
            } else if ($sms_detail->type == 'mobireach') {
                $to     = $send_to;
                $params = array(
                    'authkey'  => $sms_detail->authkey,
                    'senderid' => $sms_detail->senderid,
                    'routeid'  => $sms_detail->api_id,
                );
                $this->_CI->load->library('mobireach_lib', $params);
                $this->_CI->mobireach_lib->sendSms(array($to), $msg);

            } else if ($sms_detail->type == 'nexmo') {
                $to     = $send_to;
                $params = array(
                    'from'       => $sms_detail->senderid,
                    'api_key'    => $sms_detail->api_id,
                    'api_secret' => $sms_detail->authkey,
                );
                $this->_CI->load->library('nexmo_lib', $params);
                $this->_CI->nexmo_lib->sendSms($to, $msg);
            } else if ($sms_detail->type == 'africastalking') {
                $to     = $send_to;
                $params = array(
                    'from'         => $sms_detail->senderid,
                    'api_key'      => $sms_detail->api_id,
                    'api_username' => $sms_detail->username,

                );
                $this->_CI->load->library('africastalking_lib', $params);
                $this->_CI->africastalking_lib->sendSms($to, $msg);
            } else if ($sms_detail->type == 'smseg') {
                $to = $send_to;
                $this->_CI->load->library('smseg_lib');
                $this->_CI->smseg_lib->sendSms($to, $msg);
            } else if ($sms_detail->type == 'custom') {
                $param = array('template_id' => $template_id);
                $this->_CI->load->library('customsms', $param);
                $from    = $sms_detail->contact;
                $to      = $send_to;
                $message = $msg;
                $this->_CI->customsms->sendSMS($to, $message);
            } else {

            }
        }
        return true;
    }

    public function student_apply_leave($chk_mail_sms, $sender_details, $template, $template_id)
    {
        $sms_detail = $this->_CI->smsconfig_model->getActiveSMS();
        if (!empty($sms_detail)) {
     
        $msg        = $this->getstudent_apply_leaveContent($sender_details, $template, $sms_detail->type);
 
        $send_to = $sender_details['contact_no'];
        if (!empty($sms_detail)) {
            if ($sms_detail->type == 'clickatell') {

                $params = array(
                    'apiToken' => $sms_detail->api_id,
                );
                $this->_CI->load->library('clickatell', $params);
                try {
                    $result = $this->_CI->clickatell->sendMessage(['to' => [$send_to], 'content' => $msg]);
                    foreach ($result['messages'] as $message) {

                    }
                    return true;
                } catch (Exception $e) {
                    return true;
                }
            } else if ($sms_detail->type == 'twilio') {

                $params = array(
                    'mode'        => 'sandbox',
                    'account_sid' => $sms_detail->api_id,
                    'auth_token'  => $sms_detail->password,
                    'api_version' => '2010-04-01',
                    'number'      => $sms_detail->contact,
                );

                $this->_CI->load->library('twilio', $params);

                $from     = $sms_detail->contact;
                $to       = $send_to;
                $message  = $msg;
                $response = $this->_CI->twilio->sms($from, $to, $message);

                if ($response->IsError) {
                    return true;
                } else {
                    return true;
                }
            } else if ($sms_detail->type == 'msg_nineone') {
                $params = array(
                    'authkey'    => $sms_detail->authkey,
                    'senderid'   => $sms_detail->senderid,
                    'templateid' => $template_id,
                );

                $this->_CI->load->library('msgnineone', $params);
                $this->_CI->msgnineone->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'smscountry') {
                $params = array(
                    'username'  => $sms_detail->username,
                    'sernderid' => $sms_detail->senderid,
                    'password'  => $sms_detail->password,
                    'authkey'   => $sms_detail->authkey,
                    'api_id'    => $sms_detail->api_id,
                );
                $this->_CI->load->library('smscountry', $params);
                $this->_CI->smscountry->sendSMS($send_to, $msg);
            } else if ($sms_detail->type == 'text_local') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'hash'     => $sms_detail->password,
                );
                $this->_CI->load->library('textlocalsms', $params);
                $this->_CI->textlocalsms->sendSms(array($to), $msg, $sms_detail->senderid);
            } else if ($sms_detail->type == 'bulk_sms') {
                $to     = $send_to;
                $params = array(
                    'username' => $sms_detail->username,
                    'password' => $sms_detail->password,
                );
                $this->_CI->load->library('bulk_sms_lib', $params);
                $this->_CI->bulk_sms_lib->sendSms(array($to), $msg);
            } else if ($sms_detail->type == 'mobireach') {
                $to     = $send_to;
                $params = array(
                    'authkey'  => $sms_detail->authkey,
                    'senderid' => $sms_detail->senderid,
                    'routeid'  => $sms_detail->api_id,
                );
                $this->_CI->load->library('mobireach_lib', $params);
                $this->_CI->mobireach_lib->sendSms(array($to), $msg);

            } else if ($sms_detail->type == 'nexmo') {
                $to     = $send_to;
                $params = array(
                    'from'       => $sms_detail->senderid,
                    'api_key'    => $sms_detail->api_id,
                    'api_secret' => $sms_detail->authkey,
                );
                $this->_CI->load->library('nexmo_lib', $params);
                $this->_CI->nexmo_lib->sendSms($to, $msg);

            } else if ($sms_detail->type == 'africastalking') {
                $to     = $send_to;
                $params = array(
                    'from'         => $sms_detail->senderid,
                    'api_key'      => $sms_detail->api_id,
                    'api_username' => $sms_detail->username,

                );
                $this->_CI->load->library('africastalking_lib', $params);
                $this->_CI->africastalking_lib->sendSms($to, $msg);
            } else if ($sms_detail->type == 'smseg') {
                $to = $send_to;
                $this->_CI->load->library('smseg_lib');
                $this->_CI->smseg_lib->sendSms($to, $msg);
            } else if ($sms_detail->type == 'custom') {
                $param = array('template_id' => $template_id);
                $this->_CI->load->library('customsms', $param);
                $from    = $sms_detail->contact;
                $to      = $send_to;
                $message = $msg;
                $this->_CI->customsms->sendSMS($to, $message);
            } else {

            }
        }
    }
        return true;
    }

    public function getstudent_apply_leaveContent($sender_details, $template, $sms_detail_type)
    {

        foreach ($sender_details as $key => $value) {

            if ($sms_detail_type == 'msg_nineone') {
                if ($key != 'url') {

                    if (strlen($value) > 30) {
                        $value = substr($value, 0, 29);
                    }
                }
            }

            $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : "";
        }

        return $template;
    }

    public function getmailsubject($id, $subject)
    {
        $student                 = $this->_CI->student_model->get($id);
        $student['student_name'] = $student['firstname'] . ' ' . $student['middlename'] . '' . $student['lastname'];
        foreach ($student as $key => $value) {
        
            $subject = $value ? str_replace('{{' . $key . '}}', $value, $subject) : "";
        }

        return $subject;
    }

}
