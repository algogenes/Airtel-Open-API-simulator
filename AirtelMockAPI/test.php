<?php

class Meagle{
	function isJson($string) {
 json_decode($string);
 //return (json_last_error() == JSON_ERROR_NONE);
 //return (json_last_error());
 return (json_last_error_msg());
}

function callme(){
	echo "hello";
}

/*echo addcslashes("{
      "client_id": "qwerty111-4838-yahl-87-now",
      "client_secret": "f6d539a4-1ae0-4e43-9c79-041729fb920c",
      "grant_type": "client_credentials"
}", '"')*/

function now(){
	$string = "{'Peter':35,'Ben':37,'Joe':43}";

echo "Decoding: " . $string;
json_decode($string);
echo "<br>Error: ";

switch (json_last_error()) {
  case JSON_ERROR_NONE:
    echo "No errors";
    break;
  case JSON_ERROR_DEPTH:
    echo "Maximum stack depth exceeded";
    break;
  case JSON_ERROR_STATE_MISMATCH:
    echo "Invalid or malformed JSON";
    break;
  case JSON_ERROR_CTRL_CHAR:
    echo "Control character error";
    break;
  case JSON_ERROR_SYNTAX:
    echo "Syntax error";
    break;
  case JSON_ERROR_UTF8:
    echo "Malformed UTF-8 characters";
    break;
  default:
    echo "Unknown error";
    break;
}
}



}

$MeagleObj = new Meagle();
$MeagleObj->callme();
echo $MeagleObj->isJson("{
      \"client_id\" \"qwerty111-4838-yahl-87-now\",
      \"client_secret\": \"f6d539a4-1ae0-4e43-9c79-041729fb920c\",
      \"grant_type\": \"client_credentials\"
}");

$MeagleObj->now();



?>