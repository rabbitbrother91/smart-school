<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Payfast extends Front_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('gateway_ins_model'));
    }

    public function index()
    {

        // Tell PayFast that this page is reachable by triggering a header 200
        header('HTTP/1.0 200 OK');
        flush();

        define('SANDBOX_MODE', true);
        $pfHost = SANDBOX_MODE ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
        // Posted variables from ITN
        $pfData = $_POST;

        // Strip any slashes in data
        foreach ($pfData as $key => $val) {
            $pfData[$key] = stripslashes($val);
        }

        // Convert posted variables to a string
        foreach ($pfData as $key => $val) {
            if ($key !== 'signature') {
                $pfParamString .= $key . '=' . urlencode($val) . '&';
            } else {
                break;
            }
        }

        $pfParamString         = substr($pfParamString, 0, -1);
        $response              = "notify sent";
        $para_amount           = $this->gateway_ins_model->get_gateway_ins($pfData['m_payment_id'], 'payfast');
        $PayFast_details       = $this->gateway_ins_model->get_gateway_details('payfast');
        $posted_parameter      = json_decode($para_amount['parameter_details']);
        $get_statusByUnique_id = $this->gateway_ins_model->get_statusByUnique_id($pfData['m_payment_id'], 'payfast');
        $check1                = $this->pfValidSignature($pfData, $pfParamString, $PayFast_details->salt);
        $check2                = $this->pfValidIP();
        $check3                = $this->pfValidPaymentData($posted_parameter->amount, $pfData);
        $check4                = $this->pfValidServerConfirmation($pfParamString, $pfHost);

        if ($check1 && $check2 && $check4 && $check3) {
            if ($pfData['payment_status'] == 'COMPLETE') {
                $response    = $pfData['payment_status'];
                $paid_status = 1;

                if ($para_amount['module_type'] == 'online_course') {
                    $online_course                   = $this->gateway_ins_model->get_processing_payment($para_amount['id']);
                    $online_course['transaction_id'] = $pfData['pf_payment_id'];
                    $online_course['note']           = "Online course fees processing skrill Txn ID: " . $pfData['pf_payment_id'];
                    unset($online_course['id']);
                    unset($online_course['gateway_ins_id']);
                    $response = "success";
                    $this->gateway_ins_model->deleteprocessingpaymentByid($para_amount['id']);
                }

                if ($para_amount['module_type'] == 'fees') {
                    #==========================fees-start==========================
                    $parameter_data = $this->gateway_ins_model->get($pfData['m_payment_id'], 'payfast');
                    $bulk_fees      = array();
                    foreach ($parameter_data as $fee_key => $fee_value) {

                        $insert_fee_data = array(
                            'fee_category'             => $fee_value['fee_category'],
                            'student_transport_fee_id' => $fee_value['student_transport_fee_id'],
                            'student_fees_master_id'   => $fee_value['student_fees_master_id'],
                            'fee_groups_feetype_id'    => $fee_value['fee_groups_feetype_id'],
                            'amount_detail'            => json_decode($fee_value['amount_detail'], true),
                        );
                        $bulk_fees[] = $insert_fee_data;
                        //========
                    }
                    $insert_id = $this->gateway_ins_model->fee_deposit_bulk($bulk_fees);
                    if ($insert_id) {
                        $response = "success";
                        $this->gateway_ins_model->deleteBygateway_ins_id($para_amount['id']);
                    } else {
                        $response = "quiry_failed";
                    }
                    #==========================fees-end==========================
                }

            } else {
                $response    = $pfData['payment_status'];
                $paid_status = 0;
            }

        } else {
            $response    = "checks_failed";
            $paid_status = 2;
        }
        if ($para_amount['module_type'] == 'online_admission') {
            $this->gateway_ins_model->onlineAdmissionStatus($para_amount['online_admission_id'], $paid_status);
        }
        $gateway_ins_response = json_encode($_POST);
        $gateway_ins_add      = array('gateway_ins_id' => $get_statusByUnique_id['id'], 'posted_data' => $gateway_ins_response, 'response
        '                                      => $response);

        $this->gateway_ins_model->add_gateway_ins_response($gateway_ins_add);

        $this->gateway_ins_model->update_gateway_ins(array('id' => $get_statusByUnique_id['id'], 'payment_status' => $response));
    }

    public function pfValidIP()
    {
        // Variable initialization
        $validHosts = array(
            'www.payfast.co.za',
            'sandbox.payfast.co.za',
            'w1w.payfast.co.za',
            'w2w.payfast.co.za',
        );

        $validIps = [];

        foreach ($validHosts as $pfHostname) {
            $ips = gethostbynamel($pfHostname);

            if ($ips !== false) {
                $validIps = array_merge($validIps, $ips);
            }

        }

        // Remove duplicates
        $validIps   = array_unique($validIps);
        $referrerIp = gethostbyname(parse_url($_SERVER['HTTP_REFERER'])['host']);
        if (in_array($referrerIp, $validIps, true)) {
            return true;
        }
        return false;
    }

    public function pfValidSignature($pfData, $pfParamString, $pfPassphrase = null)
    {
        // Calculate security signature
        if ($pfPassphrase === null) {
            $tempParamString = $pfParamString;
        } else {
            $tempParamString = $pfParamString . '&passphrase=' . urlencode($pfPassphrase);
        }

        $signature = md5($tempParamString);
        return ($pfData['signature'] === $signature);
    }

    public function pfValidPaymentData($cartTotal, $pfData)
    {
        return !(abs((float) $cartTotal - (float) $pfData['amount_gross']) > 0.01);
    }

    public function pfValidServerConfirmation($pfParamString, $pfHost = 'sandbox.payfast.co.za', $pfProxy = null)
    {
        // Use cURL (if available)
        if (in_array('curl', get_loaded_extensions(), true)) {
            // Variable initialization
            $url = 'https://' . $pfHost . '/eng/query/validate';

            // Create default cURL object
            $ch = curl_init();

            // Set cURL options - Use curl_setopt for greater PHP compatibility
            // Base settings
            curl_setopt($ch, CURLOPT_USERAGENT, null); // Set user agent
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return output as string rather than outputting it
            curl_setopt($ch, CURLOPT_HEADER, false); // Don't include header in output
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            // Standard settings
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $pfParamString);
            if (!empty($pfProxy)) {
                curl_setopt($ch, CURLOPT_PROXY, $pfProxy);
            }

            // Execute cURL
            $response = curl_exec($ch);
            curl_close($ch);
            if ($response === 'VALID') {
                return true;
            }
        }
        return false;
    }

}
