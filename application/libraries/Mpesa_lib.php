<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mpesa_lib {
    public $config;
	
	function __construct(array $configs = array())
	{
		$defaults = array(
			'env'               => 'sandbox',
			'type'              => 4,
			'shortcode'         => '174379',
			'headoffice'        => '174379',
		    'key'               => 'WiGveilGB2SKbXWi9IShIHDK7XfCtvWK',
			'secret'            => 'mJBnR94sTlGFUkvM',
			'passkey'           => 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919',
			'validation_url'    => '/pesa/validate',
			'confirmation_url'  => '/pesa/confirm',
			'callback_url'      => '/pesa/reconcile',
			'timeout_url'       => '/pesa/timeout',
			'results_url'       => '/pesa/results',
		);

		if(!isset($configs['headoffice']) || empty($configs['headoffice'])){
			$defaults['headoffice'] = $configs['shortcode'];
		}

		$parsed = array_merge($defaults, $configs);

		$this->config 	= (object)$parsed;
	}

	/**
	 * @return string Access token
	 */
	public function token()
	{
		$endpoint = ($this->config->env == 'live') 
			? 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials' 
			: 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

		$credentials = base64_encode($this->config->key.':'.$this->config->secret);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $endpoint);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials));
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$curl_response = curl_exec($curl);
		$result = json_decode($curl_response);

		return isset($result->access_token) ? $result->access_token : '';
	}
	
	/**
	 * @param callable $callback Defined function or closure to process data and return true/false
	 * 
	 * @return array
	 */
	public function validate($callback = null)
	{
		$data = json_decode(file_get_contents('php://input'), true);
		if (!$data) {
			return array('ResultCode' => 1, 'ResultDesc' => 'No data received');
		}

		if(is_null($callback)){
			return array('ResultCode' => 0, 'ResultDesc' => 'Success');
		} else {
			return call_user_func_array($callback, array($data)) 
				? array('ResultCode' => 0, 'ResultDesc' => 'Success') 
				: array('ResultCode' => 1, 'ResultDesc' => 'Failed');
		}
	}
	
	/**
	 * @param callable $callback Defined function or closure to process data and return true/false
	 * 
	 * @return array
	 */
	public function confirm($callback = null)
	{
		$data = json_decode(file_get_contents('php://input'), true);
		if (!$data) {
			return array('ResultCode' => 1, 'ResultDesc' => 'No data received');
		}

		if(is_null($callback)){
			return array('ResultCode' => 0, 'ResultDesc' => 'Success');
		} else {
			return call_user_func_array($callback, array($data)) 
				? array('ResultCode' => 0, 'ResultDesc' => 'Success') 
				: array('ResultCode' => 1, 'ResultDesc' => 'Failed');
		}
	}
	
	/**
	 * @param callable $callback Defined function or closure to process data and return true/false
	 * 
	 * @return array
	 */
	public function reconcile($callback = null)
	{
		$data = json_decode(file_get_contents('php://input'), true);
		if (!$data) {
			return array('ResultCode' => 1, 'ResultDesc' => 'No data received');
		}

		if(is_null($callback)){
			return array('ResultCode' => 0, 'ResultDesc' => 'Success');
		} else {
			return call_user_func_array($callback, array($data)) 
				? array('ResultCode' => 0, 'ResultDesc' => 'Success') 
				: array('ResultCode' => 1, 'ResultDesc' => 'Failed');
		}
	}
	
	/**
	 * @param callable $callback Defined function or closure to process data and return true/false
	 * 
	 * @return array
	 */
	public function register($callback = null)
	{
		$token      = $this->token();
		$endpoint   = ($this->config->env == 'live') 
			? 'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl'
			: 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';

		$curl       = curl_init();
		curl_setopt($curl, CURLOPT_URL, $endpoint);
		curl_setopt(
			$curl, 
			CURLOPT_HTTPHEADER, 
			array(
				'Content-Type:application/json',
				'Authorization:Bearer '.$token
			)
		);

		$curl_post_data = array(
			'ShortCode' 		=> $this->config->shortcode,
			'ResultType' 		=> 'Cancelled',
			'ConfirmationURL' 	=> $this->config->confirmation_url,
			'ValidationURL' 	=> $this->config->validation_url
		);
		$data_string = json_encode($curl_post_data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$response   = curl_exec($curl);
		$content    = json_decode($response, true);

		if(is_null($callback)){
			if ($response) {
				if(isset($content['ResultDescription'])){
					$status = $content['ResultDescription'];
				} elseif(isset($content['errorMessage'])){
					$status = $content['errorMessage'];
				} else {
					$status = 'Sorry could not connect to Daraja. Check your connection/configuration and try again.';
				}
			}
			
			return array('Registration status' => $status);
		} else {
			return \call_user_func_array($callback, $content);
		}
	}

    /**
     * @param $phone The MSISDN sending the funds.
     * @param $amount The amount to be transacted.
     * @param $reference Used with M-Pesa PayBills.
     * 
     * @return array Response
     */
	public function simulate($phone, $amount = 10, $reference = 'TRX')
	{
		$token = $this->token();
		$phone = (substr($phone, 0,1) == '+') ? str_replace('+', '', $phone) : $phone;
		$phone = (substr($phone, 0,1) == '0') ? preg_replace('/^0/', '254', $phone) : $phone;

		$endpoint = ($this->config->env == 'live') 
			? 'https://api.safaricom.co.ke/mpesa/c2b/v1/simulate' 
			: 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $endpoint);
		curl_setopt(
			$curl, 
			CURLOPT_HTTPHEADER, 
			array(
				'Content-Type:application/json',
				'Authorization:Bearer '.$token
			)
		);
		$curl_post_data     = array(
			'ShortCode'     => $this->config->shortcode,
			'CommandID'     => ($this->config->type == 4) ? 'CustomerPayBillOnline' : 'CustomerBuyGoodsOnline',
			'Amount'        => round($amount),
			'Msisdn'        => $phone,
			'BillRefNumber' => $reference
		);
		$data_string        = json_encode($curl_post_data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$curl_response = curl_exec($curl);
		$response = curl_exec($curl);

		return json_decode($response, true);
	}

    /**
     * @param $phone The MSISDN sending the funds.
     * @param $amount The amount to be transacted.
     * @param $reference Used with M-Pesa PayBills.
     * @param $description A description of the transaction.
     * @param $remark Remarks
     * 
     * @return array Response
     */
    public function request($phone, $amount, $reference = 'ACCOUNT', $description = 'Transaction Description', $remark = 'Remark')
    {
        $token      = $this->token();
        
		$phone      = (substr($phone, 0,1) == '+') ? str_replace('+', '', $phone) : $phone;
		$phone      = (substr($phone, 0,1) == '0') ? preg_replace('/^0/', '254', $phone) : $phone;
		$timestamp  = date('YmdHis');
        $password   = base64_encode($this->config->shortcode.$this->config->passkey.$timestamp);
        
		$endpoint   = ($this->config->env == 'live')
            ? 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest' 
            : 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $endpoint);
        curl_setopt(
            $curl, 
            CURLOPT_HTTPHEADER, 
            array(
                'Content-Type:application/json', 
                'Authorization:Bearer '.$token
            )
        );
        $curl_post_data = array(
            'BusinessShortCode' => $this->config->headoffice,
            'Password' 			=> $password,
            'Timestamp' 		=> $timestamp,
            'TransactionType' 	=> ($this->config->type == 4) ? 'CustomerPayBillOnline' : 'CustomerBuyGoodsOnline',
            'Amount' 			=> round($amount),
            'PartyA' 			=> $phone,
            'PartyB' 			=> $this->config->shortcode,
            'PhoneNumber' 		=> $phone,
            'CallBackURL' 		=> $this->config->callback_url,
            'AccountReference' 	=> $reference,
            'TransactionDesc' 	=> $description,
            'Remark'			=> $remark
        );
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $response = curl_exec($curl);
		
		return json_decode($response, true);
    }
}
