<?php
include_once './php-jwt/src/BeforeValidException.php';
include_once './php-jwt/src/ExpiredException.php';
include_once './php-jwt/src/SignatureInvalidException.php';
include_once './php-jwt/src/JWT.php';
include_once './core.php';

use \Firebase\JWT\JWT;

function checkJWT($jwt)
{
    if ($jwt) {
        try {
            $decoded = JWT::decode($jwt, $GLOBALS["key"], array('HS256'));
            return $decoded;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(array(
                "message" => "Access denied.",
                "output" => "error",
                "error" => $e->getMessage()
            ));
            return "error";
        }
    } else {
        http_response_code(401);
        echo json_encode(array(
            "message" => "Access denied.",
            "output" => "error"
        ));
        return "error";
    }
}
