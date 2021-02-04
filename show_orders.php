<?php

include 'DBConnection.php';
session_start();
$dbconn = Connection::getPDO();
$stmt = $dbconn -> prepare('select id from orders where client = :id');
$success = $stmt -> execute([':id' => $_SESSION['id']]);
if ($success) {
    $data = $stmt -> fetchALL(PDO::FETCH_ASSOC);
    foreach ($data as $row) {
        echo 'Order ' . $row['id'];
        echo '<table class="table table-condensed">
					<thead>
						<tr class="cart_menu">
							<td class="">Product</td>
							<td class="price">Price</td>
							<td class="quantity">Amount</td>
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
				</table>';
    }
}