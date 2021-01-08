<?php

class User
{
    // database connection and table name
    private $conn;
    private $table_name = "users";

    // object properties
    public $id;
    public $name;
    public $email;
    public $password;

    // constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function setName($name)
    {
        $this->name = htmlspecialchars(strip_tags($name));
    }

    function setEmail($email)
    {
        $this->email = htmlspecialchars(strip_tags($email));
    }

    function setPassword($password)
    {
        $password_unhashed = htmlspecialchars(strip_tags($password));
        $this->password = password_hash($password_unhashed, PASSWORD_BCRYPT);
    }
    // create new user record
    function create()
    {
        $queryStr = "INSERT INTO %s (name,email,password) values('%s', '%s', '%s')";
        $query = sprintf($queryStr, $this->table_name, $this->name, $this->email, $this->password);
        // execute the query, also check if query was successful
        if ($this->conn->query($query)) {
            return true;
        }
        return false;
    }
    function checkIfExists()
    {
        $queryStr = "select id,name,email,password from %s where email = '%s'";
        $query = sprintf($queryStr, $this->table_name, $this->email);
        $result = $this->conn->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->password = $row['password'];
            }
        } else {
            return false;
        }
        return true;
    }

    function getById($id)
    {
        $queryStr = "select name,email from %s where id = %s";
        $query = sprintf($queryStr, $this->table_name, $id);
        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->name = $row['name'];
                $this->email = $row['email'];
            }
        } else {
            return false;
        }
        return true;
    }
}
