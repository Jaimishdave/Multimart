<?php
session_name("admin_session");
session_start();

// Destroy all session data
$_SESSION = [];
session_unset();
session_destroy();

// Optional: Clear session cookie if it exists
if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Redirect to index.php (login/role selection page)
header("Location: ../index.php");
exit();
