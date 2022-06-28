<?php

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

echo generateRandomString()."<br>"."now"."<br>";
$chara = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$randomString1 = '';
$c_Length = strlen($chara);

for ($j = 0; $j < 10; $j++) {
        //$randomString1 .= $chara[rand(0, strlen($chara) - 1)];
        $randomString1 .= substr($chara, rand(0, $c_Length - 1), 1);
        echo $randomString1."<br>";
    }
echo $chara[rand(0, 2)];

class Database{
  
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "mockapidb";
    private $username = "root";
    private $password = "";
    public $conn;
  
    // get the database connection
    public function getConnection(){
  
        $this->conn = null;
  
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";port=3308;dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            echo "connected to db";
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
    }
}

$db = new Database();
$db->getConnection();

$tkn = "Bearer fkIE40LNQvw3Uu1SU8hcztXUMnxTtS";
$tkn_array = explode(" ",$tkn);
$token_type = $tkn_array[0];
$token = $tkn_array[1];

echo "<br>"."token type is ".$tkn_array[0];
echo "<br>"."Array has ".count($tkn_array)."elements";
echo "<br>"."token is ".$tkn_array[1];
?>