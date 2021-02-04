<?php
include "DBConnection.php";
session_start();
if (!isset($_SESSION['id'])) {
    echo '<a href="/login.php">Login first</a>';
    header('Location: https://g22.labagh.pl/login.php?redirect=orders', true, 302);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == "POST"){

    $dbconn = Connection::getPDO();
    $dbconn->beginTransaction();
    try {
        unset($_SESSION['cart']);
        if (count(array_keys($_POST)) > 0) {
            $total = 0;
            $stmt = $dbconn->prepare('select id, available, price from products where id = :id for update');
            // Check availability + calculate total
            foreach (array_keys($_POST) as $id) {
                $stmt->execute([':id' => $id]);
                $res = $stmt->fetch();
                if ($res['available'] < $_POST[$id]) throw new RuntimeException();
                $total += intval($_POST[$id]) * intval($res['price']);
            }
            // Update availability
            $stmt = $dbconn->prepare('update shop.products set available = ((select available from shop.products where id = :id)-' . $_POST[$id] . ') where id = :id');
            foreach (array_keys($_POST) as $id) {
                $success = $stmt->execute([':id' => $id]);
                if (!$success) throw new RuntimeException();
            }
            // Create new order entry
            $stmt = $dbconn->prepare('insert into shop.orders (client, total, "date") values (:uid, :total, current_date) returning id');
            $success = $stmt->execute([':uid' => $_SESSION['id'], ':total' => $total]);
            $oid = $stmt->fetch()['id'];
            // All order parts inserted
            $stmt = $dbconn->prepare('insert into shop.parts (pid, oid, price, amount) values (:pid, :oid, :price, :amount)');
            $price = $dbconn->prepare('select price from shop.products where id = :id');
            foreach (array_keys($_POST) as $id) {
                $price->execute([':id' => $id]);
                $p = $price->fetch()['price'];
                $stmt->execute([':pid' => $id, ':oid' => $oid, ':price' => $p, ':amount' => intval($_POST[$id])]);
            }
            $dbconn->commit();
        }
        //http_send_status(200);
    }catch (Exception $e){
        echo 'Fail';
        $dbconn -> rollBack();
        http_response_code(500);
    }
}

echo '<!DOCTYPE html>
   <html lang="en">
   <head>
       <meta charset="utf-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <title>BD G22</title>
       <link href="css/bootstrap.min.css" rel="stylesheet">
       <link href="css/main.css" rel="stylesheet">
       <link href="css/animate.css" rel="stylesheet">
	   <link href="css/responsive.css" rel="stylesheet">
   </head>
   <body>
   <header id="header">
		<div class="header-middle">
			<div class="container">
				<div class="row">
					<div class="col-md-8 clearfix">
						<div class="shop-menu clearfix pull-right">
							<ul class="nav navbar-nav">
                                <li><a href="cart.php">Cart</a></li>
                                <li><a href="shop.php">Shop</a></li>';
                                echo '<li><a href="login.php"><p id="BLogin" '; if (isset($_SESSION['id'])) echo 'hidden'; echo '>Login</p></a></li>';
                                echo '<li><a onclick="logout();"><p id="BLogout" '; if (!isset($_SESSION['id'])) echo 'hidden'; echo '>Logout</p></a></li>';
                        echo '</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
    </header>
       <div class="container">';
$dbconn = Connection::getPDO();
$stmt = $dbconn -> prepare('select id from orders where client = :id');
$success = $stmt -> execute([':id' => $_SESSION['id']]);
if ($success) {
    $data = $stmt -> fetchALL(PDO::FETCH_ASSOC);
    foreach ($data as $row) {
        echo 'Order ' . $row['id'];
        echo '<div class="table-responsive cart_info">
                <table class="table table-condensed">
				    <thead>
				    	<tr class="cart_menu">
				    		<td class="description" style="width: 50%">Product</td>
				    		<td class="price" style="width: 30%">Price</td>
				    		<td class="quantity" style="width: 20%">Amount</td>
				    	</tr>
				    </thead>
				<tbody id="item_table">';
        $stmt = $dbconn -> prepare('select pid, amount, price from shop.parts where oid = :oid');
        $success = $stmt -> execute(['oid' => $row['id']]);
        if ($success) {
            $parts = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            foreach ($parts as $p) {
                echo '<tr>';
                $stmt = $dbconn -> prepare('select name from products where id = :pid');
                $stmt -> execute([':pid' => $p['pid']]);
                $name = $stmt -> fetch(PDO::FETCH_ASSOC)['name'];
                echo '<td>' . $name . '</td>';
                echo '<td>' . $p['price'] . '</td>';
                echo '<td>' . $p['amount'] . '</td>';
                echo '</tr>';
            }
        }
        echo '</tbody>
			</table>
			</div>';
    }
}
echo '<script src="/js/jquery.js"></script>
	        <script src="/js/bootstrap.min.js"></script>
	        <script src="/js/main.js"></script>
		</div>
		</body>';
http_response_code(200);
