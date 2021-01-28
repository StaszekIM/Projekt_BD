<?php

if ($_SERVER['REQUEST_METHOD'] != 'GET'){
    http_response_code(405);
    return;
}
session_start();
try {
    if (isset($_SESSION['cart'])) {
        $val = intval($_GET['id']);
        foreach (array_keys($_SESSION['cart']) as $key) {
            if ($_SESSION['cart'][$key] == $val) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
    }
    http_response_code(200);
}catch (Exception $e) {
    http_response_code(500);
}

?>
<?php
