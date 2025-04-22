<?php
require 'db.php';

function registerCustomer($username, $password, $first_name, $last_name, $email, $shipping_address){
    $database = connectDB();

    $inserting = $database->prepare("INSERT INTO Customer (username, password, first_name, last_name, email, shipping_address)
    values (:username, SHA2(:password, 256), :first_name, :last_name, :email, :shipping_address)");

    $inserting->bindParam(':username', $username);
    $inserting->bindParam(':password', $password);
    $inserting->bindParam(':first_name', $first_name);
    $inserting->bindParam(':last_name', $last_name);
    $inserting->bindParam(':email', $email);
    $inserting->bindParam(':shipping_address', $shipping_address);

    $inserting->execute();
}

function authenticate2($user, $password) {
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT count(*) FROM Customer ".
        "where username = :username and password = sha2(:password,256) ");
        $statement->bindParam(":username", $user);
        $statement->bindParam(":password", $password);
        $result = $statement->execute();
        $row=$statement->fetch();
        $dbh=null;

        return $row[0];
    }catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function authenticateEmployee($user, $password) {
    try { 
        $dbh = connectDB();



        // Fetch stored hash from DB for comparison
        $check = $dbh->prepare("SELECT password FROM Employee WHERE username = :username");
        $check->bindParam(":username", $user);
        $check->execute();
        $stored = $check->fetch();



        // Run authentication using MySQL's SHA2()
        $statement = $dbh->prepare(
            "SELECT count(*) FROM Employee 
             WHERE username = :username 
             AND password = SHA2(:password, 256)"
        );
        $statement->bindParam(":username", $user);
        $statement->bindParam(":password", $password);  // raw password

        $statement->execute();
        $row = $statement->fetch();
        $dbh = null;


        return $row[0];

    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}



function checkPasswordValidity($user, $password) {
    $dbh = connectDB();

    // Check what SHA2 will return
    $hashCheck = $dbh->prepare("SELECT password, password_updated FROM Employee WHERE username = :username");
    $hashCheck->bindParam(":username", $user);
    $hashCheck->execute();
    $result = $hashCheck->fetch();

    if (!$result) {
        return 0;
    }



    $statement = $dbh->prepare("SELECT count(*) FROM Employee WHERE username = :username AND password = SHA2(:password,256) AND password_updated = false");
    $statement->bindParam(":username", $user);
    $statement->bindParam(":password", $password);
    $statement->execute();
    $row = $statement->fetch();
    $dbh = null;

    return $row[0];
}

function get_stock_history($product_id)
{
 try {
 $dbh = connectDB();
 $statement = $dbh->prepare("SELECT time_stamp, old_stock, new_stock FROM ProductHistory where product_id = 
 :product_id ");
 $statement->bindParam(":product_id", $product_id);
 $statement->execute();

 return $statement->fetchAll();
 $dbh = null;
 } catch (PDOException $e) {
 print "Error!" . $e->getMessage() . "<br/>";
 die();
 }
}

function get_price_history($product_id)
{
 try {
 $dbh = connectDB();
 $statement = $dbh->prepare("SELECT time_stamp, old_price, new_price FROM ProductHistory where product_id = 
 :product_id ");
 $statement->bindParam(":product_id", $product_id);
 $statement->execute();

 return $statement->fetchAll();
 $dbh = null;
 } catch (PDOException $e) {
 print "Error!" . $e->getMessage() . "<br/>";
 die();
 }
}



function updateStock($product_id, $change, $user_id, $status ){
    //get stock, check that its greater than or equal to the change
    //then updates stock, then updates the product log
    try{
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT stock_quantity, price FROM Product WHERE product_id = :product_id");
        $statement->bindParam(":product_id", $product_id);
        $result1 = $statement->execute();
        $row = $statement->fetch();

        if ($row[0] + $change < 0) {
            return 1;
        } else {
            $new_stock = $row[0] + $change;

            // update the product stock quantity
            $dbh = connectDB();
            $statement = $dbh->prepare("UPDATE Product SET stock_quantity = :stock_quantity WHERE product_id = :product_id");
            $statement->bindParam(":stock_quantity", $new_stock);
            $statement->bindParam(":product_id", $product_id);
            $result = $statement->execute();

            // log the update to the product stock quantity
            $dbh = connectDB();
            $statement = $dbh->prepare("CALL log_product_update(:product_id, 'UPDATE', :old_price, :new_price, :old_stock, :new_stock, :update_id, :updated_by, null)");
            $statement->bindParam(":product_id", $product_id);
            $statement->bindParam(":old_price", $row[1]);
            $statement->bindParam(":new_price", $row[1]);
            $statement->bindParam(":old_stock", $row[0]);
            $statement->bindParam(":new_stock", $new_stock);
            $statement->bindParam(":update_id", $user_id);
            $statement->bindParam(":updated_by", $status);
            $statement->execute();
        }

    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}


//product_id is the id of the product's price to be updated
//change is the new price
//user_id is the id of the session user changing the price, designed to be fed in from session
//status is if they are an employee or customer, it is an enumerated type
//so the two valid inputs are "employee" and "customer"
function updatePrice($product_id, $change, $user_id, $status ){
    //check that new price is positive, updates price, updates product log
    //then updates stock, then updates the product log
    try{
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT stock_quantity, price FROM Product WHERE product_id = :product_id");
        $statement->bindParam(":product_id", $product_id);
        $result1=$statement->execute();
        $row=$statement->fetch();

        if( $change <= 0 ) {
            return 1;
        } else {
            //trying to update the product stock quantity
            $dbh = connectDB();
            $statement = $dbh->prepare("UPDATE Product SET price = :price WHERE product_id = :product_id");
            $statement->bindParam(":price", $change);
            $statement->bindParam(":product_id", $product_id);
            $result=$statement->execute();

            //trying to log the update to the product stock quantity
            $dbh = connectDB();
            $statement = $dbh->prepare("CALL log_product_update(:product_id, 'UPDATE', :old_price, :new_price, :old_stock, :new_stock, :update_id, :updated_by, null)");
            $statement->bindParam(":product_id", $product_id);
            $statement->bindParam(":old_price", $row[1]);
            $statement->bindParam(":new_price", $change);
            $statement->bindParam(":old_stock", $row[0]);
            $new_stock = $row[0] + $change;
            $statement->bindParam(":new_stock", $new_stock);
            $statement->bindParam(":update_id", $user_id );
            $statement->bindParam(":updated_by", $status);
            $statement->execute();
        }

    }   catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}
?>



