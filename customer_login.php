<?php
require "common.php";
session_start();
if (isset($_POST["login"])) {
    if (authenticate2($_POST["username"], $_POST["password"]) ==1){
        $_SESSION["username"] = $_POST["username"];
        $pdo = connectDB();
        $stmt = $pdo->prepare("SELECT customer_id FROM Customer WHERE username = :username");
        $stmt->bindParam(':username', $_POST['username']);
        $stmt->execute();
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        $_SESSION['customer_id'] = $customer['customer_id'];
        header("LOCATION:shopMain.php");
        return;
    } else {
        echo '<p style="color:red;">incorrect username and password</p>';
    }
} else if (isset($_POST["new_account"])) {
    header("LOCATION:register_customer.php");
    return;
}
?>
<html>
<body>
    <form method ="post" action="customer_login.php">
        <label>
            Username:
            <input type="text" name="username"><br></input>
        </label>
        <label>
            Password:   
            <input type="text" name="password"></input>
        </label>
        <br><input type="submit" name = "login" value="login">
        <input type="submit" name = "new account" value = "new_account">
    </form>

    <form method="post" action="browse_products.php">
        <input type="submit" value = "Browse products">
    </form>

    <form method="post" action="employee_login.php">
        <input type="submit" value = "Employees please login here">
    </form>
</body>
</html>


