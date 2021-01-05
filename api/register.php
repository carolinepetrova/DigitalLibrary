<?php
header("Access-Control-Allow-Origin: http://localhost/DigialLibrary");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'connection/database.php';
include_once 'model/user.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    header($_SERVER["SERVER_PROTOCOL"] . " 405 Method Not Allowed", true, 405);
    exit;
}

$database = new Database();
$conn = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

$user = new User($conn);
$user->setName($data->name);
$user->setEmail($data->email);
$user->setPassword($data->password);

// create the user
if (
    !empty($user->name) && !empty($user->email) &&
    !empty($user->password) && $user->create()
) {

    http_response_code(200);

    // display message: user was created
    echo json_encode(array("output" => "success", "message" => "Успешна регистрация! Ще бъдете пренасочени към логин страницата след 5 секунди."));
}

// message if unable to create user
else {

    // set response code
    http_response_code(400);

    // display message: unable to create user
    echo json_encode(array("output" => "error", "message" => "Възникна грешка при създаването на потребител."));
}
$conn->close();
