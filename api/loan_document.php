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

if (empty($_GET['jwt']) || empty($_GET['doc_id']) || empty($_GET['expiration_date'])) {
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

// check if user has rating - done
// substract points if has and update user - done
// add points to owner of document
// update loaned_documents
// generate document token 

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

if ($user->rating < $_GET['points']) {
    echo json_encode(array(
        "message" => "Нямате достатъчно точки за този документ.",
        "output" => "error"
    ));
    return;
}

$document = new Document($conn);
if (!$document->getDocument($_GET['doc_id'])) {
    http_response_code(404);
    echo json_encode(array(
        "message" => "Документът не е намерен.",
        "output" => "error",
        "error" => $e->getMessage()
    ));
    return;
}

$ownerOfDocument = new User($conn);
if (!$ownerOfDocument->getById($document->getOwner())) {
    http_response_code(404);
    echo json_encode(array(
        "message" => "Потребителят не е намерен.",
        "output" => "error",
        "error" => $e->getMessage()
    ));
    return;
}

$conn->begin_transaction();

if (!$user->decrementRating($_GET['points'])) {
    echo json_encode(array(
        "message" => "Възникна грешка.",
        "output" => "error"
    ));
    return;
}

if (!$ownerOfDocument->incrementRating($_GET['points'])) {
    echo json_encode(array(
        "message" => "Възникна грешка.",
        "output" => "error"
    ));
    return;
}

$loaned_document = new LoanedDocument($conn);
$loaned_document->setLoanedDocument($_GET['doc_id'], $user_id, $_GET['expiration_date']);

$token = array(
    "iat" => $issued_at,
    "exp" => time() + 3600 * 24 *  $_GET['expiration_date'], // this is the formula for days
    "iss" => $issuer,
    "data" => array(
        "user_id" => $loaned_document->getUserId(),
        "doc_id" => $loaned_document->getDocId(),
        "date_loaned" => $loaned_document->getDateLoaned()
    )
);

$jwt = JWT::encode($token, $key);

$result = $loaned_document->submit($jwt);

$conn->commit();
if (!$result) {
    echo json_encode(array(
        "message" => "Документът не успя да бъде зает.",
        "output" => "error"
    ));
    $conn->rollback();
    return;
}

echo json_encode(
    array(
        "output" => "success",
        "message" => "Successful login.",
        "jwt" => $jwt
    )
);
