<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
include_once 'connection/database.php';
include 'model/document.php';
include 'model/loaned_document.php';
include 'model/user.php';
include_once 'util/utils.php';

use \Firebase\JWT\JWT;

$database = new Database();
$connection = $database->getConnection();

$document = new Document($connection);

$jwt_decoded = checkJWT($_GET['jwt']);

if ($jwt_decoded == "error") {
    return;
}

$user_id = $jwt_decoded->data->id;

$document = new Document($connection);
$loaned_document = new LoanedDocument($connection);

$documents = $document->getDocumentsOfUser($user_id);
$count = $documents->num_rows;

if($count > 0){
    $documents_array = array();

    while ($row = $documents->fetch_assoc()){
        extract($row);
        $loansCount = $loaned_document->getLoansCount($id);
        $document_item = array(
            "id" => $id,
            "name" => $name,
            "loans_count" => $loansCount,
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