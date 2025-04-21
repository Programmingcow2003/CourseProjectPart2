


<?php
function getOrderDetails($userid){
   try{
    $pdo = connectDB();
    $sql = "Select OrderInfromation.order_id, OrderItem.price, date_ordered, total, product_id, quantity from OrderInfromation JOIN OrderItem on OrderInfromation.order_id = OrderItem.order_id where OrderInfromation.customer_id = :userid ORDER by OrderInfromation.order_id;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':userid', $userid);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $currentOrder = null;

    foreach($results as $row){
        if($currentOrder !== $row['order_id']){
            $currentOrder = $row['order_id'];
            echo "<h1>Order ID " . $currentOrder . "</h1>";
            echo "Date Ordered: " . $row['date_ordered'] . "<br>";
            echo "Total: $" . $row['total'] . "<br>";

        }
        echo "Product ID: " . $row['product_id'] . " Price per item: $" . $row['price'] ." Quantity: " .  $row['quantity'] . "<br>";
    }
    return $results;
   } catch (PDOException $e) {
    }
   }
?>


<?php
require 'db.php';
session_start();
if (!isset($_SESSION["username"])) {
    header("LOCATION:customer_login.php");
} else {
    echo '<p align="right"> Welcome ' . $_SESSION["username"] . '</p>';
}

?>

<form method = "post" action = "browse_products.php">
    <p align="right">
    <input type="submit" value="logout" name="logout">
    </p>
</form>
    <?php
if (isset($_POST["logout"])) {
    header("LOCATION: customer_login.php");
    session_destroy();
    }


?>

<form method="post" action="shopMain.php">
    <p align="right">
    <input type="submit" value = "Return to main">
    </form> 


<?php

getOrderDetails($_SESSION['customer_id']);
?>





