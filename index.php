<?php
session_start(); // No session_name here — keep it simple
// ❌ No redirect based on session
include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Multimart</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to Multimart</h1>
        <p>Your one-stop shop for phones, accessories, and furniture.</p>
        
        <form method="POST" action="">
            <label><input type="radio" name="user_role" value="admin" required> Admin</label>
            <label><input type="radio" name="user_role" value="customer" required> Customer</label>
            <label><input type="radio" name="user_role" value="delivery" required> Delivery Person</label>
            <br><br>
            <button type="submit">Continue</button>
        </form>
    </div>
</body>
</html>

<?php
include 'includes/footer.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_role = $_POST['user_role'];
    switch ($user_role) {
        case 'admin':
            header('Location: admin/admin_login.php');
            break;
        case 'customer':
            header('Location: customer/login.php');
            break;
        case 'delivery':
            header('Location: delivery/delivery_login.php');
            break;
    }
    exit();
}
?>
