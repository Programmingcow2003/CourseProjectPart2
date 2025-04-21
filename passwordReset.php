<?php
session_start();

if( isset($_POST["change"]) ) {

    if( isset( $_POST["employee_id"] ) ) {

        if( $_POST["password1"] == $_POST["password2"] ) {
            
            $pdo = connectDB();
            $stmt = $pdo->prepare("UPDATE password FROM Employee WHERE employee_id = :employee_id");
            $stmt->bindParam(':employee_id', $_POST['employee_id']);
            $stmt->execute();
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);
        
            echo '<p>Your password has been updated. </p>';


            header("LOCATION:employee_login.php");
            return;

        } else {
            echo '<p style="color:red;">Your input passwords do not match.</p>';
        }

    } if( isset( $_POST["customer_id"] ) ) {

        if( $_POST["password1"] == $_POST["password2"] ) {
            
            $pdo = connectDB();
            $stmt = $pdo->prepare("UPDATE password FROM Customer WHERE customer_id = :customer_id");
            $stmt->bindParam(':customer_id', $_POST['customer_id']);
            $stmt->execute();
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);
        
            echo '<p>Your password has been updated. </p>';


            header("LOCATION:customer_login.php");
            return;

        } else {
            echo '<p style="color:red;">Your input passwords do not match.</p>';
        }
    }
}

?>

<html>
<body>
    <form method ="post" action="passwordReset.php">
        <label>
            New Password:
            <input type="text" name="password1"><br></input>
        </label>
        <label>
            Confirm your new Password:   
            <input type="text" name="password2"></input>
        </label>
        <br><input type="submit" name = "change" value="change">
    </form>
</body>
</html>