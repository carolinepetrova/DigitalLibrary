<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");
include_once 'connection/database.php';
include_once 'core.php';
include 'model/document.php';
include 'model/user.php';
include_once 'php-jwt/src/BeforeValidException.php';
include_once 'php-jwt/src/ExpiredException.php';
include_once 'php-jwt/src/SignatureInvalidException.php';
include_once 'php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

$database = new Database();
$conn = $database->getConnection();

$jwt = isset($_GET['jwt']) ? $_GET['jwt'] : "";

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
$user = new User($conn);

if (!$document->getDocument($_GET['id'])) {
    http_response_code(404);
    echo json_encode(array(
        "message" => "Документът не е намерен.",
        "output" => "error"
    ));
}
$user->getById($document->getOwner());

$post_data = [
    'id' => $document->getId(),
    'name' => $document->getName(),
    'description' => $document->getDescription(),
    'keywords' => $document->getKeywords(),
    'filename' => $document->getFilename(),
    'rating' => $document->getRating(),
    'user_name' => $user->name,
];

http_response_code(200);
echo json_encode(array(
    "message" => "Успех",
    "output" => "success",
    "data" => json_encode($post_data)
));
