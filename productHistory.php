<?php
session_start();
require "common.php";

if (!isset($_SESSION["username"])) {
    header("LOCATION:employee_login.php");
} else {
    echo '<p align="right"> Welcome ' . $_SESSION["username"] . '</p>';
}

?>

<!DOCTYPE html>
<html>

    Enter a product id to see it's stock history: 
    <form method="post" action="productHistory.php">
        <input type="text" name="stock_id"></input><br>
        <input type="submit" value="getStock" name="stockHistory"></input><br>
    </form>

    <style>
    table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
    }
</style>
    <?php 
    if( isset($_POST["stock_id"]) ) {
        $stockHistory = get_stock_history( $_POST["stock_id"] );
        ?>
        <table>
        <tr>
        <th>Timestamp</th>
        <th>Old Price</th>
        <th>New Price</th>
        <th>Percentage change</th>
        </tr>

        <?php
        foreach ($stockHistory as $row) {
        echo "<tr>";
        echo "<td>" . $row[0] . "</td>";
        echo "<td>" . $row[1] . "</td>";
        echo "<td>" . $row[2] . "</td>";
        echo "<td>" . $row[2] - $row[1] . "</td>";
        echo "</tr>";
        }
        echo "<table>";
    }
    ?>


    Enter a product id to see it's price history: 
    <form method="post" action="productHistory.php">
        <input type="text" name="price_id"></input><br>
        <input type="submit" value="getPrice" name="priceHistory"></input><br>
    </form>

    <style>
    table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
    }
    </style>
    <?php 
    if( isset($_POST["price_id"]) ) {
        $priceHistory = get_stock_history( $_POST["price_id"] );
        ?>
        <table>
        <tr>
        <th>Timestamp</th>
        <th>Old Stock</th>
        <th>New Stock</th>
        <th>Change in Stock</th>
        </tr>

        <?php
        foreach ($priceHistory as $row) {
        echo "<tr>";
        echo "<td>" . $row[0] . "</td>";
        echo "<td>" . $row[1] . "</td>";
        echo "<td>" . $row[2] . "</td>";
        echo "<td>" . $row[2] - $row[1] / $row[1] . "</td>";
        echo "</tr>";
        }
        echo "<table>";
    }
    ?>
</html>