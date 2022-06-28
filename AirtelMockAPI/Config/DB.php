<?php
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
            //echo "connected to db";
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
    }
}

?>