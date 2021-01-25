<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
include_once 'connection/database.php';
include 'model/document.php';

$database = new Database();
$connection = $database->getConnection();

$document = new Document($connection);

$data = json_decode(file_get_contents("php://input"));
$document = $document->deleteDocument($data->id);
$count = $connection->affected_rows;

if($count > 0) {
    http_response_code(200);
    echo json_encode(array(
        "status" => 200
    ));
    
}
else {
    http_response_code(500);
    echo json_encode(array(
        "status" => 500
    ));
}