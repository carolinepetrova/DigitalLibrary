<?php

class Database
{
    private $host = "ec2-18-203-62-227.eu-west-1.compute.amazonaws.com";
    private $db_name = "d487pivssqnshp";
    private $username = "nhcffdqziwvpmy";
    private $password = "c8040071f3bc53b48d2d6846cfc898609ff622e1ef385bb4975115944e4afc07";
    private $port = "5432";
    public $connection;

    public function getConnection()
    {

        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->db_name, $this->port);

        if ($this->connection->connect_errno) {
            echo "Failed to connect to MySQL: " . $this->connection->connect_error;
            exit();
        }

        return $this->connection;
    }
}
