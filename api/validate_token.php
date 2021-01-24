<?php
header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once 'core.php';
include_once 'model/user.php';
include_once 'connection/database.php';
include_once 'php-jwt/src/BeforeValidException.php';
include_once 'php-jwt/src/ExpiredException.php';
include_once 'php-jwt/src/SignatureInvalidException.php';
include_once 'php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

$data = json_decode(file_get_contents("php://input"));


// get jwt
$jwt = isset($data->jwt) ? $data->jwt : "";

if ($jwt) {

    // if decode succeed, show user details
    try {
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        // set response code
        http_response_code(200);
        $database = new Database();
        $conn = $database->getConnection();
        $user = new User($conn);
        $user->getById($decoded->data->id);

        $post_data = [
            'id' => $user->id,
            'name' => $user->name,
            'rating' => $user->rating,
            'email' => $user->email
        ];
        // show user details
        echo json_encode(array(
            "authorization" => "success",
            "message" => "Access granted.",
            "data" => json_encode($post_data)
        ));
    } catch (Exception $e) {

        // set response code
        http_response_code(401);

        // tell the user access denied  & show error message
        echo json_encode(array(
            "authorization" => "failure",
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
} else {

    // set response code
    http_response_code(401);

    // tell the user access denied
    echo json_encode(array("authorization" => "failure", "message" => "Access denied."));
}
