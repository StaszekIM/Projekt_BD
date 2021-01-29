<?php
include "DBConnection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST"){
    session_start();
    if (!isset($_SESSION['id'])) {
        echo '<a href="/login.php">Login first</a>';
        http_send_status(401);
    }
    $dbconn = Connection::getPDO();
    $dbconn->beginTransaction();
    try {
        unset($_SESSION['cart']);
        $total = 0;
        $stmt = $dbconn->prepare('select id, available, price from products where id = :id for update');
        // Check availability + calculate total
        foreach (array_keys($_POST) as $id) {
            $stmt -> execute([':id' => $id]);
            $res = $stmt -> fetch();
            if ($res['available'] < $_POST[$id]) throw new RuntimeException();
            $total += intval($_POST[$id]) * intval($res['price']);
        }
        echo 'Checked';
        // Update availability
        $stmt = $dbconn -> prepare('update shop.products set available = ((select available from shop.products where id = :id)-'.$_POST[$id].') where id = :id');
        foreach (array_keys($_POST) as $id) {
            $success = $stmt -> execute([':id' => $id]);
            if (!$success) throw new RuntimeException();
        }
        echo 'Updated';
        // Create new order entry
        $stmt = $dbconn -> prepare('insert into shop.orders (client, total, "date") values (:uid, :total, current_date) returning id');
        $success = $stmt -> execute([':uid' => $_SESSION['id'], ':total' => $total]);
        $oid = $stmt -> fetch()['id'];
        echo 'Order created';
        // All order parts inserted
        $stmt = $dbconn -> prepare('insert into shop.parts (pid, oid, price, amount) values (:pid, :oid, :price, :amount)');
        $price = $dbconn -> prepare('select price from shop.products where id = :id');
        foreach (array_keys($_POST) as $id) {
            echo $id;
            $price -> execute([':id' => $id]);
            $p = $price -> fetch()['price'];
            echo $p;
            $stmt -> execute([':pid' => $id, ':oid' => $oid, ':price' => $p, ':amount' => intval($_POST[$id])]);
        }
        echo 'Done';
        $dbconn->commit();
        http_send_status(200);
    }catch (Exception $e){
        echo 'Fail';
        echo $e;
        $dbconn -> rollBack();
        http_send_status(500);
    }
}