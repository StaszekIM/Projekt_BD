<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>BD G22</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/price-range.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
	<link href="css/responsive.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       
    <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
</head><!--/head-->
<body>


<section>
<?php include 'manage.php' ?>

<br><br><br>
<-- -----------------------------------------------------------Edit---------------------------- --><br>
<-- -----------------Edit BRAND---------------------------- --><br>
<form action="" method='post'>
    <label for="edit_brand_id">Edit brand - id:</label><br>
	<input type="number" min="0" step="1" value="0" id="edit_brand_id" name="edit_brand_id"><br>
    <label for="edit_brand_name">New Brand name:</label><br>
	<input type="text" id="edit_brand_name" name="edit_brand_name"><br>
	<button type="submit" name="edit_brand" >Edit Brand</button>
		
</form> 
<br><br><br>
<-- -----------------Edit category---------------------------- --><br>
parent_id==0 => root category <br>
parent_category== "" 'empty' -- not pid change
<form action="" method='post'>
    <label for="edit_category_id">Edit category - id:</label><br>
	<input type="number" min="1" step="1" value="1" id="edit_category_id" name="edit_category_id"><br>
    <label for="edit_category_name">New category name:</label><br>
	<input type="text" id="edit_category_name" name="edit_category_name"><br>
	<label for="edit_category_parent_category_id">Edit parent category - id:</label><br>
	<input type="number" min="0" step="1" value="0" id="edit_category_parent_category_id" name="edit_category_parent_category_id"><br>
	<button type="submit" name="edit_category" >Edit category</button>
		
</form> 

<br><br><br>
<-- -----------------Edit product---------------------------- --><br>
<form action="" method='post'>

	<label for="edit_product_id">Edit product - id:</label><br>
	<input type="number" min="1" step="1" value="1" id="edit_product_id" name="edit_product_id"><br>
	
    <label for="edit_product_name">edit Product name:</label><br>
	<input type="text" id="edit_product_name" name="edit_product_name"><br>
	
	<label for="edit_product_category_id">edit Product Category name:</label><br>
	<input type="number" min="1" step="1" value="1" id="edit_product_category_id" name="edit_product_category_id"><br>

	<label for="edit_product_brand_name">edit Product Brand name:</label><br>
	<input type="text" id="edit_product_brand_name" name="edit_product_brand_name"><br>
	
	<label for="edit_product_price">edit Product price:</label><br>
	<input type="number" step=0.01 min="0" id="edit_product_price" name="edit_product_price" value="0.00"><br>
	
	<label for="edit_product_available">edit Product available:</label><br>
	<input type="number" step=1 min="0" id="edit_product_available" name="edit_product_available" value="0" ><br>
	
	<label for="edit_product_description">edit Product description:</label><br>
	<input type="text" id="edit_product_description" name="edit_product_description"><br>
	
	<button type="submit" name="edit_product" >edit Product</button>
		
</form>   



<br><br><br>
<-- -----------------------------------------------------------DELETE---------------------------- --><br>
<-- -----------------DELETE BRAND---------------------------- --><br>
<form action="" method='post'>
    <label for="delete_brand_name">Delete Brand - name:</label><br>
	<input type="text"  id="delete_brand_name" name="delete_brand_name"><br>
	<button type="submit" name="delete_brand" >Delete Brand</button>
		
</form> 

<br><br>
<-- -----------------DELETE category---------------------------- --><br>
<form action="" method='post'>
    <label for="delete_category_id">Delete category - id:</label><br>
	<input type="number" min="0" step="1" value="0" id="delete_category_id" name="delete_category_id"><br>
	<button type="submit" name="delete_category" >Delete Category</button>
		
</form> 
<br><br><br>

<-- -----------------DELETE product---------------------------- --><br>
<form action="" method='post'>
    <label for="delete_product_id">Delete product - id:</label><br>
	<input type="number" min="0" step="1" value="0" id="delete_product_id" name="delete_product_id"><br>
	<button type="submit" name="delete_product" >Delete product</button>
		
</form> 
<br><br><br>
<-- -----------------------------------------------------------ADD---------------------------- --><br>
<-- -----------------ADD BRAND---------------------------- --><br>


<form action="" method='post'>
    <label for="add_brand_name">Brand name:</label><br>
	<input type="text" id="add_brand_name" name="add_brand_name"><br>
	<button type="submit" name="add_brand" >Add Brand</button>
		
</form> 



<br>
<-- -----------------ADD CATEGORY---------------------------- --><br>
parent_id==0 => root category
<form action="" method='post'>
    <label for="add_category_name">Category name:</label><br>
	<input type="text" id="add_category_name" name="add_category_name"><br>
	
	<label for="add_category_parent_category_name">Parent Category name:</label><br>
	<input type="number" min="0" step="1" value="0" id="add_category_parent_category_id" name="add_category_parent_category_id"><br>

	<button type="submit" name="add_category" >Add Category</button>
		
</form> 
<br>
<-- -----------------ADD PRODUCT---------------------------- --><br>
<form action="" method='post'>
    <label for="add_product_name">Product name:</label><br>
	<input type="text" id="add_product_name" name="add_product_name"><br>
	
	<label for="add_product_category_name">Product Category name:</label><br>
	<input type="text" id="add_product_category_name" name="add_product_category_name"><br>

	<label for="add_product_brand_name">Product Brand name:</label><br>
	<input type="text" id="add_product_brand_name" name="add_product_brand_name"><br>
	
	<label for="add_product_price">Product price:</label><br>
	<input type="number" step=0.01 min="0" id="add_product_price" name="add_product_price" value="0.00"><br>
	
	<label for="add_product_available">Product available:</label><br>
	<input type="number" step=1 min="0" id="add_product_available" name="add_product_available" value="0" ><br>
	
	<label for="add_product_description">Product description:</label><br>
	<input type="text" id="add_product_description" name="add_product_description"><br>
	
	<button type="submit" name="add_product" >Add Product</button>
		
</form>    



</section>
  
    <script src="js/jquery.js"></script>
	<script src="js/price-range.js"></script>
    <script src="js/jquery.scrollUp.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/main.js"></script>
    <script src="js/cart.js"></script>
</body>
</html>