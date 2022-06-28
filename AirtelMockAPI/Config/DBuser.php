<?php

class User{
  
    // database connection and table name
    private $conn;
    private $table_name = "users";
  
    // object properties
    public $client_secret;
    public $clientID;
    public $access_token;
    //public $expires_in = 432000;
    public $expires_in = 120;
    public $expiry_date;
    public $date_created;
    public $token_type = "bearer";
    public $created_at;
    public $modified_at;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

// create user
function create($client_secret, $clientID, $access_token){
  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
        SET
            client_secret=:client_secret, clientID=:clientID, access_token=:access_token, token_type=:token_type, expires_in=:expires_in";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $client_secret=htmlspecialchars(strip_tags($client_secret));
    $clientID=htmlspecialchars(strip_tags($clientID));
    $access_token=htmlspecialchars(strip_tags($access_token));
    $this->token_type=htmlspecialchars(strip_tags($this->token_type));
    $this->expires_in=htmlspecialchars(strip_tags($this->expires_in));
  
    // bind values
    $stmt->bindParam(":client_secret", $client_secret);
    $stmt->bindParam(":clientID", $clientID);
    $stmt->bindParam(":access_token", $access_token);
    $stmt->bindParam(":token_type", $this->token_type);
    $stmt->bindParam(":expires_in", $this->expires_in);
  
    // execute query
    if($stmt->execute()){
        $this->set_token_expiry($access_token);
        
        return true;
    }
  
    return false;
      
}

function get_token($c_ID){
  
    // query to get a single token
    $query = "SELECT
              p.access_token
            FROM
                " . $this->table_name . " p
            WHERE
                p.clientID = ?
            LIMIT
                0,1";
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind id of product to be updated
    $stmt->bindParam(1, $c_ID);
  
    // execute query
    $stmt->execute();
  
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
    // set values to object properties
    //$this->clientID = $row['access_token'];
    return $row['access_token'];
}

// update the user 
function update($token, $cli_ID){
  
    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                access_token = :access_token
            WHERE
                clientID = :clientID";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $token=htmlspecialchars(strip_tags($token));
    $cli_ID=htmlspecialchars(strip_tags($cli_ID));
  
    // bind new values
    $stmt->bindParam(':access_token', $token);
    $stmt->bindParam(':clientID', $cli_ID);

    // execute the query
    if($stmt->execute()){
        $this->update_token_expiry($token);
        return true;
    }
  
    return false;
}

function user_exists($cID){

    // query to check if user exists in db single record
    $query = "SELECT clientID FROM " . $this->table_name . " WHERE clientID = :clientID";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $cID=htmlspecialchars(strip_tags($cID));
  
    // bind id of record to select
    $stmt->bindParam(':clientID', $cID);

    // execute query
    if($stmt->execute()){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if( ! $row)
        {
        return false;
        }
        
        return true;
    }
  
    return false;
}

function token_exists($token){

    // query to check if token exists in db 
    $query = "SELECT access_token FROM " . $this->table_name . " WHERE access_token = :access_token";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $token=htmlspecialchars(strip_tags($token));
  
    // bind id of record to select
    $stmt->bindParam(':access_token', $token);

    // execute query
    if($stmt->execute()){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if( ! $row)
        {
        return false;
        }
        
        return true;
    }
  
    return false;
}

function set_token_expiry($token){

    $expiry_date = $this->get_current_date($token);
    $publisher = [
    'expiry_date' => $expiry_date,
    'access_token' => $token
];

$sql = 'UPDATE users
        SET expiry_date = :expiry_date
        WHERE access_token = :access_token';

// prepare statement
//$statement = $pdo->prepare($sql);
$statement = $this->conn->prepare($sql);

$date = $this->get_current_date($token);

    //$timestamp = strtotime($date) + 60*60;
    $timestamp = strtotime($date) + $this->expires_in;
    $newdate = date('d-m-y H:i:s', $timestamp);
    //current_date is 19-05-22 14: 52: 47

// bind params
$statement->bindParam(':expiry_date', $newdate, PDO::PARAM_STR);
$statement->bindParam(':access_token', $publisher['access_token']);

// execute the UPDATE statment
if ($statement->execute()) {
    //echo 'The expiry_date has been updated successfully!';
    return true;
}
return false;
}

function verify_token_validity($token){

    //if current date is less than expiry date return true, else false
    date_default_timezone_set('Africa/Nairobi');
    $current_date = date('d-m-y H:i:s');
    $expiry_date = $this->get_expiry_date($token);

    $timestamp = strtotime($expiry_date);
    //$new_expiry_date = date('d-m-y H:i:s', $timestamp);
    $new_expiry_date = date('y-m-d H:i:s', $timestamp);

    /*echo var_dump($new_expiry_date);
    echo var_dump($current_date);*/
    

    if ($current_date < $new_expiry_date){
    //echo "valid";
    return true;
        
    }
    else{
      //  echo "is not valid";
    return false;
    }
    
}

function token_validity($token){

    //if current date is less than expiry date return true, else false
    date_default_timezone_set('Africa/Nairobi');
    $current_date = date('y-m-d H:i:s');
    $expiry_date = $this->get_expiry_date($token);

    $timestamp = strtotime($expiry_date);
    //$new_expiry_date = date('d-m-y H:i:s', $timestamp);
    $new_expiry_date = date('y-m-d H:i:s', $timestamp);

    /*echo var_dump($new_expiry_date);
    echo var_dump($current_date);*/
    

    if ($current_date < $new_expiry_date){
    //echo "valid";
    return true;
        
    }
    else{
      //  echo "is not valid";
    return false;
    }
    
}

function get_current_date($access_token){
    $sql = 'SELECT created_at, expires_in 
        FROM users
        WHERE access_token = :access_token';
$statement = $this->conn->prepare($sql);
//$statement = $pdo->prepare($sql);
$statement->bindParam(':access_token', $access_token, PDO::PARAM_STR);
$statement->execute();
$publisher = $statement->fetch(PDO::FETCH_ASSOC);

if ($publisher) {
    //echo $publisher['created_at']. '.' . $publisher['expires_in'];
} else {
    //echo "The publisher with access_token $access_token was not found.";
}

    // set values to object properties
    $this->date_created = $publisher['created_at'];
    //print_r($this->expiry_date);
    //die();
    return $this->date_created;

}

function get_expiry_date($access_token){
    $sql = 'SELECT expiry_date, expires_in 
        FROM users
        WHERE access_token = :access_token';
$statement = $this->conn->prepare($sql);
//$statement = $pdo->prepare($sql);
$statement->bindParam(':access_token', $access_token, PDO::PARAM_STR);
$statement->execute();
$publisher = $statement->fetch(PDO::FETCH_ASSOC);

if ($publisher) {
    //echo $publisher['expiry_date']. '.' . $publisher['expires_in'];
} else {
    //echo "The expiry_date with access_token $access_token was not found.";
}

    // set values to object properties
    $this->expiry_date = $publisher['expiry_date'];
    //print_r($this->expiry_date);
    //die();
    return $this->expiry_date;

}

function update_token_expiry($token){


    //$expiry_date = $this->get_current_date($token);
    $publisher = [
    //'expiry_date' => $expiry_date,
    'access_token' => $token
];

$sql = 'UPDATE users
        SET expiry_date = :expiry_date
        WHERE access_token = :access_token';

// prepare statement
//$statement = $pdo->prepare($sql);
$statement = $this->conn->prepare($sql);

$date = date('d-m-y H:i:s');

    //$timestamp = strtotime($date) + 60*60;
    $timestamp = strtotime($date) + $this->expires_in;
    $newdate = date('d-m-y H:i:s', $timestamp);
    //current_date is 19-05-22 14: 52: 47

// bind params
$statement->bindParam(':expiry_date', $newdate, PDO::PARAM_STR);
$statement->bindParam(':access_token', $publisher['access_token']);

// execute the UPDATE statment
if ($statement->execute()) {
    //echo 'The expiry_date has been updated successfully!';
    return true;
}
return false;

}




}

?>