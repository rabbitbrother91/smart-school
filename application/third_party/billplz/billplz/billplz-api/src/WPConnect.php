<?php

namespace Billplz;

class WPConnect
{
    private $api_key;
    private $x_signature_key;
    private $collection_id;

    private $process; //cURL or GuzzleHttp
    public $is_production;
    public $detect_mode;
    public $url;
    public $webhook_rank;

    public $header;

    const TIMEOUT = 10; //10 Seconds
    const PRODUCTION_URL = 'https://www.billplz.com/api/';
    const STAGING_URL = 'https://billplz-staging.herokuapp.com/api/';

    public function __construct(string $api_key)
    {
        $this->api_key = $api_key;

        $this->header = array(
            'Authorization' => 'Basic ' . base64_encode($this->api_key . ':')
        );
    }

    public function setMode(bool $is_production = false)
    {
        $this->is_production = $is_production;
        if ($is_production) {
            $this->url = self::PRODUCTION_URL;
        } else {
            $this->url = self::STAGING_URL;
        }
    }

    public function detectMode()
    {
        $this->url = self::PRODUCTION_URL;
        $this->detect_mode = true;
        return $this;
    }

    public function getWebhookRank()
    {
        $url = $this->url . 'v4/webhook_rank';

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['method'] = 'GET';
        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function getCollectionIndex(array $parameter = array())
    {
        $url = $this->url . 'v4/collections?'.http_build_query($parameter);

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['method'] = 'GET';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function createCollection(string $title, array $optional = array())
    {
        $url = $this->url . 'v4/collections';

        $body = http_build_query(['title' => $title]);
        if (isset($optional['split_header'])) {
            $split_header = http_build_query(array('split_header' => $optional['split_header']));
        }

        $split_payments = [];
        if (isset($optional['split_payments'])) {
            foreach ($optional['split_payments'] as $param) {
                $split_payments[] = http_build_query($param);
            }
        }

        if (!empty($split_payments)) {
            $body.= '&' . implode('&', $split_payments);
            if (!empty($split_header)) {
                $body.= '&' . $split_header;
            }
        }

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['body'] = $body;
        $wp_remote_data['method'] = 'POST';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function createOpenCollection(array $parameter, array $optional = array())
    {
        $url = $this->url . 'v4/open_collections';

        $body = http_build_query($parameter);
        if (isset($optional['split_header'])) {
            $split_header = http_build_query(array('split_header' => $optional['split_header']));
        }

        $split_payments = [];
        if (isset($optional['split_payments'])) {
            foreach ($optional['split_payments'] as $param) {
                $split_payments[] = http_build_query($param);
            }
        }

        if (!empty($split_payments)) {
            unset($optional['split_payments']);
            $body.= '&' . implode('&', $split_payments);
            if (!empty($split_header)) {
                unset($optional['split_header']);
                $body.= '&' . $split_header;
            }
        }

        if (!empty($optional)) {
            $body.= '&' . http_build_query($optional);
        }

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['body'] = $body;
        $wp_remote_data['method'] = 'POST';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function getCollection(string $id)
    {
        $url = $this->url . 'v4/collections/'.$id;

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['method'] = 'GET';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function getOpenCollection(string $id)
    {
        $url = $this->url . 'v4/open_collections/'.$id;

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['method'] = 'GET';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function getOpenCollectionIndex(array $parameter = array())
    {
        $url = $this->url . 'v4/open_collections?'.http_build_query($parameter);

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['method'] = 'GET';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function createMPICollection(string $title)
    {
        $url = $this->url . 'v4/mass_payment_instruction_collections';

        $data = ['title' => $title];

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['body'] = http_build_query($data);
        $wp_remote_data['method'] = 'POST';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function getMPICollection(string $id)
    {
        $url = $this->url . 'v4/mass_payment_instruction_collections/'.$id;

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['method'] = 'GET';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function createMPI(array $parameter, array $optional = array())
    {
        $url = $this->url . 'v4/mass_payment_instructions';

        //if (sizeof($parameter) !== sizeof($optional) && !empty($optional)){
        //    throw new \Exception('Optional parameter size is not match with Required parameter');
        //}

        $data = array_merge($parameter, $optional);

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['body'] = http_build_query($data);
        $wp_remote_data['method'] = 'POST';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function getMPI(string $id)
    {
        $url = $this->url . 'v4/mass_payment_instructions/'.$id;

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['body'] = http_build_query($data);
        $wp_remote_data['method'] = 'POST';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public static function getXSignature(string $x_signature_key)
    {
        $signingString = '';

        if (isset($_GET['billplz']['id']) &&isset($_GET['billplz']['paid_at']) && isset($_GET['billplz']['paid']) && isset($_GET['billplz']['x_signature'])) {
            $data = array(
                'id' => $_GET['billplz']['id'] ,
                'paid_at' =>  $_GET['billplz']['paid_at'],
                'paid' => $_GET['billplz']['paid'],
                'x_signature' =>  $_GET['billplz']['x_signature']
            );
            $type = 'redirect';
        } elseif (isset($_POST['x_signature'])) {
            $data = array(
               'amount' => isset($_POST['amount']) ? $_POST['amount'] : '',
               'collection_id' => isset($_POST['collection_id']) ? $_POST['collection_id'] : '',
               'due_at' => isset($_POST['due_at']) ? $_POST['due_at'] : '',
               'email' => isset($_POST['email']) ? $_POST['email'] : '',
               'id' => isset($_POST['id']) ? $_POST['id'] : '',
               'mobile' => isset($_POST['mobile']) ? $_POST['mobile'] : '',
               'name' => isset($_POST['name']) ? $_POST['name'] : '',
               'paid_amount' => isset($_POST['paid_amount']) ? $_POST['paid_amount'] : '',
               'paid_at' => isset($_POST['paid_at']) ? $_POST['paid_at'] : '',
               'paid' => isset($_POST['paid']) ? $_POST['paid'] : '',
               'state' => isset($_POST['state']) ? $_POST['state'] : '',
               'url' => isset($_POST['url']) ? $_POST['url'] : '',
               'x_signature' => isset($_POST['x_signature']) ? $_POST['x_signature'] :'',
            );
            $type = 'callback';
        } else {
            return false;
        }

        foreach ($data as $key => $value) {
            if (isset($_GET['billplz']['id'])) {
                $signingString .= 'billplz'.$key . $value;
            } else {
                $signingString .= $key . $value;
            }
            if (($key === 'url' && isset($_POST['x_signature']))|| ($key === 'paid' && isset($_GET['billplz']['id']))) {
                break;
            } else {
                $signingString .= '|';
            }
        }

        /*
         * Convert paid status to boolean
         */
        $data['paid'] = $data['paid'] === 'true' ? true : false;

        $signedString = hash_hmac('sha256', $signingString, $x_signature_key);

        if ($data['x_signature'] === $signedString) {
            $data['type'] = $type;
            return $data;
        }

        throw new \Exception('X Signature Calculation Mismatch!');
    }

    public function deactivateCollection(string $title, string $option = 'deactivate')
    {
        $url = $this->url . 'v3/collections/'.$title.'/'.$option;

        $data = ['title' => $title];

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['body'] = http_build_query(array());
        $wp_remote_data['method'] = 'POST';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function createBill(array $parameter, array $optional = array())
    {
        $url = $this->url . 'v3/bills';

        //if (sizeof($parameter) !== sizeof($optional) && !empty($optional)){
        //    throw new \Exception('Optional parameter size is not match with Required parameter');
        //}

        $data = array_merge($parameter, $optional);

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['body'] = http_build_query($data);
        $wp_remote_data['method'] = 'POST';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function getBill(string $id)
    {
        $url = $this->url . 'v3/bills/'.$id;

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['method'] = 'GET';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function deleteBill(string $id)
    {
        $url = $this->url . 'v3/bills/'.$id;

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['body'] = http_build_query(array());
        $wp_remote_data['method'] = 'DELETE';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function bankAccountCheck(string $id)
    {
        $url = $this->url . 'v3/check/bank_account_number/'.$id;

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['method'] = 'GET';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function getPaymentMethodIndex(string $id)
    {
        $url = $this->url . 'v3/collections/'.$id.'/payment_methods';

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['method'] = 'GET';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function getTransactionIndex(string $id, array $parameter)
    {
        $url = $this->url . 'v3/bills/'.$id.'/transactions?'.http_build_query($parameter);

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['method'] = 'GET';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function updatePaymentMethod(array $parameter)
    {
        if (!isset($parameter['collection_id'])) {
            throw new \Exception('Collection ID is not passed on updatePaymethodMethod');
        }
        $url = $this->url . 'v3/collections/'.$parameter['collection_id'].'/payment_methods';

        unset($parameter['collection_id']);
        $body = [];
        foreach ($parameter['payment_methods'] as $param) {
            $body[] = http_build_query($param);
        }

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['body'] = implode('&', $body);
        $wp_remote_data['method'] = 'PUT';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function getBankAccountIndex(array $parameter)
    {
        if (!is_array($parameter['account_numbers'])) {
            throw new \Exception('Not valid account numbers.');
        }

        $parameter = http_build_query($parameter);
        $parameter = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $parameter);

        $url = $this->url . 'v3/bank_verification_services?'.$parameter;

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['method'] = 'GET';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function getBankAccount(string $id)
    {
        $url = $this->url . 'v3/bank_verification_services/'.$id;

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['method'] = 'GET';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function createBankAccount(array $parameter)
    {
        $url = $this->url . 'v3/bank_verification_services';

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['body'] = http_build_query($parameter);
        $wp_remote_data['method'] = 'POST';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function getFpxBanks()
    {
        $url = $this->url . 'v3/fpx_banks';

        $wp_remote_data['sslverify'] = false;
        $wp_remote_data['headers'] = $this->header;
        $wp_remote_data['method'] = 'GET';

        $response = \wp_remote_post($url, $wp_remote_data);
        $header = $response['response']['code'];
        $body = \wp_remote_retrieve_body($response);

        return array($header,$body);
    }

    public function closeConnection()
    {
    }

    public function toArray(array $json)
    {
        return array($json[0], \json_decode($json[1], true));
    }
}
