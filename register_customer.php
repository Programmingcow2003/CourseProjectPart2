<?php
require 'common.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])){
    registerCustomer(
        $_POST['username'],
        $_POST['password'],
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['email'],
        $_POST['shipping_address']
    );
    header("LOCATION: customer_login.php");
}
?>

<html>
<h1> Register new customer </h2>

<form method="POST">
    <label>Enter Username:<br>
        <input type="text" name="username">
    </label><br>

    <label> Enter Password:<br>
        <input type="text" name="password">
    </label><br>

    <label> Enter First Name:<br>
        <input type="text" name="first_name">
    </label><br>

    <label> Enter Last Name:<br>
        <input type="text" name="last_name">
    </label><br>

    <label> Enter Email:<br>
        <input type="text" name="email">
    </label><br>

    <label> Enter Address:<br>
        <input type="text" name="shipping_address">
    </label><br>

    <input type = "submit" name="register" value="Register">
    <input type = "reset" value ="Cancel">
</form>
</html>