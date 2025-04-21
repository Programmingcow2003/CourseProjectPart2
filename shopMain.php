<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("LOCATION:customer_login.php");
} else {
    echo '<p align="right"> Welcome ' . $_SESSION["username"] . '</p>';
}
?>

<form method = "post" action = "shopMain.php">
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

<form method = "post" action = "changepassword.php">
<p align="right">
<input type="submit" value="change password">
</p>
</form>


<!DOCTYPE html>
    <html>
        <head>
            <p style="font-size: 50px;"> Welcome to the online store</p>
        </head>

    <form method="post" action="browse_products.php">
    <input type="submit" value = "Browse products" style="font-size: 40px;">
    </form>

    <form method="post" action="shoppingCart.php">
    <input type="submit" value = "Shopping Cart" style="font-size: 40px;">
    </form>

    <form method="post" action="viewOrderInfromation.php">
    <input type="submit" value = "Check past orders" style="font-size: 40px;">
    </form>

