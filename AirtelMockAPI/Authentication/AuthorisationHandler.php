<?php

class Authorise{

	private $token;
	private $token_length = 30;
	private $client_id;
	private $client_secret;
  private $user_grant_type;
  private $expires_in = 120;

	function __construct($id, $secret, $user_grant_type) {
    $this->client_id = $id;
    $this->client_secret = $secret;
    $this->user_grant_type = $user_grant_type;
    }

    function getToken(){
    return $this->token;	
    }

    function getclient_id(){
    return $this->client_id;  
    }

    function getclient_secret(){
    return $this->client_secret;  
    }

    function get_tonken_expire_time(){
    return $this->expires_in;  
    }

  function create_token(){
  	$token_characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $token_charactersLength = strlen($token_characters);
    $token_randomString = '';
    for ($i = 0; $i < $this->token_length; $i++) {
        //$id_randomString .= $id_characters[rand(0, $id_charactersLength - 1)];
        $token_randomString .= substr($token_characters, rand(0, $token_charactersLength - 1), 1);
    }

    $this->token = $token_randomString;
	}

}


?>