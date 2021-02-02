<?php

declare(encoding='UTF-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'DBConnection.php';

//Start or resume session
session_start();

$method = $_SERVER['REQUEST_METHOD'];


readfile('./manage.html'); //---------------------------------------------------------read html
   
//---
//---
//https://www.postgresql.org/docs/9.4/errcodes-appendix.html kody b³êdów
//---
//---

//-----------------------------------------------------------------------add brand

if( isset($_POST['add_brand']) && isset($_POST['brand_name']) ){
    
    try{
        $sql = 'INSERT INTO shop.brands (name) VALUES (:brand_name);';
        $dbconn->beginTransaction();
        $stmt = $dbconn -> prepare($sql);
        $stmt -> execute(array(':brand_name' => $_POST['brand_name']));
        $dbconn->commit();
        
        //'brand added' msg
        
    }catch (PDOException $e){
        $err_code = $e->getCode();
        $dbconn->rollback();
        if ($err_code=='23505'){
            //unique_violation - 'such brand exist' msg
        }else{
            echo '<p><h5>PDO EXCEPTION : ' . $err_code . '</h5></p>';
            //other errors about which we dont have to tell client
            // 'try again' msg
        }
    }
    
}elseif (isset($_POST['add_brand'])){
    //'complete form' msg
}





//----------------------------------------------------------------------------------------add category

if( isset($_POST['add_category']) && isset($_POST['add_category_name']) ){
    
    try{
        $dbconn->beginTransaction();
        
        if(isset($_POST['add_category_parent_category_name'])){
            $sql = 'SELECT id FROM shop.category WHERE name=:parent_category_name';
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':parent_category_name' => $_POST['add_category_parent_category_name']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $parent_id = intval($row['id']);
            
            if (empty($parent_id)){
                throw new Exception('no_such_pci');
            }
            
            $sql = 'INSERT INTO shop.category (name,parent_id) VALUES (:category_name, :parent_id);';
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':category_name' => $_POST['add_category_name'],':parent_id' => $parent_id));
            $dbconn->commit();
            
        }else{
            //'new root category'
            $sql = 'INSERT INTO shop.category (name) VALUES (:category_name);';
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':category_name' => $_POST['add_category_name']));
            $dbconn->commit();
        }
        
        //'category added' msg
        
    }catch (PDOException $e){
        $err_code = $e->getCode();
        $dbconn->rollback();
        if ($err_code=='23505'){
            //unique_violation - 'such category exist' msg
        }else{
            echo '<p><h5>PDO EXCEPTION : ' . $err_code . '</h5></p>';
            //other DATABASE errors about which we dont have to tell client
            // 'try again' msg
        }
    }catch (Exception $e){
        $dbconn->rollback();
        if ($e == 'no_such_pci'){
            //'such  parent category does not exist' msg (just in case)
        }else{
            echo '<p><h5>OTHER EXCEPTION </h5></p>';
            //co z innymi b³êdami strony ? (nie bazodanowymi?)
        }
    }
    
}elseif(isset($_POST['add_category'])){
    //'complete form' msg
}







//------------------------------------------------------------------------------------------------add product

if( isset($_POST['add_product']) && isset($_POST['add_product_name']) && isset($_POST['add_product_category_name'])
    && isset($_POST['add_product_brand_name']) && isset($_POST['add_product_price']) && isset($_POST['add_product_available'])){
        
        try{
            $dbconn->beginTransaction();
            
            //checking if such category exists
            $sql = 'SELECT id FROM shop.category WHERE name=:product_category_name';
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':product_category_name' => $_POST['add_product_category_name']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $category_id = intval($row['id']);
            
            if (empty($category_id)){
                throw new Exception('empty_c');
            }
            
            //checking if such brand exists
            $sql = 'SELECT id FROM shop.brand WHERE name=:product_brand_name';
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':product_brand_name' => $_POST['add_product_brand_name']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $brand_id = intval($row['id']);
            
            if (empty($brand_id)){
                throw new Exception('empty_b');
            }
            
            
            //sending query
            if(isset($_POST['product_description'])){
                $sql = 'INSERT INTO shop.category (cid,name,bid,price,available,description)
                        VALUES (:cid, :name, :bid, :price, :available, :description);';
                $stmt = $dbconn -> prepare($sql);
                $stmt -> execute(array(':cid' => $category_id,':name' => $_POST['add_product_name'], ':bid' => $brand_id,
                    ':price' => doubleval($_POST['add_product_price']), ':available' => intval($_POST['add_product_available']),
                    ':description' => $_POST['add_product_description']));
                $dbconn->commit();
            }else{
                $sql = 'INSERT INTO shop.category (cid,name,bid,price,available)
                        VALUES (:cid, :name, :bid, :price, :available);';
                $stmt = $dbconn -> prepare($sql);
                $stmt -> execute(array(':cid' => $category_id,':name' => $_POST['add_product_name'], ':bid' => $brand_id,
                    ':price' => doubleval($_POST['add_product_price']), ':available' => intval($_POST['add_product_available'])));
                $dbconn->commit();
            }
            
            //'category added' msg
            
        }catch (PDOException $e){
            $err_code = $e->getCode();
            $dbconn->rollback();
            if ($err_code=='23505'){
                //unique_violation - 'such product  exist' msg
            }elseif ($e == 'empty_b'){
                //'such  brand does not exist' msg (just in case)
            }elseif ($e == 'empty_c'){
                //'such category does not exist' msg (just in case)
            }else{
                echo '<p><h5>PDO EXCEPTION : ' . $err_code . '</h5></p>';
                //other DATABASE errors about which we dont have to tell client
                // 'try again' msg
            }
            
        }catch (Exception $e){
            $dbconn->rollback();
            if ($e == 'empty_b'){
                //'such  brand does not exist' msg (just in case)
            }elseif ($e == 'empty_c'){
                //'such category does not exist' msg (just in case)
            }else{
                echo '<p><h5>OTHER EXCEPTION</h5></p>';
                //co z innymi b³êdami strony ? (nie bazodanowymi?)
            }
        }
}elseif (isset($_POST['add_product'])){
    //complete form msg
}




//-----------------------------------------------------------------------delete brands

if( isset($_GET['delete_brand']) && (intval($_GET['delete_brand']) > 0) ){
    try{
        $delete_brand_id = intval($_GET['delete_brand']);
        $sql = 'DELETE FROM shop.brands WHERE id=:delete_brand_id);';
        $dbconn->beginTransaction();
        $stmt = $dbconn -> prepare($sql);
        $stmt -> execute(array(':delete_brand_id' => $delete_brand_id));
        $dbconn->commit();
        
        //'category deleted' msg
        
    }catch (PDOException $e){
        $err_code = $e->getCode();
        $dbconn->rollback();
        echo '<p><h5>PDO EXCEPTION : ' . $err_code . '</h5></p>';
    }
}


    


//---------------------------------------------------------------------delete category

if( isset($_GET['delete_category']) && isset($_GET['delete_category']) ){
    try{
        $delete_category_id = intval($_GET['delete_category']);
        $sql = 'DELETE FROM shop.categories WHERE id=:delete_category_id);';
        $dbconn->beginTransaction();
        $stmt = $dbconn -> prepare($sql);
        $stmt -> execute(array(':delete_category_id' => $delete_category_id));
        $dbconn->commit();
        
        //'category added' msg
        
    }catch (PDOException $e){
        $err_code = $e->getCode();
        $dbconn->rollback();
        echo '<p><h5>PDO EXCEPTION : ' . $err_code . '</h5></p>';
    }
}


    
//---------------------------------------------------------------------delete product

if( isset($_GET['delete_product']) && isset($_GET['delete_product']) ){
    try{
        $delete_product_id = intval($_GET['delete_product']);
        $sql = 'DELETE FROM shop.products WHERE id=:delete_product_id);';
        $dbconn->beginTransaction();
        $stmt = $dbconn -> prepare($sql);
        $stmt -> execute(array(':delete_product_id' => $delete_product_id));
        $dbconn->commit();
        
        //'product deleted' msg
        
    }catch (PDOException $e){
        $err_code = $e->getCode();
        $dbconn->rollback();
        echo '<p><h5>PDO EXCEPTION : ' . $err_code . '</h5></p>';
    }
}


    
    
  
//----------------------------------------------------------------edit brand
if(isset($_GET['edit_brand']) && intval($_GET['edit_brand'])){
    $edit_brand_id = intval($_GET['edit_brand']);
    
    $dbconn->beginTransaction();
    try {
        $sql = 'SELECT name FROM shop.brands WHERE id=:edit_brand_id;';
        $stmt = $dbconn -> prepare($sql);
        $stmt -> execute(array(':edit_brand_id' => $edit_brand_id));
        //$dbconn->commit();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $edit_brand_name = $result['name'];
        
        if(isset($_POST['modify_brand']) && isset($_POST['edit_brand_name'])){
            $edit_brand_name = $_POST['edit_brand_name'];
            $sql = 'UPDATE shop.brands SET name=:edit_brand_name WHERE id=:edit_brand_id';
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':edit_brand_name' => $edit_brand_name, ':edit_brand_id' => $edit_brand_id));
            $dbconn->commit();
            //'updated record' msg
        }elseif (isset($_GET['abort_modify_brand'])){
            $dbconn->rollback();
            //'aborted modification' msg
        }
        
    } catch (PDOException $e) {
        $err_code = $e->getCode();
        $dbconn->rollback();
        echo '<p><h5>PDO EXCEPTION : ' . $err_code . '</h5></p>';
        //other errors about which we dont have to tell client
        // 'try again' msg
    }
    
}
    
    
    

//---------------------------------------------------------------------------edit category
if(isset($_GET['edit_category']) && intval($_GET['edit_category'])){
    $edit_category_id = intval($_GET['edit_category']);
    
    $dbconn->beginTransaction();
    try {
        $sql = 'SELECT * FROM shop.categories WHERE id=:edit_category_id;';
        $stmt = $dbconn -> prepare($sql);
        $stmt -> execute(array(':edit_category_id' => $edit_category_id));
        //$dbconn->commit();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $edit_category_name = $result['name'];
        $edit_category_parent_id = intval($result['parent_id']);
        
        $sql = 'SELECT name FROM shop.category WHERE id=:edit_category_parent_id';
        $stmt = $dbconn -> prepare($sql);
        $stmt -> execute(array(':edit_category_parent_id' => $edit_category_parent_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $edit_category_parent_name = $row['name'];
        
        
        if(isset($_POST['modify_category'])){
            
            $sql = 'SELECT id FROM shop.category WHERE name=:edit_parent_category_name';
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':edit_parent_category_name' => $_POST['edit_category_parent_name']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $edit_category_parent_id = intval($row['id']);
            
            
            $sql = 'UPDATE shop.categories SET name=:edit_cateogry_name, parent_id=:edit_category_parent_id WHERE id=:edit_category_id';
            $stmt = $dbconn -> prepare($sql);
            $stmt -> execute(array(':edit_category_name' => $_POST['edit_category_name'],':edit_category_parent_id' => $_POST['edit_category_parent_id'],
                ':edit_category_id' => $edit_category_id  ));
            $dbconn->commit();
            
        }elseif (isset($_GET['abort_modify_category'])){
            $dbconn->rollback();
            //'aborted modification' msg
        }
        
        
    } catch (PDOException $e) {
        $err_code = $e->getCode();
        $dbconn->rollback();
        echo '<p><h5>PDO EXCEPTION : ' . $err_code . '</h5></p>';
        //other errors about which we dont have to tell client
        // 'try again' msg
    }
    
}





//-----------------------------------------------------------------------------------edit product

if(isset($_GET['edit_product']) && intval($_GET['edit_product'])){
    $edit_product_id = $_GET['edit_product'];
    
    $dbconn->beginTransaction();
    try {
        $sql = 'SELECT * FROM shop.products WHERE id=:edit_product_id;';
        $stmt = $dbconn -> prepare($sql);
        $stmt -> execute(array(':edit_product_id' => $edit_product_id));
        //$dbconn->commit();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $edit_product_name = $result['name'];
        $edit_product_category_id = intval($result['cid']);
        $edit_product_brand_id = intval($result['bid']);
        $edit_product_price = doubleval($result['price']);
        $edit_product_available = intval($result['available']);
        $edit_product_description = $result['description'];
        
        $sql = 'SELECT name FROM shop.brands WHERE id=:edit_product_brand_id;';
        $stmt = $dbconn -> prepare($sql);
        $stmt -> execute(array(':edit_product_brand_id' => $edit_product_brand_id));
        //$dbconn->commit();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $edit_product_brand_name = $result['name'];
        
        $sql = 'SELECT name FROM shop.categories WHERE id=:edit_product_category_id;';
        $stmt = $dbconn -> prepare($sql);
        $stmt -> execute(array(':edit_product_category_id' => $edit_product_category_id));
        //$dbconn->commit();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $edit_product_category_name = $result['name'];
        
        
        
        if(isset($_POST['modify_product']) && isset($_POST['edit_product_name']) && isset($_POST['edit_product_category_name'])
            && isset($_POST['edit_product_brand_name']) && isset($_POST['edit_product_price']) && isset($_POST['edit_product_available'])){
                
                
                //checking if such category exists
                $sql = 'SELECT id FROM shop.category WHERE name=:edit_product_category_name';
                $stmt = $dbconn -> prepare($sql);
                $stmt -> execute(array(':edit_product_category_name' => $_POST['edit_product_category_name']));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $edit_product_category_id = intval($row['id']);
                
                if (empty($edit_product_category_id)){
                    throw new Exception('empty_c');
                }
                
                //checking if such brand exists
                $sql = 'SELECT id FROM shop.brand WHERE name=:product_brand_name';
                $stmt = $dbconn -> prepare($sql);
                $stmt -> execute(array(':edit_product_brand_name' => $_POST['edit_product_brand_name']));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $edit_product_brand_id = intval($row['id']);
                
                if (empty($edit_product_brand_id)){
                    throw new Exception('empty_b');
                }
                
                
                
                $sql = 'UPDATE shop.category SET cid=:cid, name=:name, bid=:bid, price=:price, available=:available,
                            description=:decsription WHERE id=:edit_product_id';
                $stmt = $dbconn -> prepare($sql);
                $stmt -> execute(array(':cid' => $edit_product_category_id,':name' => $_POST['edit_product_name'], ':bid' => $edit_product_brand_id,
                    ':price' => doubleval($_POST['edit_product_price']), ':available' => intval($_POST['edit_product_available']),
                    ':description' => $_POST['edit_product_description']));
                $dbconn->commit();
                
        }elseif (isset($_GET['abort_modify_product'])){
            $dbconn->rollback();
            //'aborted modification' msg
        }
        
        
    } catch (PDOException $e) {
        $err_code = $e->getCode();
        $dbconn->rollback();
        echo '<p><h5>PDO EXCEPTION : ' . $err_code . '</h5></p>';
        //other errors about which we dont have to tell client
        // 'try again' msg
        
    }catch (Exception $e){
        $dbconn->rollback();
        if ($e == 'empty_b'){
            //'such  brand does not exist' msg (just in case)
        }elseif ($e == 'empty_c'){
            //'such category does not exist' msg (just in case)
        }else{
            echo '<p><h5>OTHER EXCEPTION</h5></p>';
            //co z innymi b³êdami strony ? (nie bazodanowymi?)
        }
    }
    
}



?>