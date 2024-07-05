<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mailsmsconf
{

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->helper('language');
        $this->CI->config->load("mailsms");
        $this->CI->load->library('smsgateway');
        $this->CI->load->library('mailgateway');
        $this->CI->load->model('examresult_model');
        $this->CI->load->model('studentsession_model');
        $this->CI->load->model('student_model');
        $this->CI->load->model('apply_leave_model');
        $this->config_mailsms = $this->CI->config->item('mailsms');
        $this->sch_setting    = $this->CI->setting_model->getSetting();
    }

    public function mailsms($send_for, $sender_details, $date = null, $exam_schedule_array = null, $file = null)
    {

        $send_for        = $this->config_mailsms[$send_for];
        $chk_mail_sms    = $this->CI->customlib->sendMailSMS($send_for);
        $sms_detail      = $this->CI->smsconfig_model->getActiveSMS();
        $emails          = array();
        $contact_numbers = array();

        if (is_object($sender_details)) {
            if (isset($sender_details->student_session_id)) {
                $student_session_id = $sender_details->student_session_id;
            }

        } else {
            if (isset($sender_details['student_session_id'])) {
                $student_session_id = $sender_details['student_session_id'];
            }
        }

        if (isset($student_session_id) && !empty($student_session_id)) {
            $recipient_data = $this->CI->studentsession_model->searchStudentsBySession($student_session_id);
            $pushnotification_key=array();
            if ($chk_mail_sms['student_recipient']) {
                $emails[]          = $recipient_data['email'];
                $contact_numbers[] = $recipient_data['mobileno'];
                $pushnotification_key['student']=$recipient_data['app_key'];
            }

            if ($chk_mail_sms['guardian_recipient']) {
                $emails[]          = $recipient_data['guardian_email'];
                $contact_numbers[] = $recipient_data['guardian_phone'];
                $pushnotification_key['parent']=$recipient_data['parent_app_key'];
            }
        }

        if (!empty($chk_mail_sms)) {
            if ($send_for == "student_admission") {

                if ($chk_mail_sms['mail'] && $chk_mail_sms['template'] != "") {
                    if (!empty($emails)) {
                        foreach ($emails as $key => $emailvalue) {
                            $this->CI->mailgateway->sentRegisterMail($sender_details['student_id'], $emailvalue, $chk_mail_sms['template'], $chk_mail_sms['subject']);
                        }
                    }
                }

                if ($chk_mail_sms['sms'] && $chk_mail_sms['template'] != "" && !empty($sms_detail)) {
                    if (!empty($contact_numbers)) {
                        foreach ($contact_numbers as $key => $contactvalue) {
                            $this->CI->smsgateway->sentRegisterSMS($sender_details['student_id'], $contactvalue, $chk_mail_sms['template'], $chk_mail_sms['template_id']);
                        }
                    }
                }

            } elseif ($send_for == "exam_result") {

                $this->sendResult($chk_mail_sms, $sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject'], $chk_mail_sms['template_id']);

            } elseif ($send_for == "fee_submission" && (is_array($sender_details) && $sender_details['send_type'] == "group")) {

                if ($chk_mail_sms['mail'] && $chk_mail_sms['template'] != "") {
                    foreach ($emails as $key => $emailsvalue) {
                     
                        $this->CI->mailgateway->sentGroupAddFeeMail($sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject'], $emailsvalue);
                    }
                }
                
            if ($chk_mail_sms['sms'] && $chk_mail_sms['template'] != "" && !empty($sms_detail)) {
                    foreach ($contact_numbers as $key => $contact_numbersvalue) {
                        $this->CI->smsgateway->sentAddFeeSMS($sender_details, $chk_mail_sms['template'], $chk_mail_sms['template_id'], $contact_numbersvalue);
                    }
                }

                if ($chk_mail_sms['notification'] && $chk_mail_sms['template'] != "") {
              
                 foreach($pushnotification_key as $pkey=>$pvalue){
                    $sender_details['app_key']=$pvalue;
                    $this->CI->smsgateway->sentAddFeeNotification($sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject']);
                 }
                    
                }

            } elseif ($send_for == "fee_submission") {
               
                $sender_details->amount      = amountFormat($sender_details->amount);
                $sender_details->fine_amount      = amountFormat($sender_details->fine_amount);
                if ($chk_mail_sms['mail'] && $chk_mail_sms['template'] != "") {
                    foreach ($emails as $key => $emailsvalue) {

                        $this->CI->mailgateway->sentAddFeeMail($sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject'], $emailsvalue);
                    }
                }

                if ($chk_mail_sms['sms'] && $chk_mail_sms['template'] != "" && !empty($sms_detail)) {
                    foreach ($contact_numbers as $key => $contact_numbersvalue) {
                        $this->CI->smsgateway->sentAddFeeSMS($sender_details, $chk_mail_sms['template'], $chk_mail_sms['template_id'], $contact_numbersvalue);
                    }
                }

                if ($chk_mail_sms['notification'] && $chk_mail_sms['template'] != "") {
                  
                     $type=gettype($sender_details);

                 foreach($pushnotification_key as $pkey=>$pvalue){
                    if($type=='object'){
                    $sender_details->app_key=$pvalue;
                    }else{
                    $sender_details['app_key']=$pvalue;        
                    }
                    
                    $this->CI->smsgateway->sentAddFeeNotification($sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject']);
                }
                }
            } elseif ($send_for == "fee_processing") {

                if ($chk_mail_sms['mail'] && $chk_mail_sms['template'] != "" && $chk_mail_sms['student_recipient']) {
                    $this->CI->mailgateway->sentFeeProcessingMail($sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject'], $sender_details->email);
                }

                if ($chk_mail_sms['mail'] && $chk_mail_sms['template'] != "" && $chk_mail_sms['guardian_recipient']) {
                    $this->CI->mailgateway->sentFeeProcessingMail($sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject'], $sender_details->guardian_email);
                }

                if ($chk_mail_sms['sms'] && $chk_mail_sms['template'] != "" && !empty($sms_detail) && $chk_mail_sms['student_recipient']) {
                    $this->CI->smsgateway->sentFeeProcessingSMS($sender_details, $chk_mail_sms['template'], $chk_mail_sms['template_id'], $sender_details->mobileno);
                }

                if ($chk_mail_sms['sms'] && $chk_mail_sms['template'] != "" && !empty($sms_detail) && $chk_mail_sms['guardian_recipient']) {
                    $this->CI->smsgateway->sentFeeProcessingSMS($sender_details, $chk_mail_sms['template'], $chk_mail_sms['template_id'], $sender_details->guardian_phone);
                }

                if ($chk_mail_sms['notification'] && $chk_mail_sms['template'] != "" && $chk_mail_sms['student_recipient']) {
                    $sender_details->app_keys=$sender_details->app_key;
                    $this->CI->smsgateway->sentFeeProcessingNotification($sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject']);
                }
                
                if ($chk_mail_sms['notification'] && $chk_mail_sms['template'] != "" && $chk_mail_sms['guardian_recipient']) {
                    $sender_details->app_keys=$sender_details->parent_app_key;
                    $this->CI->smsgateway->sentFeeProcessingNotification($sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject']);
                }
                
            } elseif ($send_for == "absent_attendence") {

                $this->sendAbsentAttendance($chk_mail_sms, $sender_details, $date, $chk_mail_sms['template'], $exam_schedule_array, $chk_mail_sms['subject'], $chk_mail_sms['template_id']);
                
            } elseif ($send_for == "fees_reminder") {

                if ($chk_mail_sms['mail'] && $chk_mail_sms['template'] != "") {
                     foreach ($emails as $key => $emailsvalue) {
                        $sender_details->guardian_email=$emailsvalue;
                    $this->CI->mailgateway->sentMail($sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject']);
                }
                } 

                if ($chk_mail_sms['sms'] && $chk_mail_sms['template'] != "" && !empty($sms_detail)) {
                     foreach ($contact_numbers as $key => $contact_numbersvalue) {
                    $this->CI->smsgateway->sendSMS($contact_numbersvalue, $sender_details, $chk_mail_sms['template_id'], $chk_mail_sms['template']);
                }
                }

                if ($chk_mail_sms['notification'] && $chk_mail_sms['template'] != "" && $chk_mail_sms['student_recipient']) {
                    $this->CI->smsgateway->sentNotification($sender_details->app_key, $sender_details, $chk_mail_sms['subject'], $chk_mail_sms['template']);
                }

                if ($chk_mail_sms['notification'] && $chk_mail_sms['template'] != "" && $chk_mail_sms['guardian_recipient']) {
                    $this->CI->smsgateway->sentNotification($sender_details->parent_app_key, $sender_details, $chk_mail_sms['subject'], $chk_mail_sms['template']);
                }
            } elseif ($send_for == "homework") {
             

                $this->sendHomework($chk_mail_sms, $sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject'], $chk_mail_sms['template_id']);
                
            } elseif ($send_for == "online_examination_publish_exam") {

                $this->sendOnlineexam($chk_mail_sms, $sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject'], $chk_mail_sms['template_id']);
                
            } elseif ($send_for == "online_examination_publish_result") {

                $this->sendOnlineexam($chk_mail_sms, $sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject'], $chk_mail_sms['template_id']);
                
            } elseif ($send_for == "forgot_password") {
                $school_name                   = $this->CI->setting_model->getCurrentSchoolName();
                $sender_details['school_name'] = $school_name;

                $msg = ($this->getForgotPasswordContent($sender_details, $chk_mail_sms['template']));

                if ($chk_mail_sms['mail'] && $chk_mail_sms['template'] != "" && $chk_mail_sms['student_recipient']) {
                    if (!empty($sender_details['email'])) {
                        $subject = $chk_mail_sms['subject'];
                        $this->CI->mailer->send_mail($sender_details['email'], $subject, $msg);
                    }
                }

                if ($chk_mail_sms['mail'] && $chk_mail_sms['template'] != "" && $chk_mail_sms['guardian_recipient']) {
                    if (!empty($sender_details['guardian_email'])) {
                        $subject = $chk_mail_sms['subject'];
                        $this->CI->mailer->send_mail($sender_details['guardian_email'], $subject, $msg);
                    }
                }

                if ($chk_mail_sms['mail'] && $chk_mail_sms['template'] != "" && $chk_mail_sms['staff_recipient']) {
                    if (!empty($sender_details['staff_email'])) {
                        $subject = $chk_mail_sms['subject'];
                        $this->CI->mailer->send_mail($sender_details['staff_email'], $subject, $msg);
                    }
                }

            } elseif ($send_for == "online_admission_form_submission") {
		if(isset($recipient_data) && !empty($recipient_data)){
		$sender_details['parent_app_key']=$recipient_data['parent_app_key'];
                $sender_details['app_key']=$recipient_data['app_key'];
		}
                
                $this->sendOnlineadmission($chk_mail_sms, $sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject'], $chk_mail_sms['template_id']);
				
            } elseif ($send_for == "online_admission_fees_submission") {
		if(isset($recipient_data) && !empty($recipient_data)){
$sender_details['parent_app_key']=$recipient_data['parent_app_key'];
                $sender_details['app_key']=$recipient_data['app_key'];
}
                
                $this->sendOnlineadmissionFees($chk_mail_sms, $sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject'], $chk_mail_sms['template_id']);

            } elseif ($send_for == "online_admission_fees_processing") {
                $sender_details['parent_app_key']=$recipient_data['parent_app_key'];
                $sender_details['app_key']=$recipient_data['app_key'];
                $this->sendOnlineadmissionFees($chk_mail_sms, $sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject'], $chk_mail_sms['template_id']);

            } elseif ($send_for == "student_login_credential") {

                if ($chk_mail_sms['mail'] && $chk_mail_sms['template'] != "") {

                    $this->CI->mailgateway->sendStudentLoginCredential($chk_mail_sms, $sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject']);
                }
                if ($chk_mail_sms['sms'] && $chk_mail_sms['template'] != "" && !empty($sms_detail)) {
                   
                    foreach ($contact_numbers as $key => $contact_numbersvalue) {
                        $sender_details['contact_no']=$contact_numbersvalue;
                    $this->CI->smsgateway->sendStudentLoginCredential($chk_mail_sms, $sender_details, $chk_mail_sms['template'], $chk_mail_sms['template_id']);
                }
                }
            } elseif ($send_for == "staff_login_credential" && $chk_mail_sms['staff_recipient']) {

                if ($chk_mail_sms['mail'] && $chk_mail_sms['template'] != "") {

                    $this->CI->mailgateway->sendStaffLoginCredential($chk_mail_sms, $sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject']);
                }
                if ($chk_mail_sms['sms'] && $chk_mail_sms['template'] != "" && !empty($sms_detail)) {
                    $this->CI->smsgateway->sendStaffLoginCredential($chk_mail_sms, $sender_details, $chk_mail_sms['template'], $chk_mail_sms['template_id']);
                }
            } elseif ($send_for == "student_apply_leave") {

                $student_data = $this->CI->studentsession_model->searchStudentsBySession($sender_details['student_session_id']);             

                $sender_details['class']        = $student_data['class'];
                $sender_details['section']      = $student_data['section'];
                $sender_details['student_name'] = $this->CI->customlib->getFullName($student_data['firstname'], $student_data['middlename'], $student_data['lastname'], $this->sch_setting->middlename, $this->sch_setting->lastname);
                $sender_details['id']           = $student_data['student_id'];

                if($chk_mail_sms['staff_recipient']){
                    
                        $stafflist = $this->CI->apply_leave_model->getclassteacherbyclasssection($sender_details['class_id'], $sender_details['section_id']);
                        
                    if ($chk_mail_sms['mail'] && $chk_mail_sms['template'] != "" ) {
                        foreach ($stafflist as $key => $value) {
                            $sender_details['email'] = $value['email'];
                            $this->CI->mailgateway->student_apply_leave($chk_mail_sms, $sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject'], $file);
                        }
                    }
                    
                    if ($chk_mail_sms['sms'] && $chk_mail_sms['template'] != "" ) {
                        foreach ($stafflist as $key => $value) {
                            $sender_details['contact_no'] = $value['contact_no'];
                           
                            $this->CI->smsgateway->student_apply_leave($chk_mail_sms, $sender_details, $chk_mail_sms['template'], $chk_mail_sms['template_id']);
                        }
                    }                    
                }

                if ($chk_mail_sms['mail'] && $chk_mail_sms['template'] != "" && $chk_mail_sms['guardian_recipient']) {
                    if (!empty($emails)) {
                        foreach ($emails as $key => $emailvalue) {
                            $sender_details['email'] = $emailvalue;
                            $this->CI->mailgateway->student_apply_leave($chk_mail_sms, $sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject'], $file);
                        }
                    }
                }
                    
                if (!empty($sms_detail)) {
                    if ($chk_mail_sms['sms'] && $chk_mail_sms['template'] != "" && $chk_mail_sms['guardian_recipient']) {
                        if (!empty($contact_numbers)) {
                            foreach ($contact_numbers as $key => $contactvalue) {
                                $sender_details['contact_no'] = $contactvalue;
                                $this->CI->smsgateway->student_apply_leave($chk_mail_sms, $sender_details, $chk_mail_sms['template'], $chk_mail_sms['template_id']);
                            }
                        }
                    }
                }

            } elseif ($send_for == "email_pdf_exam_marksheet") {

                if ($chk_mail_sms['mail'] && $chk_mail_sms['template'] != "" && $chk_mail_sms['student_recipient']) {
                    $this->CI->mailgateway->sendpdfExamMarksheet($chk_mail_sms, $sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject'], $file);

                }

                if ($chk_mail_sms['mail'] && $chk_mail_sms['template'] != "" && $chk_mail_sms['guardian_recipient']) {

                    $this->CI->mailgateway->sendpdfExamMarksheetGuardian($chk_mail_sms, $sender_details, $chk_mail_sms['template'], $chk_mail_sms['subject'], $file);

                }

            } else {

            }
        }
    }

    public function bulkmailsms($send_for, $sender_details) {
        
        $send_for = $this->config_mailsms[$send_for];

        $chk_mail_sms = $this->CI->customlib->sendMailSMS($send_for);
        $sms_detail = $this->CI->smsconfig_model->getActiveSMS();       
       
         
            if ($send_for == "student_admission") {
                if ($chk_mail_sms['mail'] && $chk_mail_sms['template'] != "") {
                    $this->CI->mailgateway->sentRegisterMail($sender_details['student_id'], $sender_details['email'], $chk_mail_sms['template'], $chk_mail_sms['subject']);
                }
                if ($chk_mail_sms['sms'] && $chk_mail_sms['template'] != "" && !empty($sms_detail)) {
                  
                    $this->CI->smsgateway->sentRegisterSMS($sender_details['student_id'], $sender_details['contact_no'], $chk_mail_sms['template'],$chk_mail_sms['template_id']);
                }
            }  elseif ($send_for == "student_login_credential") {

                if ($chk_mail_sms['mail'] && $chk_mail_sms['template'] != "") {

                    $this->CI->mailgateway->sendLoginCredential($chk_mail_sms, $sender_details, $chk_mail_sms['template'] , $chk_mail_sms['subject']);
                }
                if ($chk_mail_sms['sms'] && $chk_mail_sms['template'] != "" && !empty($sms_detail)) {
                    $this->CI->smsgateway->sendLoginCredential($chk_mail_sms, $sender_details, $chk_mail_sms['template'],$chk_mail_sms['template_id']);
                }
            } 
         
    }

    public function mailsmsalumnistudent($sender_details,$template_id,$files)
    {
        if ($sender_details['email_value'] == 'yes') {
            $this->CI->mailgateway->sentMailToAlumni($sender_details,$files);
        }
        if ($sender_details['sms_value'] == 'yes') {
            $this->CI->smsgateway->sentSMSToAlumni($sender_details,$template_id);
        }
    }

    public function sendResult($chk_mail_sms, $exam_result, $template, $subject, $template_id)
    {
        if ($chk_mail_sms['mail'] or $chk_mail_sms['sms'] or $chk_mail_sms['notification']) {
            $sms_detail = $this->CI->smsconfig_model->getActiveSMS();
            $exam=$exam_result['exam'];
            if (!empty($exam_result['exam_result'])) {
                foreach ($exam_result['exam_result'] as $res_key => $res_value) {

                    $detail = array(
                        'student_name'   => $this->CI->customlib->getFullName($res_value->firstname, $res_value->middlename, $res_value->lastname, $this->sch_setting->middlename, $this->sch_setting->lastname),
                    'exam_roll_no'   => ($exam->use_exam_roll_no) ? $res_value->exam_roll_no : $res_value->roll_no,
                    'admission_no'   => $res_value->admission_no,
                    'email'          => $res_value->email,
                    'guardian_email' => $res_value->guardian_email,
                    'exam'           => $exam_result['exam']->exam,                   
                    'id'             => $res_value->student_id,
                    );

                    $contact_numbers = array();
                    if($chk_mail_sms['student_recipient']) {
                        if (!empty($res_value->mobileno)) {
                            $contact_numbers[] = $res_value->mobileno;
                        }
                        $detail['app_key']      = $res_value->app_key;
                    }else{
                         $detail['app_key']      = "";
                    }
                    if($chk_mail_sms['guardian_recipient']) {
                        if (!empty($res_value->guardian_phone)) {
                            $contact_numbers[] = $res_value->guardian_phone;
                        }
                        $detail['parent_app_key'] = $res_value->parent_app_key;
                    }else{
                        $detail['parent_app_key'] = "";
                    }

                    $detail['contact_numbers'] = $contact_numbers;

                    if ($chk_mail_sms['mail'] && $chk_mail_sms['guardian_recipient']) {
                        $this->CI->mailgateway->sentExamResultMail($detail, $template, $subject);
                    }
                    if ($chk_mail_sms['mail'] && $chk_mail_sms['student_recipient']) {
                        $this->CI->mailgateway->sentExamResultMailStudent($detail, $template, $subject);
                    }
                    if ($chk_mail_sms['sms'] && !empty($sms_detail)) {
                       $this->CI->smsgateway->sentExamResultSMS($detail, $template, $template_id);
                    }
                   
                  
                    if ($chk_mail_sms['notification'] && ($detail['parent_app_key'] != "" || $detail['app_key'] != "")) {
                        $this->CI->smsgateway->sentExamResultNotification($detail, $template, $subject);
                    }


                }
            }
        }
    }

    public function sendAbsentAttendance($chk_mail_sms, $student_session_array, $date, $template, $subject_attendence, $subject, $template_id)
    {
        if ($chk_mail_sms['mail'] or $chk_mail_sms['sms'] or $chk_mail_sms['notification']) {
            $student_result = $this->getAbsentStudentlist($student_session_array);

            $sms_detail = $this->CI->smsconfig_model->getActiveSMS();
            if (!empty($student_result)) {

                foreach ($student_result as $student_result_k => $student_result_v) {
                    $detail = array(
                        'date'                => $date,
                        'parent_app_key'      => $student_result_v->parent_app_key,
                        'app_key'      => $student_result_v->app_key,
                        'mobileno'            => $student_result_v->mobileno,
                        'email'               => $student_result_v->email,
                        'father_name'         => $student_result_v->father_name,
                        'father_phone'        => $student_result_v->father_phone,
                        'father_occupation'   => $student_result_v->father_occupation,
                        'mother_name'         => $student_result_v->mother_name,
                        'mother_phone'        => $student_result_v->mother_phone,
                        'guardian_name'       => $student_result_v->guardian_name,
                        'guardian_phone'      => $student_result_v->guardian_phone,
                        'guardian_occupation' => $student_result_v->guardian_occupation,
                        'guardian_email'      => $student_result_v->guardian_email,
                        'id'                  => $student_result_v->id,
                    );

                    if (isset($subject_attendence) && !empty($subject_attendence)) {
                        $detail['time_from']    = $subject_attendence->time_from;
                        $detail['time_to']      = $subject_attendence->time_to;
                        $detail['subject_name'] = $subject_attendence->name;
                        $detail['subject_code'] = $subject_attendence->code;
                        $detail['subject_type'] = $subject_attendence->type;
                    }

                    $detail['student_name'] = $this->CI->customlib->getFullName($student_result_v->firstname, $student_result_v->middlename, $student_result_v->lastname, $this->sch_setting->middlename, $this->sch_setting->lastname);

                    if ($chk_mail_sms['mail'] && $chk_mail_sms['student_recipient']) {
                        $this->CI->mailgateway->sentAbsentStudentMail($detail, $template, $subject, $detail['email']);
                    }

                    if ($chk_mail_sms['mail'] && $chk_mail_sms['guardian_recipient']) {
                        $this->CI->mailgateway->sentAbsentStudentMail($detail, $template, $subject, $detail['guardian_email']);
                    }

                    if ($chk_mail_sms['sms'] && !empty($sms_detail) && $chk_mail_sms['student_recipient']) {
                        $this->CI->smsgateway->sentAbsentStudentSMS($detail, $template, $template_id, $detail['mobileno']);
                    }

                    if ($chk_mail_sms['sms'] && !empty($sms_detail) && $chk_mail_sms['guardian_recipient']) {
                        $this->CI->smsgateway->sentAbsentStudentSMS($detail, $template, $template_id, $detail['guardian_phone']);
                    }

                    if ($chk_mail_sms['notification'] && $chk_mail_sms['student_recipient']) {
                        
                        $this->CI->smsgateway->sentAbsentStudentNotification($detail, $template, $subject);
                    }
                    if ($chk_mail_sms['notification'] && $chk_mail_sms['guardian_recipient']) {
                         $detail['app_key']=$detail['parent_app_key'];
                        $this->CI->smsgateway->sentAbsentStudentNotification($detail, $template, $subject);
                    }
                }
            }
        }
    }

    public function getAbsentStudentlist($student_session_array)
    {
        $result = $this->CI->student_model->getStudentListBYStudentsessionID($student_session_array);
        if (!empty($result)) {
            return $result;
        }
        return false;
    }

    public function sendHomework($chk_mail_sms, $student_details, $template, $email_subject, $template_id)
    {
        $student_sms_list          = array();
        $student_email_list        = array();
        $student_notification_list = array();
        if ($chk_mail_sms['mail'] or $chk_mail_sms['sms'] or $chk_mail_sms['notification']) {
            $class_id      = ($student_details['class_id']);
            $section_id    = ($student_details['section_id']);
            $homework_date = $student_details['homework_date'];
            $submit_date   = $student_details['submit_date'];
            $subject       = $student_details['subject'];
            $student_list  = $this->CI->student_model->getStudentByClassSectionID($class_id, $section_id);
            $sms_detail    = $this->CI->smsconfig_model->getActiveSMS();
            if (!empty($student_list)) {

                foreach ($student_list as $student_key => $student_value) {

                    if ($student_value['app_key'] != "" && $chk_mail_sms['student_recipient']) {
                        $student_notification_list[] = array(
                            'app_key'       => $student_value['app_key'],
                            'class'         => $student_value['class'],
                            'section'       => $student_value['section'],
                            'homework_date' => $homework_date,
                            'submit_date'   => $submit_date,
                            'subject'       => $subject,
                            'admission_no'  => $student_value['admission_no'],
                            'student_name'  => $this->CI->customlib->getFullName($student_value['firstname'], $student_value['middlename'], $student_value['lastname'], $this->sch_setting->middlename, $this->sch_setting->lastname),
                        );
                    }
                    if ($student_value['parent_app_key'] != "" && $chk_mail_sms['guardian_recipient']) {
                        $student_notification_list[] = array(
                            'app_key'       => $student_value['parent_app_key'],
                            'class'         => $student_value['class'],
                            'section'       => $student_value['section'],
                            'homework_date' => $homework_date,
                            'submit_date'   => $submit_date,
                            'subject'       => $subject,
                            'admission_no'  => $student_value['admission_no'],
                            'student_name'  => $this->CI->customlib->getFullName($student_value['firstname'], $student_value['middlename'], $student_value['lastname'], $this->sch_setting->middlename, $this->sch_setting->lastname),
                        );
                    }
                    if ($student_value['email'] != "" && $chk_mail_sms['student_recipient']) {
                        $student_email_list[$student_value['email']] = array(
                            'class'         => $student_value['class'],
                            'section'       => $student_value['section'],
                            'homework_date' => $homework_date,
                            'submit_date'   => $submit_date,
                            'subject'       => $subject,
                            'admission_no'  => $student_value['admission_no'],
                            'student_name'  => $this->CI->customlib->getFullName($student_value['firstname'], $student_value['middlename'], $student_value['lastname'], $this->sch_setting->middlename, $this->sch_setting->lastname),
                            'id'            => $student_value['id'],
                        );
                    }
                    if ($student_value['guardian_email'] != "" && $chk_mail_sms['guardian_recipient']) {
                        $student_email_list[$student_value['guardian_email']] = array(
                            'class'         => $student_value['class'],
                            'section'       => $student_value['section'],
                            'homework_date' => $homework_date,
                            'submit_date'   => $submit_date,
                            'subject'       => $subject,
                            'admission_no'  => $student_value['admission_no'],
                            'student_name'  => $this->CI->customlib->getFullName($student_value['firstname'], $student_value['middlename'], $student_value['lastname'], $this->sch_setting->middlename, $this->sch_setting->lastname),
                            'id'            => $student_value['id'],
                        );
                    }
                    if ($student_value['mobileno'] != "" && $chk_mail_sms['student_recipient']) {
                        $student_sms_list[$student_value['mobileno']] = array(
                            'class'         => $student_value['class'],
                            'section'       => $student_value['section'],
                            'homework_date' => $homework_date,
                            'submit_date'   => $submit_date,
                            'subject'       => $subject,
                            'admission_no'  => $student_value['admission_no'],
                            'student_name'  => $this->CI->customlib->getFullName($student_value['firstname'], $student_value['middlename'], $student_value['lastname'], $this->sch_setting->middlename, $this->sch_setting->lastname),
                        );
                    }
                    if ($student_value['guardian_phone'] != "" && $chk_mail_sms['guardian_recipient']) {
                        $student_sms_list[$student_value['guardian_phone']] = array(
                            'class'         => $student_value['class'],
                            'section'       => $student_value['section'],
                            'homework_date' => $homework_date,
                            'submit_date'   => $submit_date,
                            'subject'       => $subject,
                            'admission_no'  => $student_value['admission_no'],
                            'student_name'  => $this->CI->customlib->getFullName($student_value['firstname'], $student_value['middlename'], $student_value['lastname'], $this->sch_setting->middlename, $this->sch_setting->lastname),
                        );
                    }
                }
             
                if ($chk_mail_sms['mail']) {
                    if ($student_email_list) {
                        $this->CI->mailgateway->sentHomeworkStudentMail($student_email_list, $template, $email_subject);
                    }
                }
              

                if ($chk_mail_sms['sms'] && !empty($sms_detail)) {

                    if ($student_sms_list) {
                        $this->CI->smsgateway->sentHomeworkStudentSMS($student_sms_list, $template, $template_id);
                    }
                }

                if ($chk_mail_sms['notification']) {

                    if (!empty($student_notification_list)) {
                        $this->CI->smsgateway->sentHomeworkStudentNotification($student_notification_list, $template, $email_subject);
                    }
                }
            }
        }
    }

    public function sendOnlineexam($chk_mail_sms, $student_details, $template, $subject, $template_id)
    {
        $student_sms_list          = array();
        $student_email_list        = array();
        $student_notification_list = array();
        if ($chk_mail_sms['mail'] or $chk_mail_sms['sms'] or $chk_mail_sms['notification']) {
            $student_list = $this->CI->onlineexam_model->getstudentByexam_id($student_details['exam_id']);          
            
            
            $sms_detail   = $this->CI->smsconfig_model->getActiveSMS();
            if (!empty($student_list)) {
                foreach ($student_list as $student_key => $student_value) {

                    if ($student_value['app_key'] != "" && $chk_mail_sms['student_recipient']) {
                        $student_details['app_key']  = $student_value['app_key'];
                        $student_notification_list[] = $student_details;
                    }
                    
                    if ($student_value['parent_app_key'] != "" && $chk_mail_sms['guardian_recipient']) {
                        $student_details['app_key']  = $student_value['parent_app_key'];
                        $student_notification_list[] = $student_details;
                    }

                    if ($student_value['email'] != "" && $chk_mail_sms['student_recipient']) {
                        $student_email_list[$student_value['email']] = $student_details;
                    }
                    
                    if ($student_value['guardian_email'] != "" && $chk_mail_sms['guardian_recipient']) {
                        $student_email_list[$student_value['guardian_email']] = $student_details;
                    }
                    
                    if ($student_value['mobileno'] != "" && $chk_mail_sms['student_recipient']) {
                        $student_sms_list[$student_value['mobileno']] = $student_details;
                    }
                    
                    if ($student_value['guardian_phone'] != "" && $chk_mail_sms['guardian_recipient']) {
                        $student_sms_list[$student_value['guardian_phone']] = $student_details;
                    }
                }

                if ($chk_mail_sms['mail']) {
                    if ($student_email_list) {
                        $this->CI->mailgateway->sentOnlineexamStudentMail($student_email_list, $template, $subject);
                    }
                }

                if ($chk_mail_sms['sms'] && !empty($sms_detail)) {
                    if ($student_sms_list) {
                        $this->CI->smsgateway->sentOnlineexamStudentSMS($student_sms_list, $template, $template_id);
                    }
                }

                if ($chk_mail_sms['notification']) {
                    if (!empty($student_notification_list)) {
                        $this->CI->smsgateway->sentOnlineexamStudentNotification($student_notification_list, $template, $subject);
                    }
                }
            }
        }
    }

    public function getForgotPasswordContent($student_result_detail, $template)
    {
        foreach ($student_result_detail as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }

    public function sendOnlineadmission($chk_mail_sms, $student_details, $template, $subject, $template_id)
    {        
        $student_sms_list          = array();
        $student_email_list        = array();
        $student_notification_list = array();
        if ($chk_mail_sms['mail'] or $chk_mail_sms['sms'] or $chk_mail_sms['notification']) {
            $sms_detail = $this->CI->smsconfig_model->getActiveSMS();

            if ($chk_mail_sms['mail'] && $chk_mail_sms['student_recipient']) {

                $this->CI->mailgateway->sentOnlineadmissionStudentMail($student_details, $template, $subject, $student_details['email']);
            }

            if ($chk_mail_sms['mail'] && $chk_mail_sms['guardian_recipient']) {

                $this->CI->mailgateway->sentOnlineadmissionStudentMail($student_details, $template, $subject, $student_details['guardian_email']);
            }

            if ($chk_mail_sms['sms'] && !empty($sms_detail) && $chk_mail_sms['student_recipient']) {

                $this->CI->smsgateway->sentOnlineadmissionStudentSMS($student_details, $template, $template_id, $student_details['mobileno']);
            }

            if ($chk_mail_sms['sms'] && !empty($sms_detail) && $chk_mail_sms['guardian_recipient']) {

                $this->CI->smsgateway->sentOnlineadmissionStudentSMS($student_details, $template, $template_id, $student_details['guardian_phone']);
            }
            
            if ($chk_mail_sms['notification'] && !empty($sms_detail) && $chk_mail_sms['student_recipient']) {
                
                $this->CI->smsgateway->sentNotification($student_details['app_key'],$student_details, $chk_mail_sms['subject'],$template);
                
            }
            
            if ($chk_mail_sms['notification'] && !empty($sms_detail) && $chk_mail_sms['guardian_recipient']) {

                $this->CI->smsgateway->sentNotification($student_details['parent_app_key'],$student_details,$chk_mail_sms['subject'], $template);
            }
        }
    }

    public function sendOnlineadmissionFees($chk_mail_sms, $student_details, $template, $subject, $template_id)
    {
        $student_sms_list          = array();
        $student_email_list        = array();
        $student_notification_list = array();
        
        if ($chk_mail_sms['mail'] or $chk_mail_sms['sms'] or $chk_mail_sms['notification']) {
            $sms_detail = $this->CI->smsconfig_model->getActiveSMS();

            if ($chk_mail_sms['mail'] && $chk_mail_sms['student_recipient']) {

                $this->CI->mailgateway->sentOnlineadmissionFeesMail($student_details, $template, $subject, $student_details['email']);
                
            }

            if ($chk_mail_sms['mail'] && $chk_mail_sms['guardian_recipient']) {
            
                $student_details['email']=$student_details['guardian_email'];

                $this->CI->mailgateway->sentOnlineadmissionFeesMail($student_details, $template, $subject, $student_details['guardian_email']);
                
            }

            if ($chk_mail_sms['sms'] && !empty($sms_detail) && $chk_mail_sms['student_recipient']) {

                $this->CI->smsgateway->sentOnlineadmissionFeesSMS($student_details, $template, $template_id, $student_details['mobileno']);
                
            }

            if ($chk_mail_sms['sms'] && !empty($sms_detail) && $chk_mail_sms['guardian_recipient']) {
                
                $student_details['mobileno']=$student_details['guardian_phone'];
                $this->CI->smsgateway->sentOnlineadmissionFeesSMS($student_details, $template, $template_id, $student_details['guardian_phone']);
                
            }
        }       
    }

    public function sendEailEventReminder($event_reminder_value)
    {
        $subject = 'Event Reminder for ' . $event_reminder_value['event_title'];
        $message = $event_reminder_value['event_description'] . '<br><br> Event From: ' . $event_reminder_value['start_date'] . '<br> Event To: ' . $event_reminder_value['end_date'];

        $this->CI->mailgateway->sendEailEventReminder($event_reminder_value, $subject, $message);
    }
}