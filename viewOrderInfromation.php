


<?php

function getOrderDetails($userid){
    try {
        $pdo = connectDB();
        $sql = "SELECT OrderInfromation.order_id, OrderItem.price, date_ordered, total, product_id, quantity 
                FROM OrderInfromation 
                JOIN OrderItem ON OrderInfromation.order_id = OrderItem.order_id 
                WHERE OrderInfromation.customer_id = :userid 
                ORDER BY OrderInfromation.order_id;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userid', $userid);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $currentOrder = null;

        foreach ($results as $row) {
            if ($currentOrder !== $row['order_id']) {
                if ($currentOrder !== null) {
                    echo "</table></details><br>";
                }

                $currentOrder = $row['order_id'];

                echo "<details>";
                echo "<summary>Order ID: {$currentOrder} | Date: {$row['date_ordered']} | Total: \${$row['total']}</summary>";
                echo "<table>";
                echo "<tr><th>Product ID</th><th>Price</th><th>Quantity</th></tr>";
            }

            echo "<tr>";
            echo "<td>{$row['product_id']}</td>";
            echo "<td>\${$row['price']}</td>";
            echo "<td>{$row['quantity']}</td>";
            echo "</tr>";
        }

        if ($currentOrder !== null) {
            echo "</table></details>";
        }

        return $results;

    } catch (PDOException $e) {
        echo "Error retrieving order details.";
        return [];
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





