
<?php 


include 'DBConnection.php';

session_start();

$connected=false;
$method = $_SERVER['REQUEST_METHOD'];

try{
    $dbconn = Connection::getPDO();
    $connected=true;     
    echo 'connected      ';

}catch (PDOException $e){
    echo 'Unable to connect to database';
}

if($connected==true && $method=='POST'){
    
    //-----------------------ADD--BRAND
    
    if( isset($_POST['add_brand']) && isset($_POST['add_brand_name']) && mb_strlen($_POST['add_brand_name'])>0 && mb_strlen($_POST['add_brand_name'])<30 ){
    
        try{
            $sql = 'INSERT INTO shop.brands (name) VALUES (:add_brand_name);';
            $dbconn->beginTransaction();
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':add_brand_name' => $_POST['add_brand_name']));
            $dbconn->commit();
        
            //'brand added' msg
            echo 'brand added';
        
        }catch (PDOException $e){
            $err_code = $e->getCode();
            $dbconn->rollback();
            if ($err_code=='23505'){
                echo 'uniq viol.';
            }else{
                echo '<p><h5>PDO EXCEPTION : ' . $err_code . '</h5></p>';
                //other errors about which we dont have to tell client
                // 'try again' msg
            }
        }catch (Exception $e){
            $dbconn->rollback();
            $errorMsg = $e->getMessage();
            echo $errorMsg;
            
        }
    
    }
    //--------------ADD--Category
    
    elseif (isset($_POST['add_category']) && isset($_POST['add_category_name']) && mb_strlen($_POST['add_category_name'])>0 && mb_strlen($_POST['add_category_name'])<30 ){
    
        try{
            $dbconn->beginTransaction();
            
            if(isset($_POST['add_category_parent_category_name']) && is_int($_POST['add_category_parent_category_id']) && intval($_POST['add_category_parent_category_id'])!=0){
                $sql = 'SELECT id FROM shop.categories WHERE id=:add_parent_category_id';
                $stmt = $dbconn -> prepare($sql);
                $stmt -> execute(array(':add_parent_category_id' => $_POST['add_category_parent_category_id']));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $parent_id = intval($row['id']);         
                
                
                if (empty($parent_id)){                   
                    throw new Exception('no_such_pci');
                }
                
                $sql = 'INSERT INTO shop.categories (name,parent_id) VALUES (:category_name, :parent_id);';
                $stmt = $dbconn -> prepare($sql);
                $stmt -> execute(array(':category_name' => $_POST['add_category_name'],':parent_id' => $parent_id));
                $dbconn->commit();
                
            }else{
                //'new root category'
                $sql = 'INSERT INTO shop.categories (name) VALUES (:category_name);';
                $stmt = $dbconn -> prepare($sql);
                $stmt -> execute(array(':category_name' => $_POST['add_category_name']));
                $dbconn->commit();
            }
            
            //'category added' msg
            echo 'category added';
            
        }catch (PDOException $e){
            $err_code = $e->getCode();
            $dbconn->rollback();
            if ($err_code=='23505'){
                echo 'uniq viol.';
                //unique_violation - 'such category exist' msg
            }else{
                echo '<p><h5>PDO EXCEPTION : ' . $err_code . '</h5></p>';
                //other DATABASE errors about which we dont have to tell client
                // 'try again' msg
            }
        }catch (Exception $e){
            $dbconn->rollback();
            $errorMsg = $e->getMessage();
            echo $errorMsg;
        }
    }
    //------------ADD-PRODUCT
    
    
    if( isset($_POST['add_product']) && isset($_POST['add_product_name']) 
    && mb_strlen($_POST['add_product_name'])>0 && mb_strlen($_POST['add_product_name'])<60
    && isset($_POST['add_product_category_name'])
    && mb_strlen($_POST['add_product_category_name'])>0 && mb_strlen($_POST['add_product_category_name'])<30
    && isset($_POST['add_product_brand_name']) 
    && mb_strlen($_POST['add_product_brand_name'])>0 && mb_strlen($_POST['add_product_brand_name'])<30
    && isset($_POST['add_product_price']) 
    && is_double(doubleval($_POST['add_product_price'])) && doubleval($_POST['add_product_price'])>=0.0 && doubleval($_POST['add_product_price'])<1000000.0
    && isset($_POST['add_product_available'])
    && is_int(intval($_POST['add_product_available'])) && intval($_POST['add_product_available'])>=0 && intval($_POST['add_product_available'])<1000000
    ){
        
       
        try{
            $dbconn->beginTransaction();
            
            //checking if such category exists
            $sql = 'SELECT id FROM shop.categories WHERE name=:product_category_name';
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':product_category_name' => $_POST['add_product_category_name']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $category_id = intval($row['id']);
            
            if (empty($category_id)){
                throw new Exception('empty_c');
            }
            
            //checking if such brand exists
            $sql = 'SELECT id FROM shop.brands WHERE name=:product_brand_name';
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':product_brand_name' => $_POST['add_product_brand_name']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $brand_id = intval($row['id']);
            if (empty($brand_id)){
                throw new Exception('empty_b');
            }
            
            
            //sending query
            if(isset($_POST['add_product_description']) && mb_strlen($_POST['add_product_description'])>0){
                $sql = 'INSERT INTO shop.products (cid,name,bid,price,available,description)
                        VALUES (:cid, :name, :bid, :price, :available, :description);';
                $stmt = $dbconn -> prepare($sql);
                $stmt -> execute(array(':cid' => $category_id,':name' => $_POST['add_product_name'], ':bid' => $brand_id,
                    ':price' => doubleval($_POST['add_product_price']), ':available' => intval($_POST['add_product_available']),
                    ':description' => $_POST['add_product_description']));
                $dbconn->commit();
                
            }else{
                $sql = 'INSERT INTO shop.products (cid,name,bid,price,available)
                        VALUES (:cid, :name, :bid, :price, :available);';
                $stmt = $dbconn -> prepare($sql);
                $stmt -> execute(array(':cid' => $category_id,':name' => $_POST['add_product_name'], ':bid' => $brand_id,
                    ':price' => doubleval($_POST['add_product_price']), ':available' => intval($_POST['add_product_available'])));
                $dbconn->commit();

            }
            echo 'product added';
            //'product added' msg
            
        }catch (PDOException $e){
            $err_code = $e->getCode();
            $dbconn->rollback();
            if ($err_code=='23505'){
                echo 'such product exists';
                //unique_violation - 'such product  exists' msg           
            }else{
                echo '<p><h5>PDO EXCEPTION : ' . $err_code . '</h5></p>';
                //other DATABASE errors about which we dont have to tell client
                // 'try again' msg
            }
            
        }catch (Exception $e){           
            $dbconn->rollback();
            $errorMsg = $e->getMessage();
            echo $errorMsg;
        }
    }
    
    
    //---------------------------------------------------------DELETE   
    //------------------DELETE BRAND
    
    elseif( isset($_POST['delete_brand']) && mb_strlen($_POST['delete_brand_name']) > 0 && mb_strlen($_POST['delete_brand_name'])<30){ 
        try{
            
            $dbconn->beginTransaction();
            $sql = 'SELECT id FROM shop.brands WHERE name=:delete_brand_name';
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':delete_brand_name' => $_POST['delete_brand_name']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $brand_id = intval($row['id']);
            
            echo $_POST['delete_brand_name'];
            
            if (empty($brand_id)){
                echo $brand_id;
                throw new Exception('empty_b');
            }
             
            $sql = 'DELETE FROM shop.brands WHERE id=:delete_brand_id;';

            
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':delete_brand_id' => $brand_id));
            $dbconn->commit();
            
            //'brand deleted' msg
            echo 'brand deleted';
            
        }catch (PDOException $e){
            $err_code = $e->getCode();
            $dbconn->rollback();
            if($err_code==23503){
                echo 'product of such brand exists, cannot delete brand';
            }else{
                echo '<p><h5>PDO EXCEPTION : ' . $err_code . '</h5></p>';
            }
        }catch (Exception $e){
            $dbconn->rollback();
            $errorMsg = $e->getMessage();
            echo $errorMsg;
            
        }
    }
    
    //--------------delete category
    if( isset($_POST['delete_category']) && isset($_POST['delete_category_id']) ){
        try{
            
            $dbconn->beginTransaction();
            
            /*
            //checking if such category exists
            $sql = 'SELECT id FROM shop.categories WHERE name=:delete_category_name;';
            $stmt = $dbconn -> prepare($sql);            
            $stmt -> execute(array(':delete_category_name' => $_POST['delete_category_name']));         
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $category_id = intval($row['id']);
            */
            //---------------------------
            $sql = 'SELECT id FROM shop.categories WHERE id=:delete_category_id;';
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':delete_category_id' => $_POST['delete_category_id']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $category_id = intval($row['id']);
            //-------------------------------
            if (empty($category_id)){
                throw new Exception('empty_c');
            }
            
            $sql = 'DELETE FROM shop.categories WHERE id=:delete_category_id;';           
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':delete_category_id' => $category_id));
            $dbconn->commit();
            
            echo 'category deleted, check in database if really worked';
            
        }catch (PDOException $e){
            $err_code = $e->getCode();
            $dbconn->rollback();
            if($err_code==23503){
                echo 'product of such category exists or this category is a parent category, cannot delete category';
            }else{
                echo '<p><h5>PDO EXCEPTION : ' . $err_code . '</h5></p>';
            }
           
        }catch (Exception $e){
            $dbconn->rollback();
            $errorMsg = $e->getMessage();
            echo $errorMsg;
        }
    }
    
    //---------------------------------------------------------------------delete product
    
    elseif( isset($_POST['delete_product']) && isset($_POST['delete_product_id']) ){
        try{
            
            $dbconn->beginTransaction();
            $sql = 'SELECT id FROM shop.products WHERE id=:delete_product_id;';
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':delete_product_id' => $_POST['delete_product_id']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $product_id = intval($row['id']);

            if (empty($product_id)){
                throw new Exception('empty_p');
            }
            
            $sql = 'DELETE FROM shop.products WHERE id=:delete_product_id;';
            $stmt = $dbconn -> prepare($sql);

            $stmt -> execute(array(':delete_product_id' => $product_id));
            $dbconn->commit();

            
            echo 'product deleted';
            
            
        }catch (PDOException $e){
            $err_code = $e->getCode();
            $dbconn->rollback();
            echo '<p><h5>PDO EXCEPTION : ' . $err_code . '</h5></p>';
        }catch (Exception $e){
            $dbconn->rollback();
            $errorMsg = $e->getMessage();
            echo $errorMsg;
        }
    }
    
    //---------------------------------------------------------edit
    //------------------edit BRAND
    
    elseif(isset($_POST['edit_brand']) && isset($_POST['edit_brand_id']) && mb_strlen($_POST['edit_brand_name'])>0 
    && mb_strlen($_POST['edit_brand_name'])<30){        
        try {
            
            $edit_brand_id = intval($_POST['edit_brand_id']);                      
            $dbconn->beginTransaction();
            
            $sql = 'SELECT name FROM shop.brands WHERE id=:edit_brand_id';
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':edit_brand_id' => $edit_brand_id));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $brand_name = $row['name'];
            if (mb_strlen($brand_name)==0){
                throw new Exception('no_such_brand');
            }
            
            $sql = 'UPDATE shop.brands SET name=:edit_brand_name WHERE id=:edit_brand_id';
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':edit_brand_name' => $_POST['edit_brand_name'], ':edit_brand_id' => $edit_brand_id));
            $dbconn->commit();
            echo 'brand edited';
            
        }catch (PDOException $e){
            $err_code = $e->getCode();
            $dbconn->rollback();
            if ($err_code=='23505'){
                echo 'uniq viol.';
                //unique_violation - 'such category exist' msg
            }else{
                echo '<p><h5>PDO EXCEPTION : ' . $err_code . '</h5></p>';
                //other DATABASE errors about which we dont have to tell client
                // 'try again' msg
            }
        }catch (Exception $e){
            $dbconn->rollback();
            $errorMsg = $e->getMessage();
            echo $errorMsg;
        }
    
    }
    
    
    
    //------------------edit category
    
    elseif (isset($_POST['edit_category'])  && isset($_POST['edit_category_id']) && is_int(intval($_POST['edit_category_id']))
    && isset($_POST['edit_category_parent_category_id']) && is_int(intval($_POST['edit_category_parent_category_id']))
    && intval($_POST['edit_category_parent_category_id'])!=0 && isset($_POST['edit_category_name'])
    && mb_strlen($_POST['edit_category_name'])>0 && mb_strlen($_POST['edit_category_name'])<30
    ){
        
        try{
            echo $_POST['edit_category_id'];
            $edit_category_id=intval($_POST['edit_category_id']);
            
            $dbconn->beginTransaction();
            
            $sql = 'SELECT name FROM shop.categories WHERE id=:edit_category_id';
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':edit_category_id' => $edit_category_id));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $category_name = $row['name'];
            if (mb_strlen($category_name)==0){
                throw new Exception('no_such_category');
            }
            
         
                $sql = 'SELECT id FROM shop.categories WHERE id=:edit_parent_category_id';
                $stmt = $dbconn -> prepare($sql);
                $stmt -> execute(array(':edit_parent_category_id' => $_POST['edit_category_parent_category_id']));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $parent_id = intval($row['id']);    
                if (empty($parent_id)){
                    throw new Exception('no_such_pci');
                }
                

                $sql = 'UPDATE shop.categories SET name=:edit_category_name, parent_id=:edit_parent_id WHERE id=:edit_category_id;';
                $stmt = $dbconn -> prepare($sql);
                $stmt -> execute(array(':edit_category_name' => $_POST['edit_category_name'],':edit_parent_id' => $parent_id, 
                    ':edit_category_id' => $edit_category_id));
                $dbconn->commit();
                echo 'category edited';              
            
            
            //'category added' msg
            
            
        }catch (PDOException $e){
            $err_code = $e->getCode();
            $dbconn->rollback();
            if ($err_code=='23505'){
                echo 'uniq viol.';
                //unique_violation - 'such category exist' msg
            }else{
                echo '<p><h5>PDO EXCEPTION : ' . $err_code . '</h5></p>';
                //other DATABASE errors about which we dont have to tell client
                // 'try again' msg
            }
        }catch (Exception $e){
            $dbconn->rollback();
            $errorMsg = $e->getMessage();
            echo $errorMsg;
        }
    }
    
        
  
    //------------edit-PRODUCT
    
    
    elseif( isset($_POST['edit_product']) && isset($_POST['edit_product_name'])
    && mb_strlen($_POST['edit_product_name'])>0 && mb_strlen($_POST['edit_product_name'])<60
    && isset($_POST['edit_product_category_id'])
    && intval($_POST['edit_product_category_id'])>0
    && isset($_POST['edit_product_brand_name'])
    && mb_strlen($_POST['edit_product_brand_name'])>0 && mb_strlen($_POST['edit_product_brand_name'])<30
    && isset($_POST['edit_product_price'])
    && is_double(doubleval($_POST['edit_product_price'])) && doubleval($_POST['edit_product_price'])>=0.0 && doubleval($_POST['edit_product_price'])<1000000.0
    && isset($_POST['edit_product_available'])
    && is_int(intval($_POST['edit_product_available'])) && intval($_POST['edit_product_available'])>=0 && intval($_POST['edit_product_available'])<1000000
    ){
        
        
        try{
            $dbconn->beginTransaction();
            
            $sql = 'SELECT id FROM shop.products WHERE id=:product_id';
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':product_id' => $_POST['edit_product_id']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $product_id = intval($row['id']);
            
            if (empty($product_id)){
                throw new Exception('empty_p');
            }
            
            //checking if such category exists
            $sql = 'SELECT id FROM shop.categories WHERE id=:product_category_id';
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':product_category_id' => $_POST['edit_product_category_id']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $category_id = intval($row['id']);
            
            if (empty($category_id)){
                throw new Exception('empty_c');
            }
            
            //checking if such brand exists
            $sql = 'SELECT id FROM shop.brands WHERE name=:product_brand_name';
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':product_brand_name' => $_POST['edit_product_brand_name']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $brand_id = intval($row['id']);
            if (empty($brand_id)){
                throw new Exception('empty_b');
            }
            
            
            //sending query
            if(isset($_POST['edit_product_description']) && mb_strlen($_POST['edit_product_description'])>0){
                $sql = 'UPDATE shop.products SET cid=:cid, name=:name, bid=:bid, price=:price, available=:available, 
                        description=:description WHERE id=:product_id;';
                        
                $stmt = $dbconn -> prepare($sql);
                $stmt -> execute(array(':cid' => $category_id,':name' => $_POST['edit_product_name'], ':bid' => $brand_id,
                    ':price' => doubleval($_POST['edit_product_price']), ':available' => intval($_POST['edit_product_available']),
                    ':description' => $_POST['edit_product_description'], ':product_id' => $product_id));
                $dbconn->commit();
                
            }else{
                $sql = 'UPDATE shop.products SET cid=:cid, name=:name, bid=:bid, price=:price, available=:available, description=null
                        WHERE id=:product_id;';
                $stmt = $dbconn -> prepare($sql);
                $stmt -> execute(array(':cid' => $category_id,':name' => $_POST['edit_product_name'], ':bid' => $brand_id,
                    ':price' => doubleval($_POST['edit_product_price']), ':available' => intval($_POST['edit_product_available']),
                       ':product_id' => $product_id));
                $dbconn->commit();
                
            }
            echo 'product edited';
            //'product added' msg
            
        }catch (PDOException $e){
            $err_code = $e->getCode();
            $dbconn->rollback();
            if ($err_code=='23505'){
                echo 'such product exists';
                //unique_violation - 'such product  exists' msg
            }else{
                echo '<p><h5>PDO EXCEPTION : ' . $err_code . '</h5></p>';
                //other DATABASE errors about which we dont have to tell client
                // 'try again' msg
            }
            
        }catch (Exception $e){
            $dbconn->rollback();
            $errorMsg = $e->getMessage();
            echo $errorMsg;
        }
    
    }elseif (isset($_POST['add_brand']) || isset($_POST['add_category']) || isset($_POST['add_product']) || 
            isset($_POST['delete_brand']) || isset($_POST['delete_category']) || isset($_POST['delete_product']) || 
                isset($_POST['edit_brand']) || isset($_POST['edit_category']) || isset($_POST['edit_product'])){               
        echo ' complete form';                   
    }
    
       
}

?>