<?php
header("Access-Control-Allow-Origin: http://localhost/DigialLibrary");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once 'connection/database.php';
include_once 'core.php';
include 'model/document.php';
include_once 'php-jwt/src/BeforeValidException.php';
include_once 'php-jwt/src/ExpiredException.php';
include_once 'php-jwt/src/SignatureInvalidException.php';
include_once 'php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

$database = new Database();
$conn = $database->getConnection();

$jwt = isset($_POST['jwt']) ? $_POST['jwt'] : "";

if ($jwt) {
    try {
        $decoded = JWT::decode($jwt, $key, array('HS256'));
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array(
            "message" => "Access denied.",
            "output" => "error",
            "error" => $e->getMessage()
        ));
    }
} else {
    http_response_code(401);
    echo json_encode(array(
        "message" => "Access denied.",
        "output" => "error"
    ));
}

$document = new Document($conn);
$document->setName($_POST['name']);
$document->setDescription($_POST['description']);
$document->setFormat($_POST['format']);
$document->setKeywords($_POST['keywords']);
$document->setOwner($decoded->data->id);

if (!$document->setFile($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(array(
        "message" => "Възникна проблем при качването на файл",
        "output" => "error"
    ));
    return;
}

$result = $document->create();
$user = new User($conn);
$user->updateRatingAfterUpload($decoded->data->id);

http_response_code(200);
echo json_encode(array(
    "message" => "Result " . $result,
    "output" => "success"
));
