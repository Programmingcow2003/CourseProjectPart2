<!DOCTYPE html>

<form method="post" action="shopMain.php">
    <p align="right">
    <input type="submit" value = "Return to main">
    </form> 

</html>

<?php
require "common.php";
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION["username"])) {
    echo "<p style='color:red;'>You must be logged in to change your password.</p>";
    exit;
}

// When the form is submitted
if (isset($_POST["submit"])) {
    $username = $_SESSION["username"];
    $old_password = $_POST["old_password"];
    $new_password = $_POST["password"];

    // Verify old password
    if (authenticate2($username, $old_password) == 1) {
        try {
            $pdo = connectDB();
            $stmt = $pdo->prepare("UPDATE Customer SET password = SHA2(:new_password, 256) WHERE username = :username");
            $stmt->bindParam(':new_password', $new_password);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            echo "<p> Password changed successfully</p>";
        } catch (PDOException $e) {
        }
    } else {
        echo "<p style='color:red;'>Old password is incorrect.</p>";
    }
}
?>

<html>
<body>
    <form method="post" action="changepassword.php">
        <label>
            Old password:
            <input type="text" name="old_password"><br>
        </label>

        <label>
            New password:   
            <input type="text" name="password">
        </label>

        <br><input type="submit" name="submit" value="submit">
    </form>
</body>
</html>
