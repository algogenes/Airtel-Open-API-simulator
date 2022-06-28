<?php

require_once "AuthorisationHandler.php";
require_once "AuthResponseHandler.php";
require_once "Validation.php";
require_once "../Config/DB.php";
require_once "../Config/DBuser.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get posted deta
$deta = file_get_contents("php://input");

$isValidObject = new Validation($deta);
$validity = $isValidObject->is_request_valid();

//echo var_dump($deta);
//die();

if($validity === true)
{
 	http_response_code(200);
    $data = $isValidObject->get_array_request();
    $authObject = new Authorise($data["client_id"], $data["client_secret"], $data["grant_type"]);
    $dbObject = new Database();
    $userObject = new User($dbObject->getConnection());
    $response = new AuthResponse();

    //check if user is new or already exists
    if(($userObject->user_exists($authObject->getclient_id()))){


       
        $current_token = $userObject->get_token($authObject->getclient_id());
        
        // if token is valid
        if($userObject->verify_token_validity($userObject->get_token($authObject->getclient_id())) === true){

            //return valid token
            echo $response->return_response($current_token, $authObject->get_tonken_expire_time());

        }
        
        else{//generate new token
        $authObject->create_token();

        //update token in DB
        $update_status = $userObject->update($authObject->getToken(), $authObject->getclient_id());

        //check if update was success and return error response if not
        if(!$update_status){
            echo json_encode(array("message" => "DB error."));
        }

        //return response
        echo $response->return_response($authObject->getToken(), $authObject->get_tonken_expire_time());
        }

        // echo var_dump($authObject->getToken());
        //die();

        

        
    }
    else{
        //create new user 
        $authObject->create_token();

        //add new user to db
        $update_status = $userObject->create($authObject->getclient_secret(), $authObject->getclient_id(), $authObject->getToken());

        //check if update was success and return error response if not
        if(!$update_status){
            echo json_encode(array("message" => "DB error."));
        }

        //return response
        echo $response->return_response($authObject->getToken(), $authObject->get_tonken_expire_time());
        }

    }

    else{
    // set response code to success i.e 200
    http_response_code(200);

    // tell the user the error
    echo json_encode($isValidObject->is_request_valid());
    }

    ?>