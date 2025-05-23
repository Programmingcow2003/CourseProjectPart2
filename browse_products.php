<!DOCTYPE html>
<form method="post" action="shopMain.php">
    <p align="right">
    <input type="submit" value = "Return to main">
    </form> 
</html>
<?php

session_start();
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'db.php';


if (isset($_POST['add_to_cart']) && isset($_POST['quantity'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $pdo = connectDB();

    foreach ($_POST['quantity'] as $productName => $quantity) {
        $quantity = intval($quantity);
        if ($quantity > 0) {
            if (isset($_SESSION['cart'][$productName])) {
                $_SESSION['cart'][$productName] += $quantity;
            } else {
                $_SESSION['cart'][$productName] = $quantity;
            }

            $stmt = $pdo->prepare("SELECT product_id FROM Product WHERE name = :name");
            $stmt->bindParam(':name', $productName);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $product_id = $product['product_id'];
                $customer_id = $_SESSION['customer_id']; 

                $insert = $pdo->prepare("INSERT INTO ItemInCart (customer_id, product_id, quantity) VALUES (:customer_id, :product_id, :quantity) on duplicate key update quantity = quantity + VALUES(quantity)");
                $insert->bindParam(':customer_id', $customer_id);
                $insert->bindParam(':product_id', $product_id);
                $insert->bindParam(':quantity', $quantity);
                $insert->execute();
            }
        }
    }
}



function getAllCategories() {
    try {
        $pdo = connectDB();
        $sql = "SELECT * FROM Category";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
    }
}

function getAllItems($category_name){
    try{
        $pdo = connectDB();
        $sql = "SELECT * FROM Product WHERE Category_name = '$category_name'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
    }

    }

$categories = getAllCategories();

# Struggled with losing sesson so used CHATGTP To help
if (isset($_POST['selected_category'])) {
    $_SESSION['last_selected_category'] = $_POST['selected_category'];
}

?>
<!DOCTYPE html>
<html>
<body>
    <h1>Choose your category</h1>

    <ul>
        <?php foreach ($categories as $category) { ?>
            <li>
                <form method="post">
                 <input type="hidden" name="selected_category" value="<?= htmlspecialchars($category['name']) ?>">
                 <button type="submit"><?= htmlspecialchars($category['name']) ?></button>
        </form>
        </li>
        <?php } ?>
        </ul>

<?php

if(!isset($_SESSION["username"])){

?>
    
    <br><br>
    <?php if (isset($_SESSION['last_selected_category'])): ?>
        <h2> The current category is <?= htmlspecialchars($_SESSION['last_selected_category']) ?></h2>
        <h3> Products in this category </h3>
        <ul>
            <?php
            $items = getAllItems($_SESSION['last_selected_category']);
            foreach ($items as $item) { ?>
                <li><?= htmlspecialchars($item['name']) ?> - <?= htmlspecialchars($item['price']) ?></li>
            <?php } ?>
        </ul>
    <?php else: ?>
        <h2> Please select a category </h2>
    <?php endif; ?>

<?php
} else {
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

    <?php if (isset($_SESSION['last_selected_category'])): ?>
        <h2> The current category is <?= htmlspecialchars($_SESSION['last_selected_category']) ?></h2>
        <h3> Products in this category </h3>

        <form method ="post">
            <ul>
                <?php
                $items = getAllItems($_SESSION['last_selected_category']);
                foreach ($items as $item) {
                    $productName = htmlspecialchars($item['name']);
                    $productPrice = htmlspecialchars($item['price']);
                    ?>
                    <li><?php echo $productName ?> - <?php echo $productPrice ?></li>
                    <input type="number" name="quantity[<?= $productName ?>]" min ="0">
                </li>
                <?php
                }
                ?>
            </ul>
            <button type ="submit" name="add_to_cart">Add to cart </button>
        </form>
    <?php endif; ?>
<?php }
?>
