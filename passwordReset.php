<?php
session_start();
require 'db.php';

if (isset($_POST["change"])) {

    if (isset($_POST["employee_id"])) {

        if ($_POST["password1"] == $_POST["password2"]) {
            $pdo = connectDB();

            $stmt = $pdo->prepare("UPDATE Employee SET password = SHA2(:password, 256) WHERE employee_id = :employee_id");
            $stmt->bindParam(':password', $_POST['password1']);
            $stmt->bindParam(':employee_id', $_POST['employee_id']);
            $stmt->execute();

            $pdo = connectDB();
            $stmt = $pdo->prepare("UPDATE Employee SET password_updated = 1 WHERE employee_id = :employee_id");
            $stmt->bindParam(':employee_id', $_POST['employee_id']);
            $stmt->execute();

            echo '<p>Your password has been updated. </p>';
            header("LOCATION:employee_login.php");
            return;

        } else {
            echo '<p style="color:red;">Your input passwords do not match.</p>';
        }
    }
}
?>

<html>
<body>
    <form method="post" action="passwordReset.php">
        <label>
            New Password:
            <input type="text" name="password1"><br>
        </label>
        <label>
            Confirm your new Password:
            <input type="text" name="password2">
        </label>
        <br>
        <input type="hidden" name="employee_id" value="<?= htmlspecialchars($_SESSION['employee_id'] ?? '') ?>">
        <br><input type="submit" name="change" value="change">
    </form>
</body>
</html>
