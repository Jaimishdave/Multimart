<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multimart</title>
    <link rel="stylesheet" href="/Multimart/assets/css/styles.css">
</head>
<body>
    <header>
        <h1>Multimart</h1>
        <nav>
            <ul class="nav-links">
                <li><a href="/Multimart/index.php">Home</a></li>
              
                <li><a href="/Multimart/customer/login.php">Customer Login</a></li>
                <!-- <li><a href="/Multimart/customer/register.php">Customer Register</a></li> -->
                <li><a href="/Multimart/admin/admin_login.php">Admin Login</a></li>
                <li><a href="/Multimart/delivery/delivery_login.php">Delivery Login</a></li>
            </ul>
        </nav>
    </header>
    <main>
