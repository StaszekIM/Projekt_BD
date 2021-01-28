<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST'){
    http_response_code(405);
    return;
}
session_start();
try {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = array();
    $val = intval($_POST['id']);
    if (!in_array($val, $_SESSION['cart'])) array_push($_SESSION['cart'], $val);
    http_response_code(200);
}catch (Exception $e) {
    http_response_code(500);
}

?>
