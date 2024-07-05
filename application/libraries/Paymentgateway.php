<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Paymentgateway {

    private $_CI;

    function __construct() {
        $this->_CI = & get_instance();
        $this->_CI->load->library('mailer');
        $this->_CI->mailer;
    }

    function sentRegisterMail($id, $send_to) {


        if (!empty($this->_CI->mail_config) && $send_to != "") {
            $subject = "New Registration";
            $msg = $this->getStudentRegistrationContent($id);
            $this->_CI->mailer->send_mail($send_to, $subject, $msg);
        }
    }

}

?>