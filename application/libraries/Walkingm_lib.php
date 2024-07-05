<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
  
class Walkingm_lib {

    private $_CI;
    var $client_id;
    var $client_secret;
    function __construct() {
        $this->_CI = & get_instance();
        $merchant_data = $this->_CI->paymentsetting_model->getActiveMethod();
        $this->client_id=$merchant_data->api_publishable_key;
        $this->client_secret=$merchant_data->api_secret_key;
        
        $this->session_name = $this->_CI->setting_model->getCurrentSessionName();
    } 
   
    function walkingm_login($email,$password,$payment_array) {
      
           $curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://walkingm.com/api/login",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_USERAGENT=>$_SERVER['HTTP_USER_AGENT'],
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"email\"\r\n\r\n".$email."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"password\"\r\n\r\n".$password."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
    "postman-token: 8f266b2f-38e2-628a-43d6-7222712257dd"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

    if ($err) {
      return $err;
    } else {
        $login_data= json_decode($response);
        
        if(isset($login_data->response)){
          if($login_data->response->status==200){
           $walkingm_verify = $this->walkingm_verify($payment_array,$login_data);
           return $walkingm_verify;
        }else{
            return $login_data->response->message;
        }
      }elseif($login_data->success->status!=200){
return $login_data->success->message;
      }
        
       
    }

    }

    function walkingm_verify($payment_array,$login_data){
  $curl_verify = curl_init();
  curl_setopt_array($curl_verify, array(
  CURLOPT_URL => "https://walkingm.com/api/merchant/verify",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
   CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"client_id\"\r\n\r\n".$this->client_id."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"client_secret\"\r\n\r\n".$this->client_secret."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
  CURLOPT_HTTPHEADER => array(
    "authorization-token: ".$login_data->response->token,
    "cache-control: no-cache",
    "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
    "postman-token: c6be6d85-9c05-6eed-faad-8adbb47d05c2"
  ),
));

$response_curl_verify = curl_exec($curl_verify);
$err_curl_verify = curl_error($curl_verify);

curl_close($curl_verify);

if ($err_curl_verify) {
  return $err_curl_verify;
} else {
    $verify_data= json_decode($response_curl_verify);
   
    if($verify_data->success->status==200){
     $walkingm_transaction_info= $this->walkingm_transaction_info($verify_data,$login_data,$payment_array);
     return $walkingm_transaction_info;
    }else{
      return $verify_data->success->message;
    }
    
}
    }

    function walkingm_transaction_info($verify_data,$login_data,$payment_array){
        $curl_info = curl_init();
  $payer=$payment_array['payer'];
  $amount=$payment_array['amount'];
  $currency=$payment_array['currency'];
  $successUrl=$payment_array['successUrl'];
  $cancelUrl=$payment_array['cancelUrl'];
curl_setopt_array($curl_info, array(
  CURLOPT_URL => "https://walkingm.com/api/merchant/transaction-info",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"payer\"\r\n\r\n".$payer."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"amount\"\r\n\r\n".$amount."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"currency\"\r\n\r\n".$currency."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"successUrl\"\r\n\r\n".$successUrl."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"cancelUrl\"\r\n\r\n".$cancelUrl."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
  CURLOPT_HTTPHEADER => array(
    "authorization: Bearer ".$verify_data->success->response->data->access_token,
    "authorization-token: ".$login_data->response->token,
    "cache-control: no-cache",
    "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
    "postman-token: f85ff53b-710e-05e8-146b-c05bcf02128c"
  ),
));

$response_curl_info = curl_exec($curl_info);
$err_curl_info = curl_error($curl_info);

curl_close($curl_info);

if ($err_curl_info) {
  return $err_curl_info;
} else {
  
   $info_data= json_decode($response_curl_info);

if($info_data->success->status=='success'){
 $walkingm_payment= $this->walkingm_payment($info_data,$login_data);
 return $walkingm_payment;
}else{
  return $info_data->success->message;
}
   
}
    }

    function walkingm_payment($info_data,$login_data){
      $curl_payment = curl_init();
      curl_setopt_array($curl_payment, array(
      CURLOPT_URL => "https://walkingm.com/api/merchant/payment",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"grant_id\"\r\n\r\n".$info_data->success->grandId."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"token\"\r\n\r\n".$info_data->success->token."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
      CURLOPT_HTTPHEADER => array(
       "authorization-token: ".$login_data->response->token,
        "cache-control: no-cache",
        "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
        "postman-token: df55f15b-6287-00fa-6968-f52b0b20fbdf"
      ),
    ));

    $response_curl_payment = curl_exec($curl_payment);
    $err_curl_payment = curl_error($curl_payment);

    curl_close($curl_payment);

    if ($err_curl_payment) {
      return  $err_curl_payment;
    } else { 

      $payment_data= json_decode($response_curl_payment);

      if($payment_data->success->status==200){
        $url=$payment_data->success->successPath;
     header("Location: $url");
      }else{
       return  $payment_data->success->message;
      }
      
     
    }

    }

}
?>