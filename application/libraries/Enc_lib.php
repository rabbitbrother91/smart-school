<?php

class Enc_lib {

    public $pub_key = 'ss@pubkey';
    public $pvt_key = 'ss@pvtkey';

    function encrypt($string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $this->pvt_key);
        $iv = substr(hash('sha256', $this->pub_key), 0, 16);
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        return $output;
    }

    function dycrypt($string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $this->pvt_key);
        $iv = substr(hash('sha256', $this->pub_key), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        return $output;
    }

    function passHashEnc($password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        return $hashed_password;
    }

    function passHashDyc($password, $encrypt_password) {
        $isPasswordCorrect = password_verify($password, $encrypt_password);
        return $isPasswordCorrect;
    }

}
