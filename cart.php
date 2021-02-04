<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>BD G22</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
	<link href="css/responsive.css" rel="stylesheet">
</head><!--/head-->

<body>
	<header id="header"><!--header-->
		<div class="header-middle"><!--header-middle-->
			<div class="container">
				<div class="row">
					<div class="col-md-8 clearfix">
						<div class="shop-menu clearfix pull-right">
							<ul class="nav navbar-nav">
                                <li><a href="cart.php">Cart</a></li>
                                <?php
                                include "DBConnection.php";
                                session_start();
                                echo '<li><a href="login.php"><p id="BLogin" '; if (isset($_SESSION['id'])) echo 'hidden'; echo '>Login</p></a></li>';
                                echo '<li><a onclick="logout();"><p id="BLogout" '; if (!isset($_SESSION['id'])) echo 'hidden'; echo '>Logout</p></a></li>';
                                ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div><!--/header-middle-->
	
		<div class="header-bottom"><!--header-bottom-->
			<div class="container">
				<div class="row">
					<div class="col-sm-9">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<div class="mainmenu pull-left">
							<ul class="nav navbar-nav collapse navbar-collapse">
								<li><a href="shop.php">Home</a></li>
								<li class="dropdown"><a href="#">Shop<i class="fa fa-angle-down"></i></a>
                                    <ul role="menu" class="sub-menu">
                                        <li><a href="shop.php">Products</a></li>
										<li><a href="cart.php" class="active">Cart</a></li>
                                    </ul>
                                </li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div><!--/header-bottom-->
	</header><!--/header-->

	<section id="cart_items">
		<div class="container">
			<div class="breadcrumbs">
				<ol class="breadcrumb">
				  <li><a href="#">Home</a></li>
				  <li class="active">Shopping Cart</li>
				</ol>
			</div>
            <form action="/orders.php" method="POST">
			<div class="table-responsive cart_info">
				<table class="table table-condensed">
					<thead>
						<tr class="cart_menu">
							<td class="image">Item</td>
							<td class="description"></td>
							<td class="price">Price</td>
							<td class="quantity">Quantity</td>
							<td class="total">Total</td>
							<td></td>
						</tr>
					</thead>
					<tbody id="item_table">
                    <?php
                    $dbconn = Connection::getPDO();
                    foreach ($_SESSION['cart'] as $item) {
                        $stmt = $dbconn -> prepare('select "name", price from shop.products where id = :id');
                        $stmt -> execute(['id' => $item]);
                        $res = $stmt -> fetch();
                        $name = $res['name'];
                        echo '<tr>
							<td class="cart_product"> <a>-</a>
								' . //<a href=""><img src="images/" alt=""></a>
							'</td>
                    
							<td class="cart_description">
								<h4><a href="/details?prod=' . intval($item) . '">' . $name . '</a></h4>
							</td>
							<td class="cart_price">
								<h4><a>' . $res['price'] . '</a></h4>
							</td>
							<td class="cart_quantity">
								<div class="cart_quantity_button">
									<!-- <a class="cart_quantity_up" href=""> + </a> -->
									<input class="cart_quantity_input" type="number" name="' . $item . '" value="2" autocomplete="off" size="2" onchange="calculate_total();">
									<!-- <a class="cart_quantity_down" href=""> - </a> -->
								</div>
							</td>
							<td class="cart_total">
								<p class="cart_total_price"></p>
							</td>
							<td class="cart_delete">
								<a class="btn" onclick="delete_from_cart(this);">Delete</a>
							</td>
						</tr>';
					}
					?>
					</tbody>
				</table>
			</div>
            <div class="container">
                <div class="heading">
                    <h3>Summary</h3>
                </div>
                <input type="submit" class="btn btn-default check_out" value="Buy">
                <!--
                <div class="row">
                    <div class="col-sm-6">
                        <div class="total_area">
                            <ul>
                                <li>Total <span>$61</span></li>
                            </ul>
                            Input should be moved here
                        </div>
                    </div>
                </div>
                -->
            </div>
            </form>
		</div>
	</section> <!--/#cart_items-->

	<section id="do_action">

	</section><!--/#do_action-->

    <script src="/js/jquery.js"></script>
	<script src="/js/bootstrap.min.js"></script>
	<script src="/js/jquery.scrollUp.min.js"></script>
    <script src="/js/jquery.prettyPhoto.js"></script>
    <script src="/js/main.js"></script>
    <script src="/js/total.js"></script>
    <script src="/js/cart.js"></script>
</body>
</html>