<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

include_once APPPATH . 'third_party/aws/aws-autoloader.php';

use Aws\Exception\AwsException;
use Aws\Ses\SesClient;

class Aws_mail
{
    public $CI;
    private $ses;
    private $region;
    private $name;
    private $email;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model("emailconfig_model");
        $this->CI->load->model('setting_model');
        $sch_setting = $this->CI->setting_model->get();
        $settings    = $this->CI->emailconfig_model->getActiveEmail();
        if(!empty($settings)){
            try{
                $this->email = $settings->smtp_username;
                $this->name  = $sch_setting[0]['name'];
                $this->ses   = new SesClient([
                    'version'     => 'latest',
                    'region'      => $settings->region,
                    'credentials' => [
                        'key'    => $settings->api_key,
                        'secret' => $settings->api_secret,
                    ],
                ]);
            }catch(Exception $e){
                
            }
        }
    }

    public function sendMail($mail_data)
    {
        $sender_email     = $this->email;
        $sender_name      = $this->name;
        $recipient_emails = $mail_data['recipient_emails'];
        $subject          = $mail_data['subject'];
        $html_body        = $mail_data['html_body'];
        $char_set         = 'UTF-8';
        try {
            $result = $this->ses->sendEmail([
                'Destination'      => [
                    'ToAddresses' => $recipient_emails,
                ],
                'Source'           => $sender_name . " <" . $sender_email . ">",
                'Message'          => [
                    'Body'    => [
                        'Html' => [
                            'Charset' => $char_set,
                            'Data'    => $html_body,
                        ],
                    ],
                    'Subject' => [
                        'Charset' => $char_set,
                        'Data'    => $subject,
                    ],
                ],
            ]);
            $messageId = $result['MessageId'];
            return "Email sent! Message ID: $messageId" . "\n";
        } catch (AwsException $e) {
            // output error message if fails
            echo $e->getMessage();
            echo ("The email was not sent. Error message: " . $e->getAwsErrorMessage() . "\n");
            echo "\n";
        }
    }

    public function sendMailBCC($mail_data)
    {
        $sender_email     = $this->email;
        $sender_name      = $this->name;
        $recipient_emails = $mail_data['recipient_emails'];
        $bcc_emails       = $mail_data['bcc_emails'];
        $subject          = $mail_data['subject'];
        $html_body        = $mail_data['html_body'];
        $char_set         = 'UTF-8';
        try {
            $result = $this->ses->sendEmail([
                'Destination'      => [
                    'ToAddresses' => $recipient_emails,
                    'BccAddresses' => $bcc_emails
                ],
                'Source'           => $sender_name . " <" . $sender_email . ">",
                'Message'          => [
                    'Body'    => [
                        'Html' => [
                            'Charset' => $char_set,
                            'Data'    => $html_body,
                        ],
                    ],
                    'Subject' => [
                        'Charset' => $char_set,
                        'Data'    => $subject,
                    ],
                ],
            ]);
            $messageId = $result['MessageId'];
            return "Email sent! Message ID: $messageId" . "\n";
        } catch (AwsException $e) {
            // output error message if fails
            echo $e->getMessage();
            echo ("The email was not sent. Error message: " . $e->getAwsErrorMessage() . "\n");
            echo "\n";
        }
    }

    public function sendRawMail($message){
        try {
            $result = $this->ses->sendRawEmail([
                'RawMessage' => [
                    'Data' => $message
                ]
            ]);
            // If the message was sent, show the message ID.
            $messageId = $result->get('MessageId');
            return array("status" => 1, "message" => "Email sent! Message ID: $messageId"."\n");
        } catch (SesException $error) {
            // If the message was not sent, show a message explaining what went wrong.
            return array("status" => 0, "message" => "The email was not sent. Error message: "
                 .$error->getAwsErrorMessage()."\n");
        }
    }

}
