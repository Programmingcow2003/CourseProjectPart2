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

        // Connect to DB
        $dbh = connectDB();

        // DEBUG info
        echo "<p>DEBUG - Username: $user</p>";
        echo "<p>DEBUG - Raw password: $password</p>";

        
        $statement = $dbh->prepare(
            "SELECT count(*) FROM Employee 
             WHERE username = :username 
             AND password = sha2(:password, 256)"
        );

        // Bind values (unhashed)
        $statement->bindParam(":username", $user);
        $statement->bindParam(":password", $password);

        // Execute and fetch
        $statement->execute();
        $row = $statement->fetch();
        $dbh = null;

        // DEBUG result
        echo "<p>DEBUG - Matching row count: {$row[0]}</p>";

        return $row[0];

    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}


function checkPasswordValidity( $user, $password ) {
    //getting if the employee needs to reset their password as its their first use
    $dbh = connectDB();
    $statement = $dbh->prepare("SELECT count(*) FROM Employee ".
    "where username = :username and password = sha2(:password,256) and password_updated = false");
    $statement->bindParam(":username", $user);
    $statement->bindParam(":password", $password);
    $result = $statement->execute();
    $reset=$statement->fetch();
    $dbh=null;
    
        return $row[0];
}

        //getting if the employee username and password are valid
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT count(*) FROM Employee ".
        "where username = :username and password = sha2(:password,256) ");
        $statement->bindParam(":username", $user);
        $statement->bindParam(":password", $password);
        $result = $statement->execute();
        $row=$statement->fetch();
        $dbh=null;
        
            return $row[0];



    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function checkPasswordValidity( $user, $password ) {
    //getting if the employee needs to reset their password as its their first use
    $dbh = connectDB();
    $statement = $dbh->prepare("SELECT count(*) FROM Employee ".
    "where username = :username and password = sha2(:password,256) and password_updated = false");
    $statement->bindParam(":username", $user);
    $statement->bindParam(":password", $password);
    $result = $statement->execute();
    $reset=$statement->fetch();
    $dbh=null;
    
        return $row[0];
}

//borrowed from lab 8, going to make it specific to getting product stock history
function get_stock_history($product_id)
{
 //connect to database
 //retrieve the data and display
 try {
 $dbh = connectDB();
 $statement = $dbh->prepare("SELECT time_stamp, old_stock, new_stock name FROM ProductHistory where product_id = 
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

//borrowed from lab 8, going to make it specific to getting product price history
function get_price_history($product_id)
{
 //connect to database
 //retrieve the data and display
 try {
 $dbh = connectDB();
 $statement = $dbh->prepare("SELECT time_stamp, old_price, new_price name FROM ProductHistory where product_id = 
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
?>
