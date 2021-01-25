<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
include_once 'connection/database.php';
include 'model/document.php';
include 'model/user.php';
include_once 'util/utils.php';

use \Firebase\JWT\JWT;

$database = new Database();
$connection = $database->getConnection();

$jwt_decoded = checkJWT($_GET['jwt']);

if ($jwt_decoded == "error") {
    return;
}

$user_id = $jwt_decoded->data->id;

$document = new Document($connection);
$author = new User($connection);

$documents = $document->getDocuments($user_id);
$count = $documents->num_rows;

if($count > 0){
    $documents_array = array();

    while ($row = $documents->fetch_assoc()){
        extract($row);
        $author->getById($owner);
        $document_item = array(
            "id" => $id,
            "name" => $name,
            "author" => $author->name,
            "rating" => $rating
        );
  
        array_push($documents_array, $document_item);
    }
    
    http_response_code(200);
    echo json_encode(array(
        "data" => json_encode($documents_array),
        "status" => 200
    ));
    
}
else{
    http_response_code(404);

    echo json_encode(array(
        "message" => "No documents found.",
        "status" => 404
        ));
}