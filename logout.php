<?php

session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_SESSION['id'])) {
        unset($_SESSION['id']);
        http_response_code(200);
    }else {
        http_response_code(401);
    }
}else {
    http_response_code(405);
}
