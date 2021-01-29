<?php

include 'DBConnection.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] != 'POST'){
    http_response_code(405);
    return;
}
$dbconn = Connection::getPDO();
$stmt = $dbconn -> prepare("select id from shop.users where email = :email");
$success = $stmt -> execute([':email' => $_POST['email']]);

if ($success) {
    $row = $stmt -> fetch();
    if (!isset($row['id'])){
        http_response_code(200);
    }else {
        http_response_code(409);
    }
}else {
    http_response_code(500);
}

?>