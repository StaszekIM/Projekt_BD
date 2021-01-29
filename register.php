<?php

declare(encoding='UTF-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'DBConnection.php';

//Start or resume session
session_start();

$method = $_SERVER['REQUEST_METHOD'];
//For GET display register form
if ($method == 'GET') {
    readfile('./register.html');
}
//For POST register and redirect
elseif ($method == 'POST') {

    // Check input, send error message if wrong format has been used
    $check_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    $check_surname = filter_var($_POST['surname'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    $check_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if ($check_email != $_POST['email'] || $check_name != $_POST['name'] || $check_surname != $_POST['surname'])
        readfile('register_error.html');

    //Get phone number in form of only digits
    $phone = '';
    foreach (str_split($_POST['phone']) as $c) {
        try{
            $n = intval($c);
            $phone .= $n;
        }catch(Exception $e) {}
    }
    if (strlen($phone) < 9) readfile('register_error.html'); // Minimal number of digits
    $phone = intval($phone);
    // Getting connection, preparing and executing SQL command
    try {
        $dbconn = Connection::getPDO();
        $sql = 'insert into shop.users (name, surname, email, password, phone) values (:name, :surname, :email, :password, :phone)';
        $stmt = $dbconn->prepare($sql);
        $success = $stmt->execute(array(':name' => $_POST['name'], ':surname' => $_POST['surname'],
            ':email' => $_POST['email'], ':password' => password_hash($_POST['password'], PASSWORD_DEFAULT), ':phone' => $phone,));

        if ($success) {
            readfile('register_success.html');
        }else {
            http_response_code(404);
            readfile('register_error.html');
        }
    }catch (Exception $e) {
        readfile('register_error.html');
    }

}
?>