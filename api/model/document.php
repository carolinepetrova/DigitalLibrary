<?php

class Document
{
    private $id;
    private $name;
    private $description;
    private $keywords;
    private $format;
    private $rating;
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
        $target_dir = "/Applications/XAMPP/xamppfiles/htdocs/DigitalLibrary/storage/";
        $date = date_create();
        if ($this->format == "html") {
            $unzip = new ZipArchive;
            $out = $unzip->open($value['tmp_name']);
            if ($out === TRUE) {
                $filename = pathinfo($value["name"], PATHINFO_FILENAME);
                $unzip->extractTo($target_dir . $this->owner . "_" . date_timestamp_get($date) . "_" . $filename);
                $this->filename =  '/DigitalLibrary/storage/' . $filename . "/" . $filename . '_referat.html';
                $unzip->close();
                return true;
            } else {
                return false;
            }
        } else {
            $target_file = $target_dir . $this->owner . "_" . date_timestamp_get($date) . "_" . $value["name"];
            $this->filename = $target_file;
            if (!move_uploaded_file($value['tmp_name'], $target_file)) {
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
        $queryStr = "SELECT name, description, keywords, format, filename, owner,rating from %s where id = %s";
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
                $this->owner = $row['owner'];
            }
        } else {
            return false;
        }
        return true;
    }
}
