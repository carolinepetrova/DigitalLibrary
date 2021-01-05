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
        if (empty($this->keywords)) {
            $this->keywords = str_replace(' ', ',', $this->name);
        }
        $this->keywords = $this->keywords + "," + htmlspecialchars(strip_tags($value));
    }

    function setFormat($value)
    {
        $this->format = htmlspecialchars(strip_tags($value));
    }

    function setRating($value)
    {
        $this->rating = htmlspecialchars(strip_tags($value));
    }

    function setFile($value)
    {
        $target_dir = "./";
        $date = date_create();
        $target_file = $target_dir . $this->owner . "_" . date_timestamp_get($date) . $value["name"];
        $this->filename = $target_file;
        if (!move_uploaded_file($value['tmp_name'], $target_file)) {
            return false;
        }
        return true;
    }

    function setOwner($value)
    {
        $this->owner = htmlspecialchars(strip_tags($value));
    }

    function create($data)
    {
        //TODO check if exists?
        $this->setName($data->name);
        $this->setDescription($data->description);
        $this->setKeywords($data->keywords);
        $this->setFormat($data->format);
        $this->setFilename($data->filename);
        $this->setOwner($data->filename);

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
            return true;
        }
        return false;
    }
}
