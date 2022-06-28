<?php
require_once "ResponseHandler.php";
require_once "PostRequestValidation.php";
require_once "../Config/DB.php";
require_once "../Config/DBuser.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get posted data
$data = file_get_contents("php://input");
$headers = apache_request_headers();
//echo var_dump($headers);

$responseobject = new Response();
$dbO = new Database();
$userO = new User($dbO->getConnection());
$validate = new PostValidation($data);

//extracts access token
$tkn = $responseobject->extract_access_token(apache_request_headers());

//check if access token exists in DB and is valid
if($userO->token_exists($tkn) && $userO->verify_token_validity($tkn))
{


    // make sure request is valid
if($validate->is_request_valid()){
    // set response code - 200 i.e success
    http_response_code(200);
    //echo json_encode($data);

    //return response
    echo $responseobject->return_response();
    }

    else{
    // set response code - 503 Error in request
    http_response_code(503);

    // tell the user
    echo json_encode($validate->is_request_valid());
    }
    
} 
else{
    echo json_encode(array("message" => "Unknown user or expired token."));
} 

    ?>