<?php 
include 'DBConnection.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'GET'){

    readfile('./login.html');

}elseif ($method == 'POST'){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $dbconn = Connection::getPDO();
    $stmt = $dbconn -> prepare('select "password", "id" from shop.users where email = :email');
    $success = $stmt -> execute(['email' => $username]);
    $row = $stmt -> fetch(PDO::FETCH_ASSOC);

    if ($success) {
        if ($stmt -> rowCount() && password_verify($password, $row['password'])) {
            $_SESSION['id'] = $row['id'];
            echo 'Success';
            header("Location: http://g22.labagh.pl/shop.php", true, 302);
        }else {
            http_response_code(401);
            readfile('./login.html');
            echo '<script>document.getElementById("banner").innerHTML = "Wrong credentials, try again";</script>';
        }
    }else {
        echo 'Query failed';
    }

}
?>
