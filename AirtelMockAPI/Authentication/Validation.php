<?php

Class Validation{

public $request;
public $array_request;
public $expected = array("client_id", "client_secret", "grant_type");
public $grant_type = array("client_credentials");
public $errors =array();

public function __construct($request){
$this->request = $request;
    }

function isJson() {
$string = $this->request;
$x = json_decode($string, true);
 if(json_last_error() > 0){
 		$this->errors += ["Error" => "E016"];
 	$this->errors += ["message" => json_last_error_msg()];
 	return $this->errors;
 }
 else{
 	$this->array_request = json_decode($string, true);
 		return true;
 }
}

function get_array_request(){
	return $this->array_request;
}

function params_not_empty(){
	if(
    !empty($this->array_request["client_id"]) &&
    !empty($this->array_request["client_secret"]) &&
    !empty($this->array_request["grant_type"])
	){
		return true;
	}
	else {
	 $this->errors += ["Error" => "E017"];
 	$this->errors += ["message" => "Empty params"];
 	return $this->errors;
	}
}

function number_of_params(){
	//var_dump($this->array_request);
	if(count($this->array_request)=== 3){
			return true;
	}
	else{
			 $this->errors += ["Error" => "E018"];
 	$this->errors += ["message" => "Number of params is less than or greater than expected"];
 	return $this->errors;
	}
}

function tally_params_with_expected(){
	if(empty(array_diff($this->expected, array_keys($this->array_request)))){
		return true;
	}
	else{
		$this->errors += ["Error" => "E019"];
 	$this->errors += ["message" => "One or more params is invalid"];
 	return $this->errors;
	}
}

function verify_values(){
	if(in_array($this->array_request["grant_type"], $this->grant_type)){
		return true;
	}
	else{
		$this->errors += ["Error" => "E021"];
 	$this->errors += ["message" => "Unknown value for grant_type"];
 	return $this->errors;
	}
}

function is_request_valid(){
	$this->isJson();
	if(!empty($this->errors)){
		goto errors;
	}

	$this->number_of_params();
	if(!empty($this->errors)){
		goto errors;
	}

	
	$this->tally_params_with_expected();
	if(!empty($this->errors)){
		goto errors;
	}

	$this->params_not_empty();
	if(!empty($this->errors)){
		goto errors;
	}

	$this->verify_datatypes();
	if(!empty($this->errors)){
		goto errors;
	}

	$this->verify_values();
	if(!empty($this->errors)){
		goto errors;
	}
	
	return true;
	errors:	
	return $this->errors;
	
}

function check_errors(){
	
}

function verify_datatypes(){
	if(
    is_string($this->array_request["client_id"]) &&
    is_string($this->array_request["client_secret"]) &&
    is_string($this->array_request["grant_type"])
	){
		return true;
	}
	else{
		$this->errors += ["Error" => "E020"];
 	$this->errors += ["message" => "One or more params is of wrong datatype"];
 	return $this->errors;
	}

}

}

?>