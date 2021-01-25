<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST'){
    http_response_code(405);
    return;
}
session_start();
try {
    array_push($_SESSION['cart'], $_POST['id']);
    http_response_code(200);
}catch (Exception $e) {
    http_response_code(500);
}

?>
