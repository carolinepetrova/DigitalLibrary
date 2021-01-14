<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");
include_once 'connection/database.php';
include_once 'model/document.php';
include_once 'model/user.php';
include_once 'model/loaned_document.php';
include_once 'util/utils.php';

use \Firebase\JWT\JWT;

if (empty($_GET['jwt'])) {
    http_response_code(400);
    echo json_encode(array(
        "message" => "Неправилна GET заявка",
        "output" => "error"
    ));
    return;
}

$database = new Database();
$conn = $database->getConnection();
$jwt = isset($_GET['jwt']) ? $_GET['jwt'] : "";

$jwt_decoded = checkJWT($jwt);

if ($jwt_decoded == "error") {
    return;
}

$user_id = $jwt_decoded->data->id;

$user = new User($conn);
if (!$user->getById($user_id)) {
    http_response_code(404);
    echo json_encode(array(
        "message" => "Потребителят не е намерен.",
        "output" => "error",
        "error" => $e->getMessage()
    ));
    return;
}

$queryStr = "select loaned_documents.token, loaned_documents.expiration_date, documents.name as doc_name, documents.format, documents.rating, documents.filename, users.name as user_name from loaned_documents inner join documents on documents.id = loaned_documents.doc_id inner join users on documents.owner = users.id where user_id= %s";
$query = sprintf($queryStr, $user_id);
$result = $conn->query($query);
$arr = array();
$i = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $arr[$i++] = array(
            "token" => $row['token'],
            "exp_date" => $row['expiration_date'],
            "doc_name" => $row['doc_name'],
            "format" => $row['format'],
            "rating" => $row['rating'],
            "owner" => $row['user_name']
        );
    }
} else {
    echo json_encode(
        array(
            "output" => "failure",
            "data" => "Нямате заети документи"
        )
    );
}

echo json_encode(
    array(
        "output" => "success",
        "data" => json_encode($arr)
    )
);
