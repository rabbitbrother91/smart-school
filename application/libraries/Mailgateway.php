<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mailgateway
{

    private $_CI;

    public function __construct()
    {
        $this->_CI = &get_instance();
        $this->_CI->load->model('setting_model');
        $this->_CI->load->model('studentfeemaster_model');
        $this->_CI->load->model('student_model');
        $this->_CI->load->model('teacher_model');
        $this->_CI->load->model('librarian_model');
        $this->_CI->load->model('accountant_model');
        $this->_CI->load->library('mailer');
        $this->_CI->load->library('customlib');
        $this->_CI->mailer;
        $this->sch_setting = $this->_CI->setting_model->getSetting();
    }

    public function sentMail($sender_details, $template, $subject)
    {
        $msg = $this->getContent($sender_details, $template);
        $send_to = $sender_details->guardian_email;
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function sentRegisterMail($id, $send_to, $template, $subject)
    {
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $msg     = $this->getStudentRegistrationContent($id, $template);
            $subject = $this->getmailsubject($id, $subject);
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function getmailsubject($id, $subject)
    {        
        $student                 = $this->_CI->student_model->get($id);
        $student['student_name'] = $this->_CI->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $this->sch_setting->middlename, $this->sch_setting->lastname);
        foreach ($student as $key => $value) {
            $subject = $value ? str_replace('{{' . $key . '}}', $value, $subject) : $subject;
        }

        return $subject;
    }

    public function sendLoginCredential($chk_mail_sms, $sender_details, $template, $subject)
    {
        $msg     = $this->getLoginCredentialContent($sender_details['credential_for'], $sender_details, $template);
        $send_to = $sender_details['email'];
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function sentGroupAddFeeMail($detail, $template, $subject, $send_to)
    {     
        $invoice_id=[];
        $sub_invoice_id=[];
        
        foreach ($detail['invoice'] as $inv_key => $inv_value) {
            $invoice_id[]=$inv_value['invoice_id'];
            $sub_invoice_id[]=$inv_value['sub_invoice_id'];
        }
        
        $detail['invoice_id']= "(".implode(',', $invoice_id).")";
        $detail['sub_invoice_id']= "(".implode(',', $sub_invoice_id).")";

        $msg     = $this->getGroupAddFeeContent($detail, $template);    

        $subject = $this->getmailsubject($detail['student_id'], $subject);
        
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
        return true;
    }

    public function getGroupAddFeeContent($data, $template)
    {
        $currency_symbol      = $this->_CI->customlib->getSchoolCurrencyFormat();
        $school_name          = $this->sch_setting->name;
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
        $fee_amount += ($record->amount + $record->amount_fine)-$record->amount_discount;
        }
     
        $data['payment_id']            = "(".implode(',', $payment_id).")";
        $data['class']        = $fee->class;
        $data['section']      = $fee->section;
        $data['amount']   = $currency_symbol . $data['amount'];
        $data['fine_amount']   = $currency_symbol . $data['fine_amount'];
        $data['fee_amount']   = $currency_symbol . amountFormat($fee_amount);
        $data['student_name'] = $this->_CI->customlib->getFullName($fee->firstname, $fee->middlename, $fee->lastname, $this->sch_setting->middlename, $this->sch_setting->lastname);
       
        unset($data['invoice']);

        foreach ($data as $key => $value) {
              $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : $template;
        }

        return $template;
    }

    public function sentAddFeeMail($detail, $template, $subject, $send_to)
    {
        $copy = clone $detail;

        $invoice_data           = json_decode($copy->invoice);
        $copy->invoice_id     = $invoice_data->invoice_id;
        $copy->sub_invoice_id = $invoice_data->sub_invoice_id;
        $copy->payment_id = $copy->invoice_id."/".$copy->sub_invoice_id;

        if ($copy->fee_category == "transport") {
            $fee = $this->_CI->studentfeemaster_model->getTransportFeeByInvoice($copy->invoice_id, $copy->sub_invoice_id);
        } else {
            $fee = $this->_CI->studentfeemaster_model->getFeeByInvoice($copy->invoice_id, $copy->sub_invoice_id);
        }
        
        $msg     = $this->getAddFeeContent($copy, $template);
      
        $subject = $this->getmailsubject($fee->std_id, $subject);
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function sentFeeProcessingMail($detail, $template, $subject, $send_to)
    {
        $msg = $this->getFeeProcessingContent($detail, $template);
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function sentExamResultMail($detail, $template, $subject)
    {
        $msg     = $this->getStudentResultContent($detail, $template);
        $subject = $this->getmailsubject($detail['id'], $subject);
           $send_to = $detail['guardian_email'];
       if (!empty($this->_CI->mail_config) && $send_to != "") {
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function sentExamResultMailStudent($detail, $template, $subject)
    {
        $msg     = $this->getStudentResultContent($detail, $template);
        $send_to = $detail['email'];
        $subject = $this->getmailsubject($detail['id'], $subject);
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function sentHomeworkStudentMail($detail, $template, $subject)
    {
        if (!empty($this->_CI->mail_config)) {
            foreach ($detail as $student_key => $student_value) {
                $send_to = $student_key;
                if ($send_to != "") {
                    $msg     = $this->getHomeworkStudentContent($detail[$student_key], $template);
                    $subject1 = $this->getmailsubject($student_value['id'], $subject);
                    $this->_CI->mailer->send_mail($send_to, $subject1, $msg);
                }
            }
        }
    }

    public function sentOnlineexamStudentMail($detail, $template, $subject)
    {
        if (!empty($this->_CI->mail_config)) {
            foreach ($detail as $student_key => $student_value) {
                $send_to = $student_key;
                if ($send_to != "") {
                    $msg = $this->getOnlineexamStudentContent($detail[$student_key], $template);
                    $this->_CI->mailer->send_mail($send_to, $subject, $msg);
                }
            }
        }
    }

    public function sentOnlineadmissionStudentMail($detail, $template, $subject, $send_to)
    {
        if (!empty($this->_CI->mail_config)) {
            if ($send_to != "") {
                $msg = $this->getOnlineadmissionStudentContent($detail, $template);
                $this->_CI->mailer->send_mail($send_to, $subject, $msg);
            }
        }
    }

    public function getOnlineadmissionStudentContent($student_detail, $template)
    {
        foreach ($student_detail as $key => $value) {
              $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : $template;
        }
        return $template;
    }

    public function sentOnlineadmissionFeesMail($detail, $template, $subject)
    {       
        if (!empty($this->_CI->mail_config)) {
            $send_to = $detail['email'];
            if ($send_to != "") {
                $msg = $this->getOnlineadmissionFeesContent($detail, $template);
                $this->_CI->mailer->send_mail($send_to, $subject, $msg);
            }
        }
    }

    public function getOnlineadmissionFeesContent($student_detail, $template)
    {
        $currency_symbol      = $this->_CI->customlib->getSchoolCurrencyFormat();
        $student_detail['paid_amount']   = $currency_symbol . amountFormat($student_detail['paid_amount']);        
        foreach ($student_detail as $key => $value) {            
              $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : $template;
        }

        return $template;
    }

    public function sentOnlineClassStudentMail($detail, $template)
    {
        if (!empty($this->_CI->mail_config)) {
            foreach ($detail as $student_key => $student_value) {
                $send_to = $student_key;
                if ($send_to != "") {
                    $msg = $this->getOnlineClassStudentContent($detail[$student_key], $template);
                    $subject = "Online Class";
                    $this->_CI->mailer->send_mail($send_to, $subject, $msg);
                }
            }
        }
    }

    public function sentOnlineMeetingStaffMail($detail, $template)
    {
        if (!empty($this->_CI->mail_config)) {
            foreach ($detail as $staff_key => $staff_value) {
                $send_to = $staff_key;
                if ($send_to != "") {
                    $msg = $this->getOnlineMeetingStaffContent($detail[$staff_key], $template);

                    $subject = "Online Meeting";
                    $this->_CI->mailer->send_mail($send_to, $subject, $msg);
                }
            }
        }
    }

    public function sentAbsentStudentMail($detail, $template, $subject, $send_to)
    {
        $msg     = $this->getAbsentStudentContent($detail, $template);
        $subject = $this->getmailsubject($detail['id'], $subject);
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function getAddFeeContent($data, $template)
    {
        $currency_symbol      = $this->_CI->customlib->getSchoolCurrencyFormat();
        $school_name          = $this->sch_setting->name;
        $invoice_data         = json_decode($data->invoice);
        $data->invoice_id     = $invoice_data->invoice_id;
        $data->sub_invoice_id = $invoice_data->sub_invoice_id;
        if ($data->fee_category == "transport") {

            $fee = $this->_CI->studentfeemaster_model->getTransportFeeByInvoice($data->invoice_id, $data->sub_invoice_id);

        } else {

            $fee = $this->_CI->studentfeemaster_model->getFeeByInvoice($data->invoice_id, $data->sub_invoice_id);
            
        }

        $a          = json_decode($fee->amount_detail);
        $record     = $a->{$data->sub_invoice_id};
        $fee_amount = ($record->amount + $record->amount_fine)-$record->amount_discount;
        $data->class        = $fee->class;
        $data->section      = $fee->section;
        $data->fee_amount   = $currency_symbol . amountFormat($fee_amount);
        $data->amount   = $currency_symbol . $data->amount;
        $data->fine_amount   = $currency_symbol . $data->fine_amount;
        $data->student_name = $this->_CI->customlib->getFullName($fee->firstname, $fee->middlename, $fee->lastname, $this->sch_setting->middlename, $this->sch_setting->lastname);

        foreach ($data as $key => $value) {
              $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : $template;
        }

        return $template;
    }

    public function getFeeProcessingContent($data, $template)
    {
        $currency_symbol  = $this->sch_setting->currency_symbol;
        $fee_amount       = number_format((float)(($data->fee_amount)), 2, '.', ',');
        $data->fee_amount = $currency_symbol . $fee_amount;
        foreach ($data as $key => $value) {
              $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : $template;
        }

        return $template;
    }

    public function getHomeworkStudentContent($student_detail, $template)
    {    
        foreach ($student_detail as $key => $value) {
              $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : $template;
        }
        return $template;
    }

    public function getOnlineexamStudentContent($student_detail, $template)
    {
        foreach ($student_detail as $key => $value) {
              $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : $template;
        }
        return $template;
    }

    public function getOnlineClassStudentContent($student_detail, $template)
    {
        foreach ($student_detail as $key => $value) {
              $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : $template;
        }
        return $template;
    }

    public function getOnlineMeetingStaffContent($student_detail, $template)
    {
        foreach ($student_detail as $key => $value) {
              $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : $template;
        }
        return $template;
    }

    public function getAbsentStudentContent($student_detail, $template)
    {
        $session_name                           = $this->_CI->setting_model->getCurrentSessionName();
        $student_detail['current_session_name'] = $session_name;
        foreach ($student_detail as $key => $value) {
              $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : $template;
        }

        return $template;
    }

    public function getStudentRegistrationContent($id, $template)
    {
        $session_name                    = $this->_CI->setting_model->getCurrentSessionName();
        $student                         = $this->_CI->student_model->get($id);
        $student['current_session_name'] = $session_name;
        $student['student_name']         = $this->_CI->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $this->sch_setting->middlename, $this->sch_setting->lastname);
        foreach ($student as $key => $value) {
            
            $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : $template;
            //str_replace('{{' . $key . '}}', $value, $template);            
            
        }

        return $template;
    }

    public function getLoginCredentialContent($credential_for, $sender_details, $template)
    {
        if ($credential_for == "student") {
            $student                        = $this->_CI->student_model->get($sender_details['id']);
            $sender_details['url']          = site_url('site/userlogin');
            $sender_details['display_name'] = $this->_CI->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $this->sch_setting->middlename, $this->sch_setting->lastname);
            $sender_details['display_name'] = $student['firstname'] . ' ' . $student['lastname'];
        } elseif ($credential_for == "parent") {
            $parent = $this->_CI->student_model->get($sender_details['id']);
            $sender_details['url']          = site_url('site/userlogin');
            $sender_details['display_name'] = $parent['guardian_name'];
        } elseif ($credential_for == "staff") {
            $staff                          = $this->_CI->staff_model->get($sender_details['id']);
            $sender_details['url']          = site_url('site/login');
            $sender_details['display_name'] = $staff['name'];
        }

        foreach ($sender_details as $key => $value) {
              $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : $template;
        }

        return $template;
    }

    public function getStudentResultContent($student_result_detail, $template)
    {
        foreach ($student_result_detail as $key => $value) {
            if ($key != 'contact_numbers') {
                  $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : $template;
            }
        }
        return $template;
    }

    public function getContent($sender_details, $template)
    {
        foreach ($sender_details as $key => $value) {
            $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : $template;
        }
        return $template;
    }

    public function sentMailToAlumni($sender_details,$files)
    {        
        $send_to = $sender_details['email'];
        $subject = $sender_details['subject'];
        $msg     = "Event From " . $sender_details['from_date'] . " To " . $sender_details['to_date'] . "<br><br>" .
            $sender_details['body'];

        if ($send_to != "") {
            $this->_CI->mailer->compose_mail($send_to, $subject, $msg, $files);
        }
    }

    public function sendStudentLoginCredential($chk_mail_sms, $sender_details, $template, $subject)
    {
        $msg     = $this->getLoginCredentialContent($sender_details['credential_for'], $sender_details, $template);
        $send_to = $sender_details['email'];
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function sendStaffLoginCredential($chk_mail_sms, $sender_details, $template, $subject)
    {
        $msg     = $this->getLoginCredentialContent($sender_details['credential_for'], $sender_details, $template);
        $send_to = $sender_details['email'];
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function student_apply_leave($chk_mail_sms, $sender_details, $template, $subject, $file)
    {        
        $msg     = $this->getstudent_apply_leaveContent($sender_details, $template);
        $subject = $this->getmailsubject($sender_details['id'], $subject);
        $send_to = $sender_details['email'];
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $this->_CI->mailer->send_mail($send_to, $subject, $msg, $file);
        }
    }

    public function getstudent_apply_leaveContent($sender_details, $template)
    {
        foreach ($sender_details as $key => $value) {
              $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : $template;
        }
        return $template;
    }
    
    public function sentEventReminder($sender_details, $template, $subject)
    {
        $msg     = $this->getContent($sender_details, $template);
        $send_to = $sender_details->guardian_email;
        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

    public function sendEailEventReminder($sender_details, $subject, $message)
    {
        foreach ($sender_details['event_email_list'] as $key => $sent_to) {
            $this->_CI->mailer->send_mail($sent_to, $subject, $message);
        }
    }

    public function sendpdfExamMarksheet($chk_mail_sms, $sender_details, $template, $subject, $file)
    {
        if (!empty($this->_CI->mail_config)) {
           
            $send_to   = $sender_details['email'];
            $file_name = $sender_details['student_name'] . '_' . $sender_details['admission_no'];
            
            foreach ($sender_details as $key => $value) {
                $template =  $value ? str_replace('{{' . $key . '}}', $value, $template) : $template ;
            }
       
            if ($send_to != "") {                
                $msg = $this->getpdfExamMarksheetContent($sender_details, $template);         
                $this->_CI->mailer->send_mail_marksheet($send_to, $subject, $msg, $file, $file_name);
            }
        }
    }

    public function sendpdfExamMarksheetGuardian($chk_mail_sms, $sender_details, $template, $subject, $file)
    {
        if (!empty($this->_CI->mail_config)) {

            $send_to   = $sender_details['guardian_email'];
            $file_name = $sender_details['student_name'] . '_' . $sender_details['admission_no'];

            foreach ($sender_details as $key => $value) {
                $subject = $value ? str_replace('{{' . $key . '}}', $value, $subject) : $subject;
            }

            if ($send_to != "") {
                $msg = $this->getpdfExamMarksheetContent($sender_details, $template);
                $this->_CI->mailer->send_mail_marksheet($send_to, $subject, $msg, $file, $file_name);
            }
        }
    }

    public function getpdfExamMarksheetContent($student_detail, $template)
    {
        foreach ($student_detail as $key => $value) {
              $template = $value ? str_replace('{{' . $key . '}}', $value, $template) : $template;
        }

        return $template;
    }

}
