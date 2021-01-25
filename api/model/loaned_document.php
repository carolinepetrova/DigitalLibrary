<?php

class LoanedDocument
{

    private $conn;
    private $id;
    private $doc_id;
    private $user_id;
    private $date_loaned;
    private $expiration_date;
    private $table_name = "loaned_documents";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function setDocId($value)
    {
        $this->doc_id = htmlspecialchars(strip_tags($value));
    }

    function setUserId($value)
    {
        $this->user_id = htmlspecialchars(strip_tags($value));
    }

    function setDateLoanedConst($value)
    {
        $date_loaned = new DateTime('@' . $value);
        $this->date_loaned = $date_loaned->format('U');
    }

    function setExpirationDateConst($value)
    {
        $exp_date = new DateTime('@' . $value);
        $this->expiration_date = $exp_date->format('U');
    }

    function setDateLoaned()
    {
        $current_date = new Datetime("now");
        $this->date_loaned = $current_date->format('U');
    }

    function setExpirationDate($value)
    {
        $this->expiration_date = strtotime(sprintf("+ %s days", $value), $this->date_loaned);
    }

    public function setLoanedDocument($doc_id, $user_id, $expiration_date)
    {
        $this->setUserId($user_id);
        $this->setDocId($doc_id);
        $this->setDateLoaned();
        $this->setExpirationDate($expiration_date);
    }


    public function getUserId()
    {
        return $this->user_id;
    }

    public function getDocId()
    {
        return $this->doc_id;
    }

    public function getDateLoaned()
    {
        return $this->date_loaned;
    }

    public function getExpirationDate()
    {
        return $this->expiration_date;
    }

    public function submit($token)
    {
        $queryStr = "INSERT INTO %s (doc_id,user_id,date_loaned, expiration_date, token) values('%s', '%s', '%s', '%s','%s')";
        $query = sprintf($queryStr, $this->table_name, $this->doc_id, $this->user_id, $this->date_loaned,  $this->expiration_date, $token);
        if ($this->conn->query($query)) {
            return true;
        }
        return $this->conn->error;
    }

    public function getLoanedDocument($user_id, $doc_id, $date_loaned)
    {
        $queryStr = "SELECT id,expiration_date from %s where user_id = %s and doc_id = %s and date_loaned = '%s'";
        $query = sprintf($queryStr, $this->table_name, $user_id, $doc_id, $date_loaned);
        $result = $this->conn->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->id = $row['id'];
                $this->user_id = $user_id;
                $this->doc_id = $doc_id;
                $this->date_loaned = $date_loaned;
                $this->expiration_date =  $row['expiration_date'];
            }
        } else {
            return false;
        }
        return true;
    }

<<<<<<< Updated upstream
    public function getLoansCount($doc_id){
        $queryStr = "SELECT * from %s WHERE doc_id = %s";
        $query = sprintf($queryStr, $this->table_name, $doc_id);

        $result = $this->conn->query($query);
        
        return $result->num_rows;
=======
    public function checkIfDocIsLoaned()
    {
        $queryStr = "SELECT * from %s where user_id = %s and doc_id = %s and expiration_date > '%s'";
        $query = sprintf($queryStr, $this->table_name, $this->user_id, $this->doc_id, $this->date_loaned);
        $result = $this->conn->query($query);
        return ($result->num_rows > 0);
>>>>>>> Stashed changes
    }
}
