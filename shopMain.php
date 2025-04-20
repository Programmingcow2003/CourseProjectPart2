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

<?php
if (isset($_POST["logout"])) {
    header("LOCATION: customer_login.php");
    session_destroy();
    }
?>    

<!DOCTYPE html>
    <html>
        <head>
            <p style="font-size: 50px;"> Welcome to the online store</p>
        </head>
