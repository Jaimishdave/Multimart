<?php
session_name("customer_session");
session_start();

// Destroy all session data
$_SESSION = [];
session_unset();
session_destroy();

// Optional: Clear session cookie if it exists
if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Redirect to login page or home
header("Location: ../index.php"); // or you can use customer_index.php
exit();
