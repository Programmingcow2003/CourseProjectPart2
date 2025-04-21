<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("LOCATION:employee_login.php");
} else {
    echo '<p align="right"> Welcome ' . $_SESSION["username"] . '</p>';
}
?>

<!DOCTYPE html>
<html>
    <h3> Welcome to the stock updating page </h3><br>
    <p>Enter the product id, and change in stock below</p><br>

    <form method = "post" action = "updateStock.php">
        Product id:
        <input type="text" name="product_id"></input><br>
        Change in Stock:
        <input type="text" name="stock_change"></input><br>
        <input type="submit" name="update" value="update"></input><br>
    </form>
    
    <?php 
        if( isset($_POST["submit"]) ) {
            if( updateStock( $_POST["product_id"],$_POST["stock_change"], $_SESSION["employee_id"], "employee" ) ) {
                echo '<p style="color:red;">The input stock change is invalid (likely more stock was attempted to be removed than existed).</p>';
            } else {
                echo "<p>The stock has been changed.</p>";
            }
        }
    ?>

    <form method="post" action="updateStock.php">
        <input type="submit" name="Back" value="Back">Go Back</input><br>
    </form>

    <?php
        if( isset($_POST["Back"]) ) {
            header("LOCATION:employeeMain.php");
            return;
        }
    ?>
</html>