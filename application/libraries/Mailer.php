<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once(APPPATH.'third_party/PHPMailer/src/Exception.php');
require_once(APPPATH.'third_party/PHPMailer/src/PHPMailer.php');
require_once(APPPATH.'third_party/PHPMailer/src/SMTP.php');


class Mailer
{

    public $mail_config;
    private $sch_setting;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('emailconfig_model');
        $this->CI->mail_config = $this->CI->emailconfig_model->getActiveEmail();
        $this->CI->load->model('setting_model');
        $this->sch_setting = $this->CI->setting_model->get();
    }

    public function send_mail_marksheet($toemail, $subject, $body, $file, $file_name, $cc = "")
    {
        
        $mail = new PHPMailer(true); //Argument true in constructor enables exceptions
        $mail->CharSet = 'UTF-8';
        $school_name   = $this->sch_setting[0]['name'];
        $school_email  = $this->sch_setting[0]['email'];

        if ($this->CI->mail_config->email_type == "aws_ses") {
            $this->CI->load->library("aws_mail");
            $mail->setFrom($this->CI->mail_config->smtp_username, $school_name);
            $mail->addAddress($toemail);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $body;
            if ($cc != "") {
                $mail->AddCC($cc);
            }

            $mail->addStringAttachment($file, 'reservation.pdf');
            // Attempt to assemble the above components into a MIME message.
            if (!$mail->preSend()) {
                echo $mail->ErrorInfo;
            } else {
                // Create a new variable that contains the MIME message.
                $message = $mail->getSentMIMEMessage();
            }
            $status = $this->CI->aws_mail->sendRawMail($message);
            if ($status['status']) {
                return true;
            } else {
                return false;
            }
        } else {

            if ($this->CI->mail_config->email_type == "smtp") {
                
                $mail->IsSMTP();
                $mail->SMTPAuth   = ($this->CI->mail_config->smtp_auth != "") ? $this->CI->mail_config->smtp_auth : "";
                $mail->SMTPSecure = $this->CI->mail_config->ssl_tls;
                $mail->Host       = $this->CI->mail_config->smtp_server;
                $mail->Port       = $this->CI->mail_config->smtp_port;
                $mail->Username   = $this->CI->mail_config->smtp_username;
                $mail->Password   = $this->CI->mail_config->smtp_password;
                $mail->SetFrom($this->CI->mail_config->smtp_username, $school_name);
                $mail->AddReplyTo($this->CI->mail_config->smtp_username, $this->CI->mail_config->smtp_username);
             
            } else {

                $mail->From = $school_email;
                $mail->FromName = $school_name;
                $mail->isHTML(true);
                
            }

            $mail->addStringAttachment($file, $file_name . '.pdf');
            if ($cc != "") {
                $mail->AddCC($cc);
            }

            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $body;
            $mail->AddAddress($toemail);
            if ($mail->Send()) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function send_mail($toemail, $subject, $body, $FILES = array(), $cc = "")
    {

        $mail          = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $school_name   = $this->sch_setting[0]['name'];
        $school_email  = $this->sch_setting[0]['email'];

        if ($this->CI->mail_config->email_type == "aws_ses") {
            $this->CI->load->library("aws_mail");
            $mail->setFrom($this->CI->mail_config->smtp_username, $school_name);
            $mail->addAddress($toemail);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $body;
            if ($cc != "") {
                $mail->AddCC($cc);
            }

            if (!empty($FILES)) {
                if (isset($_FILES['files']) && !empty($_FILES['files'])) {
                    $no_files = count($_FILES["files"]['name']);
                    for ($i = 0; $i < $no_files; $i++) {
                        if ($_FILES["files"]["error"][$i] > 0) {
                            echo "Error: " . $_FILES["files"]["error"][$i] . "<br>";
                        } else {
                            $file_tmp  = $_FILES["files"]["tmp_name"][$i];
                            $file_name = $_FILES["files"]["name"][$i];
                            $mail->AddAttachment($file_tmp, $file_name);
                        }
                    }
                }
            }
            // Attempt to assemble the above components into a MIME message.
            if (!$mail->preSend()) {
                echo $mail->ErrorInfo;
            } else {
                // Create a new variable that contains the MIME message.
                $message = $mail->getSentMIMEMessage();
            }
            $status = $this->CI->aws_mail->sendRawMail($message);
            if ($status['status']) {
                return true;
            } else {
                return false;
            }
        } else {

            if ($this->CI->mail_config->email_type == "smtp") {
                $mail->IsSMTP();
                $mail->SMTPAuth   = ($this->CI->mail_config->smtp_auth != "") ? $this->CI->mail_config->smtp_auth : "";
                $mail->SMTPSecure = $this->CI->mail_config->ssl_tls;
                $mail->Host       = $this->CI->mail_config->smtp_server;
                $mail->Port       = $this->CI->mail_config->smtp_port;
                $mail->Username   = $this->CI->mail_config->smtp_username;
                $mail->Password   = $this->CI->mail_config->smtp_password;
                $mail->SetFrom($this->CI->mail_config->smtp_username, $school_name);
                $mail->AddReplyTo($this->CI->mail_config->smtp_username, $this->CI->mail_config->smtp_username);
            } else {
                $mail->From = $school_email;
                $mail->FromName = $school_name;
                $mail->isHTML(true);
            }

            if (!empty($FILES)) {
                if (isset($_FILES['files']) && !empty($_FILES['files'])) {

                    $no_files = count($_FILES["files"]['name']);
                    for ($i = 0; $i < $no_files; $i++) {
                        if (file_exists($_FILES['files']['tmp_name'][$i]) || is_uploaded_file($_FILES['files']['tmp_name'][$i])) {
                            $file_tmp  = $_FILES["files"]["tmp_name"][$i];
                            $file_name = $_FILES["files"]["name"][$i];
                            $mail->AddAttachment($file_tmp, $file_name);
                        }
                    }
                }
            }
            
            if ($cc != "") {
                $mail->AddCC($cc);
            }

            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $body;
            $mail->AddAddress($toemail);
            if ($mail->Send()) {

                return true;
            } else {

                return false;
            }
        }
    }

    public function compose_mail($toemail, $subject, $body, $FILES = array(), $cc = "")
    {
      
        $mail = new PHPMailer(true); //Argument true in constructor enables exceptions
        $mail->CharSet = 'UTF-8';
        $school_name   = $this->sch_setting[0]['name'];
        $school_email  = $this->sch_setting[0]['email'];

        if ($this->CI->mail_config->email_type == "aws_ses") {
            $this->CI->load->library("aws_mail");
            $mail->setFrom($this->CI->mail_config->smtp_username, $school_name);
            $mail->addAddress($toemail);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $body;
            if ($cc != "") {
                $mail->AddCC($cc);
            }

            if (!empty($FILES)) {
                foreach ($FILES as $key => $value) {
                    $mail->AddAttachment($value['directory'] . $value['attachment'], $value['attachment_name']);
                }
            }

            // Attempt to assemble the above components into a MIME message.
            if (!$mail->preSend()) {
                echo $mail->ErrorInfo;
            } else {
                // Create a new variable that contains the MIME message.
                $message = $mail->getSentMIMEMessage();
            }
            $status = $this->CI->aws_mail->sendRawMail($message);
            if ($status['status']) {
                return true;
            } else {
                return false;
            }
        } else {

            if ($this->CI->mail_config->email_type == "smtp") {
                $mail->IsSMTP();
                $mail->SMTPAuth   = ($this->CI->mail_config->smtp_auth != "") ? $this->CI->mail_config->smtp_auth : "";
                $mail->SMTPSecure = $this->CI->mail_config->ssl_tls;
                $mail->Host       = $this->CI->mail_config->smtp_server;
                $mail->Port       = $this->CI->mail_config->smtp_port;
                $mail->Username   = $this->CI->mail_config->smtp_username;
                $mail->Password   = $this->CI->mail_config->smtp_password;
                $mail->SetFrom($this->CI->mail_config->smtp_username, $school_name);
                $mail->AddReplyTo($this->CI->mail_config->smtp_username, $this->CI->mail_config->smtp_username);
            } else {
                $mail->From = $school_email;
                $mail->FromName = $school_name;
                $mail->isHTML(true);
            }

            if (!empty($FILES)) {
                foreach ($FILES as $key => $value) {
                    $mail->AddAttachment($value['directory'] . $value['attachment'], $value['attachment_name']);
                }
            }
            if ($cc != "") {
                $mail->AddCC($cc);
            }
         
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $body;
            $mail->AddAddress($toemail);

            try {
                $mail->send();
                return true;
            } catch (Exception $e) {
               // echo "Mailer Error: " . $mail->ErrorInfo;
               return false;
            }    
             
        }
    }
}
