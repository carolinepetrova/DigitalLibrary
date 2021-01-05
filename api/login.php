<?php
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once 'connection/database.php';
include_once 'model/user.php';
include_once 'core.php';
include_once 'php-jwt/src/BeforeValidException.php';
include_once 'php-jwt/src/ExpiredException.php';
include_once 'php-jwt/src/SignatureInvalidException.php';
include_once 'php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    header($_SERVER["SERVER_PROTOCOL"] . " 405 Method Not Allowed", true, 405);
    exit;
}

$database = new Database();
$conn = $database->getConnection();

$user = new User($conn);
$data = json_decode(file_get_contents("php://input"));
$user->setEmail($data->email);

if (!$user->checkIfExists()) {
    http_response_code(401);
    echo json_encode(array("output" => "error", "message" => "Потребителят не съществува."));
} else if (password_verify($data->password, $user->password)) {
    $token = array(
        "iat" => $issued_at,
        "exp" => $expiration_time,
        "iss" => $issuer,
        "data" => array(
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email
        )
    );

    // set response code
    http_response_code(200);

    // generate jwt
    $jwt = JWT::encode($token, $key);
    echo json_encode(
        array(
            "output" => "success",
            "message" => "Successful login.",
            "jwt" => $jwt
        )
    );
} else {
    http_response_code(401);
    echo json_encode(array("output" => "error", "message" => "Грешна парола."));
}
$conn->close();
