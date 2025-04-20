<form method="post" action="shoppingCart.php">
<?php
require 'db.php';
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION["username"])) {
    header("LOCATION:customer_login.php");
} else {
    echo '<p align="right"> Welcome ' . $_SESSION["username"] . '</p>';
}

$pdo = connectDB();
$userid = $_SESSION['customer_id']; 

if (isset($_POST['new_quantity'])) {
    foreach ($_POST['new_quantity'] as $product_id => $new_quantity) {
        $sql = "Update ItemInCart set quantity = :new_quantity where product_id = :product_id and customer_id = :userid;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':new_quantity', $new_quantity);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':userid', $userid);
        $stmt->execute();
    }   
}

try {
    $sql = "Select Product.product_id, quantity, price, Product.name 
            from ItemInCart 
            join Product on Product.product_id = ItemInCart.product_id 
            where customer_id = :userid 
            group by ItemInCart.product_id;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userid', $userid);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        echo "<h1> Product ID: " . $row['product_id'] . " " . $row['name'] . " Price $ " . $row['price'] . " Quantity: " . $row['quantity'] . "</h1>";
        echo "Edit Quantity: <input type='number' name='new_quantity[" . $row['product_id'] . "]' value='" . $row['quantity'] . "' min='1'><br><br>";
    }
} catch (PDOException $e) {
}
?>

<!DOCTYPE html>
<html>
<p>
    <input type="submit" value="Change Cart">
</p>
</form>
