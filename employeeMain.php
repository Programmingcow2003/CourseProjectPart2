<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("LOCATION:employee_login.php");
} else {
    echo '<p align="right"> Welcome ' . $_SESSION["username"] . '</p>';
}
?>

<form method = "post" action = "employeeMain.php">
<p align="right">
<input type="submit" value="logout" name="logout">
</p>

<?php
if (isset($_POST["logout"])) {
    header("LOCATION: employee_login.php");
    session_destroy();
    }
?>    

<!DOCTYPE html>
    <html>
        <head>
            <p style="font-size: 50px;"> Welcome to the online store</p>
        </head>
        <br>

        <form method = "post" action = "employeeMain.php">
            Update product's stock: 
            <input type="submit" value="Restock" name="operations">Restock</input><br>
            Update product's price
            <input type="submit" value="updatePrice" name="operations">Update Price</input><br>
            View product stock and price history:
            <input type="submit" value="History" name="operations">View history</input><br>
    </form>



    </html>
        
    <?php 
    
    if( isset( $_POST["operations"]) ) {
        if( $_POST["operations"] == "Restock" ) {
            header("LOCATION: updateStock.php"); 
            return;
        }else if( $_POST["operations"] == "updatePrice" ) {
            header("LOCATION: updatePrice.php"); 
            return;
        }else if( $_POST["operations"] == "History" ) {
            header("LOCATION: productHistory.php"); 
            return;
        }
    }
    ?>
