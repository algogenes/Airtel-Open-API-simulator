<?php

class Response{
	private $id_length = 13;
	private $result_code_length = 6;
	private $response_code_length = 11;

	function return_response(){
		return json_encode(array(
			"data"=> array(
        "transaction"=>array (
          "id"=> $this->generate_id(),
          "status"=> "SUCCESS"
      )
      ),
      "status"=> array(
        "code"=> "200",
        "message"=> "SUCCESS",
        "result_code"=> $this->generate_resultcode(),
        "response_code"=> $this->generate_response_code(),
        "success"=> true
      )
		));
	}

	function generate_id(){
	$id_characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $id_charactersLength = strlen($id_characters);
    $id_randomString = '';
    for ($i = 0; $i < $this->id_length; $i++) {
        //$id_randomString .= $id_characters[rand(0, $id_charactersLength - 1)];
        $id_randomString .= substr($id_characters, rand(0, $id_charactersLength - 1), 1);
    }
    return $id_randomString;
	}

	function generate_resultcode(){
	$resultcode_characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $resultcode_charactersLength = strlen($resultcode_characters);
    $resultcode_randomString = '';
    for ($i = 0; $i < $this->result_code_length; $i++) {
        //$resultcode_randomString .= $resultcode_characters[rand(0, $resultcode_charactersLength - 1)];
        $resultcode_randomString .= substr($resultcode_characters, rand(0, $resultcode_charactersLength - 1), 1);
    }
    $resultcode_randomString = "ESB".$resultcode_randomString;
    return $resultcode_randomString;

	}

	function generate_response_code(){
	$response_code_characters = '0123456789';
    $response_code_charactersLength = strlen($response_code_characters);
    $response_code_randomString = '';
    for ($i = 0; $i < $this->response_code_length; $i++) {
        //$response_code_randomString .= $response_code_characters[rand(0, $response_code_charactersLength - 1)];
        $response_code_randomString .= substr($response_code_characters, rand(0, $response_code_charactersLength - 1), 1);
    }
    $response_code_randomString = "DP".$response_code_randomString;
    return $response_code_randomString;
	}

	function extract_access_token($header_array){
        if (substr($header_array["Authorization"], 0, 7) === "Bearer ") {
            $troken = explode(" ", $header_array["Authorization"]);

        return $troken[1];
        }
        return null;
	}


}


?>