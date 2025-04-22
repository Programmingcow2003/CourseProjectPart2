<?php
require "common.php";
session_start();

if( isset($_POST["login"]) ) {

    if( authenticateEmployee($_POST["username"], $_POST["password"]) == 1 ) {

        $_SESSION["username"] = $_POST["username"];
        $pdo = connectDB();
        $stmt = $pdo->prepare("SELECT employee_id FROM Employee WHERE username = :username");
        $stmt->bindParam(':username', $_POST['username']);
        $stmt->execute();
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        $_SESSION['employee_id'] = $employee['employee_id'];

        if( checkPasswordValidity( $_POST["username"], $_POST["password"] ) == 1 ) {

            //redirect to the password changer if this is the first time an employee is logging in
            header("LOCATION:passwordReset.php");
            return;

        } else {
            
            //redirecting to the main employee page if this is not their first time logging in
            header("LOCATION:employeeMain.php");
            return;

        }


    }else {
        echo '<p style="color:red;">incorrect username and password</p>';
    }

}
?>
<html>
<body>
    <form method ="post" action="employee_login.php">
        <label>
            Username:
            <input type="text" name="username"><br></input>
        </label>
        <label>
            Password:   
            <input type="text" name="password"></input>
        </label>
        <br><input type="submit" name = "login" value="login">
    </form>

    <form method="post" action="customer_login.php">
        <input type="submit" value = "Customers please login here">
    </form>
</body>
</html>