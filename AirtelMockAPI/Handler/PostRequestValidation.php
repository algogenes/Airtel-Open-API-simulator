<?php

Class PostValidation{

public $request;
public $array_request;
public $expected = array("reference" => "", 
						"subscriber" => array(
							"country" => "",
							"currency" => "",
							"msisdn" => ""),
						"transaction" => array(
							"amount" => "",
							"country" => "",
							"currency" => "",
							"id" => ""));
//public $grant_type = array("client_credentials");
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
    !empty($this->array_request["reference"]) &&
    !empty($this->array_request["subscriber"]["country"]) &&
    !empty($this->array_request["subscriber"]["currency"]) &&
    !empty($this->array_request["subscriber"]["msisdn"]) &&
    !empty($this->array_request["transaction"]["amount"]) &&
    !empty($this->array_request["transaction"]["country"]) &&
    !empty($this->array_request["transaction"]["currency"]) &&
    !empty($this->array_request["transaction"]["id"])
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
	if(count(array_keys($this->array_request)) === count(array_keys($this->expected)) &&
	   count(array_keys($this->array_request['subscriber']))=== count(array_keys($this->expected['subscriber'])) &&
	   count(array_keys($this->array_request['transaction']))=== count(array_keys($this->expected['transaction']))
	  ){
			return true;
	}
	else{
			 $this->errors += ["Error" => "E018"];
 	$this->errors += ["message" => "Number of params is less than or greater than expected"];
 	return $this->errors;
	}
}

function tally_params_with_expected(){
	if(empty(array_diff(array_keys($this->expected), array_keys($this->array_request))) && 
		empty(array_diff(array_keys($this->expected['subscriber']), array_keys($this->array_request['subscriber']))) &&
		empty(array_diff(array_keys($this->expected['transaction']), array_keys($this->array_request['transaction'])))
	){
		return true;
	}
	else{
		$this->errors += ["Error" => "E019"];
 	$this->errors += ["message" => "One or more params is invalid"];
 	return $this->errors;
	}
}

/*function verify_values(){
	if(in_array($this->array_request["grant_type"], $this->grant_type)){
		return true;
	}
	else{
		$this->errors += ["Error" => "E021"];
 	$this->errors += ["message" => "Unknown value for grant_type"];
 	return $this->errors;
	}
}*/

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

	/*$this->verify_values();
	if(!empty($this->errors)){
		goto errors;
	}*/
	
	return true;
	errors:	
	return $this->errors;
	
}

function check_errors(){
	
}

function verify_datatypes(){
	if(
    is_string($this->array_request["reference"]) &&
    is_string($this->array_request["subscriber"]["country"]) &&
    is_string($this->array_request["subscriber"]["currency"]) &&
    is_string($this->array_request["subscriber"]["msisdn"]) &&
    is_int($this->array_request["transaction"]["amount"]) &&
    is_string($this->array_request["transaction"]["country"]) &&
    is_string($this->array_request["transaction"]["currency"]) &&
    is_string($this->array_request["transaction"]["id"])
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

$a = "{
      \"reference\": \"Testing transaction\",
      \"subscriber\": {
        \"country\": \"UG\",
        \"currency\": \"UGX\",
        \"msisdn\": \"256755548887\"
      },
      \"transaction\": {
        \"amount\": 1000,
        \"country\": \"UG\",
        \"currency\": \"UGX\",
        \"id\": \"12054875\"
      }
}";

?>