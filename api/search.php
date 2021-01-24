<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
include_once 'connection/database.php';
include 'model/document.php';
include 'model/user.php';

$database = new Database();
$connection = $database->getConnection();

$document = new Document($connection);
$user = new User($connection);

if(isset($_GET['q']) && $_GET['q']){
    $q = preg_replace('/\s\s+/', ' ', $_GET['q']);

    $words = explode(' ', $q);

    $documents = $document->getDocumentsByKeyWords($words);
    $count = $documents->num_rows;
    
    if($count > 0){
        $documents_array = array();
    
        while ($row = $documents->fetch_assoc()){
            extract($row);
            $user->getById($owner);
            $document_item = array(
                "id" => $id,
                "name" => $name,
                "author" => $user->name,
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
}
else{
    http_response_code(400);

    echo json_encode(array(
        "message" => "No documents found.",
        "status" => 400
        ));
}