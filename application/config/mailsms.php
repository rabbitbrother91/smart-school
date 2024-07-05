<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

// Config variables

$config['mailsms'] = array(
    'student_admission'                 => 'student_admission',
    'exam_result'                       => 'exam_result',
    'fee_submission'                    => 'fee_submission',
    'group_fee_submission'              => 'group_fee_submission',
    'absent_attendence'                 => 'absent_attendence',
    'login_credential'                  => 'login_credential',
    'fees_reminder'                     => 'fees_reminder',
    'homework'                          => 'homework',
    'alumni_student'                    => 'alumni_student',
    'online_classes'                    => 'online_classes',
    'online_meeting'                    => 'online_meeting',
    'forgot_password'                   => 'forgot_password',
    'online_examination_publish_exam'   => 'online_examination_publish_exam',
    'online_examination_publish_result' => 'online_examination_publish_result',
    'online_admission_form_submission'  => 'online_admission_form_submission',
    'online_admission_fees_submission'  => 'online_admission_fees_submission',
    'online_admission_fees_processing'  => 'online_admission_fees_processing',
    'student_login_credential'          => 'student_login_credential',
    'staff_login_credential'            => 'staff_login_credential',
    'fee_processing'                    => 'fee_processing',
    'student_apply_leave'               => 'student_apply_leave',
    'email_pdf_exam_marksheet'          => 'email_pdf_exam_marksheet',
);
$config['smtp_encryption'] = array(
    ''    => 'OFF',
    'ssl' => 'SSL',
    'tls' => 'TLS',
);

$config['smtp_auth'] = array(
    'true'  => 'ON',
    'false' => 'OFF',
);

$config['attendence'] = array(
    'present'          => 1,
    'late_with_excuse' => 2,
    'late'             => 3,
    'absent'           => 4,
    'holiday'          => 5,
    'half_day'         => 6,
);

$config['attendence_exam'] = array(
    'absent' => 'absent',
);
$config['perm_category'] = array('can_view', 'can_add', 'can_edit', 'can_delete');

$config['bloodgroup'] = array('1' => 'O+', '2' => 'A+', '3' => 'B+', '4' => 'AB+', '5' => 'O-', '6' => 'A-', '7' => 'B-', '8' => 'AB-');

$config['question_type'] = array(
    'singlechoice' => lang('single_choice'),
    'multichoice'  => lang('multiple_choice'),
    'true_false'   => lang('true_false'),
    'descriptive'  => lang('descriptive'),
);

$config['question_level'] = array(
    'low'    => lang('low'),
    'medium' => lang('medium'),
    'high'   => lang('high'),
);

$config['question_true_false'] = array(
    'true'  => lang('true'),
    'false' => lang('false'),
);

$config['send_through'] = array(
    'sms'  => lang('sms'),
    'push' => lang('mobile_app'),
);
