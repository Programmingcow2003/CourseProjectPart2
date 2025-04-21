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

if (isset($_POST['remove_item'])) {
    $remove_id = $_POST['remove_item'];
    $sql = "DELETE FROM ItemInCart WHERE product_id = :product_id AND customer_id = :userid;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_id', $remove_id);
    $stmt->bindParam(':userid', $userid);
    $stmt->execute();
}


if(isset($_POST['checkout'])){
    $sql = "set @p_order_id = NULL";
    $pdo->exec($sql);
    $sql = "set @p_out_of_stock_product = NULL";
    $pdo->exec($sql);

    $sql = "Call checkout(:customer_id, @p_order_id, @p_out_of_stock_product);";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':customer_id', $userid);
    $stmt->execute();


    $missing_item_array = $pdo->query("Select @p_out_of_stock_product as missing_item;") ->fetch(PDO::FETCH_ASSOC);
    $order_id_array = $pdo->query("Select @p_order_id as order_id;")-> fetch(PDO::FETCH_ASSOC);

    $missing_item = $missing_item_array['missing_item'];
    $order_id = $order_id_array['order_id'];
    

    if($missing_item !== null){
        echo "<p> Product ID $missing_item is out of stock. </p>";
    }

    if($order_id !== null){
        echo "<p> Order was a success! Your order ID is $order_id </p>";
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

    echo "<form method='post' action='shoppingCart.php'>";
    foreach ($results as $row) {
        echo "<h1> Product ID: " . $row['product_id'] . " " . $row['name'] . " Price $ " . $row['price'] . " Quantity: " . $row['quantity'] . "</h1>";
        echo "Edit Quantity: <input type='number' name='new_quantity[" . $row['product_id'] . "]' value='" . $row['quantity'] . "' min='1'><br><br>";
        echo "<button type='submit' name='remove_item' value='" . $row['product_id'] . "'>Remove from Cart</button><br><br>";
    }
    echo "<p><input type='submit' value='Change Cart'></p>";
    echo "</form>";
} catch (PDOException $e) {
}
?>

<!DOCTYPE html>
<html>

<form action="browse_products.php" method="get">
    <input type="submit" value="Add new items from catalog">
</form>

<form method="post" action="shoppingCart.php">
    <input type="submit" name="checkout" value="Checkout">
</form>
<html>

