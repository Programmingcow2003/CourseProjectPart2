<?php
require_once 'db.php';
require_once 'common.php'; // or wherever you defined registerCustomer()

// Hardcoded test values
$username = 'testuser123';
$password = 'mytestpass';
$first_name = 'Test';
$last_name = 'User';
$email = 'testuser123@example.com';
$shipping_address = '123 Main Street, Testville';

registerCustomer($username, $password, $first_name, $last_name, $email, $shipping_address);

echo "Test registration complete!";
?>
