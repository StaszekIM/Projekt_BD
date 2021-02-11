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
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>        
</head><!--/head-->
<body>


<section>
<?php include 'manage.php' ?>

<nav class="navbar navbar-dark bg-dark">
  <a class="navbar-brand" href="./shop.php"> <h2>Return</h2></a>
</nav>


<div class="container"> 
<div id="accordion" role="tablist" aria-multiselectable="true">
    <div class="card" style="border: 1px solid; padding: 16px; border-radius: 16px; margin: 8px">
        <h3 class="card-header" role="tab" id="headingOne" >
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="d-block">
                <i class="fa fa-chevron-down pull-right"></i> Brands
            </a>
        </h3>

        <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="card-body" style="padding: 8px;margin: 4px">
                        
            	<div >
            		<table class="table table-bordered">
  						<thead>
    						<tr>
      							<th scope="col">#</th>
      							<th scope="col">Name</th>    
    						</tr>
  						</thead>
  						<tbody>
  						
  						<?php 
  						try{
  						$sql = 'SELECT * FROM shop.brands ORDER BY id;';
  						$dbconn->beginTransaction();
  						$stmt = $dbconn -> prepare($sql);
  						$stmt -> execute();
  						$dbconn->commit();
  						
  						while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  						    echo '<tr>
                                  <th scope="col">' . $row['id'] . '</th>
                                  <th scope="col">' . $row['name'] . '</th>
                                  </tr>';
  						}
  						
  						}catch(Exception $e){
  						    echo 'error';
  						}
  						?>
  						</tbody>
  					</table>			
            	</div>
                
                
                
                <div id="accordionBrands" role="tablist" aria-multiselectable="true">
                               
                	<div class="card" style="border: 1px solid; padding: 8px; border-radius: 8px; margin: 4px" >
                
                		<h4 class="card-header" role="tab" id="headingOneBrands" >
            				<a data-toggle="collapse" data-parent="#accordionBrands" href="#collapseOneBrands" aria-expanded="true" aria-controls="collapseOneBrands" class="d-block">
               				 	<i class="fa fa-chevron-down pull-right"></i> Add Brand
            				</a>
        				</h4>
                	
                		<div id="collapseOneBrands" class="collapse" role="tabpanel" aria-labelledby="headingOneBrands">
                	
                			<div class="card-body">
                				<form action="" method='post'>
                					<div class="form-group">
    									<label for="add_brand_name">Brand name:</label><br>
										<input type="text" class="form-control" id="add_brand_name" name="add_brand_name"><br>
									</div>
									<button type="submit" name="add_brand" >Add Brand</button>		
								</form> 
                			</div>               	
                		</div>             	
                	</div>
                
                	<div class="card" style="border: 1px solid; padding: 8px; border-radius: 8px; margin: 4px" >
                		<h4 class="card-header" role="tab" id="headingTwoBrands" >
            					<a data-toggle="collapse" data-parent="#accordionBrands" href="#collapseTwoBrands" aria-expanded="true" aria-controls="collapseTwoBrands" class="d-block">
               				 		<i class="fa fa-chevron-down pull-right"></i> Edit Brand
            					</a>
        				</h4>
   
   
   						<div id="collapseTwoBrands" class="collapse" role="tabpanel" aria-labelledby="headingTwoBrands">
                			<div class="card-body">
                				<form action="" method='post'>
                					<div class="form-group">
    									<label for="edit_brand_id">Edit Brand - ID:</label><br>
										<select id="edit_brand_id" class="form-control" name="edit_brand_id">
									<?php 
									try{
									    $sql = 'SELECT id FROM shop.brands ORDER BY id;';
									    $dbconn->beginTransaction();
									    $stmt = $dbconn -> prepare($sql);
									    $stmt -> execute();
									    $dbconn->commit();
									    
									    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									        echo '<option value="' . $row['id'] . '">' . $row['id'] .'</option> ';
									    }
									    
									}catch(Exception $e){
									    echo 'error';
									}
									?>
										</select>
    								</div>	
    								<div class="form-group">
    									<label for="edit_brand_name">New Brand name:</label><br>
										<input type="text" class="form-control" id="edit_brand_name" name="edit_brand_name"><br>
									</div>
									<button type="submit" name="edit_brand" >Edit Brand</button>		
								</form> 
                			</div>
                		</div>
                	</div>
                		
                		
                <div class="card" style="border: 1px solid; padding: 8px; border-radius: 8px; margin: 4px" >
                	<h4 class="card-header" role="tab" id="headingThreeBrands" >
            					<a data-toggle="collapse" data-parent="#accordionBrands" href="#collapseThreeBrands" aria-expanded="true" aria-controls="collapseThreeBrands" class="d-block">
               				 		<i class="fa fa-chevron-down pull-right"></i> Delete Brand
            					</a>
        				</h4>
					
					<div id="collapseThreeBrands" class="collapse" role="tabpanel" aria-labelledby="headingThreeBrands">
                	<div class="card-body">
                		<form action="" method='post'>
                			<div class="form-group">
    							<label for="delete_brand_id">Delete Brand - ID:</label><br>
								<select id="delete_brand_id" class="form-control" name="delete_brand_id">
									<?php 
									try{
									    $sql = 'SELECT id FROM shop.brands ORDER BY id;';
									    $dbconn->beginTransaction();
									    $stmt = $dbconn -> prepare($sql);
									    $stmt -> execute();
									    $dbconn->commit();
									    
									    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									        echo '<option value="' . $row['id'] . '">' . $row['id'] .'</option> ';
									    }
									    
									}catch(Exception $e){
									    echo 'error';
									}
									?>
								</select>
							</div>
							<button type="submit" name="delete_brand" >Delete Brand</button>		
						</form>
                	</div>
                	</div>
                	
                	
                </div>
              </div>  
       
                
                
                
                
                          
            </div>        
            
        </div>
        
        
        
        
        
    </div>
    <div class="card" style="border: 1px solid; padding: 16px; border-radius: 16px; margin: 8px">
        <h3 class="card-header" role="tab" id="headingTwo">
            <a class="collapsed d-block" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <i class="fa fa-chevron-down pull-right"></i> Categories
            </a>
        </h3>
        <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo">
            <div class="card-body" style="padding: 8px; margin: 4px">
      
                
                <div >
            		<table class="table table-bordered">
  						<thead>
    						<tr>
      							<th scope="col">#</th>
      							<th scope="col">Name</th>  
      							<th scope="col">Parent ID</th>  
    						</tr>
  						</thead>
  						<tbody>
  						
  						<?php 
  						try{
  						$sql = 'SELECT * FROM shop.categories ORDER BY id;';
  						$dbconn->beginTransaction();
  						$stmt = $dbconn -> prepare($sql);
  						$stmt -> execute();
  						$dbconn->commit();
  						
  						while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  						    echo '<tr>
                                  <th scope="col">' . $row['id'] . '</th>
                                  <th scope="col">' . $row['name'] . '</th>
                                  <th scope="col">' . $row['parent_id'] . '</th>
                                  </tr>';
  						}
  						
  						}catch(Exception $e){
  						    echo 'error';
  						}
  						?>
  						</tbody>
  					</table>			
            	</div>
            	
            	<div id="accordionCategories" role="tablist" aria-multiselectable="true">
            	
                <div class="card" style="border: 1px solid; padding: 8px; border-radius: 8px; margin: 4px" >
                	<h4 class="card-header" role="tab" id="headingOneCategories" >
            				<a data-toggle="collapse" data-parent="#accordionCategories" href="#collapseOneCategories" aria-expanded="true" aria-controls="collapseOneCategories" class="d-block">
               				 	<i class="fa fa-chevron-down pull-right"></i> Add Category
            				</a>
        				</h4>
        				
        			<div id="collapseOneCategories" class="collapse" role="tabpanel" aria-labelledby="headingOneCategories">
                	<div class="card-body">
                		<form action="" method='post'>
                			<div class="form-group">
    							<label for="add_category_name">Category name:</label><br>
								<input type="text" class="form-control" id="add_category_name" name="add_category_name"><br>
							</div>
							<div class="form-group">
								<label for="add_category_parent_category_id">Parent Category ID</label><br>
								<select id="add_category_parent_category_id" name="add_category_parent_category_id">
									<?php 
									try{
									    $sql = 'SELECT id FROM shop.categories ORDER BY id;';
									    $dbconn->beginTransaction();
									    $stmt = $dbconn -> prepare($sql);
									    $stmt -> execute();
									    $dbconn->commit();
									    
									    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									        echo '<option value="' . $row['id'] . '">' . $row['id'] .'</option> ';
									    }
									    
									}catch(Exception $e){
									    echo 'error';
									}
									?>
								</select>
							</div>
							<button type="submit" name="add_category" >Add Category</button>		
						</form> 
                	</div>
                	</div>
                	
                	
                </div>
                <div class="card" style="border: 1px solid; padding: 8px; border-radius: 8px; margin: 4px" >
                	<h4 class="card-header" role="tab" id="headingTwoCategories" >
            				<a data-toggle="collapse" data-parent="#accordionCategories" href="#collapseTwoCategories" aria-expanded="true" aria-controls="collapseTwoCategories" class="d-block">
               				 	<i class="fa fa-chevron-down pull-right"></i> Edit Category
            				</a>
        				</h4>
                	<div id="collapseTwoCategories" class="collapse" role="tabpanel" aria-labelledby="headingTwoCategories">
                	<div class="card-body">                		
                		<form action="" method='post'>
                			<div class="form-group">
    							<label for="edit_category_id">Edit category - ID:</label><br>
								<select id="edit_category_id" class="form-control" name="edit_category_id">
									<?php 
									try{
									    $sql = 'SELECT id FROM shop.categories ORDER BY id;';
									    $dbconn->beginTransaction();
									    $stmt = $dbconn -> prepare($sql);
									    $stmt -> execute();
									    $dbconn->commit();
									    
									    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									        echo '<option value="' . $row['id'] . '">' . $row['id'] .'</option> ';
									    }
									    
									}catch(Exception $e){
									    echo 'error';
									}
									?>
								</select>
    						</div>
    						<div class="form-group">
    							<label for="edit_category_name">Edit Category Name:</label><br>
								<input type="text" class="form-control" id="edit_category_name" name="edit_category_name"><br>
							</div>
							<div class="form-group">
								<label for="edit_category_parent_category_id">Edit Parent Category ID:</label><br>
								<select id="edit_category_parent_category_id" class="form-control" name="edit_category_parent_category_id">
									<?php 
									try{
									    $sql = 'SELECT id FROM shop.categories ORDER BY id;';
									    $dbconn->beginTransaction();
									    $stmt = $dbconn -> prepare($sql);
									    $stmt -> execute();
									    $dbconn->commit();
									    
									    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									        echo '<option value="' . $row['id'] . '">' . $row['id'] .'</option> ';
									    }
									    
									}catch(Exception $e){
									    echo 'error';
									}
									?>
								</select>
							</div>
							<button type="submit" name="edit_category" >Edit Category</button>
		
						</form> 
                	</div>
                	</div>
                </div>
                <div class="card" style="border: 1px solid; padding: 8px; border-radius: 8px; margin: 4px" >
                	<h4 class="card-header" role="tab" id="headingThreeCategories" >
            				<a data-toggle="collapse" data-parent="#accordionCategories" href="#collapseThreeCategories" aria-expanded="true" aria-controls="collapseThreeCategories" class="d-block">
               				 	<i class="fa fa-chevron-down pull-right"></i> Delete Category
            				</a>
        				</h4>
        			<div id="collapseThreeCategories" class="collapse" role="tabpanel" aria-labelledby="headingThreeCategories">
                	<div class="card-body">
                		<form action="" method='post'>
                			<div class="form-group">
    							<label for="delete_category_id">Delete category - id:</label><br>
								<select id="delete_category_id" class="form-control" name="delete_category_id">
									<?php 
									try{
									    $sql = 'SELECT id FROM shop.categories ORDER BY id;';
									    $dbconn->beginTransaction();
									    $stmt = $dbconn -> prepare($sql);
									    $stmt -> execute();
									    $dbconn->commit();
									    
									    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									        echo '<option value="' . $row['id'] . '">' . $row['id'] .'</option> ';
									    }
									    
									}catch(Exception $e){
									    echo 'error';
									}
									?>
								</select>
							</div>	
							<button type="submit" name="delete_category" >Delete Category</button>
						</form>
                	</div>
                	</div>
                </div>
                
                
                </div>
            </div>
        </div>
    </div>
    
    
    
    <div class="card" style="border: 1px solid; padding: 16px; border-radius: 16px; margin: 8px">
        <h3 class="card-header" role="tab" id="headingThree">
            <a class="collapsed d-block" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                <i class="fa fa-chevron-down pull-right"></i> Products
            </a>
        </h3>
        <div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree">
            <div class="card-body" style="padding: 8px; margin: 4px">
                
                <div >
            		<table class="table table-bordered">
  						<thead>
    						<tr>
      							<th scope="col">#</th>
      							<th scope="col">Name</th>  
      							<th scope="col">Category ID</th>  
      							<th scope="col">Brand ID</th>  
      							<th scope="col">Available</th>  
      							<th scope="col">Price</th>  
      							<th scope="col">Description</th>  
								<th scope="col">Discounted to</th>
    						</tr>
  						</thead>
  						<tbody>
  						
  						<?php 
  						try{
  						$sql = 'SELECT * FROM shop.products LEFT JOIN (SELECT pid, newprice FROM shop.sales) AS sales ON products.id = sales.pid ORDER BY id;';
  						$dbconn->beginTransaction();
  						$stmt = $dbconn -> prepare($sql);
  						$stmt -> execute();
  						$dbconn->commit();
  						
  						while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
							
  						    echo '<tr>
                                  <th scope="col">' . $row['id'] . '</th>
                                  <th scope="col">' . $row['name'] . '</th>
                                  <th scope="col">' . $row['cid'] . '</th>
                                  <th scope="col">' . $row['bid'] . '</th>
                                  <th scope="col">' . $row['available'] . '</th>
                                  <th scope="col">' . $row['price'] . '</th>
                                  <th scope="col">' . $row['description'] . '</th>
								  <th scope="col">' . $row['newprice'];
								  if(!empty($row['newprice'])) echo ', -' . floor(100 - 100*$row['newprice']/$row['price']) . '%';
							echo '</th>
                                  </tr>';
  						}
  						
  						}catch(Exception $e){
  						    echo 'error';
  						}
  						?>
  						</tbody>
  					</table>			
            	</div>
            	
            	<div id="accordionProducts" role="tablist" aria-multiselectable="true">
            	
                <div class="card" style="border: 1px solid; padding: 8px; border-radius: 8px; margin: 4px" >
                	<h4 class="card-header" role="tab" id="headingOneProducts" >
            				<a data-toggle="collapse" data-parent="#accordionProducts" href="#collapseOneProducts" aria-expanded="true" aria-controls="collapseOneProducts" class="d-block">
               				 	<i class="fa fa-chevron-down pull-right"></i> Add Product
            				</a>
        				</h4>
        			<div id="collapseOneProducts" class="collapse" role="tabpanel" aria-labelledby="headingOneProducts">
                	
                	<div class="card-body">
                		<form action="" method='post'>
                			<div class="form-group">
    							<label for="add_product_name">Product name:</label><br>
								<input type="text" class="form-control" id="add_product_name" name="add_product_name"><br>
							</div>
							
							<div class="form-group">
								<label for="add_product_category_id">Product Category ID:</label><br>
								<select id="add_product_category_id" class="form-control" name="add_product_category_id">
									<?php 
									try{
									    $sql = 'SELECT id FROM shop.categories ORDER BY id;';
									    $dbconn->beginTransaction();
									    $stmt = $dbconn -> prepare($sql);
									    $stmt -> execute();
									    $dbconn->commit();
									    
									    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									        echo '<option value="' . $row['id'] . '">' . $row['id'] .'</option> ';
									    }
									    
									}catch(Exception $e){
									    echo 'error';
									}
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="add_product_brand_id">Product Brand ID:</label><br>
								<select id="add_product_brand_id" class="form-control" name="add_product_brand_id">
									<?php 
									try{
									    $sql = 'SELECT id FROM shop.brands ORDER BY id;';
									    $dbconn->beginTransaction();
									    $stmt = $dbconn -> prepare($sql);
									    $stmt -> execute();
									    $dbconn->commit();
									    
									    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									        echo '<option value="' . $row['id'] . '">' . $row['id'] .'</option> ';
									    }
									    
									}catch(Exception $e){
									    echo 'error';
									}
									?>
								</select>
							</div>
							
							
							<div class="form-group">
								<label for="add_product_available">Product Available:</label><br>
								<input type="number" class="form-control" step=1 min="0" id="add_product_available" name="add_product_available" value="0" ><br>
							</div>
							<div class="form-group">
								<label for="add_product_price">Product Price:</label><br>
								<input type="number" class="form-control" step=0.01 min="0" id="add_product_price" name="add_product_price" value="0.00"><br>
							</div>							
							<div class="form-group">
								<label for="add_product_description">Product Description:</label><br>
								<input type="textarea" class="form-control" id="add_product_description" name="add_product_description"><br>
							</div>
							<button type="submit" name="add_product" >Add Product</button>
		
						</form> 
                	</div>
                	</div>
                </div>
                <div class="card" style="border: 1px solid; padding: 8px; border-radius: 8px; margin: 4px" >
                	<h4 class="card-header" role="tab" id="headingTwoProducts" >
            				<a data-toggle="collapse" data-parent="#accordionProducts" href="#collapseTwoProducts" aria-expanded="true" aria-controls="collapseTwoProducts" class="d-block">
               				 	<i class="fa fa-chevron-down pull-right"></i> Edit Product
            				</a>
        				</h4>
                	<div id="collapseTwoProducts" class="collapse" role="tabpanel" aria-labelledby="headingTwoProducts">
                	<div class="card-body">
                		<h6>You need to fill whole form</h6>
                		<form action="" method='post'>
							<div class="form-group">
							<label for="edit_product_id">Edit Product - ID:</label><br>
							<select id="edit_product_id" class="form-control"name="edit_product_id">
									<?php 
									try{
									    $sql = 'SELECT id FROM shop.products ORDER BY id;';
									    $dbconn->beginTransaction();
									    $stmt = $dbconn -> prepare($sql);
									    $stmt -> execute();
									    $dbconn->commit();
									    
									    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									        echo '<option value="' . $row['id'] . '">' . $row['id'] .'</option> ';
									    }
									    
									}catch(Exception $e){
									    echo 'error';
									}
									?>
								</select>
							</div>
							<div class="form-group">
    						<label for="edit_product_name">Edit Product Name:</label><br>
							<input type="text" id="edit_product_name" class="form-control" name="edit_product_name">
							</div>
							<div class="form-group">	
							<label for="edit_product_category_id">Edit Product Category ID:</label><br>
							<select id="edit_product_category_id" class="form-control" name="edit_product_category_id">
									<?php 
									try{
									    $sql = 'SELECT id FROM shop.categories ORDER BY id;';
									    $dbconn->beginTransaction();
									    $stmt = $dbconn -> prepare($sql);
									    $stmt -> execute();
									    $dbconn->commit();
									    
									    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									        echo '<option value="' . $row['id'] . '">' . $row['id'] .'</option> ';
									    }
									    
									}catch(Exception $e){
									    echo 'error';
									}
									?>
								</select>
							</div>
							<div class="form-group">
							<label for="edit_product_brand_id">Edit Product Brand ID:</label><br>
							<select id="edit_product_brand_id" class="form-control" name="edit_product_brand_id">
									<?php 
									try{
									    $sql = 'SELECT id FROM shop.brands ORDER BY id;';
									    $dbconn->beginTransaction();
									    $stmt = $dbconn -> prepare($sql);
									    $stmt -> execute();
									    $dbconn->commit();
									    
									    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									        echo '<option value="' . $row['id'] . '">' . $row['id'] .'</option> ';
									    }
									    
									}catch(Exception $e){
									    echo 'error';
									}
									?>
								</select>				
							</div>
							<div class="form-group">	
							<label for="edit_product_price">Edit Product Price:</label><br>
							<input class="form-control" type="number" step=0.01 min="0" id="edit_product_price" name="edit_product_price" value="0.00"><br>
							</div>
							<div class="form-group">	
							<label for="edit_product_available">Edit Product Available:</label><br>
							<input class="form-control" type="number" step=1 min="0" id="edit_product_available" name="edit_product_available" value="0" ><br>
							</div>
							<div class="form-group">	
							<label for="edit_product_description">Edit Product Description:</label><br>
							<input class="form-control" type="textarea" id="edit_product_description" name="edit_product_description"><br>
							</div>						
							<button type="submit" name="edit_product" >Edit Product</button>
		
						</form>   
                	</div>
                	</div>
                </div>
                <div class="card" style="border: 1px solid; padding: 8px; border-radius: 8px; margin: 4px" >              
                	<h4 class="card-header" role="tab" id="headingThreeProducts" >
            				<a data-toggle="collapse" data-parent="#accordionProducts" href="#collapseThreeProducts" aria-expanded="true" aria-controls="collapseThreeProducts" class="d-block">
               				 	<i class="fa fa-chevron-down pull-right"></i> Delete Product
            				</a>
        				</h4>
        			<div id="collapseThreeProducts" class="collapse" role="tabpanel" aria-labelledby="headingThreeProducts">
                	<div class="card-body">
                		<form action="" method='post'>
                			<div class="form-group">
    							<label for="delete_product_id">Delete product - id:</label><br>
    							<select id="delete_product_id" class="form-control"name="delete_product_id">
									<?php 
									try{
									    $sql = 'SELECT id FROM shop.products ORDER BY id;';
									    $dbconn->beginTransaction();
									    $stmt = $dbconn -> prepare($sql);
									    $stmt -> execute();
									    $dbconn->commit();
									    
									    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									        echo '<option value="' . $row['id'] . '">' . $row['id'] .'</option> ';
									    }
									    
									}catch(Exception $e){
									    echo 'error';
									}
									?>
								</select>
							</div>
							<button type="submit" name="delete_product" >Delete product</button>
		
						</form> 
                	</div>
                	</div>
                </div>
				<div class="card" style="border: 1px solid; padding: 8px; border-radius: 8px; margin: 4px" >              
                	<h4 class="card-header" role="tab" id="headingFourProducts" >
            				<a data-toggle="collapse" data-parent="#accordionProducts" href="#collapseFourProducts" aria-expanded="true" aria-controls="collapseFourProducts" class="d-block">
               				 	<i class="fa fa-chevron-down pull-right"></i> Discount product
            				</a>
        				</h4>
        			<div id="collapseFourProducts" class="collapse" role="tabpanel" aria-labelledby="headingFourProducts">
                	<div class="card-body">
                		<form action="" method='post'>
                			<div class="form-group">
    							<label for="discount_product_id">Discount product - ID:</label><br>
    							<select id="discount_product_id" class="form-control" name="discount_product_id">
									<?php 
									try{
									    $sql = 'SELECT id, price FROM shop.products ORDER BY id;';
									    $dbconn->beginTransaction();
									    $stmt = $dbconn -> prepare($sql);
									    $stmt -> execute();
									    $dbconn->commit();
										$data = $stmt->fetchAll();
									    
									    foreach($data as $row) {
									        echo '<option value="' . $row['id'] . '">' . $row['id'] .'</option> ';
									    }
									    
									}catch(Exception $e){
									    echo 'error';
									}
									?>
								</select>
								<h5 id="sos"></h5>
								<div class="form-group">	
									<label for="discount_product_newprice">Product price after discount:</label><br>
									<input class="form-control" type="number" step=0.01 min="0" id="discount_product_newprice" name="discount_product_newprice" value="0.00"><br>
								</div>
								<h6 id="nohigher">The discounted price cannot be higher than the original.</h6>
								<div class="form-group">	
									<label for="discount_product_percent">Product discount in percent:</label><br>
									<input class="form-control" type="number" step=1 min="0" max="100" id="discount_product_percent" name="discount_product_percent" value="0" ><br>
								</div>
								<h6 id="percenterror">This value must be between 0 and 100.</h6>
								<div class="form-group">
									<label for="discount_product_date">Time of end of discount:</label><br>
									<input class="form-control" type="date" id="discount_product_date" name="discount_product_date" value="<?php echo date('Y-m-d'); ?>">
									<input class="form-control" type="time" id="discount_product_time" name="discount_product_time" value="12:00"><br>
								</div>
								<script>
									$("#nohigher").hide();
									$("#percenterror").hide();
									var data = <?php echo json_encode($data); ?>;
									console.log(data);
									var pricey = parseInt(data[0].price);
									document.getElementById("sos").innerHTML = "Current product price is " + pricey;
									
									$('#discount_product_id').change(function() {
										var value = this.value;
										jQuery.each(data, function() {
											if (this.id == value) {
												pricey = this.price;
												document.getElementById("sos").innerHTML = "Current product price is " + pricey;
												document.getElementById("discount_product_newprice").value = 0;
												document.getElementById("discount_product_percent").value = 0;
											}
										});
									});
									
									$('#discount_product_newprice').change(function() {
										var value = parseInt(this.value);
										if ( value > pricey ) {
											this.value = 0;
											$("#nohigher").show();
											document.getElementById("discount_product_percent").value = 0;
										}
										else {
											document.getElementById("discount_product_percent").value = Math.floor(100 - 100*value/pricey);
											$("#nohigher").hide();
										}
									});
									
									$('#discount_product_percent').change(function() {
										var value = parseInt(this.value);
										if ( value >= 0 && value <= 100) {
											document.getElementById("discount_product_newprice").value = Math.round(pricey*(100-value))/100;
											$("#percenterror").hide();
										}
										else {
											this.value = 0;
											document.getElementById("discount_product_newprice").value = 0;
											$("#percenterror").show();
										}
									});
								</script>
									
								
								
							</div>
							<button type="submit" name="discount_product" >Discount product</button>
		
						</form> 
                	</div>
                	</div>
                </div>
				<div class="card" style="border: 1px solid; padding: 8px; border-radius: 8px; margin: 4px" >              
                	<h4 class="card-header" role="tab" id="headingFiveProducts" >
            				<a data-toggle="collapse" data-parent="#accordionProducts" href="#collapseFiveProducts" aria-expanded="true" aria-controls="collapseFiveProducts" class="d-block">
               				 	<i class="fa fa-chevron-down pull-right"></i> Remove discount
            				</a>
        				</h4>
        			<div id="collapseFiveProducts" class="collapse" role="tabpanel" aria-labelledby="headingFiveProducts">
                	<div class="card-body">
                		<form action="" method='post'>
                			<div class="form-group">
    							<label for="remove_discount_id">Remove discount from product - id:</label><br>
    							<select id="remove_discount_id" class="form-control"name="remove_discount_id">
									<?php 
									try{
									    $sql = 'SELECT id FROM shop.products JOIN (SELECT pid, newprice FROM shop.sales) AS sales ON products.id = sales.pid ORDER BY id;';
									    $dbconn->beginTransaction();
									    $stmt = $dbconn -> prepare($sql);
									    $stmt -> execute();
									    $dbconn->commit();
									    
									    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									        echo '<option value="' . $row['id'] . '">' . $row['id'] .'</option> ';
									    }
									    
									}catch(Exception $e){
									    echo 'error';
									}
									?>
								</select>
							</div>
							<button type="submit" name="remove_discount" >Remove discount</button>
		
						</form> 
                	</div>
                	</div>
                </div>
                
            </div>
        </div>
    </div>
    </div>
    
    
    
    
    
    <div class="card" style="border: 1px solid; padding: 16px; border-radius: 16px; margin: 8px">
        <h3 class="card-header" role="tab" id="headingFour">
            <a class="collapsed d-block" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                <i class="fa fa-chevron-down pull-right"></i> Orders
            </a>
        </h3>
        <div id="collapseFour" class="collapse" role="tabpanel" aria-labelledby="headingFour">
            <div class="card-body" style="padding: 8px; margin: 4px">
                
                <div >
            		<table class="table table-bordered">
            		<thead>
    						<tr>
      							<th scope="col">#</th>
      							<th scope="col">Client ID</th>
      							<th scope="col">Total</th>  
      							<th scope="col">Date</th>      
    						</tr>
  						</thead>
  						<tbody>
  						
  						<?php 
  						try{
  						$sql = 'SELECT * FROM shop.orders ORDER BY date;';
  						$dbconn->beginTransaction();
  						$stmt = $dbconn -> prepare($sql);
  						$stmt -> execute();
  						$dbconn->commit();
  						
  						while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  						    echo '<tr>
                                  <th scope="col">' . $row['id'] . '</th>
                                  <th scope="col">' . $row['client'] . '</th>
                                    <th scope="col">' . $row['total'] . '</th>
                                  <th scope="col">' . $row['date'] . '</th>
                                  </tr>';
  						}
  						
  						}catch(Exception $e){
  						    echo 'error';
  						}
  						?>
  						</tbody>
  				
  					</table>			
            	</div>
            	
            	<div id="accordionOrders" role="tablist" aria-multiselectable="true">
            	<?php 
  						try{
  						$sql = 'SELECT id FROM shop.orders ORDER BY id;';
  						$dbconn->beginTransaction();
  						$stmt1 = $dbconn -> prepare($sql);
  						$stmt1 -> execute();
  						$dbconn->commit();
  						
  						while($row_o = $stmt1->fetch(PDO::FETCH_ASSOC)) {
  						    $current_o=$row_o['id'];
  						    echo 
  						    '<div class="card" style="border: 1px solid; padding: 8px; border-radius: 8px; margin: 4px" >',
                	           '<h4 class="card-header" role="tab" id="heading' . $current_o . 'Orders" >',
            				        '<a data-toggle="collapse" data-parent="#accordionOrders" href="#collapse' . $current_o . 'Orders" 
                                        aria-expanded="true" aria-controls="collapse' . $current_o . 'Orders" class="d-block">',
               				 	       '<i class="fa fa-chevron-down pull-right"></i> Order - ' . $current_o . '</a> </h4>',
            				    '<div id="collapse' . $current_o . 'Orders" class="collapse" role="tabpanel" 
                                    aria-labelledby="heading' . $current_o . 'Orders">',
            				        '<div class="card-body"> ',
            				
            				
            				    '<table class="table table-bordered">
            				        <thead>
            				            <tr>
            				                <th scope="col">#</th>
            				                <th scope="col">Product ID</th>
            				                <th scope="col">Amount</th>
            				                <th scope="col">Price</th>
            				            </tr>
            				        </thead>
            				        <tbody>';
  						    
  						    $sql = 'SELECT * FROM shop.parts WHERE oid=:current_o ORDER BY id;';
  						    $dbconn->beginTransaction();
  						    $stmt2 = $dbconn -> prepare($sql);
  						    $stmt2 -> execute(array(':current_o' => $current_o));
  						    $dbconn->commit();
  						    
  						    while($row_p = $stmt2->fetch(PDO::FETCH_ASSOC)) {
  						        echo '<tr>
                                  <th scope="col">' . $row_p['id'] . '</th>
                                  <th scope="col">' . $row_p['pid'] . '</th>
                                    <th scope="col">' . $row_p['amount'] . '</th>
                                  <th scope="col">' . $row_p['price'] . '</th>
                                  </tr>';
  						        
  						    }
  						    
  						    
  						    
  						    echo '
                                    </tbody>  				
  					             </table>
  						            </div>
  						        </div> 						    
  						    </div>';
  						}
  						
  						}catch(Exception $e){
  						    echo 'error';
  						}
  						?>
            	

                
            	</div>   
                      
                      
                      
                      
            </div>
            
            
        </div>
    </div>
    
    
    
    
    
    
    
    
</div>
</div>


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