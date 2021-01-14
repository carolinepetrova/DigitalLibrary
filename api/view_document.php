<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");
include_once 'connection/database.php';
include_once 'model/loaned_document.php';
include_once 'model/document.php';
include_once 'util/utils.php';

use \Firebase\JWT\JWT;

if (empty($_GET['jwt']) || empty($_GET['docKey'])) {
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

$doc_key_decoded = checkJWT($_GET['docKey']);

if ($doc_key_decoded == "error") {
    return;
}

$doc_id = $doc_key_decoded->data->doc_id;
$user_id = $doc_key_decoded->data->user_id;
$date_loaned = $doc_key_decoded->data->date_loaned;

$loaned_document = new LoanedDocument($conn);

if (!$loaned_document->getLoanedDocument($user_id, $doc_id, $date_loaned)) {
    http_response_code(400);
    echo json_encode(array(
        "message" => "Не сте заявявали следният документ.",
        "output" => "error"
    ));
    return;
}

$exp_date = new DateTime('@' . $loaned_document->getExpirationDate());
$exp_date_formatted = $exp_date->format('U');
$now = new Datetime("now");
$now_formatted = $now->format('U');

if ($now_formatted > $exp_date_formatted) {
    http_response_code(400);
    echo json_encode(array(
        "message" => "Изтече вашият период за заемане на документа.",
        "output" => "error"
    ));
    return;
}

$document = new Document($conn);
if (!$document->getDocument($doc_id)) {
    http_response_code(404);
    echo json_encode(array(
        "message" => "Документът не е намерен.",
        "output" => "error"
    ));
    return;
}

echo json_encode(
    array(
        "output" => "success",
        "url" => $document->getFilename()
    )
);
