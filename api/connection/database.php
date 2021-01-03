<?php

class Database {
    private $host = "localhost";
    private $db_name = "digitallibrary";
    private $username = "root";
    private $password = "";
    public $connection;

    public function getConnection(){
 
        $this->connection = new mysqli($this->host,$this->username,$this->password,$this->db_name);

        if ($this->connection -> connect_errno) {
            echo "Failed to connect to MySQL: " . $this->connection->connect_error;
            exit();
          }
    
        return $this->connection;
    }
}

?>