<?php

class User {
    // database connection and table name
    private $conn;
    private $table_name = "users";
 
    // object properties
    public $name;
    public $email;
    public $password;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
    // create new user record
function create(){
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->password=htmlspecialchars(strip_tags($this->password));
    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
    
    $query = "INSERT INTO " .$this->table_name . "(name,email,password) values('" 
    . $this->name . "','" .$this->email . "','". $password_hash . "')";
    
    // execute the query, also check if query was successful
    if($this->conn->query($query)){
        return true;
    }
    return false;
}
 
// emailExists() method will be here
}
?>