<?php
include_once "./util/constants.php";

class Database
{
    private $host = DATABASE_HOST;
    private $db_name = DATABASE_NAME;
    private $username = DATABASE_USERNAME;
    private $password = DATABASE_PASSWORD;
    public $connection;

    public function getConnection()
    {

        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->db_name);

        if ($this->connection->connect_errno) {
            echo "Failed to connect to MySQL: " . $this->connection->connect_error;
            exit();
        }

        return $this->connection;
    }
}
