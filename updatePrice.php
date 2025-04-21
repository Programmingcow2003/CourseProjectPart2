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
    <h3> Welcome to the price updating page </h3><br>
    <p>Enter the product id, and new price below</p><br>

    <form method = "post" action = "updatePrice.php">
        Product id:
        <input type="text" name="product_id"></input><br>
        Change in Stock:
        <input type="text" name="new_price"></input><br>
        <input type="submit" name="update" value="update"></input><br>
    </form>
    
    <?php 
        if( isset($_POST["submit"]) ) {
            if( updatePrice( $_POST["product_id"],$_POST["new_price"], $_SESSION["employee_id"], "employee" ) ) {
                echo '<p style="color:red;">The input price change is invalid (likely the price is 0 or negative).</p>';
            } else {
                echo "<p>The price has been changed.</p>";
            }
        }
    ?>

    <form method="post" action="updatePrice.php">
        <input type="submit" name="Back" value="Back">Go Back</input><br>
    </form>

    <?php
        if( isset($_POST["Back"]) ) {
            header("LOCATION:employeeMain.php");
            return;
        }
    ?>
</html>