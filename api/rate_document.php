<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
include_once 'connection/database.php';
include 'model/document.php';
include 'model/user.php';
include_once 'util/utils.php';

use \Firebase\JWT\JWT;

$database = new Database();
$connection = $database->getConnection();

$document = new Document($connection);

$data = json_decode(file_get_contents("php://input"));

$jwt_decoded = checkJWT($data->jwt);

if ($jwt_decoded == "error") {
    return;
}

$user_id = $jwt_decoded->data->id;

if($document->getDocument($data->id)){
    $owner = new User($connection);
    if(!$owner->getById($document->getOwner())) {
        http_response_code(404);
        echo json_encode(array(
            "status" => 404
        ));
    }
    $user = new User($connection);
    if(!$user->getById($user_id)) {
        http_response_code(404);
        echo json_encode(array(
            "status" => 404
        ));
    }
    // add points to owner
    $owner->incrementRating($data->rating);
    // add points to user for voting
    $user->incrementRating(1);
    $document->rate($data->rating);
    if($connection->affected_rows == 1) {
        http_response_code(200);
        echo json_encode(array(
            "status" => 200,
        ));
    }
    else {
        http_response_code(500);
        echo json_encode(array(
            "status" => 500
        ));
    }
}
else {
    http_response_code(404);
    echo json_encode(array(
        "status" => 404
    ));
}

