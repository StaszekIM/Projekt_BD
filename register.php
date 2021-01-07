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

    //Get phone number in form of only digits
    $phone = '';
    foreach (str_split($_POST['phone']) as $c) {
        try{
            $n = intval($c);
            $phone .= $n;
        }catch(Exception $e) {}
    }
    $phone = intval($phone);
    // Getting connection, preparing and executing SQL command
    $dbconn = Connection::getPDO();    
    $sql = 'insert into users.users (name, surname, email, password, phone) values (:name, :surname, :email, :password, :phone)';
    $stmt = $dbconn -> prepare($sql);
    $success = $stmt -> execute(array(':name' => $_POST['name'], ':surname' => $_POST['surname'],
        ':email' => $_POST['email'], ':password' => password_hash($_POST['password'], PASSWORD_DEFAULT), ':phone' => $phone,));

    if ($success) {
        readfile('register_success.html');
    }else {
        http_response_code(404);
        readfile('register_error.html');
    }

}
?>