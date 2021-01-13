<?php
// Product listing
$dbconn = Connection::getPDO();
if (isset($_GET['cat'])) {

    if (isset($_GET['brd'])) {

        $stmt = $dbconn -> prepare('select * from shop.products where bid = (select id from shop.brands where "name" = :bname) and cid = (select id from categories where "name" = :cname)');
        $stmt -> execute(['cname' => $_GET['cat'], 'bname' => $_GET['brd']]);
        $data = $stmt -> fetchAll();

    }else {

        $stmt = $dbconn -> prepare('select * from shop.products where cid = (select id from categories where "name" = :cname)');
        $stmt -> execute(['cname' => $_GET['cat'], 'bname' => $_GET['brd']]);
        $data = $stmt -> fetchAll();

    }

}elseif (isset($_GET['brd'])) {

    $stmt = $dbconn -> prepare('select * from shop.products where bid = (select id from shop.brands where "name" = :bname)');
    $stmt -> execute(['cname' => $_GET['cat'], 'bname' => $_GET['brd']]);
    $data = $stmt -> fetchAll();

}else {

    $stmt = $dbconn -> prepare('select * from shop.products');
    $stmt -> execute(['cname' => $_GET['cat'], 'bname' => $_GET['brd']]);
    $data = $stmt -> fetchAll();

}

// Buying
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        $dbconn = Connection::getPDO();
        $dbconn -> beginTransaction();
        foreach ($cart as $item) {
            $stmt = $dbconn -> prepare('select available from shop.products where id = :id for update');
            $stmt -> execute([':id' => $item]);
            if ($stmt -> fetch(PDO::FETCH_ASSOC)['available'] < 1) throw new RuntimeException();
        }
    }

}

// Add to cart
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['id'])) {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = array();
        array_push($_SESSION['cart'], $_POST['id']);
    }

}

?>