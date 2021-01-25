<?php
include_once "./util/constants.php";

class Document
{
    private $id;
    private $name;
    private $description;
    private $keywords;
    private $format;
    private $rating;
    private $rating_sum;
    private $votes_num;
    public $filename;
    private $owner;
    private $table_name = "documents";
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function setName($value)
    {
        $this->name = htmlspecialchars(strip_tags($value));
    }

    function setDescription($value)
    {
        $this->description = htmlspecialchars(strip_tags($value));
    }

    function setKeywords($value)
    {
        $this->keywords = htmlspecialchars(strip_tags($value));
    }

    function setFormat($value)
    {
        $this->format = htmlspecialchars(strip_tags($value));
    }

    function setRating($value)
    {
        $this->rating = htmlspecialchars(strip_tags($value));
    }

    function getId()
    {
        return $this->id;
    }

    function getName()
    {
        return $this->name;
    }

    function getDescription()
    {
        return $this->description;
    }

    function getKeywords()
    {
        return $this->keywords;
    }

    function getFilename()
    {
        return $this->filename;
    }

    function getOwner()
    {
        return $this->owner;
    }

    function getRating()
    {
        return $this->rating;
    }

    function setFile($value)
    {
        $target_dir = SAVE_PATH;
        $date = date_create();
        if ($this->format == "html") {
            $unzip = new ZipArchive;
            $out = $unzip->open($value['tmp_name']);
            if ($out === TRUE) {
                $filename = pathinfo($value["name"], PATHINFO_FILENAME);
                $folder_name = $this->owner . "_" . date_timestamp_get($date) . "_" . $filename;
                $unzip->extractTo($target_dir . $folder_name);
                $this->filename =  SAVE_DIR . $folder_name . "/" . $filename . '_referat.html';
                $unzip->close();
                return true;
            } else {
                return false;
            }
        } else {
            $target_full = $target_dir . $this->owner . "_" . date_timestamp_get($date) . "_" . $value["name"];

            $this->filename =  SAVE_DIR . $this->owner . "_" . date_timestamp_get($date) . "_" . $value["name"];;
            if (!move_uploaded_file($value['tmp_name'], $target_full)) {
                return false;
            }
            return true;
        }
    }

    function setOwner($value)
    {
        $this->owner = htmlspecialchars(strip_tags($value));
    }

    function create()
    {
        $queryStr = "INSERT INTO %s (name,description,keywords,format, filename, owner) values('%s', '%s', '%s','%s', '%s', '%s')";
        $query = sprintf(
            $queryStr,
            $this->table_name,
            $this->name,
            $this->description,
            $this->keywords,
            $this->format,
            $this->filename,
            $this->owner
        );
        // execute the query, also check if query was successful
        if ($this->conn->query($query)) {
            return "";
        }
        return $this->conn->error;
    }

    function getDocument($id)
    {
        $queryStr = "SELECT name, description, keywords, format, filename, owner, rating, rating_sum, votes_num from %s where id = %s";
        $query = sprintf($queryStr, $this->table_name, $id);

        $result = $this->conn->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->id = $id;
                $this->name = $row['name'];
                $this->description = $row['description'];
                $this->keywords = $row['keywords'];
                $this->format = $row['format'];
                $this->filename = $row['filename'];
                $this->rating = $row['rating'];
                $this->rating_sum = $row['rating_sum'];
                $this->votes_num = $row['votes_num'];
                $this->owner = $row['owner'];
            }
        } else {
            return false;
        }
        return true;
    }

    function getDocuments()
    {
        $queryStr = "SELECT * from %s ORDER BY rating DESC LIMIT 5";
        $query = sprintf($queryStr, $this->table_name);

        $result = $this->conn->query($query);

        return $result;
    }

    function getDocumentsByKeyWords($words){
        $query = "SELECT * FROM `documents` WHERE ";
        foreach ($words as $word){
            $query .= "UPPER(keywords) LIKE UPPER('%".$word."%') OR UPPER(name) LIKE UPPER('%".$word."%') OR ";
        }
        
        $query = substr($query, 0, strlen($query)-4);
        $query .= " ORDER By rating DESC";
        $result = $this->conn->query($query);

        return $result;
    }

    function rate($rating) {
        $this->rating_sum += $rating;
        $this->votes_num += 1;
        $this->rating = (float) $this->rating_sum/ (float) $this->votes_num;
        $this->rating = round($this->rating, 1);

        $queryStr = "UPDATE %s SET rating_sum = %s, votes_num = %s, rating = %s WHERE id = %s";
        $query = sprintf($queryStr, $this->table_name, $this->rating_sum, $this->votes_num, $this->rating, $this->id);
        $this->conn->query($query);
    }
}
